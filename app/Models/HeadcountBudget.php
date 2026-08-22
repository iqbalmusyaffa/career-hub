<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadcountBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_user_id',
        'division',
        'fiscal_year',
        'target_headcount',
        'allocated_budget',
        'notes',
    ];

    protected $casts = [
        'fiscal_year' => 'integer',
        'target_headcount' => 'integer',
        'allocated_budget' => 'float',
    ];

    public function companyUser()
    {
        return $this->belongsTo(User::class, 'company_user_id');
    }
}
