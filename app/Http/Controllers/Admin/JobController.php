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
        $job = $this->jobService->createJob($request->validated());
        AuditLog::record('job_created', 'Mempublikasikan lowongan kerja baru: ' . $request->title);

        return redirect()->route('admin.jobs.index')->with('success', 'Job created successfully.');
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
