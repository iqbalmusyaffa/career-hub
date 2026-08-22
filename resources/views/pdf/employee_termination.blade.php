<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>
        @if($termination->document_type === 'recommendation_letter')
            Surat Rekomendasi Kerja - {{ $termination->employee_name }}
        @elseif($termination->document_type === 'paklaring_letter')
            Surat Paklaring Pengalaman Kerja - {{ $termination->employee_name }}
        @elseif($termination->document_type === 'phk_letter')
            Surat PHK - {{ $termination->employee_name }}
        @else
            Surat Keterangan Selesai Kontrak - {{ $termination->employee_name }}
        @endif
    </title>
    <style>
        @page {
            margin: 30px 40px;
        }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.6;
            color: #0f172a;
            background: #ffffff;
        }
        .top-bar {
            height: 4px;
            background: #0f172a;
            margin-bottom: 20px;
            border-radius: 2px;
        }
        .header {
            text-align: center;
            border-bottom: 1.5px solid #cbd5e1;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 12pt;
            font-weight: 800;
            letter-spacing: 1.5px;
            color: #475569;
            text-transform: uppercase;
        }
        .doc-title {
            font-size: 14pt;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 6px 0 2px 0;
        }
        .doc-number {
            font-size: 8.5pt;
            color: #64748b;
            font-weight: bold;
        }
        .meta-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
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
        }
        .meta-val {
            color: #0f172a;
            font-weight: 700;
        }
        .statement-body {
            margin: 15px 0 25px 0;
            white-space: pre-wrap;
            text-align: justify;
            font-size: 9.5pt;
            line-height: 1.7;
            background: #ffffff;
            padding: 16px 18px;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #0f172a;
            border-radius: 6px;
            color: #334155;
        }
        .severance-card {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 9pt;
            color: #92400e;
            font-weight: bold;
        }
        .signatures {
            margin-top: 30px;
            width: 100%;
        }
        .sig-col {
            width: 45%;
            float: left;
            text-align: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
            box-sizing: border-box;
        }
        .sig-col-right {
            float: right;
        }
        .sig-title {
            font-size: 8pt;
            font-weight: 800;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 35px;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 3px;
        }
        .sig-name {
            font-size: 9pt;
            font-weight: 800;
            color: #0f172a;
            text-decoration: underline;
        }
        .sig-role {
            font-size: 7.5pt;
            color: #64748b;
        }
        .clear {
            clear: both;
        }
        .security-footer {
            margin-top: 30px;
            border-top: 1.5px solid #e2e8f0;
            padding-top: 10px;
            font-size: 7.5pt;
            color: #64748b;
            text-align: center;
            background: #f8fafc;
            border-radius: 6px;
            padding: 10px;
        }
    </style>
</head>
<body>

@php
    $verificationUrl = route('candidate.terminations.show', $termination);
    $qrCodeBase64 = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(65)->generate($verificationUrl));
@endphp

    <div class="top-bar"></div>

    <div class="header">
        <div class="company-name">{{ $termination->application->job->company_name ?? 'PT TALENTFLOW INDONESIA' }}</div>
        <div class="doc-title">
            @if($termination->document_type === 'recommendation_letter')
                SURAT REKOMENDASI KERJA & REFERENSI KARIR
            @elseif($termination->document_type === 'paklaring_letter')
                SURAT KETERANGAN PENGALAMAN KERJA (PAKLARING)
            @elseif($termination->document_type === 'phk_letter')
                SURAT PEMUTUSAN HUBUNGAN KERJA (PHK)
            @else
                SURAT KETERANGAN SELESAI MASA KONTRAK KERJA
            @endif
        </div>
        <div class="doc-number">Nomor Dokumen: {{ $termination->document_number }}</div>
    </div>

    <div class="meta-card">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Nama Karyawan:</td>
                <td class="meta-val">{{ $termination->employee_name }}</td>
            </tr>
            <tr>
                <td class="meta-label">Posisi / Jabatan:</td>
                <td class="meta-val">{{ $termination->job_title }} &bull; {{ $termination->application->job->division ?? 'Umum' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Masa Kerja Perusahaan:</td>
                <td class="meta-val">
                    {{ $termination->start_date ? $termination->start_date->format('d F Y') : '-' }} s/d 
                    {{ $termination->end_date ? $termination->end_date->format('d F Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td class="meta-label">Perusahaan Penerbit:</td>
                <td class="meta-val">{{ $termination->application->job->company_name ?? 'PT TalentFlow Indonesia' }}</td>
            </tr>
        </table>
    </div>

    @if($termination->severance_compensation)
        <div class="severance-card">
            💰 Rincian Kompensasi / Pesangon PHK / Kontrak: <strong>{{ $termination->severance_compensation }}</strong>
        </div>
    @endif

    <div style="font-weight: 800; font-size: 9.5pt; color: #0f172a; margin-top: 15px;">PERNYATAAN RESMI PERUSAHAAN:</div>

    <div class="statement-body">
{{ $termination->reason_or_recommendation_notes }}
    </div>

    <div class="signatures">
        <!-- SIGNATORY 1: HR MANAGER -->
        <div style="width: 31%; float: left; text-align: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 4px; box-sizing: border-box;">
            <div style="font-weight: 800; font-size: 7.5pt; color: #475569; text-transform: uppercase; border-bottom: 1px dashed #cbd5e1; padding-bottom: 3px; margin-bottom: 6px;">1. HR MANAGER</div>
            @if($termination->company_signature_path)
                <img src="{{ public_path('storage/' . $termination->company_signature_path) }}" style="max-height: 60px; max-width: 100%; margin: 4px auto; display: block;">
            @else
                <div style="height: 50px; line-height: 50px; font-weight: bold; color: #94a3b8; font-size: 7.5pt; font-style: italic;">[TERVERIFIKASI HR]</div>
            @endif
            <div style="font-weight: 800; font-size: 8pt; color: #0f172a; text-decoration: underline;">{{ $termination->hr_name ?? 'HR Manager' }}</div>
            <div style="font-size: 7pt; color: #64748b;">Human Resources Dept</div>
        </div>

        <!-- SIGNATORY 2: OWNER / DIREKTUR -->
        <div style="width: 31%; float: left; text-align: center; margin-left: 3.5%; margin-right: 3.5%; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 4px; box-sizing: border-box;">
            <div style="font-weight: 800; font-size: 7.5pt; color: #475569; text-transform: uppercase; border-bottom: 1px dashed #cbd5e1; padding-bottom: 3px; margin-bottom: 6px;">2. OWNER / DIREKTUR</div>
            @if($termination->owner_signature_path)
                <img src="{{ public_path('storage/' . $termination->owner_signature_path) }}" style="max-height: 60px; max-width: 100%; margin: 4px auto; display: block;">
            @else
                <div style="height: 50px; line-height: 50px; font-weight: bold; color: #94a3b8; font-size: 7.5pt; font-style: italic;">[TERVERIFIKASI OWNER]</div>
            @endif
            <div style="font-weight: 800; font-size: 8pt; color: #0f172a; text-decoration: underline;">{{ $termination->owner_name ?? 'Direktur Utama' }}</div>
            <div style="font-size: 7pt; color: #64748b;">Pimpinan Perusahaan</div>
        </div>

        <!-- SIGNATORY 3: KARYAWAN -->
        <div style="width: 31%; float: right; text-align: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 4px; box-sizing: border-box;">
            <div style="font-weight: 800; font-size: 7.5pt; color: #475569; text-transform: uppercase; border-bottom: 1px dashed #cbd5e1; padding-bottom: 3px; margin-bottom: 6px;">3. KARYAWAN</div>
            @if($termination->signature_data)
                <img src="{{ $termination->signature_data }}" style="max-height: 60px; max-width: 100%; margin: 4px auto; display: block;">
            @else
                <div style="height: 50px; line-height: 50px; font-weight: bold; color: #94a3b8; font-size: 7.5pt; font-style: italic;">[Belum Ditandatangani]</div>
            @endif
            <div style="font-weight: 800; font-size: 8pt; color: #0f172a; text-decoration: underline;">{{ $termination->signer_name ?? $termination->employee_name }}</div>
            <div style="font-size: 7pt; color: #64748b;">
                Tgl: {{ $termination->signed_at ? $termination->signed_at->format('d M Y') : '-' }}
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="security-footer">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="text-align: left; vertical-align: middle;">
                    <strong>🔐 DOKUMEN RESMI TERARSIP DIGITAL & SAH SECARA HUKUM</strong><br>
                    Dokumen ini diterbitkan secara sah oleh manajemen {{ $termination->application->job->company_name ?? 'Perusahaan' }}.<br>
                    Status Otentikasi: Terverifikasi Digital &bull; Tgl Terbit: {{ $termination->issued_at ? $termination->issued_at->format('d M Y') : '-' }}
                </td>
                <td style="width: 65px; text-align: right; vertical-align: middle;">
                    <img src="data:image/png;base64,{{ $qrCodeBase64 }}" style="width: 50px; height: 50px; border: 1px solid #cbd5e1; padding: 1px; background: #fff; border-radius: 4px;">
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
