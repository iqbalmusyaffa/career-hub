<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Template Surat Pengunduran Diri Magang</title>
    <style>
        @page {
            margin: 25mm 20mm 25mm 20mm;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.6;
            font-size: 10.5pt;
            background: #ffffff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .company-name {
            font-size: 14pt;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .company-sub {
            font-size: 8.5pt;
            color: #64748b;
        }
        .doc-title-container {
            text-align: center;
            margin: 15px 0 20px 0;
        }
        .doc-title {
            font-size: 13pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
            text-decoration: underline;
            margin-bottom: 2px;
        }
        .data-table {
            width: 100%;
            margin: 12px 0;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 3.5px 4px;
            vertical-align: top;
            font-size: 10pt;
        }
        .data-label {
            width: 30%;
            color: #475569;
            font-weight: 600;
        }
        .data-colon {
            width: 3%;
            text-align: center;
        }
        .data-value {
            width: 67%;
            font-weight: 700;
            color: #0f172a;
        }
        .content-body {
            margin: 12px 0;
            text-align: justify;
            font-size: 10pt;
            color: #334155;
            line-height: 1.5;
        }
        .fill-box {
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            padding: 10px 14px;
            border-radius: 6px;
            margin: 10px 0;
            font-size: 9.5pt;
            color: #334155;
            min-height: 60px;
        }
        .signature-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }
        .sig-header {
            font-size: 9pt;
            color: #64748b;
            margin-bottom: 50px;
        }
        .sig-name {
            font-size: 10pt;
            font-weight: 800;
            color: #0f172a;
            border-top: 1px solid #94a3b8;
            padding-top: 4px;
            display: inline-block;
            min-width: 160px;
        }
        .sig-role {
            font-size: 8pt;
            color: #64748b;
            margin-top: 1px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <table class="header-table">
        <tr>
            <td>
                <div class="company-name">{{ $companyName }}</div>
                <div class="company-sub">Program Magang Bersertifikat & Pengembangan Karir</div>
            </td>
            <td style="text-align: right; vertical-align: middle; font-size: 8.5pt; color: #64748b;">
                Tanggal: {{ date('d F Y') }}
            </td>
        </tr>
    </table>

    <!-- JUDUL SURAT -->
    <div class="doc-title-container">
        <div class="doc-title">SURAT PERMOHONAN PENGUNDURAN DIRI MAGANG</div>
    </div>

    <!-- PEMBUKA -->
    <div class="content-body">
        Kepada Yth.<br>
        <strong>Tim HR & Manajemen {{ $companyName }}</strong><br>
        Di Tempat<br><br>
        Dengan hormat,<br>
        Yang bertanda tangan di bawah ini:
    </div>

    <!-- IDENTITAS PEMOHON -->
    <table class="data-table">
        <tr>
            <td class="data-label">Nama Lengkap</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $user->name }}</td>
        </tr>
        <tr>
            <td class="data-label">Email Terdaftar</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $user->email }}</td>
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
            <td class="data-label">Tanggal Efektif Berhenti</td>
            <td class="data-colon">:</td>
            <td class="data-value" style="color: #e11d48;">[ .................................................... ]</td>
        </tr>
    </table>

    <!-- ISI PERNYATAAN -->
    <div class="content-body">
        Dengan ini saya bermaksud untuk mengajukan permohonan pengunduran diri secara resmi dari pelaksanaan Program Magang (Internship) pada posisi tersebut di atas, terhitung sejak tanggal efektif yang tertera. Adapun alasan pengunduran diri saya adalah sebagai berikut:
    </div>

    <div class="fill-box">
        <strong>Alasan / Penjelasan Pengunduran Diri:</strong><br>
        <span style="color: #94a3b8; font-style: italic;">(Tuliskan alasan pengunduran diri Anda secara jelas dan sopan di sini...)</span>
    </div>

    <div class="content-body">
        Saya berkomitmen untuk menyelesaikan seluruh tanggung jawab yang sedang berjalan serta melakukan serah terima tugas dan dokumen kerja (handover) kepada mentor / rekan kerja sebelum tanggal efektif berakhir.
        <br><br>
        Saya mengucapkan terima kasih yang sebesar-besarnya atas kesempatan, bimbingan, serta ilmu yang telah diberikan selama masa magang di {{ $companyName }}. Saya juga menyampaikan permohonan maaf atas segala kekurangan maupun kesalahan selama bertugas.
        <br><br>
        Demikian surat permohonan ini saya buat dengan sebenarnya tanpa paksaan dari pihak mana pun.
    </div>

    <!-- TANDA TANGAN -->
    <table class="signature-table">
        <tr>
            <td>
                <div class="sig-header">
                    Mengetahui / Menyetujui,<br>
                    <strong>Mentor Pembimbing / HR</strong>
                </div>
                <div class="sig-name">( .................................................... )</div>
                <div class="sig-role">Pembimbing / Manajemen HR</div>
            </td>
            <td>
                <div class="sig-header">
                    Hormat Saya,<br>
                    <strong>Peserta Magang</strong>
                </div>
                <div class="sig-name">{{ $user->name }}</div>
                <div class="sig-role">Pemohon Pengunduran Diri</div>
            </td>
        </tr>
    </table>

</body>
</html>
