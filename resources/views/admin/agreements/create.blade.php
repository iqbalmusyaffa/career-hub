@php
    $leavePolicy = $leavePolicy ?? \App\Models\CompanyLeavePolicy::getForCompany(1);
    $annualQuota = $leavePolicy->annual_leave_quota ?? 12;
    $permanentQuota = $leavePolicy->permanent_leave_quota ?? 15;
    $internshipMaxDays = $leavePolicy->internship_max_excused_days ?? 4;

    $jobBenefits = array_values(array_filter(array_map('trim', explode(',', $application->job->benefits ?? ''))));
    if (empty($jobBenefits) && !empty($application->job?->companyProfile?->benefits)) {
        $compBenefits = $application->job->companyProfile->benefits;
        if (is_array($compBenefits)) {
            $jobBenefits = $compBenefits;
        } elseif (is_string($compBenefits)) {
            $jobBenefits = array_values(array_filter(array_map('trim', explode(',', $compBenefits))));
        }
    }
    $benefitsClause = "";
    if (!empty($jobBenefits)) {
        $benefitsClause = "\n\nFASILITAS & BENEFIT KERJA YANG DISEDIAKAN PERUSAHAAN:\n";
        foreach ($jobBenefits as $idx => $b) {
            $benefitsClause .= "• " . $b . "\n";
        }
    }

    $isInternship = Str::contains(strtolower($application->job->work_type ?? ''), ['intern', 'magang']) || Str::contains(strtolower($application->job->title ?? ''), ['intern', 'magang']);
    $isRemote = Str::contains(strtolower($application->job->work_type ?? ''), ['remote', 'wfh']);
    $isHybrid = Str::contains(strtolower($application->job->work_type ?? ''), ['hybrid']);
    $isPermanent = Str::contains(strtolower($application->job->work_type ?? ''), ['permanent', 'tetap']);

    if ($isInternship) {
        $defaultType = 'internship_agreement';
        $defaultTitle = 'Surat Perjanjian Magang Kerja (Internship Agreement)';
        $defaultTerms = "PASAL 1: STATUS PROGRAM MAGANG & JANGKA WAKTU
1. Pihak Pertama menerima Pihak Kedua sebagai Peserta Magang (Internship) untuk posisi dan divisi yang tercantum dalam perjanjian ini.
2. Program magang berlangsung terhitung mulai Tanggal Mulai hingga Tanggal Selesai yang disepakati bersama.
3. Hubungan ini merupakan Hubungan Pelatihan Kerja Praktek / Magang Akademik Berkelanjutan dan bukan hubungan kerja tetap ketenagakerjaan, mengacu pada Permenaker No. 6 Tahun 2020 tentang Penyelenggaraan Pemagangan di Dalam Negeri.

PASAL 2: HAK UANG SAKU (STIPEND), PERLINDUNGAN BPJS, MENTORSHIP & SERTIFIKAT MAGANG
1. Pihak Kedua berhak menerima Uang Saku Insentif / Uang Transport & Makan (Stipend Magang) bulanan sebesar nominal yang disepakati pada perjanjian ini.
2. Perlindungan Jaminan Sosial (BPJS): Pihak Pertama mengikutsertakan Pihak Kedua dalam program perlindungan BPJS Ketenagakerjaan (Jaminan Kecelakaan Kerja / JKK dan Jaminan Kematian / JKM) serta jaminan BPJS Kesehatan selama masa program magang berlangsung sesuai regulasi yang berlaku.
3. Pihak Pertama menunjuk Mentor Pembimbing Profesional untuk memberikan bimbingan teknis, transfer pengetahuan, dan evaluasi kompetensi secara berkala.
4. Pihak Kedua yang berhasil menyelesaikan program berhak menerima:
   a. Sertifikat Kelulusan Magang Resmi Ber-QR Code.
   b. Transkrip Evaluasi Nilai Akademik Magang (5 Kriteria Evaluasi).
   c. Surat Rekomendasi Kerja / Referensi Karir dari Manajemen.

PASAL 3: JAM PELATIHAN MAGANG & WAKTU ISTIRAHAT
1. Waktu pelaksanaan magang adalah maksimal 8 (delapan) jam per hari atau 40 (empat puluh) jam per minggu (Senin s/d Jumat) dan tidak diperkenankan melebihi batas waktu magang yang wajar.
2. Pihak Kedua berhak atas waktu istirahat minimal 1 (satu) jam per hari kerja serta libur pada hari libur resmi nasional.

PASAL 4: HAK IZIN AKADEMIK, UJIAN KAMPUS, CUTI SAKIT & KETENTUAN DISPENSASI
1. Izin Akademik: Pihak Pertama memberikan izin dispensasi resmi kepada Pihak Kedua untuk keperluan akademik institusi pendidikan (seperti: Sidang Skripsi/Tugas Akhir, Ujian Tengah/Akhir Semester, Bimbingan Dosen, atau Wisuda) dengan pemberitahuan dan surat pengantar kampus.
2. Izin Sakit: Pihak Kedua yang berhalangan hadir karena sakit berhak beristirahat dengan memberitahukan kepada Mentor dan melampirkan Surat Keterangan Dokter atau surat pemberitahuan.
3. Izin Keperluan Mendesak / Duka Cita: Diberikan izin dispensasi maksimal hingga {$internshipMaxDays} hari kerja untuk keperluan mendesak keluarga atau duka cita atas persetujuan tertulis Mentor/HRD.
4. Batas Maksimal Izin & Pemotongan Uang Saku: Batas toleransi akumulasi izin/dispensasi yang diberikan adalah maksimal {$internshipMaxDays} hari kerja. Apabila Pihak Kedua tidak hadir atau mengajukan izin melebihi batas {$internshipMaxDays} hari kerja tersebut, maka akan diberlakukan pemotongan uang saku (stipend) secara proporsional (prorata) sesuai jumlah hari ketidakhadiran berlebih.

PASAL 5: HAK KEKAYAAN INTELEKTUAL (HAKI) & KERAHASIAAN DATA (NDA STRICT)
1. Seluruh hasil karya, source code, desain antarmuka (UI/UX), dokumen teknis, dan algoritma yang dibuat Pihak Kedua selama masa magang sepenuhnya menjadi Hak Milik Intelektual Pihak Pertama.
2. Pihak Kedua dilarang keras menyalin, menyebarluaskan, atau mempublikasikan data rahasia internal, data pengguna, kredensial server, atau source code perusahaan ke publik tanpa izin tertulis dari Pihak Pertama.

PASAL 6: TATA TERTIB, KODE ETIK & LOGBOOK AKTIVITAS
1. Pihak Kedua wajib mematuhi jam pelatihan, berpenampilan rapi dan sopan, menjaga etika profesionalisme, serta mengisi Logbook Aktivitas Harian Magang secara tertib.
2. Mentor Pembimbing melakukan evaluasi berkala atas 5 Kriteria (Kedisiplinan, Keahlian Teknis, Komunikasi & Kerjasama, Inisiatif Problem Solving, dan Etika Kerja).

PASAL 7: PENGHENTIAN PROGRAM MAGANG & REKRUTMEN LANJUTAN
1. Program magang berakhir otomatis pada Tanggal Selesai yang ditentukan.
2. Pihak Pertama berhak menghentikan magang secara sepihak apabila Pihak Kedua melakukan pelanggaran berat, tindakan kriminal, absen tanpa keterangan lebih dari 30 (tiga puluh) hari kalender (1 bulan), atau membocorkan rahasia perusahaan.
3. Peserta magang dengan pencapaian predikat istimewa (Grade A) berhak diprioritaskan dalam program Fast-Track Hiring menjadi Karyawan Perusahaan.{$benefitsClause}";
    } elseif ($isRemote) {
        $defaultType = 'remote_contract';
        $defaultTitle = 'Surat Perjanjian Kerja Remote / Work From Home (Remote Work & NDA)';
        $defaultTerms = "PASAL 1: KETENTUAN HUBUNGAN KERJA REMOTE & LOKASI DOMISILI
1. Pihak Pertama mempekerjakan Pihak Kedua untuk melaksanakan tugas dan tanggung jawab pekerjaan secara Jarak Jauh (Remote Work / Work From Home / WFH) dari lokasi domisili resmi Pihak Kedua.
2. Pihak Kedua wajib memastikan ketersediaan sarana kelistrikan, ruang kerja kondusif, dan konektivitas jaringan internet yang stabil.

PASAL 2: GAJI, TUNJANGAN INTERNET, BPJS & THR KEAGAMAAN
1. Pihak Pertama membayarkan Gaji Pokok dan Tunjangan Operasional Internet/Komunikasi (Remote Allowance) bulanan kepada Pihak Kedua.
2. Pihak Pertama mengikutsertakan Pihak Kedua dalam program BPJS Ketenagakerjaan (JKK, JKM, JHT, JKP) dan BPJS Kesehatan sesuai undang-undang.
3. Pihak Kedua berhak menerima Tunjangan Hari Raya (THR) Keagamaan sesuai peraturan perundang-undangan ketenagakerjaan.

PASAL 3: JAM KERJA INTI (CORE HOURS), KETERHUBUNGAN & PRESENSI
1. Pihak Kedua wajib aktif dan responsif pada saluran komunikasi resmi perusahaan (Slack/Teams/Google Meet/Email) selama Jam Kerja Inti (Core Working Hours: pukul 09.00 - 17.00 WIB).
2. Pihak Kedua melakukan pencatatan absensi harian secara digital melalui sistem presensi TalentFlow.

PASAL 4: HAK CUTI TAHUNAN, CUTI SAKIT, HAK UNPLUGGED & CUTI KHUSUS
1. Cuti Tahunan: Pihak Kedua berhak atas {$annualQuota} hari kerja Cuti Tahunan berbayar per tahun. Selama cuti, Pihak Kedua berhak atas Right to Disconnect (tidak diganggu urusan operasional).
2. Cuti Sakit: Pihak Kedua yang sakit berhak istirahat dengan tetap menerima upah penuh dengan menyerahkan Surat Keterangan Dokter paling lambat 1x24 jam.
3. Cuti Khusus / Izin Berbayar (Paid Leave) diberikan untuk:
   a. Pernikahan karyawan: 3 (tiga) hari kerja.
   b. Pernikahan anak: 2 (dua) hari kerja.
   c. Khitanan / Baptis anak: 2 (dua) hari kerja.
   d. Istri melahirkan / keguguran: 2 (dua) hari kerja.
   e. Duka cita keluarga inti (Suami/Istri/Anak/Orang Tua): 2 (dua) hari kerja.
4. Cuti Melahirkan: Karyawati berhak atas cuti melahirkan 3 (tiga) bulan dengan upah penuh sesuai surat dokter kandungan/bidan.

PASAL 5: KEAMANAN SIBER, VPN & NON-DISCLOSURE AGREEMENT (NDA STRICT)
1. Pihak Kedua wajib menggunakan jaringan terenkripsi Virtual Private Network (VPN Perusahaan) saat mengakses database, server produksi, repositori Git, dan dashboard internal perusahaan.
2. Pihak Kedua dilarang keras menggunakan Wi-Fi publik tanpa VPN atau meminjamkan perangkat kerja kepada pihak ketiga manapun.
3. Seluruh basis data, arsitektur kode, data nasabah/pengguna, dan strategi bisnis bersifat RAHASIA NEGARA PERUSAHAAN (STRICT NDA) yang dilindungi sanksi pidana UU ITE.

PASAL 6: EVALUASI OUTPUT KERJA (DELIVERABLES) & TATA TERTIB
1. Penilaian kinerja karyawan remote difokuskan pada ketercapaian target, kualitas hasil kerja (deliverables), ketepatan deadline, dan komunikasi asinkron yang efektif.
2. Ketidakaktifan tanpa kabar (ghosting) selama lebih dari 3 (tiga) hari kerja berturut-turut merupakan pelanggaran berat dan dapat dikenakan Surat Peringatan (SP) hingga PHK.

PASAL 7: PENGAKHIRAN KERJA, PENGEMBALIAN ASET & HUKUM YANG BERLAKU
1. Pengakhiran hubungan kerja dilaksanakan berdasarkan ketentuan perundang-undangan ketenagakerjaan Republik Indonesia.
2. Saat hubungan kerja berakhir, Pihak Kedua wajib menghapus seluruh data lokal perusahaan dan mengembalikan inventaris perusahaan dalam waktu maksimal 7 (tujuh) hari kerja.
3. Segala perselisihan diselesaikan secara musyawarah untuk mufakat atau mediasi ketenagakerjaan.{$benefitsClause}";
    } elseif ($isHybrid) {
        $defaultType = 'hybrid_contract';
        $defaultTitle = 'Surat Perjanjian Kerja Hybrid (Hybrid Work & Flexible Policy)';
        $defaultTerms = "PASAL 1: KETENTUAN HUBUNGAN KERJA HYBRID & JADWAL KERJA
1. Pihak Pertama menerima Pihak Kedua untuk bekerja dengan skema Kerja Hybrid (kombinasi Work From Office / WFO di kantor perusahaan dan Work From Home / WFH dari domisili).
2. Pembagian jadwal giliran hari WFO dan WFH diatur secara fleksibel oleh Atasan Langsung (Direct Manager) atau jadwal rotasi divisi.

PASAL 2: GAJI, TUNJANGAN TRANSPORT-KOMUNIKASI, BPJS & THR
1. Pihak Pertama memberikan Hak Gaji Pokok, Tunjangan Kehadiran Transport/Makan WFO, dan Tunjangan Komunikasi WFH setiap bulan.
2. Pihak Kedua didaftarkan dalam program BPJS Ketenagakerjaan dan BPJS Kesehatan serta berhak atas THR Keagamaan tahunan.

PASAL 3: KETENTUAN KEHADIRAN KANTOR (WFO) & KETENTUAN FLEKSIBEL (WFH)
1. Pada hari kerja WFO: Pihak Kedua wajib hadir secara fisik di kantor, mematuhi jam kerja kantor (08.30 - 17.30 WIB), dan berkoordinasi langsung dengan tim.
2. Pada hari kerja WFH: Pihak Kedua wajib standby dan responsif pada saluran komunikasi resmi selama Jam Kerja Inti (09.00 - 17.00 WIB) serta menyelesaikan target harian.

PASAL 4: HAK CUTI TAHUNAN, CUTI SAKIT & HAK IZIN RESMI
1. Cuti Tahunan: Pihak Kedua berhak atas hak Cuti Tahunan {$annualQuota} hari kerja per tahun yang dapat diajukan baik pada hari jadwal WFO maupun WFH.
2. Cuti Sakit: Pihak Kedua berhak atas istirahat sakit berbayar penuh dengan melampirkan Surat Keterangan Dokter resmi.
3. Cuti Khusus & Cuti Melahirkan:
   a. Cuti Melahirkan 3 (tiga) bulan upah penuh / Cuti Keguguran 1,5 bulan.
   b. Izin Menikah: 3 (tiga) hari kerja.
   c. Izin Istri Melahirkan: 2 (dua) hari kerja.
   d. Izin Duka Cita Keluarga Inti: 2 (dua) hari kerja.

PASAL 5: KEAMANAN SIBER, VPN & KERAHASIAAN DATA PERUSAHAAN (NDA)
1. Pihak Kedua wajib mengaktifkan VPN resmi perusahaan saat bekerja pada hari WFH dan menjaga keamanan data internal dari kebocoran pihak ketiga.
2. Seluruh sistem, file pekerjaan, dan ide produk merupakan Hak Kekayaan Intelektual (HAKI) eksklusif milik Pihak Pertama.

PASAL 6: TATA TERTIB, KODE ETIK & DISIPLIN KERJA
1. Pihak Kedua wajib menjaga profesionalisme kerja, mematuhi SOP kerja, dan memenuhi Key Performance Indicators (KPI) yang ditetapkan.
2. Pelanggaran terhadap tata tertib dapat dikenakan sanksi bertingkat (SP 1, SP 2, SP 3).

PASAL 7: PENGAKHIRAN HUBUNGAN KERJA & PENYELESAIAN SENGKETA
1. Pengakhiran hubungan kerja dilaksanakan berdasarkan peraturan perundang-undangan ketenagakerjaan yang berlaku di Republik Indonesia.
2. Setiap sengketa diselesaikan secara kekeluargaan musyawarah mufakat atau mediasi ketenagakerjaan.{$benefitsClause}";
    } elseif ($isPermanent) {
        $defaultType = 'permanent_contract';
        $defaultTitle = 'Surat Perjanjian Kerja Waktu Tidak Tertentu (PKWTT / Karyawan Tetap)';
        $defaultTerms = "PASAL 1: PENGANGKATAN KARYAWAN TETAP & MASA PERCOBAAN (PROBATION)
1. Pihak Pertama mengangkat Pihak Kedua sebagai Karyawan Waktu Tidak Tertentu (PKWTT / Karyawan Tetap) untuk posisi yang ditentukan.
2. Pihak Kedua menjalani Masa Percobaan Kerja (Probation) selama maksimal 3 (tiga) bulan. Setelah lulus masa percobaan, Pihak Kedua memperoleh Surat Keputusan (SK) Pengangkatan Karyawan Tetap.

PASAL 2: HAK GAJI POKOK, TUNJANGAN, BPJS KESEHATAN & KETENAGAKERJAAN
1. Pihak Pertama memberikan Hak Gaji Pokok, Tunjangan Jabatan, dan Tunjangan Operasional bulanan yang ditransfer tepat waktu setiap bulan.
2. Pihak Pertama mengikutsertakan Pihak Kedua dalam program BPJS Kesehatan serta BPJS Ketenagakerjaan lengkap (JKK, JKM, JHT, JP / Jaminan Pensiun, dan JKP).
3. Pihak Kedua berhak menerima Tunjangan Hari Raya (THR) Keagamaan sebesar 1 (satu) bulan upah penuh setiap tahun.

PASAL 3: WAKTU KERJA, KEHADIRAN & HAK ISTIRAHAT
1. Jam kerja resmi adalah 40 (empat puluh) jam per minggu dengan sistem 5 (lima) hari kerja (Senin s/d Jumat, pukul 08.30 - 17.30 WIB termasuk 1 jam istirahat).
2. Pihak Kedua wajib mencatatkan presensi kehadiran harian melalui sistem absensi digital perusahaan.

PASAL 4: HAK CUTI TAHUNAN, CUTI SAKIT, CUTI MELAHIRKAN & IZIN RESMI
1. Cuti Tahunan: Pihak Kedua berhak atas {$permanentQuota} hari kerja Cuti Tahunan berbayar setelah bekerja 1 (satu) tahun terus-menerus.
2. Cuti Sakit Berkepanjangan: Apabila Pihak Kedua sakit berkepanjangan berdasarkan surat tim dokter, pembayaran upah dilakukan sesuai skema perlindungan Pasal 93 UU Ketenagakerjaan (100% 4 bulan pertama, 75% 4 bulan kedua, 50% 4 bulan ketiga, dan 25% untuk bulan selanjutnya).
3. Izin Khusus Meninggalkan Pekerjaan dengan Upah Penuh (Paid Leave):
   a. Pernikahan karyawan: 3 (tiga) hari kerja.
   b. Pernikahan anak: 2 (dua) hari kerja.
   c. Khitanan / Pembaptisan anak: 2 (dua) hari kerja.
   d. Istri melahirkan atau keguguran kandungan: 2 (dua) hari kerja.
   e. Suami/Istri, Orang Tua/Mertua, atau Anak meninggal dunia: 2 (dua) hari kerja.
   f. Anggota keluarga dalam satu rumah meninggal dunia: 1 (satu) hari kerja.
4. Cuti Melahirkan & Keguguran: Diberikan cuti 3 (tiga) bulan bagi karyawati yang melahirkan dan 1,5 bulan bagi yang mengalami keguguran kandungan dengan upah penuh.

PASAL 5: KERAHASIAAN INFORMASI (NDA), KEAMANAN SIBER & HAKI
1. Pihak Kedua terikat kewajiban Non-Disclosure Agreement (NDA) ketat untuk tidak menyebarluaskan rahasia perusahaan, kode sumber aplikasi, data pelanggan, dan arsip internal.
2. Seluruh Hak Cipta, Paten, Merk, dan Hak Kekayaan Intelektual atas karya yang dibuat dalam hubungan dinas sepenuhnya menjadi milik Pihak Pertama.

PASAL 6: EVALUASI KINERJA (KPI), PROMOSI & PENGEMBANGAN KARIR
1. Pihak Pertama melakukan Evaluasi Kinerja (Performance Appraisal / KPI) berkala minimal 1 (satu) kali dalam setahun untuk pertimbangan penyesuaian gaji, bonus kinerja, dan promosi jenjang karir.
2. Pihak Pertama menyediakan program pelatihan dan peningkatan kompetensi profesional bagi Pihak Kedua.

PASAL 7: PEMUTUSAN HUBUNGAN KERJA (PHK), UANG PESANGON & HAK FINANSIAL
1. Pemutusan Hubungan Kerja (PHK) dilaksanakan berdasarkan ketentuan UU Ketenagakerjaan No. 13 Tahun 2003 jo. UU No. 6 Tahun 2023 dan Peraturan Pemerintah No. 35 Tahun 2021.
2. Hak Kompensasi PHK bagi Karyawan Tetap: Dalam hal terjadi PHK oleh Pihak Pertama, Pihak Kedua berhak menerima hak finansial sesuai regulasi ketenagakerjaan yang meliputi:
   a. Uang Pesangon (UP) yang dihitung berdasarkan masa kerja riil (Pasal 40 ayat 2 PP 35/2021).
   b. Uang Penghargaan Masa Kerja (UPMK) bagi yang memiliki masa kerja 3 (tiga) tahun atau lebih (Pasal 40 ayat 3 PP 35/2021).
   c. Uang Penggantian Hak (UPH) meliputi pembayaran sisa Cuti Tahunan yang belum gugur dan ongkos kepulangan.
3. Pengunduran Diri Sukarela (Resign): Pihak Kedua wajib mengajukan surat permohonan tertulis minimal 30 (tiga puluh) hari kalender sebelumnya (one month notice) dan menyelesaikan serah terima tugas (handover). Karyawan yang resign berhak atas Uang Penggantian Hak (UPH) dan Uang Pisah sesuai peraturan yang berlaku.

PASAL 8: HUKUM YANG BERLAKU & PENYELESAIAN SENGKETA
1. Perjanjian ini tunduk pada Hukum Ketenagakerjaan Negara Republik Indonesia.
2. Segala perselisihan diselesaikan melalui forum musyawarah mufakat, Mediasi Tripartit Disnaker, hingga Pengadilan Hubungan Industrial (PHI).{$benefitsClause}";
    } else {
        $defaultType = 'employment_contract';
        $defaultTitle = 'Surat Perjanjian Kerja Waktu Tertentu (PKWT / Kontrak Kerja)';
        $defaultTerms = "PASAL 1: KETENTUAN HUBUNGAN KERJA & JANGKA WAKTU (PKWT)
1. Pihak Pertama mempekerjakan Pihak Kedua sebagai Karyawan Waktu Tertentu (PKWT) untuk posisi dan divisi yang tercantum pada perjanjian ini.
2. Perjanjian kerja ini berlaku terhitung sejak Tanggal Mulai Efektif sampai dengan Tanggal Berakhir yang disepakati bersama.
3. Hubungan kerja ini tunduk pada Undang-Undang Ketenagakerjaan No. 13 Tahun 2003 jo. UU No. 6 Tahun 2023 (Cipta Kerja) dan Peraturan Pemerintah No. 35 Tahun 2021.

PASAL 2: HAK GAJI, BPJS, THR & UANG KOMPENSASI PKWT
1. Pihak Pertama membayarkan Gaji Pokok dan Tunjangan bulanan kepada Pihak Kedua sebesar nominal yang disepakati setiap tanggal penggajian resmi.
2. Pihak Pertama mendaftarkan Pihak Kedua dalam program BPJS Ketenagakerjaan (JKK, JKM, JHT, JKP) dan BPJS Kesehatan sesuai ketentuan perundang-undangan.
3. Pihak Kedua berhak atas Tunjangan Hari Raya (THR) Keagamaan secara proporsional sesuai masa kerja yang telah dijalani.
4. Di akhir masa kontrak yang telah selesai secara penuh, Pihak Kedua berhak menerima Uang Kompensasi PKWT sesuai masa kerja berdasarkan Pasal 15 PP No. 35 Tahun 2021.

PASAL 3: WAKTU KERJA, ISTIRAHAT & KERJA LEMBUR
1. Waktu kerja adalah 8 (delapan) jam per hari atau 40 (empat puluh) jam per minggu (Senin s/d Jumat) dengan 1 (satu) jam waktu istirahat.
2. Kelebihan jam kerja atas instruksi resmi Atasan diperhitungkan sebagai Kerja Lembur sesuai ketentuan upah lembur resmi.

PASAL 4: HAK CUTI TAHUNAN, CUTI SAKIT & IZIN RESMI
1. Hak Cuti Tahunan: Pihak Kedua berhak atas Cuti Tahunan sebanyak {$annualQuota} hari kerja setelah memiliki masa kerja 12 (dua belas) bulan berturut-turut, atau cuti proporsional yang diatur dalam Peraturan Perusahaan.
2. Cuti Sakit: Pihak Kedua yang tidak dapat bekerja karena sakit berhak atas istirahat dengan tetap menerima upah penuh, wajib menyerahkan Surat Keterangan Dokter resmi paling lambat 1x24 jam.
3. Cuti Khusus / Izin Resmi dengan Upah Penuh (Paid Leave) diberikan untuk:
   a. Pernikahan Pihak Kedua: 3 (tiga) hari kerja.
   b. Pernikahan anak Pihak Kedua: 2 (dua) hari kerja.
   c. Khitanan / Pembaptisan anak: 2 (dua) hari kerja.
   d. Istri melahirkan atau keguguran kandungan: 2 (dua) hari kerja.
   e. Suami/Istri, Orang Tua/Mertua, atau Anak Pihak Kedua meninggal dunia: 2 (dua) hari kerja.
   f. Anggota keluarga dalam satu rumah meninggal dunia: 1 (satu) hari kerja.
4. Cuti Melahirkan: Karyawati berhak atas istirahat melahirkan selama 1,5 bulan sebelum dan 1,5 bulan sesudah melahirkan dengan upah penuh sesuai surat keterangan dokter kandungan/bidan.

PASAL 5: KERAHASIAAN INFORMASI (NDA) & HAK KEKAYAAN INTELEKTUAL (HAKI)
1. Pihak Kedua wajib menjaga kerahasiaan seluruh Data Perusahaan, Source Code, Database, Strategi Bisnis, Dokumen Keuangan, dan Rahasia Dagang milik Pihak Pertama selama maupun setelah hubungan kerja berakhir.
2. Seluruh hasil kerja, penemuan, sistem, modul program, desain grafis, dan inovasi yang diciptakan Pihak Kedua selama masa kerja sepenuhnya merupakan Hak Kekayaan Intelektual eksklusif milik Pihak Pertama.

PASAL 6: TATA TERTIB, DISIPLIN & SANKSI
1. Pihak Kedua wajib mematuhi seluruh Standard Operating Procedure (SOP), Kode Etik Perusahaan, serta instruksi kerja Atasan.
2. Pelanggaran terhadap disiplin dan tata tertib kerja dapat dikenakan sanksi bertingkat berupa: Surat Peringatan Pertama (SP 1), Surat Peringatan Kedua (SP 2), atau Surat Peringatan Ketiga (SP 3) / Pemutusan Hubungan Kerja.

PASAL 7: PENGAKHIRAN KONTRAK, UANG KOMPENSASI PKWT & GANTI RUGI
1. Hubungan kerja berakhir demi hukum pada saat jangka waktu PKWT telah selesai.
2. Hak Uang Kompensasi PKWT: Di akhir masa kontrak kerja, Pihak Pertama wajib membayarkan Uang Kompensasi PKWT dengan perhitungan proporsional: (Masa Kerja / 12) x 1 (satu) bulan upah penuh, sesuai ketentuan Pasal 15 s/d 17 PP No. 35 Tahun 2021.
3. Pemutusan Sebelum Waktu (Early Termination): Apabila salah satu pihak mengakhiri hubungan kerja sebelum berakhirnya jangka waktu tanpa alasan pelanggaran berat, pihak yang mengakhiri wajib membayar ganti rugi sebesar sisa upah masa kontrak kepada pihak lainnya sesuai PP 35/2021.

PASAL 8: PENYELESAIAN PERSELISIHAN
1. Segala perselisihan yang timbul diselesaikan secara musyawarah untuk mufakat (Bipartit).
2. Apabila mufakat tidak tercapai, perselisihan diselesaikan melalui mekanisme mediasi Dinas Tenaga Kerja dan Pengadilan Hubungan Industrial (PHI) sesuai Hukum Republik Indonesia.{$benefitsClause}";
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.applications.show', $application) }}" class="w-10 h-10 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center justify-center transition shadow-2xs">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                    Buat Surat Perjanjian Digital (Contract Builder)
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kandidat: <strong>{{ $application->user->name }}</strong> • Posisi: <strong>{{ $application->job->title }}</strong> ({{ $application->job->work_type }})</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 dark:bg-slate-900 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 dark:border-slate-700 space-y-6">
                <div class="border-b border-slate-100 dark:border-slate-700 pb-4 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Formulir Penyusunan Dokumen Perjanjian Kerja / Magang</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Kandidat akan menerima dokumen ini dan diminta memberikan tanda tangan digital (E-Signature).</p>
                    </div>
                    <span id="agreementModeBadge" class="px-3 py-1 bg-amber-100 dark:bg-amber-950/80 text-amber-900 dark:text-amber-300 text-3xs font-black rounded-xl uppercase border border-amber-200 dark:border-amber-800 shrink-0 transition-all shadow-2xs">
                        {{ $isInternship ? '🎓 Mode Perjanjian Magang' : '💼 Mode Kontrak Kerja (PKWT)' }}
                    </span>
                </div>

                <form action="{{ route('admin.applications.agreements.store', $application) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs">
                            <div class="font-bold flex items-center gap-2 mb-1">
                                <i class="fa-solid fa-circle-exclamation text-rose-500"></i> Terjadi kesalahan pengisian formulir:
                            </div>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Tipe Perjanjian <span class="text-rose-500">*</span></label>
                            <select id="agreementTypeSelect" name="agreement_type" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-indigo-500 focus:border-indigo-500 font-bold py-2.5">
                                <option value="employment_contract" {{ old('agreement_type', $defaultType) == 'employment_contract' ? 'selected' : '' }}>💼 Surat Perjanjian Kerja Waktu Tertentu (PKWT / Kontrak)</option>
                                <option value="permanent_contract" {{ old('agreement_type', $defaultType) == 'permanent_contract' ? 'selected' : '' }}>🏢 Surat Perjanjian Kerja Waktu Tidak Tertentu (PKWTT / Karyawan Tetap)</option>
                                <option value="remote_contract" {{ old('agreement_type', $defaultType) == 'remote_contract' ? 'selected' : '' }}>🏠 Surat Perjanjian Kerja Remote / WFH (Remote Work & NDA)</option>
                                <option value="hybrid_contract" {{ old('agreement_type', $defaultType) == 'hybrid_contract' ? 'selected' : '' }}>🔀 Surat Perjanjian Kerja Hybrid (Hybrid Work & Flexible Policy)</option>
                                <option value="internship_agreement" {{ old('agreement_type', $defaultType) == 'internship_agreement' ? 'selected' : '' }}>🎓 Surat Perjanjian Magang (Internship Agreement)</option>
                            </select>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase">Nomor Dokumen Perjanjian <span class="text-rose-500">*</span></label>
                                <span class="text-3xs text-slate-400 dark:text-slate-500 font-medium flex items-center gap-1"><i class="fa-solid fa-lock text-3xs"></i> Otomatis & Terkunci</span>
                            </div>
                            <div class="relative">
                                <input type="text" id="contractNumberInput" name="contract_number" value="{{ old('contract_number', $contractNumber) }}" readonly required class="w-full border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold py-2.5 pl-3 pr-9 cursor-not-allowed select-none focus:ring-0 focus:border-slate-300 dark:focus:border-slate-600">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Judul Dokumen Perjanjian <span class="text-rose-500">*</span></label>
                            <input type="text" id="titleInput" name="title" value="{{ old('title', $defaultTitle) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-indigo-500 focus:border-indigo-500 font-bold py-2.5">
                        </div>

                        <!-- SIGNATORY 1: HR MANAGER -->
                        <div class="bg-slate-50 dark:bg-slate-900/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-3 sm:col-span-1 shadow-2xs">
                            <h4 class="text-xs font-black uppercase text-slate-800 dark:text-white flex items-center gap-1.5 border-b border-slate-200 dark:border-slate-800 pb-2">
                                👤 Penanda Tangan 1: HRD / HR Manager
                            </h4>
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Nama HRD / Manager <span class="text-rose-500">*</span></label>
                                <input type="text" name="hr_signer_name" value="{{ old('hr_signer_name', old('hr_name', auth()->user()->name)) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2">
                            </div>
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Jabatan Resmi <span class="text-rose-500">*</span></label>
                                <input type="text" name="hr_title" value="{{ old('hr_title', 'Head of Human Resources') }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2">
                            </div>
                        </div>

                        <!-- SIGNATORY 2: COMPANY OWNER / DIRECTOR -->
                        <div class="bg-slate-50 dark:bg-slate-900/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-3 sm:col-span-1 shadow-2xs">
                            <h4 class="text-xs font-black uppercase text-slate-800 dark:text-white flex items-center gap-1.5 border-b border-slate-200 dark:border-slate-800 pb-2">
                                🏛️ Penanda Tangan 2: Owner / Direktur Utama
                            </h4>
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Nama Direktur / Owner <span class="text-rose-500">*</span></label>
                                <input type="text" name="owner_signer_name" value="{{ old('owner_signer_name', old('owner_name', $companyOwner->name ?? $application->job->company_name)) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2">
                            </div>
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Jabatan Direksi <span class="text-rose-500">*</span></label>
                                <input type="text" name="owner_title" value="{{ old('owner_title', 'Direktur Utama') }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:col-span-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Tanggal Mulai Efektif <span class="text-rose-500">*</span></label>
                                <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2.5">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Tanggal Berakhir (Kosongkan jika Karyawan Tetap)</label>
                                <input type="date" id="endDateInput" name="end_date" value="{{ old('end_date', $isInternship ? date('Y-m-d', strtotime('+3 months')) : date('Y-m-d', strtotime('+1 year'))) }}" class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2.5">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Gaji Pokok / Uang Saku Bulanan <span class="text-rose-500">*</span></label>
                            <input type="text" name="stipend_or_salary" value="{{ old('stipend_or_salary', old('salary_offered', $application->job->salary ?? 'Rp 5.000.000 / bulan')) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2.5" placeholder="Misal: Rp 4.500.000 (Sesuai UMK 2026)">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Pasal & Ketentuan Perjanjian Legal Lengkap (Legal Terms & Clauses) <span class="text-rose-500">*</span></label>
                            <textarea id="termsContentTextarea" name="terms_content" rows="16" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-indigo-500 focus:border-indigo-500 font-mono p-4 leading-relaxed">{{ old('terms_content', $defaultTerms) }}</textarea>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.applications.show', $application) }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-xl border border-slate-300 dark:border-slate-600 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs rounded-xl shadow-2xs transition border border-indigo-600 flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane text-amber-300"></i> Terbitkan & Kirim Ke Kandidat
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const benefitsClause = {!! json_encode($benefitsClause) !!};
            const agreementData = {
                employment_contract: {
                    badge: '💼 Mode Kontrak Kerja (PKWT)',
                    title: 'Surat Perjanjian Kerja Waktu Tertentu (PKWT / Kontrak Kerja)',
                    prefix: 'SPK/',
                    terms: `PASAL 1: KETENTUAN HUBUNGAN KERJA & JANGKA WAKTU (PKWT)
1. Pihak Pertama mempekerjakan Pihak Kedua sebagai Karyawan Waktu Tertentu (PKWT) untuk posisi dan divisi yang tercantum pada perjanjian ini.
2. Perjanjian kerja ini berlaku terhitung sejak Tanggal Mulai Efektif sampai dengan Tanggal Berakhir yang disepakati bersama.
3. Hubungan kerja ini tunduk pada Undang-Undang Ketenagakerjaan No. 13 Tahun 2003 jo. UU No. 6 Tahun 2023 (Cipta Kerja) dan Peraturan Pemerintah No. 35 Tahun 2021.

PASAL 2: HAK GAJI, BPJS, THR & UANG KOMPENSASI PKWT
1. Pihak Pertama membayarkan Gaji Pokok dan Tunjangan bulanan kepada Pihak Kedua sebesar nominal yang disepakati setiap tanggal penggajian resmi.
2. Pihak Pertama mendaftarkan Pihak Kedua dalam program BPJS Ketenagakerjaan (JKK, JKM, JHT, JKP) dan BPJS Kesehatan sesuai ketentuan perundang-undangan.
3. Pihak Kedua berhak atas Tunjangan Hari Raya (THR) Keagamaan secara proporsional sesuai masa kerja yang telah dijalani.
4. Di akhir masa kontrak yang telah selesai secara penuh, Pihak Kedua berhak menerima Uang Kompensasi PKWT sesuai masa kerja berdasarkan Pasal 15 PP No. 35 Tahun 2021.

PASAL 3: WAKTU KERJA, ISTIRAHAT & KERJA LEMBUR
1. Waktu kerja adalah 8 (delapan) jam per hari atau 40 (empat puluh) jam per minggu (Senin s/d Jumat) dengan 1 (satu) jam waktu istirahat.
2. Kelebihan jam kerja atas instruksi resmi Atasan diperhitungkan sebagai Kerja Lembur sesuai ketentuan upah lembur resmi.

PASAL 4: HAK CUTI TAHUNAN, CUTI SAKIT & IZIN RESMI
1. Hak Cuti Tahunan: Pihak Kedua berhak atas Cuti Tahunan sebanyak {{ $annualQuota }} hari kerja setelah memiliki masa kerja 12 (dua belas) bulan berturut-turut, atau cuti proporsional yang diatur dalam Peraturan Perusahaan.
2. Cuti Sakit: Pihak Kedua yang tidak dapat bekerja karena sakit berhak atas istirahat dengan tetap menerima upah penuh, wajib menyerahkan Surat Keterangan Dokter resmi paling lambat 1x24 jam.
3. Cuti Khusus / Izin Resmi dengan Upah Penuh (Paid Leave) diberikan untuk:
   a. Pernikahan Pihak Kedua: 3 (tiga) hari kerja.
   b. Pernikahan anak Pihak Kedua: 2 (dua) hari kerja.
   c. Khitanan / Pembaptisan anak: 2 (dua) hari kerja.
   d. Istri melahirkan atau keguguran kandungan: 2 (dua) hari kerja.
   e. Suami/Istri, Orang Tua/Mertua, atau Anak Pihak Kedua meninggal dunia: 2 (dua) hari kerja.
   f. Anggota keluarga dalam satu rumah meninggal dunia: 1 (satu) hari kerja.
4. Cuti Melahirkan: Karyawati berhak atas istirahat melahirkan selama 1,5 bulan sebelum dan 1,5 bulan sesudah melahirkan dengan upah penuh sesuai surat keterangan dokter kandungan/bidan.

PASAL 5: KERAHASIAAN INFORMASI (NDA) & HAK KEKAYAAN INTELEKTUAL (HAKI)
1. Pihak Kedua wajib menjaga kerahasiaan seluruh Data Perusahaan, Source Code, Database, Strategi Bisnis, Dokumen Keuangan, dan Rahasia Dagang milik Pihak Pertama selama maupun setelah hubungan kerja berakhir.
2. Seluruh hasil kerja, penemuan, sistem, modul program, desain grafis, dan inovasi yang diciptakan Pihak Kedua selama masa kerja sepenuhnya merupakan Hak Kekayaan Intelektual eksklusif milik Pihak Pertama.

PASAL 6: TATA TERTIB, DISIPLIN & SANKSI
1. Pihak Kedua wajib mematuhi seluruh Standard Operating Procedure (SOP), Kode Etik Perusahaan, serta instruksi kerja Atasan.
2. Pelanggaran terhadap disiplin dan tata tertib kerja dapat dikenakan sanksi bertingkat berupa: Surat Peringatan Pertama (SP 1), Surat Peringatan Kedua (SP 2), atau Surat Peringatan Ketiga (SP 3) / Pemutusan Hubungan Kerja.

PASAL 7: PENGAKHIRAN KONTRAK, UANG KOMPENSASI PKWT & GANTI RUGI
1. Hubungan kerja berakhir demi hukum pada saat jangka waktu PKWT telah selesai.
2. Hak Uang Kompensasi PKWT: Di akhir masa kontrak kerja, Pihak Pertama wajib membayarkan Uang Kompensasi PKWT dengan perhitungan proporsional: (Masa Kerja / 12) x 1 (satu) bulan upah penuh, sesuai ketentuan Pasal 15 s/d 17 PP No. 35 Tahun 2021.
3. Pemutusan Sebelum Waktu (Early Termination): Apabila salah satu pihak mengakhiri hubungan kerja sebelum berakhirnya jangka waktu tanpa alasan pelanggaran berat, pihak yang mengakhiri wajib membayar ganti rugi sebesar sisa upah masa kontrak kepada pihak lainnya sesuai PP 35/2021.

PASAL 8: PENYELESAIAN PERSELISIHAN
1. Segala perselisihan yang timbul diselesaikan secara musyawarah untuk mufakat (Bipartit).
2. Apabila mufakat tidak tercapai, perselisihan diselesaikan melalui mekanisme mediasi Dinas Tenaga Kerja dan Pengadilan Hubungan Industrial (PHI) sesuai Hukum Republik Indonesia.` + benefitsClause
                },
                permanent_contract: {
                    badge: '🏢 Mode Karyawan Tetap (PKWTT)',
                    title: 'Surat Perjanjian Kerja Waktu Tidak Tertentu (PKWTT / Karyawan Tetap)',
                    prefix: 'SPK-TETAP/',
                    terms: `PASAL 1: PENGANGKATAN KARYAWAN TETAP & MASA PERCOBAAN (PROBATION)
1. Pihak Pertama mengangkat Pihak Kedua sebagai Karyawan Waktu Tidak Tertentu (PKWTT / Karyawan Tetap) untuk posisi yang ditentukan.
2. Pihak Kedua menjalani Masa Percobaan Kerja (Probation) selama maksimal 3 (tiga) bulan. Setelah lulus masa percobaan, Pihak Kedua memperoleh Surat Keputusan (SK) Pengangkatan Karyawan Tetap.

PASAL 2: HAK GAJI POKOK, TUNJANGAN, BPJS KESEHATAN & KETENAGAKERJAAN
1. Pihak Pertama memberikan Hak Gaji Pokok, Tunjangan Jabatan, dan Tunjangan Operasional bulanan yang ditransfer tepat waktu setiap bulan.
2. Pihak Pertama mengikutsertakan Pihak Kedua dalam program BPJS Kesehatan serta BPJS Ketenagakerjaan lengkap (JKK, JKM, JHT, JP / Jaminan Pensiun, dan JKP).
3. Pihak Kedua berhak menerima Tunjangan Hari Raya (THR) Keagamaan sebesar 1 (satu) bulan upah penuh setiap tahun.

PASAL 3: WAKTU KERJA, KEHADIRAN & HAK ISTIRAHAT
1. Jam kerja resmi adalah 40 (empat puluh) jam per minggu dengan sistem 5 (lima) hari kerja (Senin s/d Jumat, pukul 08.30 - 17.30 WIB termasuk 1 jam istirahat).
2. Pihak Kedua wajib mencatatkan presensi kehadiran harian melalui sistem absensi digital perusahaan.

PASAL 4: HAK CUTI TAHUNAN, CUTI SAKIT, CUTI MELAHIRKAN & IZIN RESMI
1. Cuti Tahunan: Pihak Kedua berhak atas {{ $permanentQuota }} hari kerja Cuti Tahunan berbayar setelah bekerja 1 (satu) tahun terus-menerus.
2. Cuti Sakit Berkepanjangan: Apabila Pihak Kedua sakit berkepanjangan berdasarkan surat tim dokter, pembayaran upah dilakukan sesuai skema perlindungan Pasal 93 UU Ketenagakerjaan (100% 4 bulan pertama, 75% 4 bulan kedua, 50% 4 bulan ketiga, dan 25% untuk bulan selanjutnya).
3. Izin Khusus Meninggalkan Pekerjaan dengan Upah Penuh (Paid Leave):
   a. Pernikahan karyawan: 3 (tiga) hari kerja.
   b. Pernikahan anak: 2 (dua) hari kerja.
   c. Khitanan / Pembaptisan anak: 2 (dua) hari kerja.
   d. Istri melahirkan atau keguguran kandungan: 2 (dua) hari kerja.
   e. Suami/Istri, Orang Tua/Mertua, atau Anak meninggal dunia: 2 (dua) hari kerja.
   f. Anggota keluarga dalam satu rumah meninggal dunia: 1 (satu) hari kerja.
4. Cuti Melahirkan & Keguguran: Diberikan cuti 3 (tiga) bulan bagi karyawati yang melahirkan dan 1,5 bulan bagi yang mengalami keguguran kandungan dengan upah penuh.

PASAL 5: KERAHASIAAN INFORMASI (NDA), KEAMANAN SIBER & HAKI
1. Pihak Kedua terikat kewajiban Non-Disclosure Agreement (NDA) ketat untuk tidak menyebarluaskan rahasia perusahaan, kode sumber aplikasi, data pelanggan, dan arsip internal.
2. Seluruh Hak Cipta, Paten, Merk, dan Hak Kekayaan Intelektual atas karya yang dibuat dalam hubungan dinas sepenuhnya menjadi milik Pihak Pertama.

PASAL 6: EVALUASI KINERJA (KPI), PROMOSI & PENGEMBANGAN KARIR
1. Pihak Pertama melakukan Evaluasi Kinerja (Performance Appraisal / KPI) berkala minimal 1 (satu) kali dalam setahun untuk pertimbangan penyesuaian gaji, bonus kinerja, dan promosi jenjang karir.
2. Pihak Pertama menyediakan program pelatihan dan peningkatan kompetensi profesional bagi Pihak Kedua.

PASAL 7: PEMUTUSAN HUBUNGAN KERJA (PHK), UANG PESANGON & HAK FINANSIAL
1. Pemutusan Hubungan Kerja (PHK) dilaksanakan berdasarkan ketentuan UU Ketenagakerjaan No. 13 Tahun 2003 jo. UU No. 6 Tahun 2023 dan Peraturan Pemerintah No. 35 Tahun 2021.
2. Hak Kompensasi PHK bagi Karyawan Tetap: Dalam hal terjadi PHK oleh Pihak Pertama, Pihak Kedua berhak menerima hak finansial sesuai regulasi ketenagakerjaan yang meliputi:
   a. Uang Pesangon (UP) yang dihitung berdasarkan masa kerja riil (Pasal 40 ayat 2 PP 35/2021).
   b. Uang Penghargaan Masa Kerja (UPMK) bagi yang memiliki masa kerja 3 (tiga) tahun atau lebih (Pasal 40 ayat 3 PP 35/2021).
   c. Uang Penggantian Hak (UPH) meliputi pembayaran sisa Cuti Tahunan yang belum gugur dan ongkos kepulangan.
3. Pengunduran Diri Sukarela (Resign): Pihak Kedua wajib mengajukan surat permohonan tertulis minimal 30 (tiga puluh) hari kalender sebelumnya (one month notice) dan menyelesaikan serah terima tugas (handover). Karyawan yang resign berhak atas Uang Penggantian Hak (UPH) dan Uang Pisah sesuai peraturan yang berlaku.

PASAL 8: HUKUM YANG BERLAKU & PENYELESAIAN SENGKETA
1. Perjanjian ini tunduk pada Hukum Ketenagakerjaan Negara Republik Indonesia.
2. Segala perselisihan diselesaikan melalui forum musyawarah mufakat, Mediasi Tripartit Disnaker, hingga Pengadilan Hubungan Industrial (PHI).` + benefitsClause
                },
                remote_contract: {
                    badge: '🏠 Mode Kerja Remote / WFH',
                    title: 'Surat Perjanjian Kerja Remote / Work From Home (Remote Work & NDA)',
                    prefix: 'SPK-REMOTE/',
                    terms: `PASAL 1: KETENTUAN HUBUNGAN KERJA REMOTE & LOKASI DOMISILI
1. Pihak Pertama mempekerjakan Pihak Kedua untuk melaksanakan tugas dan tanggung jawab pekerjaan secara Jarak Jauh (Remote Work / Work From Home / WFH) dari lokasi domisili resmi Pihak Kedua.
2. Pihak Kedua wajib memastikan ketersediaan sarana kelistrikan, ruang kerja kondusif, dan konektivitas jaringan internet yang stabil.

PASAL 2: GAJI, TUNJANGAN INTERNET, BPJS & THR KEAGAMAAN
1. Pihak Pertama membayarkan Gaji Pokok dan Tunjangan Operasional Internet/Komunikasi (Remote Allowance) bulanan kepada Pihak Kedua.
2. Pihak Pertama mengikutsertakan Pihak Kedua dalam program BPJS Ketenagakerjaan (JKK, JKM, JHT, JKP) dan BPJS Kesehatan sesuai undang-undang.
3. Pihak Kedua berhak menerima Tunjangan Hari Raya (THR) Keagamaan sesuai peraturan perundang-undangan ketenagakerjaan.

PASAL 3: JAM KERJA INTI (CORE HOURS), KETERHUBUNGAN & PRESENSI
1. Pihak Kedua wajib aktif dan responsif pada saluran komunikasi resmi perusahaan (Slack/Teams/Google Meet/Email) selama Jam Kerja Inti (Core Working Hours: pukul 09.00 - 17.00 WIB).
2. Pihak Kedua melakukan pencatatan absensi harian secara digital melalui sistem presensi TalentFlow.

PASAL 4: HAK CUTI TAHUNAN, CUTI SAKIT, HAK UNPLUGGED & CUTI KHUSUS
1. Cuti Tahunan: Pihak Kedua berhak atas {{ $annualQuota }} hari kerja Cuti Tahunan berbayar per tahun. Selama cuti, Pihak Kedua berhak atas Right to Disconnect (tidak diganggu urusan operasional).
2. Cuti Sakit: Pihak Kedua yang sakit berhak istirahat dengan tetap menerima upah penuh dengan menyerahkan Surat Keterangan Dokter paling lambat 1x24 jam.
3. Cuti Khusus / Izin Berbayar (Paid Leave) diberikan untuk:
   a. Pernikahan karyawan: 3 (tiga) hari kerja.
   b. Pernikahan anak: 2 (dua) hari kerja.
   c. Khitanan / Baptis anak: 2 (dua) hari kerja.
   d. Istri melahirkan / keguguran: 2 (dua) hari kerja.
   e. Duka cita keluarga inti (Suami/Istri/Anak/Orang Tua): 2 (dua) hari kerja.
4. Cuti Melahirkan: Karyawati berhak atas cuti melahirkan 3 (tiga) bulan dengan upah penuh sesuai surat dokter kandungan/bidan.

PASAL 5: KEAMANAN SIBER, VPN & NON-DISCLOSURE AGREEMENT (NDA STRICT)
1. Pihak Kedua wajib menggunakan jaringan terenkripsi Virtual Private Network (VPN Perusahaan) saat mengakses database, server produksi, repositori Git, dan dashboard internal perusahaan.
2. Pihak Kedua dilarang keras menggunakan Wi-Fi publik tanpa VPN atau meminjamkan perangkat kerja kepada pihak ketiga manapun.
3. Seluruh basis data, arsitektur kode, data nasabah/pengguna, dan strategi bisnis bersifat RAHASIA NEGARA PERUSAHAAN (STRICT NDA) yang dilindungi sanksi pidana UU ITE.

PASAL 6: EVALUASI OUTPUT KERJA (DELIVERABLES) & TATA TERTIB
1. Penilaian kinerja karyawan remote difokuskan pada ketercapaian target, kualitas hasil kerja (deliverables), ketepatan deadline, dan komunikasi asinkron yang efektif.
2. Ketidakaktifan tanpa kabar (ghosting) selama lebih dari 3 (tiga) hari kerja berturut-turut merupakan pelanggaran berat dan dapat dikenakan Surat Peringatan (SP) hingga PHK.

PASAL 7: PENGAKHIRAN KERJA, PENGEMBALIAN ASET & HUKUM YANG BERLAKU
1. Pengakhiran hubungan kerja dilaksanakan berdasarkan ketentuan perundang-undangan ketenagakerjaan Republik Indonesia.
2. Saat hubungan kerja berakhir, Pihak Kedua wajib menghapus seluruh data lokal perusahaan dan mengembalikan inventaris perusahaan dalam waktu maksimal 7 (tujuh) hari kerja.
3. Segala perselisihan diselesaikan secara musyawarah untuk mufakat atau mediasi ketenagakerjaan.` + benefitsClause
                },
                hybrid_contract: {
                    badge: '🔀 Mode Kerja Hybrid',
                    title: 'Surat Perjanjian Kerja Hybrid (Hybrid Work & Flexible Policy)',
                    prefix: 'SPK-HYBRID/',
                    terms: `PASAL 1: KETENTUAN HUBUNGAN KERJA HYBRID & JADWAL KERJA
1. Pihak Pertama menerima Pihak Kedua untuk bekerja dengan skema Kerja Hybrid (kombinasi Work From Office / WFO di kantor perusahaan dan Work From Home / WFH dari domisili).
2. Pembagian jadwal giliran hari WFO dan WFH diatur secara fleksibel oleh Atasan Langsung (Direct Manager) atau jadwal rotasi divisi.

PASAL 2: GAJI, TUNJANGAN TRANSPORT-KOMUNIKASI, BPJS & THR
1. Pihak Pertama memberikan Hak Gaji Pokok, Tunjangan Kehadiran Transport/Makan WFO, dan Tunjangan Komunikasi WFH setiap bulan.
2. Pihak Kedua didaftarkan dalam program BPJS Ketenagakerjaan dan BPJS Kesehatan serta berhak atas THR Keagamaan tahunan.

PASAL 3: KETENTUAN KEHADIRAN KANTOR (WFO) & KETENTUAN FLEKSIBEL (WFH)
1. Pada hari kerja WFO: Pihak Kedua wajib hadir secara fisik di kantor, mematuhi jam kerja kantor (08.30 - 17.30 WIB), dan berkoordinasi langsung dengan tim.
2. Pada hari kerja WFH: Pihak Kedua wajib standby dan responsif pada saluran komunikasi resmi selama Jam Kerja Inti (09.00 - 17.00 WIB) serta menyelesaikan target harian.

PASAL 4: HAK CUTI TAHUNAN, CUTI SAKIT & HAK IZIN RESMI
1. Cuti Tahunan: Pihak Kedua berhak atas hak Cuti Tahunan {{ $annualQuota }} hari kerja per tahun yang dapat diajukan baik pada hari jadwal WFO maupun WFH.
2. Cuti Sakit: Pihak Kedua berhak atas istirahat sakit berbayar penuh dengan melampirkan Surat Keterangan Dokter resmi.
3. Cuti Khusus & Cuti Melahirkan:
   a. Cuti Melahirkan 3 (tiga) bulan upah penuh / Cuti Keguguran 1,5 bulan.
   b. Izin Menikah: 3 (tiga) hari kerja.
   c. Izin Istri Melahirkan: 2 (dua) hari kerja.
   d. Izin Duka Cita Keluarga Inti: 2 (dua) hari kerja.

PASAL 5: KEAMANAN SIBER, VPN & KERAHASIAAN DATA PERUSAHAAN (NDA)
1. Pihak Kedua wajib mengaktifkan VPN resmi perusahaan saat bekerja pada hari WFH dan menjaga keamanan data internal dari kebocoran pihak ketiga.
2. Seluruh sistem, file pekerjaan, dan ide produk merupakan Hak Kekayaan Intelektual (HAKI) eksklusif milik Pihak Pertama.

PASAL 6: TATA TERTIB, KODE ETIK & DISIPLIN KERJA
1. Pihak Kedua wajib menjaga profesionalisme kerja, mematuhi SOP kerja, dan memenuhi Key Performance Indicators (KPI) yang ditetapkan.
2. Pelanggaran terhadap tata tertib dapat dikenakan sanksi bertingkat (SP 1, SP 2, SP 3).

PASAL 7: PENGAKHIRAN HUBUNGAN KERJA & PENYELESAIAN SENGKETA
1. Pengakhiran hubungan kerja dilaksanakan berdasarkan peraturan perundang-undangan ketenagakerjaan yang berlaku di Republik Indonesia.
2. Setiap sengketa diselesaikan secara kekeluargaan musyawarah mufakat atau mediasi ketenagakerjaan.` + benefitsClause
                },
                internship_agreement: {
                    badge: '🎓 Mode Perjanjian Magang',
                    title: 'Surat Perjanjian Magang Kerja (Internship Agreement)',
                    prefix: 'SPM/',
                    terms: `PASAL 1: STATUS PROGRAM MAGANG & JANGKA WAKTU
1. Pihak Pertama menerima Pihak Kedua sebagai Peserta Magang (Internship) untuk posisi dan divisi yang tercantum dalam perjanjian ini.
2. Program magang berlangsung terhitung mulai Tanggal Mulai hingga Tanggal Selesai yang disepakati bersama.
3. Hubungan ini merupakan Hubungan Pelatihan Kerja Praktek / Magang Akademik Berkelanjutan dan bukan hubungan kerja tetap ketenagakerjaan, mengacu pada Permenaker No. 6 Tahun 2020 tentang Penyelenggaraan Pemagangan di Dalam Negeri.

PASAL 2: HAK UANG SAKU (STIPEND), PERLINDUNGAN BPJS, MENTORSHIP & SERTIFIKAT MAGANG
1. Pihak Kedua berhak menerima Uang Saku Insentif / Uang Transport & Makan (Stipend Magang) bulanan sebesar nominal yang disepakati pada perjanjian ini.
2. Perlindungan Jaminan Sosial (BPJS): Pihak Pertama mengikutsertakan Pihak Kedua dalam program perlindungan BPJS Ketenagakerjaan (Jaminan Kecelakaan Kerja / JKK dan Jaminan Kematian / JKM) serta jaminan BPJS Kesehatan selama masa program magang berlangsung sesuai regulasi yang berlaku.
3. Pihak Pertama menunjuk Mentor Pembimbing Profesional untuk memberikan bimbingan teknis, transfer pengetahuan, dan evaluasi kompetensi secara berkala.
4. Pihak Kedua yang berhasil menyelesaikan program berhak menerima:
   a. Sertifikat Kelulusan Magang Resmi Ber-QR Code.
   b. Transkrip Evaluasi Nilai Akademik Magang (5 Kriteria Evaluasi).
   c. Surat Rekomendasi Kerja / Referensi Karir dari Manajemen.

PASAL 3: JAM PELATIHAN MAGANG & WAKTU ISTIRAHAT
1. Waktu pelaksanaan magang adalah maksimal 8 (delapan) jam per hari atau 40 (empat puluh) jam per minggu (Senin s/d Jumat) dan tidak diperkenankan melebihi batas waktu magang yang wajar.
2. Pihak Kedua berhak atas waktu istirahat minimal 1 (satu) jam per hari kerja serta libur pada hari libur resmi nasional.

PASAL 4: HAK IZIN AKADEMIK, UJIAN KAMPUS, CUTI SAKIT & KETENTUAN DISPENSASI
1. Izin Akademik: Pihak Pertama memberikan izin dispensasi resmi kepada Pihak Kedua untuk keperluan akademik institusi pendidikan (seperti: Sidang Skripsi/Tugas Akhir, Ujian Tengah/Akhir Semester, Bimbingan Dosen, atau Wisuda) dengan pemberitahuan dan surat pengantar kampus.
2. Izin Sakit: Pihak Kedua yang berhalangan hadir karena sakit berhak beristirahat dengan memberitahukan kepada Mentor dan melampirkan Surat Keterangan Dokter atau surat pemberitahuan.
3. Izin Keperluan Mendesak / Duka Cita: Diberikan izin dispensasi maksimal hingga {{ $internshipMaxDays }} hari kerja untuk keperluan mendesak keluarga atau duka cita atas persetujuan tertulis Mentor/HRD.
4. Batas Maksimal Izin & Pemotongan Uang Saku: Batas toleransi akumulasi izin/dispensasi yang diberikan adalah maksimal {{ $internshipMaxDays }} hari kerja. Apabila Pihak Kedua tidak hadir atau mengajukan izin melebihi batas {{ $internshipMaxDays }} hari kerja tersebut, maka akan diberlakukan pemotongan uang saku (stipend) secara proporsional (prorata) sesuai jumlah hari ketidakhadiran berlebih.

PASAL 5: HAK KEKAYAAN INTELEKTUAL (HAKI) & KERAHASIAAN DATA (NDA STRICT)
1. Seluruh hasil karya, source code, desain antarmuka (UI/UX), dokumen teknis, dan algoritma yang dibuat Pihak Kedua selama masa magang sepenuhnya menjadi Hak Milik Intelektual Pihak Pertama.
2. Pihak Kedua dilarang keras menyalin, menyebarluaskan, atau mempublikasikan data rahasia internal, data pengguna, kredensial server, atau source code perusahaan ke publik tanpa izin tertulis dari Pihak Pertama.

PASAL 6: TATA TERTIB, KODE ETIK & LOGBOOK AKTIVITAS
1. Pihak Kedua wajib mematuhi jam pelatihan, berpenampilan rapi dan sopan, menjaga etika profesionalisme, serta mengisi Logbook Aktivitas Harian Magang secara tertib.
2. Mentor Pembimbing melakukan evaluasi berkala atas 5 Kriteria (Kedisiplinan, Keahlian Teknis, Komunikasi & Kerjasama, Inisiatif Problem Solving, dan Etika Kerja).

PASAL 7: PENGHENTIAN PROGRAM MAGANG & REKRUTMEN LANJUTAN
1. Program magang berakhir otomatis pada Tanggal Selesai yang ditentukan.
2. Pihak Pertama berhak menghentikan magang secara sepihak apabila Pihak Kedua melakukan pelanggaran berat, tindakan kriminal, absen tanpa keterangan lebih dari 30 (tiga puluh) hari kalender (1 bulan), atau membocorkan rahasia perusahaan.
3. Peserta magang dengan pencapaian predikat istimewa (Grade A) berhak diprioritaskan dalam program Fast-Track Hiring menjadi Karyawan Perusahaan.` + benefitsClause
                }
            };

            const docSuffix = "{{ date('Y/m/') }}APP{{ sprintf('%04d', $application->id) }}-U{{ sprintf('%04d', $application->user_id) }}";
            const selectEl = document.getElementById('agreementTypeSelect');
            const badgeEl = document.getElementById('agreementModeBadge');
            const contractNumberEl = document.getElementById('contractNumberInput');
            const titleEl = document.getElementById('titleInput');
            const termsEl = document.getElementById('termsContentTextarea');

            function updateAgreementForm(type, isInit = false) {
                const data = agreementData[type];
                if (!data) return;

                badgeEl.innerText = data.badge;
                contractNumberEl.value = data.prefix + docSuffix;

                if (!isInit) {
                    titleEl.value = data.title;
                    termsEl.value = data.terms;
                }
            }

            selectEl.addEventListener('change', function () {
                updateAgreementForm(this.value, false);
            });

            // Run on initial load to ensure consistency
            updateAgreementForm(selectEl.value, true);
        });
    </script>
</x-app-layout>
