<?php

use App\Http\Controllers\Api\Admin\AdminApplicationApiController;
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
    // 2. PUBLIC JOBS & SEARCH API
    // ==========================================
    Route::get('/jobs', [JobApiController::class, 'index']);
    Route::get('/jobs/{id}', [JobApiController::class, 'show']);

    // ==========================================
    // 3. REGIONAL UMK 2026 & CITIES API
    // ==========================================
    Route::get('/umk-lookup', [UmkController::class, 'lookup']);
    Route::get('/regions/cities', [UmkController::class, 'cities']);

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
        Route::get('/company-profile', [AdminJobApiController::class, 'companyProfile']);

        // Recruitment Pipeline Management
        Route::get('/applications', [AdminApplicationApiController::class, 'index']);
        Route::get('/applications/{id}', [AdminApplicationApiController::class, 'show']);
        Route::patch('/applications/{id}/status', [AdminApplicationApiController::class, 'updateStatus']);
        Route::post('/applications/{id}/schedule-interview', [AdminApplicationApiController::class, 'scheduleInterview']);
        Route::post('/applications/{id}/evaluations', [AdminApplicationApiController::class, 'storeEvaluation']);
        Route::post('/applications/{id}/offer-letter', [AdminApplicationApiController::class, 'issueOfferLetter']);
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
    });

});
