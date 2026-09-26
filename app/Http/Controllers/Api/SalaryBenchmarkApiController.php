<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UmkReference;
use App\Services\SalaryBenchmarkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SalaryBenchmarkApiController extends Controller
{
    #[OA\Get(
        path: "/salary-benchmark",
        summary: "Kalkulator & Rekomendasi Gaji Pasar (Salary Benchmark)",
        description: "Menghitung rekomendasi gaji realistis dan akurat berdasarkan jenis pekerjaan, jenjang karir (level), lokasi kota (UMK 2026), dan keahlian khusus (skills).",
        tags: ["Salary Benchmark & Insights"],
        parameters: [
            new OA\Parameter(name: "position", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Backend Developer")),
            new OA\Parameter(name: "location", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Kota Administrasi Jakarta Selatan")),
            new OA\Parameter(name: "level", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Mid Level (2-5 Tahun)")),
            new OA\Parameter(name: "skills", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Laravel, PostgreSQL, Docker, AWS"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Perhitungan rekomendasi gaji berhasil",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Rekomendasi gaji berhasil dikalkulasi.",
                        "data" => [
                            "query" => [
                                "position" => "Backend Developer",
                                "canonical_role" => "Backend Developer",
                                "category" => "Tech & Software Engineering",
                                "level" => "Mid",
                                "level_name" => "Mid Level",
                                "years_of_experience" => "2 - 5 Tahun",
                                "location" => "Jakarta",
                                "skills" => ["Laravel", "Docker", "AWS"]
                            ],
                            "salary_range" => [
                                "currency" => "IDR",
                                "min_monthly" => 15200000,
                                "median_monthly" => 19000000,
                                "max_monthly" => 30400000,
                                "formatted" => [
                                    "min" => "Rp 15.200.000",
                                    "median" => "Rp 19.000.000",
                                    "max" => "Rp 30.400.000",
                                    "summary" => "Rp 15.2 Juta - Rp 30.4 Juta / bulan"
                                ]
                            ]
                        ]
                    ]
                )
            )
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $params = [
            'position' => $request->input('position', 'Software Engineer'),
            'location' => $request->input('location', 'Jakarta'),
            'level' => $request->input('level', 'Fresh Graduate'),
            'skills' => $request->input('skills', ''),
        ];

        $result = SalaryBenchmarkService::calculate($params);

        return response()->json([
            'success' => true,
            'message' => 'Rekomendasi gaji berhasil dikalkulasi sesuai posisi dan wilayah.',
            'data' => $result,
        ]);
    }

    #[OA\Get(
        path: "/salary-benchmark/roles",
        summary: "Daftar Kategori & Posisi Pekerjaan Benchmark",
        description: "Mengambil katalog posisi pekerjaan, kategori industri, tingkat permintaan pasar, dan skill yang dicari.",
        tags: ["Salary Benchmark & Insights"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Katalog posisi pekerjaan"
            )
        ]
    )]
    public function roles(): JsonResponse
    {
        $catalog = SalaryBenchmarkService::getRolesCatalog();
        $seniorityLevels = SalaryBenchmarkService::getSeniorityLevels();

        return response()->json([
            'success' => true,
            'message' => 'Katalog profesi dan jenjang karir benchmark berhasil dimuat.',
            'data' => [
                'categories' => $catalog,
                'seniority_levels' => $seniorityLevels,
                'total_roles' => array_sum(array_map('count', $catalog)),
            ],
        ]);
    }

    #[OA\Get(
        path: "/salary-benchmark/compare",
        summary: "Bandingkan Gaji Antar Wilayah / Posisi",
        description: "Membandingkan rekomendasi gaji satu posisi di beberapa kota berbeda, atau beberapa posisi di kota yang sama.",
        tags: ["Salary Benchmark & Insights"],
        parameters: [
            new OA\Parameter(name: "position", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Software Engineer")),
            new OA\Parameter(name: "locations", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Jakarta,Surabaya,Bandung,Yogyakarta,Semarang")),
            new OA\Parameter(name: "level", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Mid"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Hasil komparasi multi wilayah"
            )
        ]
    )]
    public function compare(Request $request): JsonResponse
    {
        $position = $request->input('position', 'Software Engineer');
        $level = $request->input('level', 'Mid');
        $locationsParam = $request->input('locations', 'Jakarta,Surabaya,Bandung,Yogyakarta,Semarang,Bali');

        $locations = is_array($locationsParam) 
            ? $locationsParam 
            : array_filter(array_map('trim', explode(',', $locationsParam)));

        if (empty($locations)) {
            $locations = ['Jakarta', 'Surabaya', 'Bandung', 'Yogyakarta'];
        }

        $comparisons = [];
        foreach ($locations as $loc) {
            $calc = SalaryBenchmarkService::calculate([
                'position' => $position,
                'location' => $loc,
                'level' => $level,
                'skills' => $request->input('skills', ''),
            ]);

            $comparisons[] = [
                'location' => $calc['regional_benchmark']['city'],
                'province' => $calc['regional_benchmark']['province'],
                'umk_amount' => $calc['regional_benchmark']['umk_amount_2026'],
                'formatted_umk' => $calc['regional_benchmark']['formatted_umk'],
                'salary_min' => $calc['salary_range']['min_monthly'],
                'salary_median' => $calc['salary_range']['median_monthly'],
                'salary_max' => $calc['salary_range']['max_monthly'],
                'formatted_median' => $calc['salary_range']['formatted']['median'],
                'ratio_to_umk' => $calc['regional_benchmark']['ratio_to_umk'],
                'net_thp' => $calc['take_home_pay_estimate']['formatted_net_thp'],
            ];
        }

        // Sort by median salary descending
        usort($comparisons, fn($a, $b) => $b['salary_median'] <=> $a['salary_median']);

        return response()->json([
            'success' => true,
            'message' => 'Komparasi gaji lintas wilayah berhasil dikalkulasi.',
            'data' => [
                'position' => $position,
                'level' => $level,
                'comparisons' => $comparisons,
            ],
        ]);
    }

    #[OA\Get(
        path: "/salary-benchmark/locations",
        summary: "Daftar Kota Top Benchmark Indonesia",
        description: "Mengambil daftar kota utama dengan acuan UMK 2026 untuk benchmark.",
        tags: ["Salary Benchmark & Insights"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar kota benchmark"
            )
        ]
    )]
    public function locations(): JsonResponse
    {
        $topCities = [
            'Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 
            'Yogyakarta', 'Tangerang', 'Bekasi', 'Karawang', 'Depok', 
            'Bogor', 'Batam', 'Denpasar', 'Makassar', 'Malang', 'Surakarta'
        ];

        $results = [];
        foreach ($topCities as $city) {
            $umk = UmkReference::findByLocation($city);
            if ($umk) {
                $results[] = [
                    'city' => $umk->city_district,
                    'province' => $umk->province,
                    'umk_amount' => (int) $umk->umk_amount,
                    'formatted_umk' => $umk->formatted_umk,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar kota rujukan benchmark berhasil dimuat.',
            'data' => $results,
        ]);
    }
}
