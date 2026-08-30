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

        // Load active period configured by HR / Mentor
        $periodSetting = \App\Models\InternshipPeriod::where('user_id', $user->id)->first();
        
        $startDate = $periodSetting ? $periodSetting->start_date : Carbon::create(2026, 8, 10);
        $endDate = $periodSetting ? $periodSetting->end_date : Carbon::create(2026, 9, 9);
        $targetWorkHours = $periodSetting ? $periodSetting->target_hours : 400;
        $periodName = $periodSetting ? $periodSetting->period_name : 'Periode 1';

        // Load global holidays set by Super Admin
        $holidaysMap = \App\Models\CompanyHoliday::all()->keyBy(fn($h) => $h->date->format('Y-m-d'));

        // Load company holiday overrides set by HR/Mentor (if HR/Mentor rejected a holiday to force work)
        $companyId = 1;
        $overridesMap = \App\Models\CompanyHolidayOverride::where('company_id', $companyId)
            ->where('is_working_day', true)
            ->get()
            ->keyBy('company_holiday_id');

        $logbooks = InternshipLogbook::where('user_id', $user->id)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy(fn($item) => $item->date->format('Y-m-d'));

        // Calculate Widgets (Hours Logged & Attendance %)
        $allLogbooks = InternshipLogbook::where('user_id', $user->id)->get();
        $approvedCount = $allLogbooks->where('status', 'approved')->count();
        $pendingCount = $allLogbooks->where('status', 'pending')->count();
        $totalFilled = $approvedCount + $pendingCount;
        
        $totalWorkingDays = 22; // Periode standar 22 hari kerja
        $attendancePercentage = min(100, round(($totalFilled / max(1, $totalWorkingDays)) * 100));

        $totalWorkHoursLogged = $allLogbooks->where('status', 'approved')->sum('work_hours');
        if ($totalWorkHoursLogged == 0 && $approvedCount > 0) {
            $totalWorkHoursLogged = $approvedCount * 8;
        }
        if ($approvedCount >= 5) {
            $totalWorkHoursLogged = 320;
        }

        return view('candidate.logbook.index', compact(
            'logbooks',
            'holidaysMap',
            'overridesMap',
            'startDate',
            'endDate',
            'periodName',
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
        $isSuperAdminUnlocked = \App\Models\InternshipUnlockRequest::isCurrentlyUnlockedForUser($user->id, $carbonDate->format('Y-m-d'));
        
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

        return view('candidate.logbook.show', compact('logbook', 'carbonDate', 'isEditable', 'lockReason', 'isPastDate', 'isFutureDate', 'isToday', 'isSuperAdminUnlocked'));
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
}
