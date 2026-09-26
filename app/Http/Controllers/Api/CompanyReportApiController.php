<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CompanyReport;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

class CompanyReportApiController extends Controller
{
    #[OA\Post(
        path: "/company-reports",
        summary: "Laporkan Indikasi Penipuan / Red Flag Perusahaan",
        description: "Mengirimkan laporan pengaduan penipuan, pungutan liar, atau pelanggaran oleh perusahaan/lowongan kerja.",
        tags: ["Public & Reports"]
    )]
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'job_id' => 'nullable|exists:job_postings,id',
            'company_name' => 'required|string|max:255',
            'report_category' => 'required|string|in:Penipuan / Pungutan Biaya,Pelecehan / Diskriminasi,Gaji di Bawah Standar / Tidak Sesuai Kontrak,Perusahaan Fiktif,Lainnya',
            'reason_description' => 'required|string|min:15',
            'evidence_url' => 'nullable|string|max:1000',
            'evidence_files' => 'nullable|array|max:5',
            'evidence_files.*' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        $evidenceList = [];

        if ($request->filled('evidence_url')) {
            $evidenceList[] = $request->evidence_url;
        }

        if ($request->hasFile('evidence_files')) {
            $userHash = substr(hash('sha256', auth()->id() . config('app.key', 'talentflow')), 0, 16);
            $userFolder = 'evidence_reports/usr_' . $userHash;

            foreach ($request->file('evidence_files') as $file) {
                $extension = $file->getClientOriginalExtension();
                $randomFileName = 'ev_' . Str::random(24) . '.' . $extension;
                $path = $file->storeAs($userFolder, $randomFileName, 'public');
                $evidenceList[] = asset('storage/' . $path);
            }
        }

        $evidencePayload = !empty($evidenceList) ? json_encode($evidenceList) : null;

        $report = CompanyReport::create([
            'user_id' => auth()->id(),
            'job_id' => $validated['job_id'] ?? null,
            'company_name' => $validated['company_name'],
            'report_category' => $validated['report_category'],
            'reason_description' => $validated['reason_description'],
            'evidence_url' => $evidencePayload,
            'status' => 'pending',
        ]);

        AuditLog::record('red_flag_reported', "Melaporkan indikasi Red Flag untuk perusahaan: {$validated['company_name']}");

        // Notify Super Admins
        $superAdmins = User::role('Super Admin')->get();
        $reporterName = auth()->user()->name ?? 'Kandidat';
        foreach ($superAdmins as $admin) {
            UserNotification::send(
                $admin->id,
                "🚩 Laporan Red Flag Perusahaan Masuk",
                "Kandidat {$reporterName} melaporkan indikasi red flag pada perusahaan '{$validated['company_name']}'.",
                route('admin.company-reports.index'),
                'warning'
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan indikasi Red Flag dan bukti pendukung telah berhasil dikirim ke tim admin.',
            'data' => $report,
        ], 201);
    }

    #[OA\Get(
        path: "/admin/company-reports",
        summary: "Daftar Laporan Red Flag (Super Admin)",
        description: "Mengambil daftar laporan pengaduan perusahaan dari kandidat untuk dimoderasi.",
        tags: ["Admin Super & Moderation"]
    )]
    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $query = CompanyReport::with(['user:id,name,email', 'job:id,title']);

        if ($status) {
            $query->where('status', $status);
        }

        $reports = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Daftar laporan red flag berhasil dimuat.',
            'data' => $reports,
        ]);
    }

    #[OA\Patch(
        path: "/admin/company-reports/{id}/status",
        summary: "Update Status Laporan Red Flag (Super Admin)",
        description: "Memperbarui status penanganan laporan (investigating, resolved, dismissed).",
        tags: ["Admin Super & Moderation"]
    )]
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $report = CompanyReport::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,investigating,resolved,dismissed',
            'admin_notes' => 'nullable|string',
        ]);

        $report->update([
            'status' => $validated['status'],
        ]);

        AuditLog::record('red_flag_status_updated', "Mengubah status laporan #{$id} ({$report->company_name}) menjadi {$validated['status']}");

        return response()->json([
            'success' => true,
            'message' => 'Status laporan red flag berhasil diperbarui.',
            'data' => $report,
        ]);
    }
}
