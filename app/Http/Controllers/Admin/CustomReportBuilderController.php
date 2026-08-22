<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\InterviewScorecard;
use App\Models\HeadcountBudget;
use App\Models\CompanyProfile;
use App\Exports\CustomHrReportExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CustomReportBuilderController extends Controller
{
    /**
     * Map of available columns per domain
     */
    protected function getDomainColumns(): array
    {
        return [
            'applications' => [
                'id' => 'ID Lamaran',
                'candidate_name' => 'Nama Pelamar',
                'candidate_email' => 'Email Pelamar',
                'candidate_phone' => 'No. Telepon/WA',
                'candidate_major' => 'Jurusan Pendidikan',
                'job_title' => 'Posisi Lowongan',
                'job_division' => 'Divisi Pekerjaan',
                'company_name' => 'Perusahaan (PT)',
                'match_score' => 'Match Score (%)',
                'status_label' => 'Status Tahapan',
                'created_at_formatted' => 'Tanggal Melamar',
            ],
            'jobs' => [
                'id' => 'ID Lowongan',
                'title' => 'Judul Lowongan',
                'division' => 'Divisi',
                'company_name' => 'Perusahaan (PT)',
                'location' => 'Lokasi Penempatan',
                'work_type' => 'Tipe Kerja',
                'education_level' => 'Syarat Pendidikan',
                'major_requirement' => 'Syarat Jurusan',
                'quota' => 'Kuota Lowongan',
                'applications_count' => 'Total Pelamar',
                'status_label' => 'Status Lowongan',
                'deadline_formatted' => 'Batas Akhir (Deadline)',
            ],
            'scorecards' => [
                'id' => 'ID Scorecard',
                'candidate_name' => 'Nama Kandidat',
                'job_title' => 'Posisi Target',
                'evaluator_name' => 'Nama Pewawancara (HR/User)',
                'technical_score' => 'Skor Teknis (1-5 ⭐)',
                'communication_score' => 'Skor Komunikasi (1-5 ⭐)',
                'problem_solving_score' => 'Skor Problem Solving (1-5 ⭐)',
                'culture_fit_score' => 'Skor Culture Fit (1-5 ⭐)',
                'average_score' => 'Skor Rata-Rata',
                'recommendation' => 'Rekomendasi Akhir',
                'notes' => 'Catatan Evaluasi',
                'created_at_formatted' => 'Tanggal Evaluasi',
            ],
            'headcount' => [
                'id' => 'ID Planning',
                'division' => 'Divisi Target',
                'fiscal_year' => 'Tahun Anggaran (FY)',
                'target_headcount' => 'Target Headcount',
                'actual_hired' => 'Realisasi Hired',
                'progress_percentage' => 'Realisasi (%)',
                'allocated_budget_formatted' => 'Alokasi Anggaran (Rp)',
                'notes' => 'Catatan Strategis',
            ]
        ];
    }

    public function index(Request $request)
    {
        $domain = $request->input('domain', 'applications');
        $allDomainColumns = $this->getDomainColumns();
        $availableColumns = $allDomainColumns[$domain] ?? $allDomainColumns['applications'];

        // Selected columns (default to all if empty)
        $selectedColumnKeys = $request->input('columns', array_keys($availableColumns));
        $selectedColumns = array_intersect_key($availableColumns, array_flip($selectedColumnKeys));

        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $reportData = $this->queryReportData($domain, $startDate, $endDate);

        return view('admin.reports.builder', compact(
            'domain',
            'allDomainColumns',
            'availableColumns',
            'selectedColumnKeys',
            'selectedColumns',
            'startDate',
            'endDate',
            'reportData'
        ));
    }

    public function export(Request $request)
    {
        $domain = $request->input('domain', 'applications');
        $format = $request->input('format', 'excel');
        $allDomainColumns = $this->getDomainColumns();
        $availableColumns = $allDomainColumns[$domain] ?? $allDomainColumns['applications'];

        $selectedColumnKeys = $request->input('columns', array_keys($availableColumns));
        $selectedColumns = array_intersect_key($availableColumns, array_flip($selectedColumnKeys));

        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $reportData = $this->queryReportData($domain, $startDate, $endDate);

        $fileName = 'HR_Report_' . ucfirst($domain) . '_' . date('Ymd_His');

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf', compact('domain', 'selectedColumns', 'reportData', 'startDate', 'endDate'))
                ->setPaper('a4', 'landscape');
            return $pdf->download($fileName . '.pdf');
        }

        $exportFormat = $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX;
        $fileExt = $format === 'csv' ? '.csv' : '.xlsx';

        return Excel::download(
            new CustomHrReportExport($reportData, $selectedColumns),
            $fileName . $fileExt,
            $exportFormat
        );
    }

    protected function queryReportData(string $domain, string $startDate, string $endDate)
    {
        $user = auth()->user();
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $ownerId = $user->id;
        $teamMember = \App\Models\CompanyTeamMember::where('user_id', $user->id)->first();
        if ($teamMember) {
            $ownerId = $teamMember->owner_id;
        }
        $companyProfile = CompanyProfile::where('user_id', $ownerId)->first();
        $companyName = $companyProfile ? $companyProfile->company_name : null;

        if ($domain === 'applications') {
            $query = Application::with(['user.candidateProfile', 'job'])
                ->whereBetween('created_at', [$start, $end]);

            if (!$user->hasRole('Super Admin') && $companyName) {
                $query->whereHas('job', function($j) use ($companyName) {
                    $j->where('company_name', 'LIKE', '%' . $companyName . '%');
                });
            }

            return $query->latest()->get()->map(function($app) {
                $matchScore = $app->job ? $app->job->calculateMatchScore($app->user->candidateProfile) : 0;
                $stVal = is_object($app->status) ? $app->status->value : (string)$app->status;

                return [
                    'id' => $app->id,
                    'candidate_name' => $app->user->name ?? '-',
                    'candidate_email' => $app->user->email ?? '-',
                    'candidate_phone' => $app->user->candidateProfile->phone ?? '-',
                    'candidate_major' => $app->user->candidateProfile->major ?? ($app->user->candidateProfile->last_education ?? '-'),
                    'job_title' => $app->job->title ?? '-',
                    'job_division' => $app->job->division ?? '-',
                    'company_name' => $app->job->company_name ?? '-',
                    'match_score' => $matchScore . '%',
                    'status_label' => is_object($app->status) ? $app->status->label() : strtoupper($stVal),
                    'created_at_formatted' => $app->created_at->format('d/m/Y H:i'),
                ];
            });
        }

        if ($domain === 'jobs') {
            $query = Job::withCount('applications')
                ->whereBetween('created_at', [$start, $end]);

            if (!$user->hasRole('Super Admin') && $companyName) {
                $query->where('company_name', 'LIKE', '%' . $companyName . '%');
            }

            return $query->latest()->get()->map(function($j) {
                return [
                    'id' => $j->id,
                    'title' => $j->title,
                    'division' => $j->division,
                    'company_name' => $j->company_name,
                    'location' => $j->location,
                    'work_type' => $j->work_type,
                    'education_level' => $j->education_level,
                    'major_requirement' => $j->major_requirement ?? 'Semua Jurusan',
                    'quota' => $j->quota ?? 1,
                    'applications_count' => $j->applications_count ?? 0,
                    'status_label' => strtoupper($j->status),
                    'deadline_formatted' => $j->deadline ? Carbon::parse($j->deadline)->format('d/m/Y') : 'Tanpa Deadline',
                ];
            });
        }

        if ($domain === 'scorecards') {
            $query = InterviewScorecard::with(['application.user', 'application.job', 'evaluator'])
                ->whereBetween('created_at', [$start, $end]);

            if (!$user->hasRole('Super Admin') && $companyName) {
                $query->whereHas('application.job', function($j) use ($companyName) {
                    $j->where('company_name', 'LIKE', '%' . $companyName . '%');
                });
            }

            return $query->latest()->get()->map(function($sc) {
                return [
                    'id' => $sc->id,
                    'candidate_name' => $sc->application->user->name ?? '-',
                    'job_title' => $sc->application->job->title ?? '-',
                    'evaluator_name' => $sc->evaluator->name ?? '-',
                    'technical_score' => $sc->technical_score . ' / 5',
                    'communication_score' => $sc->communication_score . ' / 5',
                    'problem_solving_score' => $sc->problem_solving_score . ' / 5',
                    'culture_fit_score' => $sc->culture_fit_score . ' / 5',
                    'average_score' => number_format($sc->average_score, 1) . ' ⭐',
                    'recommendation' => $sc->recommendation,
                    'notes' => $sc->notes ?? '-',
                    'created_at_formatted' => $sc->created_at->format('d/m/Y H:i'),
                ];
            });
        }

        if ($domain === 'headcount') {
            $query = HeadcountBudget::whereBetween('created_at', [$start, $end]);

            if (!$user->hasRole('Super Admin')) {
                $query->where('company_user_id', $ownerId);
            }

            $divisionJobs = Job::all()->groupBy('division');

            return $query->latest()->get()->map(function($hb) use ($divisionJobs) {
                $jobs = $divisionJobs->get($hb->division, collect());
                $jobIds = $jobs->pluck('id');
                $actualHired = Application::whereIn('job_id', $jobIds)->whereIn('status', ['accepted', 'hired'])->count();
                $pct = $hb->target_headcount > 0 ? round(($actualHired / $hb->target_headcount) * 100, 1) : 0;

                return [
                    'id' => $hb->id,
                    'division' => $hb->division,
                    'fiscal_year' => $hb->fiscal_year,
                    'target_headcount' => $hb->target_headcount,
                    'actual_hired' => $actualHired,
                    'progress_percentage' => $pct . '%',
                    'allocated_budget_formatted' => 'Rp ' . number_format($hb->allocated_budget, 0, ',', '.'),
                    'notes' => $hb->notes ?? '-',
                ];
            });
        }

        return collect();
    }
}
