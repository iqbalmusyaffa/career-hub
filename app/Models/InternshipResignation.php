<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipResignation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'effective_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getCategoryLabelAttribute()
    {
        return match($this->reason_category) {
            'academic' => 'Akademik / Tugas Kampus',
            'health' => 'Kondisi Kesehatan',
            'relocation' => 'Pindah Domisili',
            'personal' => 'Alasan Pribadi / Keluarga',
            default => 'Lainnya',
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'approved' => ['label' => 'Disetujui', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/80 dark:text-emerald-300 dark:border-emerald-800', 'icon' => 'fa-circle-check'],
            'rejected' => ['label' => 'Ditolak', 'class' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/80 dark:text-rose-300 dark:border-rose-800', 'icon' => 'fa-circle-xmark'],
            'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700', 'icon' => 'fa-ban'],
            default => ['label' => 'Menunggu Verifikasi HR', 'class' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/80 dark:text-amber-300 dark:border-amber-800', 'icon' => 'fa-clock'],
        };
    }
}
