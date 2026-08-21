<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateTestResult extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'answers' => 'array',
        'passed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function jobTest()
    {
        return $this->belongsTo(JobTest::class, 'job_test_id');
    }
}
