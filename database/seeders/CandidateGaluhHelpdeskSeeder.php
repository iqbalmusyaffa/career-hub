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

class CandidateGaluhHelpdeskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Roles Setup
        $candidateRole = Role::firstOrCreate(['name' => 'Candidate', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        $hrRole = Role::firstOrCreate(['name' => 'HR', 'guard_name' => 'web']);

        // 2. Setup Company & HR for PT Solusi Layanan Terpadu
        $hrUser = User::updateOrCreate(
            ['email' => 'hrd@solusilayananterpadu.co.id'],
            [
                'name' => 'Agus Wicaksono, S.Psi (HR Manager)',
                'password' => Hash::make('password'),
            ]
        );
        $hrUser->assignRole($hrRole);

        $companyProfile = CompanyProfile::updateOrCreate(
            ['company_name' => 'PT Solusi Layanan Terpadu'],
            [
                'user_id' => $hrUser->id,
                'industry' => 'Layanan Pelanggan, IT Support & BPO',
                'company_size' => '100 - 250 Karyawan',
                'website' => 'https://solusilayananterpadu.co.id',
                'phone' => '0341-558899',
                'address' => 'Jl. Besar Ijen No. 45, Oro-oro Dowo, Kec. Klojen, Kota Malang, Jawa Timur 65119',
                'description' => 'PT Solusi Layanan Terpadu adalah perusahaan penyedia solusi IT helpdesk, customer service, technical assistance, dan managed contact center profesional terkemuka di Jawa Timur.',
                'is_verified' => true,
                'benefits' => [
                    'Gaji Pokok Standar / Di Atas UMK Kota Malang 2026',
                    'BPJS Kesehatan & BPJS Ketenagakerjaan Penuh',
                    'Fasilitas Laptop Perusahaan & Peralatan IT Support',
                    'Tunjangan Shift Kerja & Insentif Lembur',
                    'Tunjangan Uang Makan Harian & Snack/Kopi Bar Gratis',
                    'Budget Pelatihan & Sertifikasi IT (CompTIA / MTCNA)',
                    'Bonus Kinerja Tahunan & THR',
                ],
            ]
        );

        $companyBranch = CompanyBranch::updateOrCreate(
            [
                'company_profile_id' => $companyProfile->id,
                'branch_name' => 'Kantor Pusat Ijen Malang',
            ],
            [
                'city' => 'Kota Malang',
                'address' => 'Jl. Besar Ijen No. 45, Oro-oro Dowo, Kec. Klojen, Kota Malang, Jawa Timur 65119',
                'phone' => '0341-558899',
                'email' => 'support@solusilayananterpadu.co.id',
                'is_headquarter' => true,
            ]
        );

        // 3. Create / Update Job: IT Helpdesk & Technical Support Specialist
        $job = Job::updateOrCreate(
            [
                'title' => 'IT Helpdesk & Technical Support Specialist',
                'company_name' => 'PT Solusi Layanan Terpadu',
            ],
            [
                'company_branch_id' => $companyBranch->id,
                'division' => 'Layanan & CS',
                'location' => 'Malang, Jawa Timur',
                'google_maps_link' => 'https://maps.google.com/?q=-7.973333,112.627778',
                'work_type' => 'Full-time',
                'salary' => 'Rp 5.500.000 - Rp 6.800.000 / Bulan (Di Atas UMK Kota Malang 2026)',
                'quota' => 4,
                'experience_level' => 'Junior (1-2 Tahun)',
                'education_level' => 'D3 / S1',
                'major_requirement' => 'Teknik Informatika / Sistem Informasi / Teknik Komputer & Jaringan',
                'skills_required' => 'Troubleshooting Hardware & Software, Jaringan LAN/WiFi, Windows/Mac Support, Ticketing System Jira',
                'description' => "Menangani aduan teknis pengguna terkait perangkat keras (laptop/PC/printer), sistem operasi Windows & macOS, konfigurasi jaringan internet kantor, dan sistem ticketing internal perusahaan.",
                'requirements' => "1. Pendidikan min. D3 / S1 bidang Komputer / Jaringan / TKJ.\n2. Mampu melakukan troubleshooting software, hardware, printer, dan jaringan LAN/WiFi.\n3. Komunikatif, cepat tanggap, dan memiliki etos kerja tinggi dalam melayani pengguna.",
                'benefits' => 'BPJS Kesehatan & Ketenagakerjaan, Tunjangan Lembur, Laptop Kerja IT Support, Pelatihan Sertifikasi IT.',
                'deadline' => now()->addDays(25),
                'status' => 'active',
            ]
        );

        // 4. Create User: Galuh Cahyo (galuhcahyo646@gmail.com)
        $candidateUser = User::updateOrCreate(
            ['email' => 'galuhcahyo646@gmail.com'],
            [
                'name' => 'Galuh Cahyo',
                'password' => Hash::make('password'),
                'email_verified_at' => Carbon::now()->subDays(10),
                'theme_preference' => 'light',
                'email_attendance_reminder_enabled' => true,
                'inapp_attendance_reminder_enabled' => true,
            ]
        );
        $candidateUser->assignRole($candidateRole);
        $candidateUser->assignRole($employeeRole);

        // 5. Create Storage Directory & Sample Documents
        $folder = 'candidate_files/galuh-cahyo_' . $candidateUser->id;
        Storage::disk('public')->makeDirectory($folder);

        $minimalPdf = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>>>endobj\nxref\n0 4\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n190\n%%EOF";

        $docFiles = [
            'cv' => 'cv_galuh_cahyo_it_helpdesk.pdf',
            'ktp' => 'ktp_galuh_cahyo.pdf',
            'ijazah' => 'ijazah_d3_teknik_informatika_galuh.pdf',
            'transcript' => 'transkrip_nilai_akademik_galuh.pdf',
            'certificate' => 'sertifikat_comptia_mtcna_galuh.pdf',
            'portfolio' => 'portofolio_it_support_infrastructure_galuh.pdf',
            'skck' => 'skck_polres_malang_galuh.pdf',
            'health_certificate' => 'surat_keterangan_sehat_galuh.pdf',
            'cover_letter' => 'surat_lamaran_it_helpdesk_galuh.pdf',
            'consent_letter' => 'surat_pernyataan_komitmen_kerja_galuh.pdf',
        ];

        $storedPaths = [];
        foreach ($docFiles as $key => $filename) {
            $filePath = $folder . '/' . $filename;
            Storage::disk('public')->put($filePath, $minimalPdf);
            $storedPaths[$key] = $filePath;
        }

        // Avatar Image
        $avatarPath = $folder . '/photo_galuh_cahyo.png';
        $pngBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        Storage::disk('public')->put($avatarPath, base64_decode($pngBase64));

        // 6. Complete 11-Step Candidate Profile
        $profileData = [
            // STEP 1: Personal Info
            'nickname' => 'Galuh',
            'phone' => '081234567890',
            'birth_place' => 'Malang',
            'dob' => '2000-05-14',
            'gender' => 'male',
            'nationality' => 'WNI (Indonesia)',
            'marital_status' => 'Lajang',
            'religion' => 'Islam',
            'nik' => '3573031405000002',
            'address' => 'Jl. Soekarno Hatta No. 88, Kel. Mojolangu, Kec. Lowokwaru',
            'province' => 'Jawa Timur',
            'city' => 'Kota Malang',
            'postal_code' => '65142',
            'emergency_contact_name' => 'Bambang Cahyono',
            'emergency_contact_phone' => '081398765432',
            'current_salary' => 'Rp 5.500.000',
            'photo' => $avatarPath,
            'photo_path' => $avatarPath,

            // STEP 2: Professional Summary
            'summary' => 'IT Helpdesk & Technical Support Specialist yang tanggap, komunikatif, dan berdedikasi tinggi dengan pengalaman 2+ tahun dalam troubleshooting perangkat keras (PC/Laptop/Printer), administrasi sistem operasi Windows 10/11 dan macOS, konfigurasi jaringan LAN/WiFi (MikroTik/Ubiquiti), serta penanganan tiket insiden IT menggunakan Jira Service Management dan Zendesk. Terbiasa bekerja di bawah SLA ketat dan selalu siap memberikan solusi teknis terbaik bagi operasional perusahaan.',

            // STEP 3: Education
            'educations' => [
                [
                    'level' => 'D3',
                    'institution' => 'Politeknik Negeri Malang (POLINEMA)',
                    'major' => 'Manajemen Informatika / Teknik Komputer',
                    'degree' => 'Ahli Madya Komputer (A.Md.Kom)',
                    'city' => 'Malang',
                    'start_year' => '2018',
                    'end_year' => '2021',
                    'gpa' => '3.76',
                    'description' => 'Lulus dengan predikat Sangat Memuaskan. Fokus studi pada Administrasi Jaringan Komputer, Troubleshooting Hardware, dan Sistem Manajemen Layanan IT.',
                ],
            ],

            // STEP 4: Experience
            'experiences' => [
                [
                    'company' => 'PT Mandiri Solusi Pratama',
                    'position' => 'IT Support & Helpdesk Officer',
                    'job_type' => 'Full-time',
                    'location' => 'Kota Malang',
                    'start_date' => '2022-02-01',
                    'end_date' => '2024-03-31',
                    'is_current' => false,
                    'description' => "Bertanggung jawab atas kelancaran infrastruktur IT dan penanganan kendala pengguna:\n- Menangani dan menyelesaikan 150+ tiket kendala teknis bulanan melalui sistem Jira dengan tingkat kepuasan 98%.\n- Melakukan setup, konfigurasi, dan deployment 80+ unit laptop baru (Windows & Mac) untuk onboarding karyawan.\n- Mengelola konfigurasi jaringan kantor (VLAN, bandwidth management MikroTik, dan access point UniFi).\n- Memastikan ketersediaan backup data rutin dan instalasi software berlisensi.",
                ],
                [
                    'company' => 'Nusantara Tech Solution',
                    'position' => 'Technical Support Intern',
                    'job_type' => 'Internship',
                    'location' => 'Kota Malang',
                    'start_date' => '2021-02-01',
                    'end_date' => '2021-08-31',
                    'is_current' => false,
                    'description' => 'Membantu perakitan PC, instalasi sistem operasi, penggantian sparepart hardware, dan penarikan kabel jaringan LAN.',
                ],
            ],

            // STEP 5: Organizations
            'organizations' => [
                [
                    'name' => 'Komunitas IT Support & Network Malang',
                    'role' => 'Koordinator Divisi Workshop Hardware & Networking',
                    'start_year' => '2020',
                    'end_year' => '2022',
                    'description' => 'Mengorganisir sesi pelatihan troubleshooting PC dan konfigurasi dasar MikroTik bagi anggota komunitas.',
                ],
            ],

            // STEP 6: Skills & Languages
            'skills' => [
                ['name' => 'Hardware Troubleshooting (PC, Laptop, Printer)', 'level' => 'Advanced'],
                ['name' => 'Windows 10/11 & macOS Desktop Administration', 'level' => 'Advanced'],
                ['name' => 'Networking (TCP/IP, LAN, WiFi, MikroTik, Switch)', 'level' => 'Advanced'],
                ['name' => 'IT Ticketing Systems (Jira Service Desk, Freshdesk)', 'level' => 'Advanced'],
                ['name' => 'Google Workspace & Microsoft 365 Admin', 'level' => 'Intermediate'],
                ['name' => 'Remote Desktop Support (AnyDesk, TeamViewer, RDP)', 'level' => 'Advanced'],
                ['name' => 'Antivirus & Endpoint Security Management', 'level' => 'Intermediate'],
                ['name' => 'Asset & Inventory Management', 'level' => 'Intermediate'],
            ],
            'languages' => [
                ['name' => 'Bahasa Indonesia', 'proficiency' => 'Native / Bilingual'],
                ['name' => 'English', 'proficiency' => 'Working Proficiency (Technical Documentation & Reading)'],
            ],

            // STEP 7: Certificates
            'certificates' => [
                [
                    'name' => 'CompTIA A+ Certified - IT Technical Support Specialist',
                    'organization' => 'CompTIA',
                    'issue_date' => '2023-05-10',
                    'expiry_date' => '2026-05-10',
                    'credential_id' => 'COMPTIA-A-992014',
                    'credential_url' => 'https://www.comptia.org/verify',
                ],
                [
                    'name' => 'MikroTik Certified Network Associate (MTCNA)',
                    'organization' => 'MikroTik Latvia',
                    'issue_date' => '2023-08-15',
                    'expiry_date' => '2026-08-15',
                    'credential_id' => 'MTCNA-2308-4421',
                    'credential_url' => 'https://mikrotik.com/certificate/search',
                ],
            ],

            // STEP 8: Portfolios
            'portfolios' => [
                [
                    'title' => 'Office Network Architecture & Helpdesk Deployment',
                    'category' => 'IT Infrastructure & Network Engineering',
                    'project_url' => 'https://github.com/galuhcahyo/office-network-setup',
                    'description' => 'Dokumentasi perancangan topologi jaringan kantor 3 lantai dengan pembagian VLAN departemen, MikroTik bandwidth management, dan implementasi sistem tiket Jira Service Management.',
                ],
            ],

            // STEP 9: Achievements
            'achievements' => [
                [
                    'title' => 'Best Employee of the Quarter - IT Support Excellence 2023',
                    'year' => '2023',
                    'description' => 'Penghargaan atas pencapaian resolusi tiket insiden 99.2% dalam batas waktu SLA di PT Mandiri Solusi Pratama.',
                ],
            ],

            // STEP 9b: Professional References
            'references' => [
                [
                    'name' => 'Ir. Hendra Kusuma, M.T',
                    'company' => 'Politeknik Negeri Malang',
                    'position' => 'Dosen Pembimbing Akademik & Kepala Lab Jaringan Komputer',
                    'phone' => '081234998811',
                    'email' => 'hendra.kusuma@polinema.ac.id',
                ],
            ],

            // STEP 10: Career Preferences
            'job_preferences' => [
                'expected_positions' => 'IT Helpdesk, IT Support Specialist, Network Administrator Junior',
                'job_types' => ['Full-time', 'WFO'],
                'expected_salary_currency' => 'IDR',
                'expected_salary' => '6000000',
                'preferred_locations' => 'Kota Malang, Surabaya, Jawa Timur',
                'available_start' => 'Segera (Available Immediately)',
                'work_mobility' => 'Bersedia Kerja di Kantor (WFO) & Sistem Shift',
                'notice_period' => 'Tersedia Segera',
            ],
            'social_links' => [
                'linkedin' => 'https://linkedin.com/in/galuhcahyo',
                'github' => 'https://github.com/galuhcahyo',
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
            ['document_type' => 'cv', 'title' => 'Curriculum Vitae (CV) - Galuh Cahyo.pdf', 'file_path' => $storedPaths['cv']],
            ['document_type' => 'ktp', 'title' => 'KTP Resmi - Galuh Cahyo.pdf', 'file_path' => $storedPaths['ktp']],
            ['document_type' => 'ijazah', 'title' => 'Ijazah D3 Teknik Informatika - Galuh Cahyo.pdf', 'file_path' => $storedPaths['ijazah']],
            ['document_type' => 'transkrip', 'title' => 'Transkrip Nilai Akademik Resmi (IPK 3.76).pdf', 'file_path' => $storedPaths['transcript']],
            ['document_type' => 'certificate', 'title' => 'Sertifikat Resmi CompTIA A+ & MTCNA.pdf', 'file_path' => $storedPaths['certificate']],
            ['document_type' => 'portfolio', 'title' => 'Portofolio IT Infrastructure & Network Setup.pdf', 'file_path' => $storedPaths['portfolio']],
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
                'status' => 'accepted', // Hired status
            ]
        );

        // 9. Offer Letter (Diterima Kerja dengan Gaji & Fasilitas Lengkap)
        OfferLetter::updateOrCreate(
            [
                'application_id' => $application->id,
            ],
            [
                'user_id' => $candidateUser->id,
                'job_id' => $job->id,
                'position_title' => 'IT Helpdesk & Technical Support Specialist',
                'offered_salary' => 'Rp 6.200.000 / Bulan (Gaji Pokok Rp 5.500.000 + Tunjangan Makan & Shift Rp 700.000)',
                'start_date' => Carbon::now()->subDays(3)->toDateString(),
                'expiration_date' => Carbon::now()->addDays(14)->toDateString(),
                'work_location' => 'Kantor Pusat Ijen Malang, Jl. Besar Ijen No. 45, Kota Malang',
                'benefits_summary' => "1. Gaji Pokok di atas standar UMK Kota Malang 2026.\n2. BPJS Kesehatan & BPJS Ketenagakerjaan (JKK, JKM, JHT, JP).\n3. Fasilitas Laptop Kerja IT Support & Toolkit Hardware.\n4. Tunjangan Shift & Insentif Lembur.\n5. Tunjangan Makan Siang Harian & Akses Pantry (Free Coffee/Snacks).\n6. Hak Cuti Tahunan 12 Hari Kerja.\n7. Budget Pelatihan & Sertifikasi IT.",
                'additional_notes' => 'Selamat bergabung bersama PT Solusi Layanan Terpadu! Kami yakin dedikasi dan keahlian teknis Anda akan memberikan dukungan prima bagi seluruh operasional perusahaan.',
                'status' => 'accepted',
                'candidate_response_note' => 'Terima kasih atas kepercayaan dan penawaran kerja ini. Saya dengan senang hati menerima tawaran ini dan siap bekerja secara optimal sebagai IT Helpdesk & Technical Support Specialist.',
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
                'title' => 'Perjanjian Kerja Waktu Tertentu (PKWT) - IT Helpdesk & Technical Support Specialist',
                'contract_number' => 'PKWT/SLT/2026/XI/104',
                'start_date' => Carbon::now()->subDays(3)->toDateString(),
                'end_date' => Carbon::now()->addYear()->toDateString(),
                'stipend_or_salary' => 'Rp 6.200.000 / Bulan',
                'terms_content' => "PASAL 1: KEDUDUKAN & JABATAN\nKaryawan dipekerjakan sebagai IT Helpdesk & Technical Support Specialist pada Divisi Layanan & CS PT Solusi Layanan Terpadu.\n\nPASAL 2: UPAH & HAK KARYAWAN\n1. Karyawan menerima upah bulanan sebesar Rp 6.200.000 (Gaji pokok + tunjangan tetap).\n2. Karyawan memperoleh hak perlindungan BPJS Kesehatan dan BPJS Ketenagakerjaan sesuai perundang-undangan.\n\nPASAL 3: PRESENSI & DISIPLIN KERJA\n1. Jam kerja kantor terbagi dalam jadwal operasional shift Senin sampai Jumat.\n2. Karyawan wajib melakukan verifikasi presensi kehadiran harian melalui modul kamera selfie wajah dan deteksi GPS geofence kantor.",
                'signer_name' => 'Galuh Cahyo',
                'signer_ip' => '127.0.0.1',
                'signed_at' => Carbon::now()->subDays(3),
                'status' => 'signed',
            ]
        );

        // 11. Technical Interview Record
        Interview::updateOrCreate(
            ['application_id' => $application->id],
            [
                'scheduled_at' => Carbon::now()->subDays(6)->setHour(10)->setMinute(30),
                'type' => 'offline',
                'location_or_link' => 'Kantor Pusat Ijen Malang, Lantai 2 Ruang Interview B',
                'notes' => 'Wawancara teknis & simulasi troubleshooting: Kandidat sangat mahir dalam mendiagnosis masalah jaringan LAN, routing MikroTik, serta memiliki komunikasi yang santun dan solutif. Hasil evaluasi: Sangat Direkomendasikan (Lulus).',
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
                'bank_account_number' => '3150882941',
                'bank_account_holder' => 'GALUH CAHYO',
                'npwp_number' => '74.819.382.1-652.000',
                'bpjs_kesehatan_number' => '0001928471928',
                'bpjs_ketenagakerjaan_number' => '23019284719',
                'family_card_number' => '3573031201040008',
                'verification_status' => 'verified',
                'verified_at' => Carbon::now()->subDays(3),
            ]
        );

        // 13. Bookmark Job for Saved Jobs menu
        SavedJob::firstOrCreate([
            'user_id' => $candidateUser->id,
            'job_id' => $job->id,
        ]);

        // 14. Sample Attendance Records (Yesterday completed, Today ready for live camera test)
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
                'check_in_time' => '08:12:15',
                'check_in_latitude' => -7.973333,
                'check_in_longitude' => 112.627778,
                'check_in_address' => 'Jl. Besar Ijen No. 45, Oro-oro Dowo, Kec. Klojen, Kota Malang',
                'check_in_status' => 'on_time',
                'check_in_notes' => 'Pengecekan server internal dan pemeliharaan switch jaringan lantai 1.',
                'check_out_time' => '17:32:45',
                'check_out_latitude' => -7.973333,
                'check_out_longitude' => 112.627778,
                'check_out_address' => 'Jl. Besar Ijen No. 45, Oro-oro Dowo, Kec. Klojen, Kota Malang',
                'work_duration_minutes' => 560, // 9 hours 20 minutes
                'check_out_notes' => 'Semua tiket keluhan harian berhasil terselesaikan dengan baik.',
            ]
        );
    }
}

