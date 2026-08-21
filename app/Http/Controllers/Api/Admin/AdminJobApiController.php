<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\CompanyProfile;
use App\Models\CompanyBranch;
use App\Models\CompanyTeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class AdminJobApiController extends Controller
{
    #[OA\Get(
        path: "/admin/jobs",
        summary: "[HR] Daftar Lowongan Perusahaan",
        description: "Mengambil seluruh daftar lowongan kerja HR/Company saat ini.",
        security: [["bearerAuth" => []]],
        tags: ["HR Job Postings & Company"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar lowongan kerja HR",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Daftar lowongan kerja perusahaan berhasil diambil.",
                        "data" => [
                            "current_page" => 1,
                            "data" => [
                                [
                                    "id" => 1,
                                    "title" => "Senior Backend Developer",
                                    "company_name" => "PT Tech Nusantara",
                                    "applications_count" => 12,
                                    "status" => "active"
                                ]
                            ]
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Job::query();

        if (!$user->hasRole('Super Admin')) {
            $query->where('user_id', $user->id);
        }

        $jobs = $query->withCount('applications')->latest()->paginate(15);

        return $this->successResponse($jobs, 'Daftar lowongan kerja perusahaan berhasil diambil.');
    }

    #[OA\Post(
        path: "/admin/jobs",
        summary: "[HR] Buat Lowongan Pekerjaan Baru",
        description: "Membuat postingan lowongan kerja baru.",
        security: [["bearerAuth" => []]],
        tags: ["HR Job Postings & Company"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title", "job_type", "location", "salary_min", "salary_max", "description"],
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Senior Backend Developer"),
                    new OA\Property(property: "job_type", type: "string", example: "Full-time"),
                    new OA\Property(property: "location", type: "string", example: "Jakarta Selatan"),
                    new OA\Property(property: "department", type: "string", example: "Engineering"),
                    new OA\Property(property: "experience_level", type: "string", example: "Senior Level"),
                    new OA\Property(property: "salary_min", type: "number", example: 15000000),
                    new OA\Property(property: "salary_max", type: "number", example: 25000000),
                    new OA\Property(property: "description", type: "string", example: "Mengembangkan API"),
                    new OA\Property(property: "quota", type: "integer", example: 5)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Lowongan berhasil dibuat",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Lowongan pekerjaan baru berhasil dipublikasikan!",
                        "data" => [
                            "id" => 1,
                            "title" => "Senior Backend Developer",
                            "company_name" => "PT Tech Nusantara",
                            "status" => "active"
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'job_type' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'department' => 'nullable|string|max:100',
            'experience_level' => 'nullable|string|max:100',
            'salary_min' => 'required|numeric|min:0',
            'salary_max' => 'required|numeric|min:0',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'quota' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi pembuat lowongan gagal.', 422, $validator->errors());
        }

        $user = $request->user();
        $company = $user->companyProfile;

        $job = Job::create(array_merge($validator->validated(), [
            'user_id' => $user->id,
            'company_name' => $company ? $company->company_name : $user->name,
            'status' => 'active',
        ]));

        return $this->successResponse($job, 'Lowongan pekerjaan baru berhasil dipublikasikan!', 201);
    }

    #[OA\Put(
        path: "/admin/jobs/{id}",
        summary: "[HR] Perbarui Lowongan Pekerjaan",
        description: "Memperbarui data lowongan kerja.",
        security: [["bearerAuth" => []]],
        tags: ["HR Job Postings & Company"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lowongan berhasil diperbarui",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Lowongan pekerjaan berhasil diperbarui.",
                        "data" => [
                            "id" => 1,
                            "title" => "Senior Backend Engineer",
                            "status" => "active"
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $job = Job::find($id);

        if (!$job) {
            return $this->errorResponse('Lowongan pekerjaan tidak ditemukan.', 404);
        }

        $user = $request->user();
        if ($job->user_id !== $user->id && !$user->hasRole('Super Admin')) {
            return $this->errorResponse('Anda tidak berhak mengubah lowongan ini.', 403);
        }

        $job->update($request->all());

        return $this->successResponse($job, 'Lowongan pekerjaan berhasil diperbarui.');
    }

    #[OA\Delete(
        path: "/admin/jobs/{id}",
        summary: "[HR] Hapus Lowongan Pekerjaan",
        description: "Menghapus postingan lowongan kerja.",
        security: [["bearerAuth" => []]],
        tags: ["HR Job Postings & Company"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lowongan berhasil dihapus",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Lowongan pekerjaan berhasil dihapus.",
                        "data" => null,
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function destroy(Request $request, $id)
    {
        $job = Job::find($id);

        if (!$job) {
            return $this->errorResponse('Lowongan tidak ditemukan.', 404);
        }

        $user = $request->user();
        if ($job->user_id !== $user->id && !$user->hasRole('Super Admin')) {
            return $this->errorResponse('Anda tidak berhak menghapus lowongan ini.', 403);
        }

        $job->delete();

        return $this->successResponse(null, 'Lowongan pekerjaan berhasil dihapus.');
    }

    #[OA\Get(
        path: "/admin/company-profile",
        summary: "[HR] Profil Perusahaan & Cabang",
        description: "Mengambil profil perusahaan dan daftar cabang perusahaan.",
        security: [["bearerAuth" => []]],
        tags: ["HR Job Postings & Company"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Profil perusahaan",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Profil perusahaan berhasil diambil.",
                        "data" => [
                            "company_profile" => [
                                "company_name" => "PT Tech Nusantara",
                                "industry" => "Information Technology",
                                "address" => "Jl. Sudirman No. 45, Jakarta",
                                "is_verified" => true
                            ],
                            "branches" => [],
                            "team_members" => []
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function companyProfile(Request $request)
    {
        $user = $request->user();
        $profile = CompanyProfile::firstOrCreate(['user_id' => $user->id], [
            'company_name' => $user->name . ' Corp',
        ]);
        $branches = CompanyBranch::where('company_profile_id', $profile->id)->get();
        $team = CompanyTeamMember::where('company_profile_id', $profile->id)->get();

        return $this->successResponse([
            'company_profile' => $profile,
            'branches' => $branches,
            'team_members' => $team,
        ], 'Profil perusahaan berhasil diambil.');
    }
}
