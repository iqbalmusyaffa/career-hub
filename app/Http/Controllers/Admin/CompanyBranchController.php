<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyBranch;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyBranchController extends Controller
{
    /**
     * Display list of company branches.
     */
    public function index()
    {
        $user = Auth::user();
        $companyProfile = $user->currentCompanyProfile() ?? CompanyProfile::firstOrCreate(['user_id' => $user->id]);
        $branches = CompanyBranch::where('company_profile_id', $companyProfile->id)
            ->withCount('jobs')
            ->latest()
            ->get();

        return view('admin.company.branches.index', compact('companyProfile', 'branches'));
    }

    /**
     * Store new company branch.
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $user = Auth::user();
        $companyProfile = $user->currentCompanyProfile() ?? CompanyProfile::firstOrCreate(['user_id' => $user->id]);

        if ($request->has('is_headquarter')) {
            CompanyBranch::where('company_profile_id', $companyProfile->id)->update(['is_headquarter' => false]);
        }

        CompanyBranch::create([
            'company_profile_id' => $companyProfile->id,
            'branch_name' => $request->branch_name,
            'city' => $request->city,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_headquarter' => $request->has('is_headquarter'),
        ]);

        return redirect()->back()->with('success', 'Cabang Perusahaan Berhasil Ditambahkan!');
    }

    /**
     * Delete company branch.
     */
    public function destroy(CompanyBranch $branch)
    {
        $branch->delete();
        return redirect()->back()->with('success', 'Cabang Perusahaan Berhasil Dihapus.');
    }
}
