<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyReport;
use App\Models\Blacklist;
use App\Models\Job;
use App\Models\AuditLog;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyReportController extends Controller
{
    /**
     * Display list of company Red Flag reports for Super Admin moderation.
     */
    public function index(Request $request)
    {
        $query = CompanyReport::with(['reporter', 'job'])->latest();

        // Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('report_category', $request->category);
        }

        // Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('reason_description', 'like', "%{$search}%")
                  ->orWhereHas('reporter', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        $reports = $query->paginate($perPage)->withQueryString();

        // Metrics for tabs & counters
        $counts = [
            'all' => CompanyReport::count(),
            'pending' => CompanyReport::where('status', 'pending')->count(),
            'investigating' => CompanyReport::where('status', 'investigating')->count(),
            'resolved_blacklisted' => CompanyReport::where('status', 'resolved_blacklisted')->count(),
            'dismissed' => CompanyReport::where('status', 'dismissed')->count(),
        ];

        return view('admin.company_reports.index', compact('reports', 'counts'));
    }

    /**
     * Super Admin updates status & moderates the Red Flag report.
     */
    public function updateStatus(Request $request, CompanyReport $report)
    {
        $request->validate([
            'status' => 'required|in:pending,investigating,resolved_blacklisted,dismissed',
            'admin_notes' => 'nullable|string|max:2000',
            'add_to_blacklist' => 'nullable|boolean',
            'close_company_jobs' => 'nullable|boolean',
        ]);

        $admin = Auth::user();
        $oldStatus = $report->status;
        $newStatus = $request->status;

        $report->update([
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes,
        ]);

        // Action when report is approved and blacklisted
        if ($newStatus === 'resolved_blacklisted') {
            $compNameClean = strtolower(trim($report->company_name));

            // Add company name to Anti-Fraud Blacklist if requested or default
            if ($request->boolean('add_to_blacklist', true)) {
                if (!Blacklist::isBlocked($compNameClean, 'company_name')) {
                    Blacklist::create([
                        'type' => 'company_name',
                        'value' => $compNameClean,
                        'reason' => $request->admin_notes ?: "Terbukti Red Flag ({$report->category_label}) berdasarkan verifikasi Super Admin.",
                        'blocked_by' => $admin->id,
                    ]);
                }
            }

            // Close active jobs belonging to this company name if requested
            if ($request->boolean('close_company_jobs', true)) {
                Job::where('company_name', $report->company_name)
                    ->where('status', 'active')
                    ->update(['status' => 'closed']);
            }
        }

        // Record Audit Log
        AuditLog::record(
            'red_flag_moderated',
            "Super Admin {$admin->name} memoderasi Laporan Red Flag #{$report->id} ({$report->company_name}) dari [{$oldStatus}] menjadi [{$newStatus}]."
        );

        // Notify reporting candidate
        if ($report->user_id) {
            $statusLabels = [
                'investigating' => '🔍 Sedang Diinvestigasi',
                'resolved_blacklisted' => '🛡️ Disetujui & Masuk Blacklist',
                'dismissed' => 'ℹ️ Laporan Ditinjau & Diabaikan',
                'pending' => '⏳ Menunggu Verifikasi',
            ];

            $statusText = $statusLabels[$newStatus] ?? $newStatus;
            $notifMessage = match ($newStatus) {
                'resolved_blacklisted' => "Laporan indikasi Red Flag Anda untuk '{$report->company_name}' telah diverifikasi Super Admin dan perusahaan telah resmi dimasukkan ke daftar hitam (Blacklist). Terima kasih atas kontribusi Anda!",
                'investigating' => "Laporan Red Flag Anda untuk '{$report->company_name}' saat ini sedang dalam proses investigasi mendalam oleh tim Super Admin.",
                'dismissed' => "Laporan Anda untuk '{$report->company_name}' telah selesai ditinjau oleh Super Admin. Catatan: " . ($request->admin_notes ?: 'Tidak ditemukan indikasi pelanggaran berat.'),
                default => "Status laporan Red Flag Anda untuk '{$report->company_name}' telah diperbarui ke {$statusText}.",
            };

            UserNotification::send(
                $report->user_id,
                "🚩 Pembaruan Laporan Red Flag: {$statusText}",
                $notifMessage,
                route('dashboard'),
                $newStatus === 'resolved_blacklisted' ? 'success' : ($newStatus === 'dismissed' ? 'info' : 'warning')
            );
        }

        return back()->with('success', "Status laporan Red Flag untuk '{$report->company_name}' berhasil diperbarui ke " . strtoupper($newStatus) . ".");
    }

    /**
     * Delete a report.
     */
    public function destroy(CompanyReport $report)
    {
        $comp = $report->company_name;
        $report->delete();

        AuditLog::record('red_flag_deleted', "Super Admin " . Auth::user()->name . " menghapus berkas laporan Red Flag perusahaan: {$comp}");

        return back()->with('success', "Laporan Red Flag untuk '{$comp}' berhasil dihapus.");
    }
}
