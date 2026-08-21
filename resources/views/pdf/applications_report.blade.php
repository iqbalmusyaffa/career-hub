<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Pelamar Pekerjaan</title>
    <style>
        @page {
            margin: 20px 25px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #333333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            border: 1px solid #1d4ed8;
        }
        td {
            padding: 6px;
            border: 1px solid #e2e8f0;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
        }
        .badge-score {
            background-color: #dbeafe;
            color: #1e40af;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">Laporan Rekapitulasi Pelamar Pekerjaan</div>
        <div class="subtitle">Dicetak pada: {{ date('d F Y, H:i') }} WIB | TalentFlow Career System</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 20%;">Nama Pelamar</th>
                <th style="width: 20%;">Email & No. HP</th>
                <th style="width: 22%;">Posisi & Divisi</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 10%;">Match Score</th>
                <th style="width: 12%;">Tgl Melamar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $index => $app)
                @php
                    $matchScore = $app->job ? $app->job->calculateMatchScore($app->user->candidateProfile) : 0;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $app->user->name ?? '-' }}</strong></td>
                    <td>
                        {{ $app->user->email ?? '-' }}<br>
                        <span style="color: #64748b;">{{ $app->user->candidateProfile->phone ?? '-' }}</span>
                    </td>
                    <td>
                        <strong>{{ $app->job->title ?? '-' }}</strong><br>
                        <span style="color: #2563eb;">{{ $app->job->division ?? '-' }}</span>
                    </td>
                    <td>
                        <strong>{{ ucfirst(is_object($app->status) ? ($app->status->value ?? (string)$app->status) : (string)$app->status) }}</strong>
                    </td>
                    <td>
                        <span class="badge badge-score">{{ $matchScore }}% Match</span>
                    </td>
                    <td>{{ $app->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
