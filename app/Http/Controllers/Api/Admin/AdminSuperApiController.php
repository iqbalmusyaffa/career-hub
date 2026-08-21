<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CompanyProfile;
use App\Models\CompanyRoleRequest;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AdminSuperApiController extends Controller
{
    #[OA\Get(
        path: "/admin/users",
        summary: "[Super Admin] Daftar Seluruh Pengguna Platform",
        description: "Mengambil seluruh daftar pengguna (Candidate, HR, Super Admin).",
        security: [["bearerAuth" => []]],
        tags: ["Super Admin Moderation"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar pengguna",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Daftar pengguna berhasil diambil.",
                        "data" => [
                            "current_page" => 1,
                            "data" => [
                                [
                                    "id" => 1,
                                    "name" => "Budi Pratama",
                                    "email" => "budi@example.com",
                                    "is_suspended" => false,
                                    "roles" => [
                                        ["name" => "Candidate"]
                                    ]
                                ]
                            ]
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function users(Request $request)
    {
        $users = User::with('roles')->latest()->paginate(20);

        return $this->successResponse($users, 'Daftar pengguna berhasil diambil.');
    }

    #[OA\Patch(
        path: "/admin/users/{id}/toggle-suspend",
        summary: "[Super Admin] Suspend / Unsuspend Akun User",
        description: "Menangguhkan atau mengaktifkan kembali akun pengguna.",
        security: [["bearerAuth" => []]],
        tags: ["Super Admin Moderation"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Status penangguhan akun diperbarui",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Akun pengguna Budi Pratama berhasil ditangguhkan (suspended).",
                        "data" => [
                            "id" => 1,
                            "name" => "Budi Pratama",
                            "is_suspended" => true,
                            "status_reason" => "Tindakan moderasi oleh Super Admin"
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function toggleUserSuspend(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse('Pengguna tidak ditemukan.', 404);
        }

        $user->update([
            'is_suspended' => !$user->is_suspended,
            'status_reason' => $request->input('reason', 'Tindakan moderasi oleh Super Admin'),
        ]);

        $statusText = $user->is_suspended ? 'ditangguhkan (suspended)' : 'diaktifkan kembali';

        return $this->successResponse($user, "Akun pengguna {$user->name} berhasil {$statusText}.");
    }

    #[OA\Get(
        path: "/admin/companies",
        summary: "[Super Admin] Daftar Perusahaan Terdaftar",
        description: "Mengambil daftar profil seluruh perusahaan mitra.",
        security: [["bearerAuth" => []]],
        tags: ["Super Admin Moderation"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar profil perusahaan",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Daftar perusahaan berhasil diambil.",
                        "data" => [
                            "current_page" => 1,
                            "data" => [
                                [
                                    "id" => 1,
                                    "company_name" => "PT Tech Nusantara",
                                    "is_verified" => true
                                ]
                            ]
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function companies(Request $request)
    {
        $companies = CompanyProfile::with('user')->latest()->paginate(20);

        return $this->successResponse($companies, 'Daftar perusahaan berhasil diambil.');
    }

    #[OA\Patch(
        path: "/admin/companies/{id}/toggle-verify",
        summary: "[Super Admin] Verifikasi / Unverify Perusahaan",
        description: "Memberikan centang biru verifikasi resmi kepada profil perusahaan.",
        security: [["bearerAuth" => []]],
        tags: ["Super Admin Moderation"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Status verifikasi diperbarui",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Status verifikasi perusahaan PT Tech Nusantara berhasil diubah menjadi terverifikasi.",
                        "data" => [
                            "id" => 1,
                            "company_name" => "PT Tech Nusantara",
                            "is_verified" => true
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function toggleCompanyVerify(Request $request, $id)
    {
        $company = CompanyProfile::find($id);

        if (!$company) {
            return $this->errorResponse('Perusahaan tidak ditemukan.', 404);
        }

        $company->update([
            'is_verified' => !$company->is_verified,
        ]);

        $statusText = $company->is_verified ? 'terverifikasi' : 'tidak terverifikasi';

        return $this->successResponse($company, "Status verifikasi perusahaan {$company->company_name} berhasil diubah menjadi {$statusText}.");
    }

    #[OA\Get(
        path: "/admin/role-requests",
        summary: "[Super Admin] Daftar Pengajuan Role HR/Company",
        description: "Mengambil permohonan penambahan role HR / Company Owner.",
        security: [["bearerAuth" => []]],
        tags: ["Super Admin Moderation"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar pengajuan role",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Daftar pengajuan role berhasil diambil.",
                        "data" => [
                            "current_page" => 1,
                            "data" => [
                                [
                                    "id" => 1,
                                    "user_id" => 1,
                                    "requested_role" => "HR",
                                    "company_name" => "PT Tech Nusantara",
                                    "status" => "pending"
                                ]
                            ]
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function roleRequests(Request $request)
    {
        $requests = CompanyRoleRequest::with('user')->latest()->paginate(20);

        return $this->successResponse($requests, 'Daftar pengajuan role berhasil diambil.');
    }

    #[OA\Post(
        path: "/admin/role-requests/{id}/approve",
        summary: "[Super Admin] Setujui Permohonan Role HR",
        description: "Setujui permohonan role HR/Company Owner dan tetapkan role Spatie kepada user.",
        security: [["bearerAuth" => []]],
        tags: ["Super Admin Moderation"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Role request disetujui",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Permohonan role HR untuk Budi Pratama disetujui!",
                        "data" => [
                            "id" => 1,
                            "requested_role" => "HR",
                            "status" => "approved"
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function approveRoleRequest(Request $request, $id)
    {
        $roleReq = CompanyRoleRequest::find($id);

        if (!$roleReq || $roleReq->status !== 'pending') {
            return $this->errorResponse('Permohonan tidak ditemukan atau sudah diproses.', 404);
        }

        $user = $roleReq->user;
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleReq->requested_role, 'guard_name' => 'web']);
        $user->assignRole($roleReq->requested_role);

        CompanyProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['company_name' => $roleReq->company_name, 'address' => $roleReq->company_address]
        );

        $roleReq->update(['status' => 'approved']);

        return $this->successResponse($roleReq, "Permohonan role {$roleReq->requested_role} untuk {$user->name} disetujui!");
    }

    #[OA\Get(
        path: "/admin/audit-logs",
        summary: "[Super Admin] Audit Logs Keamanan Platform",
        description: "Mengambil catatan riwayat log aktivitas keamanan pengguna.",
        security: [["bearerAuth" => []]],
        tags: ["Super Admin Moderation"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Audit logs",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Log audit sistem berhasil diambil.",
                        "data" => [
                            "current_page" => 1,
                            "data" => [
                                [
                                    "id" => 1,
                                    "user_id" => 1,
                                    "action" => "LOGIN",
                                    "ip_address" => "127.0.0.1",
                                    "created_at" => "2026-08-21T22:00:00.000000Z"
                                ]
                            ]
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function auditLogs(Request $request)
    {
        $logs = AuditLog::with('user')->latest()->paginate(30);

        return $this->successResponse($logs, 'Log audit sistem berhasil diambil.');
    }
}
