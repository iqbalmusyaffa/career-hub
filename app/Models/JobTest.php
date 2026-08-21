<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function questions()
    {
        return $this->hasMany(TestQuestion::class, 'job_test_id');
    }

    public function results()
    {
        return $this->hasMany(CandidateTestResult::class, 'job_test_id');
    }
}
