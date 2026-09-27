<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'deadline_at' => 'datetime',
    ];

    /**
     * Check if test is currently open based on starts_at and deadline_at.
     */
    public function isOpen(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->deadline_at && $now->gt($this->deadline_at)) {
            return false;
        }

        return true;
    }

    public function isUpcoming(): bool
    {
        return $this->starts_at && now()->lt($this->starts_at);
    }

    public function isExpired(): bool
    {
        return $this->deadline_at && now()->gt($this->deadline_at);
    }

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
