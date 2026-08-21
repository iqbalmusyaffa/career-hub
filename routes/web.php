<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $latestJobs = \App\Models\Job::where('status', 'active')->latest()->take(3)->get();
    return view('welcome', compact('latestJobs'));
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user && ($user->hasRole('Super Admin') || $user->hasRole('HR') || $user->hasRole('Company Owner'))) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\CandidateProfileController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\CompanyDirectoryController;

Route::get('/jobs', [JobListingController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}', [JobListingController::class, 'show'])->name('jobs.show');

// Public Company Directory & Company Vacancies Profile
Route::get('/companies', [CompanyDirectoryController::class, 'index'])->name('companies.index');
Route::get('/companies/{name}', [CompanyDirectoryController::class, 'show'])->name('companies.show');

Route::middleware(['auth', 'role:Candidate'])->group(function () {
    Route::post('/jobs/{id}/apply', [JobListingController::class, 'apply'])->name('jobs.apply');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
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

    // Candidate Online Test routes
    Route::get('/candidate/tests/{job}', [\App\Http\Controllers\CandidateTestController::class, 'show'])->name('candidate.tests.show');
    Route::post('/candidate/tests/{job}/submit', [\App\Http\Controllers\CandidateTestController::class, 'submit'])->name('candidate.tests.submit');
    Route::post('/candidate/tests/{job}/submit-external', [\App\Http\Controllers\CandidateTestController::class, 'submitExternal'])->name('candidate.tests.submit-external');

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
    Route::delete('/company-team/{id}', [\App\Http\Controllers\Admin\CompanyTeamController::class, 'destroy'])->name('company-team.destroy');

    // Multi-Branch Management
    Route::get('/company/branches', [\App\Http\Controllers\Admin\CompanyBranchController::class, 'index'])->name('admin.company.branches.index');
    Route::post('/company/branches', [\App\Http\Controllers\Admin\CompanyBranchController::class, 'store'])->name('admin.company.branches.store');
    Route::delete('/company/branches/{branch}', [\App\Http\Controllers\Admin\CompanyBranchController::class, 'destroy'])->name('admin.company.branches.destroy');

    // Visual Recruitment Analytics (Chart.js)
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');

    // Interactive Recruitment Calendar (FullCalendar.js)
    Route::get('/calendar', [\App\Http\Controllers\Admin\RecruitmentCalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [\App\Http\Controllers\Admin\RecruitmentCalendarController::class, 'events'])->name('calendar.events');

    // HR Cancellation Appeal Submission Route
    Route::post('/applications/{application}/cancel-acceptance', [\App\Http\Controllers\Admin\AcceptanceCancellationController::class, 'store'])->name('cancellation-tickets.store');

    // Super Admin Exclusive Control & Moderation
    Route::middleware('role:Super Admin')->group(function () {
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
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
        Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');

        // Super Admin Company & HR Role Request Approval Center
        Route::get('/role-requests', [\App\Http\Controllers\Admin\AdminRoleRequestController::class, 'index'])->name('admin.role-requests.index');
        Route::post('/role-requests/{id}/approve', [\App\Http\Controllers\Admin\AdminRoleRequestController::class, 'approve'])->name('admin.role-requests.approve');
        Route::post('/role-requests/{id}/reject', [\App\Http\Controllers\Admin\AdminRoleRequestController::class, 'reject'])->name('admin.role-requests.reject');

        // Super Admin Cancellation Tickets Approval
        Route::get('/cancellation-tickets', [\App\Http\Controllers\Admin\AcceptanceCancellationController::class, 'index'])->name('cancellation-tickets.index');
        Route::post('/cancellation-tickets/{ticket}/approve', [\App\Http\Controllers\Admin\AcceptanceCancellationController::class, 'approve'])->name('cancellation-tickets.approve');
        Route::post('/cancellation-tickets/{ticket}/reject', [\App\Http\Controllers\Admin\AcceptanceCancellationController::class, 'reject'])->name('cancellation-tickets.reject');
    });
});

Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirect'])->name('google.login');
    Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'callback'])->name('google.callback');
});

require __DIR__.'/auth.php';
