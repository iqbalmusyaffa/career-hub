<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CandidateProfile;
use App\Models\CompanyRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: "/auth/register",
        summary: "Registrasi Akun Pelamar (Candidate)",
        description: "Mendaftarkan akun kandidat/pelamar baru dan mengembalikan Bearer Token Sanctum.",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email", "password", "password_confirmation"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Budi Pratama"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "budi@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "Secret123!"),
                    new OA\Property(property: "password_confirmation", type: "string", format: "password", example: "Secret123!")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Registrasi berhasil",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Registrasi akun kandidat berhasil!",
                        "data" => [
                            "user" => [
                                "id" => 1,
                                "name" => "Budi Pratama",
                                "email" => "budi@example.com",
                                "roles" => ["Candidate"]
                            ],
                            "token" => "1|sanctum_bearer_token_string_sample"
                        ],
                        "errors" => null
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validasi error payload",
                content: new OA\JsonContent(
                    example: [
                        "success" => false,
                        "message" => "Validasi data registrasi gagal.",
                        "data" => null,
                        "errors" => [
                            "email" => ["The email has already been taken."]
                        ]
                    ]
                )
            )
        ]
    )]
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi data registrasi gagal.', 422, $validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Candidate', 'guard_name' => 'web']);
        $user->assignRole('Candidate');
        CandidateProfile::create(['user_id' => $user->id]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
            ],
            'token' => $token,
        ], 'Registrasi akun kandidat berhasil!', 201);
    }

    #[OA\Post(
        path: "/auth/login",
        summary: "Login User & Generasi Sanctum Token",
        description: "Otentikasi kredensial pengguna dan mengembalikan Sanctum Bearer Token.",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "budi@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "Secret123!")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Login berhasil",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Login berhasil!",
                        "data" => [
                            "user" => [
                                "id" => 1,
                                "name" => "Budi Pratama",
                                "email" => "budi@example.com",
                                "roles" => ["Candidate"]
                            ],
                            "token" => "2|sanctum_bearer_token_string_sample"
                        ],
                        "errors" => null
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Kredensial salah",
                content: new OA\JsonContent(
                    example: [
                        "success" => false,
                        "message" => "Email atau password yang Anda masukkan salah.",
                        "data" => null,
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi input login gagal.', 422, $validator->errors());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Email atau password yang Anda masukkan salah.', 401);
        }

        if ($user->is_suspended) {
            return $this->errorResponse('Akun Anda ditangguhkan: ' . ($user->status_reason ?: 'Pelanggaran aturan platform'), 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
            ],
            'token' => $token,
        ], 'Login berhasil!');
    }

    #[OA\Get(
        path: "/auth/me",
        summary: "Informasi User Saat Ini",
        description: "Mengambil profil pengguna terautentikasi.",
        security: [["bearerAuth" => []]],
        tags: ["Authentication"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Berhasil mengambil data pengguna",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Data pengguna berhasil diambil.",
                        "data" => [
                            "id" => 1,
                            "name" => "Budi Pratama",
                            "email" => "budi@example.com",
                            "avatar" => "avatars/sample.jpg",
                            "is_suspended" => false,
                            "roles" => ["Candidate"],
                            "candidate_profile" => [
                                "phone" => "081234567890",
                                "bio" => "Software Engineer",
                                "education" => "S1 Teknik Informatika"
                            ]
                        ],
                        "errors" => null
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated",
                content: new OA\JsonContent(
                    example: [
                        "message" => "Unauthenticated."
                    ]
                )
            )
        ]
    )]
    public function me(Request $request)
    {
        $user = $request->user()->load(['candidateProfile', 'companyProfile']);

        return $this->successResponse([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'is_suspended' => (bool)$user->is_suspended,
            'roles' => $user->getRoleNames(),
            'candidate_profile' => $user->candidateProfile,
            'company_profile' => $user->companyProfile,
        ], 'Data pengguna berhasil diambil.');
    }

    #[OA\Post(
        path: "/auth/logout",
        summary: "Logout User",
        description: "Mencabut access token Sanctum yang sedang aktif.",
        security: [["bearerAuth" => []]],
        tags: ["Authentication"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Logout berhasil",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Logout berhasil, token telah dicabut.",
                        "data" => null,
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logout berhasil, token telah dicabut.');
    }

    #[OA\Post(
        path: "/auth/role-request",
        summary: "Pengajuan Role Perusahaan / HR",
        description: "Mengajukan permohonan pengubahan role menjadi HR atau Company Owner.",
        security: [["bearerAuth" => []]],
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["requested_role", "company_name"],
                properties: [
                    new OA\Property(property: "requested_role", type: "string", example: "HR"),
                    new OA\Property(property: "company_name", type: "string", example: "PT Teknologi Nusantara"),
                    new OA\Property(property: "company_address", type: "string", example: "Jl. Sudirman No. 45, Jakarta"),
                    new OA\Property(property: "reason", type: "string", example: "Membutuhkan akses rekruitmen.")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Request berhasil diajukan",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Permohonan role berhasil dikirim.",
                        "data" => [
                            "id" => 1,
                            "user_id" => 1,
                            "requested_role" => "HR",
                            "company_name" => "PT Teknologi Nusantara",
                            "status" => "pending"
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function requestCompanyRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'requested_role' => 'required|in:HR,Company Owner',
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi pengajuan role gagal.', 422, $validator->errors());
        }

        $user = $request->user();

        $roleRequest = CompanyRoleRequest::create([
            'user_id' => $user->id,
            'requested_role' => $request->requested_role,
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return $this->successResponse($roleRequest, 'Permohonan role berhasil dikirim.', 201);
    }
}
