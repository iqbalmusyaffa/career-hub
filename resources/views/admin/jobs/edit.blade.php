<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.jobs.index') }}" class="w-10 h-10 bg-white hover:bg-slate-100 text-slate-600 rounded-2xl border border-slate-200 flex items-center justify-center transition shadow-2xs">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    Edit Lowongan: <span class="text-blue-600">{{ $job->title }}</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui rincian kualifikasi, kuota, atau status lowongan pekerjaan.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-2xs border border-slate-200/80">
                <form action="{{ route('admin.jobs.update', $job->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="title" :value="__('Posisi / Judul Pekerjaan')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full rounded-xl text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('title', $job->title)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="division" :value="__('Kategori / Divisi Perusahaan')" />
                            <x-text-input id="division" name="division" type="text" list="division_options" class="mt-1 block w-full rounded-xl text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('division', $job->division)" required />
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div x-data="{
                            open: false,
                            location: '{{ old('location', $job->location) }}',
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
                            <x-input-label for="location" :value="__('Lokasi (Kota / Wilayah)')" />
                            <div class="relative mt-1">
                                <x-text-input id="location" name="location" type="text" x-model="location" @focus="open = true; fetchCities()" @input.debounce.300ms="fetchCities(); checkUmk(); open = true" @click.away="open = false" autocomplete="off" class="block w-full rounded-xl text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500 pr-10" placeholder="Ketik/pilih kota penempatan..." required />
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
                            <x-text-input id="google_maps_link" name="google_maps_link" type="text" class="mt-1 block w-full rounded-xl text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('google_maps_link', $job->google_maps_link)" placeholder="https://maps.google.com/?q=..." />
                            <x-input-error class="mt-2" :messages="$errors->get('google_maps_link')" />
                        </div>
                        
                        <div>
                            <x-input-label for="work_type" :value="__('Tipe Pekerjaan')" />
                            <select id="work_type" name="work_type" class="mt-1 block w-full rounded-xl text-xs font-bold border-slate-300 focus:ring-blue-500 focus:border-blue-500 bg-white" required>
                                <option value="Full-time" {{ old('work_type', $job->work_type) == 'Full-time' ? 'selected' : '' }}>Full-Time</option>
                                <option value="Part-time" {{ old('work_type', $job->work_type) == 'Part-time' ? 'selected' : '' }}>Part-Time</option>
                                <option value="Remote" {{ old('work_type', $job->work_type) == 'Remote' ? 'selected' : '' }}>Remote</option>
                                <option value="Hybrid" {{ old('work_type', $job->work_type) == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                <option value="Contract" {{ old('work_type', $job->work_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                                <option value="Internship" {{ old('work_type', $job->work_type) == 'Internship' ? 'selected' : '' }}>Internship / Magang</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('work_type')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <x-input-label for="salary" :value="__('Kisaran Gaji (Opsional)')" />
                            <x-text-input id="salary" name="salary" type="text" class="mt-1 block w-full rounded-xl text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('salary', $job->salary)" placeholder="Misal: Rp 10.000.000 - Rp 15.000.000" />
                            <x-input-error class="mt-2" :messages="$errors->get('salary')" />
                        </div>

                        <div>
                            <x-input-label for="quota" :value="__('Batas Kuota Pelamar (Opsional)')" />
                            <x-text-input id="quota" name="quota" type="number" min="1" class="mt-1 block w-full rounded-xl text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('quota', $job->quota)" placeholder="Kosongkan jika tak terbatas (Contoh: 10)" />
                            <x-input-error class="mt-2" :messages="$errors->get('quota')" />
                        </div>

                        <div>
                            <x-input-label for="deadline" :value="__('Tanggal Batas Akhir (Deadline)')" />
                            <x-text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full rounded-xl text-xs font-bold border-slate-300 focus:ring-blue-500 focus:border-blue-500" :value="old('deadline', optional($job->deadline)->format('Y-m-d'))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Deskripsi Pekerjaan')" />
                        <textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-xl text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" required>{{ old('description', $job->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div>
                        <x-input-label for="requirements" :value="__('Persyaratan & Keahlian (Kualifikasi)')" />
                        <textarea id="requirements" name="requirements" rows="5" class="mt-1 block w-full rounded-xl text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" required>{{ old('requirements', $job->requirements) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('requirements')" />
                    </div>

                    <div>
                        <x-input-label for="benefits" :value="__('Tunjangan & Benefit (Opsional)')" />
                        <textarea id="benefits" name="benefits" rows="2" class="mt-1 block w-full rounded-xl text-xs font-medium border-slate-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Misal: Asuransi Swasta, Laptop Kerja, Jam Kerja Fleksibel...">{{ old('benefits', $job->benefits) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('benefits')" />
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('Status Lowongan')" />
                        <select id="status" name="status" class="mt-1 block w-full rounded-xl text-xs font-bold border-slate-300 focus:ring-blue-500 focus:border-blue-500 bg-white" required>
                            <option value="active" {{ old('status', $job->status) == 'active' ? 'selected' : '' }}>Aktif (Tayang)</option>
                            <option value="inactive" {{ old('status', $job->status) == 'inactive' ? 'selected' : '' }}>Non-Aktif (Draf)</option>
                            <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Ditutup</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>

                    <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-100">
                        <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-2xs transition flex items-center gap-2 border border-blue-600">
                            <i class="fa-solid fa-floppy-disk"></i> Update Lowongan Pekerjaan
                        </button>
                        <a href="{{ route('admin.jobs.index') }}" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition border border-slate-200">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
