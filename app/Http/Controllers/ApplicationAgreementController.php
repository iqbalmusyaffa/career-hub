<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationAgreement;
use App\Models\AuditLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApplicationAgreementController extends Controller
{
    /**
     * HR Form: Draft a Digital Employment or Internship Agreement.
     */
    public function create($applicationId)
    {
        $realId = \App\Helpers\IdHasher::decode($applicationId) ?? $applicationId;
        $application = Application::with(['user.candidateProfile', 'job'])->findOrFail($realId);

        $job = $application->job;
        $isInternship = Str::contains(strtolower($job->work_type ?? ''), ['intern', 'magang']) || Str::contains(strtolower($job->title ?? ''), ['intern', 'magang']);
        $isRemote = Str::contains(strtolower($job->work_type ?? ''), ['remote', 'wfh', 'jarak jauh']) || Str::contains(strtolower($job->title ?? ''), ['remote', 'wfh']);
        $isHybrid = Str::contains(strtolower($job->work_type ?? ''), ['hybrid']) || Str::contains(strtolower($job->title ?? ''), ['hybrid']);
        $isPermanent = Str::contains(strtolower($job->work_type ?? ''), ['tetap', 'permanent', 'pkwtt']) || Str::contains(strtolower($job->title ?? ''), ['tetap', 'permanent']);

        if ($isInternship) {
            $defaultType = 'internship_agreement';
            $defaultTitle = 'Surat Perjanjian Magang & Insentif (Internship Agreement)';
            $prefix = 'SPM/';
        } elseif ($isRemote) {
            $defaultType = 'remote_contract';
            $defaultTitle = 'Surat Perjanjian Kerja Remote / WFH (Remote Employment Agreement & NDA)';
            $prefix = 'SPK-REMOTE/';
        } elseif ($isHybrid) {
            $defaultType = 'hybrid_contract';
            $defaultTitle = 'Surat Perjanjian Kerja Hybrid (Hybrid Employment Agreement & Flexible Policy)';
            $prefix = 'SPK-HYBRID/';
        } elseif ($isPermanent) {
            $defaultType = 'permanent_contract';
            $defaultTitle = 'Surat Perjanjian Kerja Waktu Tidak Tertentu (PKWTT / Karyawan Tetap)';
            $prefix = 'SPK-TETAP/';
        } else {
            $defaultType = 'employment_contract';
            $defaultTitle = 'Surat Perjanjian Kerja Waktu Tertentu (PKWT / Kontrak)';
            $prefix = 'SPK/';
        }

        $contractNumber = $prefix . date('Y/m/') . sprintf('%03d', rand(1, 999));

        return view('admin.agreements.create', compact('application', 'isInternship', 'isRemote', 'isHybrid', 'isPermanent', 'defaultType', 'defaultTitle', 'contractNumber'));
    }

    /**
     * HR Store: Save Digital Agreement & Notify Candidate.
     */
    public function store(Request $request, $applicationId)
    {
        $realId = \App\Helpers\IdHasher::decode($applicationId) ?? $applicationId;
        $application = Application::with('user', 'job')->findOrFail($realId);

        $request->validate([
            'agreement_type' => 'required|string|in:employment_contract,permanent_contract,remote_contract,internship_agreement,hybrid_contract',
            'title' => 'required|string|max:255',
            'contract_number' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'stipend_or_salary' => 'required|string|max:255',
            'terms_content' => 'required|string',
            'hr_signer_name' => 'nullable|string|max:255',
            'company_signature' => 'nullable|file|mimes:png,jpg,jpeg,svg|max:5120',
            'owner_signer_name' => 'nullable|string|max:255',
            'owner_signature' => 'nullable|file|mimes:png,jpg,jpeg,svg|max:5120',
        ]);

        $companySignaturePath = null;
        if ($request->hasFile('company_signature')) {
            $companySignaturePath = $request->file('company_signature')->store('company_signatures', 'public');
        }

        $ownerSignaturePath = null;
        if ($request->hasFile('owner_signature')) {
            $ownerSignaturePath = $request->file('owner_signature')->store('company_signatures', 'public');
        }

        $agreement = ApplicationAgreement::create([
            'application_id' => $application->id,
            'user_id' => $application->user_id,
            'agreement_type' => $request->agreement_type,
            'title' => $request->title,
            'contract_number' => $request->contract_number,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'stipend_or_salary' => $request->stipend_or_salary,
            'terms_content' => $request->terms_content,
            'hr_signer_name' => $request->hr_signer_name ?? Auth::user()->name,
            'company_signature_path' => $companySignaturePath,
            'owner_signer_name' => $request->owner_signer_name,
            'owner_signature_path' => $ownerSignaturePath,
            'status' => 'sent',
        ]);

        // Send In-App Notification to candidate
        \App\Models\UserNotification::send(
            $application->user_id,
            "✍️ Surat Perjanjian Digital Siap Ditandatangani!",
            "Perusahaan telah menerbitkan " . $agreement->title . " (No: " . $agreement->contract_number . "). Silakan buka dan tanda tangani secara digital.",
            route('candidate.agreements.show', $agreement),
            'info'
        );

        AuditLog::record('agreement_created', "HR " . Auth::user()->name . " menerbitkan perjanjian digital {$agreement->title} untuk " . $application->user->name);

        return redirect()->route('admin.applications.show', $application)
            ->with('success', 'Surat Perjanjian Digital berhasil dibuat dan dikirimkan ke kandidat untuk ditandatangani!');
    }

    /**
     * Candidate View: Display Interactive Signing Page with HTML5 E-Signature Pad.
     */
    public function show($agreementId)
    {
        $realId = \App\Helpers\IdHasher::decode($agreementId) ?? $agreementId;
        $agreement = ApplicationAgreement::with(['application.job', 'user.candidateProfile'])->findOrFail($realId);

        if ($agreement->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['HR', 'Super Admin', 'Company Owner'])) {
            abort(403, 'Anda tidak memiliki otorisasi membuka dokumen perjanjian ini.');
        }

        return view('candidate.agreements.show', compact('agreement'));
    }

    /**
     * Send 6-digit OTP code to Candidate EMAIL for Digital Agreement verification.
     */
    public function sendOtp(Request $request, $agreementId)
    {
        $realId = \App\Helpers\IdHasher::decode($agreementId) ?? $agreementId;
        $agreement = ApplicationAgreement::with('user')->findOrFail($realId);

        if ($agreement->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $user = Auth::user();
        $otpCode = \App\Services\OtpService::generateOtp($user->id, 'agreement_signing');

        // Send OTP via Email
        try {
            \Illuminate\Support\Facades\Mail::raw(
                "Halo {$user->name},\n\n" .
                "Kode OTP Verifikasi Keamanan 6-Digit Anda untuk penandatanganan dokumen perjanjian ({$agreement->title}) adalah:\n\n" .
                "👉 {$otpCode} 👈\n\n" .
                "Kode OTP ini berlaku selama 5 menit. Dilarang memberikan kode OTP ini kepada siapapun.\n\n" .
                "Salam,\nTim Rekrutmen TalentFlow",
                function ($mail) use ($user, $agreement) {
                    $mail->to($user->email)
                        ->subject("🔐 Kode OTP Verifikasi Tanda Tangan Email: {$agreement->contract_number}");
                }
            );
        } catch (\Exception $e) {
            // Log mail failure silently if mailer is unconfigured
        }

        // Send OTP via In-App Bell Notification
        \App\Models\UserNotification::send(
            $user->id,
            "🔐 Kode OTP Email: {$otpCode}",
            "Kode OTP 6-Digit untuk verifikasi dokumen perjanjian ({$agreement->title}) adalah {$otpCode}. Kode berlaku 5 menit.",
            route('candidate.agreements.show', $agreement),
            'info'
        );

        // Audit Log
        AuditLog::record('otp_email_sent', "Kode OTP Email dikirim ke {$user->email} untuk kandidat " . $user->name);

        return response()->json([
            'success' => true,
            'email' => $user->email,
            'message' => "Kode OTP 6-digit telah dikirimkan ke Email Anda ({$user->email})!",
            'otp_code' => $otpCode, // Displayed for demo testing convenience
        ]);
    }

    /**
     * Candidate Action: Sign Digital Agreement with 6-Digit OTP Verification.
     */
    public function sign(Request $request, $agreementId)
    {
        $realId = \App\Helpers\IdHasher::decode($agreementId) ?? $agreementId;
        $agreement = ApplicationAgreement::with(['application.job', 'user.candidateProfile'])->findOrFail($realId);

        if ($agreement->user_id !== Auth::id()) {
            return back()->with('error', 'Akses ditolak: Hanya kandidat bersangkutan yang dapat menandatangani dokumen ini.');
        }

        $request->validate([
            'signer_name' => 'required|string|max:255',
            'signature_data' => 'required|string', // Base64 signature SVG/PNG data
            'agree_checkbox' => 'accepted',
            'otp_code' => 'required|string|size:6',
        ]);

        // Verify 6-digit OTP code
        if (!\App\Services\OtpService::verifyOtp(Auth::id(), 'agreement_signing', $request->otp_code)) {
            return back()->with('error', '❌ Kode OTP verifikasi yang Anda masukkan salah atau sudah kedaluwarsa. Silakan klik "Kirim Kode OTP Baru".');
        }

        $agreement->signer_name = $request->signer_name;
        $agreement->signature_data = $request->signature_data;
        $agreement->signer_ip = $request->ip();
        $agreement->signed_at = now();
        $agreement->status = 'signed';

        // Render Final Signed PDF Document
        $pdf = Pdf::loadView('pdf.agreement_document', compact('agreement'))
            ->setPaper('a4', 'portrait');

        $pdfDirectory = 'agreements';
        if (!Storage::disk('public')->exists($pdfDirectory)) {
            Storage::disk('public')->makeDirectory($pdfDirectory);
        }

        $pdfFileName = "Signed_Agreement_{$agreement->id}_" . time() . ".pdf";
        $pdfFilePath = "{$pdfDirectory}/{$pdfFileName}";

        Storage::disk('public')->put($pdfFilePath, $pdf->output());

        $agreement->signed_pdf_path = $pdfFilePath;
        $agreement->save();

        AuditLog::record('agreement_signed', "Kandidat " . Auth::user()->name . " telah menandatangani secara digital " . $agreement->title);

        return redirect()->route('candidate.agreements.show', $agreement)
            ->with('success', '🎉 Selamat! Surat Perjanjian Digital telah berhasil ditandatangani secara SAH secara hukum digital.');
    }

    /**
     * Preview / Stream Final Signed PDF Contract directly in browser.
     */
    public function download($agreementId)
    {
        $realId = \App\Helpers\IdHasher::decode($agreementId) ?? $agreementId;
        $agreement = ApplicationAgreement::with(['application.job', 'user'])->findOrFail($realId);

        if (Auth::check()) {
            $user = Auth::user();
            $isCandidate = $agreement->user_id === $user->id;
            $isStaff = $user->hasAnyRole(['HR', 'Super Admin', 'Company Owner', 'Admin']) || ($user->company_id && $agreement->application && $agreement->application->job && $agreement->application->job->company_id === $user->company_id);
            if (!$isCandidate && !$isStaff) {
                abort(403, 'Anda tidak memiliki otorisasi untuk mengunduh dokumen perjanjian ini.');
            }
        }

        if ($agreement->signed_pdf_path && Storage::disk('public')->exists($agreement->signed_pdf_path)) {
            $fullPath = Storage::disk('public')->path($agreement->signed_pdf_path);
            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Perjanjian_' . Str::slug($agreement->title) . '.pdf"'
            ]);
        }

        // Regenerate on the fly for inline display
        $pdf = Pdf::loadView('pdf.agreement_document', compact('agreement'))->setPaper('a4', 'portrait');
        return $pdf->stream("Perjanjian_Digital_" . Str::slug($agreement->title) . ".pdf");
    }
}
