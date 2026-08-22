<?php

namespace App\Services;

use App\Interfaces\ApplicationRepositoryInterface;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ApplicationService
{
    protected $applicationRepository;

    public function __construct(ApplicationRepositoryInterface $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function applyForJob($userId, $jobId, $screeningVideoUrl = null)
    {
        $job = \App\Models\Job::findOrFail($jobId);

        if ($job->isQuotaFull()) {
            throw ValidationException::withMessages(['job_id' => 'Maaf, kuota pendaftaran untuk lowongan ini telah penuh.']);
        }

        // Check if already applied
        $existing = \App\Models\Application::where('user_id', $userId)->where('job_id', $jobId)->exists();
        if ($existing) {
            throw ValidationException::withMessages(['job_id' => 'Anda sudah melamar posisi ini sebelumnya.']);
        }

        return $this->applicationRepository->create([
            'user_id' => $userId,
            'job_id' => $jobId,
            'status' => \App\Enums\ApplicationStatus::PENDING,
            'screening_video_url' => $screeningVideoUrl,
        ]);
    }

    public function getAllApplications() { return $this->applicationRepository->getAll(); }
    public function getApplicationsByJob($jobId) { return $this->applicationRepository->getByJobId($jobId); }
    public function getApplicationsByUser($userId) { return $this->applicationRepository->getByUserId($userId); }
    public function getApplicationById($id) { return $this->applicationRepository->findById($id); }
    
    public function updateApplicationStatus($id, $newStatus)
    {
        $application = \App\Models\Application::with(['job', 'user'])->findOrFail($id);
        $user = Auth::user();
        
        $currentStatusStr = is_object($application->status) ? $application->status->value : (string) $application->status;
        $newStatusStr = is_object($newStatus) ? $newStatus->value : (string) $newStatus;

        // Rule 1: Acceptance Lock Guard (HR cannot directly revoke acceptance)
        if ($currentStatusStr === 'accepted' && $newStatusStr !== 'accepted') {
            if (!$user || !$user->hasRole('Super Admin')) {
                throw ValidationException::withMessages([
                    'status' => '🔒 PERHATIAN: Status penerimaan kandidat yang sudah DITERIMA (Hired) TIDAK DAPAT DIBATALKAN LANGSUNG OLEH HR. Silakan ajukan aduan permohonan pembatalan ke Super Admin!'
                ]);
            }
        }

        // Rule 2: Quota Limit Guard (Cannot accept more candidates than job quota)
        if ($newStatusStr === 'accepted' && $currentStatusStr !== 'accepted') {
            $job = $application->job;
            if ($job && $job->quota) {
                $hiredCount = \App\Models\Application::where('job_id', $job->id)
                    ->whereIn('status', ['accepted', 'hired'])
                    ->count();

                if ($hiredCount >= $job->quota) {
                    throw ValidationException::withMessages([
                        'status' => "⛔ GAGAL MENERIMA KANDIDAT: Kuota penerimaan untuk lowongan '{$job->title}' telah PENUH ({$hiredCount}/{$job->quota} kandidat). Kuota penerimaan tidak dapat dilampaui!"
                    ]);
                }
            }
        }

        $result = $this->applicationRepository->updateStatus($id, $newStatus);

        \App\Models\AuditLog::record('application_status_updated', "Mengubah status lamaran kandidat " . ($application->user->name ?? 'Kandidat') . " dari {$currentStatusStr} ke {$newStatusStr}");

        // Dispatch Email Notification to Candidate
        try {
            if ($application->user && $application->user->email) {
                \Illuminate\Support\Facades\Mail::to($application->user->email)
                    ->send(new \App\Mail\ApplicationStatusUpdatedMail($application));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Email notification failed: ' . $e->getMessage());
        }

        return $result;
    }
}
