<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryRequestController extends Controller
{
    public function index()
    {
        $myRequests = JobCategory::where('requested_by', auth()->id())
            ->latest()
            ->paginate(10);

        return view('admin.categories.my_requests', compact('myRequests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subtext' => 'nullable|string|max:255',
            'request_reason' => 'required|string|max:1000',
            'icon' => 'nullable|string|max:100',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'request_reason.required' => 'Alasan atau penjelasan pengajuan kategori wajib diisi.',
        ]);

        // Check if identical category already exists
        $existing = JobCategory::where('name', $validated['name'])->first();
        if ($existing) {
            if ($existing->status === 'active') {
                return redirect()->back()->with('error', "Kategori '{$validated['name']}' sudah tersedia dan aktif di sistem.");
            } elseif ($existing->status === 'pending_approval') {
                return redirect()->back()->with('error', "Kategori '{$validated['name']}' sudah diajukan sebelumnya dan sedang menunggu persetujuan Super Admin.");
            }
        }

        $category = JobCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . rand(100, 999),
            'icon' => !empty($validated['icon']) ? $validated['icon'] : 'fa-solid fa-briefcase',
            'badge_color' => 'blue',
            'subtext' => $validated['subtext'] ?? null,
            'description' => $validated['subtext'] ?? null,
            'request_reason' => $validated['request_reason'],
            'status' => 'pending_approval',
            'is_active' => false,
            'requested_by' => auth()->id(),
        ]);

        // Notify Super Admins
        $superAdmins = User::role('Super Admin')->get();
        foreach ($superAdmins as $admin) {
            UserNotification::create([
                'user_id' => $admin->id,
                'title' => 'Pengajuan Kategori Baru',
                'message' => "HR " . auth()->user()->name . " mengajukan penambahan kategori baru: '{$category->name}'.",
                'type' => 'info',
                'link' => route('admin.categories.index', ['tab' => 'requests']),
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Pengajuan kategori '{$category->name}' berhasil dikirim ke Super Admin untuk ditinjau.",
            ]);
        }

        return redirect()->back()->with('success', "Pengajuan kategori '{$category->name}' berhasil dikirim ke Super Admin. Anda akan menerima notifikasi setelah disetujui.");
    }
}
