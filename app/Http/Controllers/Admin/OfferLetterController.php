<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\OfferLetter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferLetterController extends Controller
{
    /**
     * Show form to generate offer letter for an application.
     */
    public function create(Application $application)
    {
        $application->load(['user.candidateProfile', 'job']);
        return view('admin.offer_letters.create', compact('application'));
    }

    /**
     * Generate Offer Letter PDF and save record.
     */
    public function store(Request $request, Application $application)
    {
        $request->validate([
            'offered_salary' => 'required|string|max:255',
            'start_date' => 'required|date',
            'expiration_date' => 'nullable|date|after_or_equal:today',
            'work_location' => 'nullable|string|max:255',
            'benefits_summary' => 'nullable|string',
            'additional_notes' => 'nullable|string',
        ]);

        $application->load(['user.candidateProfile', 'job']);

        $offerLetter = OfferLetter::updateOrCreate(
            ['application_id' => $application->id],
            [
                'user_id' => $application->user_id,
                'job_id' => $application->job_id,
                'position_title' => $application->job->title,
                'offered_salary' => $request->offered_salary,
                'start_date' => $request->start_date,
                'expiration_date' => $request->expiration_date,
                'work_location' => $request->work_location ?? $application->job->location,
                'benefits_summary' => $request->benefits_summary,
                'additional_notes' => $request->additional_notes,
                'status' => 'pending',
            ]
        );

        // Render & Save PDF
        $pdf = Pdf::loadView('pdf.offer_letter', compact('offerLetter', 'application'))
            ->setPaper('a4', 'portrait');

        $pdfDirectory = 'offer_letters';
        if (!Storage::disk('public')->exists($pdfDirectory)) {
            Storage::disk('public')->makeDirectory($pdfDirectory);
        }

        $pdfFileName = "Offer_Letter_{$application->id}_" . time() . ".pdf";
        $pdfFilePath = "{$pdfDirectory}/{$pdfFileName}";

        Storage::disk('public')->put($pdfFilePath, $pdf->output());

        $offerLetter->pdf_path = $pdfFilePath;
        $offerLetter->save();

        // Automatically update application status to Hired
        $application->status = \App\Enums\ApplicationStatus::HIRED;
        $application->save();

        // Send In-App Bell Notification to Candidate
        \App\Models\UserNotification::send(
            $application->user_id,
            "🎉 Surat Penawaran Kerja (Offer Letter) Resmi!",
            "Selamat! Perusahaan telah menerbitkan Surat Penawaran Kerja untuk Anda sebagai " . $offerLetter->position_title . ". Silakan buka profil untuk membaca berkas PDF.",
            route('dashboard'),
            'success'
        );

        // Dispatch Email Notification with PDF Attachment
        try {
            if ($application->user && $application->user->email) {
                \Illuminate\Support\Facades\Mail::to($application->user->email)->send(new \App\Mail\OfferLetterSentMail($offerLetter));
            }
        } catch (\Exception $e) {
            // Ignore email errors gracefully if SMTP is not configured
        }

        return redirect()->route('admin.applications.show', $application)
            ->with('success', 'Surat Penawaran Kerja (Offer Letter PDF) berhasil dibuat, dikirim via Email, dan dilampirkan!');
    }

    /**
     * Download generated Offer Letter PDF.
     */
    public function download(OfferLetter $offerLetter)
    {
        if ($offerLetter->pdf_path && Storage::disk('public')->exists($offerLetter->pdf_path)) {
            return Storage::disk('public')->download($offerLetter->pdf_path);
        }

        // Regenerate on the fly if file missing
        $application = $offerLetter->application->load(['user.candidateProfile', 'job']);
        $pdf = Pdf::loadView('pdf.offer_letter', compact('offerLetter', 'application'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Offer_Letter_{$offerLetter->application_id}.pdf");
    }

    /**
     * Candidate responds to Offer Letter (Accept / Decline).
     */
    public function respond(Request $request, OfferLetter $offerLetter)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined',
            'candidate_response_note' => 'nullable|string',
        ]);

        $offerLetter->status = $request->status;
        $offerLetter->candidate_response_note = $request->candidate_response_note;
        $offerLetter->save();

        $message = $request->status === 'accepted'
            ? 'Selamat! Anda telah MENERIMA Penawaran Kerja (Offer Letter) ini.'
            : 'Anda telah MENOLAK Penawaran Kerja ini.';

        return back()->with('success', $message);
    }
}
