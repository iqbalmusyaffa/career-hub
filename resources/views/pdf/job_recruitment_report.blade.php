<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekrutmen - {{ $job->title }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; margin: 0; padding: 20px; }
        .header { border-b: 2px solid #2563eb; padding-bottom: 12px; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: bold; color: #0f172a; }
        .report-title { font-size: 14px; color: #2563eb; font-weight: bold; margin-top: 4px; }
        .meta-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .meta-table td { padding: 4px 8px; font-size: 10px; }
        .meta-label { font-weight: bold; color: #64748b; width: 120px; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th { background-color: #f1f5f9; color: #334155; font-weight: bold; text-transform: uppercase; font-size: 9px; padding: 8px; border: 1px solid #cbd5e1; text-align: left; }
        .data-table td { padding: 8px; border: 1px solid #e2e8f0; font-size: 10px; }
        .badge { padding: 3px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; text-transform: uppercase; display: inline-block; }
        .badge-accepted { background-color: #dcfce7; color: #166534; }
        .badge-rejected { background-color: #ffe4e6; color: #9f1239; }
        .badge-pending { background-color: #fef3c7; color: #92400e; }
        .badge-interview { background-color: #dbeafe; color: #1e40af; }
        .footer { margin-top: 30px; font-size: 9px; color: #94a3b8; text-align: right; border-top: 1px solid #e2e8f0; padding-top: 8px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="company-name">{{ $job->companyProfile->company_name ?? 'TalentFlow Enterprise' }}</div>
        <div class="report-title">LAPORAN REKAPITULASI HASIL REKRUTMEN KANDIDAT</div>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Posisi Lowongan:</td>
            <td><strong>{{ $job->title }}</strong> (Divisi: {{ $job->division ?? '-' }})</td>
            <td class="meta-label">Tanggal Cetak:</td>
            <td>{{ date('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="meta-label">Lokasi & Tipe:</td>
            <td>{{ $job->location }} ({{ $job->work_type }})</td>
            <td class="meta-label">Total Pelamar:</td>
            <td><strong>{{ $job->applications->count() }} Orang</strong> (Kuota: {{ $job->quota ?? 'Tak Terbatas' }})</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Nama Pelamar</th>
                <th>Email / No. HP</th>
                <th>Pendidikan</th>
                <th style="width: 80px;">Skor Tes Online</th>
                <th style="width: 80px;">Rating HR (1-5★)</th>
                <th style="width: 80px;">Nilai Teknis</th>
                <th style="width: 90px;">Status Lamaran</th>
                <th style="width: 80px;">Tgl Melamar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($job->applications as $index => $app)
                @php
                    $profile = $app->user->candidateProfile;
                    $test = $app->testResult;
                    $evals = $app->evaluations;
                    $avgRating = $evals->count() > 0 ? round($evals->avg('rating'), 1) . ' ★' : '-';
                    $avgTech = $evals->count() > 0 ? round($evals->avg('technical_score'), 1) . ' / 100' : '-';
                    $statusStr = is_object($app->status) ? $app->status->value : (string) $app->status;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $app->user->name ?? '-' }}</strong></td>
                    <td>{{ $app->user->email ?? '-' }}<br><small style="color: #64748b;">{{ $profile->phone ?? '-' }}</small></td>
                    <td>{{ $profile->last_education ?? '-' }}</td>
                    <td>
                        @if($test)
                            <strong>{{ $test->score }}%</strong>
                            <br><small style="color: {{ $test->passed ? '#166534' : '#9f1239' }};">{{ $test->passed ? 'Lolos KKM' : 'Gagal' }}</small>
                        @else
                            <span style="color: #94a3b8;">Belum Tes</span>
                        @endif
                    </td>
                    <td style="text-align: center; color: #d97706; font-weight: bold;">{{ $avgRating }}</td>
                    <td style="text-align: center;">{{ $avgTech }}</td>
                    <td>
                        <span class="badge badge-{{ $statusStr }}">
                            {{ strtoupper($statusStr) }}
                        </span>
                    </td>
                    <td>{{ $app->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #94a3b8; padding: 20px;">
                        Belum ada pelamar terdaftar pada lowongan ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini diterbitkan secara otomatis oleh TalentFlow Enterprise System pada {{ date('d/m/Y H:i:s') }}
    </div>

</body>
</html>
