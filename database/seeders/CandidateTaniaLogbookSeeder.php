<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationAgreement;
use App\Models\CandidateProfile;
use App\Models\CompanyHoliday;
use App\Models\CompanyProfile;
use App\Models\CompanyTeamMember;
use App\Models\InternshipBatch;
use App\Models\InternshipLogbook;
use App\Models\InternshipPeriod;
use App\Models\Job;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CandidateTaniaLogbookSeeder extends Seeder
{
    /**
     * Seed dedicated intern account for dannytania5@gmail.com with complete
     * approved logbooks, approved leave requests, and user notifications.
     */
    public function run(): void
    {
        $candidateRole = Role::firstOrCreate(['name' => 'Candidate', 'guard_name' => 'web']);
        $mentorRole = Role::firstOrCreate(['name' => 'Mentor', 'guard_name' => 'web']);

        // 1. Create or Find Candidate User (dannytania5@gmail.com)
        $user = User::updateOrCreate(
            ['email' => 'dannytania5@gmail.com'],
            [
                'name' => 'Tania Danny',
                'password' => Hash::make('password'),
            ]
        );
        if (!$user->hasRole('Candidate')) {
            $user->assignRole($candidateRole);
        }

        // 2. Setup Candidate Profile
        CandidateProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nickname' => 'Tania',
                'phone' => '081298765432',
                'birth_place' => 'Jakarta',
                'dob' => '2003-04-18',
                'gender' => 'female',
                'nationality' => 'WNI (Indonesia)',
                'marital_status' => 'Lajang',
                'religion' => 'Islam',
                'address' => 'Jl. Tebet Barat Raya No. 45, Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'postal_code' => '12810',
                'summary' => 'Peserta magang Software Engineering & Fullstack Web Developer yang bersemangat mempelajari ekosistem modern Laravel, Vue.js, Tailwind CSS, dan arsitektur database relasional.',
                'skills' => ['PHP', 'Laravel', 'JavaScript', 'Tailwind CSS', 'PostgreSQL', 'Git', 'RESTful API'],
                'educations' => [
                    [
                        'level' => 'S1',
                        'institution' => 'Universitas Indonesia',
                        'major' => 'Sistem Informasi',
                        'degree' => 'Mahasiswa Aktif',
                        'city' => 'Depok',
                        'start_year' => '2022',
                        'end_year' => '2026',
                    ],
                ],
            ]
        );

        // 3. Resolve Company & Mentor
        $company = CompanyProfile::where('company_name', 'like', '%TechNova%')->first()
            ?? CompanyProfile::first();

        if (!$company) {
            $company = CompanyProfile::create([
                'company_name' => 'PT TechNova Asia Digital',
                'industry' => 'Software & Technology',
                'company_size' => '50 - 200 Karyawan',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'is_verified' => true,
            ]);
        }

        $mentor = User::where('email', 'mentor@technova.com')->first()
            ?? User::role('Mentor')->first();

        if (!$mentor) {
            $mentor = User::firstOrCreate(
                ['email' => 'mentor@technova.com'],
                [
                    'name' => 'Budi Santoso (Lead Mentor)',
                    'password' => Hash::make('password'),
                ]
            );
            $mentor->assignRole($mentorRole);
        }

        // Link Mentor to Company if not linked yet
        CompanyTeamMember::updateOrCreate(
            [
                'company_profile_id' => $company->id,
                'user_id' => $mentor->id,
            ],
            [
                'role_title' => 'Lead Software Engineering Mentor',
                'status' => 'active',
                'invited_by' => $company->user_id,
            ]
        );

        // 4. Create or Resolve Internship Job & Application
        $jobTitle = 'Magang Software Engineer & Fullstack Web (Batch 2)';
        $job = Job::where(function ($q) use ($jobTitle) {
                $q->where('title', $jobTitle)
                  ->orWhere('title', 'like', '%Software Engineer & Fullstack Web%');
            })
            ->where(function ($q) use ($company) {
                $q->where('company_name', $company->company_name)
                  ->orWhereNull('company_name');
            })
            ->first();

        if (!$job) {
            $job = Job::create([
                'title' => $jobTitle,
                'company_name' => $company->company_name,
                'division' => 'Technology & Engineering',
                'location' => 'Bandung',
                'work_type' => 'Internship / Magang',
                'batch' => 'Batch 2 - Semester Ganjil 2026',
                'salary' => 'Rp 3.500.000 - Rp 5.000.000',
                'quota' => 10,
                'duration' => '6 Bulan',
                'start_date' => Carbon::parse('2026-08-10'),
                'deadline' => Carbon::parse('2026-08-05'),
                'description' => 'Program magang intensif pengembangan aplikasi web skala enterprise berbasis Laravel, PostgreSQL, dan Tailwind CSS.',
                'requirements' => 'Mahasiswa aktif semester 5-8 jurusan Informatika/Sistem Informasi.',
                'benefits' => 'Uang Saku Bulanan Kompetitif, Sertifikat Resmi Magang, Mentoring 1-on-1, Peluang Konversi Karyawan Tetap.',
                'status' => 'published',
            ]);
        } else {
            $job->update([
                'company_name' => $company->company_name,
                'batch' => 'Batch 2 - Semester Ganjil 2026',
                'work_type' => 'Internship / Magang',
                'duration' => '6 Bulan',
                'start_date' => Carbon::parse('2026-08-10'),
                'status' => 'active',
            ]);
        }

        $application = Application::where('user_id', $user->id)
            ->where(function ($q) {
                $q->whereIn('status', ['accepted', 'hired'])
                  ->orWhereNull('status');
            })
            ->latest()
            ->first();

        if ($application) {
            $application->update([
                'job_id' => $job->id,
                'status' => 'accepted',
            ]);
        } else {
            $application = Application::create([
                'user_id' => $user->id,
                'job_id' => $job->id,
                'status' => 'accepted',
            ]);
        }

        // 5. Create or Resolve Internship Batch & Period
        $periodName = 'Batch 2 - Semester Ganjil 2026';
        $periodStart = Carbon::parse('2026-08-10');
        $periodEnd = Carbon::parse('2027-02-09');

        InternshipBatch::firstOrCreate(
            ['batch_name' => $periodName],
            [
                'company_id' => $company->id,
                'start_date' => $periodStart->format('Y-m-d'),
                'end_date' => $periodEnd->format('Y-m-d'),
                'target_hours' => 400,
                'status' => 'active',
            ]
        );

        InternshipPeriod::updateOrCreate(
            ['user_id' => $user->id],
            [
                'company_id' => $company->id,
                'period_name' => $periodName,
                'start_date' => $periodStart->format('Y-m-d'),
                'end_date' => $periodEnd->format('Y-m-d'),
                'target_hours' => 400,
            ]
        );

        // 6. Digital Agreement (SPM)
        ApplicationAgreement::updateOrCreate(
            [
                'application_id' => $application->id,
                'user_id' => $user->id,
            ],
            [
                'agreement_type' => 'internship_agreement',
                'title' => 'SURAT PERJANJIAN MAGANG KERJA (INTERNSHIP AGREEMENT)',
                'contract_number' => 'SPM/2026/09/APP' . sprintf('%04d', $application->id) . '-U' . sprintf('%04d', $user->id),
                'start_date' => '2026-08-10',
                'end_date' => '2027-02-09',
                'stipend_or_salary' => 'Rp 3.500.000 - Rp 5.000.000',
                'terms_content' => "PASAL 1: STATUS PROGRAM MAGANG & JANGKA WAKTU\n1. Pihak Pertama menerima Pihak Kedua sebagai Peserta Magang (Internship) untuk posisi dan divisi yang tercantum dalam perjanjian ini.\n2. Program magang berlangsung terhitung mulai Tanggal Mulai hingga Tanggal Selesai yang disepakati bersama.",
                'hr_signer_name' => 'Dwi Prasetyo (Lead Recruiter)',
                'owner_signer_name' => $company->company_name,
                'signer_name' => 'Tania Danny',
                'signer_ip' => '182.8.100.105',
                'signed_at' => Carbon::parse('2026-08-10 09:00:00'),
                'status' => 'signed',
            ]
        );

        // 7. Approved Leave Requests (Izin Resmi & Izin Sakit)
        $leaveAcademic = LeaveRequest::updateOrCreate(
            [
                'user_id' => $user->id,
                'start_date' => '2026-10-14',
                'end_date' => '2026-10-14',
            ],
            [
                'company_id' => $company->id,
                'application_id' => $application->id,
                'leave_type' => 'academic_leave',
                'total_days' => 1,
                'reason' => 'Mengikuti sesi bimbingan skripsi dan konsultasi metodologi penelitian dengan dosen pembimbing di kampus.',
                'status' => 'approved',
                'approver_id' => $mentor->id,
                'approved_at' => Carbon::parse('2026-10-13 16:30:00'),
                'admin_notes' => 'Disetujui. Dispensasi bimbingan akademik kampus terverifikasi resmi.',
            ]
        );

        $leaveSick = LeaveRequest::updateOrCreate(
            [
                'user_id' => $user->id,
                'start_date' => '2026-10-15',
                'end_date' => '2026-10-15',
            ],
            [
                'company_id' => $company->id,
                'application_id' => $application->id,
                'leave_type' => 'sick_leave',
                'total_days' => 1,
                'reason' => 'Istirahat masa pemulihan kondisi kesehatan (flu & demam) sesuai anjuran istirahat surat keterangan dokter klinik.',
                'status' => 'approved',
                'approver_id' => $mentor->id,
                'approved_at' => Carbon::parse('2026-10-15 08:30:00'),
                'admin_notes' => 'Disetujui. Surat dokter valid. Silakan beristirahat dan lekas pulih.',
            ]
        );

        // 8. Holidays Lookup (National / Cuti Bersama)
        $holidays = CompanyHoliday::where(function ($q) use ($company) {
            $q->whereNull('company_id')->orWhere('company_id', $company->id);
        })->get()->keyBy(fn($h) => $h->date->format('Y-m-d'));

        // 9. Structured Daily Activities for Working Days
        $activityPool = [
            [
                'act' => 'Setup development environment, instalasi Docker container, konfigurasi database PostgreSQL, dan repositori backend Laravel.',
                'learn' => 'Memahami arsitektur containerization Docker dan konfigurasi environment Laravel 11.',
                'chal' => 'Penyesuaian konfigurasi port lokal agar tidak bentrok dengan service lain.',
            ],
            [
                'act' => 'Mempelajari dan mengimplementasikan RESTful API endpoint untuk modul manajemen resource data pengguna.',
                'learn' => 'Penerapan standard REST API response, HTTP status codes, dan resource transformers.',
                'chal' => 'Validasi nested payload JSON dari client.',
            ],
            [
                'act' => 'Pembangunan database schema, relasi foreign key Eloquent, dan pembuatan migration & seeder data otomatis.',
                'learn' => 'Optimasi indexing tabel PostgreSQL dan eager loading relasi untuk mencegah N+1 query problem.',
                'chal' => 'Merancang arsitektur relasi many-to-many yang scalable.',
            ],
            [
                'act' => 'Implementasi autentikasi pengguna berbasis JWT Token dan sistem middleware otorisasi hak akses peran (Role-Based Access Control).',
                'learn' => 'Mekanisme proteksi route dengan middleware Spatie permission dan token lifecycle.',
                'chal' => 'Penanganan invalidasi session saat token kedaluwarsa (token expiration).',
            ],
            [
                'act' => 'Pengerjaan modul upload file terenkripsi dan integrasi penyimpanan berkas dokumen pendukung.',
                'learn' => 'Validasi MIME type file, pembatasan ukuran berkas, dan penyimpanan public/private disk Storage.',
                'chal' => 'Memastikan sanitasi nama file aman dari karakter berbahaya.',
            ],
            [
                'act' => 'Pembuatan modul antarmuka responsif menggunakan Tailwind CSS dan komponen interaktif Alpine.js.',
                'learn' => 'Manajemen state lokal Alpine.js dan responsive utility classes Tailwind.',
                'chal' => 'Sinkronisasi event antar komponen modal interaktif.',
            ],
            [
                'act' => 'Implementasi caching layer menggunakan Redis untuk mempercepat performa response query dashboard.',
                'learn' => 'Strategi cache invalidation berbasis Redis key expiration dan in-memory data store.',
                'chal' => 'Menghindari stale data ketika ada operasi update data master.',
            ],
            [
                'act' => 'Pembuatan template ekspor dokumen PDF digital menggunakan DomPDF engine dengan styling CSS cetak presisi.',
                'learn' => 'Format styling khusus DomPDF dan render barcode QR Code SVG.',
                'chal' => 'Menghindari page break yang memotong baris tabel tanda tangan digital.',
            ],
            [
                'act' => 'Penulisan unit testing dan automated feature test menggunakan PHPUnit untuk memvalidasi alur bisnis.',
                'learn' => 'Prinsip Test-Driven Development (TDD) dan mock assertions pada Laravel.',
                'chal' => 'Mencapai target code coverage di atas 80%.',
            ],
            [
                'act' => 'Penyusunan dokumentasi endpoint API interaktif menggunakan Swagger OpenAPI 3.0 annotations.',
                'learn' => 'Menulis anotasi schema request dan response standar OpenAPI.',
                'chal' => 'Menyelaraskan struktur request body contoh dengan validator.',
            ],
            [
                'act' => 'Investigasi dan perbaikan bug (bug fixing) terkait validasi form presensi dan filter pencarian data.',
                'learn' => 'Teknik debugging menggunakan Laravel Telescope dan query logging.',
                'chal' => 'Mengidentifikasi edge case input data yang bernilai null.',
            ],
            [
                'act' => 'Pemeriksaan keamanan aplikasi, penanganan potensi SQL Injection, XSS, dan proteksi CSRF.',
                'learn' => 'Security best practices OWASP Top 10 pada framework web modern.',
                'chal' => 'Sanitasi input teks yang memuat format HTML.',
            ],
            [
                'act' => 'Sesi Code Review mingguan bersama Mentor Pembimbing dan refactoring modul sesuai prinsip SOLID.',
                'learn' => 'Menerapkan Single Responsibility Principle dan Dependency Inversion.',
                'chal' => 'Memisahkan business logic yang menumpuk di controller ke dedicated service class.',
            ],
            [
                'act' => 'Konfigurasi pipeline CI/CD GitHub Actions untuk automated linting, testing, dan syntax checking.',
                'learn' => 'Workflow automation runner dan pengelolaan GitHub repository secrets.',
                'chal' => 'Mengatasi failure pada workflow akibat environment dependency yang belum terinstal.',
            ],
            [
                'act' => 'Evaluasi capaian sprint magang bulanan dan penyusunan laporan kemajuan proyek kepada tim Mentor.',
                'learn' => 'Komunikasi profesional, presentasi progres teknis, dan perumusan target sprint berikutnya.',
                'chal' => 'Merangkum matriks performa dan deliverables teknis secara ringkas dan informatif.',
            ],
        ];

        // 10. Generate Logbooks for "Bulan Ini" (October 2026) and optionally current month
        $monthsToSeed = ['2026-10'];
        $nowMonth = Carbon::now()->format('Y-m');
        if (!in_array($nowMonth, $monthsToSeed)) {
            $monthsToSeed[] = $nowMonth;
        }

        $actIndex = 0;

        foreach ($monthsToSeed as $yearMonth) {
            $startOfMonth = Carbon::parse($yearMonth . '-01')->startOfMonth();
            $endOfMonth = Carbon::parse($yearMonth . '-01')->endOfMonth();
            $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                $isWeekend = $date->isWeekend(); // Sabtu (6) & Minggu (7)
                $isHoliday = isset($holidays[$dateStr]);

                // Weekend atau Hari Libur Nasional: LIBUR (Tidak membuat logbook kerja)
                if ($isWeekend || $isHoliday) {
                    continue;
                }

                // Cek apakah tanggal ini adalah hari Izin Resmi / Sakit yang disetujui
                if ($dateStr === '2026-10-14') {
                    InternshipLogbook::updateOrCreate(
                        ['user_id' => $user->id, 'date' => $dateStr],
                        [
                            'company_id' => $company->id,
                            'application_id' => $application->id,
                            'mentor_id' => $mentor->id,
                            'attendance_type' => 'Tidak Hadir Dengan Keterangan',
                            'work_hours' => 0,
                            'activities' => 'Izin Resmi Disetujui: Izin Akademik / Kampus (Sidang, Ujian, Bimbingan, Wisuda). Kegiatan: Mengikuti sesi bimbingan skripsi dan konsultasi metodologi penelitian dengan dosen pembimbing di kampus.',
                            'learnings' => 'Mendapatkan masukan perbaikan mengenai implementasi backend API dan integrasi sistem.',
                            'challenges' => 'Menyelaraskan jadwal bimbingan dengan timeline pengerjaan tugas sprint magang.',
                            'status' => 'approved',
                            'mentor_notes' => 'Izin dispensasi akademik telah diverifikasi dan disetujui oleh Mentor.',
                            'approved_at' => Carbon::parse($dateStr . ' 17:00:00'),
                        ]
                    );
                    continue;
                }

                if ($dateStr === '2026-10-15') {
                    InternshipLogbook::updateOrCreate(
                        ['user_id' => $user->id, 'date' => $dateStr],
                        [
                            'company_id' => $company->id,
                            'application_id' => $application->id,
                            'mentor_id' => $mentor->id,
                            'attendance_type' => 'Tidak Hadir Dengan Keterangan',
                            'work_hours' => 0,
                            'activities' => 'Izin Resmi Disetujui: Izin Sakit (Surat Dokter Terlampir). Kondisi demam dan flu, beristirahat sesuai anjuran istirahat surat keterangan dokter klinik.',
                            'learnings' => 'Istirahat dan pemulihan kesehatan secara optimal.',
                            'challenges' => 'Menjaga komunikasi asinkron dengan tim kerja saat masa pemulihan.',
                            'status' => 'approved',
                            'mentor_notes' => 'Surat dokter valid. Istirahat dan lekas sembuh.',
                            'approved_at' => Carbon::parse($dateStr . ' 17:00:00'),
                        ]
                    );
                    continue;
                }

                // Hari kerja normal: HADIR & APPROVED (8 Jam Kerja)
                $template = $activityPool[$actIndex % count($activityPool)];
                $actIndex++;

                InternshipLogbook::updateOrCreate(
                    ['user_id' => $user->id, 'date' => $dateStr],
                    [
                        'company_id' => $company->id,
                        'application_id' => $application->id,
                        'mentor_id' => $mentor->id,
                        'attendance_type' => 'Hadir',
                        'work_hours' => 8,
                        'latitude' => '-6.2088',
                        'longitude' => '106.8456',
                        'location_address' => 'Gedung Cyber Tower Lt. 12, Jl. HR Rasuna Said, Jakarta Selatan',
                        'activities' => $template['act'],
                        'learnings' => $template['learn'],
                        'challenges' => $template['chal'],
                        'status' => 'approved',
                        'mentor_notes' => 'Laporan aktivitas harian dan jam kerja telah diverifikasi dan disetujui oleh Mentor.',
                        'approved_at' => Carbon::parse($dateStr . ' 17:15:00'),
                    ]
                );
            }
        }

        // 11. User Notifications
        $notifications = [
            [
                'title' => '🎉 Presensi & Logbook Disetujui Mentor',
                'message' => 'Mentor ' . $mentor->name . ' telah menyetujui seluruh laporan aktivitas harian magang Anda untuk bulan Oktober 2026.',
                'link' => '/candidate/internship/logbook?month=2026-10',
                'type' => 'success',
                'created_at' => Carbon::parse('2026-10-30 17:30:00'),
            ],
            [
                'title' => '✅ Permohonan Izin Akademik Disetujui',
                'message' => 'Permohonan izin dispensasi bimbingan akademik kampus Anda pada tanggal 14 Oktober 2026 telah disetujui oleh HRD & Mentor.',
                'link' => '/candidate/leaves',
                'type' => 'success',
                'created_at' => Carbon::parse('2026-10-13 16:30:00'),
            ],
            [
                'title' => '✅ Permohonan Izin Sakit Disetujui',
                'message' => 'Permohonan izin sakit Anda (15 Oktober 2026) dengan surat keterangan dokter telah diverifikasi dan disetujui.',
                'link' => '/candidate/leaves',
                'type' => 'success',
                'created_at' => Carbon::parse('2026-10-15 08:35:00'),
            ],
            [
                'title' => '🌟 Evaluasi Kinerja Mingguan Mentor',
                'message' => 'Mentor memberikan apresiasi positif atas konsistensi dan kualitas pengerjaan backend API Anda pada sprint ini.',
                'link' => '/candidate/internship/progress',
                'type' => 'info',
                'created_at' => Carbon::parse('2026-10-23 18:00:00'),
            ],
            [
                'title' => '✍️ Surat Perjanjian Magang Resmi Disahkan',
                'message' => 'Dokumen Perjanjian Magang Kerja (SPM) telah ditandatangani secara digital dengan verifikasi OTP dan sah secara hukum.',
                'link' => '/candidate/internship/progress',
                'type' => 'success',
                'created_at' => Carbon::parse('2026-08-10 09:15:00'),
            ],
            [
                'title' => '⏰ Pengingat Presensi & Logbook Magang',
                'message' => 'Ingat untuk melakukan presensi harian dan mengisi logbook kerja sebelum pukul 17:00 WIB setiap hari kerja.',
                'link' => '/candidate/internship/logbook',
                'type' => 'info',
                'created_at' => Carbon::parse('2026-10-01 08:00:00'),
            ],
        ];

        foreach ($notifications as $notif) {
            UserNotification::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => $notif['title'],
                ],
                [
                    'message' => $notif['message'],
                    'link' => $notif['link'],
                    'type' => $notif['type'],
                    'is_read' => false,
                    'created_at' => $notif['created_at'],
                ]
            );
        }
    }
}
