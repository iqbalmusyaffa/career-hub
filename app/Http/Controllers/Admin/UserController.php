<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of all users (Super Admin only).
     */
    public function index(Request $request)
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

        $perPage = (int) $request->input('per_page', 10);
        $users = $query->latest()->paginate($perPage)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Suspend or unsuspend user account.
     */
    public function toggleSuspend(Request $request, User $user)
    {
        // Prevent Super Admin from suspending themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat memblokir akun Anda sendiri.');
        }

        $user->is_suspended = !$user->is_suspended;
        $user->status_reason = $request->input('reason', $user->is_suspended ? 'Pelanggaran ketentuan layanan' : null);
        $user->save();

        // If user is company owner, also suspend their company profile
        if ($user->companyProfile) {
            $user->companyProfile->is_suspended = $user->is_suspended;
            $user->companyProfile->save();
        }

        $statusMessage = $user->is_suspended 
            ? "Akun {$user->name} ({$user->email}) berhasil DIBLOKIR / SUSPEND." 
            : "Akun {$user->name} ({$user->email}) berhasil DIKEMBALIKAN / AKTIF KEMBALI.";

        return back()->with('success', $statusMessage);
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
            'website' => 'nullable|url|max:255',
            'google_maps_link' => 'nullable|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'legal_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5048',
        ]);

        $data = $request->only([
            'company_name', 'industry', 'company_size', 'phone', 'address', 'website', 'google_maps_link', 'description'
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
}
