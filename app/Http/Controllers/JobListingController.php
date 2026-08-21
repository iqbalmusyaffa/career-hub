<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Services\ApplicationService;
use Illuminate\Http\Request;

class JobListingController extends Controller
{
    protected $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function index(Request $request)
    {
        $query = Job::with(['applications'])->where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('requirements', 'like', '%' . $search . '%');
            });
        }
        
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('division')) {
            $query->where('division', $request->division);
        }

        if ($request->filled('work_type')) {
            $query->where('work_type', $request->work_type);
        }

        // Exclude expired jobs automatically
        $query->where(function($q) {
            $q->whereNull('deadline')->orWhere('deadline', '>=', now()->startOfDay());
        });

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'deadline_asc') {
            $query->orderBy('deadline', 'asc');
        } elseif ($sort === 'title_asc') {
            $query->orderBy('title', 'asc');
        } else {
            $query->latest();
        }

        $jobs = $query->paginate(9)->withQueryString();

        // Get filter options
        $divisions = Job::where('status', 'active')->distinct()->pluck('division')->filter();
        $locations = Job::where('status', 'active')->distinct()->pluck('location')->filter();
        $workTypes = Job::where('status', 'active')->distinct()->pluck('work_type')->filter();

        // Pass saved job IDs for current user if logged in
        $savedJobIds = [];
        if (auth()->check()) {
            $savedJobIds = auth()->user()->bookmarkedJobs()->pluck('job_postings.id')->toArray();
        }

        return view('jobs.index', compact('jobs', 'divisions', 'locations', 'workTypes', 'savedJobIds'));
    }

    public function show($id)
    {
        $job = Job::with(['test.questions', 'applications'])->where('status', 'active')->findOrFail($id);
        
        $hasApplied = false;
        $testResult = null;
        if (auth()->check()) {
            $hasApplied = \App\Models\Application::where('user_id', auth()->id())->where('job_id', $id)->exists();
            $testResult = \App\Models\CandidateTestResult::where('user_id', auth()->id())->where('job_id', $id)->first();
        }

        $compName = $job->company_name ?: 'PT TechNova Asia Digital';
        $companyJobsCount = Job::where('company_name', $compName)->where('status', 'active')->count();

        $umk = \App\Models\UmkReference::findByLocation($job->location);

        return view('jobs.show', compact('job', 'hasApplied', 'testResult', 'companyJobsCount', 'compName', 'umk'));
    }

    public function apply($id)
    {
        $user = auth()->user();
        
        // Ensure profile and CV exist
        if (!$user->candidateProfile || !$user->candidateProfile->cv_path) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi profil dan unggah CV terlebih dahulu sebelum melamar.');
        }

        try {
            $this->applicationService->applyForJob($user->id, $id);
            return redirect()->route('jobs.show', $id)->with('success', 'Berhasil melamar pekerjaan ini.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('jobs.show', $id)->with('error', $e->getMessage());
        }
    }
}
