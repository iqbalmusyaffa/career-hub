<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-calculator text-blue-600"></i> Calculator & Estimasi Gaji Industri (Salary Benchmark)
                </h2>
                <p class="text-xs text-gray-500 mt-1">Analisis estimasi standar gaji pasar berdasarkan posisi, tingkat pengalaman, dan lokasi di Indonesia.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Search Form -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-gray-100">
                <form method="GET" action="{{ route('salary-benchmark.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Posisi / Jabatan</label>
                        <input type="text" name="position" value="{{ request('position', $result['position']) }}" placeholder="Contoh: Software Engineer" class="w-full border-gray-300 rounded-xl text-sm font-bold focus:ring-blue-500 focus:border-blue-500 text-slate-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tingkat Pengalaman</label>
                        <select name="level" class="w-full border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 font-bold text-slate-800">
                            <option value="Fresh Graduate (0-1 Tahun)" {{ request('level', $result['level']) == 'Fresh Graduate (0-1 Tahun)' || request('level', $result['level']) == 'Fresh Graduate' ? 'selected' : '' }}>Fresh Graduate (0-1 Tahun)</option>
                            <option value="Junior Level (1-2 Tahun)" {{ request('level', $result['level']) == 'Junior Level (1-2 Tahun)' || request('level', $result['level']) == 'Junior Level' ? 'selected' : '' }}>Junior Level (1-2 Tahun)</option>
                            <option value="Mid Level (2-5 Tahun)" {{ request('level', $result['level']) == 'Mid Level (2-5 Tahun)' || request('level', $result['level']) == 'Mid Level' ? 'selected' : '' }}>Mid Level (2-5 Tahun)</option>
                            <option value="Senior Level (5+ Tahun)" {{ request('level', $result['level']) == 'Senior Level (5+ Tahun)' || request('level', $result['level']) == 'Senior Level' ? 'selected' : '' }}>Senior Level (5+ Tahun)</option>
                            <option value="Lead / Managerial" {{ request('level', $result['level']) == 'Lead / Managerial' || request('level', $result['level']) == 'Lead / Manager' ? 'selected' : '' }}>Lead / Managerial</option>
                        </select>
                    </div>
                    <div x-data="{
                        open: false,
                        query: '{{ request('location', $result['location']) }}',
                        cities: [],
                        init() {
                            this.fetchCities();
                        },
                        fetchCities() {
                            fetch('/api/regions/cities?query=' + encodeURIComponent(this.query))
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
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Lokasi Kerja</label>
                        <div class="relative">
                            <input type="text" name="location" x-model="query" @focus="open = true; fetchCities()" @input.debounce.300ms="fetchCities(); open = true" @click.away="open = false" placeholder="Ketik/pilih kota (misal: Kota Surabaya...)" class="w-full border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 font-bold text-slate-800 pr-10" autocomplete="off">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                        </div>

                        <!-- Sleek Custom Dropdown Drawer -->
                        <div x-show="open && cities.length > 0" x-transition class="absolute left-0 right-0 mt-1.5 bg-white rounded-2xl shadow-xl border border-slate-200 z-50 max-h-60 overflow-y-auto divide-y divide-slate-100" style="display: none;">
                            <template x-for="c in cities" :key="c.city_district">
                                <div @click="selectCity(c.city_district)" class="px-4 py-2.5 hover:bg-blue-50 cursor-pointer transition flex items-center justify-between gap-2">
                                    <span class="font-extrabold text-xs text-slate-800" x-text="c.city_district"></span>
                                    <span class="text-4xs font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200" x-text="c.province"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Skill & Keahlian (Koma)</label>
                        <input type="text" name="skills" value="{{ request('skills', $result['skills_input'] ?? '') }}" placeholder="Misal: React, AWS, Laravel..." class="w-full border-gray-300 rounded-xl text-sm font-bold focus:ring-blue-500 focus:border-blue-500 text-slate-800">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs flex items-center justify-center gap-2 border border-slate-900">
                            <i class="fa-solid fa-chart-line text-xs"></i> Hitung Gaji
                        </button>
                    </div>
                </form>
            </div>

            <!-- Salary Result Cards -->
            <div class="bg-slate-900 text-white rounded-2xl p-6 sm:p-8 shadow-2xs relative overflow-hidden border border-slate-800">
                <div class="relative z-10 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="px-3 py-1 bg-slate-800 text-slate-300 text-3xs font-bold rounded-md uppercase tracking-wide border border-slate-700">
                                {{ $result['level'] }} • {{ $result['location'] }}
                            </span>
                            <h3 class="text-2xl font-bold text-white mt-2">{{ $result['position'] }}</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-slate-800">
                        <div class="bg-slate-800 p-5 rounded-xl border border-slate-700 text-center">
                            <span class="text-3xs font-bold text-slate-400 uppercase">Gaji Minimum Pasar</span>
                            <div class="text-xl font-bold text-emerald-400 mt-1">
                                Rp {{ number_format($result['min'], 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="bg-slate-800 p-5 rounded-xl border border-slate-600 text-center shadow-2xs">
                            <span class="text-3xs font-bold text-slate-300 uppercase">Rata-Rata Industri (Average)</span>
                            <div class="text-2xl font-bold text-white mt-1">
                                Rp {{ number_format($result['avg'], 0, ',', '.') }}
                            </div>
                            <span class="text-3xs text-slate-400 mt-1 block">Rekomendasi Ekspektasi Gaji</span>
                        </div>

                        <div class="bg-slate-800 p-5 rounded-xl border border-slate-700 text-center">
                            <span class="text-xs font-bold text-blue-200 uppercase">Gaji Maksimum Pasar</span>
                            <div class="text-2xl font-black text-purple-300 mt-2">
                                Rp {{ number_format($result['max'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    @if(isset($result['umk']) && $result['umk'])
                        <div class="pt-4 border-t border-white/10 flex items-center justify-between flex-wrap gap-2 text-xs text-white">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded bg-emerald-500 text-slate-950 font-black text-3xs uppercase">Acuan UMK 2026</span>
                                <span>Standar UMK Resmi {{ $result['umk']->city_district }}: <strong class="text-amber-300 font-black text-sm">{{ $result['umk']->formatted_umk }} / bulan</strong></span>
                            </div>
                            <span class="text-3xs text-blue-200 opacity-80" title="{{ $result['umk']->legal_decree }}">
                                📜 {{ $result['umk']->legal_decree }}
                            </span>
                        </div>
                    @endif

                    @if(!empty($result['skill_list']))
                        <div class="pt-3 border-t border-white/10 flex items-center justify-between flex-wrap gap-2 text-xs text-white">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2 py-0.5 rounded bg-purple-500 text-white font-black text-3xs uppercase">Skill Value Bonus</span>
                                <span>Keahlian Diinput: 
                                    @foreach($result['skill_list'] as $sk)
                                        <span class="px-2 py-0.5 rounded bg-white/20 text-yellow-300 text-3xs font-extrabold">{{ $sk }}</span>
                                    @endforeach
                                </span>
                            </div>
                            @if($result['skill_bonus_pct'] > 0)
                                <span class="text-xs font-black text-emerald-300 bg-emerald-950/80 px-2.5 py-1 rounded-lg border border-emerald-500/50">
                                    ⚡ Premium Keahlian Tinggi: +{{ $result['skill_bonus_pct'] }}% Nilai Tambah Pasar
                                </span>
                            @else
                                <span class="text-3xs text-blue-200 opacity-80">
                                    💡 Tambahkan skill spesialis (seperti AWS, React, Docker, AI) untuk meningkatkan estimasi nilai pasar.
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Popular Benchmarks Grid -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                <h4 class="font-black text-lg text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-blue-600"></i> Rata-Rata Gaji Posisi Populer Lainnya
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($benchmarks as $posName => $bData)
                        <a href="{{ route('salary-benchmark.index', ['position' => $posName]) }}" class="p-4 border border-gray-200 hover:border-blue-500 hover:bg-blue-50/50 rounded-2xl transition group">
                            <div class="font-bold text-gray-900 group-hover:text-blue-600 transition">{{ $posName }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                Rata-rata: <strong class="text-emerald-700">Rp {{ number_format($bData['avg'] ?? $bData['base'] ?? 10000000, 0, ',', '.') }}</strong>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
