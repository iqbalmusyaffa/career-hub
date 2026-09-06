<x-public-layout>
    <!-- Hero Section -->
    <div class="bg-slate-900 text-white pt-12 pb-24 relative overflow-hidden border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-slate-800 text-slate-300 text-3xs sm:text-xs font-bold uppercase tracking-wider mb-6 border border-slate-700">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Portal Rekrutmen Karir Terpercaya
            </span>
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold tracking-tight leading-tight mb-4 text-white">
                Temukan Karir Impian <span class="text-slate-200">Terbaik Anda</span>
            </h1>
            <p class="text-slate-300 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
                Jelajahi berbagai posisi strategis dari perusahaan berkembang hingga perusahaan multinasional di Indonesia.
            </p>
        </div>
    </div>

    <!-- Sticky Search & Filter Container -->
    <div class="-mt-14 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-12 relative z-20">
        <div class="bg-white rounded-2xl shadow-sm p-5 sm:p-7 border border-slate-200">
            <form action="{{ route('jobs.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-7 gap-3">
                    <!-- Search Keyword -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Posisi / kata kunci..." class="block w-full pl-10 pr-3 py-2 border-slate-300 rounded-xl focus:border-blue-500 focus:ring-blue-500 bg-slate-50/50 hover:bg-white transition text-xs font-medium text-slate-800 placeholder:text-slate-400">
                    </div>

                    <!-- Major Requirement Filter -->
                    <x-indonesia-majors-select name="major" value="{{ request('major') }}" placeholder="Semua Jurusan..." />

                    <!-- Location Filter -->
                    <x-indonesia-cities-select name="location" value="{{ request('location') }}" placeholder="Semua Lokasi..." />

                    <!-- Division Filter -->
                    <div>
                        <select name="division" class="block w-full px-3 py-2 border-slate-300 rounded-xl focus:border-blue-500 focus:ring-blue-500 bg-slate-50/50 hover:bg-white transition text-xs font-medium text-slate-700">
                            <option value="">Semua Divisi</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div }}" {{ request('division') == $div ? 'selected' : '' }}>{{ $div }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Work Type Filter -->
                    <div>
                        <select name="work_type" class="block w-full px-3 py-2 border-slate-300 rounded-xl focus:border-blue-500 focus:ring-blue-500 bg-slate-50/50 hover:bg-white transition text-xs font-medium text-slate-700">
                            <option value="">Semua Tipe Kerja</option>
                            <option value="Full-time" {{ request('work_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="Part-time" {{ request('work_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="Remote" {{ request('work_type') == 'Remote' ? 'selected' : '' }}>Remote</option>
                            <option value="Hybrid" {{ request('work_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            <option value="Contract" {{ request('work_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                            <option value="Internship" {{ request('work_type') == 'Internship' ? 'selected' : '' }}>Magang (Internship)</option>
                        </select>
                    </div>

                    <!-- Salary Range Filter -->
                    <div>
                        <select name="salary_range" class="block w-full px-3 py-2 border-slate-300 rounded-xl focus:border-blue-500 focus:ring-blue-500 bg-slate-50/50 hover:bg-white transition text-xs font-medium text-slate-700">
                            <option value="">Semua Rentang Gaji</option>
                            <option value="under_5m" {{ request('salary_range') == 'under_5m' ? 'selected' : '' }}>&lt; Rp 5 Juta</option>
                            <option value="5m_10m" {{ request('salary_range') == '5m_10m' ? 'selected' : '' }}>Rp 5 - 10 Juta</option>
                            <option value="10m_20m" {{ request('salary_range') == '10m_20m' ? 'selected' : '' }}>Rp 10 - 20 Juta</option>
                            <option value="above_20m" {{ request('salary_range') == 'above_20m' ? 'selected' : '' }}>&gt; Rp 20 Juta</option>
                        </select>
                    </div>

                    <!-- Sorting Filter -->
                    <div>
                        <select name="sort" class="block w-full px-3 py-2 border-slate-300 rounded-xl focus:border-blue-500 focus:ring-blue-500 bg-slate-50/50 hover:bg-white transition text-xs font-medium text-slate-700">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="deadline_asc" {{ request('sort') == 'deadline_asc' ? 'selected' : '' }}>Deadline Terdekat</option>
                            <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul (A-Z)</option>
                        </select>
                    </div>
                </div>

                <!-- Submit & Quick Tags -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-slate-200">
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="font-semibold text-slate-500">Pencarian Populer:</span>
                        <a href="{{ route('jobs.index', ['search' => 'Backend']) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition font-medium border border-slate-200">Backend</a>
                        <a href="{{ route('jobs.index', ['search' => 'React']) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition font-medium border border-slate-200">React</a>
                        <a href="{{ route('jobs.index', ['search' => 'UI/UX']) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition font-medium border border-slate-200">UI/UX</a>
                        <a href="{{ route('jobs.index', ['salary_range' => '10m_20m']) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition font-medium border border-slate-200">Gaji 10-20 Juta</a>
                    </div>

                    <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                        @if(request()->anyFilled(['search', 'major', 'location', 'division', 'work_type', 'salary_range', 'sort']))
                            <a href="{{ route('jobs.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs flex items-center gap-1 transition border border-slate-300">
                                <i class="fa-solid fa-xmark text-slate-500 text-xs"></i> Reset Filter
                            </a>
                        @endif
                        <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs shadow-sm transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-filter text-xs"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Job Cards Grid Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 mb-24">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Daftar Lowongan Pekerjaan</h2>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Menampilkan kesempatan karir yang sesuai dengan kualifikasi Anda</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-slate-100 text-slate-800 text-xs font-semibold rounded-lg border border-slate-200">
                    {{ $jobs->total() }} Lowongan Ditemukan
                </span>
            </div>
        </div>

        @if($jobs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jobs as $job)
                    @php
                        $isBookmarked = in_array($job->id, $savedJobIds ?? []);
                        $matchScore = auth()->check() && auth()->user()->candidateProfile ? $job->calculateMatchScore(auth()->user()->candidateProfile) : null;
                    @endphp
                    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 hover:border-slate-300 hover:shadow-md transition-all duration-200 flex flex-col group h-full relative overflow-hidden">
                        
                        <div class="p-6 flex-grow flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white font-bold text-base flex items-center justify-center border border-slate-800">
                                    {{ strtoupper(substr($job->title, 0, 1)) }}
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-md border border-slate-200">
                                        {{ ucfirst($job->work_type) }}
                                    </span>
                                    @auth
                                        <form action="{{ route('jobs.bookmark', $job) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="{{ $isBookmarked ? 'Batal simpan' : 'Simpan lowongan' }}" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition border border-transparent">
                                                <i class="fa-solid fa-bookmark text-sm {{ $isBookmarked ? 'text-blue-600' : '' }}"></i>
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                            </div>
                            
                            <!-- Title & Division -->
                            <a href="{{ route('jobs.show', $job) }}" class="group-hover:text-blue-600 transition">
                                <h3 class="text-base font-bold text-slate-900 mb-1 leading-snug line-clamp-2">{{ $job->title }}</h3>
                            </a>
                            <p class="text-xs font-medium text-slate-600 mb-2.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-building text-slate-400 text-xs"></i> {{ $job->company_name ?? 'PT TechNova Asia Digital' }}
                            </p>

                            <div class="mb-3">
                                <span class="inline-flex items-center text-xs font-medium text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">{{ $job->division }}</span>
                            </div>

                            <!-- Match Score Badge if Logged In Candidate -->
                            @if(!is_null($matchScore))
                                <div class="mb-3.5 p-2 bg-blue-50/60 rounded-xl border border-blue-100 flex items-center justify-between text-xs">
                                    <span class="text-blue-700 font-medium">Match Profile</span>
                                    <span class="font-bold text-blue-900 bg-white px-2 py-0.5 rounded-md border border-blue-200">{{ $matchScore }}% Cocok</span>
                                </div>
                            @endif
                            
                            <!-- Location & Created Date -->
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
                                {{ Str::limit(strip_tags($job->description), 100) }}
                            </p>

                            <!-- Benefits Summary Badge -->
                            @if($job->benefits)
                                <div class="mb-3.5 p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 font-medium truncate flex items-center gap-1.5">
                                    <i class="fa-solid fa-gift text-slate-400 text-xs"></i>
                                    <span class="truncate">Benefit: {{ $job->benefits }}</span>
                                </div>
                            @endif

                            <!-- Quota Indicator if Set -->
                            @if($job->quota)
                                <div class="mb-4">
                                    <div class="flex justify-between items-center text-xs font-semibold mb-1">
                                        <span class="text-slate-500">Kuota Pelamar</span>
                                        <span class="{{ $job->isQuotaFull() ? 'text-rose-600' : 'text-slate-700' }}">{{ $job->applications()->count() }} / {{ $job->quota }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $job->isQuotaFull() ? 'bg-rose-500' : 'bg-blue-600' }}" style="width: {{ min(100, round(($job->applications()->count() / $job->quota) * 100)) }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Footer Info & Apply Button -->
                        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="min-w-0">
                                <span class="text-[11px] text-slate-400 font-medium block">Gaji Ditawarkan</span>
                                <span class="text-xs font-semibold text-slate-900 block truncate">{{ $job->salary ?? 'Negosiasi' }}</span>
                            </div>
                            <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition shrink-0 w-full sm:w-auto text-center">
                                Detail Posisi
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-12">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-2xl shadow-xs border border-slate-200 max-w-2xl mx-auto">
                <div class="w-14 h-14 bg-slate-100 text-slate-500 rounded-2xl flex items-center justify-center mx-auto mb-4 font-bold text-xl border border-slate-200">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Tidak ada lowongan ditemukan</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mb-6">Coba sesuaikan kata kunci, lokasi, atau reset filter untuk melihat lowongan lain yang tersedia.</p>
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-xs text-xs transition">
                    <i class="fa-solid fa-arrow-rotate-left mr-2"></i> Reset Filter Pencarian
                </a>
            </div>
        @endif
    </div>
</x-public-layout>
