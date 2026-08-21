<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function jobTest()
    {
        return $this->belongsTo(JobTest::class, 'job_test_id');
    }
}
