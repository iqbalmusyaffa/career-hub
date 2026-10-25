<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeOvertime;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeOvertimeController extends Controller
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
        $startOfMonth = $monthDate->copy()->startOfMonth()->toDateString();
        $endOfMonth = $monthDate->copy()->endOfMonth()->toDateString();

        $query = EmployeeOvertime::with(['user.candidateProfile', 'approver', 'company'])
            ->whereBetween('date', [$startOfMonth, $endOfMonth]);

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

        $overtimes = $query->latest('date')->paginate(15)->withQueryString();

        // Metrics for summary cards
        $metricsQuery = EmployeeOvertime::whereBetween('date', [$startOfMonth, $endOfMonth]);
        if (!$isSuperAdmin && $companyId) {
            $metricsQuery->where('company_profile_id', $companyId);
        }

        $totalRequests = (clone $metricsQuery)->count();
        $pendingCount = (clone $metricsQuery)->where('status', 'pending')->count();
        $approvedHours = (float) (clone $metricsQuery)->where('status', 'approved')->sum('duration_hours');
        $approvedCompensation = (int) (clone $metricsQuery)->where('status', 'approved')->sum('total_compensation');

        return view('admin.employee_overtimes.index', compact(
            'overtimes',
            'selectedMonth',
            'selectedStatus',
            'search',
            'monthDate',
            'totalRequests',
            'pendingCount',
            'approvedHours',
            'approvedCompensation'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:500',
            'total_compensation' => 'nullable|integer|min:0',
        ]);

        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $companyProfile = $user->currentCompanyProfile();
        $companyId = $companyProfile ? $companyProfile->id : null;

        $query = EmployeeOvertime::query();
        if (!$isSuperAdmin && $companyId) {
            $query->where('company_profile_id', $companyId);
        }

        $overtime = $query->findOrFail($id);

        $updateData = [
            'status' => $validated['status'],
            'approved_by' => $user->id,
            'approved_at' => Carbon::now(),
            'admin_notes' => $validated['admin_notes'],
        ];

        if (isset($validated['total_compensation']) && $validated['total_compensation'] > 0) {
            $updateData['total_compensation'] = $validated['total_compensation'];
        }

        $overtime->update($updateData);

        AuditLog::record(
            'EMPLOYEE_OVERTIME_STATUS_UPDATE',
            "Admin {$user->name} memperbarui status lembur #{$overtime->id} ({$overtime->user->name}) menjadi: {$validated['status']}",
            $user
        );

        $statusText = $validated['status'] === 'approved' ? 'disetujui' : 'ditolak';
        return redirect()->back()->with('success', "Pengajuan lembur karyawan {$overtime->user->name} berhasil {$statusText}.");
    }
}
