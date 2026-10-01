<?php

namespace App\Console\Commands;

use App\Services\AutoApproveLogbookService;
use Illuminate\Console\Command;

class AutoApprovePendingLogbooksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logbook:auto-approve {--days=14 : Jumlah hari pending sebelum otomatis disetujui (default: 14 hari)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setujui otomatis (Auto-ACC) logbook presensi magang berstatus pending yang telah melewati 14 hari tanpa respon mentor';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        if ($days < 1) {
            $days = 14;
        }

        $this->info("Memulai proses Auto-ACC logbook magang (Batas: {$days} hari)...");

        $result = AutoApproveLogbookService::process($days);

        $this->info("Proses selesai. Berhasil menyetujui otomatis {$result['approved_count']} logbook pending (Cutoff: {$result['cutoff_date']}).");

        return Command::SUCCESS;
    }
}
