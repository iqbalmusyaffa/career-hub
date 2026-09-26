<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'total_days' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function getLeaveTypeLabelAttribute(): string
    {
        return match($this->leave_type) {
            'annual_leave' => 'Cuti Tahunan (Annual Leave)',
            'sick_leave' => 'Izin Sakit (Sick Leave)',
            'academic_leave' => 'Izin Akademik / Kampus / Wisuda',
            'family_event' => 'Izin Acara Keluarga / Keperluan Mendesak',
            'special_leave' => 'Cuti Khusus / Izin Berbayar (Menikah/Duka Cita)',
            'maternity_leave' => 'Cuti Melahirkan / Keguguran',
            'internship_permission' => 'Izin Magang / Dispensasi Khusus',
            default => ucfirst(str_replace('_', ' ', $this->leave_type)),
        };
    }

    public function getLeaveTypeIconAttribute(): string
    {
        return match($this->leave_type) {
            'annual_leave' => 'fa-calendar-check text-indigo-500',
            'sick_leave' => 'fa-notes-medical text-rose-500',
            'academic_leave' => 'fa-graduation-cap text-sky-500',
            'family_event' => 'fa-users text-purple-500',
            'special_leave' => 'fa-heart text-amber-500',
            'maternity_leave' => 'fa-baby text-pink-500',
            'internship_permission' => 'fa-certificate text-emerald-500',
            default => 'fa-calendar-day text-slate-500',
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'approved' => [
                'label' => 'Disetujui',
                'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/80 dark:text-emerald-300 dark:border-emerald-800',
                'icon' => 'fa-circle-check',
            ],
            'rejected' => [
                'label' => 'Ditolak',
                'class' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/80 dark:text-rose-300 dark:border-rose-800',
                'icon' => 'fa-circle-xmark',
            ],
            'cancelled' => [
                'label' => 'Dibatalkan',
                'class' => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
                'icon' => 'fa-ban',
            ],
            default => [
                'label' => 'Menunggu Persetujuan',
                'class' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/80 dark:text-amber-300 dark:border-amber-800',
                'icon' => 'fa-clock',
            ],
        };
    }
}
