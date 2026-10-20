<?php

namespace App\Services;

use App\Mail\AttendanceReminderMail;
use App\Models\CompanyHoliday;
use App\Models\CompanyHolidayOverride;
use App\Models\InternshipLogbook;
use App\Models\InternshipPeriod;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AttendanceReminderService
{
    /**
     * Check and generate attendance/logbook reminder notifications for a specific user.
     */
    public static function checkAndGenerateForUser($userId, bool $sendEmail = false, bool $force = false): array
    {
        // 1. Check Global Master Switch
        $masterEnabled = SystemSetting::getByKey('attendance_reminder_enabled', '1') === '1';
        if (!$masterEnabled) {
            return ['status' => 'disabled_globally', 'emails_sent' => 0];
        }

        $user = User::with(['roles', 'internshipPeriod'])->find($userId);
        if (!$user) {
            return ['status' => 'user_not_found', 'emails_sent' => 0];
        }

        $now = Carbon::now();
        $today = Carbon::today();
        $emailsSent = 0;

        // 1. Check if user is strictly a Mentor
        if ($user->hasRole('Mentor')) {
            $emailsSent += self::checkMentorPendingLogbooks($user, $today, $sendEmail, $force);
        }

        // 2. Check if user is an Intern Candidate
        if ($user->hasRole('Candidate')) {
            $emailsSent += self::checkCandidateAttendanceAndLogbook($user, $now, $today, $sendEmail, $force);
        }

        return ['status' => 'success', 'emails_sent' => $emailsSent];
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
                    if (in_array($log->status, ['approved', 'pending'])) {
                        if (in_array($log->attendance_type, ['Hadir', 'present', 'wfo', 'wfh', 'Hadir Tepat Waktu', 'Terlambat'])) {
                            $presentDays++;
                        } elseif (in_array($log->attendance_type, ['Tidak Hadir Dengan Keterangan', 'Izin', 'Sakit'])) {
                            $excusedDays++;
                        } elseif ($log->attendance_type === 'Tidak Hadir Tanpa Keterangan') {
                            $unexcusedDays++;
                        }
                    } elseif (in_array($log->status, ['action_required', 'rejected'])) {
                        $revisionDays++;
                        if ($cursor->lt($today)) {
                            // Rejected or unrevised past date counts as unexcused until resolved
                            $unexcusedDays++;
                        }
                    }

                    if ($log->status === 'pending') {
                        $pendingDays++;
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

        // Deduction Calculation (Toleransi Izin Bebas Potong 5 Hari per Periode)
        $isFutureMonth = $startOfMonth->gt($today);
        $excessExcused = max(0, $excusedDays - 5);
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
    private static function checkCandidateAttendanceAndLogbook(User $user, Carbon $now, Carbon $today, bool $sendEmail = false, bool $force = false): int
    {
        // Check if candidate is in an active internship period
        $periodSetting = $user->internshipPeriod;
        $activeApp = $user->applications()
            ->whereHas('job', fn($q) => $q->whereIn('work_type', ['internship', 'magang']))
            ->whereIn('status', ['accepted', 'hired'])
            ->first();

        if (!$periodSetting && !$activeApp) {
            return 0;
        }

        $startDate = $periodSetting && $periodSetting->start_date ? $periodSetting->start_date->copy()->startOfDay() : Carbon::create(2026, 8, 10);
        $endDate = $periodSetting && $periodSetting->end_date ? $periodSetting->end_date->copy()->endOfDay() : Carbon::create(2027, 2, 9);

        // Outside internship period -> no reminder
        if ($today->lt($startDate) || $today->gt($endDate)) {
            return 0;
        }

        // Check company ID
        $companyId = $activeApp?->job?->company_profile_id ?? 1;

        // If today is not a working day (weekend / holiday) -> no reminder
        if (!self::isWorkingDay($today, $companyId)) {
            return 0;
        }

        $todayLogbook = InternshipLogbook::where('user_id', $user->id)
            ->whereDate('date', $today->format('Y-m-d'))
            ->first();

        // Global Switches
        $inAppGlobal = SystemSetting::getByKey('attendance_reminder_inapp_enabled', '1') === '1';
        $emailGlobal = SystemSetting::getByKey('attendance_reminder_email_enabled', '1') === '1';
        $morningGlobal = SystemSetting::getByKey('attendance_reminder_morning_enabled', '1') === '1';
        $afternoonGlobal = SystemSetting::getByKey('attendance_reminder_afternoon_enabled', '1') === '1';

        // Personal User Preferences
        $userInAppAllowed = (bool) ($user->inapp_attendance_reminder_enabled ?? true);
        $userEmailAllowed = (bool) ($user->email_attendance_reminder_enabled ?? true);

        $canSendInApp = $inAppGlobal && $userInAppAllowed;
        $canSendEmail = $sendEmail && $emailGlobal && $userEmailAllowed && !empty($user->email);

        $emailsSent = 0;

        // -------------------------------------------------------------
        // REMINDER A: Morning Attendance Check-In (After 07:00 WIB)
        // -------------------------------------------------------------
        if ($morningGlobal && $now->hour >= 7) {
            if (!$todayLogbook) {
                $hasMorningNotifToday = UserNotification::where('user_id', $user->id)
                    ->where('title', 'like', '%Presensi Pagi%')
                    ->whereDate('created_at', $today->format('Y-m-d'))
                    ->exists();

                $notifTitle = '⏰ Pengingat Presensi Pagi: Anda Belum Mengisi Kehadiran';
                $notifMsg = 'Selamat pagi ' . $user->name . '! Hari ini (' . $today->translatedFormat('l, d F Y') . ') adalah hari kerja aktif. Silakan isi presensi kehadiran Anda.';
                $targetUrl = route('candidate.logbook.show', $today->format('Y-m-d'));

                if ($canSendInApp && !$hasMorningNotifToday) {
                    UserNotification::send(
                        $user->id,
                        $notifTitle,
                        $notifMsg,
                        $targetUrl,
                        'warning'
                    );
                }

                // Send email reminder if allowed
                if ($canSendEmail) {
                    $cacheKey = "attendance_email_morning_{$user->id}_{$today->format('Y-m-d')}";
                    if ($force || !Cache::has($cacheKey)) {
                        try {
                            Mail::to($user->email)->send(
                                new AttendanceReminderMail(
                                    $user,
                                    'morning_checkin',
                                    $notifTitle,
                                    $notifMsg,
                                    $targetUrl,
                                    'Isi Presensi Pagi Sekarang',
                                    $today->translatedFormat('l, d F Y')
                                )
                            );
                            Cache::put($cacheKey, true, now()->endOfDay());
                            $emailsSent++;
                        } catch (\Throwable $e) {
                            Log::warning("Failed sending morning attendance email to {$user->email}: " . $e->getMessage());
                        }
                    }
                }
            }
        }

        // -------------------------------------------------------------
        // REMINDER B: Afternoon / Evening Logbook Activities (16:00 & 17:00 WIB)
        // -------------------------------------------------------------
        if ($afternoonGlobal && $now->hour >= 16) {
            $isLogbookIncomplete = !$todayLogbook 
                || empty($todayLogbook->tasks) 
                || (float) $todayLogbook->work_hours <= 0 
                || strlen(trim($todayLogbook->tasks ?? '')) < 10;

            if ($isLogbookIncomplete) {
                $isPost17 = $now->hour >= 17;
                $slotKeyword = $isPost17 ? '17.00 WIB' : '16.00 WIB';
                $slotKey = $isPost17 ? '17' : '16';
                $notifTitle = $isPost17
                    ? '📝 Pengingat Logbook (17.00 WIB): Waktu Pulang Kerja, Lengkapi Logbook Hari Ini'
                    : '📝 Pengingat Logbook Sore (16.00 WIB): Menjelang Pulang, Lengkapi Aktivitas Harian';
                $notifMessage = $isPost17
                    ? 'Waktu kerja hari ini telah berakhir (17.00 WIB). Pastikan Anda telah melengkapi presensi dan catatan aktivitas logbook harian agar dapat diverifikasi oleh Mentor.'
                    : 'Waktu kerja hari ini hampir berakhir (16.00 WIB). Jangan lupa melengkapi ringkasan tugas & jam kerja pada logbook harian sebelum pulang.';
                $targetUrl = route('candidate.logbook.show', $today->format('Y-m-d'));

                $hasNotifForSlot = UserNotification::where('user_id', $user->id)
                    ->where('title', 'like', '%' . $slotKeyword . '%')
                    ->whereDate('created_at', $today->format('Y-m-d'))
                    ->exists();

                if ($canSendInApp && !$hasNotifForSlot) {
                    UserNotification::send(
                        $user->id,
                        $notifTitle,
                        $notifMessage,
                        $targetUrl,
                        'info'
                    );
                }

                // Send email reminder if allowed
                if ($canSendEmail) {
                    $cacheKey = "attendance_email_logbook_{$slotKey}_{$user->id}_{$today->format('Y-m-d')}";
                    if ($force || !Cache::has($cacheKey)) {
                        try {
                            Mail::to($user->email)->send(
                                new AttendanceReminderMail(
                                    $user,
                                    'afternoon_logbook',
                                    $notifTitle,
                                    $notifMessage,
                                    $targetUrl,
                                    'Lengkapi Logbook Sekarang',
                                    $today->translatedFormat('l, d F Y')
                                )
                            );
                            Cache::put($cacheKey, true, now()->endOfDay());
                            $emailsSent++;
                        } catch (\Throwable $e) {
                            Log::warning("Failed sending logbook email to {$user->email}: " . $e->getMessage());
                        }
                    }
                }
            }
        }

        return $emailsSent;
    }

    /**
     * Mentor reminder for pending logbooks.
     */
    private static function checkMentorPendingLogbooks(User $user, Carbon $today, bool $sendEmail = false, bool $force = false): int
    {
        $mentorGlobal = SystemSetting::getByKey('attendance_reminder_mentor_enabled', '1') === '1';
        if (!$mentorGlobal) {
            return 0;
        }

        $pendingCount = InternshipLogbook::where('status', 'pending')->count();
        if ($pendingCount <= 0) {
            return 0;
        }

        $inAppGlobal = SystemSetting::getByKey('attendance_reminder_inapp_enabled', '1') === '1';
        $emailGlobal = SystemSetting::getByKey('attendance_reminder_email_enabled', '1') === '1';

        $userInAppAllowed = (bool) ($user->inapp_attendance_reminder_enabled ?? true);
        $userEmailAllowed = (bool) ($user->email_attendance_reminder_enabled ?? true);

        $canSendInApp = $inAppGlobal && $userInAppAllowed;
        $canSendEmail = $sendEmail && $emailGlobal && $userEmailAllowed && !empty($user->email);

        $emailsSent = 0;
        $notifTitle = '📋 Pengingat Mentor: ' . $pendingCount . ' Logbook Menunggu Verifikasi';
        $notifMessage = 'Halo ' . $user->name . ', terdapat ' . $pendingCount . ' laporan logbook harian peserta magang yang menunggu persetujuan (ACC) Anda.';
        $targetUrl = route('mentor.logbooks.index');

        $hasMentorNotifToday = UserNotification::where('user_id', $user->id)
            ->where('title', 'like', '%Logbook Menunggu Verifikasi%')
            ->whereDate('created_at', $today->format('Y-m-d'))
            ->exists();

        if ($canSendInApp && !$hasMentorNotifToday) {
            UserNotification::send(
                $user->id,
                $notifTitle,
                $notifMessage,
                $targetUrl,
                'info'
            );
        }

        // Send email reminder to mentor if allowed
        if ($canSendEmail) {
            $cacheKey = "attendance_email_mentor_{$user->id}_{$today->format('Y-m-d')}";
            if ($force || !Cache::has($cacheKey)) {
                try {
                    Mail::to($user->email)->send(
                        new AttendanceReminderMail(
                            $user,
                            'mentor_pending',
                            $notifTitle,
                            $notifMessage,
                            $targetUrl,
                            'Verifikasi Logbook Magang',
                            $today->translatedFormat('l, d F Y'),
                            ['pending_count' => $pendingCount]
                        )
                    );
                    Cache::put($cacheKey, true, now()->endOfDay());
                    $emailsSent++;
                } catch (\Throwable $e) {
                    Log::warning("Failed sending mentor attendance email to {$user->email}: " . $e->getMessage());
                }
            }
        }

        return $emailsSent;
    }

    /**
     * Send reminders to all active interns and mentors (useful for scheduled cron/artisan command).
     */
    public static function sendRemindersToAll(bool $sendEmail = true, bool $force = false, ?int $targetUserId = null): array
    {
        if ($targetUserId) {
            $users = User::where('id', $targetUserId)->get();
        } else {
            $users = User::role(['Candidate', 'Mentor', 'HR'])->get();
        }

        $processedCount = 0;
        $totalEmailsSent = 0;

        foreach ($users as $u) {
            $res = self::checkAndGenerateForUser($u->id, $sendEmail, $force);
            $processedCount++;
            $totalEmailsSent += ($res['emails_sent'] ?? 0);
        }

        return [
            'processed_users' => $processedCount,
            'emails_sent' => $totalEmailsSent,
        ];
    }
}
