<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewScorecard extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'interviewer_id',
        'technical_score',
        'communication_score',
        'problem_solving_score',
        'culture_score',
        'average_score',
        'recommendation',
        'notes',
    ];

    protected $casts = [
        'technical_score' => 'integer',
        'communication_score' => 'integer',
        'problem_solving_score' => 'integer',
        'culture_score' => 'integer',
        'average_score' => 'float',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
}
