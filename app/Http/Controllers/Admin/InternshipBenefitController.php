<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CandidateOnboarding;
use App\Models\CompanyProfile;
use App\Models\InternshipBatch;
use App\Models\InternshipBenefit;
use App\Models\InternshipUserBenefit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternshipBenefitController extends Controller
{
    /**
     * Display internship benefits, BPJS compliance dashboard, and participant coverage.
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $isSuperAdmin = $currentUser->hasRole('Super Admin');
        $companyProfile = $currentUser->currentCompanyProfile();
        $companyId = $companyProfile ? $companyProfile->id : null;

        // Master Benefits Catalog
        $benefitQuery = InternshipBenefit::withCount(['userBenefits as active_participants_count' => function ($q) {
            $q->where('status', 'active');
        }]);

        if (!$isSuperAdmin && $companyId) {
            $benefitQuery->where(function ($q) use ($companyId) {
                $q->where('company_id', $companyId)->orWhereNull('company_id');
            });
        }

        $allBenefits = $benefitQuery->orderByDesc('is_mandatory')->orderBy('name')->get();
        $mandatoryBenefits = $allBenefits->where('is_mandatory', true);
        $optionalBenefits = $allBenefits->where('is_mandatory', false);

        // Active Interns
        $internQuery = User::role('Candidate')
            ->where(function ($q) {
                $q->whereHas('internshipPeriod')
                  ->orWhereHas('applications', function ($sub) {
                      $sub->whereIn('status', ['accepted', 'hired'])
                          ->whereHas('job', function ($jq) {
                              $jq->where('work_type', 'like', '%Intern%')
                                ->orWhere('work_type', 'like', '%Magang%')
                                ->orWhereNotNull('batch');
                          });
                  });
            })
            ->with([
                'candidateProfile',
                'candidateOnboarding',
                'internshipPeriod',
                'internshipUserBenefits.benefit',
            ]);

        // Filter search intern
        if ($request->filled('search')) {
            $search = $request->search;
            $internQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $interns = $internQuery->orderBy('name')->paginate(12)->withQueryString();

        // Statistics
        $totalActiveInterns = User::role('Candidate')
            ->where(function ($q) {
                $q->whereHas('internshipPeriod')
                  ->orWhereHas('applications', fn($sub) => $sub->whereIn('status', ['accepted', 'hired']));
            })
            ->count();

        // BPJS TK & BPJS Kes Coverage calculation
        $coveredBpjsTkCount = InternshipUserBenefit::whereHas('benefit', fn($q) => $q->where('category', 'bpjs_tk'))
            ->where('status', 'active')
            ->distinct('user_id')
            ->count('user_id');

        $coveredBpjsKesCount = InternshipUserBenefit::whereHas('benefit', fn($q) => $q->where('category', 'bpjs_kes'))
            ->where('status', 'active')
            ->distinct('user_id')
            ->count('user_id');

        $totalOptionalFacilitiesGiven = InternshipUserBenefit::whereHas('benefit', fn($q) => $q->where('is_mandatory', false))
            ->where('status', 'active')
            ->count();

        return view('admin.internship_benefits.index', compact(
            'allBenefits',
            'mandatoryBenefits',
            'optionalBenefits',
            'interns',
            'totalActiveInterns',
            'coveredBpjsTkCount',
            'coveredBpjsKesCount',
            'totalOptionalFacilitiesGiven',
            'isSuperAdmin',
            'companyProfile'
        ));
    }

    /**
     * Store a newly created master benefit / facility in catalog.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|in:bpjs_tk,bpjs_kes,transport,meal,equipment,wellness,mentoring,allowance,other',
            'is_mandatory' => 'required|boolean',
            'coverage_type' => 'required|string|in:company_paid,cash_allowance,facility_access,non_cash',
            'standard_nominal' => 'nullable|numeric|min:0',
            'provider' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:1000',
            'terms' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:100',
            'badge_color' => 'nullable|string|max:50',
        ]);

        $currentUser = Auth::user();
        $companyProfile = $currentUser->currentCompanyProfile();

        $code = strtoupper($request->category) . '_' . time();

        $benefit = InternshipBenefit::create([
            'company_id' => $companyProfile?->id,
            'name' => $request->name,
            'code' => $code,
            'category' => $request->category,
            'is_mandatory' => (bool) $request->is_mandatory,
            'coverage_type' => $request->coverage_type,
            'standard_nominal' => $request->standard_nominal ?? 0,
            'provider' => $request->provider ?: ($request->category === 'bpjs_tk' ? 'BPJS Ketenagakerjaan RI' : ($request->category === 'bpjs_kes' ? 'BPJS Kesehatan RI' : ($companyProfile?->company_name ?? 'Perusahaan Mitra'))),
            'description' => $request->description,
            'terms' => $request->terms,
            'icon' => $request->icon ?: ($request->is_mandatory ? 'fa-solid fa-user-shield' : 'fa-solid fa-gift'),
            'badge_color' => $request->badge_color ?: ($request->is_mandatory ? 'emerald' : 'indigo'),
            'is_active' => true,
        ]);

        AuditLog::record('create_internship_benefit', "Menambahkan master fasilitas/benefit magang: {$benefit->name} (" . ($benefit->is_mandatory ? 'Wajib' : 'Fasilitas Tambahan') . ").");

        return redirect()->back()->with('success', "Master benefit '{$benefit->name}' berhasil ditambahkan ke katalog!");
    }

    /**
     * Update an existing master benefit.
     */
    public function update(Request $request, $id)
    {
        $benefit = InternshipBenefit::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|in:bpjs_tk,bpjs_kes,transport,meal,equipment,wellness,mentoring,allowance,other',
            'is_mandatory' => 'required|boolean',
            'coverage_type' => 'required|string|in:company_paid,cash_allowance,facility_access,non_cash',
            'standard_nominal' => 'nullable|numeric|min:0',
            'provider' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:1000',
            'terms' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $benefit->update([
            'name' => $request->name,
            'category' => $request->category,
            'is_mandatory' => (bool) $request->is_mandatory,
            'coverage_type' => $request->coverage_type,
            'standard_nominal' => $request->standard_nominal ?? 0,
            'provider' => $request->provider,
            'description' => $request->description,
            'terms' => $request->terms,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : $benefit->is_active,
        ]);

        AuditLog::record('update_internship_benefit', "Memperbarui master fasilitas/benefit magang: {$benefit->name}.");

        return redirect()->back()->with('success', "Master benefit '{$benefit->name}' berhasil diperbarui!");
    }

    /**
     * Delete or deactivate master benefit.
     */
    public function destroy($id)
    {
        $benefit = InternshipBenefit::findOrFail($id);
        $name = $benefit->name;

        // If it's mandatory BPJS, protect it from hard delete
        if ($benefit->is_mandatory) {
            $benefit->update(['is_active' => false]);
            return redirect()->back()->with('warning', "Benefit wajib '{$name}' dinonaktifkan (tidak dihapus permanen demi kepatuhan audit).");
        }

        $benefit->delete();
        AuditLog::record('delete_internship_benefit', "Menghapus master benefit magang: {$name}.");

        return redirect()->back()->with('success', "Master benefit '{$name}' berhasil dihapus dari katalog.");
    }

    /**
     * Assign or update participant's specific benefit.
     */
    public function assignUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'internship_benefit_id' => 'required|exists:internship_benefits,id',
            'policy_number' => 'nullable|string|max:100',
            'status' => 'required|in:active,pending,inactive',
            'custom_nominal' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $user = User::findOrFail($request->user_id);
        $benefit = InternshipBenefit::findOrFail($request->internship_benefit_id);

        $userBenefit = InternshipUserBenefit::updateOrCreate(
            [
                'user_id' => $user->id,
                'internship_benefit_id' => $benefit->id,
            ],
            [
                'policy_number' => $request->policy_number,
                'status' => $request->status,
                'custom_nominal' => $request->custom_nominal,
                'notes' => $request->notes,
                'start_date' => $request->start_date ?: now(),
                'end_date' => $request->end_date,
            ]
        );

        // Also sync to CandidateOnboarding if this is BPJS TK or BPJS Kes
        if (!empty($request->policy_number)) {
            $onboarding = CandidateOnboarding::firstOrNew(['user_id' => $user->id]);
            if ($benefit->category === 'bpjs_tk') {
                $onboarding->bpjs_ketenagakerjaan_number = $request->policy_number;
            } elseif ($benefit->category === 'bpjs_kes') {
                $onboarding->bpjs_kesehatan_number = $request->policy_number;
            }
            $onboarding->save();
        }

        AuditLog::record('assign_internship_benefit', "Mengalokasikan benefit {$benefit->name} untuk {$user->name} (No: {$request->policy_number}).");

        return redirect()->back()->with('success', "Benefit '{$benefit->name}' untuk {$user->name} berhasil diperbarui!");
    }

    /**
     * Toggle status of an assigned user benefit.
     */
    public function toggleUserBenefit(Request $request, $id)
    {
        $userBenefit = InternshipUserBenefit::with(['user', 'benefit'])->findOrFail($id);
        $newStatus = $userBenefit->status === 'active' ? 'inactive' : 'active';
        $userBenefit->update(['status' => $newStatus]);

        return redirect()->back()->with('success', "Status benefit '{$userBenefit->benefit?->name}' untuk {$userBenefit->user?->name} diubah menjadi: " . strtoupper($newStatus));
    }
}
