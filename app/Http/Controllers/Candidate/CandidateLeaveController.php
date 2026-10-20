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
    public function index(Request $request)
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

        $currentYear = Carbon::now()->year;
        $selectedMonth = $request->query('month');
        if (!$selectedMonth || !preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = Carbon::now()->format('Y-m');
        }

        $periodMonths = [];
        $totalUsedAllTime = 0;

        if ($isIntern) {
            $periodSetting = \App\Models\InternshipPeriod::where('user_id', $user->id)->first();
            $masterBatch = \App\Models\InternshipBatch::where('batch_name', $periodSetting?->period_name ?? $activeApplication?->job?->batch)->first();
            $periodStartDate = $periodSetting?->start_date ? $periodSetting->start_date->copy() : ($masterBatch?->start_date ? $masterBatch->start_date->copy() : Carbon::create(2026, 8, 10));
            $periodEndDate = $periodSetting?->end_date ? $periodSetting->end_date->copy() : ($masterBatch?->end_date ? $masterBatch->end_date->copy() : Carbon::create(2027, 2, 9));

            // Generate period month options for intern
            $cursorMonth = $periodStartDate->copy()->startOfMonth();
            while ($cursorMonth->lessThanOrEqualTo($periodEndDate)) {
                $periodMonths[] = [
                    'key' => $cursorMonth->format('Y-m'),
                    'label' => $cursorMonth->translatedFormat('F Y'),
                    'is_current' => $cursorMonth->format('Y-m') === Carbon::now()->format('Y-m'),
                ];
                $cursorMonth->addMonth();
            }

            try {
                $currentPeriodDate = Carbon::createFromFormat('Y-m', $selectedMonth);
            } catch (\Exception $e) {
                $selectedMonth = Carbon::now()->format('Y-m');
                $currentPeriodDate = Carbon::createFromFormat('Y-m', $selectedMonth);
            }

            $periodLabel = $currentPeriodDate->translatedFormat('F Y');
            $quotaTitle = 'Batas Bebas Potong (' . $periodLabel . ')';
            $totalQuota = $policy->internship_max_excused_days;
            $quotaSubtitle = 'Kuota toleransi 5 hari per bulan (ter-reset otomatis setiap awal bulan)';

            $monthStart = $currentPeriodDate->copy()->startOfMonth();
            $monthEnd = $currentPeriodDate->copy()->endOfMonth();

            // Calculate used & pending days in this specific month/period
            $userLeaves = LeaveRequest::where('user_id', $user->id)
                ->where(function ($q) use ($monthStart, $monthEnd) {
                    $q->whereBetween('start_date', [$monthStart, $monthEnd])
                      ->orWhereBetween('end_date', [$monthStart, $monthEnd]);
                })
                ->get();

            $usedDays = $userLeaves->where('status', 'approved')->sum('total_days');
            $pendingDays = $userLeaves->where('status', 'pending')->sum('total_days');
            $rejectedDays = $userLeaves->where('status', 'rejected')->sum('total_days');
            $remainingDays = max(0, $totalQuota - $usedDays);
            $excessDays = max(0, $usedDays - $totalQuota);

            $totalUsedAllTime = LeaveRequest::where('user_id', $user->id)
                ->where('status', 'approved')
                ->sum('total_days');
            $selectedYear = $currentYear;
            $availableYears = [];
        } else {
            $selectedYear = (int) $request->query('year', $currentYear);
            $availableYears = [$currentYear - 1, $currentYear, $currentYear + 1];
            $periodLabel = 'Tahun ' . $selectedYear;

            if ($isPermanent) {
                $quotaTitle = 'Hak Cuti Karyawan Tetap (' . $periodLabel . ')';
                $totalQuota = $policy->permanent_leave_quota;
                $quotaSubtitle = 'Alokasi cuti resmi per tahun kalender (ter-reset otomatis setiap 1 Januari)';
            } else {
                $quotaTitle = 'Hak Cuti PKWT (' . $periodLabel . ')';
                $totalQuota = $policy->annual_leave_quota;
                $quotaSubtitle = 'Alokasi cuti resmi per tahun kalender (ter-reset otomatis setiap 1 Januari)';
            }

            $userLeaves = LeaveRequest::where('user_id', $user->id)
                ->whereYear('start_date', $selectedYear)
                ->get();

            $usedDays = $userLeaves->where('status', 'approved')->sum('total_days');
            $pendingDays = $userLeaves->where('status', 'pending')->sum('total_days');
            $rejectedDays = $userLeaves->where('status', 'rejected')->sum('total_days');
            $remainingDays = max(0, $totalQuota - $usedDays);
            $excessDays = 0;
            $totalUsedAllTime = LeaveRequest::where('user_id', $user->id)
                ->where('status', 'approved')
                ->sum('total_days');
        }

        // Leave requests list - with optional month filtering
        $filterMonth = $request->query('filter_month');
        $query = LeaveRequest::with(['approver'])
            ->where('user_id', $user->id);

        if ($filterMonth && $filterMonth !== 'all') {
            try {
                $fDate = Carbon::createFromFormat('Y-m', $filterMonth);
                $query->where(function ($q) use ($fDate) {
                    $q->whereBetween('start_date', [$fDate->copy()->startOfMonth(), $fDate->copy()->endOfMonth()])
                      ->orWhereBetween('end_date', [$fDate->copy()->startOfMonth(), $fDate->copy()->endOfMonth()]);
                });
            } catch (\Exception $e) {}
        }

        $leaveRequests = $query->orderByDesc('start_date')->paginate(10)->withQueryString();

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
            'rejectedDays',
            'remainingDays',
            'excessDays',
            'totalUsedAllTime',
            'leaveRequests',
            'selectedMonth',
            'selectedYear',
            'availableYears',
            'periodLabel',
            'periodMonths',
            'filterMonth'
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

        // Validasi kuota toleransi 5 hari per bulan untuk peserta magang
        if ($isIntern) {
            $policy = $companyId ? CompanyLeavePolicy::getForCompany($companyId) : CompanyLeavePolicy::first();
            $maxExcusedPerMonth = $policy?->internship_max_excused_days ?? 5;

            $reqMonthStart = $startDate->copy()->startOfMonth();
            $reqMonthEnd = $startDate->copy()->endOfMonth();

            $usedInMonth = LeaveRequest::where('user_id', $user->id)
                ->where('status', 'approved')
                ->where(function ($q) use ($reqMonthStart, $reqMonthEnd) {
                    $q->whereBetween('start_date', [$reqMonthStart, $reqMonthEnd])
                      ->orWhereBetween('end_date', [$reqMonthStart, $reqMonthEnd]);
                })
                ->sum('total_days');

            $remainingQuota = max(0, $maxExcusedPerMonth - $usedInMonth);

            if ($remainingQuota === 0) {
                return redirect()->back()->withInput()->with('error', "Kuota toleransi izin bebas potong Anda untuk bulan {$startDate->translatedFormat('F Y')} telah habis ({$maxExcusedPerMonth}/{$maxExcusedPerMonth} hari telah digunakan). Sesuai kebijakan, hari yang diajukan melebihi kuota toleransi langsung berstatus Ditolak dan dicatat sebagai Tidak Hadir (Alpa) dengan pemotongan uang saku proporsional.");
            }

            if ($workingDays > $remainingQuota) {
                return redirect()->back()->withInput()->with('error', "Pengajuan izin Anda ({$workingDays} hari kerja) melebihi sisa kuota toleransi bebas potong bulan {$startDate->translatedFormat('F Y')} (tersisa {$remainingQuota} hari dari batas {$maxExcusedPerMonth} hari). Silakan ajukan maksimal {$remainingQuota} hari kerja, karena hari kelebihan langsung berstatus Ditolak dan dicatat sebagai Tidak Hadir (Alpa) dengan pemotongan uang saku.");
            }
        }

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
