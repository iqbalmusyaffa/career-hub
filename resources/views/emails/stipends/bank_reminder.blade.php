<x-mail::message>
# Halo {{ $stipend->user?->name }},

Ini adalah pengingat resmi dari **{{ $senderName }}** ({{ $senderRole }}) mengenai proses pencairan uang saku magang Anda.

Data presensi dan uang saku Anda untuk periode **{{ $stipend->period_label }}** saat ini sedang dipersiapkan untuk proses transfer payroll dengan rincian berikut:

<x-mail::panel>
**Periode:** {{ $stipend->period_label }}  
**Kehadiran:** {{ $stipend->present_days }} / 22 Hari Kerja  
**Estimasi Uang Saku Bersih:** Rp {{ number_format($stipend->net_amount, 0, ',', '.') }}  
**Status Rekening:** ⚠️ Belum Mengisi Rekening Bank
</x-mail::panel>

Saat ini Anda **belum mengisi nomor rekening bank** di platform. Agar proses pencairan dan transfer uang saku tidak tertunda, mohon segera melengkapi informasi rekening bank serta mengunggah foto buku tabungan Anda.

<x-mail::button :url="route('candidate.logbook.progress')">
Lengkapi Rekening Bank Sekarang
</x-mail::button>

> **Catatan Penting:**  
> Pastikan nama pemilik rekening sesuai dengan nama pada KTP Anda. Apabila menggunakan rekening atas nama orang lain, pastikan Anda melampirkan foto buku tabungan atau surat kuasa agar verifikasi Tim HR dapat disetujui.

Terima kasih atas kerja sama dan dedikasi Anda selama masa magang!

Salam hangat,  
**{{ $senderName }}**  
Tim Payroll & Bimbingan Magang
</x-mail::message>
