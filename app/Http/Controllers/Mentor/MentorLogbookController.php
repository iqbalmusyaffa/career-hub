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

    public function index(Request $request)
    {
        $selectedBatch = $request->query('batch');

        // Fetch distinct batch names
        $batches = \App\Models\InternshipPeriod::select('period_name')
            ->distinct()
            ->orderBy('period_name')
            ->pluck('period_name');

        $query = User::role('Candidate')
            ->with(['candidateProfile', 'internshipPeriod', 'applications.job'])
            ->withCount([
                'logbooks',
                'logbooks as pending_logbooks_count' => function ($query) {
                    $query->where('status', 'pending');
                },
                'logbooks as approved_logbooks_count' => function ($query) {
                    $query->where('status', 'approved');
                },
                'logbooks as rejected_logbooks_count' => function ($query) {
                    $query->whereIn('status', ['rejected', 'action_required']);
                },
            ]);

        if ($selectedBatch) {
            $query->whereHas('internshipPeriods', function ($q) use ($selectedBatch) {
                $q->where('period_name', $selectedBatch);
            });
        }

        $interns = $query->paginate(15)->withQueryString();

        return view('mentor.logbooks.index', compact('interns', 'batches', 'selectedBatch'));
    }

    public function storeBatch(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'target_hours' => 'nullable|integer|min:1',
        ]);

        $user = auth()->user();
        $companyId = $user->companyProfile ? $user->companyProfile->id : 1;

        \App\Models\InternshipPeriod::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'period_name' => $request->period_name,
            ],
            [
                'company_id' => $companyId,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'target_hours' => $request->target_hours ?? 400,
            ]
        );

        return redirect()->back()
            ->with('success', 'Pengaturan Batch / Periode Magang peserta berhasil disimpan.');
    }

    public function internLogbooks($internId)
    {
        $intern = User::role('Candidate')->with(['candidateProfile', 'internshipPeriod'])->findOrFail($internId);

        $logbooks = InternshipLogbook::with(['intern', 'company'])
            ->where('user_id', $internId)
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('mentor.logbooks.intern_logbooks', compact('intern', 'logbooks'));
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

        $redirectUrl = $request->input('redirect_to') ?? (
            $isSuperAdmin ? route('admin.internship-monitor.index') : route('mentor.logbooks.intern', $logbook->user_id)
        );

        return redirect($redirectUrl)
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

        $redirectUrl = $request->input('redirect_to') ?? (
            $isSuperAdmin ? route('admin.internship-monitor.index') : route('mentor.logbooks.intern', $logbook->user_id)
        );

        return redirect($redirectUrl)
            ->with('warning', 'Laporan harian ' . $logbook->intern->name . ' telah ' . $msg . ' oleh ' . $actorTitle . '.');
    }
}
