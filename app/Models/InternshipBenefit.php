<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipBenefit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
        'standard_nominal' => 'decimal:2',
    ];

    public function companyProfile()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function userBenefits()
    {
        return $this->hasMany(InternshipUserBenefit::class, 'internship_benefit_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'internship_user_benefits', 'internship_benefit_id', 'user_id')
                    ->withPivot('policy_number', 'status', 'custom_nominal', 'notes', 'start_date', 'end_date')
                    ->withTimestamps();
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeOptional($query)
    {
        return $query->where('is_mandatory', false);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'bpjs_tk' => 'BPJS Ketenagakerjaan (JKK & JKM)',
            'bpjs_kes' => 'BPJS Kesehatan (JKN-KIS)',
            'transport' => 'Tunjangan Transportasi & Mobilitas',
            'meal' => 'Tunjangan Makan / Konsumsi Kantor',
            'equipment' => 'Perangkat Kerja / Fasilitas Inventaris',
            'wellness' => 'Kebugaran & Fasilitas Kantor',
            'mentoring' => 'Mentoring & Pelatihan Profesional',
            'allowance' => 'Tunjangan Tunai Tambahan',
            default => 'Fasilitas Pendukung Magang',
        };
    }

    public function getCoverageTypeLabelAttribute(): string
    {
        return match ($this->coverage_type) {
            'company_paid' => 'Ditanggung 100% Perusahaan (Bebas Biaya)',
            'cash_allowance' => 'Tunjangan Tunai (Cash Allowance)',
            'facility_access' => 'Akses Fasilitas Fisik Kantor',
            'non_cash' => 'Fasilitas Non-Tunai / In-Kind',
            default => 'Fasilitas Disediakan Perusahaan',
        };
    }

    public function getFormattedNominalAttribute(): string
    {
        if ($this->standard_nominal > 0) {
            return 'Rp ' . number_format($this->standard_nominal, 0, ',', '.');
        }

        return $this->coverage_type === 'company_paid' ? '100% Ditanggung Kantor' : 'Fasilitas Kantor';
    }
}
