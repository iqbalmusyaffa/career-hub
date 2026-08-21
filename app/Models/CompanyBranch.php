<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_profile_id',
        'branch_name',
        'city',
        'address',
        'phone',
        'email',
        'is_headquarter',
    ];

    protected $casts = [
        'is_headquarter' => 'boolean',
    ];

    public function companyProfile()
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_branch_id');
    }
}
