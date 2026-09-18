<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'theme_preference',
        'is_suspended',
        'status_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_suspended' => 'boolean',
        ];
    }

    public function candidateProfile()
    {
        return $this->hasOne(CandidateProfile::class);
    }

    public function companyProfile()
    {
        return $this->hasOne(CompanyProfile::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function savedJobs()
    {
        return $this->hasMany(SavedJob::class);
    }

    public function bookmarkedJobs()
    {
        return $this->belongsToMany(Job::class, 'saved_jobs', 'user_id', 'job_id')->withTimestamps();
    }

    public function candidateDocuments()
    {
        return $this->hasMany(CandidateDocument::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function logbooks()
    {
        return $this->hasMany(InternshipLogbook::class, 'user_id');
    }

    public function internshipLogbooks()
    {
        return $this->hasMany(InternshipLogbook::class, 'user_id');
    }

    public function internshipPeriods()
    {
        return $this->hasMany(InternshipPeriod::class, 'user_id');
    }

    public function internshipPeriod()
    {
        return $this->hasOne(InternshipPeriod::class, 'user_id')->latestOfMany();
    }

    public function internshipResignations()
    {
        return $this->hasMany(InternshipResignation::class, 'user_id');
    }

    public function employeeTerminations()
    {
        return $this->hasMany(EmployeeTermination::class, 'user_id');
    }

    /**
     * Resolve the active CompanyProfile for this user (whether Company Owner or HR Team Member).
     */
    public function currentCompanyProfile(): ?CompanyProfile
    {
        // 1. Cek apakah user tergabung dalam tim HR perusahaan
        $teamMember = CompanyTeamMember::where('user_id', $this->id)->with('companyProfile')->first();
        if ($teamMember && $teamMember->companyProfile) {
            return $teamMember->companyProfile;
        }

        // 2. Cek apakah user adalah Owner langsung dari profil perusahaan
        if ($this->companyProfile) {
            return $this->companyProfile;
        }

        return CompanyProfile::where('user_id', $this->id)->first();
    }

    /**
     * Check if candidate is ineligible/blocked from applying to internship positions.
     * Returns the reason string if blocked (Active / Resigned / Terminated), or null if eligible (e.g. initial rejected or new candidate).
     */
    public function getInternshipBlockReason(): ?string
    {
        // 1. Cek riwayat pengunduran diri magang (Resigned)
        $hasResigned = \App\Models\InternshipResignation::where('user_id', $this->id)
            ->where('status', 'approved')
            ->exists();
        if ($hasResigned) {
            return 'Anda memiliki riwayat pengunduran diri dari Program Magang sebelumnya, sehingga tidak dapat mendaftar kembali ke program magang.';
        }

        // 2. Cek riwayat pemberhentian magang (Terminated)
        $hasTerminated = \App\Models\EmployeeTermination::where('user_id', $this->id)
            ->orWhereHas('application', function($q) {
                $q->where('user_id', $this->id);
            })
            ->exists();
        if ($hasTerminated) {
            return 'Anda memiliki catatan pemberhentian (terminasi) dari Program Magang sebelumnya, sehingga tidak dapat mendaftar kembali ke program magang.';
        }

        // 3. Cek apakah sedang aktif magang di periode saat ini (Active batch)
        $activePeriod = \App\Models\InternshipPeriod::where('user_id', $this->id)
            ->where('end_date', '>=', now()->toDateString())
            ->latest()
            ->first();
        if ($activePeriod) {
            return 'Anda saat ini sedang aktif dalam Program Magang (' . $activePeriod->period_name . '). Kuota lowongan magang ini diprioritaskan bagi kandidat lain yang belum terpilih.';
        }

        // 4. Cek apakah memiliki lamaran magang yang sudah diterima (Accepted/Hired)
        $hasAcceptedInternship = \App\Models\Application::where('user_id', $this->id)
            ->whereIn('status', ['accepted', 'hired'])
            ->whereHas('job', function($q) {
                $q->where('work_type', 'like', '%Intern%')
                  ->orWhere('work_type', 'like', '%Magang%')
                  ->orWhereNotNull('batch');
            })
            ->exists();
        if ($hasAcceptedInternship) {
            return 'Anda telah diterima dalam Program Magang. Anda tidak dapat melamar lowongan magang lain agar kuota terbagi secara adil.';
        }

        return null;
    }

    /**
     * Check if user is currently active in an ongoing internship batch.
     */
    public function hasActiveInternship(): bool
    {
        return $this->getInternshipBlockReason() !== null;
    }
}
