<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Magang - {{ $certificate->participant_name }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 25px;
            background: #ffffff;
            color: #0f172a;
        }
        .cert-border {
            border: 8px solid #0f172a;
            outline: 2px solid #d97706;
            outline-offset: -12px;
            padding: 30px 40px;
            box-sizing: border-box;
            min-height: 520px;
            position: relative;
            background: #ffffff;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 13pt;
            font-weight: 800;
            letter-spacing: 2px;
            color: #475569;
            text-transform: uppercase;
        }
        .cert-title {
            font-size: 22pt;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 8px 0 2px 0;
        }
        .cert-subtitle {
            font-size: 9.5pt;
            font-weight: 700;
            color: #d97706;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .cert-number {
            font-size: 8.5pt;
            color: #64748b;
            margin-top: 6px;
            font-weight: bold;
        }
        .content {
            text-align: center;
            margin: 20px 0;
        }
        .given-to {
            font-size: 10pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .participant-name {
            font-size: 24pt;
            font-weight: 900;
            color: #0f172a;
            margin: 8px 0;
            text-decoration: underline;
            text-decoration-color: #d97706;
        }
        .institution-name {
            font-size: 11pt;
            color: #475569;
            font-weight: 700;
        }
        .description {
            font-size: 10pt;
            line-height: 1.6;
            color: #334155;
            max-width: 80%;
            margin: 15px auto;
        }
        .grade-badge {
            display: inline-block;
            padding: 4px 16px;
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
            border-radius: 12px;
            font-size: 9.5pt;
            font-weight: 800;
            margin-top: 5px;
        }
        .signatures {
            margin-top: 30px;
            width: 100%;
        }
        .sig-col {
            width: 32%;
            float: left;
            text-align: center;
        }
        .sig-col-mid {
            margin-left: 2%;
            margin-right: 2%;
        }
        .sig-title {
            font-size: 8pt;
            font-weight: 800;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 35px;
        }
        .sig-name {
            font-size: 9.5pt;
            font-weight: 800;
            color: #0f172a;
            text-decoration: underline;
        }
        .sig-role {
            font-size: 8pt;
            color: #64748b;
        }
        .clear {
            clear: both;
        }
        .footer-qr {
            position: absolute;
            bottom: 20px;
            right: 35px;
            text-align: center;
        }
        .footer-qr img {
            width: 55px;
            height: 55px;
            border: 1px solid #cbd5e1;
            padding: 2px;
            background: #fff;
            border-radius: 4px;
        }
    </style>
</head>
<body>

@php
    $verificationUrl = route('candidate.certificates.show', $certificate);
    $qrCodeBase64 = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(70)->generate($verificationUrl));
@endphp

    <div class="cert-border">
        
        <div class="header">
            <div class="company-name">{{ $certificate->application->job->company_name ?? 'PT TALENTFLOW INDONESIA' }}</div>
            <div class="cert-title">SERTIFIKAT KELULUSAN MAGANG</div>
            <div class="cert-subtitle">CERTIFICATE OF INTERNSHIP COMPLETION</div>
            <div class="cert-number">No: {{ $certificate->certificate_number }}</div>
        </div>

        <div class="content">
            <div class="given-to">Diberikan Kepada / Presented To:</div>
            <div class="participant-name">{{ $certificate->participant_name }}</div>
            <div class="institution-name">{{ $certificate->institution_name ?? 'Perguruan Tinggi / Kampus' }}</div>

            <div class="description">
                Telah berhasil menyelesaikan Program Magang Kerja (Internship) sebagai <strong>{{ $certificate->job_title }}</strong> pada {{ $certificate->application->job->company_name ?? 'Perusahaan' }} terhitung sejak tanggal <strong>{{ $certificate->start_date ? $certificate->start_date->format('d F Y') : '-' }}</strong> hingga <strong>{{ $certificate->end_date ? $certificate->end_date->format('d F Y') : '-' }}</strong> dengan hasil predikat evaluasi kinerja:
            </div>

            <div class="grade-badge">
                PREDIKAT EVALUASI: {{ strtoupper($certificate->performance_grade) }}
            </div>
        </div>

        <div class="signatures">
            <div class="sig-col">
                <div class="sig-title">MENTOR PEMBIMBING</div>
                <div class="sig-name">{{ $certificate->mentor_name ?? 'Mentor Magang' }}</div>
                <div class="sig-role">Pembimbing Lapangan</div>
                @if($certificate->mentor_phone || $certificate->mentor_email)
                    <div style="font-size: 6.5pt; color: #64748b; margin-top: 2px;">
                        {{ $certificate->mentor_phone ?? '' }} @if($certificate->mentor_phone && $certificate->mentor_email)&bull;@endif {{ $certificate->mentor_email ?? '' }}
                    </div>
                @endif
            </div>

            <div class="sig-col sig-col-mid">
                <div class="sig-title">HRD MANAGER</div>
                <div class="sig-name">{{ $certificate->hr_name ?? 'HR Manager' }}</div>
                <div class="sig-role">Human Resources Dept</div>
            </div>

            <div class="sig-col">
                <div class="sig-title">OWNER / DIREKTUR</div>
                <div class="sig-name">{{ $certificate->owner_name ?? 'Direktur Utama' }}</div>
                <div class="sig-role">Pimpinan Perusahaan</div>
            </div>
            <div class="clear"></div>
        </div>

        <div class="footer-qr">
            <img src="data:image/png;base64,{{ $qrCodeBase64 }}">
            <div style="font-size: 5.5pt; color: #64748b; font-weight: bold; margin-top: 2px;">VERIFIKASI RESMI</div>
        </div>

    </div>

</body>
</html>
