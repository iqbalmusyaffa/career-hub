<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class InternshipStipendsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Collection $stipends;

    public function __construct(Collection $stipends)
    {
        $this->stipends = $stipends;
    }

    public function collection()
    {
        return $this->stipends;
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA PESERTA (SESUAI KTP)',
            'EMAIL PESERTA',
            'PROGRAM / BATCH MAGANG',
            'PERIODE BULAN',
            'NAMA BANK',
            'NOMOR REKENING',
            'ATAS NAMA REKENING',
            'STATUS KTP MATCH',
            'HARI HADIR',
            'HARI IZIN/SAKIT',
            'UANG SAKU DASAR (RP)',
            'POTONGAN KEHADIRAN (RP)',
            'NOMINAL BERSIH DITRANSFER (RP)',
            'STATUS PENCAIRAN',
            'DIAJUKAN OLEH MENTOR',
            'WAKTU PENGAJUAN MENTOR',
            'CATATAN REKOMENDASI MENTOR',
            'TANGGAL TRANSFER HR',
            'CATATAN HR',
        ];
    }

    public function map($item): array
    {
        static $row = 0;
        $row++;

        return [
            $row,
            $item->user?->name ?? $item->bank_account_holder,
            $item->user?->email ?? '-',
            $item->batch_name ?? '-',
            $item->period_label,
            $item->bank_name ?? '-',
            "'" . ($item->bank_account_number ?? '-'),
            $item->bank_account_holder ?? '-',
            $item->is_ktp_matched ? 'SESUAI KTP' : 'NAMA BERBEDA',
            $item->present_days . ' Hari',
            $item->excused_days . ' Hari',
            (float) $item->base_nominal,
            (float) $item->deduction_amount,
            (float) $item->net_amount,
            strtoupper($item->status),
            $item->mentor?->name ?? ($item->mentor_submitted_at ? 'Mentor' : 'BELUM DIAJUKAN'),
            $item->mentor_submitted_at ? $item->mentor_submitted_at->format('d/m/Y H:i') : '-',
            $item->mentor_notes ?? '-',
            $item->transferred_at ? $item->transferred_at->format('d/m/Y H:i') : '-',
            $item->notes ?? '-',
        ];
    }
}
