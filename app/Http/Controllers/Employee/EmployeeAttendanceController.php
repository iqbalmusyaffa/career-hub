<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAttendance;
use App\Models\Application;
use App\Models\CompanyBranch;
use App\Models\CompanyProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeAttendanceController extends Controller
{
    /**
     * Display the employee attendance console, live camera feed, and monthly recap.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Interns have their own logbook module, redirect if intern tries to access employee attendance
        if ($user->isIntern()) {
            return redirect()->route('candidate.logbook.index')
                ->with('info', 'Modul presensi kamera selfie ini dikhususkan untuk Karyawan. Anda dialihkan ke Presensi & Logbook Magang.');
        }

        $today = Carbon::now()->toDateString();
        $todayAttendance = EmployeeAttendance::with('branch')
            ->where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        // Resolve active company & branch info for geofence map
        $companyProfile = $user->currentCompanyProfile();
        $companyBranch = null;

        if (!$companyProfile) {
            $activeApp = Application::with(['job.companyProfile', 'job.companyBranch'])
                ->where('user_id', $user->id)
                ->whereIn('status', ['accepted', 'hired'])
                ->latest()
                ->first();

            if ($activeApp && $activeApp->job) {
                $companyProfile = $activeApp->job->companyProfile;
                $companyBranch = $activeApp->job->companyBranch;
            }
        }

        if (!$companyProfile) {
            $companyProfile = CompanyProfile::first();
        }

        if (!$companyBranch && $companyProfile) {
            $companyBranch = CompanyBranch::where('company_profile_id', $companyProfile->id)
                ->where('is_headquarter', true)
                ->first() ?? CompanyBranch::where('company_profile_id', $companyProfile->id)->first();
        }

        // Selected month for history
        $selectedMonth = $request->query('month', Carbon::now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = Carbon::now()->format('Y-m');
        }

        $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth);
        $startOfMonth = $monthDate->copy()->startOfMonth();
        $endOfMonth = $monthDate->copy()->endOfMonth();

        // Monthly attendances
        $monthlyAttendances = EmployeeAttendance::where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderByDesc('date')
            ->get();

        // Monthly statistics
        $totalPresent = $monthlyAttendances->count();
        $onTimeCount = $monthlyAttendances->where('check_in_status', 'on_time')->count();
        $lateCount = $monthlyAttendances->where('check_in_status', 'late')->count();
        $totalWorkMinutes = $monthlyAttendances->sum('work_duration_minutes');
        $onTimePercentage = $totalPresent > 0 ? round(($onTimeCount / $totalPresent) * 100) : 100;

        $stats = [
            'total_present' => $totalPresent,
            'on_time_count' => $onTimeCount,
            'late_count' => $lateCount,
            'on_time_percentage' => $onTimePercentage,
            'total_work_hours' => round($totalWorkMinutes / 60, 1),
            'avg_daily_hours' => $totalPresent > 0 ? round(($totalWorkMinutes / $totalPresent) / 60, 1) : 0,
        ];

        // Office Geofence Coordinates (Fallback to Jakarta TechNova HQ if none configured)
        $officeCoordinates = [
            'lat' => -6.200000,
            'lng' => 106.816666,
            'name' => $companyBranch ? $companyBranch->branch_name : ($companyProfile ? $companyProfile->company_name : 'Kantor Utama'),
            'address' => $companyBranch ? $companyBranch->address : ($companyProfile ? $companyProfile->address : 'Gedung Pusat Operasional'),
            'radius_meters' => 250, // 250m geofence radius
        ];

        return view('employee.attendance.index', compact(
            'todayAttendance',
            'companyProfile',
            'companyBranch',
            'selectedMonth',
            'monthlyAttendances',
            'stats',
            'officeCoordinates'
        ));
    }

    /**
     * Submit Check-In with Selfie Camera capture & Geolocation.
     */
    public function checkIn(Request $request)
    {
        $user = auth()->user();

        if ($user->isIntern()) {
            return response()->json([
                'success' => false,
                'message' => 'Modul ini khusus karyawan regular. Peserta magang menggunakan logbook harian.',
            ], 403);
        }

        $request->validate([
            'work_type' => 'required|in:wfo,wfh,dinas_luar',
            'selfie_image' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $today = Carbon::now()->toDateString();
        $currentTime = Carbon::now();

        // Check if already checked in today
        $attendance = EmployeeAttendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($attendance && $attendance->check_in_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absen masuk hari ini pada pukul ' . $attendance->formatted_check_in_time,
            ], 422);
        }

        // Process Base64 Selfie Image
        $filePath = $this->storeBase64Selfie($request->input('selfie_image'), 'checkin_' . $user->id);

        // Determine if late (Office standard: 08:30 WIB)
        $standardCutoff = Carbon::createFromTimeString('08:30:00');
        $isLate = $currentTime->format('H:i:s') > $standardCutoff->format('H:i:s');

        // Resolve Company and Branch
        $companyProfile = $user->currentCompanyProfile();
        $branchId = null;

        if (!$companyProfile) {
            $activeApp = Application::with(['job.companyProfile', 'job.branch'])
                ->where('user_id', $user->id)
                ->whereIn('status', ['accepted', 'hired'])
                ->latest()
                ->first();

            if ($activeApp && $activeApp->job) {
                $companyProfile = $activeApp->job->companyProfile;
                $branchId = $activeApp->job->company_branch_id;
            }
        }

        if ($companyProfile && !$branchId) {
            $branch = CompanyBranch::where('company_profile_id', $companyProfile->id)->first();
            $branchId = $branch?->id;
        }

        $attendance = EmployeeAttendance::updateOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            [
                'company_profile_id' => $companyProfile?->id,
                'company_branch_id' => $branchId,
                'work_type' => $request->input('work_type', 'wfo'),
                'status' => $isLate ? 'late' : 'present',
                'check_in_time' => $currentTime->format('H:i:s'),
                'check_in_selfie_path' => $filePath,
                'check_in_latitude' => $request->input('latitude'),
                'check_in_longitude' => $request->input('longitude'),
                'check_in_address' => $request->input('address'),
                'check_in_status' => $isLate ? 'late' : 'on_time',
                'check_in_notes' => $request->input('notes'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Absen Masuk Berhasil! Waktu tercatat: ' . $attendance->formatted_check_in_time . ($isLate ? ' (Status: Terlambat)' : ' (Status: Tepat Waktu)'),
            'attendance' => $attendance,
            'redirect' => route('employee.attendance.index'),
        ]);
    }

    /**
     * Submit Check-Out with Selfie Camera capture & Geolocation.
     */
    public function checkOut(Request $request)
    {
        $user = auth()->user();

        if ($user->isIntern()) {
            return response()->json([
                'success' => false,
                'message' => 'Modul ini khusus karyawan regular.',
            ], 403);
        }

        $request->validate([
            'selfie_image' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $today = Carbon::now()->toDateString();
        $currentTime = Carbon::now();

        $attendance = EmployeeAttendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan absen masuk hari ini. Silakan absen masuk terlebih dahulu.',
            ], 422);
        }

        if ($attendance->check_out_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absen pulang hari ini pada pukul ' . $attendance->formatted_check_out_time,
            ], 422);
        }

        // Process Base64 Selfie Image for checkout
        $filePath = $this->storeBase64Selfie($request->input('selfie_image'), 'checkout_' . $user->id);

        // Calculate total working duration in minutes
        $checkInDateTime = Carbon::parse($today . ' ' . $attendance->check_in_time);
        $durationMinutes = max(0, $checkInDateTime->diffInMinutes($currentTime));

        $attendance->update([
            'check_out_time' => $currentTime->format('H:i:s'),
            'check_out_selfie_path' => $filePath,
            'check_out_latitude' => $request->input('latitude'),
            'check_out_longitude' => $request->input('longitude'),
            'check_out_address' => $request->input('address'),
            'check_out_notes' => $request->input('notes'),
            'work_duration_minutes' => $durationMinutes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absen Pulang Berhasil! Durasi kerja hari ini: ' . $attendance->formatted_work_duration . '. Selamat beristirahat!',
            'attendance' => $attendance,
            'redirect' => route('employee.attendance.index'),
        ]);
    }

    /**
     * Helper to decode and store base64 captured camera selfie image.
     */
    private function storeBase64Selfie(string $base64String, string $prefix): ?string
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
            $extension = strtolower($matches[1]) === 'png' ? 'png' : 'jpg';
            $data = substr($base64String, strpos($base64String, ',') + 1);
            $decoded = base64_decode($data);

            if ($decoded !== false) {
                $filename = $prefix . '_' . now()->format('Ymd_His') . '_' . Str::random(6) . '.' . $extension;
                $directory = 'attendance_selfies';

                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }

                $filePath = $directory . '/' . $filename;
                Storage::disk('public')->put($filePath, $decoded);

                return $filePath;
            }
        }

        return null;
    }
}
