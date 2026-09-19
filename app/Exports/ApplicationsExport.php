<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ApplicationsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        $query = Application::with(['user.candidateProfile', 'job'])->latest();
        $user = auth()->user();

        if ($user && !$user->hasRole('Super Admin')) {
            $companyProfile = $user->currentCompanyProfile();
            $companyName = $companyProfile ? $companyProfile->company_name : null;

            if ($companyName) {
                $query->whereHas('job', function($j) use ($companyName) {
                    $j->where('company_name', 'LIKE', '%' . $companyName . '%');
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID Lamaran',
            'Nama Kandidat',
            'Email',
            'No. HP',
            'Posisi Dilamar',
            'Divisi',
            'Status Lamaran',
            'Match Score (%)',
            'Tanggal Melamar',
        ];
    }

    public function map($app): array
    {
        $matchScore = $app->job ? $app->job->calculateMatchScore($app->user->candidateProfile) : 0;

        $statusStr = is_object($app->status) ? ($app->status->value ?? (string)$app->status) : (string)$app->status;

        return [
            $app->id,
            $app->user->name ?? '-',
            $app->user->email ?? '-',
            $app->user->candidateProfile->phone ?? '-',
            $app->job->title ?? '-',
            $app->job->division ?? '-',
            ucfirst($statusStr),
            $matchScore . '%',
            $app->created_at->format('d/m/Y H:i'),
        ];
    }
}
