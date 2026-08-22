<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>HR Custom Executive Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 0;
            padding: 15px;
        }
        .header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header table {
            width: 100%;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
        }
        .meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 9px;
        }
        .meta-box table {
            width: 100%;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .report-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #0f172a;
        }
        .report-table td {
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            font-size: 9px;
        }
        .report-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .footer {
            margin-top: 30px;
            width: 100%;
            font-size: 8px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="title">TALENTFLOW EXECUTIVE REPORT</div>
                    <div class="subtitle">Laporan Rekrutmen & Analytics Kustom Perusahaan</div>
                </td>
                <td style="text-align: right;">
                    <div style="font-weight: bold; font-size: 11px;">Domain: {{ strtoupper($domain) }}</div>
                    <div class="subtitle">Dicetak pada: {{ date('d M Y, H:i') }} WIB</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="meta-box">
        <table>
            <tr>
                <td><strong>Periode Laporan:</strong> {{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</td>
                <td><strong>Total Data:</strong> {{ count($reportData) }} Baris Record</td>
                <td style="text-align: right;"><strong>Total Kolom:</strong> {{ count($selectedColumns) }} Kolom Terpilih</td>
            </tr>
        </table>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                @foreach($selectedColumns as $colKey => $colLabel)
                    <th>{{ $colLabel }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $row)
                <tr>
                    @foreach($selectedColumns as $colKey => $colLabel)
                        <td>{{ data_get($row, $colKey, '-') }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($selectedColumns) }}" style="text-align: center; color: #94a3b8; padding: 20px;">
                        Tidak ada data yang tersedia untuk filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td>Dokumen resmi ini di-generate secara otomatis oleh Sistem Rekrutmen TalentFlow.</td>
                <td style="text-align: right;">Halaman 1 dari 1</td>
            </tr>
        </table>
    </div>

</body>
</html>
