<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 dark:text-white leading-tight flex items-center gap-3">
                    <span class="p-2 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-2xl">
                        <i class="fa-solid fa-sliders text-xl"></i>
                    </span>
                    Pengaturan Periode & Hak Akses Libur Nasional
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Konfigurasi periode magang dan opsi penolakan (*override*) Hari Libur Nasional / Cuti Bersama Pemerintah.
                </p>
            </div>
            <a href="{{ route('mentor.dashboard') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                ← Kembali ke Dasbor Mentor
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Card 1: Pengaturan Periode Magang Peserta -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                <h3 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-calendar-range text-indigo-500"></i>
                    1. Pengaturan Periode Magang & Target Jam Kerja Peserta
                </h3>

                <form action="{{ route('mentor.settings.periods.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-slate-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-slate-200 dark:border-slate-700">
                    @csrf
                    
                    <div class="md:col-span-3">
                        <label class="block text-2xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Pilih Peserta Magang</label>
                        <select name="user_id" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            @foreach($interns as $intern)
                                <option value="{{ $intern->id }}">{{ $intern->name }} ({{ $intern->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-2xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Nama Periode</label>
                        <input type="text" name="period_name" value="Periode 1" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-2xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="2026-08-10" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-2xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" value="2026-09-09" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-2xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Target Jam Kerja</label>
                        <input type="number" name="target_hours" value="400" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                    </div>

                    <div class="md:col-span-1">
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-indigo-600/30 transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card 2: Master Hari Libur Nasional & Cuti Bersama Government (Super Admin Master) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-700 pb-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-building-flag text-red-500"></i>
                            2. Master Hari Libur Nasional & Cuti Bersama Pemerintah
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Daftar libur nasional diatur secara terpusat oleh <span class="font-bold text-red-600 dark:text-red-400">Super Admin</span>. HR / Mentor berhak menolak libur untuk mewajibkan anak magang tetap masuk kerja.
                        </p>
                    </div>
                    <span class="px-3.5 py-1.5 bg-red-50 text-red-700 rounded-xl text-3xs font-extrabold border border-red-200 shrink-0">
                        🔒 Controlled by Super Admin
                    </span>
                </div>

                <div class="p-4 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-2xl flex items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg shrink-0 mt-0.5"></i>
                    <div class="text-xs text-amber-900 dark:text-amber-200 font-medium leading-relaxed">
                        <strong>Aturan Opsi Penolakan Libur (Override):</strong> Jika perusahaan Anda tetap beroperasi pada Hari Libur Nasional atau Cuti Bersama Pemerintah, klik tombol <strong>"Tolak Libur (Wajib Masuk Kerja)"</strong>. Tanggal tersebut akan otomatis dibuka menjadi hari kerja aktif di kalender anak magang Anda.
                    </div>
                </div>

                <!-- National Holidays Table with HR/Mentor Override Toggle -->
                <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded-2xl">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-900/60 uppercase text-3xs font-extrabold text-slate-500 border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="py-3.5 px-5">Tanggal</th>
                                <th class="py-3.5 px-5">Nama Hari Libur / Cuti Bersama</th>
                                <th class="py-3.5 px-5">Kategori</th>
                                <th class="py-3.5 px-5">Status Bagi Perusahaan Anda</th>
                                <th class="py-3.5 px-5 text-right">Aksi HR / Mentor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 font-medium">
                            @forelse($nationalHolidays as $holiday)
                                @php
                                    $isOverridden = isset($overrides[$holiday->id]);
                                @endphp
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/40 transition">
                                    <td class="py-4 px-5 font-bold text-slate-900 dark:text-white whitespace-nowrap">
                                        {{ $holiday->date->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-5 font-bold text-slate-800 dark:text-slate-200">
                                        {{ $holiday->name }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <span class="px-2.5 py-0.5 rounded-full text-3xs font-extrabold uppercase border {{ $holiday->type === 'national_holiday' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                            {{ str_replace('_', ' ', strtoupper($holiday->type)) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-5">
                                        @if($isOverridden)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-full text-3xs font-extrabold">
                                                💼 Libur Ditolak (Tetap Masuk Kerja)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 text-slate-700 border border-slate-300 rounded-full text-3xs font-bold">
                                                ⏹️ Ikut Libur Pemerintah (Default)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-5 text-right">
                                        <form action="{{ route('mentor.settings.holidays.override', $holiday->id) }}" method="POST" class="inline">
                                            @csrf
                                            @if($isOverridden)
                                                <button type="submit" class="px-3.5 py-1.5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-3xs font-extrabold hover:bg-slate-300 transition">
                                                    🔄 Pulihkan ke Libur Pemerintah
                                                </button>
                                            @else
                                                <button type="submit" class="px-3.5 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-3xs font-extrabold shadow-xs transition">
                                                    ❌ Tolak Libur (Wajib Masuk Kerja)
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                                        Belum ada Master Hari Libur Nasional yang didaftarkan Super Admin.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card 3: Tambah Hari Libur Khusus Internal Perusahaan (Dibuat oleh HR / Mentor) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-700 pb-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-mug-hot text-amber-500"></i>
                            3. Tambah Hari Libur Khusus Internal Perusahaan (Dibuat oleh HR / Mentor)
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Selain libur nasional pemerintah, HR & Mentor dapat menambahkan hari libur khusus internal (misal: *Anniversary Perusahaan*, *Gathering*, atau *Libur Cuti Bersama Perusahaan*).
                        </p>
                    </div>
                    <span class="px-3.5 py-1.5 bg-amber-50 text-amber-700 rounded-xl text-3xs font-extrabold border border-amber-200 shrink-0">
                        ✨ Created by HR / Mentor
                    </span>
                </div>

                <form action="{{ route('mentor.settings.holidays.company.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-slate-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-slate-200 dark:border-slate-700">
                    @csrf
                    
                    <div class="md:col-span-4">
                        <label class="block text-2xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Tanggal Libur Internal</label>
                        <input type="date" name="date" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100" required>
                    </div>

                    <div class="md:col-span-6">
                        <label class="block text-2xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Nama Hari Libur / Keterangan</label>
                        <input type="text" name="name" placeholder="e.g. Libur HUT Perusahaan / Family Gathering" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100" required>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-amber-600/30 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-plus-circle"></i>
                            Tambah Libur
                        </button>
                    </div>
                </form>

                <!-- Company Custom Holidays Table -->
                <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded-2xl">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-900/60 uppercase text-3xs font-extrabold text-slate-500 border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="py-3.5 px-5">Tanggal</th>
                                <th class="py-3.5 px-5">Keterangan Hari Libur Internal</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi Hapus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 font-medium">
                            @forelse($companyHolidays as $cHoliday)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/40 transition">
                                    <td class="py-4 px-5 font-bold text-slate-900 dark:text-white whitespace-nowrap">
                                        {{ $cHoliday->date->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-5 font-bold text-slate-800 dark:text-slate-200">
                                        {{ $cHoliday->name }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <span class="px-2.5 py-0.5 rounded-full text-3xs font-extrabold uppercase border bg-amber-50 text-amber-700 border-amber-200">
                                            Libur Perusahaan
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 text-right">
                                        <form action="{{ route('mentor.settings.holidays.company.delete', $cHoliday->id) }}" method="POST" onsubmit="return confirm('Hapus hari libur internal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-3xs font-extrabold hover:bg-red-100 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400 text-xs">
                                        Belum ada Hari Libur Khusus Internal Perusahaan yang ditambahkan oleh HR / Mentor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
