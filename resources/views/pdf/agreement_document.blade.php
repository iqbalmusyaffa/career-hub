<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $agreement->title }}</title>
    <style>
        @page {
            margin: 15px 25px 15px 25px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 7.2pt;
            line-height: 1.28;
            color: #1e293b;
            background: #ffffff;
        }
        .top-bar {
            height: 3px;
            background: #0f172a;
            margin-bottom: 6px;
            border-radius: 2px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1.5px solid #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .header-title {
            margin: 0;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #0f172a;
            line-height: 1.15;
        }
        .doc-badge {
            display: inline-block;
            margin-top: 2px;
            padding: 1px 6px;
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            font-size: 6.8pt;
            font-weight: bold;
        }
        .qr-download-box {
            width: 60px;
            text-align: right;
            vertical-align: middle;
        }
        .qr-download-img {
            width: 44px;
            height: 44px;
            border: 1px solid #cbd5e1;
            padding: 1.5px;
            background: #ffffff;
            border-radius: 3px;
            display: block;
            margin-left: auto;
        }
        .qr-download-text {
            font-size: 4.5pt;
            color: #64748b;
            font-weight: bold;
            text-align: center;
            margin-top: 1px;
            letter-spacing: 0.2px;
        }
        .meta-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 4px 8px;
            margin-bottom: 6px;
        }
        .meta-table {
            width: 100%;
            font-size: 6.9pt;
            border-collapse: collapse;
        }
        .meta-table td {
            vertical-align: top;
            padding: 1px 0;
        }
        .meta-label {
            width: 150px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            font-size: 6.5pt;
            letter-spacing: 0.2px;
        }
        .meta-colon {
            width: 10px;
            text-align: center;
            font-weight: bold;
            color: #64748b;
        }
        .meta-val {
            color: #0f172a;
            font-weight: 500;
        }
        .section-title {
            font-weight: bold;
            font-size: 7.2pt;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin: 4px 0 3px 0;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 1px;
        }
        .terms-wrapper {
            font-size: 6.9pt;
            line-height: 1.25;
            color: #334155;
            margin-bottom: 5px;
            text-align: left;
        }
        .term-spacer {
            height: 2px;
        }
        .pasal-header {
            font-weight: bold;
            font-size: 6.9pt;
            color: #0f172a;
            background-color: #f1f5f9;
            border-left: 2.5px solid #0f172a;
            padding: 1px 4px;
            margin-top: 3px;
            margin-bottom: 1.5px;
            border-radius: 2px;
            text-align: left;
        }
        .term-clause-num {
            margin-bottom: 1px;
            padding-left: 11px;
            text-indent: -11px;
            text-align: left;
        }
        .term-subclause {
            margin-bottom: 1px;
            padding-left: 20px;
            text-align: left;
        }
        .term-text {
            margin-bottom: 1px;
            text-align: left;
        }
        .sig-section {
            page-break-inside: avoid;
            margin-top: 5px;
            margin-bottom: 4px;
        }
        .sig-table {
            width: 100%;
            border-collapse: collapse;
        }
        .sig-cell {
            width: 31.5%;
            vertical-align: top;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 4px 3px;
            text-align: center;
            box-sizing: border-box;
        }
        .sig-gap {
            width: 2.75%;
        }
        .sig-heading {
            font-weight: bold;
            font-size: 6.5pt;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            color: #475569;
            margin-bottom: 3px;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 1.5px;
        }
        .qr-img {
            width: 38px;
            height: 38px;
            border: 1px solid #cbd5e1;
            padding: 1px;
            background: #ffffff;
            border-radius: 3px;
            display: block;
            margin: 0 auto;
        }
        .signature-img {
            max-height: 24px;
            max-width: 85%;
            margin: 2px auto 1px auto;
            display: block;
        }
        .sig-name {
            font-weight: bold;
            font-size: 6.9pt;
            color: #0f172a;
            margin-top: 2px;
            text-decoration: underline;
        }
        .sig-role {
            font-size: 6.2pt;
            color: #64748b;
            margin-top: 1px;
        }
        .badge-verified {
            color: #047857;
            font-weight: bold;
        }
        .badge-pending {
            color: #b45309;
            font-style: italic;
        }
        .security-footer {
            page-break-inside: avoid;
            margin-top: 4px;
            border-top: 1px solid #e2e8f0;
            padding: 4px 6px;
            font-size: 5.8pt;
            color: #64748b;
            text-align: center;
            background: #f8fafc;
            border-radius: 4px;
        }
        .sec-title {
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 1px;
            font-size: 6.2pt;
        }
        .sec-meta {
            color: #334155;
            margin-bottom: 1.5px;
        }
        .sec-note {
            margin-top: 2px;
            padding-top: 1.5px;
            border-top: 1px dashed #cbd5e1;
            font-size: 5.2pt;
            color: #94a3b8;
            text-align: left;
        }
    </style>
</head>
<body>

@php
    $downloadUrl = route('agreements.download', ['agreement' => $agreement->id ?? 1]);
    $qrDownloadBase64 = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(75)->generate($downloadUrl));

    $companyName = $agreement->application->job->company_name ?? 'PT TechNova Asia Digital';
    $qrHrData = "DOKUMEN SAH HRD\nPerusahaan: " . $companyName . "\nHR Manager: " . ($agreement->hr_signer_name ?? 'HR Manager') . "\nNo. Perjanjian: " . $agreement->contract_number . "\nStatus: SAH TERVERIFIKASI HR\nUnduh Dokumen: " . $downloadUrl;
    $qrHrBase64 = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(65)->generate($qrHrData));

    $qrOwnerData = "DOKUMEN SAH DIREKSI / OWNER\nPerusahaan: " . $companyName . "\nDirektur Utama: " . ($agreement->owner_signer_name ?? 'Direktur Utama') . "\nNo. Perjanjian: " . $agreement->contract_number . "\nStatus: DISETUJUI & DISAHKAN DIREKSI\nUnduh Dokumen: " . $downloadUrl;
    $qrOwnerBase64 = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(65)->generate($qrOwnerData));

    $candidateSignedTime = $agreement->signed_at ? $agreement->signed_at->format('d/m/Y H:i:s') : 'Menunggu TTD';
    $qrCandidateData = "TANDA TANGAN DIGITAL KANDIDAT\nNama: " . ($agreement->signer_name ?? $agreement->user->name) . "\nEmail: " . $agreement->user->email . "\nIP: " . ($agreement->signer_ip ?? '127.0.0.1') . "\nWaktu: " . $candidateSignedTime . " WIB\nStatus: SAH DIGITAL (OTP VERIFIED)\nUnduh Dokumen: " . $downloadUrl;
    $qrCandidateBase64 = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(65)->generate($qrCandidateData));

    $rawLines = explode("\n", str_replace(["\r\n", "\r"], "\n", $agreement->terms_content));
@endphp

    <div class="top-bar"></div>

    <table class="header-table">
        <tr>
            <td style="text-align: left; vertical-align: middle;">
                <div class="header-title">{{ $agreement->title }}</div>
                <div class="doc-badge">Nomor Perjanjian: {{ $agreement->contract_number }}</div>
            </td>
            <td class="qr-download-box">
                <img src="data:image/svg+xml;base64,{{ $qrDownloadBase64 }}" class="qr-download-img">
                <div class="qr-download-text">SCAN UNDUH</div>
            </td>
        </tr>
    </table>

    <div class="meta-card">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Pihak Pertama (Perusahaan)</td>
                <td class="meta-colon">:</td>
                <td class="meta-val">{{ $companyName }}</td>
            </tr>
            <tr>
                <td class="meta-label">Pihak Kedua (Kandidat)</td>
                <td class="meta-colon">:</td>
                <td class="meta-val"><strong>{{ $agreement->user->name }}</strong> ({{ $agreement->user->email }})</td>
            </tr>
            <tr>
                <td class="meta-label">Posisi / Divisi</td>
                <td class="meta-colon">:</td>
                <td class="meta-val">{{ $agreement->application->job->title }} &bull; {{ $agreement->application->job->division ?? 'Umum' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Masa Berlaku Perjanjian</td>
                <td class="meta-colon">:</td>
                <td class="meta-val">
                    {{ $agreement->start_date ? $agreement->start_date->format('d F Y') : '-' }} s/d 
                    {{ $agreement->end_date ? $agreement->end_date->format('d F Y') : 'Tetap (Permanen)' }}
                </td>
            </tr>
            <tr>
                <td class="meta-label">Gaji / Insentif Stipend</td>
                <td class="meta-colon">:</td>
                <td class="meta-val" style="color: #047857; font-weight: bold;">{{ $agreement->stipend_or_salary }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">PASAL-PASAL & KETENTUAN HUKUM PERJANJIAN:</div>

    <div class="terms-wrapper">
        @foreach($rawLines as $line)
            @php
                $trimmed = trim($line);
            @endphp
            @if(empty($trimmed))
                <div class="term-spacer"></div>
            @elseif(preg_match('/^(PASAL\s+\d+|FASILITAS\s+&)/i', $trimmed))
                <div class="pasal-header">{{ $trimmed }}</div>
            @elseif(preg_match('/^\d+\.\s+/', $trimmed))
                <div class="term-clause-num">{{ $trimmed }}</div>
            @elseif(preg_match('/^[a-z]\.\s+/i', $trimmed) || preg_match('/^[•\-\*]\s+/', $trimmed))
                <div class="term-subclause">{{ $trimmed }}</div>
            @else
                <div class="term-text">{{ $trimmed }}</div>
            @endif
        @endforeach
    </div>

    <div class="sig-section">
        <table class="sig-table">
            <tr>
                <!-- SIGNATORY 1: HR MANAGER -->
                <td class="sig-cell">
                    <div class="sig-heading">1. TTD DIGITAL HRD</div>
                    <div style="margin: 3px auto;">
                        <img src="data:image/svg+xml;base64,{{ $qrHrBase64 }}" class="qr-img">
                    </div>
                    @if($agreement->company_signature_path)
                        <img src="{{ public_path('storage/' . $agreement->company_signature_path) }}" class="signature-img">
                    @endif
                    <div class="sig-name">{{ $agreement->hr_signer_name ?? 'HR Manager' }}</div>
                    <div class="sig-role">Head of HR &bull; <span class="badge-verified">[Terverifikasi]</span></div>
                </td>

                <td class="sig-gap"></td>

                <!-- SIGNATORY 2: OWNER / DIREKTUR -->
                <td class="sig-cell">
                    <div class="sig-heading">2. TTD DIGITAL DIREKSI</div>
                    <div style="margin: 3px auto;">
                        <img src="data:image/svg+xml;base64,{{ $qrOwnerBase64 }}" class="qr-img">
                    </div>
                    @if($agreement->owner_signature_path)
                        <img src="{{ public_path('storage/' . $agreement->owner_signature_path) }}" class="signature-img">
                    @endif
                    <div class="sig-name">{{ $agreement->owner_signer_name ?? 'Owner / Direktur Utama' }}</div>
                    <div class="sig-role">Direktur Utama &bull; <span class="badge-verified">[Terverifikasi]</span></div>
                </td>

                <td class="sig-gap"></td>

                <!-- SIGNATORY 3: KANDIDAT -->
                <td class="sig-cell">
                    <div class="sig-heading">3. TTD DIGITAL KANDIDAT</div>
                    <div style="margin: 3px auto;">
                        <img src="data:image/svg+xml;base64,{{ $qrCandidateBase64 }}" class="qr-img">
                    </div>
                    @if($agreement->signature_data)
                        <img src="{{ $agreement->signature_data }}" class="signature-img">
                    @endif
                    <div class="sig-name">{{ $agreement->signer_name ?? $agreement->user->name }}</div>
                    <div class="sig-role">
                        @if($agreement->status === 'signed')
                            <span class="badge-verified">[Sah OTP: {{ $agreement->signed_at ? $agreement->signed_at->format('d/m/Y') : '' }}]</span>
                        @else
                            <span class="badge-pending">[Menunggu TTD]</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="security-footer">
        <div class="sec-title">
            DOKUMEN RESMI TERVERIFIKASI 3 TANDA TANGAN DIGITAL QR CODE & KODE OTP EMAIL
        </div>
        <div class="sec-meta">
            Status Otentikasi: <strong class="badge-verified">[Terverifikasi OTP Email]</strong> {{ $agreement->user->email }} &bull; IP: <code>{{ $agreement->signer_ip ?? '127.0.0.1' }}</code> &bull; Tanggal Pengesahan: {{ $agreement->signed_at ? $agreement->signed_at->format('d M Y, H:i') : '-' }} WIB
        </div>
        <div class="sec-note">
            * Dokumen digital ini sah dan mengikat secara hukum digital (UU ITE No. 11/2008 & PP No. 71/2019). Pihak ketiga dapat memverifikasi dan mengunduh berkas otentik ini secara langsung dengan memindai (scan) QR Code yang tercantum pada dokumen ini.
        </div>
    </div>

</body>
</html>
