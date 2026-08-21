<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Penawaran Kerja (Offer Letter)</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 16px; border: 1px solid #e5e7eb;">
        <div style="text-align: center; margin-bottom: 25px;">
            <h2 style="color: #059669; margin: 0;">🎉 Selamat! Surat Penawaran Kerja</h2>
            <p style="color: #6b7280; font-size: 13px; margin-top: 4px;">{{ $offerLetter->job->company_name ?? 'Perusahaan' }}</p>
        </div>

        <p>Halo <strong>{{ $offerLetter->user->name ?? 'Kandidat' }}</strong>,</p>

        <p>Kami dengan bangga menyampaikan bahwa Anda telah **LOLOS SELEKSI** dan kami menawarkan posisi sebagai <strong>{{ $offerLetter->position_title }}</strong>.</p>

        <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 15px; margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #047857;">💼 Ringkasan Penawaran:</h4>
            <p style="margin: 4px 0; font-size: 14px;"><strong>Gaji Ditawarkan:</strong> Rp {{ number_format((float) preg_replace('/[^0-9]/', '', $offerLetter->offered_salary), 0, ',', '.') }} / bulan</p>
            <p style="margin: 4px 0; font-size: 14px;"><strong>Tanggal Mulai Bekerja:</strong> {{ $offerLetter->start_date ? $offerLetter->start_date->format('d F Y') : '-' }}</p>
            <p style="margin: 4px 0; font-size: 14px;"><strong>Batas Waktu Konfirmasi:</strong> {{ $offerLetter->expiration_date ? $offerLetter->expiration_date->format('d F Y') : '7 Hari' }}</p>
        </div>

        <p>Surat Penawaran Kerja (Offer Letter PDF) resmi telah dilampirkan pada email ini. Anda juga dapat melakukan konfirmasi penerimaan langsung melalui portal kandidat.</p>

        <div style="margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 15px; font-size: 12px; color: #9ca3af; text-align: center;">
            Email ini dikirim secara otomatis oleh Sistem Web Karir TalentFlow.
        </div>
    </div>
</body>
</html>
