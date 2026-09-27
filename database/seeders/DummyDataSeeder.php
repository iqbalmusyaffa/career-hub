<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\CandidateProfile;
use App\Models\CompanyProfile;
use App\Models\Interview;
use App\Models\Job;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $candidateRole = Role::firstOrCreate(['name' => 'Candidate']);
        $hrRole = Role::firstOrCreate(['name' => 'HR']);

        // 1. Create Default HR & Company Profiles
        $hrOwner = User::firstOrCreate(
            ['email' => 'hr.official@technova.id'],
            [
                'name' => 'HR Official TechNova',
                'password' => Hash::make('S3cur3#P@ssw0rd!2026'),
            ]
        );
        $hrOwner->assignRole($hrRole);

        $companiesList = [
            ['company_name' => 'PT TechNova Asia Digital', 'industry' => 'Teknologi & Informasi', 'address' => 'Jakarta Selatan', 'is_verified' => true],
            ['company_name' => 'PT GlobalCorp Digital', 'industry' => 'Konsultan IT & Multi Perusahaan', 'address' => 'Bandung', 'is_verified' => true],
            ['company_name' => 'FinServe Digital Indonesia', 'industry' => 'Keuangan & Fintech', 'address' => 'Jakarta Pusat', 'is_verified' => true],
            ['company_name' => 'EduSmart Tech Indonesia', 'industry' => 'Teknologi Pendidikan', 'address' => 'Yogyakarta', 'is_verified' => true],
            ['company_name' => 'AeroLogistics Transport', 'industry' => 'Logistik & Transportasi', 'address' => 'Surabaya', 'is_verified' => true],
            ['company_name' => 'TokoKreatif E-Commerce', 'industry' => 'Ritel & E-Commerce', 'address' => 'Tangerang', 'is_verified' => true],
            ['company_name' => 'PT Mitra Solusi Keuangan', 'industry' => 'Akuntansi & Konsultan Finansial', 'address' => 'Surabaya', 'is_verified' => true],
            ['company_name' => 'PT Global Inovasi Media', 'industry' => 'Pemasaran Digital & Media', 'address' => 'Tangerang', 'is_verified' => true],
            ['company_name' => 'PT Nusantara Distribusi Pro', 'industry' => 'Distribusi & Penjualan B2B', 'address' => 'Jakarta Selatan', 'is_verified' => true],
            ['company_name' => 'PT Global Sarana Utama', 'industry' => 'Manajemen Bisnis & SDM', 'address' => 'Bandung', 'is_verified' => true],
            ['company_name' => 'Studio Desain Kreasi Digital', 'industry' => 'Desain Kreatif & Multimedia', 'address' => 'Jakarta Pusat', 'is_verified' => true],
            ['company_name' => 'PT Multimedia Kreasi Visual', 'industry' => 'Desain Grafis & Animasi', 'address' => 'Yogyakarta', 'is_verified' => true],
            ['company_name' => 'PT Logistik Nusantara Express', 'industry' => 'Ekspedisi & Supply Chain', 'address' => 'Semarang', 'is_verified' => true],
            ['company_name' => 'PT Fastindo Distribusi Solusi', 'industry' => 'Pergudangan & Distribusi', 'address' => 'Bekasi', 'is_verified' => true],
            ['company_name' => 'PT Solusi Layanan Terpadu', 'industry' => 'Layanan Pelanggan & Helpdesk', 'address' => 'Malang', 'is_verified' => true],
        ];

        foreach ($companiesList as $comp) {
            CompanyProfile::firstOrCreate(
                ['company_name' => $comp['company_name']],
                [
                    'user_id' => $hrOwner->id,
                    'industry' => $comp['industry'],
                    'address' => $comp['address'],
                    'is_verified' => $comp['is_verified'],
                    'description' => 'Perusahaan terkemuka mitra resmi rekrutmen TalentFlow.',
                ]
            );
        }

        // 2. Create Job Postings across all 8 Official Categories (Job::DIVISIONS)
        $jobsData = [
            // --- Kategori 1: Teknologi & IT ---
            [
                'title' => 'Senior Backend Developer (Laravel & Go)',
                'company_name' => 'PT TechNova Asia Digital',
                'division' => 'Teknologi & IT',
                'location' => 'Jakarta Selatan',
                'work_type' => 'Hybrid',
                'salary' => 'Rp 15.000.000 - Rp 22.000.000',
                'experience_level' => 'Senior / Lead (5+ Tahun)',
                'education_level' => 'S1 / D4',
                'major_requirement' => 'Teknik Informatika / Ilmu Komputer / Sistem Informasi',
                'skills_required' => 'Laravel, Go, PostgreSQL, Redis, Docker, Microservices, CI/CD, REST API',
                'description' => "Kami mencari Senior Backend Developer berpengalaman untuk merancang, mengoptimalkan, dan memelihara arsitektur microservices performa tinggi di perusahaan fintech terkemuka.\n\nTanggung Jawab:\n- Mengembangkan API RESTful dan GraphQL yang aman dan scalable.\n- Mengoptimalkan performa database PostgreSQL & Redis.\n- Berkolaborasi dengan tim DevOps dan Frontend.",
                'requirements' => "1. Minimal 4 tahun pengalaman menggunakan PHP (Laravel) & Go.\n2. Menguasai PostgreSQL, MySQL, Redis, dan Docker.\n3. Memahami konsep Microservices, CI/CD, dan Unit Testing.\n4. Pengalaman dengan AWS / Google Cloud menjadi nilai tambah.",
                'benefits' => 'Asuransi Swasta, Laptop Kerja (MacBook Pro), Tunjangan Kesehatan, Work From Anywhere 2 hari/minggu.',
                'deadline' => now()->addDays(30),
                'status' => 'active',
            ],
            [
                'title' => 'Frontend Engineer (React.js & TypeScript)',
                'company_name' => 'PT GlobalCorp Digital',
                'division' => 'Teknologi & IT',
                'location' => 'Bandung',
                'work_type' => 'Remote',
                'salary' => 'Rp 10.000.000 - Rp 16.000.000',
                'experience_level' => 'Mid-Level (2-5 Tahun)',
                'education_level' => 'S1 / D4',
                'major_requirement' => 'Teknik Informatika / Sistem Informasi / Ilmu Komputer',
                'skills_required' => 'React.js, Next.js, TypeScript, Tailwind CSS, Redux, REST API, Git',
                'description' => "Bergabunglah dengan tim produk kami untuk membangun antarmuka pengguna yang responsif, cepat, dan intuitif untuk ribuan pengguna harian.",
                'requirements' => "1. Minimal 2 tahun pengalaman menggunakan React.js / Next.js / Vue.js.\n2. Menguasai HTML5, CSS3, Tailwind CSS, dan TypeScript.\n3. Paham state management (Redux / Pinia).\n4. Memiliki portofolio aplikasi web yang menarik.",
                'benefits' => 'Tunjangan Internet, Jam Kerja Fleksibel, Bonus Tahunan.',
                'deadline' => now()->addDays(20),
                'status' => 'active',
            ],
            [
                'title' => 'DevOps & Cloud Infrastructure Engineer',
                'company_name' => 'PT TechNova Asia Digital',
                'division' => 'Teknologi & IT',
                'location' => 'Jakarta Barat',
                'work_type' => 'Remote',
                'salary' => 'Rp 14.000.000 - Rp 20.000.000',
                'experience_level' => 'Mid-Level (2-5 Tahun)',
                'education_level' => 'S1 / D4',
                'major_requirement' => 'Teknik Informatika / Teknik Komputer',
                'skills_required' => 'AWS, Kubernetes, Docker, Terraform, CI/CD, Linux, Prometheus',
                'description' => "Mengelola infrastruktur cloud berbasis AWS, mengotomatiskan pipeline CI/CD, dan memastikan uptime aplikasi 99.9%.",
                'requirements' => "1. Minimal 3 tahun pengalaman dengan AWS / GCP / Kubernetes.\n2. Mahir Terraform, Ansible, Docker, Jenkins / GitHub Actions.\n3. Pengalaman mengelola Linux Server & monitoring (Prometheus/Grafana).",
                'benefits' => 'Full Remote Work, Tunjangan Peralatan Kerja, Asuransi Swasta.',
                'deadline' => now()->addDays(12),
                'status' => 'active',
            ],

            // --- Kategori 2: Keuangan & Akuntansi ---
            [
                'title' => 'Senior Financial Analyst & Corporate Budgeting',
                'company_name' => 'FinServe Digital Indonesia',
                'division' => 'Keuangan & Akuntansi',
                'location' => 'Jakarta Pusat',
                'work_type' => 'Full-time',
                'salary' => 'Rp 12.000.000 - Rp 18.000.000',
                'experience_level' => 'Mid-Level (2-5 Tahun)',
                'education_level' => 'S1 / D4',
                'major_requirement' => 'Akuntansi / Keuangan / Manajemen Keuangan',
                'skills_required' => 'Financial Modeling, Corporate Budgeting, SAP, Financial Analysis, Excel Advanced',
                'description' => "Bertanggung jawab menyusun model proyeksi finansial, analisis anggaran tahunan (budgeting), dan pelaporan kinerja finansial kepada manajemen direksi.",
                'requirements' => "1. S1 Akuntansi / Manajemen Keuangan dengan IPK min 3.25.\n2. Pengalaman min. 3 tahun di bidang Financial Planning & Analysis (FP&A).\n3. Mahir Financial Modeling, Advanced Excel, dan sistem ERP (SAP/Oracle).\n4. Sertifikasi CFA/CPA menjadi nilai tambah.",
                'benefits' => 'Asuransi Kesehatan Keluarga, Bonus Kinerja Tahunan, Tunjangan Transportasi.',
                'deadline' => now()->addDays(25),
                'status' => 'active',
            ],
            [
                'title' => 'Staff Akuntansi, Pajak & Audit (Tax & Accounting)',
                'company_name' => 'PT Mitra Solusi Keuangan',
                'division' => 'Keuangan & Akuntansi',
                'location' => 'Surabaya',
                'work_type' => 'Full-time',
                'salary' => 'Rp 6.000.000 - Rp 9.000.000',
                'experience_level' => 'Junior (1-2 Tahun)',
                'education_level' => 'S1 / D4',
                'major_requirement' => 'Akuntansi / Perpajakan',
                'skills_required' => 'Brevet A & B, e-Faktur, e-SPT, Jurnal Akuntansi, Laporan Keuangan',
                'description' => "Menangani pencatatan transaksi harian, rekonsiliasi bank, penghitungan PPh/PPN, pelaporan SPT masa dan tahunan, serta persiapan audit tahunan.",
                'requirements' => "1. S1 Akuntansi / Perpajakan.\n2. Menguasai e-Faktur, e-SPT, DJP Online, dan Accurate/Zahir.\n3. Memiliki sertifikat Brevet A & B.\n4. Teliti, jujur, dan memiliki kemampuan analisa numerik yang tinggi.",
                'benefits' => 'BPJS Ketenagakerjaan & Kesehatan, Tunjangan Makan, Jenjang Karir.',
                'deadline' => now()->addDays(20),
                'status' => 'active',
            ],

            // --- Kategori 3: Pemasaran & Penjualan ---
            [
                'title' => 'Digital Marketing & Growth Specialist',
                'company_name' => 'PT Global Inovasi Media',
                'division' => 'Pemasaran & Penjualan',
                'location' => 'Tangerang',
                'work_type' => 'Full-time',
                'salary' => 'Rp 8.000.000 - Rp 13.000.000',
                'experience_level' => 'Mid-Level (2-5 Tahun)',
                'education_level' => 'S1 / D4',
                'major_requirement' => 'Pemasaran / Ilmu Komunikasi / Manajemen Bisnis',
                'skills_required' => 'Meta Ads, Google Ads, TikTok Ads, SEO/SEM, Google Analytics, Copywriting',
                'description' => "Memimpin strategi pertumbuhan digital melalui performance marketing berbayar, optimasi funnel konversi, dan analitik kampanye iklan.",
                'requirements' => "1. Minimal 2 tahun pengalaman di bidang Performance / Digital Marketing.\n2. Terbukti mampu mengelola budget iklan bulanan dengan ROAS positif.\n3. Mahir Meta Ads Manager, Google Ads, GA4, dan Tag Manager.\n4. Memiliki analytical mindset yang kuat.",
                'benefits' => 'Bonus Performa Target, Ruang Istirahat & Game, BPJS.',
                'deadline' => now()->addDays(18),
                'status' => 'active',
            ],
            [
                'title' => 'B2B Enterprise Corporate Sales Executive',
                'company_name' => 'PT Nusantara Distribusi Pro',
                'division' => 'Pemasaran & Penjualan',
                'location' => 'Jakarta Selatan',
                'work_type' => 'Full-time',
                'salary' => 'Rp 9.000.000 - Rp 16.000.000',
                'experience_level' => 'Mid-Level (2-5 Tahun)',
                'education_level' => 'S1 / D4',
                'major_requirement' => 'Manajemen / Komunikasi / Hubungan Internasional / Semua Jurusan',
                'skills_required' => 'B2B Sales, Lead Generation, Corporate Pitching, CRM Salesforce, Negosiasi',
                'description' => "Mengidentifikasi peluang kemitraan B2B baru, memimpin presentasi penawaran korporat, dan mencapai target penjualan tahunan.",
                'requirements' => "1. Pengalaman 2+ tahun dalam B2B Sales / Account Executive.\n2. Memiliki jaringan relasi korporat yang luas di Jabodetabek.\n3. Kemampuan presentasi, pitching, dan negosiasi kelas atas.",
                'benefits' => 'Komisi Penjualan Tanpa Batas, Tunjangan Kendaraan & Bahan Bakar, Asuransi Swasta.',
                'deadline' => now()->addDays(24),
                'status' => 'active',
            ],

            // --- Kategori 4: Administrasi & SDM ---
            [
                'title' => 'HR Talent Acquisition & People Operations Specialist',
                'company_name' => 'PT TechNova Asia Digital',
                'division' => 'Administrasi & SDM',
                'location' => 'Jakarta Selatan',
                'work_type' => 'Full-time',
                'salary' => 'Rp 8.000.000 - Rp 12.000.000',
                'experience_level' => 'Mid-Level (2-5 Tahun)',
                'education_level' => 'S1 / D4',
                'major_requirement' => 'Psikologi / Manajemen SDM / Hukum',
                'skills_required' => 'End-to-End Recruitment, Behavioral Event Interview (BEI), UU Ketenagakerjaan, HRIS',
                'description' => "Bertanggung jawab atas proses rekrutmen end-to-end posisi strategis dan teknologi, perikatan kontrak kerja, serta inisiatif employee engagement.",
                'requirements' => "1. S1 Psikologi / Hukum / Manajemen SDM.\n2. Pengalaman min. 2 tahun di bidang Tech Recruitment / Talent Acquisition.\n3. Menguasai teknik wawancara BEI dan interpretasi alat tes psikologi.\n4. Memahami regulasi ketenagakerjaan Indonesia.",
                'benefits' => 'BPJS Kesehatan, Jenjang Karir Jelas, Outing Kantor Tahunan.',
                'deadline' => now()->addDays(14),
                'status' => 'active',
            ],
            [
                'title' => 'Staff Administrasi Operasional & General Affairs',
                'company_name' => 'PT Global Sarana Utama',
                'division' => 'Administrasi & SDM',
                'location' => 'Bandung',
                'work_type' => 'Full-time',
                'salary' => 'Rp 4.800.000 - Rp 6.500.000',
                'experience_level' => 'Junior (1-2 Tahun)',
                'education_level' => 'D3 / S1',
                'major_requirement' => 'Administrasi Perkantoran / Manajemen / Sekretaris / Semua Jurusan',
                'skills_required' => 'Microsoft Office, Pengarsipan Dokumen, Inventaris Kantor, Komunikasi Formal',
                'description' => "Mengelola korespondensi kantor, pengadaan aset, pengarsipan berkas legal perusahaan, serta rekap absensi dan administrasi internal.",
                'requirements' => "1. Pendidikan min. D3/S1 Administrasi Perkantoran atau jurusan terkait.\n2. Mahir menggunakan Microsoft Office (Excel, Word, PowerPoint).\n3. Rapi, teliti, disiplin, dan memiliki integritas tinggi.",
                'benefits' => 'BPJS Kesehatan & Ketenagakerjaan, Tunjangan Makan, Lingkungan Kerja Nyaman.',
                'deadline' => now()->addDays(22),
                'status' => 'active',
            ],

            // --- Kategori 5: Kreatif & Desain ---
            [
                'title' => 'UI/UX Product Designer & Researcher',
                'company_name' => 'Studio Desain Kreasi Digital',
                'division' => 'Kreatif & Desain',
                'location' => 'Jakarta Pusat',
                'work_type' => 'Full-time',
                'salary' => 'Rp 9.000.000 - Rp 14.000.000',
                'experience_level' => 'Mid-Level (2-5 Tahun)',
                'education_level' => 'S1 / D4',
                'major_requirement' => 'Desain Komunikasi Visual (DKV) / Desain Produk / Sistem Informasi',
                'skills_required' => 'Figma, Design System, Wireframing, Usability Testing, Prototyping, User Flow',
                'description' => "Merancang pengalaman pengguna (UX) dan antarmuka visual (UI) aplikasi mobile & web berdasarkan data riset pengguna dan standar design system modern.",
                'requirements' => "1. Pengalaman 2+ tahun sebagai UI/UX Designer.\n2. Mahir Figma, Prototyping, dan pembuatan Design System yang reusable.\n3. Mampu melakukan User Research & Usability Testing.\n4. Wajib menyertakan link portofolio desain.",
                'benefits' => 'BPJS Kesehatan, Akses Kursus Desain Global, MacBook Pro disediakan.',
                'deadline' => now()->addDays(15),
                'status' => 'active',
            ],
            [
                'title' => 'Graphic Designer & Video Motion Creator',
                'company_name' => 'PT Multimedia Kreasi Visual',
                'division' => 'Kreatif & Desain',
                'location' => 'Yogyakarta',
                'work_type' => 'Hybrid',
                'salary' => 'Rp 5.500.000 - Rp 8.500.000',
                'experience_level' => 'Junior (1-2 Tahun)',
                'education_level' => 'D3 / S1',
                'major_requirement' => 'Desain Komunikasi Visual / Animasi / Multimedia',
                'skills_required' => 'Adobe Photoshop, Adobe Illustrator, After Effects, Premiere Pro, Motion Graphic',
                'description' => "Membuat konten visual kreatif untuk media sosial, materi promosi digital, dan animasi video interaktif untuk kampanye produk.",
                'requirements' => "1. Mahir Adobe Photoshop, Illustrator, Premiere Pro, dan After Effects.\n2. Kreatif, memahami tren visual terbaru, dan memiliki sense estetik yang kuat.\n3. Wajib melampirkan portofolio karya visual/video.",
                'benefits' => 'Jam Kerja Fleksibel, Lingkungan Kerja Santai & Kreatif, Bonus Proyek.',
                'deadline' => now()->addDays(20),
                'status' => 'active',
            ],

            // --- Kategori 6: Operasional & Logistik ---
            [
                'title' => 'Supply Chain & Logistics Operations Specialist',
                'company_name' => 'PT Logistik Nusantara Express',
                'division' => 'Operasional & Logistik',
                'location' => 'Semarang',
                'work_type' => 'Full-time',
                'salary' => 'Rp 7.500.000 - Rp 11.000.000',
                'experience_level' => 'Mid-Level (2-5 Tahun)',
                'education_level' => 'S1 / D4',
                'major_requirement' => 'Teknik Industri / Manajemen Logistik / Manajemen Operasional',
                'skills_required' => 'Supply Chain Management, Fleet Management, WMS, Routing Optimization, ERP Logistik',
                'description' => "Mengoptimalkan rantai pasok pengiriman barang antar cabang nasional, manajemen armada kendaraan, serta SLA pengiriman pelanggan.",
                'requirements' => "1. S1 Teknik Industri / Manajemen Logistik / Transportasi.\n2. Pengalaman 2+ tahun di industri logistik, freight forwarding, atau 3PL.\n3. Mahir Warehouse Management System (WMS) dan data analitik logistik.",
                'benefits' => 'Asuransi Kesehatan, Bonus Efisiensi Operasional, BPJS.',
                'deadline' => now()->addDays(28),
                'status' => 'active',
            ],
            [
                'title' => 'Warehouse Supervisor & Inventory Control',
                'company_name' => 'PT Fastindo Distribusi Solusi',
                'division' => 'Operasional & Logistik',
                'location' => 'Bekasi',
                'work_type' => 'Full-time',
                'salary' => 'Rp 6.000.000 - Rp 9.000.000',
                'experience_level' => 'Junior (1-2 Tahun)',
                'education_level' => 'D3 / S1',
                'major_requirement' => 'Manajemen / Teknik Industri / Semua Jurusan',
                'skills_required' => 'Stock Opname, 5S/5R, Inbound/Outbound Logistics, Barcode Scanner System, K3 Pergudangan',
                'description' => "Memimpin tim pergudangan dalam pengelolaan inbound, outbound, penyimpanan stok, akurasi stock opname, dan penerapan standar K3 pergudangan.",
                'requirements' => "1. Pendidikan min. D3/S1 semua jurusan.\n2. Pengalaman min. 2 tahun di bidang supervisi gudang dan inventory control.\n3. Memahami sistem 5S/5R dan standar keselamatan kerja (K3).",
                'benefits' => 'BPJS Kesehatan & Ketenagakerjaan, Tunjangan Shift, Uang Lembur.',
                'deadline' => now()->addDays(16),
                'status' => 'active',
            ],

            // --- Kategori 7: Magang & Entry-Level ---
            [
                'title' => 'Magang Software Engineer & Fullstack Web (Batch 2)',
                'company_name' => 'PT TechNova Asia Digital',
                'division' => 'Magang & Entry-Level',
                'location' => 'Bandung',
                'work_type' => 'Internship',
                'salary' => 'Rp 3.500.000 - Rp 5.000.000',
                'experience_level' => 'Magang / Intern',
                'education_level' => 'Mahasiswa / D3 / S1',
                'major_requirement' => 'Teknik Informatika / Sistem Informasi / Ilmu Komputer',
                'skills_required' => 'HTML, CSS, JavaScript, PHP / Python, Git, Dasar Database MySQL',
                'description' => "Program magang intensif 6 bulan berstandar industri dengan pendampingan mentor senior. Peserta akan terlibat langsung dalam pengembangan produk nyata.",
                'requirements' => "1. Mahasiswa tingkat akhir (semester 5-8) atau fresh graduate jurusan IT/Sistem Informasi.\n2. Memiliki dasar pemrograman web (HTML, CSS, JS, PHP/Node.js/Python).\n3. Memiliki motivasi belajar tinggi, proaktif, dan siap magang full-time.",
                'benefits' => 'Uang Saku Bulanan Kompetitif, Sertifikat Resmi Magang, Mentoring 1-on-1, Peluang Konversi Karyawan Tetap.',
                'deadline' => now()->addDays(35),
                'status' => 'active',
            ],
            [
                'title' => 'Magang UI/UX & Graphic Design (Batch 2)',
                'company_name' => 'Studio Desain Kreasi Digital',
                'division' => 'Magang & Entry-Level',
                'location' => 'Jakarta Selatan',
                'work_type' => 'Internship',
                'salary' => 'Rp 3.000.000 - Rp 4.500.000',
                'experience_level' => 'Magang / Intern',
                'education_level' => 'Mahasiswa / D3 / S1',
                'major_requirement' => 'DKV / Desain Produk / Multimedia / Sistem Informasi',
                'skills_required' => 'Figma, Adobe Illustrator, Prototyping, Wireframing Dasar',
                'description' => "Program magang desain produk digital. Membantu pembuatan wireframe, eksplorasi visual UI, dan pengujian prototype bersama tim designer profesional.",
                'requirements' => "1. Mahasiswa/fresh graduate bidang desain atau yang memiliki passion kuat di UI/UX.\n2. Menguasai Figma dasar dan tool visual editing.\n3. Wajib menyertakan tautan portofolio desain.",
                'benefits' => 'Uang Saku Bulanan, Sertifikat Magang, Mentoring Desain, Surat Rekomendasi Karir.',
                'deadline' => now()->addDays(28),
                'status' => 'active',
            ],
            [
                'title' => 'Magang Digital Marketing & Content Creator (Batch 2)',
                'company_name' => 'PT Global Inovasi Media',
                'division' => 'Magang & Entry-Level',
                'location' => 'Surabaya',
                'work_type' => 'Internship',
                'salary' => 'Rp 2.800.000 - Rp 4.000.000',
                'experience_level' => 'Magang / Intern',
                'education_level' => 'Mahasiswa / D3 / S1',
                'major_requirement' => 'Ilmu Komunikasi / Pemasaran / Manajemen / Semua Jurusan',
                'skills_required' => 'Copywriting, TikTok & Instagram Reels, Canva, CapCut, Social Media Management',
                'description' => "Program magang kreatif dalam pembuatan konten video pendek (TikTok/Reels), copywriting media sosial, dan riset tren konten digital.",
                'requirements' => "1. Mahasiswa aktif atau fresh graduate semua jurusan.\n2. Percaya diri di depan kamera, kreatif, dan menguasai aplikasi editing mobile (CapCut/Canva).\n3. Memahami tren media sosial terkini.",
                'benefits' => 'Uang Saku Bulanan, Portofolio Konten Nyata, Sertifikat Magang Industri.',
                'deadline' => now()->addDays(30),
                'status' => 'active',
            ],

            // --- Kategori 8: Layanan & CS ---
            [
                'title' => 'Customer Service Specialist (Omnichannel & CRM)',
                'company_name' => 'FinServe Digital Indonesia',
                'division' => 'Layanan & CS',
                'location' => 'Jakarta Barat',
                'work_type' => 'Full-time',
                'salary' => 'Rp 5.200.000 - Rp 7.500.000',
                'experience_level' => 'Junior (1-2 Tahun)',
                'education_level' => 'D3 / S1',
                'major_requirement' => 'Komunikasi / Bahasa / Manajemen / Semua Jurusan',
                'skills_required' => 'Zendesk, Live Chat, Customer Empathy, Problem Solving, CRM Salesforce',
                'description' => "Memberikan pelayanan prima kepada pelanggan melalui kanal live chat, tiket email, dan telepon dengan standar SLA cepat dan ramah.",
                'requirements' => "1. Pendidikan min. D3/S1 semua jurusan.\n2. Pengalaman min. 1 tahun sebagai Customer Service / Contact Center di industri fintech/e-commerce.\n3. Memiliki artikulasi komunikasi yang baik, sabar, dan solutif.",
                'benefits' => 'BPJS Kesehatan & Ketenagakerjaan, Insentif CS Performance, Tunjangan Shift.',
                'deadline' => now()->addDays(20),
                'status' => 'active',
            ],
            [
                'title' => 'IT Helpdesk & Technical Support Specialist',
                'company_name' => 'PT Solusi Layanan Terpadu',
                'division' => 'Layanan & CS',
                'location' => 'Malang',
                'work_type' => 'Full-time',
                'salary' => 'Rp 5.000.000 - Rp 7.000.000',
                'experience_level' => 'Junior (1-2 Tahun)',
                'education_level' => 'D3 / S1',
                'major_requirement' => 'Teknik Informatika / Sistem Informasi / Teknik Komputer & Jaringan',
                'skills_required' => 'Troubleshooting Hardware & Software, Jaringan LAN/WiFi, Windows/Mac Support, Ticketing System',
                'description' => "Menangani aduan teknis pengguna terkait perangkat keras (laptop/PC), sistem operasi, jaringan internet, dan aplikasi internal perusahaan.",
                'requirements' => "1. Pendidikan min. SMK IT / D3 / S1 bidang Komputer / Jaringan.\n2. Mampu melakukan troubleshooting software, hardware, printer, dan jaringan komputer.\n3. Komunikatif, cepat tanggap, dan memiliki etos kerja tinggi.",
                'benefits' => 'BPJS, Tunjangan Lembur, Laptop Kerja, Pelatihan Sertifikasi IT.',
                'deadline' => now()->addDays(18),
                'status' => 'active',
            ],
        ];

        $createdJobs = [];
        foreach ($jobsData as $data) {
            $createdJobs[] = Job::updateOrCreate(
                ['title' => $data['title'], 'company_name' => $data['company_name']],
                $data
            );
        }

        // 3. Create Candidate Users with Rich Profiles
        $candidatesData = [
            [
                'name' => 'Budi Pratama',
                'email' => 'budi.pratama@gmail.com',
                'phone' => '081298765432',
                'last_education' => 'S1 Teknik Informatika - ITB',
                'current_position' => 'Fullstack Web Developer',
                'summary' => 'Developer berpengalaman 4+ tahun dalam membangun aplikasi web bisnis menggunakan Laravel, Vue.js, dan MySQL. Terbiasa dengan arsitektur REST API dan CI/CD.',
                'skills' => ['php', 'laravel', 'vue.js', 'mysql', 'tailwind css', 'git', 'docker', 'rest api'],
                'experiences' => [
                    ['title' => 'Senior Web Developer', 'company' => 'PT Toko Teknologi Indonesia', 'start_date' => '2022-01', 'end_date' => 'Sekarang', 'is_current' => true, 'description' => 'Mengembangkan sistem backend e-commerce berbasis Laravel.'],
                    ['title' => 'Junior Programmer', 'company' => 'PT Solusi Digital Pro', 'start_date' => '2020-03', 'end_date' => '2021-12', 'is_current' => false, 'description' => 'Membuat modul administrasi dan laporan keuangan.'],
                ],
                'educations' => [
                    ['institution' => 'Institut Teknologi Bandung (ITB)', 'degree' => 'S1', 'field_of_study' => 'Teknik Informatika', 'start_year' => '2016', 'end_year' => '2020', 'gpa' => '3.75'],
                ],
                'languages' => [
                    ['name' => 'Bahasa Indonesia', 'proficiency' => 'Native'],
                    ['name' => 'English', 'proficiency' => 'Professional Working'],
                ],
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@gmail.com',
                'phone' => '082155443322',
                'last_education' => 'S1 Desain Komunikasi Visual - ISI Yogyakarta',
                'current_position' => 'UI/UX Designer & Product Researcher',
                'summary' => 'Designer kreatif berpengalaman 3 tahun berfokus pada desain antarmuka mobile & web modern berbasis user-centered design.',
                'skills' => ['figma', 'ui/ux designer', 'adobe xd', 'prototyping', 'wireframing', 'user research', 'html5', 'css3'],
                'experiences' => [
                    ['title' => 'UI/UX Designer Lead', 'company' => 'Studio Desain Kreatif', 'start_date' => '2021-06', 'end_date' => 'Sekarang', 'is_current' => true, 'description' => 'Memimpin pembuatan design system untuk aplikasi kesehatan.'],
                ],
                'educations' => [
                    ['institution' => 'Institut Seni Indonesia Yogyakarta', 'degree' => 'S1', 'field_of_study' => 'Desain Komunikasi Visual', 'start_year' => '2017', 'end_year' => '2021', 'gpa' => '3.82'],
                ],
                'languages' => [
                    ['name' => 'Bahasa Indonesia', 'proficiency' => 'Native'],
                    ['name' => 'English', 'proficiency' => 'Conversational'],
                ],
            ],
            [
                'name' => 'Rian Hidayat',
                'email' => 'rian.hidayat@yahoo.com',
                'phone' => '085711223344',
                'last_education' => 'S1 Sistem Informasi - Universitas Indonesia',
                'current_position' => 'DevOps Engineer',
                'summary' => 'Insinyur cloud dan otomatisasi infrastruktur berpengalaman mengelola klaster Kubernetes dan CI/CD pipeline di AWS.',
                'skills' => ['aws', 'kubernetes', 'docker', 'terraform', 'jenkins', 'linux', 'python', 'go'],
                'experiences' => [
                    ['title' => 'DevOps Specialist', 'company' => 'Cloud Infrastructure Asia', 'start_date' => '2021-02', 'end_date' => 'Sekarang', 'is_current' => true, 'description' => 'Mengelola 50+ mikroservis di AWS EKS.'],
                ],
                'educations' => [
                    ['institution' => 'Universitas Indonesia', 'degree' => 'S1', 'field_of_study' => 'Sistem Informasi', 'start_year' => '2016', 'end_year' => '2020', 'gpa' => '3.60'],
                ],
                'languages' => [
                    ['name' => 'Bahasa Indonesia', 'proficiency' => 'Native'],
                    ['name' => 'English', 'proficiency' => 'Fluent'],
                ],
            ],
            [
                'name' => 'Dewi Anggraini',
                'email' => 'dewi.anggraini@gmail.com',
                'phone' => '081388990011',
                'last_education' => 'S1 Akuntansi - Universitas Gadjah Mada',
                'current_position' => 'Senior Financial Analyst',
                'summary' => 'Analis Keuangan berpengalaman dalam financial modeling, audit, dan analisa anggaran korporat menggunakan SAP & Excel.',
                'skills' => ['financial modeling', 'sap', 'accounting', 'tax', 'excel advanced', 'budgeting'],
                'experiences' => [
                    ['title' => 'Financial Analyst', 'company' => 'PT Finansial Mitra Sukses', 'start_date' => '2022-03', 'end_date' => 'Sekarang', 'is_current' => true, 'description' => 'Menyusun laporan kinerja keuangan bulanan.'],
                ],
                'educations' => [
                    ['institution' => 'Universitas Gadjah Mada', 'degree' => 'S1', 'field_of_study' => 'Akuntansi', 'start_year' => '2017', 'end_year' => '2021', 'gpa' => '3.90'],
                ],
                'languages' => [
                    ['name' => 'Bahasa Indonesia', 'proficiency' => 'Native'],
                    ['name' => 'English', 'proficiency' => 'Advanced'],
                ],
            ],
            [
                'name' => 'Fajar Nugraha',
                'email' => 'fajar.nugraha@gmail.com',
                'phone' => '087733445566',
                'last_education' => 'S1 Teknik Elektro - ITS Surabaya',
                'current_position' => 'Frontend React Engineer',
                'summary' => 'Frontend developer dengan fokus utama pada performa web, optimasi SEO, dan arsitektur TypeScript/React.',
                'skills' => ['react.js', 'typescript', 'next.js', 'tailwind css', 'redux', 'javascript', 'git'],
                'experiences' => [
                    ['title' => 'Frontend Web Engineer', 'company' => 'PT Digital Inovasi Bangsa', 'start_date' => '2021-08', 'end_date' => 'Sekarang', 'is_current' => true, 'description' => 'Membangun platform SaaS dengan Next.js & Tailwind.'],
                ],
                'educations' => [
                    ['institution' => 'Institut Teknologi Sepuluh Nopember', 'degree' => 'S1', 'field_of_study' => 'Teknik Elektro', 'start_year' => '2016', 'end_year' => '2020', 'gpa' => '3.52'],
                ],
                'languages' => [
                    ['name' => 'Bahasa Indonesia', 'proficiency' => 'Native'],
                ],
            ],
        ];

        $createdCandidates = [];
        foreach ($candidatesData as $cData) {
            $user = User::firstOrCreate(
                ['email' => $cData['email']],
                [
                    'name' => $cData['name'],
                    'password' => Hash::make('S3cur3#P@ssw0rd!2026'),
                ]
            );
            $user->assignRole($candidateRole);

            CandidateProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $cData['phone'],
                    'summary' => $cData['summary'],
                    'skills' => $cData['skills'],
                    'experiences' => $cData['experiences'],
                    'educations' => $cData['educations'],
                    'languages' => $cData['languages'],
                ]
            );

            $createdCandidates[] = $user;
        }

        // 4. Create Applications & Interviews
        $statuses = ['pending', 'reviewed', 'interview', 'accepted', 'rejected'];

        foreach ($createdCandidates as $candidateIndex => $candidate) {
            // Apply to 3 - 4 jobs for each candidate
            $appliedJobs = array_slice($createdJobs, $candidateIndex * 2, 4);

            foreach ($appliedJobs as $jobIndex => $job) {
                $status = $statuses[($candidateIndex + $jobIndex) % count($statuses)];

                $application = Application::firstOrCreate(
                    [
                        'user_id' => $candidate->id,
                        'job_id' => $job->id,
                    ],
                    [
                        'status' => $status,
                    ]
                );

                // Create interview if status is interview
                if ($status === 'interview') {
                    Interview::firstOrCreate(
                        ['application_id' => $application->id],
                        [
                            'scheduled_at' => now()->addDays(rand(1, 10))->setHour(rand(9, 15))->setMinute(0),
                            'type' => rand(0, 1) ? 'online' : 'offline',
                            'location_or_link' => rand(0, 1) ? 'https://meet.google.com/abc-defg-hij' : 'Ruang Wawancara Lt. 3 Kantor Utama',
                            'notes' => 'Harap hadir 10 menit lebih awal dan menyiapkan dokumen identitas serta portofolio karya terbaru Anda.',
                            'status' => 'scheduled',
                        ]
                    );
                }

                // Bookmark some jobs
                if (rand(0, 1)) {
                    SavedJob::firstOrCreate([
                        'user_id' => $candidate->id,
                        'job_id' => $job->id,
                    ]);
                }
            }
        }
    }
}
