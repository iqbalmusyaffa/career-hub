<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Admin</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">Magang</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-blue-600 dark:text-blue-400 font-semibold">Buka Kunci Presensi</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/60 border border-purple-200 dark:border-purple-800/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                                Pusat Buka Kunci Presensi
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                                Evaluasi dan verifikasi permohonan pembukaan kunci presensi harian peserta magang dari mitra.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('admin.system-flow.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750 transition shadow-2xs">
                        <i class="fa-solid fa-diagram-project text-blue-500"></i>
                        <span>Flowchart Sistem</span>
                    </a>
                </div>
            </div>

            <!-- Summary Counter Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Pending Card -->
                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'pending']) }}" class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border transition shadow-xs hover:shadow-md {{ $status === 'pending' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Menunggu Review</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200/80 dark:border-amber-800/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm font-semibold">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $pendingCount }}</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tiket memerlukan persetujuan</p>
                    </div>
                </a>

                <!-- Approved Card -->
                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'approved']) }}" class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border transition shadow-xs hover:shadow-md {{ $status === 'approved' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Disetujui (Unlocked)</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/80 dark:border-emerald-800/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm font-semibold">
                            <i class="fa-solid fa-lock-open"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $approvedCount }}</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Akses presensi aktif dibuka</p>
                    </div>
                </a>

                <!-- Rejected Card -->
                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'rejected']) }}" class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border transition shadow-xs hover:shadow-md {{ $status === 'rejected' ? 'border-rose-500 ring-2 ring-rose-500/20' : 'border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Ditolak</span>
                        <div class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200/80 dark:border-rose-800/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-sm font-semibold">
                            <i class="fa-solid fa-ban"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $rejectedCount }}</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Permohonan tidak disetujui</p>
                    </div>
                </a>
            </div>

            <!-- Filter Status Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'all']) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold transition {{ $status === 'all' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750' }}">
                    <span>Semua Status</span>
                </a>
                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold transition {{ $status === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750' }}">
                    <i class="fa-regular fa-clock"></i>
                    <span>Menunggu Review</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'pending' ? 'bg-amber-700 text-amber-100' : 'bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-400' }}">{{ $pendingCount }}</span>
                </a>
                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'approved']) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold transition {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750' }}">
                    <i class="fa-solid fa-check"></i>
                    <span>Disetujui</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'approved' ? 'bg-emerald-700 text-emerald-100' : 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400' }}">{{ $approvedCount }}</span>
                </a>
                <a href="{{ route('admin.internship-unlocks.index', ['status' => 'rejected']) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold transition {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750' }}">
                    <i class="fa-solid fa-xmark"></i>
                    <span>Ditolak</span>
                </a>
            </div>

            <!-- Table of Requests -->
            <div class="bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-700/80 text-[11px] font-semibold uppercase text-slate-500 dark:text-slate-400 tracking-wider">
                                <th class="py-3.5 px-5">Tiket & Tanggal</th>
                                <th class="py-3.5 px-5">Peserta Magang</th>
                                <th class="py-3.5 px-5">Mentor & Mitra</th>
                                <th class="py-3.5 px-5">Kategori Kendala</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs">
                            @forelse($requests as $req)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-750/50 transition">
                                <td class="py-4 px-5">
                                    <div class="font-semibold text-slate-900 dark:text-white">
                                        {{ $req->target_date->isoFormat('D MMMM YYYY') }}
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                        <span class="font-mono text-purple-600 dark:text-purple-400 font-semibold">#ULK-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        <span>•</span>
                                        <span>{{ $req->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 font-bold flex items-center justify-center text-xs shrink-0">
                                            {{ substr($req->intern->name ?? 'M', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-slate-900 dark:text-slate-100">{{ $req->intern->name }}</div>
                                            <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $req->intern->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-5">
                                    <div class="font-medium text-slate-800 dark:text-slate-200">{{ $req->mentor->name }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-0.5">
                                        <i class="fa-solid fa-building text-[10px] text-slate-400"></i>
                                        <span>{{ $req->company->company_name ?? 'Mitra Terdaftar' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-5">
                                    @if($req->category === 'medical_emergency')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-2xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">
                                            <i class="fa-solid fa-hospital-user text-[10px]"></i>
                                            <span>Sakit / Medis</span>
                                        </span>
                                    @elseif($req->category === 'academic_urgent')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-2xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-900">
                                            <i class="fa-solid fa-graduation-cap text-[10px]"></i>
                                            <span>Akademik / Wisuda</span>
                                        </span>
                                    @elseif($req->category === 'personal_urgent')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-2xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">
                                            <i class="fa-solid fa-heart-pulse text-[10px]"></i>
                                            <span>Mendesak Pribadi</span>
                                        </span>
                                    @elseif($req->category === 'cuti_bersama')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-2xs font-semibold bg-teal-50 text-teal-700 border border-teal-200/80 dark:bg-teal-950/60 dark:text-teal-300 dark:border-teal-900">
                                            <i class="fa-solid fa-umbrella-beach text-[10px]"></i>
                                            <span>Cuti Bersama</span>
                                        </span>
                                    @elseif($req->category === 'dinas_luar')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-2xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900">
                                            <i class="fa-solid fa-briefcase text-[10px]"></i>
                                            <span>Dinas Luar</span>
                                        </span>
                                    @elseif($req->category === 'libur_nasional_agenda')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-2xs font-semibold bg-purple-50 text-purple-700 border border-purple-200/80 dark:bg-purple-950/60 dark:text-purple-300 dark:border-purple-900">
                                            <i class="fa-solid fa-calendar-star text-[10px]"></i>
                                            <span>Libur / Penugasan</span>
                                        </span>
                                    @elseif($req->category === 'platform_outage')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-2xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/80 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-900">
                                            <i class="fa-solid fa-globe text-[10px]"></i>
                                            <span>Kendala Server</span>
                                        </span>
                                    @elseif($req->category === 'partner_issue')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-2xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/80 dark:bg-indigo-950/60 dark:text-indigo-300 dark:border-indigo-900">
                                            <i class="fa-solid fa-building-circle-exclamation text-[10px]"></i>
                                            <span>Operasional Mitra</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-2xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">
                                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                            <span>Force Majeure</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-5">
                                    @if($req->status === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-2xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-900">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                                            <span>Disetujui</span>
                                        </span>
                                        @if($req->unlocked_until)
                                            <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">S/d: {{ $req->unlocked_until->format('d/m/y H:i') }}</div>
                                        @endif
                                    @elseif($req->status === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-2xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">
                                            <i class="fa-solid fa-circle-xmark text-[10px]"></i>
                                            <span>Ditolak</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-2xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            <span>Menunggu Review</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($req->status === 'approved')
                                            <a href="{{ route('admin.internship-unlocks.pdf', $req->id) }}" class="p-1.5 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:hover:bg-emerald-900/80 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-lg text-xs transition" title="Unduh Surat Resmi PDF">
                                                <i class="fa-solid fa-file-pdf text-rose-500"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.internship-unlocks.show', $req->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 dark:bg-slate-700 hover:bg-slate-800 dark:hover:bg-slate-650 text-white rounded-lg text-xs font-semibold transition shadow-2xs">
                                            <span>Detail</span>
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-xl mb-3">
                                        <i class="fa-regular fa-folder-open"></i>
                                    </div>
                                    <p class="font-medium text-xs text-slate-700 dark:text-slate-300">Belum ada permohonan buka kunci presensi.</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Semua permohonan dari mentor mitra akan dicatat di sini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($requests->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-700/80">
                    {{ $requests->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
