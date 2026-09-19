<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyRoleRequest;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        // Assign requested Spatie Role (Company Owner or HR)
        $targetRole = $roleRequest->requested_role ?: 'Company Owner';
        $user->syncRoles([$targetRole]);

        // Normalize company name (case-insensitive, space-normalized & legal prefix stripped: PT, CV, UD, dll)
        $canonicalInput = $this->canonicalCompanyName($roleRequest->company_name);

        // Find matching CompanyProfile (Exact or Canonical match without PT/CV)
        $existingProfile = CompanyProfile::all()->first(function ($p) use ($roleRequest, $canonicalInput) {
            $exactMatch = mb_strtolower(trim(preg_replace('/\s+/', ' ', $p->company_name))) === mb_strtolower(trim(preg_replace('/\s+/', ' ', $roleRequest->company_name)));
            $canonicalMatch = $this->canonicalCompanyName($p->company_name) === $canonicalInput;
            return $exactMatch || ($canonicalInput !== '' && $canonicalMatch);
        });

        if ($targetRole === 'HR') {
            if (!$existingProfile) {
                // Create new verified CompanyProfile initiated by HR request
                $existingProfile = CompanyProfile::create([
                    'user_id' => null, // Awaiting Company Owner to join & claim
                    'company_name' => $roleRequest->company_name,
                    'industry' => $roleRequest->industry,
                    'company_size' => $roleRequest->company_size,
                    'phone' => $roleRequest->phone,
                    'address' => $roleRequest->address,
                    'legal_doc_path' => $roleRequest->legal_doc_path,
                    'is_verified' => true,
                ]);
            }

            // Link HR user as team member to company profile
            \App\Models\CompanyTeamMember::firstOrCreate(
                [
                    'company_profile_id' => $existingProfile->id,
                    'user_id' => $user->id,
                ],
                [
                    'role_title' => 'HR Specialist',
                    'status' => 'active',
                    'invited_by' => $existingProfile->user_id,
                ]
            );

            // Notify Company Owner if owner account exists
            if ($existingProfile->user_id) {
                \App\Models\UserNotification::send(
                    $existingProfile->user_id,
                    "💼 Staf HR Baru Bergabung",
                    "Staf HR '{$user->name}' ({$user->email}) telah disetujui bergabung dengan perusahaan '{$existingProfile->company_name}'. Anda sebagai Company Owner dapat mengelola tim dan mengisi data Rekening Bank di Profil Perusahaan.",
                    route('admin.company-team.index'),
                    'info'
                );
            }
        } else {
            // Target role is Company Owner (or new profile)
            if ($existingProfile) {
                if ($existingProfile->user_id && $existingProfile->user_id !== $user->id) {
                    // Check existing Co-Owners count for this company profile
                    $coOwnersCount = 1 + \App\Models\CompanyTeamMember::where('company_profile_id', $existingProfile->id)
                        ->where('role_title', 'LIKE', '%Owner%')
                        ->count();

                    if ($coOwnersCount >= 3) {
                        // Max 3 Company Owners limit reached! Convert to HR Specialist
                        $user->syncRoles(['HR']);
                        \App\Models\CompanyTeamMember::firstOrCreate(
                            [
                                'company_profile_id' => $existingProfile->id,
                                'user_id' => $user->id,
                            ],
                            [
                                'role_title' => 'HR Specialist',
                                'status' => 'active',
                                'invited_by' => $existingProfile->user_id,
                            ]
                        );

                        \App\Models\UserNotification::send(
                            $user->id,
                            "⚠️ Batas Maksimal 3 Pemilik Perusahaan Tercapai",
                            "Perusahaan '{$existingProfile->company_name}' telah mencapai batas maksimal 3 Company Owner. Akun Anda disetujui sebagai HR Specialist.",
                            route('admin.jobs.index'),
                            'info'
                        );
                    } else {
                        // Create pending Co-Owner record requiring Primary Owner approval
                        $user->syncRoles(['HR']); // Temporary HR role until Primary Owner approves Co-Owner status
                        \App\Models\CompanyTeamMember::updateOrCreate(
                            [
                                'company_profile_id' => $existingProfile->id,
                                'user_id' => $user->id,
                            ],
                            [
                                'role_title' => 'Co-Owner (Pending Primary Owner Approval)',
                                'status' => 'pending_owner_approval',
                                'invited_by' => $existingProfile->user_id,
                            ]
                        );

                        // Notify Primary Owner to approve/reject this Co-Owner candidate
                        if ($existingProfile->user_id) {
                            \App\Models\UserNotification::send(
                                $existingProfile->user_id,
                                "👑 Permintaan Co-Owner Baru Memerlukan Persetujuan",
                                "Pengguna '{$user->name}' ({$user->email}) memohon menjadi Co-Owner '{$existingProfile->company_name}'. Dokumen telah diverifikasi Super Admin. Silakan berikan persetujuan akhir di menu Tim HR.",
                                route('admin.company-team.index'),
                                'warning'
                            );
                        }
                    }
                } else {
                    // Claim primary ownership of profile created previously by HR request
                    $existingProfile->update([
                        'user_id' => $user->id,
                        'industry' => $roleRequest->industry ?: $existingProfile->industry,
                        'company_size' => $roleRequest->company_size ?: $existingProfile->company_size,
                        'phone' => $roleRequest->phone ?: $existingProfile->phone,
                        'address' => $roleRequest->address ?: $existingProfile->address,
                        'bank_name' => $roleRequest->bank_name ?: $existingProfile->bank_name,
                        'bank_account_number' => $roleRequest->bank_account_number ?: $existingProfile->bank_account_number,
                        'bank_account_name' => $roleRequest->bank_account_name ?: $existingProfile->bank_account_name,
                        'npwp_number' => $roleRequest->npwp_number ?: $existingProfile->npwp_number,
                        'legal_doc_path' => $roleRequest->legal_doc_path ?: $existingProfile->legal_doc_path,
                        'is_verified' => true,
                    ]);

                    // Update team member records for HR staff
                    \App\Models\CompanyTeamMember::where('company_profile_id', $existingProfile->id)
                        ->update(['invited_by' => $user->id]);
                }
            } else {
                // Create new verified CompanyProfile for Owner
                CompanyProfile::create([
                    'user_id' => $user->id,
                    'company_name' => $roleRequest->company_name,
                    'industry' => $roleRequest->industry,
                    'company_size' => $roleRequest->company_size,
                    'phone' => $roleRequest->phone,
                    'address' => $roleRequest->address,
                    'bank_name' => $roleRequest->bank_name,
                    'bank_account_number' => $roleRequest->bank_account_number,
                    'bank_account_name' => $roleRequest->bank_account_name,
                    'npwp_number' => $roleRequest->npwp_number,
                    'legal_doc_path' => $roleRequest->legal_doc_path,
                    'is_verified' => true,
                ]);
            }
        }

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

    /**
     * Preview / Stream Company Legal Document (NIB/SIUP) securely inline in browser.
     */
     public function viewDocument($id)
     {
         $roleRequest = CompanyRoleRequest::findByEncryptedIdOrFail($id);

         if (!$roleRequest->legal_doc_path || !Storage::disk('public')->exists($roleRequest->legal_doc_path)) {
             abort(404, 'Dokumen legalitas tidak ditemukan pada server.');
         }

         $fullPath = Storage::disk('public')->path($roleRequest->legal_doc_path);
         $mimeType = Storage::disk('public')->mimeType($roleRequest->legal_doc_path) ?: 'application/pdf';

         return response()->file($fullPath, [
             'Content-Type' => $mimeType,
             'Content-Disposition' => 'inline; filename="' . basename($roleRequest->legal_doc_path) . '"'
         ]);
     }

    /**
     * Helper to normalize company name by stripping legal prefixes (PT, CV, UD, Inc, Ltd, etc.)
     */
    private function canonicalCompanyName($name)
    {
        if (!$name) return '';
        $clean = mb_strtolower(trim($name));
        // Remove legal prefixes & suffixes: PT, PT., CV, CV., UD, UD., Firma, Inc, Inc., Ltd, Ltd., Corp, Corp.
        $clean = preg_replace('/\b(pt|pt\.|cv|cv\.|ud|ud\.|firma|inc|inc\.|ltd|ltd\.|corp|corp\.)\b/u', '', $clean);
        // Remove punctuation
        $clean = preg_replace('/[^\w\s]/u', '', $clean);
        // Normalize whitespace
        $clean = preg_replace('/\s+/', ' ', $clean);
        return trim($clean);
    }
}
