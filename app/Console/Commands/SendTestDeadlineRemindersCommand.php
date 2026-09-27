<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobTest;
use App\Models\Application;
use App\Models\CandidateTestResult;
use App\Models\UserNotification;
use App\Models\AuditLog;
use App\Mail\CandidateTestReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class SendTestDeadlineRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tests:send-deadline-reminders {--hours=24 : Jam sebelum deadline untuk mengirim pengingat}';

    /**
     * The console command description.
     */
    protected $description = 'Kirim email & notifikasi pengingat batas waktu (deadline) ujian online ke kandidat yang belum mengerjakan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        $now = now();
        $targetDeadline = $now->copy()->addHours($hours);

        $this->info("Memeriksa jadwal ujian dengan deadline antara {$now->toDateTimeString()} s/d {$targetDeadline->toDateTimeString()}...");

        // Find active tests with upcoming deadline
        $tests = JobTest::with('job')
            ->where('is_active', true)
            ->whereNotNull('deadline_at')
            ->where('deadline_at', '>', $now)
            ->where('deadline_at', '<=', $targetDeadline)
            ->get();

        if ($tests->isEmpty()) {
            $this->info('Tidak ada jadwal ujian yang mendekati deadline.');
            return 0;
        }

        $sentCount = 0;

        foreach ($tests as $test) {
            $job = $test->job;
            if (!$job) continue;

            // Find applications in test status
            $applications = Application::with(['user', 'job'])
                ->where('job_id', $job->id)
                ->where('status', \App\Enums\ApplicationStatus::TEST)
                ->get();

            foreach ($applications as $app) {
                // Check if candidate already has completed test result
                $hasCompleted = CandidateTestResult::where('user_id', $app->user_id)
                    ->where('job_id', $job->id)
                    ->where('job_test_id', $test->id)
                    ->exists();

                if ($hasCompleted) {
                    continue;
                }

                // Prevent spam: rate limit reminder to once every 12 hours per application
                $cacheKey = "test_reminder_sent_{$app->id}_{$test->id}";
                if (Cache::has($cacheKey)) {
                    continue;
                }

                try {
                    // Send Email Reminder
                    if ($app->user && $app->user->email) {
                        Mail::to($app->user->email)->send(
                            new CandidateTestReminderMail($app, $test)
                        );
                    }

                    // Send In-App Notification
                    UserNotification::send(
                        $app->user_id,
                        "⏰ Pengingat Deadline Ujian: {$test->title}",
                        "Batas waktu pengerjaan ujian untuk lowongan {$job->title} akan berakhir pada {$test->deadline_at->translatedFormat('d M Y, H:i')} WIB. Mohon segera selesaikan ujian Anda.",
                        route('candidate.tests.show', $job->id),
                        'warning'
                    );

                    Cache::put($cacheKey, true, now()->addHours(12));
                    $sentCount++;

                    $this->info("Pengingat dikirim ke: {$app->user->name} ({$app->user->email}) untuk ujian {$test->title}");
                } catch (\Exception $e) {
                    $this->error("Gagal mengirim pengingat ke {$app->user->email}: " . $e->getMessage());
                }
            }
        }

        if ($sentCount > 0) {
            AuditLog::record('test_reminders_sent', "Mengirim {$sentCount} pengingat batas waktu ujian online ke kandidat.");
        }

        $this->info("Selesai. Total {$sentCount} pengingat berhasil dikirim.");
        return 0;
    }
}
