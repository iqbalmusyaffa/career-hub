<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class JobApiController extends Controller
{
    #[OA\Get(
        path: "/jobs",
        summary: "Pencarian & Katalog Lowongan Kerja Active",
        description: "Mengambil daftar lowongan kerja aktif dengan fitur pencarian dan pagination.",
        tags: ["Jobs & Search"],
        parameters: [
            new OA\Parameter(name: "search", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Developer")),
            new OA\Parameter(name: "location", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Jakarta")),
            new OA\Parameter(name: "job_type", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Full-time")),
            new OA\Parameter(name: "experience_level", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Senior Level")),
            new OA\Parameter(name: "per_page", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 10))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar lowongan kerja",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Daftar lowongan kerja berhasil diambil.",
                        "data" => [
                            "current_page" => 1,
                            "data" => [
                                [
                                    "id" => 1,
                                    "title" => "Senior Backend Developer",
                                    "company_name" => "PT Tech Nusantara",
                                    "location" => "Jakarta Selatan",
                                    "work_type" => "Full-time",
                                    "salary" => "15.000.000 - 25.000.000",
                                    "quota" => 5,
                                    "status" => "active"
                                ]
                            ],
                            "total" => 1
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function index(Request $request)
    {
        $query = Job::where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('skills_required', 'like', "%{$search}%");
            });
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        if ($request->filled('experience_level')) {
            $query->where('experience_level', $request->experience_level);
        }

        $perPage = (int)$request->query('per_page', 10);
        $jobs = $query->latest()->paginate($perPage);

        return $this->successResponse($jobs, 'Daftar lowongan kerja berhasil diambil.');
    }

    #[OA\Get(
        path: "/jobs/{id}",
        summary: "Detail Lowongan Kerja",
        description: "Mengambil informasi detail lowongan kerja berdasarkan ID.",
        tags: ["Jobs & Search"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail lowongan",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Detail lowongan kerja berhasil diambil.",
                        "data" => [
                            "id" => 1,
                            "title" => "Senior Backend Developer",
                            "company_name" => "PT Tech Nusantara",
                            "division" => "Engineering",
                            "location" => "Jakarta Selatan",
                            "work_type" => "Full-time",
                            "salary" => "15.000.000 - 25.000.000",
                            "description" => "Mengembangkan API dan mikroservis.",
                            "requirements" => "Pengalaman PHP Laravel > 4 tahun.",
                            "benefits" => "Asuransi, Bonus, Remote Option",
                            "quota" => 5,
                            "status" => "active"
                        ],
                        "errors" => null
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Lowongan tidak ditemukan",
                content: new OA\JsonContent(
                    example: [
                        "success" => false,
                        "message" => "Lowongan kerja tidak ditemukan.",
                        "data" => null,
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function show($id)
    {
        $job = Job::with(['user.companyProfile', 'test'])->find($id);

        if (!$job) {
            return $this->errorResponse('Lowongan kerja tidak ditemukan.', 404);
        }

        return $this->successResponse($job, 'Detail lowongan kerja berhasil diambil.');
    }

    #[OA\Post(
        path: "/jobs/{id}/bookmark",
        summary: "Toggle Bookmark / Simpan Lowongan",
        description: "Menyimpan atau menghapus lowongan dari simpanan kandidat.",
        security: [["bearerAuth" => []]],
        tags: ["Jobs & Search"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Status simpan diperbarui",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Lowongan berhasil disimpan!",
                        "data" => [
                            "bookmarked" => true
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function toggleBookmark(Request $request, $id)
    {
        $job = Job::find($id);

        if (!$job) {
            return $this->errorResponse('Lowongan tidak ditemukan.', 404);
        }

        $user = $request->user();
        $existing = SavedJob::where('user_id', $user->id)->where('job_id', $job->id)->first();

        if ($existing) {
            $existing->delete();
            return $this->successResponse(['bookmarked' => false], 'Lowongan telah dihapus dari daftar simpan.');
        }

        SavedJob::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
        ]);

        return $this->successResponse(['bookmarked' => true], 'Lowongan berhasil disimpan!');
    }

    #[OA\Get(
        path: "/candidate/saved-jobs",
        summary: "Daftar Lowongan Tersimpan Pelamar",
        description: "Mengambil seluruh lowongan kerja yang telah disimpan oleh kandidat.",
        security: [["bearerAuth" => []]],
        tags: ["Jobs & Search"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar lowongan tersimpan",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Daftar lowongan tersimpan berhasil diambil.",
                        "data" => [
                            [
                                "id" => 1,
                                "title" => "Senior Backend Developer",
                                "company_name" => "PT Tech Nusantara"
                            ]
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function savedJobs(Request $request)
    {
        $savedJobs = $request->user()->bookmarkedJobs()->latest()->get();

        return $this->successResponse($savedJobs, 'Daftar lowongan tersimpan berhasil diambil.');
    }
}
