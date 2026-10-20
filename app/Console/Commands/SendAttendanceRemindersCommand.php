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
    protected $signature = 'attendance:send-reminders 
                            {--no-email : Hanya kirim notifikasi lonceng di website (tanpa email)}
                            {--force : Paksa kirim ulang email meskipun sudah pernah terkirim hari ini}
                            {--user= : Jalankan pengecekan khusus untuk ID user tertentu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periksa dan kirim pengingat presensi & logbook otomatis ke notifikasi lonceng dan email peserta magang serta mentor';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sendEmail = !$this->option('no-email');
        $force = (bool) $this->option('force');
        $targetUserId = $this->option('user') ? (int) $this->option('user') : null;

        $this->info('🚀 Memulai pengecekan pengingat presensi dan logbook magang...');
        if ($targetUserId) {
            $this->line("Target Pengguna: ID #{$targetUserId}");
        }
        $this->line('Kirim Email: ' . ($sendEmail ? 'Aktif (Ya)' : 'Nonaktif'));
        $this->line('Mode Force: ' . ($force ? 'Ya (Abaikan limit harian)' : 'Tidak'));

        $result = AttendanceReminderService::sendRemindersToAll($sendEmail, $force, $targetUserId);

        $this->newLine();
        $this->info('✅ Selesai.');
        $this->table(
            ['Metrik', 'Jumlah'],
            [
                ['Total Pengguna Diproses', $result['processed_users']],
                ['Total Email Pengingat Terkirim', $result['emails_sent']],
            ]
        );

        return Command::SUCCESS;
    }
}
