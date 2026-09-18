<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'link',
        'type',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Send notification to user helper.
     */
    public static function send($userId, string $title, string $message, ?string $link = null, string $type = 'info')
    {
        try {
            // Normalize internal links to relative path so it works across any localhost port / dev domain
            if ($link && filter_var($link, FILTER_VALIDATE_URL)) {
                $parsed = parse_url($link);
                $path = $parsed['path'] ?? '/';
                $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
                $link = $path . $query;
            }

            return static::create([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'type' => $type,
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
