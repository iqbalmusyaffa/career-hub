<x-public-layout>
    <!-- Premium Hero Section -->
    <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-900 text-white pt-16 pb-28 relative overflow-hidden">
        <div class="absolute inset-0 bg-blue-600/10 bg-cover bg-center"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-wider mb-6 border border-blue-400/30 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> Portal Rekrutmen Karir Terpercaya
            </span>
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-black tracking-tight leading-tight mb-4">
                Temukan Karir Impian <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-300">Terbaik Anda</span>
            </h1>
            <p class="text-blue-100/80 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                Jelajahi berbagai posisi strategis dari perusahaan berkembang hingga perusahaan multinasional di Indonesia.
            </p>
        </div>
    </div>

    <!-- Sticky Search & Filter Container -->
    <div class="-mt-16 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-12 relative z-20">
        <div class="bg-white rounded-3xl shadow-2xl p-5 sm:p-7 border border-gray-100">
            <form action="{{ route('jobs.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3.5">
                    <!-- Search Keyword -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Posisi / kata kunci..." class="block w-full pl-10 pr-3 py-2.5 border-gray-200 rounded-2xl focus:border-blue-600 focus:ring-blue-600 bg-gray-50/80 hover:bg-white transition text-xs md:text-sm font-medium">
                    </div>

                    <!-- Location Filter -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                        </div>
                        <select name="location" class="block w-full pl-10 pr-3 py-2.5 border-gray-200 rounded-2xl focus:border-blue-600 focus:ring-blue-600 bg-gray-50/80 hover:bg-white transition text-xs md:text-sm font-medium">
                            <option value="">Semua Lokasi</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>📍 {{ $loc }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Division Filter -->
                    <div>
                        <select name="division" class="block w-full px-3 py-2.5 border-gray-200 rounded-2xl focus:border-blue-600 focus:ring-blue-600 bg-gray-50/80 hover:bg-white transition text-xs md:text-sm font-medium">
                            <option value="">Semua Divisi</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div }}" {{ request('division') == $div ? 'selected' : '' }}>🏢 {{ $div }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Work Type Filter -->
                    <div>
                        <select name="work_type" class="block w-full px-3 py-2.5 border-gray-200 rounded-2xl focus:border-blue-600 focus:ring-blue-600 bg-gray-50/80 hover:bg-white transition text-xs md:text-sm font-medium">
                            <option value="">Semua Tipe Kerja</option>
                            <option value="Full-time" {{ request('work_type') == 'Full-time' ? 'selected' : '' }}>💻 Full-time</option>
                            <option value="Part-time" {{ request('work_type') == 'Part-time' ? 'selected' : '' }}>⏱️ Part-time</option>
                            <option value="Remote" {{ request('work_type') == 'Remote' ? 'selected' : '' }}>🏠 Remote</option>
                            <option value="Hybrid" {{ request('work_type') == 'Hybrid' ? 'selected' : '' }}>🏢 Hybrid</option>
                            <option value="Contract" {{ request('work_type') == 'Contract' ? 'selected' : '' }}>📝 Contract</option>
                            <option value="Internship" {{ request('work_type') == 'Internship' ? 'selected' : '' }}>🎓 Internship / Magang</option>
                        </select>
                    </div>

                    <!-- Sorting Filter -->
                    <div>
                        <select name="sort" class="block w-full px-3 py-2.5 border-gray-200 rounded-2xl focus:border-blue-600 focus:ring-blue-600 bg-gray-50/80 hover:bg-white transition text-xs md:text-sm font-medium">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>✨ Terbaru</option>
                            <option value="deadline_asc" {{ request('sort') == 'deadline_asc' ? 'selected' : '' }}>⏳ Deadline Terdekat</option>
                            <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>🔤 Judul (A-Z)</option>
                        </select>
                    </div>
                </div>

                <!-- Submit & Quick Tags -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-gray-100">
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="font-bold text-gray-500">Pencarian Populer:</span>
                        <a href="{{ route('jobs.index', ['search' => 'Backend']) }}" class="px-2.5 py-1 bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-600 rounded-lg transition font-medium">Backend</a>
                        <a href="{{ route('jobs.index', ['search' => 'React']) }}" class="px-2.5 py-1 bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-600 rounded-lg transition font-medium">React</a>
                        <a href="{{ route('jobs.index', ['search' => 'UI/UX']) }}" class="px-2.5 py-1 bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-600 rounded-lg transition font-medium">UI/UX</a>
                        <a href="{{ route('jobs.index', ['search' => 'Remote']) }}" class="px-2.5 py-1 bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-600 rounded-lg transition font-medium">Remote</a>
                    </div>

                    <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                        @if(request()->anyFilled(['search', 'location', 'division', 'work_type', 'sort']))
                            <a href="{{ route('jobs.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs flex items-center gap-1 transition">
                                ✕ Reset Filter
                            </a>
                        @endif
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-xl text-xs shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                            🔍 Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Job Cards Grid Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 mb-24">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-gray-200 pb-4">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Daftar Lowongan Pekerjaan</h2>
                <p class="text-xs text-gray-500 mt-1 font-medium">Menampilkan kesempatan karir yang sesuai dengan kualifikasi Anda</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-100">
                    {{ $jobs->total() }} Lowongan Ditemukan
                </span>
            </div>
        </div>

        @if($jobs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
                @foreach($jobs as $job)
                    @php
                        $isBookmarked = in_array($job->id, $savedJobIds ?? []);
                        $matchScore = auth()->check() && auth()->user()->candidateProfile ? $job->calculateMatchScore(auth()->user()->candidateProfile) : null;
                    @endphp
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 hover:shadow-2xl hover:border-blue-200 transition-all duration-300 flex flex-col group h-full relative overflow-hidden">
                        
                        <!-- Top Accent Banner -->
                        <div class="h-2 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                        <div class="p-6 flex-grow flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-black text-xl flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                                    {{ strtoupper(substr($job->title, 0, 1)) }}
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-extrabold rounded-full border border-emerald-100 shadow-2xs">
                                        {{ ucfirst($job->work_type) }}
                                    </span>
                                    @auth
                                        <form action="{{ route('jobs.bookmark', $job->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="{{ $isBookmarked ? 'Batal simpan' : 'Simpan lowongan' }}" class="p-2 rounded-xl hover:bg-amber-50 transition border border-transparent hover:border-amber-200">
                                                <svg class="w-5 h-5 {{ $isBookmarked ? 'fill-amber-400 text-amber-500' : 'fill-none text-gray-300 hover:text-amber-500' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5c0-1.1.9-2 2-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                            </div>
                            
                            <!-- Title & Division -->
                            <a href="{{ route('jobs.show', $job->id) }}" class="group-hover:text-blue-600 transition">
                                <h3 class="text-base sm:text-lg font-black text-gray-900 mb-1 leading-snug line-clamp-2">{{ $job->title }}</h3>
                            </a>
                            <p class="text-xs font-bold text-slate-700 mb-2 flex items-center gap-1.5">
                                <i class="fa-solid fa-building text-blue-600"></i> {{ $job->company_name ?? 'PT TechNova Asia Digital' }}
                            </p>

                            <div class="mb-3">
                                <span class="inline-flex items-center text-2xs font-extrabold text-blue-700 uppercase tracking-wider bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100">{{ $job->division }}</span>
                            </div>

                            <!-- Match Score Badge if Logged In Candidate -->
                            @if(!is_null($matchScore))
                                <div class="mb-3.5 p-2 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100 flex items-center justify-between text-xs">
                                    <span class="text-gray-600 font-semibold">Match Profile:</span>
                                    <span class="font-black text-blue-700 bg-white px-2 py-0.5 rounded-md shadow-2xs border border-blue-100">🎯 {{ $matchScore }}%</span>
                                </div>
                            @endif
                            
                            <!-- Location & Created Date -->
                            <div class="flex flex-wrap items-center text-xs text-gray-500 mb-4 gap-x-4 gap-y-1.5 font-medium">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    <span>{{ $job->location }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ $job->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            
                            <!-- Description excerpt -->
                            <p class="text-gray-600 text-xs leading-relaxed line-clamp-2 mb-3 flex-grow">
                                {{ Str::limit(strip_tags($job->description), 100) }}
                            </p>

                            <!-- Benefits Summary Badge -->
                            @if($job->benefits)
                                <div class="mb-3.5 p-2 bg-amber-50/70 border border-amber-200/60 rounded-xl text-2xs text-amber-800 font-semibold truncate flex items-center gap-1.5">
                                    <i class="fa-solid fa-gift text-amber-600"></i>
                                    <span class="truncate">Benefit: {{ $job->benefits }}</span>
                                </div>
                            @endif

                            <!-- Quota Indicator if Set -->
                            @if($job->quota)
                                <div class="mb-4">
                                    <div class="flex justify-between items-center text-2xs font-bold mb-1">
                                        <span class="text-gray-500">Kuota Pelamar:</span>
                                        <span class="{{ $job->isQuotaFull() ? 'text-red-600' : 'text-indigo-600' }}">{{ $job->applications()->count() }} / {{ $job->quota }}</span>
                                    </div>
                                    <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $job->isQuotaFull() ? 'bg-red-500' : 'bg-indigo-600' }}" style="width: {{ min(100, round(($job->applications()->count() / $job->quota) * 100)) }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Footer Info & Apply Button -->
                        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/80 rounded-b-3xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="min-w-0">
                                <span class="text-[10px] uppercase tracking-wider text-gray-400 font-extrabold block">Gaji Offer</span>
                                <span class="text-xs font-black text-gray-900 block truncate">{{ $job->salary ?? 'Negosiasi' }}</span>
                            </div>
                            <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-sm hover:shadow transition shrink-0 w-full sm:w-auto text-center">
                                Detail Posisi &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-12">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-3xl shadow-sm border border-gray-100 max-w-2xl mx-auto">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-2xl">
                    🔍
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-1">Tidak ada lowongan ditemukan</h3>
                <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">Coba sesuaikan kata kunci, lokasi, atau reset filter untuk melihat lowongan lain yang tersedia.</p>
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-xl shadow-md text-xs transition">
                    ✕ Reset Filter Pencarian
                </a>
            </div>
        @endif
    </div>
</x-public-layout>
