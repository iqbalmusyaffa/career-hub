<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\InternshipLogbook;
use App\Models\User;
use Illuminate\Http\Request;

class MentorLogbookController extends Controller
{
    public function dashboard(Request $request)
    {
        $mentorId = auth()->id();
        $user = auth()->user();
        $companyId = $user?->companyProfile ? $user->companyProfile->id : null;

        $selectedBatch = $request->query('batch');
        $batches = \App\Models\InternshipBatch::getActiveBatches($companyId);

        // Query interns filtered by batch
        $internsQuery = User::role('Candidate')
            ->whereDoesntHave('internshipResignations', function($q) {
                $q->where('status', 'approved');
            })
            ->whereDoesntHave('employeeTerminations');

        if ($selectedBatch) {
            $internsQuery->where(function ($q) use ($selectedBatch) {
                $q->whereHas('internshipPeriods', function ($p) use ($selectedBatch) {
                    $p->where('period_name', $selectedBatch);
                })->orWhereHas('applications.job', function ($j) use ($selectedBatch) {
                    $j->where('batch', $selectedBatch);
                });
            });
        }
        $targetInternIds = $internsQuery->pluck('id');
        $totalInterns = $targetInternIds->count();

        // Pending logbooks requiring mentor approval (ACC)
        $pendingQuery = InternshipLogbook::with('intern')
            ->where('status', 'pending');
        if ($selectedBatch) {
            $pendingQuery->whereIn('user_id', $targetInternIds);
        }
        $pendingLogbooks = $pendingQuery->orderBy('date', 'desc')->get();

        $logbooksBaseQuery = InternshipLogbook::query();
        if ($selectedBatch) {
            $logbooksBaseQuery->whereIn('user_id', $targetInternIds);
        }

        $approvedCount = (clone $logbooksBaseQuery)->where('status', 'approved')->count();
        $rejectedCount = (clone $logbooksBaseQuery)->where('status', 'rejected')->count();
        $totalLogbooks = (clone $logbooksBaseQuery)->count();
        $totalWorkHours = (clone $logbooksBaseQuery)->where('status', 'approved')->sum('work_hours');
        $averageHoursPerIntern = $totalInterns > 0 ? round($totalWorkHours / $totalInterns, 1) : 0;

        return view('mentor.dashboard', compact(
            'pendingLogbooks',
            'approvedCount',
            'rejectedCount',
            'totalLogbooks',
            'totalInterns',
            'batches',
            'selectedBatch',
            'totalWorkHours',
            'averageHoursPerIntern'
        ));
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $companyId = $user?->companyProfile ? $user->companyProfile->id : null;

        $selectedBatch = $request->query('batch');

        // Fetch distinct batch names merged with master batches
        $batches = \App\Models\InternshipBatch::getActiveBatches($companyId);
        $masterBatches = \App\Models\InternshipBatch::where(function($q) use ($companyId) {
                $q->whereNull('company_id')->orWhere('company_id', $companyId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $query = User::role('Candidate')
            ->whereDoesntHave('internshipResignations', function($q) {
                $q->where('status', 'approved');
            })
            ->whereDoesntHave('employeeTerminations')
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
            $query->where(function ($q) use ($selectedBatch) {
                $q->whereHas('internshipPeriods', function ($p) use ($selectedBatch) {
                    $p->where('period_name', $selectedBatch);
                })->orWhereHas('applications.job', function ($j) use ($selectedBatch) {
                    $j->where('batch', $selectedBatch);
                });
            });
        }

        $interns = $query->paginate(15)->withQueryString();

        $allInterns = User::role('Candidate')
            ->whereDoesntHave('internshipResignations', function($q) {
                $q->where('status', 'approved');
            })
            ->whereDoesntHave('employeeTerminations')
            ->with('internshipPeriod')
            ->orderBy('name')
            ->get();

        return view('mentor.logbooks.index', compact('interns', 'allInterns', 'batches', 'selectedBatch', 'masterBatches'));
    }

    public function storeBatch(Request $request)
    {
        $request->validate([
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'user_id' => 'nullable|exists:users,id',
            'period_name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'target_hours' => 'nullable|integer|min:1',
        ]);

        $user = auth()->user();
        $companyId = $user->companyProfile ? $user->companyProfile->id : 1;

        $targetUserIds = [];
        if (!empty($request->user_ids)) {
            $targetUserIds = $request->user_ids;
        } elseif ($request->filled('user_id')) {
            $targetUserIds = [$request->user_id];
        }

        if (empty($targetUserIds)) {
            return redirect()->back()->with('error', 'Silakan pilih minimal 1 peserta magang untuk diterapkan ke batch ini.');
        }

        if ($request->filled('period_name')) {
            \App\Models\InternshipBatch::findOrCreateByName($request->period_name, $companyId, [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'target_hours' => $request->target_hours ?? 400,
                'status' => 'active',
            ]);
        }

        foreach ($targetUserIds as $uId) {
            \App\Models\InternshipPeriod::updateOrCreate(
                [
                    'user_id' => $uId,
                    'period_name' => $request->period_name,
                ],
                [
                    'company_id' => $companyId,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'target_hours' => $request->target_hours ?? 400,
                ]
            );
        }

        $count = count($targetUserIds);
        return redirect()->back()
            ->with('success', "Pengaturan Batch / Periode Magang berhasil disimpan untuk {$count} peserta magang.");
    }

    public function internLogbooks(Request $request, $internId)
    {
        $intern = User::role('Candidate')->with(['candidateProfile', 'internshipPeriod', 'applications.job.companyProfile'])->findOrFail($internId);

        $selectedAttendance = $request->query('attendance');
        $selectedStatus = $request->query('status');
        $selectedMonth = $request->query('month');

        $query = InternshipLogbook::with(['intern', 'company'])
            ->where('user_id', $internId);

        if ($selectedAttendance) {
            if ($selectedAttendance === 'present') {
                $query->whereIn('attendance_type', ['Hadir', 'present', 'wfo', 'wfh', 'Hadir Tepat Waktu', 'Terlambat']);
            } elseif ($selectedAttendance === 'excused' || $selectedAttendance === 'izin_sakit') {
                $query->whereIn('attendance_type', ['Tidak Hadir Dengan Keterangan', 'Izin', 'Sakit', 'permission', 'sick']);
            } elseif ($selectedAttendance === 'unexcused' || $selectedAttendance === 'alpha') {
                $query->whereIn('attendance_type', ['Tidak Hadir Tanpa Keterangan', 'absent']);
            }
        }

        if ($selectedStatus) {
            $query->where('status', $selectedStatus);
        }

        if ($selectedMonth) {
            $query->where('date', 'like', "{$selectedMonth}%");
        }

        $logbooks = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();

        // Find curriculum for candidate
        $latestApp = $intern->applications()->with('job.companyProfile')->latest()->first();
        $jobId = $latestApp?->job_id;
        $batch = $intern->internshipPeriod?->period_name ?? $latestApp?->job?->batch;
        $companyId = $latestApp?->job?->companyProfile?->id ?? 1;

        $curriculum = null;
        if ($jobId) {
            $curriculum = \App\Models\InternshipCurriculum::with('materials')->where('job_id', $jobId)->first();
        }
        if (!$curriculum && $batch) {
            $curriculum = \App\Models\InternshipCurriculum::with('materials')->where('batch', $batch)->first();
        }
        if (!$curriculum) {
            $curriculum = \App\Models\InternshipCurriculum::with('materials')->where('company_id', $companyId)->first();
        }

        $progressMap = \App\Models\InternCurriculumProgress::where('user_id', $internId)
            ->get()
            ->keyBy('curriculum_material_id');

        return view('mentor.logbooks.intern_logbooks', compact(
            'intern',
            'logbooks',
            'curriculum',
            'progressMap',
            'selectedAttendance',
            'selectedStatus',
            'selectedMonth'
        ));
    }

    public function updateMaterialProgress(Request $request, $internId, $materialId)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
            'mentor_notes' => 'nullable|string',
        ]);

        $mentor = auth()->user();
        $material = \App\Models\CurriculumMaterial::findOrFail($materialId);

        \App\Models\InternCurriculumProgress::updateOrCreate(
            [
                'user_id' => $internId,
                'curriculum_material_id' => $materialId,
            ],
            [
                'status' => $request->status,
                'mentor_id' => $mentor->id,
                'mentor_notes' => $request->mentor_notes,
                'completed_at' => $request->status === 'completed' ? now() : null,
            ]
        );

        if ($request->status === 'completed') {
            \App\Models\UserNotification::send(
                $internId,
                '🎉 Modul Pembelajaran Tervalidasi!',
                "Mentor {$mentor->name} telah memvalidasi kelulusan materi: {$material->title}.",
                route('candidate.logbook.progress'),
                'success'
            );
        }

        return redirect()->back()->with('success', "Progres modul '{$material->title}' berhasil diperbarui menjadi " . strtoupper($request->status) . '.');
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
