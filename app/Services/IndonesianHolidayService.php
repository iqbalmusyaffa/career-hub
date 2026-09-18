<?php

namespace App\Services;

use App\Models\CompanyHoliday;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndonesianHolidayService
{
    /**
     * Official fallback dataset for Indonesian National Holidays & Cuti Bersama (SKB 3 Menteri)
     */
    protected static array $fallbackHolidays = [
        ['date' => '2026-01-01', 'name' => 'Tahun Baru 2026 Masehi', 'type' => 'national_holiday'],
        ['date' => '2026-01-16', 'name' => 'Isra Mi\'raj Nabi Muhammad SAW', 'type' => 'national_holiday'],
        ['date' => '2026-02-17', 'name' => 'Tahun Baru Imlek 2577 Kongzili', 'type' => 'national_holiday'],
        ['date' => '2026-03-18', 'name' => 'Cuti Bersama Hari Suci Nyepi Tahun Baru Saka 1948', 'type' => 'cuti_bersama'],
        ['date' => '2026-03-19', 'name' => 'Hari Suci Nyepi Tahun Baru Saka 1948', 'type' => 'national_holiday'],
        ['date' => '2026-03-20', 'name' => 'Cuti Bersama Hari Raya Idul Fitri 1447 H', 'type' => 'cuti_bersama'],
        ['date' => '2026-03-21', 'name' => 'Hari Raya Idul Fitri 1447 Hijriyah', 'type' => 'national_holiday'],
        ['date' => '2026-03-22', 'name' => 'Hari Raya Idul Fitri 1447 Hijriyah', 'type' => 'national_holiday'],
        ['date' => '2026-03-23', 'name' => 'Cuti Bersama Hari Raya Idul Fitri 1447 H', 'type' => 'cuti_bersama'],
        ['date' => '2026-03-24', 'name' => 'Cuti Bersama Hari Raya Idul Fitri 1447 H', 'type' => 'cuti_bersama'],
        ['date' => '2026-04-03', 'name' => 'Wafat Yesus Kristus (Jumat Agung)', 'type' => 'national_holiday'],
        ['date' => '2026-04-05', 'name' => 'Hari Paskah', 'type' => 'national_holiday'],
        ['date' => '2026-05-01', 'name' => 'Hari Buruh Internasional', 'type' => 'national_holiday'],
        ['date' => '2026-05-14', 'name' => 'Kenaikan Yesus Kristus', 'type' => 'national_holiday'],
        ['date' => '2026-05-15', 'name' => 'Cuti Bersama Kenaikan Yesus Kristus', 'type' => 'cuti_bersama'],
        ['date' => '2026-05-27', 'name' => 'Hari Raya Idul Adha 1447 Hijriyah', 'type' => 'national_holiday'],
        ['date' => '2026-05-28', 'name' => 'Cuti Bersama Hari Raya Idul Adha 1447 H', 'type' => 'cuti_bersama'],
        ['date' => '2026-05-31', 'name' => 'Hari Raya Waisak 2570 BE', 'type' => 'national_holiday'],
        ['date' => '2026-06-01', 'name' => 'Hari Lahir Pancasila', 'type' => 'national_holiday'],
        ['date' => '2026-06-16', 'name' => 'Tahun Baru Islam 1448 Hijriyah', 'type' => 'national_holiday'],
        ['date' => '2026-08-17', 'name' => 'Hari Kemerdekaan Republik Indonesia', 'type' => 'national_holiday'],
        ['date' => '2026-08-25', 'name' => 'Maulid Nabi Muhammad SAW', 'type' => 'national_holiday'],
        ['date' => '2026-12-24', 'name' => 'Cuti Bersama Hari Raya Natal', 'type' => 'cuti_bersama'],
        ['date' => '2026-12-25', 'name' => 'Hari Raya Natal', 'type' => 'national_holiday'],
    ];

    /**
     * Sync Indonesian Government Holidays & Cuti Bersama from API with fallback
     */
    public function syncHolidays(?int $year = null): int
    {
        $year = $year ?? (int) date('Y');
        $holidays = [];

        try {
            $response = Http::timeout(8)->get('https://api-hari-libur.vercel.app/api');
            if ($response->successful()) {
                $payload = $response->json();
                $items = $payload['data'] ?? [];

                foreach ($items as $item) {
                    if (isset($item['date']) && isset($item['description'])) {
                        $desc = $item['description'];
                        $isCuti = stripos($desc, 'cuti bersama') !== false;
                        
                        $holidays[] = [
                            'date' => $item['date'],
                            'name' => $desc,
                            'type' => $isCuti ? 'cuti_bersama' : 'national_holiday',
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed fetching holidays from API: ' . $e->getMessage());
        }

        // If API did not return data for the year, use fallback
        if (empty($holidays)) {
            $holidays = self::$fallbackHolidays;
        }

        $savedCount = 0;
        foreach ($holidays as $h) {
            CompanyHoliday::updateOrCreate(
                [
                    'company_id' => null,
                    'date' => $h['date'],
                ],
                [
                    'name' => $h['name'],
                    'type' => $h['type'],
                ]
            );
            $savedCount++;
        }

        return $savedCount;
    }
}