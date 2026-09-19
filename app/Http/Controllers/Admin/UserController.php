<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\Job;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    /**
     * Display a listing of all users (Super Admin only).
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'candidateProfile', 'companyProfile', 'applications']);

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'suspended') {
                $query->where('is_suspended', true);
            } elseif ($request->status === 'active') {
                $query->where('is_suspended', false);
            }
        }

        if ($request->filled('verified')) {
            if ($request->verified === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->verified === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        $users = $query->latest()->paginate($perPage)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Suspend or unsuspend user account with custom reason.
     */
    public function toggleSuspend(Request $request, User $user)
    {
        // Prevent Super Admin from suspending themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat memblokir akun Anda sendiri.');
        }

        $user->is_suspended = !$user->is_suspended;
        $user->status_reason = $user->is_suspended 
            ? ($request->input('reason') ?: 'Pelanggaran ketentuan layanan dan etika platform') 
            : null;
        $user->save();

        // If user is company owner, also suspend their company profile
        if ($user->companyProfile) {
            $user->companyProfile->is_suspended = $user->is_suspended;
            $user->companyProfile->save();
        }

        $statusMessage = $user->is_suspended 
            ? "Akun {$user->name} ({$user->email}) berhasil DIBLOKIR / DITANGGUHKAN." 
            : "Akun {$user->name} ({$user->email}) berhasil DIKEMBALIKAN AKTIF.";

        AuditLog::record('user_suspend_toggle', "Super Admin mengubah status akun {$user->name} menjadi " . ($user->is_suspended ? 'Suspended' : 'Active'));

        return back()->with('success', $statusMessage);
    }

    /**
     * Update user role directly.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|in:Candidate,HR,Company Owner,Super Admin,Mentor',
        ]);

        if ($user->id === Auth::id() && $request->role !== 'Super Admin') {
            return back()->with('error', 'Anda tidak dapat mencabut hak akses Super Admin dari akun Anda sendiri.');
        }

        $oldRole = $user->roles->pluck('name')->first() ?? 'Tidak ada';
        $user->syncRoles([$request->role]);

        AuditLog::record('user_role_changed', "Super Admin mengubah role {$user->name} dari {$oldRole} menjadi {$request->role}");

        return back()->with('success', "Role pengguna {$user->name} berhasil diperbarui menjadi {$request->role}.");
    }

    /**
     * Toggle email verification status manually.
     */
    public function toggleEmailVerification(User $user)
    {
        if ($user->email_verified_at) {
            $user->email_verified_at = null;
            $msg = "Status verifikasi email {$user->name} berhasil DICABUT.";
            $action = 'email_unverified';
        } else {
            $user->email_verified_at = now();
            $msg = "Email {$user->name} ({$user->email}) berhasil DIVERIFIKASI secara manual oleh Super Admin.";
            $action = 'email_verified_manually';
        }

        $user->save();
        AuditLog::record($action, "Super Admin mengubah status verifikasi email {$user->name}");

        return back()->with('success', $msg);
    }

    /**
     * Send password reset link to user email.
     */
    public function sendPasswordReset(User $user)
    {
        try {
            $token = Password::createToken($user);
            $user->sendPasswordResetNotification($token);

            AuditLog::record('password_reset_sent', "Super Admin mengirimkan tautan reset password ke {$user->email}");

            return back()->with('success', "Tautan reset kata sandi telah berhasil dikirimkan ke email {$user->email}.");
        } catch (\Throwable $e) {
            return back()->with('error', "Gagal mengirimkan email reset sandi: " . $e->getMessage());
        }
    }

    /**
     * Impersonate user account (Login as User).
     */
    public function impersonate(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda sudah berada di akun ini.');
        }

        session(['impersonator_id' => Auth::id()]);
        Auth::login($user);

        AuditLog::record('impersonate_start', "Super Admin masuk sebagai {$user->name} ({$user->email})");

        if ($user->hasRole('Candidate')) {
            return redirect()->route('dashboard')->with('success', "Anda sedang masuk dalam mode simulasi sebagai: {$user->name} (Kandidat)");
        }

        return redirect()->route('admin.dashboard')->with('success', "Anda sedang masuk dalam mode simulasi sebagai: {$user->name} ({$user->roles->first()?->name})");
    }

    /**
     * Leave impersonation and restore Super Admin session.
     */
    public function leaveImpersonation()
    {
        if (session()->has('impersonator_id')) {
            $superAdminId = session('impersonator_id');
            session()->forget('impersonator_id');

            $superAdmin = User::find($superAdminId);
            if ($superAdmin) {
                Auth::login($superAdmin);
                AuditLog::record('impersonate_end', "Super Admin mengakhiri sesi impersonasi dan kembali ke akun utama.");
                return redirect()->route('admin.users.index')->with('success', "Sesi simulasi diakhiri. Anda telah kembali ke akun Super Admin.");
            }
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * Bulk action on multiple users.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:suspend,activate,verify_email,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = array_diff($request->user_ids, [Auth::id()]); // Do not affect current user
        if (empty($userIds)) {
            return back()->with('error', 'Tidak ada akun valid yang dapat diproses.');
        }

        $count = count($userIds);

        switch ($request->action) {
            case 'suspend':
                User::whereIn('id', $userIds)->update([
                    'is_suspended' => true,
                    'status_reason' => 'Penangguhan massal oleh Super Admin'
                ]);
                AuditLog::record('bulk_suspend', "Super Admin menangguhkan {$count} akun sekaligus.");
                $msg = "Sebanyak {$count} akun pengguna berhasil DIBLOKIR / DITANGGUHKAN.";
                break;

            case 'activate':
                User::whereIn('id', $userIds)->update([
                    'is_suspended' => false,
                    'status_reason' => null
                ]);
                AuditLog::record('bulk_activate', "Super Admin mengaktifkan {$count} akun sekaligus.");
                $msg = "Sebanyak {$count} akun pengguna berhasil DIAKTIFKAN KEMBALI.";
                break;

            case 'verify_email':
                User::whereIn('id', $userIds)->whereNull('email_verified_at')->update([
                    'email_verified_at' => now()
                ]);
                AuditLog::record('bulk_verify_email', "Super Admin memverifikasi email {$count} akun secara massal.");
                $msg = "Sebanyak {$count} akun pengguna berhasil DIVERIFIKASI EMAIL-nya.";
                break;

            case 'delete':
                User::whereIn('id', $userIds)->delete();
                AuditLog::record('bulk_delete', "Super Admin menghapus permanen {$count} akun secara massal.");
                $msg = "Sebanyak {$count} akun pengguna berhasil DIHAPUS PERMANEN.";
                break;
        }

        return back()->with('success', $msg);
    }

    /**
     * Export users list as CSV file.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = User::with(['roles', 'candidateProfile', 'companyProfile']);

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'suspended') {
                $query->where('is_suspended', true);
            } elseif ($request->status === 'active') {
                $query->where('is_suspended', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->get();
        $filename = 'export_pengguna_' . date('Y-m-d_His') . '.csv';

        AuditLog::record('export_users', "Super Admin mengekspor data {$users->count()} pengguna ke file CSV");

        return response()->streamDownload(function () use ($users) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Headers
            fputcsv($handle, [
                'ID',
                'Nama Lengkap',
                'Alamat Email',
                'Role Akses',
                'Afiliasi / Info Profil',
                'Status Akun',
                'Alasan Suspensi',
                'Status Verifikasi Email',
                'Tanggal Registrasi'
            ]);

            foreach ($users as $u) {
                $primaryRole = $u->roles->pluck('name')->first() ?? 'User';
                $affiliate = $u->companyProfile ? $u->companyProfile->company_name : ($u->candidateProfile ? ($u->candidateProfile->headline ?: 'Pencari Kerja') : '-');
                $status = $u->is_suspended ? 'Ditangguhkan' : 'Aktif';
                $emailVerified = $u->email_verified_at ? 'Terverifikasi (' . $u->email_verified_at->format('Y-m-d H:i') . ')' : 'Belum Verifikasi';

                fputcsv($handle, [
                    $u->id,
                    $u->name,
                    $u->email,
                    $primaryRole,
                    $affiliate,
                    $status,
                    $u->status_reason ?: '-',
                    $emailVerified,
                    $u->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Permanently delete violating user account.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $userEmail = $user->email;
        $user->delete();

        AuditLog::record('user_deleted', "Super Admin menghapus akun {$userName} ({$userEmail})");

        return back()->with('success', "Akun {$userName} ({$userEmail}) beserta seluruh datanya telah berhasil DIHAPUS PERMANEN dari platform.");
    }

    /**
     * Display a listing of all companies (Super Admin only).
     */
    public function companies(Request $request)
    {
        $query = CompanyProfile::with(['user']);

        if ($request->filled('verification')) {
            if ($request->verification === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->verification === 'unverified') {
                $query->where('is_verified', false);
            }
        }

        if ($request->filled('status')) {
            if ($request->status === 'suspended') {
                $query->where('is_suspended', true);
            } elseif ($request->status === 'active') {
                $query->where('is_suspended', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('company_name', 'like', "%{$search}%");
        }

        $perPage = (int) $request->input('per_page', 10);
        $companies = $query->latest()->paginate($perPage)->withQueryString();

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Verify or unverify company profile.
     */
    public function toggleCompanyVerify(CompanyProfile $company)
    {
        $company->is_verified = !$company->is_verified;
        $company->save();

        $statusMsg = $company->is_verified 
            ? "Perusahaan {$company->company_name} berhasil DIVERIFIKASI (Centang Biru Aktif)." 
            : "Status verifikasi perusahaan {$company->company_name} telah DICABUT.";

        AuditLog::record('company_verified_toggle', "Super Admin mengubah status verifikasi perusahaan {$company->company_name}");

        return back()->with('success', $statusMsg);
    }

    /**
     * Suspend or unsuspend company profile.
     */
    public function toggleCompanySuspend(CompanyProfile $company)
    {
        $company->is_suspended = !$company->is_suspended;
        $company->save();

        // Also update the owner user suspension status
        if ($company->user) {
            $company->user->is_suspended = $company->is_suspended;
            $company->user->save();
        }

        $statusMsg = $company->is_suspended 
            ? "Perusahaan {$company->company_name} berhasil DIBLOKIR / SUSPEND." 
            : "Perusahaan {$company->company_name} berhasil DIKEMBALIKAN / AKTIF KEMBALI.";

        AuditLog::record('company_status_toggled', "Super Admin memproses status suspend perusahaan {$company->company_name}");

        return back()->with('success', $statusMsg);
    }

    /**
     * Master Update Company Data (Super Admin Direct Override).
     */
    public function updateCompany(Request $request, CompanyProfile $company)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'google_maps_link' => 'nullable|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'legal_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5048',
        ]);

        $data = $request->only([
            'company_name', 'industry', 'company_size', 'phone', 'address', 
            'province', 'city', 'district', 'village', 'postal_code',
            'website', 'google_maps_link', 'description'
        ]);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('company_logos', 'public');
        }

        if ($request->hasFile('legal_doc')) {
            $data['legal_doc_path'] = $request->file('legal_doc')->store('legal_documents', 'public');
        }

        $company->update($data);

        AuditLog::record('company_updated', "Super Admin mengedit data perusahaan {$company->company_name}");

        return back()->with('success', "Data perusahaan {$company->company_name} berhasil diperbarui oleh Super Admin!");
    }

    /**
     * Master Delete Company Profile (Super Admin Direct Delete).
     */
    public function destroyCompany(CompanyProfile $company)
    {
        $companyName = $company->company_name;
        $company->delete();

        AuditLog::record('company_deleted', "Super Admin menghapus profil perusahaan {$companyName}");

        return back()->with('success', "Profil perusahaan {$companyName} beserta seluruh lowongan terkait berhasil DIHAPUS PERMANEN.");
    }

    /**
     * Preview / Stream Company Profile Legal Document for Super Admin.
     */
    public function viewCompanyDocument(CompanyProfile $company)
    {
        if (!$company->legal_doc_path || !Storage::disk('public')->exists($company->legal_doc_path)) {
            abort(404, 'Dokumen legalitas perusahaan tidak ditemukan pada server.');
        }

        $fullPath = Storage::disk('public')->path($company->legal_doc_path);
        $mimeType = Storage::disk('public')->mimeType($company->legal_doc_path) ?: 'application/pdf';

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($company->legal_doc_path) . '"'
        ]);
    }
}
