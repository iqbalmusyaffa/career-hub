<x-public-layout>
    <!-- 1. Enterprise Hero Section -->
    <div class="relative bg-slate-900 text-white overflow-hidden pt-12 pb-16 sm:pt-16 sm:pb-20 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                <div class="lg:col-span-7 text-center lg:text-left">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-slate-800 text-slate-300 text-3xs sm:text-xs font-bold uppercase tracking-wider mb-6 border border-slate-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Platform Rekrutmen Karir Terpercaya
                    </span>
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold tracking-tight leading-tight mb-6 text-white">
                        Hubungkan Talenta Hebat dengan <span class="text-slate-200">Karir Impian.</span>
                    </h1>
                    <p class="text-slate-300 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto lg:mx-0 leading-relaxed mb-8">
                        TalentFlow mempertemukan ribuan profesional berbakat dengan perusahaan terbaik nasional dan internasional secara cepat, transparan, dan terukur.
                    </p>
                    
                    <!-- Search Bar Widget -->
                    <div class="bg-slate-800/80 p-3 sm:p-4 rounded-2xl border border-slate-700 shadow-2xs">
                        <form action="{{ route('jobs.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                            <div class="relative flex-grow flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
                                </div>
                                <input type="text" name="search" class="block w-full pl-10 pr-3 py-2.5 border-0 rounded-xl text-slate-900 placeholder-slate-400 text-xs sm:text-sm font-medium bg-white focus:ring-2 focus:ring-slate-700" placeholder="Posisi, keahlian, atau kata kunci...">
                            </div>
                            <div class="relative flex-grow flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-location-dot text-slate-400 text-xs"></i>
                                </div>
                                <input type="text" name="location" class="block w-full pl-10 pr-3 py-2.5 border-0 rounded-xl text-slate-900 placeholder-slate-400 text-xs sm:text-sm font-medium bg-white focus:ring-2 focus:ring-slate-700" placeholder="Kota atau wilayah...">
                            </div>
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-slate-950 hover:bg-slate-900 text-white font-bold text-xs sm:text-sm rounded-xl transition border border-slate-800 shadow-2xs flex items-center justify-center gap-2">
                                <i class="fa-solid fa-search text-xs"></i> Cari Pekerjaan
                            </button>
                        </form>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center justify-center lg:justify-start gap-2 text-3xs sm:text-xs text-slate-400 font-medium">
                        <span>Pencarian Populer:</span>
                        <a href="{{ route('jobs.index', ['search' => 'Backend']) }}" class="bg-slate-800 hover:bg-slate-700 px-2.5 py-1 rounded-md transition text-slate-200 border border-slate-700">Backend Developer</a>
                        <a href="{{ route('jobs.index', ['search' => 'React']) }}" class="bg-slate-800 hover:bg-slate-700 px-2.5 py-1 rounded-md transition text-slate-200 border border-slate-700">React Engineer</a>
                        <a href="{{ route('jobs.index', ['search' => 'UI/UX']) }}" class="bg-slate-800 hover:bg-slate-700 px-2.5 py-1 rounded-md transition text-slate-200 border border-slate-700">UI/UX Designer</a>
                    </div>
                </div>

                <!-- Live Hiring Status Card -->
                <div class="lg:col-span-5 relative mt-6 lg:mt-0">
                    <div class="bg-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xs text-white relative overflow-hidden border border-slate-700">
                        <div class="flex items-center justify-between border-b border-slate-700 pb-4 mb-5">
                            <div>
                                <span class="text-3xs uppercase tracking-wider font-bold text-slate-400 block mb-1">Ringkasan Sistem</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-100">Statistik Rekrutmen Real-Time</h3>
                            </div>
                            <span class="px-2.5 py-1 bg-emerald-500/20 text-emerald-300 text-3xs font-bold rounded-lg border border-emerald-500/30">Aktif</span>
                        </div>

                        <div class="space-y-3">
                            <div class="bg-slate-900/80 p-3.5 sm:p-4 rounded-xl border border-slate-700/80 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-slate-800 text-slate-300 rounded-lg flex items-center justify-center text-sm font-bold border border-slate-700">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>
                                    <div>
                                        <div class="text-3xs text-slate-400 font-medium">Lowongan Aktif</div>
                                        <div class="text-sm sm:text-base font-bold text-slate-100">{{ \App\Models\Job::where('status', 'active')->count() }} Posisi Terbuka</div>
                                    </div>
                                </div>
                                <span class="text-3xs text-emerald-400 font-bold hidden sm:inline">Tayang</span>
                            </div>

                            <div class="bg-slate-900/80 p-3.5 sm:p-4 rounded-xl border border-slate-700/80 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-slate-800 text-slate-300 rounded-lg flex items-center justify-center text-sm font-bold border border-slate-700">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                    <div>
                                        <div class="text-3xs text-slate-400 font-medium">Kandidat Terdaftar</div>
                                        <div class="text-sm sm:text-base font-bold text-slate-100">{{ \Spatie\Permission\Models\Role::where('name', 'Candidate')->exists() ? \App\Models\User::role('Candidate')->count() : 0 }} Talenta Bergabung</div>
                                    </div>
                                </div>
                                <span class="text-3xs text-emerald-400 font-bold hidden sm:inline">Terverifikasi</span>
                            </div>

                            <div class="bg-slate-900/80 p-3.5 sm:p-4 rounded-xl border border-slate-700/80 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-slate-800 text-slate-300 rounded-lg flex items-center justify-center text-sm font-bold border border-slate-700">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </div>
                                    <div>
                                        <div class="text-3xs text-slate-400 font-medium">Verifikasi Perusahaan</div>
                                        <div class="text-sm sm:text-base font-bold text-slate-100">Mitra Terverifikasi Legal NIB</div>
                                    </div>
                                </div>
                                <span class="text-3xs text-emerald-400 font-bold hidden sm:inline">Resmi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Trusted Companies Bar -->
    <div class="bg-white border-b border-slate-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-3xs font-bold uppercase text-slate-400 tracking-widest mb-5">
                Dipercaya Oleh Perusahaan Terkemuka
            </p>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 sm:gap-4 items-center justify-center">
                @if(isset($trustedCompanies) && count($trustedCompanies) > 0)
                    @foreach($trustedCompanies as $comp)
                        <a href="{{ route('companies.show', urlencode($comp->company_name)) }}" class="bg-slate-50 hover:bg-slate-100/80 py-2.5 px-3 rounded-xl border border-slate-200 shadow-2xs flex items-center justify-center gap-2 transition group" title="Lihat Lowongan {{ $comp->company_name }}">
                            @if($comp->logo_path)
                                <img src="{{ Storage::url($comp->logo_path) }}" alt="{{ $comp->company_name }}" class="w-6 h-6 rounded-md object-cover border border-slate-300 shrink-0">
                            @else
                                <span class="w-6 h-6 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold text-3xs border border-slate-900 shrink-0">
                                    {{ strtoupper(substr($comp->company_name, 0, 2)) }}
                                </span>
                            @endif
                            <span class="text-xs font-bold text-slate-800 truncate group-hover:text-slate-900">{{ $comp->company_name }}</span>
                        </a>
                    @endforeach
                @else
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-slate-100/80 py-2.5 px-3 rounded-xl border border-slate-200 shadow-2xs flex items-center justify-center gap-2 transition">
                        <span class="w-6 h-6 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold text-3xs border border-slate-900 shrink-0">TN</span>
                        <span class="text-xs font-bold text-slate-800 truncate">TechNova Asia</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-slate-100/80 py-2.5 px-3 rounded-xl border border-slate-200 shadow-2xs flex items-center justify-center gap-2 transition">
                        <span class="w-6 h-6 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold text-3xs border border-slate-900 shrink-0">GC</span>
                        <span class="text-xs font-bold text-slate-800 truncate">GlobalCorp</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-slate-100/80 py-2.5 px-3 rounded-xl border border-slate-200 shadow-2xs flex items-center justify-center gap-2 transition">
                        <span class="w-6 h-6 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold text-3xs border border-slate-900 shrink-0">FS</span>
                        <span class="text-xs font-bold text-slate-800 truncate">FinServe Digital</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-slate-100/80 py-2.5 px-3 rounded-xl border border-slate-200 shadow-2xs flex items-center justify-center gap-2 transition">
                        <span class="w-6 h-6 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold text-3xs border border-slate-900 shrink-0">ES</span>
                        <span class="text-xs font-bold text-slate-800 truncate">EduSmart Tech</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-slate-100/80 py-2.5 px-3 rounded-xl border border-slate-200 shadow-2xs flex items-center justify-center gap-2 transition">
                        <span class="w-6 h-6 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold text-3xs border border-slate-900 shrink-0">AL</span>
                        <span class="text-xs font-bold text-slate-800 truncate">AeroLogistics</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-slate-100/80 py-2.5 px-3 rounded-xl border border-slate-200 shadow-2xs flex items-center justify-center gap-2 transition">
                        <span class="w-6 h-6 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold text-3xs border border-slate-900 shrink-0">TK</span>
                        <span class="text-xs font-bold text-slate-800 truncate">TokoKreatif</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. Category Grid Section -->
    <div class="bg-slate-50/50 py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="text-3xs font-bold text-slate-700 uppercase tracking-widest bg-slate-100 px-3 py-1 rounded-md border border-slate-200">Kategori Pekerjaan</span>
                <h2 class="text-xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-2">Jelajahi Berdasarkan Divisi</h2>
                <p class="mt-1 text-xs text-slate-500 max-w-xl mx-auto font-medium">Temukan posisi impian di berbagai divisi bisnis yang berkembang pesat.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <a href="{{ route('jobs.index', ['division' => 'Engineering']) }}" class="bg-white rounded-2xl p-6 shadow-2xs border border-slate-200 hover:border-slate-400 transition-all group">
                    <div class="w-10 h-10 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center mb-4 group-hover:bg-slate-900 group-hover:text-white transition-all text-base font-bold border border-slate-200">
                        <i class="fa-solid fa-code"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1 group-hover:text-slate-700 transition">Engineering</h3>
                    <p class="text-xs text-slate-500">Software, Backend, Frontend & DevOps</p>
                </a>

                <a href="{{ route('jobs.index', ['division' => 'Design']) }}" class="bg-white rounded-2xl p-6 shadow-2xs border border-slate-200 hover:border-slate-400 transition-all group">
                    <div class="w-10 h-10 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center mb-4 group-hover:bg-slate-900 group-hover:text-white transition-all text-base font-bold border border-slate-200">
                        <i class="fa-solid fa-palette"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1 group-hover:text-slate-700 transition">Design & Creative</h3>
                    <p class="text-xs text-slate-500">UI/UX Designer, Graphic & Prototype</p>
                </a>

                <a href="{{ route('jobs.index', ['division' => 'Product']) }}" class="bg-white rounded-2xl p-6 shadow-2xs border border-slate-200 hover:border-slate-400 transition-all group">
                    <div class="w-10 h-10 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center mb-4 group-hover:bg-slate-900 group-hover:text-white transition-all text-base font-bold border border-slate-200">
                        <i class="fa-solid fa-rocket"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1 group-hover:text-slate-700 transition">Product Management</h3>
                    <p class="text-xs text-slate-500">Product Manager & PO</p>
                </a>

                <a href="{{ route('jobs.index', ['division' => 'Data Science']) }}" class="bg-white rounded-2xl p-6 shadow-2xs border border-slate-200 hover:border-slate-400 transition-all group">
                    <div class="w-10 h-10 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center mb-4 group-hover:bg-slate-900 group-hover:text-white transition-all text-base font-bold border border-slate-200">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1 group-hover:text-slate-700 transition">Data & Analytics</h3>
                    <p class="text-xs text-slate-500">Data Analyst & BI Specialist</p>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. How It Works Section -->
    <div class="bg-white py-12 sm:py-16 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="text-3xs font-bold text-slate-700 uppercase tracking-widest bg-slate-100 px-3 py-1 rounded-md border border-slate-200">Proses Rekrutmen</span>
                <h2 class="text-xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-2">3 Langkah Mudah Mendapatkan Pekerjaan</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6 bg-slate-50/50 rounded-2xl border border-slate-200 flex flex-col items-center">
                    <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold text-sm mb-4 border border-slate-900">1</div>
                    <h3 class="text-base font-bold text-slate-900 mb-2">Buat Profil & Unggah CV</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Daftar akun kandidat, lengkapi keahlian dan riwayat pendidikan Anda, lalu buat CV PDF otomatis.</p>
                </div>

                <div class="p-6 bg-slate-50/50 rounded-2xl border border-slate-200 flex flex-col items-center">
                    <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold text-sm mb-4 border border-slate-900">2</div>
                    <h3 class="text-base font-bold text-slate-900 mb-2">Cari & Filter Lowongan</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Gunakan filter berdasarkan lokasi, gaji, divisi, dan tipe kerja yang Anda harapkan.</p>
                </div>

                <div class="p-6 bg-slate-50/50 rounded-2xl border border-slate-200 flex flex-col items-center">
                    <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold text-sm mb-4 border border-slate-900">3</div>
                    <h3 class="text-base font-bold text-slate-900 mb-2">Lamar & Pantau Status</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Lamar posisi 1-klik dan dapatkan pembaruan status lamaran serta jadwal wawancara secara langsung.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Featured Latest Jobs Section -->
    <div class="py-12 sm:py-16 bg-slate-50/50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4 border-b border-slate-200 pb-4">
                <div>
                    <span class="text-3xs font-bold text-slate-500 uppercase tracking-widest block">Lowongan Terbaru</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mt-0.5">Peluang Karir Pilihan</h2>
                </div>
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center text-xs font-bold text-slate-700 hover:text-slate-900 transition">
                    Lihat Semua Lowongan &rarr;
                </a>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($latestJobs ?? [] as $job)
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
                            
                            <a href="{{ route('jobs.show', $job) }}" class="group-hover:text-slate-700 transition">
                                <h3 class="text-base font-bold text-slate-900 mb-1 leading-snug line-clamp-2">{{ $job->title }}</h3>
                            </a>
                            <p class="text-xs font-semibold text-slate-600 mb-2 flex items-center gap-1.5">
                                <i class="fa-solid fa-building text-slate-500"></i> {{ $job->company_name ?? 'PT TechNova Asia Digital' }}
                            </p>
                            
                            <div class="mb-3">
                                <span class="inline-flex items-center text-3xs font-bold text-slate-700 uppercase tracking-wider bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">
                                    {{ $job->division }}
                                </span>
                            </div>

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
                                {{ Str::limit(strip_tags($job->description), 100) }}
                            </p>

                            <!-- Benefits Summary Badge -->
                            @if($job->benefits)
                                <div class="mb-3.5 p-2 bg-slate-50 border border-slate-200 rounded-xl text-3xs text-slate-700 font-semibold truncate flex items-center gap-1.5">
                                    <i class="fa-solid fa-gift text-slate-500"></i>
                                    <span class="truncate">Benefit: {{ $job->benefits }}</span>
                                </div>
                            @endif
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
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-slate-200">
                        <p class="text-xs font-semibold text-slate-500">Belum ada lowongan terbaru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 6. Call To Action Footer Banner -->
    <div class="bg-slate-900 text-white py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-left max-w-2xl">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight mb-2">Siap Memulai Karir Impian Anda?</h2>
                <p class="text-slate-300 text-xs sm:text-sm font-medium leading-relaxed">Daftar sekarang gratis, lengkapi profil & keahlian Anda, lalu lamar berbagai pekerjaan terbaik di Indonesia.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white text-slate-900 font-bold rounded-xl text-xs shadow-2xs hover:bg-slate-100 transition text-center border border-white">
                    Mendaftar Akun
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-xl text-xs border border-slate-700 transition text-center">
                    Masuk Portal
                </a>
            </div>
        </div>
    </div>
</x-public-layout>
