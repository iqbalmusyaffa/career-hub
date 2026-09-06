<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 text-[11px] font-semibold rounded-md border border-rose-200 dark:border-rose-800">
                        Anti-Fraud Moderation
                    </span>
                    <span class="text-xs text-slate-400 dark:text-slate-500">| Red Flag Sentinel</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight">
                    Moderasi Laporan Red Flag Perusahaan
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                    Pusat peninjauan bukti aduan pelamar, investigasi dugaan penipuan, dan eksekusi sanksi blacklist perusahaan oleh Super Admin.
                </p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('admin.blacklists.index') }}" class="bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-medium py-2 px-3.5 rounded-xl text-xs transition shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-user-shield text-xs text-rose-400"></i>
                    <span>Daftar Blacklist</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium py-2 px-3.5 rounded-xl text-xs transition border border-slate-200 dark:border-slate-700 shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-slate-400 text-xs"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 min-h-screen bg-slate-50/60 dark:bg-slate-900 transition-colors" 
         x-data="{
             selectedReport: null,
             modalOpen: false,
             previewImage: null,
             lightboxOpen: false,
             openModerationModal(report) {
                 this.selectedReport = report;
                 this.modalOpen = true;
             },
             openLightbox(imgSrc) {
                 this.previewImage = imgSrc;
                 this.lightboxOpen = true;
             }
         }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-xl text-xs font-medium flex items-center gap-3 shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm shrink-0"></i>
                    <div class="flex-1">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-xl text-xs font-medium flex items-center gap-3 shadow-2xs">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 dark:text-rose-400 text-sm shrink-0"></i>
                    <div class="flex-1">{{ session('error') }}</div>
                </div>
            @endif

            <!-- 1. METRIC SUMMARY CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Laporan -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Aduan Masuk</span>
                        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs border border-blue-100 dark:border-blue-800">
                            <i class="fa-solid fa-flag"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($counts['all']) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Semua laporan dari pelamar</p>
                    </div>
                </div>

                <!-- Menunggu Review -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Menunggu Verifikasi</span>
                        <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs border border-amber-100 dark:border-amber-800">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 tracking-tight">{{ number_format($counts['pending']) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Perlu pemeriksaan bukti</p>
                    </div>
                </div>

                <!-- Sedang Diinvestigasi -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Dalam Investigasi</span>
                        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs border border-blue-100 dark:border-blue-800">
                            <i class="fa-solid fa-magnifying-glass-chart"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 tracking-tight">{{ number_format($counts['investigating']) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Proses cross-check data</p>
                    </div>
                </div>

                <!-- Terbukti Blacklisted -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Terbukti & Blacklisted</span>
                        <div class="w-9 h-9 rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xs border border-rose-100 dark:border-rose-800">
                            <i class="fa-solid fa-ban"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-rose-600 dark:text-rose-400 tracking-tight">{{ number_format($counts['resolved_blacklisted']) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Sanksi aktif sistem</p>
                    </div>
                </div>
            </div>

            <!-- 2. STATUS TABS BAR -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 border-b border-slate-200/80 dark:border-slate-700/80 text-xs">
                @php
                    $currentStatus = request('status', 'all');
                @endphp
                <a href="{{ route('admin.company-reports.index', ['status' => 'all', 'search' => request('search')]) }}"
                   class="px-3.5 py-1.5 rounded-xl font-medium transition whitespace-nowrap {{ $currentStatus === 'all' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700/80' }}">
                    <i class="fa-solid fa-layer-group text-[10px] mr-1"></i> Semua Laporan ({{ $counts['all'] }})
                </a>
                <a href="{{ route('admin.company-reports.index', ['status' => 'pending', 'search' => request('search')]) }}"
                   class="px-3.5 py-1.5 rounded-xl font-medium transition whitespace-nowrap {{ $currentStatus === 'pending' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700/80' }}">
                    <i class="fa-solid fa-hourglass-half text-[10px] mr-1 text-amber-500"></i> Menunggu Review ({{ $counts['pending'] }})
                </a>
                <a href="{{ route('admin.company-reports.index', ['status' => 'investigating', 'search' => request('search')]) }}"
                   class="px-3.5 py-1.5 rounded-xl font-medium transition whitespace-nowrap {{ $currentStatus === 'investigating' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700/80' }}">
                    <i class="fa-solid fa-magnifying-glass text-[10px] mr-1 text-blue-500"></i> Investigasi ({{ $counts['investigating'] }})
                </a>
                <a href="{{ route('admin.company-reports.index', ['status' => 'resolved_blacklisted', 'search' => request('search')]) }}"
                   class="px-3.5 py-1.5 rounded-xl font-medium transition whitespace-nowrap {{ $currentStatus === 'resolved_blacklisted' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700/80' }}">
                    <i class="fa-solid fa-shield-halved text-[10px] mr-1 text-rose-500"></i> Blacklisted ({{ $counts['resolved_blacklisted'] }})
                </a>
                <a href="{{ route('admin.company-reports.index', ['status' => 'dismissed', 'search' => request('search')]) }}"
                   class="px-3.5 py-1.5 rounded-xl font-medium transition whitespace-nowrap {{ $currentStatus === 'dismissed' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700/80' }}">
                    <i class="fa-solid fa-ban text-[10px] mr-1 text-slate-400"></i> Ditolak ({{ $counts['dismissed'] }})
                </a>
            </div>

            <!-- 3. MAIN MODERATION TABLE CONTAINER -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 overflow-hidden shadow-2xs">
                
                <!-- Table Header & Search Filter Bar -->
                <div class="p-5 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-rose-600 dark:text-rose-400"></i>
                            <span>Daftar Laporan Aduan Red Flag</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total {{ $reports->total() }} aduan terdaftar dalam database.</p>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.company-reports.index') }}" class="flex items-center gap-2 flex-wrap">
                        <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perusahaan atau pelapor..." 
                                class="pl-8 pr-3 py-1.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-rose-600 focus:border-rose-600">
                        </div>

                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold py-1.5 px-3.5 rounded-xl text-xs transition shadow-xs flex items-center gap-1.5">
                            <i class="fa-solid fa-filter text-[10px]"></i>
                            <span>Filter</span>
                        </button>

                        @if(request('search') || (request('status') && request('status') !== 'all'))
                            <a href="{{ route('admin.company-reports.index') }}" class="px-2.5 py-1.5 bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl text-xs border border-slate-200 dark:border-slate-800 transition">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-700/80 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-4 pl-6">Perusahaan Terlapor</th>
                                <th class="py-3 px-4">Pelapor & Tanggal</th>
                                <th class="py-3 px-4">Kategori Indikasi</th>
                                <th class="py-3 px-4">Uraian Alasan</th>
                                <th class="py-3 px-4">Bukti Lampiran</th>
                                <th class="py-3 px-4">Status & Catatan</th>
                                <th class="py-3 px-4 pr-6 text-right">Aksi Moderasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs text-slate-700 dark:text-slate-300">
                            @forelse($reports as $report)
                                @php
                                    $isAlreadyBlacklisted = \App\Models\Blacklist::isBlocked($report->company_name, 'company_name');
                                    $evidenceList = $report->evidence_list;
                                @endphp
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/30 transition items-start">
                                    
                                    <!-- Perusahaan Terlapor -->
                                    <td class="py-3.5 px-4 pl-6 align-top min-w-48">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-900 dark:text-white text-xs">{{ $report->company_name }}</span>
                                            @if($isAlreadyBlacklisted)
                                                <span class="px-1.5 py-0.2 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800 rounded text-[9px] font-mono font-semibold uppercase tracking-wider" title="Perusahaan sudah ada di Blacklist">
                                                    BLACKLISTED
                                                </span>
                                            @endif
                                        </div>
                                        @if($report->job)
                                            <div class="mt-1 text-slate-500 dark:text-slate-400 text-[11px] flex items-center gap-1">
                                                <i class="fa-solid fa-briefcase text-slate-400 text-[10px]"></i>
                                                <a href="{{ route('jobs.show', $report->job_id) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline font-medium line-clamp-1">
                                                    {{ $report->job->title }} &rarr;
                                                </a>
                                            </div>
                                        @else
                                            <div class="mt-1 text-[11px] text-slate-400 italic">Aduan umum perusahaan</div>
                                        @endif
                                    </td>

                                    <!-- Pelapor & Tanggal -->
                                    <td class="py-3.5 px-4 align-top whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 font-bold flex items-center justify-center text-[10px] border border-blue-200 dark:border-blue-800">
                                                {{ strtoupper(substr($report->reporter->name ?? 'A', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-900 dark:text-white text-xs">{{ $report->reporter->name ?? 'Pelamar Anonim' }}</div>
                                                <div class="text-[11px] text-slate-400 dark:text-slate-500">{{ $report->reporter->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 font-mono mt-1 flex items-center gap-1">
                                            <i class="fa-regular fa-clock text-[9px]"></i>
                                            <span>{{ $report->created_at->format('d M Y, H:i') }} WIB</span>
                                        </div>
                                    </td>

                                    <!-- Kategori -->
                                    <td class="py-3.5 px-4 align-top">
                                        <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-mono font-semibold border leading-tight
                                            {{ $report->report_category === 'deposit_fee' ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800' : '' }}
                                            {{ $report->report_category === 'diploma_withholding' ? 'bg-orange-50 dark:bg-orange-950/60 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-800' : '' }}
                                            {{ $report->report_category === 'under_umk' ? 'bg-yellow-50 dark:bg-yellow-950/60 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800' : '' }}
                                            {{ $report->report_category === 'fake_company' ? 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border-rose-200 dark:border-rose-800' : '' }}
                                            {{ $report->report_category === 'harassment' ? 'bg-red-50 dark:bg-red-950/60 text-red-800 dark:text-red-400 border-red-200 dark:border-red-800' : '' }}
                                            {{ !in_array($report->report_category, ['deposit_fee', 'diploma_withholding', 'under_umk', 'fake_company', 'harassment']) ? 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-800' : '' }}">
                                            {{ $report->category_label }}
                                        </span>
                                    </td>

                                    <!-- Uraian Alasan -->
                                    <td class="py-3.5 px-4 align-top max-w-xs">
                                        <div class="text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-950 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 leading-relaxed font-normal">
                                            {{ $report->reason_description }}
                                        </div>
                                    </td>

                                    <!-- Bukti Lampiran -->
                                    <td class="py-3.5 px-4 align-top">
                                        @if(empty($evidenceList))
                                            <span class="text-[11px] text-slate-400 italic">Tanpa berkas</span>
                                        @else
                                            <div class="space-y-1.5">
                                                <div class="flex flex-wrap gap-1.5 max-w-[140px]">
                                                    @foreach($evidenceList as $ev)
                                                        @if(filter_var($ev, FILTER_VALIDATE_URL) && (str_contains($ev, 'drive.google.com') || str_contains($ev, 'docs.google.com') || str_contains($ev, 'dropbox.com')))
                                                            <a href="{{ $ev }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 text-blue-700 dark:text-blue-300 rounded-lg text-[10px] font-medium border border-blue-200 dark:border-blue-800 transition" title="Buka Link Dokumen Google Drive">
                                                                <i class="fa-brands fa-google-drive text-blue-500"></i> Link GDrive &rarr;
                                                            </a>
                                                        @else
                                                            <button type="button" @click="openLightbox('{{ $ev }}')" class="group relative block w-10 h-10 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 hover:border-blue-500 shadow-2xs">
                                                                <img src="{{ $ev }}" alt="Bukti" class="w-full h-full object-cover group-hover:scale-110 transition duration-150">
                                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-[10px]">
                                                                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                                                                </div>
                                                            </button>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <div class="text-[10px] text-slate-400 font-mono">
                                                    {{ count($evidenceList) }} file
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Status & Catatan -->
                                    <td class="py-3.5 px-4 align-top whitespace-nowrap">
                                        @if($report->status === 'resolved_blacklisted')
                                            <span class="px-2 py-0.5 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 font-mono font-semibold rounded-md border border-rose-200 dark:border-rose-800 text-[10px] inline-flex items-center gap-1">
                                                <i class="fa-solid fa-shield-halved text-[9px]"></i> BLACKLISTED
                                            </span>
                                        @elseif($report->status === 'investigating')
                                            <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 font-mono font-semibold rounded-md border border-blue-200 dark:border-blue-800 text-[10px] inline-flex items-center gap-1">
                                                <i class="fa-solid fa-magnifying-glass text-[9px]"></i> INVESTIGASI
                                            </span>
                                        @elseif($report->status === 'dismissed')
                                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 font-mono font-semibold rounded-md border border-slate-200 dark:border-slate-800 text-[10px] inline-flex items-center gap-1">
                                                <i class="fa-solid fa-ban text-[9px]"></i> DITOLAK
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 font-mono font-semibold rounded-md border border-amber-200 dark:border-amber-800 text-[10px] inline-flex items-center gap-1">
                                                <i class="fa-solid fa-hourglass-half text-[9px] animate-pulse"></i> MENUNGGU REVIEW
                                            </span>
                                        @endif

                                        @if($report->admin_notes)
                                            <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 max-w-[160px] truncate" title="{{ $report->admin_notes }}">
                                                <span class="font-semibold text-slate-700 dark:text-slate-300">Catatan:</span> {{ $report->admin_notes }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Aksi Super Admin -->
                                    <td class="py-3.5 px-4 pr-6 align-top text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- Tombol Moderasi ACC / Investigasi / Tolak -->
                                            <button type="button" 
                                                    @click="openModerationModal({{ json_encode([
                                                        'id' => $report->id,
                                                        'company_name' => $report->company_name,
                                                        'category_label' => $report->category_label,
                                                        'status' => $report->status,
                                                        'admin_notes' => $report->admin_notes ?? '',
                                                        'reason_description' => $report->reason_description,
                                                        'reporter_name' => $report->reporter->name ?? 'Pelamar',
                                                        'action_url' => route('admin.company-reports.updateStatus', $report->id)
                                                    ]) }})" 
                                                    class="px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-medium rounded-lg text-xs transition shadow-xs flex items-center gap-1.5">
                                                <i class="fa-solid fa-gavel text-xs text-amber-300"></i>
                                                <span>Moderasi</span>
                                            </button>

                                            <!-- Tombol Hapus Laporan -->
                                            <form method="POST" action="{{ route('admin.company-reports.destroy', $report->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berkas laporan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition" title="Hapus Laporan">
                                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs font-medium">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-900 text-slate-400 dark:text-slate-600 flex items-center justify-center text-xl mx-auto mb-3">
                                            <i class="fa-solid fa-shield-check text-emerald-500"></i>
                                        </div>
                                        <div class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tidak Ada Laporan Red Flag</div>
                                        <p class="text-slate-400 mt-1">
                                            @if(request('status') || request('search'))
                                                Tidak ditemukan laporan yang sesuai dengan filter pencarian.
                                            @else
                                                Platform berjalan bersih. Belum ada aduan pelanggaran atau red flag dari pelamar.
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($reports->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-950/60">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- 4. MODAL MODERASI & ACC SUPER ADMIN (Alpine.js) -->
        <div x-show="modalOpen" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="modalOpen" 
                     x-transition:enter="ease-out duration-200" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-150" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0" 
                     @click="modalOpen = false"
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="modalOpen" 
                     x-transition:enter="ease-out duration-200" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-150" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200/80 dark:border-slate-800">
                    
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-950/50">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-gavel"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-slate-900 dark:text-white" id="modal-title">
                                    Moderasi Laporan Red Flag
                                </h3>
                                <p class="text-[11px] text-slate-400 font-mono truncate max-w-[280px]" x-text="'Perusahaan: ' + (selectedReport ? selectedReport.company_name : '')"></p>
                            </div>
                        </div>
                        <button type="button" @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </button>
                    </div>

                    <form :action="selectedReport ? selectedReport.action_url : '#'" method="POST" class="p-6 space-y-4 text-xs">
                        @csrf
                        @method('PATCH')

                        <!-- Summary Info Card -->
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200/80 dark:border-slate-800 space-y-1.5">
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-slate-400 font-medium">Pelapor:</span>
                                <span class="font-semibold text-slate-900 dark:text-white" x-text="selectedReport ? selectedReport.reporter_name : ''"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-slate-400 font-medium">Kategori Indikasi:</span>
                                <span class="font-semibold text-rose-600 dark:text-rose-400 font-mono text-[11px]" x-text="selectedReport ? selectedReport.category_label : ''"></span>
                            </div>
                        </div>

                        <!-- Decision Radio Options -->
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Keputusan Tindak Lanjut Super Admin <span class="text-rose-500">*</span>
                            </label>
                            
                            <label class="flex items-start gap-3 p-3 rounded-xl border border-rose-200 dark:border-rose-800/80 bg-rose-50/50 dark:bg-rose-950/40 hover:bg-rose-100/50 cursor-pointer transition">
                                <input type="radio" name="status" value="resolved_blacklisted" :checked="selectedReport && selectedReport.status === 'resolved_blacklisted'" class="mt-0.5 text-rose-600 focus:ring-rose-600">
                                <div>
                                    <div class="font-bold text-xs text-rose-900 dark:text-rose-300">🛡️ ACC & Masukkan ke Blacklist Sistem</div>
                                    <div class="text-[11px] text-rose-700 dark:text-rose-400 mt-0.5">Laporan terbukti benar. Perusahaan diblacklist dan lowongan aktif dinonaktifkan.</div>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 p-3 rounded-xl border border-blue-200 dark:border-blue-800/80 bg-blue-50/50 dark:bg-blue-950/40 hover:bg-blue-100/50 cursor-pointer transition">
                                <input type="radio" name="status" value="investigating" :checked="selectedReport && selectedReport.status === 'investigating'" class="mt-0.5 text-blue-600 focus:ring-blue-600">
                                <div>
                                    <div class="font-bold text-xs text-blue-900 dark:text-blue-300">🔍 Tandai Sedang Diinvestigasi</div>
                                    <div class="text-[11px] text-blue-700 dark:text-blue-400 mt-0.5">Sedang dalam proses konfirmasi bukti atau investigasi tim Super Admin.</div>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-800/60 cursor-pointer transition">
                                <input type="radio" name="status" value="dismissed" :checked="selectedReport && selectedReport.status === 'dismissed'" class="mt-0.5 text-slate-600 focus:ring-slate-600">
                                <div>
                                    <div class="font-bold text-xs text-slate-800 dark:text-slate-200">✕ Tolak / Abaikan Laporan</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Bukti tidak valid, salah paham, atau tidak ditemukan pelanggaran aturan.</div>
                                </div>
                            </label>
                        </div>

                        <!-- Blacklist & Close Job Checkboxes -->
                        <div class="pt-2 border-t border-slate-100 dark:border-slate-800 space-y-2">
                            <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" name="add_to_blacklist" value="1" checked class="rounded border-slate-300 dark:border-slate-700 text-rose-600 focus:ring-rose-600">
                                <span>Otomatis daftarkan nama perusahaan ke modul Blacklist Anti-Fraud</span>
                            </label>
                            <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" name="close_company_jobs" value="1" checked class="rounded border-slate-300 dark:border-slate-700 text-rose-600 focus:ring-rose-600">
                                <span>Tutup semua lowongan aktif dari perusahaan ini</span>
                            </label>
                        </div>

                        <!-- Notes -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Catatan Resmi Super Admin (Untuk Riwayat & Notifikasi)
                            </label>
                            <textarea name="admin_notes" rows="3" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-rose-600 focus:border-rose-600 text-slate-900 dark:text-slate-100 py-2.5 px-3 leading-relaxed" placeholder="Tuliskan alasan keputusan, hasil verifikasi bukti, atau instruksi sanksi..." x-text="selectedReport ? selectedReport.admin_notes : ''"></textarea>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl text-xs transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                                <i class="fa-solid fa-check"></i>
                                <span>Simpan & Terapkan Keputusan</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- 5. LIGHTBOX PREVIEW BUKTI GAMBAR (Alpine.js) -->
        <div x-show="lightboxOpen" 
             style="display: none;"
             x-transition.opacity 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            
            <div @click.away="lightboxOpen = false" class="relative max-w-4xl max-h-[90vh] flex flex-col items-center">
                <button @click="lightboxOpen = false" class="absolute -top-10 right-0 text-white hover:text-rose-400 font-medium text-xs flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                    <span>Tutup Preview</span>
                </button>
                <img :src="previewImage" alt="Bukti Full Preview" class="max-w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl border border-white/20 bg-slate-900">
                <div class="mt-3 text-center">
                    <a :href="previewImage" target="_blank" download class="px-3.5 py-1.5 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-medium backdrop-blur-xs transition inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-down-to-bracket"></i>
                        <span>Buka Gambar Ukuran Asli</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
