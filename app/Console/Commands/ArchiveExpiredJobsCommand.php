<?php

namespace App\Console\Commands;

use App\Models\Job;
use Illuminate\Console\Command;

class ArchiveExpiredJobsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:archive-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis mengubah status lowongan yang sudah kadaluarsa (deadline terlewati) menjadi closed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredJobs = Job::where('status', 'active')
            ->whereNotNull('deadline')
            ->where('deadline', '<', now()->startOfDay())
            ->get();

        $count = 0;
        foreach ($expiredJobs as $job) {
            $job->update(['status' => 'closed']);
            $count++;
        }

        $this->info("Berhasil mengarsipkan {$count} lowongan yang sudah kadaluarsa.");
    }
}
