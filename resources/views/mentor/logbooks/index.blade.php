<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 dark:text-white leading-tight">
                    Riwayat Laporan & Presensi Anak Magang
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Daftar seluruh presensi dan laporan harian anak magang bimbingan Anda.
                </p>
            </div>
            <a href="{{ route('mentor.dashboard') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                ← Kembali ke Dasbor Mentor
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-extrabold uppercase text-3xs tracking-wider">
                                <th class="py-3.5 px-6">Tanggal</th>
                                <th class="py-3.5 px-6">Anak Magang</th>
                                <th class="py-3.5 px-6">Kehadiran</th>
                                <th class="py-3.5 px-6">Uraian Aktivitas</th>
                                <th class="py-3.5 px-6 text-center">Status ACC</th>
                                <th class="py-3.5 px-6 text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 font-medium">
                            @foreach($logbooks as $logbook)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/40 transition">
                                    <td class="py-4 px-6 font-bold text-slate-900 dark:text-white whitespace-nowrap">
                                        {{ $logbook->date->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-extrabold text-slate-900 dark:text-white">{{ $logbook->intern->name }}</div>
                                        <div class="text-3xs text-slate-500">{{ $logbook->intern->email }}</div>
                                    </td>
                                    <td class="py-4 px-6 font-semibold uppercase text-3xs">
                                        {{ $logbook->attendance_type }}
                                    </td>
                                    <td class="py-4 px-6 max-w-xs">
                                        <p class="text-slate-700 dark:text-slate-300 line-clamp-2 leading-relaxed">
                                            {{ $logbook->activities }}
                                        </p>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="px-3 py-1 rounded-full text-3xs font-black uppercase {{ $logbook->status_badge['class'] }}">
                                            {{ $logbook->status_badge['symbol'] }} {{ $logbook->status_badge['label'] }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ route('mentor.logbooks.show', $logbook->id) }}" class="px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 rounded-lg text-3xs font-bold hover:bg-indigo-100 transition">
                                            Tinjau
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $logbooks->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
