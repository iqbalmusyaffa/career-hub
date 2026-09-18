<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $agreement->title }}</title>
    <style>
        @page {
            margin: 28px 35px;
        }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #1e293b;
            background: #ffffff;
        }
        .top-bar {
            height: 5px;
            background: #0f172a;
            margin-bottom: 20px;
            border-radius: 3px;
        }
        .header {
            text-align: center;
            border-bottom: 1.5px solid #cbd5e1;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #0f172a;
        }
        .doc-badge {
            display: inline-block;
            margin-top: 6px;
            padding: 3px 12px;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 8.5pt;
            font-weight: bold;
        }
        .meta-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 20px;
        }
        .meta-table {
            width: 100%;
            font-size: 9.5pt;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .meta-label {
            width: 160px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            font-size: 8.5pt;
            letter-spacing: 0.3px;
        }
        .meta-val {
            color: #0f172a;
            font-weight: 600;
        }
        .section-title {
            font-weight: 800;
            font-size: 9.5pt;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .terms-body {
            margin: 10px 0 25px 0;
            white-space: pre-wrap;
            text-align: justify;
            font-size: 9pt;
            line-height: 1.6;
            background: #ffffff;
            padding: 14px 16px;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #0f172a;
            border-radius: 6px;
            color: #334155;
        }
        .signatures {
            margin-top: 30px;
            width: 100%;
        }
        .sig-card {
            width: 31%;
            float: left;
            text-align: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 6px;
            box-sizing: border-box;
        }
        .sig-card-mid {
            margin-left: 3.5%;
            margin-right: 3.5%;
        }
        .signature-title {
            font-weight: 800;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            margin-bottom: 8px;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 4px;
        }
        .signature-img {
            max-height: 70px;
            max-width: 100%;
            margin: 6px auto;
            display: block;
        }
        .sig-placeholder {
            height: 60px;
            line-height: 60px;
            font-weight: bold;
            color: #94a3b8;
            font-size: 8pt;
            font-style: italic;
        }
        .sig-name {
            font-weight: 800;
            font-size: 8.5pt;
            color: #0f172a;
            margin-top: 4px;
            text-decoration: underline;
        }
        .sig-role {
            font-size: 7.5pt;
            color: #64748b;
            margin-top: 2px;
        }
        .clear {
            clear: both;
        }
        .security-footer {
            margin-top: 35px;
            border-top: 1.5px solid #e2e8f0;
            padding-top: 12px;
            font-size: 7.5pt;
            color: #64748b;
            text-align: center;
            background: #f8fafc;
            border-radius: 8px;
            padding: 10px;
        }
    </style>
</head>
<body>

@php
    $verificationUrl = route('candidate.agreements.show', $agreement);
    $qrCodeBase64 = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(75)->generate($verificationUrl));
@endphp

    <div class="top-bar"></div>

    <div class="header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="text-align: left; vertical-align: middle;">
                    <h2>{{ $agreement->title }}</h2>
                    <div class="doc-badge">Nomor Perjanjian: {{ $agreement->contract_number }}</div>
                </td>
                <td style="width: 80px; text-align: right; vertical-align: middle;">
                    <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" style="width: 65px; height: 65px; border: 1px solid #cbd5e1; padding: 2px; background: #fff; border-radius: 6px;">
                    <div style="font-size: 6pt; color: #475569; font-weight: bold; text-align: center; margin-top: 2px; letter-spacing: 0.3px;">SCAN UNTUK VERIFIKASI</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="meta-card">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Pihak Pertama (Kantor):</td>
                <td class="meta-val">{{ $agreement->application->job->company_name ?? 'PT TalentFlow Tech' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Pihak Kedua (Kandidat):</td>
                <td class="meta-val">{{ $agreement->user->name }} ({{ $agreement->user->email }})</td>
            </tr>
            <tr>
                <td class="meta-label">Posisi / Pekerjaan:</td>
                <td class="meta-val">{{ $agreement->application->job->title }} &bull; {{ $agreement->application->job->division ?? 'Umum' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Masa Berlaku Perjanjian:</td>
                <td class="meta-val">
                    {{ $agreement->start_date ? $agreement->start_date->format('d F Y') : '-' }} s/d 
                    {{ $agreement->end_date ? $agreement->end_date->format('d F Y') : 'Tetap (Permanen)' }}
                </td>
            </tr>
            <tr>
                <td class="meta-label">Gaji / Insentif Stipend:</td>
                <td class="meta-val" style="color: #047857; font-weight: 800;">{{ $agreement->stipend_or_salary }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">PASAL-PASAL & KETENTUAN HUKUM PERJANJIAN:</div>

    <div class="terms-body">
{{ $agreement->terms_content }}
    </div>

    <div class="signatures">
        <!-- SIGNATORY 1: HR MANAGER -->
        <div class="sig-card">
            <div class="signature-title">1. HR MANAGER</div>
            @if($agreement->company_signature_path)
                <img src="{{ public_path('storage/' . $agreement->company_signature_path) }}" class="signature-img">
            @else
                <div class="sig-placeholder">[TERVERIFIKASI HR]</div>
            @endif
            <div class="sig-name">{{ $agreement->hr_signer_name ?? 'HR Manager' }}</div>
            <div class="sig-role">Pihak HR Perusahaan</div>
        </div>

        <!-- SIGNATORY 2: OWNER / DIREKTUR -->
        <div class="sig-card sig-card-mid">
            <div class="signature-title">2. OWNER / DIREKTUR</div>
            @if($agreement->owner_signature_path)
                <img src="{{ public_path('storage/' . $agreement->owner_signature_path) }}" class="signature-img">
            @else
                <div class="sig-placeholder">[TERVERIFIKASI OWNER]</div>
            @endif
            <div class="sig-name">{{ $agreement->owner_signer_name ?? 'Owner / Direktur Utama' }}</div>
            <div class="sig-role">Pimpinan Perusahaan</div>
        </div>

        <!-- SIGNATORY 3: PESERTA / KANDIDAT -->
        <div class="sig-card">
            <div class="signature-title">3. PESERTA / KARYAWAN</div>
            @if($agreement->signature_data)
                <img src="{{ $agreement->signature_data }}" class="signature-img">
            @else
                <div class="sig-placeholder">[Belum Ditandatangani]</div>
            @endif
            <div class="sig-name">{{ $agreement->signer_name ?? $agreement->user->name }}</div>
            <div class="sig-role">
                Tgl TTD: {{ $agreement->signed_at ? $agreement->signed_at->format('d M Y') : '-' }}
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="security-footer">
        <div style="font-weight: 800; color: #0f172a; margin-bottom: 2px;">
            🔐 DOKUMEN RESMI TERVERIFIKASI KODE OTP EMAIL & TERARSIP DIGITAL
        </div>
        Status Otentikasi: <strong>✅ Terverifikasi OTP Email: {{ $agreement->user->email }}</strong> &bull; IP: <code>{{ $agreement->signer_ip ?? '127.0.0.1' }}</code> &bull; Tanggal Pengesahan: {{ $agreement->signed_at ? $agreement->signed_at->format('d M Y, H:i') : '-' }} WIB
        
        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px dashed #cbd5e1; font-size: 7pt; color: #94a3b8; text-align: left;">
            * Dokumen ini sah secara hukum digital dan dapat dicetak (hardcopy) untuk pengarsipan fisik kantor. Stempel basah atau tanda tangan fisik tambahan dapat dibubuhkan jika diperlukan oleh departemen Legal/HR.
        </div>
    </div>

</body>
</html>
