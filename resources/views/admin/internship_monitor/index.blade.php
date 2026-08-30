<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-teal-600 to-emerald-600 text-white flex items-center justify-center shadow-lg shadow-teal-500/20 text-xl font-black shrink-0">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 rounded-md text-3xs font-extrabold uppercase tracking-wider border border-teal-200 dark:border-teal-900">
                            Super Admin Central Monitor
                        </span>
                    </div>
                    <h2 class="font-black text-xl text-slate-900 dark:text-white leading-tight mt-0.5">
                        Dashboard Monitoring Magang Lintas Mitra
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Top Analytics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-5 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-xs space-y-1">
                    <span class="text-3xs font-black uppercase tracking-wider text-slate-400">Total Anak Magang Aktif</span>
                    <div class="text-3xl font-black text-slate-900 dark:text-white">{{ $totalInterns }} Peserta</div>
                    <span class="text-3xs text-emerald-600 dark:text-emerald-400 font-bold">Tersebar di seluruh mitra</span>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-xs space-y-1">
                    <span class="text-3xs font-black uppercase tracking-wider text-slate-400">Rata-Rata Kehadiran Global</span>
                    <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $overallAttendanceRate }}%</div>
                    <span class="text-3xs text-slate-400 font-semibold">{{ $totalApprovedLogbooks }} / {{ $totalLogbooksCount }} Hari Di-ACC</span>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-xs space-y-1">
                    <span class="text-3xs font-black uppercase tracking-wider text-slate-400">Logbook Menunggu ACC</span>
                    <div class="text-3xl font-black text-amber-500">{{ $totalPendingLogbooks }} Laporan</div>
                    <span class="text-3xs text-amber-600 dark:text-amber-400 font-bold">Dalam antrean mentor</span>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-xs space-y-1">
                    <span class="text-3xs font-black uppercase tracking-wider text-slate-400">Mitra Perusahaan Terdaftar</span>
                    <div class="text-3xl font-black text-indigo-600 dark:text-indigo-400">{{ $companies->count() }} Mitra</div>
                    <span class="text-3xs text-slate-400 font-semibold">Tersinkronisasi</span>
                </div>
            </div>

            <!-- Overdue Review Alert Section (Pending > 3 Days) -->
            @if($overdueReviews->count() > 0)
            <div class="bg-amber-500/10 border border-amber-500/30 rounded-3xl p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-lg font-bold">
                            ⚠️
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-amber-900 dark:text-amber-300">Peringatan: Logbook Belum Di-ACC Mentor > 3 Hari</h3>
                            <p class="text-3xs text-amber-800 dark:text-amber-400">Super Admin dapat melakukan ACC langsung (*Override*) jika Mentor berhalangan.</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-amber-500 text-white rounded-full text-3xs font-black uppercase">
                        {{ $overdueReviews->count() }} Laporan Tertunda
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($overdueReviews as $rev)
                    <div class="p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center justify-between shadow-2xs">
                        <div>
                            <div class="text-xs font-bold text-slate-900 dark:text-white">{{ $rev->intern->name }}</div>
                            <span class="text-3xs text-slate-400 block">{{ $rev->company->company_name ?? 'Mitra' }} • {{ $rev->date->format('d M Y') }}</span>
                            <span class="text-[10px] text-rose-500 font-semibold">Tertunda sejak {{ $rev->created_at->diffForHumans() }}</span>
                        </div>
                        <a href="{{ route('mentor.logbooks.show', $rev->id) }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-3xs font-extrabold rounded-xl transition shadow-xs">
                            Review / ACC →
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Company Breakdown Matrix -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-4">
                    <div>
                        <span class="text-3xs font-black uppercase tracking-wider text-slate-400">Breakdown Mitra</span>
                        <h3 class="text-base font-black text-slate-900 dark:text-white mt-0.5">Statistik Magang & Kehadiran per Mitra Perusahaan</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($companyBreakdown as $b)
                    <div class="p-5 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-black text-slate-900 dark:text-white">{{ $b['company']->company_name }}</h4>
                                <span class="text-3xs text-slate-400">{{ $b['company']->industry ?? 'Software & Tech' }}</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg text-3xs font-black bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300">
                                {{ $b['attendance_rate'] }}% Hadir
                            </span>
                        </div>

                        <!-- Mini Progress -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-3xs text-slate-400 font-semibold">
                                <span>Progress Verifikasi</span>
                                <span>{{ $b['approved_count'] }} Di-ACC / {{ $b['pending_count'] }} Pending</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $b['attendance_rate'] }}%"></div>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-200 dark:border-slate-700/60 text-3xs text-slate-500 flex justify-between">
                            <span>Total Laporan Logbook:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $b['total_logbooks'] }} Laporan</span>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-8 text-slate-400 text-xs">
                        Belum ada data aktivitas magang mitra.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
