<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pernyataan Persetujuan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
            text-align: justify;
        }
        .data-row {
            margin-bottom: 10px;
        }
        .data-label {
            display: inline-block;
            width: 150px;
        }
        .signature-section {
            margin-top: 50px;
            float: right;
            text-align: center;
            width: 250px;
        }
        .signature-space {
            height: 80px;
        }
        .footer {
            clear: both;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">SURAT PERNYATAAN PERSETUJUAN</div>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini:</p>

        <div class="data-row">
            <span class="data-label">Nama Lengkap</span>: <strong>{{ $name }}</strong>
        </div>
        <div class="data-row">
            <span class="data-label">NIK (No. KTP)</span>: {{ $nik }}
        </div>
        <div class="data-row">
            <span class="data-label">Alamat Lengkap</span>: {{ $address }}
        </div>

        <p>Dengan ini menyatakan dengan sesungguhnya bahwa:</p>
        <ol>
            <li>Seluruh data, dokumen, dan informasi yang saya berikan melalui platform rekrutmen ini adalah benar, sah, dan dapat dipertanggungjawabkan kebenarannya.</li>
            <li>Saya memberikan persetujuan kepada pihak Perusahaan untuk memproses, menyimpan, dan menggunakan data pribadi saya untuk keperluan rekrutmen dan seleksi.</li>
            <li>Apabila di kemudian hari ditemukan bahwa data/dokumen yang saya berikan terbukti palsu atau tidak benar, saya bersedia menerima sanksi berupa pembatalan proses rekrutmen, pemutusan hubungan kerja (jika sudah diterima), serta diproses sesuai hukum yang berlaku.</li>
            <li>Saya bersedia mematuhi seluruh peraturan dan tata tertib yang berlaku di Perusahaan selama proses rekrutmen berlangsung.</li>
        </ol>

        <p>Demikian surat pernyataan ini saya buat dengan kesadaran penuh dan tanpa ada paksaan dari pihak manapun, untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature-section">
        <p>...................................., {{ $date }}</p>
        <p>Yang Membuat Pernyataan,</p>
        <div class="signature-space">
            <br/><br/><br/>
            (Materai Rp10.000,-)
        </div>
        <p><strong>({{ $name }})</strong></p>
    </div>

</body>
</html>
