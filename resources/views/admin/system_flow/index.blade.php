@extends('layouts.app')

@section('title', 'System Diagram & Auto-Generated ERD - TalentFlow')

@push('scripts')
<!-- CDN Mermaid.js for interactive ERD Diagram rendering -->
<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        mermaid.initialize({
            startOnLoad: true,
            theme: 'dark',
            securityLevel: 'loose',
            er: {
                useMaxWidth: true,
                layoutDirection: 'TB'
            }
        });
    });
</script>
@endpush

@section('content')
<div x-data="{
    activeTab: 'erd',
    searchQuery: '',
    selectedCategory: 'all',
    selectedModel: null,
    copiedMermaid: false,
    viewMode: 'cards',
    mermaidSyntax: @js($mermaidErd),

    copyMermaidSyntax() {
        navigator.clipboard.writeText(this.mermaidSyntax);
        this.copiedMermaid = true;
        setTimeout(() => this.copiedMermaid = false, 2500);
    }
}" class="p-4 sm:p-6 md:p-8 space-y-8 bg-slate-900 text-slate-100 min-h-screen">

    <!-- TOP BANNER & REAL-TIME STATS -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                    Auto-Generated Realtime Visualizer
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <i class="fa-solid fa-diagram-project text-blue-400"></i>
                    Diagram Flow & ERD Database
                </h1>
                <p class="text-sm text-slate-300 max-w-2xl leading-relaxed">
                    Visualisasi otomatis skema relasi database (ERD), alur kerja rekrutmen kandidat (*candidate journey*), serta arsitektur sistem TalentFlow yang disinkronkan secara langsung dari model Eloquent.
                </p>
            </div>

            <!-- STATS CARDS -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 shrink-0">
                <div class="bg-slate-800/80 backdrop-blur border border-slate-700/80 rounded-2xl p-4 text-center">
                    <div class="text-2xl font-black text-blue-400">{{ $modelsData['total_models'] }}</div>
                    <div class="text-xs font-medium text-slate-400">Model Eloquent</div>
                </div>
                <div class="bg-slate-800/80 backdrop-blur border border-slate-700/80 rounded-2xl p-4 text-center">
                    <div class="text-2xl font-black text-emerald-400">{{ $modelsData['total_relations'] }}</div>
                    <div class="text-xs font-medium text-slate-400">Relasi Entitas</div>
                </div>
                <div class="bg-slate-800/80 backdrop-blur border border-slate-700/80 rounded-2xl p-4 text-center">
                    <div class="text-2xl font-black text-amber-400">{{ count($workflowSteps) }}</div>
                    <div class="text-xs font-medium text-slate-400">Tahap Fitur</div>
                </div>
                <div class="bg-slate-800/80 backdrop-blur border border-slate-700/80 rounded-2xl p-4 text-center">
                    <div class="text-2xl font-black text-purple-400">{{ $routesSummary['total_routes'] }}</div>
                    <div class="text-xs font-medium text-slate-400">Route Sistem</div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABS NAVIGATION & CONTROL BAR -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-4">
        <div class="flex flex-wrap items-center gap-2">
            <button @click="activeTab = 'erd'"
                    :class="activeTab === 'erd' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold transition duration-200 flex items-center gap-2">
                <i class="fa-solid fa-database"></i>
                <span>1. Database ERD Visualizer</span>
                <span class="px-2 py-0.5 rounded-md text-3xs font-extrabold bg-slate-900/50 text-blue-200">{{ $modelsData['total_models'] }} Models</span>
            </button>

            <button @click="activeTab = 'flow'"
                    :class="activeTab === 'flow' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold transition duration-200 flex items-center gap-2">
                <i class="fa-solid fa-route"></i>
                <span>2. Alur Rekrutmen & Magang</span>
                <span class="px-2 py-0.5 rounded-md text-3xs font-extrabold bg-slate-900/50 text-amber-200">{{ count($workflowSteps) }} Steps</span>
            </button>

            <button @click="activeTab = 'routes'"
                    :class="activeTab === 'routes' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold transition duration-200 flex items-center gap-2">
                <i class="fa-solid fa-network-wired"></i>
                <span>3. Peta Route & Arsitektur</span>
            </button>

            <button @click="activeTab = 'logs'"
                    :class="activeTab === 'logs' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold transition duration-200 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>4. Live Flow & Audit Trail</span>
                <span class="px-2 py-0.5 rounded-md text-3xs font-extrabold bg-emerald-950 text-emerald-300 border border-emerald-800 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> Live Log
                </span>
            </button>
        </div>

        <!-- RIGHT ACTIONS (Export Mermaid, Switch Mode) -->
        <div class="flex items-center gap-2" x-show="activeTab === 'erd'">
            <div class="inline-flex p-1 bg-slate-800 border border-slate-700 rounded-xl">
                <button @click="viewMode = 'cards'"
                        :class="viewMode === 'cards' ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-border-all"></i>
                    <span>Visual Cards</span>
                </button>
                <button @click="viewMode = 'mermaid'"
                        :class="viewMode === 'mermaid' ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-project-diagram"></i>
                    <span>Mermaid Diagram</span>
                </button>
            </div>

            <button @click="copyMermaidSyntax()"
                    class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-bold text-slate-200 hover:text-white transition flex items-center gap-2">
                <i class="fa-solid" :class="copiedMermaid ? 'fa-check text-emerald-400' : 'fa-copy'"></i>
                <span x-text="copiedMermaid ? 'Copied Mermaid!' : 'Copy Mermaid Code'"></span>
            </button>
        </div>
    </div>

    <!-- ==================== TAB 1: AUTO-GENERATED DATABASE ERD ==================== -->
    <div x-show="activeTab === 'erd'" x-transition class="space-y-6">

        <!-- FILTER & SEARCH BAR -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 bg-slate-800/50 p-4 rounded-2xl border border-slate-800">
            <!-- Category Filter Pills -->
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-xs font-semibold text-slate-400 mr-2">Modul:</span>
                <template x-for="cat in ['all', 'Pengguna & Profil', 'Lowongan & Ujian', 'Proses Rekrutmen', 'Onboarding & Magang', 'Sistem & Administrasi']" :key="cat">
                    <button @click="selectedCategory = cat"
                            :class="selectedCategory === cat ? 'bg-blue-600 text-white font-bold' : 'bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white'"
                            class="px-3 py-1.5 rounded-lg text-xs transition capitalize"
                            x-text="cat === 'all' ? 'Semua Modul' : cat">
                    </button>
                </template>
            </div>

            <!-- Search Bar -->
            <div class="relative min-w-[240px]">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" x-model="searchQuery" placeholder="Cari nama model / tabel..."
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-9 pr-4 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500">
            </div>
        </div>

        <!-- MERMAID DIAGRAM VIEW MODE -->
        <div x-show="viewMode === 'mermaid'" class="bg-slate-950 border border-slate-800 rounded-3xl p-6 shadow-2xl overflow-x-auto">
            <div class="flex items-center justify-between mb-4 border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-300">Auto-Rendered Mermaid.js ERD Diagram</span>
                </div>
                <div class="text-3xs text-slate-400">Dua kali klik untuk memperbesar atau salin kode ke Draw.io / Mermaid Live</div>
            </div>

            <div class="mermaid justify-center flex py-4">
                {{ $mermaidErd }}
            </div>
        </div>

        <!-- VISUAL CARDS VIEW MODE -->
        <div x-show="viewMode === 'cards'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($modelsData['models'] as $shortName => $model)
            <div x-show="(selectedCategory === 'all' || selectedCategory === '{{ $model['category'] }}') && ('{{ strtolower($shortName) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($model['table']) }}'.includes(searchQuery.toLowerCase()))"
                 class="bg-slate-800/90 border border-slate-700/80 hover:border-blue-500/50 rounded-2xl p-5 shadow-xl transition-all duration-300 flex flex-col justify-between group">

                <div class="space-y-4">
                    <!-- Model Header -->
                    <div class="flex items-start justify-between gap-3 border-b border-slate-700/60 pb-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-extrabold text-base text-white group-hover:text-blue-400 transition">{{ $shortName }}</h3>
                                <span class="px-2 py-0.5 rounded text-3xs font-bold bg-slate-900 text-slate-400 border border-slate-700">
                                    {{ $model['table'] }}
                                </span>
                            </div>
                            <p class="text-3xs text-slate-400 mt-1 font-mono">App\Models\{{ $shortName }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-3xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/30 shrink-0">
                            {{ $model['category'] }}
                        </span>
                    </div>

                    <!-- Column Summary -->
                    <div class="space-y-1.5">
                        <div class="text-3xs font-bold uppercase tracking-wider text-slate-400 flex items-center justify-between">
                            <span>Struktur Kolom Database</span>
                            <span class="text-slate-400 font-mono">{{ count($model['columns']) }} Kolom</span>
                        </div>
                        <div class="bg-slate-900/80 rounded-xl p-3 border border-slate-700/50 space-y-1.5 max-h-48 overflow-y-auto font-mono text-xs">
                            @foreach($model['columns'] as $col)
                            <div class="flex items-center justify-between text-3xs py-0.5 border-b border-slate-800/50 last:border-0">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    @if($col['is_pk'])
                                        <span class="px-1 py-0.2 rounded text-[9px] font-black bg-amber-500/20 text-amber-400 border border-amber-500/40">PK</span>
                                    @elseif($col['is_fk'])
                                        <span class="px-1 py-0.2 rounded text-[9px] font-black bg-blue-500/20 text-blue-400 border border-blue-500/40">FK</span>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                                    @endif
                                    <span class="truncate {{ $col['is_pk'] ? 'font-bold text-amber-300' : ($col['is_fk'] ? 'text-blue-300' : 'text-slate-300') }}">{{ $col['name'] }}</span>
                                </div>
                                <span class="text-slate-400 shrink-0 font-sans text-[10px]">{{ $col['type'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Eloquent Relations Summary -->
                    <div class="space-y-1.5">
                        <div class="text-3xs font-bold uppercase tracking-wider text-slate-400">Relasi Eloquent</div>
                        @if(count($model['relations']) > 0)
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($model['relations'] as $rel)
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-3xs font-medium bg-slate-700/60 text-slate-200 border border-slate-600/60">
                                <span class="text-blue-400 font-bold">{{ $rel['type'] }}</span>
                                <i class="fa-solid fa-arrow-right text-[9px] text-slate-400"></i>
                                <span class="font-bold text-emerald-300">{{ $rel['related_model'] }}</span>
                            </span>
                            @endforeach
                        </div>
                        @else
                        <div class="text-3xs text-slate-400 italic">Tidak ada relasi langsung</div>
                        @endif
                    </div>
                </div>

                <!-- Card Footer Button -->
                <div class="pt-4 mt-4 border-t border-slate-700/60 flex items-center justify-between">
                    <span class="text-3xs text-slate-400 font-mono">PK: {{ $model['primary_key'] }}</span>
                    <button @click="selectedModel = @js($model)"
                            class="px-3 py-1.5 rounded-lg bg-blue-600/20 hover:bg-blue-600 text-blue-300 hover:text-white text-xs font-bold transition flex items-center gap-1.5">
                        <span>Inspeksi Detail</span>
                        <i class="fa-solid fa-circle-info"></i>
                    </button>
                </div>

            </div>
            @endforeach
        </div>
    </div>


    <!-- ==================== TAB 2: AUTO-GENERATED FEATURE & RECRUITMENT FLOW ==================== -->
    <div x-show="activeTab === 'flow'" x-transition class="space-y-8">
        
        <div class="bg-slate-800/40 border border-slate-800 rounded-3xl p-6">
            <div class="max-w-3xl mb-6 space-y-2">
                <h2 class="text-xl font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-network-wired text-amber-400"></i>
                    Alur Perjalanan Kandidat & Operasional HR (Candidate Hiring Journey)
                </h2>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Flowchart visual dari tahapan rekrutmen TalentFlow mulai dari publikasi posisi kerja hingga onboarding & magang. Setiap langkah terhubung otomatis dengan model database dan event controller terkait.
                </p>
            </div>

            <!-- STEP BY STEP FLOWCHART -->
            <div class="relative space-y-6 before:absolute before:inset-0 before:left-8 sm:before:left-10 before:w-1 before:bg-gradient-to-b before:from-blue-600 before:via-purple-600 before:to-emerald-600 before:-z-0">
                @foreach($workflowSteps as $step)
                <div class="relative z-10 flex items-start gap-4 sm:gap-6 group">
                    
                    <!-- STEP NUMBER CIRCLE -->
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-slate-900 border-2 border-blue-500 text-white font-black text-lg sm:text-xl flex flex-col items-center justify-center shadow-2xl shrink-0 group-hover:scale-105 transition">
                        <i class="fa-solid {{ $step['icon'] }} text-blue-400 text-base mb-0.5"></i>
                        <span class="text-3xs text-slate-400 tracking-wider">STEP {{ $step['step'] }}</span>
                    </div>

                    <!-- STEP DETAILS CARD -->
                    <div class="flex-1 bg-slate-800/90 border border-slate-700/80 rounded-2xl p-5 shadow-xl group-hover:border-blue-500/40 transition">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-700/60 pb-3 mb-3">
                            <div>
                                <h3 class="font-extrabold text-base text-white tracking-tight flex items-center gap-2">
                                    <span>{{ $step['title'] }}</span>
                                </h3>
                                <div class="text-3xs font-mono text-slate-400 mt-0.5">Status Filter: <span class="text-amber-400 font-bold">{{ $step['status'] }}</span></div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-3xs font-bold bg-slate-900 text-slate-300 border border-slate-700 shrink-0">
                                Langkah {{ $step['step'] }} dari {{ count($workflowSteps) }}
                            </span>
                        </div>

                        <p class="text-xs text-slate-300 leading-relaxed mb-4">
                            {{ $step['description'] }}
                        </p>

                        <!-- STEP METADATA GRID -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2 font-mono text-xs">
                            
                            <!-- Terkait Models -->
                            <div class="bg-slate-900/80 p-3 rounded-xl border border-slate-700/50 space-y-1">
                                <div class="text-3xs font-bold uppercase text-slate-400 font-sans">Model Database Terlibat</div>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($step['models'] as $m)
                                    <span class="px-2 py-0.5 rounded text-3xs font-bold bg-blue-500/10 text-blue-300 border border-blue-500/30">{{ $m }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Events & Triggers -->
                            <div class="bg-slate-900/80 p-3 rounded-xl border border-slate-700/50 space-y-1">
                                <div class="text-3xs font-bold uppercase text-slate-400 font-sans">Sistem Events / Triggers</div>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($step['events'] as $evt)
                                    <span class="px-2 py-0.5 rounded text-3xs font-bold bg-purple-500/10 text-purple-300 border border-purple-500/30">{{ $evt }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Associated Routes -->
                            <div class="bg-slate-900/80 p-3 rounded-xl border border-slate-700/50 space-y-1">
                                <div class="text-3xs font-bold uppercase text-slate-400 font-sans">Routes URL Handlers</div>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($step['routes'] as $r)
                                    <span class="px-2 py-0.5 rounded text-3xs font-bold bg-emerald-500/10 text-emerald-300 border border-emerald-500/30">{{ $r }}</span>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
                @endforeach
            </div>

        </div>

    </div>


    <!-- ==================== TAB 3: ROUTES & SYSTEM ARCHITECTURE ==================== -->
    <div x-show="activeTab === 'routes'" x-transition class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-slate-800/80 border border-slate-700 p-5 rounded-2xl space-y-2">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-xs font-semibold">Total Handled Routes</span>
                    <i class="fa-solid fa-route text-blue-400"></i>
                </div>
                <div class="text-2xl font-black text-white">{{ $routesSummary['total_routes'] }}</div>
                <div class="text-3xs text-slate-400">Terdaftar di Laravel Web & API Engine</div>
            </div>

            <div class="bg-slate-800/80 border border-slate-700 p-5 rounded-2xl space-y-2">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-xs font-semibold">Panel Admin Routes</span>
                    <i class="fa-solid fa-user-shield text-rose-400"></i>
                </div>
                <div class="text-2xl font-black text-rose-400">{{ $routesSummary['admin_routes'] }}</div>
                <div class="text-3xs text-slate-400">Memerlukan Auth & Role HR/Admin</div>
            </div>

            <div class="bg-slate-800/80 border border-slate-700 p-5 rounded-2xl space-y-2">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-xs font-semibold">Portal Pelamar / Candidate</span>
                    <i class="fa-solid fa-users text-emerald-400"></i>
                </div>
                <div class="text-2xl font-black text-emerald-400">{{ $routesSummary['candidate_routes'] }}</div>
                <div class="text-3xs text-slate-400">Halaman Publik & Portal Pelamar</div>
            </div>

            <div class="bg-slate-800/80 border border-slate-700 p-5 rounded-2xl space-y-2">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-xs font-semibold">API REST Endpoints</span>
                    <i class="fa-solid fa-code text-purple-400"></i>
                </div>
                <div class="text-2xl font-black text-purple-400">{{ $routesSummary['api_routes'] }}</div>
                <div class="text-3xs text-slate-400">Dokumentasi Swagger / Mobile API</div>
            </div>
        </div>

        <!-- ARCHITECTURE STACK SUMMARY -->
        <div class="bg-slate-800/60 border border-slate-700 rounded-3xl p-6 space-y-4">
            <h3 class="font-extrabold text-lg text-white flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-blue-400"></i>
                Komponen Arsitektur Sistem TalentFlow
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-slate-900/80 border border-slate-800 p-4 rounded-2xl space-y-2">
                    <div class="text-xs font-extrabold text-blue-400 uppercase tracking-wider">Otensifikasi & Otorisasi</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Menggunakan Laravel Sanctum & Spatie Laravel-Permission (Role Candidate, HR, Company Owner, Super Admin) dengan perlindungan Middleware.
                    </p>
                </div>
                <div class="bg-slate-900/80 border border-slate-800 p-4 rounded-2xl space-y-2">
                    <div class="text-xs font-extrabold text-amber-400 uppercase tracking-wider">Dokumen & PDF Generator</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Menggunakan DomPDF (ATS & Creative CV Builder, Offer Letter Generator, Sertifikat Magang, & Transkrip Akademik).
                    </p>
                </div>
                <div class="bg-slate-900/80 border border-slate-800 p-4 rounded-2xl space-y-2">
                    <div class="text-xs font-extrabold text-emerald-400 uppercase tracking-wider">Notifikasi & Media Storage</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Email Auto-Sender (SMTP Dynamic Engine), Real-time Bell Notifications, dan Spatie Media Library untuk berkas pelamar.
                    </p>
                </div>
            </div>
        </div>

    </div>


    <!-- ==================== TAB 4: LIVE SYSTEM FLOW & AUDIT TRAIL ==================== -->
    <div x-show="activeTab === 'logs'" x-transition class="space-y-6">
        
        <div class="bg-slate-800/60 border border-slate-700 rounded-3xl p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-700/80 pb-4">
                <div>
                    <span class="text-3xs font-black text-emerald-400 uppercase tracking-widest block">SYSTEM AUDIT TRAIL</span>
                    <h3 class="text-lg font-black text-white mt-0.5 flex items-center gap-2">
                        <i class="fa-solid fa-timeline text-emerald-400"></i>
                        Rekaman Jejak Eksekusi Alur Sistem (Flow Activity Log)
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">
                        Semua transaksi dan alur fitur (Presensi Magang, Keputusan Mentor, Pelamaran, Ujian, Offer Letter, dsb.) tercatat secara otomatis dan real-time oleh engine audit sistem.
                    </p>
                </div>

                <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold border border-slate-700 transition flex items-center gap-2 shrink-0">
                    <i class="fa-solid fa-list text-slate-400"></i> Buka Log Lengkap (Semua Halaman)
                </a>
            </div>

            <!-- Live Flow Timeline Cards -->
            <div class="space-y-3">
                @forelse($recentFlowLogs as $log)
                <div class="bg-slate-900/90 border border-slate-800 hover:border-blue-500/40 rounded-2xl p-4 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-sm font-black shrink-0 text-blue-400">
                            @if(str_contains($log->action, 'PRESENSI'))
                                📍
                            @elseif(str_contains($log->action, 'MENTOR'))
                                👨‍🏫
                            @elseif(str_contains($log->action, 'OFFER') || str_contains($log->action, 'AGREEMENT'))
                                📜
                            @elseif(str_contains($log->action, 'TEST') || str_contains($log->action, 'SCORE'))
                                📝
                            @else
                                ⚡
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2 py-0.5 rounded text-3xs font-mono font-black bg-blue-500/10 text-blue-400 border border-blue-500/30">
                                    {{ $log->action }}
                                </span>
                                <span class="text-3xs text-slate-400 font-medium">oleh</span>
                                <span class="text-xs font-bold text-white">
                                    {{ $log->user ? $log->user->name : 'Sistem Otomatis / Tamu' }}
                                </span>
                                @if($log->user && $log->user->roles->first())
                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700">
                                        {{ $log->user->roles->first()->name }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                                {{ $log->description }}
                            </p>
                        </div>
                    </div>

                    <div class="text-right shrink-0 font-mono text-3xs text-slate-400 flex md:flex-col items-center md:items-end justify-between md:justify-center border-t md:border-0 border-slate-800 pt-2 md:pt-0">
                        <div class="text-slate-300 font-bold flex items-center gap-1">
                            <i class="fa-regular fa-clock text-slate-500"></i>
                            {{ $log->created_at->diffForHumans() }}
                        </div>
                        <div class="text-slate-500 mt-0.5">
                            IP: {{ $log->ip_address ?? '127.0.0.1' }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center bg-slate-900/50 rounded-2xl border border-slate-800 text-slate-500 text-xs font-medium">
                    Belum ada rekaman alur sistem terbaru.
                </div>
                @endforelse
            </div>
        </div>

    </div>


    <!-- ==================== MODEL DETAIL INSPECTION MODAL ==================== -->
    <div x-show="selectedModel !== null"
         x-transition.opacity
         class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4"
         style="display: none;">
        
        <div @click.away="selectedModel = null"
             class="bg-slate-900 border border-slate-800 rounded-3xl max-w-2xl w-full p-6 space-y-6 shadow-2xl relative overflow-hidden">
            
            <div class="flex items-start justify-between border-b border-slate-800 pb-4">
                <div>
                    <span class="px-2.5 py-0.5 rounded text-3xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/30">
                        <span x-text="selectedModel?.category"></span>
                    </span>
                    <h2 class="text-2xl font-black text-white mt-1" x-text="selectedModel?.short_name"></h2>
                    <p class="text-xs font-mono text-slate-400">Tabel: <span class="text-amber-400 font-bold" x-text="selectedModel?.table"></span> | PK: <span class="text-emerald-400 font-bold" x-text="selectedModel?.primary_key"></span></p>
                </div>
                <button @click="selectedModel = null" class="w-8 h-8 rounded-full bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Columns Detail List -->
            <div class="space-y-2">
                <div class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Daftar Lengkap Kolom Database</div>
                <div class="bg-slate-950 rounded-2xl border border-slate-800 p-4 max-h-60 overflow-y-auto space-y-2 font-mono text-xs">
                    <template x-for="col in selectedModel?.columns || []" :key="col.name">
                        <div class="flex items-center justify-between py-1 border-b border-slate-900 last:border-0">
                            <div class="flex items-center gap-2">
                                <span x-show="col.is_pk" class="px-1.5 py-0.5 rounded text-[9px] font-black bg-amber-500/20 text-amber-400 border border-amber-500/40">PK</span>
                                <span x-show="col.is_fk" class="px-1.5 py-0.5 rounded text-[9px] font-black bg-blue-500/20 text-blue-400 border border-blue-500/40">FK</span>
                                <span class="font-bold text-slate-200" x-text="col.name"></span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-slate-500 text-3xs" x-text="col.nullable ? 'NULLABLE' : 'NOT NULL'"></span>
                                <span class="text-blue-400 font-bold" x-text="col.type"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Fillable Attributes -->
            <div class="space-y-1">
                <div class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Fillable Attributes ($fillable)</div>
                <div class="flex flex-wrap gap-1 bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <template x-for="field in selectedModel?.fillable || []" :key="field">
                        <span class="px-2 py-0.5 rounded text-3xs font-mono font-bold bg-slate-800 text-slate-300 border border-slate-700" x-text="field"></span>
                    </template>
                </div>
            </div>

            <!-- Close Modal -->
            <div class="flex justify-end pt-2 border-t border-slate-800">
                <button @click="selectedModel = null" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition">
                    Tutup Modal
                </button>
            </div>
        </div>

    </div>

</div>
@endsection
