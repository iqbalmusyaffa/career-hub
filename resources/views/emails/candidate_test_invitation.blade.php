<x-mail::message>
# Halo {{ $application->user->name }},

Selamat! Berkas lamaran Anda untuk posisi **{{ $application->job->title }}** di **{{ $application->job->company_name ?: 'Perusahaan Mitra' }}** telah lolos tahap peninjauan awal. Anda diundang untuk mengikuti **Tes Seleksi Online**.

---

### 🔑 Token Akses Ujian Anda (Rahasia)
<x-mail::panel>
# **{{ $testToken }}**
*Gunakan kode token unik di atas saat mengakses halaman ujian.*
</x-mail::panel>

---

### 📋 Rincian & Jadwal Asesmen:
* **Judul Tes:** {{ $test->title }}
@if($test->session_name)
* **Sesi Ujian / Gelombang:** {{ $test->session_name }}
@endif
* **Durasi Pengerjaan:** {{ $test->duration_minutes }} Menit
* **Passing Grade (KKM):** Minimal {{ $test->passing_score }}%
@if($test->starts_at)
* **Jadwal Mulai Ujian:** {{ $test->starts_at->translatedFormat('l, d F Y, H:i') }} WIB
@endif
@if($test->deadline_at)
* **Batas Waktu / Deadline:** **{{ $test->deadline_at->translatedFormat('l, d F Y, H:i') }} WIB**
@endif

@if($test->description)
> **Petunjuk:** {{ $test->description }}
@endif

---

<x-mail::button :url="route('candidate.tests.show', $application->job_id)">
Mulai Kerjakan Tes Seleksi
</x-mail::button>

*Catatan: Pastikan Anda berada di tempat yang tenang dengan koneksi internet yang stabil selama pengerjaan.*

Salam hangat,<br>
**Tim Rekrutmen {{ $application->job->company_name ?: 'Career Hub' }}**
</x-mail::message>
