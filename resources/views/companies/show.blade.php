<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-building text-blue-600"></i> Profil & Lowongan Kerja Perusahaan
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Lihat seluruh lowongan karir aktif yang dibuka oleh <strong>{{ $companyName }}</strong></p>
            </div>
            <a href="{{ route('jobs.index') }}" class="bg-white hover:bg-slate-100 text-slate-700 font-bold py-2 px-4 rounded-xl border border-slate-200 text-xs transition">
                &larr; Kembali ke Cari Lowongan
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Company Profile Banner Card (Dark Premium Theme matching Screenshot) -->
            <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-800 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-800 pb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center font-black text-3xl text-white shadow-lg shrink-0">
                            🏢
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">{{ $companyName }}</h1>
                            <p class="text-xs text-blue-300 font-semibold mt-1">
                                {{ $companyProfile->industry ?? 'Software & Technology' }} • {{ $companyProfile->employee_count ?? '50-200 Karyawan' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="px-4 py-2 bg-emerald-500/20 text-emerald-300 text-xs font-black rounded-2xl border border-emerald-500/30 flex items-center gap-1.5 shadow-2xs">
                            <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i> Perusahaan Terverifikasi
                        </span>
                        <span class="px-4 py-2 bg-blue-500/20 text-blue-300 text-xs font-black rounded-2xl border border-blue-500/30">
                            💼 {{ $jobs->count() }} Lowongan Aktif
                        </span>
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                    {{ $companyProfile->description ?? ($companyName . ' adalah perusahaan terpercaya yang menghubungkan talenta profesional terbaik dengan inovasi industri modern.') }}
                </p>

                <div class="flex flex-wrap items-center gap-x-6 gap-y-3 pt-2 text-xs font-semibold text-slate-400 border-t border-slate-800/80">
                    <a href="https://technova-asia.com" target="_blank" class="hover:text-blue-400 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-globe text-blue-400"></i> technova-asia.com
                    </a>
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-phone text-emerald-400"></i> 021-55443322
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-rose-400"></i> {{ $companyProfile->address ?? 'Jakarta Selatan, DKI Jakarta' }}
                    </span>
                </div>
            </div>

            <!-- Active Job Listings Section -->
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-briefcase text-blue-600"></i> Lowongan Kerja Aktif di {{ $companyName }} ({{ $jobs->count() }})
                    </h3>
                </div>

                @if($jobs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($jobs as $job)
                            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs hover:shadow-md transition flex flex-col justify-between space-y-4">
                                <div class="space-y-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-3xs font-black rounded-full uppercase border border-blue-100">
                                            🏢 {{ $job->division }}
                                        </span>
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-3xs font-black rounded-full uppercase border border-emerald-100">
                                            {{ ucfirst($job->work_type) }}
                                        </span>
                                    </div>

                                    <div>
                                        <h4 class="font-extrabold text-slate-900 text-base hover:text-blue-600 transition">
                                            <a href="{{ route('jobs.show', $job->id) }}">
                                                {{ $job->title }}
                                            </a>
                                        </h4>
                                        <p class="text-xs text-slate-500 font-medium mt-1"><i class="fa-solid fa-location-dot text-rose-500"></i> {{ $job->location }}</p>
                                    </div>

                                    <div class="text-xs font-bold text-slate-700">
                                        💰 {{ $job->salary ?? 'Gaji Negosiasi' }}
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-3xs text-slate-400 font-medium">
                                        Diposting {{ $job->created_at->diffForHumans() }}
                                    </span>
                                    <a href="{{ route('jobs.show', $job->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-2xs transition">
                                        Detail & Melamar &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 space-y-3">
                        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center text-2xl mx-auto">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-800">Belum Ada Lowongan Aktif</h3>
                        <p class="text-xs text-slate-500">Saat ini {{ $companyName }} belum memiliki lowongan pekerjaan aktif tambahan.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
