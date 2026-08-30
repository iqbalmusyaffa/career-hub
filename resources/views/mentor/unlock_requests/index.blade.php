<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/20 text-xl font-black shrink-0">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <div>
                    <h2 class="font-black text-xl text-slate-900 dark:text-white leading-tight">
                        Pengajuan Buka Kunci Presensi Magang
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Daftar pengajuan dispensasi tanggal terlewat yang dikirim ke Super Admin.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('mentor.unlock-requests.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-md shadow-blue-600/30 transition flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle"></i> Buat Pengajuan Buka Kunci Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Policy Warning Banner -->
            <div class="p-5 bg-gradient-to-r from-blue-900 via-indigo-950 to-slate-900 text-white rounded-3xl border border-indigo-700/80 shadow-xl flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-xl shrink-0">
                    ℹ️
                </div>
                <div class="space-y-1">
                    <h4 class="text-xs font-black uppercase tracking-wider">Ketentuan Dispensasi Buka Kunci Presensi</h4>
                    <p class="text-3xs text-slate-300 leading-relaxed font-medium">
                        Pengajuan dispensasi tanggal terlewat hanya berlaku untuk kendala teknis resmi penyelenggara/mitra atau <em>force majeure</em>. Alasan kelalaian/lupa absen dari peserta magang akan otomatis ditolak oleh Super Admin.
                    </p>
                </div>
            </div>

            <!-- Table of Mentor's Requests -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-700 text-3xs font-black uppercase text-slate-400 tracking-wider">
                                <th class="py-4 px-6">Tanggal Terkunci</th>
                                <th class="py-4 px-6">Anak Magang</th>
                                <th class="py-4 px-6">Kategori Kendala</th>
                                <th class="py-4 px-6">Status Super Admin</th>
                                <th class="py-4 px-6">Catatan Keputusan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs">
                            @forelse($requests as $req)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-900/40 transition">
                                <td class="py-4 px-6">
                                    <div class="font-extrabold text-slate-900 dark:text-white">
                                        {{ $req->target_date->isoFormat('D MMMM YYYY') }}
                                    </div>
                                    <span class="text-3xs text-slate-400 font-mono">Tiket #ULK-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-800 dark:text-slate-200">
                                    {{ $req->intern->name }}
                                </td>
                                <td class="py-4 px-6">
                                    @if($req->category === 'platform_outage')
                                        <span class="px-2.5 py-1 rounded-lg text-3xs font-extrabold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950 dark:text-blue-300">
                                            🌐 Kendala Platform
                                        </span>
                                    @elseif($req->category === 'partner_issue')
                                        <span class="px-2.5 py-1 rounded-lg text-3xs font-extrabold bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950 dark:text-purple-300">
                                            🏢 Kendala Mitra Kantor
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-3xs font-extrabold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950 dark:text-rose-300">
                                            🚨 Force Majeure
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if($req->status === 'approved')
                                        <span class="px-3 py-1 rounded-full text-3xs font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-300 dark:bg-emerald-950 dark:text-emerald-300 flex items-center gap-1 w-fit">
                                            <i class="fa-solid fa-circle-check"></i> Dibuka (Unlocked)
                                        </span>
                                        @if($req->unlocked_until)
                                            <span class="text-[10px] text-slate-400 block mt-0.5">Aktif s/d: {{ $req->unlocked_until->format('d/m H:i') }} WIB</span>
                                        @endif
                                    @elseif($req->status === 'rejected')
                                        <span class="px-3 py-1 rounded-full text-3xs font-black uppercase tracking-wider bg-red-50 text-red-700 border border-red-300 dark:bg-red-950 dark:text-red-300 flex items-center gap-1 w-fit">
                                            <i class="fa-solid fa-circle-xmark"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-3xs font-black uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-300 dark:bg-amber-950 dark:text-amber-300 flex items-center gap-1 w-fit">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> Menunggu Super Admin
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-slate-600 dark:text-slate-300 font-medium">
                                    {{ $req->admin_notes ?? 'Menunggu tinjauan Super Admin' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <div class="text-3xl mb-2">📭</div>
                                    <p class="font-bold text-xs">Anda belum pernah mengajukan tiket dispensasi buka kunci tanggal.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($requests->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $requests->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
