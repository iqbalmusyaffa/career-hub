<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEncryptedId;

class CompanyRoleRequest extends Model
{
    use HasFactory, HasEncryptedId;

    protected $fillable = [
        'user_id',
        'company_name',
        'industry',
        'company_size',
        'phone',
        'address',
        'legal_doc_path',
        'notes',
        'status',
        'admin_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'rejected' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-amber-100 text-amber-800 border-amber-200',
        };
    }
}
