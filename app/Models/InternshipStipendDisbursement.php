<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipStipendDisbursement extends Model
{
    use HasFactory;

    protected $table = 'internship_stipend_disbursements';

    protected $fillable = [
        'user_id',
        'company_id',
        'period_month',
        'period_label',
        'batch_name',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'bank_book_doc_path',
        'is_ktp_matched',
        'present_days',
        'excused_days',
        'unexcused_days',
        'total_working_days',
        'base_nominal',
        'deduction_amount',
        'net_amount',
        'status',
        'proof_path',
        'transferred_at',
        'verified_by',
        'notes',
        'mentor_id',
        'mentor_notes',
        'mentor_submitted_at',
    ];

    protected $casts = [
        'is_ktp_matched' => 'boolean',
        'present_days' => 'integer',
        'excused_days' => 'integer',
        'unexcused_days' => 'integer',
        'total_working_days' => 'integer',
        'base_nominal' => 'decimal:2',
        'deduction_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'transferred_at' => 'datetime',
        'mentor_submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function getFormattedNetAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->net_amount, 0, ',', '.');
    }

    public function getFormattedDeductionAttribute(): string
    {
        return 'Rp ' . number_format($this->deduction_amount, 0, ',', '.');
    }

    public function getFormattedBaseAttribute(): string
    {
        return 'Rp ' . number_format($this->base_nominal, 0, ',', '.');
    }
}
