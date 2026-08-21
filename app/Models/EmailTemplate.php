<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function seedDefaultTemplates()
    {
        if (self::count() < 9) {
            self::firstOrCreate(
                ['name' => 'Undangan HR Screening Call (Seleksi Berkas & Telepon Awal)'],
                [
                    'type' => 'screening',
                    'subject' => 'Undangan HR Screening Call - Posisi {job_title} ({company_name})',
                    'body_content' => "Yth. Sdr/i {candidate_name},\n\nTerima kasih atas minat Anda bergabung dengan {company_name} untuk posisi {job_title}.\n\nBerdasarkan hasil peninjauan awal berkas lamaran Anda, kami mengundang Anda untuk mengikuti sesi HR Initial Screening Call singkat selama 15-20 menit.\n\nSesi ini bertujuan untuk mendiskusikan pengalaman kerja, ekspektasi peran, serta ketersediaan Anda.\n\nTim HR kami akan menghubungi Anda melalui panggilan telepon / WhatsApp pada waktu yang disepakati.\n\nSalam hangat,\nTim HR {company_name}",
                    'is_active' => true,
                ]
            );

            self::firstOrCreate(
                ['name' => 'Undangan Ujian Teknis Coding & Software Engineer (Live Coding / Take-Home Assignment)'],
                [
                    'type' => 'test_invitation',
                    'subject' => 'Instruksi Ujian Teknis Coding (Technical Assessment) - Posisi {job_title}',
                    'body_content' => "Yth. Sdr/i {candidate_name},\n\nSelamat! Berdasarkan hasil seleksi berkas, Anda diundang untuk mengikuti Tahap Ujian Teknis Coding (Technical Coding Assessment) untuk posisi {job_title} di {company_name}.\n\n📌 Rincian Ujian Teknis:\n1. Topik: Algoritma, Pemrograman (PHP/Laravel/JS), REST API, & Database Design.\n2. Tipe Ujian: Online Assessment / Take-Home Coding Project.\n3. Portal Pengerjaan: Silakan login ke portal TalentFlow atau akses link coding challenge yang disediakan.\n\nHarap selesaikan ujian sebelum batas waktu yang ditentukan.\n\nSemoga sukses!\nTim Rekrutmen Engineering {company_name}",
                    'is_active' => true,
                ]
            );

            self::firstOrCreate(
                ['name' => 'Undangan Pengerjaan Tahap Tes Online & Psikotes'],
                [
                    'type' => 'test_invitation',
                    'subject' => 'Instruksi Pengerjaan Tes Online / Psikotes - Posisi {job_title}',
                    'body_content' => "Yth. Sdr/i {candidate_name},\n\nSelamat! Anda dinyatakan lolos ke tahap Tes Seleksi Online untuk posisi {job_title} di {company_name}.\n\nSilakan masuk ke portal sistem rekrutmen TalentFlow untuk mulai mengerjakan modul tes seleksi yang telah disiapkan oleh tim HR kami.\n\n📌 Petunjuk Pengerjaan:\n1. Pastikan koneksi internet stabil.\n2. Kerjakan di ruangan yang tenang dan tanpa gangguan.\n3. Kerjakan sebelum batas waktu pengerjaan berakhir.\n\nSemoga sukses!\nTim Rekrutmen {company_name}",
                    'is_active' => true,
                ]
            );

            self::firstOrCreate(
                ['name' => 'Undangan Wawancara HR (HR Interview)'],
                [
                    'type' => 'interview_hr',
                    'subject' => 'Undangan Wawancara HR (HR Interview) - Posisi {job_title} ({company_name})',
                    'body_content' => "Yth. Sdr/i {candidate_name},\n\nTerima kasih atas partisipasi Anda dalam rangkaian seleksi {company_name}.\n\nKami mengundang Anda untuk mengikuti sesi Wawancara HR (HR Interview) untuk posisi {job_title}.\n\nHarap konfirmasikan kehadiran Anda dan siapkan portofolio atau berkas pendukung.\n\nHormat kami,\nTim HR {company_name}",
                    'is_active' => true,
                ]
            );

            self::firstOrCreate(
                ['name' => 'Undangan Wawancara User (User / Department Manager Interview)'],
                [
                    'type' => 'interview_user',
                    'subject' => 'Undangan Wawancara User Manager - Posisi {job_title} ({company_name})',
                    'body_content' => "Yth. Sdr/i {candidate_name},\n\nSelamat! Berdasarkan hasil evaluasi sesi wawancara HR sebelumnya, Anda dinyatakan lolos ke tahap Wawancara User (User Manager Interview) untuk posisi {job_title} di {company_name}.\n\nPada sesi ini, Anda akan berdiskusi langsung dengan Department Head / Lead Manager mengenai studi kasus teknis, ekspektasi pekerjaan, serta pendalaman portofolio Anda.\n\nHarap hadir tepat waktu dan menyiapkan contoh hasil karya / kasus teknis terbaru Anda.\n\nHormat kami,\nTim Management & HR {company_name}",
                    'is_active' => true,
                ]
            );

            self::firstOrCreate(
                ['name' => 'Pemberitahuan Tahap Penawaran Kerja (Job Offer Letter)'],
                [
                    'type' => 'offering',
                    'subject' => 'Penawaran Kerja Resmi (Job Offer) - Posisi {job_title} ({company_name})',
                    'body_content' => "Yth. Sdr/i {candidate_name},\n\nSelamat! Berdasarkan hasil evaluasi akhir dari seluruh rangkaian seleksi, kami dengan senang hati menyampaikan bahwa Anda terpilih untuk bergabung sebagai {job_title} di {company_name}.\n\nKami telah menerbitkan Surat Penawaran Kerja (Offer Letter) resmi berformat PDF yang mencakup rincian kompensasi, benefit, dan tanggal mulai kerja.\n\nSilakan periksa dan berikan konfirmasi persetujuan Anda melalui portal TalentFlow.\n\nSelamat bergabung di keluarga besar {company_name}!\n\nHormat kami,\nTim Management & HR {company_name}",
                    'is_active' => true,
                ]
            );

            self::firstOrCreate(
                ['name' => 'Permintaan Verifikasi Dokumen & Background Check'],
                [
                    'type' => 'background_check',
                    'subject' => 'Permintaan Verifikasi Dokumen & Background Check - {job_title}',
                    'body_content' => "Yth. Sdr/i {candidate_name},\n\nSebagai bagian dari proses pra-kerja di {company_name} untuk posisi {job_title}, kami memerlukan beberapa dokumen pendukung untuk proses pemindaian & verifikasi latar belakang (Background Check).\n\nMohon siapkan dan unggah dokumen berikut:\n1. Salinan KTP & NPWP\n2. Ijazah & Transkrip Nilai Terakhir\n3. Surat Pengalaman Kerja (Paklaring) Perusahaan Sebelumnya\n4. Kontak Referensi Atasan/HR Perusahaan Sebelumnya\n\nTerima kasih atas kerja samanya.\nTim HR {company_name}",
                    'is_active' => true,
                ]
            );

            self::firstOrCreate(
                ['name' => 'Pengingat Sesi Wawancara Kerja (Interview Reminder)'],
                [
                    'type' => 'reminder',
                    'subject' => '[REMINDER] Pengingat Sesi Wawancara Kerja Besok - Posisi {job_title}',
                    'body_content' => "Yth. Sdr/i {candidate_name},\n\nEmail ini merupakan pengingat untuk sesi Wawancara Kerja Anda besok untuk posisi {job_title} di {company_name}.\n\nMohon hadir 10 menit sebelum waktu wawancara dan pastikan perangkat mikrofon & kamera dalam kondisi baik jika wawancara berlangsung online.\n\nSampai jumpa di sesi wawancara besok!\n\nSalam hangat,\nTim HR {company_name}",
                    'is_active' => true,
                ]
            );

            self::firstOrCreate(
                ['name' => 'Penolakan Halus & Apresiasi (Friendly Rejection Letter)'],
                [
                    'type' => 'rejection',
                    'subject' => 'Pemberitahuan Status Lamaran Kerja - {job_title} ({company_name})',
                    'body_content' => "Yth. Sdr/i {candidate_name},\n\nTerima kasih banyak atas waktu dan minat Anda melamar posisi {job_title} di {company_name}.\n\nSetelah melakukan peninjauan secara saksama terhadap seluruh kualifikasi dan berkas lamaran yang masuk, dengan berat hati kami menginformasikan bahwa saat ini kami belum dapat melanjutkan proses rekrutmen Anda ke tahap berikutnya untuk posisi ini.\n\nProfil dan kualifikasi Anda sangat mengesankan, namun kami memilih kandidat yang kriteria pengalamannya lebih sesuai dengan kebutuhan spesifik peran saat ini.\n\nKami akan tetap menyimpan data diri Anda dalam database kandidat kami mepeluang karier di masa mendatang.\n\nTerima kasih atas partisipasi Anda dan kami mendoakan kesuksesan karier Anda ke depan.\n\nHormat kami,\nTim HR {company_name}",
                    'is_active' => true,
                ]
            );
        }
    }
}
