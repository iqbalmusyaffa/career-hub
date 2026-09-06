<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class GlobalAttendanceSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'cutoff_time' => SystemSetting::getByKey('attendance_cutoff_time', '23:59'),
            'max_gps_accuracy' => (int) SystemSetting::getByKey('attendance_max_gps_accuracy', '100'),
            'anti_fake_gps' => SystemSetting::getByKey('attendance_anti_fake_gps', '1') === '1',
            'target_hours' => (int) SystemSetting::getByKey('attendance_default_target_hours', '400'),
            'min_percentage' => (int) SystemSetting::getByKey('attendance_min_passing_percentage', '80'),
        ];

        return view('admin.attendance_settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'cutoff_time' => 'required|date_format:H:i',
            'max_gps_accuracy' => 'required|numeric|min:10|max:1000',
            'target_hours' => 'required|numeric|min:50|max:2000',
            'min_percentage' => 'required|numeric|min:50|max:100',
        ]);

        SystemSetting::setKey('attendance_cutoff_time', $request->cutoff_time, 'Waktu batas pengisian absensi harian (WIB)');
        SystemSetting::setKey('attendance_max_gps_accuracy', (string) $request->max_gps_accuracy, 'Batas maksimum toleransi akurasi GPS satelit dalam meter');
        SystemSetting::setKey('attendance_anti_fake_gps', $request->has('anti_fake_gps') ? '1' : '0', 'Aktifkan filter deteksi Fake GPS & Mock Location otomatis');
        SystemSetting::setKey('attendance_default_target_hours', (string) $request->target_hours, 'Standar akumulasi jam kerja target magang');
        SystemSetting::setKey('attendance_min_passing_percentage', (string) $request->min_percentage, 'Persentase minimal kehadiran kelulusan magang');

        $user = auth()->user();
        AuditLog::record(
            'UPDATE_GLOBAL_ATTENDANCE_SETTINGS',
            "Super Admin {$user->name} memperbarui Master Pengaturan Kebijakan Presensi & GPS [Cutoff: {$request->cutoff_time}, Akurasi GPS: ±{$request->max_gps_accuracy}m, Anti-Fake: " . ($request->has('anti_fake_gps') ? 'ON' : 'OFF') . "]",
            $user
        );

        return redirect()->route('admin.attendance-settings.index')
            ->with('success', 'Master Pengaturan Kebijakan Presensi & GPS Global berhasil disimpan.');
    }
}
