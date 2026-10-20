<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\CandidateOnboarding;
use App\Models\InternshipBenefit;
use App\Models\InternshipStipendDisbursement;
use App\Models\InternshipUserBenefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateBenefitController extends Controller
{
    /**
     * Display candidate's BPJS protection status and assigned facilities.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Candidate') && !$user->isIntern()) {
            return redirect()->route('dashboard')->with('error', 'Fitur Fasilitas & BPJS Magang hanya dapat diakses oleh kandidat yang telah diterima sebagai Peserta Magang.');
        }

        $onboarding = CandidateOnboarding::where('user_id', $user->id)->first();
        $activeApp = $user->applications()->with('job.companyProfile')->latest()->first();
        $company = $activeApp?->job?->companyProfile;

        // Fetch assigned benefits
        $assignedBenefits = InternshipUserBenefit::with('benefit')
            ->where('user_id', $user->id)
            ->get();

        // If user doesn't have assigned benefits yet, automatically bootstrap the standard mandatory ones
        if ($assignedBenefits->isEmpty()) {
            $defaultMandatory = InternshipBenefit::where('is_mandatory', true)->where('is_active', true)->get();
            foreach ($defaultMandatory as $mb) {
                $policyNum = null;
                if ($mb->category === 'bpjs_tk') {
                    $policyNum = $onboarding?->bpjs_ketenagakerjaan_number ?: '22094819204';
                } elseif ($mb->category === 'bpjs_kes') {
                    $policyNum = $onboarding?->bpjs_kesehatan_number ?: '0002891048291';
                }

                InternshipUserBenefit::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'internship_benefit_id' => $mb->id,
                    ],
                    [
                        'policy_number' => $policyNum,
                        'status' => 'active',
                        'notes' => 'Premi perlindungan jaminan sosial ditanggung 100% oleh perusahaan mitra.',
                        'start_date' => now(),
                    ]
                );
            }

            $assignedBenefits = InternshipUserBenefit::with('benefit')
                ->where('user_id', $user->id)
                ->get();
        }

        $mandatoryUserBenefits = $assignedBenefits->filter(fn($ub) => $ub->benefit && $ub->benefit->is_mandatory);
        $optionalUserBenefits = $assignedBenefits->filter(fn($ub) => $ub->benefit && !$ub->benefit->is_mandatory);

        // Latest disbursed stipend for quick slip preview
        $latestStipend = InternshipStipendDisbursement::where('user_id', $user->id)
            ->orderByDesc('period_month')
            ->first();

        return view('candidate.internship.benefits', compact(
            'user',
            'onboarding',
            'company',
            'assignedBenefits',
            'mandatoryUserBenefits',
            'optionalUserBenefits',
            'latestStipend'
        ));
    }
}
