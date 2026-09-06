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
        
        $companyProfile = $user->currentCompanyProfile();
        if (!$companyProfile) {
            $companyProfile = CompanyProfile::create([
                'user_id' => $user->id,
                'company_name' => 'PT ' . $user->name,
                'is_verified' => false
            ]);
        }

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
        $companyProfile = $currentUser->currentCompanyProfile();
        if (!$companyProfile) {
            $companyProfile = CompanyProfile::create([
                'user_id' => $currentUser->id,
                'company_name' => 'PT ' . $currentUser->name,
                'is_verified' => false
            ]);
        }

        // Find or create user
        $user = User::where('email', $request->email)->first();
        $tempPassword = null;

        if (!$user) {
            $tempPassword = 'HR' . rand(100000, 999999) . '!';
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($tempPassword),
            ]);
            $user->assignRole('HR');
        } else {
            if (!$user->hasRole('HR') && !$user->hasRole('Company Owner') && !$user->hasRole('Super Admin')) {
                $user->assignRole('HR');
            }
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

        if ($tempPassword) {
            $successMsg = "🎉 Akun HR baru untuk '{$user->name}' ({$user->email}) berhasil dibuatkan dan terhubung ke perusahaan! Password sementara: {$tempPassword} (Staf HR juga dapat langsung masuk via Login Google atau Lupa Password).";
        } else {
            $successMsg = "Anggota tim HR {$user->name} ({$request->role_title}) berhasil ditambahkan ke tim perusahaan!";
        }

        return back()->with('success', $successMsg);
    }

    /**
     * Remove member from team.
     */
    public function destroy($id)
    {
        $currentUser = Auth::user();
        $companyProfile = $currentUser->currentCompanyProfile() ?: CompanyProfile::where('user_id', $currentUser->id)->firstOrFail();

        $member = CompanyTeamMember::where('company_profile_id', $companyProfile->id)
            ->findOrFail($id);

        $memberName = $member->user->name ?? 'Anggota';
        $member->delete();

        AuditLog::record('team_member_removed', "Menghapus anggota tim HR: {$memberName}");

        return back()->with('success', "Anggota tim HR {$memberName} berhasil dihapus dari tim perusahaan.");
    }

    /**
     * Primary Owner approves pending Co-Owner join request.
     */
    public function approveCoOwner($id)
    {
        $currentUser = Auth::user();
        $companyProfile = $currentUser->currentCompanyProfile() ?: CompanyProfile::where('user_id', $currentUser->id)->firstOrFail();

        $member = CompanyTeamMember::where('company_profile_id', $companyProfile->id)
            ->findOrFail($id);

        $member->update([
            'role_title' => 'Co-Owner / Founder',
            'status' => 'active',
        ]);

        if ($member->user) {
            $member->user->syncRoles(['Company Owner']);
            UserNotification::send(
                $member->user->id,
                "🎉 Persetujuan Co-Owner Disetujui!",
                "Selamat! Pemilik Utama {$currentUser->name} telah menyetujui Anda sebagai Co-Owner di {$companyProfile->company_name}. Anda kini memiliki akses pengelolaan profil & bank perusahaan.",
                route('admin.company.profile.edit'),
                'success'
            );
        }

        AuditLog::record('co_owner_approved', "Owner Utama {$currentUser->name} menyetujui {$member->user->name} sebagai Co-Owner");

        return back()->with('success', "Permintaan Co-Owner {$member->user->name} BERHASIL DISETUJUI sebagai Co-Owner resmi!");
    }

    /**
     * Primary Owner rejects pending Co-Owner request (converts them to HR Specialist).
     */
    public function rejectCoOwner($id)
    {
        $currentUser = Auth::user();
        $companyProfile = $currentUser->currentCompanyProfile() ?: CompanyProfile::where('user_id', $currentUser->id)->firstOrFail();

        $member = CompanyTeamMember::where('company_profile_id', $companyProfile->id)
            ->findOrFail($id);

        $member->update([
            'role_title' => 'HR Specialist',
            'status' => 'active',
        ]);

        if ($member->user) {
            $member->user->syncRoles(['HR']);
            UserNotification::send(
                $member->user->id,
                "ℹ️ Penyesuaian Peran Tim HR",
                "Permintaan status Co-Owner di {$companyProfile->company_name} tidak disetujui oleh Owner Utama. Peran Anda ditetapkan sebagai HR Specialist.",
                route('admin.jobs.index'),
                'info'
            );
        }

        AuditLog::record('co_owner_rejected', "Owner Utama {$currentUser->name} menetapkan {$member->user->name} sebagai HR Specialist");

        return back()->with('success', "Permintaan Co-Owner {$member->user->name} ditolak dan ditetapkan sebagai HR Specialist.");
    }
}
