<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmkReference extends Model
{
    use HasFactory;

    protected $fillable = [
        'province',
        'city_district',
        'umk_amount',
        'year',
        'legal_decree',
    ];

    public function getFormattedUmkAttribute(): string
    {
        return 'Rp ' . number_format($this->umk_amount, 0, ',', '.');
    }

    /**
     * Smart location matcher for UMK lookup.
     */
    public static function findByLocation($location)
    {
        if (empty($location)) {
            return null;
        }

        $cleanLoc = strtolower(trim($location));
        
        // Exact or LIKE match
        return static::whereRaw('LOWER(city_district) = ?', [$cleanLoc])
            ->orWhereRaw('LOWER(city_district) LIKE ?', ["%{$cleanLoc}%"])
            ->orWhereRaw('? LIKE CONCAT("%", LOWER(city_district), "%")', [$cleanLoc])
            ->first();
    }
}
