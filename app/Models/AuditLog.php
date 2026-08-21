<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a new audit log entry.
     */
    public static function record(string $action, string $description, ?User $user = null)
    {
        try {
            $actor = $user ?? Auth::user();
            
            return static::create([
                'user_id' => $actor ? $actor->id : null,
                'action' => $action,
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => substr(request()->userAgent() ?? '', 0, 255),
            ]);
        } catch (\Throwable $e) {
            // Fail safely without breaking main transaction
            return null;
        }
    }
}
