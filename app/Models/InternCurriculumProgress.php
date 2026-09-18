<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternCurriculumProgress extends Model
{
    use HasFactory;

    protected $table = 'intern_curriculum_progress';

    protected $fillable = [
        'user_id',
        'curriculum_material_id',
        'status',
        'mentor_id',
        'completed_at',
        'mentor_notes',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function intern()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function material()
    {
        return $this->belongsTo(CurriculumMaterial::class, 'curriculum_material_id');
    }
}
