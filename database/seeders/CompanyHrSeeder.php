<?php

namespace Database\Seeders;

use App\Models\CompanyLeavePolicy;
use App\Models\CompanyProfile;
use App\Models\CompanyTeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CompanyHrSeeder extends Seeder
{
    /**
     * Seed dedicated HR accounts linked to their respective verified companies.
     */
    public function run(): void
    {
        $hrRole = Role::firstOrCreate(['name' => 'HR', 'guard_name' => 'web']);
        $ownerRole = Role::firstOrCreate(['name' => 'Company Owner', 'guard_name' => 'web']);

        $companiesData = [
            [
                'company' => [
                    'company_name' => 'PT TechNova Asia Digital',
                    'industry' => 'Software & Technology',
                    'company_size' => '50 - 200 Karyawan',
                    'website' => 'https://technova-asia.com',
                    'phone' => '021-55443322',
                    'province' => 'DKI Jakarta',
                    'city' => 'Jakarta Selatan',
                    'address' => 'Gedung Cyber Tower Lt. 12, Jl. HR Rasuna Said, Kuningan',
                    'description' => 'TechNova Asia adalah penyedia solusi teknologi finansial, cloud computing, dan transformasi digital terdepan di Asia Tenggara.',
                    'is_verified' => true,
                ],
                'owner' => [
                    'name' => 'Hendrawan Pratama (Direktur Utama)',
                    'email' => 'owner@technova.com',
                    'password' => 'password',
                ],
                'hr_users' => [
                    [
                        'name' => 'Rina Agustina, S.Psi (HR Manager)',
                        'email' => 'hr@technova.com',
                        'password' => 'password',
                        'role_title' => 'Human Resources Manager',
                    ],
                    [
                        'name' => 'Dwi Prasetyo (Lead Recruiter)',
                        'email' => 'recruiter@technova.com',
                        'password' => 'password',
                        'role_title' => 'Senior Talent Acquisition',
                    ],
                    [
                        'name' => 'HR Manager TalentFlow',
                        'email' => 'admin@talentflow.com',
                        'password' => 'password',
                        'role_title' => 'Head of People Operations',
                    ],
                ],
            ],
            [
                'company' => [
                    'company_name' => 'PT GlobalCorp Digital',
                    'industry' => 'Konsultan IT & Multi Perusahaan',
                    'company_size' => '100 - 500 Karyawan',
                    'website' => 'https://globalcorp-digital.co.id',
                    'phone' => '022-77889900',
                    'province' => 'Jawa Barat',
                    'city' => 'Bandung',
                    'address' => 'Wisma Dago Techno Park Lt. 5, Jl. Ir. H. Juanda No. 88',
                    'description' => 'Konsultan transformasi digital terpercaya yang menangani implementasi enterprise software dan pengembangan aplikasi skala besar.',
                    'is_verified' => true,
                ],
                'owner' => [
                    'name' => 'Bambang Soediro (Founder & CEO)',
                    'email' => 'owner@globalcorp.com',
                    'password' => 'password',
                ],
                'hr_users' => [
                    [
                        'name' => 'Dimas Prasetyo, M.M (HR Head)',
                        'email' => 'hr@globalcorp.com',
                        'password' => 'password',
                        'role_title' => 'Head of Talent & Culture',
                    ],
                    [
                        'name' => 'Citra Maharani (Talent Specialist)',
                        'email' => 'recruiter@globalcorp.com',
                        'password' => 'password',
                        'role_title' => 'Tech Recruiter Specialist',
                    ],
                ],
            ],
            [
                'company' => [
                    'company_name' => 'FinServe Digital Indonesia',
                    'industry' => 'Keuangan & Fintech',
                    'company_size' => '50 - 200 Karyawan',
                    'website' => 'https://finserve.id',
                    'phone' => '021-88991122',
                    'province' => 'DKI Jakarta',
                    'city' => 'Jakarta Pusat',
                    'address' => 'SCBD Pacific Century Tower Lt. 18, Jl. Jend. Sudirman Kav. 52-53',
                    'description' => 'Platform teknologi finansial terdaftar OJK yang menyediakan solusi pembayaran digital dan micro-financing modern.',
                    'is_verified' => true,
                ],
                'owner' => [
                    'name' => 'Arif Setiawan (Managing Director)',
                    'email' => 'owner@finserve.id',
                    'password' => 'password',
                ],
                'hr_users' => [
                    [
                        'name' => 'Amanda Putri, S.Psi (HR Business Partner)',
                        'email' => 'hr@finserve.id',
                        'password' => 'password',
                        'role_title' => 'HR Business Partner',
                    ],
                ],
            ],
            [
                'company' => [
                    'company_name' => 'EduSmart Tech Indonesia',
                    'industry' => 'Teknologi Pendidikan',
                    'company_size' => '20 - 50 Karyawan',
                    'website' => 'https://edusmart.id',
                    'phone' => '0274-556677',
                    'province' => 'D.I. Yogyakarta',
                    'city' => 'Sleman',
                    'address' => 'Gedung Edukasi Bangsa Lt. 3, Jl. Kaliurang KM 5.5 No. 12',
                    'description' => 'Platform edutech inovatif yang berfokus pada pelatihan vokasi digital, sertifikasi profesi, dan bootcamp karir.',
                    'is_verified' => true,
                ],
                'owner' => [
                    'name' => 'Rahmat Hidayat (Chief Executive Officer)',
                    'email' => 'owner@edusmart.id',
                    'password' => 'password',
                ],
                'hr_users' => [
                    [
                        'name' => 'Bayu Pratama (People & Culture)',
                        'email' => 'hr@edusmart.id',
                        'password' => 'password',
                        'role_title' => 'People Operations Lead',
                    ],
                ],
            ],
            [
                'company' => [
                    'company_name' => 'AeroLogistics Transport',
                    'industry' => 'Logistik & Transportasi',
                    'company_size' => '100 - 500 Karyawan',
                    'website' => 'https://aerologistics.co.id',
                    'phone' => '031-89998811',
                    'province' => 'Jawa Timur',
                    'city' => 'Surabaya',
                    'address' => 'Kompleks Pergudangan Darmo Sentosa Blok A-4, Jl. Raya Darmo',
                    'description' => 'Perusahaan logistik kargo nasional terintegrasi dengan jaringan armada darat, laut, dan udara ke seluruh pelosok nusantara.',
                    'is_verified' => true,
                ],
                'owner' => [
                    'name' => 'Gunawan Santoso (Direktur Logistik)',
                    'email' => 'owner@aerologistics.com',
                    'password' => 'password',
                ],
                'hr_users' => [
                    [
                        'name' => 'Hendra Wijaya (HR & General Affairs)',
                        'email' => 'hr@aerologistics.com',
                        'password' => 'password',
                        'role_title' => 'HR & GA Supervisor',
                    ],
                ],
            ],
            [
                'company' => [
                    'company_name' => 'Studio Desain Kreasi Digital',
                    'industry' => 'Desain Kreatif & Multimedia',
                    'company_size' => '10 - 50 Karyawan',
                    'website' => 'https://kreasidigital-studio.com',
                    'phone' => '021-7223344',
                    'province' => 'DKI Jakarta',
                    'city' => 'Jakarta Selatan',
                    'address' => 'Jl. Senopati No. 23, Kebayoran Baru',
                    'description' => 'Agensi kreatif visual spesialis UI/UX design, branding korporat, video motion, dan design system aplikasi mobile.',
                    'is_verified' => true,
                ],
                'owner' => [
                    'name' => 'Kevin Aditya (Creative Director)',
                    'email' => 'owner@studiokreasi.id',
                    'password' => 'password',
                ],
                'hr_users' => [
                    [
                        'name' => 'Nadia Safitri (People & Studio Ops)',
                        'email' => 'hr@studiokreasi.id',
                        'password' => 'password',
                        'role_title' => 'People Operations Specialist',
                    ],
                ],
            ],
        ];

        foreach ($companiesData as $data) {
            // 1. Create or Find Company Owner
            $ownerUser = User::firstOrCreate(
                ['email' => $data['owner']['email']],
                [
                    'name' => $data['owner']['name'],
                    'password' => Hash::make($data['owner']['password']),
                ]
            );
            $ownerUser->assignRole($ownerRole);

            // 2. Create or Update Company Profile owned by Owner User
            $companyProfile = CompanyProfile::updateOrCreate(
                ['company_name' => $data['company']['company_name']],
                array_merge($data['company'], ['user_id' => $ownerUser->id])
            );

            // 3. Initialize default Leave Policy for this company
            CompanyLeavePolicy::firstOrCreate(
                ['company_id' => $companyProfile->id],
                [
                    'annual_leave_quota' => 12,
                    'permanent_leave_quota' => 14,
                    'internship_max_excused_days' => 5,
                    'notes' => 'Kebijakan cuti standar resmi perusahaan ' . $companyProfile->company_name,
                ]
            );

            // 4. Create HR Team Members and connect them to CompanyProfile
            foreach ($data['hr_users'] as $hrData) {
                $hrUser = User::firstOrCreate(
                    ['email' => $hrData['email']],
                    [
                        'name' => $hrData['name'],
                        'password' => Hash::make($hrData['password']),
                    ]
                );
                $hrUser->assignRole($hrRole);

                // Link to Company via CompanyTeamMember
                CompanyTeamMember::updateOrCreate(
                    [
                        'company_profile_id' => $companyProfile->id,
                        'user_id' => $hrUser->id,
                    ],
                    [
                        'role_title' => $hrData['role_title'],
                        'status' => 'active',
                        'invited_by' => $ownerUser->id,
                    ]
                );
            }
        }
    }
}
