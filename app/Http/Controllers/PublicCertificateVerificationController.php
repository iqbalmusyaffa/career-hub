<?php

namespace App\Http\Controllers;

use App\Models\InternshipCertificate;
use Illuminate\Http\Request;

class PublicCertificateVerificationController extends Controller
{
    public function verify(Request $request, $code = null)
    {
        $searchCode = $code ?? $request->input('code');
        $certificate = null;
        $searched = false;

        if ($searchCode) {
            $searched = true;
            $certificate = InternshipCertificate::with(['user', 'application.job.companyProfile'])
                ->where('certificate_number', $searchCode)
                ->first();
        }

        return view('public.certificates.verify', compact('certificate', 'searchCode', 'searched'));
    }
}
