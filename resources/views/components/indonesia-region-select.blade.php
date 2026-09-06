@props([
    'province' => '',
    'city' => '',
    'district' => '',
    'village' => '',
    'postalCode' => '',
    'provinceName' => 'province',
    'cityName' => 'city',
    'districtName' => 'district',
    'villageName' => 'village',
    'postalCodeName' => 'postal_code',
    'showPostalCode' => true,
    'gridClass' => 'grid grid-cols-1 sm:grid-cols-2 gap-3.5'
])

<div x-data="indonesiaRegionPicker({
    initialProvince: @js($province),
    initialCity: @js($city),
    initialDistrict: @js($district),
    initialVillage: @js($village),
    initialPostalCode: @js($postalCode)
})" x-init="init()" class="space-y-3.5">

    <div class="{{ $gridClass }}">
        <!-- 1. PROVINSI -->
        <div class="space-y-1">
            <label class="block font-semibold text-slate-700 dark:text-slate-300 text-xs flex items-center justify-between">
                <span>Provinsi</span>
                <span x-show="loading.provinces" class="text-[10px] text-blue-500 font-normal"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</span>
            </label>
            <div class="relative">
                <select name="{{ $provinceName }}" x-model="selectedProvinceName" @change="onProvinceChange($event.target.value)" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2 px-3">
                    <option value="">-- Pilih Provinsi --</option>
                    <template x-for="p in provinces" :key="p.id">
                        <option :value="p.name" :selected="p.name.toUpperCase() === (selectedProvinceName || '').toUpperCase()" x-text="p.name"></option>
                    </template>
                    <template x-if="selectedProvinceName && !provinces.some(p => p.name.toUpperCase() === selectedProvinceName.toUpperCase())">
                        <option :value="selectedProvinceName" selected x-text="selectedProvinceName"></option>
                    </template>
                </select>
            </div>
        </div>

        <!-- 2. KABUPATEN / KOTA -->
        <div class="space-y-1">
            <label class="block font-semibold text-slate-700 dark:text-slate-300 text-xs flex items-center justify-between">
                <span>Kabupaten / Kota</span>
                <span x-show="loading.cities" class="text-[10px] text-blue-500 font-normal"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</span>
            </label>
            <div class="relative">
                <select name="{{ $cityName }}" x-model="selectedCityName" @change="onCityChange($event.target.value)" :disabled="cities.length === 0 && !selectedCityName" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2 px-3 disabled:opacity-50">
                    <option value="">-- Pilih Kota / Kab --</option>
                    <template x-for="c in cities" :key="c.id">
                        <option :value="c.name" :selected="c.name.toUpperCase() === (selectedCityName || '').toUpperCase()" x-text="c.name"></option>
                    </template>
                    <template x-if="selectedCityName && !cities.some(c => c.name.toUpperCase() === selectedCityName.toUpperCase())">
                        <option :value="selectedCityName" selected x-text="selectedCityName"></option>
                    </template>
                </select>
            </div>
        </div>

        <!-- 3. KECAMATAN -->
        <div class="space-y-1">
            <label class="block font-semibold text-slate-700 dark:text-slate-300 text-xs flex items-center justify-between">
                <span>Kecamatan</span>
                <span x-show="loading.districts" class="text-[10px] text-blue-500 font-normal"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</span>
            </label>
            <div class="relative">
                <select name="{{ $districtName }}" x-model="selectedDistrictName" @change="onDistrictChange($event.target.value)" :disabled="districts.length === 0 && !selectedDistrictName" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2 px-3 disabled:opacity-50">
                    <option value="">-- Pilih Kecamatan --</option>
                    <template x-for="d in districts" :key="d.id">
                        <option :value="d.name" :selected="d.name.toUpperCase() === (selectedDistrictName || '').toUpperCase()" x-text="d.name"></option>
                    </template>
                    <template x-if="selectedDistrictName && !districts.some(d => d.name.toUpperCase() === selectedDistrictName.toUpperCase())">
                        <option :value="selectedDistrictName" selected x-text="selectedDistrictName"></option>
                    </template>
                </select>
            </div>
        </div>

        <!-- 4. KELURAHAN / DESA -->
        <div class="space-y-1">
            <label class="block font-semibold text-slate-700 dark:text-slate-300 text-xs flex items-center justify-between">
                <span>Kelurahan / Desa</span>
                <span x-show="loading.villages" class="text-[10px] text-blue-500 font-normal"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</span>
            </label>
            <div class="relative">
                <select name="{{ $villageName }}" x-model="selectedVillageName" @change="onVillageChange($event.target.value)" :disabled="villages.length === 0 && !selectedVillageName" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2 px-3 disabled:opacity-50">
                    <option value="">-- Pilih Kelurahan --</option>
                    <template x-for="v in villages" :key="v.id">
                        <option :value="v.name" :selected="v.name.toUpperCase() === (selectedVillageName || '').toUpperCase()" x-text="v.name"></option>
                    </template>
                    <template x-if="selectedVillageName && !villages.some(v => v.name.toUpperCase() === selectedVillageName.toUpperCase())">
                        <option :value="selectedVillageName" selected x-text="selectedVillageName"></option>
                    </template>
                </select>
            </div>
        </div>
    </div>

    @if($showPostalCode)
        <!-- 5. KODE POS (API + MANUAL OVERRIDE) -->
        <div class="space-y-1.5 pt-0.5">
            <div class="flex items-center justify-between">
                <label class="block font-semibold text-slate-700 dark:text-slate-300 text-xs flex items-center gap-1.5">
                    <span>Kode Pos</span>
                    <span x-show="loading.postalCode" class="text-[10px] text-blue-500 font-normal">
                        <i class="fa-solid fa-spinner fa-spin"></i> Mencari dari API...
                    </span>
                    <span x-show="!loading.postalCode && postalSuggestions.length > 0" class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">
                        <i class="fa-solid fa-circle-check"></i> Tersedia dari API
                    </span>
                </label>
                <button type="button" @click="fetchPostalCodes(selectedVillageName, selectedDistrictName, true)" 
                    class="text-[10px] text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center gap-1">
                    <i class="fa-solid fa-arrows-rotate"></i> Cari ulang API
                </button>
            </div>

            <div class="relative">
                <input type="text" 
                    name="{{ $postalCodeName }}" 
                    x-model="postalCode" 
                    placeholder="Contoh: 12950 (otomatis dari API / bisa ketik manual jika tidak ada)" 
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2 px-3">
            </div>

            <!-- Saran Kode Pos dari API jika ada -->
            <template x-if="postalSuggestions.length > 0">
                <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                    <span class="text-[10px] text-slate-500 dark:text-slate-400">Pilihan dari API:</span>
                    <template x-for="(item, idx) in postalSuggestions" :key="idx">
                        <button type="button" 
                            @click="postalCode = String(item.code)" 
                            :class="String(postalCode) === String(item.code) ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700'"
                            class="px-2 py-0.5 rounded text-[11px] font-mono border transition-all inline-flex items-center gap-1">
                            <span class="font-semibold" x-text="item.code"></span>
                            <span class="text-[9px] opacity-75 font-sans" x-text="'(' + (item.village || item.district) + ')'"></span>
                        </button>
                    </template>
                </div>
            </template>

            <p class="text-[10px] text-slate-500 dark:text-slate-400 flex items-center gap-1">
                <i class="fa-solid fa-circle-info text-blue-500 shrink-0"></i>
                <span>Kode pos dicari otomatis berdasarkan kelurahan/kecamatan. Jika tidak ditemukan atau berbeda, Anda dapat mengetik manual secara bebas.</span>
            </p>
        </div>
    @endif

</div>

<script>
    if (typeof window.indonesiaRegionPicker === 'undefined') {
        window.indonesiaRegionPicker = function(config) {
            return {
                provinces: [],
                cities: [],
                districts: [],
                villages: [],
                postalSuggestions: [],
                selectedProvinceName: config.initialProvince || '',
                selectedCityName: config.initialCity || '',
                selectedDistrictName: config.initialDistrict || '',
                selectedVillageName: config.initialVillage || '',
                postalCode: config.initialPostalCode || '',
                loading: {
                    provinces: false,
                    cities: false,
                    districts: false,
                    villages: false,
                    postalCode: false
                },

                async init() {
                    this.loading.provinces = true;
                    try {
                        const res = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
                        this.provinces = await res.json();

                        if (this.selectedProvinceName) {
                            const p = this.provinces.find(x => x.name.toUpperCase() === this.selectedProvinceName.toUpperCase());
                            if (p) {
                                await this.fetchCities(p.id, false);
                            }
                        }
                    } catch (e) {
                        console.warn('API Wilayah Indonesia load error:', e);
                    } finally {
                        this.loading.provinces = false;
                    }
                },

                async onProvinceChange(name) {
                    this.selectedProvinceName = name;
                    this.selectedCityName = '';
                    this.selectedDistrictName = '';
                    this.selectedVillageName = '';
                    this.cities = [];
                    this.districts = [];
                    this.villages = [];
                    this.postalSuggestions = [];

                    const p = this.provinces.find(x => x.name.toUpperCase() === (name || '').toUpperCase());
                    if (p) {
                        await this.fetchCities(p.id, true);
                    }
                },

                async fetchCities(provinceId, resetChildren = false) {
                    this.loading.cities = true;
                    try {
                        const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`);
                        this.cities = await res.json();

                        if (!resetChildren && this.selectedCityName) {
                            const c = this.cities.find(x => x.name.toUpperCase() === this.selectedCityName.toUpperCase());
                            if (c) {
                                await this.fetchDistricts(c.id, false);
                            }
                        }
                    } catch (e) {
                        console.warn('API Cities load error:', e);
                    } finally {
                        this.loading.cities = false;
                    }
                },

                async onCityChange(name) {
                    this.selectedCityName = name;
                    this.selectedDistrictName = '';
                    this.selectedVillageName = '';
                    this.districts = [];
                    this.villages = [];
                    this.postalSuggestions = [];

                    const c = this.cities.find(x => x.name.toUpperCase() === (name || '').toUpperCase());
                    if (c) {
                        await this.fetchDistricts(c.id, true);
                    }
                },

                async fetchDistricts(cityId, resetChildren = false) {
                    this.loading.districts = true;
                    try {
                        const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${cityId}.json`);
                        this.districts = await res.json();

                        if (!resetChildren && this.selectedDistrictName) {
                            const d = this.districts.find(x => x.name.toUpperCase() === this.selectedDistrictName.toUpperCase());
                            if (d) {
                                await this.fetchVillages(d.id);
                            }
                        }
                    } catch (e) {
                        console.warn('API Districts load error:', e);
                    } finally {
                        this.loading.districts = false;
                    }
                },

                async onDistrictChange(name) {
                    this.selectedDistrictName = name;
                    this.selectedVillageName = '';
                    this.villages = [];
                    this.postalSuggestions = [];

                    const d = this.districts.find(x => x.name.toUpperCase() === (name || '').toUpperCase());
                    if (d) {
                        await this.fetchVillages(d.id);
                        // Fetch postal code suggestion for district
                        this.fetchPostalCodes('', name, false);
                    }
                },

                async fetchVillages(districtId) {
                    this.loading.villages = true;
                    try {
                        const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`);
                        this.villages = await res.json();
                    } catch (e) {
                        console.warn('API Villages load error:', e);
                    } finally {
                        this.loading.villages = false;
                    }
                },

                async onVillageChange(name) {
                    this.selectedVillageName = name;
                    if (name) {
                        await this.fetchPostalCodes(name, this.selectedDistrictName, true);
                    }
                },

                async fetchPostalCodes(village, district, autoSelectFirst = true) {
                    const query = village || district;
                    if (!query) return;

                    this.loading.postalCode = true;
                    try {
                        const res = await fetch(`https://kodepos.vercel.app/search/?q=${encodeURIComponent(query)}`);
                        const data = await res.json();

                        if (data && data.data && Array.isArray(data.data) && data.data.length > 0) {
                            // Filter matching records if possible
                            let matches = data.data;
                            if (village) {
                                const exactVillage = matches.filter(m => m.village && m.village.toUpperCase() === village.toUpperCase());
                                if (exactVillage.length > 0) {
                                    matches = exactVillage;
                                }
                            }
                            
                            // Deduplicate by code
                            const seen = new Set();
                            this.postalSuggestions = matches.filter(item => {
                                const duplicate = seen.has(item.code);
                                seen.add(item.code);
                                return !duplicate;
                            }).slice(0, 5); // top 5 suggestions

                            if (autoSelectFirst && this.postalSuggestions.length > 0) {
                                this.postalCode = String(this.postalSuggestions[0].code);
                            }
                        } else {
                            this.postalSuggestions = [];
                        }
                    } catch (e) {
                        console.warn('API Kodepos load error:', e);
                        this.postalSuggestions = [];
                    } finally {
                        this.loading.postalCode = false;
                    }
                }
            };
        };
    }
</script>
