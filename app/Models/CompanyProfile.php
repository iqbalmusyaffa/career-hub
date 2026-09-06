<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_suspended' => 'boolean',
        'benefits' => 'array',
        'allow_saturday_work' => 'boolean',
        'allow_sunday_work' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branches()
    {
        return $this->hasMany(CompanyBranch::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(CompanyTeamMember::class, 'company_profile_id', 'id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_name', 'company_name');
    }
}
