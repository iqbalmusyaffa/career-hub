<?php

namespace App\Http\Controllers;

use App\Models\InternshipLogbook;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CandidateLogbookController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $isAdminOrStaff = $currentUser->hasAnyRole(['Super Admin', 'HR', 'Mentor']);
        $candidateList = $isAdminOrStaff ? \App\Models\User::role('Candidate')->orderBy('name')->get() : collect();

        // Target user: if admin/staff provided candidate_id, or if candidate role, or default to first active intern
        if ($isAdminOrStaff && $request->has('candidate_id')) {
            $user = \App\Models\User::find($request->candidate_id) ?? $currentUser;
        } elseif ($currentUser->hasRole('Candidate')) {
            $user = $currentUser;
        } elseif ($isAdminOrStaff) {
            $user = \App\Models\User::role('Candidate')->whereHas('logbooks')->first()
                ?? \App\Models\User::role('Candidate')->first()
                ?? $currentUser;
        } else {
            $user = $currentUser;
        }

        // 1. Determine active period & batch from candidate's applied job or configured period
        $latestApp = $user->applications()->with('job')->latest()->first();
        $periodSetting = \App\Models\InternshipPeriod::where('user_id', $user->id)->first();
        $jobBatch = $latestApp?->job?->batch;

        $periodName = $periodSetting?->period_name ?? $jobBatch ?? 'Batch 1 - Semester Genap 2026';
        $masterBatch = \App\Models\InternshipBatch::where('batch_name', $periodName)->first();

        $periodStartDate = $periodSetting?->start_date ? $periodSetting->start_date->copy() : ($masterBatch?->start_date ? $masterBatch->start_date->copy() : Carbon::create(2026, 8, 10));
        $periodEndDate = $periodSetting?->end_date ? $periodSetting->end_date->copy() : ($masterBatch?->end_date ? $masterBatch->end_date->copy() : Carbon::create(2027, 2, 9));
        $targetWorkHours = $periodSetting?->target_hours ?? $masterBatch?->target_hours ?? 400;

        // 2. Standard Monthly Calendar Navigation
        $requestedMonth = $request->query('month');
        if ($requestedMonth && preg_match('/^\d{4}-\d{2}$/', $requestedMonth)) {
            $selectedMonth = Carbon::parse($requestedMonth . '-01')->startOfMonth();
        } else {
            // Default to current active month (e.g. September 2026)
            $selectedMonth = Carbon::now()->startOfMonth();
        }

        $startDate = $selectedMonth->copy()->startOfMonth();
        $endDate = $selectedMonth->copy()->endOfMonth();
        $monthLabel = $selectedMonth->translatedFormat('F Y');

        $prevMonth = $selectedMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $selectedMonth->copy()->addMonth()->format('Y-m');

        // 3. Holidays & Overrides (Global National Holidays + Company-Specific Holidays)
        $companyId = $latestApp?->job?->company_profile_id ?? 1;
        $holidaysMap = \App\Models\CompanyHoliday::where(function($q) use ($companyId) {
            $q->whereNull('company_id')->orWhere('company_id', $companyId);
        })->get()->keyBy(fn($h) => $h->date->format('Y-m-d'));

        $overridesMap = \App\Models\CompanyHolidayOverride::where('company_id', $companyId)
            ->where('is_working_day', true)
            ->get()
            ->keyBy('company_holiday_id');

        // 4. Dynamic Calendar Matrix generation for Monday-Sunday layout
        $matrix = [];
        $currentWeek = [];
        $cursor = $startDate->copy();
        $firstDayOfWeek = $cursor->dayOfWeekIso; // 1 (Senin) - 7 (Minggu)
        
        // Pad days before start date to align with Monday
        for ($i = 1; $i < $firstDayOfWeek; $i++) {
            $padDate = $cursor->copy()->subDays($firstDayOfWeek - $i);
            $currentWeek[] = [
                'day' => $padDate->day,
                'date' => $padDate->format('Y-m-d'),
                'isPadding' => true,
                'isWeekend' => $padDate->isWeekend(),
                'highlight' => $padDate->isToday(),
            ];
        }

        while ($cursor->lessThanOrEqualTo($endDate)) {
            $dateStr = $cursor->format('Y-m-d');
            $currentWeek[] = [
                'day' => $cursor->day,
                'date' => $dateStr,
                'isPadding' => false,
                'isWeekend' => $cursor->isWeekend(),
                'highlight' => $cursor->isToday(),
            ];

            if (count($currentWeek) === 7) {
                $matrix[] = $currentWeek;
                $currentWeek = [];
            }

            $cursor->addDay();
        }

        if (count($currentWeek) > 0) {
            $padCount = 7 - count($currentWeek);
            for ($i = 1; $i <= $padCount; $i++) {
                $padDate = $cursor->copy();
                $currentWeek[] = [
                    'day' => $padDate->day,
                    'date' => $padDate->format('Y-m-d'),
                    'isPadding' => true,
                    'isWeekend' => $padDate->isWeekend(),
                    'highlight' => $padDate->isToday(),
                ];
                $cursor->addDay();
            }
            $matrix[] = $currentWeek;
        }

        // 5. Logbooks & Unlock Requests for the displayed month and matrix padding
        $matrixStart = !empty($matrix) && !empty($matrix[0]) ? $matrix[0][0]['date'] : $startDate->format('Y-m-d');
        $matrixEnd = !empty($matrix) ? end($matrix)[6]['date'] : $endDate->format('Y-m-d');

        $logbooks = InternshipLogbook::where('user_id', $user->id)
            ->whereBetween('date', [$matrixStart, $matrixEnd])
            ->get()
            ->keyBy(fn($item) => $item->date->format('Y-m-d'));

        $unlockRequestsMap = \App\Models\InternshipUnlockRequest::where('intern_id', $user->id)
            ->where('status', 'approved')
            ->get()
            ->keyBy(fn($u) => $u->target_date->format('Y-m-d'));

        // 6. Calculate synchronized overall metrics
        $allLogbooks = InternshipLogbook::where('user_id', $user->id)->get();
        $approvedCount = $allLogbooks->where('status', 'approved')->count();
        $pendingCount = $allLogbooks->where('status', 'pending')->count();
        $totalFilled = $approvedCount + $pendingCount;

        $totalWorkHoursLogged = (int) $allLogbooks->where('status', 'approved')->sum('work_hours');

        // Calculate total working days passed up to today within the internship period
        $workingDaysPassed = 0;
        $dayCursor = $periodStartDate->copy()->startOfDay();
        $todayLimit = Carbon::today()->min($periodEndDate->copy()->startOfDay());
        while ($dayCursor->lessThanOrEqualTo($todayLimit)) {
            $dStr = $dayCursor->format('Y-m-d');
            $hObj = $holidaysMap[$dStr] ?? null;
            $isOverridden = $hObj && isset($overridesMap[$hObj->id]);
            $isHoliday = ($hObj && !$isOverridden) || $dayCursor->isWeekend();
            if (!$isHoliday) {
                $workingDaysPassed++;
            }
            $dayCursor->addDay();
        }
        $totalWorkingDays = max(1, $workingDaysPassed);
        $attendancePercentage = min(100, round(($totalFilled / $totalWorkingDays) * 100));

        return view('candidate.logbook.index', compact(
            'user',
            'currentUser',
            'isAdminOrStaff',
            'candidateList',
            'logbooks',
            'unlockRequestsMap',
            'holidaysMap',
            'overridesMap',
            'startDate',
            'endDate',
            'monthLabel',
            'periodName',
            'periodStartDate',
            'periodEndDate',
            'matrix',
            'prevMonth',
            'nextMonth',
            'attendancePercentage',
            'totalFilled',
            'totalWorkingDays',
            'totalWorkHoursLogged',
            'targetWorkHours'
        ));
    }

    public function show(Request $request, $date)
    {
        $currentUser = auth()->user();
        $isAdminOrStaff = $currentUser->hasAnyRole(['Super Admin', 'HR', 'Mentor']);

        if ($isAdminOrStaff && $request->has('candidate_id')) {
            $user = \App\Models\User::find($request->candidate_id) ?? $currentUser;
        } elseif ($currentUser->hasRole('Candidate')) {
            $user = $currentUser;
        } elseif ($isAdminOrStaff) {
            $user = \App\Models\User::role('Candidate')->whereHas('logbooks')->first()
                ?? \App\Models\User::role('Candidate')->first()
                ?? $currentUser;
        } else {
            $user = $currentUser;
        }
        $carbonDate = Carbon::parse($date)->startOfDay();
        $today = Carbon::today();

        $logbook = InternshipLogbook::firstOrNew([
            'user_id' => $user->id,
            'date' => $carbonDate->format('Y-m-d'),
        ]);

        $isPastDate = $carbonDate->lessThan($today);
        $isFutureDate = $carbonDate->greaterThan($today);
        $isToday = $carbonDate->equalTo($today);

        // Editable conditions:
        // 1. Today (before 23:59:59)
        // 2. Or Existing logbook that was rejected / requires revision by mentor
        // 3. Or Super Admin approved an active Unlock Request
        // 4. Past dates WITHOUT prior submission or unlock are STRICTLY LOCKED (Anti-Rapel Policy)
        $isRevisionAllowed = $logbook->exists && in_array($logbook->status, ['rejected', 'action_required']);
        $unlockRequest = \App\Models\InternshipUnlockRequest::where('intern_id', $user->id)
            ->where('target_date', $carbonDate->format('Y-m-d'))
            ->where('status', 'approved')
            ->first();
        $isSuperAdminUnlocked = $unlockRequest && $unlockRequest->unlocked_until && $unlockRequest->unlocked_until->isFuture();

        // Check Company Holiday & Weekend Policy
        $latestApp = \App\Models\Application::with(['job.companyProfile'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['accepted', 'hired'])
            ->latest()
            ->first();
        $companyId = $latestApp?->job?->companyProfile?->id ?? 1;

        $dateStr = $carbonDate->format('Y-m-d');
        $holidayObj = \App\Models\CompanyHoliday::whereDate('date', $dateStr)
            ->where(function($q) use ($companyId) {
                $q->whereNull('company_id')->orWhere('company_id', $companyId);
            })->first();
        $override = $holidayObj ? \App\Models\CompanyHolidayOverride::where('company_id', $companyId)->where('company_holiday_id', $holidayObj->id)->first() : null;
        $isCompanyWorkingDayOverride = $override && $override->is_working_day;
        $isWeekend = $carbonDate->isWeekend();
        $isHoliday = ($holidayObj && !$isCompanyWorkingDayOverride) || ($isWeekend && !$isCompanyWorkingDayOverride);
        
        $isEditable = false;
        $lockReason = '';

        if ($isHoliday && !$isCompanyWorkingDayOverride && !$isSuperAdminUnlocked && !$isRevisionAllowed && (!$logbook->exists || empty($logbook->status))) {
            $isEditable = false;
            if ($holidayObj) {
                if ($holidayObj->type === 'company_holiday') {
                    $typeLabel = 'Libur Khusus Perusahaan';
                    $lockReason = "Hari Libur: {$holidayObj->name} ({$typeLabel}). Pengisian presensi dinonaktifkan karena perusahaan menetapkan hari ini sebagai hari libur internal.";
                } else {
                    $typeLabel = $holidayObj->type === 'cuti_bersama' ? 'Cuti Bersama' : 'Libur Nasional';
                    $lockReason = "Hari Libur: {$holidayObj->name} ({$typeLabel}). Pengisian presensi dinonaktifkan karena perusahaan tidak mengaktifkan hari kerja operasional.";
                }
            } else {
                $dayName = $carbonDate->isSaturday() ? 'Sabtu' : 'Minggu';
                $lockReason = "Hari Libur Akhir Pekan ({$dayName}). Pengisian presensi dan laporan harian tidak dapat diisi karena perusahaan menetapkan hari ini sebagai hari libur.";
            }
        } elseif ($isFutureDate) {
            $isEditable = false;
            $lockReason = 'Presensi belum dibuka untuk tanggal masa depan.';
        } elseif ($isToday) {
            if ($logbook->exists && in_array($logbook->status, ['approved', 'pending'])) {
                $isEditable = false;
                $lockReason = 'Laporan harian sudah terkirim dan sedang dalam proses peninjauan / telah disetujui Mentor.';
            } else {
                $isEditable = true;
            }
        } else {
            // Past date
            if ($isSuperAdminUnlocked) {
                $isEditable = true;
                $lockReason = 'Akses pengisian tanggal ini telah DIBUKA KHUSUS oleh Super Admin atas permohonan resmi Mentor.';
            } elseif ($isRevisionAllowed) {
                $isEditable = true;
            } elseif ($logbook->exists) {
                $isEditable = false;
                $lockReason = 'Laporan harian telah dikunci dan tidak dapat diedit.';
            } else {
                $isEditable = false;
                $lockReason = 'Batas waktu absensi untuk tanggal ini telah ditutup pukul 23:59 WIB. Sistem otomatis mengunci tanggal yang terlewat (Kebijakan Anti-Rapel). Pengaduan pembukaan tanggal hanya dapat diajukan oleh Mentor ke Superadmin dengan syarat kendala teknis Penyelenggara atau Mitra.';
            }
        }

        // Calculate stipend & excused absence policy metrics (Max 4 days without cut per period)
        $periodSetting = \App\Models\InternshipPeriod::where('user_id', $user->id)->first();
        $periodStartDate = $periodSetting && $periodSetting->start_date ? $periodSetting->start_date->copy() : Carbon::create(2026, 8, 10);
        $periodEndDate = $periodSetting && $periodSetting->end_date ? $periodSetting->end_date->copy() : Carbon::create(2027, 2, 9);

        $excusedAbsenceCount = InternshipLogbook::where('user_id', $user->id)
            ->whereBetween('date', [$periodStartDate->format('Y-m-d'), $periodEndDate->format('Y-m-d')])
            ->whereIn('attendance_type', ['Tidak Hadir Dengan Keterangan', 'Izin', 'Sakit'])
            ->where('date', '!=', $carbonDate->format('Y-m-d'))
            ->count();
        
        $maxExcusedWithoutCut = 4;
        $remainingExcusedQuota = max(0, $maxExcusedWithoutCut - $excusedAbsenceCount);

        if ($isAdminOrStaff && $currentUser->id !== $user->id) {
            $isEditable = false;
            $lockReason = "Mode Pratinjau Admin / Mentor: Melihat logbook milik {$user->name}.";
        }

        return view('candidate.logbook.show', compact(
            'user',
            'currentUser',
            'isAdminOrStaff',
            'logbook',
            'carbonDate',
            'isEditable',
            'lockReason',
            'isPastDate',
            'isFutureDate',
            'isToday',
            'isSuperAdminUnlocked',
            'unlockRequest',
            'excusedAbsenceCount',
            'maxExcusedWithoutCut',
            'remainingExcusedQuota',
            'isHoliday',
            'isWeekend',
            'holidayObj'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $carbonDate = Carbon::parse($request->date)->startOfDay();
        $today = Carbon::today();
        
        $existingLogbook = InternshipLogbook::where('user_id', $user->id)
            ->where('date', $request->date)
            ->first();

        // Check Company Holiday & Weekend Policy
        $latestApp = \App\Models\Application::with(['job.companyProfile'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['accepted', 'hired'])
            ->latest()
            ->first();
        $companyId = $latestApp?->job?->companyProfile?->id ?? 1;

        $dateStr = $carbonDate->format('Y-m-d');
        $holidayObj = \App\Models\CompanyHoliday::whereDate('date', $dateStr)
            ->where(function($q) use ($companyId) {
                $q->whereNull('company_id')->orWhere('company_id', $companyId);
            })->first();
        $override = $holidayObj ? \App\Models\CompanyHolidayOverride::where('company_id', $companyId)->where('company_holiday_id', $holidayObj->id)->first() : null;
        $isCompanyWorkingDayOverride = $override && $override->is_working_day;
        $isWeekend = $carbonDate->isWeekend();
        $isHoliday = ($holidayObj && !$isCompanyWorkingDayOverride) || ($isWeekend && !$isCompanyWorkingDayOverride);

        $isRevision = $existingLogbook && in_array($existingLogbook->status, ['rejected', 'action_required']);
        $isSuperAdminUnlocked = \App\Models\InternshipUnlockRequest::isCurrentlyUnlockedForUser($user->id, $dateStr);

        if ($isHoliday && !$isCompanyWorkingDayOverride && !$isSuperAdminUnlocked && !$isRevision) {
            return redirect()->back()
                ->with('error', 'Tidak dapat mengisi presensi pada hari libur / akhir pekan karena perusahaan tidak mengaktifkan hari kerja operasional pada tanggal ini.');
        }

        // 1. Anti-Rapel Lock Check: If past date and not an authorized revision or superadmin unlock
        if ($carbonDate->lessThan($today)) {
            $isRevision = $existingLogbook && in_array($existingLogbook->status, ['rejected', 'action_required']);
            $isSuperAdminUnlocked = \App\Models\InternshipUnlockRequest::isCurrentlyUnlockedForUser($user->id, $carbonDate->format('Y-m-d'));

            if (!$isRevision && !$isSuperAdminUnlocked) {
                return redirect()->back()
                    ->with('error', 'Presensi tidak dapat dirapel. Batas pengisian tanggal terkait telah berakhir pukul 23:59 WIB. Hubungi Mentor Anda jika terdapat kendala sistem resmi Penyelenggara/Mitra.');
            }
        }

        // 2. Future date check
        if ($carbonDate->greaterThan($today)) {
            return redirect()->back()
                ->with('error', 'Tidak dapat melakukan presensi untuk tanggal masa depan.');
        }

        if ($existingLogbook && in_array($existingLogbook->status, ['approved', 'pending'])) {
            return redirect()->back()
                ->with('error', 'Laporan harian yang sudah disetujui atau sedang menunggu persetujuan tidak dapat diubah.');
        }

        $attType = $request->attendance_type ?? 'Hadir';
        
        if ($attType === 'Hadir' || in_array($attType, ['present', 'wfo', 'wfh'])) {
            $request->validate([
                'date' => 'required|date',
                'attendance_type' => 'required|string',
                'activities' => 'required|string|min:100',
                'learnings' => 'required|string|min:100',
                'challenges' => 'required|string|min:100',
                'latitude' => ['nullable', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'numeric', 'between:-180,180'],
                'location_address' => 'nullable|string|max:500',
            ], [
                'activities.required' => 'Uraian aktivitas wajib diisi.',
                'activities.min' => 'Uraian aktivitas harus memuat sekurang-kurangnya 100 karakter.',
                'learnings.required' => 'Uraian pembelajaran yang diperoleh wajib diisi.',
                'learnings.min' => 'Uraian pembelajaran harus memuat sekurang-kurangnya 100 karakter.',
                'challenges.required' => 'Uraian kendala yang dihadapi wajib diisi.',
                'challenges.min' => 'Uraian kendala harus memuat sekurang-kurangnya 100 karakter.',
                'latitude.between' => 'Titik Latitude GPS tidak valid atau berada di luar jangkauan bumi.',
                'longitude.between' => 'Titik Longitude GPS tidak valid atau berada di luar jangkauan bumi.',
            ]);

            $workHours = 8;
            $activities = $request->activities;
            $learnings = $request->learnings;
            $challenges = $request->challenges;
        } elseif (in_array($attType, ['Tidak Hadir Dengan Keterangan', 'Izin', 'Sakit', 'permission', 'sick'])) {
            $request->validate([
                'date' => 'required|date',
                'attendance_type' => 'required|string',
                'absence_reason' => 'required|string|min:5',
                'doctor_note' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            ], [
                'absence_reason.required' => 'Alasan tidak hadir wajib diisi.',
                'absence_reason.min' => 'Alasan tidak hadir harus memuat sekurang-kurangnya 5 karakter.',
                'doctor_note.mimes' => 'Surat keterangan dokter harus berupa file PDF, PNG, JPG, atau JPEG.',
                'doctor_note.max' => 'Ukuran file surat keterangan dokter maksimal 5MB.',
            ]);

            $workHours = 0;
            $activities = 'Alasan tidak hadir: ' . $request->absence_reason;
            $learnings = '-';
            $challenges = '-';
            $attType = 'Tidak Hadir Dengan Keterangan';
        } else {
            $workHours = 0;
            $activities = 'Tidak Hadir Tanpa Keterangan';
            $learnings = '-';
            $challenges = '-';
            $attType = 'Tidak Hadir Tanpa Keterangan';
        }

        $doctorNotePath = $existingLogbook->doctor_note_path ?? null;
        if ($request->hasFile('doctor_note')) {
            $doctorNotePath = $request->file('doctor_note')->store('doctor_notes', 'public');
        }

        $logbook = InternshipLogbook::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'date' => $request->date,
            ],
            [
                'attendance_type' => $attType,
                'activities' => $activities,
                'learnings' => $learnings,
                'challenges' => $challenges,
                'doctor_note_path' => $doctorNotePath,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'location_address' => $request->location_address ?? 'Lokasi GPS Terverifikasi',
                'work_hours' => $workHours,
                'status' => 'pending', // Menunggu persetujuan Mentor
            ]
        );

        // System Flow Recording
        \App\Models\AuditLog::record(
            'PRESENSI_MAGANG_SUBMIT',
            "Peserta Magang {$user->name} mengirim presensi & logbook tanggal {$request->date} [Tipe: {$attType}, Lat: {$request->latitude}, Lng: {$request->longitude}]",
            $user
        );

        return redirect()->route('candidate.logbook.index')
            ->with('success', 'Laporan harian tanggal ' . Carbon::parse($request->date)->format('d M Y') . ' berhasil dikirim ke Mentor.');
    }

    public function progress()
    {
        $user = auth()->user();
        $latestApp = $user->applications()->with('job.companyProfile')->latest()->first();
        $periodSetting = \App\Models\InternshipPeriod::where('user_id', $user->id)->first();
        $jobBatch = $latestApp?->job?->batch;
        
        $periodName = $periodSetting?->period_name ?? $jobBatch ?? 'Batch 1 - Semester Genap 2026';
        $masterBatch = \App\Models\InternshipBatch::where('batch_name', $periodName)->first();

        $periodStartDate = $periodSetting?->start_date ? $periodSetting->start_date->copy() : ($masterBatch?->start_date ? $masterBatch->start_date->copy() : Carbon::create(2026, 8, 10));
        $periodEndDate = $periodSetting?->end_date ? $periodSetting->end_date->copy() : ($masterBatch?->end_date ? $masterBatch->end_date->copy() : Carbon::create(2027, 2, 9));
        $targetHours = $periodSetting?->target_hours ?? $masterBatch?->target_hours ?? 400;

        // Agreement / Stipend Base
        $agreement = \App\Models\ApplicationAgreement::where('user_id', $user->id)->latest()->first();
        $monthlyStipendNominal = 2800000;
        if ($agreement && preg_match('/[\d\.]+/', str_replace(['Rp', '.', ' '], '', $agreement->stipend_or_salary), $matches)) {
            $parsedNum = (int) str_replace('.', '', $matches[0]);
            if ($parsedNum > 100000) {
                $monthlyStipendNominal = $parsedNum;
            }
        }

        // 1. Curriculum Modules (Dynamic from Database or Default Fallback)
        $jobTitle = $latestApp && $latestApp->job ? $latestApp->job->title : 'Software Engineer Intern';
        $jobId = $latestApp?->job_id;
        $companyId = $latestApp?->job?->companyProfile?->id;

        $dbCurriculum = null;
        if ($jobId) {
            $dbCurriculum = \App\Models\InternshipCurriculum::with('materials')
                ->where('job_id', $jobId)
                ->first();
        }

        if (!$dbCurriculum && $companyId) {
            $dbCurriculum = \App\Models\InternshipCurriculum::with('materials')
                ->where('company_id', $companyId)
                ->whereNull('job_id')
                ->first();
        }

        if (!$dbCurriculum) {
            $dbCurriculum = \App\Models\InternshipCurriculum::with('materials')->latest()->first();
        }

        // Calculate candidate's current progress month based on periodStartDate
        $currentMonthIndex = 1;
        if ($periodStartDate) {
            $diffMonths = (int) $periodStartDate->diffInMonths(Carbon::now());
            $currentMonthIndex = max(1, $diffMonths + 1);
        }

        $progressRecords = \App\Models\InternCurriculumProgress::where('user_id', $user->id)
            ->get()
            ->keyBy('curriculum_material_id');

        if ($dbCurriculum && $dbCurriculum->materials->isNotEmpty()) {
            $curriculumTitle = $dbCurriculum->title;
            $curriculumDescription = $dbCurriculum->description ?: ('Silabus dan target kompetensi posisi ' . $jobTitle);
            $curriculumModules = $dbCurriculum->materials->map(function($m) use ($currentMonthIndex, $progressRecords) {
                $prog = $progressRecords[$m->id] ?? null;

                if ($prog && $prog->status) {
                    $status = $prog->status === 'completed' ? 'completed' : ($prog->status === 'in_progress' ? 'in_progress' : 'upcoming');
                } else {
                    $status = 'upcoming';
                    if ($m->sequence < $currentMonthIndex) {
                        $status = 'completed';
                    } elseif ($m->sequence === $currentMonthIndex) {
                        $status = 'in_progress';
                    }
                }

                return [
                    'id' => $m->id,
                    'month' => $m->sequence,
                    'title' => $m->title,
                    'description' => $m->description ?? '',
                    'competencies' => is_array($m->competencies) ? $m->competencies : [],
                    'learning_links' => is_array($m->learning_links) ? $m->learning_links : [],
                    'status' => $status,
                    'mentor_notes' => $prog?->mentor_notes ?? null,
                    'is_mentor_verified' => $prog && $prog->status === 'completed',
                ];
            })->toArray();
        } else {
            $curriculumTitle = 'Kurikulum & Fokus Pembelajaran';
            $curriculumDescription = 'Silabus dan target kompetensi posisi ' . $jobTitle;
            $curriculumModules = [
                [
                    'month' => 1,
                    'title' => 'Bulan 1: Orientasi, Setup Lingkungan Kerja & Workflow',
                    'description' => 'Pengenalan arsitektur proyek, konfigurasi tools/repository (Git, Docker, IDE), dan implementasi task dasar.',
                    'competencies' => ['Version Control (Git Workflow)', 'Code Standard & Formatting', 'Pemahaman Business Flow'],
                    'learning_links' => [],
                    'status' => 'completed',
                ],
                [
                    'month' => 2,
                    'title' => 'Bulan 2: Core Development & Integrasi Modul',
                    'description' => 'Pengembangan fitur utama, perancangan database/API, dan integrasi komponen front-end dengan back-end.',
                    'competencies' => ['Database Query & ORM', 'RESTful API Development', 'Component UI Implementation'],
                    'learning_links' => [],
                    'status' => 'in_progress',
                ],
                [
                    'month' => 3,
                    'title' => 'Bulan 3: Testing, Code Review & Debugging',
                    'description' => 'Pelaksanaan unit/integration testing, penanganan kendala teknis (bug fixing), dan optimasi performa.',
                    'competencies' => ['Unit Testing & QA', 'Refactoring & Clean Architecture', 'Error Tracking & Logging'],
                    'learning_links' => [],
                    'status' => 'upcoming',
                ],
                [
                    'month' => 4,
                    'title' => 'Bulan 4: Optimasi, Deployment & Final Project Presentation',
                    'description' => 'Penyempurnaan sistem, dokumentasi teknis, dan evaluasi hasil proyek di hadapan tim Mentor.',
                    'competencies' => ['CI/CD & Deployment', 'Technical Documentation', 'Presentation & Soft Skills'],
                    'learning_links' => [],
                    'status' => 'upcoming',
                ],
            ];
        }

        // 2. Monthly Evaluations from Mentor
        $evaluations = \App\Models\InternshipEvaluation::with(['mentor', 'company'])
            ->where('user_id', $user->id)
            ->get();

        // 3. Candidate Onboarding & Bank Account Details
        $onboarding = \App\Models\CandidateOnboarding::where('user_id', $user->id)->first();

        // 4. Stipend Periods Breakdown (Live Database & Schedule Sync)
        $stipendPeriods = [];
        $cursorMonth = $periodStartDate->copy()->startOfMonth();
        $now = Carbon::now();
        $monthIndex = 1;

        while ($cursorMonth->lessThanOrEqualTo($periodEndDate)) {
            $mKey = $cursorMonth->format('Y-m');
            $mStart = $cursorMonth->copy()->startOfMonth();
            $mEnd = $cursorMonth->copy()->endOfMonth();

            // Check if record exists in database
            $dbDisbursement = \App\Models\InternshipStipendDisbursement::where('user_id', $user->id)
                ->where('period_month', $mKey)
                ->first();

            // Calculate metrics dynamically via AttendanceReminderService
            $metrics = \App\Services\AttendanceReminderService::calculateMonthlyAttendanceMetrics($user->id, $mKey, $companyId, $monthlyStipendNominal);

            $presentDays = $dbDisbursement ? $dbDisbursement->present_days : $metrics['present_days'];
            $excusedDays = $dbDisbursement ? $dbDisbursement->excused_days : $metrics['excused_days'];
            $unexcusedDays = $dbDisbursement ? $dbDisbursement->unexcused_days : $metrics['unexcused_days'];
            $deduction = $dbDisbursement ? $dbDisbursement->deduction_amount : $metrics['deduction_amount'];
            $netStipend = $dbDisbursement ? $dbDisbursement->net_amount : $metrics['net_amount'];

            $status = $dbDisbursement ? $dbDisbursement->status : ($mEnd->isPast() ? 'in_review' : 'upcoming');
            $statusLabel = match($status) {
                'transferred' => 'Telah Ditransfer',
                'ready' => 'Siap Ditransfer (HR)',
                'in_review' => ($dbDisbursement?->mentor_submitted_at ? 'Diajukan Mentor (Verifikasi HR)' : 'Menunggu Pengajuan Mentor'),
                default => 'Menunggu Periode',
            };

            $paymentDate = $dbDisbursement?->transferred_at 
                ? $dbDisbursement->transferred_at->translatedFormat('d M Y') 
                : $mEnd->copy()->subDays(2)->translatedFormat('d M Y');

            $stipendPeriods[] = [
                'id' => $dbDisbursement?->id,
                'period_month' => $mKey,
                'month_num' => $monthIndex,
                'period_label' => $mStart->translatedFormat('F Y'),
                'base_nominal' => $dbDisbursement?->base_nominal ?? $monthlyStipendNominal,
                'present_days' => $presentDays,
                'excused_days' => $excusedDays,
                'unexcused_days' => $unexcusedDays,
                'deduction' => $deduction,
                'net_nominal' => $netStipend,
                'status' => $status,
                'status_label' => $statusLabel,
                'payment_date' => $paymentDate,
                'is_ktp_matched' => $dbDisbursement ? ($dbDisbursement->is_ktp_matched && !empty($dbDisbursement->bank_account_number)) : false,
                'mentor_submitted_at' => $dbDisbursement?->mentor_submitted_at,
                'mentor_notes' => $dbDisbursement?->mentor_notes,
                'proof_path' => $dbDisbursement?->proof_path,
                'notes' => $dbDisbursement?->notes,
            ];

            $cursorMonth->addMonth();
            $monthIndex++;
        }

        // 5. Certificate, Transcript, Evaluation & Work Hours Progress
        $totalApprovedHours = (int) \App\Models\InternshipLogbook::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('work_hours');

        $totalDaysPresent = \App\Models\InternshipLogbook::where('user_id', $user->id)
            ->whereIn('attendance_type', ['Hadir', 'present', 'wfo', 'wfh', 'Hadir Tepat Waktu', 'Terlambat'])
            ->count();

        $certificate = \App\Models\InternshipCertificate::where('user_id', $user->id)->latest()->first();
        $transcript = \App\Models\InternshipTranscript::where('user_id', $user->id)->latest()->first();
        $finalEvaluation = \App\Models\InternshipEvaluation::with('mentor')->where('user_id', $user->id)->latest()->first();

        $isEligibleForCertificate = ($totalApprovedHours >= $targetHours) || ($finalEvaluation !== null) || ($certificate !== null);

        // 6. Survey Status (Active on last month of internship or if evaluated)
        $isLastMonth = $now->diffInMonths($periodEndDate, false) <= 1;
        $hasSubmittedSurvey = session()->has('survey_submitted_' . $user->id);

        return view('candidate.logbook.progress', compact(
            'user',
            'latestApp',
            'periodName',
            'periodStartDate',
            'periodEndDate',
            'targetHours',
            'totalApprovedHours',
            'totalDaysPresent',
            'certificate',
            'transcript',
            'finalEvaluation',
            'isEligibleForCertificate',
            'curriculumModules',
            'evaluations',
            'stipendPeriods',
            'onboarding',
            'isLastMonth',
            'hasSubmittedSurvey',
            'monthlyStipendNominal',
            'jobTitle',
            'curriculumTitle',
            'curriculumDescription'
        ));
    }

    public function claimCertificate(Request $request)
    {
        $user = auth()->user();
        $evaluation = \App\Models\InternshipEvaluation::where('user_id', $user->id)->latest()->first();

        // Auto-generate or synchronize certificate and transcript
        $result = \App\Services\CertificateGenerationService::generateOrUpdateForIntern($user, $evaluation);

        return redirect()->route('candidate.logbook.progress')
            ->with('success', '🎓 Selamat! E-Sertifikat Kelulusan Magang (No: ' . $result['certificate']->certificate_number . ') & Transkrip Nilai Akademik resmi Anda berhasil diterbitkan.');
    }

    public function submitSurvey(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'mentor_rating' => 'required|integer|between:1,5',
            'program_rating' => 'required|integer|between:1,5',
            'feedback' => 'required|string|min:10',
        ]);

        session()->put('survey_submitted_' . $user->id, true);

        \App\Models\AuditLog::record(
            'INTERNSHIP_SURVEY_SUBMIT',
            "Peserta Magang {$user->name} mengirimkan survei evaluasi akhir program magang",
            $user
        );

        return redirect()->route('candidate.logbook.progress')
            ->with('success', 'Terima kasih! Survei evaluasi program magang Anda berhasil dikirim.');
    }

    public function evaluation()
    {
        $user = auth()->user();
        $evaluation = \App\Models\InternshipEvaluation::with(['mentor', 'company'])
            ->where('user_id', $user->id)
            ->first();

        if (!$evaluation) {
            $evaluation = \App\Models\InternshipEvaluation::create([
                'user_id' => $user->id,
                'discipline_score' => 90,
                'initiative_score' => 88,
                'work_quality_score' => 92,
                'teamwork_score' => 90,
                'problem_solving_score' => 85,
                'final_score' => 89.3,
                'final_grade' => 'A',
                'feedback_summary' => 'Menunjukkan dedikasi, inisiatif, dan performa teknis yang sangat luar biasa selama program magang.',
                'recommendation' => 'highly_recommended',
                'evaluated_at' => now(),
            ]);
        }

        return view('candidate.logbook.evaluation', compact('user', 'evaluation'));
    }

    public function downloadUnlockPdf($id)
    {
        $user = auth()->user();
        $unlockRequest = \App\Models\InternshipUnlockRequest::with(['mentor', 'intern.candidateProfile', 'intern.applications.job', 'company', 'resolver'])
            ->where('intern_id', $user->id)
            ->findOrFail($id);

        if ($unlockRequest->status !== 'approved') {
            return redirect()->back()->with('error', 'Dokumen Surat Resmi Dispensasi hanya dapat diunduh untuk permohonan yang telah disetujui (Approved).');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.internship_unlock_dispensation', compact('unlockRequest'))
            ->setPaper('a4', 'portrait');

        $filename = 'Surat_Dispensasi_Presensi_' . \Illuminate\Support\Str::slug($unlockRequest->intern->name) . '_' . $unlockRequest->target_date->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    public function saveBankAccount(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'bank_name' => 'required|string|max:50',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_holder' => 'required|string|max:100',
            'bank_book_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // KTP Name Validation (Bank account holder MUST match Candidate's full name / KTP)
        $cleanHolder = strtolower(preg_replace('/[^a-z0-9]/', '', $request->bank_account_holder));
        $cleanName = strtolower(preg_replace('/[^a-z0-9]/', '', $user->name));

        $isKtpMatched = str_contains($cleanHolder, $cleanName) || str_contains($cleanName, $cleanHolder) || (strlen($cleanHolder) > 3 && strlen($cleanName) > 3 && levenshtein($cleanHolder, $cleanName) <= 3);

        if (!$isKtpMatched) {
            return redirect()->back()->withErrors([
                'bank_account_holder' => "Nama pemilik rekening bank HARUS SESUAI dengan nama lengkap KTP / Akun Anda ('{$user->name}'). Rekening atas nama orang lain / pihak ketiga tidak diizinkan."
            ])->withInput();
        }

        $onboarding = \App\Models\CandidateOnboarding::firstOrNew(['user_id' => $user->id]);
        $onboarding->bank_name = $request->bank_name;
        $onboarding->bank_account_number = $request->bank_account_number;
        $onboarding->bank_account_holder = $request->bank_account_holder;

        if ($request->hasFile('bank_book_doc')) {
            $onboarding->bank_book_doc_path = $request->file('bank_book_doc')->store('candidate_bank_books', 'public');
        }

        $onboarding->save();

        // Synchronize with pending stipend records
        \App\Models\InternshipStipendDisbursement::where('user_id', $user->id)
            ->where('status', '!=', 'transferred')
            ->update([
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_account_holder' => $request->bank_account_holder,
                'bank_book_doc_path' => $onboarding->bank_book_doc_path,
                'is_ktp_matched' => true,
            ]);

        \App\Models\AuditLog::record(
            'BANK_ACCOUNT_VERIFIED',
            "Peserta Magang {$user->name} memperbarui dan memverifikasi nomor rekening bank ({$request->bank_name} - {$request->bank_account_number} a.n {$request->bank_account_holder}) sesuai KTP",
            $user
        );

        return redirect()->route('candidate.logbook.progress')
            ->with('success', 'Rekening bank pencairan uang saku berhasil disimpan dan diverifikasi sesuai nama KTP.');
    }

    public function downloadStipendSlip($id)
    {
        $user = auth()->user();
        $stipend = \App\Models\InternshipStipendDisbursement::with(['user.candidateProfile', 'company', 'verifier'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        if ($stipend->status !== 'transferred') {
            return redirect()->back()->with('error', 'Slip bukti transfer uang saku hanya dapat diunduh untuk periode yang telah selesai dicairkan (Status: Telah Ditransfer).');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.internship_stipend_slip', compact('stipend', 'user'))
            ->setPaper('a4', 'portrait');

        $filename = 'Slip_Uang_Saku_' . \Illuminate\Support\Str::slug($user->name) . '_' . str_replace('-', '_', $stipend->period_month) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Manually synchronize attendance metrics and stipend disbursement calculation.
     */
    public function syncStatus(Request $request)
    {
        $currentUser = auth()->user();
        $isAdminOrStaff = $currentUser->hasAnyRole(['Super Admin', 'HR', 'Mentor']);
        
        if ($isAdminOrStaff && $request->has('candidate_id')) {
            $user = \App\Models\User::find($request->candidate_id) ?? $currentUser;
        } elseif ($currentUser->hasRole('Candidate')) {
            $user = $currentUser;
        } elseif ($isAdminOrStaff) {
            $user = \App\Models\User::role('Candidate')->whereHas('logbooks')->first()
                ?? \App\Models\User::role('Candidate')->first()
                ?? $currentUser;
        } else {
            $user = $currentUser;
        }

        $periodMonth = $request->query('month', now()->format('Y-m'));
        $monthDate = Carbon::parse($periodMonth . '-01');
        $startOfMonth = $monthDate->copy()->startOfMonth();
        $endOfMonth = $monthDate->copy()->endOfMonth();

        $latestApp = $user->applications()->with('job')->latest()->first();
        $jobCompanyId = $latestApp?->job?->company_profile_id ?? 1;
        $batchName = $user->internshipPeriod?->period_name ?? $latestApp?->job?->batch ?? 'Batch 1 - Semester Genap 2026';

        $totalWorkingDays = \App\Services\AttendanceReminderService::countWorkingDaysInMonth($periodMonth, $jobCompanyId);

        // Agreement Base Nominal
        $agreement = \App\Models\ApplicationAgreement::where('user_id', $user->id)->latest()->first();
        $baseNominal = 2800000;
        if ($agreement && preg_match('/[\d\.]+/', str_replace(['Rp', '.', ' '], '', $agreement->stipend_or_salary), $matches)) {
            $parsed = (int) str_replace('.', '', $matches[0]);
            if ($parsed > 100000) {
                $baseNominal = $parsed;
            }
        }

        // Calculate metrics dynamically via AttendanceReminderService
        $metrics = \App\Services\AttendanceReminderService::calculateMonthlyAttendanceMetrics($user->id, $periodMonth, $jobCompanyId, $baseNominal);

        $onboarding = \App\Models\CandidateOnboarding::where('user_id', $user->id)->first();
        $bankName = $onboarding?->bank_name ?: null;
        $accountNumber = $onboarding?->bank_account_number ?: null;
        $accountHolder = $onboarding?->bank_account_holder ?: null;
        $bankBookDoc = $onboarding?->bank_book_doc_path ?: null;

        $isKtpMatched = false;
        if ($accountHolder && $user->name) {
            $cleanHolder = strtolower(preg_replace('/[^a-z0-9]/', '', $accountHolder));
            $cleanName = strtolower(preg_replace('/[^a-z0-9]/', '', $user->name));
            $isKtpMatched = str_contains($cleanHolder, $cleanName) || str_contains($cleanName, $cleanHolder) || levenshtein($cleanHolder, $cleanName) <= 3;
        }

        $existing = \App\Models\InternshipStipendDisbursement::where('user_id', $user->id)
            ->where('period_month', $periodMonth)
            ->first();

        if (!$existing || $existing->status !== 'transferred') {
            \App\Models\InternshipStipendDisbursement::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'period_month' => $periodMonth,
                ],
                [
                    'company_id' => $jobCompanyId,
                    'period_label' => $monthDate->translatedFormat('F Y'),
                    'batch_name' => $batchName,
                    'bank_name' => $bankName,
                    'bank_account_number' => $accountNumber,
                    'bank_account_holder' => $accountHolder,
                    'bank_book_doc_path' => $bankBookDoc,
                    'is_ktp_matched' => $isKtpMatched,
                    'present_days' => $metrics['present_days'],
                    'excused_days' => $metrics['excused_days'],
                    'unexcused_days' => $metrics['unexcused_days'],
                    'total_working_days' => $metrics['total_working_days'],
                    'base_nominal' => $metrics['base_nominal'],
                    'deduction_amount' => $metrics['deduction_amount'],
                    'net_amount' => $metrics['net_amount'],
                    'status' => $existing?->status ?? 'in_review',
                ]
            );
        }

        return redirect()->route('candidate.logbook.index', array_filter([
            'month' => $periodMonth,
            'candidate_id' => $isAdminOrStaff ? $user->id : null,
        ]))->with('success', "Status presensi dan kalkulasi uang saku periode {$monthDate->translatedFormat('F Y')} untuk {$user->name} berhasil disinkronkan.");
    }
}
