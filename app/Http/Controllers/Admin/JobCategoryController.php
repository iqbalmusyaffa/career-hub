<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobCategoryController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'categories');
        $search = $request->get('search');

        $query = JobCategory::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subtext', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = (clone $query)
            ->whereIn('status', ['active', 'rejected'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $pendingRequests = JobCategory::with('requestedBy')
            ->where('status', 'pending_approval')
            ->latest()
            ->get();

        $stats = [
            'total' => JobCategory::where('status', 'active')->count(),
            'active' => JobCategory::where('status', 'active')->where('is_active', true)->count(),
            'inactive' => JobCategory::where('status', 'active')->where('is_active', false)->count(),
            'pending' => $pendingRequests->count(),
        ];

        return view('admin.categories.index', compact('categories', 'pendingRequests', 'stats', 'tab', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:job_categories,name',
            'icon' => 'nullable|string|max:100',
            'badge_color' => 'nullable|string|max:50',
            'subtext' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['icon'] = !empty($validated['icon']) ? $validated['icon'] : 'fa-solid fa-briefcase';
        $validated['badge_color'] = !empty($validated['badge_color']) ? $validated['badge_color'] : 'blue';
        $validated['subtext'] = $validated['subtext'] ?? null;
        $validated['description'] = $validated['description'] ?? null;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active') ? (bool)$request->is_active : true;
        $validated['status'] = 'active';
        $validated['approved_at'] = now();
        $validated['approved_by'] = auth()->id();

        JobCategory::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori '{$validated['name']}' berhasil ditambahkan ke sistem.");
    }

    public function update(Request $request, JobCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:job_categories,name,' . $category->id,
            'icon' => 'nullable|string|max:100',
            'badge_color' => 'nullable|string|max:50',
            'subtext' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $oldName = $category->name;
        $validated['slug'] = Str::slug($validated['name']);
        $validated['icon'] = !empty($validated['icon']) ? $validated['icon'] : ($category->icon ?: 'fa-solid fa-briefcase');
        $validated['badge_color'] = !empty($validated['badge_color']) ? $validated['badge_color'] : ($category->badge_color ?: 'blue');
        $validated['subtext'] = $validated['subtext'] ?? null;
        $validated['description'] = $validated['description'] ?? null;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active') ? (bool)$request->is_active : $category->is_active;

        $category->update($validated);

        // If name changed, optionally sync existing job postings division name
        if ($oldName !== $validated['name']) {
            Job::where('division', $oldName)->update(['division' => $validated['name']]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori '{$category->name}' berhasil diperbarui.");
    }

    public function toggleStatus(JobCategory $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();

        $statusLabel = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Kategori '{$category->name}' berhasil {$statusLabel}.");
    }

    public function approve(Request $request, JobCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:job_categories,name,' . $category->id,
            'icon' => 'nullable|string|max:100',
            'badge_color' => 'nullable|string|max:50',
            'subtext' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => !empty($validated['icon']) ? $validated['icon'] : 'fa-solid fa-briefcase',
            'badge_color' => !empty($validated['badge_color']) ? $validated['badge_color'] : 'blue',
            'subtext' => $validated['subtext'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => 'active',
            'is_active' => true,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'rejection_reason' => null,
        ]);

        // Send notification to requesting HR
        if ($category->requested_by) {
            UserNotification::create([
                'user_id' => $category->requested_by,
                'title' => 'Pengajuan Kategori Disetujui',
                'message' => "Pengajuan kategori '{$category->name}' telah disetujui oleh Super Admin dan kini dapat digunakan saat memasang lowongan.",
                'type' => 'success',
                'link' => route('admin.jobs.create'),
            ]);
        }

        return redirect()->route('admin.categories.index', ['tab' => 'requests'])
            ->with('success', "Pengajuan kategori '{$category->name}' telah disetujui dan aktif.");
    }

    public function reject(Request $request, JobCategory $category)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $category->update([
            'status' => 'rejected',
            'is_active' => false,
            'rejection_reason' => $request->rejection_reason,
            'approved_at' => null,
            'approved_by' => null,
        ]);

        // Send notification to requesting HR
        if ($category->requested_by) {
            UserNotification::create([
                'user_id' => $category->requested_by,
                'title' => 'Pengajuan Kategori Ditolak',
                'message' => "Pengajuan kategori '{$category->name}' belum dapat disetujui: {$request->rejection_reason}",
                'type' => 'warning',
                'link' => route('admin.category-requests.index'),
            ]);
        }

        return redirect()->route('admin.categories.index', ['tab' => 'requests'])
            ->with('success', "Pengajuan kategori '{$category->name}' telah ditolak dengan catatan alasan.");
    }

    public function destroy(JobCategory $category)
    {
        $jobsCount = Job::where('division', $category->name)->count();

        if ($jobsCount > 0) {
            return redirect()->back()->with('error', "Kategori '{$category->name}' tidak dapat dihapus karena masih digunakan oleh {$jobsCount} lowongan kerja. Anda dapat menonaktifkannya.");
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori '{$name}' berhasil dihapus.");
    }
}
