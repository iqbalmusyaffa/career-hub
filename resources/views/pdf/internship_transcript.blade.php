<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transkrip Nilai Magang - {{ $transcript->participant_name }}</title>
    <style>
        @page {
            margin: 30px 40px;
        }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 9.5pt;
            line-height: 1.5;
            color: #0f172a;
            background: #ffffff;
        }
        .top-bar {
            height: 4px;
            background: #0f172a;
            margin-bottom: 15px;
            border-radius: 2px;
        }
        .header {
            text-align: center;
            border-bottom: 1.5px solid #cbd5e1;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 11pt;
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
            margin: 4px 0 2px 0;
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
            padding: 12px 16px;
            margin-bottom: 15px;
        }
        .meta-table {
            width: 100%;
            font-size: 9pt;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .meta-label {
            width: 150px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            font-size: 8pt;
        }
        .meta-val {
            color: #0f172a;
            font-weight: 700;
        }
        .section-title {
            font-weight: 800;
            font-size: 9pt;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 15px 0 8px 0;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
        }
        .grade-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9pt;
        }
        .grade-table th {
            background: #0f172a;
            color: #ffffff;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #0f172a;
        }
        .grade-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            color: #334155;
        }
        .grade-table tr:nth-child(even) {
            background: #f8fafc;
        }
        .score-box {
            background: #fef3c7;
            border: 1.5px solid #f59e0b;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 15px;
            text-align: center;
        }
        .score-final {
            font-size: 16pt;
            font-weight: 900;
            color: #92400e;
        }
        .grade-letter {
            font-size: 10pt;
            font-weight: 800;
            color: #b45309;
            margin-top: 2px;
        }
        .notes-box {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #0f172a;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 8.5pt;
            line-height: 1.6;
            color: #334155;
            margin-bottom: 20px;
        }
        .signatures {
            margin-top: 25px;
            width: 100%;
        }
        .sig-col {
            width: 48%;
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
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 30px;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 3px;
        }
        .sig-name {
            font-size: 8.5pt;
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
            margin-top: 25px;
            border-top: 1.5px solid #e2e8f0;
            padding-top: 8px;
            font-size: 7pt;
            color: #64748b;
            text-align: center;
            background: #f8fafc;
            border-radius: 6px;
            padding: 8px;
        }
    </style>
</head>
<body>

@php
    $verificationUrl = route('certificates.verify.public', ['code' => $transcript->transcript_number]);
    $qrCodeBase64 = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(65)->generate($verificationUrl));
@endphp

    <div class="top-bar"></div>

    <div class="header">
        <div class="company-name">{{ $transcript->application->job->company_name ?? 'PT TALENTFLOW INDONESIA' }}</div>
        <div class="doc-title">TRANSKRIP EVALUASI NILAI MAGANG</div>
        <div class="doc-number">Nomor Transkrip: {{ $transcript->transcript_number }}</div>
    </div>

    <div class="meta-card">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Nama Peserta Magang:</td>
                <td class="meta-val">{{ $transcript->participant_name }}</td>
            </tr>
            <tr>
                <td class="meta-label">NIM / NIS Siswa:</td>
                <td class="meta-val">{{ $transcript->student_id_number ?? '-' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Institusi / Kampus:</td>
                <td class="meta-val">{{ $transcript->institution_name ?? 'Perguruan Tinggi / Kampus' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Posisi Pekerjaan Magang:</td>
                <td class="meta-val">{{ $transcript->job_title }} &bull; {{ $transcript->application->job->division ?? 'Umum' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Periode Magang:</td>
                <td class="meta-val">
                    {{ $transcript->start_date ? $transcript->start_date->format('d F Y') : '-' }} s/d 
                    {{ $transcript->end_date ? $transcript->end_date->format('d F Y') : '-' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">I. LEMBAR RINCIAN EVALUASI KRITERIA PENILAIAN:</div>

    <table class="grade-table">
        <thead>
            <tr>
                <th style="width: 8%;">NO</th>
                <th style="width: 52%;">ASPEK KRITERIA PENILAIAN MAGANG</th>
                <th style="width: 20%; text-align: center;">NILAI (0 - 100)</th>
                <th style="width: 20%; text-align: center;">HURUF MUTU</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; font-weight: bold;">1</td>
                <td><strong>Kedisiplinan & Presensi (Discipline & Attendance)</strong></td>
                <td style="text-align: center; font-weight: bold; color: #0f172a;">{{ number_format($transcript->score_discipline, 1) }}</td>
                <td style="text-align: center; font-weight: bold; color: #047857;">{{ $transcript->score_discipline >= 85 ? 'A' : ($transcript->score_discipline >= 75 ? 'B' : 'C') }}</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">2</td>
                <td><strong>Keahlian Teknis & Hasil Kerja (Technical Skills & Deliverables)</strong></td>
                <td style="text-align: center; font-weight: bold; color: #0f172a;">{{ number_format($transcript->score_technical, 1) }}</td>
                <td style="text-align: center; font-weight: bold; color: #047857;">{{ $transcript->score_technical >= 85 ? 'A' : ($transcript->score_technical >= 75 ? 'B' : 'C') }}</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">3</td>
                <td><strong>Komunikasi & Kerjasama Tim (Communication & Teamwork)</strong></td>
                <td style="text-align: center; font-weight: bold; color: #0f172a;">{{ number_format($transcript->score_communication, 1) }}</td>
                <td style="text-align: center; font-weight: bold; color: #047857;">{{ $transcript->score_communication >= 85 ? 'A' : ($transcript->score_communication >= 75 ? 'B' : 'C') }}</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">4</td>
                <td><strong>Inisiatif & Problem Solving (Initiative & Problem Solving)</strong></td>
                <td style="text-align: center; font-weight: bold; color: #0f172a;">{{ number_format($transcript->score_problem_solving, 1) }}</td>
                <td style="text-align: center; font-weight: bold; color: #047857;">{{ $transcript->score_problem_solving >= 85 ? 'A' : ($transcript->score_problem_solving >= 75 ? 'B' : 'C') }}</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">5</td>
                <td><strong>Etika & Profesionalisme Kerja (Professional Ethics & Attitude)</strong></td>
                <td style="text-align: center; font-weight: bold; color: #0f172a;">{{ number_format($transcript->score_ethics, 1) }}</td>
                <td style="text-align: center; font-weight: bold; color: #047857;">{{ $transcript->score_ethics >= 85 ? 'A' : ($transcript->score_ethics >= 75 ? 'B' : 'C') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="score-box">
        <div style="font-size: 8.5pt; font-weight: 800; text-transform: uppercase; color: #78350f;">NILAI RATA-RATA AKHIR (FINAL EVALUATION GPA):</div>
        <div class="score-final">{{ number_format($transcript->final_score, 2) }} / 100</div>
        <div class="grade-letter">PREDIKAT AKADEMIK: {{ strtoupper($transcript->grade_letter) }}</div>
    </div>

    <div class="section-title">II. CATATAN EVALUASI & REKOMENDASI MENTOR PEMBIMBING:</div>
    <div class="notes-box">
        "{{ $transcript->mentor_notes }}"
    </div>

    <div class="signatures">
        <div class="sig-col">
            <div class="sig-title">MENTOR PEMBIMBING LAPANGAN</div>
            <div class="sig-name">{{ $transcript->mentor_name ?? 'Mentor Magang' }}</div>
            <div class="sig-role">Pembimbing Lapangan</div>
            @if($transcript->mentor_phone || $transcript->mentor_email)
                <div style="font-size: 6.5pt; color: #64748b; margin-top: 2px;">
                    {{ $transcript->mentor_phone ?? '' }} @if($transcript->mentor_phone && $transcript->mentor_email)&bull;@endif {{ $transcript->mentor_email ?? '' }}
                </div>
            @endif
        </div>

        <div class="sig-col sig-col-right">
            <div class="sig-title">HRD MANAGER</div>
            <div class="sig-name">{{ $transcript->hr_name ?? 'HR Manager' }}</div>
            <div class="sig-role">Human Resources Dept</div>
            <div style="font-size: 6.5pt; color: #64748b; margin-top: 2px;">
                Tgl Terbit: {{ $transcript->issued_at ? $transcript->issued_at->format('d F Y') : '-' }}
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="security-footer">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="text-align: left; vertical-align: middle;">
                    <strong>🔐 TRANSKRIP NILAI RESMI TERARSIP DIGITAL & SAH AKADEMIK</strong><br>
                    Dokumen transkrip nilai ini diterbitkan secara resmi oleh {{ $transcript->application->job->company_name ?? 'Perusahaan' }}.<br>
                    Hukum Otentikasi: Terverifikasi Sistem Karir Digital &bull; Tgl Pengesahan: {{ $transcript->issued_at ? $transcript->issued_at->format('d M Y') : '-' }}
                </td>
                <td style="width: 65px; text-align: right; vertical-align: middle;">
                    <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" style="width: 50px; height: 50px; border: 1px solid #cbd5e1; padding: 1px; background: #fff; border-radius: 4px;">
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
