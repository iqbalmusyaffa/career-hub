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
        $user = auth()->user();
        $companyProfile = null;
        if ($user) {
            $companyProfile = $user->currentCompanyProfile();
            if (!$companyProfile && !$user->hasRole('Super Admin')) {
                $companyProfile = \App\Models\CompanyProfile::create([
                    'user_id' => $user->id,
                    'company_name' => $user->name ? 'PT ' . $user->name : 'PT Perusahaan Mitra',
                ]);
            }
        }

        $availableBatches = \App\Models\InternshipPeriod::whereNotNull('period_name')
            ->pluck('period_name')
            ->merge(\App\Models\Job::whereNotNull('batch')->where('batch', '!=', '')->pluck('batch'))
            ->unique()
            ->values();

        return view('admin.jobs.index', compact('jobs', 'companyProfile', 'availableBatches'));
    }

    public function create()
    {
        $user = auth()->user();
        $companyProfile = null;
        if ($user) {
            $companyProfile = $user->currentCompanyProfile();
            if (!$companyProfile && !$user->hasRole('Super Admin')) {
                $companyProfile = \App\Models\CompanyProfile::create([
                    'user_id' => $user->id,
                    'company_name' => $user->name ? 'PT ' . $user->name : 'PT Perusahaan Mitra',
                ]);
            }
        }

        $availableBatches = \App\Models\InternshipPeriod::whereNotNull('period_name')
            ->pluck('period_name')
            ->merge(\App\Models\Job::whereNotNull('batch')->where('batch', '!=', '')->pluck('batch'))
            ->unique()
            ->values();

        return view('admin.jobs.create', compact('companyProfile', 'availableBatches'));
    }

    public function store(StoreJobRequest $request)
    {
        $data = $request->validated();
        $message = 'Lowongan kerja berhasil dipublikasikan!';

        // Tetapkan nama perusahaan otomatis sesuai akun HR / Company Owner yang sedang login
        $user = auth()->user();
        if (!$user->hasRole('Super Admin')) {
            $companyProfile = $user->currentCompanyProfile();
            if (!$companyProfile) {
                $companyProfile = \App\Models\CompanyProfile::create([
                    'user_id' => $user->id,
                    'company_name' => $user->name ? 'PT ' . $user->name : 'PT Perusahaan Mitra',
                ]);
            }
            $data['company_name'] = $companyProfile->company_name;
        } elseif (empty($data['company_name'])) {
            $data['company_name'] = config('app.name', 'CareerHub');
        }

        // Moderasi Otomatis untuk Lowongan Full-Time di Bawah UMK
        $umk = \App\Models\UmkReference::findByLocation($data['location'] ?? '');
        $salaryLower = strtolower($data['salary'] ?? '');
        $isExplicitUmk = str_contains($salaryLower, 'sesuai umk') || str_contains($salaryLower, 'standar umk') || str_contains($salaryLower, 'mengikuti umk');

        if ($umk && strtolower($data['work_type'] ?? '') === 'full-time' && !$isExplicitUmk) {
            $cleanSalary = preg_replace('/202[0-9]/', '', $salaryLower);
            preg_match_all('/\d[\d\.\,]*/', $cleanSalary, $matches);
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
        AuditLog::record('job_created', "Mempublikasikan lowongan kerja baru: {$request->title} untuk perusahaan {$data['company_name']}");

        return redirect()->route('admin.jobs.index')->with('success', $message);
    }

    public function edit($id)
    {
        $job = $this->jobService->getJobById($id);
        $user = auth()->user();
        $companyProfile = null;
        if ($user) {
            $companyProfile = $user->currentCompanyProfile();
            if (!$companyProfile && !$user->hasRole('Super Admin')) {
                $companyProfile = \App\Models\CompanyProfile::create([
                    'user_id' => $user->id,
                    'company_name' => $user->name ? 'PT ' . $user->name : 'PT Perusahaan Mitra',
                ]);
            }
        }

        $availableBatches = \App\Models\InternshipPeriod::whereNotNull('period_name')
            ->pluck('period_name')
            ->merge(\App\Models\Job::whereNotNull('batch')->where('batch', '!=', '')->pluck('batch'))
            ->unique()
            ->values();

        return view('admin.jobs.edit', compact('job', 'companyProfile', 'availableBatches'));
    }

    public function update(UpdateJobRequest $request, $id)
    {
        $data = $request->validated();
        $user = auth()->user();

        // Kunci nama perusahaan ke profil HR/Owner login jika bukan Super Admin
        if (!$user->hasRole('Super Admin')) {
            $companyProfile = $user->currentCompanyProfile();
            if ($companyProfile) {
                $data['company_name'] = $companyProfile->company_name;
            }
        }

        $this->jobService->updateJob($id, $data);
        AuditLog::record('job_updated', 'Memperbarui data lowongan kerja: ' . $request->title);

        return redirect()->route('admin.jobs.index')->with('success', 'Lowongan kerja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->jobService->deleteJob($id);
        AuditLog::record('job_deleted', 'Menghapus lowongan kerja ID #' . $id);

        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }
}
