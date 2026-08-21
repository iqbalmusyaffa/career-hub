<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\JobStatus;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_postings';

    protected $fillable = [
        'title',
        'company_name',
        'division',
        'location',
        'google_maps_link',
        'work_type',
        'salary',
        'quota',
        'description',
        'requirements',
        'benefits',
        'deadline',
        'status',
        'experience_level',
        'education_level',
        'major_requirement',
        'skills_required',
        'gender_requirement',
        'age_range',
        'company_branch_id',
    ];

    protected $casts = [
        'deadline' => 'date',
        'quota' => 'integer',
        'status' => JobStatus::class,
    ];

    public function branch()
    {
        return $this->belongsTo(CompanyBranch::class, 'company_branch_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function test()
    {
        return $this->hasOne(JobTest::class, 'job_id');
    }

    public function testResults()
    {
        return $this->hasMany(CandidateTestResult::class, 'job_id');
    }

    public function isExpired(): bool
    {
        return $this->deadline && $this->deadline->isPast();
    }

    public function isQuotaFull(): bool
    {
        if (is_null($this->quota) || $this->quota <= 0) {
            return false;
        }
        return $this->applications()->count() >= $this->quota;
    }

    public function remainingQuota(): ?int
    {
        if (is_null($this->quota)) {
            return null;
        }
        return max(0, $this->quota - $this->applications()->count());
    }

    public function scopeActiveNotExpired($query)
    {
        return $query->where('status', 'active')->where(function($q) {
            $q->whereNull('deadline')->orWhere('deadline', '>=', now()->startOfDay());
        });
    }

    public function calculateMatchScore(?CandidateProfile $profile): int
    {
        if (!$profile) {
            return 0;
        }

        // Get candidate skills
        $candidateSkills = [];
        if (is_array($profile->skills)) {
            foreach ($profile->skills as $s) {
                if (is_array($s) && isset($s['name'])) {
                    $candidateSkills[] = strtolower(trim($s['name']));
                } elseif (is_string($s)) {
                    $candidateSkills[] = strtolower(trim($s));
                }
            }
        } elseif (is_string($profile->skills)) {
            $candidateSkills = array_map(fn($item) => strtolower(trim($item)), explode(',', $profile->skills));
        }

        if (empty($candidateSkills)) {
            return 20; // Base score if profile exists
        }

        $jobText = strtolower($this->title . ' ' . $this->requirements . ' ' . $this->description . ' ' . $this->division);

        $matchedCount = 0;
        foreach ($candidateSkills as $skill) {
            if (empty($skill)) continue;
            if (str_contains($jobText, $skill)) {
                $matchedCount++;
            }
        }

        $totalCandidateSkills = max(count($candidateSkills), 1);
        $percentage = round(($matchedCount / $totalCandidateSkills) * 100);

        // Boost score slightly for base relevance up to 100%
        $finalScore = min(100, max(25, $percentage + 15));

        return (int) $finalScore;
    }
}
