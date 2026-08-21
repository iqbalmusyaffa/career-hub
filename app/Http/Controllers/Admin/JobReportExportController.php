<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobReportExportController extends Controller
{
    /**
     * Export recruitment report for specific Job in CSV / Excel format.
     */
    public function exportExcel(Job $job)
    {
        $job->load(['applications.user.candidateProfile', 'applications.testResult', 'applications.evaluations']);
        $filename = 'Laporan_Rekrutmen_' . \Illuminate\Support\Str::slug($job->title) . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($job) {
            $file = fopen('php://output', 'w');
            
            // Header Row
            fputcsv($file, [
                'ID Lamaran',
                'Nama Kandidat',
                'Email',
                'No. HP',
                'Pendidikan Terakhir',
                'Posisi Lowongan',
                'Skor Tes Online (%)',
                'Status Tes',
                'Rata-rata Rating HR (1-5★)',
                'Rata-rata Nilai Teknis (1-100)',
                'Status Lamaran',
                'Tanggal Melamar',
            ]);

            foreach ($job->applications as $app) {
                $profile = $app->user->candidateProfile;
                $test = $app->testResult;
                $evals = $app->evaluations;

                $avgRating = $evals->count() > 0 ? round($evals->avg('rating'), 1) : '-';
                $avgTech = $evals->count() > 0 ? round($evals->avg('technical_score'), 1) : '-';

                fputcsv($file, [
                    $app->id,
                    $app->user->name ?? '-',
                    $app->user->email ?? '-',
                    $profile->phone ?? '-',
                    $profile->last_education ?? '-',
                    $job->title,
                    $test ? $test->score . '%' : 'Belum Tes',
                    $test ? ($test->passed ? 'Lolos KKM' : 'Gagal KKM') : '-',
                    $avgRating,
                    $avgTech,
                    is_object($app->status) ? $app->status->value : $app->status,
                    $app->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Export recruitment report for specific Job in PDF format.
     */
    public function exportPdf(Job $job)
    {
        $job->load(['companyProfile', 'applications.user.candidateProfile', 'applications.testResult', 'applications.evaluations']);

        $pdf = Pdf::loadView('pdf.job_recruitment_report', compact('job'))
            ->setPaper('a4', 'landscape');

        $filename = 'Laporan_Rekrutmen_' . \Illuminate\Support\Str::slug($job->title) . '_' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}
