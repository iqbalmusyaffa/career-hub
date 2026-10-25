<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeePayroll;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeOvertime;
use App\Models\EmployeeReimbursement;
use App\Models\User;
use App\Models\Application;
use App\Models\ApplicationAgreement;
use App\Models\CompanyProfile;
use App\Models\AuditLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployeePayrollController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $companyProfile = $user->currentCompanyProfile();
        $companyId = $companyProfile ? $companyProfile->id : null;

        $selectedMonth = $request->query('month', Carbon::now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = Carbon::now()->format('Y-m');
        }

        $selectedStatus = $request->query('status', 'all');
        $search = $request->query('search', '');

        $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth);

        $query = EmployeePayroll::with(['user.candidateProfile', 'company', 'creator'])
            ->where('period_month', $selectedMonth);

        if (!$isSuperAdmin && $companyId) {
            $query->where('company_profile_id', $companyId);
        }

        if ($selectedStatus !== 'all') {
            $query->where('status', $selectedStatus);
        }

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $payrolls = $query->latest('net_salary')->paginate(15)->withQueryString();

        // Summary metrics
        $metricsQuery = EmployeePayroll::where('period_month', $selectedMonth);
        if (!$isSuperAdmin && $companyId) {
            $metricsQuery->where('company_profile_id', $companyId);
        }

        $totalPayrolls = (clone $metricsQuery)->count();
        $totalNetPayout = (int) (clone $metricsQuery)->sum('net_salary');
        $totalOvertimePay = (int) (clone $metricsQuery)->sum('overtime_pay');
        $paidCount = (clone $metricsQuery)->where('status', 'paid')->count();
        $draftCount = (clone $metricsQuery)->where('status', 'draft')->count();

        return view('admin.employee_payrolls.index', compact(
            'payrolls',
            'selectedMonth',
            'selectedStatus',
            'search',
            'monthDate',
            'totalPayrolls',
            'totalNetPayout',
            'totalOvertimePay',
            'paidCount',
            'draftCount'
        ));
    }

    public function generate(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $companyProfile = $user->currentCompanyProfile();
        $companyId = $companyProfile ? $companyProfile->id : null;

        $periodMonth = $request->input('period_month', Carbon::now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $periodMonth)) {
            $periodMonth = Carbon::now()->format('Y-m');
        }

        $monthDate = Carbon::createFromFormat('Y-m', $periodMonth);
        $startOfMonth = $monthDate->copy()->startOfMonth()->toDateString();
        $endOfMonth = $monthDate->copy()->endOfMonth()->toDateString();
        $periodLabel = $monthDate->translatedFormat('F Y');

        // Resolve regular employees (non-interns with role Employee or accepted regular application)
        $employees = User::role('Employee')
            ->where(function($q) {
                $q->whereDoesntHave('internshipPeriod')
                  ->orWhereHas('internshipPeriod', function($p) {
                      $p->where('end_date', '<', now()->toDateString());
                  });
            })
            ->get();

        if ($employees->isEmpty()) {
            // Fallback to all users who have employee attendance or accepted regular applications
            $employees = User::whereHas('employeeAttendances', function($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('date', [$startOfMonth, $endOfMonth]);
            })->get();
        }

        $generatedCount = 0;

        foreach ($employees as $emp) {
            // 1. Resolve Agreement / Base Salary
            $agreement = ApplicationAgreement::where('user_id', $emp->id)->latest()->first();
            $baseSalary = 5750000; // Standard baseline salary (DKI UMK standard)
            if ($agreement && preg_match('/[\d\.]+/', str_replace(['Rp', '.', ' '], '', $agreement->stipend_or_salary), $matches)) {
                $parsedNum = (int) str_replace('.', '', $matches[0]);
                if ($parsedNum > 500000) {
                    $baseSalary = $parsedNum;
                }
            }

            // 2. Resolve Job Title & Employment Type
            $activeApp = Application::with('job')
                ->where('user_id', $emp->id)
                ->whereIn('status', ['accepted', 'hired'])
                ->latest()
                ->first();

            $jobTitle = $activeApp?->job?->title ?? 'Software Engineer & Technology Staff';
            $empCompanyId = $activeApp?->job?->company_profile_id ?? $companyId ?? 1;

            // 3. Attendance Metrics from EmployeeAttendance
            $attendances = EmployeeAttendance::where('user_id', $emp->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();

            $presentDays = $attendances->count();
            $lateDays = $attendances->where('check_in_status', 'late')->count();
            $deductionLate = $lateDays * 25000; // Rp 25.000 per late occurrence

            // 4. Approved Overtime Pay
            $approvedOvertimes = EmployeeOvertime::where('user_id', $emp->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('status', 'approved')
                ->get();

            $overtimeHours = (float) $approvedOvertimes->sum('duration_hours');
            $overtimePay = (int) $approvedOvertimes->sum('total_compensation');

            // 5. Approved Reimbursements
            $approvedClaims = EmployeeReimbursement::where('user_id', $emp->id)
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->where('status', 'approved')
                ->get();

            $reimbursementPay = (int) $approvedClaims->sum('amount');

            // 6. Fixed Allowances & Deductions
            $allowanceFixed = 750000; // Tunjangan Jabatan / Posisi
            $allowanceTransport = max(400000, $presentDays * 25000); // Tunjangan Transport & Makan harian

            $deductionBpjsTk = (int) round($baseSalary * 0.03); // BPJS TK Karyawan 3%
            $deductionBpjsKes = (int) round($baseSalary * 0.01); // BPJS Kes Karyawan 1%
            $deductionPph21 = (int) round(($baseSalary > 5400000) ? ($baseSalary - 5400000) * 0.05 : 0);

            $totalEarnings = $baseSalary + $allowanceFixed + $allowanceTransport + $overtimePay + $reimbursementPay;
            $totalDeductions = $deductionLate + $deductionBpjsTk + $deductionBpjsKes + $deductionPph21;
            $netSalary = max(0, $totalEarnings - $totalDeductions);

            // 7. Bank Info
            $onboarding = $emp->candidateOnboarding;
            $bankName = $onboarding?->bank_name ?? 'BCA';
            $bankNum = $onboarding?->bank_account_number ?? '8801928371';
            $bankHolder = $onboarding?->bank_account_holder ?? $emp->name;

            $payrollNumber = 'SLIP-EMP-' . str_replace('-', '', $periodMonth) . '-' . str_pad($emp->id, 4, '0', STR_PAD_LEFT);

            EmployeePayroll::updateOrCreate(
                [
                    'user_id' => $emp->id,
                    'period_month' => $periodMonth,
                ],
                [
                    'company_profile_id' => $empCompanyId,
                    'payroll_number' => $payrollNumber,
                    'period_label' => $periodLabel,
                    'job_title' => $jobTitle,
                    'employment_type' => 'permanent',
                    'payment_date' => Carbon::parse($endOfMonth)->subDays(1),
                    'base_salary' => $baseSalary,
                    'allowance_fixed' => $allowanceFixed,
                    'allowance_transport' => $allowanceTransport,
                    'overtime_pay' => $overtimePay,
                    'reimbursement_pay' => $reimbursementPay,
                    'bonus_pay' => 0,
                    'deduction_late' => $deductionLate,
                    'deduction_unexcused' => 0,
                    'deduction_bpjs_tk' => $deductionBpjsTk,
                    'deduction_bpjs_kes' => $deductionBpjsKes,
                    'deduction_pph21' => $deductionPph21,
                    'deduction_other' => 0,
                    'total_earnings' => $totalEarnings,
                    'total_deductions' => $totalDeductions,
                    'net_salary' => $netSalary,
                    'present_days' => $presentDays,
                    'late_days' => $lateDays,
                    'absent_days' => 0,
                    'overtime_hours' => $overtimeHours,
                    'bank_name' => $bankName,
                    'bank_account_number' => $bankNum,
                    'bank_account_holder' => $bankHolder,
                    'created_by' => $user->id,
                ]
            );

            $generatedCount++;
        }

        AuditLog::record(
            'EMPLOYEE_PAYROLL_GENERATE',
            "Admin {$user->name} men-generate {$generatedCount} data payroll karyawan periode {$periodLabel}",
            $user
        );

        return redirect()->route('admin.employee-payrolls.index', ['month' => $periodMonth])
            ->with('success', "Berhasil men-generate & menyinkronkan {$generatedCount} data E-Slip Gaji Karyawan untuk periode {$periodLabel}.");
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,published,paid',
            'notes' => 'nullable|string|max:500',
        ]);

        $payroll = EmployeePayroll::findOrFail($id);
        $user = auth()->user();

        $updateData = [
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $payroll->notes,
        ];

        if ($validated['status'] === 'paid') {
            $updateData['paid_at'] = Carbon::now();
        }

        $payroll->update($updateData);

        AuditLog::record(
            'EMPLOYEE_PAYROLL_STATUS_UPDATE',
            "Admin {$user->name} mengubah status slip gaji #{$payroll->payroll_number} ({$payroll->user->name}) menjadi {$validated['status']}",
            $user
        );

        return redirect()->back()->with('success', "Status slip gaji #{$payroll->payroll_number} berhasil diperbarui.");
    }

    public function bulkTransfer(Request $request)
    {
        $periodMonth = $request->input('period_month', Carbon::now()->format('Y-m'));
        $user = auth()->user();

        $updated = EmployeePayroll::where('period_month', $periodMonth)
            ->where('status', '!=', 'paid')
            ->update([
                'status' => 'paid',
                'paid_at' => Carbon::now(),
            ]);

        AuditLog::record(
            'EMPLOYEE_PAYROLL_BULK_TRANSFER',
            "Admin {$user->name} melakukan transfer payroll massal untuk {$updated} karyawan periode {$periodMonth}",
            $user
        );

        return redirect()->back()->with('success', "Berhasil menandai {$updated} slip gaji karyawan periode {$periodMonth} telah ditransfer.");
    }

    public function downloadSlip($id)
    {
        $payroll = EmployeePayroll::with(['user.candidateProfile', 'company'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.employee_payslip', [
            'payroll' => $payroll,
            'user' => $payroll->user,
            'company' => $payroll->company,
        ])->setPaper('a4', 'portrait');

        $filename = 'Slip_Gaji_' . Str::slug($payroll->user->name) . '_' . str_replace('-', '_', $payroll->period_month) . '.pdf';

        return $pdf->download($filename);
    }

    public function exportCsv(Request $request)
    {
        $periodMonth = $request->query('month', Carbon::now()->format('Y-m'));
        $payrolls = EmployeePayroll::with('user')->where('period_month', $periodMonth)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"rekap_payroll_karyawan_{$periodMonth}.csv\"",
        ];

        $callback = function() use ($payrolls) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'No Slip', 'Nama Karyawan', 'Email', 'Jabatan', 'Gaji Pokok',
                'Tunjangan Tetap', 'Tunjangan Transport', 'Upah Lembur', 'Reimbursement',
                'Potongan Telat', 'Potongan BPJS TK', 'Potongan BPJS Kes', 'Potongan PPh 21',
                'Gaji Bersih (THP)', 'Bank', 'No Rekening', 'Status'
            ]);

            foreach ($payrolls as $p) {
                fputcsv($file, [
                    $p->payroll_number,
                    $p->user->name,
                    $p->user->email,
                    $p->job_title,
                    $p->base_salary,
                    $p->allowance_fixed,
                    $p->allowance_transport,
                    $p->overtime_pay,
                    $p->reimbursement_pay,
                    $p->deduction_late,
                    $p->deduction_bpjs_tk,
                    $p->deduction_bpjs_kes,
                    $p->deduction_pph21,
                    $p->net_salary,
                    $p->bank_name,
                    $p->bank_account_number,
                    $p->status_label,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
