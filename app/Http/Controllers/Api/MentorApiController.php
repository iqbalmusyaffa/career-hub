<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InternshipEvaluation;
use App\Models\InternshipLogbook;
use App\Models\InternshipPeriod;
use App\Models\InternshipStipendDisbursement;
use App\Models\InternshipCurriculum;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MentorApiController extends Controller
{
    #[OA\Get(
        path: "/mentor/dashboard",
        summary: "Dashboard & Statistik Mentor",
        description: "Mengambil statistik total mentee, jumlah logbook menunggu persetujuan, dan ringkasan aktivitas bimbingan.",
        tags: ["Mentor Workspace"]
    )]
    public function dashboard(Request $request): JsonResponse
    {
        $mentor = $request->user();

        $menteesQuery = User::role('Candidate')->where(function($q) use ($mentor) {
            $q->whereHas('applications', function($sub) use ($mentor) {
                $sub->whereIn('status', ['accepted', 'hired']);
            });
        });

        $totalMentees = $menteesQuery->count();
        $pendingLogbooks = InternshipLogbook::where('status', 'pending')->count();
        $approvedLogbooks = InternshipLogbook::where('status', 'approved')->count();

        return response()->json([
            'success' => true,
            'message' => 'Data dashboard mentor berhasil dimuat.',
            'data' => [
                'total_mentees' => $totalMentees,
                'pending_logbooks' => $pendingLogbooks,
                'approved_logbooks' => $approvedLogbooks,
            ]
        ]);
    }

    #[OA\Get(
        path: "/mentor/logbooks",
        summary: "Daftar Logbook Seluruh Mentee",
        description: "Mengambil daftar logbook kegiatan harian mentee dengan filter status dan nama mentee.",
        tags: ["Mentor Workspace"]
    )]
    public function logbooks(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $internId = $request->query('intern_id');

        $query = InternshipLogbook::with(['user:id,name,email']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($internId) {
            $query->where('user_id', $internId);
        }

        $logbooks = $query->orderBy('date', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Daftar logbook mentee berhasil diambil.',
            'data' => $logbooks,
        ]);
    }

    #[OA\Post(
        path: "/mentor/logbooks/{id}/approve",
        summary: "Setujui / ACC Logbook Mentee",
        description: "Menyetujui presensi dan jam kerja logbook harian mentee.",
        tags: ["Mentor Workspace"]
    )]
    public function approveLogbook(Request $request, int $id): JsonResponse
    {
        $logbook = InternshipLogbook::findOrFail($id);

        $validated = $request->validate([
            'mentor_feedback' => 'nullable|string',
            'adjusted_hours' => 'nullable|numeric|min:0|max:24',
        ]);

        $logbook->update([
            'status' => 'approved',
            'mentor_notes' => $validated['mentor_feedback'] ?? $logbook->mentor_notes,
            'work_hours' => $validated['adjusted_hours'] ?? $logbook->work_hours,
            'approved_at' => now(),
            'mentor_id' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Logbook tanggal ' . $logbook->date->format('d/m/Y') . ' berhasil disetujui (Approved).',
            'data' => $logbook,
        ]);
    }

    #[OA\Post(
        path: "/mentor/logbooks/{id}/reject",
        summary: "Tolak / Minta Revisi Logbook Mentee",
        description: "Menolak atau meminta revisi atas pengisian presensi kegiatan harian mentee.",
        tags: ["Mentor Workspace"]
    )]
    public function rejectLogbook(Request $request, int $id): JsonResponse
    {
        $logbook = InternshipLogbook::findOrFail($id);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:5',
        ]);

        $logbook->update([
            'status' => 'rejected',
            'mentor_notes' => $validated['rejection_reason'],
            'mentor_id' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Logbook telah ditolak dengan catatan evaluasi.',
            'data' => $logbook,
        ]);
    }

    #[OA\Get(
        path: "/mentor/interns",
        summary: "Daftar Peserta Magang (Mentees)",
        description: "Mengambil daftar lengkap seluruh peserta magang aktif berserta progres jam kerja dan persentase kehadiran.",
        tags: ["Mentor Workspace"]
    )]
    public function interns(): JsonResponse
    {
        $interns = User::role('Candidate')
            ->select('id', 'name', 'email', 'avatar', 'created_at')
            ->with([
                'candidateProfile',
                'internshipPeriod',
            ])
            ->get()
            ->map(function($user) {
                $approvedHours = (int) InternshipLogbook::where('user_id', $user->id)
                    ->where('status', 'approved')
                    ->sum('work_hours');

                $targetHours = $user->internshipPeriod?->target_hours ?? 400;
                $progressPct = $targetHours > 0 ? min(100, round(($approvedHours / $targetHours) * 100)) : 0;

                $firstEdu = $user->candidateProfile && is_array($user->candidateProfile->educations) && count($user->candidateProfile->educations) > 0 
                    ? $user->candidateProfile->educations[0] 
                    : [];

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'university' => $firstEdu['institution'] ?? $firstEdu['school'] ?? ($firstEdu['university'] ?? null),
                    'major' => $firstEdu['major'] ?? null,
                    'target_hours' => $targetHours,
                    'completed_hours' => $approvedHours,
                    'progress_percentage' => $progressPct,
                    'is_completed' => $progressPct >= 100,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Daftar peserta magang berhasil dimuat.',
            'data' => $interns,
        ]);
    }

    #[OA\Post(
        path: "/mentor/evaluations",
        summary: "Submit Nilai & Evaluasi Akhir Mentee",
        description: "Mengirimkan nilai performa akhir, soft skills, technical skills, dan ulasan rekomendasi kelulusan mentee.",
        tags: ["Mentor Workspace"]
    )]
    public function evaluations(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'intern_id' => 'required|exists:users,id',
            'technical_score' => 'required|numeric|min:0|max:100',
            'communication_score' => 'required|numeric|min:0|max:100',
            'discipline_score' => 'required|numeric|min:0|max:100',
            'initiative_score' => 'required|numeric|min:0|max:100',
            'general_notes' => 'required|string|min:10',
            'recommendation' => 'required|string|in:Sangat Direkomendasikan,Direkomendasikan,Cukup,Perlu Peningkatan',
        ]);

        $finalScore = round(($validated['technical_score'] * 0.35) + 
                            ($validated['communication_score'] * 0.25) + 
                            ($validated['discipline_score'] * 0.20) + 
                            ($validated['initiative_score'] * 0.20), 1);

        $grade = match(true) {
            $finalScore >= 85 => 'A',
            $finalScore >= 75 => 'B',
            $finalScore >= 65 => 'C',
            default => 'D',
        };

        $evaluation = InternshipEvaluation::updateOrCreate(
            [
                'user_id' => $validated['intern_id'],
            ],
            [
                'evaluator_id' => $request->user()->id,
                'technical_score' => $validated['technical_score'],
                'communication_score' => $validated['communication_score'],
                'discipline_score' => $validated['discipline_score'],
                'initiative_score' => $validated['initiative_score'],
                'final_score' => $finalScore,
                'grade' => $grade,
                'notes' => $validated['general_notes'],
                'recommendation' => $validated['recommendation'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Nilai evaluasi performa magang berhasil disimpan.',
            'data' => $evaluation,
        ]);
    }
}
