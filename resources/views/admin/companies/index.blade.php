<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1 font-medium">
                    <span>Super Admin</span>
                    <span>/</span>
                    <span class="text-slate-900 dark:text-white font-semibold">Perusahaan</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-slate-900 dark:bg-slate-700 text-white flex items-center justify-center text-xs">
                        <i class="fa-solid fa-building-circle-check"></i>
                    </span>
                    Kelola Perusahaan Mitra
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kontrol otorisasi perusahaan, verifikasi legalitas dokumen NIB/SIUP, dan status operasional.</p>
            </div>
            <div class="text-xs font-medium text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-2xs flex items-center gap-2">
                <span>Total Perusahaan:</span>
                <span class="font-bold text-slate-900 dark:text-white">{{ number_format($companies->total()) }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50/60 dark:bg-slate-900 min-h-screen transition-colors" x-data="{ activeEditModal: null, activeDrawerCompany: null }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-xl flex items-center gap-3 text-xs font-medium shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-xl flex items-center gap-3 text-xs font-medium shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 dark:text-rose-400 text-base shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Search & Filtering Toolbar -->
            <div class="bg-white dark:bg-slate-800 p-4 sm:p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80">
                <form method="GET" action="{{ route('admin.companies.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3.5 text-xs">
                    <div class="space-y-1">
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 text-[11px]">Cari Perusahaan</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama perusahaan..." class="pl-8 w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 text-[11px]">Verifikasi NIB / SIUP</label>
                        <select name="verification" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                            <option value="">Semua Status</option>
                            <option value="verified" {{ request('verification') == 'verified' ? 'selected' : '' }}>Terverifikasi Resmi</option>
                            <option value="unverified" {{ request('verification') == 'unverified' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 text-[11px]">Status Operasional</label>
                        <select name="status" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif Normal</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 text-[11px]">Baris Data</label>
                        <select name="per_page" onchange="this.form.submit()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 baris</option>
                            <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25 baris</option>
                            <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50 baris</option>
                            <option value="100" {{ request('per_page', 100) == 100 ? 'selected' : '' }}>100 baris</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3 rounded-xl text-xs transition shadow-xs flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-filter text-[11px]"></i>
                            <span>Terapkan</span>
                        </button>
                        @if(request()->hasAny(['search', 'verification', 'status', 'per_page']))
                            <a href="{{ route('admin.companies.index') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-semibold rounded-xl text-xs transition border border-slate-200 dark:border-slate-600" title="Reset Filter">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Companies Table Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-700/80 text-slate-500 dark:text-slate-400 text-[11px] font-semibold uppercase tracking-wider">
                                <th class="py-3.5 px-4 sm:px-6">Perusahaan</th>
                                <th class="py-3.5 px-4">Owner / Pengelola</th>
                                <th class="py-3.5 px-4">Sektor & Skala</th>
                                <th class="py-3.5 px-4">Dokumen NIB/SIUP</th>
                                <th class="py-3.5 px-4">Status Operasional</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Aksi & Kontrol</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs">
                            @forelse($companies as $company)
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-900/40 transition">
                                    <td class="py-3.5 px-4 sm:px-6 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @if($company->logo_path)
                                                <img src="{{ Storage::url($company->logo_path) }}" class="w-9 h-9 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-2xs shrink-0">
                                            @else
                                                <div class="w-9 h-9 rounded-xl bg-slate-900 dark:bg-slate-700 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-2xs">
                                                    {{ strtoupper(substr($company->company_name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-semibold text-slate-900 dark:text-white text-xs flex items-center gap-1.5">
                                                    <button type="button" @click="activeEditModal = {{ $company->id }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition font-semibold text-left">
                                                        {{ $company->company_name }}
                                                    </button>
                                                    @if($company->is_verified)
                                                        <span title="Terverifikasi Resmi" class="inline-flex items-center text-blue-500 text-xs">
                                                            <i class="fa-solid fa-circle-check"></i>
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($company->website)
                                                    <a href="{{ $company->website }}" target="_blank" class="text-[11px] text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 flex items-center gap-1 mt-0.5">
                                                        <i class="fa-solid fa-link text-[9px]"></i>
                                                        <span class="truncate max-w-[160px]">{{ preg_replace('#^https?://#', '', $company->website) }}</span>
                                                    </a>
                                                @else
                                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">ID: #CMP-{{ str_pad($company->id, 3, '0', STR_PAD_LEFT) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($company->user)
                                            <div class="font-semibold text-slate-900 dark:text-slate-200 text-xs">{{ $company->user->name }}</div>
                                            <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $company->user->email }}</div>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-500 italic text-xs">Tanpa Akun Owner</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="font-medium text-slate-800 dark:text-slate-200 text-xs">{{ $company->industry ?? 'Sektor Umum' }}</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $company->company_size ?? '-' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($company->legal_doc_path)
                                            <div class="flex items-center gap-2">
                                                <a href="{{ Storage::url($company->legal_doc_path) }}" target="_blank" class="px-2.5 py-1 bg-slate-50 dark:bg-slate-900/60 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium text-[11px] rounded-lg border border-slate-200 dark:border-slate-700 transition inline-flex items-center gap-1.5 shadow-2xs">
                                                    <i class="fa-solid fa-file-pdf text-rose-500 text-xs"></i>
                                                    <span>NIB / SIUP</span>
                                                </a>
                                                @if($company->is_verified)
                                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                        Valid
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[11px] text-slate-400 dark:text-slate-500">
                                                <i class="fa-solid fa-circle-minus text-[10px]"></i>
                                                Belum Unggah
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($company->is_suspended)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Ditangguhkan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif Normal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 sm:px-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- Quick Edit Button -->
                                            <button @click="activeEditModal = {{ $company->id }}" class="px-2.5 py-1.5 bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold transition shadow-2xs flex items-center gap-1.5">
                                                <i class="fa-solid fa-pen-to-square text-[11px] text-slate-400"></i>
                                                <span>Kelola</span>
                                            </button>

                                            <!-- Dropdown Menu for Secondary Actions -->
                                            <x-dropdown align="right" width="48">
                                                <x-slot name="trigger">
                                                    <button type="button" class="p-1.5 bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 rounded-lg text-xs transition shadow-2xs">
                                                        <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                                                    </button>
                                                </x-slot>

                                                <x-slot name="content">
                                                    <!-- Toggle Verify -->
                                                    <form method="POST" action="{{ route('admin.companies.toggle-verify', $company->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if($company->is_verified)
                                                            <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-xs">
                                                                <i class="fa-solid fa-circle-xmark text-slate-400 w-4"></i> Cabut Verifikasi
                                                            </x-dropdown-link>
                                                        @else
                                                            <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-xs text-blue-600 font-semibold">
                                                                <i class="fa-solid fa-circle-check text-blue-500 w-4"></i> Beri Verifikasi Resmi
                                                            </x-dropdown-link>
                                                        @endif
                                                    </form>

                                                    <!-- Toggle Suspend -->
                                                    <form method="POST" action="{{ route('admin.companies.toggle-suspend', $company->id) }}" onsubmit="return confirm('{{ $company->is_suspended ? 'Buka penangguhan perusahaan ini?' : 'Tangguhkan operasional perusahaan ini?' }}');">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if($company->is_suspended)
                                                            <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-xs text-emerald-600 font-semibold">
                                                                <i class="fa-solid fa-lock-open text-emerald-500 w-4"></i> Buka Penangguhan
                                                            </x-dropdown-link>
                                                        @else
                                                            <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-xs text-amber-600">
                                                                <i class="fa-solid fa-ban text-amber-500 w-4"></i> Tangguhkan Perusahaan
                                                            </x-dropdown-link>
                                                        @endif
                                                    </form>

                                                    @if($company->company_slug || $company->id)
                                                        <x-dropdown-link :href="route('companies.show', $company->company_slug ?? $company->id)" target="_blank" class="flex items-center gap-2 text-xs">
                                                            <i class="fa-solid fa-arrow-up-right-from-square text-slate-400 w-4"></i> Lihat Portal Publik
                                                        </x-dropdown-link>
                                                    @endif

                                                    <div class="border-t border-slate-100 dark:border-slate-800 my-1"></div>

                                                    <!-- Delete -->
                                                    <form method="POST" action="{{ route('admin.companies.destroy', $company->id) }}" onsubmit="return confirm('PERINGATAN! Hapus permanen profil {{ $company->company_name }} beserta seluruh data lowongannya?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40">
                                                            <i class="fa-solid fa-trash text-rose-500 w-4"></i> Hapus Perusahaan
                                                        </x-dropdown-link>
                                                    </form>
                                                </x-slot>
                                            </x-dropdown>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MASTER EDIT MODAL FOR EACH COMPANY -->
                                <div x-show="activeEditModal === {{ $company->id }}" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4 sm:p-6" style="display: none;">
                                    <div @click.away="activeEditModal = null" class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full max-h-[85vh] flex flex-col shadow-2xl border border-slate-200 dark:border-slate-800 text-left overflow-hidden">
                                        
                                        <!-- Modal Header Pinned -->
                                        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 px-6 py-4 shrink-0 bg-white dark:bg-slate-900">
                                            <h4 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                                <i class="fa-solid fa-building-circle-check text-blue-600 dark:text-blue-400"></i>
                                                Kelola Data Perusahaan: {{ $company->company_name }}
                                            </h4>
                                            <button type="button" @click="activeEditModal = null" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                                <i class="fa-solid fa-xmark text-sm"></i>
                                            </button>
                                        </div>

                                        <!-- Form Wrapper -->
                                        <form action="{{ route('admin.companies.update', $company->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden min-h-0">
                                            @csrf
                                            @method('PUT')

                                            <!-- Scrollable Body Content -->
                                            <div class="overflow-y-auto flex-1 p-6 space-y-4 text-xs">
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <div class="space-y-1">
                                                        <label class="block font-semibold text-slate-700 dark:text-slate-300">Nama Resmi Perusahaan *</label>
                                                        <input type="text" name="company_name" value="{{ old('company_name', $company->company_name) }}" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="block font-semibold text-slate-700 dark:text-slate-300">Sektor / Industri</label>
                                                        <input type="text" name="industry" value="{{ old('industry', $company->industry) }}" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="block font-semibold text-slate-700 dark:text-slate-300">Skala Karyawan</label>
                                                        <input type="text" name="company_size" value="{{ old('company_size', $company->company_size) }}" placeholder="Contoh: 50-100 Karyawan" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="block font-semibold text-slate-700 dark:text-slate-300">No. Telepon Perusahaan</label>
                                                        <input type="text" name="phone" value="{{ old('phone', $company->phone) }}" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="block font-semibold text-slate-700 dark:text-slate-300">Website Resmi</label>
                                                        <input type="url" name="website" value="{{ old('website', $company->website) }}" placeholder="https://example.com" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="block font-semibold text-slate-700 dark:text-slate-300">Link Google Maps</label>
                                                        <input type="text" name="google_maps_link" value="{{ old('google_maps_link', $company->google_maps_link) }}" placeholder="https://maps.google.com/..." class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                                                    </div>
                                                </div>

                                                <!-- Regional Address Picker (Provinsi, Kota/Kab, Kecamatan, Kelurahan, Kode Pos) -->
                                                <div class="space-y-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                                                    <div class="font-semibold text-slate-800 dark:text-slate-200 text-xs flex items-center gap-1.5">
                                                        <i class="fa-solid fa-map-location-dot text-blue-600 dark:text-blue-400"></i>
                                                        <span>Wilayah & Alamat Operasional Kantor</span>
                                                    </div>

                                                    <x-indonesia-region-select 
                                                        :province="$company->province ?? ''" 
                                                        :city="$company->city ?? ''" 
                                                        :district="$company->district ?? ''" 
                                                        :village="$company->village ?? ''" 
                                                        :postalCode="$company->postal_code ?? ''" 
                                                    />

                                                    <div class="space-y-1">
                                                        <label class="block font-semibold text-slate-700 dark:text-slate-300">Alamat Lengkap (Gedung, Jalan, No. RT/RW)</label>
                                                        <input type="text" name="address" value="{{ old('address', $company->address) }}" placeholder="Contoh: Gedung Bursa Efek Tower 2 Lt. 15, Jl. Jend. Sudirman Kav. 52-53" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2 px-3">
                                                    </div>
                                                </div>

                                                <div class="space-y-1">
                                                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Deskripsi Singkat</label>
                                                    <textarea name="description" rows="3" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2 px-3">{{ old('description', $company->description) }}</textarea>
                                                </div>

                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100 dark:border-slate-800">
                                                    <div class="space-y-1">
                                                        <label class="block font-semibold text-slate-700 dark:text-slate-300">Unggah / Ganti Logo</label>
                                                        <input type="file" name="logo" accept="image/*" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-slate-800 dark:file:text-slate-200 hover:file:bg-blue-100">
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="block font-semibold text-slate-700 dark:text-slate-300">Berkas NIB / SIUP (PDF/Foto)</label>
                                                        <input type="file" name="legal_doc" accept=".pdf,image/*" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-slate-800 dark:file:text-slate-200 hover:file:bg-emerald-100">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Pinned Bottom Footer Action Bar -->
                                            <div class="flex justify-end items-center gap-2.5 px-6 py-3.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/80 shrink-0">
                                                <button type="button" @click="activeEditModal = null" class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold rounded-xl text-xs transition border border-slate-200 dark:border-slate-700 shadow-2xs">
                                                    Batal
                                                </button>
                                                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs shadow-xs transition">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 px-4 text-center text-slate-400 dark:text-slate-500 font-medium">
                                        <i class="fa-solid fa-building-circle-xmark text-2xl text-slate-300 dark:text-slate-600 mb-2 block"></i>
                                        Tidak ada perusahaan ditemukan dengan kriteria filter tersebut.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-900/40">
                    {{ $companies->links('vendor.pagination.tailwind') }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
