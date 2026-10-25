<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeReimbursement;
use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeReimbursementController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isIntern()) {
            return redirect()->route('candidate.logbook.index')
                ->with('info', 'Modul klaim reimbursement dikhususkan untuk Karyawan Tetap/Kontrak.');
        }

        $selectedMonth = $request->query('month', Carbon::now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = Carbon::now()->format('Y-m');
        }

        $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth);
        $startOfMonth = $monthDate->copy()->startOfMonth()->toDateString();
        $endOfMonth = $monthDate->copy()->endOfMonth()->toDateString();

        $claims = EmployeeReimbursement::where('user_id', $user->id)
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->latest('transaction_date')
            ->get();

        $totalApproved = (int) $claims->whereIn('status', ['approved', 'paid'])->sum('amount');
        $totalPaid = (int) $claims->where('status', 'paid')->sum('amount');
        $pendingCount = $claims->where('status', 'pending')->count();
        $approvedCount = $claims->where('status', 'approved')->count();

        // Prefill default bank account from onboarding or previous claims
        $defaultBank = $user->candidateOnboarding;
        $lastClaim = EmployeeReimbursement::where('user_id', $user->id)->latest()->first();

        $bankPrefill = [
            'bank_name' => $lastClaim?->bank_name ?? $defaultBank?->bank_name ?? 'BCA',
            'bank_account_number' => $lastClaim?->bank_account_number ?? $defaultBank?->bank_account_number ?? '',
            'bank_account_holder' => $lastClaim?->bank_account_holder ?? $defaultBank?->bank_account_holder ?? $user->name,
        ];

        return view('employee.reimbursements.index', compact(
            'claims',
            'selectedMonth',
            'monthDate',
            'totalApproved',
            'totalPaid',
            'pendingCount',
            'approvedCount',
            'bankPrefill'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->isIntern()) {
            return redirect()->route('candidate.logbook.index')
                ->with('info', 'Modul klaim reimbursement dikhususkan untuk Karyawan Tetap/Kontrak.');
        }

        $validated = $request->validate([
            'category' => 'required|in:transport,medical,communication,meal_overtime,operational,other',
            'title' => 'required|string|max:200',
            'transaction_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|integer|min:1000|max:100000000',
            'description' => 'required|string|min:10|max:1000',
            'receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bank_name' => 'required|string|max:50',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_holder' => 'required|string|max:150',
        ]);

        $receiptPath = $request->file('receipt')->store('reimbursement_receipts', 'public');

        // Resolve active company
        $companyProfile = $user->currentCompanyProfile();
        if (!$companyProfile) {
            $activeApp = Application::with('job.companyProfile')
                ->where('user_id', $user->id)
                ->whereIn('status', ['accepted', 'hired'])
                ->latest()
                ->first();
            $companyProfile = $activeApp?->job?->companyProfile ?? CompanyProfile::first();
        }

        // Generate claim number (e.g. RMB-202610-1234)
        $claimNumber = 'RMB-' . Carbon::parse($validated['transaction_date'])->format('Ym') . '-' . strtoupper(Str::random(4));

        $claim = EmployeeReimbursement::create([
            'user_id' => $user->id,
            'company_profile_id' => $companyProfile?->id,
            'claim_number' => $claimNumber,
            'category' => $validated['category'],
            'title' => $validated['title'],
            'transaction_date' => $validated['transaction_date'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'receipt_path' => $receiptPath,
            'bank_name' => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_holder' => $validated['bank_account_holder'],
            'status' => 'pending',
        ]);

        AuditLog::record(
            'EMPLOYEE_REIMBURSEMENT_CLAIM',
            "Karyawan {$user->name} mengajukan klaim reimbursement #{$claimNumber} sebesar Rp " . number_format($validated['amount'], 0, ',', '.'),
            $user
        );

        return redirect()->route('employee.reimbursements.index')
            ->with('success', 'Pengajuan klaim reimbursement berhasil dikirim dengan Nomor Tiket ' . $claimNumber . '. Menunggu verifikasi tim Finance/HR.');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $claim = EmployeeReimbursement::where('user_id', $user->id)->findOrFail($id);

        if ($claim->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pengajuan dengan status "Menunggu Review" yang dapat dibatalkan.');
        }

        if ($claim->receipt_path) {
            Storage::disk('public')->delete($claim->receipt_path);
        }

        $claim->delete();

        return redirect()->route('employee.reimbursements.index')
            ->with('success', 'Pengajuan klaim reimbursement berhasil dibatalkan.');
    }
}
