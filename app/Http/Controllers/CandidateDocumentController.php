<?php

namespace App\Http\Controllers;

use App\Models\CandidateDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidateDocumentController extends Controller
{
    /**
     * Display candidate's document vault interface with auto-synced onboarding docs.
     */
    public function index()
    {
        $user = Auth::user();

        // Auto-sync any Onboarding Documents (NPWP, BPJS, KK, Surat Magang) into Document Vault
        $onboardings = \App\Models\CandidateOnboarding::where('user_id', $user->id)->with('application.job')->get();
        foreach ($onboardings as $ob) {
            $companyName = $ob->application->job->company_name ?? 'Perusahaan';
            
            $items = [
                'Dokumen NPWP' => $ob->npwp_doc_path,
                'Kartu BPJS Kesehatan' => $ob->bpjs_kesehatan_doc_path,
                'Kartu BPJS Ketenagakerjaan' => $ob->bpjs_ketenagakerjaan_doc_path,
                'Kartu Keluarga (KK)' => $ob->family_card_doc_path,
                'Surat Pengantar Magang Kampus' => $ob->internship_letter_doc_path,
            ];

            foreach ($items as $title => $path) {
                if ($path) {
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $fullPath = storage_path('app/public/' . $path);
                    $size = file_exists($fullPath) ? filesize($fullPath) : null;

                    CandidateDocument::updateOrCreate(
                        [
                            'user_id' => $user->id,
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
        }

        $documents = CandidateDocument::where('user_id', $user->id)->latest()->get();

        return view('profile.documents', compact('documents'));
    }

    /**
     * Store new document in candidate vault.
     */
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|in:ijazah,transkrip,ktp,skck,certificate,portfolio,other',
            'title' => 'required|string|max:255',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $user = Auth::user();
        $file = $request->file('document_file');
        
        $userFolder = 'vault_documents/' . \Illuminate\Support\Str::slug($user->name) . '_' . $user->id;
        $filePath = $file->store($userFolder, 'public');
        $fileSize = $file->getSize();
        $fileExtension = strtolower($file->getClientOriginalExtension());

        CandidateDocument::create([
            'user_id' => $user->id,
            'document_type' => $request->document_type,
            'title' => $request->title,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'file_extension' => $fileExtension,
        ]);

        \App\Models\AuditLog::record('document_uploaded', "Kandidat {$user->name} mengunggah berkas {$request->title} ke Vault Dokumen");

        return back()->with('success', 'Berkas dokumen pendukung berhasil diunggah ke Vault Anda!');
    }

    /**
     * Delete document from candidate vault.
     */
    public function destroy($id)
    {
        $document = CandidateDocument::findByEncryptedIdOrFail($id);

        if ($document->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Berkas dokumen berhasil dihapus dari Vault!');
    }
}
