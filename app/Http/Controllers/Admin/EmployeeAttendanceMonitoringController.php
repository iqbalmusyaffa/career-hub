<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyBranch;
use App\Models\EmployeeAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeAttendanceMonitoringController extends Controller
{
    /**
     * Display live employee attendance monitoring dashboard for HR & Management.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $companyProfile = $user->currentCompanyProfile();

        $selectedDate = $request->query('date', Carbon::now()->toDateString());
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            $selectedDate = Carbon::now()->toDateString();
        }

        $query = EmployeeAttendance::with(['user.candidateProfile', 'branch', 'companyProfile'])
            ->where('date', $selectedDate);

        // Scope to user's company if not Super Admin
        if (!$user->hasRole('Super Admin') && $companyProfile) {
            $query->where(function ($q) use ($companyProfile) {
                $q->where('company_profile_id', $companyProfile->id)
                  ->orWhereNull('company_profile_id');
            });
        }

        // Branch filter
        if ($request->filled('branch_id')) {
            $query->where('company_branch_id', $request->query('branch_id'));
        }

        // Work type filter (wfo, wfh, dinas_luar)
        if ($request->filled('work_type') && in_array($request->query('work_type'), ['wfo', 'wfh', 'dinas_luar'])) {
            $query->where('work_type', $request->query('work_type'));
        }

        // Check-in status filter (on_time, late, no_checkout)
        if ($request->filled('status')) {
            $status = $request->query('status');
            if ($status === 'on_time') {
                $query->where('check_in_status', 'on_time');
            } elseif ($status === 'late') {
                $query->where('check_in_status', 'late');
            } elseif ($status === 'no_checkout') {
                $query->whereNotNull('check_in_time')->whereNull('check_out_time');
            }
        }

        // Search employee name / email
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Metrics for selected date
        $allForDate = (clone $query)->get();
        $totalCheckedIn = $allForDate->count();
        $onTimeCount = $allForDate->where('check_in_status', 'on_time')->count();
        $lateCount = $allForDate->where('check_in_status', 'late')->count();
        $wfoCount = $allForDate->where('work_type', 'wfo')->count();
        $wfhCount = $allForDate->where('work_type', 'wfh')->count();
        $dinasCount = $allForDate->where('work_type', 'dinas_luar')->count();
        $notCheckedOutCount = $allForDate->whereNull('check_out_time')->count();

        $metrics = [
            'total' => $totalCheckedIn,
            'on_time' => $onTimeCount,
            'late' => $lateCount,
            'wfo' => $wfoCount,
            'wfh' => $wfhCount,
            'dinas' => $dinasCount,
            'not_checked_out' => $notCheckedOutCount,
            'on_time_percentage' => $totalCheckedIn > 0 ? round(($onTimeCount / $totalCheckedIn) * 100) : 100,
        ];

        $attendances = $query->orderBy('check_in_time', 'desc')
            ->paginate(15)
            ->withQueryString();

        $branches = $companyProfile
            ? CompanyBranch::where('company_profile_id', $companyProfile->id)->get()
            : CompanyBranch::all();

        return view('admin.employee_attendances.index', compact(
            'attendances',
            'metrics',
            'branches',
            'selectedDate'
        ));
    }

    /**
     * Export attendance logs to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $user = auth()->user();
        $companyProfile = $user->currentCompanyProfile();

        $selectedDate = $request->query('date', Carbon::now()->toDateString());

        $query = EmployeeAttendance::with(['user', 'branch', 'companyProfile'])
            ->where('date', $selectedDate);

        if (!$user->hasRole('Super Admin') && $companyProfile) {
            $query->where(function ($q) use ($companyProfile) {
                $q->where('company_profile_id', $companyProfile->id)
                  ->orWhereNull('company_profile_id');
            });
        }

        $records = $query->orderBy('check_in_time', 'asc')->get();

        $filename = "rekap_presensi_karyawan_{$selectedDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($records, $selectedDate) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Tanggal',
                'Nama Karyawan',
                'Email',
                'Metode Kerja',
                'Status Kehadiran',
                'Jam Absen Masuk',
                'Koordinat Masuk',
                'Lokasi / Alamat Masuk',
                'Jam Absen Pulang',
                'Koordinat Pulang',
                'Lokasi / Alamat Pulang',
                'Durasi Kerja (Menit)',
                'Durasi Kerja (Format)',
                'Catatan',
            ]);

            foreach ($records as $item) {
                fputcsv($handle, [
                    $item->date->format('Y-m-d'),
                    $item->user?->name ?? 'N/A',
                    $item->user?->email ?? 'N/A',
                    strtoupper($item->work_type),
                    $item->check_in_status === 'late' ? 'Terlambat' : 'Tepat Waktu',
                    $item->check_in_time ?? '-',
                    ($item->check_in_latitude && $item->check_in_longitude) ? "{$item->check_in_latitude}, {$item->check_in_longitude}" : '-',
                    $item->check_in_address ?? '-',
                    $item->check_out_time ?? '-',
                    ($item->check_out_latitude && $item->check_out_longitude) ? "{$item->check_out_latitude}, {$item->check_out_longitude}" : '-',
                    $item->check_out_address ?? '-',
                    $item->work_duration_minutes ?? 0,
                    $item->formatted_work_duration,
                    $item->check_in_notes ?? $item->check_out_notes ?? '-',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
