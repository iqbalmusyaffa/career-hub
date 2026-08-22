<?php

use App\Http\Controllers\Api\Admin\AdminApplicationApiController;
use App\Http\Controllers\Api\Admin\AdminCompanyTeamApiController;
use App\Http\Controllers\Api\Admin\AdminJobApiController;
use App\Http\Controllers\Api\Admin\AdminSuperApiController;
use App\Http\Controllers\Api\ApplicationApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CandidateDocumentApiController;
use App\Http\Controllers\Api\CandidateProfileApiController;
use App\Http\Controllers\Api\CandidateTestApiController;
use App\Http\Controllers\Api\JobApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\UmkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web-Karir Secure REST API Routes (v1)
|--------------------------------------------------------------------------
|
| Secure API endpoints configured with Laravel Sanctum, Rate Limiting,
| and Spatie Role-Based Access Control.
|
*/

Route::prefix('v1')->middleware(['throttle:api'])->group(function () {

    // ==========================================
    // 1. AUTHENTICATION & ROLE REQUEST API
    // ==========================================
    Route::prefix('auth')->middleware(['throttle:10,1'])->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/role-request', [AuthController::class, 'requestCompanyRole']);
        });
    });

    // ==========================================
    // 2. PUBLIC JOBS, COMPANIES & SEARCH API
    // ==========================================
    Route::get('/jobs', [JobApiController::class, 'index']);
    Route::get('/jobs/{id}', [JobApiController::class, 'show']);
    Route::get('/companies', [JobApiController::class, 'companies']);
    Route::get('/companies/{name}', [JobApiController::class, 'companyShow']);

    // ==========================================
    // 3. REGIONAL UMK 2026, CITIES & MAJORS API
    // ==========================================
    Route::get('/umk-lookup', [UmkController::class, 'lookup']);
    Route::get('/regions/cities', [UmkController::class, 'cities']);
    Route::get('/regions/majors', [UmkController::class, 'majors']);

    // ==========================================
    // 4. CANDIDATE PROTECTED APIS (auth:sanctum)
    // ==========================================
    Route::middleware(['auth:sanctum'])->group(function () {

        // Job Bookmarking & Saved Jobs
        Route::post('/jobs/{id}/bookmark', [JobApiController::class, 'toggleBookmark']);
        Route::get('/candidate/saved-jobs', [JobApiController::class, 'savedJobs']);

        // Candidate Profile & Photo Upload
        Route::get('/candidate/profile', [CandidateProfileApiController::class, 'show']);
        Route::put('/candidate/profile', [CandidateProfileApiController::class, 'update']);
        Route::post('/candidate/profile/photo', [CandidateProfileApiController::class, 'uploadPhoto']);

        // Candidate Document Vault (KTP, Ijazah, Certs, etc.)
        Route::get('/candidate/documents', [CandidateDocumentApiController::class, 'index']);
        Route::post('/candidate/documents', [CandidateDocumentApiController::class, 'store']);
        Route::delete('/candidate/documents/{id}', [CandidateDocumentApiController::class, 'destroy']);

        // Candidate Job Application & Live Chat
        Route::post('/jobs/{id}/apply', [ApplicationApiController::class, 'apply']);
        Route::get('/candidate/applications', [ApplicationApiController::class, 'myApplications']);
        Route::get('/applications/{id}/messages', [ApplicationApiController::class, 'fetchMessages']);
        Route::post('/applications/{id}/messages', [ApplicationApiController::class, 'sendMessage']);

        // Candidate Online Tests & Offer Letters
        Route::get('/candidate/tests/{jobId}', [CandidateTestApiController::class, 'showTest']);
        Route::post('/candidate/tests/{jobId}/submit', [CandidateTestApiController::class, 'submitTest']);
        Route::post('/candidate/offer-letters/{id}/respond', [CandidateTestApiController::class, 'respondOffer']);

        // Candidate Onboarding & Employee Data (Only for Accepted/Hired Candidates)
        Route::get('/candidate/applications/{id}/onboarding', [ApplicationApiController::class, 'getOnboarding']);
        Route::post('/candidate/applications/{id}/onboarding', [ApplicationApiController::class, 'storeOnboarding']);

        // Candidate Digital Contracts, Certificates, Transcripts & Terminations API
        Route::get('/candidate/agreements', [ApplicationApiController::class, 'myAgreements']);
        Route::get('/candidate/certificates', [ApplicationApiController::class, 'myCertificates']);
        Route::get('/candidate/transcripts', [ApplicationApiController::class, 'myTranscripts']);
        Route::get('/candidate/terminations', [ApplicationApiController::class, 'myTerminations']);

        // In-App Bell Notifications
        Route::get('/notifications', [NotificationApiController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationApiController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationApiController::class, 'markAllAsRead']);
    });

    // ==========================================
    // 5. HR & EMPLOYER APIS (role:HR|Company Owner|Super Admin)
    // ==========================================
    Route::middleware(['auth:sanctum', 'role:HR|Super Admin|Company Owner'])->prefix('admin')->group(function () {

        // Job Postings & Company Management
        Route::get('/jobs', [AdminJobApiController::class, 'index']);
        Route::post('/jobs', [AdminJobApiController::class, 'store']);
        Route::put('/jobs/{id}', [AdminJobApiController::class, 'update']);
        Route::delete('/jobs/{id}', [AdminJobApiController::class, 'destroy']);
        Route::get('/jobs/{id}/test', [AdminJobApiController::class, 'manageTest']);
        Route::post('/jobs/{id}/test', [AdminJobApiController::class, 'updateTest']);
        Route::get('/company-profile', [AdminJobApiController::class, 'companyProfile']);

        // Recruitment Pipeline Management
        Route::get('/applications', [AdminApplicationApiController::class, 'index']);
        Route::get('/applications/{id}', [AdminApplicationApiController::class, 'show']);
        Route::patch('/applications/{id}/status', [AdminApplicationApiController::class, 'updateStatus']);
        Route::post('/applications/bulk-status', [AdminApplicationApiController::class, 'bulkStatus']);
        Route::post('/applications/{id}/schedule-interview', [AdminApplicationApiController::class, 'scheduleInterview']);
        Route::post('/applications/{id}/evaluations', [AdminApplicationApiController::class, 'storeEvaluation']);
        Route::get('/applications/{id}/scorecards', [AdminApplicationApiController::class, 'getScorecards']);
        Route::post('/applications/{id}/scorecards', [AdminApplicationApiController::class, 'storeScorecard']);
        Route::post('/applications/{id}/offer-letter', [AdminApplicationApiController::class, 'issueOfferLetter']);
        Route::post('/applications/{id}/cancel-acceptance', [AdminApplicationApiController::class, 'cancelAcceptance']);
        Route::post('/applications/{id}/verify-onboarding', [AdminApplicationApiController::class, 'verifyOnboarding']);
        Route::post('/applications/{id}/agreements', [AdminApplicationApiController::class, 'createAgreement']);
        Route::post('/applications/{id}/certificates', [AdminApplicationApiController::class, 'createCertificate']);
        Route::post('/applications/{id}/transcripts', [AdminApplicationApiController::class, 'createTranscript']);
        Route::post('/applications/{id}/terminations', [AdminApplicationApiController::class, 'createTermination']);
        Route::post('/applications/{id}/internal-notes', [AdminApplicationApiController::class, 'storeInternalNote']);

        // Company Team & Multi-Branch Offices
        Route::get('/company-team', [AdminCompanyTeamApiController::class, 'teamIndex']);
        Route::post('/company-team', [AdminCompanyTeamApiController::class, 'teamStore']);
        Route::delete('/company-team/{id}', [AdminCompanyTeamApiController::class, 'teamDestroy']);
        Route::get('/company-team/audit-logs', [\App\Http\Controllers\Admin\CompanyActivityAuditController::class, 'index']);
        Route::get('/company/branches', [AdminCompanyTeamApiController::class, 'branchesIndex']);
        Route::post('/company/branches', [AdminCompanyTeamApiController::class, 'branchesStore']);
        Route::delete('/company/branches/{id}', [AdminCompanyTeamApiController::class, 'branchesDestroy']);

        // Headcount Budget & Recruitment Planning
        Route::get('/headcount-budgets', [\App\Http\Controllers\Admin\HeadcountBudgetController::class, 'index']);
        Route::post('/headcount-budgets', [\App\Http\Controllers\Admin\HeadcountBudgetController::class, 'store']);
        Route::get('/reports/custom', [\App\Http\Controllers\Admin\CustomReportBuilderController::class, 'export']);

        // HR Email Templates & Recruitment Calendar Events
        Route::get('/email-templates', [AdminCompanyTeamApiController::class, 'templatesIndex']);
        Route::post('/email-templates', [AdminCompanyTeamApiController::class, 'templatesStore']);
        Route::get('/calendar/events', [AdminSuperApiController::class, 'calendarEvents']);
    });

    // ==========================================
    // 6. SUPER ADMIN EXCLUSIVE APIS (role:Super Admin)
    // ==========================================
    Route::middleware(['auth:sanctum', 'role:Super Admin'])->prefix('admin')->group(function () {
        Route::get('/users', [AdminSuperApiController::class, 'users']);
        Route::patch('/users/{id}/toggle-suspend', [AdminSuperApiController::class, 'toggleUserSuspend']);

        Route::get('/companies', [AdminSuperApiController::class, 'companies']);
        Route::patch('/companies/{id}/toggle-verify', [AdminSuperApiController::class, 'toggleCompanyVerify']);

        Route::get('/role-requests', [AdminSuperApiController::class, 'roleRequests']);
        Route::post('/role-requests/{id}/approve', [AdminSuperApiController::class, 'approveRoleRequest']);

        Route::get('/audit-logs', [AdminSuperApiController::class, 'auditLogs']);
        Route::get('/analytics', [AdminSuperApiController::class, 'analytics']);
        Route::get('/announcements', [AdminSuperApiController::class, 'announcements']);
        Route::post('/announcements', [AdminSuperApiController::class, 'storeAnnouncement']);
        Route::get('/blacklists', [AdminSuperApiController::class, 'blacklists']);
        Route::post('/blacklists', [AdminSuperApiController::class, 'storeBlacklist']);

        Route::get('/cancellation-tickets', [AdminSuperApiController::class, 'cancellationTickets']);
        Route::post('/cancellation-tickets/{id}/approve', [AdminSuperApiController::class, 'approveCancellationTicket']);
        Route::post('/cancellation-tickets/{id}/reject', [AdminSuperApiController::class, 'rejectCancellationTicket']);
    });

});
