<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationAgreement;
use App\Models\CandidateDocument;
use App\Models\CandidateOnboarding;
use App\Models\CandidateProfile;
use App\Models\CompanyBranch;
use App\Models\CompanyProfile;
use App\Models\EmployeeAttendance;
use App\Models\Interview;
use App\Models\Job;
use App\Models\OfferLetter;
use App\Models\SavedJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class CandidateIqbalJuniorBackendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Roles Setup
        $candidateRole = Role::firstOrCreate(['name' => 'Candidate', 'guard_name' => 'web']);
        $hrRole = Role::firstOrCreate(['name' => 'HR', 'guard_name' => 'web']);

        // 2. Create New Company & HR Representative
        $hrUser = User::updateOrCreate(
            ['email' => 'recruitment@sinergicloud.id'],
            [
                'name' => 'Rian Pratama, S.Kom (HR Lead)',
                'password' => Hash::make('password'),
            ]
        );
        $hrUser->assignRole($hrRole);

        $companyProfile = CompanyProfile::updateOrCreate(
            ['user_id' => $hrUser->id],
            [
                'company_name' => 'PT Sinergi Cloud Nusantara',
                'industry' => 'Teknologi Informasi & Cloud Solutions',
                'company_size' => '50 - 100 Karyawan',
                'website' => 'https://sinergicloud.id',
                'phone' => '021-52908877',
                'address' => 'Cyber 2 Tower Lt. 18, Jl. H.R. Rasuna Said Blok X-5, Kuningan, Jakarta Selatan 12950',
                'description' => 'PT Sinergi Cloud Nusantara (CloudNusantara) adalah perusahaan teknologi modern yang berfokus pada penyediaan infrastruktur cloud berkinerja tinggi, API gateway berkecepatan tinggi, dan pengembangan sistem backend SaaS skala enterprise.',
                'is_verified' => true,
                'benefits' => [
                    'Gaji Pokok Standar / Di Atas UMK 2026',
                    'BPJS Kesehatan & BPJS Ketenagakerjaan Penuh',
                    'Fasilitas Laptop Perusahaan (MacBook Pro / ThinkPad Dev)',
                    'Tunjangan Internet & Pulsa Bulanan',
                    'Tunjangan Makan Siang Harian & Snack/Kopi Bar Gratis',
                    'Asuransi Kesehatan Swasta Tambahan',
                    'Budget Belajar & Sertifikasi Profesional',
                    'Bonus Kinerja Tahunan & THR',
                    'Ruang Game & Rekreasi (PS5 & Tenis Meja)',
                ],
            ]
        );

        $companyBranch = CompanyBranch::updateOrCreate(
            [
                'company_profile_id' => $companyProfile->id,
                'branch_name' => 'Kantor Pusat Cyber 2 Tower Jakarta',
            ],
            [
                'city' => 'Jakarta Selatan',
                'address' => 'Cyber 2 Tower Lt. 18, Jl. H.R. Rasuna Said Blok X-5, Kuningan, Jakarta Selatan 12950',
                'phone' => '021-52908877',
                'email' => 'office.jkt@sinergicloud.id',
                'is_headquarter' => true,
            ]
        );

        // 3. Create Job Posting: Junior Backend Developer (Salary adhering to UMK + rich facilities)
        $job = Job::updateOrCreate(
            [
                'company_name' => 'PT Sinergi Cloud Nusantara',
                'title' => 'Junior Backend Developer',
            ],
            [
                'company_branch_id' => $companyBranch->id,
                'division' => 'Teknologi & IT',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'google_maps_link' => 'https://maps.google.com/?q=-6.224167,106.831944',
                'work_type' => 'Full-time',
                'salary' => 'Rp 5.750.000 - Rp 6.800.000 / Bulan (Sesuai UMK DKI Jakarta 2026: Rp 5.729.876)',
                'quota' => 3,
                'experience_level' => 'Entry-Level / 0 - 2 Tahun',
                'education_level' => 'D3 / S1',
                'major_requirement' => 'Teknik Informatika / Ilmu Komputer / Sistem Informasi / Rekayasa Perangkat Lunak',
                'skills_required' => 'PHP, Laravel 11, MySQL, RESTful API, Git, Redis, Linux / VPS Server Management',
                'gender_requirement' => 'Semua Gender',
                'age_range' => '20 - 28 Tahun',
                'deadline' => Carbon::now()->addDays(30)->toDateString(),
                'status' => 'active',
                'description' => "Sebagai Junior Backend Developer di PT Sinergi Cloud Nusantara, Anda akan menjadi bagian dari tim Core Engineering kami yang bertanggung jawab merancang, membangun, dan memelihara backend service serta RESTful API berskala tinggi.\n\nTanggung Jawab Utama:\n- Membangun dan mengoptimalkan RESTful API menggunakan PHP (Laravel) dan database MySQL.\n- Mengembangkan arsitektur database relasional yang efisien, membuat migrasi skema, dan mengoptimalkan query yang kompleks.\n- Mengintegrasikan sistem dengan third-party services (payment gateway, cloud storage, SMS/email gateway).\n- Mengelola caching menggunakan Redis untuk meningkatkan throughput dan performa sistem.\n- Melakukan deployment dan monitoring rutin aplikasi ke server VPS Linux (Ubuntu / Nginx).\n- Menulis unit testing dan dokumentasi API yang rapi menggunakan Postman / Swagger.\n- Berkolaborasi secara aktif dalam sprint tim agile (Scrum) bersama Frontend Developer, QA Engineer, dan Product Manager.",
                'requirements' => "- Pendidikan minimal D3 atau S1 di bidang Teknik Informatika, Ilmu Komputer, Sistem Informasi, atau bidang terkait.\n- Menguasai dasar-dasar pemrograman PHP 8.x dan framework Laravel secara mendalam.\n- Memahami prinsip Object-Oriented Programming (OOP), MVC, dan clean code.\n- Terbiasa merancang database MySQL / PostgreSQL (relasi tabel, foreign keys, indexing, dan transaksi database).\n- Memiliki pengalaman membangun REST API dengan format respon JSON standar dan autentikasi token (Laravel Sanctum / JWT).\n- Terbiasa menggunakan Git version control (Branching, Commit convention, Pull Request).\n- Nilai tambah besar (Bonus Point): Pernah mengelola server VPS Linux (Ubuntu/Debian), konfigurasi Nginx, Redis, dan Docker dasar.\n- Memiliki kemauan belajar yang tinggi, komunikatif, jujur, dan bersemangat menyelesaikan masalah teknis.",
                'benefits' => "💰 Gaji Pokok Sesuai & Di Atas UMK DKI Jakarta 2026 (Rp 5.750.000 - Rp 6.800.000 / bulan)\n🏥 Jaminan Sosial Lengkap: BPJS Kesehatan & BPJS Ketenagakerjaan (JKK, JKM, JHT, JP) ditanggung perusahaan penuh\n🩺 Asuransi Kesehatan Swasta Tambahan (fasilitas rawat inap dan rawat jalan)\n💻 Fasilitas Laptop Kerja Perusahaan (MacBook Pro M-Series / Lenovo ThinkPad Developer Edition 16GB RAM)\n📶 Tunjangan Komunikasi & Paket Internet Bulanan (Rp 350.000 / bulan)\n🍱 Makan Siang Harian Disediakan & Akses Bebas Pantry (Free Premium Coffee & Snack Bar)\n🏖️ Hak Cuti Tahunan 12 Hari Kerja + Cuti Bersama & Cuti Khusus Berbayar\n🎓 Program Pengembangan Diri & Budget Sertifikasi Profesional (AWS, Laravel Certified Developer, dll.)\n📈 Tunjangan Hari Raya (THR) & Bonus Kinerja Tahunan Berdasarkan Performa Tim\n🎮 Fasilitas Kantor Modern: Ruang Santai, Game Room (PS5, Tenis Meja), dan Ruang Istirahat Nyaman",
            ]
        );

        // 4. Create or Update Candidate User: vpspaneliqbal@gmail.com
        $candidateUser = User::updateOrCreate(
            ['email' => 'vpspaneliqbal@gmail.com'],
            [
                'name' => 'Iqbal Musyaffa',
                'password' => Hash::make('password'),
                'email_attendance_reminder_enabled' => true,
                'inapp_attendance_reminder_enabled' => true,
            ]
        );
        $candidateUser->assignRole($candidateRole);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        $candidateUser->assignRole($employeeRole);

        // 5. Create Storage Directory & Minimal Valid Sample PDFs
        $folder = 'candidate_files/iqbal-musyaffa_' . $candidateUser->id;
        Storage::disk('public')->makeDirectory($folder);

        $minimalPdf = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>>>endobj\nxref\n0 4\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n190\n%%EOF";

        $docFiles = [
            'cv' => 'cv_iqbal_musyaffa_backend.pdf',
            'ktp' => 'ktp_iqbal_musyaffa.pdf',
            'ijazah' => 'ijazah_s1_teknik_informatika_iqbal.pdf',
            'transcript' => 'transkrip_nilai_akademik_iqbal.pdf',
            'certificate' => 'sertifikat_laravel_backend_iqbal.pdf',
            'portfolio' => 'portofolio_backend_api_projects_iqbal.pdf',
            'skck' => 'skck_polres_iqbal_musyaffa.pdf',
            'health_certificate' => 'surat_keterangan_sehat_iqbal.pdf',
            'cover_letter' => 'surat_lamaran_backend_developer_iqbal.pdf',
            'consent_letter' => 'surat_pernyataan_komitmen_kerja_iqbal.pdf',
        ];

        $storedPaths = [];
        foreach ($docFiles as $key => $filename) {
            $filePath = $folder . '/' . $filename;
            Storage::disk('public')->put($filePath, $minimalPdf);
            $storedPaths[$key] = $filePath;
        }

        // Avatar Image
        $avatarPath = $folder . '/photo_iqbal_musyaffa.png';
        $pngBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        Storage::disk('public')->put($avatarPath, base64_decode($pngBase64));

        // 6. Complete 11-Step Candidate Profile
        $profileData = [
            // STEP 1: Personal Info
            'nickname' => 'Iqbal',
            'phone' => '085712345678',
            'birth_place' => 'Jakarta',
            'dob' => '2001-08-20',
            'gender' => 'male',
            'nationality' => 'WNI (Indonesia)',
            'marital_status' => 'Lajang',
            'religion' => 'Islam',
            'nik' => '3174022008010005',
            'address' => 'Jl. Tebet Barat Dalam Raya No. 42, RT 004 / RW 002, Kel. Tebet Barat',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'postal_code' => '12810',
            'emergency_contact_name' => 'Fauzi Musyaffa',
            'emergency_contact_phone' => '081298765432',
            'current_salary' => 'Rp 5.800.000',
            'photo' => $avatarPath,
            'photo_path' => $avatarPath,

            // STEP 2: Professional Summary
            'summary' => 'Junior Backend Developer yang berdedikasi tinggi dan antusias dengan spesialisasi pengembangan RESTful API berkinerja tinggi menggunakan ekosistem PHP (Laravel 11), database relasional MySQL, Redis caching, dan administrasi server Linux / VPS. Memiliki pemahaman kuat mengenai clean code, MVC pattern, optimasi query database, serta penerapan keamanan autentikasi API. Berpengalaman dalam mengelola deployment aplikasi ke environment server Ubuntu/Nginx dan siap berkontribusi secara maksimal untuk pertumbuhan produk perusahaan.',

            // STEP 3: Education
            'educations' => [
                [
                    'level' => 'S1',
                    'institution' => 'Universitas Pembangunan Nasional Veteran Jakarta',
                    'major' => 'Teknik Informatika',
                    'degree' => 'Sarjana Komputer (S.Kom)',
                    'city' => 'Jakarta Selatan',
                    'start_year' => '2019',
                    'end_year' => '2023',
                    'gpa' => '3.82',
                    'description' => 'Lulus dengan predikat Sangat Memuaskan (Cum Laude). Tugas akhir berfokus pada Rancang Bangun RESTful API Skalabel dengan Optimasi Redis Cache dan Database Indexing.',
                ],
            ],

            // STEP 4: Experience
            'experiences' => [
                [
                    'company' => 'PT Kreasi Solusi Teknologi',
                    'position' => 'Junior Backend Engineer',
                    'job_type' => 'Full-time',
                    'location' => 'Jakarta Selatan',
                    'start_date' => '2023-09-01',
                    'end_date' => '2024-09-30',
                    'is_current' => false,
                    'description' => "Bertanggung jawab atas pengembangan dan pemeliharaan backend RESTful API aplikasi internal:\n- Mengembangkan 25+ endpoint REST API modular menggunakan framework Laravel 10/11.\n- Mengoptimalkan query database MySQL yang mempercepat waktu respons API sebesar 35%.\n- Mengintegrasikan payment gateway otomatis (Midtrans Snap & Core API) dan webhook notifikasi transaksi.\n- Mengelola server VPS Ubuntu untuk deployment CI/CD otomatis menggunakan Git hooks.",
                ],
                [
                    'company' => 'Nusantara Digital Lab',
                    'position' => 'Backend Developer Intern',
                    'job_type' => 'Internship',
                    'location' => 'Jakarta Pusat',
                    'start_date' => '2023-01-10',
                    'end_date' => '2023-07-15',
                    'is_current' => false,
                    'description' => 'Membantu tim senior dalam merancang arsitektur database, membuat dokumentasi API Swagger, dan pengujian unit test menggunakan Pest PHP.',
                ],
            ],

            // STEP 5: Organizations
            'organizations' => [
                [
                    'name' => 'Himpunan Mahasiswa Informatika (HMIF)',
                    'role' => 'Ketua Divisi Riset & Pengembangan Teknologi',
                    'start_year' => '2021',
                    'end_year' => '2022',
                    'description' => 'Memimpin program pelatihan backend web development bagi 100+ mahasiswa baru dan mengelola website portal HMIF.',
                ],
            ],

            // STEP 6: Skills & Languages
            'skills' => [
                ['name' => 'PHP (8.2+)', 'level' => 'Advanced'],
                ['name' => 'Laravel 11 Framework', 'level' => 'Advanced'],
                ['name' => 'MySQL Database Architecture & Optimization', 'level' => 'Advanced'],
                ['name' => 'RESTful API Design & JSON Standards', 'level' => 'Advanced'],
                ['name' => 'Git Version Control (Branching, PRs, Merge)', 'level' => 'Advanced'],
                ['name' => 'Linux / VPS Server Management (Ubuntu, Nginx)', 'level' => 'Intermediate'],
                ['name' => 'Redis In-Memory Caching', 'level' => 'Intermediate'],
                ['name' => 'Postman API Testing & Documentation', 'level' => 'Advanced'],
                ['name' => 'Docker & Containerization Basics', 'level' => 'Intermediate'],
            ],
            'languages' => [
                ['name' => 'Bahasa Indonesia', 'proficiency' => 'Native / Bilingual'],
                ['name' => 'English', 'proficiency' => 'Professional Working (Technical Documentation & Communication)'],
            ],

            // STEP 7: Certificates
            'certificates' => [
                [
                    'name' => 'Laravel Certified Developer - Web Applications Mastery',
                    'organization' => 'Laravel Certification Institute',
                    'issue_date' => '2024-04-15',
                    'expiry_date' => '2027-04-15',
                    'credential_id' => 'LC-2024-884920',
                    'credential_url' => 'https://certification.laravel.com/verify/LC-2024-884920',
                ],
                [
                    'name' => 'AWS Certified Cloud Practitioner',
                    'organization' => 'Amazon Web Services (AWS)',
                    'issue_date' => '2024-06-20',
                    'expiry_date' => '2027-06-20',
                    'credential_id' => 'AWS-CCP-992144',
                    'credential_url' => 'https://aws.amazon.com/verification',
                ],
            ],

            // STEP 8: Portfolios
            'portfolios' => [
                [
                    'title' => 'E-Commerce High-Concurrency Backend API',
                    'category' => 'Backend Engineering & API Gateway',
                    'project_url' => 'https://github.com/vpspaneliqbal/ecommerce-api-core',
                    'description' => 'Sistem backend e-commerce yang dibangun dengan Laravel 11, dilengkapi fitur order inventory lock dengan Redis, payment gateway Midtrans, dan rate limiting ketat.',
                ],
                [
                    'title' => 'Automated Linux VPS Monitoring & Backup Daemon',
                    'category' => 'System Administration & DevOps',
                    'project_url' => 'https://github.com/vpspaneliqbal/vps-backup-bot',
                    'description' => 'Bot otomatis berbasis PHP CLI untuk memantau penggunaan resource RAM/CPU server VPS, backup database MySQL otomatis ke AWS S3, dan alert notifikasi via Telegram.',
                ],
            ],

            // STEP 9: Achievements
            'achievements' => [
                [
                    'title' => 'Juara 2 National Hackathon Solusi Digital Nusantara 2024',
                    'year' => '2024',
                    'description' => 'Membangun prototipe microservices logistik terintegrasi dengan throughput tinggi dalam waktu 36 jam nonstop.',
                ],
            ],

            // STEP 9b: Professional References
            'references' => [
                [
                    'name' => 'Dr. Bambang Setiawan, M.Kom',
                    'company' => 'Fakultas Ilmu Komputer UPNVJ',
                    'position' => 'Dosen Pembimbing Skripsi & Koordinator Lab Software Engineering',
                    'phone' => '081288991122',
                    'email' => 'bambang.setiawan@upnvj.ac.id',
                ],
            ],

            // STEP 10: Career Preferences
            'job_preferences' => [
                'expected_positions' => 'Junior Backend Developer, Backend Software Engineer, API Engineer',
                'job_types' => ['Full-time', 'Hybrid'],
                'expected_salary_currency' => 'IDR',
                'expected_salary' => '6000000',
                'preferred_locations' => 'Jakarta Selatan, Jakarta Pusat, DKI Jakarta',
                'available_start' => 'Segera (Available Immediately)',
                'work_mobility' => 'Bersedia Kerja di Kantor (WFO) maupun Hybrid',
                'notice_period' => 'Tersedia Segera',
            ],
            'social_links' => [
                'linkedin' => 'https://linkedin.com/in/iqbalmusyaffa',
                'github' => 'https://github.com/iqbalmusyaffa',
                'portfolio' => 'https://iqbalmusyaffa.dev',
            ],

            // STEP 11: Document Paths
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
            ['user_id' => $candidateUser->id],
            $profileData
        );

        // 7. Synchronize Candidate Vault
        $vaultItems = [
            ['document_type' => 'cv', 'title' => 'Curriculum Vitae (CV) - Iqbal Musyaffa.pdf', 'file_path' => $storedPaths['cv']],
            ['document_type' => 'ktp', 'title' => 'KTP / Identitas Resmi - Iqbal Musyaffa.pdf', 'file_path' => $storedPaths['ktp']],
            ['document_type' => 'ijazah', 'title' => 'Ijazah S1 Teknik Informatika - Iqbal Musyaffa.pdf', 'file_path' => $storedPaths['ijazah']],
            ['document_type' => 'transkrip', 'title' => 'Transkrip Nilai Akademik Resmi (IPK 3.82).pdf', 'file_path' => $storedPaths['transcript']],
            ['document_type' => 'certificate', 'title' => 'Sertifikat Resmi Laravel Developer Certified.pdf', 'file_path' => $storedPaths['certificate']],
            ['document_type' => 'portfolio', 'title' => 'Portofolio Backend & API Engineering Projects.pdf', 'file_path' => $storedPaths['portfolio']],
            ['document_type' => 'skck', 'title' => 'Surat Keterangan Catatan Kepolisian (SKCK).pdf', 'file_path' => $storedPaths['skck']],
        ];

        foreach ($vaultItems as $item) {
            CandidateDocument::updateOrCreate(
                [
                    'user_id' => $candidateUser->id,
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

        // 8. Connect Job Application (Status: Accepted / Hired as Regular Employee)
        $application = Application::updateOrCreate(
            [
                'user_id' => $candidateUser->id,
                'job_id' => $job->id,
            ],
            [
                'status' => 'accepted', // Hired employee status
            ]
        );

        // 9. Offer Letter (Gaji Sesuai UMK + Fasilitas Lengkap)
        OfferLetter::updateOrCreate(
            [
                'application_id' => $application->id,
            ],
            [
                'user_id' => $candidateUser->id,
                'job_id' => $job->id,
                'position_title' => 'Junior Backend Developer',
                'offered_salary' => 'Rp 6.200.000 / Bulan (Gaji Pokok Rp 5.750.000 Sesuai UMK + Tunjangan Makan & Internet Rp 450.000)',
                'start_date' => Carbon::now()->subDays(2)->toDateString(),
                'expiration_date' => Carbon::now()->addDays(14)->toDateString(),
                'work_location' => 'Cyber 2 Tower Lt. 18, Jakarta Selatan (Hybrid WFO/WFH)',
                'benefits_summary' => "1. Gaji Pokok sesuai standar UMK DKI Jakarta 2026.\n2. BPJS Kesehatan & BPJS Ketenagakerjaan (JKK, JKM, JHT, JP) ditanggung perusahaan.\n3. Fasilitas Laptop Kerja MacBook Pro Developer Edition.\n4. Tunjangan Internet & Pulsa Bulanan Rp 350.000.\n5. Asuransi Kesehatan Tambahan Swasta (Rawat Inap & Jalan).\n6. Makan Siang Harian & Akses Bebas Pantry (Free Coffee & Snacks).\n7. Hak Cuti Tahunan 12 Hari Kerja.\n8. Budget Pembelajaran & Sertifikasi Profesional (AWS/Laravel).",
                'additional_notes' => 'Selamat bergabung bersama keluarga besar PT Sinergi Cloud Nusantara! Kami sangat menantikan kontribusi dan ide inovatif Anda dalam membangun arsitektur backend masa depan.',
                'status' => 'accepted',
                'candidate_response_note' => 'Dengan penuh rasa syukur, saya menyatakan menerima penawaran kerja ini dengan penuh komitmen dan siap memulai peran sebagai Junior Backend Developer.',
            ]
        );

        // 10. Digital Employment Agreement (PKWT)
        ApplicationAgreement::updateOrCreate(
            [
                'application_id' => $application->id,
            ],
            [
                'user_id' => $candidateUser->id,
                'agreement_type' => 'employment_contract',
                'title' => 'Perjanjian Kerja Waktu Tertentu (PKWT) - Junior Backend Developer',
                'contract_number' => 'PKWT/SCN/2026/X/088',
                'start_date' => Carbon::now()->subDays(2)->toDateString(),
                'end_date' => Carbon::now()->addYear()->toDateString(),
                'stipend_or_salary' => 'Rp 6.200.000 / Bulan (Sesuai UMK)',
                'terms_content' => "PASAL 1: KEDUDUKAN & JABATAN\nKaryawan dipekerjakan sebagai Junior Backend Developer pada Divisi Teknologi & IT PT Sinergi Cloud Nusantara.\n\nPASAL 2: UPAH & FASILITAS KERJA\n1. Karyawan menerima upah bulanan sebesar Rp 6.200.000 (Gaji pokok sesuai UMK DKI Jakarta 2026 + tunjangan tetap).\n2. Karyawan berhak atas fasilitas BPJS Kesehatan, BPJS Ketenagakerjaan, Laptop MacBook Pro, dan tunjangan internet bulanan.\n\nPASAL 3: WAKTU KERJA & KEHADIRAN\n1. Jam kerja standar adalah Senin sampai Jumat pukul 08:30 - 17:30 WIB.\n2. Karyawan wajib melakukan verifikasi presensi harian melalui modul kamera selfie wajah dan deteksi GPS geofence.",
                'signer_name' => 'Iqbal Musyaffa',
                'signer_ip' => '127.0.0.1',
                'signed_at' => Carbon::now()->subDays(2),
                'status' => 'signed',
            ]
        );

        // 11. Technical Interview Record
        Interview::updateOrCreate(
            ['application_id' => $application->id],
            [
                'scheduled_at' => Carbon::now()->subDays(5)->setHour(14)->setMinute(0),
                'type' => 'online',
                'location_or_link' => 'https://meet.google.com/scn-iqb-2026',
                'notes' => 'Sesi wawancara teknis backend: Kandidat menunjukkan pemahaman mendalam tentang Laravel, arsitektur database MySQL, serta kemampuan mengelola server VPS Linux yang sangat baik. Direkomendasikan untuk offering gaji sesuai UMK.',
                'status' => 'completed',
            ]
        );

        // 12. Candidate Onboarding Profile
        CandidateOnboarding::updateOrCreate(
            [
                'application_id' => $application->id,
            ],
            [
                'user_id' => $candidateUser->id,
                'bank_name' => 'Bank Central Asia (BCA)',
                'bank_account_number' => '8820491823',
                'bank_account_holder' => 'IQBAL MUSYAFFA',
                'npwp_number' => '82.910.482.1-014.000',
                'bpjs_kesehatan_number' => '0002948192841',
                'bpjs_ketenagakerjaan_number' => '24018294810',
                'family_card_number' => '3174021203050012',
                'verification_status' => 'verified',
                'verified_at' => Carbon::now()->subDays(2),
            ]
        );

        // 13. Bookmark Job for Saved Jobs menu
        SavedJob::firstOrCreate([
            'user_id' => $candidateUser->id,
            'job_id' => $job->id,
        ]);

        // 14. Sample Attendance Records (for testing the Employee Camera & Geolocation module)
        // Record 1: Yesterday attendance (Full: Check-in + Check-out)
        $yesterday = Carbon::yesterday()->toDateString();
        EmployeeAttendance::updateOrCreate(
            [
                'user_id' => $candidateUser->id,
                'date' => $yesterday,
            ],
            [
                'company_profile_id' => $companyProfile->id,
                'company_branch_id' => $companyBranch->id,
                'work_type' => 'wfo',
                'status' => 'present',
                'check_in_time' => '08:18:24',
                'check_in_latitude' => -6.224167,
                'check_in_longitude' => 106.831944,
                'check_in_address' => 'Cyber 2 Tower, Jl. H.R. Rasuna Said, Kuningan, Jakarta Selatan',
                'check_in_status' => 'on_time',
                'check_in_notes' => 'Onboarding teknis pengenalan repository backend dan setup local environment.',
                'check_out_time' => '17:35:10',
                'check_out_latitude' => -6.224167,
                'check_out_longitude' => 106.831944,
                'check_out_address' => 'Cyber 2 Tower, Jl. H.R. Rasuna Said, Kuningan, Jakarta Selatan',
                'work_duration_minutes' => 556, // 9 hours 16 minutes
                'check_out_notes' => 'Selesai setup local database dan review endpoint auth.',
            ]
        );

        // Record 2: Today's active attendance (Checked-in, currently working!)
        $today = Carbon::today()->toDateString();
        EmployeeAttendance::updateOrCreate(
            [
                'user_id' => $candidateUser->id,
                'date' => $today,
            ],
            [
                'company_profile_id' => $companyProfile->id,
                'company_branch_id' => $companyBranch->id,
                'work_type' => 'wfo',
                'status' => 'present',
                'check_in_time' => '08:14:50',
                'check_in_latitude' => -6.224167,
                'check_in_longitude' => 106.831944,
                'check_in_address' => 'Cyber 2 Tower, Jl. H.R. Rasuna Said, Kuningan, Jakarta Selatan',
                'check_in_status' => 'on_time',
                'check_in_notes' => 'Memulai sprint pertama: Implementasi API rate limiting dan Redis cache.',
            ]
        );
    }
}
