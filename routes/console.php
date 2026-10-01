<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Pengingat Presensi Pagi Otomatis (08:00 WIB Hari Kerja)
Schedule::command('attendance:send-reminders')
    ->weekdays()
    ->at('08:00')
    ->timezone('Asia/Jakarta')
    ->description('Kirim pengingat presensi pagi hari kerja');

// Pengingat Logbook Sore Otomatis Menjelang Pulang (16:00 WIB Hari Kerja)
Schedule::command('attendance:send-reminders')
    ->weekdays()
    ->at('16:00')
    ->timezone('Asia/Jakarta')
    ->description('Kirim pengingat logbook sore menjelang pulang jam 4');

// Pengingat Logbook Sore Otomatis Waktu Pulang (17:00 WIB Hari Kerja)
Schedule::command('attendance:send-reminders')
    ->weekdays()
    ->at('17:00')
    ->timezone('Asia/Jakarta')
    ->description('Kirim pengingat logbook sore waktu pulang jam 5');

// Pengingat Batas Waktu (Deadline) Ujian Online Otomatis
Schedule::command('tests:send-deadline-reminders')
    ->hourly()
    ->timezone('Asia/Jakarta')
    ->description('Kirim pengingat otomatis batas waktu ujian bagi kandidat yang belum mengerjakan');

// Auto-ACC Logbook Presensi Magang yang Pending Lebih Dari 14 Hari (Setiap Pukul 01:00 WIB)
Schedule::command('logbook:auto-approve --days=14')
    ->dailyAt('01:00')
    ->timezone('Asia/Jakarta')
    ->description('Otomatis setujui (Auto-ACC) logbook presensi magang berstatus pending setelah 14 hari tanpa review mentor');



