<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\CandidateDocument;
use App\Models\CandidateProfile;
use App\Models\Interview;
use App\Models\Job;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class CandidateFaridProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidateRole = Role::firstOrCreate(['name' => 'Candidate']);

        // 1. Create or Find User
        $user = User::updateOrCreate(
            ['email' => 'faridwimansyah8@gmail.com'],
            [
                'name' => 'Farid Wimansyah',
                'password' => Hash::make('password'),
            ]
        );
        $user->assignRole($candidateRole);

        // 2. Prepare Sample Document Directory & Minimal Valid PDFs
        $folder = 'candidate_files/farid-wimansyah_' . $user->id;
        Storage::disk('public')->makeDirectory($folder);

        $minimalPdf = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>>>endobj\nxref\n0 4\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n190\n%%EOF";

        $docFiles = [
            'cv' => 'cv_farid_wimansyah.pdf',
            'ktp' => 'ktp_farid_wimansyah.pdf',
            'ijazah' => 'ijazah_s1_ui_farid.pdf',
            'transcript' => 'transkrip_nilai_ui_farid.pdf',
            'certificate' => 'sertifikat_laravel_aws_farid.pdf',
            'portfolio' => 'portofolio_karya_farid.pdf',
            'skck' => 'skck_farid_wimansyah.pdf',
            'health_certificate' => 'surat_keterangan_sehat_farid.pdf',
            'cover_letter' => 'surat_lamaran_kerja_farid.pdf',
            'consent_letter' => 'surat_pernyataan_komitmen_farid.pdf',
        ];

        $storedPaths = [];
        foreach ($docFiles as $key => $filename) {
            $filePath = $folder . '/' . $filename;
            Storage::disk('public')->put($filePath, $minimalPdf);
            $storedPaths[$key] = $filePath;
        }

        // Generate Sample Avatar PNG (1x1 transparent PNG)
        $avatarPath = $folder . '/photo_farid_wimansyah.png';
        $pngBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        Storage::disk('public')->put($avatarPath, base64_decode($pngBase64));

        // 3. Populate 11-Step Candidate Profile
        $profileData = [
            // STEP 1: Informasi Pribadi & Kontak
            'nickname' => 'Farid',
            'phone' => '081234567890',
            'birth_place' => 'Jakarta',
            'dob' => '1998-05-14',
            'gender' => 'male',
            'nationality' => 'WNI (Indonesia)',
            'marital_status' => 'Lajang',
            'religion' => 'Islam',
            'nik' => '3171011405980001',
            'address' => 'Jl. Jenderal Sudirman Kav. 52-53, RT 05 / RW 03, Kel. Senayan',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'postal_code' => '12190',
            'emergency_contact_name' => 'Hendra Wimansyah',
            'emergency_contact_phone' => '081398765432',
            'current_salary' => 'Rp 12.000.000',
            'photo' => $avatarPath,
            'photo_path' => $avatarPath,

            // STEP 2: Ringkasan Profesional & Tentang Saya
            'summary' => 'Senior Fullstack Web Developer & Software Architect dengan pengalaman 4+ tahun dalam membangun aplikasi web skala enterprise, SaaS multi-tenant, dan sistem API performa tinggi. Sangat berpengalaman mengoptimalkan backend menggunakan ekosistem Laravel, PostgreSQL, Redis, dan Go, serta antarmuka modern responsif dengan Vue.js, React, dan Tailwind CSS. Memiliki rekam jejak terbukti dalam memimpin refaktor arsitektur microservices yang meningkatkan kecepatan sistem hingga 40%. Siap berkontribusi memberikan dampak positif dan inovasi teknologi terbaik bagi perusahaan.',

            // STEP 3: Riwayat Pendidikan (educations)
            'educations' => [
                [
                    'level' => 'S1',
                    'institution' => 'Universitas Indonesia (UI)',
                    'major' => 'Ilmu Komputer / Teknik Informatika',
                    'degree' => 'Sarjana Komputer (S.Kom)',
                    'city' => 'Depok',
                    'start_year' => '2016',
                    'end_year' => '2020',
                    'is_current' => false,
                    'gpa' => '3.85',
                    'thesis_title' => 'Implementasi Arsitektur Event-Driven Microservices pada Platform E-Commerce Skalabilitas Tinggi',
                    'description' => 'Lulus dengan predikat Cum Laude. Aktif sebagai Asisten Laboratorium Rekayasa Perangkat Lunak & Basis Data, serta Koordinator IT BEM Fasilkom UI.',
                ],
                [
                    'level' => 'SMA/SMK',
                    'institution' => 'SMAN 8 Jakarta',
                    'major' => 'MIPA',
                    'degree' => 'Ijazah SMA',
                    'city' => 'Jakarta Selatan',
                    'start_year' => '2013',
                    'end_year' => '2016',
                    'is_current' => false,
                    'gpa' => '92.4',
                    'thesis_title' => '',
                    'description' => 'Jurusan Matematika dan Ilmu Pengetahuan Alam. Meraih medali perunggu Olimpiade Sains Nasional (OSN) bidang Informatika/Komputer tingkat provinsi.',
                ],
            ],

            // STEP 4: Pengalaman Kerja & Proyek (experiences)
            'experiences' => [
                [
                    'company' => 'PT TechNova Solusi Digital',
                    'position' => 'Senior Fullstack Developer',
                    'industry' => 'Teknologi & Informasi',
                    'type' => 'Full-time',
                    'location' => 'Jakarta Selatan (Hybrid)',
                    'salary_currency' => 'IDR',
                    'last_salary' => '15000000',
                    'start_date' => '2022-03',
                    'end_date' => '',
                    'is_current' => true,
                    'supervisor_name' => 'Rudi Hartono (VP of Engineering)',
                    'supervisor_contact' => 'rudi.hartono@technova.id',
                    'reason_for_leaving' => '',
                    'description' => 'Memimpin perancangan core payment & billing engine multi-gateway berbasis Laravel 11, Redis cluster, dan PostgreSQL. Mengorkestrasi automated CI/CD pipeline dengan Docker & Kubernetes.',
                    'achievements' => 'Berhasil memangkas response time API p95 dari 320ms menjadi 75ms serta meningkatkan transaction throughput hingga 45%.',
                ],
                [
                    'company' => 'PT Inovasi Mitra Nusantara',
                    'position' => 'Fullstack Web Engineer',
                    'industry' => 'E-Commerce & Digital Commerce',
                    'type' => 'Full-time',
                    'location' => 'Jakarta Pusat',
                    'salary_currency' => 'IDR',
                    'last_salary' => '9000000',
                    'start_date' => '2020-08',
                    'end_date' => '2022-02',
                    'is_current' => false,
                    'supervisor_name' => 'Anita Wijaya (Engineering Lead)',
                    'supervisor_contact' => 'anita.w@inovasimitra.com',
                    'reason_for_leaving' => 'Mencari jenjang karir teknis dan tantangan skala sistem yang lebih besar.',
                    'description' => 'Mengembangkan modul manajemen inventaris pergudangan terintegrasi kurir logistik (JNE, SiCepat, Gosend) dan dashboard analytics.',
                    'achievements' => 'Membangun fitur automasi rekonsiliasi pembayaran instan yang memangkas waktu kerja manual tim finance sebesar 60%.',
                ],
            ],

            // STEP 5: Pengalaman Organisasi & Volunteer (organizations)
            'organizations' => [
                [
                    'name' => 'Komunitas PHP & Laravel Indonesia',
                    'position' => 'Koordinator Divisi Workshop & Edukasi Developer',
                    'level' => 'Nasional',
                    'location' => 'Jakarta',
                    'start_date' => '2021-01',
                    'end_date' => '',
                    'is_current' => true,
                    'period' => '2021 - Sekarang',
                    'description' => 'Mengorganisir meetup teknis bulanan, sesi coding live open source, dan mentorship gratis dengan lebih dari 4.000 anggota aktif.',
                ],
                [
                    'name' => 'BEM Fasilkom Universitas Indonesia',
                    'position' => 'Kepala Departemen Riset & Teknologi (Ristek)',
                    'level' => 'Universitas',
                    'location' => 'Depok',
                    'start_date' => '2018-01',
                    'end_date' => '2019-12',
                    'is_current' => false,
                    'period' => '2018 - 2019',
                    'description' => 'Mengembangkan platform portal kemahasiswaan terintegrasi dan memimpin kepanitiaan IT Hackathon nasional COMPFEST.',
                ],
            ],

            // STEP 6: Keahlian & Kemampuan Bahasa (skills & languages)
            'skills' => [
                ['name' => 'PHP & Laravel Framework (Eloquent, Queues, Testing)', 'level' => 'Expert'],
                ['name' => 'JavaScript (ES6+), TypeScript, Vue.js, React.js', 'level' => 'Advanced'],
                ['name' => 'PostgreSQL, MySQL Database Design & Query Tuning', 'level' => 'Advanced'],
                ['name' => 'Redis Caching & Pub/Sub Message Broker', 'level' => 'Advanced'],
                ['name' => 'RESTful API & GraphQL Architecture', 'level' => 'Expert'],
                ['name' => 'Docker, Containerization & CI/CD Pipeline', 'level' => 'Advanced'],
                ['name' => 'Tailwind CSS, Alpine.js, Livewire', 'level' => 'Expert'],
                ['name' => 'Git Version Control & Agile/Scrum Methodologies', 'level' => 'Expert'],
            ],
            'languages' => [
                ['name' => 'Bahasa Indonesia', 'level' => 'Native / Bilingual'],
                ['name' => 'English', 'level' => 'Professional Working (TOEFL ITP: 600)'],
            ],

            // STEP 7: Sertifikasi Profesional (certificates)
            'certificates' => [
                [
                    'name' => 'Laravel Certified Developer',
                    'issuer' => 'Laravel LLC',
                    'type' => 'Internasional',
                    'number' => 'LCD-2023-88914',
                    'url' => 'https://certification.laravel.com/verify/LCD-2023-88914',
                    'score' => '96%',
                    'issue_date' => '2023-04-15',
                    'expiry_date' => '',
                    'does_not_expire' => true,
                    'description' => 'Sertifikasi kompetensi global mendalam dalam arsitektur Laravel framework, database ORM, queue handling, dan unit testing.',
                ],
                [
                    'name' => 'AWS Certified Solutions Architect – Associate',
                    'issuer' => 'Amazon Web Services (AWS)',
                    'type' => 'Internasional',
                    'number' => 'AWS-ASA-9941203',
                    'url' => 'https://aws.amazon.com/verification',
                    'score' => '880 / 1000',
                    'issue_date' => '2023-08-20',
                    'expiry_date' => '2026-08-20',
                    'does_not_expire' => false,
                    'description' => 'Validasi keahlian mendesain sistem cloud scalable, fault-tolerant, secure, dan cost-effective di infrastruktur AWS.',
                ],
            ],

            // STEP 8: Portofolio & Prestasi (portfolios & achievements)
            'portfolios' => [
                [
                    'name' => 'TalentFlow - Intelligent ATS & Recruitment Ecosystem',
                    'category' => 'Web Application & SaaS Platform',
                    'role' => 'Lead Fullstack Architect',
                    'year' => '2026',
                    'technologies' => 'Laravel 11, PostgreSQL, Redis, Tailwind CSS, Alpine.js',
                    'url' => 'https://talentflow.id',
                    'github_url' => 'https://github.com/faridwimansyah/talentflow-recruitment',
                    'description' => 'Platform rekrutmen lengkap dengan Applicant Tracking System (ATS), kalkulasi UMK regional otomatis, integrasi live interview scorecard, dan sistem cuti terpusat.',
                ],
                [
                    'name' => 'Enterprise POS & Inventory ERP Multi-Outlet',
                    'category' => 'Enterprise ERP Solution',
                    'role' => 'Backend Engineer',
                    'year' => '2024',
                    'technologies' => 'Laravel, Vue 3, PostgreSQL, Redis, Docker',
                    'url' => 'https://pos-erp-demo.faridw.dev',
                    'github_url' => 'https://github.com/faridwimansyah/pos-erp-enterprise',
                    'description' => 'Sistem kasir cerdas multi-cabang dengan sinkronisasi inventaris stok real-time, pembukuan jurnal otomatis, dan integrasi QRIS dinamis.',
                ],
            ],
            'achievements' => [
                [
                    'name' => 'Juara 1 National Tech Innovator Hackathon',
                    'level' => 'Nasional',
                    'issuer' => 'Kementerian Komunikasi dan Informatika RI (Kominfo)',
                    'rank' => 'Juara 1 (Gold Winner)',
                    'year' => '2023',
                    'url' => 'https://kominfo.go.id/hackathon-2023',
                    'description' => 'Menciptakan solusi automasi verifikasi berkas kandidat menggunakan OCR dan evaluasi data cerdas.',
                ],
                [
                    'name' => 'Lulusan Terbaik & Berprestasi (Cum Laude Fasilkom UI)',
                    'level' => 'Universitas',
                    'issuer' => 'Universitas Indonesia',
                    'rank' => 'IPK 3.85 (Cum Laude)',
                    'year' => '2020',
                    'url' => '',
                    'description' => 'Penghargaan atas pencapaian akademik terbaik dan publikasi riset skripsi di jurnal nasional terakreditasi.',
                ],
            ],

            // STEP 9: Referensi Profesional (references)
            'references' => [
                [
                    'name' => 'Rudi Hartono, M.T.',
                    'position' => 'VP of Engineering',
                    'company' => 'PT TechNova Solusi Digital',
                    'relationship' => 'Atasan Langsung (Direct Manager)',
                    'email' => 'rudi.hartono@technova.id',
                    'phone' => '081122334455',
                    'years_known' => '3 Tahun',
                    'notes' => 'Siap memberikan testimoni etos kerja, kepemimpinan teknis, dan keandalan rekayasa perangkat lunak.',
                ],
                [
                    'name' => 'Dr. Eng. Ir. Bambang Sugiarto',
                    'position' => 'Dosen Pembimbing & Kepala Laboratorium RPL',
                    'company' => 'Fakultas Ilmu Komputer Universitas Indonesia',
                    'relationship' => 'Dosen Pembimbing Akademik',
                    'email' => 'bambang.s@cs.ui.ac.id',
                    'phone' => '081299887766',
                    'years_known' => '5 Tahun',
                    'notes' => 'Referensi mengenai integritas, kecepatan belajar, dan dedikasi pemecahan masalah komputasi kompleks.',
                ],
            ],

            // STEP 10: Preferensi Kerja & Tautan Sosial (job_preferences & social_links)
            'job_preferences' => [
                'expected_positions' => 'Senior Backend Developer, Senior Fullstack Web Engineer, Technical Lead',
                'job_types' => ['Full-time', 'Hybrid', 'Remote'],
                'expected_salary_currency' => 'IDR',
                'expected_salary' => '17000000',
                'preferred_locations' => 'Jakarta Selatan, Jakarta Pusat, Tangerang, Remote (Work from Anywhere)',
                'available_start' => 'Segera (Available dalam 2 minggu)',
                'work_mobility' => 'Bersedia Perjalanan Dinas Luar Kota',
                'notice_period' => '2 Minggu',
            ],
            'social_links' => [
                'linkedin' => 'https://linkedin.com/in/farid-wimansyah',
                'github' => 'https://github.com/faridwimansyah',
                'portfolio' => 'https://faridwimansyah.dev',
                'instagram' => 'https://instagram.com/faridwimansyah',
                'twitter' => 'https://x.com/faridwimansyah',
            ],

            // STEP 11: Dokumen Lampiran File Path
            'cv_path' => $storedPaths['cv'],
            'ktp_path' => $storedPaths['ktp'],
            'ijazah_path' => $storedPaths['ijazah'],
            'transcript_path' => $storedPaths['transcript'],
            'certificate_file_path' => $storedPaths['certificate'],
            'portfolio_file_path' => $storedPaths['portfolio'],
            'skck_path' => $storedPaths['skck'],
            'health_certificate_path' => $storedPaths['health_certificate'],
            'cover_letter_path' => $storedPaths['cover_letter'],
            'consent_letter_path' => $storedPaths['consent_letter'],
        ];

        CandidateProfile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        // 4. Synchronize into Candidate Document Vault
        $vaultItems = [
            ['document_type' => 'cv', 'title' => 'Curriculum Vitae (CV) - Farid Wimansyah.pdf', 'file_path' => $storedPaths['cv']],
            ['document_type' => 'ktp', 'title' => 'KTP / Identitas Resmi - Farid Wimansyah.pdf', 'file_path' => $storedPaths['ktp']],
            ['document_type' => 'ijazah', 'title' => 'Ijazah S1 Ilmu Komputer UI - Farid Wimansyah.pdf', 'file_path' => $storedPaths['ijazah']],
            ['document_type' => 'transkrip', 'title' => 'Transkrip Akademik Resmi Fasilkom UI.pdf', 'file_path' => $storedPaths['transcript']],
            ['document_type' => 'certificate', 'title' => 'Sertifikat Resmi Laravel & AWS Certified.pdf', 'file_path' => $storedPaths['certificate']],
            ['document_type' => 'portfolio', 'title' => 'Portofolio Proyek Software Engineering.pdf', 'file_path' => $storedPaths['portfolio']],
            ['document_type' => 'skck', 'title' => 'Surat Keterangan Catatan Kepolisian (SKCK).pdf', 'file_path' => $storedPaths['skck']],
        ];

        foreach ($vaultItems as $item) {
            CandidateDocument::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'document_type' => $item['document_type'],
                ],
                [
                    'title' => $item['title'],
                    'file_path' => $item['file_path'],
                    'file_size' => strlen($minimalPdf),
                    'file_extension' => 'pdf',
                ]
            );
        }

        // 5. Connect Farid with some realistic applications and interview invitations
        $sampleJobs = Job::where('status', 'active')->take(4)->get();
        if ($sampleJobs->count() > 0) {
            foreach ($sampleJobs as $index => $job) {
                $statusList = ['interview', 'reviewed', 'pending', 'accepted'];
                $status = $statusList[$index % count($statusList)];

                $app = Application::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'job_id' => $job->id,
                    ],
                    [
                        'status' => $status,
                    ]
                );

                if ($status === 'interview') {
                    Interview::firstOrCreate(
                        ['application_id' => $app->id],
                        [
                            'scheduled_at' => now()->addDays(3)->setHour(10)->setMinute(0),
                            'type' => 'online',
                            'location_or_link' => 'https://meet.google.com/frd-recr-2026',
                            'notes' => 'Sesi wawancara teknis arsitektur sistem bersama VP of Engineering & HR Lead.',
                            'status' => 'scheduled',
                        ]
                    );
                }

                // Bookmark 2 jobs
                if ($index < 2) {
                    SavedJob::firstOrCreate([
                        'user_id' => $user->id,
                        'job_id' => $job->id,
                    ]);
                }
            }
        }
    }
}
