# 🚀 TalentFlow (Career Hub) - Enterprise ATS, Internship & HR Payroll Ecosystem

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Swagger](https://img.shields.io/badge/OpenAPI-3.0_Swagger-85EA2D?style=for-the-badge&logo=swagger&logoColor=black)](https://swagger.io)
[![Sanctum](https://img.shields.io/badge/Auth-Laravel_Sanctum-red?style=for-the-badge&logo=laravel)](https://laravel.com/docs/sanctum)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

> **TalentFlow (Career Hub)** adalah platform ekosistem karir komprehensif yang mengintegrasikan **Applicant Tracking System (ATS)** berbasis AI, **Sistem Manajemen Magang (Internship Lifecycle)**, **Employee Self-Service (ESS) & HR Payroll Module**, serta **Salary Benchmark UMK 2026** dalam satu sistem terpusat, aman, dan modular.

---

## 📑 Daftar Isi (Table of Contents)
- [🌟 Fitur Utama Platform](#-fitur-utama-platform)
  - [1. Multi-Tenancy & Multi-Role Security (RBAC)](#1-multi-tenancy--multi-role-security-rbac)
  - [2. Rekrutmen & ATS Pipeline Terpadu](#2-rekrutmen--ats-pipeline-terpadu)
  - [3. Siklus Hidup Magang (Internship Lifecycle Management)](#3-siklus-hidup-magang-internship-lifecycle-management)
  - [4. Employee Self-Service (ESS) & Kepegawaian](#4-employee-self-service-ess--kepegawaian)
  - [5. HR Payroll & E-Slip Gaji Otomatis](#5-hr-payroll--e-slip-gaji-otomatis)
  - [6. Salary Benchmark & Detektor UMK 2026](#6-salary-benchmark--detektor-umk-2026)
  - [7. Keamanan, Anti-IDOR & Tata Kelola Perusahaan](#7-keamanan-anti-idor--tata-kelola-perusahaan)
- [🌐 RESTful API & Swagger / OpenAPI 3.0](#-restful-api--swagger--openapi-30)
- [🛠️ Tech Stack & Dependencies](#️-tech-stack--dependencies)
- [🚀 Panduan Instalasi (Installation Guide)](#-panduan-instalasi-installation-guide)
- [📖 Dokumentasi Endpoint API](#-dokumentasi-endpoint-api)
- [🔒 Lisensi (License)](#-lisensi-license)

---

## 🌟 Fitur Utama Platform

### 1. Multi-Tenancy & Multi-Role Security (RBAC)
* **Super Admin**: Monitoring global, manajemen pengguna, verifikasi legalitas NIB/SIUP perusahaan, audit log aktivitas, pengumuman sistem, dan manajemen blacklist.
* **Company Owner**: Pengelolaan profil perusahaan, brankas dokumen legalitas, manajemen cabang (`Multi-Branch`), hierarki Co-Owner (Two-Tier Approval), dan pengelolaan tim HR.
* **HR / Recruiter**: Pembuatan lowongan kerja, tracking pelamar di pipeline ATS, tes online MCQ & studi kasus, penjadwalan interview, penerbitan Offer Letter, monitoring presensi & lembur, serta payroll.
* **Mentor Magang**: Review & approval logbook harian peserta magang, evaluasi kompetensi, permohonan buka kunci logbook, dan rekomendasi uang saku.
* **Karyawan / Pegawai (Employee)**: Portal mandiri (ESS) untuk presensi harian (clock-in/out), pengajuan lembur (overtime), klaim reimbursement operasional, pengajuan cuti, dan riwayat e-slip gaji.
* **Candidate (Pelamar & Pemagang)**: Pencarian lowongan pintar, generator CV ATS, pengisian logbook magang harian, unduh sertifikat ber-QR Code, dan cek slip uang saku.

---

### 2. Rekrutmen & ATS Pipeline Terpadu
* **Smart Job Pipeline**: Tahapan seleksi terstruktur mulai dari *Applied*, *Screening*, *Assessment Test*, *Interview*, *Offering*, hingga *Hired*.
* **Online Assessment Engine**: Pembuatan bank soal Pilihan Ganda (MCQ) otomatis dengan passing grade, batas waktu pengerjaan, dan pengumpulan berkas tugas.
* **Interview Scheduler**: Penjadwalan interview terintegrasi dengan Google Calendar / iCal invite dan notifikasi email kandidat.
* **Automated Offer Letter**: Pembuatan Surat Penawaran Kerja resmi berformat PDF yang dapat langsung disetujui (*accept*) atau ditolak (*decline*) oleh kandidat secara daring.
* **Onboarding Checklist**: Verifikasi dokumen administrasi awal (KTP, NPWP, Rekening Bank, Pakta Integritas) sebelum mulai bekerja/magang.

---

### 3. Siklus Hidup Magang (Internship Lifecycle Management)
* **Logbook Harian Interaktif**: Pemagang mencatat kegiatan, tantangan, dan bukti hasil kerja setiap hari kerja.
* **Aturan Kunci Logbook (Lock Mechanism)**: Fitur lock otomatis untuk mencegah pengisian mundur sembarangan, dilengkapi sistem permohonan buka kunci (*Unlock Request*) yang harus disetujui Mentor dan HR.
* **Evaluasi & Penilaian Mentor**: Form rubrik penilaian kompetensi magang (Hard Skills, Soft Skills, Disiplin, Inisiatif) untuk syarat kelulusan.
* **Uang Saku Magang (Stipends)**: Rekomendasi uang saku per periode magang, verifikasi rekening, dan generator slip uang saku PDF resmi.
* **E-Sertifikat & Transkrip Nilai Digital**: Penerbitan sertifikat kelulusan magang dengan kode unik dan QR Code publik untuk verifikasi keaslian dokumen tanpa perlu login.
* **Alur Resign / Pengunduran Diri Resmi**: Pengajuan pengunduran diri dengan alasan, unggah surat resmi, persetujuan HR, dan surat keterangan verifikasi publik.

---

### 4. Employee Self-Service (ESS) & Kepegawaian
* **Presensi Mandiri (Attendance Clock-In/Out)**:
  * Check-in & Check-out harian berbasis waktu kerja perusahaan.
  * Deteksi otomatis status kehadiran: `Tepat Waktu (On-Time)` atau `Terlambat (Late)`.
  * Dasbor rekapitulasi kehadiran bulanan, total hari kerja, dan izin.
* **Pengajuan Lembur (Overtime Tracker)**:
  * Pengajuan lembur mandiri dengan tanggal, estimasi jam kerja, dan rincian pekerjaan.
  * Estimasi perhitungan kompensasi lembur otomatis.
  * Alur persetujuan HR (Disetujui / Ditolak / Dibatalkan).
* **Klaim Biaya Operasional (Reimbursement)**:
  * Pengajuan klaim biaya operasional kantor (Transportasi, Konsumsi Lembur, Medis, Perlengkapan Kantor).
  * Upload bukti struk/kuitansi transaksi (JPG, PNG, PDF).
  * Pelacakan status klaim (`pending` ➔ `approved` ➔ `disbursed` / `rejected`).
* **Manajemen Cuti (Leave Management)**:
  * Pengajuan cuti tahunan, cuti sakit, dan izin khusus.
  * Kalkulasi sisa kuota hak cuti tahunan karyawan.
  * Monitoring dan persetujuan terpusat oleh HR.

---

### 5. HR Payroll & E-Slip Gaji Otomatis
* **Auto-Payroll Generator**:
  * HR dapat men-generate gaji bulanan karyawan hanya dengan 1 klik per periode bulan/tahun.
  * Penggabungan otomatis: **Gaji Pokok** + **Tunjangan Tetap/Transport** + **Kompensasi Lembur Disetujui** + **Reimbursement Disetujui**.
  * Pengurangan otomatis potongan resmi (**BPJS Ketenagakerjaan**, **BPJS Kesehatan**, dan **PPh 21**).
* **Siklus Status Penggajian**:
  * `draft` (Perhitungan awal oleh HR).
  * `approved` (Disetujui untuk diproses finance/manajemen).
  * `paid` (Telah ditransfer ke rekening karyawan).
* **E-Slip Gaji Transparan**: Karyawan dapat melihat dan mengunduh slip gaji digital lengkap dengan rincian pendapatan bersih (*Take Home Pay*).

---

### 6. Salary Benchmark & Detektor UMK 2026
* **Database UMK 2026 Resmi**: Memuat 142 data UMK/UMP 2026 resmi seluruh provinsi & kabupaten/kota di Indonesia berdasarkan SK Gubernur terbaru.
* **Auto-Detection Widget**: Pendeteksi UMK otomatis saat HR menyusun lowongan kerja untuk menjamin kepatuhan regulasi ketenagakerjaan.
* **Salary Benchmark Calculator**: Kalkulator perbandingan gaji pasar berbasis level pengalaman (*Fresh Graduate, Junior, Mid, Senior, Lead*) dan *Skill Value Bonus Engine* (+8% s/d +40% untuk keahlian bernilai tinggi seperti AI, Cloud, DevOps, dan Modern Web).

---

### 7. Keamanan, Anti-IDOR & Tata Kelola Perusahaan
* **Anti-IDOR (`HasEncryptedId`)**: Seluruh ID data sensitif dienkripsi pada URL publik untuk melindungi privasi berkas kandidat dan perusahaan.
* **Corporate Two-Tier Approval**: Validasi berlapis akun Co-Owner perusahaan oleh Primary Owner & Super Admin.
* **Pencocokan Nama Kanonikal (Company Matching)**: Normalisasi otomatis nama perseroan (*PT, CV, UD, Inc.*) untuk mencegah duplikasi entitas perusahaan.
* **Audit Trail Log**: Pencatatan riwayat aktivitas penting (login, perubahan data, approval lembur, status payroll) demi akuntabilitas.

---

## 🌐 RESTful API & Swagger / OpenAPI 3.0

Platform ini dilengkapi RESTful API lengkap (96 endpoint terdokumentasi) dengan proteksi **Laravel Sanctum**.

### Akses Dokumentasi Swagger:
* **Interactive Swagger UI**:
  ```text
  http://localhost:8000/api/documentation
  ```
  *(Atau ganti dengan domain/IP server VPS Anda: `http://<domain-server>/api/documentation`)*
* **OpenAPI 3.0 Schema JSON (Untuk integrasi sistem / Postman / Mobile App)**:
  ```text
  http://localhost:8000/docs
  ```

### Autentikasi API:
Gunakan Header Authorization dengan format Bearer Token:
```http
Authorization: Bearer <sanctum_plain_text_token>
Accept: application/json
```

---

## 🛠️ Tech Stack & Dependencies

* **Backend**: Laravel 11.x, PHP 8.2+
* **Database**: MySQL 8.0+ / MariaDB
* **API Documentation**: L5-Swagger (`DarkaOnLine/L5-Swagger`), OpenAPI 3.0 (`zircote/swagger-php`)
* **API Security**: Laravel Sanctum (`laravel/sanctum`)
* **Role & Permission**: Spatie Laravel-Permission (`spatie/laravel-permission`)
* **Frontend UI**: Blade Templating, Tailwind CSS, Alpine.js, FontAwesome 6
* **Data Visualization**: Chart.js
* **Document Engines**:
  * PDF Export: DomPDF (`barryvdh/laravel-dompdf`)
  * Excel Export: Laravel Excel (`maatwebsite/excel`)
  * QR Code Generator: SimpleSoftwareIO QRCode (`simplesoftwareio/simple-qrcode`)

---

## 🚀 Panduan Instalasi (Installation Guide)

### 1. Clone Repository
```bash
git clone https://github.com/iqbalmusyaffa/career-hub.git
cd career-hub
```

### 2. Install Dependencies
```bash
composer install
npm install && npm run build
```

### 3. Konfigurasi File Environment
```bash
cp .env.example .env
php artisan key:generate
```
Sesuaikan konfigurasi database dan URL aplikasi pada berkas `.env`:
```env
APP_NAME="Career Hub"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_karir
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seeding Data
Jalankan migrasi database beserta data awal (roles, admin, data UMK 2026, kategori pekerjaan):
```bash
php artisan migrate --seed
```

### 5. Buat Storage Symlink
```bash
php artisan storage:link
```

### 6. Generate Dokumentasi Swagger
```bash
php artisan l5-swagger:generate
```

### 7. Jalankan Server Aplikasi
```bash
php artisan serve
```
Akses aplikasi melalui browser di **`http://localhost:8000`**.

---

## 📖 Ringkasan Endpoint API Utama

| Modul | Method | Endpoint | Keterangan |
| :--- | :--- | :--- | :--- |
| **Auth** | `POST` | `/api/v1/auth/register` | Pendaftaran akun baru |
| **Auth** | `POST` | `/api/v1/auth/login` | Login & generate token Sanctum |
| **Auth** | `GET` | `/api/v1/auth/me` | Profil pengguna aktif |
| **Public** | `GET` | `/api/v1/jobs` | Daftar lowongan kerja aktif & filter UMK |
| **Public** | `GET` | `/api/v1/categories` | Daftar kategori bidang pekerjaan |
| **Public** | `GET` | `/api/v1/companies` | Daftar profil perusahaan terverifikasi |
| **Pelamar** | `GET` | `/api/v1/candidate/applications` | Daftar status lamaran saya |
| **Pelamar** | `GET` | `/api/v1/candidate/cv` | Data profil CV ATS kandidat |
| **Magang** | `GET` | `/api/v1/candidate/internship/logbooks` | Riwayat logbook harian magang |
| **Magang** | `POST` | `/api/v1/candidate/internship/logbooks` | Submit logbook harian baru |
| **ESS** | `GET` | `/api/v1/employee/attendance` | Status presensi & ringkasan kehadiran hari ini |
| **ESS** | `POST` | `/api/v1/employee/attendance/check-in` | Clock In mandiri karyawan |
| **ESS** | `POST` | `/api/v1/employee/attendance/check-out` | Clock Out mandiri karyawan |
| **ESS** | `GET` | `/api/v1/employee/overtimes` | Riwayat pengajuan lembur |
| **ESS** | `POST` | `/api/v1/employee/overtimes` | Ajukan permohonan lembur |
| **ESS** | `GET` | `/api/v1/employee/reimbursements` | Riwayat klaim biaya operasional |
| **ESS** | `POST` | `/api/v1/employee/reimbursements` | Pengajuan klaim reimbursement & upload struk |
| **ESS** | `GET` | `/api/v1/employee/payrolls` | Daftar e-slip gaji karyawan |
| **Cuti** | `GET` | `/api/v1/leaves` | Riwayat & sisa kuota cuti |
| **Cuti** | `POST` | `/api/v1/leaves` | Ajukan cuti baru |
| **Admin** | `GET` | `/api/v1/admin/employee-attendances` | Monitoring presensi seluruh karyawan |
| **Admin** | `POST` | `/api/v1/admin/employee-overtimes/{id}/status` | Approval / Rejection lembur |
| **Admin** | `POST` | `/api/v1/admin/employee-reimbursements/{id}/status`| Approval / Pencairan reimbursement |
| **Admin** | `POST` | `/api/v1/admin/employee-payrolls/generate` | Generate payroll bulanan otomatis |
| **Admin** | `POST` | `/api/v1/admin/employee-payrolls/{id}/status` | Finalisasi & update status gaji |
| **Admin** | `GET` | `/api/v1/admin/leaves` | Rekapitulasi permohonan cuti |
| **Admin** | `POST` | `/api/v1/admin/leaves/{id}/approve` | Persetujuan cuti oleh HR |

---

## 🔒 Lisensi (License)

Proyek ini dikembangkan di bawah lisensi terbuka [MIT License](LICENSE).
Semua hak kekayaan intelektual kode program dilindungi dan dapat digunakan, dimodifikasi, dan didistribusikan sesuai ketentuan lisensi MIT.
