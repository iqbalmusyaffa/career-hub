<x-app-layout>
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
                <button type="button" 
                        onclick="window.dispatchEvent(new CustomEvent('open-batch-modal', { detail: { id: '', name: '', period: '', start: '', end: '', hours: 400 } }))" 
                        class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
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

    <div x-data="{ 
        showBatchModal: false, 
        selectedUserIds: [], 
        selectedMasterBatchId: '',
        periodName: '', 
        startDate: '', 
        endDate: '', 
        targetHours: 400,
        searchQuery: '',
        tabFilter: 'all',
        masterBatches: {{ Js::from(($masterBatches ?? collect())->map(fn($b) => [
            'id' => $b->id,
            'batch_name' => $b->batch_name,
            'start_date' => $b->start_date?->format('Y-m-d'),
            'end_date' => $b->end_date?->format('Y-m-d'),
            'target_hours' => $b->target_hours
        ])) }},
        onMasterBatchChange(batchId) {
            const found = this.masterBatches.find(b => b.id == batchId);
            if (found) {
                this.periodName = found.batch_name;
                if (found.start_date) this.startDate = found.start_date;
                if (found.end_date) this.endDate = found.end_date;
                if (found.target_hours) this.targetHours = found.target_hours;
            }
        },
        internsList: {{ Js::from($allInterns->map(fn($i) => [
            'id' => $i->id,
            'name' => $i->name,
            'email' => $i->email,
            'current_batch' => $i->internshipPeriod?->period_name ?? null
        ])) }},
        get unassignedCount() {
            return this.internsList.filter(i => !i.current_batch).length;
        },
        get assignedCount() {
            return this.internsList.filter(i => !!i.current_batch).length;
        },
        get filteredInterns() {
            let list = this.internsList;
            if (this.tabFilter === 'unassigned') {
                list = list.filter(i => !i.current_batch);
            } else if (this.tabFilter === 'assigned') {
                list = list.filter(i => !!i.current_batch);
            }
            if (!this.searchQuery) return list;
            const q = this.searchQuery.toLowerCase();
            return list.filter(i => i.name.toLowerCase().includes(q) || i.email.toLowerCase().includes(q));
        },
        selectAll() {
            this.selectedUserIds = this.filteredInterns.map(i => i.id);
        },
        deselectAll() {
            this.selectedUserIds = [];
        },
        toggleIntern(id) {
            if (this.selectedUserIds.includes(id)) {
                this.selectedUserIds = this.selectedUserIds.filter(item => item !== id);
            } else {
                this.selectedUserIds.push(id);
            }
        },
        openBatchModal(id = '', name = '', period = '', start = '', end = '', hours = 400) {
            if (id) {
                this.selectedUserIds = [parseInt(id)];
            } else {
                this.selectedUserIds = [];
            }
            this.periodName = period || 'Batch 1 - 2026';
            this.startDate = start || '2026-08-10';
            this.endDate = end || '2026-09-09';
            this.targetHours = hours || 400;
            this.searchQuery = '';
            this.showBatchModal = true;
        }
    }" 
    @open-batch-modal.window="openBatchModal($event.detail?.id || '', $event.detail?.name || '', $event.detail?.period || '', $event.detail?.start || '', $event.detail?.end || '', $event.detail?.hours || 400)">

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

        <!-- Modal Set / Edit Batch (Supports Bulk & Multi-Select) -->
        <div x-show="showBatchModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity">
            <div @click.away="showBatchModal = false" class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 max-w-lg w-full p-6 space-y-5">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs font-bold">
                            <i class="fa-solid fa-users-gear"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pengaturan Batch & Periode Magang</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Pilih satu atau banyak peserta sekaligus untuk diterapkan ke batch ini</p>
                        </div>
                    </div>
                    <button type="button" @click="showBatchModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('mentor.logbooks.batch.store') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Pilih Peserta Magang (Bulk Multi-Select with Search) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Pilih Peserta Magang</label>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" 
                                      :class="selectedUserIds.length > 0 ? 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-900' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'"
                                      x-text="selectedUserIds.length + ' dari ' + internsList.length + ' dipilih'"></span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[11px]">
                                <button type="button" @click="selectAll()" class="font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 cursor-pointer">
                                    Pilih Semua
                                </button>
                                <span class="text-slate-300 dark:text-slate-700">&bull;</span>
                                <button type="button" @click="deselectAll()" class="font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 cursor-pointer">
                                    Batal Pilih
                                </button>
                            </div>
                        </div>

                        <!-- Quick Tabs Filter -->
                        <div class="flex items-center gap-1.5 p-1 bg-slate-100 dark:bg-slate-950/80 rounded-xl mb-2 border border-slate-200/80 dark:border-slate-800 text-[11px]">
                            <button type="button" @click="tabFilter = 'all'" 
                                    :class="tabFilter === 'all' ? 'bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 font-bold shadow-2xs' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'"
                                    class="flex-1 py-1 rounded-lg transition text-center cursor-pointer">
                                Semua (<span x-text="internsList.length"></span>)
                            </button>
                            <button type="button" @click="tabFilter = 'unassigned'" 
                                    :class="tabFilter === 'unassigned' ? 'bg-white dark:bg-slate-800 text-amber-600 dark:text-amber-400 font-bold shadow-2xs' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'"
                                    class="flex-1 py-1 rounded-lg transition text-center cursor-pointer">
                                Belum Ada Batch (<span x-text="unassignedCount"></span>)
                            </button>
                            <button type="button" @click="tabFilter = 'assigned'" 
                                    :class="tabFilter === 'assigned' ? 'bg-white dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 font-bold shadow-2xs' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'"
                                    class="flex-1 py-1 rounded-lg transition text-center cursor-pointer">
                                Sudah Ada Batch (<span x-text="assignedCount"></span>)
                            </button>
                        </div>

                        <!-- Search input -->
                        <div class="relative mb-2">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
                            <input type="text" x-model="searchQuery" placeholder="Cari nama atau email peserta..." class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>

                        <!-- Scrollable Checkbox List -->
                        <div class="max-h-48 overflow-y-auto space-y-1.5 border border-slate-200/80 dark:border-slate-800 rounded-xl p-2 bg-slate-50/50 dark:bg-slate-950/40">
                            <template x-for="intern in filteredInterns" :key="intern.id">
                                <label class="flex items-center justify-between p-2 rounded-lg hover:bg-white dark:hover:bg-slate-800/80 cursor-pointer transition border"
                                       :class="selectedUserIds.includes(intern.id) ? 'bg-white dark:bg-slate-800/90 border-blue-300 dark:border-blue-800 shadow-2xs' : 'border-transparent'">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <input type="checkbox" 
                                               name="user_ids[]" 
                                               :value="intern.id" 
                                               x-model="selectedUserIds" 
                                               class="rounded text-blue-600 focus:ring-blue-500 border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold text-slate-900 dark:text-white truncate" x-text="intern.name"></div>
                                            <div class="text-[10px] text-slate-400 dark:text-slate-500 truncate" x-text="intern.email"></div>
                                        </div>
                                    </div>
                                    <div class="shrink-0 ml-2">
                                        <template x-if="intern.current_batch">
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-900" x-text="intern.current_batch"></span>
                                        </template>
                                        <template x-if="!intern.current_batch">
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-400">Belum ada batch</span>
                                        </template>
                                    </div>
                                </label>
                            </template>
                            <div x-show="filteredInterns.length === 0" class="p-4 text-center text-xs text-slate-400">
                                Tidak ada peserta yang cocok dengan kata kunci pencarian.
                            </div>
                        </div>
                    </div>

                    <!-- Pilih dari Master Batch atau Input Manual -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-purple-700 dark:text-purple-300">
                            Pilih dari Master Batch Terpusat
                        </label>
                        <select x-model="selectedMasterBatchId" 
                                @change="onMasterBatchChange($event.target.value)"
                                class="w-full text-xs font-semibold rounded-xl border-purple-200 dark:border-purple-800/60 bg-purple-50/50 dark:bg-purple-950/30 text-purple-900 dark:text-purple-200 py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                            <option value="">-- Pilih Batch Master (Otomatis Isi Tanggal & Jam) --</option>
                            <template x-for="mb in masterBatches" :key="mb.id">
                                <option :value="mb.id" x-text="mb.batch_name + (mb.target_hours ? ' (' + mb.target_hours + ' Jam)' : '')"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Nama Batch -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Batch / Periode <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="period_name" 
                               x-model="periodName" 
                               list="logbook_master_batches_datalist"
                               required 
                               placeholder="Contoh: Batch 1 - 2026, Semester Genap..." 
                               class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-semibold placeholder:text-slate-400">
                        <datalist id="logbook_master_batches_datalist">
                            <template x-for="mb in masterBatches" :key="'dl-'+mb.id">
                                <option :value="mb.batch_name"></option>
                            </template>
                        </datalist>
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

                    <div class="pt-2 flex items-center justify-between gap-2 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] text-slate-500 dark:text-slate-400" x-show="selectedUserIds.length === 0">
                            Pilih minimal 1 peserta magang
                        </span>
                        <span class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold" x-show="selectedUserIds.length > 0" x-text="selectedUserIds.length + ' peserta akan di-update'"></span>

                        <div class="flex items-center gap-2">
                            <button type="button" @click="showBatchModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" 
                                    :disabled="selectedUserIds.length === 0" 
                                    :class="selectedUserIds.length === 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'"
                                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs transition flex items-center gap-1.5">
                                <i class="fa-solid fa-floppy-disk text-xs"></i>
                                <span x-text="selectedUserIds.length > 0 ? 'Simpan Batch (' + selectedUserIds.length + ' Peserta)' : 'Simpan Pengaturan Batch'"></span>
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
