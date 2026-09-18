<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\CompanyHoliday;
use App\Models\CompanyHolidayOverride;
use App\Models\InternshipBatch;
use App\Models\InternshipPeriod;
use App\Models\User;
use Illuminate\Http\Request;

class MentorSettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // National Holidays & Cuti Bersama managed by Super Admin (Global)
        $nationalHolidays = CompanyHoliday::whereNull('company_id')
            ->orderBy('date', 'asc')
            ->get();

        // Get mentor/HR's company ID
        $companyId = $user->companyProfile ? $user->companyProfile->id : 1;

        // Custom Company Holidays created specifically by HR / Mentor
        $companyHolidays = CompanyHoliday::where('company_id', $companyId)
            ->orderBy('date', 'asc')
            ->get();

        // Holiday Overrides set by HR/Mentor for their company
        $overrides = CompanyHolidayOverride::where('company_id', $companyId)
            ->get()
            ->keyBy('company_holiday_id');

        $periods = InternshipPeriod::with('intern')->orderBy('start_date', 'asc')->get();

        // Centralized Master Batches
        $masterBatches = InternshipBatch::where(function($q) use ($companyId) {
                $q->whereNull('company_id')->orWhere('company_id', $companyId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // If no master batches exist yet, auto-seed defaults from existing periods/curricula
        if ($masterBatches->isEmpty()) {
            InternshipBatch::findOrCreateByName('Batch 1 - Semester Genap 2026', $companyId, [
                'start_date' => '2026-08-10',
                'end_date' => '2027-02-09',
                'target_hours' => 400,
                'status' => 'active',
                'description' => 'Program Magang Semester Genap 2026 / 2027'
            ]);

            $masterBatches = InternshipBatch::where(function($q) use ($companyId) {
                    $q->whereNull('company_id')->orWhere('company_id', $companyId);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        $interns = User::role('Candidate')
            ->with(['internshipPeriod', 'candidateProfile'])
            ->orderBy('name')
            ->get()
            ->filter(function($u) {
                $hasResigned = \App\Models\InternshipResignation::where('user_id', $u->id)->where('status', 'approved')->exists();
                $hasTerminated = \App\Models\EmployeeTermination::where('user_id', $u->id)->exists();
                return !$hasResigned && !$hasTerminated;
            })
            ->values();

        $unassignedCount = $interns->filter(fn($i) => !$i->internshipPeriod)->count();
        $assignedCount = $interns->filter(fn($i) => (bool)$i->internshipPeriod)->count();

        return view('mentor.settings.index', compact('nationalHolidays', 'companyHolidays', 'overrides', 'periods', 'masterBatches', 'interns', 'unassignedCount', 'assignedCount'));
    }

    public function storeMasterBatch(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->companyProfile ? $user->companyProfile->id : null;

        $request->validate([
            'id' => 'nullable|exists:internship_batches,id',
            'batch_name' => 'required|string|max:150',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'target_hours' => 'required|integer|min:1',
            'status' => 'required|in:active,closed,draft',
            'description' => 'nullable|string',
        ]);

        if ($request->filled('id')) {
            $batch = InternshipBatch::findOrFail($request->id);
            $batch->update([
                'batch_name' => $request->batch_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'target_hours' => $request->target_hours,
                'status' => $request->status,
                'description' => $request->description,
            ]);
            $msg = "Master Batch '{$batch->batch_name}' berhasil diperbarui.";
        } else {
            $batch = InternshipBatch::create([
                'company_id' => $companyId,
                'batch_name' => $request->batch_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'target_hours' => $request->target_hours,
                'status' => $request->status,
                'description' => $request->description,
                'created_by' => $user->id,
            ]);
            $msg = "Master Batch '{$batch->batch_name}' berhasil dibuat secara terpusat.";
        }

        return redirect()->back()->with('success', $msg);
    }

    public function deleteMasterBatch($id)
    {
        $batch = InternshipBatch::findOrFail($id);
        $batch->delete();

        return redirect()->back()->with('success', "Master Batch '{$batch->batch_name}' berhasil dihapus.");
    }

    public function storePeriod(Request $request)
    {
        $request->validate([
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'user_id' => 'nullable|exists:users,id',
            'period_name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'target_hours' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $companyId = $user->companyProfile ? $user->companyProfile->id : 1;

        $targetUserIds = [];
        if (!empty($request->user_ids)) {
            $targetUserIds = $request->user_ids;
        } elseif ($request->filled('user_id')) {
            $targetUserIds = [$request->user_id];
        }

        if (empty($targetUserIds)) {
            return redirect()->back()->with('error', 'Silakan pilih minimal 1 peserta magang.');
        }

        if ($request->filled('period_name')) {
            InternshipBatch::findOrCreateByName($request->period_name, $companyId, [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'target_hours' => $request->target_hours,
                'status' => 'active',
            ]);
        }

        foreach ($targetUserIds as $uId) {
            InternshipPeriod::updateOrCreate(
                [
                    'user_id' => $uId,
                    'period_name' => $request->period_name,
                ],
                [
                    'company_id' => $companyId,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'target_hours' => $request->target_hours,
                ]
            );
        }

        $count = count($targetUserIds);
        return redirect()->back()
            ->with('success', "Pengaturan Periode Magang berhasil disimpan untuk {$count} peserta magang.");
    }

    public function deletePeriod($id)
    {
        $user = auth()->user();
        $companyId = $user->companyProfile ? $user->companyProfile->id : 1;

        InternshipPeriod::where('id', $id)
            ->where('company_id', $companyId)
            ->delete();

        return redirect()->back()->with('success', 'Pengaturan batch peserta berhasil dihapus.');
    }

    public function bulkGenerateCertificates(Request $request)
    {
        $request->validate([
            'batch_name' => 'required|string',
        ]);

        $batchName = $request->batch_name;
        $user = auth()->user();
        $companyId = $user->companyProfile ? $user->companyProfile->id : 1;

        // Find all interns belonging to this batch
        $interns = User::role('Candidate')
            ->whereHas('internshipPeriods', function($q) use ($batchName) {
                $q->where('period_name', $batchName);
            })
            ->with(['candidateProfile', 'applications.job', 'internshipPeriod'])
            ->get();

        if ($interns->isEmpty()) {
            return redirect()->back()->with('error', "Tidak ditemukan peserta magang pada batch '{$batchName}'.");
        }

        $countGenerated = 0;
        foreach ($interns as $intern) {
            $latestApp = $intern->applications()->latest()->first();
            if (!$latestApp) continue;

            // Check if certificate already exists
            $existing = \App\Models\InternshipCertificate::where('user_id', $intern->id)->first();
            if ($existing) continue;

            $period = $intern->internshipPeriod;
            $startDate = $period?->start_date ?? now()->subMonths(6);
            $endDate = $period?->end_date ?? now();
            $certNumber = 'CERT/' . strtoupper(preg_replace('/[^A-Za-z0-9]/', '-', $batchName)) . '/' . date('Y') . '/' . str_pad($intern->id, 4, '0', STR_PAD_LEFT);

            $certificate = \App\Models\InternshipCertificate::create([
                'application_id' => $latestApp->id,
                'user_id' => $intern->id,
                'certificate_number' => $certNumber,
                'participant_name' => $intern->name,
                'institution_name' => $intern->candidateProfile?->education_institution ?? 'Universitas / Sekolah Mitra',
                'job_title' => $latestApp->job?->title ?? 'Peserta Magang',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'performance_grade' => 'Sangat Memuaskan (A)',
                'mentor_name' => $user->name ?? 'Mentor Pembimbing Magang',
                'mentor_phone' => $user->phone ?? '-',
                'mentor_email' => $user->email ?? '-',
                'hr_name' => $user->name,
                'owner_name' => 'Pimpinan Perusahaan',
                'issued_at' => now(),
            ]);

            // Notify Intern
            \App\Models\UserNotification::send(
                $intern->id,
                '🎓 E-Sertifikat Magang Telah Terbit!',
                "Selamat! E-Sertifikat resmi kelulusan magang untuk {$batchName} telah diterbitkan dan siap diunduh.",
                route('candidate.certificates.show', $certificate->id),
                'success'
            );

            $countGenerated++;
        }

        return redirect()->back()->with('success', "Berhasil menerbitkan e-sertifikat untuk {$countGenerated} peserta magang pada batch '{$batchName}'.");
    }

    public function syncGovernmentHolidays(\App\Services\IndonesianHolidayService $service)
    {
        try {
            $count = $service->syncHolidays();
            return redirect()->back()->with('success', "Berhasil menyinkronkan {$count} Hari Libur Nasional & Cuti Bersama Pemerintah dari API.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyinkronkan hari libur: ' . $e->getMessage());
        }
    }
}
