<x-public-layout>
    <!-- 1. Enterprise Hero Section -->
    <div class="relative bg-slate-900 text-white overflow-hidden pt-12 pb-16 sm:pt-16 sm:pb-24 border-b border-slate-800">
        <!-- Ambient Glow Effects -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -right-32 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                <div class="lg:col-span-7 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-800/90 text-slate-300 text-3xs sm:text-xs font-bold uppercase tracking-wider mb-6 border border-slate-700/80 shadow-2xs backdrop-blur-xs">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Platform Rekrutmen Karir Terpercaya</span>
                    </div>
                    
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight mb-6 text-white">
                        Hubungkan Talenta Hebat dengan <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400 bg-clip-text text-transparent">Karir Impian.</span>
                    </h1>
                    
                    <p class="text-slate-300 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto lg:mx-0 leading-relaxed mb-8 font-normal">
                        TalentFlow mempertemukan ribuan profesional berbakat dengan perusahaan terbaik nasional dan internasional secara cepat, transparan, dan terukur.
                    </p>
                    
                    <!-- Search Bar Widget -->
                    <div class="bg-slate-800/90 p-3 sm:p-4 rounded-2xl border border-slate-700/90 shadow-xl backdrop-blur-xs">
                        <form action="{{ route('jobs.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                            <div class="relative flex-grow flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
                                </div>
                                <input type="text" name="search" class="block w-full pl-10 pr-3 py-2.5 border-0 rounded-xl text-slate-900 placeholder-slate-400 text-xs sm:text-sm font-medium bg-white focus:ring-2 focus:ring-emerald-500 transition" placeholder="Posisi, keahlian, atau kata kunci...">
                            </div>
                            <div class="relative flex-grow flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-location-dot text-slate-400 text-xs"></i>
                                </div>
                                <input type="text" name="location" class="block w-full pl-10 pr-3 py-2.5 border-0 rounded-xl text-slate-900 placeholder-slate-400 text-xs sm:text-sm font-medium bg-white focus:ring-2 focus:ring-emerald-500 transition" placeholder="Kota atau wilayah...">
                            </div>
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm rounded-xl transition border border-emerald-500 shadow-md flex items-center justify-center gap-2 shrink-0 cursor-pointer">
                                <i class="fa-solid fa-search text-xs"></i> Cari Pekerjaan
                            </button>
                        </form>
                    </div>

                    <!-- Popular Tags -->
                    <div class="mt-4 flex flex-wrap items-center justify-center lg:justify-start gap-2 text-3xs sm:text-xs text-slate-400 font-medium">
                        <span class="text-slate-400 flex items-center gap-1"><i class="fa-solid fa-fire text-amber-400"></i> Populer:</span>
                        <a href="{{ route('jobs.index', ['search' => 'Backend']) }}" class="bg-slate-800/80 hover:bg-slate-700 px-2.5 py-1 rounded-lg transition text-slate-200 border border-slate-700 hover:border-slate-500">Backend Developer</a>
                        <a href="{{ route('jobs.index', ['search' => 'React']) }}" class="bg-slate-800/80 hover:bg-slate-700 px-2.5 py-1 rounded-lg transition text-slate-200 border border-slate-700 hover:border-slate-500">React Engineer</a>
                        <a href="{{ route('jobs.index', ['search' => 'UI/UX']) }}" class="bg-slate-800/80 hover:bg-slate-700 px-2.5 py-1 rounded-lg transition text-slate-200 border border-slate-700 hover:border-slate-500">UI/UX Designer</a>
                    </div>
                </div>

                <!-- Live Job Preview Feed Card -->
                <div class="lg:col-span-5 relative mt-6 lg:mt-0">
                    <div class="bg-slate-800/90 rounded-2xl p-5 sm:p-7 shadow-2xl text-white relative overflow-hidden border border-slate-700/90 backdrop-blur-md">
                        <!-- Top Banner Accent Gradient Line -->
                        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-500"></div>

                        <!-- Card Header -->
                        <div class="flex items-center justify-between border-b border-slate-700/80 pb-4 mb-4">
                            <div>
                                <span class="text-3xs uppercase tracking-widest font-bold text-slate-400 block mb-0.5">Peluang Karir Terbaru</span>
                                <h3 class="text-base sm:text-lg font-bold text-slate-100 flex items-center gap-2">
                                    <i class="fa-solid fa-bolt text-amber-400 text-xs"></i> Lowongan Pilihan Hari Ini
                                </h3>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/10 text-emerald-400 text-3xs font-bold rounded-full border border-emerald-500/30">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                LIVE
                            </span>
                        </div>

                        <!-- Jobs List -->
                        <div class="space-y-3 mb-4">
                            @if(isset($latestJobs) && count($latestJobs) > 0)
                                @foreach($latestJobs->take(3) as $job)
                                    <a href="{{ route('jobs.show', $job) }}" class="group block bg-slate-900/90 hover:bg-slate-900 p-3.5 rounded-xl border border-slate-700/80 hover:border-emerald-500/50 transition-all duration-200 shadow-2xs">
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-10 h-10 bg-slate-800 text-emerald-400 rounded-xl flex items-center justify-center text-sm font-bold border border-slate-700 group-hover:border-emerald-500/50 group-hover:bg-emerald-500/10 shrink-0 transition">
                                                    {{ strtoupper(substr($job->title, 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs sm:text-sm font-bold text-slate-100 group-hover:text-emerald-400 transition truncate">
                                                        {{ $job->title }}
                                                    </div>
                                                    <div class="text-3xs text-slate-400 font-medium truncate flex items-center gap-1.5 mt-0.5">
                                                        <span class="text-slate-300 font-semibold">{{ $job->company_name ?? 'TechNova Asia' }}</span>
                                                        <span>•</span>
                                                        <span class="text-slate-400"><i class="fa-solid fa-location-dot text-slate-500 text-3xs"></i> {{ $job->location }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <span class="inline-block px-2 py-0.5 bg-slate-800 text-slate-300 text-3xs font-semibold rounded-md border border-slate-700 uppercase tracking-wider">
                                                    {{ ucfirst($job->work_type ?? 'Full-time') }}
                                                </span>
                                                <div class="text-3xs text-slate-400 font-medium mt-1">
                                                    {{ $job->created_at ? $job->created_at->diffForHumans() : 'Baru saja' }}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <!-- Fallback Sample Jobs -->
                                <a href="{{ route('jobs.index') }}" class="group block bg-slate-900/90 hover:bg-slate-900 p-3.5 rounded-xl border border-slate-700/80 hover:border-emerald-500/50 transition-all duration-200 shadow-2xs">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 bg-slate-800 text-emerald-400 rounded-xl flex items-center justify-center text-sm font-bold border border-slate-700 group-hover:border-emerald-500/50 shrink-0">
                                                S
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs sm:text-sm font-bold text-slate-100 group-hover:text-emerald-400 transition truncate">
                                                    Senior Backend Developer
                                                </div>
                                                <div class="text-3xs text-slate-400 font-medium truncate mt-0.5">
                                                    TechNova Asia • Jakarta Pusat
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="inline-block px-2 py-0.5 bg-slate-800 text-slate-300 text-3xs font-semibold rounded-md border border-slate-700 uppercase">
                                                Full-Time
                                            </span>
                                            <div class="text-3xs text-slate-400 font-medium mt-1">
                                                Baru saja
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <a href="{{ route('jobs.index') }}" class="group block bg-slate-900/90 hover:bg-slate-900 p-3.5 rounded-xl border border-slate-700/80 hover:border-emerald-500/50 transition-all duration-200 shadow-2xs">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 bg-slate-800 text-emerald-400 rounded-xl flex items-center justify-center text-sm font-bold border border-slate-700 group-hover:border-emerald-500/50 shrink-0">
                                                R
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs sm:text-sm font-bold text-slate-100 group-hover:text-emerald-400 transition truncate">
                                                    React Frontend Specialist
                                                </div>
                                                <div class="text-3xs text-slate-400 font-medium truncate mt-0.5">
                                                    GlobalCorp • Remote
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="inline-block px-2 py-0.5 bg-slate-800 text-slate-300 text-3xs font-semibold rounded-md border border-slate-700 uppercase">
                                                Remote
                                            </span>
                                            <div class="text-3xs text-slate-400 font-medium mt-1">
                                                1 jam lalu
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <a href="{{ route('jobs.index') }}" class="group block bg-slate-900/90 hover:bg-slate-900 p-3.5 rounded-xl border border-slate-700/80 hover:border-emerald-500/50 transition-all duration-200 shadow-2xs">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 bg-slate-800 text-emerald-400 rounded-xl flex items-center justify-center text-sm font-bold border border-slate-700 group-hover:border-emerald-500/50 shrink-0">
                                                U
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs sm:text-sm font-bold text-slate-100 group-hover:text-emerald-400 transition truncate">
                                                    UI/UX Product Designer
                                                </div>
                                                <div class="text-3xs text-slate-400 font-medium truncate mt-0.5">
                                                    FinServe Digital • Bandung
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="inline-block px-2 py-0.5 bg-slate-800 text-slate-300 text-3xs font-semibold rounded-md border border-slate-700 uppercase">
                                                Hybrid
                                            </span>
                                            <div class="text-3xs text-slate-400 font-medium mt-1">
                                                3 jam lalu
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>

                        <!-- Card Footer CTA -->
                        <div class="pt-3 border-t border-slate-700/80 flex items-center justify-between text-xs font-bold">
                            <span class="text-slate-400 text-3xs font-medium flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check text-emerald-400 text-3xs"></i>
                                {{ \App\Models\Job::where('status', 'active')->count() }} Posisi Aktif Siap Dilamar
                            </span>
                            <a href="{{ route('jobs.index') }}" class="text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1">
                                Lihat Semua Lowongan <i class="fa-solid fa-arrow-right text-3xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Trusted Companies Bar -->
    <div class="bg-white border-b border-slate-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-3xs font-bold uppercase text-slate-400 tracking-widest mb-6 flex items-center justify-center gap-2">
                <span class="h-px w-8 bg-slate-200"></span>
                <span>Dipercaya Oleh Perusahaan Terkemuka</span>
                <span class="h-px w-8 bg-slate-200"></span>
            </p>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 sm:gap-4 items-center justify-center">
                @if(isset($trustedCompanies) && count($trustedCompanies) > 0)
                    @foreach($trustedCompanies as $comp)
                        <a href="{{ route('companies.show', urlencode($comp->company_name)) }}" class="bg-slate-50 hover:bg-white hover:shadow-md py-3 px-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-center gap-2.5 transition group" title="Lihat Lowongan {{ $comp->company_name }}">
                            @if($comp->logo_path)
                                <img src="{{ Storage::url($comp->logo_path) }}" alt="{{ $comp->company_name }}" class="w-6 h-6 rounded-md object-cover border border-slate-200 shrink-0">
                            @else
                                <span class="w-6 h-6 rounded-md bg-slate-900 text-emerald-400 flex items-center justify-center font-bold text-3xs border border-slate-800 shrink-0">
                                    {{ strtoupper(substr($comp->company_name, 0, 2)) }}
                                </span>
                            @endif
                            <span class="text-xs font-bold text-slate-800 truncate group-hover:text-emerald-600 transition">{{ $comp->company_name }}</span>
                        </a>
                    @endforeach
                @else
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-white hover:shadow-md py-3 px-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-center gap-2.5 transition group">
                        <span class="w-6 h-6 rounded-md bg-slate-900 text-emerald-400 flex items-center justify-center font-bold text-3xs border border-slate-800 shrink-0">TN</span>
                        <span class="text-xs font-bold text-slate-800 truncate group-hover:text-emerald-600 transition">TechNova Asia</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-white hover:shadow-md py-3 px-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-center gap-2.5 transition group">
                        <span class="w-6 h-6 rounded-md bg-slate-900 text-emerald-400 flex items-center justify-center font-bold text-3xs border border-slate-800 shrink-0">GC</span>
                        <span class="text-xs font-bold text-slate-800 truncate group-hover:text-emerald-600 transition">GlobalCorp</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-white hover:shadow-md py-3 px-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-center gap-2.5 transition group">
                        <span class="w-6 h-6 rounded-md bg-slate-900 text-emerald-400 flex items-center justify-center font-bold text-3xs border border-slate-800 shrink-0">FS</span>
                        <span class="text-xs font-bold text-slate-800 truncate group-hover:text-emerald-600 transition">FinServe Digital</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-white hover:shadow-md py-3 px-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-center gap-2.5 transition group">
                        <span class="w-6 h-6 rounded-md bg-slate-900 text-emerald-400 flex items-center justify-center font-bold text-3xs border border-slate-800 shrink-0">ES</span>
                        <span class="text-xs font-bold text-slate-800 truncate group-hover:text-emerald-600 transition">EduSmart Tech</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-white hover:shadow-md py-3 px-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-center gap-2.5 transition group">
                        <span class="w-6 h-6 rounded-md bg-slate-900 text-emerald-400 flex items-center justify-center font-bold text-3xs border border-slate-800 shrink-0">AL</span>
                        <span class="text-xs font-bold text-slate-800 truncate group-hover:text-emerald-600 transition">AeroLogistics</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-white hover:shadow-md py-3 px-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-center gap-2.5 transition group">
                        <span class="w-6 h-6 rounded-md bg-slate-900 text-emerald-400 flex items-center justify-center font-bold text-3xs border border-slate-800 shrink-0">TK</span>
                        <span class="text-xs font-bold text-slate-800 truncate group-hover:text-emerald-600 transition">TokoKreatif</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. Category Grid Section -->
    <div class="bg-slate-50/70 py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-3xs font-bold text-emerald-700 uppercase tracking-widest bg-emerald-50 px-3.5 py-1.5 rounded-full border border-emerald-200/80 inline-block mb-3">Kategori Pekerjaan</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Jelajahi Berdasarkan Divisi</h2>
                <p class="mt-2 text-xs sm:text-sm text-slate-500 max-w-xl mx-auto font-medium leading-relaxed">Temukan posisi impian Anda di berbagai divisi bisnis yang sedang berkembang pesat.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
                <a href="{{ route('jobs.index', ['division' => 'Engineering']) }}" class="bg-white rounded-2xl p-6 shadow-2xs border border-slate-200/90 hover:border-emerald-500 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 text-lg font-bold border border-emerald-100">
                            <i class="fa-solid fa-code"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1.5 group-hover:text-emerald-600 transition">Engineering</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">Software, Backend, Frontend & DevOps Specialist</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400 group-hover:text-emerald-600 transition">
                        <span>Lihat Posisi</span>
                        <i class="fa-solid fa-arrow-right text-3xs group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

                <a href="{{ route('jobs.index', ['division' => 'Design']) }}" class="bg-white rounded-2xl p-6 shadow-2xs border border-slate-200/90 hover:border-emerald-500 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300 text-lg font-bold border border-purple-100">
                            <i class="fa-solid fa-palette"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1.5 group-hover:text-purple-600 transition">Design & Creative</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">UI/UX Designer, Product Graphic & Prototype</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400 group-hover:text-purple-600 transition">
                        <span>Lihat Posisi</span>
                        <i class="fa-solid fa-arrow-right text-3xs group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

                <a href="{{ route('jobs.index', ['division' => 'Product']) }}" class="bg-white rounded-2xl p-6 shadow-2xs border border-slate-200/90 hover:border-emerald-500 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 text-lg font-bold border border-blue-100">
                            <i class="fa-solid fa-rocket"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1.5 group-hover:text-blue-600 transition">Product Management</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">Product Manager, Product Owner & Analyst</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400 group-hover:text-blue-600 transition">
                        <span>Lihat Posisi</span>
                        <i class="fa-solid fa-arrow-right text-3xs group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

                <a href="{{ route('jobs.index', ['division' => 'Data Science']) }}" class="bg-white rounded-2xl p-6 shadow-2xs border border-slate-200/90 hover:border-emerald-500 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300 text-lg font-bold border border-amber-100">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1.5 group-hover:text-amber-600 transition">Data & Analytics</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">Data Analyst, Data Engineer & BI Specialist</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400 group-hover:text-amber-600 transition">
                        <span>Lihat Posisi</span>
                        <i class="fa-solid fa-arrow-right text-3xs group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. How It Works Section -->
    <div class="bg-white py-16 sm:py-20 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-3xs font-bold text-emerald-700 uppercase tracking-widest bg-emerald-50 px-3.5 py-1.5 rounded-full border border-emerald-200/80 inline-block mb-3">Proses Rekrutmen</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">3 Langkah Mudah Mendapatkan Pekerjaan</h2>
                <p class="mt-2 text-xs sm:text-sm text-slate-500 max-w-xl mx-auto font-medium">Proses transparan tanpa perantara untuk karir impian Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <div class="p-8 bg-slate-50/80 rounded-2xl border border-slate-200/90 hover:border-slate-300 hover:shadow-lg transition-all text-center flex flex-col items-center group relative">
                    <div class="w-14 h-14 bg-slate-900 text-emerald-400 rounded-2xl flex items-center justify-center font-extrabold text-lg mb-6 border border-slate-800 shadow-md group-hover:scale-110 transition-transform">
                        1
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-2.5">Buat Profil & Unggah CV</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">Daftar akun kandidat, lengkapi keahlian dan riwayat pendidikan Anda, lalu gunakan pembuat CV otomatis kami.</p>
                </div>

                <div class="p-8 bg-slate-50/80 rounded-2xl border border-slate-200/90 hover:border-slate-300 hover:shadow-lg transition-all text-center flex flex-col items-center group relative">
                    <div class="w-14 h-14 bg-slate-900 text-emerald-400 rounded-2xl flex items-center justify-center font-extrabold text-lg mb-6 border border-slate-800 shadow-md group-hover:scale-110 transition-transform">
                        2
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-2.5">Cari & Filter Lowongan</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">Gunakan filter cerdas berdasarkan lokasi, rentang gaji, divisi, dan tipe kerja (*Full-Time/Remote*) yang paling sesuai.</p>
                </div>

                <div class="p-8 bg-slate-50/80 rounded-2xl border border-slate-200/90 hover:border-slate-300 hover:shadow-lg transition-all text-center flex flex-col items-center group relative">
                    <div class="w-14 h-14 bg-slate-900 text-emerald-400 rounded-2xl flex items-center justify-center font-extrabold text-lg mb-6 border border-slate-800 shadow-md group-hover:scale-110 transition-transform">
                        3
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-2.5">Lamar & Pantau Status</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">Lamar posisi 1-klik dan dapatkan pemberitahuan status lamaran serta jadwal wawancara secara langsung.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Featured Latest Jobs Section -->
    <div class="py-16 sm:py-20 bg-slate-50/70 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4 border-b border-slate-200 pb-5">
                <div>
                    <span class="text-3xs font-bold text-emerald-700 uppercase tracking-widest block mb-1">Lowongan Terbaru</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Peluang Karir Pilihan</h2>
                </div>
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 transition">
                    Lihat Semua Lowongan <i class="fa-solid fa-arrow-right text-3xs"></i>
                </a>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($latestJobs ?? [] as $job)
                    <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 hover:border-emerald-500 hover:shadow-xl transition-all duration-300 flex flex-col group h-full relative overflow-hidden">
                        <div class="p-6 flex-grow flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-11 h-11 rounded-xl bg-slate-900 text-emerald-400 font-bold text-lg flex items-center justify-center border border-slate-800 shadow-2xs">
                                    {{ strtoupper(substr($job->title, 0, 1)) }}
                                </div>
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-3xs font-bold rounded-full border border-slate-200 uppercase tracking-wider">
                                    {{ ucfirst($job->work_type) }}
                                </span>
                            </div>
                            
                            <a href="{{ route('jobs.show', $job) }}" class="group-hover:text-emerald-600 transition">
                                <h3 class="text-base font-bold text-slate-900 mb-1.5 leading-snug line-clamp-2">{{ $job->title }}</h3>
                            </a>
                            <p class="text-xs font-semibold text-slate-600 mb-3 flex items-center gap-1.5">
                                <i class="fa-solid fa-building text-slate-400"></i> {{ $job->company_name ?? 'PT TechNova Asia Digital' }}
                            </p>
                            
                            <div class="mb-4">
                                <span class="inline-flex items-center text-3xs font-bold text-slate-700 uppercase tracking-wider bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">
                                    {{ $job->division }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center text-xs text-slate-500 mb-4 gap-x-4 gap-y-1.5 font-medium">
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-slate-400"></i>
                                    <span>{{ $job->location }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-clock text-slate-400"></i>
                                    <span>{{ $job->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            
                            <!-- Description excerpt -->
                            <p class="text-slate-600 text-xs leading-relaxed line-clamp-2 mb-4 flex-grow font-normal">
                                {{ Str::limit(strip_tags($job->description), 110) }}
                            </p>

                            <!-- Benefits Summary Badge -->
                            @if($job->benefits)
                                <div class="mb-4 p-2.5 bg-slate-50 border border-slate-200/80 rounded-xl text-3xs text-slate-700 font-semibold truncate flex items-center gap-2">
                                    <i class="fa-solid fa-gift text-emerald-500"></i>
                                    <span class="truncate">Benefit: {{ $job->benefits }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 rounded-b-2xl flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <span class="text-3xs uppercase tracking-wider text-slate-400 font-bold block">Gaji Offer</span>
                                <span class="text-xs font-extrabold text-slate-900 block truncate">{{ $job->salary ?? 'Negosiasi' }}</span>
                            </div>
                            <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center justify-center px-4 py-2 bg-slate-900 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl border border-slate-900 hover:border-emerald-600 shadow-2xs transition-all shrink-0">
                                Detail Posisi &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-slate-200 shadow-2xs">
                        <p class="text-xs font-semibold text-slate-500">Belum ada lowongan terbaru saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 6. Call To Action Footer Banner -->
    <div class="bg-slate-900 text-white py-16 border-t border-slate-800 relative overflow-hidden">
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-left max-w-2xl">
                <span class="text-3xs font-bold text-emerald-400 uppercase tracking-widest mb-2 block">Bergabung Sekarang</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-3">Siap Memulai Karir Impian Anda?</h2>
                <p class="text-slate-300 text-xs sm:text-sm font-normal leading-relaxed">Daftar akun kandidat secara gratis, lengkapi profil & keahlian Anda, lalu lamar berbagai posisi pekerjaan terbaik di Indonesia.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto shrink-0">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold rounded-xl text-xs shadow-lg transition text-center border border-emerald-400">
                    Mendaftar Akun Kandidat
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-xl text-xs border border-slate-700 transition text-center">
                    Masuk Portal
                </a>
            </div>
        </div>
    </div>
</x-public-layout>
