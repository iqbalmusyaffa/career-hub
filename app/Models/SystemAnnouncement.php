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
        $query = static::with('creator')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });

        if (!$user) {
            return $query->whereIn('target_role', ['all', 'candidate'])->latest()->get();
        }

        if ($user->hasRole('Super Admin')) {
            return $query->latest()->get();
        }

        $roles = ['all'];

        // Cek apakah user kandidat atau peserta magang aktif
        if ($user->hasRole('Candidate') || $user->hasRole('Intern')) {
            $hasPeriod = (bool) $user->internshipPeriod;
            $hasActiveInternApp = \App\Models\Application::where('user_id', $user->id)
                ->whereIn('status', ['accepted', 'hired'])
                ->whereHas('job', function($q) {
                    $q->whereIn('work_type', ['internship', 'magang', 'Internship', 'Magang']);
                })
                ->exists();

            $isIntern = $hasPeriod || $hasActiveInternApp;

            if ($isIntern) {
                // Peserta magang menerima pengumuman global, pengumuman khusus magang, dan pengumuman umum
                $roles[] = 'intern';
                $roles[] = 'candidate';
            } else {
                // Pencari kerja reguler hanya menerima pengumuman global dan pengumuman pelamar kerja
                $roles[] = 'candidate';
            }
        }

        if ($user->hasRole('Company Owner')) {
            $roles[] = 'company_owner';
        }
        if ($user->hasRole('HR') || $user->hasRole('HR Manager') || $user->hasRole('HR Staff')) {
            $roles[] = 'hr';
        }
        if ($user->hasRole('Mentor')) {
            $roles[] = 'mentor';
        }

        return $query->whereIn('target_role', $roles)->latest()->get();
    }
}
