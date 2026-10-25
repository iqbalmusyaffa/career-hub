<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $table = 'employee_attendances';

    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
        'check_in_latitude' => 'float',
        'check_in_longitude' => 'float',
        'check_out_latitude' => 'float',
        'check_out_longitude' => 'float',
        'work_duration_minutes' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function companyProfile()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function branch()
    {
        return $this->belongsTo(CompanyBranch::class, 'company_branch_id');
    }

    public function getCheckInSelfieUrlAttribute(): ?string
    {
        if ($this->check_in_selfie_path) {
            return asset('storage/' . $this->check_in_selfie_path);
        }
        return null;
    }

    public function getCheckOutSelfieUrlAttribute(): ?string
    {
        if ($this->check_out_selfie_path) {
            return asset('storage/' . $this->check_out_selfie_path);
        }
        return null;
    }

    public function getIsCheckedInAttribute(): bool
    {
        return !empty($this->check_in_time);
    }

    public function getIsCheckedOutAttribute(): bool
    {
        return !empty($this->check_out_time);
    }

    public function getFormattedCheckInTimeAttribute(): string
    {
        return $this->check_in_time ? substr($this->check_in_time, 0, 5) . ' WIB' : '-';
    }

    public function getFormattedCheckOutTimeAttribute(): string
    {
        return $this->check_out_time ? substr($this->check_out_time, 0, 5) . ' WIB' : '-';
    }

    public function getFormattedWorkDurationAttribute(): string
    {
        if (!$this->work_duration_minutes || $this->work_duration_minutes <= 0) {
            return '-';
        }
        $hours = floor($this->work_duration_minutes / 60);
        $minutes = $this->work_duration_minutes % 60;
        if ($hours > 0) {
            return "{$hours} jam {$minutes} mnt";
        }
        return "{$minutes} menit";
    }

    public function getCheckInStatusBadgeAttribute(): array
    {
        if ($this->check_in_status === 'late') {
            return [
                'label' => 'Terlambat',
                'class' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20',
                'icon' => 'fa-clock-rotate-left',
            ];
        }

        return [
            'label' => 'Tepat Waktu',
            'class' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20',
            'icon' => 'fa-circle-check',
        ];
    }

    public function getWorkTypeBadgeAttribute(): array
    {
        return match ($this->work_type) {
            'wfh' => [
                'label' => 'Work From Home (WFH)',
                'short' => 'WFH',
                'class' => 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20',
                'icon' => 'fa-house-laptop',
            ],
            'dinas_luar' => [
                'label' => 'Dinas Luar Kantor',
                'short' => 'Dinas Luar',
                'class' => 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20',
                'icon' => 'fa-briefcase',
            ],
            default => [
                'label' => 'Work From Office (WFO)',
                'short' => 'WFO',
                'class' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20',
                'icon' => 'fa-building',
            ],
        };
    }
}
