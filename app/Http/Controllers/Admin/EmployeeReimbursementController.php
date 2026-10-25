<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeReimbursement;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeReimbursementController extends Controller
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
        $selectedCategory = $request->query('category', 'all');
        $search = $request->query('search', '');

        $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth);
        $startOfMonth = $monthDate->copy()->startOfMonth()->toDateString();
        $endOfMonth = $monthDate->copy()->endOfMonth()->toDateString();

        $query = EmployeeReimbursement::with(['user.candidateProfile', 'approver', 'company'])
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth]);

        if (!$isSuperAdmin && $companyId) {
            $query->where('company_profile_id', $companyId);
        }

        if ($selectedStatus !== 'all') {
            $query->where('status', $selectedStatus);
        }

        if ($selectedCategory !== 'all') {
            $query->where('category', $selectedCategory);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('claim_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $claims = $query->latest('transaction_date')->paginate(15)->withQueryString();

        // Metrics for summary cards
        $metricsQuery = EmployeeReimbursement::whereBetween('transaction_date', [$startOfMonth, $endOfMonth]);
        if (!$isSuperAdmin && $companyId) {
            $metricsQuery->where('company_profile_id', $companyId);
        }

        $totalClaims = (clone $metricsQuery)->count();
        $pendingCount = (clone $metricsQuery)->where('status', 'pending')->count();
        $approvedAmount = (int) (clone $metricsQuery)->where('status', 'approved')->sum('amount');
        $paidAmount = (int) (clone $metricsQuery)->where('status', 'paid')->sum('amount');

        return view('admin.employee_reimbursements.index', compact(
            'claims',
            'selectedMonth',
            'selectedStatus',
            'selectedCategory',
            'search',
            'monthDate',
            'totalClaims',
            'pendingCount',
            'approvedAmount',
            'paidAmount'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,paid',
            'admin_notes' => 'nullable|string|max:500',
            'disbursement_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $companyProfile = $user->currentCompanyProfile();
        $companyId = $companyProfile ? $companyProfile->id : null;

        $query = EmployeeReimbursement::query();
        if (!$isSuperAdmin && $companyId) {
            $query->where('company_profile_id', $companyId);
        }

        $claim = $query->findOrFail($id);

        $updateData = [
            'status' => $validated['status'],
            'approved_by' => $user->id,
            'approved_at' => Carbon::now(),
            'admin_notes' => $validated['admin_notes'],
        ];

        if ($validated['status'] === 'paid') {
            $updateData['paid_at'] = Carbon::now();
            if ($request->hasFile('disbursement_proof')) {
                $updateData['disbursement_proof_path'] = $request->file('disbursement_proof')->store('reimbursement_proofs', 'public');
            }
        }

        $claim->update($updateData);

        AuditLog::record(
            'EMPLOYEE_REIMBURSEMENT_STATUS_UPDATE',
            "Admin {$user->name} memperbarui status klaim #{$claim->claim_number} ({$claim->user->name}) menjadi: {$validated['status']}",
            $user
        );

        $statusText = match($validated['status']) {
            'approved' => 'disetujui (siap dibayar)',
            'paid' => 'berhasil ditandai telah dicairkan / ditransfer',
            default => 'ditolak',
        };

        return redirect()->back()->with('success', "Klaim reimbursement #{$claim->claim_number} {$statusText}.");
    }
}
