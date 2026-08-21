<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Support\Facades\DB;

class RecruitmentAnalyticsController extends Controller
{
    /**
     * Display Recruitment Analytics Dashboard with Chart.js charts.
     */
    public function index()
    {
        // 1. Hiring Funnel Stages Data
        $funnelData = [
            'pending' => Application::where('status', 'pending')->count(),
            'reviewing' => Application::where('status', 'reviewing')->count(),
            'interview' => Application::where('status', 'interview')->count(),
            'accepted' => Application::where('status', 'accepted')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];

        // 2. Monthly Application Trend for current year
        $monthlyApplications = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        for ($m = 1; $m <= 12; $m++) {
            $count = Application::whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $m)
                ->count();
            $monthlyApplications[] = $count;
        }

        // 3. Education Level Requirement Breakdown (from Job Postings)
        $educationBreakdown = Job::select('education_level', DB::raw('count(*) as total'))
            ->whereNotNull('education_level')
            ->groupBy('education_level')
            ->pluck('total', 'education_level')
            ->toArray();

        // Fallback default values if empty
        if (empty($educationBreakdown)) {
            $educationBreakdown = [
                'SMA/SMK' => 4,
                'D3' => 6,
                'S1 / D4' => 18,
                'S2' => 3
            ];
        }

        // 4. Job Work Type Distribution (WFO, Hybrid, Remote)
        $workTypeDistribution = Job::select('work_type', DB::raw('count(*) as total'))
            ->whereNotNull('work_type')
            ->groupBy('work_type')
            ->pluck('total', 'work_type')
            ->toArray();

        if (empty($workTypeDistribution)) {
            $workTypeDistribution = ['WFO' => 15, 'Hybrid' => 10, 'Remote' => 8];
        }

        return view('admin.analytics.index', compact(
            'funnelData', 'months', 'monthlyApplications', 'educationBreakdown', 'workTypeDistribution'
        ));
    }
}
