<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Penawaran Kerja (Offer Letter)</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 40px 50px;
            font-size: 13px;
            line-height: 1.6;
        }
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header table {
            width: 100%;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
        }
        .doc-title {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            color: #1f2937;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .meta-table td {
            vertical-align: top;
            padding: 3px 0;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin-top: 20px;
            margin-bottom: 12px;
        }
        .info-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            padding: 6px 4px;
        }
        .info-label {
            font-weight: bold;
            color: #475569;
            width: 35%;
        }
        .signature-table {
            width: 100%;
            margin-top: 50px;
        }
        .signature-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #1f2937;
            width: 70%;
            margin: 60px auto 5px auto;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="company-name">{{ $application->job->company_name ?? 'TALENTFLOW PARTNER' }}</div>
                    <div style="font-size: 11px; color: #64748b;">Surat Penawaran Pekerjaan Resmi (Job Offer Letter)</div>
                </td>
                <td class="doc-title">
                    SURAT PENAWARAN KERJA
                    <div style="font-size: 10px; font-weight: normal; color: #64748b;">Ref No: OL/{{ $offerLetter->id }}/{{ date('Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Date & Candidate Details -->
    <table class="meta-table">
        <tr>
            <td style="width: 60%;">
                <strong>Kepada Yth,</strong><br>
                <strong style="font-size: 15px; color: #0f172a;">{{ $application->user->name }}</strong><br>
                Email: {{ $application->user->email }}<br>
                No. HP: {{ $application->user->candidateProfile->phone ?? '-' }}
            </td>
            <td style="text-align: right;">
                <strong>Tanggal Terbit:</strong> {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>
                <strong>Batas Konfirmasi:</strong> {{ $offerLetter->expiration_date ? \Carbon\Carbon::parse($offerLetter->expiration_date)->locale('id')->translatedFormat('d F Y') : '7 Hari Kerja' }}
            </td>
        </tr>
    </table>

    <!-- Body Content -->
    <p>Dengan hormat,</p>
    <p>
        Sehubungan dengan proses seleksi dan wawancara yang telah Anda jalani, kami dari&nbsp;<strong>{{ $application->job->company_name }}</strong>&nbsp;merasa sangat terkesan dengan kualifikasi dan potensi yang Anda miliki. Oleh karena itu, kami dengan bangga menawarkan posisi pekerjaan sebagai&nbsp;<strong>{{ $offerLetter->position_title }}</strong>&nbsp;di perusahaan kami.
    </p>

    @php
        $isInternship = Str::contains(strtolower($application->job->work_type ?? ''), ['intern', 'magang']) || Str::contains(strtolower($offerLetter->position_title ?? ''), ['intern', 'magang']);
        $rawSal = trim((string)$offerLetter->offered_salary);
        
        if (str_contains($rawSal, '-') || str_contains(strtolower($rawSal), 's/d') || str_contains(strtolower($rawSal), 'sampai')) {
            $parts = preg_split('/(-|s\/d|sampai)/i', $rawSal);
            $formattedParts = [];
            foreach ($parts as $p) {
                $pClean = preg_replace('/[^0-9]/', '', $p);
                if ($pClean && is_numeric($pClean)) {
                    $formattedParts[] = 'Rp ' . number_format((float)$pClean, 0, ',', '.');
                } else {
                    $formattedParts[] = trim($p);
                }
            }
            $salaryFormatted = implode(' - ', $formattedParts);
        } else {
            $pClean = preg_replace('/[^0-9]/', '', $rawSal);
            if ($pClean && is_numeric($pClean) && strlen($pClean) >= 4) {
                $salaryFormatted = 'Rp ' . number_format((float)$pClean, 0, ',', '.');
            } else {
                $salaryFormatted = $rawSal;
                if (!str_starts_with(strtoupper($salaryFormatted), 'RP')) {
                    $salaryFormatted = 'Rp ' . $salaryFormatted;
                }
            }
        }

        if (!Str::contains(strtolower($salaryFormatted), ['bulan', 'hari', 'jam', 'proyek', 'tahun', 'bln'])) {
            $salaryFormatted .= ' / bulan';
        }
    @endphp

    <div class="section-title">RINCIAN PENAWARAN PEKERJAAN</div>
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="info-label">{{ $isInternship ? 'Posisi / Program Magang:' : 'Posisi / Jabatan:' }}</td>
                <td><strong>{{ $offerLetter->position_title }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">{{ $isInternship ? 'Uang Saku (Stipend) Ditawarkan:' : 'Gaji Yang Ditawarkan:' }}</td>
                <td><strong style="color: #15803d; font-size: 15px;">{{ $salaryFormatted }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">{{ $isInternship ? 'Tanggal Mulai Magang:' : 'Tanggal Mulai Bekerja:' }}</td>
                <td><strong>{{ $offerLetter->start_date ? \Carbon\Carbon::parse($offerLetter->start_date)->locale('id')->translatedFormat('d F Y') : '-' }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">Lokasi Penempatan:</td>
                <td>{{ $offerLetter->work_location ?? $application->job->location }}</td>
            </tr>
        </table>
    </div>

    @if($offerLetter->benefits_summary)
        <div class="section-title">FASILITAS & BENEFIT PERUSAHAAN</div>
        <p>{{ $offerLetter->benefits_summary }}</p>
    @endif

    @if($offerLetter->additional_notes)
        <div class="section-title">CATATAN KHUSUS & KETENTUAN</div>
        <p>{{ $offerLetter->additional_notes }}</p>
    @endif

    <p style="margin-top: 25px;">
        Harap melakukan konfirmasi penerimaan surat penawaran kerja ini sebelum tanggal batas waktu di atas. Kami sangat berharap Anda dapat bergabung dan tumbuh bersama tim kami.
    </p>

    <!-- Signatures -->
    <table class="signature-table">
        <tr>
            <td>
                <strong>Hormat Kami,</strong><br>
                <strong>{{ $application->job->company_name }}</strong>
                <div class="signature-line"></div>
                <strong>HR Manager / Management</strong>
            </td>
            <td>
                <strong>Persetujuan Kandidat,</strong><br>
                (Tanda Tangan & Nama Terang)
                <div class="signature-line"></div>
                <strong>{{ $application->user->name }}</strong>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini diterbitkan secara sah dan resmi melalui Platform Rekrutmen Digital Web Karir TalentFlow.
    </div>

</body>
</html>
