<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-blue-600"></i> HR Custom Report Builder
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Rancang laporan kustom, pilih kolom data, dan ekspor ke Excel, CSV, atau PDF.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-blue-50 text-blue-800 text-xs font-black rounded-xl border border-blue-200">
                    <i class="fa-solid fa-database mr-1"></i> Data Real-Time
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Report Configuration Form Card -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xs border border-slate-200/80">
                <form id="reportForm" method="GET" action="{{ route('admin.reports.builder.index') }}" class="space-y-6">
                    
                    <!-- DOMAIN SELECTION & DATE RANGE -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs">
                        <div>
                            <label class="block font-black text-slate-800 uppercase mb-1.5 text-3xs tracking-wider">1. Domain Data Laporan</label>
                            <select name="domain" onchange="this.form.submit()" class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-blue-500 focus:border-blue-500 bg-slate-50">
                                <option value="applications" {{ $domain === 'applications' ? 'selected' : '' }}>👥 Pelamar & Pipeline Rekrutmen</option>
                                <option value="jobs" {{ $domain === 'jobs' ? 'selected' : '' }}>💼 Lowongan Pekerjaan & Performa</option>
                                <option value="scorecards" {{ $domain === 'scorecards' ? 'selected' : '' }}>⭐ Evaluasi Wawancara & Scorecard</option>
                                <option value="headcount" {{ $domain === 'headcount' ? 'selected' : '' }}>💰 Headcount Budget & Target Hiring</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-black text-slate-800 uppercase mb-1.5 text-3xs tracking-wider">2. Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full border-slate-300 rounded-xl text-xs font-medium focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block font-black text-slate-800 uppercase mb-1.5 text-3xs tracking-wider">3. Tanggal Selesai</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full border-slate-300 rounded-xl text-xs font-medium focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- COLUMN SELECTION CHECKBOXES -->
                    <div class="border-t border-slate-100 pt-5">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block font-black text-slate-800 uppercase text-3xs tracking-wider">
                                4. Pilih Kolom Data yang Ingin Ditampilkan / Diekspor
                            </label>
                            <div class="flex items-center gap-3 text-xs">
                                <button type="button" id="selectAllCols" class="text-blue-600 font-extrabold hover:underline">Pilih Semua</button>
                                <span class="text-slate-300">|</span>
                                <button type="button" id="deselectAllCols" class="text-slate-500 font-bold hover:underline">Hapus Semua</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-xs">
                            @foreach($availableColumns as $key => $label)
                                <label class="flex items-center gap-2 font-semibold text-slate-700 cursor-pointer select-none hover:text-slate-900">
                                    <input type="checkbox" name="columns[]" value="{{ $key }}" class="col-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500" {{ in_array($key, $selectedColumnKeys) ? 'checked' : '' }}>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100 pt-5">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-slate-900 hover:bg-black text-white text-xs font-extrabold rounded-xl transition shadow-md flex items-center justify-center gap-2 border border-slate-900">
                            <i class="fa-solid fa-eye text-amber-400"></i> Preview Tabel Laporan
                        </button>

                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                            <button type="submit" formaction="{{ route('admin.reports.builder.export') }}" name="format" value="excel" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition shadow-2xs flex items-center gap-1.5 border border-emerald-600">
                                <i class="fa-solid fa-file-excel"></i> Export Excel (.xlsx)
                            </button>

                            <button type="submit" formaction="{{ route('admin.reports.builder.export') }}" name="format" value="csv" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold rounded-xl transition shadow-2xs flex items-center gap-1.5 border border-blue-600">
                                <i class="fa-solid fa-file-csv"></i> Export CSV
                            </button>

                            <button type="submit" formaction="{{ route('admin.reports.builder.export') }}" name="format" value="pdf" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl transition shadow-2xs flex items-center gap-1.5 border border-rose-600">
                                <i class="fa-solid fa-file-pdf"></i> Export PDF Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- PREVIEW DATA TABLE -->
            <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                            <i class="fa-solid fa-table text-emerald-600"></i> Preview Hasil Laporan Kustom
                        </h3>
                        <p class="text-3xs text-slate-500 mt-0.5">Menampilkan {{ $reportData->count() }} data teratas sesuai filter rentang tanggal dan domain terpilih.</p>
                    </div>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 text-3xs font-black rounded-lg uppercase tracking-wider">
                        {{ count($selectedColumns) }} Kolom Terpilih
                    </span>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200/80 text-slate-500 text-3xs font-extrabold uppercase tracking-wider">
                                    @foreach($selectedColumns as $colKey => $colLabel)
                                        <th class="p-4">{{ $colLabel }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @forelse($reportData as $row)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        @foreach($selectedColumns as $colKey => $colLabel)
                                            <td class="p-4 font-semibold text-slate-800">
                                                @if(str_contains($colKey, 'status'))
                                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-800 text-3xs font-black rounded-md border border-blue-200">
                                                        {{ data_get($row, $colKey, '-') }}
                                                    </span>
                                                @elseif(str_contains($colKey, 'score') || str_contains($colKey, 'percentage'))
                                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 text-3xs font-black rounded-md border border-emerald-200">
                                                        {{ data_get($row, $colKey, '-') }}
                                                    </span>
                                                @else
                                                    {{ data_get($row, $colKey, '-') }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($selectedColumns) ?: 1 }}" class="p-10 text-center text-slate-400 font-medium">
                                            Tidak ada data laporan yang memenuhi kriteria filter tanggal dan domain.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllBtn = document.getElementById('selectAllCols');
            const deselectAllBtn = document.getElementById('deselectAllCols');
            const checkboxes = document.querySelectorAll('.col-checkbox');

            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', function() {
                    checkboxes.forEach(cb => cb.checked = true);
                });
            }

            if (deselectAllBtn) {
                deselectAllBtn.addEventListener('click', function() {
                    checkboxes.forEach(cb => cb.checked = false);
                });
            }
        });
    </script>
</x-app-layout>
