<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CompleteSystemApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles exist
        Role::firstOrCreate(['name' => 'Candidate']);
        Role::firstOrCreate(['name' => 'Mentor']);
        Role::firstOrCreate(['name' => 'HR']);
        Role::firstOrCreate(['name' => 'Super Admin']);
    }

    public function test_candidate_cv_api_endpoints()
    {
        $candidate = User::factory()->create();
        $candidate->assignRole('Candidate');

        $resGet = $this->actingAs($candidate, 'sanctum')->getJson('/api/v1/candidate/cv');
        $resGet->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['user', 'profile']
            ]);

        $resSave = $this->actingAs($candidate, 'sanctum')->postJson('/api/v1/candidate/cv', [
            'name' => 'John Doe Updated',
            'summary' => 'Experienced software engineer with 5+ years of building web applications.',
            'skills' => ['PHP', 'Laravel', 'Docker', 'AWS'],
        ]);

        $resSave->assertStatus(200);
        $this->assertEquals('John Doe Updated', $candidate->fresh()->name);
    }

    public function test_candidate_internship_logbook_api()
    {
        $candidate = User::factory()->create();
        $candidate->assignRole('Candidate');

        $resStore = $this->actingAs($candidate, 'sanctum')->postJson('/api/v1/candidate/internship/logbooks', [
            'date' => now()->format('Y-m-d'),
            'attendance_type' => 'present',
            'work_hours' => 8,
            'activities' => 'Mengembangkan modul REST API untuk sistem karir dan presensi harian.',
            'learnings' => 'Memahami arsitektur RESTful API & Sanctum token auth.',
        ]);

        $resStore->assertStatus(200)
            ->assertJsonPath('success', true);

        $resList = $this->actingAs($candidate, 'sanctum')->getJson('/api/v1/candidate/internship/logbooks');
        $resList->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_mentor_workspace_api()
    {
        $mentor = User::factory()->create();
        $mentor->assignRole('Mentor');

        $resDashboard = $this->actingAs($mentor, 'sanctum')->getJson('/api/v1/mentor/dashboard');
        $resDashboard->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['total_mentees', 'pending_logbooks', 'approved_logbooks']
            ]);

        $resInterns = $this->actingAs($mentor, 'sanctum')->getJson('/api/v1/mentor/interns');
        $resInterns->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_application_documents_api_and_eager_loading()
    {
        $hr = User::factory()->create();
        $hr->assignRole('HR');

        $candidate = User::factory()->create();
        $candidate->assignRole('Candidate');

        $job = \App\Models\Job::create([
            'user_id' => $hr->id,
            'company_name' => 'PT Tech Nusantara',
            'title' => 'Senior Backend Engineer',
            'division' => 'Engineering',
            'work_type' => 'Full-time',
            'location' => 'Jakarta',
            'salary' => '15.000.000 - 20.000.000',
            'description' => 'Lowongan backend engineer',
            'requirements' => 'Pengalaman Laravel 3 tahun',
            'benefits' => 'BPJS, Asuransi, Bonus',
            'deadline' => '2026-12-31',
            'status' => 'active',
        ]);

        $application = \App\Models\Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => \App\Enums\ApplicationStatus::PENDING,
        ]);

        // 1. Create Offer Letter
        \App\Models\OfferLetter::create([
            'application_id' => $application->id,
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'position_title' => $job->title,
            'offered_salary' => '15.000.000',
            'start_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'pending',
        ]);

        // 2. Create Certificate
        \App\Models\InternshipCertificate::create([
            'application_id' => $application->id,
            'user_id' => $candidate->id,
            'certificate_number' => 'CERT/TEST/001',
            'participant_name' => $candidate->name,
            'job_title' => $job->title,
            'start_date' => now()->subMonths(3)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'performance_grade' => 'A (Sangat Memuaskan)',
            'issued_at' => now()->format('Y-m-d'),
        ]);

        // 3. Test Admin Application Detail API
        $resShow = $this->actingAs($hr, 'sanctum')->getJson("/api/v1/admin/applications/{$application->id}");
        $resShow->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.offer_letter.position_title', $job->title)
            ->assertJsonCount(1, 'data.certificates');

        // 4. Test Candidate My Applications API
        $resCand = $this->actingAs($candidate, 'sanctum')->getJson('/api/v1/candidate/applications');
        $resCand->assertStatus(200)
            ->assertJsonPath('success', true);
    }
}
