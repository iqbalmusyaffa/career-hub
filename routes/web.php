<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $latestJobs = \App\Models\Job::where('status', 'active')->latest()->take(3)->get();
    if ($latestJobs->isEmpty()) {
        $latestJobs = \App\Models\Job::latest()->take(3)->get();
    }
    
    // Perusahaan terkemuka berdasarkan lowongan aktif terbanyak
    $topCompanyNames = \App\Models\Job::where('status', 'active')
        ->where(function($q) {
            $q->whereNull('deadline')->orWhere('deadline', '>=', now()->startOfDay());
        })
        ->whereNotNull('company_name')
        ->where('company_name', '!=', '')
        ->select('company_name', \Illuminate\Support\Facades\DB::raw('count(*) as active_jobs_count'))
        ->groupBy('company_name')
        ->orderByDesc('active_jobs_count')
        ->take(6)
        ->get();

    $trustedCompanies = $topCompanyNames->map(function ($item) {
        $profile = \App\Models\CompanyProfile::where('company_name', $item->company_name)->first();
        return (object) [
            'company_name' => $item->company_name,
            'logo_path' => $profile ? $profile->logo_path : null,
            'is_verified' => $profile ? $profile->is_verified : true,
            'jobs_count' => $item->active_jobs_count,
        ];
    });

    // Fallback jika belum ada lowongan aktif yang terkumpul
    if ($trustedCompanies->isEmpty()) {
        $trustedCompanies = \App\Models\CompanyProfile::whereNotNull('company_name')
            ->where('company_name', '!=', '')
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($p) {
                return (object) [
                    'company_name' => $p->company_name,
                    'logo_path' => $p->logo_path,
                    'is_verified' => $p->is_verified,
                    'jobs_count' => \App\Models\Job::where('company_name', $p->company_name)->where('status', 'active')->count(),
                ];
            });
    }

    return view('welcome', compact('latestJobs', 'trustedCompanies'));
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user && ($user->hasRole('Super Admin') || $user->hasRole('HR') || $user->hasRole('Company Owner'))) {
        return redirect()->route('admin.dashboard');
    }

    if ($user && $user->hasRole('Mentor')) {
        return redirect()->route('mentor.dashboard');
    }

    $applications = \App\Models\Application::with(['job', 'offerLetter', 'messages'])->where('user_id', $user->id)->latest()->get();
    $totalApplications = $applications->count();
    $processingApplications = $applications->whereIn('status', ['pending', 'screening', 'test', 'interview', 'interview_hr', 'interview_user', 'background_check', 'offered'])->count();
    $acceptedApplications = $applications->where('status', 'accepted')->count();
    $rejectedApplications = $applications->where('status', 'rejected')->count();
    $recentApplications = $applications;
    $statusPopupApp = $applications->first(function($app) {
        $st = is_object($app->status) ? $app->status->value : (string)$app->status;
        return in_array($st, ['accepted', 'hired', 'offered', 'interview_hr', 'interview_user', 'interview', 'test', 'rejected']);
    });

    return view('dashboard', compact('totalApplications', 'processingApplications', 'acceptedApplications', 'rejectedApplications', 'recentApplications', 'statusPopupApp'));
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\CandidateProfileController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\CompanyDirectoryController;

Route::get('/jobs', [JobListingController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}', [JobListingController::class, 'show'])->name('jobs.show');

// Public Informational Pages (Help Center, Privacy Policy, Terms of Service, Guide)
Route::view('/faq', 'pages.faq')->name('pages.faq');
Route::view('/panduan', 'pages.guide')->name('pages.guide');
Route::view('/panduan/pelamar', 'pages.guide-candidate')->name('pages.guide.candidate');
Route::view('/panduan/penyelenggara', 'pages.guide-employer')->name('pages.guide.employer');
Route::view('/privacy-policy', 'pages.privacy')->name('pages.privacy');
Route::view('/terms-of-service', 'pages.terms')->name('pages.terms');

// Candidate Red Flag Report Submission
Route::post('/company-reports', [\App\Http\Controllers\CompanyReportController::class, 'store'])->middleware('auth')->name('company-reports.store');

// Public Company Directory & Company Vacancies Profile
Route::get('/companies', [CompanyDirectoryController::class, 'index'])->name('companies.index');
Route::get('/companies/{name}', [CompanyDirectoryController::class, 'show'])->name('companies.show');

Route::middleware(['auth', 'role:Candidate'])->group(function () {
    Route::post('/jobs/{id}/apply', [JobListingController::class, 'apply'])->name('jobs.apply');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/candidate', [CandidateProfileController::class, 'update'])->name('profile.candidate.update');
    
    // New routes for Candidate Details (Education, Experience, Skills)
    Route::get('/profile/candidate-details', [CandidateProfileController::class, 'editDetails'])->name('profile.candidate.details.edit');
    Route::post('/profile/candidate-details', [CandidateProfileController::class, 'updateDetails'])->name('profile.candidate.details.update');
    Route::post('/profile/candidate-details/upload-photo', [CandidateProfileController::class, 'uploadPhoto'])->name('profile.candidate.upload-photo');
    Route::post('/profile/candidate-details/upload-document', [CandidateProfileController::class, 'uploadDocument'])->name('profile.candidate.upload-document');
    Route::get('/profile/candidate/consent-template', [CandidateProfileController::class, 'downloadConsentTemplate'])->name('profile.candidate.consent.template');

    // Candidate Document Vault Routes
    Route::get('/profile/candidate-documents', [\App\Http\Controllers\CandidateDocumentController::class, 'index'])->name('profile.candidate.documents.index');
    Route::post('/profile/candidate-documents', [\App\Http\Controllers\CandidateDocumentController::class, 'store'])->name('profile.candidate.documents.store');
    Route::delete('/profile/candidate-documents/{document}', [\App\Http\Controllers\CandidateDocumentController::class, 'destroy'])->name('profile.candidate.documents.destroy');

    // Candidate Company Role Request Submission & Tracking
    Route::get('/profile/request-company-role', [\App\Http\Controllers\CompanyRoleRequestController::class, 'show'])->name('profile.role-request.show');
    Route::post('/profile/request-company-role', [\App\Http\Controllers\CompanyRoleRequestController::class, 'store'])->name('profile.role-request.store');

    // UMK 2026 Lookup & Cities API Route
    Route::get('/api/umk-lookup', [\App\Http\Controllers\Api\UmkController::class, 'lookup'])->name('api.umk.lookup');
    Route::get('/api/regions/cities', [\App\Http\Controllers\Api\UmkController::class, 'cities'])->name('api.regions.cities');
    // Bookmark & CV routes
    Route::post('/jobs/{job}/bookmark', [\App\Http\Controllers\BookmarkController::class, 'toggle'])->name('jobs.bookmark');
    Route::get('/saved-jobs', [\App\Http\Controllers\BookmarkController::class, 'index'])->name('saved-jobs.index');
    Route::get('/profile/cv/download', [\App\Http\Controllers\CvController::class, 'download'])->name('profile.cv.download');
    Route::get('/candidates/{userId}/cv/download', [\App\Http\Controllers\CvController::class, 'download'])->name('candidates.cv.download');
    Route::get('/candidate/cv-builder', [\App\Http\Controllers\CvController::class, 'builder'])->name('candidate.cv-builder');

    // Candidate Online Test routes
    Route::get('/candidate/tests/{job}', [\App\Http\Controllers\CandidateTestController::class, 'show'])->name('candidate.tests.show');
    Route::post('/candidate/tests/{job}/submit', [\App\Http\Controllers\CandidateTestController::class, 'submit'])->name('candidate.tests.submit');
    Route::post('/candidate/tests/{job}/submit-external', [\App\Http\Controllers\CandidateTestController::class, 'submitExternal'])->name('candidate.tests.submit-external');

    // Candidate Onboarding & Employee Data routes (Only for Accepted/Hired Candidates)
    Route::get('/candidate/onboarding/{application}', [\App\Http\Controllers\CandidateOnboardingController::class, 'create'])->name('candidate.onboarding.create');
    Route::post('/candidate/onboarding/{application}', [\App\Http\Controllers\CandidateOnboardingController::class, 'store'])->name('candidate.onboarding.store');

    // Digital Agreements & E-Signature routes
    Route::get('/candidate/agreements/{agreement}', [\App\Http\Controllers\ApplicationAgreementController::class, 'show'])->name('candidate.agreements.show');
    Route::post('/candidate/agreements/{agreement}/send-otp', [\App\Http\Controllers\ApplicationAgreementController::class, 'sendOtp'])->name('candidate.agreements.send-otp');
    Route::post('/candidate/agreements/{agreement}/sign', [\App\Http\Controllers\ApplicationAgreementController::class, 'sign'])->name('candidate.agreements.sign');
    Route::get('/agreements/{agreement}/download', [\App\Http\Controllers\ApplicationAgreementController::class, 'download'])->name('agreements.download');

    // Internship Certificate, Transcript, and Termination/Paklaring routes for candidate
    Route::get('/candidate/certificates/{certificate}', [\App\Http\Controllers\InternshipCertificateController::class, 'show'])->name('candidate.certificates.show');
    Route::get('/candidate/transcripts/{transcript}', [\App\Http\Controllers\InternshipTranscriptController::class, 'show'])->name('candidate.transcripts.show');

    // Candidate Internship Presensi & Daily Logbook Routes (Gambar 1 & 2)
    Route::get('/candidate/internship/logbook', [\App\Http\Controllers\CandidateLogbookController::class, 'index'])->name('candidate.logbook.index');
    Route::get('/candidate/internship/logbook/{date}', [\App\Http\Controllers\CandidateLogbookController::class, 'show'])->name('candidate.logbook.show');
    Route::post('/candidate/internship/logbook', [\App\Http\Controllers\CandidateLogbookController::class, 'store'])->name('candidate.logbook.store');
    Route::get('/candidate/internship/evaluation', [\App\Http\Controllers\CandidateLogbookController::class, 'evaluation'])->name('candidate.logbook.evaluation');
    Route::get('/candidate/internship/unlock-requests/{id}/pdf', [\App\Http\Controllers\CandidateLogbookController::class, 'downloadUnlockPdf'])->name('candidate.unlock-requests.pdf');

    // Dedicated Mentor Role Workspace, ACC Absensi, & Final Performance Rating Routes
    Route::get('/mentor/dashboard', [\App\Http\Controllers\Mentor\MentorLogbookController::class, 'dashboard'])->name('mentor.dashboard');
    Route::get('/mentor/logbooks', [\App\Http\Controllers\Mentor\MentorLogbookController::class, 'index'])->name('mentor.logbooks.index');
    Route::post('/mentor/logbooks/batch', [\App\Http\Controllers\Mentor\MentorLogbookController::class, 'storeBatch'])->name('mentor.logbooks.batch.store');
    Route::get('/mentor/logbooks/intern/{internId}', [\App\Http\Controllers\Mentor\MentorLogbookController::class, 'internLogbooks'])->name('mentor.logbooks.intern');
    Route::get('/mentor/logbooks/{id}', [\App\Http\Controllers\Mentor\MentorLogbookController::class, 'show'])->name('mentor.logbooks.show');
    Route::post('/mentor/logbooks/{id}/approve', [\App\Http\Controllers\Mentor\MentorLogbookController::class, 'approve'])->name('mentor.logbooks.approve');
    Route::post('/mentor/logbooks/{id}/reject', [\App\Http\Controllers\Mentor\MentorLogbookController::class, 'reject'])->name('mentor.logbooks.reject');
    Route::get('/mentor/evaluations/create/{internId}', [\App\Http\Controllers\Mentor\MentorEvaluationController::class, 'create'])->name('mentor.evaluations.create');
    Route::post('/mentor/evaluations', [\App\Http\Controllers\Mentor\MentorEvaluationController::class, 'store'])->name('mentor.evaluations.store');

    // Mentor Unlock Requests for Locked Dates
    Route::get('/mentor/unlock-requests', [\App\Http\Controllers\Mentor\MentorUnlockRequestController::class, 'index'])->name('mentor.unlock-requests.index');
    Route::get('/mentor/unlock-requests/create', [\App\Http\Controllers\Mentor\MentorUnlockRequestController::class, 'create'])->name('mentor.unlock-requests.create');
    Route::post('/mentor/unlock-requests', [\App\Http\Controllers\Mentor\MentorUnlockRequestController::class, 'store'])->name('mentor.unlock-requests.store');
    Route::get('/mentor/unlock-requests/{id}/pdf', [\App\Http\Controllers\Mentor\MentorUnlockRequestController::class, 'downloadPdf'])->name('mentor.unlock-requests.pdf');

    // HR & Mentor Settings for Internship Periods, Government Holidays, & Custom Company Holidays
    Route::get('/mentor/settings', [\App\Http\Controllers\Mentor\MentorSettingsController::class, 'index'])->name('mentor.settings.index');
    Route::post('/mentor/settings/holidays/{id}/override', [\App\Http\Controllers\Mentor\MentorSettingsController::class, 'toggleOverride'])->name('mentor.settings.holidays.override');
    Route::post('/mentor/settings/holidays/company', [\App\Http\Controllers\Mentor\MentorSettingsController::class, 'storeCompanyHoliday'])->name('mentor.settings.holidays.company.store');
    Route::delete('/mentor/settings/holidays/company/{id}', [\App\Http\Controllers\Mentor\MentorSettingsController::class, 'deleteCompanyHoliday'])->name('mentor.settings.holidays.company.delete');
    Route::post('/mentor/settings/periods', [\App\Http\Controllers\Mentor\MentorSettingsController::class, 'storePeriod'])->name('mentor.settings.periods.store');
    Route::get('/candidate/terminations/{termination}', [\App\Http\Controllers\EmployeeTerminationController::class, 'show'])->name('candidate.terminations.show');
    Route::post('/candidate/terminations/{termination}/send-otp', [\App\Http\Controllers\EmployeeTerminationController::class, 'sendOtp'])->name('candidate.terminations.send-otp');
    Route::post('/candidate/terminations/{termination}/sign', [\App\Http\Controllers\EmployeeTerminationController::class, 'sign'])->name('candidate.terminations.sign');

    // Offer Letter & Live Chat routes
    Route::get('/offer-letters/{offerLetter}/download', [\App\Http\Controllers\Admin\OfferLetterController::class, 'download'])->name('offer-letters.download');
    Route::post('/offer-letters/{offerLetter}/respond', [\App\Http\Controllers\Admin\OfferLetterController::class, 'respond'])->name('offer-letters.respond');
    Route::get('/applications/{application}/messages', [\App\Http\Controllers\ApplicationChatController::class, 'fetchMessages'])->name('applications.messages.fetch');
    Route::post('/applications/{application}/messages', [\App\Http\Controllers\ApplicationChatController::class, 'sendMessage'])->name('applications.messages.send');

    // Salary Benchmark
    Route::get('/salary-benchmark', [\App\Http\Controllers\SalaryBenchmarkController::class, 'index'])->name('salary-benchmark.index');

    // In-App Bell Notifications API
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // PDF CV Alias Routes
    Route::get('/applications/{application}/cv/ats', function(\App\Models\Application $application) {
        $user = $application->user;
        $profile = $user->candidateProfile;
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cv_ats', compact('user', 'profile'))->setPaper('a4', 'portrait');
        return $pdf->download('CV_ATS_' . \Illuminate\Support\Str::slug($user->name) . '.pdf');
    })->name('applications.cv.ats');

    Route::get('/applications/{application}/cv/creative', function(\App\Models\Application $application) {
        $user = $application->user;
        $profile = $user->candidateProfile;
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cv_creative', compact('user', 'profile'))->setPaper('a4', 'portrait');
        return $pdf->download('CV_Creative_' . \Illuminate\Support\Str::slug($user->name) . '.pdf');
    })->name('applications.cv.creative');
});

Route::middleware(['auth', 'role:HR|Super Admin|Company Owner'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('jobs', JobController::class);
    Route::get('/company-profile', [\App\Http\Controllers\Admin\CompanyProfileController::class, 'edit'])->name('company.profile.edit');
    Route::post('/company-profile', [\App\Http\Controllers\Admin\CompanyProfileController::class, 'update'])->name('company.profile.update');
    Route::get('/applications/export/csv', [ApplicationController::class, 'exportCsv'])->name('applications.export.csv');
    Route::get('/applications/export/pdf', [ApplicationController::class, 'exportPdf'])->name('applications.export.pdf');
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/applications/{id}/status', [ApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
    Route::post('/applications/{id}/schedule-interview', [\App\Http\Controllers\Admin\InterviewController::class, 'store'])->name('applications.schedule-interview');

    // Offer Letter Builder for HR
    Route::get('/applications/{application}/offer-letter/create', [\App\Http\Controllers\Admin\OfferLetterController::class, 'create'])->name('applications.offer-letter.create');
    Route::post('/applications/{application}/offer-letter', [\App\Http\Controllers\Admin\OfferLetterController::class, 'store'])->name('applications.offer-letter.store');

    // Generate Candidate PDF CV (ATS & Creative Formats)
    Route::get('/applications/{application}/cv/ats', function(\App\Models\Application $application) {
        $user = $application->user;
        $profile = $user->candidateProfile;
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cv_ats', compact('user', 'profile'))->setPaper('a4', 'portrait');
        return $pdf->download('CV_ATS_' . \Illuminate\Support\Str::slug($user->name) . '.pdf');
    })->name('applications.cv.ats');

    Route::get('/applications/{application}/cv/creative', function(\App\Models\Application $application) {
        $user = $application->user;
        $profile = $user->candidateProfile;
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cv_creative', compact('user', 'profile'))->setPaper('a4', 'portrait');
        return $pdf->download('CV_Creative_' . \Illuminate\Support\Str::slug($user->name) . '.pdf');
    })->name('applications.cv.creative');

    Route::post('/applications/{application}/verify-onboarding', [\App\Http\Controllers\CandidateOnboardingController::class, 'verifyByHr'])->name('applications.verify-onboarding');

    // Super Admin Impersonation Exit
    Route::post('/impersonate/leave', [\App\Http\Controllers\Admin\UserController::class, 'leaveImpersonation'])->name('admin.impersonate.leave');

    // HR Digital Agreement Builder routes
    Route::get('/applications/{application}/agreements/create', [\App\Http\Controllers\ApplicationAgreementController::class, 'create'])->name('applications.agreements.create');
    Route::post('/applications/{application}/agreements', [\App\Http\Controllers\ApplicationAgreementController::class, 'store'])->name('applications.agreements.store');

    // HR Internship Certificate Builder routes
    Route::get('/applications/{application}/certificates/create', [\App\Http\Controllers\InternshipCertificateController::class, 'create'])->name('applications.certificates.create');
    Route::post('/applications/{application}/certificates', [\App\Http\Controllers\InternshipCertificateController::class, 'store'])->name('applications.certificates.store');

    // HR Internship Academic Transcript Builder routes
    Route::get('/applications/{application}/transcripts/create', [\App\Http\Controllers\InternshipTranscriptController::class, 'create'])->name('applications.transcripts.create');
    Route::post('/applications/{application}/transcripts', [\App\Http\Controllers\InternshipTranscriptController::class, 'store'])->name('applications.transcripts.store');

    // HR Termination & Recommendation Letter Builder routes
    Route::get('/applications/{application}/terminations/create', [\App\Http\Controllers\EmployeeTerminationController::class, 'create'])->name('applications.terminations.create');
    Route::post('/applications/{application}/terminations', [\App\Http\Controllers\EmployeeTerminationController::class, 'store'])->name('applications.terminations.store');

    // Online Test Builder for HR / Admin
    Route::get('/jobs/{job}/test', [\App\Http\Controllers\Admin\JobTestController::class, 'edit'])->name('jobs.test.edit');
    Route::post('/jobs/{job}/test', [\App\Http\Controllers\Admin\JobTestController::class, 'update'])->name('jobs.test.update');
    Route::get('/jobs/{job}/test/export', [\App\Http\Controllers\Admin\JobTestController::class, 'export'])->name('jobs.test.export');
    Route::post('/jobs/{job}/test/import', [\App\Http\Controllers\Admin\JobTestController::class, 'import'])->name('jobs.test.import');

    // Candidate Evaluation & HR Scoring Sheet
    Route::post('/applications/{application}/evaluations', [\App\Http\Controllers\Admin\CandidateEvaluationController::class, 'store'])->name('applications.evaluations.store');
    Route::delete('/applications/{application}/evaluations/{evaluation}', [\App\Http\Controllers\Admin\CandidateEvaluationController::class, 'destroy'])->name('applications.evaluations.destroy');

    // HR Internal Confidential Notes
    Route::post('/applications/{application}/internal-notes', [\App\Http\Controllers\Admin\HrInternalNoteController::class, 'store'])->name('applications.internal-notes.store');
    Route::delete('/applications/{application}/internal-notes/{note}', [\App\Http\Controllers\Admin\HrInternalNoteController::class, 'destroy'])->name('applications.internal-notes.destroy');

    // HR Email Template Auto-Sender
    Route::post('/applications/{application}/send-email-template', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'sendTemplate'])->name('applications.send-email-template');

    // Dedicated HR Email Template Management Module
    Route::resource('email-templates', \App\Http\Controllers\Admin\EmailTemplateManagementController::class)->except(['create', 'show', 'edit']);
    Route::post('/email-templates/broadcast', [\App\Http\Controllers\Admin\EmailTemplateManagementController::class, 'broadcast'])->name('email-templates.broadcast');

    // Job Report Export (Excel & PDF per job)
    Route::get('/jobs/{job}/export/excel', [\App\Http\Controllers\Admin\JobReportExportController::class, 'exportExcel'])->name('jobs.export.excel');
    Route::get('/jobs/{job}/export/pdf', [\App\Http\Controllers\Admin\JobReportExportController::class, 'exportPdf'])->name('jobs.export.pdf');

    // HR Team Management
    Route::get('/company-team', [\App\Http\Controllers\Admin\CompanyTeamController::class, 'index'])->name('company-team.index');
    Route::post('/company-team', [\App\Http\Controllers\Admin\CompanyTeamController::class, 'store'])->name('company-team.store');
    Route::post('/company-team/{id}/approve-co-owner', [\App\Http\Controllers\Admin\CompanyTeamController::class, 'approveCoOwner'])->name('company-team.approve-co-owner');
    Route::post('/company-team/{id}/reject-co-owner', [\App\Http\Controllers\Admin\CompanyTeamController::class, 'rejectCoOwner'])->name('company-team.reject-co-owner');
    Route::delete('/company-team/{id}', [\App\Http\Controllers\Admin\CompanyTeamController::class, 'destroy'])->name('company-team.destroy');

    // Multi-Branch Management
    Route::get('/company/branches', [\App\Http\Controllers\Admin\CompanyBranchController::class, 'index'])->name('admin.company.branches.index');
    Route::post('/company/branches', [\App\Http\Controllers\Admin\CompanyBranchController::class, 'store'])->name('admin.company.branches.store');
    Route::delete('/company/branches/{branch}', [\App\Http\Controllers\Admin\CompanyBranchController::class, 'destroy'])->name('admin.company.branches.destroy');

    // Visual Recruitment Analytics (Chart.js)
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');

    // Interactive Recruitment Calendar (FullCalendar.js & Google Calendar Sync)
    Route::get('/calendar', [\App\Http\Controllers\Admin\RecruitmentCalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [\App\Http\Controllers\Admin\RecruitmentCalendarController::class, 'events'])->name('calendar.events');
    Route::get('/calendar/feed.ics', [\App\Http\Controllers\Admin\RecruitmentCalendarController::class, 'exportIcs'])->name('calendar.feed');

    // HR Cancellation Appeal Submission Route
    Route::post('/applications/{application}/cancel-acceptance', [\App\Http\Controllers\Admin\AcceptanceCancellationController::class, 'store'])->name('cancellation-tickets.store');

    // Bulk Action Pipeline
    Route::post('/applications/bulk-status', [\App\Http\Controllers\Admin\ApplicationController::class, 'bulkUpdateStatus'])->name('applications.bulk-status');

    // Structured Interview Scorecard
    Route::post('/applications/{application}/scorecards', [\App\Http\Controllers\Admin\InterviewScorecardController::class, 'store'])->name('applications.scorecards.store');
    Route::delete('/scorecards/{scorecard}', [\App\Http\Controllers\Admin\InterviewScorecardController::class, 'destroy'])->name('scorecards.destroy');

    // Company Team Activity Audit Logs
    Route::get('/company-team/audit-logs', [\App\Http\Controllers\Admin\CompanyActivityAuditController::class, 'index'])->name('company-team.audit-logs');

    // Headcount Budget & Recruitment Planning Hub
    Route::get('/headcount-budgets', [\App\Http\Controllers\Admin\HeadcountBudgetController::class, 'index'])->name('headcount-budgets.index');
    Route::post('/headcount-budgets', [\App\Http\Controllers\Admin\HeadcountBudgetController::class, 'store'])->name('headcount-budgets.store');
    Route::delete('/headcount-budgets/{headcount_budget}', [\App\Http\Controllers\Admin\HeadcountBudgetController::class, 'destroy'])->name('headcount-budgets.destroy');

    // HR Custom Report Builder (Excel & PDF Advanced Export)
    Route::get('/reports/builder', [\App\Http\Controllers\Admin\CustomReportBuilderController::class, 'index'])->name('reports.builder.index');
    Route::get('/reports/export', [\App\Http\Controllers\Admin\CustomReportBuilderController::class, 'export'])->name('reports.builder.export');

    // Dynamic System Flow & Auto-Generated ERD Visualizer
    Route::get('/system-flow', [\App\Http\Controllers\Admin\SystemFlowController::class, 'index'])->name('system-flow.index');
    Route::get('/system-flow-alias', [\App\Http\Controllers\Admin\SystemFlowController::class, 'index'])->name('system-flow');

    // Super Admin Exclusive Control & Moderation
    Route::middleware('role:Super Admin')->group(function () {
        Route::get('/users/export', [\App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
        Route::post('/users/bulk-action', [\App\Http\Controllers\Admin\UserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/update-role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.update-role');
        Route::post('/users/{user}/send-password-reset', [\App\Http\Controllers\Admin\UserController::class, 'sendPasswordReset'])->name('users.send-password-reset');
        Route::post('/users/{user}/toggle-email-verification', [\App\Http\Controllers\Admin\UserController::class, 'toggleEmailVerification'])->name('users.toggle-email-verification');
        Route::post('/users/{user}/impersonate', [\App\Http\Controllers\Admin\UserController::class, 'impersonate'])->name('users.impersonate');
        Route::patch('/users/{user}/toggle-suspend', [\App\Http\Controllers\Admin\UserController::class, 'toggleSuspend'])->name('users.toggle-suspend');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/companies', [\App\Http\Controllers\Admin\UserController::class, 'companies'])->name('companies.index');
        Route::put('/companies/{company}', [\App\Http\Controllers\Admin\UserController::class, 'updateCompany'])->name('companies.update');
        Route::delete('/companies/{company}', [\App\Http\Controllers\Admin\UserController::class, 'destroyCompany'])->name('companies.destroy');
        Route::patch('/companies/{company}/toggle-verify', [\App\Http\Controllers\Admin\UserController::class, 'toggleCompanyVerify'])->name('companies.toggle-verify');
        Route::patch('/companies/{company}/toggle-suspend', [\App\Http\Controllers\Admin\UserController::class, 'toggleCompanySuspend'])->name('companies.toggle-suspend');

        // Dynamic SMTP Email Settings
        Route::get('/settings/smtp', [\App\Http\Controllers\Admin\SmtpSettingsController::class, 'edit'])->name('settings.smtp.edit');
        Route::post('/settings/smtp', [\App\Http\Controllers\Admin\SmtpSettingsController::class, 'update'])->name('settings.smtp.update');
        Route::post('/settings/smtp/test', [\App\Http\Controllers\Admin\SmtpSettingsController::class, 'testEmail'])->name('settings.smtp.test');

        // Master Site Branding, Logo, Favicon & Global SEO Engine
        Route::get('/settings/seo', [\App\Http\Controllers\Admin\SeoBrandingController::class, 'edit'])->name('settings.seo.edit');
        Route::post('/settings/seo', [\App\Http\Controllers\Admin\SeoBrandingController::class, 'update'])->name('settings.seo.update');

        // Audit Logs
        Route::get('/audit-logs/export', [\App\Http\Controllers\Admin\AuditLogController::class, 'export'])->name('audit-logs.export');
        Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');

        // Super Admin Company & HR Role Request Approval Center
        Route::get('/role-requests', [\App\Http\Controllers\Admin\AdminRoleRequestController::class, 'index'])->name('admin.role-requests.index');
        Route::post('/role-requests/{id}/approve', [\App\Http\Controllers\Admin\AdminRoleRequestController::class, 'approve'])->name('admin.role-requests.approve');
        Route::post('/role-requests/{id}/reject', [\App\Http\Controllers\Admin\AdminRoleRequestController::class, 'reject'])->name('admin.role-requests.reject');

        // Super Admin Cancellation Tickets Approval
        Route::get('/cancellation-tickets', [\App\Http\Controllers\Admin\AcceptanceCancellationController::class, 'index'])->name('cancellation-tickets.index');
        Route::post('/cancellation-tickets/{ticket}/approve', [\App\Http\Controllers\Admin\AcceptanceCancellationController::class, 'approve'])->name('cancellation-tickets.approve');
        Route::post('/cancellation-tickets/{ticket}/reject', [\App\Http\Controllers\Admin\AcceptanceCancellationController::class, 'reject'])->name('cancellation-tickets.reject');

        // System Announcements Broadcast Center
        Route::get('/announcements', [\App\Http\Controllers\Admin\SystemAnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [\App\Http\Controllers\Admin\SystemAnnouncementController::class, 'store'])->name('announcements.store');
        Route::patch('/announcements/{announcement}/toggle', [\App\Http\Controllers\Admin\SystemAnnouncementController::class, 'toggleActive'])->name('announcements.toggle');
        Route::delete('/announcements/{announcement}', [\App\Http\Controllers\Admin\SystemAnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // Anti-Fraud Blacklist Manager
        Route::get('/blacklists', [\App\Http\Controllers\Admin\BlacklistController::class, 'index'])->name('blacklists.index');
        Route::post('/blacklists', [\App\Http\Controllers\Admin\BlacklistController::class, 'store'])->name('blacklists.store');
        Route::delete('/blacklists/{blacklist}', [\App\Http\Controllers\Admin\BlacklistController::class, 'destroy'])->name('blacklists.destroy');

        // Super Admin Moderasi & ACC Laporan Red Flag Perusahaan
        Route::get('/company-reports', [\App\Http\Controllers\Admin\CompanyReportController::class, 'index'])->name('company-reports.index');
        Route::patch('/company-reports/{report}/status', [\App\Http\Controllers\Admin\CompanyReportController::class, 'updateStatus'])->name('company-reports.updateStatus');
        Route::delete('/company-reports/{report}', [\App\Http\Controllers\Admin\CompanyReportController::class, 'destroy'])->name('company-reports.destroy');

        // FITUR A: Super Admin Pusat Tiket Buka Kunci Presensi
        Route::get('/internship-unlocks', [\App\Http\Controllers\Admin\InternshipUnlockRequestController::class, 'index'])->name('internship-unlocks.index');
        Route::get('/internship-unlocks/{id}', [\App\Http\Controllers\Admin\InternshipUnlockRequestController::class, 'show'])->name('internship-unlocks.show');
        Route::post('/internship-unlocks/{id}/approve', [\App\Http\Controllers\Admin\InternshipUnlockRequestController::class, 'approve'])->name('internship-unlocks.approve');
        Route::post('/internship-unlocks/{id}/reject', [\App\Http\Controllers\Admin\InternshipUnlockRequestController::class, 'reject'])->name('internship-unlocks.reject');
        Route::get('/internship-unlocks/{id}/pdf', [\App\Http\Controllers\Admin\InternshipUnlockRequestController::class, 'downloadPdf'])->name('internship-unlocks.pdf');

        // FITUR B: Master Pengaturan Kebijakan Presensi & GPS Global
        Route::get('/attendance-settings', [\App\Http\Controllers\Admin\GlobalAttendanceSettingsController::class, 'index'])->name('attendance-settings.index');
        Route::post('/attendance-settings', [\App\Http\Controllers\Admin\GlobalAttendanceSettingsController::class, 'update'])->name('attendance-settings.update');

        // FITUR C: Dashboard Monitoring Magang Lintas Mitra
        Route::get('/internship-monitor', [\App\Http\Controllers\Admin\CrossCompanyInternshipMonitorController::class, 'index'])->name('internship-monitor.index');

        // FITUR D: Master Manajemen & Revokasi Sertifikat Magang
        Route::get('/certificates', [\App\Http\Controllers\Admin\AdminCertificateController::class, 'index'])->name('certificates.index');
        Route::post('/certificates/{id}/revoke', [\App\Http\Controllers\Admin\AdminCertificateController::class, 'revoke'])->name('certificates.revoke');
        Route::post('/certificates/{id}/restore', [\App\Http\Controllers\Admin\AdminCertificateController::class, 'restore'])->name('certificates.restore');
    });

    // Public & Authenticated Certificate Verification Portal (FITUR D)
    Route::get('/verify-certificate/{code?}', [\App\Http\Controllers\PublicCertificateVerificationController::class, 'verify'])->name('certificates.verify.public');

    // Alias routes for PDF CV Download (both with and without admin. prefix)
    Route::get('/applications/{application}/cv/ats', function(\App\Models\Application $application) {
        $user = $application->user;
        $profile = $user->candidateProfile;
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cv_ats', compact('user', 'profile'))->setPaper('a4', 'portrait');
        return $pdf->download('CV_ATS_' . \Illuminate\Support\Str::slug($user->name) . '.pdf');
    })->name('applications.cv.ats');

    Route::get('/applications/{application}/cv/creative', function(\App\Models\Application $application) {
        $user = $application->user;
        $profile = $user->candidateProfile;
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cv_creative', compact('user', 'profile'))->setPaper('a4', 'portrait');
        return $pdf->download('CV_Creative_' . \Illuminate\Support\Str::slug($user->name) . '.pdf');
    })->name('applications.cv.creative');
});

// Standalone Public Certificate Verification Route (for guests / universal scan)
Route::get('/verify-certificate/{code?}', [\App\Http\Controllers\PublicCertificateVerificationController::class, 'verify'])->name('certificates.verify.public');

Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirect'])->name('google.login');
    Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'callback'])->name('google.callback');
});

require __DIR__.'/auth.php';
