<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipUnlockRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'target_date' => 'date',
        'unlocked_until' => 'datetime',
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function intern()
    {
        return $this->belongsTo(User::class, 'intern_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Check if a specific date is currently actively unlocked for a user by Super Admin approval.
     */
    public static function isCurrentlyUnlockedForUser($userId, $date): bool
    {
        return static::where('intern_id', $userId)
            ->where('target_date', $date)
            ->where('status', 'approved')
            ->where('unlocked_until', '>=', now())
            ->exists();
    }
}
