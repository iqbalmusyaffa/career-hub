<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;
use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\JobTest;
use Illuminate\Support\Facades\Auth;

use App\Models\CompanyRoleRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            // Super Admin Command Center Metrics
            $totalUsers = User::count();
            $totalCandidates = \Spatie\Permission\Models\Role::where('name', 'Candidate')->exists() ? User::role('Candidate')->count() : 0;
            $totalCompanies = \Spatie\Permission\Models\Role::whereIn('name', ['HR', 'Company Owner'])->exists() ? User::role(['HR', 'Company Owner'])->count() : 0;
            $verifiedCompanies = CompanyProfile::where('is_verified', true)->count();
            
            $pendingRoleRequestsCount = CompanyRoleRequest::where('status', 'pending')->count();
            $pendingCompanyProfileVerifications = CompanyProfile::whereNotNull('legal_doc_path')
                ->where('is_verified', false)
                ->count();
            $pendingCompanyVerifications = $pendingRoleRequestsCount + $pendingCompanyProfileVerifications;
            
            $suspendedUsers = User::where('is_suspended', true)->count();
            
            $totalJobs = Job::count();
            $activeJobs = Job::where('status', 'active')->count();
            $totalApplications = Application::count();
            $totalTests = JobTest::count();

            // Pending Company & HR Role Requests
            $pendingRoleRequests = CompanyRoleRequest::with('user')
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();

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
                'pendingCompanyVerifications', 'pendingRoleRequestsCount', 'pendingRoleRequests',
                'suspendedUsers', 'totalJobs', 'activeJobs',
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
        $reviewingApplications = Application::where('status', 'reviewing')->count();
        $acceptedApplications = Application::where('status', 'accepted')->count();
        $rejectedApplications = Application::where('status', 'rejected')->count();
        $interviewApplications = Application::where('status', 'interview')->count();

        $latestApplications = Application::with(['user.candidateProfile', 'job'])->latest()->take(5)->get();

        $statusCounts = [
            'pending' => $newApplications,
            'reviewing' => $reviewingApplications,
            'interview' => $interviewApplications,
            'accepted' => $acceptedApplications,
            'rejected' => $rejectedApplications,
        ];

        // Conversion Rate Percentages for Recruitment Funnel
        $conversionRate = $totalApplications > 0 ? round(($acceptedApplications / $totalApplications) * 100, 1) : 0;

        // Work Type Breakdown
        $workTypeBreakdown = [
            'fulltime' => Job::whereIn('work_type', ['fulltime', 'WFO', 'Full-time'])->count(),
            'remote' => Job::whereIn('work_type', ['remote', 'WFH', 'Remote'])->count(),
            'hybrid' => Job::whereIn('work_type', ['hybrid', 'Hybrid'])->count(),
            'internship' => Job::whereIn('work_type', ['internship', 'Magang', 'Internship'])->count(),
        ];

        // Documents Issued Statistics
        $agreementsCount = \App\Models\ApplicationAgreement::count();
        $certificatesCount = \App\Models\InternshipCertificate::count();
        $transcriptsCount = \App\Models\InternshipTranscript::count();
        $terminationsCount = \App\Models\EmployeeTermination::count();

        // Monthly Applicant Trend (Last 6 Months)
        $monthlyTrendLabels = [];
        $monthlyTrendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyTrendLabels[] = $date->format('M Y');
            $monthlyTrendData[] = Application::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

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
            'newApplications', 'latestApplications', 'statusCounts', 'topJobs', 'upcomingInterviews',
            'conversionRate', 'workTypeBreakdown', 'agreementsCount', 'certificatesCount',
            'transcriptsCount', 'terminationsCount', 'monthlyTrendLabels', 'monthlyTrendData'
        ));
    }
}
