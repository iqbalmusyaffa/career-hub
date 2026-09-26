<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipSurvey extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'mentor_rating',
        'program_rating',
        'environment_rating',
        'career_readiness_rating',
        'recommendation_nps',
        'is_anonymous',
        'feedback',
    ];

    protected $casts = [
        'mentor_rating' => 'integer',
        'program_rating' => 'integer',
        'environment_rating' => 'integer',
        'career_readiness_rating' => 'integer',
        'is_anonymous' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }
}
