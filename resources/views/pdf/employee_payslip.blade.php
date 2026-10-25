<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $payroll->user?->name ?? 'Karyawan' }} - {{ $payroll->period_label }}</title>
    <style>
        @page {
            margin: 25px 32px;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 8.5pt;
            line-height: 1.4;
            color: #0f172a;
            background: #ffffff;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .kop-company {
            font-size: 14pt;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }
        .kop-sub {
            font-size: 8pt;
            color: #475569;
            margin-top: 1px;
        }
        .kop-tagline {
            font-size: 7.5pt;
            color: #2563eb;
            font-weight: bold;
        }
        .double-divider {
            border-top: 2px solid #0f172a;
            border-bottom: 0.75px solid #0f172a;
            height: 2px;
            margin-bottom: 12px;
        }
        .doc-title-container {
            text-align: center;
            margin-bottom: 12px;
        }
        .doc-title {
            font-size: 12pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin-bottom: 1px;
        }
        .doc-number {
            font-size: 8pt;
            color: #475569;
            font-weight: bold;
        }
        .badge-paid {
            display: inline-block;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-card {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .info-card td {
            padding: 5px 8px;
            font-size: 8pt;
        }
        .info-label {
            color: #64748b;
            width: 18%;
        }
        .info-val {
            color: #0f172a;
            font-weight: bold;
            width: 32%;
        }
        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .breakdown-col {
            width: 50%;
            vertical-align: top;
        }
        .sub-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
        }
        .sub-table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-size: 8.5pt;
            font-weight: bold;
            padding: 6px 8px;
            text-align: left;
            border-bottom: 1px solid #cbd5e1;
            text-transform: uppercase;
        }
        .sub-table td {
            padding: 5px 8px;
            font-size: 8pt;
            border-bottom: 1px solid #f1f5f9;
        }
        .sub-table tr:last-child td {
            border-bottom: none;
        }
        .amount-col {
            text-align: right;
            font-family: 'Courier New', Courier, monospace;
            font-weight: 600;
        }
        .subtotal-row {
            background-color: #f8fafc;
            font-weight: bold;
            border-top: 1px solid #cbd5e1;
        }
        .net-summary-box {
            width: 100%;
            background: #eff6ff;
            border: 1.5px solid #bfdbfe;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }
        .net-title {
            font-size: 9pt;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .net-amount {
            font-size: 16pt;
            font-weight: 900;
            color: #1e3a8a;
            font-family: 'Courier New', Courier, monospace;
            margin-top: 2px;
        }
        .net-note {
            font-size: 7.5pt;
            color: #3b82f6;
            margin-top: 3px;
            font-style: italic;
        }
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .signatures-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 8pt;
        }
        .sign-space {
            height: 55px;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
        .sign-title {
            color: #64748b;
            font-size: 7.5pt;
        }
        .footer-note {
            margin-top: 25px;
            font-size: 7pt;
            color: #94a3b8;
            text-align: center;
            border-top: 1px dashed #e2e8f0;
            padding-top: 6px;
        }
    </style>
</head>
<body>

    <!-- KOP PERUSAHAAN -->
    <table class="header-table">
        <tr>
            <td style="width: 75%;">
                <div class="kop-company">{{ $company->company_name ?? 'PT TechNova Asia Digital' }}</div>
                <div class="kop-sub">{{ $company->address ?? 'Gedung Cyber Tower Lt. 12, Jl. HR Rasuna Said, Jakarta Selatan' }}</div>
                <div class="kop-sub">NPWP: 01.345.678.9-012.000 &bull; Telp: (021) 5798-2026 &bull; Email: hr@technova.com</div>
            </td>
            <td style="width: 25%; text-align: right;">
                <div class="kop-tagline">Portal Karir & HRIS System</div>
                <div style="font-size: 7pt; color: #64748b; margin-top: 3px;">Dokumen Resmi Kerahasiaan (Private & Confidential)</div>
            </td>
        </tr>
    </table>

    <div class="double-divider"></div>

    <!-- JUDUL DOKUMEN -->
    <div class="doc-title-container">
        <div class="doc-title">SLIP GAJI KARYAWAN (PAYSLIP)</div>
        <div class="doc-number">No. Dokumen: {{ $payroll->payroll_number }} &bull; Periode: {{ $payroll->period_label }}</div>
        @if($payroll->status === 'paid')
            <div class="badge-paid">&#10003; TELAH DITRANSFER PADA {{ $payroll->paid_at ? $payroll->paid_at->format('d M Y') : ($payroll->payment_date ? $payroll->payment_date->format('d M Y') : date('d M Y')) }}</div>
        @else
            <div class="badge-paid" style="background: #eff6ff; color: #1e40af; border-color: #bfdbfe;">STATUS: SIAP DIBAYAR</div>
        @endif
    </div>

    <!-- INFORMASI KARYAWAN -->
    <table class="info-card">
        <tr>
            <td class="info-label">Nama Karyawan</td>
            <td class="info-val">: {{ $payroll->user->name }}</td>
            <td class="info-label">Departemen / Divisi</td>
            <td class="info-val">: Technology & Engineering</td>
        </tr>
        <tr>
            <td class="info-label">Posisi / Jabatan</td>
            <td class="info-val">: {{ $payroll->job_title }}</td>
            <td class="info-label">Status Hubungan Kerja</td>
            <td class="info-val">: {{ $payroll->employment_type_label }}</td>
        </tr>
        <tr>
            <td class="info-label">Rekening Pembayaran</td>
            <td class="info-val">: {{ $payroll->bank_name }} - {{ $payroll->bank_account_number }}</td>
            <td class="info-label">Rekapitulasi Kehadiran</td>
            <td class="info-val">: Hadir: {{ $payroll->present_days }} hr &bull; Telat: {{ $payroll->late_days }} hr &bull; Lembur: {{ $payroll->overtime_hours }} jam</td>
        </tr>
    </table>

    <!-- BREAKDOWN PENERIMAAN & POTONGAN -->
    <table class="breakdown-table">
        <tr>
            <!-- KOLOM PENERIMAAN -->
            <td class="breakdown-col" style="padding-right: 6px;">
                <table class="sub-table">
                    <thead>
                        <tr>
                            <th>1. PENERIMAAN (EARNINGS)</th>
                            <th class="amount-col" style="width: 35%;">JUMLAH (RP)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gaji Pokok (Basic Salary)</td>
                            <td class="amount-col">Rp {{ number_format($payroll->base_salary, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Tetap / Jabatan</td>
                            <td class="amount-col">Rp {{ number_format($payroll->allowance_fixed, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Transport & Makan</td>
                            <td class="amount-col">Rp {{ number_format($payroll->allowance_transport, 0, ',', '.') }}</td>
                        </tr>
                        @if($payroll->overtime_pay > 0)
                        <tr>
                            <td>Upah Kerja Lembur (Overtime)</td>
                            <td class="amount-col">Rp {{ number_format($payroll->overtime_pay, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($payroll->reimbursement_pay > 0)
                        <tr>
                            <td>Klaim Reimbursement Operasional</td>
                            <td class="amount-col">Rp {{ number_format($payroll->reimbursement_pay, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($payroll->bonus_pay > 0)
                        <tr>
                            <td>Insentif / Bonus Kinerja</td>
                            <td class="amount-col">Rp {{ number_format($payroll->bonus_pay, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="subtotal-row">
                            <td>TOTAL PENERIMAAN KOTOR (A)</td>
                            <td class="amount-col">Rp {{ number_format($payroll->total_earnings, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>

            <!-- KOLOM POTONGAN -->
            <td class="breakdown-col" style="padding-left: 6px;">
                <table class="sub-table">
                    <thead>
                        <tr>
                            <th>2. POTONGAN (DEDUCTIONS)</th>
                            <th class="amount-col" style="width: 35%;">JUMLAH (RP)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>BPJS Ketenagakerjaan (JHT, JKK, JKM)</td>
                            <td class="amount-col">Rp {{ number_format($payroll->deduction_bpjs_tk, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>BPJS Kesehatan (1%)</td>
                            <td class="amount-col">Rp {{ number_format($payroll->deduction_bpjs_kes, 0, ',', '.') }}</td>
                        </tr>
                        @if($payroll->deduction_late > 0)
                        <tr>
                            <td>Potongan Keterlambatan Absensi</td>
                            <td class="amount-col">Rp {{ number_format($payroll->deduction_late, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($payroll->deduction_pph21 > 0)
                        <tr>
                            <td>Pajak Penghasilan (PPh 21)</td>
                            <td class="amount-col">Rp {{ number_format($payroll->deduction_pph21, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($payroll->deduction_other > 0)
                        <tr>
                            <td>Potongan Lain-lain</td>
                            <td class="amount-col">Rp {{ number_format($payroll->deduction_other, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($payroll->deduction_late == 0 && $payroll->deduction_pph21 == 0 && $payroll->deduction_other == 0)
                        <tr>
                            <td style="color: #94a3b8; font-style: italic;">Tidak ada potongan denda</td>
                            <td class="amount-col" style="color: #94a3b8;">Rp 0</td>
                        </tr>
                        @endif
                        <tr class="subtotal-row">
                            <td>TOTAL POTONGAN (B)</td>
                            <td class="amount-col">Rp {{ number_format($payroll->total_deductions, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- TOTAL GAJI BERSIH (TAKE HOME PAY) -->
    <div class="net-summary-box">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="net-title">PENGHASILAN BERSIH YANG DITERIMA (TAKE HOME PAY = A - B)</div>
                    <div class="net-amount">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</div>
                    <div class="net-note">Pembayaran ditransfer ke rekening {{ $payroll->bank_name }} a.n. {{ $payroll->bank_account_holder }} ({{ $payroll->bank_account_number }})</div>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <div style="font-size: 8pt; font-weight: bold; color: #1e3a8a;">Kerahasiaan Dokumen</div>
                    <div style="font-size: 7.5pt; color: #64748b;">Harap simpan bukti slip gaji ini dengan baik untuk keperluan perpajakan & administrasi pribadi.</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- TANDA TANGAN -->
    <table class="signatures-table">
        <tr>
            <td>
                <div>Mengetahui,</div>
                <div style="font-size: 7.5pt; color: #64748b;">Human Resources & Finance Department</div>
                <div class="sign-space" style="margin-top: 10px;">
                    <span style="font-size: 8pt; color: #059669; font-weight: bold; border: 1px dashed #059669; padding: 4px 12px; border-radius: 4px;">
                        &#10003; VERIFIED DIGITAL SIGNATURE
                    </span>
                </div>
                <div class="sign-name">Budi Santoso, S.Kom.</div>
                <div class="sign-title">Head of HR & Talent Management</div>
            </td>
            <td>
                <div>Diterima oleh Karyawan,</div>
                <div style="font-size: 7.5pt; color: #64748b;">Penerima Hak Gaji & Kompensasi</div>
                <div class="sign-space" style="margin-top: 10px;">
                    <span style="font-size: 8pt; color: #2563eb; font-weight: bold; border: 1px dashed #2563eb; padding: 4px 12px; border-radius: 4px;">
                        &#10003; SIGNED BY EMPLOYEE
                    </span>
                </div>
                <div class="sign-name">{{ $payroll->user->name }}</div>
                <div class="sign-title">{{ $payroll->job_title }}</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini diterbitkan secara otomatis dan sah melalui Sistem Terintegrasi Career Hub & HRIS Platform. &bull; Waktu Cetak: {{ date('d F Y, H:i:s') }} WIB
    </div>

</body>
</html>
