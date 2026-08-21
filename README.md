# 🚀 TalentFlow - Enterprise Applicant Tracking System (ATS) & Recruitment Ecosystem

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.style=for-the-badge)](LICENSE)

> **TalentFlow** adalah platform rekrutmen cerdas & Applicant Tracking System (ATS) berbasis AI & Data UMK 2026 yang dirancang untuk menghubungkan perusahaan, tim HR, dan talenta terbaik secara efisien, transparan, terstruktur, dan aman.

---

## 🌟 Fitur Utama Platform (Core Features)

### 👥 1. Multi-Tenancy & Multi-Role Security (RBAC)
* **Super Admin**: Modul pengawasan platform global, manajemen pengguna, verifikasi pendaftaran perusahaan, audit log aktivitas, serta pengaturan SMTP & SEO master.
* **Company Owner (Pemilik Perusahaan)**: Pengelolaan profil perusahaan, vault dokumen NIB/SIUP legal, manajemen cabang & anak perusahaan (`Multi-Branch Management`), serta pengelolaan tim HR internal.
* **HR Recruiter (Tim Rekruter)**: Pembuatan lowongan kerja, pengelolaan pipeline seleksi kandidat, pembuatan tes online, wawancara, dan pengiriman Surat Penawaran Kerja (Offer Letter PDF).
* **Candidate (Pelamar)**: Pencarian lowongan cerdas, otomatisasi pengisian profil, generator CV PDF (format ATS & Creative), serta Vault Dokumen Pendukung otomatis (*Ijazah, Transkrip, KTP, SKCK, Sertifikat, Portofolio*).

---

### 📈 2. Smart UMK/UMR 2026 Detector & Salary Benchmark Engine
* **Data UMK 2026 Resmi**: 142 data UMK/UMP 2026 resmi berbasis Surat Keputusan (SK) Gubernur untuk seluruh provinsi & kabupaten/kota se-Indonesia.
* **Auto-Detector Widget**: Deteksi otomatis nilai UMK 2026 saat HR membuat atau mengedit lowongan kerja.
* **Salary Benchmark Calculator**: Kalkulator estimasi gaji pasar berbasis Pengalaman Kerja (Fresh Graduate, Junior, Mid, Senior) dan *Skill Value Bonus Engine* (+8% hingga +40% premium untuk keahlian spesialis seperti AWS, React, Docker, AI, Kubernetes).

---

### 🏢 3. Multi-Branch & Subsidiary Management
* Pengelolaan cabang kantor dan anak perusahaan (*Kantor Pusat, Cabang Jakarta, Surabaya, Bandung, Bali, dll.*).
* Penugasan lokasi kerja dan penyesuaian UMK regional per cabang secara otomatis.

---

### 📝 4. Job Test Builder & Automated Grading System
* Pembuatan bank soal Pilihan Ganda (MCQ) ujian online dengan batas durasi dan KKM (*Passing Score*).
* Pengunggahan & evaluasi berkas PDF studi kasus rekrutmen.
* Generator Laporan Hasil Ujian Tes Kualifikasi Kandidat.

---

### 📊 5. HR Analytics & Recruitment KPI Dashboard
* Visualisasi grafik *Chart.js* interaktif di dasbor HR:
  * *Time-to-Hire & Conversion Rate* antar tahapan pipeline seleksi.
  * Demografi Pelamar berbasis Tingkat Pendidikan, Gender, & Kota.
  * Ranking Lowongan Terpopuler berdasarkan jumlah pelamar.

---

### ✉️ 6. Automated Email Gateway & In-App Live Chat
* **Email Gateway**: Pengiriman email otomatis berdesain modern saat kandidat lolos screening, diundang wawancara, atau menerima Offer Letter PDF.
* **In-App Live Chat**: Fitur berkirim pesan langsung 2 arah antara HR dan kandidat di dalam aplikasi.

---

### 📜 7. Verification & Anti-IDOR Security Trait
* **URL ID Encryption (`HasEncryptedId`)**: Enkripsi ID data sensitif pada URL untuk mencegah serangan IDOR (*Insecure Direct Object References*).
* **Audit Logs Activity**: Pencatatan jejak digital seluruh tindakan rekrutmen internal demi transparansi & akuntabilitas.
* **Company Role Request & Verification**: Alur pendaftaran perusahaan baru dengan verifikasi dokumen NIB/SIUP oleh Super Admin.

---

## 🛠️ Tech Stack & Dependencies

* **Framework**: Laravel 11.x (PHP 8.2+)
* **Database**: MySQL 8.0+ / MariaDB
* **Frontend**: Blade Templating, Alpine.js, Tailwind CSS, FontAwesome 6, Chart.js
* **PDF Engine**: DomPDF (`barryvdh/laravel-dompdf`)
* **Excel Engine**: Laravel Excel (`maatwebsite/excel`)
* **Role Permission**: Spatie Laravel-Permission (`spatie/laravel-permission`)

---

## 🚀 Panduan Instalasi (Installation Guide)

### 1. Clone Repository
```bash
git clone https://github.com/your-repo/talentflow.git
cd talentflow
```

### 2. Install Dependencies
```bash
composer install
npm install && npm run build
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```
Edit berkas `.env` dan atur koneksi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_karir
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi Database & Seeding Data UMK 2026
```bash
php artisan migrate --seed
```

### 5. Buat Storage Symlink
```bash
php artisan storage:link
```

### 6. Bersihkan Cache & Jalankan Server Lokal
```bash
php artisan optimize:clear
php artisan serve
```
Akses aplikasi melalui browser di **`http://localhost:8000`**.

---

## 🔒 Lisensi (License)

Proyek ini dikembangkan di bawah lisensi **MIT License**.
