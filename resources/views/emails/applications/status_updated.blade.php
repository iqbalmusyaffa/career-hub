<x-mail::message>
# Halo {{ $application->user->name }},

Status lamaran kerja Anda untuk posisi **{{ $application->job->title }}** di **{{ $application->job->company_name ?: 'PT TechNova Asia Digital' }}** telah diperbarui.

### 📌 Status Terbaru:
**{{ $statusLabel }}**

<x-mail::button :url="route('dashboard')">
Pantau Dashboard Lamaran
</x-mail::button>

Terima kasih,<br>
Tim Rekrutmen {{ $application->job->company_name ?: 'TalentFlow HR' }}
</x-mail::message>
