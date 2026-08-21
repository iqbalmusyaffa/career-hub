<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-bullhorn text-blue-600"></i> Pengaturan Branding, Logo & SEO Web Global
                </h2>
                <p class="text-xs text-slate-500 mt-1">Kelola identitas merek, logo header, ikon favicon, meta tag SEO, dan OpenGraph preview sosial media.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-4 rounded-xl text-sm transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center gap-2 shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.settings.seo.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- SECTION 1: LOGO & FAVICON ICON -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-icons text-purple-600"></i> Logo Perusahaan & Favicon Icon
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Unggah ikon Favicon (.ico / .png) untuk tab browser dan Logo Header web.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Site Logo -->
                        <div class="space-y-3 bg-slate-50 p-5 rounded-2xl border border-slate-200/80">
                            <label class="block text-xs font-bold text-slate-800">Logo Utama Header Web</label>
                            @if(!empty($settings['site_logo']))
                                <div class="p-3 bg-white rounded-xl border border-slate-200 inline-block">
                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo Web" class="h-10 object-contain">
                                </div>
                            @else
                                <div class="p-3 bg-blue-600 text-white font-black text-xl rounded-xl inline-block">
                                    TalentFlow
                                </div>
                            @endif
                            <input type="file" name="site_logo" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-3xs text-slate-400">Rekomendasi: PNG Transparan atau SVG. Ukuran ideal: 200x50px.</p>
                        </div>

                        <!-- Site Favicon -->
                        <div class="space-y-3 bg-slate-50 p-5 rounded-2xl border border-slate-200/80">
                            <label class="block text-xs font-bold text-slate-800">Favicon Icon (Tab Browser)</label>
                            @if(!empty($settings['site_favicon']))
                                <div class="p-3 bg-white rounded-xl border border-slate-200 inline-block">
                                    <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="w-8 h-8 object-contain">
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-black flex items-center justify-center text-xs">
                                    T
                                </div>
                            @endif
                            <input type="file" name="site_favicon" accept="image/*,.ico" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                            <p class="text-3xs text-slate-400">Rekomendasi: File .ico atau PNG 32x32px.</p>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: SITE IDENTITY & CONTACT -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-blue-600"></i> Identitas Platform & Kontak Resmi
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Nama website, tagline, dan informasi kontak customer support.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Website / Platform</label>
                            <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Slogan / Tagline Website</label>
                            <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline']) }}" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Email Support</label>
                            <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email']) }}" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">No. WhatsApp / Telepon Support</label>
                            <input type="text" name="support_phone" value="{{ old('support_phone', $settings['support_phone']) }}" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-bold text-slate-700 mb-1">Alamat Kantor Utama</label>
                            <input type="text" name="address" value="{{ old('address', $settings['address']) }}" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: SEO META & OPENGRAPH SOCIAL MEDIA -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-magnifying-glass text-emerald-600"></i> Mesin Optimasi SEO & Social Share (OpenGraph)
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Atur judul meta, deskripsi pencarian Google, kata kunci, dan gambar preview saat link disebar ke WhatsApp / Sosmed.</p>
                    </div>

                    <div class="space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Meta Title (Judul Pencarian Google)</label>
                            <input type="text" name="seo_meta_title" value="{{ old('seo_meta_title', $settings['seo_meta_title']) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-semibold">
                            <p class="text-3xs text-slate-400 mt-1">Judul yang muncul di halaman hasil pencarian Google.</p>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Meta Description (Deskripsi Ringkas SEO)</label>
                            <textarea name="seo_meta_description" rows="3" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">{{ old('seo_meta_description', $settings['seo_meta_description']) }}</textarea>
                            <p class="text-3xs text-slate-400 mt-1">Rekomendasi 150-160 karakter untuk hasil pencarian Google yang maksimal.</p>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Meta Keywords (Kata Kunci Dipisah Koma)</label>
                            <input type="text" name="seo_meta_keywords" value="{{ old('seo_meta_keywords', $settings['seo_meta_keywords']) }}" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                            <!-- OpenGraph Image Upload -->
                            <div class="space-y-3 bg-slate-50 p-5 rounded-2xl border border-slate-200/80">
                                <label class="block font-bold text-slate-800">OpenGraph Preview Image (Social Media Share)</label>
                                @if(!empty($settings['seo_og_image']))
                                    <div class="p-2 bg-white rounded-xl border border-slate-200 inline-block">
                                        <img src="{{ asset('storage/' . $settings['seo_og_image']) }}" alt="OG Preview" class="h-24 object-cover rounded-lg">
                                    </div>
                                @endif
                                <input type="file" name="seo_og_image" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                <p class="text-3xs text-slate-400">Gambar kartu yang tampil otomatis saat link website dibagikan ke WhatsApp, LinkedIn, & Facebook. (Rekomendasi: 1200x630px).</p>
                            </div>

                            <!-- Google Analytics ID -->
                            <div class="space-y-3 bg-slate-50 p-5 rounded-2xl border border-slate-200/80">
                                <label class="block font-bold text-slate-800">Google Analytics Measurement ID</label>
                                <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', $settings['google_analytics_id']) }}" placeholder="G-XXXXXXXXXX atau UA-XXXXX-Y" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-mono">
                                <p class="text-3xs text-slate-400">Masukkan kode Google Analytics untuk melacak pengunjung secara otomatis.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-8 py-3 rounded-2xl text-xs transition shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan Branding & SEO
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
