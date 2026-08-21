<x-public-layout>
    <!-- 1. Enterprise Hero Section -->
    <div class="relative bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-900 text-white overflow-hidden pt-12 pb-20 sm:pt-16 sm:pb-24 lg:pt-24 lg:pb-32">
        <div class="absolute inset-0 bg-blue-500/10 bg-cover bg-center"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                <div class="lg:col-span-7 text-center lg:text-left">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/20 text-blue-300 text-2xs sm:text-xs font-bold uppercase tracking-wider mb-6 border border-blue-400/30 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> Platform Rekrutmen Karir Digital Terpercaya
                    </span>
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight mb-6">
                        Hubungkan Talenta Hebat dengan <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-indigo-300 to-sky-400">Karir Impian.</span>
                    </h1>
                    <p class="text-blue-100/80 text-sm sm:text-lg lg:text-xl max-w-2xl mx-auto lg:mx-0 leading-relaxed mb-8">
                        TalentFlow mempertemukan ribuan profesional berbakat dengan perusahaan terbaik nasional dan internasional secara cepat, transparan, dan terukur.
                    </p>
                    
                    <!-- Search Bar Widget -->
                    <div class="bg-white/10 backdrop-blur-xl p-3 sm:p-4 rounded-3xl border border-white/20 shadow-2xl">
                        <form action="{{ route('jobs.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                            <div class="relative flex-grow flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" class="block w-full pl-10 pr-3 py-3 border-0 rounded-2xl text-gray-900 placeholder-gray-400 text-xs sm:text-sm font-semibold bg-white focus:ring-2 focus:ring-blue-600 shadow-inner" placeholder="Posisi, keahlian, atau kata kunci...">
                            </div>
                            <div class="relative flex-grow flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                </div>
                                <input type="text" name="location" class="block w-full pl-10 pr-3 py-3 border-0 rounded-2xl text-gray-900 placeholder-gray-400 text-xs sm:text-sm font-semibold bg-white focus:ring-2 focus:ring-blue-600 shadow-inner" placeholder="Kota atau wilayah...">
                            </div>
                            <button type="submit" class="w-full sm:w-auto px-7 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-lg hover:shadow-xl transition transform hover:scale-105 flex items-center justify-center gap-2">
                                🔍 Cari Pekerjaan
                            </button>
                        </form>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center justify-center lg:justify-start gap-2 text-2xs sm:text-xs text-blue-200/90 font-medium">
                        <span>Pencarian Populer:</span>
                        <a href="{{ route('jobs.index', ['search' => 'Backend']) }}" class="bg-white/10 hover:bg-white/20 px-2.5 py-1 rounded-lg transition text-white">Backend Developer</a>
                        <a href="{{ route('jobs.index', ['search' => 'React']) }}" class="bg-white/10 hover:bg-white/20 px-2.5 py-1 rounded-lg transition text-white">React Engineer</a>
                        <a href="{{ route('jobs.index', ['search' => 'UI/UX']) }}" class="bg-white/10 hover:bg-white/20 px-2.5 py-1 rounded-lg transition text-white">UI/UX Designer</a>
                    </div>
                </div>

                <!-- Live Hiring Status Card -->
                <div class="lg:col-span-5 relative mt-6 lg:mt-0">
                    <div class="bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-3xl p-6 sm:p-8 shadow-2xl text-white relative overflow-hidden border border-white/20">
                        <div class="flex items-center justify-between border-b border-white/20 pb-5 mb-5">
                            <div>
                                <span class="text-2xs uppercase tracking-wider font-extrabold text-blue-200 block mb-1">Status Rekrutmen Real-time</span>
                                <h3 class="text-xl sm:text-2xl font-black">Live Hiring Stats</h3>
                            </div>
                            <span class="px-3 py-1 bg-green-400/20 text-green-300 text-2xs font-bold rounded-full border border-green-400/30">Aktif</span>
                        </div>

                        <div class="space-y-3.5">
                            <div class="bg-white/10 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/10 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-blue-500/30 text-white rounded-xl flex items-center justify-center text-lg sm:text-xl font-bold">💼</div>
                                    <div>
                                        <div class="text-2xs sm:text-xs text-blue-200 font-semibold">Lowongan Aktif</div>
                                        <div class="text-base sm:text-xl font-black">{{ \App\Models\Job::where('status', 'active')->count() }} Posisi Terbuka</div>
                                    </div>
                                </div>
                                <span class="text-2xs text-green-300 font-bold hidden sm:inline">Tayang</span>
                            </div>

                            <div class="bg-white/10 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/10 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-indigo-500/30 text-white rounded-xl flex items-center justify-center text-lg sm:text-xl font-bold">👥</div>
                                    <div>
                                        <div class="text-2xs sm:text-xs text-blue-200 font-semibold">Kandidat Terdaftar</div>
                                        <div class="text-base sm:text-xl font-black">{{ \App\Models\User::role('Candidate')->count() }} Talenta Bergabung</div>
                                    </div>
                                </div>
                                <span class="text-2xs text-green-300 font-bold hidden sm:inline">Terverifikasi</span>
                            </div>

                            <div class="bg-white/10 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/10 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-emerald-500/30 text-white rounded-xl flex items-center justify-center text-lg sm:text-xl font-bold">🎯</div>
                                    <div>
                                        <div class="text-2xs sm:text-xs text-blue-200 font-semibold">Tingkat Kesesuaian Skill</div>
                                        <div class="text-base sm:text-xl font-black">98.5% Smart Match</div>
                                    </div>
                                </div>
                                <span class="text-2xs text-green-300 font-bold hidden sm:inline">Akurat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Trusted Companies Bar (Smooth Marquee Ticker) -->
    <div class="bg-white border-t border-b border-gray-100 py-8 overflow-hidden relative">
        <p class="text-center text-2xs sm:text-xs font-black uppercase text-gray-400 tracking-widest mb-6">
            Dipercaya Oleh 500+ Perusahaan Terkemuka
        </p>

        <!-- Gradient Fades on Edges -->
        <div class="absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
        <div class="absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>

        <!-- Marquee Track -->
        <div class="flex overflow-x-auto no-scrollbar py-2 px-4 sm:px-0">
            <div class="flex items-center gap-10 sm:gap-16 shrink-0 animate-marquee hover:[animation-play-state:paused] flex-nowrap">
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">TN</span> TechNova Asia</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">GC</span> GlobalCorp</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">FS</span> FinServe Digital</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-amber-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">ES</span> EduSmart Tech</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">AL</span> AeroLogistics</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">TK</span> TokoKreatif</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-cyan-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">SA</span> SolusiAsia</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-teal-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">CP</span> CloudPrima</div>
            </div>

            <!-- Duplicate set for seamless looping -->
            <div class="flex items-center gap-10 sm:gap-16 shrink-0 animate-marquee hover:[animation-play-state:paused] flex-nowrap pl-10 sm:pl-16">
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">TN</span> TechNova Asia</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">GC</span> GlobalCorp</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">FS</span> FinServe Digital</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-amber-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">ES</span> EduSmart Tech</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">AL</span> AeroLogistics</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">TK</span> TokoKreatif</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-cyan-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">SA</span> SolusiAsia</div>
                <div class="flex items-center text-sm sm:text-base font-black text-gray-800 shrink-0"><span class="w-8 h-8 rounded-xl bg-teal-600 text-white flex items-center justify-center font-black mr-2.5 text-xs shadow-xs">CP</span> CloudPrima</div>
            </div>
        </div>
    </div>

    <!-- Inline Styles for Marquee Animation -->
    <style>
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            animation: marquee 25s linear infinite;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <!-- 3. Category Grid Section -->
    <div class="bg-gray-50/80 py-12 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-16">
                <span class="text-2xs sm:text-xs font-black text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-full border border-blue-100">Kategori Pekerjaan</span>
                <h2 class="text-2xl sm:text-4xl font-black text-gray-900 tracking-tight mt-2 sm:mt-3">Jelajahi Berdasarkan Divisi</h2>
                <p class="mt-2 text-xs sm:text-sm text-gray-500 max-w-xl mx-auto font-medium">Temukan posisi impian di berbagai divisi bisnis yang berkembang pesat.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <a href="{{ route('jobs.index', ['division' => 'Engineering']) }}" class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-300 transition-all group">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-all text-xl font-bold">💻</div>
                    <h3 class="text-base font-black text-gray-900 mb-1 group-hover:text-blue-600 transition">Engineering</h3>
                    <p class="text-xs text-gray-500">Software, Backend, Frontend & DevOps</p>
                </a>

                <a href="{{ route('jobs.index', ['division' => 'Design']) }}" class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-purple-300 transition-all group">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white transition-all text-xl font-bold">🎨</div>
                    <h3 class="text-base font-black text-gray-900 mb-1 group-hover:text-purple-600 transition">Design & Creative</h3>
                    <p class="text-xs text-gray-500">UI/UX Designer, Graphic & Prototype</p>
                </a>

                <a href="{{ route('jobs.index', ['division' => 'Product']) }}" class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-amber-300 transition-all group">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-all text-xl font-bold">🚀</div>
                    <h3 class="text-base font-black text-gray-900 mb-1 group-hover:text-amber-600 transition">Product Management</h3>
                    <p class="text-xs text-gray-500">Product Manager & PO</p>
                </a>

                <a href="{{ route('jobs.index', ['division' => 'Data Science']) }}" class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-emerald-300 transition-all group">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-all text-xl font-bold">📊</div>
                    <h3 class="text-base font-black text-gray-900 mb-1 group-hover:text-emerald-600 transition">Data & Analytics</h3>
                    <p class="text-xs text-gray-500">Data Analyst & BI Specialist</p>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. How It Works Section -->
    <div class="bg-white py-12 sm:py-20 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <span class="text-2xs sm:text-xs font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">Proses Rekrutmen</span>
                <h2 class="text-2xl sm:text-4xl font-black text-gray-900 tracking-tight mt-2">3 Langkah Mudah Mendapatkan Pekerjaan</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center relative">
                <div class="p-6 bg-gray-50/70 rounded-3xl border border-gray-100 flex flex-col items-center">
                    <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center font-black text-xl mb-4 shadow-md">1</div>
                    <h3 class="text-lg font-black text-gray-900 mb-2">Buat Profil & Unggah CV</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Daftar akun kandidat, lengkapi keahlian dan riwayat pendidikan Anda, lalu buat CV PDF otomatis.</p>
                </div>

                <div class="p-6 bg-gray-50/70 rounded-3xl border border-gray-100 flex flex-col items-center">
                    <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center font-black text-xl mb-4 shadow-md">2</div>
                    <h3 class="text-lg font-black text-gray-900 mb-2">Cari & Filter Lowongan</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Gunakan filter cerdas berdasarkan lokasi, gaji, divisi, dan tipe kerja yang Anda harapkan.</p>
                </div>

                <div class="p-6 bg-gray-50/70 rounded-3xl border border-gray-100 flex flex-col items-center">
                    <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center font-black text-xl mb-4 shadow-md">3</div>
                    <h3 class="text-lg font-black text-gray-900 mb-2">Lamar & Pantau Status</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Lamar posisi 1-klik dan dapatkan pembaruan status lamaran serta jadwal wawancara secara langsung.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Featured Latest Jobs Section -->
    <div class="py-12 sm:py-20 bg-gray-50/60 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 sm:mb-12 gap-4 border-b border-gray-200 pb-5">
                <div>
                    <span class="text-2xs sm:text-xs font-black text-blue-600 uppercase tracking-widest">Lowongan Terbaru</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight mt-1">Peluang Karir Pilihan</h2>
                </div>
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center text-xs font-extrabold text-blue-600 hover:text-blue-800 transition">
                    Lihat Semua Lowongan &rarr;
                </a>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($latestJobs ?? [] as $job)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 hover:shadow-2xl hover:border-blue-200 transition-all duration-300 flex flex-col group h-full relative overflow-hidden">
                        <div class="h-2 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                        <div class="p-6 flex-grow flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-black text-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                                    {{ strtoupper(substr($job->title, 0, 1)) }}
                                </div>
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-2xs sm:text-xs font-extrabold rounded-full border border-emerald-100">
                                    {{ ucfirst($job->work_type) }}
                                </span>
                            </div>
                            
                            <a href="{{ route('jobs.show', $job->id) }}" class="group-hover:text-blue-600 transition">
                                <h3 class="text-base sm:text-lg font-black text-gray-900 mb-1 leading-snug line-clamp-2">{{ $job->title }}</h3>
                            </a>
                            <p class="text-xs font-bold text-slate-700 mb-2 flex items-center gap-1.5">
                                <i class="fa-solid fa-building text-blue-600"></i> {{ $job->company_name ?? 'PT TechNova Asia Digital' }}
                            </p>
                            
                            <div class="mb-3">
                                <span class="inline-flex items-center text-2xs font-extrabold text-blue-700 uppercase tracking-wider bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100">
                                    {{ $job->division }}
                                </span>
                            </div>

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
                        </div>
                        
                        <!-- Card Footer -->
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
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-3xl border border-gray-200">
                        <p class="text-xs font-semibold text-gray-500">Belum ada lowongan terbaru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 6. Call To Action Footer Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col md:flex-row items-center justify-between gap-6 sm:gap-8">
            <div class="text-left max-w-2xl">
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight mb-2">Siap Memulai Karir Impian Anda?</h2>
                <p class="text-blue-100 text-xs sm:text-sm font-medium leading-relaxed">Daftar sekarang gratis, lengkapi profil & keahlian Anda, lalu lamar berbagai pekerjaan terbaik di Indonesia.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-7 py-3 bg-white text-blue-700 font-extrabold rounded-2xl text-xs shadow-xl hover:bg-gray-100 transition text-center">
                    Mendaftar Akun
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 bg-blue-800/60 hover:bg-blue-800 text-white font-extrabold rounded-2xl text-xs border border-blue-400/40 transition text-center">
                    Masuk Portal
                </a>
            </div>
        </div>
    </div>
</x-public-layout>
