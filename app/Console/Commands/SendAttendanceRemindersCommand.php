<?php

namespace App\Console\Commands;

use App\Services\AttendanceReminderService;
use Illuminate\Console\Command;

class SendAttendanceRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periksa dan kirim pengingat presensi dan logbook otomatis ke notifikasi peserta magang dan mentor';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan pengingat presensi dan logbook magang...');
        $result = AttendanceReminderService::sendRemindersToAll();
        $this->info('Selesai. Total pengguna diproses: ' . $result['processed_users']);
        return Command::SUCCESS;
    }
}
