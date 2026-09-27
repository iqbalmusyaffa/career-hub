<x-public-layout>
    <!-- 1. Clean Modern Job Portal Hero Section -->
    <div class="relative bg-gradient-to-b from-slate-50/80 via-blue-50/20 to-white py-14 sm:py-20 border-b border-slate-200/80">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <!-- Trust Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-100/80 text-blue-700 text-xs font-semibold mb-6 shadow-xs">
                <i class="fa-solid fa-circle-check text-blue-600 text-xs"></i>
                <span>Platform Lowongan Kerja & Magang Terverifikasi</span>
            </div>
            
            <!-- Headline -->
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4 max-w-3xl mx-auto">
                Temukan Lowongan Kerja & Bangun <span class="text-blue-600">Karir Masa Depanmu</span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed mb-8">
                Jelajahi peluang karir terbaik dari berbagai perusahaan terkemuka di Indonesia dengan proses rekrutmen yang transparan dan aman.
            </p>
            
            <!-- Main Integrated Search Box -->
            <div class="bg-white p-2.5 sm:p-3 rounded-2xl border border-slate-200 shadow-lg max-w-4xl mx-auto mb-6">
                <form action="{{ route('jobs.index') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                    <div class="relative flex-1 flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs sm:text-sm"></i>
                        </div>
                        <input type="text" name="search" class="block w-full pl-10 pr-3 py-3 border-0 rounded-xl text-slate-900 placeholder-slate-400 text-xs sm:text-sm font-medium bg-slate-50/70 focus:bg-white focus:ring-2 focus:ring-blue-600 transition" placeholder="Cari posisi, bidang, atau keahlian...">
                    </div>
                    
                    <div class="relative flex-1 flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-location-dot text-xs sm:text-sm"></i>
                        </div>
                        <input type="text" name="location" class="block w-full pl-10 pr-3 py-3 border-0 rounded-xl text-slate-900 placeholder-slate-400 text-xs sm:text-sm font-medium bg-slate-50/70 focus:bg-white focus:ring-2 focus:ring-blue-600 transition" placeholder="Kota atau wilayah penempatan...">
                    </div>
                    
                    <button type="submit" class="px-7 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs sm:text-sm rounded-xl transition shadow-xs flex items-center justify-center gap-2 shrink-0 cursor-pointer">
                        <i class="fa-solid fa-search text-xs"></i> Cari Lowongan
                    </button>
                </form>
            </div>

            <!-- Quick Popular Searches -->
            <div class="flex flex-wrap items-center justify-center gap-2 text-xs text-slate-500 mb-10">
                <span class="font-medium text-slate-400">Pencarian Populer:</span>
                <a href="{{ route('jobs.index', ['search' => 'Backend']) }}" class="bg-white hover:bg-blue-50 hover:text-blue-600 px-3 py-1.5 rounded-lg transition text-slate-600 border border-slate-200 text-xs font-medium shadow-2xs">Backend Developer</a>
                <a href="{{ route('jobs.index', ['search' => 'Frontend']) }}" class="bg-white hover:bg-blue-50 hover:text-blue-600 px-3 py-1.5 rounded-lg transition text-slate-600 border border-slate-200 text-xs font-medium shadow-2xs">Frontend Developer</a>
                <a href="{{ route('jobs.index', ['search' => 'UI/UX']) }}" class="bg-white hover:bg-blue-50 hover:text-blue-600 px-3 py-1.5 rounded-lg transition text-slate-600 border border-slate-200 text-xs font-medium shadow-2xs">UI/UX Designer</a>
                <a href="{{ route('jobs.index', ['search' => 'Marketing']) }}" class="bg-white hover:bg-blue-50 hover:text-blue-600 px-3 py-1.5 rounded-lg transition text-slate-600 border border-slate-200 text-xs font-medium shadow-2xs">Digital Marketing</a>
                <a href="{{ route('jobs.index', ['search' => 'Magang']) }}" class="bg-white hover:bg-blue-50 hover:text-blue-600 px-3 py-1.5 rounded-lg transition text-slate-600 border border-slate-200 text-xs font-medium shadow-2xs">Magang / Internship</a>
            </div>

            <!-- Value Highlights Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-left pt-6 border-t border-slate-200/80">
                <div class="flex items-start gap-3.5 p-3.5 rounded-xl bg-white border border-slate-100 shadow-2xs">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-shield-halved text-base"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900">Perusahaan Terverifikasi</div>
                        <div class="text-[11px] text-slate-500 mt-0.5 leading-snug">Profil penyelenggara terverifikasi resmi untuk keamanan rekrutmen.</div>
                    </div>
                </div>

                <div class="flex items-start gap-3.5 p-3.5 rounded-xl bg-white border border-slate-100 shadow-2xs">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-money-bill-wave text-base"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900">Gaji & Benefit Transparan</div>
                        <div class="text-[11px] text-slate-500 mt-0.5 leading-snug">Rentang gaji, uang saku magang, dan benefit tertera jelas tanpa pungutan.</div>
                    </div>
                </div>

                <div class="flex items-start gap-3.5 p-3.5 rounded-xl bg-white border border-slate-100 shadow-2xs">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-bolt text-base"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900">Proses Cepat & Terpantau</div>
                        <div class="text-[11px] text-slate-500 mt-0.5 leading-snug">Pantau status seleksi lamaran dan jadwal interview di dashboard.</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- 2. Trusted Companies Bar -->
    <div class="bg-white border-b border-slate-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-xs font-semibold uppercase text-slate-400 tracking-wider mb-6">
                Dipercaya Oleh Berbagai Perusahaan Terkemuka
            </p>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 sm:gap-4 items-center justify-center">
                @if(isset($trustedCompanies) && count($trustedCompanies) > 0)
                    @foreach($trustedCompanies as $comp)
                        <a href="{{ route('companies.show', urlencode($comp->company_name)) }}" title="{{ $comp->jobs_count ?? 0 }} Lowongan Aktif" class="bg-slate-50 hover:bg-white hover:shadow-sm py-2.5 px-3.5 rounded-xl border border-slate-200 flex items-center justify-center gap-2 transition group">
                            @if($comp->logo_path)
                                <img src="{{ Storage::url($comp->logo_path) }}" alt="{{ $comp->company_name }}" class="w-6 h-6 rounded object-cover border border-slate-200 shrink-0">
                            @else
                                <span class="w-6 h-6 rounded bg-slate-900 text-white flex items-center justify-center font-bold text-[11px] shrink-0">
                                    {{ strtoupper(substr($comp->company_name, 0, 2)) }}
                                </span>
                            @endif
                            <span class="text-xs font-semibold text-slate-700 truncate group-hover:text-blue-600 transition">{{ $comp->company_name }}</span>
                            @if(!empty($comp->is_verified))
                                <i class="fa-solid fa-circle-check text-[10px] text-blue-500 shrink-0" title="Terverifikasi"></i>
                            @endif
                        </a>
                    @endforeach
                @else
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-white hover:shadow-sm py-3 px-3.5 rounded-xl border border-slate-200 flex items-center justify-center gap-2.5 transition group">
                        <span class="w-6 h-6 rounded bg-slate-900 text-white flex items-center justify-center font-bold text-xs shrink-0">TN</span>
                        <span class="text-xs font-semibold text-slate-700 truncate group-hover:text-blue-600 transition">TechNova Asia</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-white hover:shadow-sm py-3 px-3.5 rounded-xl border border-slate-200 flex items-center justify-center gap-2.5 transition group">
                        <span class="w-6 h-6 rounded bg-slate-900 text-white flex items-center justify-center font-bold text-xs shrink-0">GC</span>
                        <span class="text-xs font-semibold text-slate-700 truncate group-hover:text-blue-600 transition">GlobalCorp</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-white hover:shadow-sm py-3 px-3.5 rounded-xl border border-slate-200 flex items-center justify-center gap-2.5 transition group">
                        <span class="w-6 h-6 rounded bg-slate-900 text-white flex items-center justify-center font-bold text-xs shrink-0">FS</span>
                        <span class="text-xs font-semibold text-slate-700 truncate group-hover:text-blue-600 transition">FinServe Digital</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-white hover:shadow-sm py-3 px-3.5 rounded-xl border border-slate-200 flex items-center justify-center gap-2.5 transition group">
                        <span class="w-6 h-6 rounded bg-slate-900 text-white flex items-center justify-center font-bold text-xs shrink-0">ES</span>
                        <span class="text-xs font-semibold text-slate-700 truncate group-hover:text-blue-600 transition">EduSmart Tech</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-white hover:shadow-sm py-3 px-3.5 rounded-xl border border-slate-200 flex items-center justify-center gap-2.5 transition group">
                        <span class="w-6 h-6 rounded bg-slate-900 text-white flex items-center justify-center font-bold text-xs shrink-0">AL</span>
                        <span class="text-xs font-semibold text-slate-700 truncate group-hover:text-blue-600 transition">AeroLogistics</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="bg-slate-50 hover:bg-white hover:shadow-sm py-3 px-3.5 rounded-xl border border-slate-200 flex items-center justify-center gap-2.5 transition group">
                        <span class="w-6 h-6 rounded bg-slate-900 text-white flex items-center justify-center font-bold text-xs shrink-0">TK</span>
                        <span class="text-xs font-semibold text-slate-700 truncate group-hover:text-blue-600 transition">TokoKreatif</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. Real-World Job Categories Section -->
    <div class="bg-slate-50/70 dark:bg-slate-900/50 py-16 sm:py-24 border-t border-slate-200/80 dark:border-slate-800"
         x-data="{ 
            page: 1, 
            pageSize: 8, 
            total: {{ \App\Models\JobCategory::active()->count() }},
            get totalPages() { return Math.ceil(this.total / this.pageSize) || 1; },
            nextPage() { if (this.page < this.totalPages) this.page++; },
            prevPage() { if (this.page > 1) this.page--; },
            setPage(p) { this.page = p; }
         }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 text-xs font-bold uppercase tracking-wider mb-3.5 shadow-2xs">
                    <i class="fa-solid fa-layer-group text-[11px]"></i>
                    <span>Kategori Bidang Pekerjaan</span>
                </div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                    Jelajahi Berdasarkan Bidang Karir
                </h2>
                <p class="mt-3 text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    Temukan posisi yang sesuai dengan keahlian spesifik dan minat karir Anda di berbagai sektor industri terkemuka.
                </p>
            </div>

            @php
                $displayCategories = \App\Models\JobCategory::active()->orderBy('sort_order')->orderBy('name')->get();
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @forelse($displayCategories as $index => $cat)
                    @php
                        $styles = $cat->color_styles;
                        $jobCount = $cat->active_jobs_count;
                    @endphp
                    <div x-show="(({{ $index }} >= (page - 1) * pageSize) && ({{ $index }} < page * pageSize))"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="h-full">
                        <a href="{{ route('jobs.index', ['division' => $cat->name]) }}" 
                           class="group bg-white dark:bg-slate-800 rounded-2xl p-5 sm:p-6 border border-slate-200/90 dark:border-slate-700/80 hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between h-full relative overflow-hidden">
                            
                            <!-- Top Row: Icon & Job Count Badge -->
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-2xl {{ $styles['bg_light'] }} {{ $styles['text'] }} flex items-center justify-center text-lg font-bold border {{ $styles['border'] }} {{ $styles['hover_bg'] }} transition-all duration-200 shadow-2xs shrink-0">
                                        <i class="{{ $cat->icon ?: 'fa-solid fa-briefcase' }}"></i>
                                    </div>

                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full border {{ $styles['badge'] }} transition">
                                        <span>{{ $jobCount }}</span>
                                        <span class="font-medium text-[10px] opacity-80">Posisi</span>
                                    </span>
                                </div>

                                <!-- Title & Description -->
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white mb-1.5 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition leading-snug">
                                    {{ $cat->name }}
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                    {{ $cat->subtext ?: ($cat->description ?: 'Peluang karir terbaik bidang ' . $cat->name) }}
                                </p>
                            </div>

                            <!-- Footer Action -->
                            <div class="mt-5 pt-3.5 border-t border-slate-100 dark:border-slate-700/70 flex items-center justify-between text-xs font-semibold text-slate-400 dark:text-slate-500 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                <span class="text-[11px] font-bold">Jelajahi Lowongan</span>
                                <div class="w-6 h-6 rounded-full bg-slate-50 dark:bg-slate-700/50 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/40 flex items-center justify-center transition">
                                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700">
                        <i class="fa-solid fa-layer-group text-3xl mb-2 text-slate-300 dark:text-slate-600"></i>
                        <p class="text-xs font-semibold">Belum ada kategori aktif yang tersedia.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Controls (Muncul jika Kategori Lebih dari 8) -->
            <div x-show="totalPages > 1" class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <div class="inline-flex items-center gap-2 p-1.5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-2xs">
                    <!-- Tombol Sebelumnya -->
                    <button @click="prevPage()" 
                            :disabled="page === 1"
                            :class="page === 1 ? 'opacity-40 cursor-not-allowed text-slate-400' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-blue-600'"
                            type="button" 
                            class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                        <span>Sebelumnya</span>
                    </button>

                    <!-- Indikator Halaman -->
                    <div class="px-3 py-1.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                        <span x-text="page"></span> / <span x-text="totalPages"></span>
                    </div>

                    <!-- Tombol Selanjutnya -->
                    <button @click="nextPage()" 
                            :disabled="page === totalPages"
                            :class="page === totalPages ? 'opacity-40 cursor-not-allowed text-slate-400' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-blue-600'"
                            type="button" 
                            class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                        <span>Selanjutnya</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </div>

            <!-- Bottom Browse All Link -->
            <div class="mt-8 text-center">
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 border border-slate-200 dark:border-slate-700 hover:border-blue-500 dark:hover:border-blue-500 text-xs font-bold shadow-2xs hover:shadow-sm transition">
                    <span>Lihat Semua Lowongan di Semua Bidang</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. How It Works Section (Clean & Human Workflow) -->
    <div class="bg-white py-16 sm:py-20 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider bg-blue-50 px-3 py-1 rounded-full border border-blue-100 inline-block mb-3">Langkah Mudah</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">3 Langkah Memulai Karir Impian</h2>
                <p class="mt-2 text-xs sm:text-sm text-slate-500 max-w-xl mx-auto">Proses pencarian dan pelamaran kerja yang transparan, mudah, dan aman.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Step 1 -->
                <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-200/90 text-left flex flex-col justify-between relative hover:shadow-sm transition">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-lg border border-blue-100">
                                <i class="fa-solid fa-id-card-clip"></i>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 bg-white border border-slate-200 rounded-full text-slate-500">Langkah 01</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-2">Buat Akun & Lengkapi CV</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">Daftarkan akun kandidat gratis, lengkapi data profil pengalaman Anda, atau manfaatkan fitur pembuat CV online otomatis.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-200/90 text-left flex flex-col justify-between relative hover:shadow-sm transition">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-lg border border-indigo-100">
                                <i class="fa-solid fa-magnifying-glass-location"></i>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 bg-white border border-slate-200 rounded-full text-slate-500">Langkah 02</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-2">Pilih Posisi yang Sesuai</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">Gunakan filter pencarian untuk menemukan lowongan berdasarkan lokasi penempatan, rentang gaji, dan tipe kerja (Full-Time/Magang).</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-200/90 text-left flex flex-col justify-between relative hover:shadow-sm transition">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-lg border border-emerald-100">
                                <i class="fa-solid fa-paper-plane"></i>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 bg-white border border-slate-200 rounded-full text-slate-500">Langkah 03</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-2">Lamar & Pantau Seleksi</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">Kirimkan berkas lamaran langsung ke perusahaan tujuan dan pantau tahapan seleksi serta undangan interview secara transparan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Featured Latest Jobs Section -->
    <div class="py-16 sm:py-20 bg-slate-50/60 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4 border-b border-slate-200 pb-4">
                <div>
                    <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider block mb-1">Lowongan Pilihan</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Peluang Karir Terbaru</h2>
                </div>
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 transition">
                    Lihat Semua Lowongan <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @forelse($latestJobs ?? [] as $job)
                    <div class="bg-white rounded-2xl border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all flex flex-col group h-full">
                        <div class="p-5 flex-grow flex flex-col">
                            <div class="flex justify-between items-start mb-3.5">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                    {{ strtoupper(substr($job->title, 0, 2)) }}
                                </div>
                                <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 text-[11px] font-semibold rounded-md border border-slate-200">
                                    {{ ucfirst($job->work_type ?? 'Full-time') }}
                                </span>
                            </div>
                            
                            <a href="{{ route('jobs.show', $job) }}" class="group-hover:text-blue-600 transition">
                                <h3 class="text-sm font-bold text-slate-900 mb-1 leading-snug line-clamp-2">{{ $job->title }}</h3>
                            </a>
                            <p class="text-xs font-medium text-slate-600 mb-3 flex items-center gap-1.5">
                                <i class="fa-solid fa-building text-slate-400"></i> {{ $job->company_name ?? 'Perusahaan Mitra' }}
                            </p>
                            
                            <div class="mb-3">
                                <span class="inline-flex items-center text-[11px] font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                    {{ $job->division ?? 'Umum' }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center text-xs text-slate-500 mb-3 gap-x-4 gap-y-1">
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-slate-400"></i>
                                    <span>{{ $job->location }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-clock text-slate-400"></i>
                                    <span>{{ $job->created_at ? $job->created_at->diffForHumans() : 'Baru saja' }}</span>
                                </div>
                            </div>
                            
                            <!-- Excerpt -->
                            <p class="text-slate-600 text-xs leading-relaxed line-clamp-2 mb-4 flex-grow">
                                {{ Str::limit(strip_tags($job->description), 110) }}
                            </p>
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold block">Kompensasi / Gaji</span>
                                <span class="text-xs font-bold text-slate-900 block truncate">{{ $job->salary ?? 'Negosiasi' }}</span>
                            </div>
                            <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center justify-center px-3.5 py-1.5 bg-slate-900 hover:bg-blue-600 text-white font-semibold text-xs rounded-lg transition shadow-xs">
                                Detail Posisi &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-slate-200">
                        <p class="text-xs font-medium text-slate-500">Belum ada lowongan terbaru saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 6. Dual Call To Action Section (Pelamar & Penyelenggara) -->
    <div class="bg-white py-16 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Card Pelamar Kerja -->
                <div class="bg-gradient-to-br from-blue-50/60 to-slate-50 p-8 rounded-3xl border border-blue-100 flex flex-col justify-between">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100/80 text-blue-700 text-xs font-semibold rounded-full mb-4">
                            <i class="fa-solid fa-user-graduate text-blue-600"></i> Untuk Pencari Kerja
                        </span>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Siap Memulai Langkah Karir Baru?</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                            Buat akun kandidat secara gratis, lengkapi profil resume Anda, dan kirimkan lamaran ke berbagai posisi pekerjaan terbaik di Indonesia.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                            Daftar Sebagai Pelamar
                        </a>
                        <a href="{{ route('jobs.index') }}" class="px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 shadow-2xs transition">
                            Jelajahi Lowongan
                        </a>
                    </div>
                </div>

                <!-- Card Penyelenggara / Perusahaan -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-8 rounded-3xl border border-slate-700 flex flex-col justify-between">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-800 text-blue-400 text-xs font-semibold rounded-full mb-4 border border-slate-700">
                            <i class="fa-solid fa-building"></i> Untuk Perusahaan & HR
                        </span>
                        <h3 class="text-xl font-bold text-white mb-2">Cari Talenta Terbaik untuk Perusahaan Anda?</h3>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-6">
                            Publikasikan lowongan pekerjaan terverifikasi, kelola alur seleksi kandidat secara efisien, dan temukan kandidat berkualitas tinggi.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                            Pasang Lowongan Kerja
                        </a>
                        <a href="{{ route('pages.guide') }}#panduan-penyelenggara" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs rounded-xl border border-slate-700 transition">
                            Panduan Penyelenggara
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
