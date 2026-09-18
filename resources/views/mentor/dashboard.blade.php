<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-[11px] font-semibold rounded-md border border-blue-200/80 dark:border-blue-900">
                        Mentor Pembimbing
                    </span>
                    <span class="text-xs text-slate-400 dark:text-slate-500 font-normal">| Program Magang & Prakerin</span>
                </div>
                <h2 class="font-bold text-xl sm:text-2xl text-slate-900 dark:text-white tracking-tight">
                    Dashboard Pembimbing & Presensi Magang
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                    Tinjau laporan aktivitas harian, verifikasi lokasi GPS presensi, dan kelola evaluasi performa peserta magang.
                </p>
            </div>
            
            <div class="flex items-center gap-2 flex-wrap">
                @php
                    $sampleIntern = \App\Models\User::role('Candidate')->first();
                @endphp
                @if($sampleIntern)
                    <a href="{{ route('mentor.evaluations.create', $sampleIntern->id) }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center gap-2">
                        <i class="fa-solid fa-award text-xs"></i>
                        <span>Penilaian Kinerja Magang</span>
                    </a>
                @endif
                <a href="{{ route('mentor.settings.index') }}" class="px-3.5 py-2 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold text-xs rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs transition flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-slate-400 text-xs"></i>
                    <span>Pengaturan Periode & Libur</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl text-xs font-medium flex items-center gap-3 shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Batch Filter Header Bar -->
            <div class="bg-white dark:bg-slate-900 p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm shrink-0 border border-indigo-200/80 dark:border-indigo-900/60">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                            Filter Berdasarkan Batch Angkatan
                        </h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">
                            Menampilkan statistik kehadiran dan laporan harian per angkatan.
                        </p>
                    </div>
                </div>

                <form method="GET" action="{{ route('mentor.dashboard') }}" class="flex items-center gap-2">
                    <select name="batch" onchange="this.form.submit()" class="text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 py-2 px-3">
                        <option value="">-- Semua Batch Angkatan Magang --</option>
                        @foreach($batches as $batchName)
                            <option value="{{ $batchName }}" {{ $selectedBatch === $batchName ? 'selected' : '' }}>
                                {{ $batchName }}
                            </option>
                        @endforeach
                    </select>
                    @if($selectedBatch)
                        <a href="{{ route('mentor.dashboard') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left text-xs"></i>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Summary Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- 1. Pending Approvals -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Menunggu ACC</span>
                        <div class="w-10 h-10 bg-amber-50/60 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center text-xs border border-amber-200/60 dark:border-amber-900/60">
                            <i class="fa-solid fa-clock text-base"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 tracking-tight">{{ $pendingLogbooks->count() }}</div>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 font-normal">Laporan harian perlu diperiksa</p>
                    </div>
                </div>

                <!-- 2. Approved Logbooks -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Disetujui</span>
                        <div class="w-10 h-10 bg-emerald-50/60 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center text-xs border border-emerald-200/60 dark:border-emerald-900/60">
                            <i class="fa-solid fa-circle-check text-base"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 tracking-tight">{{ $approvedCount }}</div>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 font-normal">Logbook terverifikasi mentor</p>
                    </div>
                </div>

                <!-- 3. Total Interns -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Peserta Magang</span>
                        <div class="w-10 h-10 bg-blue-50/60 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-xs border border-blue-200/60 dark:border-blue-900/60">
                            <i class="fa-solid fa-user-graduate text-base"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $totalInterns }}</div>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 font-normal">Talenta dalam bimbingan</p>
                    </div>
                </div>

                <!-- 4. Total Logbooks -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Logbook</span>
                        <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl flex items-center justify-center text-xs border border-slate-200 dark:border-slate-700">
                            <i class="fa-solid fa-folder-open text-base"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $totalLogbooks }}</div>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 font-normal">Riwayat presensi tersimpan</p>
                    </div>
                </div>
            </div>

            <!-- Pending Logbooks Table (ACC Required Queue) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 overflow-hidden space-y-0">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-blue-600 dark:text-blue-400 text-xs"></i> Antrean Laporan Harian Menunggu Persetujuan (ACC)
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                            Tinjau uraian kegiatan, pembelajaran, serta titik koordinat GPS presensi sebelum memberikan persetujuan.
                        </p>
                    </div>

                    <a href="{{ route('mentor.logbooks.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                        Lihat Semua Riwayat Presensi &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-semibold uppercase text-[11px] tracking-wider">
                                <th class="py-3.5 px-6">Tanggal</th>
                                <th class="py-3.5 px-6">Peserta Magang</th>
                                <th class="py-3.5 px-6">Lokasi Presensi</th>
                                <th class="py-3.5 px-6">Uraian Kegiatan</th>
                                <th class="py-3.5 px-6 text-center">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-normal">
                            @forelse($pendingLogbooks as $logbook)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                    <td class="py-4 px-6 font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                                        {{ $logbook->date->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $logbook->intern->name }}</div>
                                        <div class="text-[11px] text-slate-400 dark:text-slate-500 font-normal">{{ $logbook->intern->email }}</div>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @if($logbook->latitude)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800 rounded-md text-[11px] font-medium">
                                                <i class="fa-solid fa-location-crosshairs text-[10px]"></i> GPS Terverifikasi
                                            </span>
                                        @else
                                            <span class="text-[11px] text-slate-400 dark:text-slate-500 font-normal">Input Manual</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 max-w-xs">
                                        <p class="text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed font-normal">
                                            {{ $logbook->activities }}
                                        </p>
                                    </td>
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800 rounded-md text-[11px] font-semibold">
                                            Menunggu ACC
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('mentor.logbooks.show', $logbook->id) }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-2xs">
                                                Detail
                                            </a>
                                            <form action="{{ route('mentor.logbooks.approve', $logbook->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-xs transition flex items-center gap-1">
                                                    <i class="fa-solid fa-check text-[10px]"></i> Setujui
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500 bg-slate-50/50 dark:bg-slate-950/40">
                                        <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-2xl mb-2 block"></i>
                                        <p class="font-bold text-slate-800 dark:text-slate-200 text-xs">Semua Laporan Telah Disetujui</p>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Tidak ada antrean presensi atau logbook yang menunggu ACC saat ini.</p>
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
