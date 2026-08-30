<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyHolidayOverride extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_working_day' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function holiday()
    {
        return $this->belongsTo(CompanyHoliday::class, 'company_holiday_id');
    }
}
