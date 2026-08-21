<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Undangan Wawancara Kerja</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 16px; border: 1px solid #e5e7eb;">
        <div style="text-align: center; margin-bottom: 25px;">
            <h2 style="color: #2563eb; margin: 0;">TalentFlow Recruitment</h2>
            <p style="color: #6b7280; font-size: 13px; margin-top: 4px;">Undangan Resmi Wawancara Kerja</p>
        </div>

        <p>Halo <strong>{{ $interview->application->user->name ?? 'Kandidat' }}</strong>,</p>

        <p>Selamat! Lamaran Anda untuk posisi <strong>{{ $interview->application->job->title ?? '-' }}</strong> di <strong>{{ $interview->application->job->company_name ?? 'Perusahaan' }}</strong> telah lolos ke tahap Wawancara.</p>

        <div style="background-color: #fef3c7; border: 1px solid #fde68a; border-radius: 12px; padding: 15px; margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #92400e;">📌 Detail Jadwal Wawancara:</h4>
            <p style="margin: 4px 0; font-size: 14px;"><strong>Tanggal & Waktu:</strong> {{ $interview->scheduled_at->format('d F Y, H:i') }} WIB</p>
            <p style="margin: 4px 0; font-size: 14px;"><strong>Tipe Wawancara:</strong> {{ strtoupper($interview->type) }}</p>
            @if($interview->location_or_link)
                <p style="margin: 4px 0; font-size: 14px;"><strong>Lokasi / Link:</strong> <a href="{{ $interview->location_or_link }}" target="_blank" style="color: #2563eb;">{{ $interview->location_or_link }}</a></p>
            @endif
            @if($interview->notes)
                <p style="margin: 10px 0 0 0; font-size: 13px; font-style: italic; color: #78350f;">"{{ $interview->notes }}"</p>
            @endif
        </div>

        <p>Harap menyiapkan diri dan hadir 10 menit sebelum waktu pengerjaan yang ditentukan.</p>

        <div style="margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 15px; font-size: 12px; color: #9ca3af; text-align: center;">
            Email ini dikirim secara otomatis oleh Sistem Web Karir TalentFlow.
        </div>
    </div>
</body>
</html>
