<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\CompanyTeamMember;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanyTeamController extends Controller
{
    /**
     * Display HR Team Members list.
     */
    public function index()
    {
        $user = Auth::user();
        
        $companyProfile = CompanyProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['company_name' => 'PT ' . $user->name, 'is_verified' => false]
        );

        $perPage = (int) request('per_page', 10);
        $teamMembers = CompanyTeamMember::with(['user', 'inviter'])
            ->where('company_profile_id', $companyProfile->id)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.company_team.index', compact('companyProfile', 'teamMembers'));
    }

    /**
     * Invite / Add new HR Team Member.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role_title' => 'required|string|in:Lead Recruiter,Interviewer,HR Specialist,HR Administrator',
        ]);

        $currentUser = Auth::user();
        $companyProfile = CompanyProfile::firstOrCreate(
            ['user_id' => $currentUser->id],
            ['company_name' => 'PT ' . $currentUser->name, 'is_verified' => false]
        );

        // Find or create user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(12)),
            ]);
            $user->assignRole('HR');
        }

        // Check if already in team
        $exists = CompanyTeamMember::where('company_profile_id', $companyProfile->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Pengguna ini sudah menjadi anggota tim HR perusahaan Anda.');
        }

        CompanyTeamMember::create([
            'company_profile_id' => $companyProfile->id,
            'user_id' => $user->id,
            'role_title' => $request->role_title,
            'status' => 'active',
            'invited_by' => $currentUser->id,
        ]);

        AuditLog::record('team_member_added', "Menambahkan anggota tim HR baru: {$user->name} ({$request->role_title})");

        UserNotification::send(
            $user->id,
            "🎉 Undangan Tim HR Perusahaan",
            "Anda telah ditambahkan sebagai anggota tim HR ({$request->role_title}) di {$companyProfile->company_name}.",
            route('admin.dashboard'),
            'success'
        );

        return back()->with('success', "Anggota tim HR {$user->name} ({$request->role_title}) berhasil ditambahkan!");
    }

    /**
     * Remove member from team.
     */
    public function destroy($id)
    {
        $currentUser = Auth::user();
        $companyProfile = CompanyProfile::where('user_id', $currentUser->id)->firstOrFail();

        $member = CompanyTeamMember::where('company_profile_id', $companyProfile->id)
            ->findOrFail($id);

        $memberName = $member->user->name ?? 'Anggota';
        $member->delete();

        AuditLog::record('team_member_removed', "Menghapus anggota tim HR: {$memberName}");

        return back()->with('success', "Anggota tim HR {$memberName} berhasil dihapus dari tim perusahaan.");
    }
}
