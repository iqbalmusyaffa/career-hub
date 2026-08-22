<x-public-layout>
    <div class="bg-slate-50/50 py-10 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Breadcrumb Navigation -->
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-500">
                    <li>
                        <a href="{{ route('companies.index') }}" class="hover:text-slate-900 transition flex items-center gap-1">
                            <i class="fa-solid fa-building text-slate-400 text-xs"></i> Perusahaan Terverifikasi
                        </a>
                    </li>
                    <li>
                        <span class="text-slate-300">/</span>
                    </li>
                    <li class="text-slate-900 font-bold truncate max-w-xs md:max-w-md">
                        {{ $companyName }}
                    </li>
                </ol>
            </nav>

            <!-- Company Profile Banner Card (Enterprise Dark Slate Theme) -->
            <div class="bg-slate-900 text-white rounded-3xl overflow-hidden shadow-md border border-slate-800 space-y-0">
                <!-- Cover Image Banner Header -->
                @if(isset($companyProfile->cover_image_path) && $companyProfile->cover_image_path)
                    <div class="w-full h-48 sm:h-64 relative bg-slate-950 overflow-hidden">
                        <img src="{{ Storage::url($companyProfile->cover_image_path) }}" alt="{{ $companyName }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
                    </div>
                @endif

                <div class="p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-800 pb-6">
                        <div class="flex items-center gap-4 min-w-0">
                            @if(isset($companyProfile->logo_path) && $companyProfile->logo_path)
                                <img src="{{ Storage::url($companyProfile->logo_path) }}" alt="{{ $companyName }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border-2 border-slate-700 shadow-md shrink-0">
                            @else
                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-800 text-slate-100 rounded-2xl flex items-center justify-center font-bold text-2xl border-2 border-slate-700 shrink-0">
                                    {{ strtoupper(substr($companyName, 0, 2)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white truncate">{{ $companyName }}</h1>
                                @if(isset($companyProfile->tagline) && $companyProfile->tagline)
                                    <p class="text-xs text-blue-400 font-bold mt-0.5 tracking-wide italic">"{{ $companyProfile->tagline }}"</p>
                                @endif
                                <p class="text-xs text-slate-300 font-medium mt-1 truncate">
                                    {{ $companyProfile->industry ?? 'Software & Technology' }} • {{ $companyProfile->company_size ?? '50-200 Karyawan' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 shrink-0">
                            <span class="px-3.5 py-1.5 bg-emerald-500/20 text-emerald-300 text-xs font-extrabold rounded-xl border border-emerald-500/30 flex items-center gap-1.5 shadow-2xs">
                                <i class="fa-solid fa-circle-check text-emerald-400 text-xs"></i> Perusahaan Terverifikasi
                            </span>
                            <span class="px-3.5 py-1.5 bg-blue-500/20 text-blue-300 text-xs font-extrabold rounded-xl border border-blue-500/30 flex items-center gap-1.5 shadow-2xs">
                                <i class="fa-solid fa-briefcase text-blue-400 text-xs"></i> {{ $jobs->count() }} Lowongan Aktif
                            </span>
                        </div>
                    </div>

                    <div class="px-1">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-2">Tentang Perusahaan</h3>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-medium break-words">
                            {{ $companyProfile->description ?? ($companyName . ' adalah penyedia solusi teknologi terkemuka dan transformasi digital terpercaya di Indonesia.') }}
                        </p>
                    </div>

                    <!-- Workplace Culture & Benefits Section -->
                    @if(isset($companyProfile->culture_description) || (isset($companyProfile->benefits) && is_array($companyProfile->benefits) && count($companyProfile->benefits) > 0))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-800">
                            @if($companyProfile->culture_description)
                                <div class="space-y-2">
                                    <h4 class="text-xs font-black uppercase tracking-wider text-amber-400 flex items-center gap-1.5">
                                        <i class="fa-solid fa-users-rays"></i> Budaya & Suasana Kerja
                                    </h4>
                                    <p class="text-xs text-slate-300 leading-relaxed font-medium bg-slate-800/60 p-4 rounded-2xl border border-slate-800">
                                        {{ $companyProfile->culture_description }}
                                    </p>
                                </div>
                            @endif

                            @if(is_array($companyProfile->benefits) && count($companyProfile->benefits) > 0)
                                <div class="space-y-2">
                                    <h4 class="text-xs font-black uppercase tracking-wider text-emerald-400 flex items-center gap-1.5">
                                        <i class="fa-solid fa-gift"></i> Tunjangan & Benefit Karyawan
                                    </h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                        @foreach($companyProfile->benefits as $benefit)
                                            <div class="p-2.5 bg-slate-800/80 rounded-xl border border-slate-700/80 flex items-center gap-2 text-slate-200">
                                                <i class="fa-solid fa-circle-check text-emerald-400 text-xs shrink-0"></i>
                                                <span class="font-bold text-3xs">{{ $benefit }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2.5 pt-4 text-xs font-medium text-slate-400 border-t border-slate-800">
                        @if(isset($companyProfile->website) && $companyProfile->website)
                            <a href="{{ $companyProfile->website }}" target="_blank" class="hover:text-white transition flex items-center gap-1.5">
                                <i class="fa-solid fa-globe text-blue-400 text-xs"></i> {{ $companyProfile->website }}
                            </a>
                        @endif
                        @if(isset($companyProfile->phone) && $companyProfile->phone)
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-phone text-emerald-400 text-xs"></i> {{ $companyProfile->phone }}
                            </span>
                        @endif
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-rose-400 text-xs"></i> {{ $companyProfile->address ?? 'Gedung Cyber Tower Lt. 12, Jl. HR Rasuna Said, Jakarta Selatan' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Active Job Listings Section -->
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-200 pb-4 gap-2">
                    <div>
                        <span class="text-3xs font-bold text-slate-500 uppercase tracking-widest block">Lowongan Perusahaan</span>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight mt-0.5">
                            Lowongan Kerja Aktif di {{ $companyName }} ({{ $jobs->count() }})
                        </h2>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="text-xs font-bold text-slate-700 hover:text-slate-900 transition flex items-center gap-1">
                        Lihat Semua Lowongan &rarr;
                    </a>
                </div>

                @if($jobs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($jobs as $job)
                            <div class="bg-white rounded-2xl shadow-2xs border border-slate-200 hover:border-slate-400 transition-all duration-200 flex flex-col group h-full relative overflow-hidden">
                                <div class="p-6 flex-grow flex flex-col">
                                    
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white font-bold text-base flex items-center justify-center border border-slate-900">
                                            {{ strtoupper(substr($job->title, 0, 1)) }}
                                        </div>
                                        <span class="px-2.5 py-0.5 bg-slate-100 text-slate-800 text-3xs font-bold rounded-md border border-slate-200 uppercase">
                                            {{ ucfirst($job->work_type) }}
                                        </span>
                                    </div>

                                    <!-- Title & Division -->
                                    <a href="{{ route('jobs.show', $job) }}" class="group-hover:text-slate-700 transition mb-1">
                                        <h3 class="text-base font-bold text-slate-900 leading-snug line-clamp-2">{{ $job->title }}</h3>
                                    </a>

                                    <div class="mb-3">
                                        <span class="inline-flex items-center text-3xs font-bold text-slate-700 uppercase tracking-wider bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">
                                            {{ $job->division }}
                                        </span>
                                    </div>

                                    <!-- Location & Date -->
                                    <div class="flex flex-wrap items-center text-xs text-slate-500 mb-4 gap-x-4 gap-y-1.5 font-medium">
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-location-dot text-slate-500"></i>
                                            <span>{{ $job->location }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-clock text-slate-400"></i>
                                            <span>{{ $job->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <!-- Description excerpt -->
                                    <p class="text-slate-600 text-xs leading-relaxed line-clamp-2 mb-3 flex-grow">
                                        {{ Str::limit(strip_tags($job->description), 90) }}
                                    </p>
                                </div>

                                <!-- Card Footer -->
                                <div class="px-5 py-4 border-t border-slate-200 bg-slate-50/50 rounded-b-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <span class="text-3xs uppercase tracking-wider text-slate-400 font-bold block">Gaji Offer</span>
                                        <span class="text-xs font-bold text-slate-900 block truncate">{{ $job->salary ?? 'Negosiasi' }}</span>
                                    </div>
                                    <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center justify-center px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl border border-slate-900 shadow-2xs transition shrink-0 w-full sm:w-auto text-center">
                                        Detail Posisi &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 space-y-3 shadow-2xs">
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
