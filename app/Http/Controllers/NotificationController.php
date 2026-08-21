<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get JSON list of notifications & unread count.
     */
    public function index()
    {
        $userId = Auth::id();

        $notifications = UserNotification::where('user_id', $userId)
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = UserNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'link' => $notif->link ?? '#',
                    'type' => $notif->type,
                    'is_read' => (bool) $notif->is_read,
                    'created_at_human' => $notif->created_at->diffForHumans(),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark single notification as read & redirect.
     */
    public function markAsRead($id)
    {
        $notification = UserNotification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update(['is_read' => true]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect($notification->link ?? route('dashboard'));
    }

    /**
     * Mark all notifications for user as read.
     */
    public function markAllAsRead()
    {
        UserNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
