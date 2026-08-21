<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'evaluator_user_id',
        'rating',
        'technical_score',
        'attitude_score',
        'communication_score',
        'comments',
        'recommendation',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_user_id');
    }
}
