<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UmkReference;
use Illuminate\Http\Request;

class UmkController extends Controller
{
    /**
     * Real-time UMK 2026 lookup by location query.
     */
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

    /**
     * Get list of all official Indonesian cities/districts for autocomplete.
     */
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
