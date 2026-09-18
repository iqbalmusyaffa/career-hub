<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Uang Saku - {{ $stipend->user?->name ?? 'Peserta' }} - {{ $stipend->period_label }}</title>
    <style>
        @page {
            margin: 28px 35px;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 9.5pt;
            line-height: 1.5;
            color: #0f172a;
            background: #ffffff;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .kop-company {
            font-size: 14pt;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .kop-sub {
            font-size: 8.5pt;
            color: #475569;
            margin-top: 2px;
        }
        .double-divider {
            border-top: 2px solid #0f172a;
            border-bottom: 0.75px solid #0f172a;
            height: 2px;
            margin-bottom: 14px;
        }
        .doc-title-container {
            text-align: center;
            margin-bottom: 14px;
        }
        .doc-title {
            font-size: 12pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .doc-number {
            font-size: 8.5pt;
            color: #475569;
            font-weight: bold;
        }
        .badge-paid {
            display: inline-block;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section-title {
            font-size: 9pt;
            font-weight: 800;
            text-transform: uppercase;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
            margin: 12px 0 6px 0;
            letter-spacing: 0.3px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9pt;
        }
        .info-table td {
            padding: 3.5px 4px;
            vertical-align: top;
        }
        .info-label {
            width: 160px;
            color: #475569;
            font-weight: 600;
        }
        .info-colon {
            width: 12px;
            text-align: center;
            color: #64748b;
        }
        .info-value {
            color: #0f172a;
            font-weight: 500;
        }
        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 14px 0;
            font-size: 9pt;
        }
        .breakdown-table th {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-weight: 700;
            color: #334155;
            text-align: left;
        }
        .breakdown-table td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            color: #1e293b;
        }
        .total-row td {
            background: #f1f5f9;
            font-weight: 800;
            color: #0f172a;
            font-size: 9.5pt;
        }
        .highlight-net {
            background: #ecfdf5;
            border: 1.5px solid #10b981;
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 14px;
        }
        .highlight-net .label {
            font-size: 8.5pt;
            font-weight: 700;
            color: #065f46;
            text-transform: uppercase;
        }
        .highlight-net .amount {
            font-size: 16pt;
            font-weight: 900;
            color: #047857;
            margin-top: 2px;
        }
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .signatures-table td {
            width: 50%;
            vertical-align: top;
            padding: 4px 8px;
        }
        .sig-box {
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            background: #fafafa;
        }
        .sig-title {
            font-size: 8.5pt;
            font-weight: 700;
            color: #334155;
            margin-bottom: 45px;
        }
        .sig-name {
            font-size: 9pt;
            font-weight: 800;
            color: #0f172a;
            text-decoration: underline;
        }
        .sig-sub {
            font-size: 7.5pt;
            color: #64748b;
        }
        .footer-note {
            margin-top: 16px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            font-size: 7.5pt;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- KOP PERUSAHAAN -->
    <table class="header-table">
        <tr>
            <td style="width: 70px;">
                @if($stipend->company?->logo_url)
                    <img src="{{ public_path('storage/' . $stipend->company->logo_url) }}" style="max-height: 55px; max-width: 65px;" alt="Logo">
                @else
                    <div style="width: 50px; height: 50px; background: #2563eb; border-radius: 8px; text-align: center; color: white; line-height: 50px; font-weight: bold; font-size: 18pt;">
                        {{ strtoupper(substr($stipend->company?->name ?? 'K', 0, 1)) }}
                    </div>
                @endif
            </td>
            <td>
                <div class="kop-company">{{ $stipend->company?->name ?? 'PT KARIR NUSANTARA UTAMA' }}</div>
                <div class="kop-sub">{{ $stipend->company?->address ?? 'Human Resources & People Operations Division' }}</div>
                <div class="kop-tagline">Sistem Manajemen & Payroll Magang Terpadu</div>
            </td>
            <td style="text-align: right; width: 140px;">
                <div style="font-size: 7.5pt; color: #64748b;">Tanggal Cetak:</div>
                <div style="font-size: 8.5pt; font-weight: bold; color: #0f172a;">{{ now()->translatedFormat('d F Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="double-divider"></div>

    <!-- TITLE & DOCUMENT REF -->
    <div class="doc-title-container">
        <div class="doc-title">SLIP BUKTI PENCAIRAN UANG SAKU MAGANG</div>
        <div class="doc-number">NO: SLIP/STIPEND/{{ str_replace('-', '/', $stipend->period_month) }}/{{ str_pad($stipend->id, 4, '0', STR_PAD_LEFT) }}</div>
        <div>
            <span class="badge-paid">&#10003; TELAH DITRANSFER (LUNAS)</span>
        </div>
    </div>

    <!-- RECIPIENT & BANK ACCOUNT INFO -->
    <div class="section-title">I. IDENTITAS PENERIMA & REKENING BANK</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Nama Peserta Magang</td>
            <td class="info-colon">:</td>
            <td class="info-value"><strong>{{ $stipend->user?->name }}</strong> (Sesuai KTP)</td>
        </tr>
        <tr>
            <td class="info-label">Email / ID Akun</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $stipend->user?->email }} (ID: #{{ $stipend->user_id }})</td>
        </tr>
        <tr>
            <td class="info-label">Program & Batch</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $stipend->batch_name ?? 'Batch Magang 2026' }}</td>
        </tr>
        <tr>
            <td class="info-label">Periode Bulan</td>
            <td class="info-colon">:</td>
            <td class="info-value"><strong>{{ $stipend->period_label }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Bank Penerima</td>
            <td class="info-colon">:</td>
            <td class="info-value"><strong>{{ $stipend->bank_name }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Nomor Rekening</td>
            <td class="info-colon">:</td>
            <td class="info-value"><strong>{{ $stipend->bank_account_number }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Nama Pemilik Rekening</td>
            <td class="info-colon">:</td>
            <td class="info-value">
                <strong>{{ $stipend->bank_account_holder }}</strong>
                @if($stipend->is_ktp_matched)
                    <span style="color: #047857; font-weight: bold; font-size: 8pt;"> (&#10003; Sesuai KTP Terverifikasi)</span>
                @endif
            </td>
        </tr>
    </table>

    <!-- ATTENDANCE & CALCULATION BREAKDOWN -->
    <div class="section-title">II. RINCIAN PRESENSI & PERHITUNGAN UANG SAKU</div>
    <table class="breakdown-table">
        <thead>
            <tr>
                <th style="width: 50%;">Deskripsi Komponen</th>
                <th style="width: 20%; text-align: center;">Jumlah / Hari</th>
                <th style="width: 30%; text-align: right;">Nominal (IDR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Uang Saku Pokok Bulanan (Base Stipend)</strong>
                    <div style="font-size: 7.5pt; color: #64748b;">Alokasi {{ $stipend->total_working_days ?? 22 }} hari kerja efektif pada bulan {{ $stipend->period_label }}</div>
                </td>
                <td style="text-align: center;">1 Bulan ({{ $stipend->total_working_days ?? 22 }} HK)</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($stipend->base_nominal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>
                    <div>Presensi Hadir (WFO/WFH) Terverifikasi</div>
                    <div style="font-size: 7.5pt; color: #64748b;">Logbook disetujui Mentor & Sistem</div>
                </td>
                <td style="text-align: center; color: #047857; font-weight: bold;">{{ $stipend->present_days }} Hari</td>
                <td style="text-align: right; color: #64748b;">-</td>
            </tr>
            <tr>
                <td>
                    <div>Izin / Sakit Resmi (Batas Toleransi: 4 Hari)</div>
                    <div style="font-size: 7.5pt; color: #64748b;">
                        @if($stipend->excused_days <= 4)
                            Termasuk batas toleransi resmi (Bebas potongan)
                        @else
                            Melebihi kuota toleransi sebanyak {{ $stipend->excused_days - 4 }} hari
                        @endif
                    </div>
                </td>
                <td style="text-align: center;">{{ $stipend->excused_days }} Hari</td>
                <td style="text-align: right; color: #64748b;">-</td>
            </tr>
            <tr>
                <td>
                    <div>Ketidakhadiran Tanpa Keterangan (Alpa)</div>
                    <div style="font-size: 7.5pt; color: #64748b;">Tidak mengisi logbook / tanpa izin</div>
                </td>
                <td style="text-align: center; color: {{ $stipend->unexcused_days > 0 ? '#b91c1c' : '#64748b' }}; font-weight: bold;">{{ $stipend->unexcused_days }} Hari</td>
                <td style="text-align: right; color: #64748b;">-</td>
            </tr>
            <tr>
                <td style="color: #b91c1c;">
                    <strong>Pemotongan Ketidakhadiran (Deductions)</strong>
                    <div style="font-size: 7.5pt; color: #b91c1c;">
                        Formula: (Kelebihan Izin + Alpa) &#215; (Rp {{ number_format(round($stipend->base_nominal / ($stipend->total_working_days ?: 22)), 0, ',', '.') }}/hari)
                    </div>
                </td>
                <td style="text-align: center; color: #b91c1c; font-weight: bold;">
                    {{ max(0, $stipend->excused_days - 4) + $stipend->unexcused_days }} Hari Potong
                </td>
                <td style="text-align: right; color: #b91c1c; font-weight: bold;">
                    - Rp {{ number_format($stipend->deduction_amount, 0, ',', '.') }}
                </td>
            </tr>
            <tr class="total-row">
                <td colspan="2" style="text-align: right; font-weight: 900; text-transform: uppercase;">
                    TOTAL DITERIMA BERSIH (NET STIPEND)
                </td>
                <td style="text-align: right; font-weight: 900; color: #047857; font-size: 10pt;">
                    Rp {{ number_format($stipend->net_amount, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- HIGHLIGHT NET AMOUNT -->
    <div class="highlight-net">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: middle;">
                    <div class="label">Total Nominal Ditransfer ke Rekening</div>
                    <div class="amount">Rp {{ number_format($stipend->net_amount, 0, ',', '.') }}</div>
                </td>
                <td style="text-align: right; vertical-align: middle; font-size: 8pt; color: #065f46;">
                    <div>Tanggal Transfer: <strong>{{ $stipend->transferred_at ? $stipend->transferred_at->translatedFormat('d F Y, H:i') . ' WIB' : now()->translatedFormat('d F Y') }}</strong></div>
                    <div>Status Payroll: <strong>VALID & DIBAYARKAN</strong></div>
                </td>
            </tr>
        </table>
    </div>

    <!-- SIGNATURES / VALIDATION -->
    <table class="signatures-table">
        <tr>
            <td>
                <div class="sig-box">
                    <div class="sig-title">Diterima oleh Peserta Magang,</div>
                    <div class="sig-name">{{ $stipend->user?->name }}</div>
                    <div class="sig-sub">Peserta Program Magang</div>
                </div>
            </td>
            <td>
                <div class="sig-box">
                    <div class="sig-title">Disetujui & Diverifikasi oleh HR / Finance,</div>
                    <div class="sig-name">{{ $stipend->verifier?->name ?? 'Human Resources Department' }}</div>
                    <div class="sig-sub">{{ $stipend->company?->name ?? 'Divisi Kepegawaian & Payroll' }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini dibuat dan diterbitkan secara digital oleh Sistem Magang & HR Career Hub. Validitas dan keaslian dokumen dapat diverifikasi secara internal melalui modul payroll perusahaan.
    </div>

</body>
</html>
