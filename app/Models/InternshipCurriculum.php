<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipCurriculum extends Model
{
    use HasFactory;

    protected $table = 'internship_curriculums';

    protected $fillable = [
        'company_id',
        'job_id',
        'batch',
        'title',
        'description',
        'created_by',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materials()
    {
        return $this->hasMany(CurriculumMaterial::class, 'internship_curriculum_id')->orderBy('sequence', 'asc');
    }
}
