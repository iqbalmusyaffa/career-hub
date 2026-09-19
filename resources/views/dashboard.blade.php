<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-lg sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl {{ ($isIntern ?? false) ? 'bg-emerald-600 dark:bg-emerald-500' : 'bg-slate-900 dark:bg-blue-600' }} text-white flex items-center justify-center text-xs shadow-xs shrink-0">
                        <i class="fa-solid {{ ($isIntern ?? false) ? 'fa-graduation-cap' : 'fa-user' }}"></i>
                    </span>
                    <span>{{ ($isIntern ?? false) ? 'Dashboard Peserta Magang' : 'Dashboard Karir' }}</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    @if($isIntern ?? false)
                        Kelola presensi harian, jurnal logbook magang, dan pantau pemenuhan jam kerja Anda.
                    @else
                        Pantau status lamaran kerja, jadwal wawancara, dan dokumen karir Anda secara terpadu.
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto flex-wrap sm:flex-nowrap">
                @if($isIntern ?? false)
                    <a href="{{ route('candidate.logbook.index') }}" class="w-full sm:w-auto text-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                        <i class="fa-solid fa-calendar-check text-xs"></i> 
                        <span>Presensi & Logbook</span>
                    </a>
                    <a href="{{ route('candidate.logbook.evaluation') }}" class="w-full sm:w-auto text-center justify-center bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold py-2 px-4 rounded-xl text-xs transition border border-slate-200 dark:border-slate-800 shadow-xs flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-slate-400 text-xs"></i> 
                        <span>Nilai & Evaluasi</span>
                    </a>
                @else
                    <a href="{{ route('jobs.index') }}" class="w-full sm:w-auto text-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i> 
                        <span>Cari Lowongan</span>
                    </a>
                    <a href="{{ route('profile.candidate.details.edit') }}" class="w-full sm:w-auto text-center justify-center bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold py-2 px-4 rounded-xl text-xs transition border border-slate-200 dark:border-slate-800 shadow-xs flex items-center gap-2">
                        <i class="fa-solid fa-user-pen text-slate-400 text-xs"></i> 
                        <span>Edit Profil & CV</span>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl shadow-xs flex items-center gap-3 text-xs font-medium transition">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- BANNER NOTIFIKASI PENGUMUMAN TERBARU (TINGGAL KLIK -> MODAL POPUP) -->
            <x-announcements-modal-widget />

            @if($isIntern ?? false)
                <!-- ======================================================= -->
                <!-- A. TAMPILAN KHUSUS PESERTA MAGANG (INTERNSHIP ACTIVE)   -->
                <!-- ======================================================= -->

                <!-- 1. HERO WELCOME BANNER PESERTA MAGANG -->
                <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="space-y-1.5 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/80 dark:border-emerald-900 text-emerald-700 dark:text-emerald-300 rounded-md text-[11px] font-bold uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Peserta Magang Aktif
                            </span>
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-normal">| {{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <h2 class="text-lg sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                            Selamat Bertugas, {{ auth()->user()->name }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-normal">
                            Program: <strong class="text-slate-800 dark:text-slate-200">{{ $internMetrics['period']->period_name ?? ($internMetrics['job']->title ?? 'Internship') }}</strong>
                            @if(isset($internMetrics['period']) && $internMetrics['period']->company)
                                &bull; Perusahaan: <strong class="text-slate-800 dark:text-slate-200">{{ $internMetrics['period']->company->company_name }}</strong>
                            @elseif(isset($internMetrics['job']))
                                &bull; Perusahaan: <strong class="text-slate-800 dark:text-slate-200">{{ $internMetrics['job']->company_name }}</strong>
                            @endif
                        </p>
                    </div>

                    <!-- Quick Action -->
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('candidate.logbook.index') }}" class="py-2.5 px-4 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white text-xs font-semibold rounded-xl transition flex items-center gap-2 border border-slate-700">
                            <i class="fa-solid fa-calendar-days text-blue-400"></i>
                            <span>Buka Kalender Logbook</span>
                        </a>
                    </div>
                </div>

                @php
                    $pStart = null;
                    $pEnd = null;
                    if (isset($internMetrics['period']) && $internMetrics['period']->start_date && $internMetrics['period']->end_date) {
                        $pStart = $internMetrics['period']->start_date;
                        $pEnd = $internMetrics['period']->end_date;
                    } elseif (isset($internMetrics['job']) && $internMetrics['job']->start_date) {
                        $pStart = \Carbon\Carbon::parse($internMetrics['job']->start_date);
                        preg_match('/(\d+)/', $internMetrics['job']->duration ?? '6', $m);
                        $mo = isset($m[1]) ? (int)$m[1] : 6;
                        $pEnd = $pStart->copy()->addMonths($mo)->subDay();
                    } else {
                        $pStart = \Carbon\Carbon::create(2026, 8, 10);
                        $pEnd = \Carbon\Carbon::create(2027, 2, 9);
                    }
                    $periodString = $pStart->translatedFormat('d M Y') . '–' . $pEnd->translatedFormat('d M Y');
                    $hasTodaySubmitted = isset($internMetrics['todayLogbook']) && $internMetrics['todayLogbook'];

                    // Holiday & Weekend Detection for Today
                    $today = today();
                    $companyId = $internMetrics['job']->company_profile_id ?? 1;
                    $isWeekend = $today->isWeekend();
                    $todayHoliday = \App\Models\CompanyHoliday::whereDate('date', $today->format('Y-m-d'))
                        ->where(function ($q) use ($companyId) {
                            $q->whereNull('company_id')->orWhere('company_id', $companyId);
                        })
                        ->first();
                    $isOverrideWorkingDay = false;
                    if ($todayHoliday) {
                        $isOverrideWorkingDay = \App\Models\CompanyHolidayOverride::where('company_id', $companyId)
                            ->where('company_holiday_id', $todayHoliday->id)
                            ->where('is_working_day', true)
                            ->exists();
                    }
                    $isDayOff = ($isWeekend || ($todayHoliday && !$isOverrideWorkingDay));
                    $dayOffTitle = $todayHoliday ? ($todayHoliday->type === 'national_holiday' ? 'Libur Nasional: ' . $todayHoliday->name : 'Cuti Bersama: ' . $todayHoliday->name) : 'Libur Akhir Pekan (' . $today->translatedFormat('l') . ')';
                @endphp

                <!-- 2. MAIN SECTION: KARTU HARI INI & 4 METRIK BENTO (SPLIT 2 KOLOM RAPI) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                    
                    <!-- KOLOM KIRI: KARTU HARI INI (STANDALONE SESUAI REFERENSI GAMBAR) -->
                    <div class="lg:col-span-5 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-6 sm:p-7 rounded-3xl shadow-xs flex flex-col justify-between space-y-5">
                        <!-- Header Kartu -->
                        <div class="flex justify-between items-start gap-3 border-b border-slate-100 dark:border-slate-800/80 pb-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-2xl {{ $isDayOff && !$hasTodaySubmitted ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800' : 'bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900/50' }} flex items-center justify-center text-lg shrink-0">
                                    <i class="fa-regular {{ $isDayOff && !$hasTodaySubmitted ? 'fa-calendar-minus' : 'fa-calendar' }}"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white leading-none">Hari Ini</h3>
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 mt-1">
                                        {{ now()->translatedFormat('l, d F Y') }}
                                    </p>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 font-normal">
                                        Periode magang {{ $periodString }}
                                    </p>
                                </div>
                            </div>
                            @if($hasTodaySubmitted)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900 shrink-0">
                                    Sudah Lapor
                                </span>
                            @elseif($isDayOff)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800/80 shrink-0">
                                    <i class="fa-solid fa-mug-hot text-[9px] text-amber-600"></i> Hari Libur
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900 shrink-0">
                                    Hari Kerja Aktif
                                </span>
                            @endif
                        </div>

                        <!-- Body Kartu -->
                        @if($hasTodaySubmitted)
                            <!-- KONDISI 1: SUDAH ABSEN / LAPOR -->
                            <div class="text-center py-3 space-y-1.5">
                                <div class="w-20 h-20 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto border border-emerald-200/60 dark:border-emerald-900/50 shadow-xs">
                                    <i class="fa-solid fa-clipboard-check text-3xl"></i>
                                </div>
                                <h4 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white pt-2">
                                    Laporan hari ini sudah diisi
                                </h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Laporan dan kehadiran Anda sudah tercatat.
                                </p>
                            </div>

                            <div>
                                <a href="{{ route('candidate.logbook.show', today()->format('Y-m-d')) }}" 
                                   class="w-full py-3.5 px-5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-2xl shadow-xs transition flex items-center justify-between group cursor-pointer">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fa-solid fa-file-circle-check text-base"></i>
                                        <span>Lihat Laporan Hari Ini</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>

                            <!-- Notice Box Selesai -->
                            <div class="bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-200/70 dark:border-emerald-900/40 rounded-xl p-3 flex items-center gap-2.5 text-xs text-emerald-800 dark:text-emerald-300">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-sm shrink-0"></i>
                                <span>Presensi dan aktivitas hari ini telah tercatat dan menunggu verifikasi Mentor.</span>
                            </div>

                        @elseif($isDayOff)
                            <!-- KONDISI 2: HARI LIBUR / AKHIR PEKAN -->
                            <div class="text-center py-3 space-y-1.5">
                                <div class="w-20 h-20 rounded-full bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto border border-amber-200/80 dark:border-amber-800/80 shadow-xs">
                                    <i class="fa-solid fa-mug-hot text-3xl text-amber-500"></i>
                                </div>
                                <h4 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white pt-2">
                                    {{ $dayOffTitle }}
                                </h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
                                    Hari ini adalah hari libur. Tidak ada kewajiban mengisi presensi kehadiran atau laporan logbook harian.
                                </p>
                            </div>

                            <div class="space-y-2">
                                <a href="{{ route('candidate.logbook.index') }}" 
                                   class="w-full py-3.5 px-5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-semibold text-xs sm:text-sm rounded-2xl shadow-xs transition flex items-center justify-between group cursor-pointer">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fa-regular fa-calendar-days text-base text-blue-400"></i>
                                        <span>Lihat Kalender & Riwayat Logbook</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </a>
                                <div class="text-center pt-1">
                                    <a href="{{ route('candidate.logbook.show', today()->format('Y-m-d')) }}" class="text-[11px] font-medium text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 underline transition">
                                        Tetap isi laporan lembur / kegiatan tambahan (opsional)
                                    </a>
                                </div>
                            </div>

                            <!-- Notice Box Hari Libur -->
                            <div class="bg-amber-50/50 dark:bg-amber-950/30 border border-amber-200/70 dark:border-amber-900/40 rounded-xl p-3 flex items-center gap-2.5 text-xs text-slate-600 dark:text-slate-300">
                                <i class="fa-solid fa-couch text-amber-500 text-sm shrink-0"></i>
                                <span>Selamat menikmati waktu istirahat! Jam kerja aktif akan dimulai kembali pada hari kerja berikutnya.</span>
                            </div>

                        @else
                            <!-- KONDISI 3: HARI KERJA AKTIF & BELUM ABSEN -->
                            <div class="text-center py-3 space-y-1.5">
                                <div class="w-20 h-20 rounded-full bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto border border-amber-200/60 dark:border-amber-900/50 shadow-xs">
                                    <i class="fa-solid fa-calendar-xmark text-3xl"></i>
                                </div>
                                <h4 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white pt-2">
                                    Laporan hari ini belum diisi
                                </h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Silakan isi presensi kehadiran & jurnal aktivitas magang.
                                </p>
                            </div>

                            <div>
                                <a href="{{ route('candidate.logbook.show', today()->format('Y-m-d')) }}" 
                                   class="w-full py-3.5 px-5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs sm:text-sm rounded-2xl shadow-xs transition flex items-center justify-between group cursor-pointer">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fa-solid fa-pen-to-square text-base"></i>
                                        <span>Isi Presensi & Laporan Sekarang</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>

                            <!-- Notice Box Batas Waktu -->
                            <div class="bg-blue-50/50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/40 rounded-xl p-3 flex items-center gap-2.5 text-xs text-slate-600 dark:text-slate-300">
                                <i class="fa-solid fa-circle-info text-blue-500 text-sm shrink-0"></i>
                                <span>Batas pengisian laporan hari ini pukul <strong class="text-slate-900 dark:text-white font-bold">23.59 WIB</strong></span>
                            </div>
                        @endif
                    </div>

                    <!-- KOLOM KANAN: 4 METRIK BENTO CARDS -->
                    <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- 1. Target Jam Kerja -->
                        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between space-y-3">
                            <div class="flex justify-between items-start">
                                <div class="space-y-0.5">
                                    <p class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Jam Kerja Selesai</p>
                                    <p class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                                        {{ $internMetrics['completedHours'] }} <span class="text-xs font-normal text-slate-400">/ {{ $internMetrics['targetHours'] }} Jam</span>
                                    </p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-200/60 dark:border-blue-900/60 shrink-0">
                                    <i class="fa-solid fa-hourglass-half text-base"></i>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                    <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ $internMetrics['progressPercentage'] }}%"></div>
                                </div>
                                <p class="text-[10px] text-slate-400 text-right font-semibold">{{ $internMetrics['progressPercentage'] }}% Tercapai</p>
                            </div>
                        </div>

                        <!-- 2. Total Kehadiran -->
                        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between space-y-3">
                            <div class="flex justify-between items-start">
                                <div class="space-y-0.5">
                                    <p class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Hari Hadir</p>
                                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 tracking-tight">{{ $internMetrics['totalDaysPresent'] }} Hari</p>
                                    <p class="text-[11px] text-slate-400 font-normal">Presensi Masuk Tercatat</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-200/60 dark:border-emerald-900/60 shrink-0">
                                    <i class="fa-solid fa-calendar-check text-base"></i>
                                </div>
                            </div>
                            <div class="pt-1 text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-[10px]"></i>
                                <span>Kehadiran Terverifikasi</span>
                            </div>
                        </div>

                        <!-- 3. Logbook Disetujui Mentor -->
                        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between space-y-3">
                            <div class="flex justify-between items-start">
                                <div class="space-y-0.5">
                                    <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Jurnal Di-ACC</p>
                                    <p class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $internMetrics['approvedLogbooks'] }} Hari</p>
                                    <p class="text-[11px] text-amber-600 font-medium">{{ $internMetrics['pendingLogbooks'] }} Menunggu Review</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center border border-slate-200 dark:border-slate-700 shrink-0">
                                    <i class="fa-solid fa-file-circle-check text-base"></i>
                                </div>
                            </div>
                            <div class="pt-1 text-[11px] text-slate-500 flex items-center justify-between">
                                <span>Mentor Lapangan</span>
                                <a href="{{ route('candidate.logbook.index') }}" class="text-blue-600 font-semibold hover:underline">Riwayat &rarr;</a>
                            </div>
                        </div>

                        <!-- 4. Nilai / Sertifikat -->
                        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between space-y-3">
                            <div class="flex justify-between items-start">
                                <div class="space-y-0.5">
                                    <p class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">Hasil Evaluasi</p>
                                    @if(isset($internMetrics['transcript']) && $internMetrics['transcript'])
                                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 tracking-tight">{{ $internMetrics['transcript']->final_score }}</p>
                                        <p class="text-[11px] text-emerald-600 font-semibold">{{ $internMetrics['transcript']->predicate ?? 'Lulus Magang' }}</p>
                                    @else
                                        <p class="text-lg font-bold text-slate-800 dark:text-slate-200 tracking-tight">Sedang Berjalan</p>
                                        <p class="text-[11px] text-slate-400 font-normal">Penilaian akhir periode</p>
                                    @endif
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-200/60 dark:border-indigo-900/60 shrink-0">
                                    <i class="fa-solid fa-award text-base"></i>
                                </div>
                            </div>
                            <div class="pt-1 text-[11px] text-indigo-600 dark:text-indigo-400 font-semibold flex items-center justify-between">
                                <span>Status Transkrip</span>
                                <span>{{ isset($internMetrics['transcript']) ? 'Tersedia' : 'Tahap Akhir' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. STEPPER ALUR MAGANG (INTERNSHIP LIFECYCLE) -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-7 shadow-xs border border-slate-200/80 dark:border-slate-800 space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-0.5 rounded-md border border-emerald-200/80 dark:border-emerald-900 inline-block mb-1">
                                Siklus Program Magang
                            </span>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">Tahapan Magang & Kelulusan</h3>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">Semester / Batch Berjalan</span>
                    </div>

                    @php
                        $hasCert = (bool) ($internMetrics['certificate'] ?? false);
                        $hasTrans = (bool) ($internMetrics['transcript'] ?? false);
                    @endphp

                    <div class="overflow-x-auto pb-3 pt-1 scrollbar-thin">
                        <div class="flex items-center min-w-max md:min-w-0 md:justify-between gap-3 px-1">
                            
                            <!-- Step 1: Diterima & Onboarding (Passed) -->
                            <div class="flex items-center">
                                <div class="flex flex-col items-center text-center space-y-1.5 w-28 md:w-auto">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-xs font-bold shadow-xs shrink-0">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <span class="text-[10px] font-bold text-emerald-600 uppercase">Tahap 1</span>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Onboarding & Kontrak</span>
                                </div>
                                <div class="w-8 sm:w-12 lg:w-20 h-0.5 mx-2 rounded-full bg-emerald-500"></div>
                            </div>

                            <!-- Step 2: Pelaksanaan & Logbook (Current Active) -->
                            <div class="flex items-center">
                                <div class="flex flex-col items-center text-center space-y-1.5 w-28 md:w-auto">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xs font-bold ring-4 ring-blue-100 dark:ring-blue-900/40 shadow-xs shrink-0">
                                        <i class="fa-solid fa-calendar-check"></i>
                                    </div>
                                    <span class="text-[10px] font-bold text-blue-600 uppercase">Tahap 2 (Aktif)</span>
                                    <span class="text-xs font-semibold text-slate-900 dark:text-white">Pelaksanaan & Presensi</span>
                                </div>
                                <div class="w-8 sm:w-12 lg:w-20 h-0.5 mx-2 rounded-full {{ $hasTrans ? 'bg-emerald-500' : 'bg-slate-200 dark:bg-slate-800' }}"></div>
                            </div>

                            <!-- Step 3: Evaluasi Nilai Mentor -->
                            <div class="flex items-center">
                                <div class="flex flex-col items-center text-center space-y-1.5 w-28 md:w-auto">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl {{ $hasTrans ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-slate-700' }} flex items-center justify-center text-xs font-bold shadow-xs shrink-0">
                                        @if($hasTrans) <i class="fa-solid fa-check"></i> @else <i class="fa-solid fa-chart-pie"></i> @endif
                                    </div>
                                    <span class="text-[10px] font-bold uppercase {{ $hasTrans ? 'text-emerald-600' : 'text-slate-400' }}">Tahap 3</span>
                                    <span class="text-xs font-medium {{ $hasTrans ? 'text-slate-800 dark:text-slate-200 font-semibold' : 'text-slate-400' }}">Penilaian Mentor</span>
                                </div>
                                <div class="w-8 sm:w-12 lg:w-20 h-0.5 mx-2 rounded-full {{ $hasCert ? 'bg-emerald-500' : 'bg-slate-200 dark:bg-slate-800' }}"></div>
                            </div>

                            <!-- Step 4: Sertifikat & Transkrip -->
                            <div class="flex items-center">
                                <div class="flex flex-col items-center text-center space-y-1.5 w-28 md:w-auto">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl {{ $hasCert ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-slate-700' }} flex items-center justify-center text-xs font-bold shadow-xs shrink-0">
                                        @if($hasCert) <i class="fa-solid fa-check"></i> @else <i class="fa-solid fa-graduation-cap"></i> @endif
                                    </div>
                                    <span class="text-[10px] font-bold uppercase {{ $hasCert ? 'text-emerald-600' : 'text-slate-400' }}">Tahap 4</span>
                                    <span class="text-xs font-medium {{ $hasCert ? 'text-slate-800 dark:text-slate-200 font-semibold' : 'text-slate-400' }}">Sertifikat Kelulusan</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- 4. DUAL COLUMN: LOGBOOK TERAKHIR & REKOMENDASI KARIR -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                    <!-- Jurnal Logbook Terkini -->
                    <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col space-y-4">
                        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-book-bookmark text-blue-600"></i>
                                <span>Jurnal Logbook Terkini</span>
                            </h3>
                            <a href="{{ route('candidate.logbook.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                                Buka Kalender &rarr;
                            </a>
                        </div>

                        <div class="max-h-[380px] overflow-y-auto pr-1 space-y-3 scrollbar-thin">
                            @if(isset($internMetrics['recentLogbooks']) && $internMetrics['recentLogbooks']->count() > 0)
                                @foreach($internMetrics['recentLogbooks'] as $lgb)
                                    <div class="p-3.5 rounded-xl border border-slate-100 dark:border-slate-800/80 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition space-y-2">
                                        <div class="flex justify-between items-start gap-2">
                                            <div>
                                                <span class="text-xs font-bold text-slate-900 dark:text-white">
                                                    {{ $lgb->date->format('l, d M Y') }}
                                                </span>
                                                <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">
                                                    {{ $lgb->activity_description ?? 'Aktivitas magang harian' }}
                                                </p>
                                            </div>
                                            <span class="text-[10px] px-2 py-0.5 rounded font-bold shrink-0 {{ $lgb->status_badge['class'] }}">
                                                {{ $lgb->status_badge['label'] }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between text-[11px] text-slate-400 pt-1 border-t border-slate-100 dark:border-slate-800/60">
                                            <span><i class="fa-regular fa-clock mr-1"></i> {{ $lgb->work_hours }} Jam Kerja</span>
                                            <a href="{{ route('candidate.logbook.show', $lgb->date->format('Y-m-d')) }}" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">
                                                Detail &rarr;
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-10 text-slate-400 text-xs space-y-2">
                                    <i class="fa-solid fa-inbox text-3xl"></i>
                                    <p class="font-semibold text-slate-600 dark:text-slate-400">Belum ada catatan logbook.</p>
                                    <a href="{{ route('candidate.logbook.index') }}" class="inline-block mt-2 text-blue-600 font-bold hover:underline">Mulai Isi Logbook &rarr;</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Dokumen Magang & Peluang Karir Lulusan -->
                    <div class="space-y-6">
                        <!-- Card Sertifikat / Transkrip Siap Unduh -->
                        @if(isset($internMetrics['certificate']) || isset($internMetrics['transcript']))
                            <div class="bg-gradient-to-br from-indigo-900 to-slate-900 text-white p-5 rounded-2xl shadow-xs space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white/10 text-indigo-300 flex items-center justify-center text-lg shrink-0">
                                        <i class="fa-solid fa-graduation-cap"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-white">Dokumen Kelulusan Magang Resmi</h4>
                                        <p class="text-xs text-indigo-200">Telah diverifikasi dan ditandatangani perusahaan.</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if(isset($internMetrics['certificate']))
                                        <a href="{{ route('candidate.certificates.show', $internMetrics['certificate']) }}" target="_blank" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition flex items-center gap-1.5 shadow-xs">
                                            <i class="fa-solid fa-file-pdf"></i> Unduh Sertifikat
                                        </a>
                                    @endif
                                    @if(isset($internMetrics['transcript']))
                                        <a href="{{ route('candidate.transcripts.show', $internMetrics['transcript']) }}" target="_blank" class="px-3.5 py-2 bg-white/10 hover:bg-white/20 text-white font-semibold text-xs rounded-xl transition flex items-center gap-1.5 border border-white/20">
                                            <i class="fa-solid fa-file-lines"></i> Transkrip Nilai
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Peluang Karir & Rekomendasi Lowongan Lulusan -->
                        <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col space-y-4">
                            <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-briefcase text-blue-600 dark:text-blue-400"></i>
                                    <span>Peluang Karir Lulusan</span>
                                </h3>
                                <a href="{{ route('jobs.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline transition flex items-center gap-1">
                                    Lihat Semua &rarr;
                                </a>
                            </div>
                            
                            <div class="max-h-[380px] overflow-y-auto pr-1 space-y-3 scrollbar-thin">
                                @php
                                    $recommendedJobs = \App\Models\Job::where('status', 'active')->latest()->take(4)->get();
                                @endphp

                                @if($recommendedJobs->count() > 0)
                                    <div class="space-y-2.5">
                                        @foreach($recommendedJobs as $job)
                                            <div class="group border border-slate-200/80 dark:border-slate-800 p-3.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/40 hover:border-slate-300 dark:hover:border-slate-700 transition cursor-pointer space-y-1.5" onclick="window.location.href='{{ route('jobs.show', $job) }}'">
                                                <div class="flex justify-between items-start gap-2">
                                                    <div class="min-w-0">
                                                        <h4 class="font-bold text-slate-900 dark:text-white text-xs sm:text-sm group-hover:text-blue-600 dark:group-hover:text-blue-400 transition truncate">
                                                            {{ $job->title }}
                                                        </h4>
                                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal truncate">
                                                            {{ $job->company_name }} &bull; <i class="fa-solid fa-location-dot text-slate-400"></i> {{ $job->location }}
                                                        </p>
                                                    </div>
                                                    <span class="text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 px-2 py-0.5 rounded-md uppercase shrink-0">
                                                        {{ $job->work_type }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center py-10 text-slate-400 dark:text-slate-500 text-xs text-center space-y-2">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 text-lg">
                                            <i class="fa-solid fa-briefcase"></i>
                                        </div>
                                        <p class="font-semibold text-slate-700 dark:text-slate-300">Belum ada lowongan aktif saat ini.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- ======================================================= -->
                <!-- B. TAMPILAN KHUSUS PELAMAR KERJA (JOB SEEKER MODE)      -->
                <!-- ======================================================= -->

                <!-- 1. HERO WELCOME BANNER CARD -->
                <div class="bg-white dark:bg-slate-900 p-5 sm:p-7 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="space-y-1.5 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/60 border border-blue-200/80 dark:border-blue-900 text-blue-700 dark:text-blue-300 rounded-md text-[11px] font-semibold uppercase tracking-wider">
                                    Pencari Kerja
                                </span>
                                <span class="text-xs text-slate-400 dark:text-slate-500 font-normal">| {{ now()->translatedFormat('l, d F Y') }}</span>
                            </div>
                            <h2 class="text-lg sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                                Selamat Datang, {{ auth()->user()->name }}
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-2xl leading-relaxed font-normal">
                                Pantau perkembangan berkas lamaran Anda atau temukan peluang karir baru yang sesuai dengan kompetensi Anda.
                            </p>
                        </div>

                        @php
                            $candidateProfile = auth()->user()->candidateProfile;
                            $completionPercentage = $candidateProfile ? $candidateProfile->completion_percentage : 0;
                        @endphp
                        <div class="bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 p-4 rounded-xl shrink-0 space-y-2 w-full md:w-64">
                            <div class="flex justify-between items-center text-xs font-semibold">
                                <span class="text-slate-700 dark:text-slate-300">Kelengkapan Profil</span>
                                <span class="text-blue-600 dark:text-blue-400 font-bold">
                                    {{ $completionPercentage }}%
                                </span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-blue-600 dark:bg-blue-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ $completionPercentage }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-slate-500 dark:text-slate-400">
                                    @if($completionPercentage === 100)
                                        Profil & CV Lengkap
                                    @elseif(!$candidateProfile || !$candidateProfile->cv_path)
                                        CV Belum Diunggah
                                    @else
                                        {{ $completionPercentage }}% Terisi
                                    @endif
                                </span>
                                @if($completionPercentage < 100)
                                    <a href="{{ route('profile.candidate.details.edit') }}" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">
                                        {{ (!$candidateProfile || !$candidateProfile->cv_path) ? 'Upload CV →' : 'Lengkapi Profil →' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. ACTION HUB / NOTIFICATIONS REQUIRING ATTENTION -->
                @php
                    $userInterviews = \App\Models\Interview::whereHas('application', function($q) {
                        $q->where('user_id', auth()->id());
                    })->where('scheduled_at', '>=', now())
                      ->with('application.job')
                      ->get();

                    $acceptedApps = isset($recentApplications) 
                        ? $recentApplications->filter(function($app) {
                            $st = is_object($app->status) ? $app->status->value : (string)$app->status;
                            return in_array($st, ['accepted', 'hired']);
                        })
                        : collect();

                    $pendingAgreements = \App\Models\ApplicationAgreement::where('user_id', auth()->id())
                        ->where('status', 'sent')
                        ->with('application.job')
                        ->get();

                    $hasActionRequired = $userInterviews->count() > 0 || $acceptedApps->count() > 0 || $pendingAgreements->count() > 0;
                @endphp

                @if($hasActionRequired)
                    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6 rounded-2xl shadow-xs space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2.5">
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-600"></span>
                                </span>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">Pemberitahuan & Tindakan Diperlukan</h3>
                            </div>
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">Perlu Respon</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                            {{-- Undangan Wawancara --}}
                            @foreach($userInterviews as $userInt)
                                <div class="bg-blue-50/50 dark:bg-blue-950/20 border border-blue-200/70 dark:border-blue-900/60 p-4 rounded-xl flex flex-col justify-between gap-3 hover:border-blue-300 transition">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="uppercase font-bold text-[10px] bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 px-2 py-0.5 rounded">
                                                Wawancara {{ $userInt->type }}
                                            </span>
                                            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                                                <i class="fa-regular fa-clock mr-1"></i> {{ $userInt->scheduled_at->format('d M Y, H:i') }} WIB
                                            </span>
                                        </div>
                                        <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white">
                                            {{ $userInt->application->job->title ?? 'Pekerjaan' }}
                                        </h4>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">
                                            Perusahaan: <strong>{{ $userInt->application->job->company_name ?? '-' }}</strong>
                                        </p>
                                    </div>
                                    <div class="pt-1">
                                        @if($userInt->location_or_link && Str::startsWith($userInt->location_or_link, 'http'))
                                            <a href="{{ $userInt->location_or_link }}" target="_blank" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg shadow-xs transition flex items-center justify-center gap-1.5">
                                                <i class="fa-solid fa-video text-xs"></i> Buka Link Wawancara &rarr;
                                            </a>
                                        @elseif($userInt->location_or_link)
                                            <div class="text-xs text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 p-2 rounded-lg border border-slate-200 dark:border-slate-800">
                                                <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $userInt->location_or_link }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            {{-- Diterima Kerja / Onboarding --}}
                            @foreach($acceptedApps as $acceptedApp)
                                <div class="bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-200/70 dark:border-emerald-900/60 p-4 rounded-xl flex flex-col justify-between gap-3 hover:border-emerald-300 transition">
                                    <div class="space-y-1.5">
                                        <span class="uppercase font-bold text-[10px] bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 px-2 py-0.5 rounded">
                                            Selamat! Anda Diterima
                                        </span>
                                        <h4 class="font-bold text-xs sm:text-sm text-emerald-950 dark:text-emerald-200">
                                            {{ $acceptedApp->job->title }}
                                        </h4>
                                        <p class="text-xs text-emerald-800 dark:text-emerald-400">
                                            Perusahaan: <strong>{{ $acceptedApp->job->company_name }}</strong>. Silakan lengkapi data onboarding.
                                        </p>
                                    </div>
                                    <div class="pt-1">
                                        <a href="{{ route('candidate.onboarding.create', $acceptedApp) }}" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-lg shadow-xs transition flex items-center justify-center gap-1.5">
                                            <i class="fa-solid fa-file-signature text-xs"></i> Lengkapi Onboarding &rarr;
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Perjanjian Kerja Menunggu TTD --}}
                            @foreach($pendingAgreements as $pendingAgreement)
                                <div class="bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200/70 dark:border-amber-900/60 p-4 rounded-xl flex flex-col justify-between gap-3 hover:border-amber-300 transition">
                                    <div class="space-y-1.5">
                                        <span class="uppercase font-bold text-[10px] bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300 px-2 py-0.5 rounded">
                                            Perjanjian Kerja (Sign Pending)
                                        </span>
                                        <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white">
                                            {{ $pendingAgreement->title }}
                                        </h4>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">
                                            Perusahaan: <strong>{{ $pendingAgreement->application->job->company_name }}</strong>
                                        </p>
                                    </div>
                                    <div class="pt-1">
                                        <a href="{{ route('candidate.agreements.show', $pendingAgreement) }}" class="w-full py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs rounded-lg shadow-xs transition flex items-center justify-center gap-1.5">
                                            <i class="fa-solid fa-signature text-xs"></i> Tanda Tangani Dokumen &rarr;
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 3. RECRUITMENT PROGRESS STEPPER FOR LATEST APPLICATION -->
                @if(isset($recentApplications) && $recentApplications->count() > 0)
                    @php
                        $latestApp = $recentApplications->first();
                        $statusVal = is_object($latestApp->status) ? $latestApp->status->value : (string) $latestApp->status;
                        $job = $latestApp->job;
                        $hasActiveTest = $job && $job->test && $job->test->is_active;
                        $hasOfferLetter = (bool) $latestApp->offerLetter;

                        $stages = [
                            ['key' => 'pending', 'label' => 'Melamar Berkas', 'icon' => 'fa-file-signature'],
                        ];

                        if ($statusVal === 'screening') {
                            $stages[] = ['key' => 'screening', 'label' => 'HR Screening', 'icon' => 'fa-user-check'];
                        }

                        if ($hasActiveTest || $statusVal === 'test') {
                            $stages[] = ['key' => 'test', 'label' => 'Tes Online', 'icon' => 'fa-laptop-code'];
                        }

                        $stages[] = ['key' => 'interview_hr', 'label' => 'Wawancara HR', 'icon' => 'fa-comments'];

                        if ($statusVal === 'interview_user') {
                            $stages[] = ['key' => 'interview_user', 'label' => 'Wawancara User', 'icon' => 'fa-users-viewfinder'];
                        }

                        if ($statusVal === 'background_check') {
                            $stages[] = ['key' => 'background_check', 'label' => 'Background Check', 'icon' => 'fa-shield-halved'];
                        }

                        if ($hasOfferLetter || $statusVal === 'offered' || $statusVal === 'accepted') {
                            $stages[] = ['key' => 'offered', 'label' => 'Offer Letter', 'icon' => 'fa-file-contract'];
                        }

                        $stages[] = ['key' => 'accepted', 'label' => 'Diterima Kerja', 'icon' => 'fa-circle-check'];

                        $stageKeys = array_column($stages, 'key');
                        $currentKey = $statusVal;
                        if ($statusVal === 'reviewed' || $statusVal === 'processing') $currentKey = 'pending';
                        if ($statusVal === 'interview') $currentKey = 'interview_hr';
                        
                        $activeIdx = array_search($currentKey, $stageKeys);
                        if ($activeIdx === false) {
                            $activeIdx = 0;
                        }
                        $currentStepNum = $activeIdx + 1;
                        $isRejected = ($statusVal === 'rejected');
                    @endphp

                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-7 shadow-xs border border-slate-200/80 dark:border-slate-800 space-y-5">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                            <div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 px-2.5 py-0.5 rounded-md border border-blue-200/80 dark:border-blue-900 inline-block mb-1">
                                    Progress Rekrutmen Terkini
                                </span>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">
                                    {{ $latestApp->job->title }} <span class="text-xs text-slate-400 font-normal">({{ $latestApp->job->company_name }})</span>
                                </h3>
                            </div>
                            <div>
                                @if(is_object($latestApp->status) && method_exists($latestApp->status, 'color'))
                                    <span class="px-3 py-1 rounded-xl text-xs font-semibold uppercase tracking-wider border border-slate-200 dark:border-slate-700 {{ $latestApp->status->color() }}">
                                        {{ $latestApp->status->label() }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-slate-900 dark:bg-slate-800 text-white rounded-xl text-xs font-semibold uppercase tracking-wider">
                                        {{ $latestApp->status }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($isRejected)
                            <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-rose-800 dark:text-rose-300 rounded-xl flex items-center gap-3 text-xs font-medium">
                                <i class="fa-solid fa-circle-xmark text-rose-600 dark:text-rose-400 text-lg shrink-0"></i>
                                <div>
                                    <p class="font-bold text-sm text-rose-900 dark:text-rose-200">Status Lamaran: Belum Lolos Seleksi</p>
                                    <p class="text-xs text-rose-700 dark:text-rose-400 font-normal mt-0.5">Terima kasih atas partisipasi Anda. Tetap semangat melamar lowongan lainnya.</p>
                                </div>
                            </div>
                        @else
                            <div class="overflow-x-auto pb-3 pt-1 scrollbar-thin">
                                <div class="flex items-center min-w-max md:min-w-0 md:justify-between gap-2 sm:gap-4 px-1">
                                    @foreach($stages as $idx => $stg)
                                        @php
                                            $stepIndex = $idx + 1;
                                            $isPassed = $currentStepNum > $stepIndex;
                                            $isCurrent = $currentStepNum === $stepIndex;
                                        @endphp
                                        <div class="flex items-center">
                                            <div class="flex flex-col items-center text-center space-y-1.5 w-24 sm:w-28 md:w-auto">
                                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center text-xs shadow-xs transition duration-200 shrink-0
                                                    {{ $isCurrent ? 'bg-blue-600 text-white font-bold ring-4 ring-blue-100 dark:ring-blue-900/40' : ($isPassed ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 border border-slate-200 dark:border-slate-700') }}">
                                                    @if($isPassed)
                                                        <i class="fa-solid fa-check text-xs"></i>
                                                    @else
                                                        <i class="fa-solid {{ $stg['icon'] }}"></i>
                                                    @endif
                                                </div>
                                                <div class="space-y-0.5">
                                                    <span class="text-[10px] font-semibold block uppercase tracking-wider {{ $isCurrent ? 'text-blue-600 dark:text-blue-400' : ($isPassed ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500') }}">
                                                        Tahap {{ $stepIndex }}
                                                    </span>
                                                    <span class="text-[11px] sm:text-xs font-medium leading-tight block truncate max-w-[100px] sm:max-w-none {{ $isCurrent ? 'text-slate-900 dark:text-white font-semibold' : ($isPassed ? 'text-slate-700 dark:text-slate-300' : 'text-slate-400 dark:text-slate-500') }}">
                                                        {{ $stg['label'] }}
                                                    </span>
                                                </div>
                                            </div>

                                            @if(!$loop->last)
                                                <div class="hidden sm:block w-8 sm:w-12 lg:w-16 h-0.5 mx-1.5 rounded-full {{ $isPassed ? 'bg-emerald-500' : 'bg-slate-200 dark:bg-slate-800' }}"></div>
                                                <div class="sm:hidden text-slate-300 dark:text-slate-700 mx-1 text-xs">
                                                    <i class="fa-solid fa-chevron-right"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- 4. METRIC BENTO CARDS -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                    <!-- Total Lamaran -->
                    <div class="bg-white dark:bg-slate-900 p-4 sm:p-5 rounded-2xl shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-all border border-slate-200/80 dark:border-slate-800 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                        <div class="space-y-0.5">
                            <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Lamaran</p>
                            <p class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $totalApplications ?? 0 }}</p>
                            <p class="text-[10px] sm:text-[11px] text-slate-400 dark:text-slate-500 font-normal">Berkas Dikirim</p>
                        </div>
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-slate-50 dark:bg-slate-800/80 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-slate-200/80 dark:border-slate-700 shrink-0">
                            <i class="fa-solid fa-file-lines text-sm sm:text-base"></i>
                        </div>
                    </div>

                    <!-- Diproses -->
                    <div class="bg-white dark:bg-slate-900 p-4 sm:p-5 rounded-2xl shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-all border border-slate-200/80 dark:border-slate-800 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                        <div class="space-y-0.5">
                            <p class="text-[11px] font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Diproses</p>
                            <p class="text-xl sm:text-2xl font-bold text-amber-600 dark:text-amber-400 tracking-tight">{{ $processingApplications ?? 0 }}</p>
                            <p class="text-[10px] sm:text-[11px] text-slate-400 dark:text-slate-500 font-normal">Tahap Seleksi</p>
                        </div>
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-amber-50/50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-200/60 dark:border-amber-900/60 shrink-0">
                            <i class="fa-solid fa-spinner text-sm sm:text-base"></i>
                        </div>
                    </div>

                    <!-- Diterima -->
                    <div class="bg-white dark:bg-slate-900 p-4 sm:p-5 rounded-2xl shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-all border border-slate-200/80 dark:border-slate-800 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                        <div class="space-y-0.5">
                            <p class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Diterima</p>
                            <p class="text-xl sm:text-2xl font-bold text-emerald-600 dark:text-emerald-400 tracking-tight">{{ $acceptedApplications ?? 0 }}</p>
                            <p class="text-[10px] sm:text-[11px] text-slate-400 dark:text-slate-500 font-normal">Lolos Seleksi</p>
                        </div>
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-200/60 dark:border-emerald-900/60 shrink-0">
                            <i class="fa-solid fa-circle-check text-sm sm:text-base"></i>
                        </div>
                    </div>

                    <!-- Ditolak -->
                    <div class="bg-white dark:bg-slate-900 p-4 sm:p-5 rounded-2xl shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-all border border-slate-200/80 dark:border-slate-800 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                        <div class="space-y-0.5">
                            <p class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Belum Lolos</p>
                            <p class="text-xl sm:text-2xl font-bold text-rose-600 dark:text-rose-400 tracking-tight">{{ $rejectedApplications ?? 0 }}</p>
                            <p class="text-[10px] sm:text-[11px] text-slate-400 dark:text-slate-500 font-normal">Berkas Ditolak</p>
                        </div>
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-rose-50/50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center border border-rose-200/60 dark:border-rose-900/60 shrink-0">
                            <i class="fa-solid fa-circle-xmark text-sm sm:text-base"></i>
                        </div>
                    </div>
                </div>

                <!-- 5. SPLIT 2 COLUMNS: RECENT APPLICATIONS & RECOMMENDED JOBS -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                    
                    <!-- Kolom Kiri: Lamaran Terakhir Saya -->
                    <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col space-y-4">
                        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                                <span>Lamaran Terakhir Saya</span>
                            </h3>
                            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">
                                {{ isset($recentApplications) ? $recentApplications->count() : 0 }} Lamaran
                            </span>
                        </div>
                        
                        <div class="max-h-[380px] overflow-y-auto pr-1 space-y-3 scrollbar-thin">
                            @if(isset($recentApplications) && $recentApplications->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentApplications as $app)
                                        <div class="p-3.5 rounded-xl border border-slate-100 dark:border-slate-800/80 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition space-y-2">
                                            <div class="flex justify-between items-start gap-2">
                                                <div class="min-w-0">
                                                    <a href="{{ route('jobs.show', $app->job) }}" class="font-bold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 text-xs sm:text-sm transition block truncate">
                                                        {{ $app->job->title }}
                                                    </a>
                                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal mt-0.5 truncate">
                                                        {{ $app->job->division ?? 'Umum' }} &bull; {{ $app->job->company_name }}
                                                    </p>
                                                </div>
                                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-md border uppercase border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 shrink-0">
                                                    {{ is_object($app->status) && method_exists($app->status, 'label') ? $app->status->label() : $app->status }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between text-[11px] font-normal pt-1 border-t border-slate-100 dark:border-slate-800/60">
                                                <span class="text-slate-400 dark:text-slate-500">
                                                    <i class="fa-regular fa-calendar text-slate-400 mr-1"></i> {{ $app->created_at->format('d M Y') }}
                                                </span>
                                                <button onclick="openLiveChat({{ $app->id }}, '{{ addslashes($app->job->title) }}', 'HR {{ addslashes($app->job->company_name) }}')" class="px-2.5 py-1 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white font-medium rounded-lg transition text-[11px] flex items-center gap-1.5 border border-slate-700 dark:border-slate-600">
                                                    <i class="fa-solid fa-comments text-[10px] text-blue-300"></i> 
                                                    <span>Chat HR</span>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-12 text-slate-400 dark:text-slate-500 text-xs text-center space-y-2">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 text-xl">
                                        <i class="fa-solid fa-inbox"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700 dark:text-slate-300">Anda belum melamar pekerjaan apapun.</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">Temukan lowongan idaman dan kirimkan lamaran pertama Anda.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Kolom Kanan: Rekomendasi Lowongan Terbaru -->
                    <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col space-y-4">
                        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-briefcase text-blue-600 dark:text-blue-400"></i>
                                <span>Rekomendasi Lowongan</span>
                            </h3>
                            <a href="{{ route('jobs.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline transition flex items-center gap-1">
                                Lihat Semua &rarr;
                            </a>
                        </div>
                        
                        <div class="max-h-[380px] overflow-y-auto pr-1 space-y-3 scrollbar-thin">
                            @php
                                $latestJobs = \App\Models\Job::where('status', 'active')->latest()->take(5)->get();
                            @endphp

                            @if($latestJobs->count() > 0)
                                <div class="space-y-3">
                                    @foreach($latestJobs as $job)
                                        <div class="group border border-slate-200/80 dark:border-slate-800 p-3.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/40 hover:border-slate-300 dark:hover:border-slate-700 transition cursor-pointer space-y-2" onclick="window.location.href='{{ route('jobs.show', $job) }}'">
                                            <div class="flex justify-between items-start gap-2">
                                                <div class="min-w-0">
                                                    <h4 class="font-bold text-slate-900 dark:text-white text-xs sm:text-sm group-hover:text-blue-600 dark:group-hover:text-blue-400 transition truncate">
                                                        {{ $job->title }}
                                                    </h4>
                                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal mt-0.5 truncate">
                                                        {{ $job->company_name }} &bull; <i class="fa-solid fa-location-dot text-slate-400"></i> {{ $job->location }}
                                                    </p>
                                                </div>
                                                <span class="text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 px-2 py-0.5 rounded-md uppercase shrink-0">
                                                    {{ $job->work_type }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-12 text-slate-400 dark:text-slate-500 text-xs text-center space-y-2">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 text-xl">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700 dark:text-slate-300">Belum ada lowongan aktif saat ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- CANDIDATE APPLICATION STATUS POP-UP MODAL (DISMISSIBLE / SMART DISPLAY) -->
    @if(isset($statusPopupApp) && $statusPopupApp)
        @php
            $stVal = is_object($statusPopupApp->status) ? $statusPopupApp->status->value : (string)$statusPopupApp->status;
            $isAccepted = in_array($stVal, ['accepted', 'hired']);
            $isOffered = ($stVal === 'offered');
            $isInterview = in_array($stVal, ['interview', 'interview_hr', 'interview_user']);
            $isTest = ($stVal === 'test');
            $isRejected = ($stVal === 'rejected');
            $popupStorageKey = 'last_dismissed_status_' . $statusPopupApp->id . '_' . $stVal;
        @endphp

        <div x-data="{ 
            showStatusModal: localStorage.getItem('{{ $popupStorageKey }}') !== 'true',
            dismissModal() {
                localStorage.setItem('{{ $popupStorageKey }}', 'true');
                this.showStatusModal = false;
            }
        }">
            <div x-show="showStatusModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-md flex items-center justify-center p-4" 
                 style="display: none;">
                
                <div @click.outside="dismissModal()" class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800 relative text-left">
                    
                    <button type="button" @click="dismissModal()" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-slate-100/80 dark:bg-slate-800/80 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center text-xs transition cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                    @if($isAccepted)
                        @php $isInternApp = strtolower($statusPopupApp->job->work_type ?? '') === 'internship' || strtolower($statusPopupApp->job->work_type ?? '') === 'magang'; @endphp
                        <!-- ACCEPTED MODAL -->
                        <div class="bg-emerald-600 text-white p-6 sm:p-7 text-center relative overflow-hidden">
                            <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center text-xl mx-auto mb-2.5 shadow-xs border border-white/20">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <span class="px-2.5 py-0.5 bg-white/20 text-white rounded-md text-[10px] font-semibold uppercase tracking-wider inline-block mb-1.5 border border-white/20">
                                {{ $isInternApp ? 'Program Magang / Internship' : 'Pemberitahuan Kelulusan' }}
                            </span>
                            <h2 class="text-lg sm:text-xl font-bold tracking-tight text-white mb-0.5">
                                {{ $isInternApp ? 'Selamat! Anda Diterima Magang' : 'Selamat! Anda Lolos Seleksi' }}
                            </h2>
                            <p class="text-xs text-emerald-100 font-normal">
                                {{ $isInternApp ? 'Peserta Program Magang Resmi Perusahaan' : 'Lamaran Kerja Berhasil Diterima Perusahaan' }}
                            </p>
                        </div>

                        <div class="p-6 space-y-4 text-center">
                            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-200 dark:border-slate-800 text-xs space-y-1">
                                <p class="text-slate-500 dark:text-slate-400 font-normal text-[11px]">Posisi Pekerjaan:</p>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 dark:text-slate-300 font-medium">{{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                                Perusahaan <strong>{{ $statusPopupApp->job->company_name }}</strong> telah menerima lamaran Anda. Silakan lengkapi data onboarding dan administrasi awal Anda.
                            </p>

                            <div class="pt-1 flex flex-col gap-2">
                                <a href="{{ route('candidate.onboarding.create', $statusPopupApp) }}" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-file-signature text-xs"></i> Lengkapi Data Onboarding &rarr;
                                </a>
                                <button type="button" @click="dismissModal()" class="text-xs text-slate-400 font-medium hover:underline py-1">Tutup Nanti Saja</button>
                            </div>
                        </div>

                    @elseif($isOffered)
                        <!-- OFFERED MODAL -->
                        <div class="bg-amber-600 text-white p-6 sm:p-7 text-center relative overflow-hidden">
                            <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center text-xl mx-auto mb-2.5 shadow-xs border border-white/20">
                                <i class="fa-solid fa-file-contract"></i>
                            </div>
                            <span class="px-2.5 py-0.5 bg-white/20 text-white rounded-md text-[10px] font-semibold uppercase tracking-wider inline-block mb-1.5 border border-white/20">
                                Penawaran Kerja Resmi
                            </span>
                            <h2 class="text-lg sm:text-xl font-bold tracking-tight text-white mb-0.5">Surat Penawaran Kerja (Offering)</h2>
                            <p class="text-xs text-amber-100 font-normal">Offer Letter Resmi Telah Diterbitkan</p>
                        </div>

                        <div class="p-6 space-y-4 text-center">
                            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-200 dark:border-slate-800 text-xs space-y-1">
                                <p class="text-slate-500 dark:text-slate-400 font-normal text-[11px]">Posisi Ditawarkan:</p>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 dark:text-slate-300 font-medium">{{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                                Perusahaan <strong>{{ $statusPopupApp->job->company_name }}</strong> telah menerbitkan Surat Penawaran Kerja. Silakan tinjau penawaran dan beri respon Anda.
                            </p>

                            <div class="pt-1 flex flex-col gap-2">
                                <button type="button" @click="dismissModal()" class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-file-contract text-xs"></i> Tinjau Surat Penawaran &rarr;
                                </button>
                                <button type="button" @click="dismissModal()" class="text-xs text-slate-400 font-medium hover:underline py-1">Tutup</button>
                            </div>
                        </div>

                    @elseif($isInterview)
                        <!-- INTERVIEW MODAL -->
                        <div class="bg-blue-600 text-white p-6 sm:p-7 text-center relative overflow-hidden">
                            <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center text-xl mx-auto mb-2.5 shadow-xs border border-white/20">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>
                            <span class="px-2.5 py-0.5 bg-white/20 text-white rounded-md text-[10px] font-semibold uppercase tracking-wider inline-block mb-1.5 border border-white/20">
                                Tahap Interview
                            </span>
                            <h2 class="text-lg sm:text-xl font-bold tracking-tight text-white mb-0.5">Undangan Sesi Wawancara</h2>
                            <p class="text-xs text-blue-100 font-normal">Berkas Anda Lolos ke Tahap Interview</p>
                        </div>

                        <div class="p-6 space-y-4 text-center">
                            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-200 dark:border-slate-800 text-xs space-y-1">
                                <p class="text-slate-500 dark:text-slate-400 font-normal text-[11px]">Posisi Lowongan:</p>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 dark:text-slate-300 font-medium">{{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                                Berkas lamaran Anda telah memenuhi kriteria dan diundang untuk wawancara. Silakan cek rincian jadwal pada kartu undangan di atas.
                            </p>

                            <div class="pt-1 flex flex-col gap-2">
                                <button type="button" @click="dismissModal()" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-calendar-days text-xs"></i> Lihat Jadwal Wawancara
                                </button>
                            </div>
                        </div>

                    @elseif($isTest)
                        <!-- TEST MODAL -->
                        <div class="bg-indigo-600 text-white p-6 sm:p-7 text-center relative overflow-hidden">
                            <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center text-xl mx-auto mb-2.5 shadow-xs border border-white/20">
                                <i class="fa-solid fa-laptop-code"></i>
                            </div>
                            <span class="px-2.5 py-0.5 bg-white/20 text-white rounded-md text-[10px] font-semibold uppercase tracking-wider inline-block mb-1.5 border border-white/20">
                                Asesmen Kemampuan
                            </span>
                            <h2 class="text-lg sm:text-xl font-bold tracking-tight text-white mb-0.5">Ujian / Tes Online Seleksi</h2>
                            <p class="text-xs text-indigo-100 font-normal">Tahap Penilaian Kemampuan Teknis</p>
                        </div>

                        <div class="p-6 space-y-4 text-center">
                            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-200 dark:border-slate-800 text-xs space-y-1">
                                <p class="text-slate-500 dark:text-slate-400 font-normal text-[11px]">Posisi Lowongan:</p>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 dark:text-slate-300 font-medium">{{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                                Lamaran Anda dinyatakan berhak mengikuti tahapan Tes Online. Pastikan koneksi internet Anda stabil sebelum memulai ujian.
                            </p>

                            <div class="pt-1 flex flex-col gap-2">
                                <a href="{{ route('candidate.tests.show', $statusPopupApp->job) }}" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i> Mulai Kerjakan Tes Sekarang &rarr;
                                </a>
                                <button type="button" @click="dismissModal()" class="text-xs text-slate-400 font-medium hover:underline py-1">Kerjakan Nanti</button>
                            </div>
                        </div>

                    @elseif($isRejected)
                        <!-- REJECTED MODAL -->
                        <div class="bg-slate-800 dark:bg-slate-950 text-white p-6 sm:p-7 text-center relative overflow-hidden">
                            <div class="w-12 h-12 rounded-xl bg-white/10 text-rose-400 flex items-center justify-center text-xl mx-auto mb-2.5 shadow-xs border border-white/10">
                                <i class="fa-solid fa-circle-info"></i>
                            </div>
                            <span class="px-2.5 py-0.5 bg-white/10 text-slate-300 rounded-md text-[10px] font-semibold uppercase tracking-wider inline-block mb-1.5 border border-white/10">
                                Status Lamaran
                            </span>
                            <h2 class="text-lg sm:text-xl font-bold tracking-tight text-white mb-0.5">Pemberitahuan Tahapan Seleksi</h2>
                            <p class="text-xs text-slate-400 font-normal">Informasi Terkait Hasil Seleksi Berkas</p>
                        </div>

                        <div class="p-6 space-y-4 text-center">
                            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-200 dark:border-slate-800 text-xs space-y-1">
                                <p class="text-slate-500 dark:text-slate-400 font-normal text-[11px]">Posisi Lowongan:</p>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 dark:text-slate-300 font-medium">{{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                                Terima kasih atas minat dan partisipasi Anda pada lowongan ini. Saat ini kualifikasi Anda belum sesuai dengan kebutuhan posisi terkait. Tetap semangat melamar peluang lainnya.
                            </p>

                            <div class="pt-1 flex flex-col gap-2">
                                <a href="{{ route('jobs.index') }}" class="w-full py-2.5 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-magnifying-glass text-xs"></i> Jelajahi Lowongan Lainnya &rarr;
                                </a>
                                <button type="button" @click="dismissModal()" class="text-xs text-slate-400 font-medium hover:underline py-1">Tutup</button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endif

    <x-live-chat-drawer />
</x-app-layout>
