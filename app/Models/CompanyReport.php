<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_id',
        'company_name',
        'report_category',
        'reason_description',
        'evidence_url',
        'status',
        'admin_notes',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->report_category) {
            'deposit_fee' => '💸 Meminta Uang Jaminan / Biaya Rekrutmen (Scam)',
            'diploma_withholding' => '📜 Penahanan Ijazah Asli Tanpa Syarat Sah',
            'under_umk' => '⚠️ Gaji Di Bawah UMK & Jam Kerja Unfair',
            'fake_company' => '🏢 Perusahaan Fiktif / Alamat Palsu',
            'harassment' => '🚨 Perlakuan Diskriminatif / Pelecehan',
            default => '🚩 Indikasi Red Flag Umum',
        };
    }
}
