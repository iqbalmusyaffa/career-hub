<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEncryptedId;

class InternshipCertificate extends Model
{
    use HasFactory, HasEncryptedId;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'issued_at' => 'date',
        'revoked_at' => 'datetime',
        'is_revoked' => 'boolean',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function revoker()
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }
}
