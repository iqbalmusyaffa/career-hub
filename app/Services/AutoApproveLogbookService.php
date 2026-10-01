<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\InternshipLogbook;
use App\Models\UserNotification;
use Carbon\Carbon;

class AutoApproveLogbookService
{
    /**
     * Run auto-approval on pending logbooks that have exceeded the cutoff days (default: 14 days / 2 weeks).
     *
     * @param int $days
     * @param int|null $userId Optional: only for a specific intern
     * @return array
     */
    public static function process(int $days = 14, ?int $userId = null): array
    {
        $cutoffDate = Carbon::today()->subDays($days);

        $query = InternshipLogbook::with(['intern', 'mentor', 'application'])
            ->where('status', 'pending')
            ->where(function ($q) use ($cutoffDate) {
                $q->whereDate('date', '<=', $cutoffDate)
                  ->orWhereDate('created_at', '<=', $cutoffDate);
            });

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $pendingLogbooks = $query->get();
        $approvedCount = 0;

        foreach ($pendingLogbooks as $logbook) {
            $logbookDateStr = $logbook->date->translatedFormat('d F Y');
            $attLabel = $logbook->attendance_type_label;

            $logbook->update([
                'status' => 'approved',
                'approved_at' => now(),
                'mentor_notes' => $logbook->mentor_notes 
                    ? $logbook->mentor_notes . " [Auto-ACC Sistem: 14 hari tanpa respon mentor]" 
                    : "Disetujui otomatis oleh sistem (Auto-ACC setelah {$days} hari tanpa respon mentor).",
            ]);

            // Record system audit log
            AuditLog::record(
                'AUTO_ACC_LOGBOOK_14_DAYS',
                "Sistem menyetujui otomatis (Auto-ACC 14 Hari) logbook presensi tanggal {$logbookDateStr} ({$attLabel}) milik {$logbook->intern?->name}."
            );

            // Notify the intern
            if ($logbook->user_id) {
                UserNotification::send(
                    $logbook->user_id,
                    '⚡ Presensi Disetujui Otomatis (Auto-ACC)',
                    "Presensi/Laporan harian Anda untuk tanggal {$logbookDateStr} ({$attLabel}) telah disetujui otomatis oleh sistem karena melewati batas 14 hari tanpa tinjauan mentor.",
                    route('candidate.logbook.show', ['date' => $logbook->date->format('Y-m-d')]),
                    'success'
                );
            }

            $approvedCount++;
        }

        return [
            'approved_count' => $approvedCount,
            'cutoff_date' => $cutoffDate->format('Y-m-d'),
        ];
    }
}
