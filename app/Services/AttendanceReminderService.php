<?php

namespace App\Services;

use App\Models\CompanyHoliday;
use App\Models\CompanyHolidayOverride;
use App\Models\InternshipLogbook;
use App\Models\InternshipPeriod;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;

class AttendanceReminderService
{
    /**
     * Check and generate attendance/logbook reminder notifications for a specific user.
     */
    public static function checkAndGenerateForUser($userId): void
    {
        $user = User::with(['roles', 'internshipPeriod'])->find($userId);
        if (!$user) {
            return;
        }

        $now = Carbon::now();
        $today = Carbon::today();

        // 1. Check if user is strictly a Mentor
        if ($user->hasRole('Mentor')) {
            self::checkMentorPendingLogbooks($user, $today);
        }

        // 2. Check if user is an Intern Candidate
        if ($user->hasRole('Candidate')) {
            self::checkCandidateAttendanceAndLogbook($user, $now, $today);
        }
    }

    /**
     * Check if today is an active working day for the company/candidate.
     */
    public static function isWorkingDay(Carbon $date, $companyId = 1): bool
    {
        // 1. Weekend check (Saturday / Sunday)
        if ($date->isWeekend()) {
            return false;
        }

        // 2. Holiday check
        $holiday = CompanyHoliday::whereDate('date', $date->format('Y-m-d'))
            ->where(function ($q) use ($companyId) {
                $q->whereNull('company_id')->orWhere('company_id', $companyId);
            })
            ->first();

        if ($holiday) {
            // Check if overridden as working day
            $override = CompanyHolidayOverride::where('company_id', $companyId)
                ->where('company_holiday_id', $holiday->id)
                ->where('is_working_day', true)
                ->first();

            return (bool) $override;
        }

        return true;
    }

    /**
     * Calculate total active working days in a specific month for a company (excluding weekends & holidays).
     */
    public static function countWorkingDaysInMonth(string $periodMonth, $companyId = 1): int
    {
        $monthDate = Carbon::parse($periodMonth . '-01');
        $startOfMonth = $monthDate->copy()->startOfMonth();
        $endOfMonth = $monthDate->copy()->endOfMonth();

        $workingDays = 0;
        $cursor = $startOfMonth->copy();
        while ($cursor->lte($endOfMonth)) {
            if (self::isWorkingDay($cursor, $companyId ?: 1)) {
                $workingDays++;
            }
            $cursor->addDay();
        }

        return $workingDays > 0 ? $workingDays : 22;
    }

    /**
     * Compute comprehensive attendance metrics for an intern in a given month.
     */
    public static function calculateMonthlyAttendanceMetrics(int $userId, string $periodMonth, ?int $companyId = null, int $baseNominal = 2800000): array
    {
        $user = User::with('internshipPeriod')->find($userId);
        $companyId = $companyId ?: 1;

        $monthDate = Carbon::parse($periodMonth . '-01');
        $startOfMonth = $monthDate->copy()->startOfMonth();
        $endOfMonth = $monthDate->copy()->endOfMonth();
        $periodLabel = $monthDate->translatedFormat('F Y');
        $today = Carbon::today();

        // Internship Period bounds
        $periodSetting = $user?->internshipPeriod;
        $periodStart = $periodSetting?->start_date ? $periodSetting->start_date->copy()->startOfDay() : Carbon::create(2026, 8, 10);
        $periodEnd = $periodSetting?->end_date ? $periodSetting->end_date->copy()->startOfDay() : Carbon::create(2027, 2, 9);

        $totalWorkingDays = self::countWorkingDaysInMonth($periodMonth, $companyId);

        // Fetch all logbooks for this user in this month
        $logbooks = InternshipLogbook::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->get()
            ->keyBy(fn($l) => $l->date->format('Y-m-d'));

        $presentDays = 0;
        $excusedDays = 0;
        $unexcusedDays = 0;
        $pendingDays = 0;
        $revisionDays = 0;

        // Iterate through all calendar days in this month
        $cursor = $startOfMonth->copy();
        while ($cursor->lte($endOfMonth)) {
            $dStr = $cursor->format('Y-m-d');
            $isWorking = self::isWorkingDay($cursor, $companyId);
            $isWithinPeriod = $cursor->gte($periodStart) && $cursor->lte($periodEnd);

            if ($isWorking && $isWithinPeriod) {
                if (isset($logbooks[$dStr])) {
                    $log = $logbooks[$dStr];
                    if ($log->status === 'approved') {
                        if (in_array($log->attendance_type, ['Hadir', 'present', 'wfo', 'wfh', 'Hadir Tepat Waktu', 'Terlambat'])) {
                            $presentDays++;
                        } elseif (in_array($log->attendance_type, ['Tidak Hadir Dengan Keterangan', 'Izin', 'Sakit'])) {
                            $excusedDays++;
                        } elseif ($log->attendance_type === 'Tidak Hadir Tanpa Keterangan') {
                            $unexcusedDays++;
                        }
                    } elseif ($log->status === 'pending') {
                        $pendingDays++;
                    } elseif (in_array($log->status, ['action_required', 'rejected'])) {
                        $revisionDays++;
                    }
                } else {
                    // No logbook submitted for this working day
                    if ($cursor->lt($today)) {
                        // Past working day without logbook is Unexcused (Alpa / Tidak Hadir)
                        $unexcusedDays++;
                    }
                }
            }

            $cursor->addDay();
        }

        // Deduction Calculation
        $isFutureMonth = $startOfMonth->gt($today);
        $excessExcused = max(0, $excusedDays - 4);
        $cutDays = $excessExcused + $unexcusedDays;
        $dailyRate = $totalWorkingDays > 0 ? ($baseNominal / $totalWorkingDays) : ($baseNominal / 22);

        if ($isFutureMonth) {
            $deductionAmount = 0;
            $netAmount = 0; // Future period has not occurred yet
        } else {
            $deductionAmount = $cutDays * $dailyRate;
            $netAmount = max(0, $baseNominal - $deductionAmount);
        }

        return [
            'period_month' => $periodMonth,
            'period_label' => $periodLabel,
            'total_working_days' => $totalWorkingDays,
            'present_days' => $presentDays,
            'excused_days' => $excusedDays,
            'unexcused_days' => $unexcusedDays,
            'pending_days' => $pendingDays,
            'revision_days' => $revisionDays,
            'is_future' => $isFutureMonth,
            'base_nominal' => $baseNominal,
            'daily_rate' => $dailyRate,
            'deduction_amount' => $deductionAmount,
            'net_amount' => $netAmount,
        ];
    }

    /**
     * Candidate attendance & logbook notifications check.
     */
    private static function checkCandidateAttendanceAndLogbook(User $user, Carbon $now, Carbon $today): void
    {
        // Check if candidate is in an active internship period
        $periodSetting = $user->internshipPeriod;
        $activeApp = $user->applications()
            ->whereHas('job', fn($q) => $q->whereIn('work_type', ['internship', 'magang']))
            ->whereIn('status', ['accepted', 'hired'])
            ->first();

        if (!$periodSetting && !$activeApp) {
            return;
        }

        $startDate = $periodSetting && $periodSetting->start_date ? $periodSetting->start_date->copy()->startOfDay() : Carbon::create(2026, 8, 10);
        $endDate = $periodSetting && $periodSetting->end_date ? $periodSetting->end_date->copy()->endOfDay() : Carbon::create(2027, 2, 9);

        // Outside internship period -> no reminder
        if ($today->lt($startDate) || $today->gt($endDate)) {
            return;
        }

        // Check company ID
        $companyId = $activeApp?->job?->company_profile_id ?? 1;

        // If today is not a working day (weekend / holiday) -> no reminder
        if (!self::isWorkingDay($today, $companyId)) {
            return;
        }

        $todayLogbook = InternshipLogbook::where('user_id', $user->id)
            ->whereDate('date', $today->format('Y-m-d'))
            ->first();

        // -------------------------------------------------------------
        // REMINDER A: Morning Attendance Check-In (After 07:00 WIB)
        // -------------------------------------------------------------
        if ($now->hour >= 7) {
            if (!$todayLogbook) {
                $hasMorningNotifToday = UserNotification::where('user_id', $user->id)
                    ->where('title', 'like', '%Presensi Pagi%')
                    ->whereDate('created_at', $today->format('Y-m-d'))
                    ->exists();

                if (!$hasMorningNotifToday) {
                    UserNotification::send(
                        $user->id,
                        '⏰ Pengingat Presensi Pagi: Anda Belum Mengisi Kehadiran',
                        'Selamat pagi ' . $user->name . '! Hari ini (' . $today->translatedFormat('l, d F Y') . ') adalah hari kerja aktif. Silakan isi presensi kehadiran Anda.',
                        route('candidate.logbook.show', $today->format('Y-m-d')),
                        'warning'
                    );
                }
            }
        }

        // -------------------------------------------------------------
        // REMINDER B: Afternoon / Evening Logbook Activities (After 15:30 WIB)
        // -------------------------------------------------------------
        if ($now->hour >= 15) {
            $isLogbookIncomplete = !$todayLogbook 
                || empty($todayLogbook->tasks) 
                || (float) $todayLogbook->work_hours <= 0 
                || strlen(trim($todayLogbook->tasks ?? '')) < 10;

            if ($isLogbookIncomplete) {
                $hasEveningNotifToday = UserNotification::where('user_id', $user->id)
                    ->where('title', 'like', '%Logbook Sore%')
                    ->whereDate('created_at', $today->format('Y-m-d'))
                    ->exists();

                if (!$hasEveningNotifToday) {
                    UserNotification::send(
                        $user->id,
                        '📝 Pengingat Logbook Sore: Lengkapi Catatan Aktivitas Harian',
                        'Waktu kerja hari ini hampir berakhir. Jangan lupa melengkapi ringkasan tugas & jam kerja pada logbook harian agar dapat diverifikasi oleh Mentor.',
                        route('candidate.logbook.show', $today->format('Y-m-d')),
                        'info'
                    );
                }
            }
        }
    }

    /**
     * Mentor reminder for pending logbooks.
     */
    private static function checkMentorPendingLogbooks(User $user, Carbon $today): void
    {
        $pendingCount = InternshipLogbook::where('status', 'pending')->count();
        if ($pendingCount <= 0) {
            return;
        }

        $hasMentorNotifToday = UserNotification::where('user_id', $user->id)
            ->where('title', 'like', '%Logbook Menunggu Verifikasi%')
            ->whereDate('created_at', $today->format('Y-m-d'))
            ->exists();

        if (!$hasMentorNotifToday) {
            UserNotification::send(
                $user->id,
                '📋 Pengingat Mentor: ' . $pendingCount . ' Logbook Menunggu Verifikasi',
                'Halo ' . $user->name . ', terdapat ' . $pendingCount . ' laporan logbook harian peserta magang yang menunggu persetujuan (ACC) Anda.',
                route('mentor.logbooks.index'),
                'info'
            );
        }
    }

    /**
     * Send reminders to all active interns and mentors (useful for scheduled cron/artisan command).
     */
    public static function sendRemindersToAll(): array
    {
        $users = User::role(['Candidate', 'Mentor', 'HR'])->get();
        $count = 0;

        foreach ($users as $u) {
            self::checkAndGenerateForUser($u->id);
            $count++;
        }

        return ['processed_users' => $count];
    }
}
