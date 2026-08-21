<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyRoleRequest;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Http\Request;

class AdminRoleRequestController extends Controller
{
    /**
     * Display all company role requests for Super Admin.
     */
    public function index()
    {
        $requests = CompanyRoleRequest::with('user')->latest()->get();
        return view('admin.role_requests.index', compact('requests'));
    }

    /**
     * Approve company role request.
     */
    public function approve(Request $request, $id)
    {
        $roleRequest = CompanyRoleRequest::findByEncryptedIdOrFail($id);
        $user = $roleRequest->user;

        // Assign Spatie Role 'Company Owner'
        $user->syncRoles(['Company Owner']);

        // Create or update verified CompanyProfile
        CompanyProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => $roleRequest->company_name,
                'industry' => $roleRequest->industry,
                'company_size' => $roleRequest->company_size,
                'phone' => $roleRequest->phone,
                'address' => $roleRequest->address,
                'legal_doc_path' => $roleRequest->legal_doc_path,
                'is_verified' => true,
            ]
        );

        $roleRequest->update([
            'status' => 'approved',
            'admin_notes' => $request->input('admin_notes', 'Pengajuan disetujui oleh Super Admin. Selamat bergabung!'),
        ]);

        // Send In-App Notification
        \App\Models\UserNotification::send(
            $user->id,
            "🎉 Pengajuan Perusahaan Disetujui!",
            "Selamat! Pengajuan akun Perusahaan '{$roleRequest->company_name}' Anda telah disetujui oleh Super Admin. Anda kini dapat memposting lowongan kerja.",
            route('admin.jobs.create'),
            'success'
        );

        \App\Models\AuditLog::record('company_role_approved', "Super Admin menyetujui akun Perusahaan '{$roleRequest->company_name}' untuk pengguna {$user->name}");

        return redirect()->back()->with('success', "Pengajuan Perusahaan '{$roleRequest->company_name}' BERHASIL DISETUJUI! Role pengguna telah diubah menjadi Company Owner.");
    }

    /**
     * Reject company role request.
     */
    public function reject(Request $request, $id)
    {
        $roleRequest = CompanyRoleRequest::findByEncryptedIdOrFail($id);
        $user = $roleRequest->user;

        $roleRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->input('admin_notes', 'Dokumen legalitas atau informasi perusahaan belum memenuhi syarat verifikasi.'),
        ]);

        // Send In-App Notification
        \App\Models\UserNotification::send(
            $user->id,
            "❌ Pengajuan Perusahaan Belum Disetujui",
            "Mohon maaf, pengajuan akun Perusahaan '{$roleRequest->company_name}' belum disetujui. Catatan Admin: " . $request->input('admin_notes'),
            route('profile.role-request.show'),
            'warning'
        );

        \App\Models\AuditLog::record('company_role_rejected', "Super Admin menolak pengajuan Perusahaan '{$roleRequest->company_name}' pengguna {$user->name}");

        return redirect()->back()->with('success', "Pengajuan Perusahaan '{$roleRequest->company_name}' telah ditolak.");
    }
}
