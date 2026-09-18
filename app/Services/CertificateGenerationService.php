<?php

namespace App\Services;

use App\Models\Application;
use App\Models\AuditLog;
use App\Models\InternshipCertificate;
use App\Models\InternshipEvaluation;
use App\Models\InternshipPeriod;
use App\Models\InternshipTranscript;
use App\Models\User;
use App\Models\UserNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateGenerationService
{
    /**
     * Generate or Synchronize Certificate & Academic Transcript for an Intern.
     */
    public static function generateOrUpdateForIntern(User $intern, ?InternshipEvaluation $evaluation = null): array
    {
        // 1. Fetch relevant application & internship period
        $application = $intern->applications()
            ->with(['job.companyProfile', 'onboarding'])
            ->whereHas('job', function ($q) {
                $q->whereIn('work_type', ['internship', 'magang']);
            })
            ->latest()
            ->first() ?? $intern->applications()->with(['job.companyProfile', 'onboarding'])->latest()->first();

        if (!$application) {
            $defaultJob = \App\Models\Job::whereIn('work_type', ['internship', 'magang'])->first() ?? \App\Models\Job::first();
            if ($defaultJob) {
                $application = Application::firstOrCreate([
                    'user_id' => $intern->id,
                    'job_id' => $defaultJob->id,
                ], [
                    'status' => 'accepted',
                    'cover_letter' => 'Peserta Program Magang Terdaftar',
                ]);
            }
        }

        $periodSetting = InternshipPeriod::where('user_id', $intern->id)->first();
        $startDate = $periodSetting && $periodSetting->start_date ? $periodSetting->start_date->copy() : Carbon::create(2026, 8, 10);
        $endDate = $periodSetting && $periodSetting->end_date ? $periodSetting->end_date->copy() : Carbon::create(2027, 2, 9);

        $institutionName = $application?->onboarding?->institution_name 
            ?? $intern->candidateProfile?->university 
            ?? 'Perguruan Tinggi / Kampus';

        $studentIdNumber = $application?->onboarding?->student_id_number 
            ?? $intern->candidateProfile?->nim 
            ?? '-';

        $jobTitle = $application?->job?->title ?? 'Software Engineer Intern';

        // 2. Fetch evaluation details if not provided
        if (!$evaluation) {
            $evaluation = InternshipEvaluation::with('mentor')->where('user_id', $intern->id)->first();
        }

        $mentorName = $evaluation?->mentor?->name ?? 'Mentor Pembimbing Magang';
        $mentorPhone = $evaluation?->mentor?->phone ?? null;
        $mentorEmail = $evaluation?->mentor?->email ?? null;

        $discipline = (float) ($evaluation->discipline_score ?? 90);
        $technical = (float) ($evaluation->work_quality_score ?? 92);
        $communication = (float) ($evaluation->teamwork_score ?? 90);
        $problemSolving = (float) ($evaluation->problem_solving_score ?? 88);
        $ethics = (float) ($evaluation->initiative_score ?? 94);

        $finalScore = $evaluation?->final_score ?? round(($discipline + $technical + $communication + $problemSolving + $ethics) / 5, 2);
        $finalGrade = $evaluation?->final_grade ?? ($finalScore >= 90 ? 'A' : ($finalScore >= 80 ? 'B' : 'C'));

        $performanceGradeStr = match (strtoupper(substr($finalGrade, 0, 1))) {
            'A' => 'A (Sangat Memuaskan / Outstanding)',
            'B' => 'B (Baik / Very Good)',
            'C' => 'C (Cukup / Satisfactory)',
            default => 'D (Kurang / Pass)'
        };

        $mentorNotes = $evaluation?->feedback_summary ?? 'Peserta telah menunjukkan dedikasi, inisiatif, dan pencapaian target kerja yang sangat memuaskan selama program magang.';

        // 3. Create or Update Internship Certificate
        $certNumber = 'CERT/MAGANG/' . date('Y/m/') . sprintf('%04d', $intern->id);
        
        $certificate = InternshipCertificate::firstOrNew([
            'user_id' => $intern->id,
        ]);

        $certificate->fill([
            'application_id' => $application?->id,
            'certificate_number' => $certificate->certificate_number ?: $certNumber,
            'participant_name' => $intern->name,
            'institution_name' => $institutionName,
            'job_title' => $jobTitle,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'performance_grade' => $performanceGradeStr,
            'mentor_name' => $mentorName,
            'mentor_phone' => $mentorPhone,
            'mentor_email' => $mentorEmail,
            'hr_name' => 'HR Manager',
            'owner_name' => 'Direktur Utama',
            'issued_at' => $certificate->issued_at ?: now(),
        ]);
        $certificate->save();

        // Render Landscape Certificate PDF
        $certPdf = Pdf::loadView('pdf.internship_certificate', compact('certificate'))
            ->setPaper('a4', 'landscape');

        $certDir = 'certificates';
        if (!Storage::disk('public')->exists($certDir)) {
            Storage::disk('public')->makeDirectory($certDir);
        }

        $certFileName = "Certificate_{$certificate->id}_" . time() . ".pdf";
        $certPath = "{$certDir}/{$certFileName}";
        Storage::disk('public')->put($certPath, $certPdf->output());
        $certificate->pdf_path = $certPath;
        $certificate->save();

        // 4. Create or Update Internship Academic Transcript
        $transcriptNumber = 'TRANS/MAGANG/' . date('Y/m/') . sprintf('%04d', $intern->id);

        $transcript = InternshipTranscript::firstOrNew([
            'user_id' => $intern->id,
        ]);

        $transcript->fill([
            'application_id' => $application?->id,
            'transcript_number' => $transcript->transcript_number ?: $transcriptNumber,
            'participant_name' => $intern->name,
            'student_id_number' => $studentIdNumber,
            'institution_name' => $institutionName,
            'job_title' => $jobTitle,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'score_discipline' => $discipline,
            'score_technical' => $technical,
            'score_communication' => $communication,
            'score_problem_solving' => $problemSolving,
            'score_ethics' => $ethics,
            'final_score' => $finalScore,
            'grade_letter' => $performanceGradeStr,
            'mentor_notes' => $mentorNotes,
            'mentor_name' => $mentorName,
            'mentor_phone' => $mentorPhone,
            'mentor_email' => $mentorEmail,
            'hr_name' => 'HR Manager',
            'issued_at' => $transcript->issued_at ?: now(),
        ]);
        $transcript->save();

        // Render Portrait Transcript PDF
        $transcriptPdf = Pdf::loadView('pdf.internship_transcript', compact('transcript'))
            ->setPaper('a4', 'portrait');

        $transcriptDir = 'transcripts';
        if (!Storage::disk('public')->exists($transcriptDir)) {
            Storage::disk('public')->makeDirectory($transcriptDir);
        }

        $transcriptFileName = "Transcript_{$transcript->id}_" . time() . ".pdf";
        $transcriptPath = "{$transcriptDir}/{$transcriptFileName}";
        Storage::disk('public')->put($transcriptPath, $transcriptPdf->output());
        $transcript->pdf_path = $transcriptPath;
        $transcript->save();

        // Send Notification
        UserNotification::send(
            $intern->id,
            "🎓 E-Sertifikat & Transkrip Kelulusan Magang Siap Diunduh!",
            "Selamat! E-Sertifikat Magang (No: {$certificate->certificate_number}) & Transkrip Nilai Akademik resmi Anda telah diterbitkan dengan QR Code Terverifikasi.",
            route('candidate.logbook.progress'),
            'success'
        );

        AuditLog::record('CERTIFICATE_AUTO_GENERATED', "E-Sertifikat & Transkrip Magang diterbitkan untuk {$intern->name} (No: {$certificate->certificate_number})", $intern);

        return [
            'certificate' => $certificate,
            'transcript' => $transcript,
        ];
    }
}
