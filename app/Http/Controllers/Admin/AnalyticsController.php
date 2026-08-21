<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    /**
     * Display HR Recruitment Analytics & KPI Dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super-admin');

        $jobsQuery = Job::query();
        $appsQuery = Application::query();

        if (!$isSuperAdmin) {
            $ownerId = $user->id;
            $teamMember = \App\Models\CompanyTeamMember::where('user_id', $user->id)->first();
            if ($teamMember) {
                $ownerId = $teamMember->owner_id;
            }

            $profile = CompanyProfile::where('user_id', $ownerId)->first();
            $compName = $profile ? $profile->company_name : 'PT TechNova Asia Digital';

            $jobsQuery->where('company_name', $compName);
            $appsQuery->whereHas('job', function($q) use ($compName) {
                $q->where('company_name', $compName);
            });
        }

        $totalActiveJobs = (clone $jobsQuery)->where('status', 'active')->count();
        $totalApplications = (clone $appsQuery)->count();

        // Pipeline stage conversion breakdown
        $stageStats = [
            'Pending / Screening' => (clone $appsQuery)->where('status', 'pending')->count(),
            'Tes Online' => (clone $appsQuery)->where('status', 'test_online')->count(),
            'Wawancara HR' => (clone $appsQuery)->where('status', 'interview_hr')->count(),
            'Wawancara User' => (clone $appsQuery)->where('status', 'interview_user')->count(),
            'Offer Letter' => (clone $appsQuery)->where('status', 'offer_letter')->count(),
            'Diterima (Hired)' => (clone $appsQuery)->where('status', 'accepted')->count(),
            'Ditolak' => (clone $appsQuery)->where('status', 'rejected')->count(),
        ];

        // Top 5 Popular Jobs by Applications count
        $popularJobs = (clone $jobsQuery)->withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->take(5)
            ->get();

        // Education Breakdown of applicants
        $educationStats = [
            'SMA/SMK' => (clone $appsQuery)->whereHas('user.candidateProfile', function($q) {
                $q->where('last_education', 'like', '%SMA%')->orWhere('last_education', 'like', '%SMK%');
            })->count(),
            'D3 / Diploma' => (clone $appsQuery)->whereHas('user.candidateProfile', function($q) {
                $q->where('last_education', 'like', '%D3%')->orWhere('last_education', 'like', '%Diploma%');
            })->count(),
            'S1 / Sarjana' => (clone $appsQuery)->whereHas('user.candidateProfile', function($q) {
                $q->where('last_education', 'like', '%S1%')->orWhere('last_education', 'like', '%Sarjana%');
            })->count(),
            'S2 / Magister' => (clone $appsQuery)->whereHas('user.candidateProfile', function($q) {
                $q->where('last_education', 'like', '%S2%');
            })->count(),
        ];

        return view('admin.analytics.index', compact(
            'totalActiveJobs',
            'totalApplications',
            'stageStats',
            'popularJobs',
            'educationStats'
        ));
    }
}
