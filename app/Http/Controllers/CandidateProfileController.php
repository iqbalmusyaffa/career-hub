<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CandidateProfileController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = $request->user();
        $profile = $user->candidateProfile()->firstOrCreate();

        $data = [];
        if ($request->hasFile('photo')) {
            if ($profile->photo_path) {
                Storage::disk('public')->delete($profile->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('photos', 'public');
            $profile->update($data);
        }

        return redirect()->route('profile.edit')->with('status', 'candidate-profile-updated');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = $request->user();
        $profile = $user->candidateProfile()->firstOrCreate(['user_id' => $user->id]);

        if ($profile->photo_path) {
            Storage::disk('public')->delete($profile->photo_path);
        }
        if ($profile->photo) {
            Storage::disk('public')->delete($profile->photo);
        }

        $userFolder = 'candidate_files/' . \Illuminate\Support\Str::slug($user->name) . '_' . $user->id;
        $path = $request->file('photo')->store($userFolder, 'public');
        $profile->update([
            'photo' => $path,
            'photo_path' => $path,
        ]);

        return response()->json([
            'success' => true,
            'url' => Storage::url($path),
            'message' => 'Pas foto berhasil diunggah & disimpan otomatis!',
        ]);
    }

    public function uploadDocument(Request $request)
    {
        try {
            $fileType = $request->input('document_type');

            $allowedMap = [
                'cv' => 'cv_path',
                'ktp' => 'ktp_path',
                'ijazah' => 'ijazah_path',
                'transcript' => 'transcript_path',
                'certificate' => 'certificate_file_path',
                'portfolio' => 'portfolio_file_path',
                'cover_letter' => 'cover_letter_path',
                'skck' => 'skck_path',
                'health_certificate' => 'health_certificate_path',
                'consent_letter' => 'consent_letter_path',
            ];

            if (!isset($allowedMap[$fileType])) {
                return response()->json(['success' => false, 'message' => 'Tipe dokumen tidak valid (' . $fileType . ')'], 422);
            }

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'document' => 'required|file|max:10240',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $field = $allowedMap[$fileType];
            $user = $request->user();
            $profile = $user->candidateProfile()->firstOrCreate(['user_id' => $user->id]);

            if ($profile->$field) {
                Storage::disk('public')->delete($profile->$field);
            }

            $userFolder = 'candidate_files/' . \Illuminate\Support\Str::slug($user->name) . '_' . $user->id;
            $uploadedFile = $request->file('document');
            $path = $uploadedFile->store($userFolder, 'public');
            $profile->update([$field => $path]);

            // Sync automatically to Candidate Document Vault
            $this->syncToCandidateDocumentVault($user, $fileType, $path, $uploadedFile);

            return response()->json([
                'success' => true,
                'url' => Storage::url($path),
                'document_type' => $fileType,
                'message' => 'Dokumen berhasil diunggah & disimpan otomatis ke Vault Dokumen!',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Upload Document Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function editDetails(Request $request)
    {
        $user = $request->user();
        $profile = $user->candidateProfile;
        return view('profile.candidate-details', compact('user', 'profile'));
    }

    public function updateDetails(Request $request)
    {
        $validated = $request->validate([
            // User Info
            'name' => 'required|string|max:255',
            
            // Personal Info
            'nickname' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'birth_place' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'nationality' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'summary' => 'required|string',
            
            // New Indonesian Standard Fields
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'marital_status' => 'nullable|string|in:Lajang,Menikah,Cerai',
            'religion' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:20',
            'current_salary' => 'nullable|string|max:50',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            
            // JSON Arrays
            'educations' => 'nullable|array',
            'experiences' => 'nullable|array',
            'organizations' => 'nullable|array',
            'skills' => 'nullable|array',
            'languages' => 'nullable|array',
            'certificates' => 'nullable|array',
            'portfolios' => 'nullable|array',
            'achievements' => 'nullable|array',
            'references' => 'nullable|array',
            'job_preferences' => 'nullable|array',
            'social_links' => 'nullable|array',
            
            // Files
            'cv' => 'nullable|file|mimes:pdf|max:5120',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'transcript' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'certificate_file' => 'nullable|file|mimes:pdf,zip|max:10240',
            'portfolio_file' => 'nullable|file|mimes:pdf,zip|max:10240',
            'cover_letter' => 'nullable|file|mimes:pdf|max:5120',
            'skck' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'health_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'consent_letter' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $user = $request->user();
        
        // Update user name
        if (isset($validated['name'])) {
            $user->update(['name' => $validated['name']]);
        }

        $profile = $user->candidateProfile()->firstOrCreate();
        
        $data = collect($validated)->except([
            'photo', 'cv', 'ktp', 'ijazah', 'transcript', 'certificate_file', 'portfolio_file', 'name',
            'cover_letter', 'skck', 'health_certificate', 'consent_letter'
        ])->toArray();

        // Handle File Uploads
        $files = [
            'photo' => 'photo',
            'cv' => 'cv_path',
            'ktp' => 'ktp_path',
            'ijazah' => 'ijazah_path',
            'transcript' => 'transcript_path',
            'certificate_file' => 'certificate_file_path',
            'portfolio_file' => 'portfolio_file_path',
            'cover_letter' => 'cover_letter_path',
            'skck' => 'skck_path',
            'health_certificate' => 'health_certificate_path',
            'consent_letter' => 'consent_letter_path',
        ];

        $userFolder = 'candidate_files/' . \Illuminate\Support\Str::slug($user->name) . '_' . $user->id;

        foreach ($files as $input => $column) {
            if ($request->hasFile($input)) {
                // Delete old file if exists
                if ($profile->$column) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($profile->$column);
                }
                $uploadedFile = $request->file($input);
                $storedPath = $uploadedFile->store($userFolder, 'public');
                $data[$column] = $storedPath;

                // Sync automatically to Candidate Document Vault
                if ($input !== 'photo') {
                    $this->syncToCandidateDocumentVault($user, $input, $storedPath, $uploadedFile);
                }
            }
        }

        $profile->update($data);

        return redirect()->route('profile.candidate.details.edit')->with('status', 'candidate-details-updated');
    }

    /**
     * Helper method to sync uploaded candidate document automatically to Vault Dokumen Pendukung.
     */
    private function syncToCandidateDocumentVault($user, $fileType, $filePath, $file = null)
    {
        $vaultTitleMap = [
            'cv' => 'Curriculum Vitae (CV)',
            'ktp' => 'KTP / Kartu Identitas',
            'ijazah' => 'Ijazah Pendidikan',
            'transcript' => 'Transkrip Nilai Akademik',
            'certificate' => 'Sertifikat Keahlian',
            'certificate_file' => 'Sertifikat Keahlian',
            'portfolio' => 'Portofolio Berkas Karya',
            'portfolio_file' => 'Portofolio Berkas Karya',
            'cover_letter' => 'Surat Lamaran Kerja',
            'skck' => 'SKCK Kepolisian',
            'health_certificate' => 'Surat Keterangan Sehat',
            'consent_letter' => 'Surat Persetujuan',
        ];

        $docTypeNormalized = match ($fileType) {
            'transcript' => 'transkrip',
            'certificate_file' => 'certificate',
            'portfolio_file' => 'portfolio',
            default => $fileType,
        };

        $docTitle = $vaultTitleMap[$fileType] ?? ucfirst($docTypeNormalized);
        $fileSize = $file ? $file->getSize() : (Storage::disk('public')->exists($filePath) ? Storage::disk('public')->size($filePath) : 0);
        $fileExt = $file ? strtolower($file->getClientOriginalExtension()) : pathinfo($filePath, PATHINFO_EXTENSION);

        \App\Models\CandidateDocument::updateOrCreate(
            [
                'user_id' => $user->id,
                'document_type' => $docTypeNormalized,
            ],
            [
                'title' => $docTitle,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'file_extension' => $fileExt,
                'updated_at' => now(),
            ]
        );
    }

    public function downloadConsentTemplate(Request $request)
    {
        $user = $request->user();
        $profile = $user->candidateProfile;

        $pdf = Pdf::loadView('pdf.consent-template', [
            'name' => $user->name,
            'nik' => $profile->nik ?? '______________________',
            'address' => $profile->address ?? '____________________________________________________',
            'date' => now()->translatedFormat('d F Y')
        ]);

        return $pdf->download('Template_Surat_Pernyataan_Persetujuan_' . str_replace(' ', '_', $user->name) . '.pdf');
    }
}
