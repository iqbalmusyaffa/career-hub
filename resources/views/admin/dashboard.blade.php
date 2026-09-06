<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-slate-900 dark:bg-slate-700 text-white flex items-center justify-center text-xs">
                        <i class="fa-solid fa-chart-pie"></i>
                    </span>
                    Dashboard & Funnel Rekrutmen
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Statistik konversi pelamar, funnel seleksi rekrutmen, dan performa penerbitan dokumen resmi.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.jobs.index') }}?create=1" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Tambah Lowongan
                </a>
                <a href="{{ route('admin.analytics.index') }}" class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold py-2 px-4 rounded-xl text-xs transition border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-slate-400 text-xs"></i> Analitik Rekrutmen
                </a>
                <a href="{{ route('admin.calendar.index') }}" class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold py-2 px-4 rounded-xl text-xs transition border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-slate-400 text-xs"></i> Kalender Interview
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50/60 dark:bg-slate-900 min-h-screen transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- METRIC BENTO CARDS GRID (4 COLUMNS) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Lowongan -->
                <a href="{{ route('admin.jobs.index') }}" class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs hover:border-slate-300 dark:hover:border-slate-600 transition-all border border-slate-200/80 dark:border-slate-700/80 space-y-2 group cursor-pointer block">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Total Lowongan</span>
                        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs border border-blue-100 dark:border-blue-800">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $totalJobs }}</div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal"><strong class="text-blue-600 dark:text-blue-400 font-semibold">{{ $activeJobs }}</strong> lowongan aktif</div>
                    </div>
                </a>

                <!-- Total Pelamar -->
                <a href="{{ route('admin.applications.index') }}" class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs hover:border-slate-300 dark:hover:border-slate-600 transition-all border border-slate-200/80 dark:border-slate-700/80 space-y-2 group cursor-pointer block">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Total Pelamar</span>
                        <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs border border-indigo-100 dark:border-indigo-800">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalApplicants) }}</div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal"><strong class="text-slate-700 dark:text-slate-300 font-semibold">{{ number_format($totalApplications) }}</strong> berkas masuk</div>
                    </div>
                </a>

                <!-- Tahap Wawancara -->
                <a href="{{ route('admin.calendar.index') }}" class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs hover:border-slate-300 dark:hover:border-slate-600 transition-all border border-slate-200/80 dark:border-slate-700/80 space-y-2 group cursor-pointer block">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition">Tahap Wawancara</span>
                        <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs border border-amber-100 dark:border-amber-800">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 tracking-tight">{{ $statusCounts['interview'] ?? 0 }}</div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Kandidat aktif interview</div>
                    </div>
                </a>

                <!-- Rate Konversi Hired -->
                <a href="{{ route('admin.applications.index') }}" class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs hover:border-slate-300 dark:hover:border-slate-600 transition-all border border-slate-200/80 dark:border-slate-700/80 space-y-2 group cursor-pointer block">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition">Tingkat Konversi</span>
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs border border-emerald-100 dark:border-emerald-800">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 tracking-tight">{{ $conversionRate }}%</div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal"><strong class="text-emerald-700 dark:text-emerald-400 font-semibold">{{ $statusCounts['accepted'] ?? 0 }}</strong> lolos seleksi</div>
                    </div>
                </a>
            </div>

            <!-- RECRUITMENT FUNNEL CONVERSION BAR -->
            <div class="bg-white dark:bg-slate-800 p-5 sm:p-6 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-5">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 dark:border-slate-700/60 pb-3 gap-2">
                    <div>
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                            <i class="fa-solid fa-filter text-blue-600 dark:text-blue-400 text-xs"></i> Funnel Rekrutmen Pelamar
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Tahapan proses seleksi pelamar dari berkas masuk hingga penerimaan.</p>
                    </div>
                    <span class="px-3 py-1 bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-800 text-blue-800 dark:text-blue-300 text-xs font-semibold rounded-lg">
                        Konversi Kelulusan: {{ $conversionRate }}%
                    </span>
                </div>

                <!-- FUNNEL STAGES GRID -->
                @php
                    $maxFunnel = max($totalApplications, 1);
                    $pendingPct = round(($statusCounts['pending'] / $maxFunnel) * 100, 1);
                    $reviewPct = round(($statusCounts['reviewing'] / $maxFunnel) * 100, 1);
                    $interviewPct = round(($statusCounts['interview'] / $maxFunnel) * 100, 1);
                    $hiredPct = round(($statusCounts['accepted'] / $maxFunnel) * 100, 1);
                    $rejectedPct = round(($statusCounts['rejected'] / $maxFunnel) * 100, 1);
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 text-center">
                    <!-- STAGE 1: PENDING -->
                    <a href="{{ route('admin.applications.index') }}?status=pending" class="p-3 bg-slate-50 dark:bg-slate-900/60 hover:bg-slate-100/80 dark:hover:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-700 space-y-1.5 transition block cursor-pointer group">
                        <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 block group-hover:text-slate-900 dark:group-hover:text-white">1. Masuk</span>
                        <div class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($statusCounts['pending']) }}</div>
                        <div class="w-full bg-slate-200 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-slate-700 dark:bg-slate-400 h-full rounded-full" style="width: {{ $pendingPct }}%;"></div>
                        </div>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-normal">{{ $pendingPct }}% dari total</span>
                    </a>

                    <!-- STAGE 2: REVIEWING -->
                    <a href="{{ route('admin.applications.index') }}?status=reviewing" class="p-3 bg-blue-50/40 dark:bg-blue-950/20 hover:bg-blue-100/40 dark:hover:bg-blue-950/40 rounded-xl border border-blue-100 dark:border-blue-900/60 space-y-1.5 transition block cursor-pointer group">
                        <span class="text-[11px] font-semibold text-blue-900 dark:text-blue-300 block">2. Peninjauan</span>
                        <div class="text-lg font-bold text-blue-900 dark:text-blue-300">{{ number_format($statusCounts['reviewing']) }}</div>
                        <div class="w-full bg-blue-200 dark:bg-blue-900 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-blue-600 dark:bg-blue-400 h-full rounded-full" style="width: {{ $reviewPct }}%;"></div>
                        </div>
                        <span class="text-[10px] text-blue-800 dark:text-blue-400 font-normal">{{ $reviewPct }}% dari total</span>
                    </a>

                    <!-- STAGE 3: INTERVIEW -->
                    <a href="{{ route('admin.calendar.index') }}" class="p-3 bg-amber-50/40 dark:bg-amber-950/20 hover:bg-amber-100/40 dark:hover:bg-amber-950/40 rounded-xl border border-amber-100 dark:border-amber-900/60 space-y-1.5 transition block cursor-pointer group">
                        <span class="text-[11px] font-semibold text-amber-900 dark:text-amber-300 block">3. Wawancara</span>
                        <div class="text-lg font-bold text-amber-900 dark:text-amber-300">{{ number_format($statusCounts['interview']) }}</div>
                        <div class="w-full bg-amber-200 dark:bg-amber-900 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-amber-500 dark:bg-amber-400 h-full rounded-full" style="width: {{ $interviewPct }}%;"></div>
                        </div>
                        <span class="text-[10px] text-amber-800 dark:text-amber-400 font-normal">{{ $interviewPct }}% dari total</span>
                    </a>

                    <!-- STAGE 4: HIRED -->
                    <a href="{{ route('admin.applications.index') }}?status=accepted" class="p-3 bg-emerald-50/40 dark:bg-emerald-950/20 hover:bg-emerald-100/40 dark:hover:bg-emerald-950/40 rounded-xl border border-emerald-100 dark:border-emerald-900/60 space-y-1.5 transition block cursor-pointer group">
                        <span class="text-[11px] font-semibold text-emerald-900 dark:text-emerald-300 block">4. Diterima</span>
                        <div class="text-lg font-bold text-emerald-900 dark:text-emerald-300">{{ number_format($statusCounts['accepted']) }}</div>
                        <div class="w-full bg-emerald-200 dark:bg-emerald-900 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-600 dark:bg-emerald-400 h-full rounded-full" style="width: {{ $hiredPct }}%;"></div>
                        </div>
                        <span class="text-[10px] text-emerald-800 dark:text-emerald-400 font-normal">{{ $hiredPct }}% dari total</span>
                    </a>

                    <!-- STAGE 5: REJECTED -->
                    <a href="{{ route('admin.applications.index') }}?status=rejected" class="p-3 bg-rose-50/40 dark:bg-rose-950/20 hover:bg-rose-100/40 dark:hover:bg-rose-950/40 rounded-xl border border-rose-100 dark:border-rose-900/60 space-y-1.5 transition block cursor-pointer group">
                        <span class="text-[11px] font-semibold text-rose-900 dark:text-rose-300 block">5. Tidak Lolos</span>
                        <div class="text-lg font-bold text-rose-900 dark:text-rose-300">{{ number_format($statusCounts['rejected']) }}</div>
                        <div class="w-full bg-rose-200 dark:bg-rose-900 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-rose-500 dark:bg-rose-400 h-full rounded-full" style="width: {{ $rejectedPct }}%;"></div>
                        </div>
                        <span class="text-[10px] text-rose-800 dark:text-rose-400 font-normal">{{ $rejectedPct }}% dari total</span>
                    </a>
                </div>
            </div>

            <!-- DOCUMENT & WORK TYPE STATISTICS BAR -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- CHART 1: MONTHLY APPLICANT TREND -->
                <div class="bg-white dark:bg-slate-800 p-5 sm:p-6 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700/60 pb-3">
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-chart-area text-blue-600 dark:text-blue-400 text-xs"></i> Tren Pertumbuhan Pelamar (6 Bulan Terakhir)
                        </h3>
                    </div>
                    <div class="h-64 relative flex items-center justify-center">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>

                <!-- LEGAL DOCUMENTS ISSUED STATS -->
                <div class="bg-white dark:bg-slate-800 p-5 sm:p-6 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-4">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3">
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-file-shield text-slate-600 dark:text-slate-400 text-xs"></i> Dokumen Resmi HR Diterbitkan
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Total dokumen legalitas karir & magang yang diterbitkan sistem.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200/80 dark:border-slate-700 space-y-1">
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 block flex items-center gap-1.5">
                                <i class="fa-solid fa-file-contract text-slate-400 text-xs"></i> Perjanjian Kerja
                            </span>
                            <div class="text-xl font-bold text-slate-900 dark:text-white">{{ number_format($agreementsCount) }}</div>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">PKWT, PKWTT, Remote</span>
                        </div>

                        <div class="p-3.5 bg-amber-50/40 dark:bg-amber-950/20 rounded-xl border border-amber-100 dark:border-amber-900/60 space-y-1">
                            <span class="text-xs font-semibold text-amber-900 dark:text-amber-300 block flex items-center gap-1.5">
                                <i class="fa-solid fa-graduation-cap text-amber-500 text-xs"></i> Sertifikat Magang
                            </span>
                            <div class="text-xl font-bold text-amber-900 dark:text-amber-300">{{ number_format($certificatesCount) }}</div>
                            <span class="text-[10px] text-amber-700/70 dark:text-amber-400/70 font-normal">E-Sertifikat Resmi</span>
                        </div>

                        <div class="p-3.5 bg-blue-50/40 dark:bg-blue-950/20 rounded-xl border border-blue-100 dark:border-blue-900/60 space-y-1">
                            <span class="text-xs font-semibold text-blue-900 dark:text-blue-300 block flex items-center gap-1.5">
                                <i class="fa-solid fa-square-poll-vertical text-blue-500 text-xs"></i> Transkrip Nilai
                            </span>
                            <div class="text-xl font-bold text-blue-900 dark:text-blue-300">{{ number_format($transcriptsCount) }}</div>
                            <span class="text-[10px] text-blue-700/70 dark:text-blue-400/70 font-normal">Evaluasi & Nilai Magang</span>
                        </div>

                        <div class="p-3.5 bg-slate-100 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700 space-y-1">
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 block flex items-center gap-1.5">
                                <i class="fa-solid fa-file-signature text-slate-500 text-xs"></i> Surat Keterangan
                            </span>
                            <div class="text-xl font-bold text-slate-900 dark:text-white">{{ number_format($terminationsCount) }}</div>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 font-normal">Paklaring, Rekomendasi</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid: Latest Applications & Upcoming Interviews -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Latest Applications (2 Cols) -->
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 p-5 sm:p-6 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700/60 pb-3">
                        <div>
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-slate-400 text-xs"></i> Pelamar Terbaru
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Daftar berkas lamaran yang baru saja dikirim oleh kandidat.</p>
                        </div>
                        <a href="{{ route('admin.applications.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    @if(count($latestApplications) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50/80 dark:bg-slate-900/40 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200/80 dark:border-slate-700 uppercase tracking-wider text-[11px]">
                                        <th class="p-3 pl-4">Kandidat</th>
                                        <th class="p-3">Posisi Dilamar</th>
                                        <th class="p-3">Status</th>
                                        <th class="p-3 pr-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-slate-700 dark:text-slate-300 font-normal">
                                    @foreach($latestApplications as $app)
                                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/30 transition cursor-pointer group" onclick="window.location.href='{{ route('admin.applications.show', $app->id) }}'">
                                            <td class="p-3 pl-4 whitespace-nowrap">
                                                <div class="font-bold text-slate-900 dark:text-white text-xs group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">{{ $app->user->name ?? 'Kandidat' }}</div>
                                                <div class="text-[11px] text-slate-400 dark:text-slate-500 font-normal">{{ $app->user->email ?? '-' }}</div>
                                            </td>
                                            <td class="p-3 whitespace-nowrap">
                                                <div class="font-medium text-slate-800 dark:text-slate-200 text-xs">{{ $app->job->title ?? '-' }}</div>
                                            </td>
                                            <td class="p-3 whitespace-nowrap">
                                                @php
                                                    $statusStr = is_object($app->status) ? $app->status->value : (string) $app->status;
                                                    $badgeStyle = match($statusStr) {
                                                        'pending' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                                        'reviewing' => 'bg-blue-50 dark:bg-blue-950/40 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                                        'interview' => 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-800 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
                                                        'accepted' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                                        'rejected' => 'bg-rose-50 dark:bg-rose-950/40 text-rose-800 dark:text-rose-300 border-rose-200 dark:border-rose-800',
                                                        default => 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                                                    };
                                                @endphp
                                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-md border uppercase tracking-wider {{ $badgeStyle }}">
                                                    {{ $statusStr }}
                                                </span>
                                            </td>
                                            <td class="p-3 pr-4 text-right whitespace-nowrap">
                                                <a href="{{ route('admin.applications.show', $app->id) }}" class="px-3 py-1 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg text-xs transition shadow-xs inline-flex items-center gap-1">
                                                    Review Berkas
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-dashed border-slate-200 dark:border-slate-700 text-xs text-slate-500 dark:text-slate-400 font-normal">
                            Belum ada berkas lamaran baru yang masuk.
                        </div>
                    @endif
                </div>

                <!-- Right Column: Upcoming Interviews -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 p-5 sm:p-6 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700/60 pb-3">
                        <div>
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-calendar-check text-slate-400 text-xs"></i> Wawancara Mendatang
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Jadwal interview aktif kandidat.</p>
                        </div>
                        <a href="{{ route('admin.calendar.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                            Kalender &rarr;
                        </a>
                    </div>

                    @if(count($upcomingInterviews) > 0)
                        <div class="space-y-3">
                            @foreach($upcomingInterviews as $interview)
                                <a href="{{ route('admin.applications.show', $interview->application_id) }}" class="p-3.5 bg-slate-50 dark:bg-slate-900/60 hover:bg-slate-100/80 dark:hover:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-700 space-y-1.5 text-xs transition cursor-pointer block group">
                                    <div class="flex justify-between items-start">
                                        <span class="font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">{{ $interview->application->user->name ?? 'Kandidat' }}</span>
                                        <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold rounded text-[10px] uppercase border border-blue-100 dark:border-blue-800">
                                            {{ strtoupper($interview->type) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 font-normal">{{ $interview->application->job->title ?? '-' }}</p>
                                    <div class="text-[11px] text-slate-400 dark:text-slate-500 pt-1 flex items-center gap-1.5 border-t border-slate-200/60 dark:border-slate-700/60">
                                        <i class="fa-regular fa-clock text-slate-400"></i>
                                        <span>{{ $interview->scheduled_at->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-dashed border-slate-200 dark:border-slate-700 text-xs text-slate-400 dark:text-slate-500 font-normal">
                            Belum ada jadwal wawancara mendatang.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- CHART.JS INTEGRATION -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('monthlyTrendChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyTrendLabels) !!},
                    datasets: [{
                        label: 'Jumlah Lamaran Masuk',
                        data: {!! json_encode($monthlyTrendData) !!},
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#2563eb'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
