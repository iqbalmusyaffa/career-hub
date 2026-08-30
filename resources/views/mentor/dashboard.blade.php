<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 dark:text-white leading-tight flex items-center gap-3">
                    <span class="p-2 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-2xl">
                        <i class="fa-solid fa-user-check text-xl"></i>
                    </span>
                    Dasbor Pembimbing & ACC Presensi Magang
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Tinjau laporan harian dan berikan persetujuan (ACC) presensi anak magang di bawah bimbingan Anda.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                @php
                    $sampleIntern = \App\Models\User::role('Candidate')->first();
                @endphp
                @if($sampleIntern)
                    <a href="{{ route('mentor.evaluations.create', $sampleIntern->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-indigo-600/30 transition flex items-center gap-2">
                        <i class="fa-solid fa-award"></i>
                        ⭐ Penilaian Kinerja Magang
                    </a>
                @endif
                <a href="{{ route('mentor.settings.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-extrabold text-xs rounded-xl hover:bg-slate-200 border border-slate-200 dark:border-slate-700 transition flex items-center gap-2">
                    <i class="fa-solid fa-sliders"></i>
                    ⚙️ Pengaturan Periode & Libur
                </a>
                <span class="px-3.5 py-1.5 bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 rounded-xl text-xs font-black border border-indigo-200 dark:border-indigo-800">
                    👑 Role: Mentor Pembimbing
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Summary Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-3xs font-extrabold text-slate-400 uppercase tracking-widest block">Menunggu ACC Mentor</span>
                        <span class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1 block">{{ $pendingLogbooks->count() }} Laporan</span>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center font-bold text-xl shrink-0">
                        🔷
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-3xs font-extrabold text-slate-400 uppercase tracking-widest block">Total Disetujui (ACC)</span>
                        <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1 block">{{ $approvedCount }} Laporan</span>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center font-bold text-xl shrink-0">
                        ✔️
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-3xs font-extrabold text-slate-400 uppercase tracking-widest block">Anak Magang Bimbingan</span>
                        <span class="text-2xl font-black text-slate-900 dark:text-white mt-1 block">{{ $totalInterns }} Orang</span>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center font-bold text-xl shrink-0">
                        👨‍🎓
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-3xs font-extrabold text-slate-400 uppercase tracking-widest block">Total Logbook Diisi</span>
                        <span class="text-2xl font-black text-purple-600 dark:text-purple-400 mt-1 block">{{ $totalLogbooks }} Berkas</span>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center font-bold text-xl shrink-0">
                        📁
                    </div>
                </div>
            </div>

            <!-- Pending Logbooks Table (ACC Required Queue) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-3 h-3 bg-blue-600 rounded-full animate-ping"></span>
                            Antrean Laporan Harian Menunggu ACC
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Tinjau uraian kegiatan, pembelajaran, dan lokasi GPS anak magang sebelum menyetujui.
                        </p>
                    </div>

                    <a href="{{ route('mentor.logbooks.index') }}" class="text-xs font-extrabold text-indigo-600 dark:text-indigo-400 hover:underline">
                        Lihat Semua Riwayat Presensi →
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-extrabold uppercase text-3xs tracking-wider">
                                <th class="py-3.5 px-6">Tanggal</th>
                                <th class="py-3.5 px-6">Anak Magang</th>
                                <th class="py-3.5 px-6">Lokasi GPS</th>
                                <th class="py-3.5 px-6">Uraian Aktivitas</th>
                                <th class="py-3.5 px-6 text-center">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi ACC</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 font-medium">
                            @forelse($pendingLogbooks as $logbook)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/40 transition">
                                    <td class="py-4 px-6 font-bold text-slate-900 dark:text-white whitespace-nowrap">
                                        {{ $logbook->date->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-extrabold text-slate-900 dark:text-white">{{ $logbook->intern->name }}</div>
                                        <div class="text-3xs text-slate-500">{{ $logbook->intern->email }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($logbook->latitude)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-3xs font-bold">
                                                📍 GPS Terkunci
                                            </span>
                                        @else
                                            <span class="text-3xs text-slate-400">Lokasi Manual</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 max-w-xs">
                                        <p class="text-slate-700 dark:text-slate-300 line-clamp-2 leading-relaxed">
                                            {{ $logbook->activities }}
                                        </p>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 border border-blue-300 rounded-full text-3xs font-black uppercase">
                                            🔷 Menunggu ACC
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('mentor.logbooks.show', $logbook->id) }}" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-3xs font-bold hover:bg-slate-200 transition">
                                                Detail
                                            </a>
                                            <form action="{{ route('mentor.logbooks.approve', $logbook->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-3xs font-black shadow-xs transition">
                                                    ✔️ ACC Setujui
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400">
                                        <div class="text-2xl mb-2">🎉</div>
                                        <p class="font-bold text-xs">Semua laporan presensi anak magang telah di-ACC!</p>
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
