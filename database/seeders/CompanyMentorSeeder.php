<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use App\Models\CompanyTeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class CompanyMentorSeeder extends Seeder
{
    /**
     * Seed dedicated Mentor accounts linked to each verified company profile.
     */
    public function run(): void
    {
        $mentorRole = Role::firstOrCreate(['name' => 'Mentor', 'guard_name' => 'web']);

        $companyMentorsData = [
            'PT TechNova Asia Digital' => [
                [
                    'name' => 'Budi Santoso (Lead Mentor Engineering)',
                    'email' => 'mentor@technova.com',
                    'password' => 'password',
                    'role_title' => 'Lead Software Engineering Mentor',
                ],
                [
                    'name' => 'Siska Wijaya (Frontend & UI Mentor)',
                    'email' => 'mentor.fe@technova.com',
                    'password' => 'password',
                    'role_title' => 'Frontend & Mobile Engineering Mentor',
                ],
            ],
            'PT GlobalCorp Digital' => [
                [
                    'name' => 'Reza Fahlevi (Enterprise Systems Mentor)',
                    'email' => 'mentor@globalcorp.com',
                    'password' => 'password',
                    'role_title' => 'Senior Enterprise Architect Mentor',
                ],
                [
                    'name' => 'Sarah Oktaviani (DevOps & Cloud Mentor)',
                    'email' => 'mentor.devops@globalcorp.com',
                    'password' => 'password',
                    'role_title' => 'Cloud & DevOps Engineering Mentor',
                ],
            ],
            'FinServe Digital Indonesia' => [
                [
                    'name' => 'Agus Setiawan (Fintech Security Mentor)',
                    'email' => 'mentor@finserve.id',
                    'password' => 'password',
                    'role_title' => 'Financial Technology & Security Mentor',
                ],
            ],
            'EduSmart Tech Indonesia' => [
                [
                    'name' => 'Dewi Anggraini (Fullstack & Curriculum Mentor)',
                    'email' => 'mentor@edusmart.id',
                    'password' => 'password',
                    'role_title' => 'Principal Learning & Tech Mentor',
                ],
            ],
            'AeroLogistics Transport' => [
                [
                    'name' => 'Fajar Nugroho (Supply Chain & IT Mentor)',
                    'email' => 'mentor@aerologistics.com',
                    'password' => 'password',
                    'role_title' => 'Logistics Automation & Data Mentor',
                ],
            ],
            'Studio Desain Kreasi Digital' => [
                [
                    'name' => 'Randy Pratama (Lead UI/UX & Design Mentor)',
                    'email' => 'mentor@studiokreasi.id',
                    'password' => 'password',
                    'role_title' => 'Lead Product Designer & UX Mentor',
                ],
            ],
        ];

        // 1. Seed specific mentors defined for known companies
        foreach ($companyMentorsData as $companyName => $mentors) {
            $company = CompanyProfile::where('company_name', $companyName)->first();
            if (!$company) {
                $company = CompanyProfile::firstOrCreate(
                    ['company_name' => $companyName],
                    [
                        'industry' => 'Software & Technology',
                        'company_size' => '50 - 200 Karyawan',
                        'province' => 'DKI Jakarta',
                        'city' => 'Jakarta Selatan',
                        'is_verified' => true,
                    ]
                );
            }

            $ownerUserId = $company->user_id ?? User::role('Company Owner')->first()?->id ?? 1;

            foreach ($mentors as $mentorData) {
                $mentorUser = User::firstOrCreate(
                    ['email' => $mentorData['email']],
                    [
                        'name' => $mentorData['name'],
                        'password' => Hash::make($mentorData['password']),
                    ]
                );

                if (!$mentorUser->hasRole('Mentor')) {
                    $mentorUser->assignRole($mentorRole);
                }

                CompanyTeamMember::updateOrCreate(
                    [
                        'company_profile_id' => $company->id,
                        'user_id' => $mentorUser->id,
                    ],
                    [
                        'role_title' => $mentorData['role_title'],
                        'status' => 'active',
                        'invited_by' => $ownerUserId,
                    ]
                );
            }
        }

        // 2. Ensure any other existing companies in DB also have at least 1 mentor
        $allCompanies = CompanyProfile::all();
        foreach ($allCompanies as $company) {
            $hasMentor = CompanyTeamMember::where('company_profile_id', $company->id)
                ->whereHas('user', function($q) {
                    $q->role('Mentor');
                })
                ->exists();

            if (!$hasMentor) {
                $slug = Str::slug($company->company_name);
                $mentorEmail = "mentor@{$slug}.com";
                if (strlen($slug) < 3) {
                    $mentorEmail = "mentor_{$company->id}@talentflow.com";
                }

                $mentorUser = User::firstOrCreate(
                    ['email' => $mentorEmail],
                    [
                        'name' => 'Mentor Pembimbing (' . $company->company_name . ')',
                        'password' => Hash::make('password'),
                    ]
                );

                if (!$mentorUser->hasRole('Mentor')) {
                    $mentorUser->assignRole($mentorRole);
                }

                CompanyTeamMember::updateOrCreate(
                    [
                        'company_profile_id' => $company->id,
                        'user_id' => $mentorUser->id,
                    ],
                    [
                        'role_title' => 'Pembimbing Magang Profesional',
                        'status' => 'active',
                        'invited_by' => $company->user_id,
                    ]
                );
            }
        }
    }
}
