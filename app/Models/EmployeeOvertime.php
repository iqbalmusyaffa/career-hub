<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOvertime extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
        'duration_hours' => 'decimal:2',
        'hourly_rate' => 'integer',
        'total_compensation' => 'integer',
        'approved_at' => 'datetime',
        'is_payroll_calculated' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            default => 'Menunggu Review',
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'approved' => [
                'text' => 'Disetujui',
                'class' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800',
                'icon' => 'fa-circle-check text-emerald-600',
            ],
            'rejected' => [
                'text' => 'Ditolak',
                'class' => 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800',
                'icon' => 'fa-circle-xmark text-rose-600',
            ],
            'cancelled' => [
                'text' => 'Dibatalkan',
                'class' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700',
                'icon' => 'fa-ban text-slate-500',
            ],
            default => [
                'text' => 'Menunggu Review',
                'class' => 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800',
                'icon' => 'fa-clock text-amber-600',
            ],
        };
    }
}
