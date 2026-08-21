<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ApplicationStatus;

use App\Traits\HasEncryptedId;

class Application extends Model
{
    use HasFactory, HasEncryptedId;

    protected $fillable = [
        'user_id',
        'job_id',
        'status',
    ];

    protected $casts = [
        'status' => ApplicationStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function interview()
    {
        return $this->hasOne(Interview::class);
    }

    public function offerLetter()
    {
        return $this->hasOne(OfferLetter::class);
    }

    public function messages()
    {
        return $this->hasMany(ApplicationMessage::class);
    }

    public function testResult()
    {
        return $this->hasOne(CandidateTestResult::class, 'user_id', 'user_id')
            ->where('job_id', $this->job_id);
    }

    public function evaluations()
    {
        return $this->hasMany(CandidateEvaluation::class);
    }

    public function internalNotes()
    {
        return $this->hasMany(HrInternalNote::class);
    }
}
