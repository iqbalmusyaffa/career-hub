<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-lg font-bold shrink-0">
                    <i class="fa-solid fa-user-xmark"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-0.5">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Admin</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">Rekrutmen & Tim</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Permohonan Pembatalan Penerimaan Kandidat
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        Daftar aduan resmi dari HR untuk membatalkan penerimaan kandidat yang sudah berstatus Diterima (Hired).
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-2xs">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/80 rounded-2xl text-emerald-800 dark:text-emerald-300 text-xs font-medium flex items-center gap-2.5 shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800/80 rounded-2xl text-rose-800 dark:text-rose-300 text-xs font-medium flex items-center gap-2.5 shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 dark:text-rose-400 text-sm shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Cancellation Tickets Table -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span>Berkas Permohonan Pembatalan</span>
                            <span class="px-2 py-0.5 rounded-full text-2xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200/80 dark:border-rose-900">
                                {{ $tickets->total() }} Aduan
                            </span>
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Tinjau alasan HR dan tentukan keputusan pembatalan status penerimaan kandidat.
                        </p>
                    </div>

                    <form method="GET" action="{{ route('admin.cancellation-tickets.index') }}" class="flex items-center gap-2 text-xs">
                        <label class="font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">Baris per Halaman:</label>
                        <select name="per_page" onchange="this.form.submit()" class="border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-slate-100 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium py-1.5 px-3">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25 Baris</option>
                            <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50 Baris</option>
                            <option value="100" {{ request('per_page', 100) == 100 ? 'selected' : '' }}>100 Baris</option>
                        </select>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse bg-white dark:bg-slate-900">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/80 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold uppercase text-slate-500 dark:text-slate-400 tracking-wider">
                                <th class="py-3.5 px-5">Tgl Pengajuan</th>
                                <th class="py-3.5 px-5">Kandidat & Lowongan</th>
                                <th class="py-3.5 px-5">HR Pemohon</th>
                                <th class="py-3.5 px-5">Alasan Pembatalan HR</th>
                                <th class="py-3.5 px-5">Status Request</th>
                                <th class="py-3.5 px-5 text-right">Aksi Super Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs bg-white dark:bg-slate-900">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/50 transition">
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <div class="font-semibold text-slate-900 dark:text-white">{{ $ticket->created_at->format('d M Y, H:i') }} WIB</div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">{{ $ticket->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="py-4 px-5">
                                        <div class="font-semibold text-slate-900 dark:text-white">{{ $ticket->application->user->name ?? 'Kandidat' }}</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $ticket->application->job->title ?? '-' }}</div>
                                        <a href="{{ route('admin.applications.show', $ticket->application_id) }}" target="_blank" class="text-[11px] font-medium text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center gap-1 mt-1">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                            <span>Lihat Rekam Seleksi</span>
                                        </a>
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <div class="font-semibold text-slate-900 dark:text-white">{{ $ticket->hrUser->name ?? 'HR' }}</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $ticket->hrUser->email ?? '-' }}</div>
                                    </td>
                                    <td class="py-4 px-5 max-w-xs">
                                        <div class="text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-950/60 p-3 rounded-xl border border-slate-200/70 dark:border-slate-800 leading-relaxed font-normal">
                                            "{{ $ticket->reason }}"
                                        </div>
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        @if($ticket->status === 'approved')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200/80 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-900 font-semibold rounded-full text-[11px]">
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                                <span>Disetujui</span>
                                            </span>
                                        @elseif($ticket->status === 'rejected')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200/80 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900 font-semibold rounded-full text-[11px]">
                                                <i class="fa-solid fa-xmark text-[10px]"></i>
                                                <span>Ditolak</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200/80 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900 font-semibold rounded-full text-[11px]">
                                                <i class="fa-regular fa-clock text-[10px]"></i>
                                                <span>Menunggu Review</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        @if($ticket->status === 'pending')
                                            <div class="flex justify-end items-center gap-2">
                                                <form method="POST" action="{{ route('admin.cancellation-tickets.reject', $ticket->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK pembatalan penerimaan kandidat ini?');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-white dark:bg-slate-900 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-slate-200 dark:border-slate-800 hover:border-rose-200 dark:hover:border-rose-900 rounded-lg text-xs font-semibold transition shadow-2xs">
                                                        Tolak Aduan
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('admin.cancellation-tickets.approve', $ticket->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI pembatalan penerimaan kandidat ini?');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg text-xs transition shadow-2xs">
                                                        Setujui Pembatalan
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-[11px] text-slate-400 dark:text-slate-500">Ditangani: {{ $ticket->handler->name ?? 'Super Admin' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                        <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-xl mb-3">
                                            <i class="fa-solid fa-clipboard-check"></i>
                                        </div>
                                        <p class="font-medium text-xs text-slate-700 dark:text-slate-300">Belum ada permohonan aduan pembatalan penerimaan kandidat dari HR.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                    <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
