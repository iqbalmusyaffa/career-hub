<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head>
    <meta charset="utf-8">
    <title>Template Surat Pengunduran Diri Magang</title>
    <!--[if gte mso 9]>
    <xml>
        <w:WordDocument>
            <w:View>Print</w:View>
            <w:Zoom>100</w:Zoom>
            <w:DoNotOptimizeForBrowser/>
        </w:WordDocument>
    </xml>
    <![endif]-->
    <style>
        body {
            font-family: 'Calibri', 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000000;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        .header-title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        .data-table td {
            padding: 4px 6px;
            vertical-align: top;
            font-size: 11pt;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            padding: 20px 10px;
            width: 50%;
        }
    </style>
</head>
<body>

    <table style="width: 100%; border-bottom: 2px solid #000000; margin-bottom: 20px; padding-bottom: 10px;">
        <tr>
            <td style="font-size: 14pt; font-weight: bold; text-transform: uppercase;">
                {{ $companyName }}
            </td>
            <td style="text-align: right; font-size: 10pt; color: #555555;">
                Tanggal: {{ date('d F Y') }}
            </td>
        </tr>
    </table>

    <div class="header-title">SURAT PERMOHONAN PENGUNDURAN DIRI MAGANG</div>

    <p>
        Kepada Yth.<br>
        <strong>Tim HR & Manajemen {{ $companyName }}</strong><br>
        Di Tempat
    </p>

    <p>Dengan hormat,</p>
    <p>Yang bertanda tangan di bawah ini:</p>

    <table class="data-table" style="margin-left: 15px; margin-bottom: 15px;">
        <tr>
            <td style="width: 25%; font-weight: bold;">Nama Lengkap</td>
            <td style="width: 3%;">:</td>
            <td style="width: 72%;">{{ $user->name }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Email Terdaftar</td>
            <td>:</td>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Posisi Penugasan</td>
            <td>:</td>
            <td>{{ $jobTitle }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Perusahaan Penempatan</td>
            <td>:</td>
            <td>{{ $companyName }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tanggal Efektif Berhenti</td>
            <td>:</td>
            <td>[ ................................................................ ]</td>
        </tr>
    </table>

    <p style="text-align: justify;">
        Dengan ini saya bermaksud untuk mengajukan permohonan pengunduran diri secara resmi dari pelaksanaan Program Magang (Internship) pada posisi dan perusahaan tersebut di atas, terhitung sejak tanggal efektif yang tertera.
    </p>

    <p style="text-align: justify;">
        <strong>Adapun alasan pengunduran diri saya adalah sebagai berikut:</strong><br>
        [ <i>Tuliskan alasan pengunduran diri Anda secara lengkap dan jelas di sini...</i> ]
    </p>

    <p style="text-align: justify;">
        <strong>Catatan Serah Terima Tugas & Dokumen (Handover):</strong><br>
        [ <i>Tuliskan daftar berkas atau tugas yang telah diserahterimakan kepada rekan kerja/mentor di sini...</i> ]
    </p>

    <p style="text-align: justify;">
        Saya berkomitmen untuk menyelesaikan tanggung jawab yang masih berjalan serta melakukan proses serah terima pekerjaan sebaik-baiknya sebelum tanggal efektif berakhir.
    </p>

    <p style="text-align: justify;">
        Saya mengucapkan terima kasih yang sebesar-besarnya atas kesempatan, ilmu, serta bimbingan yang telah diberikan selama masa magang di {{ $companyName }}. Saya juga memohon maaf yang tulus atas segala kekhilafan selama saya bertugas.
    </p>

    <p>Demikian surat permohonan pengunduran diri ini saya buat dengan sebenarnya tanpa paksaan dari pihak mana pun.</p>

    <br><br>

    <table class="signature-table">
        <tr>
            <td>
                Mengetahui / Menyetujui,<br>
                <strong>Mentor Pembimbing / HR</strong>
                <br><br><br><br><br>
                ( ................................................................ )<br>
                Pembimbing Lapangan / Manajemen HR
            </td>
            <td>
                Hormat Saya,<br>
                <strong>Peserta Magang</strong>
                <br><br><br><br><br>
                <u><strong>{{ $user->name }}</strong></u><br>
                Pemohon Pengunduran Diri
            </td>
        </tr>
    </table>

</body>
</html>
