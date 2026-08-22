<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEncryptedId;

class InternshipTranscript extends Model
{
    use HasFactory, HasEncryptedId;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'issued_at' => 'date',
        'score_discipline' => 'float',
        'score_technical' => 'float',
        'score_communication' => 'float',
        'score_problem_solving' => 'float',
        'score_ethics' => 'float',
        'final_score' => 'float',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
