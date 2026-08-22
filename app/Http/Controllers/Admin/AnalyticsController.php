<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $totalCompanies = CompanyProfile::count();
        $totalJobs = Job::count();
        $totalApplications = Application::count();

        $hiredCount = Application::whereIn('status', ['accepted', 'hired'])->count();
        $interviewCount = Application::where('status', 'interview')->count();
        $testCount = Application::where('status', 'test')->count();
        $pendingCount = Application::where('status', 'pending')->count();
        $rejectedCount = Application::where('status', 'rejected')->count();

        // Overall Conversion Rate (Applied to Hired)
        $conversionRate = $totalApplications > 0 ? round(($hiredCount / $totalApplications) * 100, 1) : 0;

        // Average Time-to-Hire in days
        $avgDaysToHire = Application::whereIn('status', ['accepted', 'hired'])
            ->select(DB::raw('AVG(DATEDIFF(updated_at, created_at)) as avg_days'))
            ->value('avg_days');
        $avgDaysToHire = $avgDaysToHire ? round($avgDaysToHire, 1) : 3.5;

        // Top Hiring Companies
        $topCompanies = CompanyProfile::latest()
            ->take(5)
            ->get();

        // Top Job Categories / Divisions
        $topDivisions = Job::select('division', DB::raw('count(*) as count'))
            ->groupBy('division')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        // Monthly Applications Trend (Last 6 Months)
        $monthlyTrend = Application::select(
            DB::raw('DATE_FORMAT(created_at, "%b %Y") as month'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('month')
        ->orderBy('created_at', 'asc')
        ->take(6)
        ->get();

        return view('admin.analytics.index', compact(
            'totalCompanies',
            'totalJobs',
            'totalApplications',
            'hiredCount',
            'interviewCount',
            'testCount',
            'pendingCount',
            'rejectedCount',
            'conversionRate',
            'avgDaysToHire',
            'topCompanies',
            'topDivisions',
            'monthlyTrend'
        ));
    }
}
