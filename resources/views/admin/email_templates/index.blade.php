<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-700 font-semibold">Komunikasi HR</span>
                    <span>/</span>
                    <span class="text-slate-900 font-bold">Template Email</span>
                </nav>
                <h2 class="text-xl font-bold tracking-tight text-slate-900">
                    Template Email & Komunikasi
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Kelola draf pesan standar rekrutmen, undangan wawancara, asesmen teknis, offering letter, dan korespondensi kandidat.
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                <button type="button" @click="$dispatch('open-broadcast-modal')" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:text-slate-900 shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Kirim Email Cepat
                </button>
                <button type="button" @click="$dispatch('open-create-modal')" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 shadow-2xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Template
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50/60 min-h-screen" x-data="emailTemplateManager()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-xs font-semibold shadow-2xs">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between text-xs font-semibold shadow-2xs">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- KPI Metric Summary -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-2xs flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500">Total Draf Template</div>
                        <div class="text-xl font-bold text-slate-900 mt-0.5">{{ $totalTemplates }} <span class="text-xs font-normal text-slate-400">template</span></div>
                    </div>
                </div>

                <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-2xs flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500">Tahap Wawancara & Tes</div>
                        <div class="text-xl font-bold text-slate-900 mt-0.5">{{ ($countsByType['interview'] ?? 0) + ($countsByType['test_invitation'] ?? 0) }} <span class="text-xs font-normal text-slate-400">draf aktif</span></div>
                    </div>
                </div>

                <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-2xs flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500">Offering & Onboarding</div>
                        <div class="text-xl font-bold text-slate-900 mt-0.5">{{ ($countsByType['offering'] ?? 0) + ($countsByType['background_check'] ?? 0) }} <span class="text-xs font-normal text-slate-400">template</span></div>
                    </div>
                </div>

                <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-2xs flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500">Target Pelamar Aktif</div>
                        <div class="text-xl font-bold text-slate-900 mt-0.5">{{ count($applications) }} <span class="text-xs font-normal text-slate-400">kandidat</span></div>
                    </div>
                </div>
            </div>

            <!-- Filters & Category Navigation -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 space-y-3">
                <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                    <!-- Search Input Form -->
                    <form method="GET" action="{{ route('admin.email-templates.index') }}" class="flex-1 flex items-center gap-2">
                        <input type="hidden" name="type" value="{{ request('type', 'all') }}">
                        <div class="relative flex-1">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Cari berdasarkan judul template, subjek, atau kata kunci..." 
                                style="color: #0f172a !important; background-color: #f8fafc !important;"
                                class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>
                        <button type="submit" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-medium transition">
                            Cari
                        </button>
                        @if(request('search') || (request('type') && request('type') !== 'all'))
                            <a href="{{ route('admin.email-templates.index') }}" class="px-3 py-2 text-slate-500 hover:text-slate-800 text-xs font-medium transition">
                                Reset
                            </a>
                        @endif
                    </form>

                    <!-- Page Size Selector -->
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <span>Tampilkan:</span>
                        <select onchange="window.location.href=this.value" style="color: #0f172a !important; background-color: #f8fafc !important;" class="py-1.5 pl-2.5 pr-8 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 12]) }}" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12 per halaman</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 24]) }}" {{ request('per_page', 24) == 24 ? 'selected' : '' }}>24 per halaman</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 48]) }}" {{ request('per_page', 48) == 48 ? 'selected' : '' }}>48 per halaman</option>
                        </select>
                    </div>
                </div>

                <!-- Stage Filter Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 pt-1 scrollbar-none border-t border-slate-100">
                    @php
                        $currentType = request('type', 'all');
                        $stageFilters = [
                            'all' => ['label' => 'Semua', 'count' => $countsByType['all'] ?? 0],
                            'screening' => ['label' => 'HR Screening', 'count' => $countsByType['screening'] ?? 0],
                            'test_invitation' => ['label' => 'Tes & Asesmen', 'count' => $countsByType['test_invitation'] ?? 0],
                            'interview' => ['label' => 'Wawancara', 'count' => $countsByType['interview'] ?? 0],
                            'offering' => ['label' => 'Offering Letter', 'count' => $countsByType['offering'] ?? 0],
                            'background_check' => ['label' => 'Background Check', 'count' => $countsByType['background_check'] ?? 0],
                            'reminder' => ['label' => 'Pengingat Sesi', 'count' => $countsByType['reminder'] ?? 0],
                            'rejection' => ['label' => 'Penolakan', 'count' => $countsByType['rejection'] ?? 0],
                        ];
                    @endphp

                    @foreach($stageFilters as $key => $filter)
                        <a 
                            href="{{ request()->fullUrlWithQuery(['type' => $key, 'page' => 1]) }}" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ $currentType === $key ? 'bg-slate-900 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900' }}"
                        >
                            <span>{{ $filter['label'] }}</span>
                            <span class="text-3xs px-1.5 py-0.2 rounded-full font-bold {{ $currentType === $key ? 'bg-slate-700 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">
                                {{ $filter['count'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Templates Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($templates as $tpl)
                    @php
                        $badgeStyles = [
                            'screening' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'HR Screening'],
                            'test_invitation' => ['bg' => 'bg-indigo-50 text-indigo-700 border-indigo-200', 'label' => 'Tes & Asesmen'],
                            'interview_hr' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200', 'label' => 'Wawancara HR'],
                            'interview_user' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200', 'label' => 'Wawancara User'],
                            'interview' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200', 'label' => 'Wawancara'],
                            'offering' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Offering Letter'],
                            'background_check' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Background Check'],
                            'reminder' => ['bg' => 'bg-sky-50 text-sky-700 border-sky-200', 'label' => 'Pengingat Sesi'],
                            'rejection' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'label' => 'Penolakan Lamaran'],
                        ];
                        $badge = $badgeStyles[$tpl->type] ?? ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'label' => ucfirst($tpl->type)];
                    @endphp

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs hover:shadow-xs transition flex flex-col justify-between overflow-hidden group">
                        <!-- Card Header -->
                        <div class="p-5 border-b border-slate-100 space-y-2.5">
                            <div class="flex items-center justify-between gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-3xs font-bold border {{ $badge['bg'] }}">
                                    {{ $badge['label'] }}
                                </span>
                                <div class="flex items-center gap-1 opacity-80 group-hover:opacity-100 transition">
                                    <button 
                                        type="button" 
                                        @click="previewTemplate({{ json_encode($tpl) }})"
                                        class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition" 
                                        title="Lihat Pratinjau Email"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button 
                                        type="button" 
                                        @click="editTemplate({{ json_encode($tpl) }})"
                                        class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                                        title="Edit Template"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.email-templates.destroy', $tpl->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus template ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Template">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <h3 class="font-bold text-sm text-slate-900 line-clamp-1 group-hover:text-blue-600 transition">
                                {{ $tpl->name }}
                            </h3>

                            <div class="text-xs text-slate-600 font-medium line-clamp-1">
                                <span class="text-slate-400 font-normal">Subjek:</span> {{ $tpl->subject }}
                            </div>
                        </div>

                        <!-- Card Body Excerpt -->
                        <div class="p-5 flex-1 space-y-3">
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                {{ strip_tags($tpl->body_content) }}
                            </p>

                            <!-- Detected Dynamic Placeholders -->
                            <div class="flex flex-wrap gap-1 pt-1">
                                @if(str_contains($tpl->body_content, '{candidate_name}'))
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 font-mono text-3xs font-semibold rounded">candidate_name</span>
                                @endif
                                @if(str_contains($tpl->body_content, '{job_title}'))
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 font-mono text-3xs font-semibold rounded">job_title</span>
                                @endif
                                @if(str_contains($tpl->body_content, '{company_name}'))
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 font-mono text-3xs font-semibold rounded">company_name</span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-2">
                            <button 
                                type="button" 
                                @click="previewTemplate({{ json_encode($tpl) }})"
                                class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition flex items-center gap-1.5"
                            >
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Pratinjau
                            </button>

                            <button 
                                type="button" 
                                @click="useTemplate({{ json_encode($tpl) }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white shadow-2xs transition"
                            >
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Gunakan Draf
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200 shadow-2xs">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-sm text-slate-800">Tidak ada template yang ditemukan</h3>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Coba gunakan kata kunci pencarian lain atau buat template baru untuk tahapan ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Links -->
            @if(method_exists($templates, 'hasPages') && $templates->hasPages())
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs flex justify-center">
                    {{ $templates->links() }}
                </div>
            @endif

        </div>

        <!-- ======================================================== -->
        <!-- MODAL 1: PREVIEW TEMPLATE                                -->
        <!-- ======================================================== -->
        <div 
            x-show="showPreviewModal" 
            x-transition.opacity 
            class="fixed inset-0 bg-slate-950/75 backdrop-blur-xs z-50 flex items-center justify-center p-4 sm:p-6" 
            style="display: none;"
        >
            <div @click.away="showPreviewModal = false" class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[90vh]">
                <!-- Mock Email Header Bar -->
                <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-2">
                        <div class="flex gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                        </div>
                        <span class="text-xs font-semibold text-slate-300 ml-2">Pratinjau Email Resmi</span>
                    </div>
                    <button type="button" @click="showPreviewModal = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Email Envelope Meta -->
                <div class="p-6 border-b border-slate-100 bg-slate-50 space-y-2 text-xs shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-500 w-16">Pengirim:</span>
                        <span class="font-semibold text-slate-900">Tim HR & Recruitment &lt;no-reply@talentflow.id&gt;</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-500 w-16">Penerima:</span>
                        <span class="font-semibold text-slate-800 bg-slate-200 px-2 py-0.5 rounded font-mono text-3xs">{candidate_name} &lt;kandidat@email.com&gt;</span>
                    </div>
                    <div class="flex items-start gap-2 pt-1 border-t border-slate-200">
                        <span class="font-bold text-slate-500 w-16 pt-0.5">Subjek:</span>
                        <span class="font-bold text-slate-900 text-sm" x-text="previewData.subject"></span>
                    </div>
                </div>

                <!-- Email Content -->
                <div class="p-6 overflow-y-auto flex-1 bg-white">
                    <div class="text-xs text-slate-900 leading-relaxed whitespace-pre-line font-medium" x-text="previewData.body_content"></div>
                </div>

                <!-- Modal Actions -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
                    <span class="text-3xs text-slate-500">Variabel `{candidate_name}`, `{job_title}`, dll otomatis digantikan saat pengiriman.</span>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="showPreviewModal = false" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl text-xs hover:bg-slate-100">
                            Tutup
                        </button>
                        <button type="button" @click="useTemplate(previewData); showPreviewModal = false" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs shadow-2xs">
                            Gunakan Draf Ini
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 2: SEND INSTANT EMAIL BROADCAST (REFINED)          -->
        <!-- ======================================================== -->
        <div 
            x-show="showBroadcastModal" 
            x-transition.opacity 
            class="fixed inset-0 bg-slate-950/75 backdrop-blur-xs z-50 flex items-center justify-center p-4 sm:p-6" 
            style="display: none;"
        >
            <div @click.away="showBroadcastModal = false" class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[92vh]">
                <!-- Modal Header -->
                <div class="px-6 py-4.5 border-b border-slate-100 bg-white flex justify-between items-center shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-base text-slate-900">Kirim Email Korespondensi</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Pilih kandidat dan sesuaikan isi pesan sebelum dikirim.</p>
                        </div>
                    </div>
                    <button type="button" @click="showBroadcastModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body Form -->
                <form action="{{ route('admin.email-templates.broadcast') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    <div class="p-6 space-y-4 overflow-y-auto flex-1 bg-white text-xs">
                        
                        <!-- Candidate Picker -->
                        <div>
                            <label class="block font-bold text-slate-800 mb-1.5 flex items-center justify-between">
                                <span>Pilih Kandidat Pelamar <span class="text-rose-500">*</span></span>
                                <span class="text-3xs text-slate-400 font-normal">Data variabel otomatis digantikan</span>
                            </label>
                            <div class="relative">
                                <select 
                                    name="application_id" 
                                    @change="onCandidateSelect($event)" 
                                    required 
                                    style="color: #0f172a !important; background-color: #ffffff !important;"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-2xs"
                                >
                                    <option value="" style="color: #64748b !important;">-- Pilih dari daftar pelamar aktif --</option>
                                    @foreach($applications as $app)
                                        <option 
                                            value="{{ $app->id }}" 
                                            data-name="{{ $app->user->name ?? 'Pelamar' }}" 
                                            data-job="{{ $app->job->title ?? 'Posisi' }}" 
                                            data-company="{{ $app->job->company_name ?? 'Perusahaan' }}"
                                            style="color: #0f172a !important; background-color: #ffffff !important;"
                                        >
                                            {{ $app->user->name ?? 'Pelamar' }} — {{ $app->job->title ?? '-' }} ({{ $app->user->email ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Subject Line -->
                        <div>
                            <label class="block font-bold text-slate-800 mb-1.5">Subjek Email <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="subject" 
                                x-model="broadcastSubject" 
                                required 
                                placeholder="Subjek email..." 
                                style="color: #0f172a !important; background-color: #ffffff !important;"
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-2xs"
                            >
                        </div>

                        <!-- Content Body -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block font-bold text-slate-800">Isi Pesan Email <span class="text-rose-500">*</span></label>
                                <span class="text-3xs text-slate-400">Gunakan tombol token untuk menyisipkan variabel</span>
                            </div>
                            <textarea 
                                id="broadcast_body_content"
                                name="body_content" 
                                rows="8" 
                                x-model="broadcastBody" 
                                required 
                                style="color: #0f172a !important; background-color: #ffffff !important;"
                                class="w-full bg-white border border-slate-300 rounded-xl p-3.5 text-xs leading-relaxed text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-2xs"
                            ></textarea>

                            <!-- Variable Chips -->
                            <div class="flex items-center gap-1.5 flex-wrap mt-2">
                                <span class="text-3xs text-slate-500 font-medium mr-1">Sisipkan Tag:</span>
                                <button type="button" @click="insertBroadcastTag('{candidate_name}')" class="px-2 py-0.5 bg-slate-100 hover:bg-blue-50 hover:text-blue-700 border border-slate-200 rounded text-3xs font-mono font-semibold text-slate-700 transition">
                                    + {candidate_name}
                                </button>
                                <button type="button" @click="insertBroadcastTag('{job_title}')" class="px-2 py-0.5 bg-slate-100 hover:bg-blue-50 hover:text-blue-700 border border-slate-200 rounded text-3xs font-mono font-semibold text-slate-700 transition">
                                    + {job_title}
                                </button>
                                <button type="button" @click="insertBroadcastTag('{company_name}')" class="px-2 py-0.5 bg-slate-100 hover:bg-blue-50 hover:text-blue-700 border border-slate-200 rounded text-3xs font-mono font-semibold text-slate-700 transition">
                                    + {company_name}
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
                        <span class="text-3xs text-slate-500">Notifikasi salinan juga otomatis dikirim ke akun portal pelamar.</span>
                        <div class="flex items-center gap-2.5">
                            <button type="button" @click="showBroadcastModal = false" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl text-xs hover:bg-slate-100 transition shadow-2xs">
                                Batal
                            </button>
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs shadow-xs transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Kirim Email Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 3: CREATE NEW TEMPLATE                             -->
        <!-- ======================================================== -->
        <div 
            x-show="showCreateModal" 
            x-transition.opacity 
            class="fixed inset-0 bg-slate-950/75 backdrop-blur-xs z-50 flex items-center justify-center p-4 sm:p-6" 
            style="display: none;"
        >
            <div @click.away="showCreateModal = false" class="bg-white rounded-2xl max-w-xl w-full p-6 space-y-5 shadow-2xl border border-slate-200">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3.5">
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Buat Template Email Baru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Definisikan draf pesan standar untuk mempercepat komunikasi rekrutmen.</p>
                    </div>
                    <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('admin.email-templates.store') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Nama Template</label>
                            <input 
                                type="text" 
                                name="name" 
                                required 
                                placeholder="Misal: Undangan Wawancara Direksi" 
                                style="color: #0f172a !important; background-color: #ffffff !important;"
                                class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-2xs"
                            >
                        </div>
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Kategori Tahap</label>
                            <select 
                                name="type" 
                                required 
                                style="color: #0f172a !important; background-color: #ffffff !important;"
                                class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-2xs"
                            >
                                <option value="screening">HR Screening</option>
                                <option value="test_invitation">Tes Online & Asesmen</option>
                                <option value="interview_hr">Wawancara HR</option>
                                <option value="interview_user">Wawancara User</option>
                                <option value="offering">Offering Letter (Penawaran)</option>
                                <option value="background_check">Background Check</option>
                                <option value="reminder">Pengingat Jadwal</option>
                                <option value="rejection">Penolakan Lamaran</option>
                                <option value="general">Umum / Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-800 mb-1">Subjek Email</label>
                        <input 
                            type="text" 
                            name="subject" 
                            required 
                            placeholder="Misal: Undangan Wawancara Posisi {job_title} - {company_name}" 
                            style="color: #0f172a !important; background-color: #ffffff !important;"
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-2xs"
                        >
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block font-bold text-slate-800">Konten Pesan</label>
                            <span class="text-3xs text-slate-400">Klik tag untuk menyisipkan ke kursor</span>
                        </div>
                        <textarea 
                            id="create_body_content"
                            name="body_content" 
                            rows="7" 
                            required 
                            placeholder="Tuliskan draf email resmi... Gunakan tag dinamis {candidate_name}, {job_title}, {company_name}" 
                            style="color: #0f172a !important; background-color: #ffffff !important;"
                            class="w-full border border-slate-300 rounded-xl p-3.5 text-xs leading-relaxed text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-2xs"
                        ></textarea>

                        <!-- Variable Chips -->
                        <div class="flex items-center gap-1.5 flex-wrap mt-2">
                            <span class="text-3xs text-slate-500 font-medium mr-1">Sisipkan:</span>
                            <button type="button" @click="insertTag('create_body_content', '{candidate_name}')" class="px-2 py-0.5 bg-slate-100 hover:bg-blue-50 hover:text-blue-700 border border-slate-200 rounded text-3xs font-mono font-semibold text-slate-700 transition">
                                + {candidate_name}
                            </button>
                            <button type="button" @click="insertTag('create_body_content', '{job_title}')" class="px-2 py-0.5 bg-slate-100 hover:bg-blue-50 hover:text-blue-700 border border-slate-200 rounded text-3xs font-mono font-semibold text-slate-700 transition">
                                + {job_title}
                            </button>
                            <button type="button" @click="insertTag('create_body_content', '{company_name}')" class="px-2 py-0.5 bg-slate-100 hover:bg-blue-50 hover:text-blue-700 border border-slate-200 rounded text-3xs font-mono font-semibold text-slate-700 transition">
                                + {company_name}
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-semibold rounded-xl text-xs hover:bg-slate-200 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl text-xs shadow-2xs transition">
                            Simpan Template
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 4: EDIT TEMPLATE                                   -->
        <!-- ======================================================== -->
        <div 
            x-show="showEditModal" 
            x-transition.opacity 
            class="fixed inset-0 bg-slate-950/75 backdrop-blur-xs z-50 flex items-center justify-center p-4 sm:p-6" 
            style="display: none;"
        >
            <div @click.away="showEditModal = false" class="bg-white rounded-2xl max-w-xl w-full p-6 space-y-5 shadow-2xl border border-slate-200">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3.5">
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Perbarui Template Email</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Ubah judul, subjek, atau draf teks template.</p>
                    </div>
                    <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form :action="editFormUrl" method="POST" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Nama Template</label>
                            <input 
                                type="text" 
                                name="name" 
                                x-model="activeTemplate.name" 
                                required 
                                style="color: #0f172a !important; background-color: #ffffff !important;"
                                class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-2xs"
                            >
                        </div>
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Kategori Tahap</label>
                            <select 
                                name="type" 
                                x-model="activeTemplate.type" 
                                required 
                                style="color: #0f172a !important; background-color: #ffffff !important;"
                                class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-2xs"
                            >
                                <option value="screening">HR Screening</option>
                                <option value="test_invitation">Tes Online & Asesmen</option>
                                <option value="interview_hr">Wawancara HR</option>
                                <option value="interview_user">Wawancara User</option>
                                <option value="offering">Offering Letter (Penawaran)</option>
                                <option value="background_check">Background Check</option>
                                <option value="reminder">Pengingat Jadwal</option>
                                <option value="rejection">Penolakan Lamaran</option>
                                <option value="general">Umum / Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-800 mb-1">Subjek Email</label>
                        <input 
                            type="text" 
                            name="subject" 
                            x-model="activeTemplate.subject" 
                            required 
                            style="color: #0f172a !important; background-color: #ffffff !important;"
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-2xs"
                        >
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block font-bold text-slate-800">Konten Pesan</label>
                            <span class="text-3xs text-slate-400">Gunakan tag dinamis `{candidate_name}`, `{job_title}`, `{company_name}`</span>
                        </div>
                        <textarea 
                            id="edit_body_content"
                            name="body_content" 
                            rows="7" 
                            x-model="activeTemplate.body_content" 
                            required 
                            style="color: #0f172a !important; background-color: #ffffff !important;"
                            class="w-full border border-slate-300 rounded-xl p-3.5 text-xs leading-relaxed text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-2xs"
                        ></textarea>

                        <!-- Variable Chips -->
                        <div class="flex items-center gap-1.5 flex-wrap mt-2">
                            <span class="text-3xs text-slate-500 font-medium mr-1">Sisipkan:</span>
                            <button type="button" @click="insertTag('edit_body_content', '{candidate_name}')" class="px-2 py-0.5 bg-slate-100 hover:bg-blue-50 hover:text-blue-700 border border-slate-200 rounded text-3xs font-mono font-semibold text-slate-700 transition">
                                + {candidate_name}
                            </button>
                            <button type="button" @click="insertTag('edit_body_content', '{job_title}')" class="px-2 py-0.5 bg-slate-100 hover:bg-blue-50 hover:text-blue-700 border border-slate-200 rounded text-3xs font-mono font-semibold text-slate-700 transition">
                                + {job_title}
                            </button>
                            <button type="button" @click="insertTag('edit_body_content', '{company_name}')" class="px-2 py-0.5 bg-slate-100 hover:bg-blue-50 hover:text-blue-700 border border-slate-200 rounded text-3xs font-mono font-semibold text-slate-700 transition">
                                + {company_name}
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-semibold rounded-xl text-xs hover:bg-slate-200 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs shadow-2xs transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>

<script>
    function emailTemplateManager() {
        return {
            showPreviewModal: false,
            showCreateModal: false,
            showEditModal: false,
            showBroadcastModal: false,
            previewData: { subject: '', body_content: '', name: '' },
            activeTemplate: {},
            editFormUrl: '',
            broadcastSubject: '',
            broadcastBody: '',

            init() {
                window.addEventListener('open-create-modal', () => {
                    this.showCreateModal = true;
                });
                window.addEventListener('open-broadcast-modal', () => {
                    this.showBroadcastModal = true;
                });
            },

            previewTemplate(tpl) {
                this.previewData = { ...tpl };
                this.showPreviewModal = true;
            },

            editTemplate(tpl) {
                this.activeTemplate = { ...tpl };
                this.editFormUrl = `/admin/email-templates/${tpl.id}`;
                this.showEditModal = true;
            },

            useTemplate(tpl) {
                this.activeTemplate = { ...tpl };
                this.broadcastSubject = tpl.subject || '';
                this.broadcastBody = tpl.body_content || '';
                this.showBroadcastModal = true;
            },

            onCandidateSelect(e) {
                const opt = e.target.options[e.target.selectedIndex];
                if (opt && opt.value) {
                    const cName = opt.getAttribute('data-name') || '';
                    const cJob = opt.getAttribute('data-job') || '';
                    const cCompany = opt.getAttribute('data-company') || '';

                    if (this.activeTemplate && this.activeTemplate.subject) {
                        this.broadcastSubject = this.activeTemplate.subject
                            .replace(/\{candidate_name\}/g, cName)
                            .replace(/\{job_title\}/g, cJob)
                            .replace(/\{company_name\}/g, cCompany);

                        this.broadcastBody = this.activeTemplate.body_content
                            .replace(/\{candidate_name\}/g, cName)
                            .replace(/\{job_title\}/g, cJob)
                            .replace(/\{company_name\}/g, cCompany);
                    }
                }
            },

            insertBroadcastTag(tag) {
                const el = document.getElementById('broadcast_body_content');
                if (!el) return;

                const startPos = el.selectionStart || 0;
                const endPos = el.selectionEnd || 0;
                const text = el.value;

                el.value = text.substring(0, startPos) + tag + text.substring(endPos);
                this.broadcastBody = el.value;
                el.focus();
                el.selectionStart = el.selectionEnd = startPos + tag.length;
            },

            insertTag(textareaId, tag) {
                const el = document.getElementById(textareaId);
                if (!el) return;

                const startPos = el.selectionStart || 0;
                const endPos = el.selectionEnd || 0;
                const text = el.value;

                el.value = text.substring(0, startPos) + tag + text.substring(endPos);
                el.focus();
                el.selectionStart = el.selectionEnd = startPos + tag.length;

                if (textareaId === 'edit_body_content') {
                    this.activeTemplate.body_content = el.value;
                }
            }
        }
    }
</script>
