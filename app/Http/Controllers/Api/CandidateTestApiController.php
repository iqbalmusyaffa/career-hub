<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CandidateTestResult;
use App\Models\Job;
use App\Models\JobTest;
use App\Models\OfferLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class CandidateTestApiController extends Controller
{
    #[OA\Get(
        path: "/candidate/tests/{jobId}",
        summary: "Lihat Soal / Detail Tes Online Lowongan",
        description: "Mengambil informasi tes online psikotes/kompetensi.",
        security: [["bearerAuth" => []]],
        tags: ["Online Tests & Offers"],
        parameters: [
            new OA\Parameter(name: "jobId", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail tes online",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Detail tes online berhasil diambil.",
                        "data" => [
                            "test" => [
                                "id" => 1,
                                "title" => "Tes Logika & Pemrograman Backend",
                                "test_mode" => "internal",
                                "duration_minutes" => 30,
                                "description" => "Kerjakan soal dalam waktu 30 menit",
                                "questions" => [
                                    [
                                        "id" => 101,
                                        "question_text" => "Metode mana yang digunakan untuk routing HTTP GET di Laravel?",
                                        "option_a" => "Route::get()",
                                        "option_b" => "Route::post()",
                                        "option_c" => "Route::put()",
                                        "option_d" => "Route::delete()"
                                    ]
                                ]
                            ],
                            "candidate_result" => null
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function showTest(Request $request, $jobId)
    {
        $realJobId = \App\Helpers\IdHasher::decode($jobId) ?? $jobId;
        $job = Job::find($realJobId);

        if (!$job) {
            return $this->errorResponse('Lowongan pekerjaan tidak ditemukan.', 404);
        }

        $test = JobTest::with('questions')->where('job_id', $job->id)->where('is_active', true)->first();

        if (!$test) {
            return $this->errorResponse('Tidak ada tes online aktif untuk lowongan ini.', 404);
        }

        $existingResult = CandidateTestResult::where('job_test_id', $test->id)
            ->where('user_id', $request->user()->id)
            ->first();

        return $this->successResponse([
            'test' => [
                'id' => $test->id,
                'title' => $test->title,
                'test_mode' => $test->test_mode,
                'external_url' => $test->external_url,
                'file_path' => $test->file_path ? asset('storage/' . $test->file_path) : null,
                'duration_minutes' => $test->duration_minutes,
                'description' => $test->description,
                'questions' => $test->questions->map(function ($q) {
                    return [
                        'id' => $q->id,
                        'question_text' => $q->question_text,
                        'option_a' => $q->option_a,
                        'option_b' => $q->option_b,
                        'option_c' => $q->option_c,
                        'option_d' => $q->option_d,
                    ];
                }),
            ],
            'candidate_result' => $existingResult,
        ], 'Detail tes online berhasil diambil.');
    }

    #[OA\Post(
        path: "/candidate/tests/{jobId}/submit",
        summary: "Kirim Jawaban Tes Online",
        description: "Mengirimkan jawaban pilihan ganda atau berkas tugas tes online.",
        security: [["bearerAuth" => []]],
        tags: ["Online Tests & Offers"],
        parameters: [
            new OA\Parameter(name: "jobId", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "answers", type: "object", example: ["101" => "a"]),
                    new OA\Property(property: "project_link", type: "string", example: "https://github.com/budi/project")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Tes berhasil disubmit",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Jawaban tes berhasil dikirim!",
                        "data" => [
                            "score" => 100,
                            "status" => "passed",
                            "passed" => true
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function submitTest(Request $request, $jobId)
    {
        $realJobId = \App\Helpers\IdHasher::decode($jobId) ?? $jobId;
        $test = JobTest::with('questions')->where('job_id', $realJobId)->first();

        if (!$test) {
            return $this->errorResponse('Tes online tidak ditemukan.', 404);
        }

        $user = $request->user();
        $totalQuestions = $test->questions->count();
        $correctCount = 0;
        $userAnswers = $request->input('answers', []);

        foreach ($test->questions as $q) {
            if (isset($userAnswers[$q->id]) && strtolower($userAnswers[$q->id]) === strtolower($q->correct_option)) {
                $correctCount++;
            }
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
        $passed = $score >= $test->passing_score;

        $result = CandidateTestResult::updateOrCreate(
            ['job_test_id' => $test->id, 'user_id' => $user->id],
            [
                'score' => $score,
                'status' => $passed ? 'passed' : 'failed',
                'answers_data' => json_encode($userAnswers),
                'project_link' => $request->project_link,
            ]
        );

        return $this->successResponse([
            'score' => $score,
            'status' => $result->status,
            'passed' => $passed,
        ], 'Jawaban tes berhasil dikirim!');
    }

    #[OA\Post(
        path: "/candidate/offer-letters/{id}/respond",
        summary: "Respon Surat Penawaran (Offer Letter)",
        description: "Pelamar menerima (accept) atau menolak (reject) surat penawaran kerja.",
        security: [["bearerAuth" => []]],
        tags: ["Online Tests & Offers"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["status"],
                properties: [
                    new OA\Property(property: "status", type: "string", example: "accepted"),
                    new OA\Property(property: "notes", type: "string", example: "Terima kasih, saya menerima penawaran ini.")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Respon berhasil disimpan",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Respon penawaran kerja berhasil disimpan!",
                        "data" => [
                            "id" => 1,
                            "offered_position" => "Backend Engineer",
                            "offered_salary" => 18000000,
                            "status" => "accepted"
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function respondOffer(Request $request, $id)
    {
        $offer = OfferLetter::find($id);

        if (!$offer) {
            return $this->errorResponse('Offer letter tidak ditemukan.', 404);
        }

        if ($offer->candidate_id !== $request->user()->id) {
            return $this->errorResponse('Anda tidak memiliki akses pada offer letter ini.', 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:accepted,rejected',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Respon tidak valid.', 422, $validator->errors());
        }

        $offer->update([
            'status' => $request->status,
            'candidate_notes' => $request->notes,
            'responded_at' => now(),
        ]);

        return $this->successResponse($offer, 'Respon penawaran kerja berhasil disimpan!');
    }
}
