<x-mail::message>
# Halo {{ $application->user->name }},

Ini adalah pesan pengingat bahwa Anda memiliki agenda **Ujian Seleksi Online** yang belum diselesaikan untuk posisi **{{ $application->job->title }}** di **{{ $application->job->company_name ?: 'Perusahaan Mitra' }}**.

@if($test->deadline_at)
> ⚠️ **Batas Akhir Ujian (Deadline):** **{{ $test->deadline_at->translatedFormat('l, d F Y, H:i') }} WIB**
> *Mohon selesaikan ujian sebelum batas waktu berakhir agar berkas lamaran Anda dapat diproses ke tahapan seleksi berikutnya.*
@endif

---

@if($testToken)
### 🔑 Token Akses Ujian Anda
<x-mail::panel>
# **{{ $testToken }}**
*Gunakan kode token rahasia di atas saat mengakses laman pengerjaan ujian.*
</x-mail::panel>
@endif

---

### 📋 Rincian Ujian Seleksi:
* **Judul Tes:** {{ $test->title }}
@if($test->session_name)
* **Sesi Ujian:** {{ $test->session_name }}
@endif
* **Durasi Pengerjaan:** {{ $test->duration_minutes }} Menit
* **Passing Grade (KKM Minimal):** {{ $test->passing_score }}%

---

<x-mail::button :url="route('candidate.tests.show', $application->job_id)">
Kerjakan Ujian Sekarang
</x-mail::button>

*Catatan: Pastikan Anda berada di lingkungan yang kondusif dan memiliki koneksi internet yang stabil selama pengerjaan.*

Salam hangat,<br>
**Tim Rekrutmen {{ $application->job->company_name ?: 'Career Hub' }}**
</x-mail::message>
