<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="px-2.5 py-0.5 bg-amber-500 text-white text-[10px] font-bold rounded-md uppercase tracking-wider shadow-2xs">Company Owner</span>
                    Eksekutif Dashboard Perusahaan
                </h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Ringkasan performa rekrutmen, statistik pelamar, dan legalitas bisnis {{ $companyProfile->company_name }}.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.jobs.create') }}" class="bg-slate-900 hover:bg-black text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-plus text-amber-400"></i> Pasang Lowongan Baru
                </a>
                <a href="{{ route('admin.company.profile.edit') }}" class="bg-white hover:bg-slate-100 text-slate-800 font-bold py-2.5 px-4 rounded-xl text-xs transition border border-slate-300 shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-building-user text-amber-500"></i> Profil & Legalitas SIUP
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/70 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Company Status Banner Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/90 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-slate-900 text-white font-black text-2xl flex items-center justify-center border border-slate-800 shadow-md shrink-0">
                        {{ strtoupper(substr($companyProfile->company_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-xl font-black text-slate-900 tracking-tight">{{ $companyProfile->company_name }}</h3>
                            @if($companyProfile->is_verified)
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-800 text-3xs font-extrabold rounded-lg border border-emerald-200 flex items-center gap-1.5 shadow-2xs">
                                    <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> Verified Official Perusahaan
                                </span>
                            @else
                                <span class="px-3 py-1 bg-amber-50 text-amber-900 text-3xs font-extrabold rounded-lg border border-amber-200 shadow-2xs">
                                    ⏳ Menunggu Verifikasi SIUP / NIB
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-1 font-semibold"><i class="fa-solid fa-industry text-slate-400 mr-1"></i> {{ $companyProfile->industry ?? 'Industri Umum' }} &bull; <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $companyProfile->address ?? 'Lokasi Belum Diatur' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.company.profile.edit') }}" class="px-4 py-2.5 bg-amber-50 text-amber-900 font-extrabold rounded-xl text-xs border border-amber-200 hover:bg-amber-100 transition shadow-2xs flex items-center gap-2">
                        <i class="fa-solid fa-file-shield text-amber-600 text-sm"></i> Kelola Dokumen SIUP / NIB
                    </a>
                </div>
            </div>

            <!-- Executive Stat Cards (4 Cards Grid) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Active Jobs -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-3 group">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Lowongan Aktif</span>
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-black border border-blue-100 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $activeJobsCount }}</div>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5">Dari {{ $companyJobsCount }} Total Lowongan</p>
                    </div>
                </div>

                <!-- Total Applicants -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-3 group">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Total Pelamar Masuk</span>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-black border border-indigo-100 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-indigo-600 tracking-tight">{{ number_format($totalCompanyApplications) }}</div>
                        <p class="text-xs text-indigo-700 font-extrabold mt-0.5">{{ $pendingCount }} Menunggu Review</p>
                    </div>
                </div>

                <!-- Wawancara Active -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-3 group">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Tahap Wawancara</span>
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-black border border-amber-100 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-amber-600 tracking-tight">{{ $interviewCount }}</div>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5">Dalam Proses Interview</p>
                    </div>
                </div>

                <!-- Hired Rate -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-3 group">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Kandidat Diterima</span>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-black border border-emerald-100 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-emerald-600 tracking-tight">{{ $hiredCount }}</div>
                        <p class="text-xs text-emerald-700 font-extrabold mt-0.5">Lolos Rekrutmen Perusahaan</p>
                    </div>
                </div>
            </div>

            <!-- Content Grid: Active Jobs & Recent Applicants -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Active Jobs (2 Cols) -->
                <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/90 p-6 space-y-6 lg:col-span-2">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-black text-base text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-blue-600"></i> Performa Lowongan Pekerjaan Perusahaan
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5 font-medium">Daftar lowongan kerja yang dipublikasikan oleh perusahaan.</p>
                        </div>
                        <a href="{{ route('admin.jobs.index') }}" class="text-xs font-black text-blue-600 hover:underline">Kelola Lowongan &rarr;</a>
                    </div>

                    @if(count($companyJobs) > 0)
                        <div class="space-y-3">
                            @foreach($companyJobs->take(5) as $job)
                                <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-200/90 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                    <div>
                                        <a href="{{ route('admin.jobs.show', $job->id) }}" class="font-black text-slate-900 text-sm hover:text-blue-600 transition">{{ $job->title }}</a>
                                        <p class="text-slate-500 mt-0.5 font-medium">{{ $job->division ?? 'Umum' }} &bull; {{ $job->location }} &bull; <span class="font-extrabold text-blue-600">{{ $job->work_type }}</span></p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <div class="font-black text-slate-900 text-sm">{{ $job->applications_count }} Pelamar</div>
                                            <div class="text-3xs text-slate-400 font-medium">Kuota: {{ $job->quota ?? 'Tak Terbatas' }}</div>
                                        </div>
                                        <a href="{{ route('admin.jobs.edit', $job->id) }}" class="px-3 py-1.5 bg-white border border-slate-300 text-slate-800 font-extrabold rounded-lg hover:bg-slate-100 transition text-3xs shadow-2xs">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <p class="text-xs text-slate-500 font-medium mb-3">Belum ada lowongan dibuka untuk perusahaan ini.</p>
                            <a href="{{ route('admin.jobs.create') }}" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-xl text-xs shadow-md">
                                + Pasang Lowongan Pertama
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Recent Applicants -->
                <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/90 p-6 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-black text-base text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-amber-500"></i> Pelamar Terbaru
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5 font-medium">Pendaftar baru di perusahaan Anda.</p>
                        </div>
                        <a href="{{ route('admin.applications.index') }}" class="text-xs font-black text-blue-600 hover:underline">Semua Pelamar &rarr;</a>
                    </div>

                    @if(count($recentApplications) > 0)
                        <div class="space-y-3">
                            @foreach($recentApplications as $app)
                                <a href="{{ route('admin.applications.show', $app->id) }}" class="block p-4 bg-slate-50/80 hover:bg-slate-100 rounded-2xl border border-slate-200/80 transition group">
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="font-black text-slate-900 group-hover:text-blue-600 transition">{{ $app->user->name ?? 'Kandidat' }}</div>
                                        <span class="px-2 py-0.5 bg-white font-extrabold rounded border border-slate-200 text-3xs uppercase text-slate-700">{{ is_object($app->status) ? $app->status->value : $app->status }}</span>
                                    </div>
                                    <p class="text-3xs text-slate-500 mt-1 font-bold">{{ $app->job->title ?? '-' }}</p>
                                    <p class="text-3xs text-slate-400 mt-0.5 font-medium">{{ $app->created_at->diffForHumans() }}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-xs text-slate-400 font-medium">
                            Belum ada pelamar masuk.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
