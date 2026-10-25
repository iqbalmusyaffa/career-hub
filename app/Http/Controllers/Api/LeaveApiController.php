<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class LeaveApiController extends Controller
{
    // ==========================================
    // 1. CANDIDATE / EMPLOYEE LEAVE REQUESTS
    // ==========================================

    #[OA\Get(
        path: "/leaves",
        summary: "[Karyawan/Magang] Riwayat Pengajuan Cuti & Izin",
        description: "Mengambil daftar pengajuan cuti tahunan, izin sakit, dan izin khusus karyawan/peserta magang.",
        security: [["bearerAuth" => []]],
        tags: ["Leave & Attendance Management"],
        responses: [
            new OA\Response(response: 200, description: "Daftar cuti berhasil dimuat."),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function myLeaves(Request $request): JsonResponse
    {
        $user = $request->user();
        $leaves = LeaveRequest::where('user_id', $user->id)
            ->latest('start_date')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengajuan cuti & izin berhasil diambil.',
            'data' => [
                'total_leaves' => $leaves->count(),
                'approved_count' => $leaves->where('status', 'approved')->count(),
                'pending_count' => $leaves->where('status', 'pending')->count(),
                'leaves' => $leaves,
            ]
        ]);
    }

    #[OA\Post(
        path: "/leaves",
        summary: "[Karyawan/Magang] Ajukan Permohonan Cuti / Izin Baru",
        description: "Mengirimkan formulir permohonan cuti tahunan atau izin sakit dengan lampiran surat pendukung.",
        security: [["bearerAuth" => []]],
        tags: ["Leave & Attendance Management"],
        responses: [
            new OA\Response(response: 201, description: "Pengajuan cuti berhasil dikirim."),
            new OA\Response(response: 422, description: "Validasi gagal")
        ]
    )]
    public function storeLeave(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'leave_type' => 'required|in:annual_leave,sick_leave,academic_leave,family_event,special_leave,maternity_leave,internship_permission',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);
        $totalDays = (int) $start->diffInDays($end) + 1;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave_attachments', 'public');
        }

        $leave = LeaveRequest::create([
            'user_id' => $user->id,
            'company_id' => $user->currentCompanyProfile()?->id ?? 1,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'attachment_path' => $attachmentPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti/izin selama ' . $totalDays . ' hari berhasil dikirimkan.',
            'data' => $leave,
        ], 201);
    }

    #[OA\Post(
        path: "/leaves/{id}/cancel",
        summary: "[Karyawan/Magang] Batalkan Permohonan Cuti",
        description: "Membatalkan pengajuan cuti yang masih menunggu persetujuan (pending).",
        security: [["bearerAuth" => []]],
        tags: ["Leave & Attendance Management"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Cuti berhasil dibatalkan."),
            new OA\Response(response: 403, description: "Cuti sudah diproses.")
        ]
    )]
    public function cancelLeave(Request $request, int $id): JsonResponse
    {
        $leave = LeaveRequest::where('user_id', $request->user()->id)->findOrFail($id);

        if ($leave->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengajuan cuti yang berstatus Menunggu Persetujuan yang dapat dibatalkan.',
            ], 403);
        }

        $leave->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti berhasil dibatalkan.',
            'data' => $leave,
        ]);
    }

    // ==========================================
    // 2. HR / ADMIN LEAVE APPROVAL
    // ==========================================

    #[OA\Get(
        path: "/admin/leaves",
        summary: "[HR] Daftar Pengajuan Cuti Seluruh Karyawan",
        description: "Mengambil daftar seluruh permohonan cuti dan izin yang masuk untuk di-review HR.",
        security: [["bearerAuth" => []]],
        tags: ["Leave & Attendance Management"],
        responses: [
            new OA\Response(response: 200, description: "Daftar cuti berhasil dimuat.")
        ]
    )]
    public function adminLeaves(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $query = LeaveRequest::with(['user:id,name,email,avatar', 'approver:id,name']);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $leaves = $query->latest('start_date')->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengajuan cuti berhasil diambil.',
            'data' => [
                'total_leaves' => $leaves->total(),
                'pending_count' => LeaveRequest::where('status', 'pending')->count(),
                'leaves' => $leaves,
            ]
        ]);
    }

    #[OA\Post(
        path: "/admin/leaves/{id}/approve",
        summary: "[HR] Setujui Permohonan Cuti",
        description: "Menyetujui pengajuan cuti atau izin karyawan/peserta magang.",
        security: [["bearerAuth" => []]],
        tags: ["Leave & Attendance Management"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Cuti berhasil disetujui.")
        ]
    )]
    public function approveLeave(Request $request, int $id): JsonResponse
    {
        $leave = LeaveRequest::findOrFail($id);

        $leave->update([
            'status' => 'approved',
            'approver_id' => $request->user()->id,
            'approved_at' => now(),
            'admin_notes' => $request->input('notes', 'Disetujui oleh HR'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti berhasil disetujui.',
            'data' => $leave,
        ]);
    }

    #[OA\Post(
        path: "/admin/leaves/{id}/reject",
        summary: "[HR] Tolak Permohonan Cuti",
        description: "Menolak pengajuan cuti beserta catatan alasan penolakan.",
        security: [["bearerAuth" => []]],
        tags: ["Leave & Attendance Management"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Cuti telah ditolak."),
            new OA\Response(response: 422, description: "Alasan penolakan wajib diisi.")
        ]
    )]
    public function rejectLeave(Request $request, int $id): JsonResponse
    {
        $leave = LeaveRequest::findOrFail($id);

        $validated = $request->validate([
            'reason' => 'required|string|min:5',
        ]);

        $leave->update([
            'status' => 'rejected',
            'approver_id' => $request->user()->id,
            'admin_notes' => $validated['reason'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti telah ditolak.',
            'data' => $leave,
        ]);
    }
}
