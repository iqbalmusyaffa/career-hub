<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-800/60">
                        <i class="fa-solid fa-coins text-[10px]"></i>
                        Planning & Budgeting
                    </span>
                    <span class="text-xs text-slate-300 dark:text-slate-600">•</span>
                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Headcount Allocation FY {{ $fiscalYear }}</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight">
                    Headcount Budget & Recruitment Planning Hub
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Perencanaan kuota target karyawan baru dan alokasi anggaran rekrutmen per divisi perusahaan.
                </p>
            </div>

            <!-- Fiscal Year Filter & Quick Actions -->
            <div class="flex items-center gap-2 flex-wrap">
                <form method="GET" action="{{ route('admin.headcount-budgets.index') }}" class="flex items-center gap-1.5 bg-white dark:bg-slate-800 p-1 rounded-xl border border-slate-200/80 dark:border-slate-700 shadow-2xs">
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 pl-2 pr-1">Tahun:</span>
                    @foreach([2024, 2025, 2026, 2027] as $year)
                        <button type="submit" name="fiscal_year" value="{{ $year }}" 
                            class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-colors {{ $fiscalYear == $year ? 'bg-emerald-600 text-white shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            {{ $year }}
                        </button>
                    @endforeach
                </form>

                <a href="#form-allocation" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-2xs transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Target</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 min-h-screen bg-slate-50/60 dark:bg-slate-900" x-data="{
        editMode: false,
        division: '',
        fiscal_year: '{{ $fiscalYear }}',
        target_headcount: '',
        allocated_budget: '',
        notes: '',
        setEdit(stat) {
            this.editMode = true;
            this.division = stat.division;
            this.fiscal_year = '{{ $fiscalYear }}';
            this.target_headcount = stat.target_headcount;
            this.allocated_budget = stat.allocated_budget;
            this.notes = stat.notes || '';
            document.getElementById('form-allocation').scrollIntoView({ behavior: 'smooth' });
        },
        resetForm() {
            this.editMode = false;
            this.division = '';
            this.fiscal_year = '{{ $fiscalYear }}';
            this.target_headcount = '';
            this.allocated_budget = '';
            this.notes = '';
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert Notification -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center justify-between text-xs font-medium shadow-2xs">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:hover:text-emerald-200 text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            <!-- Top Scorecards Metric Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Target Headcount Total -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-2xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Target Headcount Total</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight flex items-baseline gap-1.5">
                            {{ number_format($totalTargetHeadcount) }}
                            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Orang</span>
                        </div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                            Total kuota dari <strong class="text-slate-700 dark:text-slate-300">{{ count($budgetStats) }} divisi</strong>
                        </div>
                    </div>
                </div>

                <!-- Realisasi Diterima (Hired) -->
                @php
                    $overallPercentage = $totalTargetHeadcount > 0 ? round(($totalActualHired / $totalTargetHeadcount) * 100, 1) : 0;
                    $totalRemaining = max(0, $totalTargetHeadcount - $totalActualHired);
                @endphp
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-2xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Realisasi Diterima (Hired)</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 tracking-tight flex items-baseline gap-1.5">
                            {{ number_format($totalActualHired) }}
                            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Orang</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] mt-1">
                            <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $overallPercentage }}% Tercapai</span>
                            <span class="text-slate-400 dark:text-slate-500">{{ $totalActualHired }}/{{ $totalTargetHeadcount }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden mt-1.5">
                            <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ min(100, max(3, $overallPercentage)) }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Sisa Kuota Rekrutmen -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-2xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Sisa Kuota Rekrutmen</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 tracking-tight flex items-baseline gap-1.5">
                            {{ number_format($totalRemaining) }}
                            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Posisi</span>
                        </div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                            Kebutuhan kandidat yang belum terpenuhi
                        </div>
                    </div>
                </div>

                <!-- Total Anggaran Dialokasikan -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-2xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Anggaran (FY {{ $fiscalYear }})</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 tracking-tight">
                            Rp {{ number_format($totalAllocatedBudget, 0, ',', '.') }}
                        </div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                            @if($totalTargetHeadcount > 0)
                                Rata-rata: Rp {{ number_format($totalAllocatedBudget / $totalTargetHeadcount, 0, ',', '.') }}/hire
                            @else
                                Belum ada alokasi budget
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Form: Tambah / Atur Target & Budget Divisi -->
            <div id="form-allocation" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-2xs p-5 sm:p-6 transition-all">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 dark:border-slate-700/60 pb-4 mb-5 gap-2">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white" x-text="editMode ? 'Perbarui Target & Anggaran Divisi' : 'Alokasi Target & Anggaran Divisi'">
                                Alokasi Target & Anggaran Divisi
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">
                                Atur kuota rekrutmen dan alokasi dana per divisi untuk tahun fiskal berjalan.
                            </p>
                        </div>
                    </div>

                    <template x-if="editMode">
                        <button type="button" @click="resetForm()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg transition">
                            <i class="fa-solid fa-rotate-left text-xs"></i>
                            Batal Edit
                        </button>
                    </template>
                </div>

                <form action="{{ route('admin.headcount-budgets.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Divisi Perusahaan -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Divisi Perusahaan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="division" x-model="division" list="division_options" required 
                                placeholder="Pilih atau ketik divisi..." 
                                class="w-full rounded-xl text-xs font-medium text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 py-2.5 px-3">
                            <datalist id="division_options">
                                <option value="IT & Engineering">
                                <option value="Product & Design">
                                <option value="Marketing & Growth">
                                <option value="Finance & Accounting">
                                <option value="Human Resources">
                                <option value="Sales & Business Development">
                                <option value="Operations & Supply Chain">
                                <option value="Data & AI">
                                <option value="Legal & Compliance">
                                <option value="Customer Support">
                            </datalist>
                        </div>

                        <!-- Tahun Fiskal -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Tahun Fiskal <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="fiscal_year" x-model="fiscal_year" min="2024" max="2030" required 
                                class="w-full rounded-xl text-xs font-medium text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 py-2.5 px-3">
                        </div>

                        <!-- Target Headcount -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Target Headcount <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="target_headcount" x-model="target_headcount" min="1" required 
                                    placeholder="Contoh: 5" 
                                    class="w-full rounded-xl text-xs font-medium text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 py-2.5 pl-3 pr-12">
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-slate-400 pointer-events-none font-medium">
                                    Orang
                                </span>
                            </div>
                        </div>

                        <!-- Alokasi Budget -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Alokasi Anggaran (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-slate-400 pointer-events-none font-semibold">
                                    Rp
                                </span>
                                <input type="number" name="allocated_budget" x-model="allocated_budget" min="0" step="500000" required 
                                    placeholder="150000000" 
                                    class="w-full rounded-xl text-xs font-medium text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 py-2.5 pl-9 pr-3">
                            </div>
                        </div>
                    </div>

                    <!-- Notes / Keterangan Opsional & Submit Button Row -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between pt-2 gap-3">
                        <div class="flex-1 sm:max-w-md">
                            <input type="text" name="notes" x-model="notes" placeholder="Catatan / keterangan alokasi (opsional)..." 
                                class="w-full rounded-xl text-xs font-normal text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 py-2 px-3">
                        </div>

                        <button type="submit" 
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-2xs transition">
                            <i class="fa-solid fa-floppy-disk text-xs"></i>
                            <span x-text="editMode ? 'Perbarui Alokasi' : 'Simpan Alokasi'">Simpan Alokasi</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Progress Headcount Realisasi -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-2xs overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-700/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-chart-simple"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white">
                                Monitoring Realisasi Headcount per Divisi
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">
                                Evaluasi pemenuhan target kuota dan penyerapan anggaran rekrutmen tahun fiskal {{ $fiscalYear }}.
                            </p>
                        </div>
                    </div>

                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 dark:bg-slate-700/70 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-lg self-start sm:self-auto">
                        <i class="fa-regular fa-building text-[10px]"></i>
                        {{ count($budgetStats) }} Divisi Terdaftar
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 dark:bg-slate-900/40 border-b border-slate-200/80 dark:border-slate-700 text-[11px] font-semibold uppercase text-slate-500 dark:text-slate-400 tracking-wider">
                                <th class="py-3.5 px-4">Divisi Perusahaan</th>
                                <th class="py-3.5 px-4 text-center">Target</th>
                                <th class="py-3.5 px-4 text-center">Realisasi (Hired)</th>
                                <th class="py-3.5 px-4 text-center">Sisa Kuota</th>
                                <th class="py-3.5 px-4 min-w-[180px]">Progress Pemenuhan</th>
                                <th class="py-3.5 px-4 text-right">Alokasi Anggaran</th>
                                <th class="py-3.5 px-4 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs">
                            @forelse($budgetStats as $stat)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/30 transition group">
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ substr($stat['division'], 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900 dark:text-white">
                                                    {{ $stat['division'] }}
                                                </div>
                                                @if(!empty($stat['notes']))
                                                    <div class="text-[11px] text-slate-400 dark:text-slate-500 font-normal">
                                                        {{ $stat['notes'] }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                        {{ $stat['target_headcount'] }} <span class="text-[11px] font-normal text-slate-400">org</span>
                                    </td>

                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                            {{ $stat['actual_hired'] }}
                                        </span>
                                    </td>

                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        @if($stat['remaining'] == 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400">
                                                Terpenuhi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/60">
                                                {{ $stat['remaining'] }} <span class="text-[10px] font-normal">sisa</span>
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-3.5 px-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center justify-between text-[11px]">
                                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $stat['percentage'] }}%</span>
                                                <span class="text-slate-400 dark:text-slate-500">{{ $stat['actual_hired'] }} dari {{ $stat['target_headcount'] }}</span>
                                            </div>
                                            <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-500 {{ $stat['percentage'] >= 100 ? 'bg-emerald-500' : ($stat['percentage'] >= 50 ? 'bg-blue-500' : ($stat['percentage'] > 0 ? 'bg-amber-500' : 'bg-slate-300 dark:bg-slate-600')) }}" 
                                                    style="width: {{ min(100, max($stat['percentage'] > 0 ? 5 : 0, $stat['percentage'])) }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-3.5 px-4 text-right whitespace-nowrap font-bold text-slate-900 dark:text-white">
                                        Rp {{ number_format($stat['allocated_budget'], 0, ',', '.') }}
                                    </td>

                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1">
                                            <button type="button" @click="setEdit({{ json_encode($stat) }})" 
                                                class="w-7 h-7 inline-flex items-center justify-center rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 transition" 
                                                title="Edit Alokasi">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </button>

                                            <form action="{{ route('admin.headcount-budgets.destroy', $stat['id']) }}" method="POST" onsubmit="return confirm('Hapus alokasi headcount divisi {{ $stat['division'] }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="w-7 h-7 inline-flex items-center justify-center rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-slate-700 transition" 
                                                    title="Hapus Divisi">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 px-4 text-center">
                                        <div class="max-w-xs mx-auto text-center space-y-2">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto text-lg">
                                                <i class="fa-solid fa-layer-group"></i>
                                            </div>
                                            <div class="font-bold text-sm text-slate-800 dark:text-slate-200">
                                                Belum Ada Alokasi Divisi (FY {{ $fiscalYear }})
                                            </div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                                Gunakan formulir di atas untuk mulai menambahkan kuota target headcount dan anggaran rekrutmen.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
