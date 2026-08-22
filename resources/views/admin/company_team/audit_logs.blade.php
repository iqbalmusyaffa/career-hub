<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-xl text-slate-800 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-blue-600"></i> Log Aktivitas Tim HR Perusahaan
                </h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Pantau seluruh aksi, perubahan status pelamar, dan pembuatan lowongan oleh anggota tim HR internal.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Log Aktivitas -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs">
                <form action="{{ route('admin.company-team.audit-logs') }}" method="GET" class="flex items-center gap-4">
                    <div class="flex-1">
                        <input type="text" name="action" value="{{ request('action') }}" placeholder="Cari aksi (misal: UPDATE_STATUS, ISSUE_OFFER, CREATE_JOB)..." class="w-full border-slate-300 rounded-xl text-xs font-bold p-3 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl shadow-md transition">
                        Filter Log
                    </button>
                </form>
            </div>

            <!-- Tabel Log Aktivitas -->
            <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-3xs font-extrabold uppercase text-slate-500 tracking-wider">
                                <th class="p-4">Waktu Aktivitas</th>
                                <th class="p-4">Anggota Tim HR</th>
                                <th class="p-4">Tipe Aksi</th>
                                <th class="p-4">Alamat IP</th>
                                <th class="p-4">Detail Perubahan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($logs as $log)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-4 whitespace-nowrap font-bold text-slate-900">
                                        {{ $log->created_at->format('d M Y, H:i') }} WIB
                                        <div class="text-3xs text-slate-400 font-medium">{{ $log->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="font-extrabold text-slate-900">{{ $log->user->name ?? 'Tim HR' }}</div>
                                        <div class="text-3xs text-slate-400 font-medium">{{ $log->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-800 text-3xs font-black rounded-lg uppercase border border-blue-200">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap font-mono text-xs text-slate-500">
                                        {{ $log->ip_address ?? '127.0.0.1' }}
                                    </td>
                                    <td class="p-4">
                                        <div class="text-3xs text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-200 max-w-xl truncate font-medium">
                                            {{ is_array($log->details) ? json_encode($log->details) : ($log->details ?? 'Aktivitas terekam') }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-400 font-medium">
                                        Belum ada catatan log aktivitas tim HR.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
