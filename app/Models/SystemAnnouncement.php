<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'target_role',
        'type',
        'is_active',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function getActiveAnnouncementsForUser($user = null)
    {
        $query = static::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });

        if (!$user) {
            return $query->whereIn('target_role', ['all', 'candidate'])->latest()->get();
        }

        $roles = ['all'];
        if ($user->hasRole('Super Admin')) {
            $roles[] = 'super_admin';
        }
        if ($user->hasRole('Company Owner')) {
            $roles[] = 'company_owner';
        }
        if ($user->hasRole('HR Manager') || $user->hasRole('HR Staff')) {
            $roles[] = 'hr';
        }
        if ($user->hasRole('Candidate')) {
            $roles[] = 'candidate';
        }

        return $query->whereIn('target_role', $roles)->latest()->get();
    }
}
