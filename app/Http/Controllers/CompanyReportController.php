<?php

namespace App\Http\Controllers;

use App\Models\CompanyReport;
use App\Models\Job;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class CompanyReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'nullable|exists:job_postings,id',
            'company_name' => 'required|string|max:255',
            'report_category' => 'required|string',
            'reason_description' => 'required|string|min:15',
        ]);

        $report = CompanyReport::create([
            'user_id' => auth()->id(),
            'job_id' => $request->job_id,
            'company_name' => $request->company_name,
            'report_category' => $request->report_category,
            'reason_description' => $request->reason_description,
            'status' => 'pending',
        ]);

        AuditLog::record('red_flag_reported', "Melaporkan indikasi Red Flag untuk perusahaan: {$request->company_name}");

        return back()->with('success', '🚩 Laporan indikasi Red Flag telah berhasil dikirim ke tim Super Admin TalentFlow untuk diinvestigasi!');
    }
}
