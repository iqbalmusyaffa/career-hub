<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\CompanyLeavePolicy;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateLeaveController extends Controller
{
    /**
     * Display candidate's leave requests, balances, and submission form.
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's active/accepted job application
        $activeApplication = Application::with(['job.companyProfile', 'agreement'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['accepted', 'hired'])
            ->latest()
            ->first();

        $companyId = $activeApplication?->job?->companyProfile?->id ?? 1;
        $policy = CompanyLeavePolicy::getForCompany($companyId);

        // Determine Quota based on candidate category
        $isIntern = $user->isIntern();
        $isPermanent = false;

        if ($activeApplication && $activeApplication->agreement) {
            $isPermanent = $activeApplication->agreement->agreement_type === 'permanent_contract';
        }

        if ($isIntern) {
            $quotaTitle = 'Batas Izin Toleransi Magang (Non-Potong)';
            $totalQuota = $policy->internship_max_excused_days;
            $quotaSubtitle = 'Batas izin tanpa pemotongan uang saku / stipend';
        } elseif ($isPermanent) {
            $quotaTitle = 'Hak Cuti Tahunan Karyawan Tetap';
            $totalQuota = $policy->permanent_leave_quota;
            $quotaSubtitle = 'Alokasi cuti tahunan resmi per tahun';
        } else {
            $quotaTitle = 'Hak Cuti Tahunan (PKWT / Remote / Hybrid)';
            $totalQuota = $policy->annual_leave_quota;
            $quotaSubtitle = 'Alokasi cuti tahunan resmi per tahun';
        }

        // Calculate Used & Pending Days (in current year / period)
        $currentYear = Carbon::now()->year;
        $userLeaves = LeaveRequest::where('user_id', $user->id)
            ->whereYear('start_date', $currentYear)
            ->get();

        $usedDays = $userLeaves->where('status', 'approved')->sum('total_days');
        $pendingDays = $userLeaves->where('status', 'pending')->sum('total_days');
        $remainingDays = max(0, $totalQuota - $usedDays);

        $leaveRequests = LeaveRequest::with(['approver'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('candidate.leaves.index', compact(
            'user',
            'activeApplication',
            'policy',
            'isIntern',
            'isPermanent',
            'quotaTitle',
            'quotaSubtitle',
            'totalQuota',
            'usedDays',
            'pendingDays',
            'remainingDays',
            'leaveRequests'
        ));
    }

    /**
     * Submit a new leave or permission request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $isIntern = $user->isIntern();

        $allowedTypes = $isIntern
            ? 'academic_leave,family_event,sick_leave,internship_permission'
            : 'annual_leave,sick_leave,academic_leave,family_event,maternity_leave,special_leave';

        $request->validate([
            'leave_type' => "required|string|in:{$allowedTypes}",
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:5|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'leave_type.in' => $isIntern
                ? 'Peserta magang hanya diperbolehkan mengajukan Izin Akademik/Kampus, Izin Acara Keluarga/Mendesak, Izin Sakit, atau Dispensasi Magang.'
                : 'Jenis cuti/izin yang dipilih tidak valid.',
            'end_date.after_or_equal' => 'Tanggal selesai cuti harus sama atau setelah tanggal mulai.',
            'reason.min' => 'Alasan cuti/izin minimal harus 5 karakter.',
            'attachment.max' => 'Ukuran berkas lampiran maksimal adalah 5 MB.',
        ]);

        // Calculate working days (exclude weekends)
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $period = CarbonPeriod::create($startDate, $endDate);

        $workingDays = 0;
        foreach ($period as $date) {
            if (!$date->isWeekend()) {
                $workingDays++;
            }
        }

        if ($workingDays === 0) {
            return redirect()->back()->withInput()->with('error', 'Rentang tanggal yang Anda pilih hanya mencakup hari libur akhir pekan (Sabtu/Minggu).');
        }

        // Handle attachment upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = 'leave_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('leave_attachments', $filename, 'public');
            $attachmentPath = '/storage/' . $path;
        }

        $activeApplication = Application::with('job.companyProfile')
            ->where('user_id', $user->id)
            ->whereIn('status', ['accepted', 'hired'])
            ->latest()
            ->first();

        $companyId = $activeApplication?->job?->companyProfile?->id ?? null;

        $leave = LeaveRequest::create([
            'user_id' => $user->id,
            'company_id' => $companyId,
            'application_id' => $activeApplication?->id,
            'leave_type' => $request->leave_type,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'total_days' => $workingDays,
            'reason' => $request->reason,
            'attachment_path' => $attachmentPath,
            'status' => 'pending',
        ]);

        AuditLog::record(
            'create_leave_request',
            "Mengajukan permohonan {$leave->leave_type_label} selama {$workingDays} hari kerja ({$request->start_date} s/d {$request->end_date})."
        );

        return redirect()->back()->with('success', "Permohonan cuti/izin ({$workingDays} hari kerja) berhasil diajukan dan sedang menunggu tinjauan HRD/Manajemen!");
    }

    /**
     * Cancel a pending leave request.
     */
    public function cancel(LeaveRequest $leave)
    {
        $user = Auth::user();

        if ($leave->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk membatalkan pengajuan ini.');
        }

        if ($leave->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pengajuan dengan status Menunggu Persetujuan yang dapat dibatalkan.');
        }

        $leave->update(['status' => 'cancelled']);

        AuditLog::record(
            'cancel_leave_request',
            "Membatalkan pengajuan {$leave->leave_type_label} ({$leave->total_days} hari)."
        );

        return redirect()->back()->with('success', 'Permohonan cuti berhasil dibatalkan.');
    }
}
