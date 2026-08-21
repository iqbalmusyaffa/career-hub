<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Creative Modern CV - {{ $user->name }}</title>
    <style>
        @page {
            margin: 0px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #334155;
            line-height: 1.4;
            font-size: 11px;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        table.container {
            width: 100%;
            border-collapse: collapse;
        }
        /* Left Sidebar Column */
        td.sidebar {
            width: 32%;
            background-color: #0f172a;
            color: #ffffff;
            padding: 30px 20px;
            vertical-align: top;
        }
        /* Right Main Content Column */
        td.main-content {
            width: 68%;
            padding: 30px 28px;
            vertical-align: top;
            background-color: #ffffff;
        }

        /* Sidebar Styling */
        .avatar-initial {
            width: 60px;
            height: 60px;
            background-color: #2563eb;
            color: #ffffff;
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            line-height: 60px;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .sidebar-title {
            font-size: 11px;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            border-bottom: 1px solid #334155;
            padding-bottom: 4px;
            margin-top: 22px;
            margin-bottom: 10px;
        }
        .contact-item {
            font-size: 10px;
            color: #cbd5e1;
            margin-bottom: 8px;
            word-break: break-all;
        }
        .skill-pill {
            display: inline-block;
            background-color: #1e293b;
            color: #60a5fa;
            border: 1px solid #334155;
            padding: 3px 7px;
            font-size: 9.5px;
            font-weight: 600;
            border-radius: 12px;
            margin-right: 3px;
            margin-bottom: 5px;
        }

        /* Main Content Styling */
        .header-name {
            font-size: 24px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 2px 0;
        }
        .header-position {
            font-size: 13px;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 14px;
        }
        .summary-box {
            background-color: #f8fafc;
            border-left: 4px solid #2563eb;
            padding: 10px 14px;
            font-size: 11px;
            color: #334155;
            margin-bottom: 20px;
            border-radius: 0 8px 8px 0;
        }
        .main-section-heading {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 4px;
            margin-top: 20px;
            margin-bottom: 12px;
        }
        .exp-card {
            margin-bottom: 14px;
        }
        .exp-title {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }
        .exp-company {
            font-size: 11px;
            font-weight: bold;
            color: #2563eb;
        }
        .exp-date {
            font-size: 9.5px;
            color: #64748b;
            text-align: right;
        }
        .exp-desc {
            font-size: 10.5px;
            color: #475569;
            margin-top: 4px;
        }
    </style>
</head>
<body>

    <table class="container">
        <tr>
            <!-- Left Dark Sidebar Column -->
            <td class="sidebar">
                <div class="avatar-initial">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div class="sidebar-title">Kontak</div>
                <div class="contact-item">📧 {{ $user->email }}</div>
                @if($profile->phone) <div class="contact-item">📞 {{ $profile->phone }}</div> @endif
                @if($profile->address) <div class="contact-item">📍 {{ $profile->address }}</div> @endif

                <!-- Keahlian Sidebar -->
                @if(!empty($profile->skills) && is_array($profile->skills))
                    <div class="sidebar-title">Keahlian</div>
                    <div>
                        @foreach($profile->skills as $skill)
                            <span class="skill-pill">{{ is_array($skill) ? ($skill['name'] ?? implode(', ', $skill)) : $skill }}</span>
                        @endforeach
                    </div>
                @endif

                <!-- Pendidikan Sidebar -->
                @if(!empty($profile->educations) && is_array($profile->educations))
                    <div class="sidebar-title">Pendidikan</div>
                    @foreach($profile->educations as $edu)
                        <div style="margin-bottom: 10px;">
                            <div style="font-weight: bold; font-size: 10.5px; color: #ffffff;">{{ $edu['institution'] ?? ($edu['school'] ?? 'Pendidikan') }}</div>
                            <div style="font-size: 9.5px; color: #94a3b8;">{{ $edu['degree'] ?? '' }} {{ $edu['field_of_study'] ?? '' }}</div>
                            <div style="font-size: 9px; color: #64748b;">{{ $edu['start_year'] ?? '' }} - {{ $edu['end_year'] ?? 'Selesai' }}</div>
                        </div>
                    @endforeach
                @endif

                <!-- Bahasa Sidebar -->
                @if(!empty($profile->languages) && is_array($profile->languages))
                    <div class="sidebar-title">Bahasa</div>
                    @foreach($profile->languages as $lang)
                        <div class="contact-item">
                            • {{ is_array($lang) ? ($lang['name'] ?? '') : $lang }}
                            @if(is_array($lang) && !empty($lang['proficiency']))
                                <span style="color: #94a3b8;">({{ $lang['proficiency'] }})</span>
                            @endif
                        </div>
                    @endforeach
                @endif
            </td>

            <!-- Right Main Content Column -->
            <td class="main-content">
                <div class="header-name">{{ $user->name }}</div>
                <div class="header-position">{{ $profile->current_position ?? 'Kandidat Profesional' }}</div>

                @if($profile->summary)
                    <div class="summary-box">
                        {{ $profile->summary }}
                    </div>
                @endif

                <!-- Pengalaman Kerja -->
                @if(!empty($profile->experiences) && is_array($profile->experiences))
                    <div class="main-section-heading">Pengalaman Kerja</div>
                    @foreach($profile->experiences as $exp)
                        <div class="exp-card">
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <div class="exp-title">{{ $exp['title'] ?? ($exp['position'] ?? 'Posisi Pekerjaan') }}</div>
                                        <div class="exp-company">{{ $exp['company'] ?? '' }}</div>
                                    </td>
                                    <td class="exp-date" style="vertical-align: top;">
                                        {{ $exp['start_date'] ?? '' }} - {{ (!empty($exp['is_current']) && $exp['is_current']) ? 'Sekarang' : ($exp['end_date'] ?? 'Selesai') }}
                                    </td>
                                </tr>
                            </table>
                            @if(!empty($exp['description']))
                                <div class="exp-desc">{{ $exp['description'] }}</div>
                            @endif
                        </div>
                    @endforeach
                @endif

                <!-- Pengalaman Organisasi -->
                @if(!empty($profile->organizations) && is_array($profile->organizations))
                    <div class="main-section-heading">Pengalaman Organisasi & Komunitas</div>
                    @foreach($profile->organizations as $org)
                        <div class="exp-card">
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <div class="exp-title">{{ $org['position'] ?? 'Anggota' }}</div>
                                        <div class="exp-company">{{ $org['name'] ?? 'Organisasi' }} @if(!empty($org['level'])) <span style="font-size: 8.5px; color: #64748b;">({{ $org['level'] }})</span> @endif</div>
                                    </td>
                                    <td class="exp-date" style="vertical-align: top;">
                                        @if(!empty($org['start_date']))
                                            {{ \Carbon\Carbon::parse($org['start_date'])->format('M Y') }} - {{ (!empty($org['is_current']) && $org['is_current']) ? 'Sekarang' : (!empty($org['end_date']) ? \Carbon\Carbon::parse($org['end_date'])->format('M Y') : 'Selesai') }}
                                        @else
                                            {{ $org['period'] ?? '' }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            @if(!empty($org['location']))
                                <div style="font-size: 8.5px; color: #64748b; margin-top: 1px;">📍 {{ $org['location'] }}</div>
                            @endif
                            @if(!empty($org['description']))
                                <div class="exp-desc">{{ $org['description'] }}</div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </td>
        </tr>
    </table>

</body>
</html>
