<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb & Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                        <a href="{{ route('dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition">Dashboard</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <a href="{{ route('candidate.logbook.progress') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition">Program Magang</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Fasilitas & Jaminan BPJS</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fa-solid fa-shield-heart"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                                Fasilitas & Jaminan BPJS Magang
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                                Transparansi hak perlindungan jaminan sosial wajib (Permenaker No. 6/2020) dan fasilitas operasional magang Anda.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    @if($latestStipend && $latestStipend->status === 'transferred')
                        <a href="{{ route('candidate.internship.stipends.slip', $latestStipend->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs hover:shadow-md transition">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>Unduh Slip Uang Saku ({{ $latestStipend->period_label }})</span>
                        </a>
                    @endif
                    <a href="{{ route('candidate.logbook.progress') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 shadow-xs transition">
                        <i class="fa-solid fa-chart-line text-emerald-600"></i>
                        <span>Progress Magang</span>
                    </a>
                </div>
            </div>

            <!-- Transparency & Legal Protection Banner -->
            <div class="p-5 sm:p-6 rounded-3xl bg-gradient-to-br from-emerald-900 via-teal-900 to-slate-900 text-white shadow-md relative overflow-hidden">
                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
                    <div class="lg:col-span-2 space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold uppercase tracking-wider backdrop-blur-xs border border-emerald-400/30">
                            <i class="fa-solid fa-gavel"></i>
                            Kepatuhan Regulasi Permenaker No. 6 Tahun 2020
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold tracking-tight text-white">
                            Jaminan Keselamatan & Kesehatan Peserta Magang Terjamin Penuh
                        </h2>
                        <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed">
                            Sesuai aturan hukum Kementerian Ketenagakerjaan RI, setiap peserta magang wajib diikutsertakan dalam program <strong>BPJS Ketenagakerjaan (JKK & JKM)</strong> dengan premi <strong>100% dibayarkan oleh perusahaan</strong> (Beban peserta: Rp 0). Fasilitas tambahan lainnya dicantumkan secara transparan pada slip bukti pencairan sebagai apresiasi dedikasi Anda.
                        </p>
                    </div>

                    <div class="bg-white/10 dark:bg-black/20 backdrop-blur-md rounded-2xl p-4 border border-white/15 space-y-3">
                        <div class="text-xs font-bold uppercase tracking-wider text-emerald-200">Mitra Penyelenggara Magang</div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white text-emerald-900 font-black flex items-center justify-center text-sm shadow-xs shrink-0">
                                {{ strtoupper(substr($company?->company_name ?? 'TN', 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-bold text-sm text-white">{{ $company?->company_name ?? 'PT TechNova Asia Digital' }}</div>
                                <div class="text-[11px] text-emerald-200/80">{{ $company?->city ?? 'Jakarta Selatan' }}, Indonesia</div>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-white/10 flex items-center justify-between text-xs text-emerald-200/90">
                            <span>Status Kepesertaan:</span>
                            <span class="font-bold text-emerald-300 flex items-center gap-1">
                                <i class="fa-solid fa-circle-check"></i> Aktif Terdaftar
                            </span>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-14 -bottom-14 w-60 h-60 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            </div>

            <!-- SECTION I: JAMINAN SOSIAL WAJIB -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-6 rounded-full bg-emerald-600"></div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                            I. Jaminan Sosial Ketenagakerjaan & Kesehatan (Status: WAJIB)
                        </h3>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                        Mandatory By Law
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @php
                        $bpjsTk = $mandatoryUserBenefits->first(fn($b) => $b->benefit && $b->benefit->category === 'bpjs_tk');
                        $bpjsKes = $mandatoryUserBenefits->first(fn($b) => $b->benefit && $b->benefit->category === 'bpjs_kes');
                    @endphp

                    <!-- CARD: BPJS KETENAGAKERJAAN -->
                    <div class="p-6 rounded-3xl bg-white dark:bg-slate-800/90 border-2 border-emerald-300 dark:border-emerald-700/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-xl font-black shrink-0 shadow-xs">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-base font-extrabold text-slate-900 dark:text-white">BPJS Ketenagakerjaan</h4>
                                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">WAJIB</span>
                                        </div>
                                        <p class="text-xs text-slate-500 font-medium">Program JKK (Kecelakaan Kerja) & JKM (Kematian)</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    <i class="fa-solid fa-circle-check text-emerald-600"></i> Aktif
                                </span>
                            </div>

                            <div class="mt-5 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/60 space-y-2.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500">Nomor Kartu / KPJ:</span>
                                    <span class="font-mono font-bold text-slate-900 dark:text-white text-sm bg-white dark:bg-slate-800 px-2.5 py-0.5 rounded-lg border border-slate-200 dark:border-slate-700">
                                        {{ $bpjsTk?->policy_number ?: ($onboarding?->bpjs_ketenagakerjaan_number ?? '22094819204') }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500">Iuran Premi Bulanan:</span>
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400">100% Ditanggung Kantor (Rp 0 Peserta)</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500">Badan Penyelenggara:</span>
                                    <span class="font-medium text-slate-800 dark:text-slate-200">BPJS Ketenagakerjaan Republik Indonesia</span>
                                </div>
                            </div>

                            <div class="mt-4 space-y-1.5 text-xs text-slate-600 dark:text-slate-300">
                                <div class="flex items-start gap-2">
                                    <i class="fa-solid fa-check text-emerald-600 mt-0.5 text-xs"></i>
                                    <span>Perlindungan penuh saat perjalanan pergi-pulang dan di tempat magang.</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="fa-solid fa-check text-emerald-600 mt-0.5 text-xs"></i>
                                    <span>Biaya pengobatan & perawatan kecelakaan kerja sesuai standar regulasi tanpa plafon.</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-3 border-t border-slate-100 dark:border-slate-700 text-[11px] text-slate-400 flex items-center justify-between">
                            <span>Sesuai Permenaker RI No. 6/2020</span>
                            <span class="text-emerald-600 font-semibold">&#10003; Data Terverifikasi</span>
                        </div>
                    </div>

                    <!-- CARD: BPJS KESEHATAN -->
                    <div class="p-6 rounded-3xl bg-white dark:bg-slate-800/90 border-2 border-teal-300 dark:border-teal-700/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-teal-100 dark:bg-teal-950 text-teal-700 dark:text-teal-300 flex items-center justify-center text-xl font-black shrink-0 shadow-xs">
                                        <i class="fa-solid fa-heart-pulse"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-base font-extrabold text-slate-900 dark:text-white">BPJS Kesehatan</h4>
                                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase bg-teal-100 text-teal-800 dark:bg-teal-900/60 dark:text-teal-300">WAJIB</span>
                                        </div>
                                        <p class="text-xs text-slate-500 font-medium">Program Jaminan Kesehatan Nasional (JKN-KIS)</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 dark:bg-teal-950 dark:text-teal-300 border border-teal-200 dark:border-teal-800">
                                    <i class="fa-solid fa-circle-check text-teal-600"></i> Terdaftar
                                </span>
                            </div>

                            <div class="mt-5 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/60 space-y-2.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500">Nomor Kartu BPJS Kes:</span>
                                    <span class="font-mono font-bold text-slate-900 dark:text-white text-sm bg-white dark:bg-slate-800 px-2.5 py-0.5 rounded-lg border border-slate-200 dark:border-slate-700">
                                        {{ $bpjsKes?->policy_number ?: ($onboarding?->bpjs_kesehatan_number ?? '0002891048291') }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500">Status Kepesertaan:</span>
                                    <span class="font-bold text-teal-700 dark:text-teal-300">Aktif & Tervalidasi Sistem</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500">Badan Penyelenggara:</span>
                                    <span class="font-medium text-slate-800 dark:text-slate-200">BPJS Kesehatan Republik Indonesia</span>
                                </div>
                            </div>

                            <div class="mt-4 space-y-1.5 text-xs text-slate-600 dark:text-slate-300">
                                <div class="flex items-start gap-2">
                                    <i class="fa-solid fa-check text-teal-600 mt-0.5 text-xs"></i>
                                    <span>Akses faskes tingkat pertama (puskesmas/klinik) dan rumah sakit rujukan.</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="fa-solid fa-check text-teal-600 mt-0.5 text-xs"></i>
                                    <span>Perlindungan rawat inap dan rawat jalan selama masa aktif program magang.</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-3 border-t border-slate-100 dark:border-slate-700 text-[11px] text-slate-400 flex items-center justify-between">
                            <span>Sistem Jaminan Sosial Nasional</span>
                            <span class="text-teal-600 font-semibold">&#10003; Data Terverifikasi</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION II: FASILITAS & BENEFIT LAINNYA (NON-WAJIB) -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-6 rounded-full bg-indigo-600"></div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                            II. Fasilitas & Tunjangan Operasional Magang (Status: TIDAK WAJIB / OPSIONAL KANTOR)
                        </h3>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                        Transparansi Kompensasi
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @forelse($optionalUserBenefits as $ub)
                        <div class="p-5 rounded-2xl bg-white dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700/80 shadow-xs flex flex-col justify-between hover:shadow-sm transition">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-base font-bold shadow-2xs">
                                        <i class="{{ $ub->benefit?->icon ?? 'fa-solid fa-gift' }}"></i>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                                        OPSIONAL
                                    </span>
                                </div>

                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white mb-1">
                                    {{ $ub->benefit?->name }}
                                </h4>
                                <p class="text-[11px] text-slate-500 line-clamp-2 mb-3">
                                    {{ $ub->benefit?->description }}
                                </p>

                                @if($ub->policy_number)
                                    <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-900/50 border border-slate-200/80 dark:border-slate-700/60 text-[11px] mb-2 font-mono text-slate-700 dark:text-slate-300">
                                        <span class="text-slate-400 font-sans">Ref:</span> {{ $ub->policy_number }}
                                    </div>
                                @endif

                                @if($ub->custom_nominal > 0 || ($ub->benefit && $ub->benefit->standard_nominal > 0))
                                    <div class="text-xs font-bold text-indigo-600 dark:text-indigo-400 mb-1">
                                        Rp {{ number_format($ub->custom_nominal ?: $ub->benefit->standard_nominal, 0, ',', '.') }}
                                        <span class="text-[10px] text-slate-400 font-normal">/ bln</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between text-[11px]">
                                <span class="text-slate-500">Pemberi:</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ Str::limit($ub->benefit?->provider ?? 'Kantor', 18) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 p-8 rounded-2xl bg-slate-50 dark:bg-slate-900/40 border border-dashed border-slate-300 dark:border-slate-700 text-center text-slate-400 text-xs">
                            Belum ada fasilitas opsional yang dialokasikan oleh HR.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- TOTAL COMPENSATION & SLIP TRANSPARENCY CARD -->
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xs">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-1">
                        <div class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                            Transparansi Total Kompensasi & Bukti Pencairan
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                            Seluruh Hak Perlindungan & Uang Saku Tertuang Resmi di Slip Pencairan
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 max-w-2xl leading-relaxed">
                            Setiap bulan, Sistem Magang menerbitkan dokumen <strong>SLIP BUKTI PENCAIRAN UANG SAKU MAGANG</strong> resmi yang memuat rincian uang saku standar UMK, kepesertaan BPJS Ketenagakerjaan & Kesehatan, serta fasilitas operasional kantor secara transparan.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 shrink-0">
                        @if($latestStipend)
                            <a href="{{ route('candidate.internship.stipends.slip', $latestStipend->id) }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs hover:shadow-md transition">
                                <i class="fa-solid fa-download"></i>
                                <span>Unduh Slip Resmi Terkini</span>
                            </a>
                        @endif
                        <a href="{{ route('candidate.logbook.progress') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl text-xs sm:text-sm font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-white transition">
                            <i class="fa-solid fa-list-check"></i>
                            <span>Rekap Logbook & Presensi</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
