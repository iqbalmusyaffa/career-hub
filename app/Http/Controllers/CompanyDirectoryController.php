<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;

class CompanyDirectoryController extends Controller
{
    /**
     * Display directory of companies opening job listings.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query distinct companies from job postings
        $query = Job::where('status', 'active')
            ->where(function($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now()->startOfDay());
            });

        if ($search) {
            $query->where('company_name', 'like', "%{$search}%");
        }

        $companyNames = $query->distinct()->pluck('company_name')->filter();

        // Build company items with job counts & company profile details
        $companies = collect($companyNames)->map(function ($name) {
            $profile = CompanyProfile::where('company_name', $name)->first();
            $jobsCount = Job::where('company_name', $name)->where('status', 'active')->count();
            $latestJob = Job::where('company_name', $name)->where('status', 'active')->latest()->first();

            return (object) [
                'name' => $name,
                'profile' => $profile,
                'jobs_count' => $jobsCount,
                'location' => $profile->address ?? ($latestJob ? $latestJob->location : 'Indonesia'),
                'industry' => $profile->industry ?? ($latestJob ? $latestJob->division : 'Software & Technology'),
                'employee_count' => $profile->employee_count ?? '50-200 Karyawan',
                'is_verified' => $profile ? $profile->is_verified : true,
            ];
        });

        return view('companies.index', compact('companies', 'search'));
    }

    /**
     * Display public company profile page with all active open job postings.
     */
    public function show($companyName)
    {
        $companyName = urldecode($companyName);

        // Find company profile database record if exists
        $companyProfile = CompanyProfile::where('company_name', 'like', "%{$companyName}%")->first();

        // Get all active job listings opened by this company
        $jobs = Job::where('company_name', 'like', "%{$companyName}%")
            ->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now()->startOfDay());
            })
            ->latest()
            ->get();

        $savedJobIds = [];
        if (auth()->check()) {
            $savedJobIds = auth()->user()->bookmarkedJobs()->pluck('job_postings.id')->toArray();
        }

        return view('companies.show', compact('companyName', 'companyProfile', 'jobs', 'savedJobIds'));
    }
}
