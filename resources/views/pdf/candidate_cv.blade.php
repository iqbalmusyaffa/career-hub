<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Curriculum Vitae - {{ $user->name }}</title>
    <style>
        @page {
            margin: 25px 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            line-height: 1.5;
            font-size: 12px;
        }
        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .name {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .title {
            font-size: 14px;
            color: #475569;
            margin-top: 3px;
        }
        .contact-info {
            text-align: right;
            font-size: 11px;
            color: #475569;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-top: 18px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .summary {
            background-color: #f8fafc;
            padding: 10px 12px;
            border-left: 3px solid #2563eb;
            margin-bottom: 15px;
            font-style: italic;
        }
        .item {
            margin-bottom: 12px;
        }
        .item-header {
            width: 100%;
        }
        .item-title {
            font-weight: bold;
            font-size: 12px;
            color: #0f172a;
        }
        .item-sub {
            font-weight: 600;
            color: #2563eb;
            font-size: 11px;
        }
        .item-date {
            text-align: right;
            font-size: 10px;
            color: #64748b;
        }
        .skill-badge {
            display: inline-block;
            background-color: #eff6ff;
            color: #1d4ed8;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            margin-right: 4px;
            margin-bottom: 4px;
            border: 1px solid #bfdbfe;
        }
        p {
            margin: 3px 0;
        }
        ul {
            margin: 4px 0 8px 18px;
            padding: 0;
        }
        li {
            margin-bottom: 3px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td style="vertical-align: top;">
                    <div class="name">{{ $user->name }}</div>
                    <div class="title">{{ $profile->current_position ?? 'Kandidat Profesional' }}</div>
                </td>
                <td class="contact-info" style="vertical-align: top;">
                    <div>📧 {{ $user->email }}</div>
                    @if($profile->phone) <div>📞 {{ $profile->phone }}</div> @endif
                    @if($profile->address) <div>📍 {{ $profile->address }}</div> @endif
                </td>
            </tr>
        </table>
    </div>

    @if($profile->summary)
        <div class="summary">
            {{ $profile->summary }}
        </div>
    @endif

    <!-- Pengalaman Kerja -->
    @if(!empty($profile->experiences) && is_array($profile->experiences))
        <div class="section-title">Pengalaman Kerja</div>
        @foreach($profile->experiences as $exp)
            <div class="item">
                <table class="item-header">
                    <tr>
                        <td>
                            <span class="item-title">{{ $exp['title'] ?? ($exp['position'] ?? 'Posisi Pekerjaan') }}</span>
                            <span class="item-sub"> - {{ $exp['company'] ?? '' }}</span>
                        </td>
                        <td class="item-date">
                            {{ $exp['start_date'] ?? '' }} - {{ $exp['is_current'] ?? false ? 'Sekarang' : ($exp['end_date'] ?? '') }}
                        </td>
                    </tr>
                </table>
                @if(!empty($exp['description']))
                    <p>{{ $exp['description'] }}</p>
                @endif
            </div>
        @endforeach
    @endif

    <!-- Pendidikan -->
    @if(!empty($profile->educations) && is_array($profile->educations))
        <div class="section-title">Pendidikan</div>
        @foreach($profile->educations as $edu)
            <div class="item">
                <table class="item-header">
                    <tr>
                        <td>
                            <span class="item-title">{{ $edu['institution'] ?? ($edu['school'] ?? 'Institusi Pendidikan') }}</span>
                            <span class="item-sub"> - {{ $edu['degree'] ?? '' }} {{ $edu['field_of_study'] ?? '' }}</span>
                        </td>
                        <td class="item-date">
                            {{ $edu['start_year'] ?? '' }} - {{ $edu['end_year'] ?? 'Selesai' }}
                        </td>
                    </tr>
                </table>
                @if(!empty($edu['gpa']))
                    <p>IPK/Nilai: {{ $edu['gpa'] }}</p>
                @endif
            </div>
        @endforeach
    @endif

    <!-- Keahlian / Skills -->
    @if(!empty($profile->skills) && is_array($profile->skills))
        <div class="section-title">Keahlian & Kompetensi</div>
        <div style="margin-top: 5px;">
            @foreach($profile->skills as $skill)
                <span class="skill-badge">{{ is_array($skill) ? ($skill['name'] ?? implode(', ', $skill)) : $skill }}</span>
            @endforeach
        </div>
    @endif

    <!-- Bahasa -->
    @if(!empty($profile->languages) && is_array($profile->languages))
        <div class="section-title">Bahasa</div>
        <ul>
            @foreach($profile->languages as $lang)
                <li>
                    <strong>{{ is_array($lang) ? ($lang['name'] ?? '') : $lang }}</strong>
                    @if(is_array($lang) && !empty($lang['proficiency']))
                        ({{ $lang['proficiency'] }})
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

</body>
</html>
