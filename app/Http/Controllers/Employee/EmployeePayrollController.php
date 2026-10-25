<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeePayroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployeePayrollController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isIntern()) {
            return redirect()->route('candidate.logbook.progress')
                ->with('info', 'Slip uang saku magang dapat diakses di menu Uang Saku Program Magang.');
        }

        $payrolls = EmployeePayroll::where('user_id', $user->id)
            ->whereIn('status', ['published', 'paid'])
            ->latest('period_month')
            ->get();

        $selectedPayrollId = $request->query('id', $payrolls->first()?->id);
        $activePayroll = $payrolls->firstWhere('id', $selectedPayrollId) ?? $payrolls->first();

        return view('employee.payrolls.index', compact('payrolls', 'activePayroll'));
    }

    public function downloadSlip($id)
    {
        $user = auth()->user();
        $payroll = EmployeePayroll::with(['user.candidateProfile', 'company'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['published', 'paid'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('pdf.employee_payslip', [
            'payroll' => $payroll,
            'user' => $payroll->user,
            'company' => $payroll->company,
        ])->setPaper('a4', 'portrait');

        $filename = 'Slip_Gaji_' . Str::slug($payroll->user->name) . '_' . str_replace('-', '_', $payroll->period_month) . '.pdf';

        return $pdf->download($filename);
    }
}
