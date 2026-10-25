<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeOvertime;
use App\Models\EmployeeReimbursement;
use App\Models\EmployeePayroll;
use App\Models\Application;
use App\Models\ApplicationAgreement;
use App\Models\User;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AdminEmployeePayrollApiController extends Controller
{
    // ==========================================
    // 1. MONITORING PRESENSI KARYAWAN (HR)
    // ==========================================

    #[OA\Get(
        path: "/admin/employee-attendances",
        summary: "[HR] Monitoring Rekapitulasi Presensi Karyawan",
        description: "Mengambil daftar presensi harian seluruh karyawan tetap/kontrak dengan filter tanggal/status.",
        security: [["bearerAuth" => []]],
        tags: ["HR Employee Management & Payroll"],
        responses: [
            new OA\Response(response: 200, description: "Data presensi berhasil dimuat.")
        ]
    )]
    public function attendancesIndex(Request $request): JsonResponse
    {
        $date = $request->query('date', Carbon::today()->toDateString());
        $status = $request->query('status');

        $query = EmployeeAttendance::with(['user:id,name,email,avatar'])
            ->where('date', $date);

        if ($status && $status !== 'all') {
            $query->where('check_in_status', $status);
        }

        $attendances = $query->latest('check_in_time')->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Data presensi karyawan berhasil diambil.',
            'data' => [
                'date' => $date,
                'total_present' => EmployeeAttendance::where('date', $date)->count(),
                'on_time_count' => EmployeeAttendance::where('date', $date)->where('check_in_status', 'on_time')->count(),
                'late_count' => EmployeeAttendance::where('date', $date)->where('check_in_status', 'late')->count(),
                'attendances' => $attendances,
            ]
        ]);
    }

    // ==========================================
    // 2. PERSETUJUAN LEMBUR KARYAWAN (HR)
    // ==========================================

    #[OA\Get(
        path: "/admin/employee-overtimes",
        summary: "[HR] Daftar Pengajuan Lembur Karyawan",
        description: "Mengambil daftar pengajuan lembur karyawan dengan filter bulan dan status persetujuan.",
        security: [["bearerAuth" => []]],
        tags: ["HR Employee Management & Payroll"],
        responses: [
            new OA\Response(response: 200, description: "Daftar lembur berhasil dimuat.")
        ]
    )]
    public function overtimesIndex(Request $request): JsonResponse
    {
        $month = $request->query('month', Carbon::now()->format('Y-m'));
        $monthDate = Carbon::createFromFormat('Y-m', $month);
        $status = $request->query('status');

        $query = EmployeeOvertime::with(['user:id,name,email', 'approver:id,name'])
            ->whereBetween('date', [$monthDate->copy()->startOfMonth()->toDateString(), $monthDate->copy()->endOfMonth()->toDateString()]);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $overtimes = $query->latest('date')->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengajuan lembur berhasil diambil.',
            'data' => [
                'total_requests' => $overtimes->total(),
                'pending_count' => EmployeeOvertime::where('status', 'pending')->count(),
                'approved_hours' => (float) EmployeeOvertime::where('status', 'approved')->sum('duration_hours'),
                'approved_compensation' => (int) EmployeeOvertime::where('status', 'approved')->sum('total_compensation'),
                'overtimes' => $overtimes,
            ]
        ]);
    }

    #[OA\Post(
        path: "/admin/employee-overtimes/{id}/status",
        summary: "[HR] Verifikasi & Update Status Lembur Karyawan",
        description: "Menyetujui (Approved) atau menolak (Rejected) permohonan upah lembur karyawan.",
        security: [["bearerAuth" => []]],
        tags: ["HR Employee Management & Payroll"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Status lembur berhasil diperbarui."),
            new OA\Response(response: 422, description: "Validasi gagal")
        ]
    )]
    public function updateOvertimeStatus(Request $request, int $id): JsonResponse
    {
        $overtime = EmployeeOvertime::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:500',
            'total_compensation' => 'nullable|integer|min:0',
        ]);

        $updateData = [
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ];

        if (isset($validated['total_compensation']) && $validated['total_compensation'] > 0) {
            $updateData['total_compensation'] = $validated['total_compensation'];
        }

        $overtime->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Status pengajuan lembur berhasil diperbarui menjadi ' . ucfirst($validated['status']),
            'data' => $overtime,
        ]);
    }

    // ==========================================
    // 3. KLAIM REIMBURSEMENT OPERASIONAL (HR)
    // ==========================================

    #[OA\Get(
        path: "/admin/employee-reimbursements",
        summary: "[HR] Daftar Klaim Reimbursement Karyawan",
        description: "Mengambil daftar pengajuan klaim penggantian biaya operasional kantor.",
        security: [["bearerAuth" => []]],
        tags: ["HR Employee Management & Payroll"],
        responses: [
            new OA\Response(response: 200, description: "Daftar klaim berhasil diambil.")
        ]
    )]
    public function reimbursementsIndex(Request $request): JsonResponse
    {
        $month = $request->query('month', Carbon::now()->format('Y-m'));
        $monthDate = Carbon::createFromFormat('Y-m', $month);
        $status = $request->query('status');

        $query = EmployeeReimbursement::with(['user:id,name,email', 'approver:id,name'])
            ->whereBetween('transaction_date', [$monthDate->copy()->startOfMonth()->toDateString(), $monthDate->copy()->endOfMonth()->toDateString()]);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $claims = $query->latest('transaction_date')->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Daftar klaim reimbursement berhasil diambil.',
            'data' => [
                'total_claims' => $claims->total(),
                'pending_count' => EmployeeReimbursement::where('status', 'pending')->count(),
                'approved_amount' => (int) EmployeeReimbursement::where('status', 'approved')->sum('amount'),
                'paid_amount' => (int) EmployeeReimbursement::where('status', 'paid')->sum('amount'),
                'claims' => $claims,
            ]
        ]);
    }

    #[OA\Post(
        path: "/admin/employee-reimbursements/{id}/status",
        summary: "[HR] Verifikasi & Pembayaran Klaim Reimbursement",
        description: "Menyetujui (approved), menolak (rejected), atau menandai telah ditransfer (paid).",
        security: [["bearerAuth" => []]],
        tags: ["HR Employee Management & Payroll"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Status klaim berhasil diperbarui."),
            new OA\Response(response: 422, description: "Validasi gagal")
        ]
    )]
    public function updateReimbursementStatus(Request $request, int $id): JsonResponse
    {
        $claim = EmployeeReimbursement::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,paid',
            'admin_notes' => 'nullable|string|max:500',
            'disbursement_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $updateData = [
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $claim->admin_notes,
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ];

        if ($validated['status'] === 'paid') {
            $updateData['paid_at'] = now();
            if ($request->hasFile('disbursement_proof')) {
                $updateData['disbursement_proof_path'] = $request->file('disbursement_proof')->store('reimbursement_disbursements', 'public');
            }
        }

        $claim->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Status klaim ' . $claim->claim_number . ' berhasil diperbarui menjadi ' . ucfirst($validated['status']),
            'data' => $claim,
        ]);
    }

    // ==========================================
    // 4. PAYROLL ENGINE & E-SLIP GAJI (HR)
    // ==========================================

    #[OA\Get(
        path: "/admin/employee-payrolls",
        summary: "[HR] Rekapitulasi Payroll & E-Slip Gaji Bulanan",
        description: "Mengambil daftar rekapitulasi gaji, upah lembur, potongan BPJS/PPH21 seluruh karyawan.",
        security: [["bearerAuth" => []]],
        tags: ["HR Employee Management & Payroll"],
        responses: [
            new OA\Response(response: 200, description: "Daftar payroll berhasil dimuat.")
        ]
    )]
    public function payrollsIndex(Request $request): JsonResponse
    {
        $month = $request->query('month', Carbon::now()->format('Y-m'));
        $status = $request->query('status');

        $query = EmployeePayroll::with(['user:id,name,email', 'company'])
            ->where('period_month', $month);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $payrolls = $query->latest('net_salary')->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Daftar payroll periode ' . $month . ' berhasil diambil.',
            'data' => [
                'period_month' => $month,
                'total_employees' => $payrolls->total(),
                'total_net_payout' => (int) EmployeePayroll::where('period_month', $month)->sum('net_salary'),
                'total_overtime_payout' => (int) EmployeePayroll::where('period_month', $month)->sum('overtime_pay'),
                'paid_count' => EmployeePayroll::where('period_month', $month)->where('status', 'paid')->count(),
                'draft_count' => EmployeePayroll::where('period_month', $month)->where('status', 'draft')->count(),
                'payrolls' => $payrolls,
            ]
        ]);
    }

    #[OA\Post(
        path: "/admin/employee-payrolls/generate",
        summary: "[HR] Generate Otomatis Payroll & Sinkronisasi Kehadiran",
        description: "Menghitung dan membuat slip gaji bulanan seluruh karyawan tetap/kontrak aktif secara otomatis.",
        security: [["bearerAuth" => []]],
        tags: ["HR Employee Management & Payroll"],
        responses: [
            new OA\Response(response: 200, description: "Payroll berhasil di-generate."),
            new OA\Response(response: 422, description: "Format periode salah")
        ]
    )]
    public function generatePayroll(Request $request): JsonResponse
    {
        $periodMonth = $request->input('period_month', Carbon::now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $periodMonth)) {
            $periodMonth = Carbon::now()->format('Y-m');
        }

        $monthDate = Carbon::createFromFormat('Y-m', $periodMonth);
        $startOfMonth = $monthDate->copy()->startOfMonth()->toDateString();
        $endOfMonth = $monthDate->copy()->endOfMonth()->toDateString();
        $periodLabel = $monthDate->translatedFormat('F Y');

        $employees = User::role('Employee')
            ->where(function($q) {
                $q->whereDoesntHave('internshipPeriod')
                  ->orWhereHas('internshipPeriod', function($p) {
                      $p->where('end_date', '<', now()->toDateString());
                  });
            })
            ->get();

        if ($employees->isEmpty()) {
            $employees = User::whereHas('employeeAttendances', function($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('date', [$startOfMonth, $endOfMonth]);
            })->get();
        }

        $count = 0;
        foreach ($employees as $emp) {
            $baseSalary = 5750000;
            $agreement = ApplicationAgreement::where('user_id', $emp->id)->latest()->first();
            if ($agreement && preg_match('/[\d\.]+/', str_replace(['Rp', '.', ' '], '', $agreement->stipend_or_salary), $matches)) {
                $parsed = (int) str_replace('.', '', $matches[0]);
                if ($parsed > 500000) $baseSalary = $parsed;
            }

            $attendances = EmployeeAttendance::where('user_id', $emp->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();

            $presentDays = $attendances->count();
            $lateDays = $attendances->where('check_in_status', 'late')->count();
            $deductionLate = $lateDays * 25000;

            $approvedOvertimes = EmployeeOvertime::where('user_id', $emp->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('status', 'approved')
                ->get();
            $overtimeHours = (float) $approvedOvertimes->sum('duration_hours');
            $overtimePay = (int) $approvedOvertimes->sum('total_compensation');

            $approvedClaims = EmployeeReimbursement::where('user_id', $emp->id)
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->where('status', 'approved')
                ->get();
            $reimbursementPay = (int) $approvedClaims->sum('amount');

            $allowanceFixed = 750000;
            $allowanceTransport = max(400000, $presentDays * 25000);

            $deductionBpjsTk = (int) round($baseSalary * 0.03);
            $deductionBpjsKes = (int) round($baseSalary * 0.01);
            $deductionPph21 = (int) round(($baseSalary > 5400000) ? ($baseSalary - 5400000) * 0.05 : 0);

            $totalEarnings = $baseSalary + $allowanceFixed + $allowanceTransport + $overtimePay + $reimbursementPay;
            $totalDeductions = $deductionLate + $deductionBpjsTk + $deductionBpjsKes + $deductionPph21;
            $netSalary = max(0, $totalEarnings - $totalDeductions);

            $onboarding = $emp->candidateOnboarding;
            $bankName = $onboarding?->bank_name ?? 'BCA';
            $bankNum = $onboarding?->bank_account_number ?? '8801928371';
            $bankHolder = $onboarding?->bank_account_holder ?? $emp->name;

            EmployeePayroll::updateOrCreate(
                [
                    'user_id' => $emp->id,
                    'period_month' => $periodMonth,
                ],
                [
                    'company_profile_id' => 1,
                    'payroll_number' => 'SLIP-EMP-' . str_replace('-', '', $periodMonth) . '-' . str_pad($emp->id, 4, '0', STR_PAD_LEFT),
                    'period_label' => $periodLabel,
                    'job_title' => 'Software Engineer & Technology Staff',
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
                    'status' => 'draft',
                    'created_by' => $request->user()->id,
                ]
            );
            $count++;
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil men-generate & menyinkronkan ' . $count . ' data E-Slip Gaji Karyawan untuk periode ' . $periodLabel,
            'data' => [
                'period_month' => $periodMonth,
                'generated_count' => $count,
            ]
        ]);
    }

    #[OA\Post(
        path: "/admin/employee-payrolls/{id}/status",
        summary: "[HR] Update Status Slip Gaji (Draft / Published / Paid)",
        description: "Menerbitkan slip gaji ke portal karyawan (published) atau menandai sudah ditransfer (paid).",
        security: [["bearerAuth" => []]],
        tags: ["HR Employee Management & Payroll"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Status payroll berhasil diperbarui."),
            new OA\Response(response: 422, description: "Validasi gagal")
        ]
    )]
    public function updatePayrollStatus(Request $request, int $id): JsonResponse
    {
        $payroll = EmployeePayroll::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:draft,published,paid',
            'notes' => 'nullable|string|max:500',
        ]);

        $updateData = [
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $payroll->notes,
        ];

        if ($validated['status'] === 'paid') {
            $updateData['paid_at'] = now();
        }

        $payroll->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Status payroll ' . $payroll->payroll_number . ' berhasil diperbarui menjadi ' . ucfirst($validated['status']),
            'data' => $payroll,
        ]);
    }
}
