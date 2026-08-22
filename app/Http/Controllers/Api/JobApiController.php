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
            new OA\Parameter(name: "division", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Engineering")),
            new OA\Parameter(name: "work_type", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Full-time")),
            new OA\Parameter(name: "salary_range", in: "query", required: false, schema: new OA\Schema(type: "string", example: "10m_20m")),
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

        if ($request->filled('division')) {
            $query->where('division', $request->division);
        }

        if ($request->filled('job_type') || $request->filled('work_type')) {
            $query->where('work_type', $request->job_type ?? $request->work_type);
        }

        if ($request->filled('experience_level')) {
            $exp = $request->experience_level;
            $query->where(function($q) use ($exp) {
                $q->where('experience_level', $exp)
                  ->orWhere('requirements', 'like', "%{$exp}%");
            });
        }

        if ($request->filled('salary_range')) {
            $sal = $request->salary_range;
            if ($sal === 'under_5m') {
                $query->where(function($q) {
                    $q->where('salary', 'like', '%3.%')
                      ->orWhere('salary', 'like', '%4.%')
                      ->orWhere('salary', 'like', '%Rp 3%')
                      ->orWhere('salary', 'like', '%Rp 4%');
                });
            } elseif ($sal === '5m_10m') {
                $query->where(function($q) {
                    $q->where('salary', 'like', '%5.%')
                      ->orWhere('salary', 'like', '%6.%')
                      ->orWhere('salary', 'like', '%7.%')
                      ->orWhere('salary', 'like', '%8.%')
                      ->orWhere('salary', 'like', '%9.%');
                });
            } elseif ($sal === '10m_20m') {
                $query->where(function($q) {
                    $q->where('salary', 'like', '%10.%')
                      ->orWhere('salary', 'like', '%12.%')
                      ->orWhere('salary', 'like', '%14.%')
                      ->orWhere('salary', 'like', '%15.%')
                      ->orWhere('salary', 'like', '%18.%');
                });
            } elseif ($sal === 'above_20m') {
                $query->where(function($q) {
                    $q->where('salary', 'like', '%20.%')
                      ->orWhere('salary', 'like', '%22.%')
                      ->orWhere('salary', 'like', '%25.%')
                      ->orWhere('salary', 'like', '%30.%');
                });
            }
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
        $realId = \App\Helpers\IdHasher::decode($id) ?? $id;
        $job = Job::with(['user.companyProfile', 'test'])->find($realId);

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
        $realId = \App\Helpers\IdHasher::decode($id) ?? $id;
        $job = Job::find($realId);

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

    #[OA\Get(
        path: "/companies",
        summary: "Direktori Perusahaan Terverifikasi",
        description: "Mengambil daftar perusahaan terpercaya yang bermitra dengan platform.",
        tags: ["Jobs & Search"],
        parameters: [
            new OA\Parameter(name: "search", in: "query", required: false, schema: new OA\Schema(type: "string", example: "TechNova")),
            new OA\Parameter(name: "per_page", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 10))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar perusahaan terverifikasi",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Daftar perusahaan terverifikasi berhasil diambil.",
                        "data" => [
                            "data" => [
                                [
                                    "id" => 1,
                                    "company_name" => "PT TechNova Asia Digital",
                                    "industry" => "Teknologi & Informasi",
                                    "is_verified" => true
                                ]
                            ]
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function companies(Request $request)
    {
        $query = \App\Models\CompanyProfile::query();

        if ($request->filled('search')) {
            $query->where('company_name', 'like', "%{$request->search}%");
        }

        $companies = $query->latest()->paginate((int)$request->query('per_page', 10));

        return $this->successResponse($companies, 'Daftar perusahaan terverifikasi berhasil diambil.');
    }

    #[OA\Get(
        path: "/companies/{name}",
        summary: "Detail Profil Perusahaan & Lowongan Aktif",
        description: "Mengambil profil lengkap perusahaan beserta seluruh lowongan pekerjaan aktif milik perusahaan tersebut.",
        tags: ["Jobs & Search"],
        parameters: [
            new OA\Parameter(name: "name", in: "path", required: true, schema: new OA\Schema(type: "string", example: "PT TechNova Asia Digital"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail profil perusahaan dan lowongan aktif",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Detail profil perusahaan berhasil diambil.",
                        "data" => [
                            "company_name" => "PT TechNova Asia Digital",
                            "active_jobs" => [
                                [
                                    "id" => 1,
                                    "title" => "Senior Backend Developer",
                                    "location" => "Jakarta Selatan"
                                ]
                            ]
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function companyShow($name)
    {
        $decodedName = urldecode($name);
        $company = \App\Models\CompanyProfile::where('company_name', $decodedName)->first();
        $jobs = Job::where('company_name', $decodedName)->where('status', 'active')->latest()->get();

        if (!$company && $jobs->isEmpty()) {
            return $this->errorResponse('Perusahaan tidak ditemukan.', 404);
        }

        return $this->successResponse([
            'company' => $company,
            'company_name' => $decodedName,
            'active_jobs' => $jobs,
        ], 'Detail profil perusahaan berhasil diambil.');
    }
}
