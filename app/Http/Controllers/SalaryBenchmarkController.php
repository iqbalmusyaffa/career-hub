<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class SalaryBenchmarkController extends Controller
{
    /**
     * Display Salary Benchmark & Market Salary Insights page.
     */
    public function index(Request $request)
    {
        $position = $request->input('position', 'Software Engineer');
        $location = $request->input('location', 'Jakarta');
        $level = $request->input('level', 'Fresh Graduate');

        // Query average salary from existing active job listings
        $jobs = Job::where('title', 'like', "%{$position}%")
            ->orWhere('division', 'like', "%{$position}%")
            ->get();

        $benchmarks = [
            'Software Engineer' => ['base' => 14000000, 'avg' => 14000000],
            'Frontend Developer' => ['base' => 12500000, 'avg' => 12500000],
            'Backend Developer' => ['base' => 15000000, 'avg' => 15000000],
            'Fullstack Developer' => ['base' => 16000000, 'avg' => 16000000],
            'UI/UX Designer' => ['base' => 11000000, 'avg' => 11000000],
            'Data Analyst' => ['base' => 13000000, 'avg' => 13000000],
            'HR Manager' => ['base' => 15000000, 'avg' => 15000000],
            'Marketing Specialist' => ['base' => 10000000, 'avg' => 10000000],
            'Product Manager' => ['base' => 20000000, 'avg' => 20000000],
            'Finance & Accounting' => ['base' => 10500000, 'avg' => 10500000],
        ];

        $umk = \App\Models\UmkReference::findByLocation($location);

        if ($umk) {
            $baseUmk = (float) $umk->umk_amount;

            // Smart experience level multipliers based on UMK
            switch ($level) {
                case 'Fresh Graduate':
                case 'Fresh Graduate (0-1 Tahun)':
                    $minSalary = (int) $baseUmk; // Exactly UMK for fresh graduates
                    $avgSalary = (int) round($baseUmk * 1.35, -4);
                    $maxSalary = (int) round($baseUmk * 1.85, -4);
                    break;

                case 'Junior Level (1-2 Tahun)':
                case 'Junior Level':
                    $minSalary = (int) round($baseUmk * 1.25, -4); // 1-2 years experience starts above UMK
                    $avgSalary = (int) round($baseUmk * 1.8, -4);
                    $maxSalary = (int) round($baseUmk * 2.5, -4);
                    break;

                case 'Mid Level (2-5 Tahun)':
                case 'Mid Level':
                    $minSalary = (int) round($baseUmk * 1.8, -4);
                    $avgSalary = (int) round($baseUmk * 2.6, -4);
                    $maxSalary = (int) round($baseUmk * 3.8, -4);
                    break;

                case 'Senior Level (5+ Tahun)':
                case 'Senior Level':
                    $minSalary = (int) round($baseUmk * 2.6, -4);
                    $avgSalary = (int) round($baseUmk * 4.2, -4);
                    $maxSalary = (int) round($baseUmk * 6.0, -4);
                    break;

                case 'Lead / Managerial':
                case 'Lead / Manager':
                default:
                    $minSalary = (int) round($baseUmk * 3.8, -4);
                    $avgSalary = (int) round($baseUmk * 6.5, -4);
                    $maxSalary = (int) round($baseUmk * 10.0, -4);
                    break;
            }
        } else {
            // Default national fallback
            $baseVal = 10000000;
            foreach ($benchmarks as $key => $val) {
                if (str_contains(strtolower($position), strtolower($key))) {
                    $baseVal = $val['base'];
                    break;
                }
            }

            $mult = match ($level) {
                'Fresh Graduate', 'Fresh Graduate (0-1 Tahun)' => 0.5,
                'Junior Level (1-2 Tahun)', 'Junior Level' => 0.8,
                'Senior Level (5+ Tahun)', 'Senior Level' => 1.6,
                'Lead / Managerial', 'Lead / Manager' => 2.2,
                default => 1.0,
            };

            $minSalary = (int) ($baseVal * $mult * 0.65);
            $avgSalary = (int) ($baseVal * $mult);
            $maxSalary = (int) ($baseVal * $mult * 1.6);
        }

        $skillsInput = $request->input('skills', '');
        $skillList = array_filter(array_map('trim', explode(',', $skillsInput)));

        // Skill premium calculation engine
        $skillBonusPct = 0;
        $highDemandKeywords = ['aws', 'cloud', 'kubernetes', 'docker', 'devops', 'react', 'laravel', 'node', 'python', 'ai', 'machine learning', 'golang', 'flutter', 'cyber security', 'data science', 'scrum', 'pmp', 'postgresql', 'ci/cd', 'microservices'];
        
        $matchedHighDemand = [];
        foreach ($skillList as $s) {
            $lowerS = strtolower($s);
            foreach ($highDemandKeywords as $hd) {
                if (str_contains($lowerS, $hd)) {
                    $skillBonusPct += 8; // +8% salary premium per high demand skill
                    $matchedHighDemand[] = ucwords($s);
                    break;
                }
            }
        }
        
        // Cap skill bonus to 40% max
        $skillBonusPct = min(40, $skillBonusPct);
        $skillMultiplier = 1 + ($skillBonusPct / 100);

        $avgSalary = (int) round($avgSalary * $skillMultiplier, -4);
        $maxSalary = (int) round($maxSalary * $skillMultiplier, -4);
        
        // Min salary gets slight bump if candidate has high-demand skills and experience
        if ($level !== 'Fresh Graduate' && $level !== 'Fresh Graduate (0-1 Tahun)' && $skillBonusPct > 0) {
            $minSalary = (int) round($minSalary * (1 + ($skillBonusPct / 200)), -4);
        }

        $result = [
            'position' => $position,
            'location' => $location,
            'level' => $level,
            'skills_input' => $skillsInput,
            'skill_list' => $skillList,
            'matched_high_demand' => $matchedHighDemand,
            'skill_bonus_pct' => $skillBonusPct,
            'min' => $minSalary,
            'avg' => $avgSalary,
            'max' => $maxSalary,
            'umk' => $umk,
            'sample_jobs' => $jobs->take(5),
        ];

        return view('salary_benchmark.index', compact('result', 'benchmarks'));
    }
}
