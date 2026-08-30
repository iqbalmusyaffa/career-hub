<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipUnlockRequest;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InternshipUnlockRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $query = InternshipUnlockRequest::with(['mentor', 'intern', 'company', 'resolver'])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->paginate(15)->withQueryString();

        $pendingCount = InternshipUnlockRequest::where('status', 'pending')->count();
        $approvedCount = InternshipUnlockRequest::where('status', 'approved')->count();
        $rejectedCount = InternshipUnlockRequest::where('status', 'rejected')->count();

        return view('admin.internship_unlocks.index', compact(
            'requests',
            'status',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    public function show($id)
    {
        $unlockRequest = InternshipUnlockRequest::with(['mentor', 'intern', 'company', 'resolver'])->findOrFail($id);

        return view('admin.internship_unlocks.show', compact('unlockRequest'));
    }

    public function approve(Request $request, $id)
    {
        $unlockRequest = InternshipUnlockRequest::findOrFail($id);
        $user = auth()->user();

        $hours = (int) $request->input('unlock_hours', 24);
        $unlockedUntil = Carbon::now()->addHours($hours);

        $unlockRequest->update([
            'status' => 'approved',
            'admin_notes' => $request->input('admin_notes', 'Permohonan buka kunci tanggal presensi telah disetujui oleh Super Admin.'),
            'unlocked_until' => $unlockedUntil,
            'resolved_by' => $user->id,
        ]);

        AuditLog::record(
            'SUPERADMIN_APPROVE_UNLOCK_PRESENSI',
            "Super Admin {$user->name} MENYETUJUI permohonan buka kunci tanggal {$unlockRequest->target_date->format('d M Y')} untuk anak magang {$unlockRequest->intern->name}. Akses dibuka s/d {$unlockedUntil->format('d M Y H:i')} WIB.",
            $user
        );

        return redirect()->route('admin.internship-unlocks.index')
            ->with('success', "Permohonan buka kunci tanggal {$unlockRequest->target_date->format('d M Y')} milik {$unlockRequest->intern->name} BERHASIL DISETUJUI (Akses aktif {$hours} jam).");
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|min:5',
        ], [
            'admin_notes.required' => 'Wajib mencantumkan alasan penolakan permohonan.',
        ]);

        $unlockRequest = InternshipUnlockRequest::findOrFail($id);
        $user = auth()->user();

        $unlockRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'resolved_by' => $user->id,
        ]);

        AuditLog::record(
            'SUPERADMIN_REJECT_UNLOCK_PRESENSI',
            "Super Admin {$user->name} MENOLAK permohonan buka kunci tanggal {$unlockRequest->target_date->format('d M Y')} untuk {$unlockRequest->intern->name}. Alasan: '{$request->admin_notes}'",
            $user
        );

        return redirect()->route('admin.internship-unlocks.index')
            ->with('warning', "Permohonan buka kunci tanggal {$unlockRequest->target_date->format('d M Y')} telah ditolak.");
    }
}
