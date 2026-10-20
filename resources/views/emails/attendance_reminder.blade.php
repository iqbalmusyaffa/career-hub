<x-mail::message>
# Halo, {{ $user->name }} 👋

{{ $messageContent }}

<x-mail::panel>
### 📌 Informasi Jadwal
* **Hari & Tanggal:** {{ $dateFormatted }}
* **Kategori:** {{ match($type) {
    'morning_checkin' => 'Presensi Pagi (Check-In Kehadiran)',
    'afternoon_logbook' => 'Logbook Sore (Laporan Tugas & Jam Kerja)',
    'mentor_pending' => 'Verifikasi Logbook Magang oleh Mentor',
    default => 'Aktivitas Magang'
} }}
@if(isset($extraInfo['pending_count']))
* **Total Laporan Menunggu:** {{ $extraInfo['pending_count'] }} Peserta
@endif
</x-mail::panel>

@if($type === 'morning_checkin')
> 💡 **Tips Presensi:** Pastikan Anda mengisi presensi kehadiran tepat waktu dan mengaktifkan izin lokasi (GPS) pada browser jika diminta oleh sistem perusahaan.
@elseif($type === 'afternoon_logbook')
> 💡 **Tips Logbook:** Tuliskan ringkasan tugas yang telah dikerjakan hari ini dan akumulasi jam kerja Anda secara jelas agar mentor dapat menyetujui (ACC) laporan Anda.
@elseif($type === 'mentor_pending')
> 💡 **Informasi Mentor:** Logbook harian peserta yang belum disetujui akan diakumulasikan. Anda dapat meninjau dan memberikan catatan/feedback langsung di dashboard mentor.
@endif

<x-mail::button :url="$actionUrl">
{{ $actionText }}
</x-mail::button>

Jika tombol di atas tidak dapat diklik, salin dan tempel tautan berikut ke browser Anda:  
[{{ $actionUrl }}]({{ $actionUrl }})

---
Salam hangat,  
**Tim Pengelola Program Magang & Karir**
</x-mail::message>
