<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationMessage;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class ApplicationApiController extends Controller
{
    #[OA\Post(
        path: "/jobs/{id}/apply",
        summary: "Mengirim Lamaran Kerja",
        description: "Pelamar mengirimkan lamaran untuk lowongan pekerjaan tertentu.",
        security: [["bearerAuth" => []]],
        tags: ["Candidate Applications"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "cover_letter", type: "string", example: "Saya berminat bergabung di posisi ini..."),
                        new OA\Property(property: "resume", type: "string", format: "binary")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Lamaran berhasil dikirim",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Lamaran pekerjaan berhasil dikirim!",
                        "data" => [
                            "id" => 10,
                            "job_id" => 1,
                            "user_id" => 1,
                            "cover_letter" => "Saya berminat bergabung...",
                            "status" => "pending",
                            "created_at" => "2026-08-21T22:00:00.000000Z"
                        ],
                        "errors" => null
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Sudah melamar atau kuota penuh",
                content: new OA\JsonContent(
                    example: [
                        "success" => false,
                        "message" => "Anda sudah melamar pekerjaan ini sebelumnya.",
                        "data" => null,
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function apply(Request $request, $id)
    {
        $job = Job::find($id);

        if (!$job || $job->status !== 'active') {
            return $this->errorResponse('Lowongan kerja tidak tersedia atau sudah ditutup.', 404);
        }

        $user = $request->user();

        $existing = Application::where('job_id', $job->id)->where('user_id', $user->id)->first();
        if ($existing) {
            return $this->errorResponse('Anda sudah melamar pekerjaan ini sebelumnya.', 400);
        }

        if ($job->quota > 0) {
            $appliedCount = Application::where('job_id', $job->id)->count();
            if ($appliedCount >= $job->quota) {
                return $this->errorResponse('Kuota pendaftaran untuk lowongan ini sudah memenuhi kapasitas.', 400);
            }
        }

        $validator = Validator::make($request->all(), [
            'cover_letter' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi berkas lamaran gagal.', 422, $validator->errors());
        }

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        } else {
            $profile = $user->candidateProfile;
            $resumePath = $profile ? $profile->resume_file : null;
        }

        $application = Application::create([
            'job_id' => $job->id,
            'user_id' => $user->id,
            'cover_letter' => $request->cover_letter,
            'resume' => $resumePath,
            'status' => 'pending',
        ]);

        return $this->successResponse($application, 'Lamaran pekerjaan berhasil dikirim!', 201);
    }

    #[OA\Get(
        path: "/candidate/applications",
        summary: "Daftar Riwayat Lamaran Saya",
        description: "Mengambil riwayat lamaran yang pernah dikirimkan.",
        security: [["bearerAuth" => []]],
        tags: ["Candidate Applications"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar lamaran kandidat",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Daftar riwayat lamaran berhasil diambil.",
                        "data" => [
                            [
                                "id" => 10,
                                "job_id" => 1,
                                "status" => "interview",
                                "created_at" => "2026-08-21T22:00:00.000000Z",
                                "job" => [
                                    "title" => "Senior Backend Developer",
                                    "company_name" => "PT Tech Nusantara"
                                ],
                                "interviews" => [
                                    [
                                        "interview_date" => "2026-09-01 10:00:00",
                                        "location_or_link" => "Google Meet: https://meet.google.com/abc-defg-hij"
                                    ]
                                ]
                            ]
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function myApplications(Request $request)
    {
        $applications = Application::with(['job', 'interviews', 'offerLetter'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return $this->successResponse($applications, 'Daftar riwayat lamaran berhasil diambil.');
    }

    #[OA\Get(
        path: "/applications/{id}/messages",
        summary: "Pesan Live Chat Lamaran",
        description: "Mengambil histori percakapan antara Pelamar dan HR.",
        security: [["bearerAuth" => []]],
        tags: ["Candidate Applications"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 10))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Histori percakapan chat",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Pesan percakapan berhasil diambil.",
                        "data" => [
                            [
                                "id" => 1,
                                "application_id" => 10,
                                "sender_id" => 1,
                                "message" => "Halo HR, mohon info jadwal wawancara",
                                "created_at" => "2026-08-21T22:05:00.000000Z",
                                "sender" => [
                                    "id" => 1,
                                    "name" => "Budi Pratama"
                                ]
                            ]
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function fetchMessages(Request $request, $id)
    {
        $application = Application::find($id);

        if (!$application) {
            return $this->errorResponse('Lamaran tidak ditemukan.', 404);
        }

        $user = $request->user();
        if ($application->user_id !== $user->id && !$user->hasAnyRole(['HR', 'Super Admin', 'Company Owner'])) {
            return $this->errorResponse('Anda tidak memiliki otoritas melihat chat lamaran ini.', 403);
        }

        $messages = ApplicationMessage::with('sender')
            ->where('application_id', $application->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return $this->successResponse($messages, 'Pesan percakapan berhasil diambil.');
    }

    #[OA\Post(
        path: "/applications/{id}/messages",
        summary: "Kirim Pesan Live Chat",
        description: "Mengirim pesan baru pada saluran obrolan lamaran kerja.",
        security: [["bearerAuth" => []]],
        tags: ["Candidate Applications"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 10))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["message"],
                properties: [
                    new OA\Property(property: "message", type: "string", example: "Halo HR, apakah ada kelanjutan wawancara?")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Pesan berhasil terkirim",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Pesan berhasil terkirim.",
                        "data" => [
                            "id" => 2,
                            "application_id" => 10,
                            "sender_id" => 1,
                            "message" => "Halo HR, apakah ada kelanjutan wawancara?",
                            "created_at" => "2026-08-21T22:10:00.000000Z"
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function sendMessage(Request $request, $id)
    {
        $application = Application::find($id);

        if (!$application) {
            return $this->errorResponse('Lamaran tidak ditemukan.', 404);
        }

        $user = $request->user();
        if ($application->user_id !== $user->id && !$user->hasAnyRole(['HR', 'Super Admin', 'Company Owner'])) {
            return $this->errorResponse('Anda tidak memiliki otoritas mengirim pesan pada lamaran ini.', 403);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Pesan tidak boleh kosong.', 422, $validator->errors());
        }

        $msg = ApplicationMessage::create([
            'application_id' => $application->id,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        return $this->successResponse($msg->load('sender'), 'Pesan berhasil terkirim.', 201);
    }
}
