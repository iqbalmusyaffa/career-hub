<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 text-[11px] font-semibold rounded-md border border-amber-200 dark:border-amber-800">
                        Company Owner
                    </span>
                    <span class="text-xs text-slate-400 dark:text-slate-500 font-normal">| Panel Eksekutif</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight">
                    Dashboard Eksekutif Perusahaan
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                    Ringkasan performa rekrutmen, statistik pelamar kerja, dan status legalitas {{ $companyProfile->company_name }}.
                </p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.jobs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3.5 rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Pasang Lowongan Baru
                </a>
                <a href="{{ route('admin.company.profile.edit') }}" class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold py-2 px-3.5 rounded-xl text-xs transition border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-building-shield text-slate-500 dark:text-slate-400 text-xs"></i> Profil & Dokumen Legalitas
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50/60 dark:bg-slate-900 min-h-screen transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Company Status Identity Banner -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 sm:p-6 shadow-2xs border border-slate-200/80 dark:border-slate-700/80 flex flex-col md:flex-row justify-between items-start md:items-center gap-5">
                <div class="flex items-start sm:items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-slate-900 dark:bg-slate-700 text-white font-bold text-xl flex items-center justify-center border border-slate-800 dark:border-slate-600 shrink-0">
                        {{ strtoupper(substr($companyProfile->company_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">{{ $companyProfile->company_name }}</h3>
                            @if($companyProfile->is_verified)
                                <span class="px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 text-xs font-semibold rounded-md border border-emerald-200 dark:border-emerald-800 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-xs"></i> Terverifikasi Resmi
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 text-xs font-semibold rounded-md border border-amber-200 dark:border-amber-800 flex items-center gap-1">
                                    <i class="fa-solid fa-clock text-amber-600 dark:text-amber-400 text-xs"></i> Menunggu Verifikasi NIB / SIUP
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-normal flex flex-wrap items-center gap-3">
                            <span><i class="fa-solid fa-industry text-slate-400 mr-1"></i> {{ $companyProfile->industry ?? 'Industri Umum' }}</span>
                            <span>&bull;</span>
                            <span><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $companyProfile->address ?? 'Lokasi Kantor' }}</span>
                        </p>
                    </div>
                </div>

                <div class="shrink-0">
                    <a href="{{ route('admin.company.profile.edit') }}" class="px-4 py-2 bg-slate-50 dark:bg-slate-900/60 hover:bg-slate-100 dark:hover:bg-slate-900 text-slate-700 dark:text-slate-200 font-semibold rounded-xl text-xs border border-slate-200 dark:border-slate-700 transition shadow-2xs flex items-center gap-2">
                        <i class="fa-solid fa-file-contract text-slate-500 dark:text-slate-400 text-xs"></i> Kelola Legalitas NIB / SIUP
                    </a>
                </div>
            </div>

            <!-- Executive Stat Cards (4 Cards Grid) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- 1. Active Jobs -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Lowongan Aktif</span>
                        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs border border-blue-100 dark:border-blue-800">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $activeJobsCount }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Dari {{ $companyJobsCount }} total lowongan</p>
                    </div>
                </div>

                <!-- 2. Total Applicants -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Berkas Lamaran</span>
                        <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs border border-indigo-100 dark:border-indigo-800">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalCompanyApplications) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal"><span class="font-semibold text-amber-600 dark:text-amber-400">{{ $pendingCount }}</span> berkas baru masuk</p>
                    </div>
                </div>

                <!-- 3. Interviews -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tahap Wawancara</span>
                        <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs border border-amber-100 dark:border-amber-800">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 tracking-tight">{{ $interviewCount }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Kandidat aktif interview</p>
                    </div>
                </div>

                <!-- 4. Hired -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Kandidat Diterima</span>
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs border border-emerald-100 dark:border-emerald-800">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 tracking-tight">{{ $hiredCount }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Lolos tahap seleksi</p>
                    </div>
                </div>
            </div>

            <!-- Content Grid: Active Jobs & Recent Applicants -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Active Jobs (2 Cols) -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 p-5 sm:p-6 space-y-4 lg:col-span-2">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700/60 pb-3">
                        <div>
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-briefcase text-blue-600 dark:text-blue-400 text-xs"></i> Performa Lowongan Kerja
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Daftar posisi yang sedang dipublikasikan perusahaan.</p>
                        </div>
                        <a href="{{ route('admin.jobs.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                            Kelola Lowongan &rarr;
                        </a>
                    </div>

                    @if(count($companyJobs) > 0)
                        <div class="space-y-3">
                            @foreach($companyJobs->take(5) as $job)
                                <div class="p-3.5 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200/80 dark:border-slate-700/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                    <div>
                                        <a href="{{ route('admin.jobs.show', $job->id) }}" class="font-bold text-slate-900 dark:text-white text-xs hover:text-blue-600 dark:hover:text-blue-400 transition">{{ $job->title }}</a>
                                        <p class="text-slate-500 dark:text-slate-400 mt-0.5 text-[11px] font-normal">
                                            {{ $job->division ?? 'Umum' }} &bull; {{ $job->location }} &bull; <span class="font-semibold text-blue-600 dark:text-blue-400">{{ ucfirst($job->work_type) }}</span>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3 shrink-0">
                                        <div class="text-right">
                                            <span class="font-bold text-slate-900 dark:text-white text-xs">{{ $job->applications_count }} Pelamar</span>
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-normal block">Kuota: {{ $job->quota ?? 'Fleksibel' }}</span>
                                        </div>
                                        <a href="{{ route('admin.jobs.edit', $job->id) }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 font-semibold rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition text-xs shadow-2xs">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-dashed border-slate-200 dark:border-slate-700 text-xs text-slate-500 dark:text-slate-400 font-normal">
                            <p class="mb-3">Belum ada lowongan pekerjaan dibuka untuk perusahaan ini.</p>
                            <a href="{{ route('admin.jobs.create') }}" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-xl text-xs shadow-xs">
                                + Pasang Lowongan Pertama
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Recent Applicants -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 p-5 sm:p-6 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700/60 pb-3">
                        <div>
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-slate-400 text-xs"></i> Pelamar Terbaru
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Pendaftar baru di perusahaan Anda.</p>
                        </div>
                        <a href="{{ route('admin.applications.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                            Semua Pelamar &rarr;
                        </a>
                    </div>

                    @if(count($recentApplications) > 0)
                        <div class="space-y-2.5">
                            @foreach($recentApplications as $app)
                                <a href="{{ route('admin.applications.show', $app->id) }}" class="block p-3 bg-slate-50 dark:bg-slate-900/60 hover:bg-slate-100/80 dark:hover:bg-slate-900 rounded-xl border border-slate-200/70 dark:border-slate-700/70 transition group">
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">{{ $app->user->name ?? 'Kandidat' }}</div>
                                        <span class="px-2 py-0.5 bg-white dark:bg-slate-800 font-semibold rounded border border-slate-200 dark:border-slate-700 text-[10px] uppercase text-slate-700 dark:text-slate-300">
                                            {{ is_object($app->status) ? $app->status->value : $app->status }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-600 dark:text-slate-400 mt-1 font-medium">{{ $app->job->title ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-normal mt-0.5">{{ $app->created_at->diffForHumans() }}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-dashed border-slate-200 dark:border-slate-700 text-xs text-slate-400 dark:text-slate-500 font-normal">
                            Belum ada berkas pelamar masuk.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
