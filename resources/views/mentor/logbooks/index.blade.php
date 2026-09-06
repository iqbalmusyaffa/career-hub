<x-app-layout>
    <div x-data="{ 
        showBatchModal: false, 
        selectedUserId: '', 
        selectedUserName: '', 
        periodName: '', 
        startDate: '', 
        endDate: '', 
        targetHours: 400,
        openBatchModal(id = '', name = '', period = '', start = '', end = '', hours = 400) {
            this.selectedUserId = id;
            this.selectedUserName = name;
            this.periodName = period || 'Batch 1 - 2026';
            this.startDate = start || '2026-08-10';
            this.endDate = end || '2026-09-09';
            this.targetHours = hours || 400;
            this.showBatchModal = true;
        }
    }">
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <h2 class="font-bold text-xl sm:text-2xl text-slate-900 dark:text-white tracking-tight">
                        ACC Presensi Magang
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                        Pilih nama peserta magang untuk meninjau dan menyetujui (ACC) riwayat laporan harian & presensi.
                    </p>
                </div>
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <button type="button" @click="openBatchModal()" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Atur Batch Baru</span>
                    </button>
                    <a href="{{ route('mentor.dashboard') }}" class="px-4 py-2 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 shadow-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left text-xs text-slate-400"></i>
                        <span>Kembali ke Dasbor</span>
                    </a>
                </div>
            </div>
        </x-slot>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 overflow-hidden">
                    
                    <!-- Filter and Search Header -->
                    <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                                Daftar Peserta Magang Bimbingan
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Total {{ $interns->total() }} peserta magang aktif terdaftar di sistem.
                            </p>
                        </div>

                        <!-- Filter Form by Batch -->
                        <form method="GET" action="{{ route('mentor.logbooks.index') }}" class="flex items-center gap-2">
                            <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 whitespace-nowrap">Filter Batch:</label>
                            <select name="batch" onchange="this.form.submit()" class="text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 py-2 px-3">
                                <option value="">Semua Batch / Periode</option>
                                @foreach($batches as $batchName)
                                    <option value="{{ $batchName }}" {{ $selectedBatch === $batchName ? 'selected' : '' }}>{{ $batchName }}</option>
                                @endforeach
                            </select>
                            @if($selectedBatch)
                                <a href="{{ route('mentor.logbooks.index') }}" class="px-2.5 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 bg-slate-100 dark:bg-slate-800 rounded-xl transition">
                                    Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Interns Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-semibold uppercase text-[11px] tracking-wider">
                                    <th class="py-3.5 px-6">PESERTA MAGANG</th>
                                    <th class="py-3.5 px-6">BATCH / PERIODE</th>
                                    <th class="py-3.5 px-6 text-center">TOTAL PRESENSI</th>
                                    <th class="py-3.5 px-6 text-center">DISETUJUI (ACC)</th>
                                    <th class="py-3.5 px-6 text-center">STATUS VERIFIKASI</th>
                                    <th class="py-3.5 px-6 text-right">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-normal">
                                @forelse($interns as $intern)
                                    @php
                                        $period = $intern->internshipPeriod;
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/80 dark:border-blue-900 font-bold flex items-center justify-center text-xs shrink-0">
                                                    {{ strtoupper(substr($intern->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-900 dark:text-white text-xs">
                                                        {{ $intern->name }}
                                                    </div>
                                                    <div class="text-[11px] text-slate-400 dark:text-slate-500 font-normal">
                                                        {{ $intern->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="py-4 px-6 whitespace-nowrap">
                                            @if($period)
                                                <div class="flex items-center gap-2">
                                                    <div>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-900">
                                                            {{ $period->period_name }}
                                                        </span>
                                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">
                                                            {{ $period->start_date->format('d M') }} - {{ $period->end_date->format('d M Y') }} ({{ $period->target_hours }} Jam)
                                                        </div>
                                                    </div>
                                                    <button type="button" @click="openBatchModal('{{ $intern->id }}', '{{ addslashes($intern->name) }}', '{{ addslashes($period->period_name) }}', '{{ $period->start_date->format('Y-m-d') }}', '{{ $period->end_date->format('Y-m-d') }}', '{{ $period->target_hours }}')" title="Ubah Batch" class="w-6 h-6 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-800 transition flex items-center justify-center text-[10px]">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <button type="button" @click="openBatchModal('{{ $intern->id }}', '{{ addslashes($intern->name) }}')" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-medium bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition">
                                                    <i class="fa-solid fa-gear text-[10px]"></i>
                                                    <span>Set Batch</span>
                                                </button>
                                            @endif
                                        </td>

                                        <td class="py-4 px-6 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200/80 dark:border-slate-700">
                                                {{ $intern->logbooks_count }} Laporan
                                            </span>
                                        </td>

                                        <td class="py-4 px-6 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900">
                                                <i class="fa-solid fa-circle-check text-[10px]"></i>
                                                <span>{{ $intern->approved_logbooks_count }} Disetujui</span>
                                            </span>
                                        </td>

                                        <td class="py-4 px-6 text-center whitespace-nowrap">
                                            @if($intern->pending_logbooks_count > 0)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200/80 dark:border-amber-900">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                    <span>{{ $intern->pending_logbooks_count }} Menunggu ACC</span>
                                                </span>
                                            @elseif($intern->logbooks_count > 0)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900">
                                                    <i class="fa-solid fa-check-double text-[10px]"></i>
                                                    <span>Semua Ter-ACC</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                                    <i class="fa-regular fa-clock text-[10px]"></i>
                                                    <span>Belum Ada Presensi</span>
                                                </span>
                                            @endif
                                        </td>

                                        <td class="py-4 px-6 text-right whitespace-nowrap">
                                            <a href="{{ route('mentor.logbooks.intern', $intern->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs transition">
                                                <span>Lihat Presensi</span>
                                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500 bg-slate-50/50 dark:bg-slate-950/40">
                                            <p class="font-bold text-slate-800 dark:text-slate-200 text-xs">Belum Ada Peserta Magang</p>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Peserta magang yang aktif pada filter ini akan muncul di sini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($interns->hasPages())
                        <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                            {{ $interns->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- Modal Set / Edit Batch -->
        <div x-show="showBatchModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity">
            <div @click.away="showBatchModal = false" class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 max-w-md w-full p-6 space-y-5">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs font-bold">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pengaturan Batch / Periode Magang</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Tentukan periode aktif dan target jam kerja</p>
                        </div>
                    </div>
                    <button type="button" @click="showBatchModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('mentor.logbooks.batch.store') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Pilih Peserta Magang -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Peserta Magang</label>
                        <select name="user_id" x-model="selectedUserId" required class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <option value="">-- Pilih Peserta Magang --</option>
                            @foreach($interns as $internOption)
                                <option value="{{ $internOption->id }}">{{ $internOption->name }} ({{ $internOption->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nama Batch -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Batch / Periode</label>
                        <input type="text" name="period_name" x-model="periodName" required placeholder="Contoh: Batch 1 - 2026, Semester Genap..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-semibold placeholder:text-slate-400">
                    </div>

                    <!-- Tanggal Mulai & Tanggal Selesai -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" x-model="startDate" required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Selesai</label>
                            <input type="date" name="end_date" x-model="endDate" required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Target Jam Kerja -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Target Total Jam Kerja (Jam)</label>
                        <input type="number" name="target_hours" x-model="targetHours" required min="1" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-semibold">
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="showBatchModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs transition">
                            Simpan Pengaturan Batch
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
