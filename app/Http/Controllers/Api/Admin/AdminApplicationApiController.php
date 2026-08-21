<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CandidateEvaluation;
use App\Models\Interview;
use App\Models\OfferLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class AdminApplicationApiController extends Controller
{
    #[OA\Get(
        path: "/admin/applications",
        summary: "[HR] Daftar Pelamar Masuk (Applicant Pipeline)",
        description: "Mengambil daftar seluruh berkas pelamar yang masuk.",
        security: [["bearerAuth" => []]],
        tags: ["HR Recruitment Pipeline"],
        parameters: [
            new OA\Parameter(name: "job_id", in: "query", required: false, schema: new OA\Schema(type: "integer", example: 1)),
            new OA\Parameter(name: "status", in: "query", required: false, schema: new OA\Schema(type: "string", example: "pending"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar pelamar",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Daftar pelamar berhasil diambil.",
                        "data" => [
                            "current_page" => 1,
                            "data" => [
                                [
                                    "id" => 10,
                                    "user" => [
                                        "name" => "Budi Pratama",
                                        "email" => "budi@example.com"
                                    ],
                                    "job" => [
                                        "title" => "Senior Backend Developer"
                                    ],
                                    "status" => "pending"
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
        $query = Application::with(['user.candidateProfile', 'job']);

        if (!$user->hasRole('Super Admin')) {
            $userJobIds = \App\Models\Job::where('user_id', $user->id)->pluck('id');
            $query->whereIn('job_id', $userJobIds);
        }

        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(15);

        return $this->successResponse($applications, 'Daftar pelamar berhasil diambil.');
    }

    #[OA\Get(
        path: "/admin/applications/{id}",
        summary: "[HR] Detail Pelamar & Evaluasi",
        description: "Mengambil informasi detail pelamar.",
        security: [["bearerAuth" => []]],
        tags: ["HR Recruitment Pipeline"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 10))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail pelamar",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Detail pelamar berhasil diambil.",
                        "data" => [
                            "id" => 10,
                            "status" => "interview",
                            "cover_letter" => "Saya berminat...",
                            "user" => [
                                "name" => "Budi Pratama",
                                "email" => "budi@example.com",
                                "candidate_profile" => [
                                    "phone" => "081234567890",
                                    "skills" => "PHP, Laravel, Vue.js"
                                ]
                            ],
                            "evaluations" => [],
                            "internal_notes" => []
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function show(Request $request, $id)
    {
        $application = Application::with(['user.candidateProfile', 'user.candidateDocuments', 'job', 'evaluations', 'internalNotes', 'interviews', 'offerLetter'])
            ->find($id);

        if (!$application) {
            return $this->errorResponse('Lamaran tidak ditemukan.', 404);
        }

        return $this->successResponse($application, 'Detail pelamar berhasil diambil.');
    }

    #[OA\Patch(
        path: "/admin/applications/{id}/status",
        summary: "[HR] Update Status Tahap Seleksi Pelamar",
        description: "Mengubah status seleksi kandidat (pending, review, shortlisted, interview, hired, rejected).",
        security: [["bearerAuth" => []]],
        tags: ["HR Recruitment Pipeline"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 10))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["status"],
                properties: [
                    new OA\Property(property: "status", type: "string", example: "shortlisted")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Status berhasil diperbarui",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Status tahap seleksi pelamar berhasil diperbarui.",
                        "data" => [
                            "id" => 10,
                            "status" => "shortlisted"
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function updateStatus(Request $request, $id)
    {
        $application = Application::find($id);

        if (!$application) {
            return $this->errorResponse('Lamaran tidak ditemukan.', 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,review,shortlisted,interview,hired,rejected',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Status tidak valid.', 422, $validator->errors());
        }

        $application->update(['status' => $request->status]);

        return $this->successResponse($application, 'Status tahap seleksi pelamar berhasil diperbarui.');
    }

    #[OA\Post(
        path: "/admin/applications/{id}/schedule-interview",
        summary: "[HR] Penjadwalan Wawancara (Interview)",
        description: "Membuat jadwal interview baru untuk kandidat.",
        security: [["bearerAuth" => []]],
        tags: ["HR Recruitment Pipeline"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 10))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["interview_date", "location_or_link"],
                properties: [
                    new OA\Property(property: "interview_date", type: "string", format: "date-time", example: "2026-09-01T10:00:00"),
                    new OA\Property(property: "location_or_link", type: "string", example: "Google Meet: https://meet.google.com/abc-defg-hij"),
                    new OA\Property(property: "notes", type: "string", example: "Persiapkan presentasi.")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Interview berhasil dijadwalkan",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Jadwal wawancara berhasil dikirimkan ke pelamar.",
                        "data" => [
                            "id" => 1,
                            "application_id" => 10,
                            "interview_date" => "2026-09-01 10:00:00",
                            "location_or_link" => "Google Meet: https://meet.google.com/abc-defg-hij"
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function scheduleInterview(Request $request, $id)
    {
        $application = Application::find($id);

        if (!$application) {
            return $this->errorResponse('Lamaran tidak ditemukan.', 404);
        }

        $validator = Validator::make($request->all(), [
            'interview_date' => 'required|date',
            'location_or_link' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi jadwal interview gagal.', 422, $validator->errors());
        }

        $interview = Interview::create([
            'application_id' => $application->id,
            'interview_date' => $request->interview_date,
            'location_or_link' => $request->location_or_link,
            'notes' => $request->notes,
        ]);

        $application->update(['status' => 'interview']);

        return $this->successResponse($interview, 'Jadwal wawancara berhasil dikirimkan ke pelamar.', 201);
    }

    #[OA\Post(
        path: "/admin/applications/{id}/evaluations",
        summary: "[HR] Tambah Skor Evaluasi Kandidat",
        description: "Menambahkan skor penilain kriteria kandidat oleh tim HR.",
        security: [["bearerAuth" => []]],
        tags: ["HR Recruitment Pipeline"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 10))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["criteria", "score"],
                properties: [
                    new OA\Property(property: "criteria", type: "string", example: "Problem Solving"),
                    new OA\Property(property: "score", type: "integer", example: 85),
                    new OA\Property(property: "feedback", type: "string", example: "Sangat baik.")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Evaluasi berhasil disimpan",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Skor evaluasi berhasil disimpan.",
                        "data" => [
                            "id" => 1,
                            "application_id" => 10,
                            "criteria" => "Problem Solving",
                            "score" => 85,
                            "feedback" => "Sangat baik."
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function storeEvaluation(Request $request, $id)
    {
        $application = Application::find($id);

        if (!$application) {
            return $this->errorResponse('Lamaran tidak ditemukan.', 404);
        }

        $validator = Validator::make($request->all(), [
            'criteria' => 'required|string|max:255',
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi skor evaluasi gagal.', 422, $validator->errors());
        }

        $eval = CandidateEvaluation::create([
            'application_id' => $application->id,
            'evaluator_id' => $request->user()->id,
            'criteria' => $request->criteria,
            'score' => $request->score,
            'feedback' => $request->feedback,
        ]);

        return $this->successResponse($eval, 'Skor evaluasi berhasil disimpan.', 201);
    }

    #[OA\Post(
        path: "/admin/applications/{id}/offer-letter",
        summary: "[HR] Terbitkan Surat Penawaran Kerja (Offer Letter)",
        description: "Membuat dan mengirimkan offer letter kepada kandidat terpilih.",
        security: [["bearerAuth" => []]],
        tags: ["HR Recruitment Pipeline"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 10))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["offered_position", "offered_salary", "start_date"],
                properties: [
                    new OA\Property(property: "offered_position", type: "string", example: "Backend Engineer"),
                    new OA\Property(property: "offered_salary", type: "number", example: 18000000),
                    new OA\Property(property: "start_date", type: "string", format: "date", example: "2026-10-01"),
                    new OA\Property(property: "terms_and_conditions", type: "string", example: "Jam kerja 09.00-17.00 WIB.")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Offer Letter diterbitkan",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Surat Penawaran Kerja (Offer Letter) berhasil diterbitkan!",
                        "data" => [
                            "id" => 1,
                            "application_id" => 10,
                            "offered_position" => "Backend Engineer",
                            "offered_salary" => 18000000,
                            "start_date" => "2026-10-01",
                            "status" => "pending"
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function issueOfferLetter(Request $request, $id)
    {
        $application = Application::find($id);

        if (!$application) {
            return $this->errorResponse('Lamaran tidak ditemukan.', 404);
        }

        $validator = Validator::make($request->all(), [
            'offered_position' => 'required|string|max:255',
            'offered_salary' => 'required|numeric',
            'start_date' => 'required|date',
            'terms_and_conditions' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi data offer letter gagal.', 422, $validator->errors());
        }

        $offer = OfferLetter::updateOrCreate(
            ['application_id' => $application->id],
            [
                'candidate_id' => $application->user_id,
                'offered_position' => $request->offered_position,
                'offered_salary' => $request->offered_salary,
                'start_date' => $request->start_date,
                'terms_and_conditions' => $request->terms_and_conditions,
                'status' => 'pending',
            ]
        );

        $application->update(['status' => 'hired']);

        return $this->successResponse($offer, 'Surat Penawaran Kerja (Offer Letter) berhasil diterbitkan!', 201);
    }
}
