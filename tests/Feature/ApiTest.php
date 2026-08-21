<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'Candidate']);
        Role::firstOrCreate(['name' => 'HR']);
        Role::firstOrCreate(['name' => 'Super Admin']);
    }

    public function test_can_register_candidate_via_api()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Kandidat API',
            'email' => 'kandidat.api' . time() . '@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'roles'],
                    'token',
                ]
            ]);
    }

    public function test_can_login_via_api_and_access_protected_me_endpoint()
    {
        $user = User::factory()->create([
            'password' => bcrypt('Secret123!'),
        ]);
        $user->assignRole('Candidate');

        $loginRes = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'Secret123!',
        ]);

        $loginRes->assertStatus(200);
        $token = $loginRes->json('data.token');

        $meRes = $this->withToken($token)->getJson('/api/v1/auth/me');
        $meRes->assertStatus(200)
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_unauthenticated_user_cannot_access_protected_endpoint()
    {
        $response = $this->getJson('/api/v1/candidate/profile');
        $response->assertStatus(401);
    }

    public function test_public_jobs_api_returns_jobs_list()
    {
        $user = User::factory()->create();
        $user->assignRole('HR');

        Job::create([
            'user_id' => $user->id,
            'company_name' => 'PT Tech Nusantara',
            'title' => 'Laravel API Developer',
            'division' => 'Engineering',
            'work_type' => 'Full-time',
            'location' => 'Jakarta',
            'salary' => '10.000.000 - 15.000.000',
            'description' => 'Lowongan backend laravel',
            'requirements' => 'Pengalaman Laravel 3 tahun',
            'benefits' => 'BPJS, Asuransi, Bonus',
            'deadline' => '2026-12-31',
            'status' => 'active',
        ]);

        $response = $this->getJson('/api/v1/jobs');
        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_swagger_documentation_page_is_accessible()
    {
        $response = $this->get('/api/documentation');
        $response->assertStatus(200);
    }
}
