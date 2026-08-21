<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Web-Karir (Career Hub) Secure REST API",
 *     version="1.0.0",
 *     description="Dokumentasi REST API lengkap dan terproteksi untuk platform Web-Karir (Career Hub). Menggunakan Laravel Sanctum Bearer Token Authentication, Rate Limiting, dan Spatie Role-Based Access Control.",
 *     @OA\Contact(
 *         email="admin@webkarir.com",
 *         name="Web-Karir Support Team"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Web-Karir Primary API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Gunakan token Sanctum yang didapatkan dari endpoint POST /api/v1/auth/login"
 * )
 */
abstract class Controller
{
    /**
     * Standardized JSON success response wrapper.
     */
    protected function successResponse($data = null, string $message = 'Success', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], $code);
    }

    /**
     * Standardized JSON error response wrapper.
     */
    protected function errorResponse(string $message = 'Error', int $code = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], $code);
    }
}
