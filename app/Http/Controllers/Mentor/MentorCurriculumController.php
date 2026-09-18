<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\CurriculumMaterial;
use App\Models\InternshipCurriculum;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MentorCurriculumController extends Controller
{
    /**
     * Display a listing of the internship curriculums.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $companyProfile = $user->currentCompanyProfile();

        $query = InternshipCurriculum::with(['job', 'company', 'materials', 'creator'])
            ->withCount('materials');

        if (!$isSuperAdmin) {
            $companyId = $companyProfile ? $companyProfile->id : 1;
            $query->where('company_id', $companyId);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('batch', 'like', "%{$search}%")
                    ->orWhereHas('job', function ($jq) use ($search) {
                        $jq->where('title', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        if ($request->filled('batch')) {
            $query->where('batch', $request->batch);
        }

        $curriculums = $query->latest()->paginate(10)->withQueryString();

        // Get jobs for filter
        $jobsQuery = Job::query();
        if (!$isSuperAdmin && $companyProfile) {
            $jobsQuery->where('company_name', $companyProfile->company_name);
        }
        $availableJobs = $jobsQuery->orderBy('title')->get(['id', 'title', 'company_name', 'batch']);
        $companyId = $companyProfile ? $companyProfile->id : null;
        $availableBatches = \App\Models\InternshipBatch::getActiveBatches($companyId);

        return view('mentor.curriculums.index', compact('curriculums', 'availableJobs', 'availableBatches', 'isSuperAdmin'));
    }

    /**
     * Show the form for creating a new curriculum.
     */
    public function create()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $companyProfile = $user->currentCompanyProfile();

        $jobsQuery = Job::query();
        if (!$isSuperAdmin && $companyProfile) {
            $jobsQuery->where('company_name', $companyProfile->company_name);
        }
        $availableJobs = $jobsQuery->orderBy('title')->get(['id', 'title', 'company_name', 'batch']);

        $companyId = $companyProfile ? $companyProfile->id : null;
        $availableBatches = \App\Models\InternshipBatch::getActiveBatches($companyId);

        $curriculum = new InternshipCurriculum();
        $initialMaterials = [
            [
                'sequence' => 1,
                'title' => 'Bulan 1: Orientasi, Setup Lingkungan Kerja & Workflow',
                'description' => 'Pengenalan arsitektur proyek, konfigurasi tools/repository (Git, Docker, IDE), dan implementasi task dasar.',
                'competencies' => ['Version Control (Git Workflow)', 'Code Standard & Formatting', 'Pemahaman Business Flow'],
                'learning_links' => [
                    ['title' => 'Panduan Git & Workflow Repository', 'url' => 'https://git-scm.com/doc'],
                    ['title' => 'Standar Kode & Best Practices', 'url' => 'https://github.com']
                ],
            ],
            [
                'sequence' => 2,
                'title' => 'Bulan 2: Core Development & Integrasi Modul',
                'description' => 'Pengembangan fitur utama, perancangan database/API, dan integrasi komponen front-end dengan back-end.',
                'competencies' => ['Database Query & ORM', 'RESTful API Development', 'Component UI Implementation'],
                'learning_links' => [
                    ['title' => 'Dokumentasi REST API & Model Eloquent', 'url' => 'https://laravel.com/docs']
                ],
            ],
            [
                'sequence' => 3,
                'title' => 'Bulan 3: Testing, Code Review & Debugging',
                'description' => 'Pelaksanaan unit/integration testing, penanganan kendala teknis (bug fixing), dan optimasi performa.',
                'competencies' => ['Unit Testing & QA', 'Refactoring & Clean Architecture', 'Error Tracking & Logging'],
                'learning_links' => [
                    ['title' => 'Panduan Unit Testing & QA Checklist', 'url' => 'https://phpunit.de']
                ],
            ],
            [
                'sequence' => 4,
                'title' => 'Bulan 4: Optimasi, Deployment & Final Project Presentation',
                'description' => 'Penyempurnaan sistem, dokumentasi teknis, dan evaluasi hasil proyek di hadapan tim Mentor.',
                'competencies' => ['CI/CD & Deployment', 'Technical Documentation', 'Presentation & Soft Skills'],
                'learning_links' => [
                    ['title' => 'Template Dokumen Laporan Akhir Magang', 'url' => 'https://docs.google.com']
                ],
            ],
        ];

        return view('mentor.curriculums.form', [
            'isEdit' => false,
            'curriculum' => $curriculum,
            'availableJobs' => $availableJobs,
            'availableBatches' => $availableBatches,
            'initialMaterials' => $initialMaterials,
        ]);
    }

    /**
     * Store a newly created curriculum in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $companyProfile = $user->currentCompanyProfile();

        $request->validate([
            'title' => 'required|string|max:255',
            'job_id' => 'nullable|exists:job_postings,id',
            'batch' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'materials_json' => 'required|string',
        ]);

        $materials = json_decode($request->materials_json, true);
        if (!is_array($materials) || empty($materials)) {
            return back()->withInput()->with('error', 'Silabus materi minimal harus memiliki 1 modul pembelajaran.');
        }

        $companyId = $companyProfile ? $companyProfile->id : 1;
        if ($request->filled('job_id')) {
            $job = Job::find($request->job_id);
            if ($job && $job->companyProfile) {
                $companyId = $job->companyProfile->id;
            }
        }

        DB::beginTransaction();
        try {
            if ($request->filled('batch')) {
                \App\Models\InternshipBatch::findOrCreateByName($request->batch, $companyId);
            }

            $curriculum = InternshipCurriculum::create([
                'company_id' => $companyId,
                'job_id' => $request->job_id,
                'batch' => $request->batch,
                'title' => $request->title,
                'description' => $request->description,
                'created_by' => $user->id,
            ]);

            foreach ($materials as $index => $mat) {
                $competencies = isset($mat['competencies']) && is_array($mat['competencies']) ? $mat['competencies'] : [];
                $learningLinks = isset($mat['learning_links']) && is_array($mat['learning_links']) ? array_values(array_filter($mat['learning_links'], fn($l) => !empty($l['url']))) : [];

                CurriculumMaterial::create([
                    'internship_curriculum_id' => $curriculum->id,
                    'sequence' => $mat['sequence'] ?? ($index + 1),
                    'title' => $mat['title'] ?? ('Modul ' . ($index + 1)),
                    'description' => $mat['description'] ?? null,
                    'competencies' => $competencies,
                    'learning_links' => $learningLinks,
                ]);
            }

            DB::commit();
            return redirect()->route('mentor.curriculums.index')
                ->with('success', 'Silabus Kurikulum & Materi Pembelajaran berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan kurikulum: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified curriculum.
     */
    public function edit($id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $companyProfile = $user->currentCompanyProfile();

        $curriculum = InternshipCurriculum::with('materials')->findOrFail($id);

        if (!$isSuperAdmin && $companyProfile && $curriculum->company_id !== $companyProfile->id) {
            abort(403, 'Anda tidak memiliki otorisasi untuk mengubah kurikulum ini.');
        }

        $jobsQuery = Job::query();
        if (!$isSuperAdmin && $companyProfile) {
            $jobsQuery->where('company_name', $companyProfile->company_name);
        }
        $availableJobs = $jobsQuery->orderBy('title')->get(['id', 'title', 'company_name', 'batch']);

        $initialMaterials = $curriculum->materials->map(function ($m) {
            return [
                'id' => $m->id,
                'sequence' => $m->sequence,
                'title' => $m->title,
                'description' => $m->description ?? '',
                'competencies' => $m->competencies ?? [],
                'learning_links' => $m->learning_links ?? [],
            ];
        })->toArray();

        $companyId = $companyProfile ? $companyProfile->id : null;
        $availableBatches = \App\Models\InternshipBatch::getActiveBatches($companyId);

        return view('mentor.curriculums.form', [
            'isEdit' => true,
            'curriculum' => $curriculum,
            'availableJobs' => $availableJobs,
            'availableBatches' => $availableBatches,
            'initialMaterials' => $initialMaterials,
        ]);
    }

    /**
     * Update the specified curriculum in storage.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $companyProfile = $user->currentCompanyProfile();

        $curriculum = InternshipCurriculum::findOrFail($id);

        if (!$isSuperAdmin && $companyProfile && $curriculum->company_id !== $companyProfile->id) {
            abort(403, 'Anda tidak memiliki otorisasi untuk mengubah kurikulum ini.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'job_id' => 'nullable|exists:job_postings,id',
            'batch' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'materials_json' => 'required|string',
        ]);

        $materials = json_decode($request->materials_json, true);
        if (!is_array($materials) || empty($materials)) {
            return back()->withInput()->with('error', 'Silabus materi minimal harus memiliki 1 modul pembelajaran.');
        }

        DB::beginTransaction();
        try {
            if ($request->filled('batch')) {
                \App\Models\InternshipBatch::findOrCreateByName($request->batch, $curriculum->company_id);
            }

            $curriculum->update([
                'job_id' => $request->job_id,
                'batch' => $request->batch,
                'title' => $request->title,
                'description' => $request->description,
            ]);

            // Re-sync materials
            CurriculumMaterial::where('internship_curriculum_id', $curriculum->id)->delete();

            foreach ($materials as $index => $mat) {
                $competencies = isset($mat['competencies']) && is_array($mat['competencies']) ? $mat['competencies'] : [];
                $learningLinks = isset($mat['learning_links']) && is_array($mat['learning_links']) ? array_values(array_filter($mat['learning_links'], fn($l) => !empty($l['url']))) : [];

                CurriculumMaterial::create([
                    'internship_curriculum_id' => $curriculum->id,
                    'sequence' => $mat['sequence'] ?? ($index + 1),
                    'title' => $mat['title'] ?? ('Modul ' . ($index + 1)),
                    'description' => $mat['description'] ?? null,
                    'competencies' => $competencies,
                    'learning_links' => $learningLinks,
                ]);
            }

            DB::commit();
            return redirect()->route('mentor.curriculums.index')
                ->with('success', 'Kurikulum & Materi Pembelajaran berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui kurikulum: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified curriculum from storage.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $companyProfile = $user->currentCompanyProfile();

        $curriculum = InternshipCurriculum::findOrFail($id);

        if (!$isSuperAdmin && $companyProfile && $curriculum->company_id !== $companyProfile->id) {
            abort(403, 'Anda tidak memiliki otorisasi untuk menghapus kurikulum ini.');
        }

        $curriculum->delete();

        return redirect()->route('mentor.curriculums.index')
            ->with('success', 'Silabus Kurikulum & Materi berhasil dihapus.');
    }
}
