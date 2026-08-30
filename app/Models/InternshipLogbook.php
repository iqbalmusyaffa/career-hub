<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipLogbook extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
        'work_hours' => 'integer',
    ];

    public function intern()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'approved' => ['label' => 'Disetujui', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300', 'icon' => 'fa-check-circle', 'symbol' => '✔️'],
            'rejected' => ['label' => 'Kehadiran Ditolak', 'class' => 'bg-red-100 text-red-800 border-red-300', 'icon' => 'fa-times-circle', 'symbol' => '❌'],
            'action_required' => ['label' => 'Perlu Tindakan Anda', 'class' => 'bg-amber-100 text-amber-800 border-amber-300', 'icon' => 'fa-exclamation-triangle', 'symbol' => '🔺'],
            'absent' => ['label' => 'Tidak Hadir', 'class' => 'bg-rose-100 text-rose-800 border-rose-300', 'icon' => 'fa-user-xmark', 'symbol' => '🔴'],
            default => ['label' => 'Menunggu Tindakan Mentor', 'class' => 'bg-blue-100 text-blue-800 border-blue-300', 'icon' => 'fa-clock', 'symbol' => '🔷'],
        };
    }
}
