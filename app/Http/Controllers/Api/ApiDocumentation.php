<?php

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Web-Karir (Career Hub) Secure REST API",
    description: "Dokumentasi REST API lengkap dan terproteksi untuk platform Web-Karir (Career Hub). Menggunakan Laravel Sanctum Bearer Token Authentication, Rate Limiting, dan Spatie Role-Based Access Control.",
    contact: new OA\Contact(name: "Web-Karir Support Team", email: "admin@webkarir.com")
)]
#[OA\Server(
    url: "/api/v1",
    description: "Web-Karir Primary API Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    name: "Authorization",
    in: "header",
    bearerFormat: "JWT",
    scheme: "bearer",
    description: "Masukkan Sanctum Bearer Token yang didapatkan dari endpoint POST /api/v1/auth/login"
)]
class ApiDocumentation
{
    // Class placeholder for Swagger Root OpenAPI Definition
}
