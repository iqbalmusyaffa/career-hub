<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-0.5">
                    <a href="{{ route('mentor.logbooks.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">ACC Presensi Magang</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $intern->name }}</span>
                </div>
                <h2 class="font-bold text-xl sm:text-2xl text-slate-900 dark:text-white tracking-tight">
                    Riwayat Laporan & Presensi Peserta Magang
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                    Daftar seluruh presensi dan laporan harian peserta magang bimbingan Anda.
                </p>
            </div>
            <a href="{{ route('mentor.logbooks.index') }}" class="px-4 py-2 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 shadow-xs transition flex items-center gap-1.5 self-start sm:self-auto">
                <i class="fa-solid fa-arrow-left text-xs text-slate-400"></i>
                <span>Kembali ke Daftar Peserta</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @php
                $activePeriod = $intern->internshipPeriod;
            @endphp

            <!-- Intern & Batch Profile Card -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/80 dark:border-blue-900 font-black flex items-center justify-center text-sm shrink-0">
                        {{ strtoupper(substr($intern->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold text-slate-900 dark:text-white leading-tight">
                                {{ $intern->name }}
                            </h3>
                            @if($activePeriod)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-900">
                                    <i class="fa-solid fa-layer-group text-[10px]"></i>
                                    <span>{{ $activePeriod->period_name }}</span>
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $intern->email }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-xs">
                    @if($activePeriod)
                        <div class="px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                            <span class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 block">Periode Magang</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $activePeriod->start_date->format('d M Y') }} - {{ $activePeriod->end_date->format('d M Y') }}</span>
                        </div>
                        <div class="px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                            <span class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 block">Target Jam</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $activePeriod->target_hours }} Jam Kerja</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section: Progres Kurikulum & Verifikasi Modul Materi Magang -->
            @if($curriculum && $curriculum->materials->isNotEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-sm border border-purple-200/60 dark:border-purple-900/60">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                                    Progres Silabus & Verifikasi Modul: {{ $curriculum->title }}
                                </h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">
                                    Validasi penguasaan materi pembelajaran dan kompetensi peserta magang per modul.
                                </p>
                            </div>
                        </div>

                        @php
                            $totalMats = $curriculum->materials->count();
                            $completedMats = $curriculum->materials->filter(function($m) use ($progressMap) {
                                return isset($progressMap[$m->id]) && $progressMap[$m->id]->status === 'completed';
                            })->count();
                            $percentProgress = $totalMats > 0 ? round(($completedMats / $totalMats) * 100) : 0;
                        @endphp

                        <div class="flex items-center gap-2 self-start sm:self-auto">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                {{ $completedMats }}/{{ $totalMats }} Modul Lulus ({{ $percentProgress }}%)
                            </span>
                            <div class="w-24 bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden border border-slate-200 dark:border-slate-700">
                                <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $percentProgress }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Materials Grid / List -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($curriculum->materials as $mat)
                            @php
                                $prog = $progressMap[$mat->id] ?? null;
                                $status = $prog?->status ?? 'pending';
                            @endphp
                            <div class="p-4 rounded-xl border {{ $status === 'completed' ? 'border-emerald-200 dark:border-emerald-800/80 bg-emerald-50/30 dark:bg-emerald-950/20' : ($status === 'in_progress' ? 'border-blue-200 dark:border-blue-800/80 bg-blue-50/30 dark:bg-blue-950/20' : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40') }} space-y-3 flex flex-col justify-between">
                                <div class="space-y-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-lg {{ $status === 'completed' ? 'bg-emerald-600 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }} flex items-center justify-center text-[10px] font-bold">
                                                {{ $mat->sequence }}
                                            </span>
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white">
                                                {{ $mat->title }}
                                            </h4>
                                        </div>

                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $status === 'completed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800' : ($status === 'in_progress' ? 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-300 dark:border-blue-800' : 'bg-slate-200 text-slate-600 dark:bg-slate-800 dark:text-slate-400') }}">
                                            {{ $status === 'completed' ? '✓ Lulus / Selesai' : ($status === 'in_progress' ? 'Sedang Berjalan' : 'Belum Mulai') }}
                                        </span>
                                    </div>

                                    @if($mat->description)
                                        <p class="text-[11px] text-slate-600 dark:text-slate-400">
                                            {{ $mat->description }}
                                        </p>
                                    @endif

                                    @if(!empty($mat->competencies))
                                        <div class="flex flex-wrap gap-1 pt-1">
                                            @foreach($mat->competencies as $comp)
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800">
                                                    {{ $comp }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($prog && $prog->mentor_notes)
                                        <div class="p-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-[11px] text-slate-600 dark:text-slate-300">
                                            <span class="font-bold text-slate-700 dark:text-slate-200 block text-[10px]">Catatan Mentor:</span>
                                            {{ $prog->mentor_notes }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Mentor Verification Form -->
                                <form method="POST" action="{{ route('mentor.interns.materials.progress', [$intern->id, $mat->id]) }}" class="pt-2 border-t border-slate-200/60 dark:border-slate-800/80 flex items-center justify-between gap-2">
                                    @csrf
                                    <div class="flex items-center gap-1.5 flex-1">
                                        <input type="text" name="mentor_notes" value="{{ $prog?->mentor_notes ?? '' }}" placeholder="Catatan evaluasi mentor..." class="w-full text-[11px] py-1 px-2.5 rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400">
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0">
                                        @if($status !== 'completed')
                                            <button type="submit" name="status" value="completed" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-bold shadow-2xs transition flex items-center gap-1" title="Validasi Lulus Modul">
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                                <span>Lulus</span>
                                            </button>
                                        @else
                                            <button type="submit" name="status" value="in_progress" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-[11px] font-semibold transition" title="Ubah ke Sedang Berjalan">
                                                <span>Revisi</span>
                                            </button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Filter Bar for Logbooks -->
            <div class="p-4 bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800">
                <form method="GET" action="{{ route('mentor.logbooks.intern', $intern->id) }}" class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2.5 flex-1 min-w-[280px]">
                        <!-- Kehadiran Filter -->
                        <div class="w-48">
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-1">Kehadiran</label>
                            <select name="attendance" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-slate-800 dark:text-slate-200 focus:ring-blue-500 focus:border-blue-500 py-1.5 px-2.5">
                                <option value="">Semua Kehadiran</option>
                                <option value="present" {{ $selectedAttendance === 'present' ? 'selected' : '' }}>🟢 Hadir</option>
                                <option value="izin_sakit" {{ in_array($selectedAttendance, ['izin_sakit', 'excused']) ? 'selected' : '' }}>🟡 Izin / Sakit</option>
                                <option value="alpha" {{ in_array($selectedAttendance, ['alpha', 'unexcused']) ? 'selected' : '' }}>🔴 Tidak Hadir / Alpa</option>
                            </select>
                        </div>

                        <!-- Status ACC Filter -->
                        <div class="w-48">
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-1">Status ACC</label>
                            <select name="status" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-slate-800 dark:text-slate-200 focus:ring-blue-500 focus:border-blue-500 py-1.5 px-2.5">
                                <option value="">Semua Status</option>
                                <option value="approved" {{ $selectedStatus === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="pending" {{ $selectedStatus === 'pending' ? 'selected' : '' }}>Menunggu ACC</option>
                                <option value="action_required" {{ $selectedStatus === 'action_required' ? 'selected' : '' }}>Perlu Revisi</option>
                                <option value="rejected" {{ $selectedStatus === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <!-- Month Filter -->
                        <div class="w-44">
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mb-1">Periode Bulan</label>
                            <input type="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-slate-800 dark:text-slate-200 focus:ring-blue-500 focus:border-blue-500 py-1.5 px-2.5">
                        </div>
                    </div>

                    @if($selectedAttendance || $selectedStatus || $selectedMonth)
                        <div class="self-end pb-0.5">
                            <a href="{{ route('mentor.logbooks.intern', $intern->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                                <i class="fa-solid fa-rotate-left text-[11px]"></i>
                                <span>Reset Filter</span>
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Table of Logbooks -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-semibold uppercase text-[11px] tracking-wider">
                                <th class="py-3.5 px-6">TANGGAL</th>
                                <th class="py-3.5 px-6">PESERTA MAGANG</th>
                                <th class="py-3.5 px-6">BATCH / PERIODE</th>
                                <th class="py-3.5 px-6">KEHADIRAN</th>
                                <th class="py-3.5 px-6">URAIAN AKTIVITAS</th>
                                <th class="py-3.5 px-6 text-center">STATUS ACC</th>
                                <th class="py-3.5 px-6 text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-normal">
                            @forelse($logbooks as $logbook)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                    <td class="py-4 px-6 font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                                        {{ $logbook->date->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $logbook->intern->name }}</div>
                                        <div class="text-[11px] text-slate-400 dark:text-slate-500 font-normal">{{ $logbook->intern->email }}</div>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-900">
                                            {{ $activePeriod->period_name ?? 'Batch 1' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @php
                                            $attLabel = $logbook->attendance_type_label ?? $logbook->attendance_type;
                                            $attColor = match($attLabel) {
                                                'Hadir' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-900',
                                                'Tidak Hadir Dengan Keterangan' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200/80 dark:border-amber-900',
                                                'Tidak Hadir Tanpa Keterangan' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200/80 dark:border-rose-900',
                                                default => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $attColor }}">
                                            {{ $attLabel }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 max-w-xs">
                                        <p class="text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed font-normal">
                                            {{ $logbook->activities }}
                                        </p>
                                    </td>
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        @if($logbook->status === 'approved')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold border bg-emerald-50 text-emerald-700 border-emerald-200/80 dark:bg-emerald-950/80 dark:text-emerald-300 dark:border-emerald-800">
                                                <i class="fa-solid fa-circle-check text-[10px]"></i>
                                                <span>Disetujui</span>
                                            </span>
                                        @elseif($logbook->status === 'rejected')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold border bg-rose-50 text-rose-700 border-rose-200/80 dark:bg-rose-950/80 dark:text-rose-300 dark:border-rose-800">
                                                <i class="fa-solid fa-circle-xmark text-[10px]"></i>
                                                <span>Kehadiran Ditolak</span>
                                            </span>
                                        @elseif($logbook->status === 'action_required')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold border bg-amber-50 text-amber-700 border-amber-200/80 dark:bg-amber-950/80 dark:text-amber-300 dark:border-amber-800">
                                                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                                <span>Perlu Revisi</span>
                                            </span>
                                        @elseif($logbook->status === 'absent')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold border bg-rose-50 text-rose-700 border-rose-200/80 dark:bg-rose-950/80 dark:text-rose-300 dark:border-rose-800">
                                                <i class="fa-solid fa-user-xmark text-[10px]"></i>
                                                <span>Tidak Hadir</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold border bg-blue-50 text-blue-700 border-blue-200/80 dark:bg-blue-950/80 dark:text-blue-300 dark:border-blue-800">
                                                <i class="fa-regular fa-clock text-[10px]"></i>
                                                <span>Menunggu ACC Mentor</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <a href="{{ route('mentor.logbooks.show', $logbook->id) }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-2xs">
                                            Tinjau
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400 dark:text-slate-500 bg-slate-50/50 dark:bg-slate-950/40">
                                        <p class="font-bold text-slate-800 dark:text-slate-200 text-xs">Belum Ada Riwayat Presensi</p>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Laporan logbook peserta magang akan muncul di sini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logbooks->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $logbooks->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
