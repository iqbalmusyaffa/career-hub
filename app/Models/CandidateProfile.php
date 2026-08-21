<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'dob' => 'date',
        'educations' => 'array',
        'experiences' => 'array',
        'organizations' => 'array',
        'skills' => 'array',
        'languages' => 'array',
        'certificates' => 'array',
        'portfolios' => 'array',
        'achievements' => 'array',
        'references' => 'array',
        'job_preferences' => 'array',
        'social_links' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
