<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyTeamMember;
use App\Models\CompanyBranch;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class AdminCompanyTeamApiController extends Controller
{
    // ==========================================
    // 1. COMPANY TEAM MANAGEMENT API
    // ==========================================

    #[OA\Get(
        path: "/admin/company-team",
        summary: "[HR] Daftar Tim HR Perusahaan",
        description: "Mengambil daftar anggota tim HR perusahaan.",
        security: [["bearerAuth" => []]],
        tags: ["Company Management"],
        responses: [
            new OA\Response(response: 200, description: "Daftar anggota tim HR")
        ]
    )]
    public function teamIndex(Request $request)
    {
        $user = $request->user();
        $companyProfile = $user->currentCompanyProfile();
        if (!$companyProfile) {
            return $this->successResponse([], 'Perusahaan belum terdaftar.');
        }

        $members = CompanyTeamMember::with('user')
            ->where('company_profile_id', $companyProfile->id)
            ->latest()
            ->paginate(15);

        return $this->successResponse($members, 'Daftar tim HR perusahaan berhasil diambil.');
    }

    #[OA\Post(
        path: "/admin/company-team",
        summary: "[Company Owner] Tambah Anggota Tim HR",
        description: "Mendaftarkan anggota tim HR baru.",
        security: [["bearerAuth" => []]],
        tags: ["Company Management"],
        responses: [
            new OA\Response(response: 201, description: "Anggota tim berhasil ditambahkan")
        ]
    )]
    public function teamStore(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|in:HR Manager,HR Staff',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi pendaftaran anggota tim gagal.', 422, $validator->errors());
        }

        $companyProfile = $user->currentCompanyProfile();
        if (!$companyProfile) {
            $companyProfile = CompanyProfile::firstOrCreate(['user_id' => $user->id], ['company_name' => 'PT ' . $user->name]);
        }

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password123'),
        ]);

        \Spatie\Permission\Models\Role::firstOrCreate(['name' => $request->role, 'guard_name' => 'web']);
        $newUser->assignRole($request->role);

        $member = CompanyTeamMember::create([
            'company_profile_id' => $companyProfile->id,
            'user_id' => $newUser->id,
            'role_title' => $request->role,
            'invited_by' => $user->id,
        ]);

        return $this->successResponse($member, "Anggota tim {$newUser->name} ({$request->role}) berhasil ditambahkan!", 201);
    }

    #[OA\Delete(
        path: "/admin/company-team/{id}",
        summary: "[Company Owner] Hapus Anggota Tim HR",
        description: "Menghapus anggota tim HR perusahaan.",
        security: [["bearerAuth" => []]],
        tags: ["Company Management"],
        responses: [
            new OA\Response(response: 200, description: "Anggota tim dihapus")
        ]
    )]
    public function teamDestroy(Request $request, $id)
    {
        $member = CompanyTeamMember::find($id);
        if (!$member) {
            return $this->errorResponse('Anggota tim tidak ditemukan.', 404);
        }

        $member->delete();
        return $this->successResponse(null, 'Anggota tim HR berhasil dihapus.');
    }

    // ==========================================
    // 2. COMPANY BRANCHES API
    // ==========================================

    #[OA\Get(
        path: "/admin/company/branches",
        summary: "[Company Owner] Daftar Cabang Kantor",
        description: "Mengambil daftar lokasi kantor cabang perusahaan.",
        security: [["bearerAuth" => []]],
        tags: ["Company Management"],
        responses: [
            new OA\Response(response: 200, description: "Daftar cabang")
        ]
    )]
    public function branchesIndex(Request $request)
    {
        $user = $request->user();
        $companyProfile = $user->currentCompanyProfile();
        if (!$companyProfile) {
            return $this->successResponse([], 'Perusahaan belum terdaftar.');
        }

        $branches = CompanyBranch::where('company_profile_id', $companyProfile->id)->latest()->get();
        return $this->successResponse($branches, 'Daftar cabang perusahaan berhasil diambil.');
    }

    #[OA\Post(
        path: "/admin/company/branches",
        summary: "[Company Owner] Tambah Cabang Kantor Baru",
        description: "Menambahkan informasi kantor cabang perusahaan.",
        security: [["bearerAuth" => []]],
        tags: ["Company Management"],
        responses: [
            new OA\Response(response: 201, description: "Cabang berhasil ditambahkan")
        ]
    )]
    public function branchesStore(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'branch_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi data cabang gagal.', 422, $validator->errors());
        }

        $companyProfile = $user->currentCompanyProfile();
        if (!$companyProfile) {
            $companyProfile = CompanyProfile::firstOrCreate(['user_id' => $user->id], ['company_name' => 'PT ' . $user->name]);
        }

        $branch = CompanyBranch::create([
            'company_profile_id' => $companyProfile->id,
            'branch_name' => $request->branch_name,
            'city' => $request->city,
            'address' => $request->address,
            'phone' => $request->phone,
            'is_headquarter' => $request->boolean('is_main'),
        ]);

        return $this->successResponse($branch, "Cabang {$branch->branch_name} berhasil ditambahkan!", 201);
    }

    #[OA\Delete(
        path: "/admin/company/branches/{id}",
        summary: "[Company Owner] Hapus Cabang Kantor",
        description: "Menghapus lokasi kantor cabang perusahaan.",
        security: [["bearerAuth" => []]],
        tags: ["Company Management"],
        responses: [
            new OA\Response(response: 200, description: "Cabang dihapus")
        ]
    )]
    public function branchesDestroy($id)
    {
        $branch = CompanyBranch::find($id);
        if (!$branch) {
            return $this->errorResponse('Cabang tidak ditemukan.', 404);
        }

        $branch->delete();
        return $this->successResponse(null, 'Cabang perusahaan berhasil dihapus.');
    }

    // ==========================================
    // 3. HR EMAIL TEMPLATES API
    // ==========================================

    #[OA\Get(
        path: "/admin/email-templates",
        summary: "[HR] Daftar Template Email HR",
        description: "Mengambil daftar template email rekrutmen.",
        security: [["bearerAuth" => []]],
        tags: ["HR Recruitment Pipeline"],
        responses: [
            new OA\Response(response: 200, description: "Daftar template email")
        ]
    )]
    public function templatesIndex()
    {
        $templates = EmailTemplate::latest()->paginate(15);
        return $this->successResponse($templates, 'Daftar template email HR berhasil diambil.');
    }

    #[OA\Post(
        path: "/admin/email-templates",
        summary: "[HR] Buat Template Email HR Baru",
        description: "Membuat template email rekrutmen baru.",
        security: [["bearerAuth" => []]],
        tags: ["HR Recruitment Pipeline"],
        responses: [
            new OA\Response(response: 201, description: "Template email berhasil dibuat")
        ]
    )]
    public function templatesStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'category' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi template email gagal.', 422, $validator->errors());
        }

        $template = EmailTemplate::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
            'category' => $request->category,
        ]);

        return $this->successResponse($template, 'Template email berhasil dibuat!', 201);
    }
}
