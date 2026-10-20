<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Uang Saku - {{ $stipend->user?->name ?? 'Peserta' }} - {{ $stipend->period_label }}</title>
    <style>
        @page {
            margin: 22px 30px;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 8.5pt;
            line-height: 1.4;
            color: #0f172a;
            background: #ffffff;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .kop-company {
            font-size: 13pt;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }
        .kop-sub {
            font-size: 8pt;
            color: #475569;
            margin-top: 1px;
        }
        .kop-tagline {
            font-size: 7.5pt;
            color: #059669;
            font-weight: bold;
        }
        .double-divider {
            border-top: 2px solid #0f172a;
            border-bottom: 0.75px solid #0f172a;
            height: 2px;
            margin-bottom: 10px;
        }
        .doc-title-container {
            text-align: center;
            margin-bottom: 10px;
        }
        .doc-title {
            font-size: 11pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin-bottom: 1px;
        }
        .doc-number {
            font-size: 8pt;
            color: #475569;
            font-weight: bold;
        }
        .badge-paid {
            display: inline-block;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: bold;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-mandatory {
            display: inline-block;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #10b981;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 6.5pt;
            font-weight: 900;
            text-transform: uppercase;
        }
        .badge-optional {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .section-title {
            font-size: 8pt;
            font-weight: 800;
            text-transform: uppercase;
            color: #1e293b;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 2px;
            margin: 8px 0 4px 0;
            letter-spacing: 0.3px;
        }
        .section-desc {
            font-size: 7pt;
            color: #64748b;
            margin-bottom: 4px;
            font-style: italic;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 8pt;
        }
        .info-table td {
            padding: 2px 3px;
            vertical-align: top;
        }
        .info-label {
            width: 140px;
            color: #475569;
            font-weight: 600;
        }
        .info-colon {
            width: 8px;
            text-align: center;
            color: #64748b;
        }
        .info-value {
            color: #0f172a;
            font-weight: 500;
        }
        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0 8px 0;
            font-size: 8pt;
        }
        .breakdown-table th {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            font-weight: 700;
            color: #334155;
            text-align: left;
            font-size: 7.5pt;
        }
        .breakdown-table td {
            border: 1px solid #e2e8f0;
            padding: 4px 6px;
            color: #1e293b;
        }
        .total-row td {
            background: #f1f5f9;
            font-weight: 800;
            color: #0f172a;
            font-size: 8.5pt;
        }
        .highlight-net {
            background: #ecfdf5;
            border: 1.5px solid #10b981;
            padding: 6px 12px;
            border-radius: 5px;
            margin-bottom: 8px;
        }
        .highlight-net .label {
            font-size: 7.5pt;
            font-weight: 700;
            color: #065f46;
            text-transform: uppercase;
        }
        .highlight-net .amount {
            font-size: 13pt;
            font-weight: 900;
            color: #047857;
            margin-top: 1px;
        }
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .signatures-table td {
            width: 50%;
            vertical-align: top;
            padding: 2px 6px;
        }
        .sig-box {
            border: 1px dashed #cbd5e1;
            border-radius: 5px;
            padding: 6px;
            text-align: center;
            background: #fafafa;
        }
        .sig-title {
            font-size: 7.5pt;
            font-weight: 700;
            color: #334155;
            margin-bottom: 30px;
        }
        .sig-name {
            font-size: 8pt;
            font-weight: 800;
            color: #0f172a;
            text-decoration: underline;
        }
        .sig-sub {
            font-size: 7pt;
            color: #64748b;
        }
        .footer-note {
            margin-top: 8px;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
            font-size: 6.8pt;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    @php
        $recipient = $stipend->user ?? $user;
        $onboarding = $recipient?->candidateOnboarding;
        $allUserBenefits = $recipient ? \App\Models\InternshipUserBenefit::with('benefit')->where('user_id', $recipient->id)->get() : collect();
        
        $bpjsTk = $allUserBenefits->first(fn($b) => $b->benefit && $b->benefit->category === 'bpjs_tk');
        $bpjsKes = $allUserBenefits->first(fn($b) => $b->benefit && $b->benefit->category === 'bpjs_kes');
        
        $bpjsTkNumber = $bpjsTk?->policy_number ?: ($onboarding?->bpjs_ketenagakerjaan_number ?? '22094819204');
        $bpjsKesNumber = $bpjsKes?->policy_number ?: ($onboarding?->bpjs_kesehatan_number ?? '0002891048291');
        
        $optionalUserBenefits = $allUserBenefits->filter(fn($b) => $b->benefit && !$b->benefit->is_mandatory);
    @endphp

    <!-- KOP PERUSAHAAN -->
    <table class="header-table">
        <tr>
            <td style="width: 55px;">
                @if($stipend->company?->logo_url)
                    <img src="{{ public_path('storage/' . $stipend->company->logo_url) }}" style="max-height: 45px; max-width: 55px;" alt="Logo">
                @else
                    <div style="width: 42px; height: 42px; background: #059669; border-radius: 6px; text-align: center; color: white; line-height: 42px; font-weight: bold; font-size: 15pt;">
                        {{ strtoupper(substr($stipend->company?->company_name ?? 'TN', 0, 1)) }}
                    </div>
                @endif
            </td>
            <td>
                <div class="kop-company">{{ $stipend->company?->company_name ?? 'PT TECHNOVA ASIA DIGITAL' }}</div>
                <div class="kop-sub">{{ $stipend->company?->address ?? 'Gedung Menara TechNova Lt. 12, Jl. HR Rasuna Said, Jakarta Selatan' }}</div>
                <div class="kop-tagline">Sistem Manajemen & Payroll Terpadu Pemagangan Nasional</div>
            </td>
            <td style="text-align: right; width: 140px;">
                <div style="font-size: 7pt; color: #64748b;">Tanggal Cetak Slip:</div>
                <div style="font-size: 8pt; font-weight: bold; color: #0f172a;">{{ now()->translatedFormat('d F Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="double-divider"></div>

    <!-- TITLE & DOCUMENT REF -->
    <div class="doc-title-container">
        <div class="doc-title">SLIP BUKTI PENCAIRAN UANG SAKU MAGANG</div>
        <div class="doc-number">NO: SLIP/STIPEND/{{ str_replace('-', '/', $stipend->period_month) }}/{{ str_pad($stipend->id, 4, '0', STR_PAD_LEFT) }}</div>
        <div>
            <span class="badge-paid">&#10003; TELAH DITRANSFER (LUNAS)</span>
        </div>
    </div>

    <!-- I. IDENTITAS PENERIMA & REKENING BANK -->
    <div class="section-title">I. IDENTITAS PENERIMA & REKENING BANK</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Nama Peserta Magang</td>
            <td class="info-colon">:</td>
            <td class="info-value"><strong>{{ $recipient?->name }}</strong> (Sesuai KTP Terverifikasi)</td>
            <td class="info-label">Bank Penerima</td>
            <td class="info-colon">:</td>
            <td class="info-value"><strong>{{ $stipend->bank_name }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Email / Akun ID</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $recipient?->email }} (ID: #{{ $recipient?->id }})</td>
            <td class="info-label">Nomor Rekening</td>
            <td class="info-colon">:</td>
            <td class="info-value"><strong>{{ $stipend->bank_account_number }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Program & Batch</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $stipend->batch_name ?? 'Batch Magang 2 - Semester Ganjil 2026' }}</td>
            <td class="info-label">Pemilik Rekening</td>
            <td class="info-colon">:</td>
            <td class="info-value"><strong>{{ $stipend->bank_account_holder }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Periode Uang Saku</td>
            <td class="info-colon">:</td>
            <td class="info-value"><strong>{{ $stipend->period_label }}</strong></td>
            <td class="info-label">Nomor Pokok Wajib Pajak (NPWP)</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $onboarding?->npwp_number ?? '84.921.492.1-014.000' }}</td>
        </tr>
    </table>

    <!-- II. RINCIAN PRESENSI & PERHITUNGAN UANG SAKU -->
    <div class="section-title">II. RINCIAN PRESENSI & PERHITUNGAN UANG SAKU POKOK</div>
    <table class="breakdown-table">
        <thead>
            <tr>
                <th style="width: 52%;">Komponen Uang Saku & Presensi</th>
                <th style="width: 22%; text-align: center;">Keterangan Hari</th>
                <th style="width: 26%; text-align: right;">Nominal (IDR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Uang Saku Pokok Bulanan (Standar UMK)</strong>
                    <div style="font-size: 6.8pt; color: #64748b;">Alokasi {{ $stipend->total_working_days ?? 22 }} hari kerja efektif pada bulan {{ $stipend->period_label }}</div>
                </td>
                <td style="text-align: center;">1 Bulan ({{ $stipend->total_working_days ?? 22 }} HK)</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($stipend->base_nominal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>
                    <div>Presensi Hadir (WFO/WFH) Terverifikasi</div>
                    <div style="font-size: 6.8pt; color: #64748b;">Logbook kerja harian disetujui Mentor & Sistem</div>
                </td>
                <td style="text-align: center; color: #047857; font-weight: bold;">{{ $stipend->present_days }} Hari</td>
                <td style="text-align: right; color: #64748b;">-</td>
            </tr>
            <tr>
                <td>
                    <div>Izin / Sakit Resmi (Batas Toleransi: 5 Hari / Bulan)</div>
                    <div style="font-size: 6.8pt; color: #64748b;">
                        @if($stipend->excused_days <= 5)
                            Termasuk kuota toleransi bebas potongan
                        @else
                            Melebihi kuota toleransi {{ $stipend->excused_days - 5 }} hari (dikenakan potong proporsional)
                        @endif
                    </div>
                </td>
                <td style="text-align: center;">{{ $stipend->excused_days }} Hari</td>
                <td style="text-align: right; color: #64748b;">-</td>
            </tr>
            <tr>
                <td>
                    <div>Ketidakhadiran Tanpa Keterangan (Alpa)</div>
                    <div style="font-size: 6.8pt; color: #64748b;">Tidak mengisi logbook kerja harian</div>
                </td>
                <td style="text-align: center; color: {{ $stipend->unexcused_days > 0 ? '#b91c1c' : '#64748b' }}; font-weight: bold;">
                    {{ $stipend->unexcused_days }} Hari
                </td>
                <td style="text-align: right; color: #64748b;">-</td>
            </tr>
            @if($stipend->deduction_amount > 0)
                <tr>
                    <td style="color: #b91c1c;">
                        <strong>Pemotongan Ketidakhadiran (Deduction)</strong>
                        <div style="font-size: 6.8pt; color: #b91c1c;">
                            Formula: {{ max(0, $stipend->excused_days - 5) + $stipend->unexcused_days }} hari &#215; (Rp {{ number_format(round($stipend->base_nominal / ($stipend->total_working_days ?: 22)), 0, ',', '.') }}/hari)
                        </div>
                    </td>
                    <td style="text-align: center; color: #b91c1c; font-weight: bold;">
                        {{ max(0, $stipend->excused_days - 5) + $stipend->unexcused_days }} Hari Potong
                    </td>
                    <td style="text-align: right; color: #b91c1c; font-weight: bold;">
                        - Rp {{ number_format($stipend->deduction_amount, 0, ',', '.') }}
                    </td>
                </tr>
            @endif
            <tr class="total-row">
                <td colspan="2" style="text-align: right; font-weight: 900; text-transform: uppercase;">
                    TOTAL UANG SAKU DITERIMA BERSIH (NET STIPEND)
                </td>
                <td style="text-align: right; font-weight: 900; color: #047857; font-size: 9pt;">
                    Rp {{ number_format($stipend->net_amount, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- HIGHLIGHT NET TRANSFER -->
    <div class="highlight-net">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: middle;">
                    <div class="label">Total Nominal Ditransfer ke Rekening Peserta</div>
                    <div class="amount">Rp {{ number_format($stipend->net_amount, 0, ',', '.') }}</div>
                </td>
                <td style="text-align: right; vertical-align: middle; font-size: 7.5pt; color: #065f46;">
                    <div>Tanggal Transfer: <strong>{{ $stipend->transferred_at ? $stipend->transferred_at->translatedFormat('d F Y, H:i') . ' WIB' : now()->translatedFormat('d F Y') }}</strong></div>
                    <div>Status Payroll: <strong>VALID & LUNAS DIBAYARKAN</strong></div>
                </td>
            </tr>
        </table>
    </div>

    <!-- III. JAMINAN SOSIAL KETENAGAKERJAAN & KESEHATAN (WAJIB) -->
    <div class="section-title">
        III. JAMINAN SOSIAL KETENAGAKERJAAN & KESEHATAN (STATUS: WAJIB)
    </div>
    <div class="section-desc">
        *Wajib diikutsertakan oleh perusahaan penyelenggara magang berlandaskan Permenaker No. 6/2020. Iuran 100% ditanggung penuh oleh perusahaan.
    </div>
    <table class="breakdown-table">
        <thead>
            <tr>
                <th style="width: 32%;">Program Jaminan Sosial</th>
                <th style="width: 20%;">Nomor Kartu / Polis</th>
                <th style="width: 18%; text-align: center;">Status Kepatuhan</th>
                <th style="width: 15%; text-align: center;">Beban Peserta</th>
                <th style="width: 15%; text-align: right;">Iuran Perusahaan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>BPJS Ketenagakerjaan</strong>
                    <div style="font-size: 6.8pt; color: #64748b;">Jaminan Kecelakaan Kerja (JKK) & Jaminan Kematian (JKM)</div>
                    <div style="font-size: 6.5pt; color: #059669; font-weight: bold;">Dasar: Permenaker RI No. 6 Tahun 2020</div>
                </td>
                <td style="font-family: monospace; font-size: 8pt; font-weight: bold; color: #0f172a;">
                    {{ $bpjsTkNumber }}
                </td>
                <td style="text-align: center;">
                    <span class="badge-mandatory">&#10003; WAJIB - AKTIF</span>
                </td>
                <td style="text-align: center; color: #047857; font-weight: bold;">
                    Rp 0 (100% Bebas)
                </td>
                <td style="text-align: right; color: #047857; font-weight: bold;">
                    Ditanggung 100%
                </td>
            </tr>
            <tr>
                <td>
                    <strong>BPJS Kesehatan</strong>
                    <div style="font-size: 6.8pt; color: #64748b;">Jaminan Pemeliharaan Kesehatan Nasional (JKN-KIS)</div>
                    <div style="font-size: 6.5pt; color: #059669; font-weight: bold;">Dasar: Sistem Jaminan Sosial Nasional</div>
                </td>
                <td style="font-family: monospace; font-size: 8pt; font-weight: bold; color: #0f172a;">
                    {{ $bpjsKesNumber }}
                </td>
                <td style="text-align: center;">
                    <span class="badge-mandatory">&#10003; WAJIB - TERDAFTAR</span>
                </td>
                <td style="text-align: center; color: #047857; font-weight: bold;">
                    Rp 0 (Terdaftar)
                </td>
                <td style="text-align: right; color: #047857; font-weight: bold;">
                    Perlindungan Aktif
                </td>
            </tr>
        </tbody>
    </table>

    <!-- IV. FASILITAS & BENEFIT OPERASIONAL MAGANG LAINNYA (NON-WAJIB) -->
    <div class="section-title">
        IV. FASILITAS & BENEFIT OPERASIONAL MAGANG LAINNYA (STATUS: TIDAK WAJIB / OPSIONAL KANTOR)
    </div>
    <div class="section-desc">
        *Fasilitas penunjang kerja dan pengembangan skill peserta magang, dicantumkan demi transparansi total kompensasi pemagangan.
    </div>
    <table class="breakdown-table">
        <thead>
            <tr>
                <th style="width: 32%;">Fasilitas / Tunjangan Tambahan</th>
                <th style="width: 18%; text-align: center;">Status Fasilitas</th>
                <th style="width: 25%;">Keterangan & Akses</th>
                <th style="width: 25%; text-align: right;">Bentuk Kompensasi</th>
            </tr>
        </thead>
        <tbody>
            @if($optionalUserBenefits->isNotEmpty())
                @foreach($optionalUserBenefits as $ub)
                    <tr>
                        <td>
                            <strong>{{ $ub->benefit?->name }}</strong>
                            <div style="font-size: 6.8pt; color: #64748b;">Penyedia: {{ $ub->benefit?->provider ?? 'Perusahaan' }}</div>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge-optional">TIDAK WAJIB (OPSIONAL)</span>
                        </td>
                        <td style="font-size: 7.2pt; color: #334155;">
                            {{ $ub->notes ?: ($ub->benefit?->description ?? 'Fasilitas operasional magang.') }}
                            @if($ub->policy_number)
                                <div style="font-family: monospace; font-size: 6.8pt; color: #475569;">No: {{ $ub->policy_number }}</div>
                            @endif
                        </td>
                        <td style="text-align: right; font-weight: bold; color: #1e293b; font-size: 7.5pt;">
                            @if($ub->custom_nominal > 0 || ($ub->benefit && $ub->benefit->standard_nominal > 0))
                                Rp {{ number_format($ub->custom_nominal ?: $ub->benefit->standard_nominal, 0, ',', '.') }} / bln
                            @else
                                Fasilitas In-Kind Kantor
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>
                        <strong>Tunjangan Transportasi & Mobilitas</strong>
                        <div style="font-size: 6.8pt; color: #64748b;">Bantuan mobilitas transportasi harian magang</div>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge-optional">TIDAK WAJIB</span>
                    </td>
                    <td style="font-size: 7.2pt; color: #334155;">Ditransfer bersamaan dengan uang saku</td>
                    <td style="text-align: right; font-weight: bold; color: #1e293b;">Rp 500.000 / bln</td>
                </tr>
                <tr>
                    <td>
                        <strong>Makan Siang & Pantry Kantor</strong>
                        <div style="font-size: 6.8pt; color: #64748b;">Prasmanan katering & pantry gedung TechNova Tower</div>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge-optional">TIDAK WAJIB</span>
                    </td>
                    <td style="font-size: 7.2pt; color: #334155;">Akses makan siang & kopi espresso setiap hari kerja WFO</td>
                    <td style="text-align: right; font-weight: bold; color: #1e293b;">In-Kind (Tanpa Potongan)</td>
                </tr>
                <tr>
                    <td>
                        <strong>Laptop & Perangkat Kerja Inventaris</strong>
                        <div style="font-size: 6.8pt; color: #64748b;">MacBook Pro M2 (No. INV-TN-2026-084)</div>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge-optional">TIDAK WAJIB</span>
                    </td>
                    <td style="font-size: 7.2pt; color: #334155;">Peminjaman inventaris unit kerja & lisensi software</td>
                    <td style="text-align: right; font-weight: bold; color: #1e293b;">Aset Inventaris</td>
                </tr>
                <tr>
                    <td>
                        <strong>Program Mentoring 1-on-1 & Sertifikasi</strong>
                        <div style="font-size: 6.8pt; color: #64748b;">Bimbingan intensif mingguan Senior Lead Engineer</div>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge-optional">TIDAK WAJIB</span>
                    </td>
                    <td style="font-size: 7.2pt; color: #334155;">Sesi bimbingan, code review, dan sertifikat resmi</td>
                    <td style="text-align: right; font-weight: bold; color: #1e293b;">Pengembangan Karir</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- SIGNATURES / VALIDATION -->
    <table class="signatures-table">
        <tr>
            <td>
                <div class="sig-box">
                    <div class="sig-title">Diterima oleh Peserta Magang,</div>
                    <div class="sig-name">{{ $recipient?->name }}</div>
                    <div class="sig-sub">Peserta Program Magang Terdaftar</div>
                </div>
            </td>
            <td>
                <div class="sig-box">
                    <div class="sig-title">Disetujui oleh HR / Finance & People Operations,</div>
                    <div class="sig-name">{{ $stipend->verifier?->name ?? 'Dimas Wicaksono, S.Kom' }}</div>
                    <div class="sig-sub">{{ $stipend->company?->company_name ?? 'PT TechNova Asia Digital' }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini diterbitkan secara digital oleh Career Hub Internship Payroll System sesuai Peraturan Menteri Ketenagakerjaan RI No. 6 Tahun 2020. Validitas dokumen terverifikasi resmi dalam modul payroll dan kepesertaan jaminan magang internal.
    </div>

</body>
</html>
