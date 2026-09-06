<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.jobs.index') }}" class="w-9 h-9 bg-white hover:bg-slate-100 text-slate-600 rounded-xl border border-slate-200 flex items-center justify-center transition shadow-xs text-xs">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-bold text-xl text-slate-900 tracking-tight">
                    Tambah Lowongan Pekerjaan Baru
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Publikasikan posisi lowongan baru untuk menjaring kandidat terbaik.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50/60 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl p-6 sm:p-8 shadow-xs border border-slate-200/80 space-y-6">
                <form action="{{ route('admin.jobs.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Section 1: Informasi Utama -->
                    <div>
                        <div class="border-b border-slate-100 pb-3 mb-4 flex flex-wrap justify-between items-center gap-2">
                            <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-briefcase text-blue-600"></i> Informasi Utama Lowongan
                            </h3>
                            @if(!auth()->user()->hasRole('Super Admin'))
                                <span class="text-[11px] font-semibold text-slate-500 flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                                    <i class="fa-solid fa-building text-blue-600"></i>
                                    <span>Perusahaan:</span>
                                    <strong class="text-slate-900">{{ $companyProfile->company_name ?? (auth()->user()->name . ' Company') }}</strong>
                                </span>
                            @endif
                        </div>

                        @if(!auth()->user()->hasRole('Super Admin'))
                            <!-- Banner Indikator Perusahaan HR Login -->
                            <div class="mb-4 p-3.5 bg-blue-50/70 border border-blue-200/80 rounded-xl flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-lg bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-2xs shrink-0">
                                        <i class="fa-solid fa-building"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] text-blue-700 font-bold uppercase tracking-wider">Perusahaan Penyelenggara (Sesuai Akun HR)</div>
                                        <div class="font-bold text-slate-900 text-sm mt-0.5 flex items-center gap-1.5">
                                            <span>{{ $companyProfile->company_name ?? (auth()->user()->name . ' Company') }}</span>
                                            <span class="px-1.5 py-0.2 bg-emerald-100 text-emerald-800 text-[9px] font-black rounded border border-emerald-200 uppercase">
                                                <i class="fa-solid fa-circle-check"></i> Otomatis Sesuai HR
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('admin.company.profile.edit') }}" target="_blank" class="text-xs font-bold text-blue-700 hover:text-blue-900 hover:underline shrink-0 flex items-center gap-1">
                                    <span>Ubah Profil</span> <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            </div>
                            <input type="hidden" name="company_name" value="{{ $companyProfile->company_name ?? (auth()->user()->name . ' Company') }}">
                        @else
                            <div class="mb-4">
                                <x-input-label for="company_name" :value="__('Perusahaan Penyelenggara')" class="text-xs font-semibold text-slate-700" />
                                <x-text-input id="company_name" name="company_name" type="text" list="company_list_options" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('company_name', $companyProfile->company_name ?? '')" placeholder="Pilih atau ketik nama perusahaan..." required />
                                <datalist id="company_list_options">
                                    @foreach(\App\Models\CompanyProfile::whereNotNull('company_name')->where('company_name', '!=', '')->pluck('company_name')->unique() as $cName)
                                        <option value="{{ $cName }}">
                                    @endforeach
                                </datalist>
                                <p class="text-[10px] text-slate-400 mt-1">Sebagai Super Admin, Anda dapat mempublikasikan lowongan untuk perusahaan mitra mana pun.</p>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('company_name')" />
                            </div>
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="title" :value="__('Posisi / Judul Pekerjaan')" class="text-xs font-semibold text-slate-700" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('title')" placeholder="Misal: Senior Backend Developer" required autofocus />
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="division" :value="__('Kategori / Divisi')" class="text-xs font-semibold text-slate-700" />
                                <x-text-input id="division" name="division" type="text" list="division_options" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('division')" placeholder="Pilih atau ketik divisi baru..." required />
                                <datalist id="division_options">
                                    <option value="Engineering">
                                    <option value="Design">
                                    <option value="Product">
                                    <option value="Data Science">
                                    <option value="Marketing">
                                    <option value="Human Resources">
                                    <option value="Finance">
                                    <option value="Operations">
                                    <option value="Sales & Business Development">
                                </datalist>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('division')" />
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Penempatan & Sistem Kerja -->
                    <div>
                        <div class="border-b border-slate-100 pb-3 mb-4">
                            <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-blue-600"></i> Penempatan & Sistem Kerja
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div x-data="{
                                open: false,
                                location: '{{ old('location') }}',
                                umkInfo: null,
                                cities: [],
                                init() {
                                    this.fetchCities();
                                    this.checkUmk();
                                },
                                fetchCities() {
                                    fetch('/api/regions/cities?query=' + encodeURIComponent(this.location))
                                        .then(res => res.json())
                                        .then(data => {
                                            this.cities = data;
                                        });
                                },
                                checkUmk() {
                                    if (!this.location || this.location.length < 3) {
                                        this.umkInfo = null;
                                        return;
                                    }
                                    fetch('/api/umk-lookup?location=' + encodeURIComponent(this.location))
                                        .then(res => res.json())
                                        .then(data => {
                                            if (data.found) {
                                                this.umkInfo = data;
                                            } else {
                                                this.umkInfo = null;
                                            }
                                        });
                                },
                                selectCity(cityName) {
                                    this.location = cityName;
                                    this.open = false;
                                    this.checkUmk();
                                }
                            }" class="relative" x-init="init()">
                                <x-input-label for="location" :value="__('Lokasi Penempatan (Kota / Wilayah)')" class="text-xs font-semibold text-slate-700" />
                                <div class="relative mt-1">
                                    <x-text-input id="location" name="location" type="text" x-model="location" @focus="open = true; fetchCities()" @input.debounce.300ms="fetchCities(); checkUmk(); open = true" @click.away="open = false" autocomplete="off" class="block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500 pr-9" placeholder="Ketik kota penempatan..." required />
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                        <i class="fa-solid fa-location-dot text-xs"></i>
                                    </div>
                                </div>

                                <!-- Custom Dropdown Drawer -->
                                <div x-show="open && cities.length > 0" x-transition class="absolute left-0 right-0 mt-1 bg-white rounded-lg shadow-lg border border-slate-200 z-50 max-h-48 overflow-y-auto divide-y divide-slate-100" style="display: none;">
                                    <template x-for="c in cities" :key="c.city_district">
                                        <div @click="selectCity(c.city_district)" class="px-3 py-2 hover:bg-blue-50 cursor-pointer transition flex items-center justify-between text-xs">
                                            <span class="font-semibold text-slate-800" x-text="c.city_district"></span>
                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200" x-text="c.province"></span>
                                        </div>
                                    </template>
                                </div>
                                
                                <template x-if="umkInfo">
                                    <div class="mt-1.5 p-2 bg-emerald-50 rounded-lg border border-emerald-200 text-xs text-emerald-900 flex items-center justify-between gap-2 shadow-2xs">
                                        <span class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-scale-balanced text-emerald-600"></i>
                                            <span x-text="'Acuan UMK 2026 (' + umkInfo.city_district + '):'"></span>
                                            <strong class="text-emerald-700 font-bold" x-text="umkInfo.formatted_umk + '/bln'"></strong>
                                        </span>
                                        <span class="text-[10px] text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded font-medium" x-text="umkInfo.province"></span>
                                    </div>
                                </template>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('location')" />
                            </div>

                            <div>
                                <x-input-label for="work_type" :value="__('Sistem & Tipe Kerja')" class="text-xs font-semibold text-slate-700" />
                                <select id="work_type" name="work_type" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500 bg-white" required>
                                    <option value="Full-time" {{ old('work_type') == 'Full-time' ? 'selected' : '' }}>Full-Time (WFO)</option>
                                    <option value="Hybrid" {{ old('work_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid (WFO & Remote)</option>
                                    <option value="Remote" {{ old('work_type') == 'Remote' ? 'selected' : '' }}>Remote (100% Work from Anywhere)</option>
                                    <option value="Part-time" {{ old('work_type') == 'Part-time' ? 'selected' : '' }}>Part-Time</option>
                                    <option value="Contract" {{ old('work_type') == 'Contract' ? 'selected' : '' }}>Contract / Kontrak Proyek</option>
                                    <option value="Internship" {{ old('work_type') == 'Internship' ? 'selected' : '' }}>Internship / Magang</option>
                                </select>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('work_type')" />
                            </div>

                            <div>
                                <x-input-label for="experience_level" :value="__('Tingkat Pengalaman')" class="text-xs font-semibold text-slate-700" />
                                <select id="experience_level" name="experience_level" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    <option value="Semua Tingkat" {{ old('experience_level') == 'Semua Tingkat' ? 'selected' : '' }}>Semua Tingkat Pengalaman</option>
                                    <option value="Fresh Graduate" {{ old('experience_level') == 'Fresh Graduate' ? 'selected' : '' }}>Fresh Graduate (0-1 Tahun)</option>
                                    <option value="Junior Level" {{ old('experience_level') == 'Junior Level' ? 'selected' : '' }}>Junior Level (1-3 Tahun)</option>
                                    <option value="Mid Level" {{ old('experience_level') == 'Mid Level' ? 'selected' : '' }}>Mid Level (3-5 Tahun)</option>
                                    <option value="Senior Level" {{ old('experience_level') == 'Senior Level' ? 'selected' : '' }}>Senior Level (5+ Tahun)</option>
                                    <option value="Managerial" {{ old('experience_level') == 'Managerial' ? 'selected' : '' }}>Lead / Managerial</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="google_maps_link" :value="__('Tautan Google Maps Kantor / Lokasi (Opsional)')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
                            <x-text-input id="google_maps_link" name="google_maps_link" type="text" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 focus:ring-blue-500 focus:border-blue-500" :value="old('google_maps_link')" placeholder="https://maps.google.com/?q=..." />
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('google_maps_link')" />
                        </div>
                    </div>

                    <!-- Section: Batch, Periode & Durasi Program -->
                    <div>
                        <div class="border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-blue-600 dark:text-blue-400"></i> Program Batch, Periode & Durasi
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="batch" :value="__('Batch / Periode Program (Opsional)')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
                                <x-text-input id="batch" name="batch" type="text" list="batch_options_list_create_page" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 focus:ring-blue-500 focus:border-blue-500" :value="old('batch')" placeholder="Misal: Batch 1 - Semester Genap 2026" />
                                <datalist id="batch_options_list_create_page">
                                    @foreach($availableBatches ?? [] as $bName)
                                        <option value="{{ $bName }}">
                                    @endforeach
                                    <option value="Batch 1 - 2026">
                                    <option value="Batch 2 - 2026">
                                    <option value="Batch 1 - Semester Ganjil 2026">
                                    <option value="Batch 2 - Semester Genap 2026">
                                </datalist>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('batch')" />
                            </div>

                            <div>
                                <x-input-label for="duration" :value="__('Durasi Magang / Kontrak (Opsional)')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
                                <x-text-input id="duration" name="duration" type="text" list="duration_options_list_create_page" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 focus:ring-blue-500 focus:border-blue-500" :value="old('duration')" placeholder="Misal: 6 Bulan / 3 Bulan / Tetap" />
                                <datalist id="duration_options_list_create_page">
                                    <option value="3 Bulan">
                                    <option value="6 Bulan">
                                    <option value="1 Tahun">
                                    <option value="Tetap (Permanent)">
                                    <option value="Fleksibel / Proyek">
                                </datalist>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('duration')" />
                            </div>

                            <div>
                                <x-input-label for="start_date" :value="__('Tanggal Mulai / Onboarding (Opsional)')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
                                <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 focus:ring-blue-500 focus:border-blue-500" :value="old('start_date')" />
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('start_date')" />
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Kriteria & Kualifikasi -->
                    <div>
                        <div class="border-b border-slate-100 pb-3 mb-4">
                            <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-graduation-cap text-blue-600"></i> Kriteria & Persyaratan Pendidikan
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <x-input-label for="education_level" :value="__('Minimal Pendidikan')" class="text-xs font-semibold text-slate-700" />
                                <select id="education_level" name="education_level" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    <option value="Semua Jenjang" {{ old('education_level') == 'Semua Jenjang' ? 'selected' : '' }}>Semua Jenjang</option>
                                    <option value="SMA/SMK" {{ old('education_level') == 'SMA/SMK' ? 'selected' : '' }}>SMA / SMK Sederajat</option>
                                    <option value="D3" {{ old('education_level') == 'D3' ? 'selected' : '' }}>D3 (Diploma 3)</option>
                                    <option value="D4/S1" {{ old('education_level') == 'D4/S1' ? 'selected' : '' }}>D4 / S1 (Sarjana)</option>
                                    <option value="S2" {{ old('education_level') == 'S2' ? 'selected' : '' }}>S2 (Magister)</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="major_requirement" :value="__('Jurusan / Bidang Studi')" class="text-xs font-semibold text-slate-700" />
                                <x-text-input id="major_requirement" name="major_requirement" type="text" list="indonesia-majors-list" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('major_requirement')" placeholder="Pilih atau ketik jurusan..." />
                                <x-indonesia-majors-datalist />
                            </div>

                            <div>
                                <x-input-label for="gender_requirement" :value="__('Kriteria Gender')" class="text-xs font-semibold text-slate-700" />
                                <select id="gender_requirement" name="gender_requirement" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    <option value="Pria & Wanita" {{ old('gender_requirement') == 'Pria & Wanita' ? 'selected' : '' }}>Pria & Wanita (Terbuka Umum)</option>
                                    <option value="Khusus Pria" {{ old('gender_requirement') == 'Khusus Pria' ? 'selected' : '' }}>Khusus Pria</option>
                                    <option value="Khusus Wanita" {{ old('gender_requirement') == 'Khusus Wanita' ? 'selected' : '' }}>Khusus Wanita</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="age_range" :value="__('Batasan Usia (Tahun)')" class="text-xs font-semibold text-slate-700" />
                                <div class="relative">
                                    <x-text-input id="age_range" name="age_range" type="text" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500 pr-14" :value="old('age_range')" placeholder="Contoh: 21 - 35 atau 30" />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-[11px] font-semibold text-slate-400">
                                        Tahun
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="skills_required" :value="__('Kata Kunci Keahlian Utama (Pisahkan dengan koma)')" class="text-xs font-semibold text-slate-700" />
                            <x-text-input id="skills_required" name="skills_required" type="text" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('skills_required')" placeholder="Misal: PHP, Laravel, MySQL, REST API, Docker" />
                            <p class="text-[11px] text-slate-500 mt-1">Digunakan untuk kalkulasi kecocokan kualifikasi (Match Score %) profil pelamar.</p>
                        </div>
                    </div>

                    <!-- Section 4: Kompensasi, Kuota & Batas Waktu -->
                    <div>
                        <div class="border-b border-slate-100 pb-3 mb-4">
                            <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-money-bill-wave text-blue-600"></i> Kompensasi & Batas Pendaftaran
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="salary" :value="__('Kisaran Gaji / Uang Saku')" class="text-xs font-semibold text-slate-700" />
                                <x-text-input id="salary" name="salary" type="text" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('salary')" placeholder="Misal: Sesuai UMK / Negosiasi" />
                                <div class="mt-1.5 flex flex-wrap items-center gap-1 text-[10px]">
                                    <button type="button" onclick="document.getElementById('salary').value = 'Gaji Negosiasi'" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 transition">Gaji Negosiasi</button>
                                    <button type="button" onclick="document.getElementById('salary').value = 'Sesuai UMK 2026 Wilayah'" class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition">Sesuai UMK 2026</button>
                                    <button type="button" onclick="document.getElementById('salary').value = 'Magang Sesuai UMK 2026 Wilayah'" class="px-2 py-0.5 rounded bg-teal-50 text-teal-700 hover:bg-teal-100 border border-teal-200 transition">Magang UMK</button>
                                </div>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('salary')" />
                            </div>

                            <div>
                                <x-input-label for="quota" :value="__('Batas Kuota Pelamar (Opsional)')" class="text-xs font-semibold text-slate-700" />
                                <x-text-input id="quota" name="quota" type="number" min="1" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('quota')" placeholder="Kosongkan jika tak terbatas" />
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('quota')" />
                            </div>

                            <div>
                                <x-input-label for="deadline" :value="__('Tanggal Batas Akhir (Deadline)')" class="text-xs font-semibold text-slate-700" />
                                <x-text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('deadline', date('Y-m-d', strtotime('+30 days')))" required />
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('deadline')" />
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Rincian Keterangan -->
                    <div>
                        <div class="border-b border-slate-100 pb-3 mb-4">
                            <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-file-lines text-blue-600"></i> Rincian Deskripsi & Persyaratan
                            </h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="description" :value="__('Deskripsi Pekerjaan & Tanggung Jawab')" class="text-xs font-semibold text-slate-700" />
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" required placeholder="Jelaskan gambaran umum peran dan tugas utama sehari-hari...">{{ old('description') }}</textarea>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('description')" />
                            </div>

                            <div>
                                <x-input-label for="requirements" :value="__('Kualifikasi & Persyaratan Detail')" class="text-xs font-semibold text-slate-700" />
                                <textarea id="requirements" name="requirements" rows="4" class="mt-1 block w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" required placeholder="Tuliskan persyaratan teknis, sertifikasi, dan kualifikasi yang diharapkan...">{{ old('requirements') }}</textarea>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('requirements')" />
                            </div>

                            @php
                                $defaultBenefits = [
                                    'Asuransi Kesehatan & BPJS',
                                    'Jam Kerja Fleksibel / Hybrid',
                                    'Laptop Kerja Perusahaan',
                                    'Makan Siang & Snack Gratis',
                                    'Bonus Kinerja & THR',
                                    'Pelatihan & Sertifikasi Industri',
                                    'Cuti Tahunan Tambahan',
                                    'Fasilitas Olahraga / Gym'
                                ];
                                $oldBenefits = old('benefits') ? array_values(array_filter(array_map('trim', explode(',', old('benefits'))))) : [];
                            @endphp
                            <div x-data="benefitSelector(@js($defaultBenefits), @js($oldBenefits))" class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <x-input-label for="benefits" :value="__('Tunjangan & Fasilitas Benefit (Opsional)')" class="text-xs font-semibold text-slate-700" />
                                    <span class="text-[11px] font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100" x-text="selectedBenefits.length + ' dipilih'"></span>
                                </div>

                                <input type="hidden" name="benefits" :value="selectedBenefits.join(', ')">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <template x-for="(benefit, index) in availableBenefits" :key="index">
                                        <label class="flex items-center justify-between gap-2 p-2.5 rounded-xl border cursor-pointer text-xs transition select-none"
                                               :class="selectedBenefits.includes(benefit) ? 'bg-blue-50/80 border-blue-400 text-blue-900 font-semibold shadow-2xs ring-1 ring-blue-300' : 'bg-white border-slate-200 hover:border-slate-300 text-slate-700 hover:bg-slate-50/50'">
                                            <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                                <input type="checkbox" 
                                                       :checked="selectedBenefits.includes(benefit)"
                                                       @change="toggleBenefit(benefit)"
                                                       class="rounded text-blue-600 focus:ring-blue-500 border-slate-300 h-4 w-4 shrink-0 cursor-pointer">
                                                <span class="truncate" x-text="benefit"></span>
                                            </div>
                                            <template x-if="!defaultBenefits.includes(benefit)">
                                                <button type="button" @click.stop.prevent="removeBenefit(benefit)" class="text-slate-400 hover:text-rose-600 p-1 rounded-md hover:bg-rose-50 transition shrink-0" title="Hapus dari daftar">
                                                    <i class="fa-solid fa-xmark text-xs"></i>
                                                </button>
                                            </template>
                                        </label>
                                    </template>
                                </div>

                                <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-50/80 border border-slate-200">
                                    <div class="relative flex-1">
                                        <input type="text" x-model="newBenefit" @keydown.enter.prevent="addBenefit()"
                                               placeholder="Tambah benefit kustom (misal: Ruang Game, Reimburse Kacamata)..."
                                               class="w-full rounded-lg text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500 py-1.5 px-3 bg-white">
                                    </div>
                                    <button type="button" @click="addBenefit()" 
                                            class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition shrink-0 flex items-center gap-1.5 shadow-xs border border-blue-600 cursor-pointer">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                        <span>Tambah</span>
                                    </button>
                                </div>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('benefits')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status Publikasi Awal')" class="text-xs font-semibold text-slate-700" />
                                <select id="status" name="status" style="color: #0f172a !important; background-color: #ffffff !important;" class="mt-1 block w-full sm:w-64 rounded-lg text-xs font-semibold text-slate-900 border-slate-300 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-2xs" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif (Langsung Tayang)</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif (Draf)</option>
                                    <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                                </select>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('status')" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-6 border-t border-slate-100">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center gap-2 border border-blue-600">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Simpan & Publikasikan Lowongan</span>
                        </button>
                        <a href="{{ route('admin.jobs.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs rounded-xl transition border border-slate-200">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function benefitSelector(defaultBenefits, selectedBenefits) {
            return {
                defaultBenefits: defaultBenefits || [],
                availableBenefits: Array.from(defaultBenefits || []),
                selectedBenefits: Array.from(selectedBenefits || []),
                newBenefit: '',
                init() {
                    var self = this;
                    this.selectedBenefits.forEach(function(b) {
                        if (!self.availableBenefits.includes(b)) {
                            self.availableBenefits.push(b);
                        }
                    });
                },
                toggleBenefit(b) {
                    if (this.selectedBenefits.includes(b)) {
                        this.selectedBenefits = this.selectedBenefits.filter(function(x) { return x !== b; });
                    } else {
                        this.selectedBenefits.push(b);
                    }
                },
                addBenefit() {
                    var val = this.newBenefit.trim();
                    if (!val) return;
                    if (!this.availableBenefits.includes(val)) {
                        this.availableBenefits.push(val);
                    }
                    if (!this.selectedBenefits.includes(val)) {
                        this.selectedBenefits.push(val);
                    }
                    this.newBenefit = '';
                },
                removeBenefit(b) {
                    this.availableBenefits = this.availableBenefits.filter(function(x) { return x !== b; });
                    this.selectedBenefits = this.selectedBenefits.filter(function(x) { return x !== b; });
                }
            };
        }
    </script>
</x-app-layout>
