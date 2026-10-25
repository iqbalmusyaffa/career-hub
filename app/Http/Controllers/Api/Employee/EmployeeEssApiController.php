<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeOvertime;
use App\Models\EmployeeReimbursement;
use App\Models\EmployeePayroll;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

class EmployeeEssApiController extends Controller
{
    // ==========================================
    // 1. PRESENSI KARYAWAN (ATTENDANCE)
    // ==========================================

    #[OA\Get(
        path: "/employee/attendance",
        summary: "[Karyawan] Status Presensi Hari Ini & Riwayat Bulan Ini",
        description: "Mengambil status kehadiran hari ini (sudah check-in/check-out) dan daftar presensi bulan berjalan.",
        security: [["bearerAuth" => []]],
        tags: ["Employee Self Service (ESS)"],
        responses: [
            new OA\Response(response: 200, description: "Data presensi berhasil dimuat."),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function attendanceToday(Request $request): JsonResponse
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $todayAttendance = EmployeeAttendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        $month = $request->query('month', Carbon::now()->format('Y-m'));
        $monthDate = Carbon::createFromFormat('Y-m', $month);

        $monthlyAttendances = EmployeeAttendance::where('user_id', $user->id)
            ->whereBetween('date', [$monthDate->copy()->startOfMonth()->toDateString(), $monthDate->copy()->endOfMonth()->toDateString()])
            ->latest('date')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Status presensi karyawan berhasil dimuat.',
            'data' => [
                'today' => $todayAttendance,
                'has_checked_in' => $todayAttendance ? $todayAttendance->is_checked_in : false,
                'has_checked_out' => $todayAttendance ? $todayAttendance->is_checked_out : false,
                'summary' => [
                    'total_present' => $monthlyAttendances->count(),
                    'on_time_count' => $monthlyAttendances->where('check_in_status', 'on_time')->count(),
                    'late_count' => $monthlyAttendances->where('check_in_status', 'late')->count(),
                    'total_duration_hours' => round($monthlyAttendances->sum('work_duration_minutes') / 60, 1),
                ],
                'history' => $monthlyAttendances,
            ]
        ]);
    }

    #[OA\Post(
        path: "/employee/attendance/check-in",
        summary: "[Karyawan] Check-in Presensi (Selfie & GPS)",
        description: "Melakukan presensi masuk kerja dengan foto selfie dan koordinat lokasi GPS.",
        security: [["bearerAuth" => []]],
        tags: ["Employee Self Service (ESS)"],
        responses: [
            new OA\Response(response: 200, description: "Check-in berhasil."),
            new OA\Response(response: 422, description: "Validasi gagal / Sudah check-in sebelumnya.")
        ]
    )]
    public function checkIn(Request $request): JsonResponse
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $existing = EmployeeAttendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($existing && $existing->is_checked_in) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan presensi masuk hari ini pada ' . $existing->formatted_check_in_time,
            ], 422);
        }

        $validated = $request->validate([
            'photo' => 'nullable|string', // base64 or photo upload
            'photo_file' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo_file')) {
            $photoPath = $request->file('photo_file')->store('employee_attendances/' . date('Y/m'), 'public');
        } elseif (!empty($validated['photo']) && str_starts_with($validated['photo'], 'data:image')) {
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['photo']);
            $decoded = base64_decode($imageData);
            $filename = 'employee_attendances/' . date('Y/m') . '/checkin_' . $user->id . '_' . time() . '.jpg';
            Storage::disk('public')->put($filename, $decoded);
            $photoPath = $filename;
        }

        $now = Carbon::now();
        $onTimeThreshold = Carbon::today()->setHour(9)->setMinute(0)->setSecond(0);
        $checkInStatus = $now->gt($onTimeThreshold) ? 'late' : 'on_time';
        $lateMinutes = $now->gt($onTimeThreshold) ? (int) $now->diffInMinutes($onTimeThreshold) : 0;

        $attendance = EmployeeAttendance::updateOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            [
                'check_in_time' => $now->toTimeString(),
                'check_in_status' => $checkInStatus,
                'late_minutes' => $lateMinutes,
                'check_in_latitude' => $validated['latitude'] ?? null,
                'check_in_longitude' => $validated['longitude'] ?? null,
                'check_in_address' => $validated['location_address'] ?? 'Lokasi Terdeteksi',
                'check_in_photo_path' => $photoPath,
                'notes' => $validated['notes'] ?? null,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Presensi masuk berhasil dicatat. Status: ' . ($checkInStatus === 'late' ? 'Terlambat ' . $lateMinutes . ' menit' : 'Tepat Waktu'),
            'data' => $attendance,
        ]);
    }

    #[OA\Post(
        path: "/employee/attendance/check-out",
        summary: "[Karyawan] Check-out Presensi Pulang Kerja",
        description: "Melakukan presensi pulang kerja dan mencatat durasi total jam kerja.",
        security: [["bearerAuth" => []]],
        tags: ["Employee Self Service (ESS)"],
        responses: [
            new OA\Response(response: 200, description: "Check-out berhasil."),
            new OA\Response(response: 422, description: "Belum check-in / Sudah check-out.")
        ]
    )]
    public function checkOut(Request $request): JsonResponse
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $attendance = EmployeeAttendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance || !$attendance->is_checked_in) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan presensi masuk hari ini.',
            ], 422);
        }

        if ($attendance->is_checked_out) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan presensi pulang hari ini pada ' . $attendance->formatted_check_out_time,
            ], 422);
        }

        $now = Carbon::now();
        $checkInDateTime = Carbon::parse($today . ' ' . $attendance->check_in_time);
        $durationMinutes = (int) $now->diffInMinutes($checkInDateTime);

        $attendance->update([
            'check_out_time' => $now->toTimeString(),
            'work_duration_minutes' => $durationMinutes,
            'check_out_latitude' => $request->input('latitude'),
            'check_out_longitude' => $request->input('longitude'),
            'check_out_address' => $request->input('location_address'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi pulang berhasil dicatat. Durasi kerja: ' . round($durationMinutes / 60, 1) . ' jam.',
            'data' => $attendance,
        ]);
    }

    // ==========================================
    // 2. PENGAJUAN LEMBUR (OVERTIME)
    // ==========================================

    #[OA\Get(
        path: "/employee/overtimes",
        summary: "[Karyawan] Daftar Riwayat Pengajuan Lembur",
        description: "Mengambil daftar pengajuan lembur dan kompensasi upah lembur karyawan.",
        security: [["bearerAuth" => []]],
        tags: ["Employee Self Service (ESS)"],
        responses: [
            new OA\Response(response: 200, description: "Daftar lembur berhasil dimuat.")
        ]
    )]
    public function overtimesList(Request $request): JsonResponse
    {
        $user = $request->user();
        $month = $request->query('month', Carbon::now()->format('Y-m'));
        $monthDate = Carbon::createFromFormat('Y-m', $month);

        $overtimes = EmployeeOvertime::where('user_id', $user->id)
            ->whereBetween('date', [$monthDate->copy()->startOfMonth()->toDateString(), $monthDate->copy()->endOfMonth()->toDateString()])
            ->latest('date')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengajuan lembur berhasil diambil.',
            'data' => [
                'total_approved_hours' => (float) $overtimes->where('status', 'approved')->sum('duration_hours'),
                'total_approved_compensation' => (int) $overtimes->where('status', 'approved')->sum('total_compensation'),
                'pending_count' => $overtimes->where('status', 'pending')->count(),
                'overtimes' => $overtimes,
            ]
        ]);
    }

    #[OA\Post(
        path: "/employee/overtimes",
        summary: "[Karyawan] Buat Pengajuan Lembur Baru",
        description: "Mengirimkan formulir pengajuan lembur kerja untuk di-review oleh HR.",
        security: [["bearerAuth" => []]],
        tags: ["Employee Self Service (ESS)"],
        responses: [
            new OA\Response(response: 201, description: "Pengajuan lembur berhasil dikirim."),
            new OA\Response(response: 422, description: "Validasi gagal")
        ]
    )]
    public function storeOvertime(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'task_description' => 'required|string|min:10|max:1000',
            'compensation_type' => 'required|in:paid,leave_balance',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $start = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $end = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);
        if ($end->lt($start)) {
            $end->addDay();
        }

        $durationHours = round($end->diffInMinutes($start) / 60, 2);
        $hourlyRate = 35000;
        $totalCompensation = $validated['compensation_type'] === 'paid' ? (int) round($durationHours * $hourlyRate) : 0;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('overtime_attachments', 'public');
        }

        $overtime = EmployeeOvertime::create([
            'user_id' => $user->id,
            'company_profile_id' => $user->currentCompanyProfile()?->id ?? 1,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration_hours' => $durationHours,
            'task_description' => $validated['task_description'],
            'compensation_type' => $validated['compensation_type'],
            'hourly_rate' => $hourlyRate,
            'total_compensation' => $totalCompensation,
            'attachment_path' => $attachmentPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan lembur sebesar ' . $durationHours . ' jam berhasil dikirim ke HR.',
            'data' => $overtime,
        ], 201);
    }

    #[OA\Delete(
        path: "/employee/overtimes/{id}",
        summary: "[Karyawan] Batalkan Pengajuan Lembur",
        description: "Membatalkan pengajuan lembur yang masih berstatus pending.",
        security: [["bearerAuth" => []]],
        tags: ["Employee Self Service (ESS)"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Pengajuan lembur berhasil dibatalkan."),
            new OA\Response(response: 403, description: "Lembur yang sudah diproses tidak dapat dibatalkan.")
        ]
    )]
    public function destroyOvertime(Request $request, int $id): JsonResponse
    {
        $overtime = EmployeeOvertime::where('user_id', $request->user()->id)->findOrFail($id);

        if ($overtime->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengajuan berstatus Menunggu Persetujuan yang dapat dibatalkan.',
            ], 403);
        }

        $overtime->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan lembur berhasil dibatalkan.',
        ]);
    }

    // ==========================================
    // 3. KLAIM REIMBURSEMENT
    // ==========================================

    #[OA\Get(
        path: "/employee/reimbursements",
        summary: "[Karyawan] Daftar Klaim Biaya Reimbursement",
        description: "Mengambil daftar riwayat klaim reimbursement operasional dan bukti struk pembayaran.",
        security: [["bearerAuth" => []]],
        tags: ["Employee Self Service (ESS)"],
        responses: [
            new OA\Response(response: 200, description: "Daftar klaim berhasil diambil.")
        ]
    )]
    public function reimbursementsList(Request $request): JsonResponse
    {
        $user = $request->user();
        $month = $request->query('month', Carbon::now()->format('Y-m'));
        $monthDate = Carbon::createFromFormat('Y-m', $month);

        $claims = EmployeeReimbursement::where('user_id', $user->id)
            ->whereBetween('transaction_date', [$monthDate->copy()->startOfMonth()->toDateString(), $monthDate->copy()->endOfMonth()->toDateString()])
            ->latest('transaction_date')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar klaim reimbursement berhasil diambil.',
            'data' => [
                'total_approved' => (int) $claims->whereIn('status', ['approved', 'paid'])->sum('amount'),
                'total_paid' => (int) $claims->where('status', 'paid')->sum('amount'),
                'pending_count' => $claims->where('status', 'pending')->count(),
                'claims' => $claims,
            ]
        ]);
    }

    #[OA\Post(
        path: "/employee/reimbursements",
        summary: "[Karyawan] Ajukan Klaim Biaya Reimbursement Baru",
        description: "Mengirimkan permohonan klaim penggantian biaya operasional beserta lampiran bukti transaksi/struk.",
        security: [["bearerAuth" => []]],
        tags: ["Employee Self Service (ESS)"],
        responses: [
            new OA\Response(response: 201, description: "Klaim berhasil diajukan."),
            new OA\Response(response: 422, description: "Validasi gagal")
        ]
    )]
    public function storeReimbursement(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'category' => 'required|in:transport,medical,communication,meal_overtime,operational,other',
            'title' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'amount' => 'required|integer|min:1000',
            'description' => 'required|string|min:10|max:1000',
            'receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bank_name' => 'required|string|max:50',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_holder' => 'required|string|max:150',
        ]);

        $receiptPath = $request->file('receipt')->store('reimbursement_receipts', 'public');
        $claimNumber = 'REIMB-' . date('Ym') . '-' . strtoupper(Str::random(6));

        $claim = EmployeeReimbursement::create([
            'user_id' => $user->id,
            'company_profile_id' => $user->currentCompanyProfile()?->id ?? 1,
            'claim_number' => $claimNumber,
            'category' => $validated['category'],
            'title' => $validated['title'],
            'transaction_date' => $validated['transaction_date'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'receipt_path' => $receiptPath,
            'bank_name' => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_holder' => $validated['bank_account_holder'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Klaim reimbursement ' . $claimNumber . ' berhasil dikirim ke Finance/HR.',
            'data' => $claim,
        ], 201);
    }

    #[OA\Delete(
        path: "/employee/reimbursements/{id}",
        summary: "[Karyawan] Batalkan Pengajuan Klaim",
        description: "Membatalkan klaim reimbursement yang masih berstatus pending.",
        security: [["bearerAuth" => []]],
        tags: ["Employee Self Service (ESS)"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Klaim berhasil dibatalkan."),
            new OA\Response(response: 403, description: "Klaim yang sudah diproses tidak dapat dibatalkan.")
        ]
    )]
    public function destroyReimbursement(Request $request, int $id): JsonResponse
    {
        $claim = EmployeeReimbursement::where('user_id', $request->user()->id)->findOrFail($id);

        if ($claim->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya klaim berstatus Menunggu Persetujuan yang dapat dibatalkan.',
            ], 403);
        }

        $claim->delete();

        return response()->json([
            'success' => true,
            'message' => 'Klaim reimbursement berhasil dibatalkan.',
        ]);
    }

    // ==========================================
    // 4. PAYROLL & E-SLIP GAJI
    // ==========================================

    #[OA\Get(
        path: "/employee/payrolls",
        summary: "[Karyawan] Riwayat Slip Gaji & Take Home Pay",
        description: "Mengambil daftar E-Slip Gaji yang telah terbit (published/paid) dan rincian gaji bulan berjalan.",
        security: [["bearerAuth" => []]],
        tags: ["Employee Self Service (ESS)"],
        responses: [
            new OA\Response(response: 200, description: "Daftar slip gaji berhasil dimuat.")
        ]
    )]
    public function payrollsList(Request $request): JsonResponse
    {
        $user = $request->user();

        $payrolls = EmployeePayroll::where('user_id', $user->id)
            ->whereIn('status', ['published', 'paid'])
            ->latest('period_month')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat E-Slip Gaji berhasil diambil.',
            'data' => [
                'total_slips' => $payrolls->count(),
                'latest_payroll' => $payrolls->first(),
                'payrolls' => $payrolls,
            ]
        ]);
    }

    #[OA\Get(
        path: "/employee/payrolls/{id}",
        summary: "[Karyawan] Rincian Detail E-Slip Gaji Bulanan",
        description: "Mengambil rincian breakdown gaji pokok, upah lembur, tunjangan, potongan BPJS/PPH21, dan take-home pay.",
        security: [["bearerAuth" => []]],
        tags: ["Employee Self Service (ESS)"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Detail slip gaji berhasil diambil."),
            new OA\Response(response: 404, description: "Slip gaji tidak ditemukan.")
        ]
    )]
    public function payrollDetail(Request $request, int $id): JsonResponse
    {
        $payroll = EmployeePayroll::where('user_id', $request->user()->id)
            ->whereIn('status', ['published', 'paid'])
            ->with(['company'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail slip gaji berhasil diambil.',
            'data' => $payroll,
        ]);
    }
}
