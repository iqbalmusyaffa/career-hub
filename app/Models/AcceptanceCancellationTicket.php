<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcceptanceCancellationTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'hr_user_id',
        'reason',
        'status',
        'superadmin_note',
        'handled_by',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function hrUser()
    {
        return $this->belongsTo(User::class, 'hr_user_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
