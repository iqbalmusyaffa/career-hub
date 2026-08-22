<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApplicationService;
use App\Http\Requests\UpdateApplicationStatusRequest;
use App\Exports\ApplicationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    protected $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function index(Request $request)
    {
        $applications = $this->applicationService->getAllApplications();
        return view('admin.applications.index', compact('applications'));
    }

    public function show($id)
    {
        $application = $this->applicationService->getApplicationById($id);
        return view('admin.applications.show', compact('application'));
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, $id)
    {
        $this->applicationService->updateApplicationStatus($id, $request->status);
        return redirect()->back()->with('success', 'Application status updated successfully.');
    }

    public function exportCsv()
    {
        $filename = 'Laporan_Pelamar_' . date('Y-m-d_H-i') . '.csv';
        return Excel::download(new ApplicationsExport, $filename, \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf()
    {
        $applications = \App\Models\Application::with(['user.candidateProfile', 'job'])->latest()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.applications_report', compact('applications'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Pelamar_' . date('Y-m-d') . '.pdf');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array|min:1',
            'status' => 'required|string',
        ]);

        $count = 0;
        foreach ($request->application_ids as $id) {
            $realId = \App\Helpers\IdHasher::decode($id) ?? $id;
            $this->applicationService->updateApplicationStatus($realId, $request->status);
            $count++;
        }

        return redirect()->back()->with('success', "Status {$count} pelamar berhasil diperbarui menjadi " . strtoupper($request->status) . '!');
    }
}
