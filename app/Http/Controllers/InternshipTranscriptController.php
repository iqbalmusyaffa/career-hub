<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\InternshipTranscript;
use App\Models\AuditLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InternshipTranscriptController extends Controller
{
    /**
     * HR Form: Grade Builder for Internship Academic Transcript.
     */
    public function create($applicationId)
    {
        $realId = \App\Helpers\IdHasher::decode($applicationId) ?? $applicationId;
        $application = Application::with(['user.candidateProfile', 'job', 'onboarding'])->findOrFail($realId);

        $transcriptNumber = 'TRANSKRIP/MAGANG/' . date('Y/m/') . sprintf('%03d', rand(1, 999));
        $participantName = $application->user->name;
        $studentIdNumber = $application->onboarding->student_id_number ?? ($application->user->candidateProfile->nim ?? '-');
        $institutionName = $application->onboarding->institution_name ?? ($application->user->candidateProfile->university ?? 'Perguruan Tinggi / Kampus');
        $jobTitle = $application->job->title;

        return view('admin.transcripts.create', compact('application', 'transcriptNumber', 'participantName', 'studentIdNumber', 'institutionName', 'jobTitle'));
    }

    /**
     * HR Store: Save Grade Records, Compute Final GPA & Grade Letter, Render PDF.
     */
    public function store(Request $request, $applicationId)
    {
        $realId = \App\Helpers\IdHasher::decode($applicationId) ?? $applicationId;
        $application = Application::with(['user', 'job'])->findOrFail($realId);

        $request->validate([
            'transcript_number' => 'required|string|max:100',
            'participant_name' => 'required|string|max:255',
            'student_id_number' => 'nullable|string|max:100',
            'institution_name' => 'nullable|string|max:255',
            'job_title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'score_discipline' => 'required|numeric|min:0|max:100',
            'score_technical' => 'required|numeric|min:0|max:100',
            'score_communication' => 'required|numeric|min:0|max:100',
            'score_problem_solving' => 'required|numeric|min:0|max:100',
            'score_ethics' => 'required|numeric|min:0|max:100',
            'mentor_notes' => 'nullable|string',
            'mentor_name' => 'nullable|string|max:255',
            'mentor_phone' => 'nullable|string|max:50',
            'mentor_email' => 'nullable|string|email|max:255',
            'hr_name' => 'nullable|string|max:255',
            'issued_at' => 'required|date',
        ]);

        $scoreDiscipline = (float) $request->score_discipline;
        $scoreTechnical = (float) $request->score_technical;
        $scoreCommunication = (float) $request->score_communication;
        $scoreProblemSolving = (float) $request->score_problem_solving;
        $scoreEthics = (float) $request->score_ethics;

        // Compute Automatic Final Average Score
        $finalScore = round(($scoreDiscipline + $scoreTechnical + $scoreCommunication + $scoreProblemSolving + $scoreEthics) / 5, 2);

        if ($finalScore >= 90) {
            $gradeLetter = 'A (Sangat Memuaskan / Outstanding)';
        } elseif ($finalScore >= 80) {
            $gradeLetter = 'B (Baik / Very Good)';
        } elseif ($finalScore >= 70) {
            $gradeLetter = 'C (Cukup / Satisfactory)';
        } else {
            $gradeLetter = 'D (Kurang / Pass)';
        }

        $transcript = InternshipTranscript::create([
            'application_id' => $application->id,
            'user_id' => $application->user_id,
            'transcript_number' => $request->transcript_number,
            'participant_name' => $request->participant_name,
            'student_id_number' => $request->student_id_number,
            'institution_name' => $request->institution_name,
            'job_title' => $request->job_title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'score_discipline' => $scoreDiscipline,
            'score_technical' => $scoreTechnical,
            'score_communication' => $scoreCommunication,
            'score_problem_solving' => $scoreProblemSolving,
            'score_ethics' => $scoreEthics,
            'final_score' => $finalScore,
            'grade_letter' => $gradeLetter,
            'mentor_notes' => $request->mentor_notes ?? 'Peserta magang telah menunjukkan dedikasi, kedisiplinan, dan kontribusi yang sangat baik selama masa magang.',
            'mentor_name' => $request->mentor_name ?? 'Mentor Pembimbing Magang',
            'mentor_phone' => $request->mentor_phone,
            'mentor_email' => $request->mentor_email,
            'hr_name' => $request->hr_name ?? Auth::user()->name,
            'issued_at' => $request->issued_at,
        ]);

        // Render PDF Academic Transcript in Portrait A4 Mode
        $pdf = Pdf::loadView('pdf.internship_transcript', compact('transcript'))
            ->setPaper('a4', 'portrait');

        $transcriptDir = 'transcripts';
        if (!Storage::disk('public')->exists($transcriptDir)) {
            Storage::disk('public')->makeDirectory($transcriptDir);
        }

        $pdfFileName = "Transcript_{$transcript->id}_" . time() . ".pdf";
        $pdfPath = "{$transcriptDir}/{$pdfFileName}";

        Storage::disk('public')->put($pdfPath, $pdf->output());

        $transcript->pdf_path = $pdfPath;
        $transcript->save();

        // Send In-App Notification to candidate
        \App\Models\UserNotification::send(
            $application->user_id,
            "📊 Transkrip Nilai Magang Resmi Anda Telah Diterbitkan!",
            "Tim HR telah menerbitkan Transkrip Evaluasi Nilai Magang resmi (Skor Akhir: {$finalScore} / Grade: {$gradeLetter}). Silakan unduh berkas resmi Anda.",
            route('candidate.transcripts.show', $transcript),
            'success'
        );

        AuditLog::record('transcript_issued', "HR " . Auth::user()->name . " menerbitkan Transkrip Nilai Magang resmi untuk " . $transcript->participant_name);

        return redirect()->route('admin.applications.show', $application)
            ->with('success', '📊 Transkrip Nilai Evaluasi Magang Resmi berhasil diterbitkan dan siap diunduh!');
    }

    /**
     * Preview / Download PDF Transcript Inline in Browser Tab.
     */
    public function show($transcriptId)
    {
        $realId = \App\Helpers\IdHasher::decode($transcriptId) ?? $transcriptId;
        $transcript = InternshipTranscript::with(['application.job', 'user'])->findOrFail($realId);

        if ($transcript->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['HR', 'Super Admin', 'Company Owner', 'Mentor'])) {
            abort(403, 'Anda tidak memiliki otorisasi melihat transkrip nilai ini.');
        }

        if ($transcript->pdf_path && Storage::disk('public')->exists($transcript->pdf_path)) {
            $fullPath = Storage::disk('public')->path($transcript->pdf_path);
            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Transkrip_Nilai_Magang_' . Str::slug($transcript->participant_name) . '.pdf"'
            ]);
        }

        // Regenerate on the fly in portrait
        $pdf = Pdf::loadView('pdf.internship_transcript', compact('transcript'))->setPaper('a4', 'portrait');
        return $pdf->stream("Transkrip_Nilai_Magang_" . Str::slug($transcript->participant_name) . ".pdf");
    }
}
