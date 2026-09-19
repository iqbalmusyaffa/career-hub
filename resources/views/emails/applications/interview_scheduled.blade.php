<x-mail::message>
# Undangan Wawancara Resmi

Halo **{{ $application->user->name ?? 'Kandidat' }}**,

Selamat! Tim Rekrutmen **{{ $application->job->company_name ?? 'Perusahaan' }}** mengundang Anda untuk mengikuti sesi wawancara untuk posisi **{{ $application->job->title ?? 'Posisi Pekerjaan' }}**.

### 🗓️ Detail Jadwal Wawancara:
* **Tanggal & Waktu**: {{ isset($interview->scheduled_at) ? \Carbon\Carbon::parse($interview->scheduled_at)->format('d F Y, H:i WIB') : (isset($application->interview_date) ? \Carbon\Carbon::parse($application->interview_date)->format('d F Y, H:i WIB') : 'Akan Diinformasikan') }}
* **Tipe Wawancara**: {{ ucfirst($interview->type ?? $application->interview_type ?? 'Online') }}
* **Tautan / Lokasi**: {{ $interview->location_or_link ?? $application->interview_location_link ?? '-' }}
* **Catatan Tim HR**: {{ $interview->notes ?? $application->interview_notes ?? 'Mohon hadir tepat waktu.' }}

<x-mail::button :url="route('dashboard')">
Konfirmasi & Lihat Jadwal Wawancara
</x-mail::button>

Terima kasih,<br>
Tim Rekrutmen {{ $application->job->company_name ?? config('app.name', 'KarirHub') }}
</x-mail::message>
