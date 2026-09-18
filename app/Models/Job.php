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
        'batch',
        'duration',
        'start_date',
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
        'start_date' => 'date',
        'quota' => 'integer',
        'status' => JobStatus::class,
    ];

    public function getRouteKey()
    {
        return \App\Helpers\IdHasher::encode($this->getKey());
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $realId = \App\Helpers\IdHasher::decode($value) ?? $value;
        return $this->where($field ?? $this->getKeyName(), $realId)->first();
    }

    public function getHashIdAttribute()
    {
        return \App\Helpers\IdHasher::encode($this->id);
    }

    public function branch()
    {
        return $this->belongsTo(CompanyBranch::class, 'company_branch_id');
    }

    public function companyProfile()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_name', 'company_name');
    }

    public function company()
    {
        return $this->companyProfile();
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

    public function curriculum()
    {
        return $this->hasOne(InternshipCurriculum::class, 'job_id');
    }

    public function curriculums()
    {
        return $this->hasMany(InternshipCurriculum::class, 'job_id');
    }

    public function isExpired(): bool
    {
        return $this->deadline && $this->deadline->isPast();
    }

    public function isInternship(): bool
    {
        $workType = strtolower($this->work_type ?? '');
        $title = strtolower($this->title ?? '');
        $exp = strtolower($this->experience_level ?? '');
        
        return str_contains($workType, 'intern') 
            || str_contains($workType, 'magang')
            || str_contains($title, 'magang')
            || str_contains($title, 'intern')
            || str_contains($exp, 'magang')
            || str_contains($exp, 'intern')
            || !empty($this->batch);
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

    public function getUmkCheckAttribute(): array
    {
        $umk = \App\Models\UmkReference::findByLocation($this->location);
        if (!$umk) {
            return [
                'has_umk' => false,
                'is_below' => false,
                'message' => 'Data UMK belum tersedia untuk lokasi ini.'
            ];
        }

        $salaryLower = strtolower($this->salary ?? '');
        $umkAmount = (float)$umk->umk_amount;

        // Jika gaji eksplisit tertulis "sesuai umk" atau "standar umk"
        if (str_contains($salaryLower, 'sesuai umk') || str_contains($salaryLower, 'standar umk') || str_contains($salaryLower, 'mengikuti umk')) {
            return [
                'has_umk' => true,
                'city_district' => $umk->city_district,
                'umk_amount' => $umkAmount,
                'formatted_umk' => $umk->formatted_umk,
                'offered_salary' => $umkAmount,
                'is_below' => false,
                'difference' => 0,
                'formatted_difference' => 'Rp 0',
            ];
        }

        // Hapus angka tahun seperti "2026", "2025" agar tidak salah terbaca sebagai nominal gaji
        $cleanSalary = preg_replace('/202[0-9]/', '', $salaryLower);

        preg_match_all('/\d[\d\.\,]*/', $cleanSalary, $matches);
        $numSalary = 0;
        if (!empty($matches[0])) {
            $rawNum = str_replace(['.', ','], '', $matches[0][0]);
            $numSalary = (float)$rawNum;
            if ($numSalary > 0 && $numSalary < 100) {
                $numSalary = $numSalary * 1000000;
            }
        }

        // Jika hanya teks negosiasi / kompetitif tanpa angka nominal
        if ($numSalary <= 0) {
            return [
                'has_umk' => true,
                'city_district' => $umk->city_district,
                'umk_amount' => $umkAmount,
                'formatted_umk' => $umk->formatted_umk,
                'offered_salary' => 0,
                'is_below' => false,
                'difference' => 0,
                'formatted_difference' => 'Rp 0',
            ];
        }

        $isBelow = ($numSalary > 0) && ($numSalary < $umkAmount);
        $diff = $numSalary - $umkAmount;

        return [
            'has_umk' => true,
            'city_district' => $umk->city_district,
            'umk_amount' => $umkAmount,
            'formatted_umk' => $umk->formatted_umk,
            'offered_salary' => $numSalary,
            'is_below' => $isBelow,
            'difference' => $diff,
            'formatted_difference' => 'Rp ' . number_format(abs($diff), 0, ',', '.'),
        ];
    }

    public function getFormattedAgeAttribute(): string
    {
        if (empty($this->age_range)) {
            return 'Bebas / Semua Usia';
        }

        $val = trim($this->age_range);

        // Jika sudah ada kata 'tahun' atau 'thn'
        if (preg_match('/tahun|thn/i', $val)) {
            return $val;
        }

        // Jika berupa rentang angka murni seperti "21 - 35" atau "21-35"
        if (preg_match('/^(\d+)\s*[-–—]\s*(\d+)$/', $val, $matches)) {
            return "{$matches[1]} - {$matches[2]} Tahun";
        }

        // Jika berupa angka tunggal seperti "30"
        if (is_numeric($val)) {
            return "Maksimal {$val} Tahun";
        }

        return "{$val} Tahun";
    }
}
