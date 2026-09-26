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
                'organizations' => [],
                'certificates' => [],
                'languages' => [],
                'portfolios' => [],
                'social_links' => [],
            ]);
        }

        return view('candidate.cv_builder', compact('user', 'profile'));
    }

    public function saveProfile(Request $request)
    {
        $user = auth()->user();
        $profile = $user->candidateProfile;

        if (!$profile) {
            $profile = $user->candidateProfile()->create([]);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'summary' => 'nullable|string|max:3000',
            'linkedin' => 'nullable|string|max:255',
            'portfolio' => 'nullable|string|max:255',
            'skills' => 'nullable',
            'experiences' => 'nullable|array',
            'educations' => 'nullable|array',
            'organizations' => 'nullable|array',
            'certificates' => 'nullable|array',
            'languages' => 'nullable|array',
        ]);

        if (!empty($validated['name'])) {
            $user->name = $validated['name'];
            $user->save();
        }

        $profile->current_position = $validated['position'] ?? $profile->current_position;
        $profile->phone = $validated['phone'] ?? $profile->phone;
        $profile->address = $validated['address'] ?? $profile->address;
        $profile->summary = $validated['summary'] ?? $profile->summary;

        // Social Links
        $socialLinks = $profile->social_links ?? [];
        if (isset($validated['linkedin'])) {
            $socialLinks['linkedin'] = $validated['linkedin'];
        }
        if (isset($validated['portfolio'])) {
            $socialLinks['portfolio'] = $validated['portfolio'];
        }
        $profile->social_links = $socialLinks;

        // Skills (handle string or array)
        if (isset($validated['skills'])) {
            if (is_string($validated['skills'])) {
                $skillsArray = array_values(array_filter(array_map('trim', explode(',', $validated['skills']))));
                $profile->skills = $skillsArray;
            } elseif (is_array($validated['skills'])) {
                $profile->skills = $validated['skills'];
            }
        }

        if (isset($validated['experiences'])) {
            $profile->experiences = $validated['experiences'];
        }
        if (isset($validated['educations'])) {
            $profile->educations = $validated['educations'];
        }
        if (isset($validated['organizations'])) {
            $profile->organizations = $validated['organizations'];
        }
        if (isset($validated['certificates'])) {
            $profile->certificates = $validated['certificates'];
        }
        if (isset($validated['languages'])) {
            $profile->languages = $validated['languages'];
        }

        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Data CV dan Profil berhasil disimpan ke sistem!',
        ]);
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

        // Check if form data was submitted via POST to render live customization
        if ($request->isMethod('post') && $request->has('form')) {
            $formData = $request->input('form');
            if (is_string($formData)) {
                $formData = json_decode($formData, true) ?? [];
            }

            if (!empty($formData['name'])) $user->name = $formData['name'];
            if (isset($formData['position'])) $profile->current_position = $formData['position'];
            if (isset($formData['phone'])) $profile->phone = $formData['phone'];
            if (isset($formData['address'])) $profile->address = $formData['address'];
            if (isset($formData['summary'])) $profile->summary = $formData['summary'];
            
            $socialLinks = $profile->social_links ?? [];
            if (!empty($formData['linkedin'])) $socialLinks['linkedin'] = $formData['linkedin'];
            if (!empty($formData['portfolio'])) $socialLinks['portfolio'] = $formData['portfolio'];
            $profile->social_links = $socialLinks;

            if (isset($formData['skillsInput'])) {
                $skillsArray = array_values(array_filter(array_map('trim', explode(',', $formData['skillsInput']))));
                $profile->skills = $skillsArray;
            } elseif (isset($formData['skills'])) {
                $profile->skills = is_array($formData['skills']) ? $formData['skills'] : json_decode($formData['skills'], true);
            }

            if (isset($formData['experiences'])) {
                $profile->experiences = is_array($formData['experiences']) ? $formData['experiences'] : json_decode($formData['experiences'], true);
            }
            if (isset($formData['educations'])) {
                $profile->educations = is_array($formData['educations']) ? $formData['educations'] : json_decode($formData['educations'], true);
            }
            if (isset($formData['organizations'])) {
                $profile->organizations = is_array($formData['organizations']) ? $formData['organizations'] : json_decode($formData['organizations'], true);
            }
            if (isset($formData['certificates'])) {
                $profile->certificates = is_array($formData['certificates']) ? $formData['certificates'] : json_decode($formData['certificates'], true);
            }
            if (isset($formData['languages'])) {
                $profile->languages = is_array($formData['languages']) ? $formData['languages'] : json_decode($formData['languages'], true);
            }
        }

        $format = strtolower($request->input('format', $request->query('format', 'creative')));
        $accentColor = $request->input('color', $request->query('color', '#0f172a'));
        
        $viewName = match($format) {
            'ats' => 'pdf.cv_ats',
            'minimalist' => 'pdf.cv_minimalist',
            default => 'pdf.cv_creative',
        };

        $pdf = Pdf::loadView($viewName, compact('user', 'profile', 'accentColor'))
            ->setPaper('a4', 'portrait');

        $fileName = 'CV_' . strtoupper($format) . '_' . str_replace(' ', '_', preg_replace('/[^A-Za-z0-9_]/', '', $user->name)) . '.pdf';

        return $pdf->download($fileName);
    }
}
