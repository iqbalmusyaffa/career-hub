<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyLeavePolicy extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'annual_leave_quota' => 'integer',
        'permanent_leave_quota' => 'integer',
        'internship_max_excused_days' => 'integer',
        'allow_half_day' => 'boolean',
        'require_attachment_for_sick' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Get or create default leave policy for a company.
     */
    public static function getForCompany(?int $companyId = null): self
    {
        $policy = self::where('company_id', $companyId)->first();
        if (!$policy) {
            $policy = self::create([
                'company_id' => $companyId,
                'annual_leave_quota' => 12,
                'permanent_leave_quota' => 15,
                'internship_max_excused_days' => 4,
                'allow_half_day' => false,
                'require_attachment_for_sick' => true,
            ]);
        }
        return $policy;
    }
}
