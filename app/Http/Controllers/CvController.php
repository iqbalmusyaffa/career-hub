<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CvController extends Controller
{
    public function download(Request $request, $userId = null)
    {
        $targetUserId = $userId ?? auth()->id();
        
        // Authorization: candidate can download own CV, HR/Admin can download any candidate's CV
        if ($targetUserId != auth()->id()) {
            if (!auth()->user()->hasRole('HR') && !auth()->user()->hasRole('Super Admin')) {
                abort(403, 'Anda tidak memiliki akses.');
            }
        }

        $user = User::with('candidateProfile')->findOrFail($targetUserId);
        $profile = $user->candidateProfile;

        if (!$profile) {
            return back()->with('error', 'Profil kandidat belum diisi.');
        }

        $format = $request->query('format', 'creative');
        $viewName = ($format === 'ats') ? 'pdf.cv_ats' : 'pdf.cv_creative';

        $pdf = Pdf::loadView($viewName, compact('user', 'profile'))
            ->setPaper('a4', 'portrait');

        $fileName = 'CV_' . strtoupper($format) . '_' . str_replace(' ', '_', $user->name) . '.pdf';

        return $pdf->download($fileName);
    }
}
