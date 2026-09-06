<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 font-medium mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-700 dark:text-slate-300 font-semibold">Laporan & Analisis</span>
                    <span>/</span>
                    <span class="text-slate-900 dark:text-white font-bold">Custom Report Builder</span>
                </nav>
                <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">
                    Pembuat Laporan Kustom & Ekspor Data
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Konfigurasikan parameter data, tentukan kolom spesifik, dan ekspor ke format Excel, CSV, atau PDF Dokumen Resmi.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 text-xs font-semibold rounded-xl border border-blue-200/80 dark:border-blue-800 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                    </svg>
                    Data Terkoneksi Real-Time
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50/60 dark:bg-slate-900 min-h-screen transition-colors" x-data="reportBuilder()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Domain Switcher Tabs -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                @php
                    $domainConfigs = [
                        'applications' => [
                            'title' => 'Pelamar & Pipeline',
                            'desc' => 'Data kandidat, status, dan riwayat seleksi',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />',
                        ],
                        'jobs' => [
                            'title' => 'Lowongan Pekerjaan',
                            'desc' => 'Performa lowongan, kuota, dan deadline',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />',
                        ],
                        'scorecards' => [
                            'title' => 'Scorecard Wawancara',
                            'desc' => 'Hasil evaluasi teknis, user, dan rekomendasi',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />',
                        ],
                        'headcount' => [
                            'title' => 'Headcount & Budget',
                            'desc' => 'Perencanaan kuota hiring dan alokasi anggaran',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                        ],
                    ];
                @endphp

                @foreach($domainConfigs as $key => $cfg)
                    <a 
                        href="{{ route('admin.reports.builder.index', ['domain' => $key, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                        class="p-4 rounded-2xl border transition flex flex-col justify-between {{ $domain === $key ? 'bg-white dark:bg-slate-800 border-blue-500 dark:border-blue-500 ring-2 ring-blue-500/20 shadow-xs' : 'bg-white dark:bg-slate-800/80 border-slate-200/80 dark:border-slate-700/80 hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 shadow-2xs' }}"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $domain === $key ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    {!! $cfg['icon'] !!}
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-xs {{ $domain === $key ? 'text-blue-600 dark:text-blue-400' : 'text-slate-900 dark:text-white' }}">
                                    {{ $cfg['title'] }}
                                </h3>
                                <p class="text-3xs text-slate-500 dark:text-slate-400 line-clamp-1 mt-0.5">{{ $cfg['desc'] }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Report Configuration Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-2xs p-5 sm:p-6 space-y-6">
                <form id="reportForm" method="GET" action="{{ route('admin.reports.builder.index') }}" class="space-y-6">
                    <input type="hidden" name="domain" value="{{ $domain }}">

                    <!-- Date Range Selection with Presets -->
                    <div class="space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 dark:border-slate-700/60 pb-3">
                            <label class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Periode Waktu Laporan</span>
                            </label>
                            
                            <!-- Quick Date Presets -->
                            <div class="flex items-center gap-1.5 text-3xs font-semibold">
                                <span class="text-slate-400 dark:text-slate-500 mr-1">Preset:</span>
                                <button type="button" @click="setPreset(30)" class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-lg transition">30 Hari Terakhir</button>
                                <button type="button" @click="setPreset('this_month')" class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-lg transition">Bulan Ini</button>
                                <button type="button" @click="setPreset('this_year')" class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-lg transition">Tahun Ini</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Mulai</label>
                                <input 
                                    type="date" 
                                    id="start_date_input"
                                    name="start_date" 
                                    value="{{ $startDate }}" 
                                    class="w-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-2xs"
                                >
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Selesai</label>
                                <input 
                                    type="date" 
                                    id="end_date_input"
                                    name="end_date" 
                                    value="{{ $endDate }}" 
                                    class="w-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-2xs"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Column Selection with High-Contrast Checkboxes -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/60 pb-3">
                            <div class="flex items-center gap-2">
                                <label class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                    </svg>
                                    <span>Pilih Kolom Data yang Ingin Ditampilkan</span>
                                </label>
                                <span class="text-3xs font-semibold px-2 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-md">
                                    <span x-text="selectedCols.length"></span> dari {{ count($availableColumns) }} kolom aktif
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <button type="button" @click="selectAll()" class="text-blue-600 dark:text-blue-400 font-bold hover:underline transition">Pilih Semua</button>
                                <span class="text-slate-300 dark:text-slate-600">·</span>
                                <button type="button" @click="deselectAll()" class="text-slate-500 dark:text-slate-400 font-semibold hover:underline transition">Hapus Semua</button>
                            </div>
                        </div>

                        <!-- Custom High-Contrast Checkbox Cards -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-700 text-xs">
                            @foreach($availableColumns as $key => $label)
                                <label 
                                    class="relative flex items-center gap-2.5 p-2.5 rounded-xl border transition cursor-pointer select-none group"
                                    :class="selectedCols.includes('{{ $key }}') ? 'bg-blue-50/70 dark:bg-blue-950/40 border-blue-500 dark:border-blue-500 ring-1 ring-blue-500/20 shadow-2xs' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600'"
                                >
                                    <input 
                                        type="checkbox" 
                                        name="columns[]" 
                                        value="{{ $key }}" 
                                        x-model="selectedCols"
                                        class="sr-only"
                                    >

                                    <!-- Crisp High-Contrast Checkmark Icon Box -->
                                    <div 
                                        class="w-4.5 h-4.5 rounded-md flex items-center justify-center border transition shrink-0"
                                        :class="selectedCols.includes('{{ $key }}') ? 'bg-blue-600 border-blue-600 text-white shadow-2xs' : 'bg-white dark:bg-slate-900 border-slate-300 dark:border-slate-600 group-hover:border-slate-400'"
                                    >
                                        <svg x-show="selectedCols.includes('{{ $key }}')" class="w-3.5 h-3.5 stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>

                                    <span 
                                        class="text-xs tracking-tight" 
                                        :class="selectedCols.includes('{{ $key }}') ? 'text-blue-950 dark:text-blue-200 font-bold' : 'text-slate-800 dark:text-slate-200 font-medium'"
                                    >
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Toolbar -->
                    <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 pt-3 border-t border-slate-100 dark:border-slate-700/60">
                        <button 
                            type="submit" 
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-900 dark:bg-slate-700 hover:bg-slate-800 dark:hover:bg-slate-600 text-white text-xs font-semibold rounded-xl transition shadow-2xs"
                        >
                            <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Perbarui Pratinjau Tabel
                        </button>

                        <!-- Export Options -->
                        <div class="flex flex-wrap items-center gap-2">
                            <button 
                                type="submit" 
                                formaction="{{ route('admin.reports.builder.export') }}" 
                                name="format" 
                                value="excel" 
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition shadow-2xs"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Ekspor Excel (.xlsx)
                            </button>

                            <button 
                                type="submit" 
                                formaction="{{ route('admin.reports.builder.export') }}" 
                                name="format" 
                                value="csv" 
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition shadow-2xs"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Ekspor CSV
                            </button>

                            <button 
                                type="submit" 
                                formaction="{{ route('admin.reports.builder.export') }}" 
                                name="format" 
                                value="pdf" 
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl transition shadow-2xs"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Ekspor Dokumen PDF
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Preview Data Table Card with Pagination -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-2xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Pratinjau Hasil Laporan Kustom
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Menampilkan halaman {{ $reportData->currentPage() }} dari {{ $reportData->lastPage() ?: 1 }} (Total {{ $totalRecords }} data).
                        </p>
                    </div>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-lg border border-slate-200/60 dark:border-slate-600">
                            {{ count($selectedColumns) }} Kolom Aktif
                        </span>
                        <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold rounded-lg border border-blue-200/60 dark:border-blue-800">
                            {{ $totalRecords }} Total Data
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 dark:bg-slate-900/40 border-b border-slate-200/80 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-3xs font-bold uppercase tracking-wider">
                                @foreach($selectedColumns as $colKey => $colLabel)
                                    <th class="p-3.5 whitespace-nowrap">{{ $colLabel }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs">
                            @forelse($reportData as $row)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/30 transition">
                                    @foreach($selectedColumns as $colKey => $colLabel)
                                        <td class="p-3.5 whitespace-nowrap text-slate-800 dark:text-slate-200">
                                            @if(str_contains($colKey, 'status'))
                                                <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 text-3xs font-semibold rounded-md border border-blue-200/80 dark:border-blue-800">
                                                    {{ data_get($row, $colKey, '-') }}
                                                </span>
                                            @elseif(str_contains($colKey, 'match_score') || str_contains($colKey, 'progress_percentage'))
                                                <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 text-3xs font-bold rounded-md border border-emerald-200/80 dark:border-emerald-800">
                                                    {{ data_get($row, $colKey, '-') }}
                                                </span>
                                            @elseif(str_contains($colKey, 'score') && !str_contains($colKey, 'match'))
                                                <span class="inline-flex items-center px-2 py-0.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 text-3xs font-bold rounded-md border border-indigo-200/80 dark:border-indigo-800">
                                                    {{ data_get($row, $colKey, '-') }}
                                                </span>
                                            @else
                                                <span class="text-slate-900 dark:text-white font-normal">{{ data_get($row, $colKey, '-') }}</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($selectedColumns) ?: 1 }}" class="py-16 text-center text-slate-400 dark:text-slate-500">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <p class="font-semibold text-xs text-slate-700 dark:text-slate-300">Tidak ada data laporan yang sesuai</p>
                                        <p class="text-3xs text-slate-400 dark:text-slate-500 mt-0.5">Coba ubah rentang tanggal atau pilih domain laporan yang berbeda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                @if($reportData->hasPages())
                    <div class="p-4 bg-slate-50/70 dark:bg-slate-900/60 border-t border-slate-100 dark:border-slate-700/60 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                        <div class="text-slate-500 dark:text-slate-400 text-3xs font-medium">
                            Menampilkan data <span class="font-bold text-slate-800 dark:text-slate-200">{{ $reportData->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800 dark:text-slate-200">{{ $reportData->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-slate-800 dark:text-slate-200">{{ $totalRecords }}</span> data
                        </div>
                        <div>
                            {{ $reportData->links() }}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        function reportBuilder() {
            return {
                selectedCols: @json($selectedColumnKeys),
                allCols: @json(array_keys($availableColumns)),

                selectAll() {
                    this.selectedCols = [...this.allCols];
                },

                deselectAll() {
                    this.selectedCols = [];
                },

                setPreset(preset) {
                    const today = new Date();
                    const end = today.toISOString().split('T')[0];
                    let start = '';

                    if (preset === 30) {
                        const d = new Date();
                        d.setDate(d.getDate() - 30);
                        start = d.toISOString().split('T')[0];
                    } else if (preset === 'this_month') {
                        const d = new Date(today.getFullYear(), today.getMonth(), 1);
                        start = d.toISOString().split('T')[0];
                    } else if (preset === 'this_year') {
                        const d = new Date(today.getFullYear(), 0, 1);
                        start = d.toISOString().split('T')[0];
                    }

                    if (start) {
                        document.getElementById('start_date_input').value = start;
                        document.getElementById('end_date_input').value = end;
                    }
                }
            }
        }
    </script>
</x-app-layout>
