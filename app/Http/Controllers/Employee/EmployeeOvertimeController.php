<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeOvertime;
use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeOvertimeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isIntern()) {
            return redirect()->route('candidate.logbook.index')
                ->with('info', 'Fitur lembur dikhususkan untuk Karyawan Tetap/Kontrak.');
        }

        $selectedMonth = $request->query('month', Carbon::now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = Carbon::now()->format('Y-m');
        }

        $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth);
        $startOfMonth = $monthDate->copy()->startOfMonth()->toDateString();
        $endOfMonth = $monthDate->copy()->endOfMonth()->toDateString();

        $overtimes = EmployeeOvertime::where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->latest('date')
            ->get();

        $totalApprovedHours = (float) $overtimes->where('status', 'approved')->sum('duration_hours');
        $totalApprovedPay = (int) $overtimes->where('status', 'approved')->sum('total_compensation');
        $pendingCount = $overtimes->where('status', 'pending')->count();
        $rejectedCount = $overtimes->where('status', 'rejected')->count();

        return view('employee.overtimes.index', compact(
            'overtimes',
            'selectedMonth',
            'monthDate',
            'totalApprovedHours',
            'totalApprovedPay',
            'pendingCount',
            'rejectedCount'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->isIntern()) {
            return redirect()->route('candidate.logbook.index')
                ->with('info', 'Fitur lembur dikhususkan untuk Karyawan Tetap/Kontrak.');
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'task_description' => 'required|string|min:10|max:1000',
            'compensation_type' => 'required|in:paid,leave_balance',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $start = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $end = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);

        // Handle overnight overtime (e.g. 22:00 to 02:00)
        if ($end->lessThanOrEqualTo($start)) {
            $end->addDay();
        }

        $durationMinutes = $start->diffInMinutes($end);
        $durationHours = round($durationMinutes / 60, 2);

        if ($durationHours < 0.5) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Durasi lembur minimal adalah 30 menit (0.5 jam).');
        }

        // Standard hourly overtime rate (Depnaker baseline: Rp 35.000 / jam)
        $hourlyRate = 35000;
        $totalCompensation = $validated['compensation_type'] === 'paid' ? round($durationHours * $hourlyRate) : 0;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('overtime_attachments', 'public');
        }

        // Resolve active company
        $companyProfile = $user->currentCompanyProfile();
        if (!$companyProfile) {
            $activeApp = Application::with('job.companyProfile')
                ->where('user_id', $user->id)
                ->whereIn('status', ['accepted', 'hired'])
                ->latest()
                ->first();
            $companyProfile = $activeApp?->job?->companyProfile ?? CompanyProfile::first();
        }

        $overtime = EmployeeOvertime::create([
            'user_id' => $user->id,
            'company_profile_id' => $companyProfile?->id,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration_hours' => $durationHours,
            'task_description' => $validated['task_description'],
            'compensation_type' => $validated['compensation_type'],
            'hourly_rate' => $hourlyRate,
            'total_compensation' => $totalCompensation,
            'attachment_path' => $attachmentPath,
            'status' => 'pending',
        ]);

        AuditLog::record(
            'EMPLOYEE_OVERTIME_REQUEST',
            "Karyawan {$user->name} mengajukan lembur tanggal {$validated['date']} selama {$durationHours} jam",
            $user
        );

        return redirect()->route('employee.overtimes.index')
            ->with('success', 'Pengajuan lembur berhasil dikirim ke HR/Atasan dan menunggu verifikasi.');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $overtime = EmployeeOvertime::where('user_id', $user->id)->findOrFail($id);

        if ($overtime->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pengajuan yang berstatus "Menunggu Review" yang dapat dibatalkan.');
        }

        if ($overtime->attachment_path) {
            Storage::disk('public')->delete($overtime->attachment_path);
        }

        $overtime->delete();

        return redirect()->route('employee.overtimes.index')
            ->with('success', 'Pengajuan lembur berhasil dibatalkan.');
    }
}
