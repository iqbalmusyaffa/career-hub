<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CompanyLeavePolicy;
use App\Models\CompanyProfile;
use App\Models\InternshipLogbook;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveManagementController extends Controller
{
    /**
     * Display leave requests dashboard, policy settings, and statistics.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfile = $user->currentCompanyProfile() ?? CompanyProfile::firstOrCreate(['user_id' => $user->id]);
        $policy = CompanyLeavePolicy::getForCompany($companyProfile->id);

        $query = LeaveRequest::with(['user.candidateProfile', 'approver', 'application.job'])
            ->where(function ($q) use ($companyProfile) {
                $q->where('company_id', $companyProfile->id)
                  ->orWhereNull('company_id');
            });

        // Filters
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type') && $request->leave_type !== 'all') {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $leaveRequests = $query->latest()->paginate(15)->withQueryString();

        // Summary metrics
        $today = Carbon::today()->format('Y-m-d');
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        $baseMetricsQuery = LeaveRequest::where(function ($q) use ($companyProfile) {
            $q->where('company_id', $companyProfile->id)->orWhereNull('company_id');
        });

        $pendingCount = (clone $baseMetricsQuery)->where('status', 'pending')->count();
        $approvedThisMonth = (clone $baseMetricsQuery)->where('status', 'approved')
            ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
            ->count();
        $onLeaveTodayCount = (clone $baseMetricsQuery)->where('status', 'approved')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->count();
        $totalRequests = (clone $baseMetricsQuery)->count();

        return view('admin.leaves.index', compact(
            'companyProfile',
            'policy',
            'leaveRequests',
            'pendingCount',
            'approvedThisMonth',
            'onLeaveTodayCount',
            'totalRequests'
        ));
    }

    /**
     * Approve a leave request.
     */
    public function approve(Request $request, LeaveRequest $leave)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $approver = Auth::user();

        $leave->update([
            'status' => 'approved',
            'approver_id' => $approver->id,
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        // Sync with internship logbook if user is intern
        if ($leave->user && $leave->user->isIntern()) {
            $period = CarbonPeriod::create($leave->start_date, $leave->end_date);
            foreach ($period as $date) {
                if (!$date->isWeekend()) {
                    InternshipLogbook::updateOrCreate(
                        [
                            'user_id' => $leave->user_id,
                            'date' => $date->format('Y-m-d'),
                        ],
                        [
                            'company_id' => $leave->company_id,
                            'application_id' => $leave->application_id,
                            'attendance_type' => 'Tidak Hadir Dengan Keterangan',
                            'activity_title' => 'Izin Resmi Disetujui: ' . $leave->leave_type_label,
                            'activity_description' => 'Izin resmi telah diverifikasi oleh HRD/Manajemen. Alasan: ' . $leave->reason,
                            'work_hours' => 0,
                            'status' => 'approved',
                            'approved_at' => now(),
                            'mentor_id' => $approver->id,
                        ]
                    );
                }
            }
        }

        AuditLog::record(
            'approve_leave',
            "Menyetujui permohonan {$leave->leave_type_label} untuk {$leave->user->name} ({$leave->total_days} hari)."
        );

        return redirect()->back()->with('success', "Permohonan cuti/izin untuk {$leave->user->name} berhasil disetujui!");
    }

    /**
     * Reject a leave request.
     */
    public function reject(Request $request, LeaveRequest $leave)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ], [
            'admin_notes.required' => 'Wajib memberikan alasan atau catatan penolakan permohonan cuti.',
        ]);

        $approver = Auth::user();

        $leave->update([
            'status' => 'rejected',
            'approver_id' => $approver->id,
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        AuditLog::record(
            'reject_leave',
            "Menolak permohonan {$leave->leave_type_label} untuk {$leave->user->name}. Alasan: {$request->admin_notes}"
        );

        return redirect()->back()->with('success', "Permohonan cuti/izin untuk {$leave->user->name} telah ditolak dengan catatan.");
    }

    /**
     * Update company leave policy settings.
     */
    public function updatePolicy(Request $request)
    {
        $request->validate([
            'annual_leave_quota' => 'required|integer|min:1|max:365',
            'permanent_leave_quota' => 'required|integer|min:1|max:365',
            'internship_max_excused_days' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $companyProfile = $user->currentCompanyProfile() ?? CompanyProfile::firstOrCreate(['user_id' => $user->id]);
        $policy = CompanyLeavePolicy::getForCompany($companyProfile->id);

        $policy->update([
            'annual_leave_quota' => $request->annual_leave_quota,
            'permanent_leave_quota' => $request->permanent_leave_quota,
            'internship_max_excused_days' => $request->internship_max_excused_days,
            'notes' => $request->notes,
        ]);

        AuditLog::record(
            'update_leave_policy',
            "Memperbarui kebijakan cuti perusahaan (PKWT: {$request->annual_leave_quota} hari, PKWTT: {$request->permanent_leave_quota} hari, Magang: {$request->internship_max_excused_days} hari)."
        );

        return redirect()->back()->with('success', 'Kebijakan kuota cuti perusahaan berhasil diperbarui!');
    }
}
