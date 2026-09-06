<x-app-layout>
    <div class="py-8" x-data="{
        searchQuery: '',
        filterStatus: 'all',
        selectedCompany: null,
        modalTab: 'pending', // 'all', 'pending', 'approved', 'rejected'
        modalInternFilter: 'all',

        openCompanyDetail(companyData) {
            this.selectedCompany = companyData;
            this.modalTab = companyData.pending_count > 0 ? 'pending' : 'all';
            this.modalInternFilter = 'all';
        },
        closeCompanyDetail() {
            this.selectedCompany = null;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Admin</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">Monitoring Platform</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-teal-600 dark:text-teal-400 font-semibold">Magang Lintas Mitra</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                                Monitoring Magang Lintas Mitra
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                                Klik pada mitra perusahaan atau status presensi untuk melihat rincian nama peserta, riwayat logbook, dan melakukan ACC langsung.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.internship-unlocks.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-2xs">
                        <i class="fa-solid fa-key text-purple-500"></i>
                        <span>Pusat Buka Kunci</span>
                    </a>
                </div>
            </div>

            <!-- Top Analytics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Total Interns -->
                <div class="p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Peserta Aktif</span>
                        <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 border border-teal-200/80 dark:border-teal-900 flex items-center justify-center text-xs font-semibold">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalInterns }} <span class="text-xs font-normal text-slate-500">Kandidat</span></div>
                        <div class="flex items-center gap-1.5 text-[11px] text-teal-600 dark:text-teal-400 font-medium mt-1">
                            <i class="fa-solid fa-circle-check text-[9px]"></i>
                            <span>Tersebar di {{ $companies->count() }} mitra perusahaan</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Attendance Rate -->
                <div class="p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Tingkat Kehadiran</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-900 flex items-center justify-center text-xs font-semibold">
                            <i class="fa-solid fa-percent"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $overallAttendanceRate }}%</div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                            {{ $totalApprovedLogbooks }} disetujui dari {{ $totalLogbooksCount }} total laporan
                        </div>
                    </div>
                </div>

                <!-- Card 3: Pending Reviews -->
                <div class="p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Menunggu ACC Mentor</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200/80 dark:border-amber-900 flex items-center justify-center text-xs font-semibold">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $totalPendingLogbooks }} <span class="text-xs font-normal text-slate-500">Laporan</span></div>
                        <div class="flex items-center gap-1.5 text-[11px] text-amber-600 dark:text-amber-400 font-medium mt-1">
                            <span>{{ $overdueReviews->count() }} laporan melebihi batas 3 hari</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total Companies -->
                <div class="p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Mitra Terdaftar</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/80 dark:border-blue-900 flex items-center justify-center text-xs font-semibold">
                            <i class="fa-solid fa-building"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $companies->count() }} <span class="text-xs font-normal text-slate-500">Perusahaan</span></div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                            Status sinkronisasi platform aktif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overdue Review SLA Warning Section -->
            @if($overdueReviews->count() > 0)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-amber-500/40 dark:border-amber-500/30 shadow-xs overflow-hidden">
                <div class="p-4 sm:p-5 bg-amber-50/70 dark:bg-amber-950/40 border-b border-amber-200/80 dark:border-amber-900/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-xs">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-900 dark:text-white">
                                Antrean Logbook Tertunda (SLA &gt; 3 Hari)
                            </h3>
                            <p class="text-[11px] text-slate-600 dark:text-slate-400">
                                Laporan harian yang belum diverifikasi oleh mentor mitra terkait. Super Admin dapat melakukan tindakan ACC langsung.
                            </p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-2xs font-bold bg-amber-100 dark:bg-amber-900/80 text-amber-800 dark:text-amber-200 border border-amber-300 dark:border-amber-700 w-fit">
                        {{ $overdueReviews->count() }} Antrean Melebihi SLA
                    </span>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                    @foreach($overdueReviews as $rev)
                    <div class="p-4 sm:px-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50 dark:hover:bg-slate-850/60 transition bg-white dark:bg-slate-900">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-bold flex items-center justify-center text-xs shrink-0 border border-slate-200 dark:border-slate-700">
                                {{ substr($rev->intern->name ?? 'M', 0, 1) }}
                            </div>
                            <div>
                                <div class="font-semibold text-xs text-slate-900 dark:text-white">{{ $rev->intern->name }}</div>
                                <div class="flex flex-wrap items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $rev->company->company_name ?? 'Mitra Perusahaan' }}</span>
                                    <span>•</span>
                                    <span>Tanggal: {{ $rev->date->isoFormat('D MMM YYYY') }}</span>
                                    <span>•</span>
                                    <span>Mentor: {{ $rev->mentor->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-end sm:self-center">
                            <span class="text-[10px] font-medium text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/60 px-2 py-0.5 rounded border border-rose-200/80 dark:border-rose-900">
                                Tertunda {{ $rev->created_at->diffForHumans(null, true) }}
                            </span>
                            <a href="{{ route('mentor.logbooks.show', $rev->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-lg text-xs font-semibold transition shadow-2xs border border-slate-700">
                                <span>Periksa & ACC</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Company Breakdown Matrix (Data Table Format) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
                <!-- Table Header & Controls -->
                <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-slate-900">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span>Matriks Kinerja & Kehadiran per Mitra</span>
                            <span class="text-2xs font-normal text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full">Klik baris mana saja untuk melihat nama peserta & status ACC</span>
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Rincian tingkat kepatuhan kehadiran, rasio verifikasi logbook, dan kebijakan hari kerja masing-masing mitra.
                        </p>
                    </div>

                    <!-- Search Input -->
                    <div class="w-full sm:w-64 relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" x-model="searchQuery" placeholder="Cari nama mitra industri..." class="w-full pl-8 pr-3 py-1.5 rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-xs text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                </div>

                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse bg-white dark:bg-slate-900">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/80 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold uppercase text-slate-500 dark:text-slate-400 tracking-wider">
                                <th class="py-3.5 px-5">Mitra Perusahaan</th>
                                <th class="py-3.5 px-5">Total Peserta & Laporan</th>
                                <th class="py-3.5 px-5">Status Verifikasi (ACC / Pending)</th>
                                <th class="py-3.5 px-5">Tingkat Kehadiran</th>
                                <th class="py-3.5 px-5">Kebijakan Kerja</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs bg-white dark:bg-slate-900">
                            @forelse($companyBreakdown as $b)
                            @php
                                $companyJson = json_encode([
                                    'id' => $b['company']->id,
                                    'company_name' => $b['company']->company_name,
                                    'industry' => $b['company']->industry,
                                    'total_logbooks' => $b['total_logbooks'],
                                    'approved_count' => $b['approved_count'],
                                    'pending_count' => $b['pending_count'],
                                    'rejected_count' => $b['rejected_count'],
                                    'attendance_rate' => $b['attendance_rate'],
                                    'allow_saturday_work' => (bool) ($b['company']->allow_saturday_work ?? false),
                                    'interns_list' => $b['interns_list'],
                                ]);
                            @endphp
                            <tr x-show="searchQuery === '' || '{{ strtolower(addslashes($b['company']->company_name)) }}'.includes(searchQuery.toLowerCase())"
                                @click="openCompanyDetail({{ $companyJson }})"
                                class="hover:bg-slate-50 dark:hover:bg-slate-850/50 transition cursor-pointer group">
                                
                                <!-- Company Name & Industry -->
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/80 dark:border-blue-900 font-bold flex items-center justify-center text-xs shrink-0 group-hover:scale-105 transition">
                                            {{ strtoupper(substr($b['company']->company_name ?? 'M', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition flex items-center gap-1.5">
                                                <span>{{ $b['company']->company_name }}</span>
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400 opacity-0 group-hover:opacity-100 transition"></i>
                                            </div>
                                            <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ $b['company']->industry ?? 'Mitra Perusahaan' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Total Peserta & Logbooks -->
                                <td class="py-4 px-5">
                                    <div class="font-semibold text-slate-900 dark:text-white">{{ $b['interns_count'] }} Peserta</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $b['total_logbooks'] }} total logbook</div>
                                </td>

                                <!-- Approval Status (ACC vs Pending) -->
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="inline-flex items-center gap-1 font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-200/60 dark:border-emerald-900/60">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                            {{ $b['approved_count'] }} ACC
                                        </span>
                                        <span class="text-slate-300 dark:text-slate-700">/</span>
                                        <span class="inline-flex items-center gap-1 font-semibold {{ $b['pending_count'] > 0 ? 'text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 border border-amber-200/60 dark:border-amber-900/60' : 'text-slate-500' }} px-2 py-0.5 rounded">
                                            <i class="fa-regular fa-clock text-[10px]"></i>
                                            {{ $b['pending_count'] }} Pending
                                        </span>
                                    </div>
                                </td>

                                <!-- Attendance Progress Bar -->
                                <td class="py-4 px-5">
                                    <div class="w-44 space-y-1.5">
                                        <div class="flex justify-between text-[11px]">
                                            <span class="font-bold text-slate-900 dark:text-white">{{ $b['attendance_rate'] }}%</span>
                                            <span class="text-slate-400 text-[10px]">
                                                @if($b['attendance_rate'] >= 80)
                                                    <span class="text-emerald-600 dark:text-emerald-400 font-medium">Optimal</span>
                                                @elseif($b['attendance_rate'] > 0)
                                                    <span class="text-amber-600 dark:text-amber-400 font-medium">Perlu Pantauan</span>
                                                @else
                                                    <span class="text-slate-400">Belum Ada Data</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-1.5 rounded-full transition-all duration-300 {{ $b['attendance_rate'] >= 80 ? 'bg-emerald-500' : ($b['attendance_rate'] > 0 ? 'bg-amber-500' : 'bg-slate-300 dark:bg-slate-700') }}"
                                                 style="width: {{ max(4, $b['attendance_rate']) }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Work Schedule Policy Badge -->
                                <td class="py-4 px-5">
                                    @if($b['company']->allow_saturday_work ?? false)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/80 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-900">
                                            <i class="fa-solid fa-briefcase text-[9px]"></i>
                                            <span>6 Hari (Sabtu)</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700">
                                            <span>5 Hari Kerja</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Action Button -->
                                <td class="py-4 px-5 text-right">
                                    <button type="button" @click.stop="openCompanyDetail({{ $companyJson }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-semibold transition border border-slate-200 dark:border-slate-700">
                                        <span>Rincian & ACC</span>
                                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-xl mb-3">
                                        <i class="fa-regular fa-folder-open"></i>
                                    </div>
                                    <p class="font-medium text-xs text-slate-700 dark:text-slate-300">Belum ada data aktivitas magang mitra.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- ==================================================================== -->
        <!-- INTERACTIVE DRILL-DOWN MODAL: DETAIL PESERTA & ACC LOGBOOK PER MITRA -->
        <!-- ==================================================================== -->
        <div x-show="selectedCompany !== null" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 dark:bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4 sm:p-6"
             @keydown.escape.window="closeCompanyDetail()">
            
            <div x-show="selectedCompany !== null"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.away="closeCompanyDetail()"
                 class="w-full max-w-5xl bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                
                <!-- Modal Header -->
                <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/70 dark:bg-slate-950/50 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/80 dark:border-blue-900 font-bold flex items-center justify-center text-sm shrink-0">
                            <span x-text="selectedCompany ? selectedCompany.company_name.substring(0, 1).toUpperCase() : 'M'"></span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="selectedCompany ? selectedCompany.company_name : ''"></h3>
                                <template x-if="selectedCompany && selectedCompany.allow_saturday_work">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-900">
                                        6 Hari Kerja (Sabtu Masuk)
                                    </span>
                                </template>
                                <template x-if="selectedCompany && !selectedCompany.allow_saturday_work">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700">
                                        5 Hari Kerja
                                    </span>
                                </template>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5" x-text="selectedCompany ? selectedCompany.industry : ''"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" @click="closeCompanyDetail()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Subheader / Quick Statistics & Tabs -->
                <div class="px-5 py-3 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex flex-wrap items-center justify-between gap-3 shrink-0">
                    <!-- Status Filter Tabs -->
                    <div class="flex items-center gap-1.5 p-1 bg-slate-100 dark:bg-slate-950 rounded-xl">
                        <button type="button" 
                                @click="modalTab = 'pending'" 
                                :class="modalTab === 'pending' ? 'bg-white dark:bg-slate-900 text-amber-600 dark:text-amber-400 font-bold shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 font-medium'"
                                class="px-3 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5">
                            <i class="fa-regular fa-clock text-[11px]"></i>
                            <span>Menunggu ACC</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300 font-bold" x-text="selectedCompany ? selectedCompany.pending_count : 0"></span>
                        </button>

                        <button type="button" 
                                @click="modalTab = 'approved'" 
                                :class="modalTab === 'approved' ? 'bg-white dark:bg-slate-900 text-emerald-600 dark:text-emerald-400 font-bold shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 font-medium'"
                                class="px-3 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5">
                            <i class="fa-solid fa-check text-[11px]"></i>
                            <span>Sudah di-ACC</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 font-bold" x-text="selectedCompany ? selectedCompany.approved_count : 0"></span>
                        </button>

                        <button type="button" 
                                @click="modalTab = 'all'" 
                                :class="modalTab === 'all' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-bold shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 font-medium'"
                                class="px-3 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5">
                            <span>Semua Logbook</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold" x-text="selectedCompany ? selectedCompany.total_logbooks : 0"></span>
                        </button>
                    </div>

                    <!-- Filter by Intern -->
                    <div class="flex items-center gap-2">
                        <label class="text-xs text-slate-500 font-medium">Filter Peserta:</label>
                        <select x-model="modalInternFilter" class="py-1 px-2.5 rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-xs text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-blue-500">
                            <option value="all">Semua Peserta Magang</option>
                            <template x-for="intern in (selectedCompany ? selectedCompany.interns_list : [])" :key="intern.user_id">
                                <option :value="intern.user_id" x-text="intern.name + ' (' + intern.pending_count + ' Pending)'"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <!-- Modal Body / Logbook Feed & Actions -->
                <div class="p-5 overflow-y-auto space-y-4 divide-y divide-slate-100 dark:divide-slate-800">
                    <template x-for="intern in (selectedCompany ? selectedCompany.interns_list : [])" :key="intern.user_id">
                        <div x-show="modalInternFilter === 'all' || modalInternFilter == intern.user_id" class="pt-4 first:pt-0 space-y-3">
                            <!-- Intern Summary Header -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3 rounded-xl bg-slate-50/80 dark:bg-slate-950/40 border border-slate-200/70 dark:border-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 font-bold flex items-center justify-center text-xs shrink-0">
                                        <span x-text="intern.name.substring(0, 1).toUpperCase()"></span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-xs text-slate-900 dark:text-white" x-text="intern.name"></div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400" x-text="intern.email"></div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 self-start sm:self-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-900" x-text="intern.approved_count + ' ACC'"></span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900" x-text="intern.pending_count + ' Pending'"></span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold text-slate-700 dark:text-slate-300 bg-slate-200/80 dark:bg-slate-800" x-text="intern.rate + '% Presensi'"></span>
                                </div>
                            </div>

                            <!-- Logbooks List Under this Intern -->
                            <div class="space-y-2 pl-2 sm:pl-4">
                                <template x-for="logbook in intern.logbooks" :key="logbook.id">
                                    <div x-show="modalTab === 'all' || (modalTab === 'pending' && logbook.status === 'pending') || (modalTab === 'approved' && logbook.status === 'approved') || (modalTab === 'rejected' && logbook.status === 'rejected')"
                                         class="p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700 transition flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                        
                                        <div class="space-y-1.5 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="font-bold text-xs text-slate-900 dark:text-white" x-text="logbook.date"></span>
                                                <span class="text-[10px] font-medium text-slate-400">•</span>
                                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded uppercase"
                                                      :class="logbook.status === 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300' : (logbook.status === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300')"
                                                      x-text="logbook.status === 'approved' ? 'Disetujui (ACC)' : (logbook.status === 'pending' ? 'Menunggu ACC' : logbook.status)">
                                                </span>
                                                
                                                <template x-if="logbook.has_gps">
                                                    <span class="text-[10px] text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/60 px-1.5 py-0.5 rounded border border-teal-200/60 dark:border-teal-900 flex items-center gap-1">
                                                        <i class="fa-solid fa-location-dot text-[9px]"></i>
                                                        <span x-text="logbook.location_address"></span>
                                                    </span>
                                                </template>
                                            </div>

                                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-normal" x-text="logbook.activities"></p>

                                            <div class="text-[11px] text-slate-400 flex items-center gap-2 pt-0.5">
                                                <span>Mentor: <strong class="text-slate-600 dark:text-slate-300" x-text="logbook.mentor_name"></strong></span>
                                                <template x-if="logbook.mentor_notes">
                                                    <span>• Catatan: <em class="text-slate-500" x-text="logbook.mentor_notes"></em></span>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Quick Direct ACC Button & Full View -->
                                        <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                            <template x-if="logbook.status === 'pending'">
                                                <form :action="logbook.approve_url" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="mentor_notes" value="Disetujui (ACC Langsung) oleh Super Admin melalui Monitoring Platform.">
                                                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition">
                                                        <i class="fa-solid fa-check text-[10px]"></i>
                                                        <span>ACC Langsung</span>
                                                    </button>
                                                </form>
                                            </template>

                                            <a :href="logbook.show_url" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition">
                                                <span>Tinjau</span>
                                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                            </a>
                                        </div>

                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Empty state if no logbooks found under filter -->
                    <template x-if="selectedCompany && selectedCompany.interns_list.length === 0">
                        <div class="py-12 text-center text-slate-500">
                            <i class="fa-regular fa-folder-open text-3xl mb-2 text-slate-400"></i>
                            <p class="text-xs font-medium">Belum ada aktivitas presensi atau laporan harian dari peserta magang mitra ini.</p>
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50 flex items-center justify-between shrink-0">
                    <span class="text-xs text-slate-500 dark:text-slate-400">
                        Super Admin memiliki wewenang penuh untuk melakukan ACC dan verifikasi logbook lintas entitas mitra.
                    </span>
                    <button type="button" @click="closeCompanyDetail()" class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold border border-slate-200 dark:border-slate-700 transition">
                        Tutup
                    </button>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
