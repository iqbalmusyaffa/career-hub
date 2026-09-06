<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-slate-900 dark:text-white tracking-tight">
                    Pengajuan Buka Kunci Presensi Magang
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                    Daftar pengajuan dispensasi tanggal terlewat yang diajukan ke Super Admin.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('mentor.unlock-requests.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> 
                    <span>Buat Pengajuan Buka Kunci</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Policy Warning Banner -->
            <div class="p-5 bg-blue-50/50 dark:bg-blue-950/30 border border-blue-200/80 dark:border-blue-900/60 rounded-2xl shadow-xs flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm font-bold shrink-0 mt-0.5">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="space-y-0.5">
                    <h4 class="text-xs font-bold text-blue-950 dark:text-blue-200 uppercase tracking-wider">Ketentuan Dispensasi Buka Kunci Presensi</h4>
                    <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed font-normal">
                        Pengajuan dispensasi tanggal terlewat hanya berlaku untuk kendala teknis resmi penyelenggara/mitra atau <em>force majeure</em>. Alasan kelalaian/lupa absen dari peserta magang akan ditolak oleh Super Admin.
                    </p>
                </div>
            </div>

            <!-- Table of Mentor's Requests -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-semibold uppercase text-[11px] tracking-wider">
                                <th class="py-3.5 px-6">Tanggal Terkunci</th>
                                <th class="py-3.5 px-6">Peserta Magang</th>
                                <th class="py-3.5 px-6">Kategori Kendala</th>
                                <th class="py-3.5 px-6">Status Keputusan</th>
                                <th class="py-3.5 px-6">Catatan Admin</th>
                                <th class="py-3.5 px-6 text-right">Surat Resmi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-normal">
                            @forelse($requests as $req)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <div class="font-bold text-slate-900 dark:text-white">
                                        {{ $req->target_date->isoFormat('D MMMM YYYY') }}
                                    </div>
                                    <span class="text-[11px] text-slate-400 font-mono">#ULK-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                                    {{ $req->intern->name }}
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    @if($req->category === 'medical_emergency')
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">
                                            🏥 Sakit / Rawat Medis
                                        </span>
                                    @elseif($req->category === 'academic_urgent')
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-900">
                                            🎓 Akademik (Wisuda/Ijazah)
                                        </span>
                                    @elseif($req->category === 'personal_urgent')
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">
                                            🚨 Mendesak Pribadi/Keluarga
                                        </span>
                                    @elseif($req->category === 'cuti_bersama')
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-teal-50 text-teal-700 border border-teal-200 dark:bg-teal-950/60 dark:text-teal-300 dark:border-teal-900">
                                            🏖️ Cuti Bersama
                                        </span>
                                    @elseif($req->category === 'dinas_luar')
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900">
                                            💼 Dinas Luar / Lapangan
                                        </span>
                                    @elseif($req->category === 'libur_nasional_agenda')
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/60 dark:text-purple-300 dark:border-purple-900">
                                            🏛️ Libur Nasional / Penugasan
                                        </span>
                                    @elseif($req->category === 'platform_outage')
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-900">
                                            🌐 Server Outage
                                        </span>
                                    @elseif($req->category === 'partner_issue')
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-950/60 dark:text-indigo-300 dark:border-indigo-900">
                                            🏢 Kendala Kantor Mitra
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">
                                            Force Majeure
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    @if($req->status === 'approved')
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-900 inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle-check text-xs"></i> Dibuka (Unlocked)
                                        </span>
                                        @if($req->unlocked_until)
                                            <span class="text-[10px] text-slate-400 block mt-0.5 font-normal">Aktif s/d: {{ $req->unlocked_until->format('d/m H:i') }} WIB</span>
                                        @endif
                                    @elseif($req->status === 'rejected')
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900 inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle-xmark text-xs"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900 inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> Menunggu Review
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-slate-600 dark:text-slate-400 text-xs">
                                    {{ $req->admin_notes ?? 'Menunggu tinjauan Super Admin' }}
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    @if($req->status === 'approved')
                                        <a href="{{ route('mentor.unlock-requests.pdf', $req->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:hover:bg-emerald-900/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 transition shadow-2xs">
                                            <i class="fa-solid fa-file-pdf text-rose-500"></i>
                                            <span>Unduh PDF</span>
                                        </a>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500 bg-slate-50/50 dark:bg-slate-950/40">
                                    <p class="font-bold text-slate-800 dark:text-slate-200 text-xs">Belum Ada Pengajuan</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Anda belum pernah mengajukan tiket dispensasi buka kunci tanggal.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($requests->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $requests->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
