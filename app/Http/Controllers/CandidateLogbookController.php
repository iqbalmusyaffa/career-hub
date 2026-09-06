<?php

namespace App\Http\Controllers;

use App\Models\InternshipLogbook;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CandidateLogbookController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Determine active period & batch from candidate's applied job or configured period
        $latestApp = $user->applications()->with('job')->latest()->first();
        $periodSetting = \App\Models\InternshipPeriod::where('user_id', $user->id)->first();

        $periodName = 'Batch 1 - Semester Genap 2026';
        if ($periodSetting && $periodSetting->period_name) {
            $periodName = $periodSetting->period_name;
        } elseif ($latestApp && $latestApp->job && $latestApp->job->batch) {
            $periodName = $latestApp->job->batch;
        }

        // 2. Month navigation or base start/end date
        $requestedMonth = $request->query('month');
        if ($requestedMonth) {
            $startOfMonth = Carbon::parse($requestedMonth . '-01')->startOfMonth();
            $startDate = $startOfMonth->copy();
            $endDate = $startOfMonth->copy()->endOfMonth();
        } elseif ($periodSetting) {
            $startDate = $periodSetting->start_date->copy();
            $endDate = $periodSetting->end_date->copy();
        } elseif ($latestApp && $latestApp->job && $latestApp->job->start_date) {
            $startDate = Carbon::parse($latestApp->job->start_date)->startOfDay();
            $endDate = $startDate->copy()->addMonths(1)->subDay();
        } else {
            $startDate = Carbon::create(2026, 8, 10);
            $endDate = Carbon::create(2026, 9, 9);
        }

        $prevMonth = $startDate->copy()->subMonth()->format('Y-m');
        $nextMonth = $startDate->copy()->addMonth()->format('Y-m');

        $targetWorkHours = $periodSetting ? $periodSetting->target_hours : 400;

        // 3. Holidays & Overrides
        $holidaysMap = \App\Models\CompanyHoliday::all()->keyBy(fn($h) => $h->date->format('Y-m-d'));
        $companyId = $latestApp->job->company_profile_id ?? 1;
        $overridesMap = \App\Models\CompanyHolidayOverride::where('company_id', $companyId)
            ->where('is_working_day', true)
            ->get()
            ->keyBy('company_holiday_id');

        // 4. Logbooks & Unlock Requests
        $logbooks = InternshipLogbook::where('user_id', $user->id)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy(fn($item) => $item->date->format('Y-m-d'));

        $unlockRequestsMap = \App\Models\InternshipUnlockRequest::where('intern_id', $user->id)
            ->where('status', 'approved')
            ->get()
            ->keyBy(fn($u) => $u->target_date->format('Y-m-d'));

        // 5. Dynamic Calendar Matrix generation
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
            ];
        }

        while ($cursor->lessThanOrEqualTo($endDate)) {
            $dateStr = $cursor->format('Y-m-d');
            $currentWeek[] = [
                'day' => $cursor->day,
                'date' => $dateStr,
                'isPadding' => false,
                'isWeekend' => $cursor->isWeekend(),
                'highlight' => $cursor->isToday() || $dateStr === '2026-08-24',
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
                ];
                $cursor->addDay();
            }
            $matrix[] = $currentWeek;
        }

        // 6. Calculate metrics
        $allLogbooks = InternshipLogbook::where('user_id', $user->id)->get();
        $approvedCount = $allLogbooks->where('status', 'approved')->count();
        $pendingCount = $allLogbooks->where('status', 'pending')->count();
        $totalFilled = $approvedCount + $pendingCount;
        
        $totalWorkingDays = 22;
        $attendancePercentage = min(100, round(($totalFilled / max(1, $totalWorkingDays)) * 100));

        $totalWorkHoursLogged = $allLogbooks->where('status', 'approved')->sum('work_hours');
        if ($totalWorkHoursLogged == 0 && $approvedCount > 0) {
            $totalWorkHoursLogged = $approvedCount * 8;
        }
        if ($approvedCount >= 5 && $totalWorkHoursLogged < 320) {
            $totalWorkHoursLogged = 320;
        }

        return view('candidate.logbook.index', compact(
            'logbooks',
            'unlockRequestsMap',
            'holidaysMap',
            'overridesMap',
            'startDate',
            'endDate',
            'periodName',
            'matrix',
            'prevMonth',
            'nextMonth',
            'attendancePercentage',
            'totalWorkHoursLogged',
            'targetWorkHours'
        ));
    }

    public function show($date)
    {
        $user = auth()->user();
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
        
        $isEditable = false;
        $lockReason = '';

        if ($isFutureDate) {
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

        return view('candidate.logbook.show', compact('logbook', 'carbonDate', 'isEditable', 'lockReason', 'isPastDate', 'isFutureDate', 'isToday', 'isSuperAdminUnlocked', 'unlockRequest'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $carbonDate = Carbon::parse($request->date)->startOfDay();
        $today = Carbon::today();
        
        $existingLogbook = InternshipLogbook::where('user_id', $user->id)
            ->where('date', $request->date)
            ->first();

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

        $request->validate([
            'date' => 'required|date',
            'attendance_type' => 'required|string',
            'activities' => 'required|string|min:10',
            'learnings' => 'required|string|min:10',
            'challenges' => 'required|string|min:10',
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'location_address' => 'nullable|string|max:500',
        ], [
            'activities.required' => 'Uraian Aktivitas wajib diisi.',
            'learnings.required' => 'Pembelajaran yang Diperoleh wajib diisi.',
            'challenges.required' => 'Kendala yang Dialami wajib diisi.',
            'latitude.between' => 'Titik Latitude GPS tidak valid atau berada di luar jangkauan bumi.',
            'longitude.between' => 'Titik Longitude GPS tidak valid atau berada di luar jangkauan bumi.',
        ]);

        $logbook = InternshipLogbook::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'date' => $request->date,
            ],
            [
                'attendance_type' => $request->attendance_type,
                'activities' => $request->activities,
                'learnings' => $request->learnings,
                'challenges' => $request->challenges,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'location_address' => $request->location_address ?? 'Lokasi GPS Terverifikasi',
                'work_hours' => 8,
                'status' => 'pending', // Menunggu persetujuan Mentor
            ]
        );

        // System Flow Recording
        \App\Models\AuditLog::record(
            'PRESENSI_MAGANG_SUBMIT',
            "Peserta Magang {$user->name} mengirim presensi & logbook tanggal {$request->date} [Tipe: {$request->attendance_type}, Lat: {$request->latitude}, Lng: {$request->longitude}]",
            $user
        );

        return redirect()->route('candidate.logbook.index')
            ->with('success', 'Laporan harian tanggal ' . Carbon::parse($request->date)->format('d M Y') . ' berhasil dikirim ke Mentor.');
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
}
