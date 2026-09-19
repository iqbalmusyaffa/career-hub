<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- PAGE HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500 mb-1">
                    <span>Pengaturan Perusahaan</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <span class="text-blue-600 dark:text-blue-400">Profil & Branding</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                    <i class="fa-solid fa-building text-blue-600 dark:text-blue-400"></i>
                    Profil & Data Perusahaan
                </h1>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Kelola informasi legalitas, identitas visual, budaya kerja, dan halaman portal karir publik perusahaan.
                </p>
            </div>

            <div class="flex items-center gap-2">
                @if($profile->is_verified)
                    <span class="px-3.5 py-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60 text-xs font-semibold flex items-center gap-2 shadow-xs">
                        <i class="fa-solid fa-shield-check text-emerald-600 dark:text-emerald-400"></i> Terverifikasi Resmi
                    </span>
                @else
                    <span class="px-3.5 py-2 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/60 text-xs font-semibold flex items-center gap-2 shadow-xs">
                        <i class="fa-solid fa-shield-halved text-amber-600 dark:text-amber-400"></i> Menunggu Verifikasi
                    </span>
                @endif
                <a href="{{ route('companies.show', urlencode($profile->company_name)) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-semibold transition flex items-center gap-2 shadow-xs">
                    <i class="fa-solid fa-arrow-up-right-from-square text-slate-400"></i> Portal Publik
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center gap-2.5 shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.company.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- 1. IDENTITAS UTAMA & LOGO RESMI -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 sm:p-6 space-y-5 shadow-xs">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-id-card text-blue-600 dark:text-blue-400"></i>
                        Identitas & Logo Resmi Perusahaan
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Logo akan ditampilkan di halaman pencarian lowongan, header tawaran kerja, dan portal karir.</p>
                </div>

                <!-- Logo Upload Box -->
                <div class="p-4 sm:p-5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800/80 flex flex-col sm:flex-row items-center gap-5">
                    <div class="shrink-0 relative group">
                        <div id="logo-preview-box" class="w-20 h-20 rounded-2xl overflow-hidden bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-xs relative flex items-center justify-center">
                            @if($profile->logo_path)
                                <img id="logo-preview-img" src="{{ Storage::url($profile->logo_path) }}" alt="Company Logo" class="w-full h-full object-cover">
                            @else
                                <div id="logo-preview-placeholder" class="w-full h-full bg-blue-600 text-white font-black text-2xl flex items-center justify-center">
                                    {{ strtoupper(substr($profile->company_name ?? 'C', 0, 1)) }}
                                </div>
                                <img id="logo-preview-img" class="w-full h-full object-cover hidden">
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 text-center sm:text-left space-y-1.5 w-full">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Unggah Logo Perusahaan (PNG, JPG, WebP)</label>
                        <input type="file" name="logo" accept="image/*" onchange="previewFile(event, 'logo-preview-img', 'logo-preview-placeholder', 'logo-status-badge')" 
                               class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition cursor-pointer">
                        <div class="flex items-center justify-center sm:justify-start gap-2 pt-0.5">
                            <span class="text-[11px] text-slate-400">Rekomendasi rasio 1:1 persegi, maksimal 2MB</span>
                            <span id="logo-status-badge" class="hidden text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-800/60">
                                Pratinjau Siap Simpan
                            </span>
                        </div>
                        @error('logo') <span class="text-xs text-rose-500 block font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Form Fields Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Resmi Perusahaan <span class="text-rose-500">*</span></label>
                        <input type="text" name="company_name" required value="{{ old('company_name', $profile->company_name) }}" placeholder="Misal: PT TechNova Asia Digital"
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
                        @error('company_name') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Sektor & Industri</label>
                        <input type="text" name="industry" value="{{ old('industry', $profile->industry) }}" placeholder="Misal: Software, Fintech, E-Commerce"
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
                        @error('industry') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Skala / Ukuran Perusahaan</label>
                        <select name="company_size" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-semibold">
                            <option value="">Pilih Ukuran Perusahaan</option>
                            <option value="1 - 10 Karyawan" {{ old('company_size', $profile->company_size) == '1 - 10 Karyawan' ? 'selected' : '' }}>1 - 10 Karyawan (Startup / Micro)</option>
                            <option value="11 - 50 Karyawan" {{ old('company_size', $profile->company_size) == '11 - 50 Karyawan' ? 'selected' : '' }}>11 - 50 Karyawan (Small)</option>
                            <option value="50 - 200 Karyawan" {{ old('company_size', $profile->company_size) == '50 - 200 Karyawan' ? 'selected' : '' }}>50 - 200 Karyawan (Medium)</option>
                            <option value="200 - 1000 Karyawan" {{ old('company_size', $profile->company_size) == '200 - 1000 Karyawan' ? 'selected' : '' }}>200 - 1000 Karyawan (Large Enterprise)</option>
                            <option value="1000+ Karyawan" {{ old('company_size', $profile->company_size) == '1000+ Karyawan' ? 'selected' : '' }}>1000+ Karyawan (Multinational)</option>
                        </select>
                        @error('company_size') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Website Resmi</label>
                        <input type="url" name="website" value="{{ old('website', $profile->website) }}" placeholder="https://perusahaan.com"
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
                        @error('website') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Telepon Kantor</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" placeholder="021-55443322"
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
                        @error('phone') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Dokumen Legalitas / NIB / SIUP (PDF / Gambar)</label>
                        <input type="file" name="legal_doc" accept=".pdf,.jpg,.png"
                               class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-300 dark:hover:file:bg-slate-700 transition cursor-pointer">
                        @if($profile->legal_doc_path)
                            <a href="{{ route('admin.company.profile.document') }}" target="_blank" class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold hover:underline mt-1 inline-flex items-center gap-1">
                                <i class="fa-solid fa-file-pdf"></i> Lihat File Legalitas Terunggah
                            </a>
                        @endif
                        @error('legal_doc') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Regional Cascading Picker (Provinsi, Kota/Kabupaten, Kecamatan, Kelurahan, Kode Pos) -->
                <div class="space-y-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                        <i class="fa-solid fa-map-location-dot text-blue-600 dark:text-blue-400 mr-1"></i>
                        Wilayah Administratif Kantor Pusat
                    </label>

                    <x-indonesia-region-select 
                        :province="$profile->province ?? ''" 
                        :city="$profile->city ?? ''" 
                        :district="$profile->district ?? ''" 
                        :village="$profile->village ?? ''" 
                        :postalCode="$profile->postal_code ?? ''" 
                    />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Alamat Lengkap (Gedung, Jalan, No. RT/RW)</label>
                    <textarea name="address" rows="2" placeholder="Jl. HR Rasuna Said Kav. 10, Kuningan, Jakarta Selatan..."
                              class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">{{ old('address', $profile->address) }}</textarea>
                    @error('address') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Singkat & Visi Perusahaan</label>
                    <textarea name="description" rows="3" placeholder="Jelaskan bidang usaha, visi misi, dan budaya perusahaan Anda..."
                              class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">{{ old('description', $profile->description) }}</textarea>
                    @error('description') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- 2. REKENING BANK & PAJAK (OWNER / SUPER ADMIN) -->
            @if(auth()->user()->hasRole('Company Owner') || auth()->user()->hasRole('Super Admin'))
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 sm:p-6 space-y-4 shadow-xs">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-building-columns text-blue-600 dark:text-blue-400"></i>
                        Rekening Bank & Pajak Perusahaan
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Informasi rekening resmi perusahaan untuk keperluan administrasi dan pencairan.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Bank</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $profile->bank_name) }}" placeholder="Misal: Bank BCA / Mandiri / BNI"
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Rekening</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $profile->bank_account_number) }}" placeholder="Misal: 1234567890"
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Atas Nama Rekening</label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $profile->bank_account_name) }}" placeholder="Misal: PT TechNova Asia Digital"
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor NPWP Perusahaan</label>
                        <input type="text" name="npwp_number" value="{{ old('npwp_number', $profile->npwp_number) }}" placeholder="Misal: 01.234.567.8-901.000"
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
                    </div>
                </div>
            </div>
            @endif

            <!-- 3. PUBLIC CAREER PAGE BUILDER -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 sm:p-6 space-y-5 shadow-xs">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-globe text-blue-600 dark:text-blue-400"></i>
                            Public Career Page Builder
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kustomisasi halaman portal karir publik yang dilihat oleh para pencari kerja.</p>
                    </div>
                </div>

                <!-- Cover Banner Upload -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Header Banner Perusahaan (Opsional)</label>
                    <input type="file" name="cover_image" accept="image/*"
                           class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-300 dark:hover:file:bg-slate-700 transition cursor-pointer">
                    @if($profile->cover_image_path)
                        <div class="w-full h-32 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 relative mt-2">
                            <img src="{{ Storage::url($profile->cover_image_path) }}" class="w-full h-full object-cover">
                            <div class="absolute bottom-2 left-2 px-2 py-0.5 bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-semibold rounded">
                                Banner Aktif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Tagline -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tagline Slogan Utama</label>
                    <input type="text" name="tagline" value="{{ old('tagline', $profile->tagline) }}" placeholder="Misal: Building Future Tech Pioneers Across Southeast Asia"
                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
                </div>

                <!-- Workplace Culture -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Budaya & Suasana Kerja (Workplace Culture)</label>
                    <textarea name="culture_description" rows="3" placeholder="Jelaskan lingkungan kerja, nilai-nilai utama, dan kebiasaan tim di perusahaan Anda..."
                              class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">{{ old('culture_description', $profile->culture_description) }}</textarea>
                </div>

                <!-- Benefit Checkboxes -->
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
                    $existingBenefits = is_array($profile->benefits) ? $profile->benefits : [];
                @endphp
                <div x-data="benefitSelector(@js($defaultBenefits), @js($existingBenefits))" class="space-y-2.5">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Fasilitas & Benefit Karyawan</label>
                        <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 px-2 py-0.5 rounded border border-blue-200 dark:border-blue-800/60" x-text="selectedBenefits.length + ' Dipilih'"></span>
                    </div>

                    <template x-for="b in selectedBenefits" :key="b">
                        <input type="hidden" name="benefits[]" :value="b">
                    </template>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <template x-for="(benefit, index) in availableBenefits" :key="index">
                            <label class="flex items-center justify-between gap-2 p-2.5 rounded-xl border cursor-pointer text-xs transition select-none"
                                   :class="selectedBenefits.includes(benefit) ? 'bg-blue-50 dark:bg-blue-950/40 border-blue-500/60 dark:border-blue-500/60 text-blue-700 dark:text-blue-300 font-semibold' : 'bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60'">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <input type="checkbox" 
                                           :checked="selectedBenefits.includes(benefit)"
                                           @change="toggleBenefit(benefit)"
                                           class="rounded text-blue-600 focus:ring-blue-500 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 h-4 w-4 shrink-0 cursor-pointer">
                                    <span class="truncate" x-text="benefit"></span>
                                </div>
                                <template x-if="!defaultBenefits.includes(benefit)">
                                    <button type="button" @click.stop.prevent="removeBenefit(benefit)" class="text-slate-400 hover:text-rose-500 p-1 rounded transition shrink-0" title="Hapus dari daftar">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </template>
                            </label>
                        </template>
                    </div>

                    <!-- Custom Tag Adder -->
                    <div class="flex items-center gap-2 p-1.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <input type="text" x-model="newBenefit" @keydown.enter.prevent="addBenefit()"
                               placeholder="Tambah benefit kustom (misal: Ruang Game, Gym, Reimburse Kacamata)..."
                               class="w-full bg-transparent border-0 px-3 py-1 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none font-medium">
                        <button type="button" @click="addBenefit()" 
                                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition shrink-0 flex items-center gap-1 shadow-xs cursor-pointer">
                            <i class="fa-solid fa-plus text-[10px]"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>

            <!-- 5. KEBIJAKAN HARI KERJA & PRESENSI MAGANG MITRA -->
            <div x-data="{
                saturdayActive: {{ $profile->allow_saturday_work ? 'true' : 'false' }},
                sundayActive: {{ $profile->allow_sunday_work ? 'true' : 'false' }}
            }" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 sm:p-6 space-y-5 shadow-xs">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-regular fa-calendar-check text-blue-600 dark:text-blue-400"></i>
                        Kebijakan Hari Kerja Operasional & Presensi Magang
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tentukan hari operasional kerja resmi bagi peserta magang yang ditempatkan di perusahaan Anda.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-start gap-3 cursor-pointer p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border transition"
                           :class="saturdayActive ? 'border-blue-500 ring-2 ring-blue-500/10 dark:bg-slate-900/80' : 'border-slate-200 dark:border-slate-800/80 hover:bg-slate-100/70 dark:hover:bg-slate-900/60'">
                        <input type="checkbox" name="allow_saturday_work" value="1" x-model="saturdayActive" class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-900 dark:text-slate-100 block">Masuk Hari Sabtu (Pola 6 Hari Kerja)</span>
                                <span x-show="saturdayActive" class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300">Aktif</span>
                            </div>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 block mt-0.5">Aktifkan jika anak magang di perusahaan Anda wajib hadir dan mengisi logbook di hari Sabtu.</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border transition"
                           :class="sundayActive ? 'border-purple-500 ring-2 ring-purple-500/10 dark:bg-slate-900/80' : 'border-slate-200 dark:border-slate-800/80 hover:bg-slate-100/70 dark:hover:bg-slate-900/60'">
                        <input type="checkbox" name="allow_sunday_work" value="1" x-model="sundayActive" class="mt-0.5 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-900 dark:text-slate-100 block">Masuk Hari Minggu (Shift Weekend)</span>
                                <span x-show="sundayActive" class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300">Aktif</span>
                            </div>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 block mt-0.5">Aktifkan untuk industri ritel, event, atau sistem shift khusus di hari Minggu.</span>
                        </div>
                    </label>
                </div>

                <!-- Dynamic Alert Info Banner -->
                <div x-show="saturdayActive || sundayActive" x-transition class="p-3.5 bg-blue-50/70 dark:bg-blue-950/40 border border-blue-200/80 dark:border-blue-900/60 rounded-xl flex items-start gap-3 text-xs text-blue-900 dark:text-blue-200">
                    <i class="fa-solid fa-bell text-blue-600 dark:text-blue-400 mt-0.5 shrink-0"></i>
                    <div class="space-y-0.5 leading-relaxed">
                        <span class="font-bold">Notifikasi Pengaturan Jadwal:</span>
                        <p class="text-[11px] text-blue-800/90 dark:text-blue-300">
                            Peserta magang di perusahaan Anda akan dapat mengisi presensi dan logbook pada hari <span x-show="saturdayActive" class="font-bold">Sabtu</span><span x-show="saturdayActive && sundayActive"> & </span><span x-show="sundayActive" class="font-bold">Minggu</span> tanpa terblokir status libur.
                        </p>
                    </div>
                </div>

                <div x-show="!saturdayActive && !sundayActive" class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800/80 flex items-center gap-2.5 text-[11px] text-slate-500 dark:text-slate-400">
                    <i class="fa-solid fa-shield-check text-slate-400"></i>
                    <span>Pola standar 5 hari kerja (Senin–Jumat) aktif. Akhir pekan (Sabtu & Minggu) otomatis menjadi hari libur operasional dan tidak dihitung alpa.</span>
                </div>

                <p class="text-[11px] text-slate-400 dark:text-slate-500 flex items-center gap-1.5 pt-1">
                    <i class="fa-solid fa-lock text-[10px]"></i>
                    <span>Jam cut-off harian (23:59 WIB) dan sensor GPS dilindungi oleh kontrol keamanan platform pusat.</span>
                </p>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Profil
                </button>
            </div>

        </form>

    </div>

    <script>
        function previewFile(event, imgId, placeholderId, badgeId) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(imgId);
                    const placeholder = document.getElementById(placeholderId);
                    const badge = document.getElementById(badgeId);

                    if (img) {
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                    if (badge) {
                        badge.classList.remove('hidden');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

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
