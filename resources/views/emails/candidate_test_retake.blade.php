<x-mail::message>
# Halo {{ $application->user->name }},

Kabar baik! Tim Rekrutmen **{{ $application->job->company_name ?: 'Perusahaan Mitra' }}** telah mereset sesi ujian Anda dan memberikan **1x Kesempatan Ujian Ulang (Retake)** untuk posisi **{{ $application->job->title }}**.

Silakan manfaatkan kesempatan ini dengan sebaik-baiknya untuk memperbaiki perolehan skor Anda.

---

### 🔑 Token Akses Ujian Ulang Anda (Rahasia)
<x-mail::panel>
# **{{ $testToken }}**
*Masukkan kode token aktif di atas saat membuka laman pengerjaan ujian ulang.*
</x-mail::panel>

---

### 📋 Rincian & Ketentuan Ujian:
* **Judul Tes:** {{ $test->title }}
@if($test->session_name)
* **Sesi Ujian / Gelombang:** {{ $test->session_name }}
@endif
* **Durasi Pengerjaan:** {{ $test->duration_minutes }} Menit
* **Passing Grade (KKM Minimal):** Minimal {{ $test->passing_score }}%
@if($test->starts_at)
* **Jadwal Mulai:** {{ $test->starts_at->translatedFormat('l, d F Y, H:i') }} WIB
@endif
@if($test->deadline_at)
* **Batas Waktu / Deadline:** **{{ $test->deadline_at->translatedFormat('l, d F Y, H:i') }} WIB**
@endif

@if($test->description)
> **Petunjuk:** {{ $test->description }}
@endif

---

<x-mail::button :url="route('candidate.tests.show', $application->job_id)">
Mulai Kerjakan Ujian Ulang
</x-mail::button>

*Catatan: Pastikan koneksi internet Anda stabil dan kerjakan soal di lingkungan yang tenang.*

Salam hangat,<br>
**Tim Rekrutmen {{ $application->job->company_name ?: 'Career Hub' }}**
</x-mail::message>
