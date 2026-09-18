<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get JSON list of notifications & unread count (for dropdowns).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = $user?->id;

        // Automatically check and generate attendance/logbook reminder notifications for current user
        if ($userId) {
            \App\Services\AttendanceReminderService::checkAndGenerateForUser($userId);
        }

        $isSuperAdmin = $user && $user->hasRole('Super Admin');

        if ($isSuperAdmin) {
            // Super Admin sees global platform notifications in bell dropdown (newest at top)
            $notifications = UserNotification::with('user.roles')
                ->orderByDesc('id')
                ->take(15)
                ->get();

            $unreadCount = UserNotification::where('is_read', false)->count();
        } else {
            // Regular roles (Candidate, Mentor, HR) strictly see their own notifications (newest at top)
            $notifications = UserNotification::where('user_id', $userId)
                ->orderByDesc('id')
                ->take(15)
                ->get();

            $unreadCount = UserNotification::where('user_id', $userId)
                ->where('is_read', false)
                ->count();
        }

        return response()->json([
            'is_super_admin' => $isSuperAdmin,
            'notifications' => $notifications->map(function ($notif) use ($isSuperAdmin) {
                $recipientRole = $notif->user?->roles?->pluck('name')->first() ?? 'User';
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'link' => $notif->link ?? '#',
                    'type' => $notif->type,
                    'is_read' => (bool) $notif->is_read,
                    'recipient_name' => $isSuperAdmin ? ($notif->user?->name ?? 'Pengguna') : null,
                    'recipient_role' => $isSuperAdmin ? $recipientRole : null,
                    'created_at_formatted' => $notif->created_at->format('d M Y, H:i'),
                    'created_at_human' => $notif->created_at->diffForHumans(),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * View full notifications center page with filters and pagination.
     */
    public function all(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $isSuperAdmin = $user->hasRole('Super Admin');

        if ($userId) {
            \App\Services\AttendanceReminderService::checkAndGenerateForUser($userId);
        }

        // Scope: For Super Admin default to 'global' (all platform notifications), regular users 'personal'
        $scope = $isSuperAdmin ? $request->query('scope', 'global') : 'personal';

        $query = UserNotification::with('user.roles');

        if ($scope === 'personal') {
            $query->where('user_id', $userId);
        }

        // Role Filter (Super Admin Global view)
        $roleFilter = $request->query('role', 'all');
        if ($isSuperAdmin && $scope === 'global' && $roleFilter !== 'all') {
            $query->whereHas('user.roles', function ($q) use ($roleFilter) {
                $q->where('name', $roleFilter);
            });
        }

        // Status Filter
        $status = $request->query('status', 'all');
        if ($status === 'unread') {
            $query->where('is_read', false);
        } elseif ($status === 'read') {
            $query->where('is_read', true);
        }

        // Type Filter
        $type = $request->query('type', 'all');
        if (in_array($type, ['info', 'warning', 'success'])) {
            $query->where('type', $type);
        }

        // Search Filter
        $search = $request->query('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Always show newest notification at the top
        $notifications = $query->orderByDesc('id')->paginate(15)->withQueryString();

        // Calculate counts
        $countQuery = UserNotification::query();
        if ($scope === 'personal') {
            $countQuery->where('user_id', $userId);
        }
        $totalCount = (clone $countQuery)->count();
        $unreadCount = (clone $countQuery)->where('is_read', false)->count();

        return view('notifications.index', compact(
            'notifications', 
            'unreadCount', 
            'totalCount', 
            'status', 
            'type', 
            'search',
            'isSuperAdmin',
            'scope',
            'roleFilter'
        ));
    }

    /**
     * Mark single notification as read & redirect or return JSON.
     */
    public function markAsRead(Request $request, $id)
    {
        $user = Auth::user();
        $query = UserNotification::query();
        
        if (!$user->hasRole('Super Admin')) {
            $query->where('user_id', $user->id);
        }

        $notification = $query->findOrFail($id);
        $notification->update(['is_read' => true]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        if ($request->has('redirect_to_link') && $notification->link) {
            return redirect($notification->link);
        }

        return redirect()->back()->with('success', 'Notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Mark all notifications for user as read.
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        $query = UserNotification::where('is_read', false);

        if (!$user->hasRole('Super Admin')) {
            $query->where('user_id', $user->id);
        }

        $query->update(['is_read' => true]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai telah dibaca.');
    }

    /**
     * Delete a single notification.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $query = UserNotification::query();

        if (!$user->hasRole('Super Admin')) {
            $query->where('user_id', $user->id);
        }

        $notification = $query->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Clear all read notifications.
     */
    public function clearAllRead()
    {
        $user = Auth::user();
        $query = UserNotification::where('is_read', true);

        if (!$user->hasRole('Super Admin')) {
            $query->where('user_id', $user->id);
        }

        $deleted = $query->delete();

        return redirect()->back()->with('success', "Berhasil membersihkan {$deleted} notifikasi yang telah dibaca.");
    }
}
