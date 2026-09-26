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

        <p>Halo&nbsp;<strong>{{ $offerLetter->user->name ?? 'Kandidat' }}</strong>,</p>

        <p>Kami dengan bangga menyampaikan bahwa Anda telah **LOLOS SELEKSI** dan kami menawarkan posisi sebagai&nbsp;<strong>{{ $offerLetter->position_title }}</strong>.</p>

        @php
            $isInternship = Str::contains(strtolower($offerLetter->job->work_type ?? ''), ['intern', 'magang']) || Str::contains(strtolower($offerLetter->position_title ?? ''), ['intern', 'magang']);
            $rawSal = trim((string)$offerLetter->offered_salary);
            
            if (str_contains($rawSal, '-') || str_contains(strtolower($rawSal), 's/d') || str_contains(strtolower($rawSal), 'sampai')) {
                $parts = preg_split('/(-|s\/d|sampai)/i', $rawSal);
                $formattedParts = [];
                foreach ($parts as $p) {
                    $pClean = preg_replace('/[^0-9]/', '', $p);
                    if ($pClean && is_numeric($pClean)) {
                        $formattedParts[] = 'Rp ' . number_format((float)$pClean, 0, ',', '.');
                    } else {
                        $formattedParts[] = trim($p);
                    }
                }
                $salaryFormatted = implode(' - ', $formattedParts);
            } else {
                $pClean = preg_replace('/[^0-9]/', '', $rawSal);
                if ($pClean && is_numeric($pClean) && strlen($pClean) >= 4) {
                    $salaryFormatted = 'Rp ' . number_format((float)$pClean, 0, ',', '.');
                } else {
                    $salaryFormatted = $rawSal;
                    if (!str_starts_with(strtoupper($salaryFormatted), 'RP')) {
                        $salaryFormatted = 'Rp ' . $salaryFormatted;
                    }
                }
            }

            if (!Str::contains(strtolower($salaryFormatted), ['bulan', 'hari', 'jam', 'proyek', 'tahun', 'bln'])) {
                $salaryFormatted .= ' / bulan';
            }
        @endphp
        <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 15px; margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #047857;">💼 Ringkasan Penawaran:</h4>
            <p style="margin: 4px 0; font-size: 14px;"><strong>{{ $isInternship ? 'Uang Saku (Stipend):' : 'Gaji Ditawarkan:' }}</strong> <span style="color: #059669; font-weight: bold;">{{ $salaryFormatted }}</span></p>
            <p style="margin: 4px 0; font-size: 14px;"><strong>{{ $isInternship ? 'Tanggal Mulai Magang:' : 'Tanggal Mulai Bekerja:' }}</strong> {{ $offerLetter->start_date ? \Carbon\Carbon::parse($offerLetter->start_date)->locale('id')->translatedFormat('d F Y') : '-' }}</p>
            <p style="margin: 4px 0; font-size: 14px;"><strong>Batas Waktu Konfirmasi:</strong> {{ $offerLetter->expiration_date ? \Carbon\Carbon::parse($offerLetter->expiration_date)->locale('id')->translatedFormat('d F Y') : '7 Hari Kerja' }}</p>
        </div>

        <p>Surat Penawaran Kerja (Offer Letter PDF) resmi telah dilampirkan pada email ini. Anda juga dapat melakukan konfirmasi penerimaan langsung melalui portal kandidat.</p>

        <div style="margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 15px; font-size: 12px; color: #9ca3af; text-align: center;">
            Email ini dikirim secara otomatis oleh Sistem Web Karir TalentFlow.
        </div>
    </div>
</body>
</html>
