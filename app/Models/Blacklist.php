<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'value',
        'reason',
        'blocked_by',
    ];

    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    public static function isBlocked($value, $type = null): bool
    {
        if (empty($value)) {
            return false;
        }

        $query = static::where('value', strtolower(trim($value)));
        if ($type) {
            $query->where('type', $type);
        }

        return $query->exists();
    }
}
