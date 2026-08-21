<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Tambah Lowongan Pekerjaan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-8 sm:p-10 text-gray-900 space-y-6">
                <form action="{{ route('admin.jobs.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="border-b border-gray-100 pb-4">
                        <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-briefcase text-blue-600"></i> Informasi Dasar Lowongan
                        </h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="title" :value="__('Posisi / Judul Pekerjaan')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('title')" placeholder="Misal: Senior Backend Developer" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="division" :value="__('Kategori / Divisi Perusahaan')" />
                            <x-text-input id="division" name="division" type="text" list="division_options" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('division')" placeholder="Pilih atau ketik divisi baru..." required />
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
                            <x-input-error class="mt-2" :messages="$errors->get('division')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                            <x-input-label for="location" :value="__('Lokasi Penempatan (Kota / Wilayah)')" />
                            <div class="relative mt-1">
                                <x-text-input id="location" name="location" type="text" x-model="location" @focus="open = true; fetchCities()" @input.debounce.300ms="fetchCities(); checkUmk(); open = true" @click.away="open = false" autocomplete="off" class="block w-full bg-gray-50 focus:bg-white pr-10" placeholder="Ketik/pilih kota penempatan..." required />
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                            </div>

                            <!-- Sleek Custom Dropdown Drawer -->
                            <div x-show="open && cities.length > 0" x-transition class="absolute left-0 right-0 mt-1.5 bg-white rounded-2xl shadow-xl border border-slate-200 z-50 max-h-60 overflow-y-auto divide-y divide-slate-100" style="display: none;">
                                <template x-for="c in cities" :key="c.city_district">
                                    <div @click="selectCity(c.city_district)" class="px-4 py-2.5 hover:bg-blue-50 cursor-pointer transition flex items-center justify-between gap-2">
                                        <span class="font-extrabold text-xs text-slate-800" x-text="c.city_district"></span>
                                        <span class="text-4xs font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200" x-text="c.province"></span>
                                    </div>
                                </template>
                            </div>
                            
                            <template x-if="umkInfo">
                                <div class="mt-2 p-2.5 bg-emerald-50 rounded-xl border border-emerald-200 text-3xs font-extrabold text-emerald-900 flex items-center justify-between gap-2 shadow-2xs">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-scale-balanced text-emerald-600"></i>
                                        <span x-text="'Acuan UMK 2026 Resmi (' + umkInfo.city_district + '):'"></span>
                                        <strong class="text-emerald-700 font-black text-xs" x-text="umkInfo.formatted_umk + ' / bulan'"></strong>
                                    </span>
                                    <span class="text-4xs text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded font-bold" x-text="umkInfo.province"></span>
                                </div>
                            </template>
                            <x-input-error class="mt-2" :messages="$errors->get('location')" />
                        </div>

                        <div>
                            <x-input-label for="google_maps_link" :value="__('Link Google Maps Kantor / Sematan Lokasi')" />
                            <x-text-input id="google_maps_link" name="google_maps_link" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('google_maps_link')" placeholder="https://maps.google.com/?q=..." />
                            <x-input-error class="mt-2" :messages="$errors->get('google_maps_link')" />
                        </div>
                        
                        <div>
                            <x-input-label for="work_type" :value="__('Sistem & Tipe Kerja')" />
                            <select id="work_type" name="work_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition-colors" required>
                                <option value="Full-time" {{ old('work_type') == 'Full-time' ? 'selected' : '' }}>Full-Time (WFO)</option>
                                <option value="Hybrid" {{ old('work_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid (WFO & Remote)</option>
                                <option value="Remote" {{ old('work_type') == 'Remote' ? 'selected' : '' }}>Remote (100% Work from Anywhere)</option>
                                <option value="Part-time" {{ old('work_type') == 'Part-time' ? 'selected' : '' }}>Part-Time</option>
                                <option value="Contract" {{ old('work_type') == 'Contract' ? 'selected' : '' }}>Contract / Kontrak Project</option>
                                <option value="Internship" {{ old('work_type') == 'Internship' ? 'selected' : '' }}>Internship / Magang</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('work_type')" />
                        </div>

                        <div>
                            <x-input-label for="experience_level" :value="__('Tingkat Pengalaman Kerja')" />
                            <select id="experience_level" name="experience_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition-colors">
                                <option value="Semua Tingkat" {{ old('experience_level') == 'Semua Tingkat' ? 'selected' : '' }}>Semua Tingkat Pengalaman</option>
                                <option value="Fresh Graduate" {{ old('experience_level') == 'Fresh Graduate' ? 'selected' : '' }}>Fresh Graduate (0-1 Tahun)</option>
                                <option value="Junior Level" {{ old('experience_level') == 'Junior Level' ? 'selected' : '' }}>Junior Level (1-3 Tahun)</option>
                                <option value="Mid Level" {{ old('experience_level') == 'Mid Level' ? 'selected' : '' }}>Mid Level (3-5 Tahun)</option>
                                <option value="Senior Level" {{ old('experience_level') == 'Senior Level' ? 'selected' : '' }}>Senior Level (5+ Tahun)</option>
                                <option value="Managerial" {{ old('experience_level') == 'Managerial' ? 'selected' : '' }}>Lead / Managerial</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <x-input-label for="education_level" :value="__('Minimal Pendidikan')" />
                            <select id="education_level" name="education_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition-colors">
                                <option value="Semua Jenjang" {{ old('education_level') == 'Semua Jenjang' ? 'selected' : '' }}>Semua Jenjang / Tanpa Minimal</option>
                                <option value="SMA/SMK" {{ old('education_level') == 'SMA/SMK' ? 'selected' : '' }}>SMA / SMK Sederajat</option>
                                <option value="D3" {{ old('education_level') == 'D3' ? 'selected' : '' }}>D3 (Diploma 3)</option>
                                <option value="D4/S1" {{ old('education_level') == 'D4/S1' ? 'selected' : '' }}>D4 / S1 (Sarjana)</option>
                                <option value="S2" {{ old('education_level') == 'S2' ? 'selected' : '' }}>S2 (Magister)</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="major_requirement" :value="__('Jurusan / Bidang Studi')" />
                            <x-text-input id="major_requirement" name="major_requirement" type="text" list="major_options" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('major_requirement')" placeholder="Misal: Teknik Informatika / Semua Jurusan" />
                            <datalist id="major_options">
                                <option value="Semua Jurusan">
                                <option value="Teknik Informatika / Ilmu Komputer">
                                <option value="Sistem Informasi">
                                <option value="Teknik Komputer / Elektro">
                                <option value="Manajemen / Bisnis">
                                <option value="Akuntansi / Keuangan">
                                <option value="Desain Komunikasi Visual (DKV)">
                                <option value="Ilmu Komunikasi / PR">
                                <option value="Psikologi / HR">
                                <option value="Hukum">
                                <option value="Teknik Industri">
                            </datalist>
                        </div>

                        <div>
                            <x-input-label for="gender_requirement" :value="__('Kriteria Gender')" />
                            <select id="gender_requirement" name="gender_requirement" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition-colors">
                                <option value="Pria & Wanita" {{ old('gender_requirement') == 'Pria & Wanita' ? 'selected' : '' }}>Pria & Wanita (Terbuka Umum)</option>
                                <option value="Khusus Pria" {{ old('gender_requirement') == 'Khusus Pria' ? 'selected' : '' }}>Khusus Pria</option>
                                <option value="Khusus Wanita" {{ old('gender_requirement') == 'Khusus Wanita' ? 'selected' : '' }}>Khusus Wanita</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="age_range" :value="__('Batasan Usia')" />
                            <x-text-input id="age_range" name="age_range" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('age_range')" placeholder="Misal: 21 - 35 Tahun" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <x-input-label for="salary" :value="__('Kisaran Gaji / Uang Saku (Bebas Diatur HR)')" />
                            <x-text-input id="salary" name="salary" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('salary')" placeholder="Misal: Rp 2.500.000 (Uang Saku Magang) / Sesuai UMK" />
                            <div class="mt-1.5 flex flex-wrap items-center gap-1.5 text-4xs font-bold text-slate-600">
                                <button type="button" onclick="document.getElementById('salary').value = 'Gaji Negosiasi'" class="px-2 py-0.5 rounded bg-slate-200 hover:bg-slate-300 transition">Gaji Negosiasi</button>
                                <button type="button" onclick="document.getElementById('salary').value = 'Sesuai UMK 2026 Wilayah'" class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition">Sesuai UMK 2026</button>
                                <button type="button" onclick="document.getElementById('salary').value = 'Magang Sesuai UMK 2026 Wilayah'" class="px-2 py-0.5 rounded bg-teal-100 text-teal-800 hover:bg-teal-200 transition">Magang Sesuai UMK</button>
                                <button type="button" onclick="document.getElementById('salary').value = 'Rp 1.500.000 - Rp 3.000.000 (Uang Saku Magang)'" class="px-2 py-0.5 rounded bg-purple-100 text-purple-800 hover:bg-purple-200 transition">Uang Saku Magang</button>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('salary')" />
                        </div>

                        <div>
                            <x-input-label for="quota" :value="__('Batas Kuota Pelamar (Opsional)')" />
                            <x-text-input id="quota" name="quota" type="number" min="1" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('quota')" placeholder="Kosongkan jika tak terbatas (Contoh: 10)" />
                            <x-input-error class="mt-2" :messages="$errors->get('quota')" />
                        </div>

                        <div>
                            <x-input-label for="deadline" :value="__('Tanggal Batas Akhir (Deadline)')" />
                            <x-text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('deadline', date('Y-m-d', strtotime('+30 days')))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="skills_required" :value="__('Kata Kunci Skill Utama (Pisahkan dengan koma)')" />
                        <x-text-input id="skills_required" name="skills_required" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('skills_required')" placeholder="Misal: PHP, Laravel, MySQL, REST API, Docker" />
                        <p class="text-xs text-blue-600 mt-1">*Skill ini akan dipakai oleh algoritma pencocokan otomatis (Match Score %) kandidat.</p>
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Deskripsi Pekerjaan')" />
                        <textarea id="description" name="description" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white" required placeholder="Tuliskan peran dan tanggung jawab utama...">{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div>
                        <x-input-label for="requirements" :value="__('Persyaratan & Keahlian (Kualifikasi Detail)')" />
                        <textarea id="requirements" name="requirements" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white" required placeholder="Tuliskan kualifikasi, pengalaman, dan keahlian yang dibutuhkan...">{{ old('requirements') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('requirements')" />
                    </div>

                    <div>
                        <x-input-label for="benefits" :value="__('Tunjangan & Benefit (Opsional)')" />
                        <textarea id="benefits" name="benefits" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white" placeholder="Misal: Asuransi Swasta, Laptop Kerja, Jam Kerja Fleksibel, Bonus Kinerja...">{{ old('benefits') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('benefits')" />
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('Status Lowongan')" />
                        <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition-colors" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif (Tayang)</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif (Draf)</option>
                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>

                    <div class="flex items-center gap-4 mt-8 pt-6 border-t border-gray-100">
                        <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md transition">
                            🚀 Simpan & Tayangkan Lowongan
                        </button>
                        <a href="{{ route('admin.jobs.index') }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm transition">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
