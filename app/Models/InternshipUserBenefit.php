<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipUserBenefit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'custom_nominal' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function benefit()
    {
        return $this->belongsTo(InternshipBenefit::class, 'internship_benefit_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function getEffectiveNominalAttribute(): float
    {
        if (!is_null($this->custom_nominal)) {
            return (float) $this->custom_nominal;
        }

        return (float) ($this->benefit?->standard_nominal ?? 0);
    }

    public function getFormattedNominalAttribute(): string
    {
        $nom = $this->effective_nominal;
        if ($nom > 0) {
            return 'Rp ' . number_format($nom, 0, ',', '.');
        }

        if ($this->benefit?->coverage_type === 'company_paid') {
            return '100% Ditanggung Kantor (Rp 0 Beban Peserta)';
        }

        return 'Fasilitas Kantor Disediakan';
    }
}
