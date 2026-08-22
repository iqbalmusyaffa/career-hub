<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        // If Super Admin accesses company profile menu, redirect to Master Companies DataTables List!
        if ($user->hasRole('Super Admin')) {
            return redirect()->route('admin.companies.index');
        }

        $ownerId = $user->id;
        $teamMember = \App\Models\CompanyTeamMember::where('user_id', $user->id)->first();
        if ($teamMember) {
            $ownerId = $teamMember->owner_id;
        }

        $profile = CompanyProfile::firstOrCreate(
            ['user_id' => $ownerId],
            ['company_name' => $user->name . ' Company']
        );

        return view('admin.company_profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:255',
            'npwp_number' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'culture_description' => 'nullable|string',
            'benefits' => 'nullable|array',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:4096',
            'legal_doc' => 'nullable|mimes:pdf,jpg,png|max:5120',
        ]);

        $user = Auth::user();

        $ownerId = $user->id;
        $teamMember = \App\Models\CompanyTeamMember::where('user_id', $user->id)->first();
        if ($teamMember) {
            $ownerId = $teamMember->owner_id;
        }

        $profile = CompanyProfile::firstOrCreate(['user_id' => $ownerId]);

        $data = [
            'company_name' => $request->company_name,
            'tagline' => $request->tagline,
            'industry' => $request->industry,
            'company_size' => $request->company_size,
            'website' => $request->website,
            'phone' => $request->phone,
            'address' => $request->address,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name' => $request->bank_account_name,
            'npwp_number' => $request->npwp_number,
            'description' => $request->description,
            'culture_description' => $request->culture_description,
            'benefits' => $request->benefits ?? [],
        ];

        $companyFolder = 'company_files/' . \Illuminate\Support\Str::slug($request->company_name);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($profile->logo_path) {
                Storage::disk('public')->delete($profile->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store($companyFolder, 'public');
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            if ($profile->cover_image_path) {
                Storage::disk('public')->delete($profile->cover_image_path);
            }
            $data['cover_image_path'] = $request->file('cover_image')->store($companyFolder, 'public');
        }

        // Handle legal document upload (NIB / SIUP)
        if ($request->hasFile('legal_doc')) {
            if ($profile->legal_doc_path) {
                Storage::disk('public')->delete($profile->legal_doc_path);
            }
            $data['legal_doc_path'] = $request->file('legal_doc')->store($companyFolder, 'public');
        }

        $profile->update($data);

        return back()->with('success', 'Profil & Halaman Karir Perusahaan berhasil diperbarui!');
    }
}
