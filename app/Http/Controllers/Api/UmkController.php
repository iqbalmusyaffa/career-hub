<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UmkReference;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UmkController extends Controller
{
    #[OA\Get(
        path: "/umk-lookup",
        summary: "Pencarian UMK 2026 Wilayah",
        description: "Mencari besaran Nilai UMK Resmi 2026 berdasarkan lokasi kota/kabupaten.",
        tags: ["Regional UMK & Benchmark"],
        parameters: [
            new OA\Parameter(name: "location", in: "query", required: true, schema: new OA\Schema(type: "string", example: "Jakarta Pusat"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Data UMK ditemukan",
                content: new OA\JsonContent(
                    example: [
                        "found" => true,
                        "city_district" => "Kota Administrasi Jakarta Pusat",
                        "province" => "DKI JAKARTA",
                        "umk_amount" => 5729876,
                        "formatted_umk" => "Rp 5.729.876",
                        "legal_decree" => "KEPUTUSAN MENTERI KETENAGAKERJAAN REPUBLIK INDONESIA NOMOR 65 TAHUN 2026"
                    ]
                )
            )
        ]
    )]
    public function lookup(Request $request)
    {
        $location = $request->query('location');
        if (empty($location)) {
            return response()->json(['found' => false]);
        }

        $umk = UmkReference::findByLocation($location);

        if (!$umk) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'city_district' => $umk->city_district,
            'province' => $umk->province,
            'umk_amount' => (float) $umk->umk_amount,
            'formatted_umk' => $umk->formatted_umk,
            'legal_decree' => $umk->legal_decree,
        ]);
    }

    #[OA\Get(
        path: "/regions/cities",
        summary: "Daftar Wilayah Kota/Kabupaten Autocomplete",
        description: "Mengambil daftar kota/kabupaten di Indonesia untuk pilihan autocomplete.",
        tags: ["Regional UMK & Benchmark"],
        parameters: [
            new OA\Parameter(name: "query", in: "query", required: false, schema: new OA\Schema(type: "string", example: "Bandung"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar kota/kabupaten",
                content: new OA\JsonContent(
                    example: [
                        [
                            "city_district" => "Kota Bandung",
                            "province" => "JAWA BARAT",
                            "umk_amount" => 4737678
                        ],
                        [
                            "city_district" => "Kabupaten Bandung",
                            "province" => "JAWA BARAT",
                            "umk_amount" => 3972202
                        ]
                    ]
                )
            )
        ]
    )]
    public function cities(Request $request)
    {
        $search = $request->query('query', '');

        $query = UmkReference::query();
        if (!empty($search)) {
            $query->where('city_district', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%");
        }

        $results = $query->select('city_district', 'province', 'umk_amount')
            ->orderBy('city_district', 'asc')
            ->get();

        return response()->json($results);
    }
}
