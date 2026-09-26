<?php

namespace Tests\Feature;

use App\Models\CompanyLeavePolicy;
use App\Models\CompanyProfile;
use App\Models\InternshipLogbook;
use App\Models\LeaveRequest;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles exist
        Role::firstOrCreate(['name' => 'HR', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Candidate', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Mentor', 'guard_name' => 'web']);
    }

    public function test_candidate_can_view_leaves_portal()
    {
        $candidate = User::factory()->create();
        $candidate->assignRole('Candidate');

        $response = $this->actingAs($candidate)->get(route('candidate.leaves.index'));
        $response->assertStatus(200);
        $response->assertSee('Portal Cuti & Izin Mandiri', false);
    }

    public function test_candidate_can_submit_and_cancel_leave_request()
    {
        $candidate = User::factory()->create();
        $candidate->assignRole('Candidate');

        // Next Monday to Next Wednesday (3 working days)
        $start = Carbon::now()->next(Carbon::MONDAY);
        $end = (clone $start)->addDays(2);

        $response = $this->actingAs($candidate)->post(route('candidate.leaves.store'), [
            'leave_type' => 'annual_leave',
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'reason' => 'Keperluan keluarga mendesak di luar kota.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leave_requests', [
            'user_id' => $candidate->id,
            'leave_type' => 'annual_leave',
            'total_days' => 3,
            'status' => 'pending',
        ]);

        $leave = LeaveRequest::where('user_id', $candidate->id)->first();

        // Cancel
        $cancelResponse = $this->actingAs($candidate)->post(route('candidate.leaves.cancel', $leave));
        $cancelResponse->assertRedirect();
        $this->assertEquals('cancelled', $leave->fresh()->status);

        // Test Academic Leave submission (Magang / Wisuda / Sidang)
        $academicResponse = $this->actingAs($candidate)->post(route('candidate.leaves.store'), [
            'leave_type' => 'academic_leave',
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $start->format('Y-m-d'),
            'reason' => 'Menghadiri Sidang Skripsi & Wisuda Kampus.',
        ]);
        $academicResponse->assertRedirect();
        $this->assertDatabaseHas('leave_requests', [
            'user_id' => $candidate->id,
            'leave_type' => 'academic_leave',
            'total_days' => 1,
        ]);
    }

    public function test_hr_can_manage_leaves_and_update_policies()
    {
        $hr = User::factory()->create();
        $hr->assignRole('HR');

        $company = CompanyProfile::firstOrCreate([
            'user_id' => $hr->id,
        ], [
            'company_name' => 'Tech Corp Indonesia',
        ]);

        $candidate = User::factory()->create();
        $candidate->assignRole('Candidate');

        $start = Carbon::now()->next(Carbon::MONDAY);
        $end = (clone $start)->addDays(1);

        $leave = LeaveRequest::create([
            'user_id' => $candidate->id,
            'company_id' => $company->id,
            'leave_type' => 'sick_leave',
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'total_days' => 2,
            'reason' => 'Sakit demam dan istirahat dokter',
            'status' => 'pending',
        ]);

        // 1. HR views leaves dashboard
        $viewResponse = $this->actingAs($hr)->get(route('admin.leaves.index'));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Manajemen Cuti & Izin', false);

        // 2. HR updates policy
        $policyResponse = $this->actingAs($hr)->post(route('admin.leaves.policy.update'), [
            'annual_leave_quota' => 12,
            'permanent_leave_quota' => 15,
            'internship_max_excused_days' => 4,
            'notes' => 'Pengajuan cuti wajib H-3.',
        ]);
        $policyResponse->assertRedirect();
        $this->assertDatabaseHas('company_leave_policies', [
            'company_id' => $company->id,
            'permanent_leave_quota' => 15,
            'internship_max_excused_days' => 4,
        ]);

        // 3. HR Approves leave
        $approveResponse = $this->actingAs($hr)->post(route('admin.leaves.approve', $leave), [
            'admin_notes' => 'Disetujui. Lekas sembuh!',
        ]);
        $approveResponse->assertRedirect();
        $this->assertEquals('approved', $leave->fresh()->status);
        $this->assertEquals($hr->id, $leave->fresh()->approver_id);
    }
}
