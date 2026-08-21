<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-red-600"></i> Permohonan Pembatalan Penerimaan Kandidat
                </h2>
                <p class="text-xs text-gray-500 mt-1">Daftar aduan resmi dari HR untuk membatalkan penerimaan kandidat yang sudah berstatus Diterima/Hired.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-xl text-sm transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center gap-2 shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-800 text-xs font-bold flex items-center gap-2 shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-red-600 text-base"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Cancellation Tickets Table -->
            <div class="bg-white rounded-3xl shadow-2xs border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-red-600"></i> Berkas Permohonan Pembatalan ({{ $tickets->total() }})
                    </h3>

                    <form method="GET" action="{{ route('admin.cancellation-tickets.index') }}" class="flex items-center gap-2 text-xs">
                        <label class="font-bold text-gray-500 whitespace-nowrap">Baris per Halaman:</label>
                        <select name="per_page" onchange="this.form.submit()" class="border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold py-1.5 px-3">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                        </select>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-2xs font-bold text-gray-400 uppercase tracking-wider">
                                <th class="p-4 pl-6">Tgl Pengajuan</th>
                                <th class="p-4">Kandidat & Lowongan</th>
                                <th class="p-4">HR Pemohon</th>
                                <th class="p-4">Alasan Pembatalan HR</th>
                                <th class="p-4">Status Request</th>
                                <th class="p-4 pr-6 text-right">Aksi Super Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="p-4 pl-6 whitespace-nowrap">
                                        <div class="font-bold text-gray-900">{{ $ticket->created_at->format('d M Y, H:i') }} WIB</div>
                                        <div class="text-2xs text-gray-400 mt-0.5">{{ $ticket->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900 text-sm">{{ $ticket->application->user->name ?? 'Kandidat' }}</div>
                                        <div class="text-2xs text-gray-500 mt-0.5">{{ $ticket->application->job->title ?? '-' }}</div>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900">{{ $ticket->hrUser->name ?? 'HR' }}</div>
                                        <div class="text-2xs text-gray-400">{{ $ticket->hrUser->email ?? '-' }}</div>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-xs text-gray-800 bg-gray-50 p-2.5 rounded-xl border border-gray-200 max-w-xs leading-relaxed">
                                            "{{ $ticket->reason }}"
                                        </p>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        @if($ticket->status === 'approved')
                                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 font-bold rounded-lg border border-emerald-200 text-2xs">✓ DISETUJUI</span>
                                        @elseif($ticket->status === 'rejected')
                                            <span class="px-2.5 py-1 bg-red-50 text-red-800 font-bold rounded-lg border border-red-200 text-2xs">✗ DITOLAK</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-amber-50 text-amber-800 font-bold rounded-lg border border-amber-200 text-2xs animate-pulse">⏳ MENUNGGU DECISION</span>
                                        @endif
                                    </td>
                                    <td class="p-4 pr-6 text-right whitespace-nowrap">
                                        @if($ticket->status === 'pending')
                                            <div class="flex justify-end gap-2">
                                                <form method="POST" action="{{ route('admin.cancellation-tickets.approve', $ticket->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI pembatalan penerimaan kandidat ini?');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-2xs transition shadow-2xs">
                                                        Setujui Pembatalan
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('admin.cancellation-tickets.reject', $ticket->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK pembatalan penerimaan kandidat ini?');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-2xs transition shadow-2xs">
                                                        Tolak Aduan
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-2xs text-gray-400 italic">Ditangani oleh: {{ $ticket->handler->name ?? 'Admin' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-400 text-xs font-medium">
                                        Belum ada permohonan aduan pembatalan penerimaan kandidat dari HR.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
