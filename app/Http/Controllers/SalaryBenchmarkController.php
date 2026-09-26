<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\UmkReference;
use App\Services\SalaryBenchmarkService;
use Illuminate\Http\Request;

class SalaryBenchmarkController extends Controller
{
    /**
     * Display Salary Benchmark & Market Salary Insights page.
     */
    public function index(Request $request)
    {
        $position = $request->input('position', '');
        $location = $request->input('location', '');
        $level = $request->input('level', 'Fresh Graduate (0-1 Tahun)');
        $skillsInput = $request->input('skills', '');

        $effectiveLoc = $location ?: 'Jakarta';
        $effectiveLevel = $level ?: 'Fresh Graduate (0-1 Tahun)';

        $result = null;

        // Perform calculation if user has inputted position or location
        if (!empty($position) || !empty($location) || $request->has('calculate')) {
            $effectivePos = $position ?: 'Software Engineer';

            $calc = SalaryBenchmarkService::calculate([
                'position' => $effectivePos,
                'location' => $effectiveLoc,
                'level' => $effectiveLevel,
                'skills' => $skillsInput,
            ]);

            $umk = UmkReference::findByLocation($effectiveLoc);

            // Query active matching jobs
            $jobs = Job::where('title', 'like', "%{$effectivePos}%")
                ->orWhere('division', 'like', "%{$effectivePos}%")
                ->latest()
                ->take(5)
                ->get();

            $matchedHighDemand = array_map(function ($item) {
                return $item['skill'];
            }, $calc['market_insights']['matched_skill_premiums']);

            $result = [
                'position' => $position ?: $calc['query']['canonical_role'],
                'location' => $location ?: $calc['regional_benchmark']['city'],
                'level' => $level,
                'skills_input' => $skillsInput,
                'skill_list' => $calc['query']['skills'],
                'matched_high_demand' => $matchedHighDemand,
                'skill_bonus_pct' => $calc['market_insights']['skill_bonus_percentage'],
                'min' => $calc['salary_range']['min_monthly'],
                'avg' => $calc['salary_range']['median_monthly'],
                'max' => $calc['salary_range']['max_monthly'],
                'umk' => $umk,
                'sample_jobs' => $jobs,
                'thp_estimate' => $calc['take_home_pay_estimate'],
                'category' => $calc['query']['category'],
                'demand' => $calc['market_insights']['market_demand'],
            ];
        }

        // Dynamically compute accurate benchmarks for 9 popular roles across major job families
        $popularRoles = [
            'Software Engineer',
            'Backend Developer',
            'Frontend Developer',
            'Fullstack Developer',
            'UI/UX Designer',
            'Data Analyst',
            'Product Manager',
            'Digital Marketing Specialist',
            'Accounting Specialist',
        ];

        $benchmarks = [];
        foreach ($popularRoles as $roleName) {
            $roleCalc = SalaryBenchmarkService::calculate([
                'position' => $roleName,
                'location' => $effectiveLoc,
                'level' => $effectiveLevel,
                'skills' => '',
            ]);

            $benchmarks[$roleName] = [
                'category' => $roleCalc['query']['category'],
                'demand' => $roleCalc['market_insights']['market_demand'],
                'avg' => $roleCalc['salary_range']['median_monthly'],
                'formatted_avg' => $roleCalc['salary_range']['formatted']['median'],
                'min' => $roleCalc['salary_range']['min_monthly'],
                'max' => $roleCalc['salary_range']['max_monthly'],
                'formatted_range' => 'Rp ' . SalaryBenchmarkService::formatJuta($roleCalc['salary_range']['min_monthly']) . ' - ' . SalaryBenchmarkService::formatJuta($roleCalc['salary_range']['max_monthly']),
            ];
        }

        return view('salary_benchmark.index', compact('result', 'benchmarks', 'position', 'location', 'level', 'skillsInput', 'effectiveLoc', 'effectiveLevel'));
    }
}
