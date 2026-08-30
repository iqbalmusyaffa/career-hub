<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipEvaluation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'evaluated_at' => 'datetime',
        'final_score' => 'float',
    ];

    public static function computeFinalScore($d, $i, $w, $t, $p): float
    {
        return round(($d * 0.20) + ($i * 0.20) + ($w * 0.25) + ($t * 0.20) + ($p * 0.15), 2);
    }

    public static function computeGrade($score): string
    {
        if ($score >= 85) return 'A';
        if ($score >= 75) return 'B';
        if ($score >= 65) return 'C';
        return 'D';
    }

    public function intern()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }
}
