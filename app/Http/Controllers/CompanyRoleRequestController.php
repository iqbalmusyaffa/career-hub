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
            'company_name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'company_size' => 'nullable|string|max:100',
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string',
            'legal_doc' => 'required|file|mimes:pdf|max:10240', // NIB / SIUP PDF
            'notes' => 'nullable|string|max:1000',
        ]);

        $companyFolder = 'company_legals/' . Str::slug($request->company_name);
        $legalPath = $request->file('legal_doc')->store($companyFolder, 'public');

        CompanyRoleRequest::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'industry' => $request->industry,
            'company_size' => $request->company_size,
            'phone' => $request->phone,
            'address' => $request->address,
            'legal_doc_path' => $legalPath,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        \App\Models\AuditLog::record('company_role_requested', "Pengguna {$user->name} mengajukan pendaftaran akun perusahaan '{$request->company_name}'");

        return redirect()->back()->with('success', 'Pengajuan Akun Perusahaan berhasil dikirim! Tim Super Admin akan memverifikasi dokumen Anda.');
    }
}
