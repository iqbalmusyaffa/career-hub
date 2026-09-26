<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CV ATS Friendly - {{ $user->name }}</title>
    <style>
        @page {
            margin: 28px 36px;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
            line-height: 1.4;
            font-size: 10pt;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1.5px solid #111827;
        }
        .name {
            font-size: 18pt;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .target-role {
            font-size: 11pt;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 4px;
        }
        .contact-info {
            font-size: 9pt;
            color: #374151;
            line-height: 1.35;
        }
        .section-title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #111827;
            padding-bottom: 2px;
            margin-top: 12px;
            margin-bottom: 6px;
        }
        .entry {
            margin-bottom: 8px;
        }
        .entry-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        .entry-title {
            font-size: 10pt;
            font-weight: bold;
            color: #000000;
        }
        .entry-company {
            font-size: 9.5pt;
            font-weight: bold;
            color: #1f2937;
        }
        .entry-date {
            text-align: right;
            font-size: 9pt;
            color: #374151;
            white-space: nowrap;
            vertical-align: top;
        }
        .entry-location {
            font-size: 8.5pt;
            color: #4b5563;
            font-style: italic;
        }
        .bullet-list {
            margin: 2px 0 4px 16px;
            padding: 0;
            font-size: 9pt;
            color: #1f2937;
        }
        .bullet-list li {
            margin-bottom: 2px;
            line-height: 1.35;
            text-align: justify;
        }
        .plain-text {
            font-size: 9pt;
            color: #1f2937;
            line-height: 1.35;
            text-align: justify;
            margin: 0;
        }
        .skills-list {
            font-size: 9pt;
            color: #1f2937;
            line-height: 1.45;
        }
    </style>
</head>
<body>

    <!-- Header / Contact Block (Standard Linear ATS) -->
    <div class="header">
        <div class="name">{{ $user->name }}</div>
        @if(!empty($profile->current_position))
            <div class="target-role">{{ $profile->current_position }}</div>
        @endif
        <div class="contact-info">
            {{ $user->email }}
            @if(!empty($profile->phone)) • {{ $profile->phone }} @endif
            @if(!empty($profile->address)) • {{ $profile->address }} @endif
            @if(!empty($profile->social_links['linkedin'])) • {{ $profile->social_links['linkedin'] }} @endif
            @if(!empty($profile->social_links['portfolio'])) • {{ $profile->social_links['portfolio'] }} @endif
        </div>
    </div>

    <!-- 1. Ringkasan Profesional -->
    @if(!empty($profile->summary))
        <div class="section-title">RINGKASAN PROFESIONAL</div>
        <p class="plain-text">{{ $profile->summary }}</p>
    @endif

    <!-- 2. Pengalaman Kerja -->
    @if(!empty($profile->experiences) && is_array($profile->experiences) && count($profile->experiences) > 0)
        <div class="section-title">PENGALAMAN KERJA</div>
        @foreach($profile->experiences as $exp)
            <div class="entry">
                <table class="entry-table">
                    <tr>
                        <td style="vertical-align: top;">
                            <span class="entry-title">{{ $exp['title'] ?? ($exp['position'] ?? 'Posisi Pekerjaan') }}</span>
                            @if(!empty($exp['company']))
                                <span class="entry-company"> — {{ $exp['company'] }}</span>
                            @endif
                            @if(!empty($exp['location']))
                                <span class="entry-location">({{ $exp['location'] }})</span>
                            @endif
                        </td>
                        <td class="entry-date">
                            {{ $exp['start_date'] ?? '' }} – {{ (!empty($exp['is_current']) && $exp['is_current']) ? 'Sekarang' : ($exp['end_date'] ?? 'Selesai') }}
                        </td>
                    </tr>
                </table>

                @if(!empty($exp['description']))
                    @php
                        $lines = preg_split('/\r\n|\r|\n/', trim($exp['description']));
                        $lines = array_filter(array_map('trim', $lines), fn($l) => !empty($l));
                    @endphp
                    @if(count($lines) > 1)
                        <ul class="bullet-list">
                            @foreach($lines as $line)
                                <li>{{ ltrim($line, "•-*\t ") }}</li>
                            @endforeach
                        </ul>
                    @else
                        <ul class="bullet-list">
                            <li>{{ ltrim($lines[0] ?? $exp['description'], "•-*\t ") }}</li>
                        </ul>
                    @endif
                @endif
            </div>
        @endforeach
    @endif

    <!-- 3. Pendidikan -->
    @if(!empty($profile->educations) && is_array($profile->educations) && count($profile->educations) > 0)
        <div class="section-title">PENDIDIKAN</div>
        @foreach($profile->educations as $edu)
            <div class="entry">
                <table class="entry-table">
                    <tr>
                        <td style="vertical-align: top;">
                            <span class="entry-title">{{ $edu['institution'] ?? ($edu['school'] ?? 'Institusi Pendidikan') }}</span>
                            @if(!empty($edu['degree']) || !empty($edu['field_of_study']))
                                <span class="entry-company"> — {{ $edu['degree'] ?? '' }} {{ $edu['field_of_study'] ?? '' }}</span>
                            @endif
                        </td>
                        <td class="entry-date">
                            {{ $edu['start_year'] ?? ($edu['start_date'] ?? '') }} – {{ $edu['end_year'] ?? ($edu['end_date'] ?? 'Selesai') }}
                        </td>
                    </tr>
                </table>
                @if(!empty($edu['gpa']))
                    <div class="plain-text" style="font-size: 8.5pt; color: #374151;">IPK / Nilai: <strong>{{ $edu['gpa'] }}</strong></div>
                @endif
                @if(!empty($edu['description']))
                    <div class="plain-text" style="font-size: 8.5pt; color: #4b5563; margin-top: 1px;">{{ $edu['description'] }}</div>
                @endif
            </div>
        @endforeach
    @endif

    <!-- 4. Keahlian & Kompetensi -->
    @if(!empty($profile->skills) && is_array($profile->skills) && count($profile->skills) > 0)
        @php
            $skillNames = array_map(function($s) {
                return is_array($s) ? ($s['name'] ?? implode(', ', $s)) : (string)$s;
            }, $profile->skills);
            $skillNames = array_filter(array_map('trim', $skillNames));
        @endphp
        @if(count($skillNames) > 0)
            <div class="section-title">KEAHLIAN & KOMPETENSI</div>
            <div class="skills-list">
                {{ implode(' • ', $skillNames) }}
            </div>
        @endif
    @endif

    <!-- 5. Pengalaman Organisasi -->
    @if(!empty($profile->organizations) && is_array($profile->organizations) && count($profile->organizations) > 0)
        <div class="section-title">PENGALAMAN ORGANISASI</div>
        @foreach($profile->organizations as $org)
            <div class="entry">
                <table class="entry-table">
                    <tr>
                        <td style="vertical-align: top;">
                            <span class="entry-title">{{ $org['position'] ?? 'Anggota' }}</span>
                            <span class="entry-company"> — {{ $org['name'] ?? 'Organisasi' }}</span>
                            @if(!empty($org['level'])) <span class="entry-location">({{ $org['level'] }})</span> @endif
                        </td>
                        <td class="entry-date">
                            @if(!empty($org['start_date']))
                                {{ \Carbon\Carbon::parse($org['start_date'])->format('M Y') }} – {{ (!empty($org['is_current']) && $org['is_current']) ? 'Sekarang' : (!empty($org['end_date']) ? \Carbon\Carbon::parse($org['end_date'])->format('M Y') : 'Selesai') }}
                            @else
                                {{ $org['period'] ?? '' }}
                            @endif
                        </td>
                    </tr>
                </table>
                @if(!empty($org['description']))
                    <div class="plain-text" style="font-size: 8.5pt; color: #374151; margin-top: 1px;">
                        • {{ $org['description'] }}
                    </div>
                @endif
            </div>
        @endforeach
    @endif

    <!-- 6. Sertifikasi & Pelatihan -->
    @if(!empty($profile->certificates) && is_array($profile->certificates) && count($profile->certificates) > 0)
        <div class="section-title">SERTIFIKASI & LISENSI</div>
        <ul class="bullet-list">
            @foreach($profile->certificates as $cert)
                <li>
                    <strong>{{ is_array($cert) ? ($cert['name'] ?? '') : $cert }}</strong>
                    @if(is_array($cert) && !empty($cert['issuer'])) — {{ $cert['issuer'] }} @endif
                    @if(is_array($cert) && !empty($cert['year'])) ({{ $cert['year'] }}) @endif
                </li>
            @endforeach
        </ul>
    @endif

    <!-- 7. Kemampuan Bahasa -->
    @if(!empty($profile->languages) && is_array($profile->languages) && count($profile->languages) > 0)
        <div class="section-title">KEMAMPUAN BAHASA</div>
        <div class="skills-list">
            @foreach($profile->languages as $index => $lang)
                <strong>{{ is_array($lang) ? ($lang['name'] ?? '') : $lang }}</strong>@if(is_array($lang) && !empty($lang['proficiency'])) ({{ $lang['proficiency'] }})@endif{{ $index < count($profile->languages) - 1 ? ' • ' : '' }}
            @endforeach
        </div>
    @endif

</body>
</html>
