<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\EmployeeTermination;
use App\Models\AuditLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeTerminationController extends Controller
{
    /**
     * HR Form: Recommendation & Termination Document Builder.
     */
    public function create($applicationId)
    {
        $realId = \App\Helpers\IdHasher::decode($applicationId) ?? $applicationId;
        $application = Application::with(['user.candidateProfile', 'job'])->findOrFail($realId);

        $docNumber = 'SKK/' . date('Y/m/') . sprintf('%03d', rand(1, 999));
        $employeeName = $application->user->name;
        $jobTitle = $application->job->title;

        return view('admin.terminations.create', compact('application', 'docNumber', 'employeeName', 'jobTitle'));
    }

    /**
     * HR Store: Save Recommendation/Termination Record & Render PDF.
     */
    public function store(Request $request, $applicationId)
    {
        $realId = \App\Helpers\IdHasher::decode($applicationId) ?? $applicationId;
        $application = Application::with(['user', 'job'])->findOrFail($realId);

        $request->validate([
            'document_type' => 'required|string|in:recommendation_letter,paklaring_letter,phk_letter,contract_expired',
            'document_number' => 'required|string|max:100',
            'employee_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason_or_recommendation_notes' => 'required|string',
            'severance_compensation' => 'nullable|string|max:255',
            'hr_name' => 'nullable|string|max:255',
            'owner_name' => 'nullable|string|max:255',
            'company_signature' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'owner_signature' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'issued_at' => 'required|date',
        ]);

        $companySigPath = null;
        if ($request->hasFile('company_signature')) {
            $companySigPath = $request->file('company_signature')->store('signatures', 'public');
        }

        $ownerSigPath = null;
        if ($request->hasFile('owner_signature')) {
            $ownerSigPath = $request->file('owner_signature')->store('signatures', 'public');
        }

        $termination = EmployeeTermination::create([
            'application_id' => $application->id,
            'user_id' => $application->user_id,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'employee_name' => $request->employee_name,
            'job_title' => $request->job_title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason_or_recommendation_notes' => $request->reason_or_recommendation_notes,
            'severance_compensation' => $request->severance_compensation,
            'hr_name' => $request->hr_name ?? Auth::user()->name,
            'owner_name' => $request->owner_name ?? 'Direktur Utama',
            'company_signature_path' => $companySigPath,
            'owner_signature_path' => $ownerSigPath,
            'issued_at' => $request->issued_at,
            'status' => 'pending_signature',
        ]);

        // Render Initial PDF Document
        $pdf = Pdf::loadView('pdf.employee_termination', compact('termination'))
            ->setPaper('a4', 'portrait');

        $docDir = 'terminations';
        if (!Storage::disk('public')->exists($docDir)) {
            Storage::disk('public')->makeDirectory($docDir);
        }

        $pdfFileName = "Termination_Doc_{$termination->id}_" . time() . ".pdf";
        $pdfPath = "{$docDir}/{$pdfFileName}";

        Storage::disk('public')->put($pdfPath, $pdf->output());

        $termination->pdf_path = $pdfPath;
        $termination->save();

        $docTitleMap = [
            'recommendation_letter' => 'Surat Rekomendasi Kerja & Referensi Karir',
            'paklaring_letter' => 'Surat Pengalaman Kerja (Paklaring)',
            'phk_letter' => 'Surat Pemutusan Hubungan Kerja (PHK & Bersama)',
            'contract_expired' => 'Surat Keterangan Selesai Kontrak Kerja',
        ];
        $docLabel = $docTitleMap[$request->document_type] ?? 'Surat Pengalaman / Perpisahan Kerja';

        // Send In-App Notification to employee
        \App\Models\UserNotification::send(
            $application->user_id,
            "📄 {$docLabel} Telah Diterbitkan!",
            "Perusahaan telah menerbitkan {$docLabel} resmi (No: {$request->document_number}). Silakan baca & tanda tangani secara digital.",
            route('candidate.terminations.show', $termination),
            'warning'
        );

        AuditLog::record('termination_doc_issued', "HR " . Auth::user()->name . " menerbitkan {$docLabel} untuk " . $termination->employee_name);

        return redirect()->route('admin.applications.show', $application)
            ->with('success', "📄 {$docLabel} berhasil diterbitkan & terkirim ke karyawan!");
    }

    /**
     * Send 6-Digit Email OTP for Signing Termination Document.
     */
    public function sendOtp($terminationId)
    {
        $realId = \App\Helpers\IdHasher::decode($terminationId) ?? $terminationId;
        $termination = EmployeeTermination::findOrFail($realId);

        if ($termination->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        $otp = \App\Services\OtpService::generateOtp(Auth::id(), 'termination_signing');

        // Send OTP via Email
        try {
            \Illuminate\Support\Facades\Mail::raw("Kode OTP Verifikasi Penandatanganan Dokumen Resign / PHK Anda adalah: {$otp}\n\nKode ini berlaku selama 5 menit. Dilarang memberikan kode OTP kepada siapapun.", function ($message) {
                $message->to(Auth::user()->email)
                    ->subject('🔐 Kode OTP Verifikasi Tanda Tangan Dokumen Resign / PHK');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Gagal mengirim email OTP: " . $e->getMessage());
        }

        // Send OTP via In-App Notification
        \App\Models\UserNotification::send(
            Auth::id(),
            "🔐 Kode OTP Tanda Tangan Dokumen: {$otp}",
            "Masukkan kode OTP {$otp} pada formulir Tanda Tangan Digital untuk mengesahkan dokumen Perjanjian Resign / PHK.",
            route('candidate.terminations.show', $termination),
            'info'
        );

        return back()->with('success', "🎉 Kode OTP verifikasi 6-digit berhasil dikirimkan ke email " . Auth::user()->email . " dan notifikasi sistem!");
    }

    /**
     * Candidate Sign Termination Document with Canvas Pad & Email OTP.
     */
    public function sign(Request $request, $terminationId)
    {
        $realId = \App\Helpers\IdHasher::decode($terminationId) ?? $terminationId;
        $termination = EmployeeTermination::findOrFail($realId);

        if ($termination->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        $request->validate([
            'signer_name' => 'required|string|max:255',
            'signature_data' => 'required|string',
            'agree_checkbox' => 'accepted',
            'otp_code' => 'required|string|size:6',
        ]);

        // Verify 6-digit OTP code
        if (!\App\Services\OtpService::verifyOtp(Auth::id(), 'termination_signing', $request->otp_code)) {
            return back()->with('error', '❌ Kode OTP verifikasi yang Anda masukkan salah atau sudah kedaluwarsa.');
        }

        $termination->signer_name = $request->signer_name;
        $termination->signature_data = $request->signature_data;
        $termination->signer_ip = $request->ip();
        $termination->signed_at = now();
        $termination->status = 'signed';

        // Render Final Signed PDF Document
        $pdf = Pdf::loadView('pdf.employee_termination', compact('termination'))
            ->setPaper('a4', 'portrait');

        $docDir = 'terminations';
        if (!Storage::disk('public')->exists($docDir)) {
            Storage::disk('public')->makeDirectory($docDir);
        }

        $pdfFileName = "Signed_Termination_{$termination->id}_" . time() . ".pdf";
        $pdfPath = "{$docDir}/{$pdfFileName}";

        Storage::disk('public')->put($pdfPath, $pdf->output());

        $termination->pdf_path = $pdfPath;
        $termination->save();

        AuditLog::record('termination_signed', "Karyawan " . Auth::user()->name . " telah menandatangani secara digital Dokumen Resign/PHK " . $termination->document_number);

        return redirect()->route('candidate.terminations.show', $termination)
            ->with('success', '🎉 Selamat! Dokumen Resign / PHK & Rekomendasi Kerja telah berhasil ditandatangani secara SAH secara hukum digital.');
    }

    /**
     * Preview / Download PDF Recommendation/Termination Document Inline in Browser Tab.
     */
    public function show($terminationId)
    {
        $realId = \App\Helpers\IdHasher::decode($terminationId) ?? $terminationId;
        $termination = EmployeeTermination::with(['application.job', 'user'])->findOrFail($realId);

        if ($termination->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['HR', 'Super Admin', 'Company Owner'])) {
            abort(403, 'Anda tidak memiliki otorisasi melihat dokumen ini.');
        }

        if ($termination->pdf_path && Storage::disk('public')->exists($termination->pdf_path)) {
            $fullPath = Storage::disk('public')->path($termination->pdf_path);
            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Surat_' . Str::slug($termination->employee_name) . '.pdf"'
            ]);
        }

        // Regenerate on the fly in portrait
        $pdf = Pdf::loadView('pdf.employee_termination', compact('termination'))->setPaper('a4', 'portrait');
        return $pdf->stream("Surat_" . Str::slug($termination->employee_name) . ".pdf");
    }
}
