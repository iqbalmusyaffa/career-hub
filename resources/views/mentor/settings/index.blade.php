<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-slate-900 dark:text-white tracking-tight">
                    Pengaturan Batch & Hari Libur Perusahaan
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                    Konfigurasi Batch / Periode magang peserta, libur internal perusahaan, dan pengajuan operasional Cuti Bersama ke Super Admin.
                </p>
            </div>
            <a href="{{ route('mentor.logbooks.index') }}" class="px-4 py-2 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 shadow-xs transition flex items-center gap-1.5 self-start sm:self-auto">
                <i class="fa-solid fa-arrow-left text-xs text-slate-400"></i>
                <span>Kembali ke Presensi Magang</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Card 1: Pengaturan Batch & Periode Magang Peserta -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-7 space-y-5">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-blue-600 dark:text-blue-400 text-xs"></i>
                    1. Pengaturan Batch / Periode Magang & Target Jam Kerja Peserta
                </h3>

                <form action="{{ route('mentor.settings.periods.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-slate-50 dark:bg-slate-950/60 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800">
                    @csrf
                    
                    <div class="md:col-span-3">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Pilih Peserta Magang</label>
                        <select name="user_id" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            @foreach($interns as $intern)
                                <option value="{{ $intern->id }}">{{ $intern->name }} ({{ $intern->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Nama Periode</label>
                        <input type="text" name="period_name" value="Periode 1" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="2026-08-10" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" value="2026-09-09" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Target Jam Kerja</label>
                        <input type="number" name="target_hours" value="400" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                    </div>

                    <div class="md:col-span-1">
                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card 2: Master Hari Libur Nasional & Cuti Bersama Government (Super Admin Master) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-7 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-landmark text-blue-600 dark:text-blue-400 text-xs"></i>
                            2. Master Hari Libur Nasional & Cuti Bersama Pemerintah
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-normal">
                            Daftar libur nasional & cuti bersama resmi diatur terpusat oleh <strong class="text-slate-800 dark:text-slate-200">Super Admin</strong>.
                        </p>
                    </div>
                    <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-md text-[11px] font-semibold border border-slate-200 dark:border-slate-700 shrink-0">
                        Controlled by Super Admin
                    </span>
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 rounded-xl space-y-1.5">
                    <div class="flex items-center gap-2 font-bold text-slate-900 dark:text-white text-xs">
                        <i class="fa-solid fa-shield-halved text-blue-600 dark:text-blue-400"></i>
                        <span>Ketentuan Hari Libur Nasional & Cuti Bersama:</span>
                    </div>
                    <ul class="text-xs text-slate-600 dark:text-slate-400 font-normal leading-relaxed list-disc list-inside space-y-1">
                        <li><strong class="text-slate-800 dark:text-slate-200">Hari Libur Nasional:</strong> Bersifat <em class="text-rose-600 dark:text-rose-400 font-semibold not-italic">wajib dan mutlak libur</em> bagi seluruh peserta magang (terkunci otomatis dan tidak dapat diubah).</li>
                        <li><strong class="text-slate-800 dark:text-slate-200">Cuti Bersama Pemerintah:</strong> Jika operasional perusahaan tetap berjalan, Mentor/HR dapat mengirimkan <strong class="text-blue-600 dark:text-blue-400">Pengajuan Izin Masuk ke Super Admin</strong>.</li>
                    </ul>
                </div>

                <!-- National Holidays Table with HR/Mentor Override Toggle -->
                <div class="overflow-x-auto border border-slate-200/80 dark:border-slate-800 rounded-xl">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-950/60 uppercase text-[11px] font-semibold text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="py-3.5 px-5">Tanggal</th>
                                <th class="py-3.5 px-5">Nama Hari Libur / Cuti Bersama</th>
                                <th class="py-3.5 px-5">Kategori</th>
                                <th class="py-3.5 px-5">Status Bagi Perusahaan</th>
                                <th class="py-3.5 px-5 text-right">Aksi HR / Mentor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-normal">
                            @forelse($nationalHolidays as $holiday)
                                @php
                                    $isOverridden = isset($overrides[$holiday->id]);
                                    $isNationalHoliday = ($holiday->type === 'national_holiday');
                                @endphp
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                    <td class="py-4 px-5 font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                                        {{ $holiday->date->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-5 font-bold text-slate-800 dark:text-slate-200">
                                        {{ $holiday->name }}
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        @if($isNationalHoliday)
                                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold uppercase border bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">
                                                Libur Nasional
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold uppercase border bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900">
                                                Cuti Bersama
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        @if($isNationalHoliday)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-900 rounded-md text-[11px] font-semibold">
                                                <i class="fa-solid fa-lock text-xs"></i> Wajib Libur Nasional
                                            </span>
                                        @else
                                            @if($isOverridden)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 rounded-md text-[11px] font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> Diajukan ke Super Admin
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-md text-[11px] font-medium">
                                                    <i class="fa-solid fa-umbrella-beach text-xs"></i> Ikut Libur Pemerintah
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        @if($isNationalHoliday)
                                            <span class="text-slate-400 dark:text-slate-500 text-xs font-medium italic">
                                                Terkunci (Wajib Libur)
                                            </span>
                                        @else
                                            <form action="{{ route('mentor.settings.holidays.override', $holiday->id) }}" method="POST" class="inline">
                                                @csrf
                                                @if($isOverridden)
                                                    <button type="submit" class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-2xs">
                                                        Batalkan Pengajuan
                                                    </button>
                                                @else
                                                    <button type="submit" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-xs transition inline-flex items-center gap-1.5">
                                                        <i class="fa-solid fa-paper-plane text-[10px]"></i>
                                                        <span>Ajukan ke Super Admin</span>
                                                    </button>
                                                @endif
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500 text-xs">
                                        Belum ada Master Hari Libur Nasional yang didaftarkan Super Admin.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card 3: Tambah Hari Libur Khusus Internal Perusahaan (Dibuat oleh HR / Mentor) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-7 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-calendar-plus text-blue-600 dark:text-blue-400 text-xs"></i>
                            3. Tambah Hari Libur Khusus Internal Perusahaan (Dibuat oleh HR / Mentor)
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-normal">
                            Selain libur nasional pemerintah, HR & Mentor dapat menambahkan hari libur khusus internal (misal: <em>Anniversary Perusahaan</em>, <em>Gathering</em>, dsb).
                        </p>
                    </div>
                    <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 rounded-md text-[11px] font-semibold border border-blue-200/80 dark:border-blue-900 shrink-0">
                        Created by HR / Mentor
                    </span>
                </div>

                <form action="{{ route('mentor.settings.holidays.company.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-slate-50 dark:bg-slate-950/60 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800">
                    @csrf
                    
                    <div class="md:col-span-4">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Tanggal Libur Internal</label>
                        <input type="date" name="date" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100" required>
                    </div>

                    <div class="md:col-span-6">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Nama Hari Libur / Keterangan</label>
                        <input type="text" name="name" placeholder="Contoh: Libur HUT Perusahaan / Family Gathering" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100" required>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>Tambah Libur</span>
                        </button>
                    </div>
                </form>

                <!-- Company Custom Holidays Table -->
                <div class="overflow-x-auto border border-slate-200/80 dark:border-slate-800 rounded-xl">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-950/60 uppercase text-[11px] font-semibold text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="py-3.5 px-5">Tanggal</th>
                                <th class="py-3.5 px-5">Keterangan Hari Libur Internal</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi Hapus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-normal">
                            @forelse($companyHolidays as $cHoliday)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                    <td class="py-4 px-5 font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                                        {{ $cHoliday->date->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-5 font-bold text-slate-800 dark:text-slate-200">
                                        {{ $cHoliday->name }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold uppercase border bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900">
                                            Libur Perusahaan
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 text-right">
                                        <form action="{{ route('mentor.settings.holidays.company.delete', $cHoliday->id) }}" method="POST" onsubmit="return confirm('Hapus hari libur internal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900 rounded-lg text-xs font-semibold hover:bg-rose-100 dark:hover:bg-rose-900/60 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400 dark:text-slate-500 text-xs">
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
