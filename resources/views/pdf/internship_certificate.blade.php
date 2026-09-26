<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Magang - {{ $certificate->participant_name }}</title>
    <style>
        @page {
            size: landscape;
            margin: 8mm 10mm;
        }
        * {
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
        }
        .cert-outer-table {
            width: 100%;
            border: 4px solid #0f172a;
            border-collapse: collapse;
            background: #ffffff;
        }
        .cert-outer-td {
            padding: 4px;
            vertical-align: middle;
        }
        .cert-inner-table {
            width: 100%;
            border: 2px solid #b45309;
            border-collapse: collapse;
            background: #ffffff;
        }
        .cert-inner-td {
            height: 184mm;
            padding: 24px 36px 20px 36px;
            text-align: center;
            vertical-align: middle;
        }
        .company-name {
            font-size: 11pt;
            font-weight: 800;
            letter-spacing: 3.5px;
            color: #475569;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .cert-title {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 26pt;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            margin: 4px 0 2px 0;
        }
        .cert-subtitle {
            font-size: 9.5pt;
            font-weight: 700;
            color: #b45309;
            letter-spacing: 4.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .divider-line {
            width: 160px;
            height: 2px;
            background: #b45309;
            margin: 8px auto 8px auto;
        }
        .cert-no {
            font-size: 8.5pt;
            color: #64748b;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 14px;
        }
        .presented-to {
            font-size: 9pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .participant-name {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 26pt;
            font-weight: 900;
            color: #0f172a;
            margin: 4px 0 4px 0;
            letter-spacing: 1px;
            border-bottom: 2.5px solid #d97706;
            display: inline-block;
            padding-bottom: 3px;
        }
        .campus-name {
            font-size: 11pt;
            color: #475569;
            font-weight: 700;
            margin-top: 4px;
            margin-bottom: 12px;
        }
        .description-text {
            font-size: 10pt;
            line-height: 1.7;
            color: #334155;
            width: 90%;
            margin: 0 auto 14px auto;
        }
        .grade-badge-wrap {
            margin-bottom: 18px;
        }
        .grade-badge {
            display: inline-block;
            padding: 5px 24px;
            background: #fef3c7;
            color: #92400e;
            border: 1.5px solid #d97706;
            border-radius: 20px;
            font-size: 9.5pt;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .sig-col {
            width: 27%;
            text-align: center;
            vertical-align: top;
            padding: 0 6px;
        }
        .qr-col {
            width: 19%;
            text-align: center;
            vertical-align: top;
            border-left: 1px dashed #cbd5e1;
            padding-left: 10px;
        }
        .sig-label {
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .sig-person {
            font-size: 9.5pt;
            font-weight: 800;
            color: #0f172a;
            margin-top: 4px;
        }
        .sig-status {
            font-size: 7.5pt;
            color: #64748b;
            font-weight: 600;
            line-height: 1.35;
        }
    </style>
</head>
<body>

@php
    $verificationUrl = route('certificates.verify.public', ['code' => $certificate->certificate_number]);
    $qrCodeBase64 = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(75)->generate($verificationUrl));

    $qrMentorData = "VALIDASI MENTOR PEMBIMBING\nNama: " . ($certificate->mentor_name ?? 'Mentor Magang') . "\nNo. Sertifikat: " . $certificate->certificate_number . "\nStatus: TERVERIFIKASI & MEMENUHI SYARAT\nVerifikasi: " . $verificationUrl;
    $qrMentorSvg = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(55)->generate($qrMentorData));

    $qrHrData = "VALIDASI HRD MANAGER\nNama: " . ($certificate->hr_name ?? 'HR Manager') . "\nNo. Sertifikat: " . $certificate->certificate_number . "\nStatus: DISAHKAN HRD\nVerifikasi: " . $verificationUrl;
    $qrHrSvg = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(55)->generate($qrHrData));

    $qrOwnerData = "VALIDASI DIREKSI / OWNER\nNama: " . ($certificate->owner_name ?? 'Direktur Utama') . "\nNo. Sertifikat: " . $certificate->certificate_number . "\nStatus: DISETUJUI DIREKSI UTAMA\nVerifikasi: " . $verificationUrl;
    $qrOwnerSvg = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(55)->generate($qrOwnerData));
    
    $startDateFormatted = $certificate->start_date ? \Carbon\Carbon::parse($certificate->start_date)->locale('id')->translatedFormat('d F Y') : '-';
    $endDateFormatted = $certificate->end_date ? \Carbon\Carbon::parse($certificate->end_date)->locale('id')->translatedFormat('d F Y') : '-';
@endphp

    <table class="cert-outer-table">
        <tr>
            <td class="cert-outer-td">
                <table class="cert-inner-table">
                    <tr>
                        <td class="cert-inner-td">
                            
                            <div class="company-name">{{ $certificate->application->job->company_name ?? 'PT TALENTFLOW INDONESIA' }}</div>
                            <div class="cert-title">SERTIFIKAT KELULUSAN MAGANG</div>
                            <div class="cert-subtitle">Certificate of Internship Completion</div>
                            <div class="divider-line"></div>
                            <div class="cert-no">No: {{ $certificate->certificate_number }}</div>

                            <div class="presented-to">Diberikan Kepada / Presented To:</div>
                            <div>
                                <div class="participant-name">{{ $certificate->participant_name }}</div>
                            </div>
                            <div class="campus-name">{{ $certificate->institution_name ?? 'Perguruan Tinggi / Institusi Pendidikan' }}</div>

                            <div class="description-text">
                                Telah berhasil menyelesaikan Program Magang Kerja (Internship) sebagai <strong>{{ $certificate->job_title }}</strong> pada {{ $certificate->application->job->company_name ?? 'Perusahaan' }} terhitung sejak tanggal <strong>{{ $startDateFormatted }}</strong> hingga <strong>{{ $endDateFormatted }}</strong> dengan predikat evaluasi kinerja:
                            </div>

                            <div class="grade-badge-wrap">
                                <div class="grade-badge">
                                    PREDIKAT EVALUASI: {{ strtoupper($certificate->performance_grade) }}
                                </div>
                            </div>

                            <!-- Footer Table with 3 Signatures + Official Verification QR in a Single Row -->
                            <table class="footer-table">
                                <tr>
                                    <!-- 1. MENTOR -->
                                    <td class="sig-col">
                                        <div class="sig-label">MENTOR PEMBIMBING</div>
                                        <div style="margin: 2px auto;">
                                            <img src="data:image/svg+xml;base64,{{ $qrMentorSvg }}" style="width: 42px; height: 42px; border: 1px solid #cbd5e1; padding: 2px; background: #fff; border-radius: 4px;">
                                        </div>
                                        <div class="sig-person">{{ $certificate->mentor_name ?? 'Mentor Magang' }}</div>
                                        <div class="sig-status">Pembimbing Lapangan<br>[Terverifikasi Digital]</div>
                                    </td>

                                    <!-- 2. HRD -->
                                    <td class="sig-col">
                                        <div class="sig-label">HRD MANAGER</div>
                                        <div style="margin: 2px auto;">
                                            <img src="data:image/svg+xml;base64,{{ $qrHrSvg }}" style="width: 42px; height: 42px; border: 1px solid #cbd5e1; padding: 2px; background: #fff; border-radius: 4px;">
                                        </div>
                                        <div class="sig-person">{{ $certificate->hr_name ?? 'HR Manager' }}</div>
                                        <div class="sig-status">Human Resources Dept<br>[Terverifikasi Digital]</div>
                                    </td>

                                    <!-- 3. DIREKTUR -->
                                    <td class="sig-col">
                                        <div class="sig-label">OWNER / DIREKTUR</div>
                                        <div style="margin: 2px auto;">
                                            <img src="data:image/svg+xml;base64,{{ $qrOwnerSvg }}" style="width: 42px; height: 42px; border: 1px solid #cbd5e1; padding: 2px; background: #fff; border-radius: 4px;">
                                        </div>
                                        <div class="sig-person">{{ $certificate->owner_name ?? 'Direktur Utama' }}</div>
                                        <div class="sig-status">Pimpinan Perusahaan<br>[Terverifikasi Digital]</div>
                                    </td>

                                    <!-- 4. OFFICIAL VERIFICATION QR -->
                                    <td class="qr-col">
                                        <div class="sig-label" style="color: #b45309;">VERIFIKASI RESMI</div>
                                        <div style="margin: 2px auto;">
                                            <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" style="width: 42px; height: 42px; border: 1px solid #d97706; padding: 2px; background: #fff; border-radius: 4px;">
                                        </div>
                                        <div style="font-size: 6pt; color: #64748b; font-weight: bold; margin-top: 2px;">Scan Keaslian</div>
                                        <div style="font-size: 5.5pt; color: #b45309; font-weight: bold;">ID: {{ $certificate->certificate_number }}</div>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
