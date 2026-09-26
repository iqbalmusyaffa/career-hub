<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pernyataan Rekening Bank - {{ $user->name }}</title>
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
            font-size: 13pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .kop-sub {
            font-size: 8.5pt;
            color: #475569;
            margin-top: 2px;
        }
        .kop-tagline {
            font-size: 7.5pt;
            color: #64748b;
            font-style: italic;
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
            font-size: 11.5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .doc-number {
            font-size: 8.5pt;
            color: #475569;
            font-weight: bold;
        }
        .badge-verified {
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
            margin-bottom: 8px;
            font-size: 9pt;
        }
        .info-table td {
            padding: 3px 4px;
            vertical-align: top;
        }
        .info-label {
            width: 170px;
            color: #64748b;
            font-weight: 600;
        }
        .info-separator {
            width: 10px;
            color: #94a3b8;
        }
        .info-value {
            color: #0f172a;
            font-weight: 700;
        }
        .bank-card {
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-left: 4px solid #059669;
            border-radius: 6px;
            padding: 10px 14px;
            margin: 10px 0;
        }
        .statement-box {
            background: #fefce8;
            border: 1px solid #fef08a;
            border-left: 3px solid #eab308;
            border-radius: 4px;
            padding: 8px 12px;
            margin: 10px 0;
            font-size: 8.5pt;
            color: #713f12;
            line-height: 1.5;
        }
        .statement-list {
            margin: 4px 0 0 0;
            padding-left: 18px;
        }
        .statement-list li {
            margin-bottom: 4px;
        }
        .statement-text {
            font-size: 8.8pt;
            color: #334155;
            text-align: justify;
            margin: 10px 0;
            line-height: 1.6;
        }
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }
        .signatures-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 0 15px;
        }
        .sig-role-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .sig-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 10px;
        }
        .sig-stamp {
            display: inline-block;
            border: 1.5px dashed #059669;
            color: #059669;
            padding: 2.5px 6px;
            font-size: 6.5pt;
            font-weight: 900;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }
        .sig-stamp-admin {
            border-color: #0284c7;
            color: #0284c7;
        }
        .sig-name {
            font-size: 9pt;
            font-weight: 800;
            color: #0f172a;
            border-top: 1px solid #94a3b8;
            padding-top: 4px;
            margin-top: 6px;
            display: inline-block;
            min-width: 160px;
        }
        .sig-sub {
            font-size: 7.2pt;
            color: #64748b;
            margin-top: 1px;
        }
        .qr-box {
            text-align: center;
            margin-bottom: 4px;
        }
        .qr-box img {
            width: 60px;
            height: 60px;
            padding: 2px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            background: #ffffff;
        }
        .footer-note {
            margin-top: 14px;
            padding-top: 6px;
            border-top: 0.75px dashed #cbd5e1;
            font-size: 6.8pt;
            color: #94a3b8;
            text-align: center;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT RESMI -->
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <div class="kop-company">{{ $company->company_name ?? 'PROGRAM MAGANG & KARIR TERPADU' }}</div>
                <div class="kop-sub">Sistem Manajemen Presensi & Pencairan Uang Saku Peserta Magang</div>
                <div class="kop-tagline">Verifikasi Identitas Kependudukan & Otorisasi Rekening Perbankan Resmi</div>
            </td>
            <td style="width: 30%; text-align: right;">
                <div style="font-size: 8pt; color: #64748b; font-weight: bold;">TANGGAL PENGAJUAN / UPDATE:</div>
                <div style="font-size: 9pt; color: #0f172a; font-weight: 800;">{{ $generatedAt->translatedFormat('d F Y H:i') }} WIB</div>
            </td>
        </tr>
    </table>

    <div class="double-divider"></div>

    <!-- JUDUL DOKUMEN -->
    <div class="doc-title-container">
        <div class="doc-title">SURAT PERNYATAAN KEBENARAN DATA & VALIDASI REKENING BANK</div>
        <div class="doc-number">Nomor: {{ $docNumber }}</div>
        <div>
            <span class="badge-verified">✓ TERVERIFIKASI SESUAI NAMA KTP / IDENTITAS RESMI</span>
        </div>
    </div>

    <!-- SECTION 1: DATA IDENTITAS PESERTA MAGANG -->
    <div class="section-title">I. DATA IDENTITAS PESERTA MAGANG</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Nama Lengkap (Sesuai KTP)</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $user->name }}</td>
        </tr>
        <tr>
            <td class="info-label">Nomor Induk Kependudukan (NIK)</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $user->candidateProfile->nik ?? $user->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Alamat Email Terdaftar</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $user->email }}</td>
        </tr>
        <tr>
            <td class="info-label">Nomor WhatsApp / Telepon</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $user->phone ?? $user->candidateProfile->phone ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Institusi / Universitas</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $user->candidateProfile->university ?? $user->candidateProfile->institution ?? 'Institusi Pendidikan Mitra' }}</td>
        </tr>
        <tr>
            <td class="info-label">Posisi & Batch Magang</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $jobTitle }} ({{ $periodName }})</td>
        </tr>
        <tr>
            <td class="info-label">Mitra / Perusahaan Penempatan</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $company->company_name ?? 'PT Mitra Industri Terpadu' }}</td>
        </tr>
    </table>

    <!-- SECTION 2: DATA REKENING BANK PENCAIRAN UANG SAKU -->
    <div class="section-title">II. DATA REKENING BANK PENCAIRAN UANG SAKU</div>
    <div class="bank-card">
        <table class="info-table" style="margin-bottom: 0;">
            <tr>
                <td class="info-label" style="width: 180px;">Nama Bank Penerima</td>
                <td class="info-separator">:</td>
                <td class="info-value" style="font-size: 10pt; color: #1e40af;">{{ $onboarding->bank_name ?? 'BCA' }}</td>
            </tr>
            <tr>
                <td class="info-label">Nomor Rekening</td>
                <td class="info-separator">:</td>
                <td class="info-value" style="font-size: 11pt; letter-spacing: 1px; font-family: monospace;">{{ $onboarding->bank_account_number ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Nama Pemilik Rekening</td>
                <td class="info-separator">:</td>
                <td class="info-value" style="color: #059669;">{{ $onboarding->bank_account_holder ?? $user->name }}</td>
            </tr>
            <tr>
                <td class="info-label">Status Validasi Nama</td>
                <td class="info-separator">:</td>
                <td class="info-value" style="color: #047857; font-size: 8.5pt;">
                    ✓ 100% Sesuai Identitas Resmi Peserta (KTP Matched)
                </td>
            </tr>
            <tr>
                <td class="info-label">Dokumen Buku Tabungan / E-Statement</td>
                <td class="info-separator">:</td>
                <td class="info-value" style="font-weight: normal; font-size: 8.5pt;">
                    {{ $onboarding->bank_book_doc_path ? 'Terlampir & Terunggah pada Sistem Portal Magang' : 'Tervalidasi Digital Sesuai NIK/KTP' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- SECTION 3: KETENTUAN & PERNYATAAN HUKUM -->
    <div class="section-title">III. PERNYATAAN & KETENTUAN PERUBAHAN REKENING</div>
    <div class="statement-box">
        <strong>Ketentuan dan Alur Perubahan Rekening Bank:</strong>
        <ul class="statement-list">
            <li><strong>Rekening Pribadi:</strong> Rekening bank di atas adalah sah milik pribadi peserta bersangkutan dan aktif digunakan untuk transaksi perbankan.</li>
            <li><strong>Mekanisme Pergantian Rekening:</strong> Peserta diperbolehkan memperbarui nomor rekening sewaktu-waktu melalui Portal Magang. Nomor rekening baru akan otomatis digunakan untuk <u>seluruh periode pencairan berikutnya yang belum ditransfer</u>.</li>
            <li><strong>Integritas Audit Finansial:</strong> Periode uang saku yang telah selesai dicairkan (*Status: Telah Ditransfer*) tetap tercatat sesuai bukti transfer pada rekening terdahulu demi akuntabilitas audit keuangan.</li>
            <li><strong>Tanggung Jawab Data:</strong> Segala risiko kegagalan transfer atau keterlambatan pencairan akibat penutupan rekening sepihak tanpa pembaruan data pada sistem merupakan tanggung jawab peserta magang.</li>
        </ul>
    </div>

    <div class="statement-text">
        Dengan ini saya menyatakan dengan sesungguhnya dan sebenar-benarnya bahwa seluruh data identitas diri dan nomor rekening bank yang saya cantumkan di atas adalah benar, sah, dan dapat dipertanggungjawabkan secara hukum. Apabila di kemudian hari ditemukan ketidakbenaran atau manipulasi data pihak ketiga, saya bersedia menerima sanksi pembatalan kepesertaan magang dan penangguhan hak uang saku sesuai regulasi yang berlaku.
    </div>

    <!-- TANDA TANGAN 2 PIHAK (DUAL QR CODE VERIFICATION: PESERTA & SUPER ADMIN) -->
    <table class="signatures-table">
        <tr>
            <!-- 1. Peserta Magang (User E-Signature QR Code) -->
            <td>
                <div class="sig-role-title">Yang Membuat Pernyataan,</div>
                <div class="sig-card">
                    <div class="qr-box">
                        @if(isset($userQrCodeBase64) && $userQrCodeBase64)
                            <img src="data:image/svg+xml;base64,{{ $userQrCodeBase64 }}" alt="QR Tanda Tangan Peserta">
                        @endif
                    </div>
                    <div>
                        <span class="sig-stamp">DIGITALLY SIGNED BY CANDIDATE</span>
                    </div>
                    <div class="sig-name">{{ $user->name }}</div>
                    <div class="sig-sub">Peserta Magang / Pemilik Rekening</div>
                    <div style="font-size: 6pt; color: #94a3b8; margin-top: 2px;">
                        NIK: {{ $user->candidateProfile->nik ?? '-' }} • Tervalidasi Pengajuan: {{ $generatedAt->format('d/m/Y H:i') }} WIB
                    </div>
                </div>
            </td>

            <!-- 2. Super Admin (Super Admin Verification QR Code) -->
            <td>
                <div class="sig-role-title">Disetujui & Divalidasi Oleh,</div>
                <div class="sig-card">
                    <div class="qr-box">
                        @if(isset($adminQrCodeBase64) && $adminQrCodeBase64)
                            <img src="data:image/svg+xml;base64,{{ $adminQrCodeBase64 }}" alt="QR Otorisasi Super Admin">
                        @endif
                    </div>
                    <div>
                        <span class="sig-stamp sig-stamp-admin">SUPER ADMIN VERIFIED & APPROVED</span>
                    </div>
                    <div class="sig-name">{{ $superAdmin->name ?? 'Super Administrator' }}</div>
                    <div class="sig-sub">Super Admin & Head of Finance</div>
                    <div style="font-size: 6pt; color: #94a3b8; margin-top: 2px;">
                        ID: ADM-{{ str_pad($superAdmin->id ?? 1, 4, '0', STR_PAD_LEFT) }} • Career Hub Finance Authority
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini diterbitkan secara otomatis dan terotentikasi oleh Sistem Manajemen Magang & Karir Terpadu. Keabsahan data dan nomor rekening terdaftar pada basis data resmi dan dapat diverifikasi melalui pemindaian QR Code peserta & administrator di atas.
    </div>

</body>
</html>
