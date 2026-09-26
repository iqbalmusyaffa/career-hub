<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InternshipEvaluation;
use App\Models\InternshipLogbook;
use App\Models\InternshipPeriod;
use App\Models\InternshipResignation;
use App\Models\InternshipStipendDisbursement;
use App\Models\InternshipTranscript;
use App\Models\InternshipCertificate;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

class CandidateInternshipApiController extends Controller
{
    #[OA\Get(
        path: "/candidate/internship/logbooks",
        summary: "Daftar Logbook & Presensi Magang",
        description: "Mengambil riwayat presensi harian, logbook kegiatan, dan statistik jam kerja magang kandidat.",
        tags: ["Candidate Internship & Logbook"]
    )]
    public function logbooks(Request $request): JsonResponse
    {
        $user = $request->user();

        $month = $request->query('month');
        $query = InternshipLogbook::where('user_id', $user->id);

        if ($month && preg_match('/^\d{4}-\d{2}$/', $month)) {
            $parsedMonth = Carbon::parse($month . '-01');
            $query->whereYear('date', $parsedMonth->year)
                  ->whereMonth('date', $parsedMonth->month);
        }

        $logbooks = $query->orderBy('date', 'desc')->get();

        $totalHours = (int) InternshipLogbook::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('work_hours');

        $totalApproved = InternshipLogbook::where('user_id', $user->id)->where('status', 'approved')->count();
        $totalPending = InternshipLogbook::where('user_id', $user->id)->where('status', 'pending')->count();
        $totalRejected = InternshipLogbook::where('user_id', $user->id)->where('status', 'rejected')->count();

        return response()->json([
            'success' => true,
            'message' => 'Data logbook magang berhasil diambil.',
            'data' => [
                'summary' => [
                    'total_approved_hours' => $totalHours,
                    'approved_count' => $totalApproved,
                    'pending_count' => $totalPending,
                    'rejected_count' => $totalRejected,
                    'total_entries' => $logbooks->count(),
                ],
                'logbooks' => $logbooks,
            ]
        ]);
    }

    #[OA\Get(
        path: "/candidate/internship/logbooks/{date}",
        summary: "Detail Logbook Tanggal Tertentu",
        description: "Mengambil detail presensi dan catatan kegiatan untuk tanggal tertentu (format: YYYY-MM-DD).",
        tags: ["Candidate Internship & Logbook"]
    )]
    public function showLogbook(Request $request, string $date): JsonResponse
    {
        $user = $request->user();

        $logbook = InternshipLogbook::where('user_id', $user->id)
            ->whereDate('date', $date)
            ->first();

        if (!$logbook) {
            return response()->json([
                'success' => false,
                'message' => 'Logbook untuk tanggal ' . $date . ' belum diisi.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail logbook berhasil diambil.',
            'data' => $logbook,
        ]);
    }

    #[OA\Post(
        path: "/candidate/internship/logbooks",
        summary: "Submit / Check-in Presensi & Logbook Harian",
        description: "Mengisi presensi harian, aktivitas, jam kerja, foto bukti, dan koordinat GPS.",
        tags: ["Candidate Internship & Logbook"]
    )]
    public function storeLogbook(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'date' => 'required|date',
            'attendance_type' => 'nullable|string',
            'work_hours' => 'nullable|numeric|min:0|max:24',
            'activities' => 'required|string|min:10',
            'learnings' => 'nullable|string',
            'challenges' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_address' => 'nullable|string',
        ]);

        $logbook = InternshipLogbook::updateOrCreate(
            [
                'user_id' => $user->id,
                'date' => $validated['date'],
            ],
            [
                'attendance_type' => $validated['attendance_type'] ?? 'present',
                'work_hours' => $validated['work_hours'] ?? 8,
                'activities' => $validated['activities'],
                'learnings' => $validated['learnings'] ?? ($request->input('learning_achievements') ?? null),
                'challenges' => $validated['challenges'] ?? ($request->input('obstacles') ?? null),
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'location_address' => $validated['location_address'] ?? null,
                'status' => 'pending',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Presensi dan logbook kegiatan berhasil disimpan dan menunggu verifikasi mentor.',
            'data' => $logbook,
        ]);
    }

    #[OA\Get(
        path: "/candidate/internship/progress",
        summary: "Ringkasan Progress Magang & Nilai",
        description: "Mengambil capaian jam kerja magang, persentase kelulusan, nilai evaluasi mentor, transkrip nilai, dan sertifikat.",
        tags: ["Candidate Internship & Logbook"]
    )]
    public function progress(Request $request): JsonResponse
    {
        $user = $request->user();

        $period = InternshipPeriod::where('user_id', $user->id)->first();
        $targetHours = $period ? $period->target_hours : 400;

        $completedHours = (int) InternshipLogbook::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('work_hours');

        $evaluation = InternshipEvaluation::where('user_id', $user->id)->latest()->first();
        $transcript = InternshipTranscript::where('user_id', $user->id)->latest()->first();
        $certificate = InternshipCertificate::where('user_id', $user->id)->latest()->first();

        $progressPct = $targetHours > 0 ? min(100, round(($completedHours / $targetHours) * 100)) : 0;

        return response()->json([
            'success' => true,
            'message' => 'Progress magang berhasil diambil.',
            'data' => [
                'target_hours' => $targetHours,
                'completed_hours' => $completedHours,
                'remaining_hours' => max(0, $targetHours - $completedHours),
                'progress_percentage' => $progressPct,
                'is_completed' => $progressPct >= 100,
                'period' => $period,
                'evaluation' => $evaluation,
                'has_transcript' => $transcript !== null,
                'has_certificate' => $certificate !== null,
                'certificate_number' => $certificate?->certificate_number,
            ]
        ]);
    }

    #[OA\Post(
        path: "/candidate/internship/bank-account",
        summary: "Simpan Rekening Bank untuk Uang Saku",
        description: "Menyimpan atau memperbarui data nomor rekening bank kandidat untuk pencairan uang saku magang.",
        tags: ["Candidate Internship & Logbook"]
    )]
    public function bankAccount(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:150',
        ]);

        $profile = $user->candidateProfile;
        if ($profile) {
            $profile->update([
                'bank_name' => $validated['bank_name'],
                'bank_account_number' => $validated['account_number'],
                'bank_account_holder' => $validated['account_holder'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data rekening bank berhasil disimpan.',
            'data' => [
                'bank_name' => $validated['bank_name'],
                'account_number' => $validated['account_number'],
                'account_holder' => $validated['account_holder'],
            ]
        ]);
    }

    #[OA\Get(
        path: "/candidate/internship/stipends",
        summary: "Riwayat Pencairan Uang Saku Magang",
        description: "Mengambil daftar pencairan uang saku dan insentif magang kandidat.",
        tags: ["Candidate Internship & Logbook"]
    )]
    public function stipends(Request $request): JsonResponse
    {
        $user = $request->user();

        $stipends = InternshipStipendDisbursement::where('user_id', $user->id)
            ->latest('disbursement_date')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat uang saku berhasil diambil.',
            'data' => $stipends,
        ]);
    }

    #[OA\Post(
        path: "/candidate/internship/resignations",
        summary: "Pengajuan Pengunduran Diri Magang (Self-Resignation)",
        description: "Mengirimkan formulir pengajuan pengunduran diri magang beserta alasan dan dokumen pendukung.",
        tags: ["Candidate Internship & Logbook"]
    )]
    public function resignations(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'reason' => 'required|string|min:20',
            'effective_date' => 'required|date',
            'document' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $docPath = null;
        if ($request->hasFile('document')) {
            $docPath = $request->file('document')->store('resignation_documents', 'public');
        }

        $resignation = InternshipResignation::create([
            'user_id' => $user->id,
            'reason' => $validated['reason'],
            'effective_date' => $validated['effective_date'],
            'document_path' => $docPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan pengunduran diri berhasil dikirimkan.',
            'data' => $resignation,
        ], 201);
    }
}
