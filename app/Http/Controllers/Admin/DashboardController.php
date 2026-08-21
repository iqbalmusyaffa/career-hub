<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;
use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\JobTest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            // Super Admin Command Center Metrics
            $totalUsers = User::count();
            $totalCandidates = User::role('Candidate')->count();
            $totalCompanies = User::role(['HR', 'Company Owner'])->count();
            $verifiedCompanies = CompanyProfile::where('is_verified', true)->count();
            $pendingCompanyVerifications = CompanyProfile::whereNotNull('legal_doc_path')
                ->where('is_verified', false)
                ->count();
            $suspendedUsers = User::where('is_suspended', true)->count();
            
            $totalJobs = Job::count();
            $activeJobs = Job::where('status', 'active')->count();
            $totalApplications = Application::count();
            $totalTests = JobTest::count();

            // Companies requiring legal document verification
            $pendingCompaniesList = CompanyProfile::with('user')
                ->whereNotNull('legal_doc_path')
                ->where('is_verified', false)
                ->latest()
                ->take(5)
                ->get();

            // Recent registered users across platform
            $recentUsers = User::latest()->take(6)->get();

            return view('admin.superadmin_dashboard', compact(
                'totalUsers', 'totalCandidates', 'totalCompanies', 'verifiedCompanies',
                'pendingCompanyVerifications', 'suspendedUsers', 'totalJobs', 'activeJobs',
                'totalApplications', 'totalTests', 'pendingCompaniesList', 'recentUsers'
            ));
        }

        if ($user->hasRole('Company Owner')) {
            // Company Owner Executive Overview Dashboard
            $companyProfile = CompanyProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => 'PT ' . $user->name,
                    'industry' => 'Teknologi & Bisnis',
                    'is_verified' => false,
                ]
            );

            $companyJobs = Job::withCount('applications')->latest()->get();
            $companyJobsCount = $companyJobs->count();
            $activeJobsCount = $companyJobs->where('status', 'active')->count();

            $totalCompanyApplications = Application::count();
            $hiredCount = Application::where('status', 'accepted')->count();
            $interviewCount = Application::where('status', 'interview')->count();
            $pendingCount = Application::where('status', 'pending')->count();

            $recentApplications = Application::with(['user.candidateProfile', 'job'])
                ->latest()
                ->take(5)
                ->get();

            return view('admin.owner_dashboard', compact(
                'companyProfile', 'companyJobs', 'companyJobsCount', 'activeJobsCount',
                'totalCompanyApplications', 'hiredCount', 'interviewCount', 'pendingCount',
                'recentApplications'
            ));
        }

        // Standard HR / Admin Dashboard
        $totalJobs = Job::count();
        $activeJobs = Job::where('status', 'active')->count();
        $totalApplicants = Application::distinct('user_id')->count('user_id');
        $totalApplications = Application::count();
        $newApplications = Application::where('status', 'pending')->count();
        $acceptedApplications = Application::where('status', 'accepted')->count();
        $rejectedApplications = Application::where('status', 'rejected')->count();
        $interviewApplications = Application::where('status', 'interview')->count();

        $latestApplications = Application::with(['user.candidateProfile', 'job'])->latest()->take(5)->get();

        $statusCounts = [
            'pending' => $newApplications,
            'reviewing' => Application::where('status', 'reviewing')->count(),
            'interview' => $interviewApplications,
            'accepted' => $acceptedApplications,
            'rejected' => $rejectedApplications,
        ];

        $topJobs = Job::withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->take(5)
            ->get();

        $upcomingInterviews = \App\Models\Interview::with(['application.user', 'application.job'])
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalJobs', 'activeJobs', 'totalApplicants', 'totalApplications', 
            'newApplications', 'latestApplications', 'statusCounts', 'topJobs', 'upcomingInterviews'
        ));
    }
}
