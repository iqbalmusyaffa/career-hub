<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemAnnouncement;
use App\Models\AuditLog;
use App\Models\UserNotification;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemAnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $targetRole = $request->input('target_role');
        $status = $request->input('status');

        $query = SystemAnnouncement::with('creator')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($targetRole) {
            $query->where('target_role', $targetRole);
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', $status === 'active');
        }

        $perPage = (int) $request->input('per_page', 10);
        $announcements = $query->paginate($perPage)->withQueryString();

        // Summary metrics
        $totalAnnouncements = SystemAnnouncement::count();
        $activeAnnouncements = SystemAnnouncement::where('is_active', true)->count();
        $urgentAnnouncements = SystemAnnouncement::where('type', 'danger')->count();
        $allAudienceAnnouncements = SystemAnnouncement::where('target_role', 'all')->count();

        return view('admin.announcements.index', compact(
            'announcements',
            'totalAnnouncements',
            'activeAnnouncements',
            'urgentAnnouncements',
            'allAudienceAnnouncements'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_role' => 'required|string|in:all,company_owner,hr,candidate',
            'type' => 'required|string|in:info,warning,success,danger',
            'expires_at' => 'nullable|date',
        ]);

        $announcement = SystemAnnouncement::create([
            'title' => $request->title,
            'content' => $request->content,
            'target_role' => $request->target_role,
            'type' => $request->type,
            'is_active' => true,
            'expires_at' => $request->expires_at,
            'created_by' => Auth::id(),
        ]);

        AuditLog::record('announcement_created', "Super Admin mempublikasikan pengumuman global: {$request->title}");

        // Broadcast System Notifications
        $usersQuery = User::query();
        if ($request->target_role === 'company_owner') {
            $usersQuery->role('Company Owner');
        } elseif ($request->target_role === 'hr') {
            $usersQuery->role(['HR Manager', 'HR Staff']);
        } elseif ($request->target_role === 'candidate') {
            $usersQuery->role('Candidate');
        }

        $users = $usersQuery->take(100)->get();
        foreach ($users as $u) {
            UserNotification::send(
                $u->id,
                "📢 " . $request->title,
                Str::limit(strip_tags($request->content), 120),
                route('dashboard'),
                $request->type
            );
        }

        return back()->with('success', 'Pengumuman global berhasil dipublikasikan & disiarkan!');
    }

    public function toggleActive(SystemAnnouncement $announcement)
    {
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();

        $statusMsg = $announcement->is_active
            ? "Pengumuman '{$announcement->title}' berhasil DIAKTIFKAN."
            : "Pengumuman '{$announcement->title}' berhasil DINONAKTIFKAN.";

        return back()->with('success', $statusMsg);
    }

    public function destroy(SystemAnnouncement $announcement)
    {
        $title = $announcement->title;
        $announcement->delete();

        AuditLog::record('announcement_deleted', "Super Admin menghapus pengumuman global: {$title}");

        return back()->with('success', "Pengumuman '{$title}' berhasil dihapus.");
    }
}
