<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- PAGE HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500 mb-1">
                    <span>Pengaturan Perusahaan</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <a href="{{ route('admin.company-team.index') }}" class="hover:text-blue-600">Tim HR</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <span class="text-blue-600 dark:text-blue-400">Log Aktivitas</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                    <i class="fa-solid fa-clock-rotate-left text-blue-600 dark:text-blue-400"></i>
                    Log Aktivitas Tim HR Perusahaan
                </h1>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Pantau riwayat aksi, mutasi status pelamar, pembuatan lowongan, dan keputusan oleh staf rekrutmen internal.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.company-team.index') }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-semibold transition flex items-center gap-2 shadow-xs">
                    <i class="fa-solid fa-users text-slate-400"></i> Anggota Tim HR
                </a>
            </div>
        </div>

        <!-- FILTER TOOLBAR -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
            <form action="{{ route('admin.company-team.audit-logs') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="action" value="{{ request('action') }}" placeholder="Cari nama aksi (misal: UPDATE_STATUS, ISSUE_OFFER, CREATE_JOB)..." 
                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl pl-9 pr-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
                </div>
                <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl transition shadow-xs flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter text-xs"></i> Filter Log
                </button>
            </form>
        </div>

        <!-- LOG TABLE -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4 pl-6">Waktu Aktivitas</th>
                            <th class="py-3 px-4">Anggota Tim HR</th>
                            <th class="py-3 px-4">Tipe Aksi</th>
                            <th class="py-3 px-4">Alamat IP</th>
                            <th class="py-3 px-4 pr-6">Detail Perubahan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs text-slate-700 dark:text-slate-300">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-4 pl-6 whitespace-nowrap">
                                    <div class="font-bold text-slate-900 dark:text-white text-xs">{{ $log->created_at->format('d M Y, H:i') }} WIB</div>
                                    <div class="text-[11px] text-slate-400">{{ $log->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900 dark:text-white text-xs">{{ $log->user->name ?? 'Tim HR' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $log->user->email ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-[11px] font-mono font-semibold rounded-md border border-blue-200 dark:border-blue-800/60">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap font-mono text-[11px] text-slate-500 dark:text-slate-400">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                                <td class="py-3.5 px-4 pr-6">
                                    <div class="text-[11px] text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-950 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800 max-w-xl truncate font-mono">
                                        {{ is_array($log->details) ? json_encode($log->details) : ($log->details ?? 'Aktivitas terekam') }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs font-medium">
                                    <i class="fa-solid fa-timeline text-3xl mb-2 text-slate-300 dark:text-slate-700 block"></i>
                                    Belum ada catatan log aktivitas tim HR.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($logs, 'hasPages') && $logs->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
