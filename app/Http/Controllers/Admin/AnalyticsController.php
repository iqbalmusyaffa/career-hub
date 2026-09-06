<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Resolve company filter for the logged-in user.
     */
    protected function getCompanyFilter()
    {
        $user = auth()->user();
        if (!$user || $user->hasRole('Super Admin')) {
            return request('company_name');
        }

        $profile = $user->currentCompanyProfile();
        return $profile ? $profile->company_name : null;
    }

    public function index(Request $request)
    {
        $companyName = $this->getCompanyFilter();
        $isSuperAdmin = auth()->user() && auth()->user()->hasRole('Super Admin');

        // 1. Base Query Scopes
        $appQuery = Application::query();
        $jobQuery = Job::query();

        if ($companyName) {
            $appQuery->whereHas('job', function($j) use ($companyName) {
                $j->where('company_name', 'LIKE', '%' . $companyName . '%');
            });
            $jobQuery->where('company_name', 'LIKE', '%' . $companyName . '%');
        }

        // 2. High-Level Metrics
        $totalApplications = (clone $appQuery)->count();
        $totalJobs = (clone $jobQuery)->count();
        $activeJobsCount = (clone $jobQuery)->where(function($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->count();
        $totalCompanies = CompanyProfile::count();

        // Status Counts
        $statusRaw = (clone $appQuery)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $pendingCount = ($statusRaw['pending'] ?? 0) + ($statusRaw['reviewing'] ?? 0) + ($statusRaw['reviewed'] ?? 0) + ($statusRaw['screening'] ?? 0);
        $testCount = ($statusRaw['test'] ?? 0);
        $interviewCount = ($statusRaw['interview'] ?? 0) + ($statusRaw['interview_hr'] ?? 0) + ($statusRaw['interview_user'] ?? 0);
        $hiredCount = ($statusRaw['accepted'] ?? 0) + ($statusRaw['hired'] ?? 0) + ($statusRaw['offered'] ?? 0);
        $rejectedCount = ($statusRaw['rejected'] ?? 0);

        // Overall Conversion Rate (Applied to Hired)
        $conversionRate = $totalApplications > 0 ? round(($hiredCount / $totalApplications) * 100, 1) : 0;
        $interviewRate = $totalApplications > 0 ? round(($interviewCount / $totalApplications) * 100, 1) : 0;

        // Average Time-to-Hire in days
        $avgDaysToHire = (clone $appQuery)->whereIn('status', ['accepted', 'hired'])
            ->select(DB::raw('AVG(DATEDIFF(updated_at, created_at)) as avg_days'))
            ->value('avg_days');
        $avgDaysToHire = $avgDaysToHire ? round($avgDaysToHire, 1) : 3.5;

        // 3. Top Divisions Breakdown
        $topDivisions = (clone $jobQuery)->select('division', DB::raw('count(*) as count'))
            ->whereNotNull('division')
            ->groupBy('division')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        // 4. Monthly Trend (Last 6 Months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $year = $monthDate->year;
            $month = $monthDate->month;
            $monthLabel = $monthDate->translatedFormat('M Y');

            $appCount = (clone $appQuery)->whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
            $hireMonthCount = (clone $appQuery)->whereIn('status', ['accepted', 'hired'])->whereYear('updated_at', $year)->whereMonth('updated_at', $month)->count();

            $monthlyTrend[] = [
                'month' => $monthLabel,
                'applications' => $appCount,
                'hires' => $hireMonthCount,
            ];
        }

        // 5. Top Hiring Companies or Top Job Postings
        $topCompanies = CompanyProfile::withCount('jobs')
            ->latest()
            ->take(5)
            ->get();

        $topJobs = (clone $jobQuery)
            ->withCount('applications')
            ->orderByDesc('applications_count')
            ->take(5)
            ->get();

        return view('admin.analytics.index', compact(
            'totalCompanies',
            'totalJobs',
            'activeJobsCount',
            'totalApplications',
            'pendingCount',
            'testCount',
            'interviewCount',
            'hiredCount',
            'rejectedCount',
            'conversionRate',
            'interviewRate',
            'avgDaysToHire',
            'topDivisions',
            'monthlyTrend',
            'topCompanies',
            'topJobs',
            'isSuperAdmin',
            'companyName'
        ));
    }
}

