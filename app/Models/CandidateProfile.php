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

    public function getCompletionPercentageAttribute(): int
    {
        $completed = 0;
        $totalSections = 11;

        if (!empty($this->nickname) && !empty($this->phone) && !empty($this->dob)) {
            $completed++;
        }
        if (!empty($this->summary)) {
            $completed++;
        }
        if (!empty($this->educations) && count($this->educations) > 0) {
            $completed++;
        }
        if (!empty($this->experiences) && count($this->experiences) > 0) {
            $completed++;
        }
        if (!empty($this->organizations) && count($this->organizations) > 0) {
            $completed++;
        }
        if ((!empty($this->skills) && count($this->skills) > 0) || (!empty($this->languages) && count($this->languages) > 0)) {
            $completed++;
        }
        if (!empty($this->certificates) && count($this->certificates) > 0) {
            $completed++;
        }
        if ((!empty($this->portfolios) && count($this->portfolios) > 0) || (!empty($this->achievements) && count($this->achievements) > 0)) {
            $completed++;
        }
        if (!empty($this->references) && count($this->references) > 0) {
            $completed++;
        }
        if (!empty($this->job_preferences) || !empty($this->social_links)) {
            $completed++;
        }
        if (!empty($this->cv_path) || !empty($this->ktp_path) || !empty($this->ijazah_path)) {
            $completed++;
        }

        return (int) round(($completed / $totalSections) * 100);
    }
}
