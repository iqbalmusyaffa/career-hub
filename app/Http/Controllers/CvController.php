<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CvController extends Controller
{
    public function builder()
    {
        $user = auth()->user()->load('candidateProfile');
        $profile = $user->candidateProfile;

        if (!$profile) {
            $profile = $user->candidateProfile()->create([
                'phone' => '',
                'summary' => '',
                'skills' => [],
                'experiences' => [],
                'educations' => [],
            ]);
        }

        return view('candidate.cv_builder', compact('user', 'profile'));
    }

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

        $format = strtolower($request->query('format', 'creative'));
        $accentColor = $request->query('color', '#0f172a');
        
        $viewName = match($format) {
            'ats' => 'pdf.cv_ats',
            'minimalist' => 'pdf.cv_minimalist',
            default => 'pdf.cv_creative',
        };

        $pdf = Pdf::loadView($viewName, compact('user', 'profile', 'accentColor'))
            ->setPaper('a4', 'portrait');

        $fileName = 'CV_' . strtoupper($format) . '_' . str_replace(' ', '_', $user->name) . '.pdf';

        return $pdf->download($fileName);
    }
}
