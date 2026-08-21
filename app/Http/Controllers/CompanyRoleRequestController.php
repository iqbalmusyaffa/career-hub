<?php

namespace App\Http\Controllers;

use App\Models\CompanyRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CompanyRoleRequestController extends Controller
{
    /**
     * Show company role request status & submission form for user.
     */
    public function show()
    {
        $user = Auth::user();
        $requests = CompanyRoleRequest::where('user_id', $user->id)->latest()->get();

        return view('profile.role_request', compact('user', 'requests'));
    }

    /**
     * Store candidate company role request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'requested_role' => 'required|string|in:Company Owner,HR',
            'company_name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'company_size' => 'nullable|string|max:100',
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:255',
            'npwp_number' => 'nullable|string|max:100',
            'legal_doc' => 'required|file|mimes:pdf|max:10240', // NIB / SIUP PDF
            'notes' => 'nullable|string|max:1000',
        ]);

        $companyFolder = 'company_legals/' . Str::slug($request->company_name);
        $legalPath = $request->file('legal_doc')->store($companyFolder, 'public');

        CompanyRoleRequest::create([
            'user_id' => $user->id,
            'requested_role' => $request->requested_role,
            'company_name' => $request->company_name,
            'industry' => $request->industry,
            'company_size' => $request->company_size,
            'phone' => $request->phone,
            'address' => $request->address,
            'bank_name' => $request->requested_role === 'Company Owner' ? $request->bank_name : null,
            'bank_account_number' => $request->requested_role === 'Company Owner' ? $request->bank_account_number : null,
            'bank_account_name' => $request->requested_role === 'Company Owner' ? $request->bank_account_name : null,
            'npwp_number' => $request->requested_role === 'Company Owner' ? $request->npwp_number : null,
            'legal_doc_path' => $legalPath,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // Security Alert to existing Owner if company already exists
        $existingProfile = CompanyProfile::all()->first(function ($p) use ($request) {
            $cleanInput = mb_strtolower(trim(preg_replace('/[^\w\s]/u', '', preg_replace('/\b(pt|pt\.|cv|cv\.|ud|ud\.)\b/u', '', $request->company_name))));
            $cleanProfile = mb_strtolower(trim(preg_replace('/[^\w\s]/u', '', preg_replace('/\b(pt|pt\.|cv|cv\.|ud|ud\.)\b/u', '', $p->company_name))));
            return $cleanInput !== '' && $cleanInput === $cleanProfile;
        });

        if ($existingProfile && $existingProfile->user_id && $existingProfile->user_id !== $user->id) {
            \App\Models\UserNotification::send(
                $existingProfile->user_id,
                "🛡️ Notifikasi Keamanan Pengajuan Perusahaan",
                "Pengguna '{$user->name}' ({$user->email}) baru saja mengajukan pendaftaran akun untuk perusahaan Anda ('{$existingProfile->company_name}'). Tim Super Admin akan memverifikasi berkas legalitas resmi sebelum menyetujui.",
                route('admin.company-team.index'),
                'warning'
            );
        }

        \App\Models\AuditLog::record('company_role_requested', "Pengguna {$user->name} mengajukan pendaftaran akun perusahaan '{$request->company_name}'");

        return redirect()->back()->with('success', 'Pengajuan Akun Perusahaan berhasil dikirim! Tim Super Admin akan memverifikasi dokumen Anda.');
    }
}
