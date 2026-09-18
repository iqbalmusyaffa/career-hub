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

        if ($request->filled('major')) {
            $major = $request->major;
            $query->where(function($q) use ($major) {
                $q->where('major_requirement', 'like', "%{$major}%")
                  ->orWhere('description', 'like', "%{$major}%")
                  ->orWhere('requirements', 'like', "%{$major}%");
            });
        }

        if ($request->filled('experience_level')) {
            $exp = $request->experience_level;
            $query->where(function($q) use ($exp) {
                $q->where('experience_level', $exp)
                  ->orWhere('requirements', 'like', "%{$exp}%")
                  ->orWhere('title', 'like', "%{$exp}%");
            });
        }

        if ($request->filled('salary_range')) {
            $sal = $request->salary_range;
            if ($sal === 'under_5m') {
                $query->where(function($q) {
                    $q->where('salary', 'like', '%3.%')
                      ->orWhere('salary', 'like', '%4.%')
                      ->orWhere('salary', 'like', '%Rp 3%')
                      ->orWhere('salary', 'like', '%Rp 4%');
                });
            } elseif ($sal === '5m_10m') {
                $query->where(function($q) {
                    $q->where('salary', 'like', '%5.%')
                      ->orWhere('salary', 'like', '%6.%')
                      ->orWhere('salary', 'like', '%7.%')
                      ->orWhere('salary', 'like', '%8.%')
                      ->orWhere('salary', 'like', '%9.%');
                });
            } elseif ($sal === '10m_20m') {
                $query->where(function($q) {
                    $q->where('salary', 'like', '%10.%')
                      ->orWhere('salary', 'like', '%12.%')
                      ->orWhere('salary', 'like', '%14.%')
                      ->orWhere('salary', 'like', '%15.%')
                      ->orWhere('salary', 'like', '%18.%');
                });
            } elseif ($sal === 'above_20m') {
                $query->where(function($q) {
                    $q->where('salary', 'like', '%20.%')
                      ->orWhere('salary', 'like', '%22.%')
                      ->orWhere('salary', 'like', '%25.%')
                      ->orWhere('salary', 'like', '%30.%');
                });
            }
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
        $experienceLevels = ['Magang / Intern', 'Junior (1-2 Tahun)', 'Mid-Level (2-5 Tahun)', 'Senior / Lead (5+ Tahun)'];

        // Pass saved job IDs for current user if logged in
        $savedJobIds = [];
        if (auth()->check()) {
            $savedJobIds = auth()->user()->bookmarkedJobs()->pluck('job_postings.id')->toArray();
        }

        return view('jobs.index', compact('jobs', 'divisions', 'locations', 'workTypes', 'experienceLevels', 'savedJobIds'));
    }

    public function show($id)
    {
        $realId = \App\Helpers\IdHasher::decode($id) ?? $id;
        $job = Job::with(['test.questions', 'applications'])->where('status', 'active')->findOrFail($realId);
        
        $hasApplied = false;
        $testResult = null;
        $isAlreadyEnrolled = false;
        $internshipBlockReason = null;

        if (auth()->check()) {
            $user = auth()->user();
            $hasApplied = \App\Models\Application::where('user_id', $user->id)->where('job_id', $job->id)->exists();
            $testResult = \App\Models\CandidateTestResult::where('user_id', $user->id)->where('job_id', $job->id)->first();

            // Pembatasan magang berlaku jika lowongan yang sedang dilihat adalah posisi MAGANG (Internship)
            if ($user->hasRole('Candidate') && $job->isInternship()) {
                $internshipBlockReason = $user->getInternshipBlockReason();
                if ($internshipBlockReason) {
                    $isAlreadyEnrolled = true;
                }
            }
        }

        $compName = $job->company_name ?: 'PT TechNova Asia Digital';
        $companyJobsCount = Job::where('company_name', $compName)->where('status', 'active')->count();

        $umk = \App\Models\UmkReference::findByLocation($job->location);

        return view('jobs.show', compact('job', 'hasApplied', 'testResult', 'companyJobsCount', 'compName', 'umk', 'isAlreadyEnrolled', 'internshipBlockReason'));
    }

    public function apply(Request $request, $id)
    {
        $realId = \App\Helpers\IdHasher::decode($id) ?? $id;
        $job = Job::where('status', 'active')->findOrFail($realId);
        $user = auth()->user();
        
        // Ensure profile and CV exist
        if (!$user->candidateProfile || !$user->candidateProfile->cv_path) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi profil dan unggah CV terlebih dahulu sebelum melamar.');
        }

        $screeningVideoUrl = $request->input('screening_video_url');

        try {
            $this->applicationService->applyForJob($user->id, $job->id, $screeningVideoUrl);
            return redirect()->route('jobs.show', $job)->with('success', 'Berhasil melamar pekerjaan ini.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('jobs.show', $job)->with('error', $e->getMessage());
        }
    }

    public function updateVideoScreening(Request $request, $id)
    {
        $realId = \App\Helpers\IdHasher::decode($id) ?? $id;
        $application = \App\Models\Application::where('user_id', auth()->id())->findOrFail($realId);
        
        $request->validate([
            'screening_video_url' => 'required|url|max:500',
        ]);

        $application->update([
            'screening_video_url' => $request->screening_video_url,
        ]);

        return back()->with('success', 'Link video perkenalan screening berhasil diperbarui.');
    }
}
