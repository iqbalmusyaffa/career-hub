<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 text-[11px] font-semibold rounded-md border border-rose-200 dark:border-rose-800">
                        Anti-Fraud & Security
                    </span>
                    <span class="text-xs text-slate-400 dark:text-slate-500">| Sentinel Interceptor</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight">
                    Anti-Fraud Security Sentinel & Blacklist
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                    Kelola daftar hitam email berbahaya, IP address penyerang, nomor telepon spam, dan perusahaan fiktif.
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.dashboard') }}" class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium py-2 px-3.5 rounded-xl text-xs transition border border-slate-200 dark:border-slate-700 shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-slate-400 text-xs"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 min-h-screen bg-slate-50/60 dark:bg-slate-900 transition-colors"
         x-data="blacklistManager({
             type: @js(old('type', 'email')),
             value: @js(old('value', '')),
             reason: @js(old('reason', ''))
         })">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-xl text-xs font-medium flex items-center gap-3 shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm shrink-0"></i>
                    <div class="flex-1">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-xl text-xs font-medium flex items-center gap-3 shadow-2xs">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 dark:text-rose-400 text-sm shrink-0"></i>
                    <div class="flex-1">{{ session('error') }}</div>
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

            <!-- 1. METRIC SUMMARY CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Blacklists -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Entitas Diblokir</span>
                        <div class="w-9 h-9 rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xs border border-rose-100 dark:border-rose-800">
                            <i class="fa-solid fa-ban"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalBlacklists) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Aktif dicegat oleh sistem</p>
                    </div>
                </div>

                <!-- Email Blacklists -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Alamat Email Diblokir</span>
                        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs border border-blue-100 dark:border-blue-800">
                            <i class="fa-solid fa-envelope-circle-check"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 tracking-tight">{{ number_format($emailBlacklists) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Penipuan & spam akun</p>
                    </div>
                </div>

                <!-- IP Blacklists -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">IP Address Dicekal</span>
                        <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs border border-amber-100 dark:border-amber-800">
                            <i class="fa-solid fa-network-wired"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 tracking-tight">{{ number_format($ipBlacklists) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Pencegahan bot & brute-force</p>
                    </div>
                </div>

                <!-- Phone & Company -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Telepon & PT Bodong</span>
                        <div class="w-9 h-9 rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs border border-purple-100 dark:border-purple-800">
                            <i class="fa-solid fa-building-circle-xmark"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($otherBlacklists) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Perusahaan fiktif & nomor WA</p>
                    </div>
                </div>
            </div>

            <!-- 2. SPLIT SECTION: FORM (7 COLS) & SENTINEL GUIDE (5 COLS) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left: Form Tambah Blacklist Item (7 cols) -->
                <div class="lg:col-span-7 space-y-6">
                    <form action="{{ route('admin.blacklists.store') }}" method="POST" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                        @csrf

                        <!-- Card Header -->
                        <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-user-slash text-rose-600 dark:text-rose-400"></i>
                                    <span>Tambah Item ke Daftar Hitam (Blacklist)</span>
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Entitas yang didaftarkan akan langsung ditolak saat mencoba login atau register.</p>
                            </div>
                            <span class="text-[11px] font-mono text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/60 px-2 py-0.5 rounded border border-rose-200 dark:border-rose-800">Auto-Reject</span>
                        </div>

                        <div class="p-6 space-y-5">
                            <!-- Entity Type Selection Cards -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    Tipe Entitas Blacklist <span class="text-rose-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                    <label class="cursor-pointer border rounded-xl p-2.5 flex items-center gap-2 transition-all"
                                        :class="type === 'email' ? 'border-rose-600 bg-rose-50/50 dark:bg-rose-950/40 text-rose-900 dark:text-rose-300 shadow-2xs font-semibold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:border-slate-300'">
                                        <input type="radio" name="type" value="email" x-model="type" class="text-rose-600 focus:ring-rose-600">
                                        <div class="text-xs flex items-center gap-1.5">
                                            <i class="fa-solid fa-at text-rose-500"></i>
                                            <span>Email</span>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer border rounded-xl p-2.5 flex items-center gap-2 transition-all"
                                        :class="type === 'ip' ? 'border-amber-500 bg-amber-50/50 dark:bg-amber-950/40 text-amber-900 dark:text-amber-300 shadow-2xs font-semibold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:border-slate-300'">
                                        <input type="radio" name="type" value="ip" x-model="type" class="text-amber-500 focus:ring-amber-500">
                                        <div class="text-xs flex items-center gap-1.5">
                                            <i class="fa-solid fa-network-wired text-amber-500"></i>
                                            <span>IP Address</span>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer border rounded-xl p-2.5 flex items-center gap-2 transition-all"
                                        :class="type === 'phone' ? 'border-blue-600 bg-blue-50/50 dark:bg-blue-950/40 text-blue-900 dark:text-blue-300 shadow-2xs font-semibold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:border-slate-300'">
                                        <input type="radio" name="type" value="phone" x-model="type" class="text-blue-600 focus:ring-blue-600">
                                        <div class="text-xs flex items-center gap-1.5">
                                            <i class="fa-solid fa-phone text-blue-500"></i>
                                            <span>Telepon</span>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer border rounded-xl p-2.5 flex items-center gap-2 transition-all"
                                        :class="type === 'company_name' ? 'border-purple-600 bg-purple-50/50 dark:bg-purple-950/40 text-purple-900 dark:text-purple-300 shadow-2xs font-semibold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:border-slate-300'">
                                        <input type="radio" name="type" value="company_name" x-model="type" class="text-purple-600 focus:ring-purple-600">
                                        <div class="text-xs flex items-center gap-1.5">
                                            <i class="fa-solid fa-building text-purple-500"></i>
                                            <span>PT Bodong</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Value Input -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    Nilai Entitas yang Diblokir <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                        <template x-if="type === 'email'"><i class="fa-solid fa-envelope"></i></template>
                                        <template x-if="type === 'ip'"><i class="fa-solid fa-network-wired"></i></template>
                                        <template x-if="type === 'phone'"><i class="fa-solid fa-phone"></i></template>
                                        <template x-if="type === 'company_name'"><i class="fa-solid fa-building"></i></template>
                                    </div>
                                    <input type="text" name="value" x-model="value" required 
                                        :placeholder="getPlaceholder()" 
                                        class="w-full pl-9 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-rose-600 focus:border-rose-600 text-slate-900 dark:text-slate-100 py-2.5 px-3 font-mono">
                                </div>
                            </div>

                            <!-- Reason Input -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    Alasan Resmi Pemblokiran (Justifikasi Audit) <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="reason" x-model="reason" rows="3" required placeholder="Jelaskan indikasi kecurangan, penipuan, pemalsuan dokumen, atau aktivitas spamming..." 
                                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-rose-600 focus:border-rose-600 text-slate-900 dark:text-slate-100 py-2.5 px-3 leading-relaxed"></textarea>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500">Minimal 5 karakter. Alasan ini akan tercatat dalam log audit sistem.</p>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-950 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                                <i class="fa-solid fa-shield-halved text-rose-500"></i>
                                <span>Akun terhubung dengan email ini akan otomatis disuspensi.</span>
                            </span>
                            <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold py-2.5 px-5 rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                                <i class="fa-solid fa-ban"></i>
                                <span>Blokir & Simpan Blacklist</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Security Sentinel Architecture Card (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 p-5 space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-700/80">
                            <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-shield-virus text-rose-600 dark:text-rose-400"></i>
                                <span>Mekanisme Sentinel Anti-Fraud</span>
                            </h4>
                            <span class="text-[10px] font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-800 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Middleware Aktif
                            </span>
                        </div>

                        <div class="space-y-3 text-xs text-slate-600 dark:text-slate-300">
                            <!-- Rule 1 -->
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 space-y-1">
                                <div class="font-semibold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                    <i class="fa-solid fa-lock text-rose-500"></i>
                                    <span>Pencegahan Login & Pendaftaran</span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                    Setiap percobaan login atau registrasi yang mendeteksi email atau IP di daftar hitam akan langsung dibatalkan dengan pesan error keamanan.
                                </p>
                            </div>

                            <!-- Rule 2 -->
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 space-y-1">
                                <div class="font-semibold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                    <i class="fa-solid fa-user-xmark text-amber-500"></i>
                                    <span>Suspensi Akun Otomatis</span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                    Jika email yang didaftarkan ke blacklist sudah memiliki akun aktif di sistem, status akun tersebut akan otomatis diubah menjadi <strong>Suspended</strong>.
                                </p>
                            </div>

                            <!-- Rule 3 -->
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 space-y-1">
                                <div class="font-semibold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                    <i class="fa-solid fa-building-circle-xmark text-purple-500"></i>
                                    <span>Penyaringan Perusahaan Fiktif</span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                    Nama perusahaan penipuan atau nomor kontak bodong yang masuk daftar ini dicegah dari memposting lowongan kerja baru.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- 3. TABEL DATA BLACKLIST (BOTTOM) -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 overflow-hidden shadow-2xs">
                
                <!-- Table Header & Filter Bar -->
                <div class="p-5 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-shield-cat text-rose-600 dark:text-rose-400"></i>
                            <span>Daftar Entitas Blacklist Aktif</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total {{ $blacklists->total() }} entitas saat ini dicegah dari akses platform.</p>
                    </div>

                    <!-- Search Filter -->
                    <form method="GET" action="{{ route('admin.blacklists.index') }}" class="flex items-center gap-2 flex-wrap">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nilai/alasan blokir..." 
                                class="pl-8 pr-3 py-1.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-rose-600 focus:border-rose-600">
                        </div>

                        <select name="type" onchange="this.form.submit()" class="bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 py-1.5 px-2.5 focus:ring-1 focus:ring-rose-600">
                            <option value="">Semua Tipe</option>
                            <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="ip" {{ request('type') == 'ip' ? 'selected' : '' }}>IP Address</option>
                            <option value="phone" {{ request('type') == 'phone' ? 'selected' : '' }}>Telepon</option>
                            <option value="company_name" {{ request('type') == 'company_name' ? 'selected' : '' }}>PT Bodong</option>
                        </select>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-700/80 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-4 pl-6">Tgl Didaftarkan</th>
                                <th class="py-3 px-4">Tipe & Nilai Diblokir</th>
                                <th class="py-3 px-4">Alasan Pemblokiran</th>
                                <th class="py-3 px-4">Admin Pemblokir</th>
                                <th class="py-3 px-4 pr-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs text-slate-700 dark:text-slate-300">
                            @forelse($blacklists as $item)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/30 transition">
                                    <!-- Date -->
                                    <td class="py-3.5 px-4 pl-6 whitespace-nowrap">
                                        <div class="font-bold text-slate-900 dark:text-white text-xs font-mono">
                                            {{ $item->created_at->format('d M Y, H:i') }} WIB
                                        </div>
                                        <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                                            {{ $item->created_at->diffForHumans() }}
                                        </div>
                                    </td>

                                    <!-- Type & Value -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 text-[10px] font-mono font-semibold rounded-md border uppercase inline-flex items-center gap-1
                                                {{ $item->type === 'email' ? 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border-rose-200 dark:border-rose-800' : '' }}
                                                {{ $item->type === 'ip' ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800' : '' }}
                                                {{ $item->type === 'phone' ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800' : '' }}
                                                {{ $item->type === 'company_name' ? 'bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-800' : '' }}">
                                                @if($item->type === 'email')<i class="fa-solid fa-at text-[9px]"></i>@endif
                                                @if($item->type === 'ip')<i class="fa-solid fa-network-wired text-[9px]"></i>@endif
                                                @if($item->type === 'phone')<i class="fa-solid fa-phone text-[9px]"></i>@endif
                                                @if($item->type === 'company_name')<i class="fa-solid fa-building text-[9px]"></i>@endif
                                                <span>{{ $item->type }}</span>
                                            </span>
                                            <code class="font-mono text-xs font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-800">{{ $item->value }}</code>
                                        </div>
                                    </td>

                                    <!-- Reason -->
                                    <td class="py-3.5 px-4 max-w-md">
                                        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-[11px] leading-relaxed">
                                            "{{ $item->reason }}"
                                        </div>
                                    </td>

                                    <!-- Blocker Admin -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="font-semibold text-slate-900 dark:text-white text-xs">
                                            {{ $item->blocker->name ?? 'Super Admin' }}
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-3.5 px-4 pr-6 text-right whitespace-nowrap">
                                        <form action="{{ route('admin.blacklists.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENGHAPUS entitas ini dari daftar hitam?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-medium text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-800 transition inline-flex items-center gap-1">
                                                <i class="fa-solid fa-unlock text-[10px]"></i>
                                                <span>Buka Blokir</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs font-medium">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-900 text-slate-400 dark:text-slate-600 flex items-center justify-center text-xl mx-auto mb-3">
                                            <i class="fa-solid fa-shield-check text-emerald-500"></i>
                                        </div>
                                        <div class="text-sm font-semibold text-slate-700 dark:text-slate-300">Daftar Hitam Kosong</div>
                                        <p class="text-slate-400 mt-1">Belum ada entitas berbahaya yang terdaftar dalam sistem.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($blacklists->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-950/60">
                        {{ $blacklists->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Alpine.js Dynamic Placeholder Manager -->
    <script>
        function blacklistManager(initial) {
            return {
                type: initial.type || 'email',
                value: initial.value || '',
                reason: initial.reason || '',

                getPlaceholder() {
                    if (this.type === 'email') return 'Contoh: penipu@gmail.com atau spammer@domain.com';
                    if (this.type === 'ip') return 'Contoh: 192.168.1.100 atau 103.45.67.89';
                    if (this.type === 'phone') return 'Contoh: +6281234567890 atau 081298765432';
                    if (this.type === 'company_name') return 'Contoh: PT Fake Investasi Digital';
                    return 'Masukkan nilai yang ingin diblokir...';
                }
            };
        }
    </script>
</x-app-layout>
