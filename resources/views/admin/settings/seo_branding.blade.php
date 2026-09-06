<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 text-[11px] font-semibold rounded-md border border-blue-200 dark:border-blue-800">
                        Brand & Search Optimization
                    </span>
                    <span class="text-xs text-slate-400 dark:text-slate-500">| Global Metadata</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight">
                    Pengaturan Branding, Logo & SEO Web
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                    Kelola identitas merek, logo header, favicon browser, meta tag pencarian Google, dan OpenGraph preview sosial media.
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.dashboard') }}" class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium py-2 px-3.5 rounded-xl text-xs transition border border-slate-200 dark:border-slate-700 shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-slate-400 text-xs"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 min-h-screen bg-slate-50/60 dark:bg-slate-900 transition-colors"
         x-data="seoBrandingManager({
             siteName: @js(old('site_name', $settings['site_name'])),
             siteTagline: @js(old('site_tagline', $settings['site_tagline'])),
             metaTitle: @js(old('seo_meta_title', $settings['seo_meta_title'])),
             metaDescription: @js(old('seo_meta_description', $settings['seo_meta_description'])),
             existingLogoUrl: @js(!empty($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : ''),
             existingFaviconUrl: @js(!empty($settings['site_favicon']) ? asset('storage/' . $settings['site_favicon']) : ''),
             existingOgUrl: @js(!empty($settings['seo_og_image']) ? asset('storage/' . $settings['seo_og_image']) : '')
         })">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-xl text-xs font-medium flex items-center gap-3 shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm shrink-0"></i>
                    <div class="flex-1">{{ session('success') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 rounded-xl text-xs font-medium space-y-1 shadow-2xs">
                    <div class="flex items-center gap-2 font-semibold">
                        <i class="fa-solid fa-circle-exclamation text-amber-600"></i>
                        <span>Mohon periksa kesalahan input berikut:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Main Two-Column Layout -->
            <form action="{{ route('admin.settings.seo.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    <!-- Left Column: Form Settings (8 cols) -->
                    <div class="lg:col-span-8 space-y-6">

                        <!-- SECTION 1: VISUAL ASSETS (LOGO & FAVICON) -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                            <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                        <i class="fa-solid fa-photo-film text-purple-600 dark:text-purple-400"></i>
                                        <span>Aset Visual & Logo Platform</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ikon favicon untuk tab browser dan logo resmi header platform.</p>
                                </div>
                                <span class="text-[11px] font-mono text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded">PNG / SVG / ICO</span>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <!-- 1. Site Header Logo -->
                                    <div class="space-y-3 p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800">
                                        <div class="flex items-center justify-between">
                                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                                Logo Utama Header Web
                                            </label>
                                            <span class="text-[10px] text-slate-400">Maks. 2MB</span>
                                        </div>

                                        <!-- Preview Box -->
                                        <div class="h-20 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center p-3 relative overflow-hidden group">
                                            <template x-if="logoPreviewUrl">
                                                <img :src="logoPreviewUrl" alt="Logo Preview" class="max-h-14 max-w-full object-contain">
                                            </template>
                                            <template x-if="!logoPreviewUrl">
                                                <div class="px-3.5 py-1.5 bg-blue-600 text-white font-extrabold text-sm rounded-lg tracking-tight">
                                                    <span x-text="siteName || 'TalentFlow'"></span>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="space-y-1.5">
                                            <input type="file" name="site_logo" accept="image/png,image/jpeg,image/svg+xml,image/webp" 
                                                @change="handleLogoChange($event)"
                                                class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 dark:file:bg-blue-950/60 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/60 cursor-pointer">
                                            <p class="text-[10px] text-slate-400 dark:text-slate-500">Rekomendasi: PNG Transparan atau SVG. Ukuran ideal ~200x50px.</p>
                                        </div>
                                    </div>

                                    <!-- 2. Favicon Tab Browser -->
                                    <div class="space-y-3 p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800">
                                        <div class="flex items-center justify-between">
                                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                                Favicon Icon (Tab Browser)
                                            </label>
                                            <span class="text-[10px] text-slate-400">Maks. 1MB</span>
                                        </div>

                                        <!-- Preview Box -->
                                        <div class="h-20 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center p-3">
                                            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                                <template x-if="faviconPreviewUrl">
                                                    <img :src="faviconPreviewUrl" alt="Favicon" class="w-5 h-5 object-contain">
                                                </template>
                                                <template x-if="!faviconPreviewUrl">
                                                    <div class="w-5 h-5 rounded bg-blue-600 text-white font-bold flex items-center justify-center text-[10px]">
                                                        <span x-text="(siteName || 'T').charAt(0)"></span>
                                                    </div>
                                                </template>
                                                <span class="text-xs text-slate-600 dark:text-slate-300 font-medium truncate max-w-[120px]" x-text="(siteName || 'TalentFlow') + ' - Tab'"></span>
                                            </div>
                                        </div>

                                        <div class="space-y-1.5">
                                            <input type="file" name="site_favicon" accept="image/x-icon,image/png,image/svg+xml,image/webp"
                                                @change="handleFaviconChange($event)"
                                                class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 dark:file:bg-purple-950/60 file:text-purple-700 dark:file:text-purple-300 hover:file:bg-purple-100 dark:hover:file:bg-purple-900/60 cursor-pointer">
                                            <p class="text-[10px] text-slate-400 dark:text-slate-500">Rekomendasi: Format .ico atau PNG rasio 1:1 (32x32px / 64x64px).</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: PLATFORM IDENTITY & TAGLINE -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                            <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                        <i class="fa-solid fa-id-card text-blue-600 dark:text-blue-400"></i>
                                        <span>Identitas Platform & Tagline</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Nama brand utama dan slogan yang tampil pada navbar dan footer.</p>
                                </div>
                            </div>

                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            Nama Website / Brand <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="site_name" x-model="siteName" required placeholder="Contoh: TalentFlow" 
                                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3 font-semibold">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            Tagline / Slogan Brand
                                        </label>
                                        <input type="text" name="site_tagline" x-model="siteTagline" placeholder="Contoh: Platform Rekrutmen Enterprise & Portal Karir Modern" 
                                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: SEO META & SEARCH OPTIMIZATION -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                            <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                        <i class="fa-solid fa-magnifying-glass text-emerald-600 dark:text-emerald-400"></i>
                                        <span>Optimasi Meta Tag & SEO Google</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pengaturan judul dan deskripsi yang dibaca oleh mesin perayap (Google, Bing).</p>
                                </div>
                                <span class="text-[11px] font-mono text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded">SERP Ready</span>
                            </div>

                            <div class="p-6 space-y-4">
                                <!-- Meta Title -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            Meta Title (Judul Hasil Pencarian Google) <span class="text-rose-500">*</span>
                                        </label>
                                        <span class="text-[10px] font-mono" :class="metaTitle.length > 60 ? 'text-amber-500 font-bold' : 'text-slate-400'">
                                            <span x-text="metaTitle.length"></span> / 60 karakter
                                        </span>
                                    </div>
                                    <input type="text" name="seo_meta_title" x-model="metaTitle" required placeholder="Judul halaman utama di hasil pencarian" 
                                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3 font-semibold">
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500">Panjang ideal antara 50 - 60 karakter agar tidak terpotong (... ) di Google.</p>
                                </div>

                                <!-- Meta Description -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            Meta Description (Ringkasan Cuplikan SEO) <span class="text-rose-500">*</span>
                                        </label>
                                        <span class="text-[10px] font-mono" :class="metaDescription.length > 160 ? 'text-amber-500 font-bold' : 'text-slate-400'">
                                            <span x-text="metaDescription.length"></span> / 160 karakter
                                        </span>
                                    </div>
                                    <textarea name="seo_meta_description" x-model="metaDescription" rows="3" required placeholder="Deskripsi singkat tentang layanan portal karir dan rekrutmen perusahaan" 
                                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3 leading-relaxed"></textarea>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500">Panjang ideal antara 120 - 160 karakter untuk tingkat klik (CTR) yang maksimal.</p>
                                </div>

                                <!-- Meta Keywords -->
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        Meta Keywords (Kata Kunci Relevan)
                                    </label>
                                    <input type="text" name="seo_meta_keywords" value="{{ old('seo_meta_keywords', $settings['seo_meta_keywords']) }}" placeholder="lowongan kerja, karir indonesia, rekrutmen enterprise, hr system, lamar online" 
                                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500">Pisahkan setiap kata kunci menggunakan tanda koma (,).</p>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: OPENGRAPH & SOCIAL SHARING -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                            <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                        <i class="fa-solid fa-share-nodes text-sky-500"></i>
                                        <span>OpenGraph & Pratinjau Sosial Media</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Gambar banner yang muncul saat link website disebarkan ke WhatsApp, LinkedIn, dan Twitter.</p>
                                </div>
                                <span class="text-[11px] font-mono text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded">1200 x 630 px</span>
                            </div>

                            <div class="p-6">
                                <div class="space-y-3 p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            OpenGraph Banner Image
                                        </label>
                                        <span class="text-[10px] text-slate-400">Rekomendasi 1200x630px (Maks. 3MB)</span>
                                    </div>

                                    <div class="h-32 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center overflow-hidden">
                                        <template x-if="ogPreviewUrl">
                                            <img :src="ogPreviewUrl" alt="OG Preview Banner" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!ogPreviewUrl">
                                            <div class="text-center p-4">
                                                <i class="fa-regular fa-image text-slate-300 dark:text-slate-600 text-2xl mb-1"></i>
                                                <p class="text-xs text-slate-400">Belum ada gambar OpenGraph kustom (Banner default digunakan)</p>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="space-y-1.5">
                                        <input type="file" name="seo_og_image" accept="image/png,image/jpeg,image/webp"
                                            @change="handleOgChange($event)"
                                            class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-sky-50 dark:file:bg-sky-950/60 file:text-sky-700 dark:file:text-sky-300 hover:file:bg-sky-100 dark:hover:file:bg-sky-900/60 cursor-pointer">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 5: CONTACT & ANALYTICS -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                            <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                        <i class="fa-solid fa-headset text-indigo-500"></i>
                                        <span>Informasi Kontak & Analytics</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kontak bantuan resmi dan integrasi pelacakan lalu lintas pengunjung.</p>
                                </div>
                            </div>

                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            Email Customer Support
                                        </label>
                                        <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email']) }}" placeholder="support@domain.com" 
                                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            No. WhatsApp / Telepon Hotline
                                        </label>
                                        <input type="text" name="support_phone" value="{{ old('support_phone', $settings['support_phone']) }}" placeholder="+62 812-3456-7890" 
                                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                                    </div>

                                    <div class="sm:col-span-2 space-y-1.5">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            Alamat Kantor Pusat
                                        </label>
                                        <input type="text" name="address" value="{{ old('address', $settings['address']) }}" placeholder="Jakarta South Quarter Lt. 15, Jl. RA Kartini No. 8, Cilandak, Jakarta Selatan" 
                                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                                    </div>

                                    <div class="sm:col-span-2 space-y-1.5">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            Google Analytics Measurement ID
                                        </label>
                                        <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', $settings['google_analytics_id']) }}" placeholder="G-XXXXXXXXXX" 
                                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3 font-mono">
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500">Opsional: Masukkan kode Google Analytics 4 (GA4) untuk melacak pengunjung secara otomatis.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button Bar -->
                        <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-2xs">
                            <span class="text-xs text-slate-500 dark:text-slate-400">
                                Pastikan pratinjau di samping kanan sudah sesuai sebelum menyimpan.
                            </span>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span>Simpan Perubahan Branding & SEO</span>
                            </button>
                        </div>

                    </div>

                    <!-- Right Column: Live SERP & Social Previews (4 cols) -->
                    <div class="lg:col-span-4 space-y-6">

                        <!-- 1. Google SERP Live Preview -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 overflow-hidden sticky top-6">
                            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-brands fa-google text-red-500"></i>
                                    <span>Pratinjau Hasil Pencarian Google</span>
                                </h4>
                                <span class="text-[10px] font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-800">
                                    Live SERP
                                </span>
                            </div>

                            <div class="p-5 space-y-4">
                                <!-- Google Snippet Mockup -->
                                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 space-y-1.5 font-sans">
                                    <div class="flex items-center gap-2 text-xs">
                                        <div class="w-4 h-4 rounded bg-blue-600 text-white flex items-center justify-center text-[9px] font-bold shrink-0">
                                            <template x-if="faviconPreviewUrl">
                                                <img :src="faviconPreviewUrl" class="w-3.5 h-3.5 object-contain">
                                            </template>
                                            <template x-if="!faviconPreviewUrl">
                                                <span x-text="(siteName || 'T').charAt(0)"></span>
                                            </template>
                                        </div>
                                        <div class="truncate text-[11px] text-slate-600 dark:text-slate-400">
                                            <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="siteName || 'TalentFlow'"></span>
                                            <span class="text-slate-400 text-[10px]">&bull; https://talentflow.id</span>
                                        </div>
                                    </div>

                                    <h5 class="text-sm font-medium text-blue-700 dark:text-blue-400 hover:underline cursor-pointer leading-snug break-words" 
                                        x-text="metaTitle || 'Judul Halaman Pencarian Belum Diisi'"></h5>

                                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-3 break-words" 
                                        x-text="metaDescription || 'Deskripsi cuplikan belum disetel. Isi formulir di sebelah kiri untuk melihat hasil pratinjau langsung...'"></p>
                                </div>

                                <!-- 2. Social Media OpenGraph Card Preview -->
                                <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-slate-700/60">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                            <i class="fa-solid fa-share-nodes text-sky-500"></i>
                                            <span>Pratinjau Kartu Sosmed (WhatsApp / LinkedIn)</span>
                                        </span>
                                    </div>

                                    <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-950">
                                        <!-- OG Image Mock -->
                                        <div class="h-28 bg-slate-200 dark:bg-slate-900 flex items-center justify-center overflow-hidden">
                                            <template x-if="ogPreviewUrl">
                                                <img :src="ogPreviewUrl" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!ogPreviewUrl">
                                                <div class="flex flex-col items-center justify-center text-slate-400 text-xs">
                                                    <i class="fa-solid fa-image text-xl mb-1"></i>
                                                    <span>OpenGraph Default Banner</span>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="p-3 bg-white dark:bg-slate-900 space-y-1">
                                            <span class="text-[10px] text-slate-400 uppercase tracking-wider font-mono">talentflow.id</span>
                                            <div class="text-xs font-bold text-slate-900 dark:text-white line-clamp-1" x-text="metaTitle || siteName || 'TalentFlow'"></div>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2" x-text="metaDescription || 'Platform Rekrutmen Enterprise'"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3. SEO Quality Health Indicators -->
                                <div class="space-y-2.5 pt-2 border-t border-slate-100 dark:border-slate-700/60">
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Indikator Kualitas SEO:</span>
                                    
                                    <div class="space-y-2 text-xs">
                                        <div class="flex items-center justify-between">
                                            <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                                <i class="fa-solid fa-circle text-[8px]" :class="metaTitle.length >= 30 && metaTitle.length <= 60 ? 'text-emerald-500' : 'text-amber-500'"></i>
                                                <span>Panjang Meta Title</span>
                                            </span>
                                            <span class="font-mono text-[11px]" :class="metaTitle.length >= 30 && metaTitle.length <= 60 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400'" x-text="metaTitle.length + ' char'"></span>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                                <i class="fa-solid fa-circle text-[8px]" :class="metaDescription.length >= 70 && metaDescription.length <= 160 ? 'text-emerald-500' : 'text-amber-500'"></i>
                                                <span>Panjang Meta Description</span>
                                            </span>
                                            <span class="font-mono text-[11px]" :class="metaDescription.length >= 70 && metaDescription.length <= 160 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400'" x-text="metaDescription.length + ' char'"></span>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                                <i class="fa-solid fa-circle text-[8px]" :class="faviconPreviewUrl ? 'text-emerald-500' : 'text-slate-300 dark:text-slate-600'"></i>
                                                <span>Ikon Favicon</span>
                                            </span>
                                            <span class="text-[11px]" :class="faviconPreviewUrl ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400'" x-text="faviconPreviewUrl ? 'Terpasang' : 'Default'"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>

    <!-- Alpine.js Image Preview & Character Counter Logic -->
    <script>
        function seoBrandingManager(initial) {
            return {
                siteName: initial.siteName || '',
                siteTagline: initial.siteTagline || '',
                metaTitle: initial.metaTitle || '',
                metaDescription: initial.metaDescription || '',
                logoPreviewUrl: initial.existingLogoUrl || '',
                faviconPreviewUrl: initial.existingFaviconUrl || '',
                ogPreviewUrl: initial.existingOgUrl || '',

                handleLogoChange(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.logoPreviewUrl = URL.createObjectURL(file);
                    }
                },

                handleFaviconChange(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.faviconPreviewUrl = URL.createObjectURL(file);
                    }
                },

                handleOgChange(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.ogPreviewUrl = URL.createObjectURL(file);
                    }
                }
            };
        }
    </script>
</x-app-layout>
