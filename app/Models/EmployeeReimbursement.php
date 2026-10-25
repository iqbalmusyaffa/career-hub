<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeReimbursement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'integer',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'is_included_in_payroll' => 'boolean',
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

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'transport' => 'Transportasi & Bensin',
            'medical' => 'Kesehatan / Medis',
            'communication' => 'Pulsa & Internet WFH',
            'meal_overtime' => 'Konsumsi Lembur / Dinas',
            'operational' => 'Operasional Kantor / Alat',
            default => 'Lain-lain',
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'transport' => 'fa-car-side text-blue-500',
            'medical' => 'fa-heart-pulse text-rose-500',
            'communication' => 'fa-wifi text-indigo-500',
            'meal_overtime' => 'fa-utensils text-amber-500',
            'operational' => 'fa-briefcase text-emerald-500',
            default => 'fa-receipt text-slate-500',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'approved' => 'Disetujui',
            'paid' => 'Telah Dicairkan',
            'rejected' => 'Ditolak',
            default => 'Menunggu Review',
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'paid' => [
                'text' => 'Telah Dicairkan',
                'class' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800',
                'icon' => 'fa-circle-check text-emerald-600',
            ],
            'approved' => [
                'text' => 'Disetujui (Siap Bayar)',
                'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
                'icon' => 'fa-thumbs-up text-blue-600',
            ],
            'rejected' => [
                'text' => 'Ditolak',
                'class' => 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800',
                'icon' => 'fa-circle-xmark text-rose-600',
            ],
            default => [
                'text' => 'Menunggu Review',
                'class' => 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800',
                'icon' => 'fa-clock text-amber-600',
            ],
        };
    }
}
