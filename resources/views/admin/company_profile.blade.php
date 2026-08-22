<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-building text-blue-600"></i> {{ __('Profil & Data Perusahaan') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-sm text-sm font-bold">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 p-6 sm:p-10">
                <div class="border-b border-gray-100 pb-6 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-black text-gray-900">Kelola Informasi Resmi Perusahaan</h3>
                        <p class="text-xs text-gray-500 font-medium mt-1">Unggah logo, identitas legalitas, dan profil perusahaan yang tampil di portal karir.</p>
                    </div>
                    <span class="px-3.5 py-1.5 bg-blue-50 text-blue-700 text-xs font-black rounded-full border border-blue-200/60 w-fit flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-blue-600"></i> Verifikasi Perusahaan
                    </span>
                </div>

                <form action="{{ route('admin.company.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Logo Upload Section -->
                    <div class="p-6 bg-gray-50/70 rounded-2xl border border-gray-100 flex flex-col sm:flex-row items-center gap-6">
                        <div class="shrink-0 relative group">
                            <div id="logo-preview-box" class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-white shadow-md relative transition-transform duration-300">
                                @if($profile->logo_path)
                                    <img id="logo-preview-img" src="{{ Storage::url($profile->logo_path) }}" alt="Company Logo" class="w-full h-full object-cover">
                                @else
                                    <div id="logo-preview-placeholder" class="w-full h-full bg-slate-900 text-white font-bold text-xl rounded-xl flex items-center justify-center border border-slate-900">
                                        {{ strtoupper(substr($profile->company_name ?? 'C', 0, 1)) }}
                                    </div>
                                    <img id="logo-preview-img" class="w-full h-full object-cover hidden">
                                @endif
                            </div>
                            <div id="logo-upload-spinner" class="absolute inset-0 bg-slate-900/70 backdrop-blur-xs rounded-2xl flex items-center justify-center text-white hidden animate-fade-in">
                                <i class="fa-solid fa-circle-notch fa-spin text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-grow text-center sm:text-left">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Logo Resmi Perusahaan (PNG/JPG)</label>
                            <input type="file" name="logo" accept="image/*" onchange="previewFile(event, 'logo-preview-img', 'logo-preview-placeholder', 'logo-status-badge')" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition cursor-pointer">
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-2xs text-gray-400">Maksimal 2MB (Rekomendasi rasio 1:1 / Persegi)</p>
                                <span id="logo-status-badge" class="hidden text-2xs font-extrabold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 animate-pulse">
                                    ✨ Pratinjau Siap Simpan!
                                </span>
                            </div>
                            @error('logo') <span class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Grid Info Utama -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="company_name" :value="__('Nama Resmi Perusahaan')" />
                            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('company_name', $profile->company_name)" required placeholder="Misal: PT TechNova Asia Digital" />
                            @error('company_name') <span class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label for="industry" :value="__('Sektor & Industri')" />
                            <x-text-input id="industry" name="industry" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('industry', $profile->industry)" placeholder="Misal: Software, Fintech, E-Commerce" />
                            @error('industry') <span class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label for="company_size" :value="__('Jumlah Karyawan / Ukuran Perusahaan')" />
                            <select id="company_size" name="company_size" class="mt-1 block w-full border-gray-300 rounded-xl shadow-xs focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition text-sm">
                                <option value="">Pilih Ukuran Perusahaan</option>
                                <option value="1 - 10 Karyawan" {{ old('company_size', $profile->company_size) == '1 - 10 Karyawan' ? 'selected' : '' }}>1 - 10 Karyawan (Startup / Micro)</option>
                                <option value="11 - 50 Karyawan" {{ old('company_size', $profile->company_size) == '11 - 50 Karyawan' ? 'selected' : '' }}>11 - 50 Karyawan (Small)</option>
                                <option value="50 - 200 Karyawan" {{ old('company_size', $profile->company_size) == '50 - 200 Karyawan' ? 'selected' : '' }}>50 - 200 Karyawan (Medium)</option>
                                <option value="200 - 1000 Karyawan" {{ old('company_size', $profile->company_size) == '200 - 1000 Karyawan' ? 'selected' : '' }}>200 - 1000 Karyawan (Large Enterprise)</option>
                                <option value="1000+ Karyawan" {{ old('company_size', $profile->company_size) == '1000+ Karyawan' ? 'selected' : '' }}>1000+ Karyawan (Multinational)</option>
                            </select>
                            @error('company_size') <span class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label for="website" :value="__('Website Resmi Perusahaan')" />
                            <x-text-input id="website" name="website" type="url" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('website', $profile->website)" placeholder="https://perusahaan.com" />
                            @error('website') <span class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label for="phone" :value="__('Nomor Telepon Kantor')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('phone', $profile->phone)" placeholder="021-55443322" />
                            @error('phone') <span class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label for="legal_doc" :value="__('Dokumen Legalitas / NIB / SIUP (Opsional)')" />
                            <input type="file" name="legal_doc" accept=".pdf,.jpg,.png" class="mt-1 block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-800 file:text-white hover:file:bg-black transition">
                            @if($profile->legal_doc_path)
                                <a href="{{ Storage::url($profile->legal_doc_path) }}" target="_blank" class="text-2xs text-blue-600 font-bold hover:underline mt-1 inline-block">
                                    <i class="fa-solid fa-file-pdf"></i> Lihat File Legalitas Terunggah
                                </a>
                            @endif
                            @error('legal_doc') <span class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <x-input-label for="address" :value="__('Alamat Lengkap Kantor Utama')" />
                        <textarea id="address" name="address" rows="2" class="mt-1 block w-full border-gray-300 rounded-xl shadow-xs focus:border-slate-800 focus:ring-slate-800 bg-gray-50 focus:bg-white transition text-sm" placeholder="Jl. HR Rasuna Said, Jakarta Selatan...">{{ old('address', $profile->address) }}</textarea>
                        @error('address') <span class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Rekening Bank & NPWP Perusahaan (Hanya untuk Role Company Owner & Super Admin) -->
                    @if(auth()->user()->hasRole('Company Owner') || auth()->user()->hasRole('Super Admin'))
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                        <div class="flex items-center gap-2 text-slate-900 font-bold text-xs">
                            <i class="fa-solid fa-building-columns text-slate-700"></i>
                            <span>Informasi Rekening Bank & NPWP Perusahaan (Wewenang Khusus Company Owner)</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="bank_name" :value="__('Nama Bank')" />
                                <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full bg-white text-xs border-slate-300 rounded-xl" :value="old('bank_name', $profile->bank_name)" placeholder="Misal: Bank BCA / Mandiri / BNI" />
                            </div>

                            <div>
                                <x-input-label for="bank_account_number" :value="__('Nomor Rekening')" />
                                <x-text-input id="bank_account_number" name="bank_account_number" type="text" class="mt-1 block w-full bg-white text-xs border-slate-300 rounded-xl" :value="old('bank_account_number', $profile->bank_account_number)" placeholder="Misal: 1234567890" />
                            </div>

                            <div>
                                <x-input-label for="bank_account_name" :value="__('Atas Nama Rekening')" />
                                <x-text-input id="bank_account_name" name="bank_account_name" type="text" class="mt-1 block w-full bg-white text-xs border-slate-300 rounded-xl" :value="old('bank_account_name', $profile->bank_account_name)" placeholder="Misal: PT TechNova Asia Digital" />
                            </div>

                            <div>
                                <x-input-label for="npwp_number" :value="__('Nomor NPWP Perusahaan')" />
                                <x-text-input id="npwp_number" name="npwp_number" type="text" class="mt-1 block w-full bg-white text-xs border-slate-300 rounded-xl" :value="old('npwp_number', $profile->npwp_number)" placeholder="Misal: 01.234.567.8-901.000" />
                            </div>
                        </div>
                    </div>
                    @endif

                    <div>
                        <x-input-label for="description" :value="__('Deskripsi & Profil Singkat Perusahaan')" />
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-xl shadow-xs focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition text-sm" placeholder="Jelaskan bidang usaha, visi misi, dan budaya perusahaan Anda...">{{ old('description', $profile->description) }}</textarea>
                        @error('description') <span class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <!-- KUSTOMISASI PORTAL KARIR PUBLIK PERUSAHAAN (CAREER PAGE BUILDER) -->
                    <div class="p-6 bg-slate-900 text-white rounded-3xl space-y-6 shadow-md border border-slate-800">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-800 pb-4 gap-3">
                            <div>
                                <h4 class="font-black text-base text-white tracking-tight flex items-center gap-2">
                                    <i class="fa-solid fa-globe text-blue-400"></i> Public Career Page Builder
                                </h4>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">Kustomisasi halaman portal karir publik perusahaan Anda yang dilihat oleh para pencari kerja.</p>
                            </div>
                            <a href="{{ route('companies.show', urlencode($profile->company_name)) }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition shadow-2xs flex items-center gap-2 shrink-0">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Portal Karir Publik &rarr;
                            </a>
                        </div>

                        <!-- Cover Banner Upload -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-300">Gambar Cover / Header Banner Perusahaan (Opsional)</label>
                            <input type="file" name="cover_image" accept="image/*" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-700 transition cursor-pointer border border-slate-700 rounded-xl">
                            @if($profile->cover_image_path)
                                <div class="w-full h-36 rounded-2xl overflow-hidden border border-slate-700 mt-2 relative">
                                    <img src="{{ Storage::url($profile->cover_image_path) }}" class="w-full h-full object-cover">
                                    <div class="absolute bottom-2 left-2 px-2.5 py-1 bg-slate-900/80 backdrop-blur-xs text-white text-3xs font-extrabold rounded-lg">Banner Cover Aktif</div>
                                </div>
                            @endif
                        </div>

                        <!-- Tagline Perusahaan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1">Tagline Perusahaan (Slogan Utama)</label>
                            <input type="text" name="tagline" value="{{ old('tagline', $profile->tagline) }}" placeholder="Misal: Building Future Tech Pioneers Across Southeast Asia" class="w-full p-3 bg-slate-800 border border-slate-700 rounded-xl text-xs text-white focus:ring-2 focus:ring-blue-500 font-medium">
                        </div>

                        <!-- Budaya Kerja -->
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1">Budaya & Suasana Kerja (Workplace Culture)</label>
                            <textarea name="culture_description" rows="3" placeholder="Jelaskan lingkungan kerja, nilai-nilai utama, dan kebiasaan tim di perusahaan Anda..." class="w-full p-3 bg-slate-800 border border-slate-700 rounded-xl text-xs text-white focus:ring-2 focus:ring-blue-500 font-medium">{{ old('culture_description', $profile->culture_description) }}</textarea>
                        </div>

                        <!-- Benefit Karyawan Checkboxes -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-300">Tunjangan & Benefit Karyawan (Fasilitas Perusahaan)</label>
                            @php
                                $existingBenefits = is_array($profile->benefits) ? $profile->benefits : [];
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
                            @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                @foreach($defaultBenefits as $b)
                                    <label class="flex items-center gap-2 p-2.5 bg-slate-800/80 rounded-xl border border-slate-700/80 cursor-pointer hover:bg-slate-800 transition">
                                        <input type="checkbox" name="benefits[]" value="{{ $b }}" {{ in_array($b, $existingBenefits) ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="text-slate-200 font-semibold text-xs">{{ $b }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl shadow-lg hover:shadow-xl transition transform hover:scale-105 text-xs flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Data & Portal Karir Perusahaan
                        </button>
                    </div>
            </div>
        </div>
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
                        img.classList.add('animate-fade-in');
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
    </script>
</x-app-layout>
