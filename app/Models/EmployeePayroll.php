<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayroll extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'payment_date' => 'date',
        'base_salary' => 'integer',
        'allowance_fixed' => 'integer',
        'allowance_transport' => 'integer',
        'overtime_pay' => 'integer',
        'reimbursement_pay' => 'integer',
        'bonus_pay' => 'integer',
        'deduction_late' => 'integer',
        'deduction_unexcused' => 'integer',
        'deduction_bpjs_tk' => 'integer',
        'deduction_bpjs_kes' => 'integer',
        'deduction_pph21' => 'integer',
        'deduction_other' => 'integer',
        'total_earnings' => 'integer',
        'total_deductions' => 'integer',
        'net_salary' => 'integer',
        'present_days' => 'integer',
        'late_days' => 'integer',
        'absent_days' => 'integer',
        'overtime_hours' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'paid' => 'Telah Ditransfer',
            'published' => 'Siap / Terbit',
            default => 'Draf Payroll',
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'paid' => [
                'text' => 'Telah Ditransfer',
                'class' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800',
                'icon' => 'fa-circle-check text-emerald-600',
            ],
            'published' => [
                'text' => 'Siap / Terbit',
                'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
                'icon' => 'fa-paper-plane text-blue-600',
            ],
            default => [
                'text' => 'Draf Payroll',
                'class' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700',
                'icon' => 'fa-file-lines text-slate-500',
            ],
        };
    }

    public function getEmploymentTypeLabelAttribute(): string
    {
        return match($this->employment_type) {
            'permanent' => 'Karyawan Tetap (PKWTT)',
            'contract' => 'Karyawan Kontrak (PKWT)',
            'probation' => 'Masa Percobaan (Probation)',
            default => 'Karyawan',
        };
    }
}
