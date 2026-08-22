<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-xl text-slate-800 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-coins text-emerald-600"></i> Headcount Budget & Recruitment Planning Hub
                </h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Perencanaan kuota target karyawan baru dan anggaran rekrutmen per divisi perusahaan.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Cards KPI Makro Perusahaan -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wide">Target Headcount Total</div>
                    <div class="text-3xl font-black text-slate-900 mt-2 flex items-baseline gap-2">
                        {{ number_format($totalTargetHeadcount) }} <span class="text-xs font-extrabold text-slate-400">Orang</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wide">Realisasi Diterima (Hired)</div>
                    <div class="text-3xl font-black text-emerald-600 mt-2 flex items-baseline gap-2">
                        {{ number_format($totalActualHired) }} <span class="text-xs font-extrabold text-slate-400">Orang</span>
                    </div>
                    <div class="text-3xs font-extrabold text-emerald-600 mt-1">
                        {{ $totalTargetHeadcount > 0 ? round(($totalActualHired / $totalTargetHeadcount) * 100, 1) : 0 }}% Tercapai
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wide">Total Dialokasikan (FY {{ $fiscalYear }})</div>
                    <div class="text-3xl font-black text-blue-600 mt-2">
                        Rp {{ number_format($totalAllocatedBudget, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Form Tambah Target & Budget Divisi -->
            <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 p-6 sm:p-8 space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-black text-base text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-plus-circle text-emerald-600"></i> Alokasi Target & Budget Divisi Baru
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium">Tentukan nama divisi, kuota target rekrutmen, dan alokasi anggaran tahun fiskal.</p>
                </div>

                <form action="{{ route('admin.headcount-budgets.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Divisi Perusahaan <span class="text-rose-500">*</span></label>
                            <select name="division" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-emerald-500 focus:border-emerald-500 font-bold p-3">
                                <option value="IT & Engineering">IT & Engineering</option>
                                <option value="Product & Design">Product & Design</option>
                                <option value="Marketing & Growth">Marketing & Growth</option>
                                <option value="Finance & Accounting">Finance & Accounting</option>
                                <option value="Human Resources">Human Resources</option>
                                <option value="Sales & Business Dev">Sales & Business Dev</option>
                                <option value="Operations & Supply Chain">Operations & Supply Chain</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Fiskal <span class="text-rose-500">*</span></label>
                            <input type="number" name="fiscal_year" value="2026" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-emerald-500 focus:border-emerald-500 font-bold p-3">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Target Headcount (Orang) <span class="text-rose-500">*</span></label>
                            <input type="number" name="target_headcount" min="1" required placeholder="Misal: 5" class="w-full border-slate-300 rounded-xl text-xs focus:ring-emerald-500 focus:border-emerald-500 font-bold p-3">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Alokasi Budget (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="allocated_budget" min="0" step="100000" required placeholder="Misal: 150000000" class="w-full border-slate-300 rounded-xl text-xs focus:ring-emerald-500 focus:border-emerald-500 font-bold p-3">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-7 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                            <i class="fa-solid fa-save"></i> Simpan Target Headcount & Budget
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Progress Headcount Realisasi -->
            <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-chart-simple text-emerald-600"></i> Monitoring Realisasi Headcount per Divisi (FY {{ $fiscalYear }})
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-3xs font-extrabold uppercase text-slate-500 tracking-wider">
                                <th class="p-4">Divisi Perusahaan</th>
                                <th class="p-4 text-center">Target</th>
                                <th class="p-4 text-center">Tercapai (Hired)</th>
                                <th class="p-4 text-center">Sisa Kuota</th>
                                <th class="p-4">Progress Realisasi</th>
                                <th class="p-4 text-right">Alokasi Budget</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($budgetStats as $stat)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="font-extrabold text-slate-900 text-sm">{{ $stat['division'] }}</div>
                                    </td>
                                    <td class="p-4 text-center font-black text-slate-900 whitespace-nowrap">
                                        {{ $stat['target_headcount'] }} Orang
                                    </td>
                                    <td class="p-4 text-center font-black text-emerald-600 whitespace-nowrap">
                                        {{ $stat['actual_hired'] }} Orang
                                    </td>
                                    <td class="p-4 text-center font-bold text-amber-600 whitespace-nowrap">
                                        {{ $stat['remaining'] }} Orang
                                    </td>
                                    <td class="p-4">
                                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden border border-slate-200/80">
                                            <div class="bg-emerald-500 h-3 rounded-full transition-all duration-500" style="width: {{ $stat['percentage'] }}%;"></div>
                                        </div>
                                        <div class="text-3xs font-bold text-slate-500 mt-1 flex justify-between">
                                            <span>{{ $stat['percentage'] }}% Terpenuhi</span>
                                            <span>{{ $stat['actual_hired'] }}/{{ $stat['target_headcount'] }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-right whitespace-nowrap font-extrabold text-slate-900">
                                        Rp {{ number_format($stat['allocated_budget'], 0, ',', '.') }}
                                    </td>
                                    <td class="p-4 text-right whitespace-nowrap">
                                        <form action="{{ route('admin.headcount-budgets.destroy', $stat['id']) }}" method="POST" onsubmit="return confirm('Hapus alokasi budget divisi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-rose-50 hover:text-rose-700 text-slate-600 font-bold text-3xs rounded-xl border border-slate-300 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-400 font-medium">
                                        Belum ada alokasi target headcount & budget yang diset untuk tahun fiskal {{ $fiscalYear }}.
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
