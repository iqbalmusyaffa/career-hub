<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\InternshipLogbook;
use App\Models\InternshipEvaluation;
use App\Models\InternshipPeriod;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CrossCompanyInternshipMonitorController extends Controller
{
    public function index(Request $request)
    {
        $companyFilter = $request->input('company_id');

        // All active interns across platform
        $internsQuery = User::role('Candidate')
            ->with(['companyProfile', 'roles']);

        $interns = $internsQuery->get();
        $totalInterns = $interns->count();

        // Total companies with active interns
        $companies = CompanyProfile::all();

        // Pending logbooks requiring mentor review older than 3 days
        $threeDaysAgo = Carbon::now()->subDays(3)->format('Y-m-d');
        $overdueReviews = InternshipLogbook::with(['intern', 'company', 'mentor'])
            ->where('status', 'pending')
            ->where('date', '<=', $threeDaysAgo)
            ->latest('date')
            ->take(10)
            ->get();

        $totalLogbooksCount = InternshipLogbook::count();
        $totalApprovedLogbooks = InternshipLogbook::where('status', 'approved')->count();
        $totalPendingLogbooks = InternshipLogbook::where('status', 'pending')->count();
        $totalRejectedLogbooks = InternshipLogbook::where('status', 'rejected')->count();

        $overallAttendanceRate = $totalLogbooksCount > 0
            ? round(($totalApprovedLogbooks / max(1, $totalLogbooksCount)) * 100)
            : 100;

        // Company Breakdown Stats with nested candidate & logbook details
        $companyBreakdown = $companies->map(function ($company) {
            $logbooks = InternshipLogbook::with(['intern', 'mentor'])
                ->where('company_id', $company->id)
                ->orderBy('date', 'desc')
                ->get();

            $approved = $logbooks->where('status', 'approved')->count();
            $pending = $logbooks->where('status', 'pending')->count();
            $rejected = $logbooks->where('status', 'rejected')->count();
            $total = $logbooks->count();
            $rate = $total > 0 ? round(($approved / max(1, $total)) * 100) : 0;

            // Group logbooks by intern for expandable modal/drill-down
            $internsGrouped = $logbooks->groupBy('user_id')->map(function ($internLogbooks) {
                $first = $internLogbooks->first();
                $internUser = $first->intern ?? null;
                $iApproved = $internLogbooks->where('status', 'approved')->count();
                $iPending = $internLogbooks->where('status', 'pending')->count();
                $iRejected = $internLogbooks->where('status', 'rejected')->count();
                $iTotal = $internLogbooks->count();

                return [
                    'user' => $internUser,
                    'user_id' => $first->user_id,
                    'name' => $internUser->name ?? 'Peserta Magang',
                    'email' => $internUser->email ?? '-',
                    'total_logbooks' => $iTotal,
                    'approved_count' => $iApproved,
                    'pending_count' => $iPending,
                    'rejected_count' => $iRejected,
                    'rate' => $iTotal > 0 ? round(($iApproved / max(1, $iTotal)) * 100) : 0,
                    'logbooks' => $internLogbooks->map(function ($l) {
                        return [
                            'id' => $l->id,
                            'date' => $l->date ? $l->date->isoFormat('D MMM YYYY') : '-',
                            'status' => $l->status,
                            'status_badge' => $l->status_badge,
                            'attendance_type' => $l->attendance_type ?? 'WFO',
                            'activities' => $l->activities ?? '-',
                            'location_address' => $l->location_address ?? 'Lokasi Terverifikasi',
                            'has_gps' => !empty($l->latitude),
                            'mentor_name' => $l->mentor->name ?? 'Belum Ditugaskan',
                            'mentor_notes' => $l->mentor_notes,
                            'show_url' => route('mentor.logbooks.show', $l->id),
                            'approve_url' => route('mentor.logbooks.approve', $l->id),
                        ];
                    })->values(),
                ];
            })->values();

            return [
                'company' => $company,
                'total_logbooks' => $total,
                'approved_count' => $approved,
                'pending_count' => $pending,
                'rejected_count' => $rejected,
                'attendance_rate' => $rate,
                'interns_count' => $internsGrouped->count(),
                'interns_list' => $internsGrouped,
                'recent_logbooks' => $logbooks->take(20)->map(function ($l) {
                    return [
                        'id' => $l->id,
                        'intern_name' => $l->intern->name ?? 'Peserta',
                        'intern_email' => $l->intern->email ?? '-',
                        'date' => $l->date ? $l->date->isoFormat('D MMM YYYY') : '-',
                        'status' => $l->status,
                        'status_badge' => $l->status_badge,
                        'activities' => $l->activities ?? '-',
                        'mentor_name' => $l->mentor->name ?? 'Belum Ditugaskan',
                        'show_url' => route('mentor.logbooks.show', $l->id),
                        'approve_url' => route('mentor.logbooks.approve', $l->id),
                    ];
                })->values(),
            ];
        });

        return view('admin.internship_monitor.index', compact(
            'totalInterns',
            'companies',
            'overdueReviews',
            'totalLogbooksCount',
            'totalApprovedLogbooks',
            'totalPendingLogbooks',
            'totalRejectedLogbooks',
            'overallAttendanceRate',
            'companyBreakdown'
        ));
    }
}
