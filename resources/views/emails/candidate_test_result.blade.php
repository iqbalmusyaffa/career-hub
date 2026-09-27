<x-mail::message>
# Halo {{ $user->name }},

Terima kasih telah menyelesaikan **{{ $test->title }}** untuk posisi **{{ $job->title }}** di **{{ $job->company_name ?: 'Perusahaan Mitra' }}**.

Berikut adalah rincian rangkuman hasil evaluasi tes online Anda:

---

### 📊 Hasil Ujian Online Seleksi

<x-mail::panel>
# Skor Anda: **{{ $result->score }}%**
@if($result->passed)
**Status: ✅ LULUS / MEMENUHI KKM (Passing Grade Minimal: {{ $test->passing_score }}%)**
@else
**Status: ❌ BELUM MEMENUHI KKM (Passing Grade Minimal: {{ $test->passing_score }}%)**
@endif
</x-mail::panel>

---

### 📋 Rincian Asesmen:
* **Posisi Lowongan:** {{ $job->title }}
* **Perusahaan:** {{ $job->company_name ?: 'Perusahaan Mitra' }}
* **Kategori Ujian:** {{ ucfirst($test->category) }}
@if($test->session_name)
* **Sesi Ujian:** {{ $test->session_name }}
@endif
* **Nilai Kelulusan Minimum (KKM):** Minimal {{ $test->passing_score }}%
* **Skor yang Diperoleh:** {{ $result->score }}%
* **Waktu Selesai:** {{ $result->completed_at ? $result->completed_at->translatedFormat('l, d F Y, H:i') . ' WIB' : now()->translatedFormat('l, d F Y, H:i') . ' WIB' }}

---

@if($result->passed)
### 🎉 Langkah Selanjutnya:
Selamat atas pencapaian Anda! Karena skor ujian Anda telah memenuhi batas kelulusan minimum (KKM), lamaran Anda akan diproses ke tahapan seleksi berikutnya (Tahap Wawancara / Review Lanjutan Tim HR). Tim rekruter akan segera menghubungi Anda jika ada jadwal tahapan wawancara.
@else
### 💡 Catatan Rekrutmen:
Terima kasih banyak atas waktu dan usaha yang telah Anda luangkan untuk mengikuti ujian seleksi online ini. Meskipun perolehan skor Anda saat ini belum memenuhi batas kelulusan minimum posisi ini, kami sangat menghargai partisipasi Anda. Tetap semangat dan jangan ragu untuk melamar kesempatan karir lainnya di portal TalentFlow!
@endif

<x-mail::button :url="route('candidate.tests.show', $job->id)">
Lihat Ringkasan Hasil di Portal Karir
</x-mail::button>

Salam hangat,<br>
**Tim Rekrutmen {{ $job->company_name ?: 'Career Hub' }}**
</x-mail::message>
