<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CandidateCvApiController extends Controller
{
    #[OA\Get(
        path: "/candidate/cv",
        summary: "Ambil Data CV ATS Kandidat",
        description: "Mengambil data lengkap 8 bagian CV standar ATS (profil, ringkasan, pengalaman kerja, pendidikan, keahlian, proyek, sertifikasi, organisasi, dan bahasa).",
        tags: ["Candidate CV Builder"]
    )]
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load('candidateProfile');
        $profile = $user->candidateProfile;

        if (!$profile) {
            $profile = $user->candidateProfile()->create([]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data CV berhasil diambil.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                ],
                'profile' => [
                    'phone' => $profile->phone,
                    'address' => $profile->address,
                    'city' => $profile->city,
                    'province' => $profile->province,
                    'summary' => $profile->summary,
                    'social_links' => $profile->social_links ?? [],
                    'skills' => $profile->skills ?? [],
                    'experiences' => $profile->experiences ?? [],
                    'educations' => $profile->educations ?? [],
                    'organizations' => $profile->organizations ?? [],
                    'certificates' => $profile->certificates ?? [],
                    'languages' => $profile->languages ?? [],
                    'portfolios' => $profile->portfolios ?? [],
                ],
            ]
        ]);
    }

    #[OA\Post(
        path: "/candidate/cv",
        summary: "Simpan & Update Data CV ATS",
        description: "Menyimpan atau memperbarui data 8 bagian CV kandidat.",
        tags: ["Candidate CV Builder"]
    )]
    public function save(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->candidateProfile;

        if (!$profile) {
            $profile = $user->candidateProfile()->create([]);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'summary' => 'nullable|string|max:3000',
            'linkedin' => 'nullable|string|max:255',
            'portfolio' => 'nullable|string|max:255',
            'skills' => 'nullable',
            'experiences' => 'nullable|array',
            'educations' => 'nullable|array',
            'organizations' => 'nullable|array',
            'certificates' => 'nullable|array',
            'languages' => 'nullable|array',
            'portfolios' => 'nullable|array',
        ]);

        if (!empty($validated['name'])) {
            $user->name = $validated['name'];
            $user->save();
        }

        $profile->phone = $validated['phone'] ?? $profile->phone;
        $profile->address = $validated['address'] ?? $profile->address;
        $profile->city = $validated['city'] ?? $profile->city;
        $profile->province = $validated['province'] ?? $profile->province;
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

        // Skills (array or comma string)
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
        if (isset($validated['portfolios'])) {
            $profile->portfolios = $validated['portfolios'];
        }

        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil dan data CV ATS berhasil disimpan.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'profile' => $profile,
            ]
        ]);
    }
}
