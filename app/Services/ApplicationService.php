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

        // Guard KHUSUS LOWONGAN MAGANG / INTERNSHIP:
        // Kandidat yang sedang aktif, pernah mengundurkan diri (resigned), atau diberhentikan (terminated) tidak dapat mendaftar magang lagi.
        // Kandidat yang tidak lolos seleksi awal tetap diperbolehkan melamar ke lowongan magang lainnya.
        if ($job->isInternship()) {
            $applicant = \App\Models\User::find($userId);
            if ($applicant && ($reason = $applicant->getInternshipBlockReason())) {
                throw ValidationException::withMessages([
                    'job_id' => 'Mohon maaf, ' . $reason
                ]);
            }
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
    public function getStatusCounts() { return $this->applicationRepository->getStatusCounts(); }
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

        // Auto-assign Internship Batch when candidate is accepted/hired
        if (in_array($newStatusStr, ['accepted', 'hired'])) {
            $job = $application->job;
            if ($job && !empty($job->batch) && $application->user_id) {
                $companyId = $job->company_profile_id ?? ($job->companyProfile ? $job->companyProfile->id : 1);
                $batchModel = \App\Models\InternshipBatch::findOrCreateByName($job->batch, $companyId, [
                    'start_date' => $job->start_date ?? now()->startOfDay(),
                    'end_date' => $job->start_date ? \Carbon\Carbon::parse($job->start_date)->addMonths(6)->endOfDay() : now()->addMonths(6)->endOfDay(),
                    'target_hours' => 400,
                    'status' => 'active'
                ]);

                \App\Models\InternshipPeriod::updateOrCreate(
                    [
                        'user_id' => $application->user_id,
                    ],
                    [
                        'company_id' => $companyId,
                        'period_name' => $batchModel->batch_name,
                        'start_date' => $batchModel->start_date ?? now()->startOfDay(),
                        'end_date' => $batchModel->end_date ?? now()->addMonths(6)->endOfDay(),
                        'target_hours' => $batchModel->target_hours ?? 400,
                    ]
                );

                \App\Models\UserNotification::send(
                    $application->user_id,
                    '🎉 Selamat! Anda Terdaftar di ' . $batchModel->batch_name,
                    'Lamaran Anda untuk posisi ' . $job->title . ' telah diterima. Anda telah otomatis terdaftar pada program ' . $batchModel->batch_name . ' (Target: ' . ($batchModel->target_hours ?? 400) . ' Jam). Silakan akses Dashboard Presensi Magang Anda.',
                    route('candidate.logbook.index'),
                    'success'
                );
            }
        }

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
