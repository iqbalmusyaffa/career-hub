<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\InternshipLogbook;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $hrRole = Role::firstOrCreate(['name' => 'HR']);
        $ownerRole = Role::firstOrCreate(['name' => 'Company Owner']);
        $mentorRole = Role::firstOrCreate(['name' => 'Mentor']);
        $candidateRole = Role::firstOrCreate(['name' => 'Candidate']);

        // Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@talentflow.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        // Create HR User
        $admin = User::firstOrCreate(
            ['email' => 'admin@talentflow.com'],
            [
                'name' => 'HR Manager',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($hrRole);

        // Create Company Owner User
        $owner = User::firstOrCreate(
            ['email' => 'owner@technova.com'],
            [
                'name' => 'Direktur TechNova Asia',
                'password' => Hash::make('password'),
            ]
        );
        $owner->assignRole($ownerRole);

        // Seed Company Profile for Owner
        $company = CompanyProfile::firstOrCreate(
            ['user_id' => $owner->id],
            [
                'company_name' => 'PT TechNova Asia Digital',
                'industry' => 'Software & Technology',
                'company_size' => '50 - 200 Karyawan',
                'website' => 'https://technova-asia.com',
                'phone' => '021-55443322',
                'address' => 'Gedung Cyber Tower Lt. 12, Jl. HR Rasuna Said, Jakarta Selatan',
                'description' => 'TechNova Asia adalah penyedia solusi teknologi finansial dan transformasi digital terdepan di Asia Tenggara.',
                'is_verified' => true,
            ]
        );

        // Seed Global National Holidays managed by Super Admin (company_id = null)
        \App\Models\CompanyHoliday::firstOrCreate(
            ['date' => '2026-08-17'],
            ['name' => 'HUT Kemerdekaan Republik Indonesia Ke-81', 'type' => 'national_holiday', 'company_id' => null]
        );
        \App\Models\CompanyHoliday::firstOrCreate(
            ['date' => '2026-08-25'],
            ['name' => 'Cuti Bersama Pemerintah', 'type' => 'cuti_bersama', 'company_id' => null]
        );

        // Create Mentor User
        $mentor = User::firstOrCreate(
            ['email' => 'mentor@technova.com'],
            [
                'name' => 'Budi Santoso (Lead Mentor)',
                'password' => Hash::make('password'),
            ]
        );
        $mentor->assignRole($mentorRole);

        // Create Dummy Candidate / Intern
        $candidate = User::firstOrCreate(
            ['email' => 'candidate@talentflow.com'],
            [
                'name' => 'Ahmad Rizky (Anak Magang)',
                'password' => Hash::make('password'),
            ]
        );
        $candidate->assignRole($candidateRole);

        // Seed Internship Period / Batch
        \App\Models\InternshipPeriod::firstOrCreate(
            [
                'user_id' => $candidate->id,
                'period_name' => 'Batch 1 - Semester Genap 2026',
            ],
            [
                'company_id' => $company->id,
                'start_date' => '2026-08-10',
                'end_date' => '2026-09-09',
                'target_hours' => 400,
            ]
        );
        $fullPeriodData = [
            '2026-08-10' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Orientasi lingkungan kerja dan setup environment Laravel.', 'learnings' => 'Mempelajari arsitektur dasar Laravel 11.', 'challenges' => 'Konfigurasi environment lokal.'],
            '2026-08-11' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Eksplorasi modul autentikasi dan database migration.', 'learnings' => 'Memahami Spatie permission dan Sanctum token.', 'challenges' => 'Memahami relasi database yang kompleks.'],
            '2026-08-12' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Integrasi API endpoint dan pencocokan UMK 2026.', 'learnings' => 'Penggunaan Eloquent scope & custom traits.', 'challenges' => 'Validasi data UMK per daerah.'],
            '2026-08-13' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Pembuatan modul ujian online Pilihan Ganda (MCQ).', 'learnings' => 'Mempelajari pengujian otomatis passing score.', 'challenges' => 'Manajemen timer durasi ujian.'],
            '2026-08-14' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Pengerjaan bug fixes dan penyesuaian layout Tailwind.', 'learnings' => 'Menguasai responsive design & dark mode class.', 'challenges' => 'Cross-browser compatibility.'],
            
            '2026-08-18' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Implementasi E-Signature dan OTP verifikasi dokumen.', 'learnings' => 'Mempelajari hashing OTP dan digital contract signing.', 'challenges' => 'Integrasi PDF rendering engine.'],
            '2026-08-19' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Pengujian modul Live CV Builder interaktif.', 'learnings' => 'Alpine.js reactivity & DomPDF layouting.', 'challenges' => 'CSS flexbox pada DomPDF.'],
            '2026-08-20' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Refactoring API Documentation Swagger OpenAPI 3.0.', 'learnings' => 'Menulis L5-Swagger annotations.', 'challenges' => 'Dokumentasi schema JSON response.'],
            '2026-08-21' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Pemeriksaan audit log security & enkripsi URL HasEncryptedId.', 'learnings' => 'Mencegah serangan IDOR vulnerability.', 'challenges' => 'Dekripsi ID secara transparan.'],

            '2026-08-24' => [
                'status' => 'pending', 
                'type' => 'present', 
                'activities' => 'Setelah menyelesaikan proses testing dan pengecekan issue sebelumnya, kegiatan dilanjutkan dengan mempelajari dan memperdalam pemahaman mengenai bahasa pemrograman C#, framework .NET, serta database PostgreSQL yang digunakan dalam pengembangan aplikasi.', 
                'learnings' => 'Mempelajari konsep dasar dan struktur bahasa C#, memahami penggunaan framework .NET dalam pengembangan aplikasi, serta mempelajari dasar-dasar PostgreSQL untuk memahami pengelolaan dan penggunaan database pada aplikasi.', 
                'challenges' => 'Dalam proses pembelajaran, masih terdapat beberapa konsep pada C#, .NET, dan PostgreSQL yang perlu dipahami lebih lanjut melalui latihan dan eksplorasi agar dapat lebih memahami penerapannya pada aplikasi.'
            ],
            '2026-08-25' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Eksplorasi pembuatan REST API microservices dengan C# .NET Core.', 'learnings' => 'Memahami Controller, Dependency Injection, dan Entity Framework.', 'challenges' => 'Penyesuaian sintaks strongly-typed C#.'],
            '2026-08-26' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Pembangunan database schema PostgreSQL & pembuatan relasi foreign key.', 'learnings' => 'Memahami tipe data JSONB & indexing pada PostgreSQL.', 'challenges' => 'Optimasi query JOIN berlipat.'],
            '2026-08-27' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Integrasi autentikasi JWT token pada backend .NET Core.', 'learnings' => 'Memahami middleware JWT claim & token validation.', 'challenges' => 'Handling token expiration.'],
            '2026-08-28' => ['status' => 'action_required', 'type' => 'present', 'activities' => 'Pembuatan unit test dan pengujian automated endpoint.', 'learnings' => 'Menggunakan xUnit & Moq framework.', 'challenges' => 'Coverage test masih di bawah target 80%.'],

            '2026-08-31' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Refactoring arsitektur Clean Architecture / DDD pada service layer.', 'learnings' => 'Penerapan Repository pattern dan Unit of Work.', 'challenges' => 'Pemisahan concern antar layer.'],
            '2026-09-01' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Integrasi Redis Caching untuk mempercepat response time API.', 'learnings' => 'Prinsip In-Memory caching & TTL expiration.', 'challenges' => 'Cache invalidation strategy.'],
            '2026-09-02' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Pembuatan Dockerfile dan Docker Compose untuk containerization.', 'learnings' => 'Multi-stage build Docker & environment environment variables.', 'challenges' => 'Ukuran Docker image yang besar.'],
            '2026-09-03' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Setup CI/CD pipeline menggunakan GitHub Actions.', 'learnings' => 'Automated testing & deployment runner.', 'challenges' => 'Secret management pada CI/CD.'],
            '2026-09-04' => ['status' => 'pending', 'type' => 'present', 'activities' => 'Penulisan dokumentasi teknis & modul pengujian beban (Load Testing).', 'learnings' => 'K6 Load testing & stress analysis.', 'challenges' => 'Menganalisis bottleneck concurrency.'],

            '2026-09-07' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Persiapan materi presentasi hasil pengerjaan magang.', 'learnings' => 'Menyusun laporan akhir magang & slide presentasi.', 'challenges' => 'Penyusunan resume pencapaian.'],
            '2026-09-08' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Presentasi hasil proyek magang di depan Mentor & Tim Engineering.', 'learnings' => 'Public speaking & jawaban Q&A teknis.', 'challenges' => 'Penyampaian demo live secara lancar.'],
            '2026-09-09' => ['status' => 'approved', 'type' => 'present', 'activities' => 'Serah terima repositori kode & pembagian dokumentasi modul akhir.', 'learnings' => 'Handover proyek & dokumentasi arsitektur final.', 'challenges' => 'Penyelesaian administrasi magang.'],
        ];

        foreach ($fullPeriodData as $date => $data) {
            InternshipLogbook::firstOrCreate(
                ['user_id' => $candidate->id, 'date' => $date],
                [
                    'company_id' => $company->id,
                    'mentor_id' => $mentor->id,
                    'attendance_type' => $data['type'],
                    'work_hours' => 8,
                    'latitude' => '-6.2088',
                    'longitude' => '106.8456',
                    'location_address' => 'Gedung Cyber Tower Lt. 12, Rasuna Said, Jakarta',
                    'activities' => $data['activities'],
                    'learnings' => $data['learnings'],
                    'challenges' => $data['challenges'],
                    'status' => $data['status'],
                    'approved_at' => $data['status'] === 'approved' ? now() : null,
                ]
            );
        }
    }
}
