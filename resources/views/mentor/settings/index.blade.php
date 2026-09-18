<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-slate-900 dark:text-white tracking-tight">
                    Pengaturan Batch & Hari Libur Perusahaan
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                    Konfigurasi Batch / Periode magang peserta, libur internal perusahaan, dan pengajuan operasional Cuti Bersama ke Super Admin.
                </p>
            </div>
            <a href="{{ route('mentor.logbooks.index') }}" class="px-4 py-2 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 shadow-xs transition flex items-center gap-1.5 self-start sm:self-auto">
                <i class="fa-solid fa-arrow-left text-xs text-slate-400"></i>
                <span>Kembali ke Presensi Magang</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        masterBatchModal: false,
        masterBatchForm: {
            id: null,
            batch_name: '',
            start_date: '{{ date('Y-m-d') }}',
            end_date: '{{ date('Y-m-d', strtotime('+6 months')) }}',
            target_hours: 400,
            status: 'active',
            description: ''
        },
        openCreateMasterBatch() {
            this.masterBatchForm = {
                id: null,
                batch_name: '',
                start_date: '{{ date('Y-m-d') }}',
                end_date: '{{ date('Y-m-d', strtotime('+6 months')) }}',
                target_hours: 400,
                status: 'active',
                description: ''
            };
            this.masterBatchModal = true;
        },
        openEditMasterBatch(b) {
            this.masterBatchForm = {
                id: b.id,
                batch_name: b.batch_name,
                start_date: b.start_date || '',
                end_date: b.end_date || '',
                target_hours: b.target_hours || 400,
                status: b.status || 'active',
                description: b.description || ''
            };
            this.masterBatchModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl flex items-center gap-3 text-emerald-800 dark:text-emerald-300 text-xs font-medium">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-xl flex items-center gap-3 text-rose-800 dark:text-rose-300 text-xs font-medium">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-base shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- =================================================================== -->
            <!-- CARD 0: MASTER BATCH & PERIODE MAGANG TERPUSAT                      -->
            <!-- (Dikelola oleh Super Admin, HR, Company Owner, & Mentor)            -->
            <!-- =================================================================== -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-7 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                                Master Batch & Periode Magang Terpusat
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                                Sumber data utama batch untuk Lowongan Kerja, Kurikulum, Presensi, dan Sertifikat.
                            </p>
                        </div>
                    </div>

                    <button type="button" 
                            @click="openCreateMasterBatch()" 
                            class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-semibold shadow-xs transition flex items-center gap-2 self-start sm:self-auto cursor-pointer">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Buat Master Batch Baru</span>
                    </button>
                </div>

                <!-- Master Batches Grid -->
                @if($masterBatches->isEmpty())
                    <div class="p-6 text-center text-xs text-slate-400 bg-slate-50 dark:bg-slate-950/40 rounded-xl border border-slate-200/60 dark:border-slate-800">
                        Belum ada Master Batch. Klik "Buat Master Batch Baru" untuk mendaftarkan batch terpusat.
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($masterBatches as $mb)
                            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-950/40 space-y-3 flex flex-col justify-between hover:border-slate-300 dark:hover:border-slate-700 transition">
                                <div class="space-y-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $mb->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : ($mb->status === 'draft' ? 'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300' : 'bg-slate-200 text-slate-600 dark:bg-slate-800 dark:text-slate-400') }}">
                                                {{ $mb->status === 'active' ? 'Aktif' : ($mb->status === 'draft' ? 'Draf' : 'Ditutup') }}
                                            </span>
                                            <h4 class="text-xs font-bold text-slate-900 dark:text-white mt-1">
                                                {{ $mb->batch_name }}
                                            </h4>
                                        </div>

                                        <div class="flex items-center gap-1 shrink-0">
                                            <button type="button" 
                                                    @click="openEditMasterBatch({{ Js::from([
                                                        'id' => $mb->id,
                                                        'batch_name' => $mb->batch_name,
                                                        'start_date' => $mb->start_date?->format('Y-m-d'),
                                                        'end_date' => $mb->end_date?->format('Y-m-d'),
                                                        'target_hours' => $mb->target_hours,
                                                        'status' => $mb->status,
                                                        'description' => $mb->description
                                                    ]) }})"
                                                    class="p-1.5 rounded-lg text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-slate-800 transition" 
                                                    title="Edit Batch">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </button>
                                            <form method="POST" action="{{ route('mentor.settings.master-batches.delete', $mb->id) }}" onsubmit="return confirm('Hapus Master Batch \'{{ $mb->batch_name }}\'?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 dark:text-slate-400 dark:hover:text-rose-400 hover:bg-white dark:hover:bg-slate-800 transition" title="Hapus Batch">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    @if($mb->description)
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2">
                                            {{ $mb->description }}
                                        </p>
                                    @endif
                                </div>

                                <div class="pt-2 border-t border-slate-200/60 dark:border-slate-800/80 space-y-2">
                                    <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                                        <span>
                                            <i class="fa-solid fa-calendar-days text-[10px] text-slate-400 mr-1"></i>
                                            {{ $mb->start_date ? $mb->start_date->format('d M Y') : '-' }} — {{ $mb->end_date ? $mb->end_date->format('d M Y') : '-' }}
                                        </span>
                                        <span class="font-bold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-700">
                                            {{ $mb->target_hours }} Jam
                                        </span>
                                    </div>

                                    <div class="pt-1 flex items-center justify-between gap-2">
                                        <form method="POST" action="{{ route('mentor.batches.bulk-certificates') }}" onsubmit="return confirm('Terbitkan e-Sertifikat untuk seluruh peserta magang pada \'{{ $mb->batch_name }}\'? Peserta yang sudah selesai akan otomatis menerima notifikasi.');">
                                            @csrf
                                            <input type="hidden" name="batch_name" value="{{ $mb->batch_name }}">
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-purple-50 dark:bg-purple-950/60 hover:bg-purple-100 dark:hover:bg-purple-900/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 transition cursor-pointer">
                                                <i class="fa-solid fa-graduation-cap text-xs"></i>
                                                <span>Terbitkan Sertifikat Angkatan</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Card 1: Kelola Batch & Periode Magang Peserta (Full Workspace) -->
            <div x-data="{
                selectedUserIds: [],
                tabFilter: 'all',
                searchQuery: '',
                selectedMasterBatchId: '',
                periodName: 'Batch 1 - Semester Genap 2026',
                startDate: '2026-08-10',
                endDate: '2027-02-09',
                targetHours: 400,
                masterBatches: {{ Js::from($masterBatches->map(fn($b) => [
                    'id' => $b->id,
                    'batch_name' => $b->batch_name,
                    'start_date' => $b->start_date?->format('Y-m-d'),
                    'end_date' => $b->end_date?->format('Y-m-d'),
                    'target_hours' => $b->target_hours
                ])) }},
                internsList: {{ Js::from($interns->map(fn($i) => [
                    'id' => $i->id,
                    'name' => $i->name,
                    'email' => $i->email,
                    'current_batch' => $i->internshipPeriod?->period_name ?? null,
                    'start_date' => $i->internshipPeriod?->start_date?->format('Y-m-d') ?? null,
                    'end_date' => $i->internshipPeriod?->end_date?->format('Y-m-d') ?? null,
                    'target_hours' => $i->internshipPeriod?->target_hours ?? 400,
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
                editInternBatch(intern) {
                    this.selectedUserIds = [intern.id];
                    this.periodName = intern.current_batch || 'Batch 1 - Semester Genap 2026';
                    this.startDate = intern.start_date || '2026-08-10';
                    this.endDate = intern.end_date || '2027-02-09';
                    this.targetHours = intern.target_hours || 400;
                    window.scrollTo({ top: 300, behavior: 'smooth' });
                }
            }" class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-7 space-y-6">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-users-gear text-blue-600 dark:text-blue-400 text-xs"></i>
                            1. Kelola Batch & Periode Magang Peserta
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-normal">
                            Tetapkan nama batch, tanggal mulai & selesai, serta target jam kerja ke satu atau banyak peserta sekaligus.
                        </p>
                    </div>

                    <!-- Quick Summary Badges -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            Total: <strong class="text-slate-900 dark:text-white" x-text="internsList.length"></strong> Peserta
                        </span>
                        <span class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-900">
                            Belum Ada Batch: <strong x-text="unassignedCount"></strong>
                        </span>
                        <span class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900">
                            Sudah Ter-Batch: <strong x-text="assignedCount"></strong>
                        </span>
                    </div>
                </div>

                <!-- Form Bulk Batch Setup -->
                <form action="{{ route('mentor.settings.periods.store') }}" method="POST" class="space-y-5 bg-slate-50 dark:bg-slate-950/60 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        
                        <!-- Left Col: Candidate Multi-Select Checklist (Col-span 7) -->
                        <div class="lg:col-span-7 space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <label class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                                        Pilih Peserta Magang
                                    </label>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                          :class="selectedUserIds.length > 0 ? 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-900' : 'bg-slate-200 text-slate-600 dark:bg-slate-800 dark:text-slate-400'"
                                          x-text="selectedUserIds.length + ' dipilih'"></span>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <button type="button" @click="selectAll()" class="font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 cursor-pointer">
                                        Pilih Semua Sesuai Tab
                                    </button>
                                    <span class="text-slate-300 dark:text-slate-700">&bull;</span>
                                    <button type="button" @click="deselectAll()" class="font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 cursor-pointer">
                                        Batal Pilih
                                    </button>
                                </div>
                            </div>

                            <!-- Tabs Filter -->
                            <div class="flex items-center gap-1.5 p-1 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 text-xs">
                                <button type="button" @click="tabFilter = 'all'" 
                                        :class="tabFilter === 'all' ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 font-bold border border-blue-200 dark:border-blue-900' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                                        class="flex-1 py-1.5 rounded-lg transition text-center cursor-pointer">
                                    Semua (<span x-text="internsList.length"></span>)
                                </button>
                                <button type="button" @click="tabFilter = 'unassigned'" 
                                        :class="tabFilter === 'unassigned' ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 font-bold border border-amber-200 dark:border-amber-900' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                                        class="flex-1 py-1.5 rounded-lg transition text-center cursor-pointer">
                                    Belum Ada Batch (<span x-text="unassignedCount"></span>)
                                </button>
                                <button type="button" @click="tabFilter = 'assigned'" 
                                        :class="tabFilter === 'assigned' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-bold border border-emerald-200 dark:border-emerald-900' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                                        class="flex-1 py-1.5 rounded-lg transition text-center cursor-pointer">
                                    Sudah Ada Batch (<span x-text="assignedCount"></span>)
                                </button>
                            </div>

                            <!-- Live Search -->
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-xs text-slate-400"></i>
                                <input type="text" x-model="searchQuery" placeholder="Cari nama atau email peserta magang..." class="w-full pl-8 pr-3 py-2 text-xs rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
                            </div>

                            <!-- Candidate Checklist Scrollable -->
                            <div class="max-h-60 overflow-y-auto space-y-1.5 border border-slate-200/80 dark:border-slate-800 rounded-xl p-2.5 bg-white dark:bg-slate-900 divide-y divide-slate-100 dark:divide-slate-800">
                                <template x-for="intern in filteredInterns" :key="intern.id">
                                    <label class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/80 cursor-pointer transition border"
                                           :class="selectedUserIds.includes(intern.id) ? 'bg-blue-50/40 dark:bg-blue-950/30 border-blue-300 dark:border-blue-800 shadow-2xs' : 'border-transparent'">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <input type="checkbox" 
                                                   name="user_ids[]" 
                                                   :value="intern.id" 
                                                   x-model="selectedUserIds" 
                                                   class="rounded text-blue-600 focus:ring-blue-500 border-slate-300 dark:border-slate-700 dark:bg-slate-950 w-4 h-4">
                                            <div class="min-w-0">
                                                <div class="text-xs font-bold text-slate-900 dark:text-white truncate" x-text="intern.name"></div>
                                                <div class="text-[11px] text-slate-400 dark:text-slate-500 truncate font-normal" x-text="intern.email"></div>
                                            </div>
                                        </div>
                                        <div class="shrink-0 ml-3 flex items-center gap-2">
                                            <template x-if="intern.current_batch">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-900" x-text="intern.current_batch"></span>
                                            </template>
                                            <template x-if="!intern.current_batch">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-900">Belum ada batch</span>
                                            </template>
                                        </div>
                                    </label>
                                </template>
                                <div x-show="filteredInterns.length === 0" class="p-6 text-center text-xs text-slate-400">
                                    Tidak ada peserta magang yang cocok dengan filter atau pencarian ini.
                                </div>
                            </div>
                        </div>

                        <!-- Right Col: Batch Setup Parameters (Col-span 5) -->
                        <div class="lg:col-span-5 space-y-4 bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
                            <div class="space-y-3.5">
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                                    <i class="fa-solid fa-pen-ruler text-blue-600 dark:text-blue-400"></i>
                                    Konfigurasi Periode & Target
                                </h4>

                                <!-- Pilih dari Master Batch atau Input Manual -->
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                            Pilih dari Master Batch Terpusat
                                        </label>
                                        <button type="button" 
                                                @click="openCreateMasterBatch()" 
                                                class="text-[11px] text-purple-600 dark:text-purple-400 font-semibold hover:underline flex items-center gap-1 cursor-pointer">
                                            <i class="fa-solid fa-plus text-[10px]"></i>
                                            <span>Batch Baru</span>
                                        </button>
                                    </div>
                                    <select x-model="selectedMasterBatchId" 
                                            @change="onMasterBatchChange($event.target.value)"
                                            class="w-full text-xs font-semibold rounded-xl border-purple-200 dark:border-purple-800/60 bg-purple-50/50 dark:bg-purple-950/30 text-purple-900 dark:text-purple-200 py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                                        <option value="">-- Pilih Batch Master (Auto-fill Tanggal & Jam) --</option>
                                        <template x-for="mb in masterBatches" :key="mb.id">
                                            <option :value="mb.id" x-text="mb.batch_name + (mb.target_hours ? ' (' + mb.target_hours + ' Jam)' : '')"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Nama Batch / Periode -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">
                                        Nama Batch / Periode <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="period_name" 
                                           x-model="periodName" 
                                           list="master_batches_datalist"
                                           required 
                                           placeholder="Contoh: Batch 1 - 2026, Semester Genap..." 
                                           class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                    <datalist id="master_batches_datalist">
                                        <template x-for="mb in masterBatches" :key="'dl-'+mb.id">
                                            <option :value="mb.batch_name"></option>
                                        </template>
                                    </datalist>
                                </div>

                                <!-- Tanggal Mulai & Tanggal Selesai -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">
                                            Tanggal Mulai
                                        </label>
                                        <input type="date" name="start_date" x-model="startDate" required class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">
                                            Tanggal Selesai
                                        </label>
                                        <input type="date" name="end_date" x-model="endDate" required class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                    </div>
                                </div>

                                <!-- Target Jam Kerja -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">
                                        Target Total Jam Kerja (Jam)
                                    </label>
                                    <input type="number" name="target_hours" x-model="targetHours" required min="1" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                                <button type="submit" 
                                        :disabled="selectedUserIds.length === 0" 
                                        :class="selectedUserIds.length === 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'"
                                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                                    <span x-text="selectedUserIds.length > 0 ? 'Terapkan Batch ke ' + selectedUserIds.length + ' Peserta' : 'Pilih Minimal 1 Peserta'"></span>
                                </button>
                            </div>
                        </div>

                    </div>
                </form>

                <!-- Table of Current Active Internship Periods -->
                @if(count($periods) > 0)
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                                Riwayat Batch & Periode Peserta yang Telah Diatur ({{ count($periods) }})
                            </h4>
                        </div>

                        <div class="overflow-x-auto border border-slate-200/80 dark:border-slate-800 rounded-xl">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-slate-50 dark:bg-slate-950/60 uppercase text-[11px] font-semibold text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                                    <tr>
                                        <th class="py-3 px-4">PESERTA MAGANG</th>
                                        <th class="py-3 px-4">NAMA BATCH</th>
                                        <th class="py-3 px-4">PERIODE TANGGAL</th>
                                        <th class="py-3 px-4 text-center">TARGET JAM</th>
                                        <th class="py-3 px-4 text-right">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-normal">
                                    @foreach($periods as $period)
                                        @if($period->intern)
                                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                                <td class="py-3 px-4 whitespace-nowrap">
                                                    <div class="font-bold text-slate-900 dark:text-white text-xs">
                                                        {{ $period->intern->name }}
                                                    </div>
                                                    <div class="text-[10px] text-slate-400 dark:text-slate-500">
                                                        {{ $period->intern->email }}
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-900">
                                                        {{ $period->period_name }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4 whitespace-nowrap">
                                                    {{ $period->start_date ? $period->start_date->format('d M Y') : '-' }} s/d {{ $period->end_date ? $period->end_date->format('d M Y') : '-' }}
                                                </td>
                                                <td class="py-3 px-4 text-center whitespace-nowrap font-bold">
                                                    {{ $period->target_hours }} Jam
                                                </td>
                                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                                    <div class="flex items-center justify-end gap-1.5">
                                                        <button type="button" 
                                                                @click="editInternBatch({
                                                                    id: {{ $period->user_id }},
                                                                    current_batch: '{{ addslashes($period->period_name) }}',
                                                                    start_date: '{{ $period->start_date ? $period->start_date->format('Y-m-d') : '' }}',
                                                                    end_date: '{{ $period->end_date ? $period->end_date->format('Y-m-d') : '' }}',
                                                                    target_hours: {{ $period->target_hours }}
                                                                })"
                                                                title="Ubah Batch" 
                                                                class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-800 transition cursor-pointer">
                                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                        </button>
                                                        <form action="{{ route('mentor.settings.periods.delete', $period->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaturan batch peserta ini?')" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" title="Hapus Batch" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 dark:hover:bg-slate-800 transition cursor-pointer">
                                                                <i class="fa-solid fa-trash text-xs"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>

            <!-- Card 2: Master Hari Libur Nasional & Cuti Bersama Government (Super Admin Master) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-7 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-landmark text-blue-600 dark:text-blue-400 text-xs"></i>
                            2. Master Hari Libur Nasional & Cuti Bersama Pemerintah
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-normal">
                            Daftar libur nasional & cuti bersama resmi diatur terpusat oleh <strong class="text-slate-800 dark:text-slate-200">Super Admin</strong>.
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('mentor.settings.holidays.sync') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs transition flex items-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-arrows-rotate text-[11px]"></i>
                                <span>Sinkronkan API Libur Pemerintah</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 rounded-xl space-y-1.5">
                    <div class="flex items-center gap-2 font-bold text-slate-900 dark:text-white text-xs">
                        <i class="fa-solid fa-shield-halved text-blue-600 dark:text-blue-400"></i>
                        <span>Ketentuan Hari Libur Nasional & Cuti Bersama:</span>
                    </div>
                    <ul class="text-xs text-slate-600 dark:text-slate-400 font-normal leading-relaxed list-disc list-inside space-y-1">
                        <li><strong class="text-slate-800 dark:text-slate-200">Hari Libur Nasional:</strong> Bersifat <em class="text-rose-600 dark:text-rose-400 font-semibold not-italic">wajib dan mutlak libur</em> bagi seluruh peserta magang (terkunci otomatis dan tidak dapat diubah).</li>
                        <li><strong class="text-slate-800 dark:text-slate-200">Cuti Bersama Pemerintah:</strong> Jika operasional perusahaan tetap berjalan, Mentor/HR dapat mengirimkan <strong class="text-blue-600 dark:text-blue-400">Pengajuan Izin Masuk ke Super Admin</strong>.</li>
                    </ul>
                </div>

                <!-- National Holidays Table with HR/Mentor Override Toggle -->
                <div class="overflow-x-auto border border-slate-200/80 dark:border-slate-800 rounded-xl">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-950/60 uppercase text-[11px] font-semibold text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="py-3.5 px-5">Tanggal</th>
                                <th class="py-3.5 px-5">Nama Hari Libur / Cuti Bersama</th>
                                <th class="py-3.5 px-5">Kategori</th>
                                <th class="py-3.5 px-5">Status Bagi Perusahaan</th>
                                <th class="py-3.5 px-5 text-right">Aksi HR / Mentor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-normal">
                            @forelse($nationalHolidays as $holiday)
                                @php
                                    $isOverridden = isset($overrides[$holiday->id]);
                                    $isNationalHoliday = ($holiday->type === 'national_holiday');
                                @endphp
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                    <td class="py-4 px-5 font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                                        {{ $holiday->date->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-5 font-bold text-slate-800 dark:text-slate-200">
                                        {{ $holiday->name }}
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        @if($isNationalHoliday)
                                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold uppercase border bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">
                                                Libur Nasional
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold uppercase border bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900">
                                                Cuti Bersama
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        @if($isNationalHoliday)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-900 rounded-md text-[11px] font-semibold">
                                                <i class="fa-solid fa-lock text-xs"></i> Wajib Libur Nasional
                                            </span>
                                        @else
                                            @if($isOverridden)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 rounded-md text-[11px] font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> Diajukan ke Super Admin
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-md text-[11px] font-medium">
                                                    <i class="fa-solid fa-umbrella-beach text-xs"></i> Ikut Libur Pemerintah
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        @if($isNationalHoliday)
                                            <span class="text-slate-400 dark:text-slate-500 text-xs font-medium italic">
                                                Terkunci (Wajib Libur)
                                            </span>
                                        @else
                                            <form action="{{ route('mentor.settings.holidays.override', $holiday->id) }}" method="POST" class="inline">
                                                @csrf
                                                @if($isOverridden)
                                                    <button type="submit" class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-2xs">
                                                        Batalkan Pengajuan
                                                    </button>
                                                @else
                                                    <button type="submit" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-xs transition inline-flex items-center gap-1.5">
                                                        <i class="fa-solid fa-paper-plane text-[10px]"></i>
                                                        <span>Ajukan ke Super Admin</span>
                                                    </button>
                                                @endif
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500 text-xs">
                                        Belum ada Master Hari Libur Nasional yang didaftarkan Super Admin.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card 3: Tambah Hari Libur Khusus Internal Perusahaan (Dibuat oleh HR / Mentor) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-7 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-calendar-plus text-blue-600 dark:text-blue-400 text-xs"></i>
                            3. Tambah Hari Libur Khusus Internal Perusahaan (Dibuat oleh HR / Mentor)
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-normal">
                            Selain libur nasional pemerintah, HR & Mentor dapat menambahkan hari libur khusus internal (misal: <em>Anniversary Perusahaan</em>, <em>Gathering</em>, dsb).
                        </p>
                    </div>
                    <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 rounded-md text-[11px] font-semibold border border-blue-200/80 dark:border-blue-900 shrink-0">
                        Created by HR / Mentor
                    </span>
                </div>

                <form action="{{ route('mentor.settings.holidays.company.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-slate-50 dark:bg-slate-950/60 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800">
                    @csrf
                    
                    <div class="md:col-span-4">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Tanggal Libur Internal</label>
                        <input type="date" name="date" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100" required>
                    </div>

                    <div class="md:col-span-6">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Nama Hari Libur / Keterangan</label>
                        <input type="text" name="name" placeholder="Contoh: Libur HUT Perusahaan / Family Gathering" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100" required>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>Tambah Libur</span>
                        </button>
                    </div>
                </form>

                <!-- Company Custom Holidays Table -->
                <div class="overflow-x-auto border border-slate-200/80 dark:border-slate-800 rounded-xl">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-950/60 uppercase text-[11px] font-semibold text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="py-3.5 px-5">Tanggal</th>
                                <th class="py-3.5 px-5">Keterangan Hari Libur Internal</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi Hapus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-normal">
                            @forelse($companyHolidays as $cHoliday)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                    <td class="py-4 px-5 font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                                        {{ $cHoliday->date->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-5 font-bold text-slate-800 dark:text-slate-200">
                                        {{ $cHoliday->name }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold uppercase border bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900">
                                            Libur Perusahaan
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 text-right">
                                        <form action="{{ route('mentor.settings.holidays.company.delete', $cHoliday->id) }}" method="POST" onsubmit="return confirm('Hapus hari libur internal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900 rounded-lg text-xs font-semibold hover:bg-rose-100 dark:hover:bg-rose-900/60 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400 dark:text-slate-500 text-xs">
                                        Belum ada Hari Libur Khusus Internal Perusahaan yang ditambahkan oleh HR / Mentor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Master Batch Create / Edit Modal -->
        <div x-show="masterBatchModal" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" 
                 @click="masterBatchModal = false"></div>

            <div class="min-h-full flex items-center justify-center p-4">
                <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 p-6 space-y-5"
                     @click.outside="masterBatchModal = false">
                    
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white" x-text="masterBatchForm.id ? 'Edit Master Batch' : 'Buat Master Batch Baru'"></h3>
                        </div>
                        <button type="button" @click="masterBatchModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-sm">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('mentor.settings.master-batches.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="id" :value="masterBatchForm.id">

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Nama Batch / Periode <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="batch_name" 
                                   x-model="masterBatchForm.batch_name" 
                                   placeholder="Contoh: Batch 1 - Semester Genap 2026"
                                   required 
                                   class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Tanggal Mulai
                                </label>
                                <input type="date" 
                                       name="start_date" 
                                       x-model="masterBatchForm.start_date" 
                                       class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Tanggal Selesai
                                </label>
                                <input type="date" 
                                       name="end_date" 
                                       x-model="masterBatchForm.end_date" 
                                       class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Target Jam Kerja
                                </label>
                                <input type="number" 
                                       name="target_hours" 
                                       x-model="masterBatchForm.target_hours" 
                                       min="1" 
                                       placeholder="400"
                                       class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Status Batch
                                </label>
                                <select name="status" 
                                        x-model="masterBatchForm.status"
                                        class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                                    <option value="active">Aktif</option>
                                    <option value="draft">Draf (Persiapan)</option>
                                    <option value="closed">Ditutup</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Deskripsi / Catatan Batch
                            </label>
                            <textarea name="description" 
                                      x-model="masterBatchForm.description" 
                                      rows="2" 
                                      placeholder="Keterangan tambahan terkait periode atau ketentuan batch ini..."
                                      class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-purple-500 focus:border-purple-500"></textarea>
                        </div>

                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5">
                            <button type="button" 
                                    @click="masterBatchModal = false" 
                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-xl shadow-xs transition flex items-center gap-1.5">
                                <i class="fa-solid fa-save text-xs"></i>
                                <span x-text="masterBatchForm.id ? 'Simpan Perubahan' : 'Buat Batch'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
