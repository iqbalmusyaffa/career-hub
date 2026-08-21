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
        return $this->hasMany(CompanyTeamMember::class, 'owner_id', 'user_id');
    }
}
