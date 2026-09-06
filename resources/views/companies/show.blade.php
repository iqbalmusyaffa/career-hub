<x-public-layout>
    <div class="bg-slate-50/50 py-10 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Breadcrumb Navigation -->
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 text-xs font-medium text-slate-500">
                    <li>
                        <a href="{{ route('companies.index') }}" class="hover:text-blue-600 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-building text-slate-400 text-xs"></i> Perusahaan Terverifikasi
                        </a>
                    </li>
                    <li>
                        <span class="text-slate-300">/</span>
                    </li>
                    <li class="text-slate-900 font-semibold truncate max-w-xs md:max-w-md">
                        {{ $companyName }}
                    </li>
                </ol>
            </nav>

            <!-- Company Profile Banner Card -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-xs border border-slate-200/90 space-y-0">
                <!-- Cover Image Banner Header -->
                @if(isset($companyProfile->cover_image_path) && $companyProfile->cover_image_path)
                    <div class="w-full h-48 sm:h-64 relative bg-slate-100 overflow-hidden">
                        <img src="{{ Storage::url($companyProfile->cover_image_path) }}" alt="{{ $companyName }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    </div>
                @endif

                <div class="p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-100 pb-6">
                        <div class="flex items-center gap-4 min-w-0">
                            @if(isset($companyProfile->logo_path) && $companyProfile->logo_path)
                                <img src="{{ Storage::url($companyProfile->logo_path) }}" alt="{{ $companyName }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border border-slate-200 shadow-2xs shrink-0">
                            @else
                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center font-bold text-2xl border border-blue-100 shrink-0">
                                    {{ strtoupper(substr($companyName, 0, 2)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 truncate">{{ $companyName }}</h1>
                                @if(isset($companyProfile->tagline) && $companyProfile->tagline)
                                    <p class="text-xs text-blue-600 font-medium mt-0.5 tracking-wide italic">"{{ $companyProfile->tagline }}"</p>
                                @endif
                                <p class="text-xs text-slate-500 font-normal mt-1 truncate">
                                    {{ $companyProfile->industry ?? 'Software & Technology' }} • {{ $companyProfile->company_size ?? '50-200 Karyawan' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 shrink-0">
                            <span class="px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-xl border border-emerald-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> Perusahaan Terverifikasi
                            </span>
                            <span class="px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-semibold rounded-xl border border-blue-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-briefcase text-blue-600 text-xs"></i> {{ $jobs->count() }} Lowongan Aktif
                            </span>
                        </div>
                    </div>

                    <div class="px-1">
                        <h3 class="text-xs font-semibold uppercase text-slate-500 tracking-wider mb-2">Tentang Perusahaan</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal break-words">
                            {{ $companyProfile->description ?? ($companyName . ' adalah perusahaan terpercaya yang membuka berbagai kesempatan karir bagi talenta profesional terbaik di Indonesia.') }}
                        </p>
                    </div>

                    <!-- Workplace Culture & Benefits Section -->
                    @if(isset($companyProfile->culture_description) || (isset($companyProfile->benefits) && is_array($companyProfile->benefits) && count($companyProfile->benefits) > 0))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                            @if($companyProfile->culture_description)
                                <div class="space-y-2">
                                    <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                        <i class="fa-solid fa-users text-blue-600"></i> Budaya & Suasana Kerja
                                    </h4>
                                    <p class="text-xs text-slate-600 leading-relaxed font-normal bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                                        {{ $companyProfile->culture_description }}
                                    </p>
                                </div>
                            @endif

                            @if(is_array($companyProfile->benefits) && count($companyProfile->benefits) > 0)
                                <div class="space-y-2">
                                    <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                        <i class="fa-solid fa-gift text-emerald-600"></i> Tunjangan & Benefit Karyawan
                                    </h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                        @foreach($companyProfile->benefits as $benefit)
                                            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/80 flex items-center gap-2 text-slate-700">
                                                <i class="fa-solid fa-circle-check text-emerald-600 text-xs shrink-0"></i>
                                                <span class="font-medium text-xs">{{ $benefit }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2.5 pt-4 text-xs font-normal text-slate-500 border-t border-slate-100">
                        @if(isset($companyProfile->website) && $companyProfile->website)
                            <a href="{{ $companyProfile->website }}" target="_blank" class="hover:text-blue-600 transition flex items-center gap-1.5">
                                <i class="fa-solid fa-globe text-slate-400 text-xs"></i> {{ $companyProfile->website }}
                            </a>
                        @endif
                        @if(isset($companyProfile->phone) && $companyProfile->phone)
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-phone text-slate-400 text-xs"></i> {{ $companyProfile->phone }}
                            </span>
                        @endif
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-slate-400 text-xs"></i> {{ $companyProfile->address ?? 'Jakarta, Indonesia' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Active Job Listings Section -->
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-200 pb-4 gap-2">
                    <div>
                        <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider block">Lowongan Perusahaan</span>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight mt-0.5">
                            Lowongan Kerja Aktif di {{ $companyName }} ({{ $jobs->count() }})
                        </h2>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition flex items-center gap-1">
                        Lihat Semua Lowongan &rarr;
                    </a>
                </div>

                @if($jobs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($jobs as $job)
                            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 hover:border-slate-300 hover:shadow-md transition-all duration-200 flex flex-col group h-full relative overflow-hidden">
                                <div class="p-6 flex-grow flex flex-col">
                                    
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white font-bold text-base flex items-center justify-center border border-slate-800">
                                            {{ strtoupper(substr($job->title, 0, 1)) }}
                                        </div>
                                        <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-md border border-slate-200">
                                            {{ ucfirst($job->work_type) }}
                                        </span>
                                    </div>

                                    <!-- Title & Division -->
                                    <a href="{{ route('jobs.show', $job) }}" class="group-hover:text-blue-600 transition mb-1">
                                        <h3 class="text-base font-bold text-slate-900 leading-snug line-clamp-2">{{ $job->title }}</h3>
                                    </a>

                                    <div class="mb-3">
                                        <span class="inline-flex items-center text-xs font-medium text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">
                                            {{ $job->division }}
                                        </span>
                                    </div>

                                    <!-- Location & Date -->
                                    <div class="flex flex-wrap items-center text-xs text-slate-500 mb-4 gap-x-4 gap-y-1.5 font-normal">
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-location-dot text-slate-400 text-xs"></i>
                                            <span>{{ $job->location }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-clock text-slate-400 text-xs"></i>
                                            <span>{{ $job->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <!-- Description excerpt -->
                                    <p class="text-slate-600 text-xs leading-relaxed line-clamp-2 mb-3 flex-grow font-normal">
                                        {{ Str::limit(strip_tags($job->description), 90) }}
                                    </p>
                                </div>

                                <!-- Card Footer -->
                                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <span class="text-[11px] text-slate-400 font-medium block">Gaji Ditawarkan</span>
                                        <span class="text-xs font-semibold text-slate-900 block truncate">{{ $job->salary ?? 'Negosiasi' }}</span>
                                    </div>
                                    <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition shrink-0 w-full sm:w-auto text-center">
                                        Detail Posisi &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 space-y-3 shadow-xs">
                        <div class="w-14 h-14 bg-slate-100 text-slate-500 rounded-2xl flex items-center justify-center text-xl mx-auto font-bold border border-slate-200">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Belum Ada Lowongan Aktif</h3>
                        <p class="text-xs text-slate-500">Saat ini {{ $companyName }} belum membuka lowongan pekerjaan tambahan.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-public-layout>
