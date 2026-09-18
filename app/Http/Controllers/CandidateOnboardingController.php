<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\CandidateOnboarding;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidateOnboardingController extends Controller
{
    /**
     * Display the onboarding employee data form (ONLY FOR ACCEPTED/HIRED CANDIDATES).
     */
    public function create($applicationId)
    {
        $realAppId = \App\Helpers\IdHasher::decode($applicationId) ?? $applicationId;
        $application = Application::with(['job', 'onboarding'])->where('user_id', Auth::id())->findOrFail($realAppId);

        $statusStr = is_object($application->status) ? $application->status->value : (string) $application->status;
        
        // Strict Logic: Only accepted/hired candidates can access this form
        if (!in_array($statusStr, ['accepted', 'hired'])) {
            return redirect()->route('dashboard')->with('error', 'Formulir data onboarding hanya dapat diisi oleh pelamar yang telah dinyatakan DITERIMA (Hired).');
        }

        $onboarding = $application->onboarding;

        return view('candidate.onboarding.form', compact('application', 'onboarding'));
    }

    /**
     * Store or Update Onboarding Employee Data and Document Attachments (PDF/PNG/JPG).
     */
    public function store(Request $request, $applicationId)
    {
        $realAppId = \App\Helpers\IdHasher::decode($applicationId) ?? $applicationId;
        $application = Application::with('job')->where('user_id', Auth::id())->findOrFail($realAppId);

        $statusStr = is_object($application->status) ? $application->status->value : (string) $application->status;

        if (!in_array($statusStr, ['accepted', 'hired'])) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak: Lamaran belum dinyatakan diterima.');
        }

        $request->validate([
            'bank_name' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_holder' => 'required|string|max:255',
            'student_id_number' => 'nullable|string|max:50',
            'institution_name' => 'nullable|string|max:255',
            'internship_letter_doc' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'npwp_number' => 'nullable|string|max:50',
            'npwp_doc' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'bpjs_kesehatan_number' => 'nullable|string|max:50',
            'bpjs_kesehatan_doc' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'bpjs_ketenagakerjaan_number' => 'nullable|string|max:50',
            'bpjs_ketenagakerjaan_doc' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'family_card_number' => 'nullable|string|max:50',
            'family_card_doc' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        $onboarding = CandidateOnboarding::firstOrNew([
            'application_id' => $application->id,
            'user_id' => Auth::id(),
        ]);

        $onboarding->bank_name = $request->bank_name;
        $onboarding->bank_account_number = $request->bank_account_number;
        $onboarding->bank_account_holder = $request->bank_account_holder;
        $onboarding->student_id_number = $request->student_id_number;
        $onboarding->institution_name = $request->institution_name;
        $onboarding->npwp_number = $request->npwp_number;
        $onboarding->bpjs_kesehatan_number = $request->bpjs_kesehatan_number;
        $onboarding->bpjs_ketenagakerjaan_number = $request->bpjs_ketenagakerjaan_number;
        $onboarding->family_card_number = $request->family_card_number;
        $onboarding->notes = $request->notes;

        $docDirectory = 'onboarding_docs';

        // Upload File Attachments
        if ($request->hasFile('internship_letter_doc')) {
            $onboarding->internship_letter_doc_path = $request->file('internship_letter_doc')->store($docDirectory, 'public');
        }

        if ($request->hasFile('npwp_doc')) {
            $onboarding->npwp_doc_path = $request->file('npwp_doc')->store($docDirectory, 'public');
        }

        if ($request->hasFile('bpjs_kesehatan_doc')) {
            $onboarding->bpjs_kesehatan_doc_path = $request->file('bpjs_kesehatan_doc')->store($docDirectory, 'public');
        }

        if ($request->hasFile('bpjs_ketenagakerjaan_doc')) {
            $onboarding->bpjs_ketenagakerjaan_doc_path = $request->file('bpjs_ketenagakerjaan_doc')->store($docDirectory, 'public');
        }

        if ($request->hasFile('family_card_doc')) {
            $onboarding->family_card_doc_path = $request->file('family_card_doc')->store($docDirectory, 'public');
        }

        $onboarding->verification_status = 'pending';
        $onboarding->save();

        // Immediate Sync to Candidate Document Vault
        $companyName = $application->job->company_name ?? 'Perusahaan';
        $syncItems = [
            'Dokumen NPWP' => $onboarding->npwp_doc_path,
            'Kartu BPJS Kesehatan' => $onboarding->bpjs_kesehatan_doc_path,
            'Kartu BPJS Ketenagakerjaan' => $onboarding->bpjs_ketenagakerjaan_doc_path,
            'Kartu Keluarga (KK)' => $onboarding->family_card_doc_path,
            'Surat Pengantar Magang Kampus' => $onboarding->internship_letter_doc_path,
        ];

        foreach ($syncItems as $title => $path) {
            if ($path) {
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $fullPath = storage_path('app/public/' . $path);
                $size = file_exists($fullPath) ? filesize($fullPath) : null;

                \App\Models\CandidateDocument::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'file_path' => $path,
                    ],
                    [
                        'document_type' => 'other',
                        'title' => $title . " - " . $companyName,
                        'file_size' => $size,
                        'file_extension' => $ext,
                    ]
                );
            }
        }

        // Check KTP Name Match & Notify Candidate if Different
        $user = Auth::user();
        if ($onboarding->bank_account_holder && $user->name) {
            $cleanHolder = strtolower(preg_replace('/[^a-z0-9]/', '', $onboarding->bank_account_holder));
            $cleanName = strtolower(preg_replace('/[^a-z0-9]/', '', $user->name));
            $isKtpMatched = str_contains($cleanHolder, $cleanName) || str_contains($cleanName, $cleanHolder) || levenshtein($cleanHolder, $cleanName) <= 3;

            if (!$isKtpMatched) {
                \App\Models\UserNotification::send(
                    $user->id,
                    '⚠️ Peringatan: Nama Rekening Berbeda dengan KTP',
                    "Nama pemilik rekening bank yang Anda masukkan ({$onboarding->bank_account_holder}) berbeda dengan nama KTP akun Anda ({$user->name}). Pastikan foto buku tabungan/surat kuasa telah diunggah dengan jelas agar pencairan uang saku tidak tertunda.",
                    route('candidate.onboarding.create', $application),
                    'warning'
                );
            }
        }

        AuditLog::record('onboarding_submitted', "Kandidat " . Auth::user()->name . " mengunggah berkas data onboarding untuk posisi " . $application->job->title);

        return redirect()->route('dashboard')->with('success', '🎉 Berhasil! Data onboarding & rekening insentif Anda telah tersimpan dan terkirim ke Tim HR.');
    }

    /**
     * HR Action: Verify or Request Re-upload for Candidate Onboarding Documents.
     */
    public function verifyByHr(Request $request, $applicationId)
    {
        $realAppId = \App\Helpers\IdHasher::decode($applicationId) ?? $applicationId;
        $application = Application::findOrFail($realAppId);
        $onboarding = CandidateOnboarding::where('application_id', $application->id)->firstOrFail();

        $action = $request->input('action', 'verify');

        if ($action === 'reject') {
            $request->validate([
                'rejection_note' => 'required|string|max:500',
            ]);

            $onboarding->verification_status = 'rejected';
            $onboarding->notes = "Penolakan HR: " . $request->rejection_note;
            $onboarding->save();

            // Send Notification to candidate
            \App\Models\UserNotification::send(
                $application->user_id,
                "⚠️ Permintaan Perbaikan Berkas Onboarding",
                "Tim HR meminta perbaikan/unggah ulang berkas onboarding Anda: {$request->rejection_note}. Silakan buka formulir untuk memperbarui.",
                route('candidate.onboarding.create', $application),
                'warning'
            );

            AuditLog::record('onboarding_rejected', "HR " . Auth::user()->name . " meminta perbaikan berkas onboarding " . $application->user->name . " dengan alasan: " . $request->rejection_note);

            return back()->with('success', 'Permintaan perbaikan berkas onboarding telah dikirimkan ke pelamar!');
        }

        $onboarding->verification_status = 'verified';
        $onboarding->verified_at = now();
        $onboarding->save();

        // Send Notification to candidate
        \App\Models\UserNotification::send(
            $application->user_id,
            "✅ Berkas Onboarding Karyawan Terverifikasi!",
            "Tim HR telah memverifikasi data rekening bank, NPWP, dan BPJS Anda. Selamat bergabung di perusahaan!",
            route('dashboard'),
            'success'
        );

        AuditLog::record('onboarding_verified', "HR " . Auth::user()->name . " memverifikasi data onboarding karyawan " . $application->user->name);

        return back()->with('success', 'Berkas Onboarding & Data Bank pelamar berhasil DIVERIFIKASI!');
    }
}
