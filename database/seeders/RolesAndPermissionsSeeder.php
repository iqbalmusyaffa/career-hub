<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
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
        \App\Models\CompanyProfile::firstOrCreate(
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

        // Create Dummy Candidate
        $candidate = User::firstOrCreate(
            ['email' => 'candidate@talentflow.com'],
            [
                'name' => 'Dummy Candidate',
                'password' => Hash::make('password'),
            ]
        );
        $candidate->assignRole($candidateRole);
    }
}
