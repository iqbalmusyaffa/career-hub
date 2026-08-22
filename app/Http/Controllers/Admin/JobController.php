<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\JobService;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\AuditLog;

class JobController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function index()
    {
        $jobs = $this->jobService->getAllJobs();
        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(StoreJobRequest $request)
    {
        $data = $request->validated();
        $message = 'Lowongan kerja berhasil dipublikasikan!';

        // Moderasi Otomatis untuk Lowongan Full-Time di Bawah UMK
        $umk = \App\Models\UmkReference::findByLocation($data['location'] ?? '');
        if ($umk && strtolower($data['work_type'] ?? '') === 'full-time') {
            preg_match_all('/\d[\d\.\,]*/', $data['salary'] ?? '', $matches);
            $numSalary = 0;
            if (!empty($matches[0])) {
                $rawNum = str_replace(['.', ','], '', $matches[0][0]);
                $numSalary = (float)$rawNum;
                if ($numSalary > 0 && $numSalary < 100) {
                    $numSalary = $numSalary * 1000000;
                }
            }
            if ($numSalary > 0 && $numSalary < (float)$umk->umk_amount && !auth()->user()->hasRole('Super Admin')) {
                $data['status'] = 'inactive';
                $message = '⚠️ Lowongan berhasil disimpan sebagai DRAF (Menunggu Review) karena gaji di bawah UMK 2026 Wilayah ' . $umk->city_district . ' (' . $umk->formatted_umk . '). Memerlukan persetujuan Super Admin sebelum ditayangkan.';
            }
        }

        $job = $this->jobService->createJob($data);
        AuditLog::record('job_created', 'Mempublikasikan lowongan kerja baru: ' . $request->title);

        return redirect()->route('admin.jobs.index')->with('success', $message);
    }

    public function edit($id)
    {
        $job = $this->jobService->getJobById($id);
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(UpdateJobRequest $request, $id)
    {
        $this->jobService->updateJob($id, $request->validated());
        AuditLog::record('job_updated', 'Memperbarui data lowongan kerja: ' . $request->title);

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy($id)
    {
        $this->jobService->deleteJob($id);
        AuditLog::record('job_deleted', 'Menghapus lowongan kerja ID #' . $id);

        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }
}
