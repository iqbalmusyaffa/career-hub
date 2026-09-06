<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CompanyTeamMember;

use App\Models\HeadcountBudget;
use App\Models\Job;
use Illuminate\Http\Request;

class HeadcountBudgetController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $companyProfile = $user->currentCompanyProfile();
        $companyUserId = $companyProfile ? $companyProfile->user_id : $user->id;

        $fiscalYear = $request->input('fiscal_year', 2026);
        $budgets = HeadcountBudget::where('company_user_id', $companyUserId)
            ->where('fiscal_year', $fiscalYear)
            ->get();

        // Calculate actual hired count per division for this company
        $companyName = $companyProfile ? $companyProfile->company_name : null;

        $divisionJobMap = $companyName ? Job::where('company_name', 'LIKE', '%' . $companyName . '%')->get()->groupBy('division') : Job::all()->groupBy('division');

        $budgetStats = [];
        $totalTargetHeadcount = 0;
        $totalActualHired = 0;
        $totalAllocatedBudget = 0;

        foreach ($budgets as $b) {
            $divisionJobs = $divisionJobMap->get($b->division, collect());
            $divisionJobIds = $divisionJobs->pluck('id');

            $actualHired = Application::whereIn('job_id', $divisionJobIds)
                ->whereIn('status', ['accepted', 'hired'])
                ->count();

            $percentage = $b->target_headcount > 0 ? round(($actualHired / $b->target_headcount) * 100, 1) : 0;

            $budgetStats[] = [
                'id' => $b->id,
                'division' => $b->division,
                'target_headcount' => $b->target_headcount,
                'actual_hired' => $actualHired,
                'remaining' => max(0, $b->target_headcount - $actualHired),
                'allocated_budget' => $b->allocated_budget,
                'percentage' => min(100, $percentage),
                'notes' => $b->notes,
            ];

            $totalTargetHeadcount += $b->target_headcount;
            $totalActualHired += $actualHired;
            $totalAllocatedBudget += $b->allocated_budget;
        }

        return view('admin.headcount_budgets.index', compact(
            'budgets',
            'budgetStats',
            'fiscalYear',
            'totalTargetHeadcount',
            'totalActualHired',
            'totalAllocatedBudget'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $companyProfile = $user->currentCompanyProfile();
        $companyUserId = $companyProfile ? $companyProfile->user_id : $user->id;

        $request->validate([
            'division' => 'required|string|max:255',
            'fiscal_year' => 'required|integer|min:2024|max:2030',
            'target_headcount' => 'required|integer|min:1',
            'allocated_budget' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        HeadcountBudget::updateOrCreate(
            [
                'company_user_id' => $companyUserId,
                'division' => $request->division,
                'fiscal_year' => $request->fiscal_year,
            ],
            [
                'target_headcount' => $request->target_headcount,
                'allocated_budget' => $request->allocated_budget,
                'notes' => $request->notes,
            ]
        );

        return back()->with('success', "Target Headcount & Budget divisi {$request->division} berhasil disimpan!");
    }

    public function destroy(HeadcountBudget $headcount_budget)
    {
        $headcount_budget->delete();
        return back()->with('success', 'Alokasi Headcount Budget berhasil dihapus.');
    }
}
