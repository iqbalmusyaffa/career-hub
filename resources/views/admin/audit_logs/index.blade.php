<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 text-[11px] font-semibold rounded-md border border-blue-200 dark:border-blue-800">
                        Security & Compliance
                    </span>
                    <span class="text-xs text-slate-400 dark:text-slate-500">| System Audit Trail</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight">
                    Audit Logs (Jejak Aktivitas & Keamanan Sistem)
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                    Catatan immutable mutasi data, moderasi akun pengguna, aktivitas autentikasi, dan perubahan konfigurasi platform.
                </p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('admin.audit-logs.export', request()->query()) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-3.5 rounded-xl text-xs transition shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-file-csv text-xs"></i>
                    <span>Ekspor CSV</span>
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
             selectedLog: null,
             showDetailModal: false,
             openDetail(log) {
                 this.selectedLog = log;
                 this.showDetailModal = true;
             }
         }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. METRIC SUMMARY CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Logs -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Jejak Audit</span>
                        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs border border-blue-100 dark:border-blue-800">
                            <i class="fa-solid fa-database"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalLogs) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Entri tercatat di database</p>
                    </div>
                </div>

                <!-- Today Activity -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Aktivitas Hari Ini</span>
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs border border-emerald-100 dark:border-emerald-800">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 tracking-tight">{{ number_format($todayLogs) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Kejadian dalam 24 jam terakhir</p>
                    </div>
                </div>

                <!-- Security Events -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Event Kritis & Akses</span>
                        <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs border border-amber-100 dark:border-amber-800">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 tracking-tight">{{ number_format($securityLogs) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Impersonasi, peran, & konfigurasi</p>
                    </div>
                </div>

                <!-- Unique Actors -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Aktor Pengguna Aktif</span>
                        <div class="w-9 h-9 rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs border border-purple-100 dark:border-purple-800">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($uniqueActors) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Pengguna dengan riwayat mutasi</p>
                    </div>
                </div>
            </div>

            <!-- 2. CATEGORY PILL TABS -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 border-b border-slate-200/80 dark:border-slate-700/80 text-xs">
                @php
                    $currentCat = request('category', '');
                @endphp
                <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('category', 'page'), ['category' => ''])) }}"
                   class="px-3.5 py-1.5 rounded-xl font-medium transition whitespace-nowrap {{ empty($currentCat) ? 'bg-blue-600 text-white shadow-2xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700/80' }}">
                    <i class="fa-solid fa-layer-group text-[10px] mr-1"></i> Semua Jejak
                </a>
                <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('category', 'page'), ['category' => 'auth'])) }}"
                   class="px-3.5 py-1.5 rounded-xl font-medium transition whitespace-nowrap {{ $currentCat === 'auth' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700/80' }}">
                    <i class="fa-solid fa-shield-halved text-[10px] mr-1"></i> Keamanan & Autentikasi
                </a>
                <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('category', 'page'), ['category' => 'users'])) }}"
                   class="px-3.5 py-1.5 rounded-xl font-medium transition whitespace-nowrap {{ $currentCat === 'users' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700/80' }}">
                    <i class="fa-solid fa-user-gear text-[10px] mr-1"></i> Kelola Pengguna
                </a>
                <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('category', 'page'), ['category' => 'companies'])) }}"
                   class="px-3.5 py-1.5 rounded-xl font-medium transition whitespace-nowrap {{ $currentCat === 'companies' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700/80' }}">
                    <i class="fa-solid fa-building-circle-check text-[10px] mr-1"></i> Perusahaan & Legalitas
                </a>
                <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('category', 'page'), ['category' => 'jobs'])) }}"
                   class="px-3.5 py-1.5 rounded-xl font-medium transition whitespace-nowrap {{ $currentCat === 'jobs' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700/80' }}">
                    <i class="fa-solid fa-briefcase text-[10px] mr-1"></i> Lowongan & Pelamar
                </a>
                <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('category', 'page'), ['category' => 'settings'])) }}"
                   class="px-3.5 py-1.5 rounded-xl font-medium transition whitespace-nowrap {{ $currentCat === 'settings' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700/80' }}">
                    <i class="fa-solid fa-sliders text-[10px] mr-1"></i> Pengaturan Sistem (SMTP/SEO)
                </a>
            </div>

            <!-- 3. SEARCH & FILTER TOOLBAR -->
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-2xs">
                <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 text-xs">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif

                    <!-- Search Input -->
                    <div class="lg:col-span-4 space-y-1">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400">Pencarian Kata Kunci</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas, nama, email, atau IP..." 
                                   class="w-full pl-9 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2 px-3">
                        </div>
                    </div>

                    <!-- Action Type Dropdown -->
                    <div class="lg:col-span-3 space-y-1">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400">Spesifik Tipe Aksi</label>
                        <select name="action" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2 px-3">
                            <option value="">Semua Tipe Aksi ({{ $actionTypes->count() }})</option>
                            @foreach($actionTypes as $type)
                                <option value="{{ $type }}" {{ request('action') == $type ? 'selected' : '' }}>{{ strtoupper(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="lg:col-span-2 space-y-1">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400">Rentang Waktu</label>
                        <select name="date_range" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2 px-3">
                            <option value="">Semua Waktu</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="7days" {{ request('date_range') == '7days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                            <option value="30days" {{ request('date_range') == '30days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        </select>
                    </div>

                    <!-- Per Page -->
                    <div class="lg:col-span-1 space-y-1">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400">Baris</label>
                        <select name="per_page" onchange="this.form.submit()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2 px-3">
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <!-- Filter & Reset Buttons -->
                    <div class="lg:col-span-2 flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3.5 rounded-xl text-xs transition shadow-2xs flex items-center justify-center gap-1.5 h-[36px]">
                            <i class="fa-solid fa-filter"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('admin.audit-logs.index') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium rounded-xl text-xs border border-slate-200 dark:border-slate-800 transition flex items-center justify-center h-[36px]">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- 4. AUDIT LOGS TABLE -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 overflow-hidden shadow-2xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-700/80 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-4 pl-6">Waktu & IP Address</th>
                                <th class="py-3 px-4">Pengguna (Actor)</th>
                                <th class="py-3 px-4">Aksi / Event</th>
                                <th class="py-3 px-4">Rincian Deskripsi Aktivitas</th>
                                <th class="py-3 px-4 pr-6 text-right">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs text-slate-700 dark:text-slate-300">
                            @forelse($logs as $log)
                                @php
                                    $action = strtolower($log->action);
                                    
                                    // Determine badge styling based on action keyword
                                    if (str_contains($action, 'created') || str_contains($action, 'verified') || str_contains($action, 'approve')) {
                                        $badgeClass = 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/80';
                                        $iconClass = 'fa-circle-plus';
                                    } elseif (str_contains($action, 'delete') || str_contains($action, 'reset') || str_contains($action, 'destroy')) {
                                        $badgeClass = 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border-rose-200 dark:border-rose-800/80';
                                        $iconClass = 'fa-trash-can';
                                    } elseif (str_contains($action, 'suspend') || str_contains($action, 'reject')) {
                                        $badgeClass = 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800/80';
                                        $iconClass = 'fa-ban';
                                    } elseif (str_contains($action, 'impersonate') || str_contains($action, 'role')) {
                                        $badgeClass = 'bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-800/80';
                                        $iconClass = 'fa-user-shield';
                                    } else {
                                        $badgeClass = 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800/80';
                                        $iconClass = 'fa-pen-to-square';
                                    }

                                    // Simple User-Agent Parsing
                                    $ua = $log->user_agent ?? '';
                                    $browser = 'Browser';
                                    $browserIcon = 'fa-globe';
                                    if (str_contains($ua, 'Edg/')) { $browser = 'Edge'; $browserIcon = 'fa-edge'; }
                                    elseif (str_contains($ua, 'Chrome/')) { $browser = 'Chrome'; $browserIcon = 'fa-chrome'; }
                                    elseif (str_contains($ua, 'Firefox/')) { $browser = 'Firefox'; $browserIcon = 'fa-firefox'; }
                                    elseif (str_contains($ua, 'Safari/') && !str_contains($ua, 'Chrome/')) { $browser = 'Safari'; $browserIcon = 'fa-safari'; }

                                    $os = '';
                                    if (str_contains($ua, 'Windows')) { $os = 'Windows'; }
                                    elseif (str_contains($ua, 'Macintosh')) { $os = 'macOS'; }
                                    elseif (str_contains($ua, 'Linux')) { $os = 'Linux'; }
                                    elseif (str_contains($ua, 'Android')) { $os = 'Android'; }
                                    elseif (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) { $os = 'iOS'; }
                                @endphp

                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/30 transition group">
                                    <!-- Timestamp & IP -->
                                    <td class="py-3.5 px-4 pl-6 whitespace-nowrap">
                                        <div class="font-bold text-slate-900 dark:text-white text-xs font-mono">
                                            {{ $log->created_at->format('d M Y, H:i:s') }}
                                        </div>
                                        <div class="text-[11px] text-slate-400 dark:text-slate-500 font-mono mt-0.5 flex items-center gap-1.5">
                                            <span class="px-1.5 py-0.2 bg-slate-100 dark:bg-slate-900 rounded text-[10px]">{{ $log->ip_address ?? '127.0.0.1' }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $log->created_at->diffForHumans() }}</span>
                                        </div>
                                    </td>

                                    <!-- Actor -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($log->user)
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 font-bold flex items-center justify-center text-xs border border-blue-200 dark:border-blue-800/60 shrink-0">
                                                    {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-slate-900 dark:text-white text-xs flex items-center gap-1.5">
                                                        <span>{{ $log->user->name }}</span>
                                                        <span class="text-[9px] px-1.5 py-0.2 rounded font-mono font-normal bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-800">
                                                            {{ $log->user->role }}
                                                        </span>
                                                    </div>
                                                    <div class="text-[11px] text-slate-400 dark:text-slate-500">{{ $log->user->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2 text-slate-400 dark:text-slate-500">
                                                <i class="fa-solid fa-robot text-xs"></i>
                                                <span class="italic text-xs">Sistem / Background Job</span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Action / Event Badge -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="px-2 py-0.5 text-[10px] font-mono font-semibold rounded-md border inline-flex items-center gap-1.5 uppercase tracking-wider {{ $badgeClass }}">
                                            <i class="fa-solid {{ $iconClass }} text-[9px]"></i>
                                            <span>{{ str_replace('_', ' ', $log->action) }}</span>
                                        </span>
                                    </td>

                                    <!-- Description & Client Signature -->
                                    <td class="py-3.5 px-4">
                                        <div class="font-medium text-slate-800 dark:text-slate-200 text-xs">
                                            {{ $log->description }}
                                        </div>
                                        @if($log->user_agent)
                                            <div class="text-[10px] text-slate-400 dark:text-slate-500 flex items-center gap-1.5 mt-0.5">
                                                <i class="fa-brands {{ $browserIcon }} text-slate-400"></i>
                                                <span>{{ $browser }} {{ $os ? "({$os})" : '' }}</span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Action / Detail Modal Trigger -->
                                    <td class="py-3.5 px-4 pr-6 text-right whitespace-nowrap">
                                        <button type="button" 
                                            @click="openDetail({{ json_encode([
                                                'id' => $log->id,
                                                'created_at' => $log->created_at->format('d M Y, H:i:s') . ' (' . $log->created_at->diffForHumans() . ')',
                                                'action' => strtoupper(str_replace('_', ' ', $log->action)),
                                                'description' => $log->description,
                                                'ip_address' => $log->ip_address ?? '127.0.0.1',
                                                'user_agent' => $log->user_agent ?? '-',
                                                'user_name' => $log->user ? $log->user->name : 'Sistem / Background Worker',
                                                'user_email' => $log->user ? $log->user->email : '-',
                                                'user_role' => $log->user ? $log->user->role : 'System Worker'
                                            ]) }})"
                                            class="px-2.5 py-1 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 bg-slate-100 dark:bg-slate-900 hover:bg-blue-50 dark:hover:bg-blue-950/40 border border-slate-200 dark:border-slate-800 transition inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle-info text-[10px]"></i>
                                            <span>Detail</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-14 text-center text-slate-400 dark:text-slate-500 text-xs font-medium">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-900 text-slate-400 dark:text-slate-600 flex items-center justify-center text-xl mx-auto mb-3">
                                            <i class="fa-solid fa-list-check"></i>
                                        </div>
                                        <div class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tidak ada log aktivitas ditemukan</div>
                                        <p class="text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter kategori di atas.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-950/60">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- 5. INTERACTIVE AUDIT LOG DETAIL MODAL -->
        <div x-show="showDetailModal" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDetailModal" 
                     x-transition:enter="ease-out duration-200" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-150" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0" 
                     @click="showDetailModal = false"
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showDetailModal" 
                     x-transition:enter="ease-out duration-200" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-150" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200/80 dark:border-slate-800">
                    
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-950/50">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs border border-blue-200 dark:border-blue-800">
                                <i class="fa-solid fa-file-waveform"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-slate-900 dark:text-white" id="modal-title">
                                    Inspeksi Detail Jejak Audit #<span x-text="selectedLog?.id"></span>
                                </h3>
                                <p class="text-[11px] text-slate-400 font-mono" x-text="selectedLog?.created_at"></p>
                            </div>
                        </div>
                        <button type="button" @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-4 text-xs">
                        <!-- Action Event Badge -->
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Tipe Aksi Terdeteksi:</span>
                            <span class="px-2.5 py-0.5 rounded-md font-mono font-semibold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 text-[11px]" x-text="selectedLog?.action"></span>
                        </div>

                        <!-- Description -->
                        <div class="space-y-1">
                            <label class="block font-semibold text-slate-700 dark:text-slate-300">Deskripsi Aktivitas</label>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-slate-800 dark:text-slate-200 leading-relaxed font-sans" x-text="selectedLog?.description"></div>
                        </div>

                        <!-- Actor Card -->
                        <div class="space-y-1">
                            <label class="block font-semibold text-slate-700 dark:text-slate-300">Pelaku Aktivitas (Actor)</label>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white" x-text="selectedLog?.user_name"></div>
                                    <div class="text-[11px] text-slate-400" x-text="selectedLog?.user_email"></div>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300" x-text="selectedLog?.user_role"></span>
                            </div>
                        </div>

                        <!-- Technical Headers -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block font-semibold text-slate-700 dark:text-slate-300">IP Address</label>
                                <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 font-mono text-slate-700 dark:text-slate-300" x-text="selectedLog?.ip_address"></div>
                            </div>
                            <div class="space-y-1">
                                <label class="block font-semibold text-slate-700 dark:text-slate-300">Status Integritas</label>
                                <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Immutable Verified</span>
                                </div>
                            </div>
                        </div>

                        <!-- User Agent Raw -->
                        <div class="space-y-1">
                            <label class="block font-semibold text-slate-700 dark:text-slate-300">Raw Client User-Agent</label>
                            <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 font-mono text-[11px] text-slate-500 dark:text-slate-400 break-all" x-text="selectedLog?.user_agent"></div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-3.5 bg-slate-50 dark:bg-slate-950 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                        <button type="button" @click="showDetailModal = false" class="bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold py-2 px-4 rounded-xl text-xs transition">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</x-app-layout>
