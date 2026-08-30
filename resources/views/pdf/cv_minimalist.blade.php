<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CV Executive Minimalist - {{ $user->name }}</title>
    <style>
        @page {
            margin: 30px 40px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            line-height: 1.5;
            font-size: 10.5px;
        }
        .header {
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid {{ $accentColor ?? '#0f172a' }};
        }
        .name {
            font-size: 24px;
            font-weight: 800;
            color: {{ $accentColor ?? '#0f172a' }};
            letter-spacing: -0.5px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .position {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .contact-bar {
            font-size: 9.5px;
            color: #475569;
            background: #f8fafc;
            padding: 6px 12px;
            border-radius: 4px;
            border-left: 3px solid {{ $accentColor ?? '#0f172a' }};
        }
        .section-title {
            font-size: 11px;
            font-weight: 800;
            color: {{ $accentColor ?? '#0f172a' }};
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 16px;
            margin-bottom: 8px;
            padding-bottom: 3px;
            border-bottom: 1px solid #e2e8f0;
        }
        .item-table {
            width: 100%;
            margin-bottom: 6px;
            border-collapse: collapse;
        }
        .item-title {
            font-weight: 700;
            font-size: 11px;
            color: #0f172a;
        }
        .item-sub {
            font-weight: 600;
            color: {{ $accentColor ?? '#2563eb' }};
        }
        .item-date {
            text-align: right;
            font-size: 9.5px;
            color: #64748b;
            font-weight: 500;
        }
        .desc-text {
            font-size: 10px;
            color: #334155;
            margin-top: 3px;
            text-align: justify;
        }
        .skill-pill {
            display: inline-block;
            background-color: #f1f5f9;
            color: #1e293b;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            margin-right: 5px;
            margin-bottom: 5px;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <div class="name">{{ $user->name }}</div>
        <div class="position">{{ $profile->current_position ?? 'Kandidat Profesional' }}</div>
        <div class="contact-bar">
            <strong>Email:</strong> {{ $user->email }}
            @if($profile->phone) | <strong>HP:</strong> {{ $profile->phone }} @endif
            @if($profile->address) | <strong>Lokasi:</strong> {{ $profile->address }} @endif
            @if($profile->dob) | <strong>Tgl Lahir:</strong> {{ $profile->dob->format('d M Y') }} @endif
        </div>
    </div>

    <!-- Executive Summary -->
    @if($profile->summary)
        <div class="section-title">Ringkasan Eksekutif</div>
        <div class="desc-text">{{ $profile->summary }}</div>
    @endif

    <!-- Work Experience -->
    @if(!empty($profile->experiences) && is_array($profile->experiences))
        <div class="section-title">Pengalaman Kerja & Profesional</div>
        @foreach($profile->experiences as $exp)
            <div style="margin-bottom: 10px;">
                <table class="item-table">
                    <tr>
                        <td style="vertical-align: top;">
                            <span class="item-title">{{ $exp['title'] ?? ($exp['position'] ?? 'Posisi Pekerjaan') }}</span>
                            @if(!empty($exp['company']))
                                <span class="item-sub"> | {{ $exp['company'] }}</span>
                            @endif
                        </td>
                        <td class="item-date" style="vertical-align: top;">
                            {{ $exp['start_date'] ?? '' }} - {{ (!empty($exp['is_current']) && $exp['is_current']) ? 'Sekarang' : ($exp['end_date'] ?? 'Selesai') }}
                        </td>
                    </tr>
                </table>
                @if(!empty($exp['description']))
                    <div class="desc-text">{{ $exp['description'] }}</div>
                @endif
            </div>
        @endforeach
    @endif

    <!-- Education -->
    @if(!empty($profile->educations) && is_array($profile->educations))
        <div class="section-title">Pendidikan Akademis</div>
        @foreach($profile->educations as $edu)
            <div style="margin-bottom: 8px;">
                <table class="item-table">
                    <tr>
                        <td style="vertical-align: top;">
                            <span class="item-title">{{ $edu['institution'] ?? ($edu['school'] ?? 'Institusi Pendidikan') }}</span>
                            <span style="color: #475569;"> — {{ $edu['degree'] ?? '' }} {{ $edu['field_of_study'] ?? '' }}</span>
                        </td>
                        <td class="item-date" style="vertical-align: top;">
                            {{ $edu['start_year'] ?? '' }} - {{ $edu['end_year'] ?? 'Selesai' }}
                        </td>
                    </tr>
                </table>
                @if(!empty($edu['gpa']))
                    <div class="desc-text"><strong>IPK:</strong> {{ $edu['gpa'] }}</div>
                @endif
            </div>
        @endforeach
    @endif

    <!-- Skills -->
    @if(!empty($profile->skills) && is_array($profile->skills))
        <div class="section-title">Keahlian & Keahlian Utama</div>
        <div style="margin-top: 6px;">
            @foreach($profile->skills as $skill)
                <span class="skill-pill">{{ is_array($skill) ? ($skill['name'] ?? implode(', ', $skill)) : $skill }}</span>
            @endforeach
        </div>
    @endif

    <!-- Certifications -->
    @if(!empty($profile->certificates) && is_array($profile->certificates))
        <div class="section-title">Sertifikasi & Lisensi</div>
        @foreach($profile->certificates as $cert)
            <div style="margin-bottom: 4px;" class="desc-text">
                • <strong>{{ is_array($cert) ? ($cert['name'] ?? '') : $cert }}</strong>
                @if(is_array($cert) && !empty($cert['issuer'])) ({{ $cert['issuer'] }}) @endif
                @if(is_array($cert) && !empty($cert['year'])) — {{ $cert['year'] }} @endif
            </div>
        @endforeach
    @endif

    <!-- Languages -->
    @if(!empty($profile->languages) && is_array($profile->languages))
        <div class="section-title">Penguasaan Bahasa</div>
        <div class="desc-text">
            @foreach($profile->languages as $index => $lang)
                <strong>{{ is_array($lang) ? ($lang['name'] ?? '') : $lang }}</strong>
                @if(is_array($lang) && !empty($lang['proficiency'])) ({{ $lang['proficiency'] }}) @endif
                {{ $index < count($profile->languages) - 1 ? ' • ' : '' }}
            @endforeach
        </div>
    @endif

</body>
</html>
