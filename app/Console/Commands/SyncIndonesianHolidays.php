<?php

namespace App\Console\Commands;

use App\Services\IndonesianHolidayService;
use Illuminate\Console\Command;

class SyncIndonesianHolidays extends Command
{
    protected $signature = 'holidays:sync {--year= : Tahun yang ingin disinkronkan}';
    protected $description = 'Sinkronkan Hari Libur Nasional & Cuti Bersama Pemerintah RI dari API';

    public function handle(IndonesianHolidayService $service): int
    {
        $year = $this->option('year') ? (int) $this->option('year') : (int) date('Y');
        $this->info("Menyinkronkan Hari Libur & Cuti Bersama Pemerintah untuk tahun {$year}...");

        $count = $service->syncHolidays($year);

        $this->info("Berhasil menyinkronkan {$count} Hari Libur Nasional & Cuti Bersama Pemerintah.");
        return Command::SUCCESS;
    }
}