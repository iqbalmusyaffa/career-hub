<?php

namespace App\Http\Controllers;

use App\Models\CompanyReport;
use App\Models\Job;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class CompanyReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'nullable|exists:job_postings,id',
            'company_name' => 'required|string|max:255',
            'report_category' => 'required|string',
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
            // Buat folder unik terenkripsi/hash per-user (contoh: evidence_reports/usr_a8f9c2d1e4b6...)
            $userHash = substr(hash('sha256', auth()->id() . config('app.key', 'talentflow')), 0, 16);
            $userFolder = 'evidence_reports/usr_' . $userHash;

            foreach ($request->file('evidence_files') as $file) {
                // Nama file acak terenkripsi (random hash) + ekstensi asli
                $extension = $file->getClientOriginalExtension();
                $randomFileName = 'ev_' . \Illuminate\Support\Str::random(24) . '.' . $extension;
                
                $path = $file->storeAs($userFolder, $randomFileName, 'public');
                $evidenceList[] = asset('storage/' . $path);
            }
        }

        $evidencePayload = !empty($evidenceList) ? json_encode($evidenceList) : null;

        $report = CompanyReport::create([
            'user_id' => auth()->id(),
            'job_id' => $request->job_id,
            'company_name' => $request->company_name,
            'report_category' => $request->report_category,
            'reason_description' => $request->reason_description,
            'evidence_url' => $evidencePayload,
            'status' => 'pending',
        ]);

        AuditLog::record('red_flag_reported', "Melaporkan indikasi Red Flag untuk perusahaan: {$request->company_name}");

        // Beri notifikasi real-time ke semua akun Super Admin
        $superAdmins = \App\Models\User::role('Super Admin')->get();
        $reporterName = auth()->user()->name ?? 'Kandidat';
        foreach ($superAdmins as $admin) {
            \App\Models\UserNotification::send(
                $admin->id,
                "🚩 Laporan Red Flag Perusahaan Masuk",
                "Kandidat {$reporterName} melaporkan indikasi red flag pada perusahaan '{$request->company_name}'. Mohon segera ditinjau dan ditindaklanjuti.",
                route('admin.company-reports.index'),
                'warning'
            );
        }

        return back()->with('success', '🚩 Laporan indikasi Red Flag dan seluruh bukti pendukung telah berhasil dikirim ke tim Super Admin untuk diinvestigasi!');
    }
}
