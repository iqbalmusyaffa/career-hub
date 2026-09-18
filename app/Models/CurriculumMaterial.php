<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumMaterial extends Model
{
    use HasFactory;

    protected $table = 'curriculum_materials';

    protected $fillable = [
        'internship_curriculum_id',
        'sequence',
        'title',
        'description',
        'competencies',
        'learning_links',
    ];

    protected $casts = [
        'sequence' => 'integer',
        'competencies' => 'array',
        'learning_links' => 'array',
    ];

    public function curriculum()
    {
        return $this->belongsTo(InternshipCurriculum::class, 'internship_curriculum_id');
    }

    public function internProgresses()
    {
        return $this->hasMany(InternCurriculumProgress::class, 'curriculum_material_id');
    }
}
