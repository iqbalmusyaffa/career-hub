<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 text-[11px] font-semibold rounded-md border border-blue-200 dark:border-blue-800">
                        Platform Broadcast
                    </span>
                    <span class="text-xs text-slate-400 dark:text-slate-500">| System Notices</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight">
                    Broadcast Center & Pengumuman Global
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                    Kirim pengumuman resmi, banner siaran darurat, dan notifikasi sistem ke seluruh pengguna platform.
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
         x-data="announcementManager({
             title: @js(old('title', '')),
             content: @js(old('content', '')),
             type: @js(old('type', 'info')),
             targetRole: @js(old('target_role', 'all'))
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

            <!-- 1. METRIC SUMMARY CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Announcements -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Siaran Dibuat</span>
                        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs border border-blue-100 dark:border-blue-800">
                            <i class="fa-solid fa-bullhorn"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalAnnouncements) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Riwayat pengumuman sistem</p>
                    </div>
                </div>

                <!-- Active Broadcasts -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Siaran Tayang Aktif</span>
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs border border-emerald-100 dark:border-emerald-800">
                            <i class="fa-solid fa-tower-broadcast"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 tracking-tight">{{ number_format($activeAnnouncements) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Tampil di dashboard pengguna</p>
                    </div>
                </div>

                <!-- Urgent / Critical Alerts -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Siaran Kritis (Danger)</span>
                        <div class="w-9 h-9 rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xs border border-rose-100 dark:border-rose-800">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-rose-600 dark:text-rose-400 tracking-tight">{{ number_format($urgentAnnouncements) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Pemberitahuan darurat / maintenance</p>
                    </div>
                </div>

                <!-- Global Audience -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Target Seluruh Pengguna</span>
                        <div class="w-9 h-9 rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs border border-purple-100 dark:border-purple-800">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($allAudienceAnnouncements) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Disiarkan ke semua role</p>
                    </div>
                </div>
            </div>

            <!-- 2. SPLIT SECTION: CREATOR FORM (7 COLS) & LIVE BANNER PREVIEW (5 COLS) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left: Announcement Creator Form (7 cols) -->
                <div class="lg:col-span-7 space-y-6">
                    <form action="{{ route('admin.announcements.store') }}" method="POST" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                        @csrf

                        <!-- Card Header -->
                        <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-paper-plane text-blue-600 dark:text-blue-400"></i>
                                    <span>Buat & Siarkan Pengumuman Baru</span>
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tentukan isi pesan, target audiens, dan tanggal kedaluwarsa.</p>
                            </div>
                            <span class="text-[11px] font-mono text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded">Broadcast Push</span>
                        </div>

                        <div class="p-6 space-y-5">
                            <!-- Judul -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    Judul Pengumuman <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="title" x-model="title" required placeholder="Misal: Pemeliharaan Server Terjadwal 24 Agustus 2026" 
                                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3 font-semibold">
                            </div>

                            <!-- Alert Type Selector Cards -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    Tipe Banner Alert & Warna <span class="text-rose-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                    <label class="cursor-pointer border rounded-xl p-2.5 flex items-center gap-2 transition-all"
                                        :class="type === 'info' ? 'border-blue-600 bg-blue-50/50 dark:bg-blue-950/40 text-blue-900 dark:text-blue-300 shadow-2xs font-semibold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:border-slate-300'">
                                        <input type="radio" name="type" value="info" x-model="type" class="text-blue-600 focus:ring-blue-600">
                                        <div class="text-xs flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle-info text-blue-500"></i>
                                            <span>Info</span>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer border rounded-xl p-2.5 flex items-center gap-2 transition-all"
                                        :class="type === 'warning' ? 'border-amber-500 bg-amber-50/50 dark:bg-amber-950/40 text-amber-900 dark:text-amber-300 shadow-2xs font-semibold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:border-slate-300'">
                                        <input type="radio" name="type" value="warning" x-model="type" class="text-amber-500 focus:ring-amber-500">
                                        <div class="text-xs flex items-center gap-1.5">
                                            <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                                            <span>Peringatan</span>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer border rounded-xl p-2.5 flex items-center gap-2 transition-all"
                                        :class="type === 'success' ? 'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-300 shadow-2xs font-semibold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:border-slate-300'">
                                        <input type="radio" name="type" value="success" x-model="type" class="text-emerald-600 focus:ring-emerald-600">
                                        <div class="text-xs flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                            <span>Sukses</span>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer border rounded-xl p-2.5 flex items-center gap-2 transition-all"
                                        :class="type === 'danger' ? 'border-rose-600 bg-rose-50/50 dark:bg-rose-950/40 text-rose-900 dark:text-rose-300 shadow-2xs font-semibold' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:border-slate-300'">
                                        <input type="radio" name="type" value="danger" x-model="type" class="text-rose-600 focus:ring-rose-600">
                                        <div class="text-xs flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                                            <span>Kritis</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Target Role & Expiry Date -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        Target Pengguna Penerima <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="target_role" x-model="targetRole" required 
                                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                                        <option value="all">🌐 Seluruh Pengguna (Global Platform Update)</option>
                                        <option value="intern">🎓 Khusus Peserta Magang (Internship)</option>
                                        <option value="candidate">👨‍💼 Khusus Pencari Kerja (Job Seekers)</option>
                                        <option value="company_owner">🏢 Pimpinan Perusahaan (Company Owner)</option>
                                        <option value="hr">💼 Tim HR & Recruiter</option>
                                        <option value="mentor">🧑‍🏫 Pembimbing / Mentor Magang</option>
                                    </select>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        Tanggal Kedaluwarsa (Opsional)
                                    </label>
                                    <input type="date" name="expires_at" 
                                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                                </div>
                            </div>

                            <!-- Content Area -->
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        Materi Pengumuman Lengkap <span class="text-rose-500">*</span>
                                    </label>
                                    <span class="text-[10px] text-slate-400 font-mono">
                                        <span x-text="content.length"></span> karakter
                                    </span>
                                </div>
                                <textarea name="content" x-model="content" rows="4" required placeholder="Tuliskan pesan detail pengumuman sistem di sini..." 
                                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3 leading-relaxed"></textarea>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-950 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                                <i class="fa-solid fa-bell text-blue-500"></i>
                                <span>Notifikasi in-app akan langsung dikirimkan ke target pengguna.</span>
                            </span>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>Publikasikan & Siarkan</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Live Banner Preview & Scope Card (5 cols) -->
                <div class="lg:col-span-5 space-y-6">

                    <!-- Live Banner Simulation Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 overflow-hidden sticky top-6">
                        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
                            <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-eye text-blue-600 dark:text-blue-400"></i>
                                <span>Simulasi Tampilan Banner Pengguna</span>
                            </h4>
                            <span class="text-[10px] font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/50 px-2 py-0.5 rounded border border-blue-200 dark:border-blue-800">
                                Real-Time Preview
                            </span>
                        </div>

                        <div class="p-5 space-y-4">
                            <!-- Live Banner Component -->
                            <div class="rounded-xl p-4 border transition-all space-y-1.5 shadow-2xs"
                                :class="{
                                    'bg-blue-50/90 dark:bg-blue-950/50 border-blue-200 dark:border-blue-800/80 text-blue-900 dark:text-blue-200': type === 'info',
                                    'bg-amber-50/90 dark:bg-amber-950/50 border-amber-200 dark:border-amber-800/80 text-amber-900 dark:text-amber-200': type === 'warning',
                                    'bg-emerald-50/90 dark:bg-emerald-950/50 border-emerald-200 dark:border-emerald-800/80 text-emerald-900 dark:text-emerald-200': type === 'success',
                                    'bg-rose-50/90 dark:bg-rose-950/50 border-rose-200 dark:border-rose-800/80 text-rose-900 dark:text-rose-200': type === 'danger'
                                }">
                                <div class="flex items-start gap-2.5">
                                    <div class="mt-0.5 shrink-0 text-sm"
                                        :class="{
                                            'text-blue-600 dark:text-blue-400': type === 'info',
                                            'text-amber-600 dark:text-amber-400': type === 'warning',
                                            'text-emerald-600 dark:text-emerald-400': type === 'success',
                                            'text-rose-600 dark:text-rose-400': type === 'danger'
                                        }">
                                        <template x-if="type === 'info'"><i class="fa-solid fa-circle-info"></i></template>
                                        <template x-if="type === 'warning'"><i class="fa-solid fa-triangle-exclamation"></i></template>
                                        <template x-if="type === 'success'"><i class="fa-solid fa-circle-check"></i></template>
                                        <template x-if="type === 'danger'"><i class="fa-solid fa-circle-exclamation"></i></template>
                                    </div>
                                    <div class="flex-1 space-y-1 min-w-0">
                                        <div class="font-bold text-xs leading-snug break-words" x-text="title || 'Judul Pengumuman Sistem Belum Diisi'"></div>
                                        <p class="text-[11px] opacity-90 leading-relaxed break-words" x-text="content || 'Isi materi pengumuman akan ditampilkan di sini sebagaimana dilihat oleh pengguna target...'"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Scope & Target Badge -->
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 space-y-2 text-xs">
                                <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                                    <span class="font-medium">Target Jangkauan:</span>
                                    <span class="font-semibold text-slate-800 dark:text-slate-200 uppercase font-mono text-[11px]" x-text="targetRole === 'all' ? 'Seluruh Pengguna' : targetRole"></span>
                                </div>
                                <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                                    <span class="font-medium">Prioritas Banner:</span>
                                    <span class="font-semibold uppercase font-mono text-[11px]" 
                                        :class="{
                                            'text-blue-600 dark:text-blue-400': type === 'info',
                                            'text-amber-600 dark:text-amber-400': type === 'warning',
                                            'text-emerald-600 dark:text-emerald-400': type === 'success',
                                            'text-rose-600 dark:text-rose-400': type === 'danger'
                                        }"
                                        x-text="type"></span>
                                </div>
                            </div>

                            <p class="text-[11px] text-slate-400 dark:text-slate-500 leading-relaxed">
                                Banner di atas akan dipasang di bagian atas halaman utama dashboard pengguna yang ditargetkan hingga dinonaktifkan atau melewati tanggal kedaluwarsa.
                            </p>
                        </div>
                    </div>

                </div>

            </div>

            <!-- 3. RIWAYAT PENGUMUMAN TABLE (BOTTOM) -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 overflow-hidden shadow-2xs">
                
                <!-- Table Header & Filter Bar -->
                <div class="p-5 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-blue-600 dark:text-blue-400"></i>
                            <span>Riwayat Siaran Pengumuman</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total {{ $announcements->total() }} pengumuman tersimpan dalam sistem.</p>
                    </div>

                    <!-- Search Filter -->
                    <form method="GET" action="{{ route('admin.announcements.index') }}" class="flex items-center gap-2 flex-wrap">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul/isi siaran..." 
                                class="pl-8 pr-3 py-1.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-blue-600 focus:border-blue-600">
                        </div>

                        <select name="type" onchange="this.form.submit()" class="bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 py-1.5 px-2.5 focus:ring-1 focus:ring-blue-600">
                            <option value="">Semua Tipe</option>
                            <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info</option>
                            <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>Success</option>
                            <option value="danger" {{ request('type') == 'danger' ? 'selected' : '' }}>Danger</option>
                        </select>

                        <select name="status" onchange="this.form.submit()" class="bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 py-1.5 px-2.5 focus:ring-1 focus:ring-blue-600">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Tayang Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-700/80 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-4 pl-6">Tgl Publikasi</th>
                                <th class="py-3 px-4">Judul & Isi Pengumuman</th>
                                <th class="py-3 px-4">Target Audiens</th>
                                <th class="py-3 px-4">Tipe Alert</th>
                                <th class="py-3 px-4">Status Tayang</th>
                                <th class="py-3 px-4 pr-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs text-slate-700 dark:text-slate-300">
                            @forelse($announcements as $ann)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/30 transition">
                                    <!-- Date & Author -->
                                    <td class="py-3.5 px-4 pl-6 whitespace-nowrap">
                                        <div class="font-bold text-slate-900 dark:text-white text-xs font-mono">
                                            {{ $ann->created_at->format('d M Y, H:i') }} WIB
                                        </div>
                                        <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 flex items-center gap-1.5">
                                            <span>Oleh: {{ $ann->creator->name ?? 'Super Admin' }}</span>
                                        </div>
                                    </td>

                                    <!-- Title & Snippet -->
                                    <td class="py-3.5 px-4 max-w-md">
                                        <div class="font-semibold text-slate-900 dark:text-white text-xs">
                                            {{ $ann->title }}
                                        </div>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2 leading-relaxed">
                                            {{ strip_tags($ann->content) }}
                                        </p>
                                    </td>

                                    <!-- Target -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="px-2 py-0.5 text-[10px] font-mono font-semibold rounded-md border bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-800 uppercase">
                                            {{ $ann->target_role === 'all' ? '🌐 Seluruh Pengguna' : $ann->target_role }}
                                        </span>
                                    </td>

                                    <!-- Alert Type -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="px-2 py-0.5 text-[10px] font-mono font-semibold rounded-md border uppercase inline-flex items-center gap-1
                                            {{ $ann->type === 'danger' ? 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border-rose-200 dark:border-rose-800' : '' }}
                                            {{ $ann->type === 'warning' ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800' : '' }}
                                            {{ $ann->type === 'success' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : '' }}
                                            {{ $ann->type === 'info' ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800' : '' }}">
                                            @if($ann->type === 'danger')<i class="fa-solid fa-circle-exclamation text-[9px]"></i>@endif
                                            @if($ann->type === 'warning')<i class="fa-solid fa-triangle-exclamation text-[9px]"></i>@endif
                                            @if($ann->type === 'success')<i class="fa-solid fa-circle-check text-[9px]"></i>@endif
                                            @if($ann->type === 'info')<i class="fa-solid fa-circle-info text-[9px]"></i>@endif
                                            <span>{{ $ann->type }}</span>
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($ann->is_active)
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Tayang Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-3.5 px-4 pr-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <form action="{{ route('admin.announcements.toggle', $ann) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-medium border transition {{ $ann->is_active ? 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 border-slate-200 dark:border-slate-700' : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 border-emerald-200 dark:border-emerald-800' }}">
                                                    {{ $ann->is_active ? 'Sembunyikan' : 'Tayangkan' }}
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.announcements.destroy', $ann) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 text-rose-600 dark:text-rose-400 text-xs rounded-lg border border-rose-200 dark:border-rose-800 transition">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs font-medium">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-900 text-slate-400 dark:text-slate-600 flex items-center justify-center text-xl mx-auto mb-3">
                                            <i class="fa-solid fa-bullhorn"></i>
                                        </div>
                                        <div class="text-sm font-semibold text-slate-700 dark:text-slate-300">Belum ada pengumuman global yang disiarkan</div>
                                        <p class="text-slate-400 mt-1">Gunakan formulir di atas untuk mempublikasikan pengumuman baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($announcements->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-950/60">
                        {{ $announcements->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Alpine.js Live Preview Manager -->
    <script>
        function announcementManager(initial) {
            return {
                title: initial.title || '',
                content: initial.content || '',
                type: initial.type || 'info',
                targetRole: initial.targetRole || 'all'
            };
        }
    </script>
</x-app-layout>
