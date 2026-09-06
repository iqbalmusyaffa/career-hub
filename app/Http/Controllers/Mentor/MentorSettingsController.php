<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\CompanyHoliday;
use App\Models\CompanyHolidayOverride;
use App\Models\InternshipPeriod;
use App\Models\User;
use Illuminate\Http\Request;

class MentorSettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // National Holidays & Cuti Bersama managed by Super Admin (Global)
        $nationalHolidays = CompanyHoliday::whereNull('company_id')
            ->orderBy('date', 'asc')
            ->get();

        // Get mentor/HR's company ID
        $companyId = $user->companyProfile ? $user->companyProfile->id : 1;

        // Custom Company Holidays created specifically by HR / Mentor
        $companyHolidays = CompanyHoliday::where('company_id', $companyId)
            ->orderBy('date', 'asc')
            ->get();

        // Holiday Overrides set by HR/Mentor for their company
        $overrides = CompanyHolidayOverride::where('company_id', $companyId)
            ->get()
            ->keyBy('company_holiday_id');

        $periods = InternshipPeriod::with('intern')->orderBy('start_date', 'asc')->get();
        $interns = User::role('Candidate')->get();

        return view('mentor.settings.index', compact('nationalHolidays', 'companyHolidays', 'overrides', 'periods', 'interns'));
    }

    public function storeCompanyHoliday(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $companyId = $user->companyProfile ? $user->companyProfile->id : 1;

        CompanyHoliday::create([
            'company_id' => $companyId,
            'date' => $request->date,
            'name' => $request->name,
            'type' => 'company_holiday',
        ]);

        return redirect()->back()
            ->with('success', 'Hari Libur Internal Perusahaan "' . $request->name . '" berhasil ditambahkan.');
    }

    public function deleteCompanyHoliday($id)
    {
        $user = auth()->user();
        $companyId = $user->companyProfile ? $user->companyProfile->id : 1;

        CompanyHoliday::where('id', $id)
            ->where('company_id', $companyId)
            ->delete();

        return redirect()->back()->with('success', 'Hari libur internal perusahaan berhasil dihapus.');
    }

    public function toggleOverride(Request $request, $holidayId)
    {
        $user = auth()->user();
        $companyId = $user->companyProfile ? $user->companyProfile->id : 1;

        $holiday = CompanyHoliday::findOrFail($holidayId);

        // Libur Nasional mutlak libur dan tidak boleh di-override
        if ($holiday->type === 'national_holiday') {
            return redirect()->back()->with('error', 'Hari Libur Nasional bersifat mutlak (Wajib Libur) dan tidak dapat diubah menjadi hari kerja.');
        }

        $override = CompanyHolidayOverride::where('company_id', $companyId)
            ->where('company_holiday_id', $holidayId)
            ->first();

        if ($override) {
            // Batalkan pengajuan / restore default
            $override->delete();
            $message = 'Pengajuan izin masuk kerja pada Cuti Bersama telah dibatalkan. Status dikembalikan ke Libur Pemerintah.';
        } else {
            CompanyHolidayOverride::create([
                'company_id' => $companyId,
                'company_holiday_id' => $holidayId,
                'is_working_day' => true,
                'reason' => $request->input('reason', 'Permohonan dispensasi operasional kerja pada Cuti Bersama Pemerintah diajukan ke Super Admin.'),
            ]);
            $message = 'Permohonan operasional kerja pada Cuti Bersama "' . $holiday->name . '" berhasil dikirimkan ke Super Admin.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function storePeriod(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'target_hours' => 'required|integer|min:1',
        ]);

        InternshipPeriod::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'period_name' => $request->period_name,
            ],
            [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'target_hours' => $request->target_hours,
            ]
        );

        return redirect()->back()
            ->with('success', 'Pengaturan Periode Magang & Jam Kerja peserta berhasil diperbarui.');
    }
}
