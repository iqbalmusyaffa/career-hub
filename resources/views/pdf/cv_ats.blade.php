<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CV ATS Friendly - {{ $user->name }}</title>
    <style>
        @page {
            margin: 25px 35px;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
            line-height: 1.45;
            font-size: 11px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .name {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .position {
            font-size: 12px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        .contact {
            font-size: 10px;
            color: #334155;
        }
        .section-heading {
            font-size: 11.5px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 2px;
            margin-top: 14px;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .item-table {
            width: 100%;
            margin-bottom: 8px;
            border-collapse: collapse;
        }
        .job-title {
            font-weight: bold;
            font-size: 11px;
            color: #0f172a;
        }
        .company-name {
            font-weight: bold;
            color: #2563eb;
        }
        .date-range {
            text-align: right;
            font-size: 10px;
            color: #475569;
        }
        .desc-text {
            font-size: 10px;
            color: #334155;
            margin-top: 2px;
            text-align: justify;
        }
        .skill-badge {
            display: inline-block;
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 2px 7px;
            border: 1px solid #cbd5e1;
            font-size: 9.5px;
            margin-right: 4px;
            margin-bottom: 4px;
            border-radius: 3px;
        }
        p { margin: 0 0 3px 0; }
    </style>
</head>
<body>

    <!-- Header Section (ATS Center Standard) -->
    <div class="header">
        <div class="name">{{ $user->name }}</div>
        <div class="position">{{ $profile->current_position ?? 'Kandidat Profesional' }}</div>
        <div class="contact">
            Email: {{ $user->email }}
            @if($profile->phone) | No. HP: {{ $profile->phone }} @endif
            @if($profile->address) | Lokasi: {{ $profile->address }} @endif
            @if($profile->dob) | Tgl Lahir: {{ $profile->dob->format('d M Y') }} @endif
            @if($profile->gender) | Gender: {{ ucfirst($profile->gender) }} @endif
        </div>
    </div>

    <!-- Ringkasan Profil -->
    @if($profile->summary)
        <div class="section-heading">Ringkasan Eksekutif & Profil</div>
        <p class="desc-text">{{ $profile->summary }}</p>
    @endif

    <!-- Pengalaman Kerja -->
    @if(!empty($profile->experiences) && is_array($profile->experiences))
        <div class="section-heading">Pengalaman Kerja & Profesional</div>
        @foreach($profile->experiences as $exp)
            <div style="margin-bottom: 8px;">
                <table class="item-table">
                    <tr>
                        <td style="vertical-align: top;">
                            <span class="job-title">{{ $exp['title'] ?? ($exp['position'] ?? 'Posisi Pekerjaan') }}</span>
                            @if(!empty($exp['company']))
                                <span class="company-name"> — {{ $exp['company'] }}</span>
                            @endif
                        </td>
                        <td class="date-range" style="vertical-align: top;">
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

    <!-- Pendidikan -->
    @if(!empty($profile->educations) && is_array($profile->educations))
        <div class="section-heading">Pendidikan Akademis</div>
        @foreach($profile->educations as $edu)
            <div style="margin-bottom: 6px;">
                <table class="item-table">
                    <tr>
                        <td style="vertical-align: top;">
                            <span class="job-title">{{ $edu['institution'] ?? ($edu['school'] ?? 'Institusi Pendidikan') }}</span>
                            <span style="color: #475569;"> — {{ $edu['degree'] ?? '' }} {{ $edu['field_of_study'] ?? '' }}</span>
                        </td>
                        <td class="date-range" style="vertical-align: top;">
                            {{ $edu['start_year'] ?? '' }} - {{ $edu['end_year'] ?? 'Selesai' }}
                        </td>
                    </tr>
                </table>
                @if(!empty($edu['gpa']))
                    <div class="desc-text"><strong>IPK/Nilai:</strong> {{ $edu['gpa'] }}</div>
                @endif
            </div>
        @endforeach
    @endif

    <!-- Pengalaman Organisasi -->
    @if(!empty($profile->organizations) && is_array($profile->organizations))
        <div class="section-heading">Pengalaman Organisasi & Komunitas</div>
        @foreach($profile->organizations as $org)
            <div style="margin-bottom: 6px;">
                <table class="item-table">
                    <tr>
                        <td style="vertical-align: top;">
                            <span class="job-title">{{ $org['position'] ?? 'Anggota' }}</span>
                            <span class="company-name"> — {{ $org['name'] ?? 'Organisasi' }}</span>
                            @if(!empty($org['level'])) <span style="font-size: 8pt; color: #64748b;">({{ $org['level'] }})</span> @endif
                        </td>
                        <td class="date-range" style="vertical-align: top;">
                            @if(!empty($org['start_date']))
                                {{ \Carbon\Carbon::parse($org['start_date'])->format('M Y') }} - {{ (!empty($org['is_current']) && $org['is_current']) ? 'Sekarang' : (!empty($org['end_date']) ? \Carbon\Carbon::parse($org['end_date'])->format('M Y') : 'Selesai') }}
                            @else
                                {{ $org['period'] ?? '' }}
                            @endif
                        </td>
                    </tr>
                </table>
                @if(!empty($org['location']))
                    <div style="font-size: 8pt; color: #64748b; margin-top: 1px;">Lokasi: {{ $org['location'] }}</div>
                @endif
                @if(!empty($org['description']))
                    <div class="desc-text" style="margin-top: 2px;">{{ $org['description'] }}</div>
                @endif
            </div>
        @endforeach
    @endif

    <!-- Keahlian Utama -->
    @if(!empty($profile->skills) && is_array($profile->skills))
        <div class="section-heading">Keahlian & Kompetensi Teknis</div>
        <div style="margin-top: 4px;">
            @foreach($profile->skills as $skill)
                <span class="skill-badge">{{ is_array($skill) ? ($skill['name'] ?? implode(', ', $skill)) : $skill }}</span>
            @endforeach
        </div>
    @endif

    <!-- Sertifikasi & Pelatihan -->
    @if(!empty($profile->certificates) && is_array($profile->certificates))
        <div class="section-heading">Sertifikasi & Lisensi</div>
        @foreach($profile->certificates as $cert)
            <div style="margin-bottom: 4px;" class="desc-text">
                <strong>• {{ is_array($cert) ? ($cert['name'] ?? '') : $cert }}</strong>
                @if(is_array($cert) && !empty($cert['issuer'])) (Penerbit: {{ $cert['issuer'] }}) @endif
                @if(is_array($cert) && !empty($cert['year'])) - Tahun {{ $cert['year'] }} @endif
            </div>
        @endforeach
    @endif

    <!-- Bahasa -->
    @if(!empty($profile->languages) && is_array($profile->languages))
        <div class="section-heading">Kemampuan Bahasa</div>
        <div class="desc-text">
            @foreach($profile->languages as $index => $lang)
                <strong>{{ is_array($lang) ? ($lang['name'] ?? '') : $lang }}</strong>
                @if(is_array($lang) && !empty($lang['proficiency'])) (Tingkat: {{ $lang['proficiency'] }}) @endif
                {{ $index < count($profile->languages) - 1 ? ' • ' : '' }}
            @endforeach
        </div>
    @endif

</body>
</html>
