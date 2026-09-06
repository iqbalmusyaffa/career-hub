@extends('layouts.app')

@section('title', 'Skema & Arsitektur Sistem - TalentFlow')

@push('scripts')
<!-- CDN Mermaid.js for interactive ERD Diagram rendering -->
<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const isDark = document.documentElement.classList.contains('dark') || localStorage.getItem('theme') === 'dark';
        mermaid.initialize({
            startOnLoad: true,
            theme: isDark ? 'dark' : 'default',
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
}" class="p-4 sm:p-6 lg:p-8 space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500 mb-1">
                <span>Pengaturan Sistem</span>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-blue-600 dark:text-blue-400">Arsitektur & Skema Database</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                <i class="fa-solid fa-diagram-project text-blue-600 dark:text-blue-400"></i>
                Diagram Alur & Skema ERD
            </h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 max-w-3xl leading-relaxed">
                Pemetaan relasi model Eloquent database, pipeline alur rekrutmen kandidat, endpoint route aplikasi, dan rekaman audit aktivitas sistem.
            </p>
        </div>

        <!-- QUICK KPI STRIP -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 shrink-0">
            <div class="bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 shadow-xs min-w-[105px]">
                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Model</div>
                <div class="text-xl font-bold text-slate-900 dark:text-white mt-0.5">{{ $modelsData['total_models'] }}</div>
            </div>
            <div class="bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 shadow-xs min-w-[105px]">
                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Relasi</div>
                <div class="text-xl font-bold text-slate-900 dark:text-white mt-0.5">{{ $modelsData['total_relations'] }}</div>
            </div>
            <div class="bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 shadow-xs min-w-[105px]">
                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Workflow</div>
                <div class="text-xl font-bold text-slate-900 dark:text-white mt-0.5">{{ count($workflowSteps) }}</div>
            </div>
            <div class="bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 shadow-xs min-w-[105px]">
                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Route</div>
                <div class="text-xl font-bold text-slate-900 dark:text-white mt-0.5">{{ $routesSummary['total_routes'] }}</div>
            </div>
        </div>
    </div>

    <!-- TABS BAR & ACTIONS -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 border-b border-slate-200 dark:border-slate-800 pb-4">
        <!-- Navigation Tabs -->
        <div class="flex flex-wrap items-center gap-1.5 p-1 bg-slate-100 dark:bg-slate-900/80 rounded-xl border border-slate-200 dark:border-slate-800/80">
            <button @click="activeTab = 'erd'"
                    :class="activeTab === 'erd' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-xs font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                    class="px-3.5 py-2 rounded-lg text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-database text-blue-500"></i>
                <span>Relasi ERD</span>
                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300">{{ $modelsData['total_models'] }}</span>
            </button>

            <button @click="activeTab = 'flow'"
                    :class="activeTab === 'flow' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-xs font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                    class="px-3.5 py-2 rounded-lg text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-route text-amber-500"></i>
                <span>Alur Rekrutmen</span>
                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300">{{ count($workflowSteps) }} Tahap</span>
            </button>

            <button @click="activeTab = 'routes'"
                    :class="activeTab === 'routes' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-xs font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                    class="px-3.5 py-2 rounded-lg text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-network-wired text-purple-500"></i>
                <span>Peta Route</span>
            </button>

            <button @click="activeTab = 'logs'"
                    :class="activeTab === 'logs' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-xs font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                    class="px-3.5 py-2 rounded-lg text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-emerald-500"></i>
                <span>Audit Trail</span>
            </button>
        </div>

        <!-- Right Side ERD Controls -->
        <div class="flex items-center gap-2" x-show="activeTab === 'erd'">
            <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                <button @click="viewMode = 'cards'"
                        :class="viewMode === 'cards' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-2xs font-semibold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                        class="px-3 py-1.5 rounded-md text-xs transition flex items-center gap-1.5">
                    <i class="fa-solid fa-table-cells-large"></i>
                    <span>Tampilan Kartu</span>
                </button>
                <button @click="viewMode = 'mermaid'"
                        :class="viewMode === 'mermaid' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-2xs font-semibold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                        class="px-3 py-1.5 rounded-md text-xs transition flex items-center gap-1.5">
                    <i class="fa-solid fa-project-diagram"></i>
                    <span>Diagram Grafis</span>
                </button>
            </div>

            <button @click="copyMermaidSyntax()"
                    class="px-3 py-1.5 rounded-lg bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 transition flex items-center gap-1.5 shadow-2xs">
                <i class="fa-solid" :class="copiedMermaid ? 'fa-check text-emerald-500' : 'fa-copy'"></i>
                <span x-text="copiedMermaid ? 'Tersalin' : 'Salin Sintaks Mermaid'"></span>
            </button>
        </div>
    </div>

    <!-- ==================== TAB 1: DATABASE ERD VISUALIZER ==================== -->
    <div x-show="activeTab === 'erd'" x-transition class="space-y-4">

        <!-- FILTER & SEARCH TOOLBAR -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white dark:bg-slate-900/70 p-3.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
            <!-- Category Filter Pills -->
            <div class="flex flex-wrap items-center gap-1">
                <template x-for="cat in ['all', 'Pengguna & Profil', 'Lowongan & Ujian', 'Proses Rekrutmen', 'Onboarding & Magang', 'Sistem & Administrasi']" :key="cat">
                    <button @click="selectedCategory = cat"
                            :class="selectedCategory === cat ? 'bg-blue-600 text-white font-semibold shadow-xs' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white'"
                            class="px-3 py-1.5 rounded-lg text-xs transition"
                            x-text="cat === 'all' ? 'Semua Modul' : cat">
                    </button>
                </template>
            </div>

            <!-- Search Bar -->
            <div class="relative min-w-[240px]">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" x-model="searchQuery" placeholder="Cari model atau tabel..."
                       class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-lg pl-8 pr-3 py-1.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>
        </div>

        <!-- MERMAID DIAGRAM VIEW -->
        <div x-show="viewMode === 'mermaid'" class="bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-xs overflow-x-auto">
            <div class="flex items-center justify-between mb-4 border-b border-slate-200 dark:border-slate-800 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Visualisasi Skema Mermaid.js</span>
                </div>
                <div class="text-xs text-slate-500">Gunakan diagram ini untuk arsitektur skema entitas secara komprehensif</div>
            </div>

            <div class="mermaid justify-center flex py-4">
                {{ $mermaidErd }}
            </div>
        </div>

        <!-- VISUAL CARDS VIEW -->
        <div x-show="viewMode === 'cards'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($modelsData['models'] as $shortName => $model)
            <div x-show="(selectedCategory === 'all' || selectedCategory === '{{ $model['category'] }}') && ('{{ strtolower($shortName) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($model['table']) }}'.includes(searchQuery.toLowerCase()))"
                 class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-blue-500/50 dark:hover:border-blue-500/50 rounded-2xl p-5 shadow-xs transition duration-200 flex flex-col justify-between group">

                <div class="space-y-3.5">
                    <!-- Model Header -->
                    <div class="border-b border-slate-100 dark:border-slate-800 pb-3 space-y-1.5">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition break-all" title="{{ $shortName }}">
                                {{ $shortName }}
                            </h3>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60 shrink-0 text-right">
                                {{ $model['category'] }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap text-xs">
                            <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-slate-100 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 break-all inline-flex items-center">
                                <i class="fa-solid fa-table text-[10px] text-slate-400 mr-1.5"></i>{{ $model['table'] }}
                            </span>
                            <span class="text-[11px] text-slate-400 font-mono truncate">App\Models\{{ $shortName }}</span>
                        </div>
                    </div>

                    <!-- Column Summary -->
                    <div class="space-y-1.5">
                        <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 flex items-center justify-between">
                            <span>Kolom Database</span>
                            <span class="font-mono text-slate-400">{{ count($model['columns']) }} Kolom</span>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-950 rounded-xl p-2.5 border border-slate-200 dark:border-slate-800/80 space-y-1 max-h-44 overflow-y-auto font-mono text-xs">
                            @foreach($model['columns'] as $col)
                            <div class="flex items-center justify-between text-[11px] py-0.5 border-b border-slate-100 dark:border-slate-900 last:border-0">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    @if($col['is_pk'])
                                        <span class="px-1 py-0.2 rounded text-[9px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/60">PK</span>
                                    @elseif($col['is_fk'])
                                        <span class="px-1 py-0.2 rounded text-[9px] font-bold bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60">FK</span>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                    @endif
                                    <span class="truncate {{ $col['is_pk'] ? 'font-bold text-amber-700 dark:text-amber-300' : ($col['is_fk'] ? 'text-blue-600 dark:text-blue-300' : 'text-slate-700 dark:text-slate-300') }}">{{ $col['name'] }}</span>
                                </div>
                                <span class="text-slate-400 shrink-0 font-sans text-[10px]">{{ $col['type'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Eloquent Relations Summary -->
                    <div class="space-y-1.5">
                        <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Relasi Eloquent</div>
                        @if(count($model['relations']) > 0)
                        <div class="flex flex-wrap gap-1">
                            @foreach($model['relations'] as $rel)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                <span class="text-blue-600 dark:text-blue-400 font-semibold">{{ $rel['type'] }}</span>
                                <i class="fa-solid fa-arrow-right text-[8px] text-slate-400"></i>
                                <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $rel['related_model'] }}</span>
                            </span>
                            @endforeach
                        </div>
                        @else
                        <div class="text-[11px] text-slate-400 italic">Tidak ada relasi langsung</div>
                        @endif
                    </div>
                </div>

                <!-- Card Footer Button -->
                <div class="pt-3 mt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <span class="text-[10px] text-slate-400 font-mono">PK: {{ $model['primary_key'] }}</span>
                    <button @click="selectedModel = @js($model)"
                            class="px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 text-blue-600 dark:text-blue-300 text-xs font-semibold transition flex items-center gap-1.5">
                        <span>Detail Skema</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </button>
                </div>

            </div>
            @endforeach
        </div>
    </div>


    <!-- ==================== TAB 2: RECRUITMENT JOURNEY & WORKFLOW ==================== -->
    <div x-show="activeTab === 'flow'" x-transition class="space-y-6">
        
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
            <div class="max-w-3xl mb-6 space-y-1.5">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-route text-amber-500"></i>
                    Pipeline Alur Rekrutmen Kandidat (Candidate Journey)
                </h2>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    Tahapan operasional rekrutmen TalentFlow mulai dari publikasi lowongan kerja hingga onboarding magang beserta integrasi model dan event terkait.
                </p>
            </div>

            <!-- STEP BY STEP FLOWCHART -->
            <div class="relative space-y-4 before:absolute before:inset-0 before:left-7 sm:before:left-8 before:w-0.5 before:bg-slate-200 dark:before:bg-slate-800 before:-z-0">
                @foreach($workflowSteps as $step)
                <div class="relative z-10 flex items-start gap-4 group">
                    
                    <!-- STEP NUMBER BADGE -->
                    <div class="w-14 h-14 rounded-xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white font-bold flex flex-col items-center justify-center shadow-xs shrink-0 group-hover:border-blue-500 transition">
                        <i class="fa-solid {{ $step['icon'] }} text-blue-600 dark:text-blue-400 text-sm mb-0.5"></i>
                        <span class="text-[9px] text-slate-500 dark:text-slate-400">Step {{ $step['step'] }}</span>
                    </div>

                    <!-- STEP DETAILS CARD -->
                    <div class="flex-1 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800/80 rounded-xl p-4 sm:p-5 shadow-xs group-hover:border-blue-500/40 transition">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-2.5 mb-2.5">
                            <div>
                                <h3 class="font-bold text-sm text-slate-900 dark:text-white">
                                    {{ $step['title'] }}
                                </h3>
                                <div class="text-[11px] font-mono text-slate-500 dark:text-slate-400 mt-0.5">Status: <span class="text-amber-600 dark:text-amber-400 font-semibold">{{ $step['status'] }}</span></div>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 shrink-0">
                                Tahap {{ $step['step'] }} dari {{ count($workflowSteps) }}
                            </span>
                        </div>

                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-3">
                            {{ $step['description'] }}
                        </p>

                        <!-- METADATA TAGS -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2.5 font-mono text-xs">
                            <div class="bg-slate-50 dark:bg-slate-900 p-2.5 rounded-lg border border-slate-100 dark:border-slate-800 space-y-1">
                                <div class="text-[10px] font-semibold uppercase text-slate-500 dark:text-slate-400 font-sans">Model Terkait</div>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($step['models'] as $m)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">{{ $m }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-900 p-2.5 rounded-lg border border-slate-100 dark:border-slate-800 space-y-1">
                                <div class="text-[10px] font-semibold uppercase text-slate-500 dark:text-slate-400 font-sans">Event & Trigger</div>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($step['events'] as $evt)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60">{{ $evt }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-900 p-2.5 rounded-lg border border-slate-100 dark:border-slate-800 space-y-1">
                                <div class="text-[10px] font-semibold uppercase text-slate-500 dark:text-slate-400 font-sans">Route Handler</div>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($step['routes'] as $r)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">{{ $r }}</span>
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


    <!-- ==================== TAB 3: ROUTES & ARCHITECTURE ==================== -->
    <div x-show="activeTab === 'routes'" x-transition class="space-y-4">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3.5">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl space-y-1 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                    <span class="text-xs font-semibold">Total Route Sistem</span>
                    <i class="fa-solid fa-route text-blue-500"></i>
                </div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $routesSummary['total_routes'] }}</div>
                <div class="text-[11px] text-slate-400">Terdaftar di Laravel Engine</div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl space-y-1 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                    <span class="text-xs font-semibold">Panel Admin</span>
                    <i class="fa-solid fa-user-shield text-rose-500"></i>
                </div>
                <div class="text-2xl font-bold text-rose-600 dark:text-rose-400">{{ $routesSummary['admin_routes'] }}</div>
                <div class="text-[11px] text-slate-400">Auth HR / Admin Protected</div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl space-y-1 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                    <span class="text-xs font-semibold">Portal Pelamar</span>
                    <i class="fa-solid fa-users text-emerald-500"></i>
                </div>
                <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $routesSummary['candidate_routes'] }}</div>
                <div class="text-[11px] text-slate-400">Halaman Publik & Kandidat</div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl space-y-1 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                    <span class="text-xs font-semibold">REST API Endpoint</span>
                    <i class="fa-solid fa-code text-purple-500"></i>
                </div>
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $routesSummary['api_routes'] }}</div>
                <div class="text-[11px] text-slate-400">API Backend & Mobile Sync</div>
            </div>
        </div>

        <!-- ARCHITECTURE STACK -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-6 space-y-4 shadow-xs">
            <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-blue-600 dark:text-blue-400"></i>
                Pondasi Arsitektur Sistem
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800/80 p-4 rounded-xl space-y-1.5">
                    <div class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Autentikasi & RBAC</div>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Menggunakan Laravel Sanctum & Spatie Laravel-Permission (Role Candidate, HR, Company Owner, Super Admin) terlindungi oleh Middleware.
                    </p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800/80 p-4 rounded-xl space-y-1.5">
                    <div class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Dokumen & PDF Generator</div>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Engine DomPDF untuk ekspor ATS & CV Kreatif, Surat Penawaran Kerja (Offer Letter), Sertifikat Magang, & Transkrip Akademik.
                    </p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800/80 p-4 rounded-xl space-y-1.5">
                    <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Notifikasi & Media Engine</div>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Email Auto-Sender (SMTP Dynamic Engine), Real-time In-App Notifications, dan Spatie Media Library untuk berkas pelamar.
                    </p>
                </div>
            </div>
        </div>

    </div>


    <!-- ==================== TAB 4: AUDIT TRAIL LOGS ==================== -->
    <div x-show="activeTab === 'logs'" x-transition class="space-y-4">
        
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-6 space-y-4 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-emerald-500"></i>
                        Aktivitas Alur Sistem Terbaru
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Log peristiwa real-time pada alur aplikasi (Presensi, Penilaian, Pelamaran, Ujian, dsb.).
                    </p>
                </div>

                <a href="{{ route('admin.audit-logs.index') }}" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-semibold border border-slate-200 dark:border-slate-700 transition flex items-center gap-1.5 shrink-0">
                    <i class="fa-solid fa-list text-slate-400"></i> Buka Log Lengkap
                </a>
            </div>

            <!-- Activity List -->
            <div class="space-y-2">
                @forelse($recentFlowLogs as $log)
                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800/80 rounded-xl p-3.5 transition flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-xs font-bold shrink-0 text-blue-600 dark:text-blue-400 shadow-2xs">
                            @if(str_contains($log->action, 'PRESENSI'))
                                <i class="fa-solid fa-location-dot text-emerald-500"></i>
                            @elseif(str_contains($log->action, 'MENTOR'))
                                <i class="fa-solid fa-chalkboard-user text-amber-500"></i>
                            @elseif(str_contains($log->action, 'OFFER') || str_contains($log->action, 'AGREEMENT'))
                                <i class="fa-solid fa-file-signature text-blue-500"></i>
                            @elseif(str_contains($log->action, 'TEST') || str_contains($log->action, 'SCORE'))
                                <i class="fa-solid fa-pen-to-square text-purple-500"></i>
                            @else
                                <i class="fa-solid fa-bolt text-slate-400"></i>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60">
                                    {{ $log->action }}
                                </span>
                                <span class="text-xs font-semibold text-slate-900 dark:text-white">
                                    {{ $log->user ? $log->user->name : 'Sistem Otomatis' }}
                                </span>
                                @if($log->user && $log->user->roles->first())
                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-medium bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                        {{ $log->user->roles->first()->name }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                                {{ $log->description }}
                            </p>
                        </div>
                    </div>

                    <div class="text-right shrink-0 font-mono text-[11px] text-slate-400 flex md:flex-col items-center md:items-end justify-between md:justify-center border-t md:border-0 border-slate-200 dark:border-slate-800 pt-2 md:pt-0">
                        <div class="text-slate-600 dark:text-slate-400 font-medium flex items-center gap-1">
                            <i class="fa-regular fa-clock text-[10px] text-slate-400"></i>
                            {{ $log->created_at->diffForHumans() }}
                        </div>
                        <div class="text-[10px] text-slate-400 mt-0.5">
                            IP: {{ $log->ip_address ?? '127.0.0.1' }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-400 text-xs">
                    Belum ada rekaman alur aktivitas terbaru.
                </div>
                @endforelse
            </div>
        </div>

    </div>


    <!-- ==================== MODEL DETAIL INSPECTION MODAL ==================== -->
    <div x-show="selectedModel !== null"
         x-transition.opacity
         class="fixed inset-0 z-50 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        
        <div @click.away="selectedModel = null"
             class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-2xl w-full p-6 space-y-5 shadow-xl relative overflow-hidden">
            
            <div class="flex items-start justify-between border-b border-slate-100 dark:border-slate-800 pb-3.5">
                <div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60">
                        <span x-text="selectedModel?.category"></span>
                    </span>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mt-1" x-text="selectedModel?.short_name"></h2>
                    <p class="text-xs font-mono text-slate-500 dark:text-slate-400">Tabel: <span class="text-amber-600 dark:text-amber-400 font-semibold" x-text="selectedModel?.table"></span> | PK: <span class="text-emerald-600 dark:text-emerald-400 font-semibold" x-text="selectedModel?.primary_key"></span></p>
                </div>
                <button @click="selectedModel = null" class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            <!-- Columns Detail List -->
            <div class="space-y-1.5">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Daftar Kolom Database</div>
                <div class="bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 p-3 max-h-56 overflow-y-auto space-y-1.5 font-mono text-xs">
                    <template x-for="col in selectedModel?.columns || []" :key="col.name">
                        <div class="flex items-center justify-between py-1 border-b border-slate-100 dark:border-slate-900 last:border-0">
                            <div class="flex items-center gap-2">
                                <span x-show="col.is_pk" class="px-1 py-0.2 rounded text-[9px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/60">PK</span>
                                <span x-show="col.is_fk" class="px-1 py-0.2 rounded text-[9px] font-bold bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60">FK</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="col.name"></span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-slate-400 text-[10px]" x-text="col.nullable ? 'NULLABLE' : 'NOT NULL'"></span>
                                <span class="text-blue-600 dark:text-blue-400 font-semibold" x-text="col.type"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Fillable Attributes -->
            <div class="space-y-1.5">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Fillable Attributes ($fillable)</div>
                <div class="flex flex-wrap gap-1 bg-slate-50 dark:bg-slate-950 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800">
                    <template x-for="field in selectedModel?.fillable || []" :key="field">
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700" x-text="field"></span>
                    </template>
                </div>
            </div>

            <!-- Close Modal -->
            <div class="flex justify-end pt-2 border-t border-slate-100 dark:border-slate-800">
                <button @click="selectedModel = null" class="px-4 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold transition">
                    Tutup
                </button>
            </div>
        </div>

    </div>

</div>
@endsection
