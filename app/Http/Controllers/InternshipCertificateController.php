<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\InternshipCertificate;
use App\Models\AuditLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InternshipCertificateController extends Controller
{
    /**
     * HR Form: Certificate Builder for Internship Graduates.
     */
    public function create($applicationId)
    {
        $realId = \App\Helpers\IdHasher::decode($applicationId) ?? $applicationId;
        $application = Application::with(['user.candidateProfile', 'job', 'onboarding'])->findOrFail($realId);

        $certNumber = 'CERT/MAGANG/' . date('Y/m/') . sprintf('APP%04d-U%04d', $application->id, $application->user_id);
        $participantName = $application->user->name;
        
        $profile = $application->user->candidateProfile;
        $educations = is_array($profile?->educations) ? $profile->educations : [];
        $firstEdu = !empty($educations) ? $educations[0] : null;

        $institutionName = $application->onboarding?->institution_name 
            ?? $profile?->university 
            ?? ($firstEdu['institution'] ?? ($firstEdu['school'] ?? ($firstEdu['name'] ?? ($profile?->last_education ?? 'Perguruan Tinggi / Sekolah'))));

        $jobTitle = $application->job->title;

        // Auto-resolve assigned mentor if available
        $mentorUser = \App\Models\User::role('Mentor')->first();
        $mentorName = $mentorUser?->name ?? 'Rizky Ramadhan, M.T';
        $mentorPhone = $mentorUser?->candidateProfile?->phone ?? '081234567890';
        $mentorEmail = $mentorUser?->email ?? 'mentor@company.com';

        return view('admin.certificates.create', compact('application', 'certNumber', 'participantName', 'institutionName', 'jobTitle', 'mentorName', 'mentorPhone', 'mentorEmail'));
    }

    /**
     * HR Store: Save Certificate Record & Render Official PDF Certificate.
     */
    public function store(Request $request, $applicationId)
    {
        $realId = \App\Helpers\IdHasher::decode($applicationId) ?? $applicationId;
        $application = Application::with(['user', 'job'])->findOrFail($realId);

        $request->validate([
            'certificate_number' => 'required|string|max:100',
            'participant_name' => 'required|string|max:255',
            'institution_name' => 'nullable|string|max:255',
            'job_title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'performance_grade' => 'required|string|max:100',
            'mentor_name' => 'nullable|string|max:255',
            'mentor_phone' => 'nullable|string|max:50',
            'mentor_email' => 'nullable|string|email|max:255',
            'hr_name' => 'nullable|string|max:255',
            'owner_name' => 'nullable|string|max:255',
            'issued_at' => 'required|date',
        ]);

        $certificate = InternshipCertificate::create([
            'application_id' => $application->id,
            'user_id' => $application->user_id,
            'certificate_number' => $request->certificate_number,
            'participant_name' => $request->participant_name,
            'institution_name' => $request->institution_name,
            'job_title' => $request->job_title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'performance_grade' => $request->performance_grade,
            'mentor_name' => $request->mentor_name ?? 'Mentor Pembimbing Magang',
            'mentor_phone' => $request->mentor_phone,
            'mentor_email' => $request->mentor_email,
            'hr_name' => $request->hr_name ?? Auth::user()->name,
            'owner_name' => $request->owner_name ?? 'Direktur Utama',
            'issued_at' => $request->issued_at,
        ]);

        // Render PDF Certificate in Landscape A4 Mode
        $pdf = Pdf::loadView('pdf.internship_certificate', compact('certificate'))
            ->setPaper('a4', 'landscape');

        $certDir = 'certificates';
        if (!Storage::disk('public')->exists($certDir)) {
            Storage::disk('public')->makeDirectory($certDir);
        }

        $pdfFileName = "Certificate_{$certificate->id}_" . time() . ".pdf";
        $pdfPath = "{$certDir}/{$pdfFileName}";

        Storage::disk('public')->put($pdfPath, $pdf->output());

        $certificate->pdf_path = $pdfPath;
        $certificate->save();

        // Send In-App Notification to candidate
        \App\Models\UserNotification::send(
            $application->user_id,
            "🎓 Sertifikat Magang Resmi Anda Telah Diterbitkan!",
            "Selamat! Perusahaan telah menerbitkan Sertifikat Kelulusan Magang (No: " . $certificate->certificate_number . "). Silakan unduh sertifikat resmi Anda.",
            route('candidate.certificates.show', $certificate),
            'success'
        );

        AuditLog::record('certificate_issued', "HR " . Auth::user()->name . " menerbitkan Sertifikat Magang resmi untuk " . $certificate->participant_name);

        return redirect()->route('admin.applications.show', $application)
            ->with('success', '🎓 Sertifikat Magang Resmi berhasil diterbitkan dan siap diunduh oleh kandidat!');
    }

    /**
     * Preview / Download PDF Certificate Inline in Browser Tab.
     */
    public function show($certificateId)
    {
        $realId = \App\Helpers\IdHasher::decode($certificateId) ?? $certificateId;
        $certificate = InternshipCertificate::with(['application.job', 'user'])->findOrFail($realId);

        if ($certificate->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['HR', 'Super Admin', 'Company Owner', 'Mentor'])) {
            abort(403, 'Anda tidak memiliki otorisasi melihat sertifikat ini.');
        }

        if ($certificate->user_id === Auth::id() && Auth::user()->hasRole('Candidate')) {
            $hasSurvey = \App\Models\InternshipSurvey::where('user_id', Auth::id())->exists() || session()->has('survey_submitted_' . Auth::id());
            if (!$hasSurvey) {
                return redirect()->route('candidate.logbook.progress')
                    ->with('error', 'Silakan lengkapi survei evaluasi akhir program magang terlebih dahulu untuk mengunduh E-Sertifikat resmi.');
            }
        }

        // Generate fresh landscape PDF and update cache
        $pdf = Pdf::loadView('pdf.internship_certificate', compact('certificate'))->setPaper('a4', 'landscape');
        
        if ($certificate->pdf_path) {
            Storage::disk('public')->put($certificate->pdf_path, $pdf->output());
        }

        return $pdf->stream("Sertifikat_Magang_" . Str::slug($certificate->participant_name) . ".pdf");
    }
}
