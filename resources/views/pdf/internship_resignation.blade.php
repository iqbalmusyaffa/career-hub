<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengunduran Diri - {{ $resignation->user->name }}</title>
    <style>
        @page {
            margin: 25mm 20mm 25mm 20mm;
        }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.6;
            font-size: 11pt;
            background: #ffffff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 16pt;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .company-sub {
            font-size: 9pt;
            color: #64748b;
        }
        .doc-title-container {
            text-align: center;
            margin: 20px 0 25px 0;
        }
        .doc-title {
            font-size: 14pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
            text-decoration: underline;
            margin-bottom: 4px;
        }
        .doc-number {
            font-size: 9.5pt;
            color: #64748b;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 4px 6px;
            vertical-align: top;
            font-size: 10.5pt;
        }
        .data-label {
            width: 28%;
            color: #475569;
            font-weight: 600;
        }
        .data-colon {
            width: 3%;
            text-align: center;
        }
        .data-value {
            width: 69%;
            font-weight: 700;
            color: #0f172a;
        }
        .content-body {
            margin: 15px 0;
            text-align: justify;
            font-size: 10.5pt;
            color: #334155;
        }
        .reason-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #e11d48;
            padding: 10px 14px;
            border-radius: 6px;
            margin: 12px 0;
            font-size: 10pt;
            font-style: italic;
            color: #1e293b;
        }
        .signature-section {
            margin-top: 35px;
            width: 100%;
        }
        .sig-box {
            width: 45%;
            float: left;
            text-align: center;
        }
        .sig-box-right {
            width: 45%;
            float: right;
            text-align: center;
        }
        .sig-header {
            font-size: 9.5pt;
            color: #64748b;
            margin-bottom: 10px;
        }
        .sig-qr-container {
            height: 80px;
            margin: 8px 0;
        }
        .sig-qr {
            width: 75px;
            height: 75px;
            padding: 3px;
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }
        .sig-name {
            font-size: 10.5pt;
            font-weight: 800;
            color: #0f172a;
            text-decoration: underline;
            margin-top: 5px;
        }
        .sig-role {
            font-size: 8.5pt;
            color: #64748b;
        }
        .clear {
            clear: both;
        }
        .security-badge {
            margin-top: 30px;
            padding: 10px;
            background: #f1f5f9;
            border-radius: 8px;
            border: 1px dashed #cbd5e1;
            font-size: 8pt;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>

@php
    $companyName = $resignation->company ? $resignation->company->company_name : ($resignation->application && $resignation->application->job ? $resignation->application->job->company_name : 'PT TALENTFLOW INDONESIA');
    $jobTitle = $resignation->application && $resignation->application->job ? $resignation->application->job->title : 'Peserta Program Magang';
    $location = 'KOTA SURABAYA';
    if ($resignation->application && $resignation->application->job && $resignation->application->job->location) {
        $location = $resignation->application->job->location;
    } elseif ($resignation->company && $resignation->company->city) {
        $location = $resignation->company->city;
    }
    $docNumber = 'SPD/MGN/' . $resignation->created_at->format('Y/m/') . sprintf('%04d', $resignation->id);
    $verificationUrl = route('resignations.verify.public', ['id' => $resignation->id]);
    $qrCodeBase64 = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(90)->errorCorrection('H')->generate($verificationUrl));
@endphp

    <!-- HEADER / KOP SURAT -->
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <div class="company-name">{{ $companyName }}</div>
                <div class="company-sub">Portal Resmi Manajemen Magang & Rekrutmen Terpadu</div>
            </td>
            <td style="width: 30%; text-align: right; vertical-align: middle;">
                <span style="display: inline-block; padding: 4px 10px; background: #e11d48; color: #fff; border-radius: 4px; font-size: 8pt; font-weight: bold; text-transform: uppercase;">
                    Dokumen Resmi
                </span>
            </td>
        </tr>
    </table>

    <!-- JUDUL SURAT -->
    <div class="doc-title-container">
        <div class="doc-title">SURAT PERMOHONAN PENGUNDURAN DIRI MAGANG</div>
        <div class="doc-number">Nomor Registrasi: {{ $docNumber }}</div>
    </div>

    <!-- PEMBUKA -->
    <div class="content-body">
        Yang bertanda tangan di bawah ini:
    </div>

    <!-- IDENTITAS PEMOHON -->
    <table class="data-table">
        <tr>
            <td class="data-label">Nama Lengkap</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $resignation->user->name }}</td>
        </tr>
        <tr>
            <td class="data-label">Email Terdaftar</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $resignation->user->email }}</td>
        </tr>
        <tr>
            <td class="data-label">Posisi Penugasan</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $jobTitle }}</td>
        </tr>
        <tr>
            <td class="data-label">Perusahaan Penempatan</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $companyName }}</td>
        </tr>
        <tr>
            <td class="data-label">Lokasi Penugasan</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ strtoupper($location) }}</td>
        </tr>
        <tr>
            <td class="data-label">Tanggal Efektif Berhenti</td>
            <td class="data-colon">:</td>
            <td class="data-value" style="color: #e11d48;">{{ $resignation->effective_date->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <!-- ISI PERNYATAAN -->
    <div class="content-body">
        Dengan ini mengajukan permohonan pengunduran diri secara resmi dari pelaksanaan Program Magang (Internship) pada posisi dan perusahaan tersebut di atas dengan kategori alasan <strong>{{ $resignation->category_label }}</strong>, dikarenakan:
    </div>

    <!-- KOTAK ALASAN -->
    <div class="reason-box">
        "{{ $resignation->reason_details }}"
    </div>

    @if($resignation->handover_notes)
        <div class="content-body" style="margin-top: 10px;">
            <strong>Catatan Serah Terima Tugas & Dokumen:</strong><br>
            <span style="color: #475569;">{{ $resignation->handover_notes }}</span>
        </div>
    @endif

    <div class="content-body" style="margin-top: 12px;">
        Saya mengucapkan terima kasih yang sebesar-besarnya atas bimbingan, kesempatan berharga, dan pengalaman kerja yang telah diberikan selama masa magang berlangsung. Saya memohon maaf apabila terdapat kekeliruan selama masa penugasan.
    </div>

    <!-- TANDA TANGAN DENGAN QR CODE RESMI -->
    <div class="signature-section">
        <div class="sig-box">
            <div class="sig-header">
                Mengetahui & Menyetujui,<br>
                <strong>TIM HR / PEMBIMBING PERUSAHAAN</strong>
            </div>
            <div class="sig-qr-container">
                <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" class="sig-qr" alt="QR Validasi">
            </div>
            <div class="sig-name">{{ $resignation->reviewer->name ?? 'Human Resources Department' }}</div>
            <div class="sig-role">Verifikasi Digital Perusahaan</div>
        </div>

        <div class="sig-box-right">
            <div class="sig-header">
                Diajukan Secara Sah Oleh,<br>
                <strong>PESERTA MAGANG</strong>
            </div>
            <div class="sig-qr-container">
                <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" class="sig-qr" alt="QR Validasi">
            </div>
            <div class="sig-name">{{ $resignation->user->name }}</div>
            <div class="sig-role">Tanda Tangan Elektronik Sah</div>
        </div>

        <div class="clear"></div>
    </div>

    <!-- KEAMANAN & VERIFIKASI -->
    <div class="security-badge">
        <strong style="color: #0f172a;">KEASLIAN DOKUMEN TERSERTIFIKASI DIGITAL</strong><br>
        Dokumen ini diterbitkan secara elektronik oleh TalentFlow Platform dan memiliki kekuatan hukum yang sah.<br>
        Scan QR Code di atas menggunakan kamera smartphone untuk memvalidasi keaslian surat ini secara real-time.
    </div>

</body>
</html>
