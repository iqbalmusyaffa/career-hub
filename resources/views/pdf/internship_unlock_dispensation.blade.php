<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Dispensasi Presensi - {{ $unlockRequest->intern->name }}</title>
    <style>
        @page {
            margin: 28px 35px;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 9.5pt;
            line-height: 1.5;
            color: #0f172a;
            background: #ffffff;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .kop-company {
            font-size: 13pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .kop-sub {
            font-size: 8.5pt;
            color: #475569;
            margin-top: 2px;
        }
        .kop-tagline {
            font-size: 7.5pt;
            color: #64748b;
            font-style: italic;
        }
        .double-divider {
            border-top: 2px solid #0f172a;
            border-bottom: 0.75px solid #0f172a;
            height: 2px;
            margin-bottom: 16px;
        }
        .doc-title-container {
            text-align: center;
            margin-bottom: 16px;
        }
        .doc-title {
            font-size: 12pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .doc-number {
            font-size: 8.5pt;
            color: #475569;
            font-weight: bold;
        }
        .badge-approved {
            display: inline-block;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section-title {
            font-size: 9pt;
            font-weight: 800;
            text-transform: uppercase;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
            margin: 12px 0 6px 0;
            letter-spacing: 0.3px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9pt;
        }
        .info-table td {
            padding: 3px 4px;
            vertical-align: top;
        }
        .info-label {
            width: 160px;
            color: #64748b;
            font-weight: 600;
        }
        .info-separator {
            width: 10px;
            color: #94a3b8;
        }
        .info-value {
            color: #0f172a;
            font-weight: 700;
        }
        .detail-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #2563eb;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 10px;
            font-size: 8.8pt;
        }
        .statement-text {
            text-align: justify;
            font-size: 8.8pt;
            line-height: 1.55;
            color: #334155;
            margin-bottom: 14px;
        }
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }
        .signatures-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 4px;
        }
        .sig-role-title {
            font-size: 8pt;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .sig-space {
            height: 55px;
            position: relative;
        }
        .sig-stamp {
            display: inline-block;
            border: 1.5px dashed #2563eb;
            color: #2563eb;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }
        .sig-name {
            font-size: 8.8pt;
            font-weight: 800;
            color: #0f172a;
            border-top: 1px solid #94a3b8;
            padding-top: 3px;
            display: inline-block;
            min-width: 140px;
        }
        .sig-sub {
            font-size: 7.5pt;
            color: #64748b;
            margin-top: 1px;
        }
        .footer-note {
            margin-top: 20px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 6px;
            font-size: 7.2pt;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT RESMI -->
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <div class="kop-company">{{ $unlockRequest->company->company_name ?? 'CAREER HUB & MITRA INDUSTRI' }}</div>
                <div class="kop-sub">Program Magang Bersertifikat & Pengembangan Talenta Karir</div>
                <div class="kop-tagline">Dokumen Resmi Berita Acara & Dispensasi Presensi Magang</div>
            </td>
            <td style="width: 30%; text-align: right;">
                <div style="font-size: 7.5pt; color: #64748b;">
                    Tanggal Terbit:<br>
                    <strong style="color: #0f172a; font-size: 8.5pt;">{{ $unlockRequest->updated_at ? $unlockRequest->updated_at->format('d F Y') : now()->format('d F Y') }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <div class="double-divider"></div>

    <!-- JUDUL DOKUMEN -->
    <div class="doc-title-container">
        <div class="doc-title">SURAT KETERANGAN DISPENSASI PRESENSI</div>
        <div class="doc-number">Nomor: DISP/{{ str_pad($unlockRequest->id, 4, '0', STR_PAD_LEFT) }}/MAGANG/{{ $unlockRequest->created_at ? $unlockRequest->created_at->format('Y') : date('Y') }}</div>
        <div>
            <span class="badge-approved">✓ DISETUJUI & DISAHKAN</span>
        </div>
    </div>

    <!-- PENGANTAR -->
    <div class="statement-text">
        Menerangkan bahwa permohonan pembukaan akses presensi untuk tanggal terlewat / dispensasi kehadiran khusus peserta magang di bawah ini telah ditinjau, diverifikasi, dan <strong>DISETUJUI</strong> oleh Manajemen Penyelenggara Magang dan Mentor Perusahaan:
    </div>

    <!-- DATA PESERTA & PROGRAM -->
    <div class="section-title">I. IDENTITAS PESERTA MAGANG</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Nama Lengkap</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $unlockRequest->intern->name }}</td>
        </tr>
        <tr>
            <td class="info-label">Email Terdaftar</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $unlockRequest->intern->email }}</td>
        </tr>
        <tr>
            <td class="info-label">Institusi / Kampus</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $unlockRequest->intern->candidateProfile->institution ?? 'Universitas / Perguruan Tinggi Mitra' }}</td>
        </tr>
        <tr>
            <td class="info-label">Posisi / Batch Magang</td>
            <td class="info-separator">:</td>
            <td class="info-value">
                @php
                    $latestApp = $unlockRequest->intern->applications()->with('job')->latest()->first();
                @endphp
                {{ $latestApp->job->title ?? 'Peserta Magang Industri' }} 
                @if(!empty($latestApp->job->batch))
                    ({{ $latestApp->job->batch }})
                @endif
            </td>
        </tr>
    </table>

    <!-- DATA MENTOR PEMBIMBING -->
    <div class="section-title">II. MENTOR PEMBIMBING LAPANGAN</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Nama Mentor Pengaju</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $unlockRequest->mentor->name }}</td>
        </tr>
        <tr>
            <td class="info-label">Perusahaan Mitra</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $unlockRequest->company->company_name ?? 'Mitra Perusahaan' }}</td>
        </tr>
        <tr>
            <td class="info-label">Email Mentor</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $unlockRequest->mentor->email }}</td>
        </tr>
    </table>

    <!-- RINCIAN DISPENSASI PRESENSI -->
    <div class="section-title">III. RINCIAN DISPENSASI & KEPUTUSAN SISTEM</div>
    <div class="detail-box">
        <table class="info-table" style="margin-bottom: 0;">
            <tr>
                <td class="info-label" style="width: 170px;">Tanggal Presensi Terkunci</td>
                <td class="info-separator">:</td>
                <td class="info-value" style="color: #1e3a8a;">
                    {{ $unlockRequest->target_date ? $unlockRequest->target_date->format('l, d F Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td class="info-label">Kategori Kendala Resmi</td>
                <td class="info-separator">:</td>
                <td class="info-value">
                    @if($unlockRequest->category === 'medical_emergency')
                        🏥 Sakit / Rawat Inap / Pengobatan Medis
                    @elseif($unlockRequest->category === 'academic_urgent')
                        🎓 Keperluan Akademik Kampus Mendesak (Wisuda/Sidang/Ijazah)
                    @elseif($unlockRequest->category === 'personal_urgent')
                        🚨 Keperluan Mendesak Pribadi / Keluarga Inti
                    @elseif($unlockRequest->category === 'dinas_luar')
                        💼 Penugasan Dinas Luar / Event Lapangan Mitra
                    @elseif($unlockRequest->category === 'cuti_bersama')
                        🏖️ Cuti Bersama / Hari Libur Tertentu (Dispensasi Magang)
                    @elseif($unlockRequest->category === 'libur_nasional_agenda')
                        🏛️ Penugasan Agenda Libur Nasional / Penyesuaian Kalender
                    @elseif($unlockRequest->category === 'platform_outage')
                        🌐 Gangguan Teknis Server Penyelenggara (Platform Outage)
                    @elseif($unlockRequest->category === 'partner_issue')
                        🏢 Kendala Operasional Resmi Mitra (Listrik/Jaringan)
                    @else
                        🚨 Keadaan Darurat Lapangan / Force Majeure
                    @endif
                </td>
            </tr>
            <tr>
                <td class="info-label">Uraian / Kronologi</td>
                <td class="info-separator">:</td>
                <td class="info-value" style="font-weight: normal; font-size: 8.5pt;">
                    {{ $unlockRequest->description }}
                </td>
            </tr>
            <tr>
                <td class="info-label">Akses Buka Kunci Berlaku</td>
                <td class="info-separator">:</td>
                <td class="info-value" style="color: #047857;">
                    {{ $unlockRequest->unlocked_until ? $unlockRequest->unlocked_until->format('d M Y H:i') . ' WIB' : 'Akses Terbuka Permanen' }}
                </td>
            </tr>
            <tr>
                <td class="info-label">Catatan Admin / HR</td>
                <td class="info-separator">:</td>
                <td class="info-value" style="font-weight: normal; color: #475569; font-size: 8.5pt;">
                    {{ $unlockRequest->admin_notes ?? 'Permohonan disetujui sesuai regulasi dan bukti pendukung yang sah.' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- PERNYATAAN INTEGRITAS -->
    <div class="statement-text">
        Demikian Surat Keterangan Dispensasi ini diterbitkan secara sah oleh sistem untuk dipergunakan sebagaimana mestinya sebagai bukti resmi dispensasi kehadiran dan logbook aktivitas magang.
    </div>

    <!-- TANDA TANGAN 3 PIHAK (PESERTA, MENTOR, ADMIN) -->
    <table class="signatures-table">
        <tr>
            <!-- 1. Peserta Magang -->
            <td>
                <div class="sig-role-title">Peserta Magang</div>
                <div class="sig-space">
                    <div style="font-size: 6.8pt; color: #64748b; margin-top: 15px;">(Tanda Tangan Mahasiswa)</div>
                </div>
                <div class="sig-name">{{ $unlockRequest->intern->name }}</div>
                <div class="sig-sub">Mahasiswa Magang</div>
            </td>

            <!-- 2. Mentor Pembimbing -->
            <td>
                <div class="sig-role-title">Mentor Pembimbing</div>
                <div class="sig-space">
                    <div class="sig-stamp">VERIFIED BY MENTOR</div>
                    <div style="font-size: 6.5pt; color: #64748b; margin-top: 3px;">Tervalidasi Digital</div>
                </div>
                <div class="sig-name">{{ $unlockRequest->mentor->name }}</div>
                <div class="sig-sub">{{ $unlockRequest->company->company_name ?? 'Pembimbing Lapangan' }}</div>
            </td>

            <!-- 3. Super Admin / HR -->
            <td>
                <div class="sig-role-title">HR & Super Admin</div>
                <div class="sig-space">
                    <div class="sig-stamp" style="border-color: #059669; color: #059669;">APPROVED & SIGNED</div>
                    <div style="font-size: 6.5pt; color: #64748b; margin-top: 3px;">
                        Oleh: {{ $unlockRequest->resolver->name ?? 'Super Administrator' }}
                    </div>
                </div>
                <div class="sig-name">{{ $unlockRequest->resolver->name ?? 'Super Administrator' }}</div>
                <div class="sig-sub">Manajemen Karir & Presensi</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini diterbitkan secara otomatis dan terotentikasi oleh Sistem Career Hub. Berlaku sebagai surat resmi pertanggungjawaban presensi magang.
    </div>

</body>
</html>
