<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcceptanceCancellationTicket;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceptanceCancellationController extends Controller
{
    /**
     * Display list of cancellation requests for Super Admin.
     */
    public function index()
    {
        $perPage = (int) request('per_page', 10);
        $tickets = AcceptanceCancellationTicket::with(['application.user', 'application.job', 'hrUser', 'handler'])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.cancellation_tickets.index', compact('tickets'));
    }

    /**
     * HR Submits a Cancellation Appeal to Super Admin.
     */
    public function store(Request $request, $applicationId)
    {
        $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        $application = Application::with(['user', 'job'])->findOrFail($applicationId);
        $hrUser = Auth::user();

        // Check if there is already a pending ticket
        $existingTicket = AcceptanceCancellationTicket::where('application_id', $application->id)
            ->where('status', 'pending')
            ->first();

        if ($existingTicket) {
            return back()->with('error', 'Aduan pembatalan penerimaan untuk kandidat ini sudah diajukan dan sedang menunggu review Super Admin.');
        }

        $ticket = AcceptanceCancellationTicket::create([
            'application_id' => $application->id,
            'hr_user_id' => $hrUser->id,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        AuditLog::record('cancellation_requested', "HR {$hrUser->name} mengajukan aduan pembatalan penerimaan untuk kandidat {$application->user->name}");

        // Notify Super Admins
        $superAdmins = \App\Models\User::role('Super Admin')->get();
        foreach ($superAdmins as $admin) {
            UserNotification::send(
                $admin->id,
                "📢 Permohonan Pembatalan Penerimaan Kandidat",
                "HR {$hrUser->name} mengajukan permohonan pembatalan penerimaan kandidat {$application->user->name} untuk posisi {$application->job->title}.",
                route('admin.cancellation-tickets.index'),
                'warning'
            );
        }

        return back()->with('success', 'Aduan pembatalan penerimaan kandidat berhasil dikirim ke Super Admin. Silakan tunggu keputusan verifikasi Super Admin.');
    }

    /**
     * Super Admin approves cancellation.
     */
    public function approve(Request $request, $ticketId)
    {
        $ticket = AcceptanceCancellationTicket::with(['application.user', 'application.job', 'hrUser'])->findOrFail($ticketId);
        $admin = Auth::user();

        $ticket->update([
            'status' => 'approved',
            'superadmin_note' => $request->input('superadmin_note', 'Permohonan pembatalan penerimaan disetujui oleh Super Admin.'),
            'handled_by' => $admin->id,
        ]);

        // Reset application status to rejected or pending
        $application = $ticket->application;
        $application->status = \App\Enums\ApplicationStatus::REJECTED;
        $application->save();

        AuditLog::record('cancellation_approved', "Super Admin {$admin->name} MENYETUJUI pembatalan penerimaan kandidat {$application->user->name}");

        // Notify HR
        UserNotification::send(
            $ticket->hr_user_id,
            "✅ Permohonan Pembatalan Penerimaan Disetujui",
            "Super Admin menyetujui pembatalan penerimaan kandidat {$application->user->name}. Status lamaran telah diperbarui ke Ditolak.",
            route('admin.applications.show', $application->id),
            'success'
        );

        return back()->with('success', 'Permohonan pembatalan penerimaan berhasil DISETUJUI. Status kandidat telah direset.');
    }

    /**
     * Super Admin rejects cancellation.
     */
    public function reject(Request $request, $ticketId)
    {
        $ticket = AcceptanceCancellationTicket::with(['application.user', 'hrUser'])->findOrFail($ticketId);
        $admin = Auth::user();

        $ticket->update([
            'status' => 'rejected',
            'superadmin_note' => $request->input('superadmin_note', 'Permohonan pembatalan ditolak oleh Super Admin.'),
            'handled_by' => $admin->id,
        ]);

        AuditLog::record('cancellation_rejected', "Super Admin {$admin->name} MENOLAK pembatalan penerimaan kandidat {$ticket->application->user->name}");

        // Notify HR
        UserNotification::send(
            $ticket->hr_user_id,
            "❌ Permohonan Pembatalan Penerimaan Ditolak",
            "Super Admin menolak permohonan pembatalan penerimaan kandidat {$ticket->application->user->name}. Status kandidat tetap Diterima.",
            route('admin.applications.show', $ticket->application_id),
            'warning'
        );

        return back()->with('success', 'Permohonan pembatalan penerimaan DITOLAK. Status kandidat tetap Diterima.');
    }
}
