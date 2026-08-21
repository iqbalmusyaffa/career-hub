<?php

namespace App\Http\Controllers;

use App\Models\CandidateDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidateDocumentController extends Controller
{
    /**
     * Display candidate's document vault interface.
     */
    public function index()
    {
        $user = Auth::user();
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
