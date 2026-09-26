<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipStipendDisbursement;
use App\Models\InternshipUnlockRequest;
use App\Models\InternshipLogbook;
use App\Models\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AdminInternshipApiController extends Controller
{
    #[OA\Get(
        path: "/admin/internship-stipends",
        summary: "Daftar Rekapitulasi Uang Saku Magang (HR & Admin)",
        description: "Mengambil daftar pencairan dan pengajuan uang saku seluruh peserta magang.",
        tags: ["HR & Employer Recruitment"]
    )]
    public function stipends(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $query = InternshipStipendDisbursement::with(['user:id,name,email', 'user.candidateProfile:id,user_id,bank_name,bank_account_number,bank_account_holder']);

        if ($status) {
            $query->where('payment_status', $status);
        }

        $stipends = $query->latest('disbursement_date')->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Daftar rekapitulasi uang saku magang berhasil dimuat.',
            'data' => $stipends,
        ]);
    }

    #[OA\Post(
        path: "/admin/internship-stipends/{id}/status",
        summary: "Update Status Pembayaran Uang Saku",
        description: "Memperbarui status pembayaran uang saku (pending, verified, transferred, rejected).",
        tags: ["HR & Employer Recruitment"]
    )]
    public function updateStipendStatus(Request $request, int $id): JsonResponse
    {
        $stipend = InternshipStipendDisbursement::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,verified,transferred,rejected',
            'notes' => 'nullable|string',
            'transfer_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $proofPath = null;
        if ($request->hasFile('transfer_proof')) {
            $proofPath = $request->file('transfer_proof')->store('stipend_proofs', 'public');
        }

        $stipend->update([
            'payment_status' => $validated['status'],
            'transfer_proof_path' => $proofPath ?: $stipend->transfer_proof_path,
            'notes' => $validated['notes'] ?? $stipend->notes,
            'transferred_at' => $validated['status'] === 'transferred' ? now() : $stipend->transferred_at,
        ]);

        // Notify Intern
        if ($validated['status'] === 'transferred') {
            UserNotification::send(
                $stipend->user_id,
                "💰 Uang Saku Magang Telah Ditransfer",
                "Uang saku magang Anda periode {$stipend->period_name} sebesar Rp " . number_format($stipend->net_amount ?? $stipend->amount, 0, ',', '.') . " telah berhasil ditransfer ke rekening bank Anda.",
                route('candidate.logbook.index'),
                'success'
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pencairan uang saku berhasil diperbarui.',
            'data' => $stipend,
        ]);
    }

    #[OA\Get(
        path: "/admin/internship-unlocks",
        summary: "Daftar Pengajuan Buka Kunci Presensi (HR & Admin)",
        description: "Mengambil daftar tiket permohonan buka kunci presensi pada tanggal yang telah terkunci.",
        tags: ["HR & Employer Recruitment"]
    )]
    public function unlocks(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $query = InternshipUnlockRequest::with(['user:id,name,email', 'mentor:id,name']);

        if ($status) {
            $query->where('status', $status);
        }

        $unlocks = $query->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Daftar tiket buka kunci presensi berhasil dimuat.',
            'data' => $unlocks,
        ]);
    }

    #[OA\Post(
        path: "/admin/internship-unlocks/{id}/approve",
        summary: "Setujui Tiket Buka Kunci Presensi",
        description: "Menyetujui pembukaan kunci tanggal presensi sehingga peserta magang dapat mengisi logbook yang terlewat.",
        tags: ["HR & Employer Recruitment"]
    )]
    public function approveUnlock(Request $request, int $id): JsonResponse
    {
        $unlock = InternshipUnlockRequest::findOrFail($id);

        $unlock->update([
            'status' => 'approved',
            'resolved_at' => now(),
            'resolved_by' => $request->user()->id,
            'admin_notes' => $request->input('notes', 'Disetujui oleh HR/Admin'),
        ]);

        // Notify Intern
        UserNotification::send(
            $unlock->user_id,
            "🔓 Pengajuan Buka Kunci Presensi Disetujui",
            "Permohonan pengisian presensi tanggal {$unlock->locked_date->format('d/m/Y')} telah disetujui. Silakan segera isi logbook harian Anda.",
            route('candidate.logbook.index'),
            'success'
        );

        return response()->json([
            'success' => true,
            'message' => 'Tiket permohonan buka kunci berhasil disetujui.',
            'data' => $unlock,
        ]);
    }

    #[OA\Post(
        path: "/admin/internship-unlocks/{id}/reject",
        summary: "Tolak Tiket Buka Kunci Presensi",
        description: "Menolak tiket permohonan buka kunci presensi.",
        tags: ["HR & Employer Recruitment"]
    )]
    public function rejectUnlock(Request $request, int $id): JsonResponse
    {
        $unlock = InternshipUnlockRequest::findOrFail($id);

        $validated = $request->validate([
            'reason' => 'required|string|min:5',
        ]);

        $unlock->update([
            'status' => 'rejected',
            'resolved_at' => now(),
            'resolved_by' => $request->user()->id,
            'admin_notes' => $validated['reason'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tiket permohonan buka kunci telah ditolak.',
            'data' => $unlock,
        ]);
    }
}
