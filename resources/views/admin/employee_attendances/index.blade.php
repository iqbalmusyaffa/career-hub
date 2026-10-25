<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                    <i class="fa-solid fa-user-check"></i>
                    <span>HR & Manajemen TalentFlow</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">
                    Monitoring Presensi Karyawan
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Pantau kehadiran karyawan secara real-time, validasi foto selfie wajah, dan koordinat GPS geofence.
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.employee-attendances.export-csv', request()->query()) }}" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-xs hover:shadow-md transition-all flex items-center gap-2">
                    <i class="fa-solid fa-file-csv"></i>
                    <span>Export Rekap CSV</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- KPI METRIC CARDS -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Total Hadir -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-xs">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <span>Total Masuk Hari Ini</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-black text-slate-900 dark:text-white">{{ $metrics['total'] }}</span>
                        <span class="text-xs text-slate-500">Karyawan</span>
                    </div>
                </div>

                <!-- Tepat Waktu -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-xs">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <span>Tepat Waktu</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $metrics['on_time'] }}</span>
                        <span class="text-xs text-slate-500">({{ $metrics['on_time_percentage'] }}%)</span>
                    </div>
                </div>

                <!-- Terlambat -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-xs">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <span>Terlambat (>08:30)</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ $metrics['late'] }}</span>
                        <span class="text-xs text-slate-500">Karyawan</span>
                    </div>
                </div>

                <!-- Belum Checkout -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-xs">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <span>Sedang Bekerja</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-business-time"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ $metrics['not_checked_out'] }}</span>
                        <span class="text-xs text-slate-500">Belum Pulang</span>
                    </div>
                </div>

                <!-- Distribusi Mode Kerja -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-xs col-span-2 lg:col-span-1">
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">
                        Distribusi Mode Kerja
                    </div>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold" title="WFO Kantor">
                            🏢 {{ $metrics['wfo'] }}
                        </span>
                        <span class="px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 font-bold" title="WFH Rumah">
                            🏠 {{ $metrics['wfh'] }}
                        </span>
                        <span class="px-2 py-0.5 rounded-md bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-bold" title="Dinas Luar">
                            🚗 {{ $metrics['dinas'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- FILTER & SEARCH BAR -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-xs">
                <form method="GET" action="{{ route('admin.employee-attendances.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
                    <!-- Date Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal</label>
                        <input type="date" name="date" value="{{ $selectedDate }}" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Branch Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Cabang Kantor</label>
                        <select name="branch_id" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Cabang</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Work Type Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Mode Kerja</label>
                        <select name="work_type" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Mode</option>
                            <option value="wfo" {{ request('work_type') === 'wfo' ? 'selected' : '' }}>🏢 WFO (Kantor)</option>
                            <option value="wfh" {{ request('work_type') === 'wfh' ? 'selected' : '' }}>🏠 WFH (Rumah)</option>
                            <option value="dinas_luar" {{ request('work_type') === 'dinas_luar' ? 'selected' : '' }}>🚗 Dinas Luar</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Status Kehadiran</label>
                        <select name="status" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="on_time" {{ request('status') === 'on_time' ? 'selected' : '' }}>Tepat Waktu</option>
                            <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                            <option value="no_checkout" {{ request('status') === 'no_checkout' ? 'selected' : '' }}>Belum Absen Pulang</option>
                        </select>
                    </div>

                    <!-- Search Input -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Cari Karyawan</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / Email..." class="w-full text-xs pl-8 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                            <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('admin.employee-attendances.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold transition-colors" title="Reset Filter">
                            <i class="fa-solid fa-arrow-rotate-left"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- ATTENDANCE MONITORING TABLE -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">
                            Daftar Presensi Karyawan: {{ \Carbon\Carbon::parse($selectedDate)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Menampilkan {{ $attendances->total() }} catatan presensi terverifikasi.
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 uppercase tracking-wider font-semibold border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-5 py-3.5">Karyawan</th>
                                <th class="px-5 py-3.5">Mode Kerja</th>
                                <th class="px-5 py-3.5">Absen Masuk</th>
                                <th class="px-5 py-3.5">Foto Masuk</th>
                                <th class="px-5 py-3.5">Absen Pulang</th>
                                <th class="px-5 py-3.5">Foto Pulang</th>
                                <th class="px-5 py-3.5">Durasi</th>
                                <th class="px-5 py-3.5">Lokasi & GPS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                            @forelse($attendances as $item)
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                    <!-- Karyawan -->
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 font-bold flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($item->user?->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900 dark:text-white">
                                                    {{ $item->user?->name ?? 'Pengguna Dihapus' }}
                                                </div>
                                                <div class="text-[11px] text-slate-500 dark:text-slate-400">
                                                    {{ $item->user?->email ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Mode Kerja -->
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $item->work_type_badge['class'] }}">
                                            <i class="fa-solid {{ $item->work_type_badge['icon'] }} mr-1"></i>
                                            {{ $item->work_type_badge['short'] }}
                                        </span>
                                    </td>

                                    <!-- Absen Masuk -->
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="font-mono font-bold text-slate-900 dark:text-white">
                                            {{ $item->formatted_check_in_time }}
                                        </div>
                                        <span class="inline-block mt-0.5 text-[10px] font-bold px-1.5 py-0.5 rounded-sm {{ $item->check_in_status_badge['class'] }}">
                                            {{ $item->check_in_status_badge['label'] }}
                                        </span>
                                    </td>

                                    <!-- Foto Masuk -->
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if($item->check_in_selfie_url)
                                            <button type="button" onclick="openPhotoModal('{{ $item->check_in_selfie_url }}', 'Selfie Masuk - {{ $item->user?->name }}')" class="w-10 h-10 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 hover:scale-105 transition-transform shadow-xs">
                                                <img src="{{ $item->check_in_selfie_url }}" class="w-full h-full object-cover" alt="Selfie Masuk">
                                            </button>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">Tidak ada foto</span>
                                        @endif
                                    </td>

                                    <!-- Absen Pulang -->
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if($item->check_out_time)
                                            <div class="font-mono font-bold text-slate-900 dark:text-white">
                                                {{ $item->formatted_check_out_time }}
                                            </div>
                                            <span class="inline-block mt-0.5 text-[10px] font-medium text-emerald-600">
                                                Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-amber-600 dark:text-amber-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Sedang Bekerja
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Foto Pulang -->
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if($item->check_out_selfie_url)
                                            <button type="button" onclick="openPhotoModal('{{ $item->check_out_selfie_url }}', 'Selfie Pulang - {{ $item->user?->name }}')" class="w-10 h-10 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 hover:scale-105 transition-transform shadow-xs">
                                                <img src="{{ $item->check_out_selfie_url }}" class="w-full h-full object-cover" alt="Selfie Pulang">
                                            </button>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>

                                    <!-- Durasi -->
                                    <td class="px-5 py-4 whitespace-nowrap font-semibold">
                                        {{ $item->formatted_work_duration }}
                                    </td>

                                    <!-- Lokasi & GPS -->
                                    <td class="px-5 py-4 max-w-xs">
                                        <div class="truncate text-[11px] text-slate-600 dark:text-slate-300 font-medium" title="{{ $item->check_in_address }}">
                                            {{ $item->check_in_address ?? 'Lokasi Terdaftar' }}
                                        </div>
                                        @if($item->check_in_latitude && $item->check_in_longitude)
                                            <a href="https://www.google.com/maps?q={{ $item->check_in_latitude }},{{ $item->check_in_longitude }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] text-blue-600 dark:text-blue-400 hover:underline mt-0.5">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                <span>Buka Google Maps</span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-12 text-center text-slate-500 dark:text-slate-400">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-2 text-xl">
                                            <i class="fa-regular fa-clipboard"></i>
                                        </div>
                                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Tidak Ada Data Presensi</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Tidak ditemukan catatan presensi karyawan untuk filter tanggal yang dipilih.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($attendances->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $attendances->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- MODAL: PREVIEW HIGH-RES SELFIE -->
    <div id="photoModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-800">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h4 id="photoModalTitle" class="text-sm font-bold text-slate-900 dark:text-white">Foto Selfie Verifikasi</h4>
                <button type="button" onclick="closePhotoModal()" class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-4 bg-slate-950 flex items-center justify-center">
                <img id="photoModalImg" src="" alt="Selfie" class="max-h-[440px] w-auto rounded-lg shadow-md object-contain">
            </div>
            <div class="p-3 bg-slate-50 dark:bg-slate-800/60 text-right">
                <button type="button" onclick="closePhotoModal()" class="px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 rounded-xl border border-slate-200 dark:border-slate-600 hover:bg-slate-100">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openPhotoModal(imgUrl, title) {
            document.getElementById('photoModalImg').src = imgUrl;
            document.getElementById('photoModalTitle').textContent = title;
            document.getElementById('photoModal').classList.remove('hidden');
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>
