<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-purple-500/20 text-xl font-black shrink-0">
                    <i class="fa-solid fa-key"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 rounded-md text-3xs font-extrabold uppercase tracking-wider border border-purple-200 dark:border-purple-900">
                            Super Admin Control Desk
                        </span>
                    </div>
                    <h2 class="font-black text-xl text-slate-900 dark:text-white leading-tight mt-0.5">
                        Pusat Tiket Buka Kunci Presensi Magang
                    </h2>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.system-flow') }}" class="px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-extrabold transition shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-diagram-project text-blue-500"></i> Diagram Flow Sistem
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Summary Counter Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'pending']) }}" class="p-5 bg-white dark:bg-slate-800 rounded-3xl border {{ $status === 'pending' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-slate-200 dark:border-slate-700' }} shadow-xs flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <span class="text-3xs font-black uppercase tracking-wider text-amber-500">Menunggu Review</span>
                        <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $pendingCount }} Tiket</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 flex items-center justify-center text-xl font-bold">
                        ⏳
                    </div>
                </a>

                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'approved']) }}" class="p-5 bg-white dark:bg-slate-800 rounded-3xl border {{ $status === 'approved' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-700' }} shadow-xs flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <span class="text-3xs font-black uppercase tracking-wider text-emerald-500">Disetujui (Unlocked)</span>
                        <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $approvedCount }} Tiket</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center text-xl font-bold">
                        ✔️
                    </div>
                </a>

                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'rejected']) }}" class="p-5 bg-white dark:bg-slate-800 rounded-3xl border {{ $status === 'rejected' ? 'border-red-500 ring-2 ring-red-500/20' : 'border-slate-200 dark:border-slate-700' }} shadow-xs flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <span class="text-3xs font-black uppercase tracking-wider text-red-500">Ditolak</span>
                        <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $rejectedCount }} Tiket</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-red-950/60 text-red-600 flex items-center justify-center text-xl font-bold">
                        ❌
                    </div>
                </a>
            </div>

            <!-- Filter Status Bar -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'all' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">
                    Semua Status
                </a>
                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'pending' ? 'bg-amber-600 text-white shadow-md shadow-amber-600/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">
                    Menunggu Review ({{ $pendingCount }})
                </a>
                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">
                    Disetujui
                </a>
                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'rejected' ? 'bg-red-600 text-white shadow-md shadow-red-600/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">
                    Ditolak
                </a>
            </div>

            <!-- Table of Requests -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-700 text-3xs font-black uppercase text-slate-400 tracking-wider">
                                <th class="py-4 px-6">Tiket & Tanggal Terkunci</th>
                                <th class="py-4 px-6">Anak Magang & Mitra</th>
                                <th class="py-4 px-6">Mentor Pengaju</th>
                                <th class="py-4 px-6">Kategori Kendala</th>
                                <th class="py-4 px-6">Status Keputusan</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs">
                            @forelse($requests as $req)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-900/40 transition">
                                <td class="py-4 px-6">
                                    <div class="font-extrabold text-slate-900 dark:text-white">
                                        {{ $req->target_date->isoFormat('D MMMM YYYY') }}
                                    </div>
                                    <span class="text-3xs text-slate-400 font-mono">Tiket #ULK-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }} • {{ $req->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-800 dark:text-slate-200">{{ $req->intern->name }}</div>
                                    <span class="text-3xs text-slate-400">{{ $req->company->company_name ?? 'Mitra Terdaftar' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-slate-700 dark:text-slate-300">{{ $req->mentor->name }}</div>
                                    <span class="text-3xs text-slate-400">{{ $req->mentor->email }}</span>
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
                                            <i class="fa-solid fa-circle-check"></i> Disetujui
                                        </span>
                                        @if($req->unlocked_until)
                                            <span class="text-[10px] text-slate-400 block mt-0.5">S/d: {{ $req->unlocked_until->format('d/m/y H:i') }}</span>
                                        @endif
                                    @elseif($req->status === 'rejected')
                                        <span class="px-3 py-1 rounded-full text-3xs font-black uppercase tracking-wider bg-red-50 text-red-700 border border-red-300 dark:bg-red-950 dark:text-red-300 flex items-center gap-1 w-fit">
                                            <i class="fa-solid fa-circle-xmark"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-3xs font-black uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-300 dark:bg-amber-950 dark:text-amber-300 flex items-center gap-1 w-fit">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> Menunggu Review
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('admin.internship-unlocks.show', $req->id) }}" class="px-3.5 py-1.5 bg-slate-900 dark:bg-slate-700 hover:bg-slate-800 text-white rounded-xl text-xs font-extrabold transition shadow-xs">
                                        Periksa Tiket →
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <div class="text-3xl mb-2">📭</div>
                                    <p class="font-bold text-xs">Belum ada tiket permohonan buka kunci presensi.</p>
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
