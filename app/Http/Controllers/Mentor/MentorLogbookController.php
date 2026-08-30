<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\InternshipLogbook;
use App\Models\User;
use Illuminate\Http\Request;

class MentorLogbookController extends Controller
{
    public function dashboard()
    {
        $mentorId = auth()->id();

        // Pending logbooks requiring mentor approval (ACC)
        $pendingLogbooks = InternshipLogbook::with('intern')
            ->where('status', 'pending')
            ->orderBy('date', 'desc')
            ->get();

        $approvedCount = InternshipLogbook::where('status', 'approved')->count();
        $rejectedCount = InternshipLogbook::where('status', 'rejected')->count();
        $totalLogbooks = InternshipLogbook::count();

        // Total active interns assigned
        $totalInterns = User::role('Candidate')->count();

        return view('mentor.dashboard', compact(
            'pendingLogbooks',
            'approvedCount',
            'rejectedCount',
            'totalLogbooks',
            'totalInterns'
        ));
    }

    public function index()
    {
        $logbooks = InternshipLogbook::with(['intern', 'company'])
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('mentor.logbooks.index', compact('logbooks'));
    }

    public function show($id)
    {
        $logbook = InternshipLogbook::with(['intern', 'company'])->findOrFail($id);

        return view('mentor.logbooks.show', compact('logbook'));
    }

    public function approve(Request $request, $id)
    {
        $logbook = InternshipLogbook::findOrFail($id);
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        $logbook->update([
            'status' => 'approved',
            'mentor_id' => $user->id,
            'approved_at' => now(),
            'mentor_notes' => $request->input('mentor_notes', $isSuperAdmin ? 'Kehadiran & Laporan Harian disetujui (ACC) oleh Super Admin.' : 'Laporan harian dan kehadiran disetujui oleh Mentor.'),
        ]);

        $actorTitle = $isSuperAdmin ? 'Super Admin' : 'Mentor';

        // System Flow Recording
        \App\Models\AuditLog::record(
            $isSuperAdmin ? 'SUPERADMIN_ACC_PRESENSI' : 'MENTOR_ACC_PRESENSI',
            "{$actorTitle} {$user->name} MENYETUJUI (ACC) presensi tanggal {$logbook->date->format('d M Y')} milik {$logbook->intern->name}.",
            $user
        );

        return redirect()->route('mentor.dashboard')
            ->with('success', 'Kehadiran & Laporan Harian tanggal ' . $logbook->date->format('d M Y') . ' milik ' . $logbook->intern->name . ' BERHASIL DI-ACC (' . $actorTitle . ').');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'mentor_notes' => 'required|string|min:5',
            'status_type' => 'required|in:rejected,action_required',
        ], [
            'mentor_notes.required' => 'Wajib memberikan alasan atau arahan perbaikan pada catatan.',
        ]);

        $logbook = InternshipLogbook::findOrFail($id);
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        $logbook->update([
            'status' => $request->status_type,
            'mentor_id' => $user->id,
            'mentor_notes' => $request->mentor_notes,
        ]);

        $msg = $request->status_type === 'rejected' ? 'ditolak' : 'dikembalikan untuk revisi';
        $actorTitle = $isSuperAdmin ? 'Super Admin' : 'Mentor';

        // System Flow Recording
        \App\Models\AuditLog::record(
            $isSuperAdmin ? 'SUPERADMIN_REJECT_PRESENSI' : 'MENTOR_REJECT_PRESENSI',
            "{$actorTitle} {$user->name} mengubah status presensi {$logbook->intern->name} tanggal {$logbook->date->format('d M Y')} menjadi {$msg}. Catatan: '{$request->mentor_notes}'",
            $user
        );

        return redirect()->route('mentor.dashboard')
            ->with('warning', 'Laporan harian ' . $logbook->intern->name . ' telah ' . $msg . ' oleh ' . $actorTitle . '.');
    }
}
