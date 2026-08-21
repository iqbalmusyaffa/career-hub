<x-mail::message>
# Undangan Wawancara Resmi

Halo **{{ $application->user->name }}**,

Selamat! Tim Rekrutmen **{{ $application->job->company_name ?: 'PT TechNova Asia Digital' }}** mengundang Anda untuk mengikuti sesi wawancara untuk posisi **{{ $application->job->title }}**.

### 🗓️ Detail Jadwal Wawancara:
* **Tanggal & Waktu**: {{ $application->interview_date ? \Carbon\Carbon::parse($application->interview_date)->format('d F Y H:i WIB') : 'Akan Diinformasikan' }}
* **Tipe Wawancara**: {{ ucfirst($application->interview_type ?? 'Online') }}
* **Tautan / Lokasi**: {{ $application->interview_location_link ?? 'Google Meet / Zoom Link' }}
* **Catatan Tim HR**: {{ $application->interview_notes ?? 'Mohon hadir 10 menit sebelum waktu yang ditentukan.' }}

<x-mail::button :url="route('dashboard')">
Konfirmasi & Lihat Jadwal Wawancara
</x-mail::button>

Terima kasih,<br>
Tim Rekrutmen {{ $application->job->company_name ?: 'TalentFlow HR' }}
</x-mail::message>
