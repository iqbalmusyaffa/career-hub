<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-xs shrink-0">
                    <i class="fa-solid fa-calculator"></i>
                </div>
                <div>
                    <h1 class="text-lg sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                        <span>Kalkulator Tolok Ukur Gaji Industri</span>
                        <span class="text-4xs px-2.5 py-0.5 bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 font-bold rounded-full uppercase">Benchmark 2026</span>
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Estimasi standar kompensasi pasar kerja di Indonesia berdasarkan posisi, pengalaman, keahlian, dan UMK resmi.
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. Search & Filter Card (Clean, Sleek & Modern) -->
            <div class="bg-white dark:bg-slate-800 p-5 sm:p-7 rounded-3xl shadow-2xs border border-slate-200 dark:border-slate-700"
                x-data="{
                    quickPosition(pos) {
                        $refs.positionInput.value = pos;
                        if (!$refs.locationInput.value) {
                            $refs.locationInput.value = 'Jakarta';
                        }
                        $refs.salaryForm.submit();
                    }
                }">
                
                <form x-ref="salaryForm" method="GET" action="{{ route('salary-benchmark.index') }}" class="space-y-4">
                    
                    <!-- Row 1: Posisi, Pengalaman, Lokasi -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <!-- 1. Posisi / Jabatan -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Posisi / Jabatan
                            </label>
                            <div class="relative">
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <input type="text" x-ref="positionInput" name="position" value="{{ $position }}" placeholder="Contoh: Software Engineer, UI/UX..." class="w-full border-slate-300 dark:border-slate-700 dark:bg-slate-900 rounded-xl text-xs font-medium focus:ring-blue-500 focus:border-blue-500 text-slate-800 dark:text-slate-100 pl-10 pr-3 py-2.5">
                            </div>
                        </div>

                        <!-- 2. Tingkat Pengalaman -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Tingkat Pengalaman
                            </label>
                            <div class="relative">
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs">
                                    <i class="fa-solid fa-user-graduate"></i>
                                </div>
                                <select name="level" class="w-full border-slate-300 dark:border-slate-700 dark:bg-slate-900 rounded-xl text-xs font-medium focus:ring-blue-500 focus:border-blue-500 text-slate-800 dark:text-slate-100 pl-10 pr-8 py-2.5">
                                    <option value="Fresh Graduate (0-1 Tahun)" {{ in_array($level, ['Fresh Graduate (0-1 Tahun)', 'Fresh Graduate']) ? 'selected' : '' }}>Fresh Graduate (0-1 Tahun)</option>
                                    <option value="Junior Level (1-2 Tahun)" {{ in_array($level, ['Junior Level (1-2 Tahun)', 'Junior Level']) ? 'selected' : '' }}>Junior Level (1-2 Tahun)</option>
                                    <option value="Mid Level (2-5 Tahun)" {{ in_array($level, ['Mid Level (2-5 Tahun)', 'Mid Level']) ? 'selected' : '' }}>Mid Level (2-5 Tahun)</option>
                                    <option value="Senior Level (5+ Tahun)" {{ in_array($level, ['Senior Level (5+ Tahun)', 'Senior Level']) ? 'selected' : '' }}>Senior Level (5+ Tahun)</option>
                                    <option value="Lead / Managerial" {{ in_array($level, ['Lead / Managerial', 'Lead / Manager']) ? 'selected' : '' }}>Lead / Managerial</option>
                                </select>
                            </div>
                        </div>

                        <!-- 3. Lokasi Kerja -->
                        <div x-data="{
                            open: false,
                            query: '{{ $location }}',
                            cities: [],
                            init() {
                                if (this.query) {
                                    this.fetchCities();
                                }
                            },
                            fetchCities() {
                                fetch('/api/regions/cities?query=' + encodeURIComponent(this.query || ''))
                                    .then(res => res.json())
                                    .then(data => {
                                        this.cities = data;
                                    });
                            },
                            selectCity(cityName) {
                                this.query = cityName;
                                this.open = false;
                            }
                        }" class="relative">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Lokasi Penempatan
                            </label>
                            <div class="relative">
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <input type="text" x-ref="locationInput" name="location" x-model="query" @focus="open = true; fetchCities()" @input.debounce.300ms="fetchCities(); open = true" @click.away="open = false" placeholder="Ketik atau pilih kota..." class="w-full border-slate-300 dark:border-slate-700 dark:bg-slate-900 rounded-xl text-xs font-medium focus:ring-blue-500 focus:border-blue-500 text-slate-800 dark:text-slate-100 pl-10 pr-8 py-2.5" autocomplete="off">
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-3xs">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </div>
                            </div>

                            <!-- Dropdown Drawer -->
                            <div x-show="open && cities.length > 0" x-transition class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 z-50 max-h-56 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700/60" style="display: none;">
                                <template x-for="c in cities" :key="c.city_district">
                                    <div @click="selectCity(c.city_district)" class="px-3.5 py-2.5 hover:bg-blue-50 dark:hover:bg-slate-700/60 cursor-pointer transition flex items-center justify-between gap-2">
                                        <span class="font-bold text-xs text-slate-800 dark:text-slate-100" x-text="c.city_district"></span>
                                        <span class="text-4xs font-bold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600" x-text="c.province"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>

                    <!-- Row 2: Keahlian Spesifik & Tombol Hitung -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Keahlian Spesifik <span class="text-slate-400 font-normal">(Opsional, pisahkan dengan koma)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs">
                                    <i class="fa-solid fa-bolt"></i>
                                </div>
                                <input type="text" name="skills" value="{{ $skillsInput }}" placeholder="Contoh: React, AWS, Laravel, Docker, PostgreSQL..." class="w-full border-slate-300 dark:border-slate-700 dark:bg-slate-900 rounded-xl text-xs font-medium focus:ring-blue-500 focus:border-blue-500 text-slate-800 dark:text-slate-100 pl-10 pr-3 py-2.5">
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="submit" class="flex-1 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-calculator"></i>
                                <span>Hitung Estimasi Gaji</span>
                            </button>
                            
                            <a href="{{ route('salary-benchmark.index') }}" title="Reset Pencarian" class="py-2.5 px-3.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/80 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold rounded-xl text-xs transition border border-slate-200 dark:border-slate-600 flex items-center justify-center gap-1.5 cursor-pointer shrink-0">
                                <i class="fa-solid fa-rotate-left text-slate-400"></i>
                                <span>Reset</span>
                            </a>
                        </div>
                    </div>

                    <!-- Row 3: Quick Position Suggestions -->
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-700/80 flex items-center gap-1.5 flex-wrap">
                        <span class="text-3xs font-bold text-slate-400 uppercase tracking-wider mr-1">Saran Posisi:</span>
                        @foreach(['Software Engineer', 'Frontend Developer', 'Data Analyst', 'UI/UX Designer', 'Product Manager', 'HR Specialist', 'Finance & Accounting'] as $sugPos)
                            <button type="button" @click="quickPosition('{{ $sugPos }}')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-blue-50 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-3xs font-semibold transition border border-slate-200 dark:border-slate-600/60 cursor-pointer">
                                {{ $sugPos }}
                            </button>
                        @endforeach
                    </div>
                </form>
            </div>

            <!-- 2. Main Salary Result Dashboard OR Empty Getting Started State -->
            @if($result)
                <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-800 relative overflow-hidden space-y-6">
                    
                    <!-- Result Title Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-800">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap mb-1.5">
                                <span class="px-2.5 py-0.5 bg-blue-900/60 text-blue-300 text-3xs font-bold rounded-full uppercase tracking-wider border border-blue-700/60">
                                    {{ $result['level'] }}
                                </span>
                                <span class="px-2.5 py-0.5 bg-slate-800 text-slate-300 text-3xs font-bold rounded-full uppercase tracking-wider border border-slate-700">
                                    <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i>{{ $result['location'] }}
                                </span>
                            </div>
                            <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">{{ $result['position'] }}</h2>
                        </div>

                        <div class="text-left sm:text-right">
                            <span class="text-3xs text-slate-400 block font-medium">Tolok ukur pasar Indonesia</span>
                            <span class="text-xs font-bold text-emerald-400 flex items-center sm:justify-end gap-1.5 mt-0.5">
                                <i class="fa-solid fa-circle-check"></i> Estimasi Terkalkulasi
                            </span>
                        </div>
                    </div>

                    <!-- 3 Clean Symmetrical Metric Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <!-- Card 1: Minimum Pasar -->
                        <div class="bg-slate-800/80 p-5 rounded-2xl border border-slate-700/80 flex flex-col justify-between text-center space-y-3">
                            <div class="inline-flex self-center px-2.5 py-0.5 bg-slate-700 text-slate-300 text-4xs font-bold rounded-full uppercase tracking-wider">
                                Batas Bawah
                            </div>
                            <div>
                                <span class="text-3xs font-bold text-slate-400 uppercase tracking-wider block mb-1">
                                    Gaji Minimum Pasar
                                </span>
                                <div class="text-xl sm:text-2xl font-black text-slate-200 tracking-tight">
                                    Rp {{ number_format($result['min'], 0, ',', '.') }}
                                </div>
                            </div>
                            <span class="text-3xs text-slate-400 font-medium">
                                @if(str_contains(strtolower($result['level']), 'fresh'))
                                    Sesuai standar UMK resmi wilayah
                                @elseif(str_contains(strtolower($result['level']), 'magang') || str_contains(strtolower($result['level']), 'intern'))
                                    Standar uang saku magang
                                @else
                                    Batas bawah standar industri
                                @endif
                            </span>
                        </div>

                        <!-- Card 2: Rata-Rata Industri (Clean Highlight, No Overlap) -->
                        <div class="bg-gradient-to-b from-blue-950/70 to-slate-800/90 p-5 rounded-2xl border-2 border-blue-500/80 flex flex-col justify-between text-center space-y-3 shadow-md">
                            <div class="inline-flex self-center items-center gap-1 px-3 py-0.5 bg-blue-600 text-white text-4xs font-black uppercase tracking-widest rounded-full shadow-xs">
                                <i class="fa-solid fa-star text-amber-300"></i> Rekomendasi Nego Gaji
                            </div>
                            <div>
                                <span class="text-3xs font-bold text-blue-200 uppercase tracking-wider block mb-1">
                                    Rata-Rata Industri (Average)
                                </span>
                                <div class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                                    Rp {{ number_format($result['avg'], 0, ',', '.') }}
                                </div>
                            </div>
                            <span class="text-3xs text-blue-200/80 font-medium">Ekspektasi gaji wajar & kompetitif</span>
                        </div>

                        <!-- Card 3: Maksimum Pasar -->
                        <div class="bg-slate-800/80 p-5 rounded-2xl border border-slate-700/80 flex flex-col justify-between text-center space-y-3">
                            <div class="inline-flex self-center px-2.5 py-0.5 bg-purple-900/60 text-purple-300 text-4xs font-bold rounded-full uppercase tracking-wider border border-purple-700/50">
                                Batas Atas
                            </div>
                            <div>
                                <span class="text-3xs font-bold text-purple-300 uppercase tracking-wider block mb-1">
                                    Gaji Maksimum Pasar
                                </span>
                                <div class="text-xl sm:text-2xl font-black text-purple-300 tracking-tight">
                                    Rp {{ number_format($result['max'], 0, ',', '.') }}
                                </div>
                            </div>
                            <span class="text-3xs text-slate-400 font-medium">Potensi batas atas kandidat unggulan</span>
                        </div>

                    </div>

                    <!-- Visual Range Spectrum Bar -->
                    <div class="p-4 bg-slate-800/50 rounded-2xl border border-slate-700/60 space-y-2">
                        <div class="flex justify-between text-3xs font-bold text-slate-400">
                            <span>Min: Rp {{ number_format($result['min'], 0, ',', '.') }}</span>
                            <span class="text-blue-300 font-extrabold">Rata-Rata: Rp {{ number_format($result['avg'], 0, ',', '.') }}</span>
                            <span>Max: Rp {{ number_format($result['max'], 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full h-2 bg-slate-700 rounded-full overflow-hidden flex items-center">
                            <div class="h-full bg-gradient-to-r from-emerald-500 via-blue-500 to-purple-500 rounded-full w-full"></div>
                        </div>
                    </div>

                    <!-- UMK Reference Banner -->
                    @if(isset($result['umk']) && $result['umk'])
                        <div class="p-4 bg-slate-800/80 rounded-2xl border border-slate-700/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm shrink-0 border border-emerald-500/30">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div>
                                    <div class="text-3xs font-bold text-slate-400 uppercase">Standar UMK Resmi 2026 ({{ $result['umk']->city_district }})</div>
                                    <div class="text-sm font-black text-amber-300">{{ $result['umk']->formatted_umk }} <span class="text-3xs font-normal text-slate-400">/ bulan</span></div>
                                </div>
                            </div>
                            <div class="text-3xs text-slate-400 font-medium sm:text-right" title="{{ $result['umk']->legal_decree }}">
                                <i class="fa-solid fa-scale-balanced mr-1 text-slate-500"></i>{{ $result['umk']->legal_decree }}
                            </div>
                        </div>
                    @endif

                    <!-- Skill Value Bonus Banner -->
                    @if(!empty($result['skill_list']))
                        <div class="p-4 bg-slate-800/80 rounded-2xl border border-slate-700/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-sm shrink-0 border border-purple-500/30">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                </div>
                                <div>
                                    <div class="text-3xs font-bold text-slate-400 uppercase">Nilai Tambah Keahlian (Skill Premium)</div>
                                    <div class="flex items-center gap-1.5 flex-wrap mt-1">
                                        @foreach($result['skill_list'] as $sk)
                                            <span class="px-2 py-0.5 rounded-md bg-purple-900/60 text-purple-200 text-3xs font-bold border border-purple-700/60">{{ $sk }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @if($result['skill_bonus_pct'] > 0)
                                <div class="px-3 py-1.5 bg-emerald-950/80 border border-emerald-500/50 rounded-xl text-xs font-bold text-emerald-300 shrink-0 text-center">
                                    ⚡ +{{ $result['skill_bonus_pct'] }}% Nilai Pasar Tambahan
                                </div>
                            @else
                                <span class="text-3xs text-slate-400 sm:text-right">
                                    Keahlian spesifik meningkatkan daya tawar gaji Anda.
                                </span>
                            @endif
                        </div>
                    @endif

                </div>
            @else
                <!-- Empty Initial / Reset State Card -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 sm:p-10 text-center border border-slate-200 dark:border-slate-700 shadow-2xs space-y-4">
                    <div class="w-16 h-16 rounded-3xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl mx-auto shadow-2xs">
                        <i class="fa-solid fa-magnifying-glass-chart"></i>
                    </div>
                    <div class="max-w-md mx-auto space-y-1">
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Cari & Ketahui Nilai Pasar Gaji Anda</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Ketik posisi pekerjaan dan kota penempatan di atas, atau klik salah satu saran posisi untuk melihat kalkulasi gaji, batas UMK, dan estimasi take-home pay.
                        </p>
                    </div>
                    <div class="flex items-center justify-center gap-2 pt-2 flex-wrap">
                        <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 rounded-xl text-3xs font-semibold flex items-center gap-1.5">
                            <i class="fa-solid fa-shield-halved text-emerald-500"></i> UMK 2026 Resmi
                        </span>
                        <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 rounded-xl text-3xs font-semibold flex items-center gap-1.5">
                            <i class="fa-solid fa-layer-group text-blue-500"></i> Multi-Jenjang Karir
                        </span>
                        <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 rounded-xl text-3xs font-semibold flex items-center gap-1.5">
                            <i class="fa-solid fa-calculator text-purple-500"></i> Estimasi Bersih THP
                        </span>
                    </div>
                </div>
            @endif

            <!-- 3. Explore Other Popular Roles (Dynamic to current level & location) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200 dark:border-slate-700">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3.5 mb-5 border-b border-slate-100 dark:border-slate-700 gap-2">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 font-bold text-xs flex items-center justify-center">
                            <i class="fa-solid fa-layer-group text-2xs"></i>
                        </span>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                            Eksplorasi Standar Gaji Posisi Populer
                        </h4>
                    </div>
                    <span class="text-3xs text-slate-400 font-medium">
                        Dihitung berdasarkan: <strong class="text-slate-600 dark:text-slate-300 font-semibold">{{ $effectiveLevel }}</strong> &bull; <strong class="text-slate-600 dark:text-slate-300 font-semibold">{{ $effectiveLoc }}</strong>
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                    @foreach($benchmarks as $posName => $bData)
                        <a href="{{ route('salary-benchmark.index', ['position' => $posName, 'location' => $effectiveLoc, 'level' => $effectiveLevel]) }}" class="p-4 border border-slate-200 dark:border-slate-700 hover:border-blue-500 dark:hover:border-blue-500 bg-slate-50/50 dark:bg-slate-900/50 hover:bg-blue-50/50 dark:hover:bg-slate-900 rounded-2xl transition group shadow-2xs flex flex-col justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-4xs font-bold px-2 py-0.5 rounded-md bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                        {{ $bData['category'] }}
                                    </span>
                                    <span class="text-4xs font-bold px-2 py-0.5 rounded-md {{ $bData['demand'] === 'Sangat Tinggi' ? 'bg-amber-100 text-amber-700 dark:bg-amber-950/80 dark:text-amber-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300' }}">
                                        {{ $bData['demand'] }}
                                    </span>
                                </div>
                                <div class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition flex items-center justify-between pt-1">
                                    <span>{{ $posName }}</span>
                                    <i class="fa-solid fa-chevron-right text-3xs text-slate-400 group-hover:translate-x-0.5 transition"></i>
                                </div>
                            </div>
                            
                            <div class="pt-2 border-t border-slate-200/60 dark:border-slate-700/60 flex items-center justify-between text-2xs">
                                <div>
                                    <span class="text-4xs text-slate-400 block">Rata-rata:</span>
                                    <strong class="text-emerald-600 dark:text-emerald-400 font-black text-xs">Rp {{ number_format($bData['avg'], 0, ',', '.') }}</strong>
                                </div>
                                <div class="text-right">
                                    <span class="text-4xs text-slate-400 block">Rentang:</span>
                                    <span class="text-3xs text-slate-500 dark:text-slate-300 font-semibold">{{ $bData['formatted_range'] }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
