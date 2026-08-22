@props([
    'name' => 'location',
    'value' => '',
    'placeholder' => 'Cari / Pilih Lokasi Kota...',
    'inputClass' => 'block w-full pl-10 pr-8 py-2 border-slate-300 rounded-xl focus:border-slate-800 focus:ring-slate-800 bg-slate-50/50 hover:bg-white transition text-xs font-medium text-slate-800'
])

@php
    $cities = \App\Models\UmkReference::select('city_district', 'province', 'umk_amount')
        ->orderBy('city_district', 'asc')
        ->get()
        ->map(function($c) {
            return [
                'city' => $c->city_district,
                'province' => $c->province,
                'umk' => $c->formatted_umk ?? ('Rp ' . number_format($c->umk_amount, 0, ',', '.'))
            ];
        });
@endphp

<div x-data="{ 
    open: false, 
    search: '{{ $value }}', 
    cities: {{ json_encode($cities) }},
    get filteredCities() {
        if (!this.search) return this.cities.slice(0, 60);
        const query = this.search.toLowerCase();
        return this.cities.filter(c => c.city.toLowerCase().includes(query) || c.province.toLowerCase().includes(query)).slice(0, 60);
    },
    select(cityName) {
        this.search = cityName;
        this.open = false;
    }
}" @click.outside="open = false" class="relative w-full">
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
            <i class="fa-solid fa-location-dot text-xs text-slate-400"></i>
        </div>
        
        <input type="text" 
               x-model="search" 
               @focus="open = true" 
               @input="open = true"
               placeholder="{{ $placeholder }}" 
               class="{{ $inputClass }}"
               autocomplete="off">
        
        <input type="hidden" name="{{ $name }}" :value="search">
        
        <button type="button" x-show="search" @click="search = ''; open = true" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>
    </div>

    <!-- Dropdown Panel (Sleek Modern White UI) -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-1.5 w-full max-h-64 overflow-y-auto bg-white rounded-2xl shadow-2xl border border-slate-200 py-1.5 text-xs divide-y divide-slate-100" 
         style="display: none;">
        
        <template x-for="item in filteredCities" :key="item.city">
            <button type="button" 
                    @click="select(item.city)" 
                    class="w-full text-left px-4 py-2.5 hover:bg-slate-100/80 transition flex items-center justify-between gap-2 group cursor-pointer">
                <div class="flex flex-col gap-0.5">
                    <span class="font-extrabold text-slate-900 group-hover:text-blue-600 text-xs flex items-center gap-1.5">
                        <span>📍</span> <span x-text="item.city"></span>
                    </span>
                    <span class="text-3xs text-slate-400 font-semibold uppercase tracking-wider" x-text="item.province"></span>
                </div>
                <span class="text-3xs font-extrabold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100" x-text="'UMK: ' + item.umk"></span>
            </button>
        </template>

        <div x-show="filteredCities.length === 0" class="px-4 py-3 text-center text-slate-400 italic">
            Kota tidak ditemukan. Anda tetap bisa memasukkannya secara manual!
        </div>
    </div>
</div>
