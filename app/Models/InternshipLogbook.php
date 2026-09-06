<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipLogbook extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
        'work_hours' => 'integer',
    ];

    public function intern()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function getAttendanceTypeLabelAttribute()
    {
        return match($this->attendance_type) {
            'Hadir', 'present', 'wfo', 'wfh' => 'Hadir',
            'Tidak Hadir Dengan Keterangan', 'sick', 'permission' => 'Tidak Hadir Dengan Keterangan',
            'Tidak Hadir Tanpa Keterangan', 'absent' => 'Tidak Hadir Tanpa Keterangan',
            default => $this->attendance_type ?? 'Hadir',
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'approved' => ['label' => 'Disetujui', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80 dark:bg-emerald-950/80 dark:text-emerald-300 dark:border-emerald-800', 'icon' => 'fa-circle-check', 'symbol' => '✔️'],
            'rejected' => ['label' => 'Kehadiran Ditolak', 'class' => 'bg-rose-50 text-rose-700 border-rose-200/80 dark:bg-rose-950/80 dark:text-rose-300 dark:border-rose-800', 'icon' => 'fa-circle-xmark', 'symbol' => '❌'],
            'action_required' => ['label' => 'Perlu Tindakan Anda', 'class' => 'bg-amber-50 text-amber-700 border-amber-200/80 dark:bg-amber-950/80 dark:text-amber-300 dark:border-amber-800', 'icon' => 'fa-triangle-exclamation', 'symbol' => '🔺'],
            'absent' => ['label' => 'Tidak Hadir Tanpa Keterangan', 'class' => 'bg-rose-50 text-rose-700 border-rose-200/80 dark:bg-rose-950/80 dark:text-rose-300 dark:border-rose-800', 'icon' => 'fa-user-xmark', 'symbol' => '🔴'],
            default => ['label' => 'Menunggu Tindakan Mentor', 'class' => 'bg-blue-50 text-blue-700 border-blue-200/80 dark:bg-blue-950/80 dark:text-blue-300 dark:border-blue-800', 'icon' => 'fa-clock', 'symbol' => '🔷'],
        };
    }
}
