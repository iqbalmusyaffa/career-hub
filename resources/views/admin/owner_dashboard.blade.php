<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="px-2.5 py-0.5 bg-amber-500 text-white text-3xs font-black rounded-lg uppercase tracking-wider">Company Owner</span>
                    Eksekutif Dashboard Perusahaan
                </h2>
                <p class="text-xs text-slate-500 mt-1">Ringkasan performa rekrutmen, statistik pelamar, dan legalitas bisnis {{ $companyProfile->company_name }}.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.jobs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Pasang Lowongan Baru
                </a>
                <a href="{{ route('admin.company.profile.edit') }}" class="bg-slate-900 hover:bg-black text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs flex items-center gap-1.5">
                    <i class="fa-solid fa-building-user text-amber-400"></i> Profil & Legalitas
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Company Status Banner Card -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-slate-100 text-slate-800 font-black text-xl flex items-center justify-center border border-slate-200 shrink-0">
                        {{ strtoupper(substr($companyProfile->company_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">{{ $companyProfile->company_name }}</h3>
                            @if($companyProfile->is_verified)
                                <span class="px-2.5 py-0.5 bg-blue-50 text-blue-800 text-3xs font-bold rounded-md border border-blue-200 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check text-blue-600"></i> Verified Official
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 bg-amber-50 text-amber-800 text-3xs font-semibold rounded-md border border-amber-200">
                                    ⏳ Menunggu Verifikasi SIUP
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-1 font-medium">{{ $companyProfile->industry ?? 'Industri Umum' }} • {{ $companyProfile->address ?? 'Lokasi Belum Diatur' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.company.profile.edit') }}" class="px-4 py-2 bg-amber-50 text-amber-800 font-semibold rounded-xl text-xs border border-amber-200 hover:bg-amber-100 transition">
                        <i class="fa-solid fa-file-shield mr-1"></i> Kelola Legalitas SIUP/NIB
                    </a>
                </div>
            </div>

            <!-- Executive Stat Cards (4 Cards) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Active Jobs -->
                <div class="bg-white p-6 rounded-2xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Lowongan Aktif</span>
                        <i class="fa-solid fa-briefcase text-blue-600 text-base"></i>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $activeJobsCount }}</div>
                    <p class="text-xs text-slate-500 font-medium">Dari {{ $companyJobsCount }} Total Lowongan</p>
                </div>

                <!-- Total Applicants -->
                <div class="bg-white p-6 rounded-2xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pelamar Masuk</span>
                        <i class="fa-solid fa-users text-indigo-600 text-base"></i>
                    </div>
                    <div class="text-3xl font-extrabold text-indigo-600 tracking-tight">{{ number_format($totalCompanyApplications) }}</div>
                    <p class="text-xs text-indigo-700 font-semibold">{{ $pendingCount }} Menunggu Review</p>
                </div>

                <!-- Wawancara Active -->
                <div class="bg-white p-6 rounded-2xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tahap Wawancara</span>
                        <i class="fa-solid fa-comments text-amber-500 text-base"></i>
                    </div>
                    <div class="text-3xl font-extrabold text-amber-600 tracking-tight">{{ $interviewCount }}</div>
                    <p class="text-xs text-slate-500 font-medium">Dalam Proses Interview</p>
                </div>

                <!-- Hired Rate -->
                <div class="bg-white p-6 rounded-2xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Kandidat Diterima</span>
                        <i class="fa-solid fa-user-check text-emerald-600 text-base"></i>
                    </div>
                    <div class="text-3xl font-extrabold text-emerald-600 tracking-tight">{{ $hiredCount }}</div>
                    <p class="text-xs text-emerald-700 font-semibold">Lolos Rekrutmen Perusahaan</p>
                </div>
            </div>

            <!-- Content Grid: Active Jobs & Recent Applicants -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Active Jobs (2 Cols) -->
                <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/80 p-6 space-y-6 lg:col-span-2">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-extrabold text-base text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-blue-600"></i> Performa Lowongan Pekerjaan Perusahaan
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Daftar lowongan kerja yang dipublikasikan oleh perusahaan.</p>
                        </div>
                        <a href="{{ route('admin.jobs.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Kelola Lowongan &rarr;</a>
                    </div>

                    @if(count($companyJobs) > 0)
                        <div class="space-y-3">
                            @foreach($companyJobs->take(5) as $job)
                                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                    <div>
                                        <a href="{{ route('admin.jobs.show', $job->id) }}" class="font-bold text-slate-900 text-sm hover:text-blue-600 transition">{{ $job->title }}</a>
                                        <p class="text-slate-500 mt-0.5">{{ $job->division }} • {{ $job->location }} • <span class="font-semibold text-blue-600">{{ $job->work_type }}</span></p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <div class="font-extrabold text-slate-900 text-sm">{{ $job->applications_count }} Pelamar</div>
                                            <div class="text-3xs text-slate-400">Kuota: {{ $job->quota ?? 'Tak Terbatas' }}</div>
                                        </div>
                                        <a href="{{ route('admin.jobs.edit', $job->id) }}" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-100 transition text-3xs">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <p class="text-xs text-slate-500 font-medium mb-3">Belum ada lowongan dibuka untuk perusahaan ini.</p>
                            <a href="{{ route('admin.jobs.create') }}" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-xl text-xs shadow-2xs">
                                + Pasang Lowongan Pertama
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Recent Applicants -->
                <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/80 p-6 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-extrabold text-base text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-amber-500"></i> Pelamar Terbaru
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Pendaftar baru di perusahaan Anda.</p>
                        </div>
                        <a href="{{ route('admin.applications.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Semua Pelamar &rarr;</a>
                    </div>

                    @if(count($recentApplications) > 0)
                        <div class="space-y-3">
                            @foreach($recentApplications as $app)
                                <a href="{{ route('admin.applications.show', $app->id) }}" class="block p-3.5 bg-slate-50 hover:bg-slate-100/80 rounded-xl border border-slate-200/80 transition group">
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="font-extrabold text-slate-900 group-hover:text-blue-600 transition">{{ $app->user->name ?? 'Kandidat' }}</div>
                                        <span class="px-2 py-0.5 bg-white font-bold rounded border border-slate-200 text-3xs uppercase text-slate-700">{{ $app->status }}</span>
                                    </div>
                                    <p class="text-3xs text-slate-500 mt-1 font-medium">{{ $app->job->title ?? '-' }}</p>
                                    <p class="text-3xs text-slate-400 mt-0.5">{{ $app->created_at->diffForHumans() }}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200 text-xs text-slate-400">
                            Belum ada pelamar masuk.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
