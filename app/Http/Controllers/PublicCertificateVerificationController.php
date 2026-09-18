<?php

namespace App\Http\Controllers;

use App\Models\InternshipCertificate;
use App\Models\InternshipTranscript;
use Illuminate\Http\Request;

class PublicCertificateVerificationController extends Controller
{
    public function verify(Request $request, $code = null)
    {
        $searchCode = trim($code ?? $request->input('code') ?? '');
        $certificate = null;
        $transcript = null;
        $searched = false;

        if (!empty($searchCode)) {
            $searched = true;

            // 1. Search by Certificate Number
            $certificate = InternshipCertificate::with(['user.candidateProfile', 'application.job.companyProfile', 'revoker'])
                ->where('certificate_number', $searchCode)
                ->orWhere('certificate_number', 'like', '%' . $searchCode . '%')
                ->first();

            // 2. Search by Transcript Number if certificate not matched directly
            if (!$certificate) {
                $transcript = InternshipTranscript::with(['user.candidateProfile', 'application.job.companyProfile'])
                    ->where('transcript_number', $searchCode)
                    ->orWhere('transcript_number', 'like', '%' . $searchCode . '%')
                    ->first();

                if ($transcript) {
                    $certificate = InternshipCertificate::with(['user.candidateProfile', 'application.job.companyProfile', 'revoker'])
                        ->where(function($q) use ($transcript) {
                            $q->where('application_id', $transcript->application_id)
                              ->orWhere('user_id', $transcript->user_id);
                        })
                        ->first();
                }
            } else {
                // Find matching transcript
                $transcript = InternshipTranscript::with(['user.candidateProfile', 'application.job.companyProfile'])
                    ->where(function($q) use ($certificate) {
                        $q->where('application_id', $certificate->application_id)
                          ->orWhere('user_id', $certificate->user_id);
                    })
                    ->first();
            }
        }

        return view('public.certificates.verify', compact('certificate', 'transcript', 'searchCode', 'searched'));
    }
}
