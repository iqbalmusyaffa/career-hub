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

        // Company Breakdown Stats
        $companyBreakdown = $companies->map(function ($company) {
            $logbooks = InternshipLogbook::where('company_id', $company->id)->get();
            $approved = $logbooks->where('status', 'approved')->count();
            $pending = $logbooks->where('status', 'pending')->count();
            $total = $logbooks->count();
            $rate = $total > 0 ? round(($approved / max(1, $total)) * 100) : 0;

            return [
                'company' => $company,
                'total_logbooks' => $total,
                'approved_count' => $approved,
                'pending_count' => $pending,
                'attendance_rate' => $rate,
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
