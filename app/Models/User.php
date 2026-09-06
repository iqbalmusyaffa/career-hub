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

    public function internshipPeriods()
    {
        return $this->hasMany(InternshipPeriod::class, 'user_id');
    }

    public function internshipPeriod()
    {
        return $this->hasOne(InternshipPeriod::class, 'user_id')->latestOfMany();
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
}
