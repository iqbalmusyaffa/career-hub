<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\HrInternalNote;
use Illuminate\Http\Request;

class HrInternalNoteController extends Controller
{
    public function store(Request $request, Application $application)
    {
        $request->validate([
            'note_text' => 'required|string|max:2000',
        ]);

        $application->internalNotes()->create([
            'hr_user_id' => auth()->id(),
            'note_text' => $request->note_text,
            'is_confidential' => true,
        ]);

        \App\Models\AuditLog::record('hr_note_added', "HR " . auth()->user()->name . " menambahkan catatan rahasia internal pada lamaran " . $application->user->name);

        return back()->with('success', 'Catatan rahasia internal HR berhasil disimpan!');
    }

    public function destroy(Application $application, HrInternalNote $note)
    {
        if ($note->hr_user_id !== auth()->id() && !auth()->user()->hasRole('Super Admin')) {
            return back()->with('error', 'Anda hanya dapat menghapus catatan rahasia yang Anda buat.');
        }

        $note->delete();

        return back()->with('success', 'Catatan rahasia internal HR berhasil dihapus.');
    }
}
