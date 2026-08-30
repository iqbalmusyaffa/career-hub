<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipCertificate;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminCertificateController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = InternshipCertificate::with(['user', 'application.job.companyProfile', 'revoker'])->latest();

        if ($search) {
            $query->where('certificate_number', 'like', "%{$search}%")
                ->orWhere('participant_name', 'like', "%{$search}%")
                ->orWhere('institution_name', 'like', "%{$search}%");
        }

        $certificates = $query->paginate(15)->withQueryString();
        $totalCertificates = InternshipCertificate::count();
        $validCertificates = InternshipCertificate::where('is_revoked', false)->count();
        $revokedCertificates = InternshipCertificate::where('is_revoked', true)->count();

        return view('admin.certificates.index', compact(
            'certificates',
            'totalCertificates',
            'validCertificates',
            'revokedCertificates',
            'search'
        ));
    }

    public function revoke(Request $request, $id)
    {
        $request->validate([
            'revocation_reason' => 'required|string|min:10',
        ], [
            'revocation_reason.required' => 'Wajib mencantumkan alasan pencabutan sertifikat.',
        ]);

        $certificate = InternshipCertificate::findOrFail($id);
        $user = auth()->user();

        $certificate->update([
            'is_revoked' => true,
            'revoked_at' => now(),
            'revocation_reason' => $request->revocation_reason,
            'revoked_by' => $user->id,
        ]);

        AuditLog::record(
            'SUPERADMIN_REVOKE_CERTIFICATE',
            "Super Admin {$user->name} MENCABUT Sertifikat Magang #{$certificate->certificate_number} atas nama {$certificate->participant_name}. Alasan: '{$request->revocation_reason}'",
            $user
        );

        return redirect()->route('admin.certificates.index')
            ->with('warning', "Sertifikat #{$certificate->certificate_number} milik {$certificate->participant_name} BERHASIL DICABUT.");
    }

    public function restore($id)
    {
        $certificate = InternshipCertificate::findOrFail($id);
        $user = auth()->user();

        $certificate->update([
            'is_revoked' => false,
            'revoked_at' => null,
            'revocation_reason' => null,
            'revoked_by' => null,
        ]);

        AuditLog::record(
            'SUPERADMIN_RESTORE_CERTIFICATE',
            "Super Admin {$user->name} MENGAKTIFKAN KEMBALI Sertifikat Magang #{$certificate->certificate_number} atas nama {$certificate->participant_name}.",
            $user
        );

        return redirect()->route('admin.certificates.index')
            ->with('success', "Sertifikat #{$certificate->certificate_number} milik {$certificate->participant_name} telah dipulihkan / aktif kembali.");
    }
}
