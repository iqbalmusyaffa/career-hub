<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationAgreement;
use App\Models\CandidateOnboarding;
use App\Models\CandidateProfile;
use App\Models\CompanyHoliday;
use App\Models\CompanyProfile;
use App\Models\CompanyTeamMember;
use App\Models\InternshipBatch;
use App\Models\InternshipBenefit;
use App\Models\InternshipLogbook;
use App\Models\InternshipPeriod;
use App\Models\InternshipStipendDisbursement;
use App\Models\InternshipUserBenefit;
use App\Models\Job;
use App\Models\LeaveRequest;
use App\Models\UmkReference;
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

        // Resolve official UMK reference for the Company city
        $city = $company->city ?: 'Jakarta Selatan';
        $umk = UmkReference::findByLocation($city) 
            ?? UmkReference::findByLocation('Jakarta Selatan');

        $umkNominal = $umk ? (int) $umk->umk_amount : 5729876;
        $formattedUmk = 'Rp ' . number_format($umkNominal, 0, ',', '.');

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
                'location' => 'Jakarta Selatan',
                'work_type' => 'Internship / Magang',
                'batch' => 'Batch 2 - Semester Ganjil 2026',
                'salary' => $formattedUmk,
                'quota' => 10,
                'duration' => '6 Bulan',
                'start_date' => Carbon::parse('2026-08-10'),
                'deadline' => Carbon::parse('2026-08-05'),
                'description' => 'Program magang intensif pengembangan aplikasi web skala enterprise berbasis Laravel, PostgreSQL, dan Tailwind CSS.',
                'requirements' => 'Mahasiswa aktif semester 5-8 jurusan Informatika/Sistem Informasi.',
                'benefits' => "Uang Saku Bulanan Sesuai Standar UMK ({$formattedUmk}), Sertifikat Resmi Magang, Mentoring 1-on-1, Peluang Konversi Karyawan Tetap.",
                'status' => 'published',
            ]);
        } else {
            $job->update([
                'company_name' => $company->company_name,
                'location' => 'Jakarta Selatan',
                'batch' => 'Batch 2 - Semester Ganjil 2026',
                'work_type' => 'Internship / Magang',
                'salary' => $formattedUmk,
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

        // 6. Digital Agreement (SPM) with Stipend According to Official UMK
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
                'stipend_or_salary' => $formattedUmk,
                'terms_content' => "PASAL 1: STATUS PROGRAM MAGANG & JANGKA WAKTU\n1. Pihak Pertama menerima Pihak Kedua sebagai Peserta Magang (Internship) untuk posisi dan divisi yang tercantum dalam perjanjian ini.\n2. Program magang berlangsung terhitung mulai Tanggal Mulai hingga Tanggal Selesai yang disepakati bersama.\n\nPASAL 2: HAK UANG SAKU\n1. Pihak Kedua menerima hak uang saku bulanan sebesar {$formattedUmk} per bulan sesuai acuan ketetapan standar UMK wilayah penempatan kerja.",
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

        // 10. Generate Logbooks for August, September, and October 2026
        $monthsToSeed = ['2026-08', '2026-09', '2026-10'];
        $nowMonth = Carbon::now()->format('Y-m');
        if (!in_array($nowMonth, $monthsToSeed)) {
            $monthsToSeed[] = $nowMonth;
        }

        $actIndex = 0;
        $today = Carbon::today(); // e.g. 2026-10-18

        // HAPUS SEMUA LOGBOOK TANGGAL MASA DEPAN YANG BELUM DILALUI / BELUM DIISI!
        InternshipLogbook::where('user_id', $user->id)
            ->whereDate('date', '>', $today->format('Y-m-d'))
            ->delete();

        // Daftar Hari Izin Resmi Disetujui (Agustus: 3 hari, September: 6 hari, Oktober: 2 hari)
        $leaveDays = [
            // AGUSTUS 2026 (3 Hari Izin - Bebas Potong Kuota <= 4)
            '2026-08-20' => [
                'type' => 'Tidak Hadir Dengan Keterangan',
                'act' => 'Izin Resmi Disetujui: Izin Akademik / Kampus. Kegiatan: Konsultasi metodologi penelitian dan bimbingan skripsi Bab 3 di kampus.',
                'learn' => 'Masukan penulisan dan kerangka sistem skripsi.',
                'chal' => 'Penyesuaian jadwal bimbingan dengan deadline sprint magang.',
                'mentor_notes' => 'Izin akademik telah diverifikasi dan disetujui Mentor.',
            ],
            '2026-08-21' => [
                'type' => 'Tidak Hadir Dengan Keterangan',
                'act' => 'Izin Resmi Disetujui: Izin Akademik / Kampus. Kegiatan: Menghadiri seminar proposal mahasiswa di universitas.',
                'learn' => 'Referensi implementasi arsitektur perangkat lunak.',
                'chal' => 'Manajemen waktu presentasi.',
                'mentor_notes' => 'Izin akademik disetujui Mentor.',
            ],
            '2026-08-27' => [
                'type' => 'Tidak Hadir Dengan Keterangan',
                'act' => 'Izin Resmi Disetujui: Izin Sakit (Surat Dokter Terlampir). Kondisi demam dan flu, istirahat anjuran dokter.',
                'learn' => 'Menjaga kondisi kesehatan fisik.',
                'chal' => 'Komunikasi asinkron pengerjaan modul sprint.',
                'mentor_notes' => 'Surat keterangan dokter valid. Cepat sembuh.',
            ],

            // SEPTEMBER 2026 (6 Hari Izin - 4 Hari Kuota Bebas Potong + 2 Hari Potong Kuota)
            '2026-09-08' => [
                'type' => 'Tidak Hadir Dengan Keterangan',
                'act' => 'Izin Resmi Disetujui: Izin Akademik / Ujian Tengah Semester (UTS) mata kuliah Rekayasa Perangkat Lunak.',
                'learn' => 'Penerapan konsep algoritma dan optimasi query.',
                'chal' => 'Fokus pengerjaan soal ujian komprehensif.',
                'mentor_notes' => 'Dispensasi ujian semester kampus disetujui.',
            ],
            '2026-09-09' => [
                'type' => 'Tidak Hadir Dengan Keterangan',
                'act' => 'Izin Resmi Disetujui: Izin Akademik / Ujian Tengah Semester (UTS) mata kuliah Basis Data Lanjut.',
                'learn' => 'Normalisasi database dan transaksi ACID PostgreSQL.',
                'chal' => 'Ujian praktik query berkecepatan tinggi.',
                'mentor_notes' => 'Dispensasi ujian semester kampus disetujui.',
            ],
            '2026-09-17' => [
                'type' => 'Tidak Hadir Dengan Keterangan',
                'act' => 'Izin Resmi Disetujui: Izin Sakit (Surat Keterangan Dokter Klinik Pratama). Gejala demam dan radang tenggorokan.',
                'learn' => 'Istirahat dan pemulihan kesehatan.',
                'chal' => 'Mendelegasikan review PR ke rekan tim.',
                'mentor_notes' => 'Surat dokter terverifikasi. Istirahat yang cukup.',
            ],
            '2026-09-18' => [
                'type' => 'Tidak Hadir Dengan Keterangan',
                'act' => 'Izin Resmi Disetujui: Izin Sakit (Lanjutan Pemulihan Surat Dokter).',
                'learn' => 'Pemulihan kondisi stamina.',
                'chal' => 'Menjaga sinkronisasi task backlog di Jira.',
                'mentor_notes' => 'Disetujui untuk pemulihan optimal.',
            ],
            '2026-09-24' => [
                'type' => 'Tidak Hadir Dengan Keterangan',
                'status' => 'approved',
                'act' => 'Izin Resmi Disetujui: Izin Acara Keluarga / Keperluan Mendesak di luar kota (Hari ke-1 / Kuota ke-5).',
                'learn' => 'Keseimbangan prioritas urusan keluarga dan profesionalisme.',
                'chal' => 'Perjalanan darat mendesak.',
                'mentor_notes' => 'Izin keperluan keluarga disetujui Mentor & HR (Hari ke-5 / Batas kuota toleransi bulanan).',
            ],
            '2026-09-25' => [
                'type' => 'Tidak Hadir Tanpa Keterangan',
                'status' => 'rejected',
                'act' => 'Tidak Hadir Tanpa Keterangan (Alpa): Permohonan izin ke luar kota hari ke-2 ditolak karena telah melebihi batas kuota toleransi 5 hari per bulan.',
                'learn' => 'Memahami batas kepatuhan kuota toleransi izin operasional magang.',
                'chal' => 'Kehadiran tercatat sebagai Tidak Hadir (Alpa) dan dikenakan pemotongan uang saku 1 hari kerja.',
                'mentor_notes' => 'Permohonan izin ditolak HR/Mentor: Melebihi kuota toleransi 5 hari/bulan. Dicatat sebagai Tidak Hadir (Alpa) dengan pemotongan uang saku 1 hari kerja.',
            ],

            // OKTOBER 2026 (2 Hari Izin)
            '2026-10-14' => [
                'type' => 'Tidak Hadir Dengan Keterangan',
                'act' => 'Izin Resmi Disetujui: Izin Akademik / Kampus (Sidang, Ujian, Bimbingan, Wisuda). Kegiatan: Mengikuti sesi bimbingan skripsi dan konsultasi metodologi penelitian dengan dosen pembimbing di kampus.',
                'learn' => 'Mendapatkan masukan perbaikan mengenai implementasi backend API dan integrasi sistem.',
                'chal' => 'Menyelaraskan jadwal bimbingan dengan timeline pengerjaan tugas sprint magang.',
                'mentor_notes' => 'Izin dispensasi akademik telah diverifikasi dan disetujui oleh Mentor.',
            ],
            '2026-10-15' => [
                'type' => 'Tidak Hadir Dengan Keterangan',
                'act' => 'Izin Resmi Disetujui: Izin Sakit (Surat Dokter Terlampir). Kondisi demam dan flu, beristirahat sesuai anjuran istirahat surat keterangan dokter klinik.',
                'learn' => 'Istirahat dan pemulihan kesehatan secara optimal.',
                'chal' => 'Menjaga komunikasi asinkron dengan tim kerja saat masa pemulihan.',
                'mentor_notes' => 'Surat dokter valid. Istirahat dan lekas sembuh.',
            ],
        ];

        foreach ($monthsToSeed as $yearMonth) {
            $startOfMonth = Carbon::parse($yearMonth . '-01')->startOfMonth();
            $endOfMonth = Carbon::parse($yearMonth . '-01')->endOfMonth();
            $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

            foreach ($period as $date) {
                // Jangan buat sebelum tanggal resmi periode magang (10 Agustus 2026)
                if ($date->lt($periodStart)) {
                    continue;
                }

                $dateStr = $date->format('Y-m-d');
                $isWeekend = $date->isWeekend(); // Sabtu (6) & Minggu (7)
                $isHoliday = isset($holidays[$dateStr]);

                // Weekend atau Hari Libur Nasional: LIBUR (Tidak membuat logbook kerja)
                if ($isWeekend || $isHoliday) {
                    continue;
                }

                // PENTING: Tanggal masa depan yang belum dilalui / belum diisi jangan dibuatkan logbook!
                if ($date->gt($today)) {
                    continue;
                }

                // Cek apakah tanggal ini adalah hari Izin Resmi / Sakit yang disetujui atau ditolak
                if (isset($leaveDays[$dateStr])) {
                    $leave = $leaveDays[$dateStr];
                    $logStatus = $leave['status'] ?? 'approved';
                    InternshipLogbook::updateOrCreate(
                        ['user_id' => $user->id, 'date' => $dateStr],
                        [
                            'company_id' => $company->id,
                            'application_id' => $application->id,
                            'mentor_id' => $mentor->id,
                            'attendance_type' => $leave['type'],
                            'work_hours' => 0,
                            'activities' => $leave['act'],
                            'learnings' => $leave['learn'],
                            'challenges' => $leave['chal'],
                            'status' => $logStatus,
                            'mentor_notes' => $leave['mentor_notes'],
                            'approved_at' => ($logStatus === 'approved') ? Carbon::parse($dateStr . ' 17:00:00') : null,
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

        // 11. Seed Disbursed Stipends for August & September 2026
        // AGUSTUS 2026: Masuk 10 Agt (14 hr kerja): 11 Hadir + 3 Izin (Bebas Potong Kuota) -> Full UMK
        InternshipStipendDisbursement::updateOrCreate(
            [
                'user_id' => $user->id,
                'period_month' => '2026-08',
            ],
            [
                'company_id' => $company->id,
                'period_label' => 'Agustus 2026',
                'batch_name' => $periodName,
                'bank_name' => 'Bank Central Asia (BCA)',
                'bank_account_number' => '8830192847',
                'bank_account_holder' => 'Tania Maharani',
                'is_ktp_matched' => true,
                'present_days' => 11,
                'excused_days' => 3,
                'unexcused_days' => 0,
                'total_working_days' => 14,
                'base_nominal' => $umkNominal,
                'deduction_amount' => 0,
                'net_amount' => $umkNominal,
                'status' => 'transferred',
                'transferred_at' => Carbon::parse('2026-08-29 14:00:00'),
                'mentor_id' => $mentor->id,
                'mentor_submitted_at' => Carbon::parse('2026-08-28 17:00:00'),
                'mentor_notes' => 'Presensi bulan Agustus baik, 11 hari hadir dan 3 hari izin akademik/sakit resmi disetujui penuh (bebas potong kuota).',
                'verified_by' => $company->user_id ?? 1,
                'notes' => 'Uang saku bulan Agustus 2026 telah ditransfer ke rekening BCA atas nama Tania Maharani.',
            ]
        );

        // SEPTEMBER 2026: 22 hr kerja: 16 Hadir + 5 Izin Disetujui + 1 Hari Ditolak (Alpa) -> Net: Rp 5.469.427
        $sepExcessDays = 1; // 1 hari alpa / ditolak
        $sepDailyRate = round($umkNominal / 22);
        $sepDeduction = $sepExcessDays * $sepDailyRate; // Rp 260.449
        $sepNetAmount = $umkNominal - $sepDeduction; // Rp 5.469.427

        InternshipStipendDisbursement::updateOrCreate(
            [
                'user_id' => $user->id,
                'period_month' => '2026-09',
            ],
            [
                'company_id' => $company->id,
                'period_label' => 'September 2026',
                'batch_name' => $periodName,
                'bank_name' => 'Bank Central Asia (BCA)',
                'bank_account_number' => '8830192847',
                'bank_account_holder' => 'Tania Maharani',
                'is_ktp_matched' => true,
                'present_days' => 16,
                'excused_days' => 5,
                'unexcused_days' => 1,
                'total_working_days' => 22,
                'base_nominal' => $umkNominal,
                'deduction_amount' => $sepDeduction,
                'net_amount' => $sepNetAmount,
                'status' => 'transferred',
                'transferred_at' => Carbon::parse('2026-09-28 15:30:00'),
                'mentor_id' => $mentor->id,
                'mentor_submitted_at' => Carbon::parse('2026-09-27 17:00:00'),
                'mentor_notes' => 'Presensi September: 16 hari hadir, 5 hari izin resmi disetujui (kuota toleransi 5 hari), dan 1 hari tidak hadir/alpa (kelebihan izin ditolak tanggal 25 Sep). Dikenakan pemotongan uang saku 1 hari kerja.',
                'verified_by' => $company->user_id ?? 1,
                'notes' => 'Uang saku bulan September 2026 (net pemotongan 1 hari alpa akibat izin melebihi batas kuota 5 hari) telah ditransfer ke rekening BCA atas nama Tania Maharani.',
            ]
        );

        // Hapus record lama izin gabungan 24-25 September jika ada
        LeaveRequest::where('user_id', $user->id)
            ->where('start_date', '2026-09-24')
            ->where('end_date', '2026-09-25')
            ->delete();

        // 12. Seed Official Leave Requests (Modul Cuti / Leaves Module)
        $leaveRequestsToSeed = [
            // AGUSTUS 2026 (Total 3 Hari Cuti/Izin)
            [
                'leave_type' => 'academic_leave',
                'start_date' => '2026-08-20',
                'end_date' => '2026-08-21',
                'total_days' => 2,
                'reason' => 'Dispensasi bimbingan skripsi Bab 3 dan seminar proposal penelitian di universitas.',
                'status' => 'approved',
                'approved_at' => Carbon::parse('2026-08-19 15:00:00'),
                'admin_notes' => 'Disetujui oleh Mentor & HR. Tetap koordinasikan progres bimbingan dengan tim sprint.',
            ],
            [
                'leave_type' => 'sick_leave',
                'start_date' => '2026-08-27',
                'end_date' => '2026-08-27',
                'total_days' => 1,
                'reason' => 'Sakit demam dan flu (Surat istirahat dokter klinik pratama terlampir).',
                'status' => 'approved',
                'approved_at' => Carbon::parse('2026-08-27 08:30:00'),
                'admin_notes' => 'Surat dokter valid. Istirahat dan pemulihan kesehatan optimal.',
            ],

            // SEPTEMBER 2026 (5 Hari Bebas Potong Disetujui + 1 Hari Kelebihan Ditolak/Alpa)
            [
                'leave_type' => 'academic_leave',
                'start_date' => '2026-09-08',
                'end_date' => '2026-09-09',
                'total_days' => 2,
                'reason' => 'Dispensasi Ujian Tengah Semester (UTS) mata kuliah RPL dan Basis Data Lanjut di kampus.',
                'status' => 'approved',
                'approved_at' => Carbon::parse('2026-09-05 16:00:00'),
                'admin_notes' => 'Jadwal UTS kampus terverifikasi dan disetujui penuh.',
            ],
            [
                'leave_type' => 'sick_leave',
                'start_date' => '2026-09-17',
                'end_date' => '2026-09-18',
                'total_days' => 2,
                'reason' => 'Sakit demam dan radang tenggorokan (Surat Keterangan Dokter Klinik Pratama).',
                'status' => 'approved',
                'approved_at' => Carbon::parse('2026-09-17 08:15:00'),
                'admin_notes' => 'Surat dokter valid. Istirahat dan lekas sembuh.',
            ],
            [
                'leave_type' => 'family_event',
                'start_date' => '2026-09-24',
                'end_date' => '2026-09-24',
                'total_days' => 1,
                'reason' => 'Izin acara keluarga penting dan keperluan mendesak di luar kota (Hari ke-1).',
                'status' => 'approved',
                'approved_at' => Carbon::parse('2026-09-22 14:00:00'),
                'admin_notes' => 'Disetujui. Ini merupakan hari izin ke-5 (batas maksimal kuota toleransi bebas potong bulan September).',
            ],
            [
                'leave_type' => 'family_event',
                'start_date' => '2026-09-25',
                'end_date' => '2026-09-25',
                'total_days' => 1,
                'reason' => 'Izin acara keluarga penting dan keperluan mendesak di luar kota (Hari ke-2).',
                'status' => 'rejected',
                'approved_at' => null,
                'admin_notes' => 'Ditolak. Permohonan izin melebihi batas kuota toleransi 5 hari per bulan. Kehadiran tercatat sebagai Tidak Hadir Tanpa Keterangan (Alpa) di logbook dan dikenakan pemotongan uang saku 1 hari kerja.',
            ],

            // OKTOBER 2026 (Total 2 Hari Cuti/Izin)
            [
                'leave_type' => 'academic_leave',
                'start_date' => '2026-10-14',
                'end_date' => '2026-10-14',
                'total_days' => 1,
                'reason' => 'Konsultasi metodologi penelitian dan bimbingan skripsi dengan dosen pembimbing di kampus.',
                'status' => 'approved',
                'approved_at' => Carbon::parse('2026-10-13 16:30:00'),
                'admin_notes' => 'Izin dispensasi akademik telah diverifikasi dan disetujui Mentor.',
            ],
            [
                'leave_type' => 'sick_leave',
                'start_date' => '2026-10-15',
                'end_date' => '2026-10-15',
                'total_days' => 1,
                'reason' => 'Sakit demam dan flu (Surat keterangan dokter terlampir).',
                'status' => 'approved',
                'approved_at' => Carbon::parse('2026-10-15 08:35:00'),
                'admin_notes' => 'Surat dokter terverifikasi. Istirahat dan pemulihan kesehatan.',
            ],
        ];

        foreach ($leaveRequestsToSeed as $lrData) {
            LeaveRequest::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'start_date' => $lrData['start_date'],
                    'end_date' => $lrData['end_date'],
                ],
                [
                    'company_id' => $company->id,
                    'application_id' => $application->id,
                    'leave_type' => $lrData['leave_type'],
                    'total_days' => $lrData['total_days'],
                    'reason' => $lrData['reason'],
                    'status' => $lrData['status'],
                    'approver_id' => $mentor->id,
                    'approved_at' => $lrData['approved_at'],
                    'admin_notes' => $lrData['admin_notes'],
                ]
            );
        }

        // 13. User Notifications
        $notifications = [
            [
                'title' => '🎉 Presensi & Logbook Disetujui Mentor',
                'message' => 'Mentor ' . $mentor->name . ' telah menyetujui seluruh laporan aktivitas harian magang Anda yang telah diisi.',
                'link' => '/candidate/internship/logbook?month=2026-10',
                'type' => 'success',
                'created_at' => Carbon::parse('2026-10-16 17:30:00'),
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
                'created_at' => Carbon::parse('2026-10-16 18:00:00'),
            ],
            [
                'title' => '✍️ Surat Perjanjian Magang Resmi Disahkan',
                'message' => "Dokumen Perjanjian Magang Kerja (SPM) dengan uang saku standar UMK ({$formattedUmk}) telah ditandatangani secara digital.",
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

        // 12. Candidate Onboarding Data (with BPJS Ketenagakerjaan & BPJS Kesehatan)
        CandidateOnboarding::updateOrCreate(
            ['user_id' => $user->id],
            [
                'application_id' => $application?->id,
                'bank_name' => 'BCA (Bank Central Asia)',
                'bank_account_number' => '8045239102',
                'bank_account_holder' => 'Tania Danny',
                'npwp_number' => '84.921.492.1-014.000',
                'bpjs_kesehatan_number' => '0002891048291',
                'bpjs_ketenagakerjaan_number' => '22094819204',
                'family_card_number' => '3174081902030005',
                'student_id_number' => '2206819204',
                'institution_name' => 'Universitas Indonesia',
                'verification_status' => 'verified',
                'verified_at' => Carbon::parse('2026-08-08 14:00:00'),
            ]
        );

        // 13. Master Internship Benefits Catalog (BPJS Mandatory + Facilities Optional)
        $masterBenefits = [
            [
                'code' => 'BPJS_TK',
                'name' => 'BPJS Ketenagakerjaan (JKK & JKM)',
                'category' => 'bpjs_tk',
                'is_mandatory' => true,
                'coverage_type' => 'company_paid',
                'standard_nominal' => 16800.00,
                'provider' => 'BPJS Ketenagakerjaan RI',
                'description' => 'Jaminan Kecelakaan Kerja (JKK) & Jaminan Kematian (JKM) sesuai amanat regulasi Permenaker No. 6 Tahun 2020. Iuran 100% ditanggung oleh perusahaan mitra pemberi kerja (Rp 0 beban peserta).',
                'terms' => 'Wajib bagi seluruh peserta program magang selama masa kontrak berlangsung.',
                'icon' => 'fa-solid fa-shield-halved',
                'badge_color' => 'emerald',
                'policy_number' => '22094819204',
                'user_notes' => 'Terdaftar aktif pada program JKK & JKM. Premi perlindungan disetor penuh oleh PT TechNova Asia Digital.',
            ],
            [
                'code' => 'BPJS_KES',
                'name' => 'BPJS Kesehatan (JKN-KIS)',
                'category' => 'bpjs_kes',
                'is_mandatory' => true,
                'coverage_type' => 'company_paid',
                'standard_nominal' => 0.00,
                'provider' => 'BPJS Kesehatan RI',
                'description' => 'Jaminan Pemeliharaan Kesehatan Nasional untuk perlindungan rawat jalan dan rawat inap peserta magang.',
                'terms' => 'Wajib terdaftar aktif pada basis data kepesertaan BPJS Kesehatan nasional.',
                'icon' => 'fa-solid fa-heart-pulse',
                'badge_color' => 'emerald',
                'policy_number' => '0002891048291',
                'user_notes' => 'Status kepesertaan JKN-KIS tervalidasi aktif untuk fasilitas kesehatan tingkat pertama dan rujukan.',
            ],
            [
                'code' => 'TRANS_ALLOWANCE',
                'name' => 'Tunjangan Transportasi & Mobilitas',
                'category' => 'transport',
                'is_mandatory' => false,
                'coverage_type' => 'cash_allowance',
                'standard_nominal' => 500000.00,
                'provider' => $company->company_name,
                'description' => 'Bantuan tunjangan mobilitas harian untuk transportasi pulang-pergi ke kantor mitra magang.',
                'terms' => 'Fasilitas opsional tambahan perusahaan, dibayarkan beriringan dengan uang saku bulanan.',
                'icon' => 'fa-solid fa-bus-simple',
                'badge_color' => 'blue',
                'policy_number' => 'ALLOW-TR-2026',
                'user_notes' => 'Tunjangan transport bulanan ditransfer bersamaan dengan pencairan uang saku.',
            ],
            [
                'code' => 'OFFICE_MEAL',
                'name' => 'Fasilitas Makan Siang & Pantry Kantor',
                'category' => 'meal',
                'is_mandatory' => false,
                'coverage_type' => 'facility_access',
                'standard_nominal' => 600000.00,
                'provider' => 'Internal Pantry & Catering ' . $company->company_name,
                'description' => 'Akses makan siang prasmanan katering harian, snack, kopi espresso, dan teh sepuasnya di pantry kantor.',
                'terms' => 'Fasilitas in-kind kantor tanpa pemotongan uang saku, berlaku pada hari kerja efektif WFO.',
                'icon' => 'fa-solid fa-utensils',
                'badge_color' => 'amber',
                'policy_number' => 'PANTRY-ACC-T12',
                'user_notes' => 'Akses pantry & catering TechNova Tower Lantai 12.',
            ],
            [
                'code' => 'LAPTOP_ASSET',
                'name' => 'Laptop Inventaris & Perangkat Kerja',
                'category' => 'equipment',
                'is_mandatory' => false,
                'coverage_type' => 'non_cash',
                'standard_nominal' => 0.00,
                'provider' => 'IT Asset Management ' . $company->company_name,
                'description' => 'Peminjaman unit laptop workstation perusahaan serta lisensi software profesional (GitHub Enterprise, Figma, JetBrains).',
                'terms' => 'Fasilitas inventaris kerja kantor, wajib dijaga dan dikembalikan utuh di akhir masa magang.',
                'icon' => 'fa-solid fa-laptop-code',
                'badge_color' => 'purple',
                'policy_number' => 'INV-TN-2026-084',
                'user_notes' => 'MacBook Pro M2 16GB RAM / 512GB SSD - Unit Inventaris Magang.',
            ],
            [
                'code' => 'MENTORING_1ON1',
                'name' => 'Program Mentoring 1-on-1 & Sertifikasi',
                'category' => 'mentoring',
                'is_mandatory' => false,
                'coverage_type' => 'facility_access',
                'standard_nominal' => 1500000.00,
                'provider' => 'Engineering Leadership Team',
                'description' => 'Bimbingan intensif tatap muka 1-on-1 mingguan bersama Senior Software Engineer, pendampingan portofolio, dan sertifikat resmi.',
                'terms' => 'Fasilitas akselerasi karir eksklusif tanpa biaya bagi peserta magang.',
                'icon' => 'fa-solid fa-graduation-cap',
                'badge_color' => 'cyan',
                'policy_number' => 'MENTOR-DIMAS-01',
                'user_notes' => 'Mentor resmi: Dimas Wicaksono (Senior Lead Software Engineer).',
            ],
        ];

        foreach ($masterBenefits as $bData) {
            $policyNo = $bData['policy_number'] ?? null;
            $userNote = $bData['user_notes'] ?? null;
            unset($bData['policy_number'], $bData['user_notes']);

            $benefit = InternshipBenefit::updateOrCreate(
                ['code' => $bData['code']],
                array_merge($bData, [
                    'company_id' => $company->id,
                    'is_active' => true,
                ])
            );

            // Assign to Tania Danny
            InternshipUserBenefit::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'internship_benefit_id' => $benefit->id,
                ],
                [
                    'application_id' => $application?->id,
                    'policy_number' => $policyNo,
                    'status' => 'active',
                    'custom_nominal' => $benefit->standard_nominal,
                    'notes' => $userNote,
                    'start_date' => Carbon::parse('2026-08-10'),
                    'end_date' => Carbon::parse('2027-02-10'),
                ]
            );
        }
    }
}
