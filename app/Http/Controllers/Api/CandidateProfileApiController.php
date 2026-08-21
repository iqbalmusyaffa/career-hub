<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CandidateProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class CandidateProfileApiController extends Controller
{
    #[OA\Get(
        path: "/candidate/profile",
        summary: "Detail Profil Pelamar Terautentikasi",
        description: "Mengambil profil lengkap kandidat.",
        security: [["bearerAuth" => []]],
        tags: ["Candidate Profile"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Data profil kandidat",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Profil kandidat berhasil diambil.",
                        "data" => [
                            "user" => [
                                "id" => 1,
                                "name" => "Budi Pratama",
                                "email" => "budi@example.com",
                                "avatar" => "avatars/sample.jpg"
                            ],
                            "profile" => [
                                "phone" => "081234567890",
                                "nickname" => "Budi",
                                "bio" => "Software Engineer berpengalaman 3 tahun",
                                "education" => "S1 Teknik Informatika - Universitas Indonesia",
                                "experience" => "Fullstack Developer at Tech Corp (2023-2026)",
                                "skills" => "PHP, Laravel, Vue.js, MySQL, REST API",
                                "portfolio_website" => "https://budi.dev",
                                "expected_salary" => 12000000
                            ]
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function show(Request $request)
    {
        $user = $request->user();
        $profile = CandidateProfile::firstOrCreate(['user_id' => $user->id]);

        return $this->successResponse([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
            ],
            'profile' => $profile,
        ], 'Profil kandidat berhasil diambil.');
    }

    #[OA\Put(
        path: "/candidate/profile",
        summary: "Perbarui Detail Profil Pelamar",
        description: "Memperbarui data bio, pendidikan, pengalaman kerja, keahlian, dan kontak kandidat.",
        security: [["bearerAuth" => []]],
        tags: ["Candidate Profile"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "phone", type: "string", example: "081234567890"),
                    new OA\Property(property: "nickname", type: "string", example: "Budi"),
                    new OA\Property(property: "bio", type: "string", example: "Senior Fullstack Developer"),
                    new OA\Property(property: "education", type: "string", example: "S1 Teknik Informatika"),
                    new OA\Property(property: "experience", type: "string", example: "Software Engineer at Tech Corp"),
                    new OA\Property(property: "skills", type: "string", example: "PHP, Laravel, Docker, React"),
                    new OA\Property(property: "portfolio_website", type: "string", example: "https://budi.dev"),
                    new OA\Property(property: "expected_salary", type: "number", example: 15000000)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Profil diperbarui",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Profil kandidat berhasil diperbarui!",
                        "data" => [
                            "phone" => "081234567890",
                            "bio" => "Senior Fullstack Developer",
                            "skills" => "PHP, Laravel, Docker, React",
                            "expected_salary" => 15000000
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function update(Request $request)
    {
        $user = $request->user();
        $profile = CandidateProfile::firstOrCreate(['user_id' => $user->id]);

        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|string|max:30',
            'nickname' => 'nullable|string|max:50',
            'bio' => 'nullable|string',
            'education' => 'nullable|string',
            'experience' => 'nullable|string',
            'skills' => 'nullable|string',
            'portfolio_website' => 'nullable|url',
            'expected_salary' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi pembaruan profil gagal.', 422, $validator->errors());
        }

        $profile->update($request->only([
            'phone', 'nickname', 'bio', 'education', 'experience',
            'skills', 'portfolio_website', 'expected_salary'
        ]));

        return $this->successResponse($profile, 'Profil kandidat berhasil diperbarui!');
    }

    #[OA\Post(
        path: "/candidate/profile/photo",
        summary: "Upload Foto Profil Candidate",
        description: "Mengunggah foto profil pengguna.",
        security: [["bearerAuth" => []]],
        tags: ["Candidate Profile"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "photo", type: "string", format: "binary")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Foto berhasil diunggah",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Foto profil berhasil diperbarui.",
                        "data" => [
                            "avatar_url" => "http://localhost/storage/avatars/sample.jpg"
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function uploadPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Foto tidak valid (harus JPG/PNG max 3MB).', 422, $validator->errors());
        }

        $user = $request->user();
        $path = $request->file('photo')->store('avatars', 'public');

        $user->update(['avatar' => $path]);

        return $this->successResponse(['avatar_url' => asset('storage/' . $path)], 'Foto profil berhasil diperbarui.');
    }
}
