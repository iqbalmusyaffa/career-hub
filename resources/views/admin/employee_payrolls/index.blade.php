<x-app-layout>
    <div class="py-8" x-data="{
        statusModalOpen: false,
        activePayroll: null,
        payrollStatus: 'published',
        payrollNotes: '',
        openStatus(p) {
            this.activePayroll = p;
            this.payrollStatus = p.status;
            this.payrollNotes = p.notes || '';
            this.statusModalOpen = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumbs & Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Admin</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">Karyawan</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-blue-600 dark:text-blue-400 font-semibold">Payroll & Slip Gaji</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                                Rekapitulasi & Payroll Karyawan Tetap/Kontrak
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                                Sinkronisasi otomatis jam kerja, keterlambatan, lembur, reimbursement, dan perhitungan slip gaji bulanan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons: Generate & Export -->
                <div class="flex items-center gap-2.5 flex-wrap">
                    <form method="POST" action="{{ route('admin.employee-payrolls.generate') }}">
                        @csrf
                        <input type="hidden" name="period_month" value="{{ $selectedMonth }}">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-xs transition cursor-pointer">
                            <i class="fa-solid fa-arrows-rotate"></i>
                            <span>Generate & Sinkronkan Payroll</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.employee-payrolls.bulk-transfer') }}" onsubmit="return confirm('Tandai seluruh slip gaji periode ini telah ditransfer?')">
                        @csrf
                        <input type="hidden" name="period_month" value="{{ $selectedMonth }}">
                        <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition">
                            <i class="fa-solid fa-check-double"></i>
                            <span>Transfer Massal</span>
                        </button>
                    </form>

                    <a href="{{ route('admin.employee-payrolls.export-csv', ['month' => $selectedMonth]) }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 shadow-2xs transition">
                        <i class="fa-solid fa-file-csv text-emerald-500"></i>
                        <span>Export CSV</span>
                    </a>
                </div>
            </div>

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl shadow-xs flex items-center justify-between text-xs font-medium transition">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            <!-- 4 Bento Metric Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Karyawan</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">{{ $totalPayrolls }} <span class="text-xs font-medium text-slate-400">Orang</span></div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-emerald-200 dark:border-emerald-800/60 shadow-xs bg-emerald-50/20 dark:bg-emerald-950/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Total Pengeluaran Gaji</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-xl sm:text-2xl font-bold text-emerald-700 dark:text-emerald-300">Rp {{ number_format($totalNetPayout, 0, ',', '.') }}</div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-indigo-200 dark:border-indigo-800/60 shadow-xs bg-indigo-50/20 dark:bg-indigo-950/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-400">Total Kompensasi Lembur</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-business-time"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-xl sm:text-2xl font-bold text-indigo-700 dark:text-indigo-300">Rp {{ number_format($totalOvertimePay, 0, ',', '.') }}</div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Pencairan</span>
                        <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-sm font-bold text-slate-800 dark:text-slate-200">
                        <span class="text-emerald-600 font-extrabold">{{ $paidCount }} Ditransfer</span> &bull; 
                        <span class="text-slate-500 font-normal">{{ $draftCount }} Draf</span>
                    </div>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="p-4 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
                <form method="GET" action="{{ route('admin.employee-payrolls.index') }}" class="flex items-center gap-2 flex-wrap flex-1">
                    <input type="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()"
                           class="text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 font-semibold">

                    <div class="relative min-w-[200px]">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama karyawan..."
                               class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 pl-8 pr-3 py-2">
                        <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
                    </div>

                    <select name="status" onchange="this.form.submit()" class="text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 font-medium">
                        <option value="all" {{ $selectedStatus === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="draft" {{ $selectedStatus === 'draft' ? 'selected' : '' }}>Draf Payroll</option>
                        <option value="published" {{ $selectedStatus === 'published' ? 'selected' : '' }}>Siap / Terbit</option>
                        <option value="paid" {{ $selectedStatus === 'paid' ? 'selected' : '' }}>Telah Ditransfer</option>
                    </select>

                    <button type="submit" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-2xs">
                        Filter
                    </button>
                    @if($search || $selectedStatus !== 'all')
                        <a href="{{ route('admin.employee-payrolls.index', ['month' => $selectedMonth]) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table of Payrolls -->
            <div class="bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs overflow-hidden">
                @if($payrolls->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                                    <th class="p-4 font-semibold">Karyawan & No. Slip</th>
                                    <th class="p-4 font-semibold">Gaji Pokok & Tunjangan</th>
                                    <th class="p-4 font-semibold">Lembur & Klaim</th>
                                    <th class="p-4 font-semibold">Potongan (BPJS/Pajak)</th>
                                    <th class="p-4 font-semibold">Take Home Pay</th>
                                    <th class="p-4 font-semibold">Rekening Bank</th>
                                    <th class="p-4 font-semibold">Status</th>
                                    <th class="p-4 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-300">
                                @foreach($payrolls as $p)
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="font-bold text-slate-900 dark:text-white">{{ $p->user->name }}</div>
                                            <span class="font-mono text-[10px] text-slate-400 block">{{ $p->payroll_number }}</span>
                                            <span class="text-[11px] text-indigo-600 dark:text-indigo-400 font-semibold">{{ $p->job_title }}</span>
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="font-mono font-bold text-slate-800 dark:text-slate-200">Rp {{ number_format($p->base_salary, 0, ',', '.') }}</div>
                                            <div class="text-[10px] text-slate-400">+ Tunj: Rp {{ number_format($p->allowance_fixed + $p->allowance_transport, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="font-mono text-emerald-600 dark:text-emerald-400 font-bold">+ Rp {{ number_format($p->overtime_pay + $p->reimbursement_pay, 0, ',', '.') }}</div>
                                            <div class="text-[10px] text-slate-400">Lembur: {{ $p->overtime_hours }} jam</div>
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="font-mono text-rose-600 dark:text-rose-400 font-bold">- Rp {{ number_format($p->total_deductions, 0, ',', '.') }}</div>
                                            <div class="text-[10px] text-slate-400">BPJS & Telat ({{ $p->late_days }}x)</div>
                                        </td>
                                        <td class="p-4 whitespace-nowrap font-mono font-extrabold text-sm text-blue-600 dark:text-blue-400">
                                            Rp {{ number_format($p->net_salary, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <span class="font-semibold block text-slate-800 dark:text-slate-200">{{ $p->bank_name }}</span>
                                            <span class="font-mono text-[10px] text-slate-400">{{ $p->bank_account_number }}</span>
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $p->status_badge['class'] }}">
                                                <i class="fa-solid {{ $p->status_badge['icon'] }} text-[11px]"></i>
                                                <span>{{ $p->status_badge['text'] }}</span>
                                            </span>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-right space-x-1.5">
                                            <button type="button" @click="openStatus({{ json_encode($p) }})"
                                                    class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-semibold transition">
                                                Ubah Status
                                            </button>
                                            <a href="{{ route('admin.employee-payrolls.slip', $p->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 rounded-lg text-xs font-semibold transition">
                                                <i class="fa-solid fa-file-pdf"></i>
                                                <span>Slip</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                        {{ $payrolls->links() }}
                    </div>
                @else
                    <div class="p-12 text-center space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 mx-auto flex items-center justify-center text-xl">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white">Belum Ada Data Payroll Periode Ini</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">
                            Klik tombol "Generate & Sinkronkan Payroll" di atas untuk secara otomatis mengompilasi gaji seluruh karyawan aktif periode {{ $monthDate->translatedFormat('F Y') }}.
                        </p>
                    </div>
                @endif
            </div>

        </div>

        <!-- MODAL UPDATE STATUS PAYROLL -->
        <div x-show="statusModalOpen" x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/75 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="statusModalOpen = false" class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Ubah Status Slip Gaji</h3>
                    <button type="button" @click="statusModalOpen = false" class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <template x-if="activePayroll">
                    <form method="POST" :action="'{{ url('/admin/employee-payrolls') }}/' + activePayroll.id + '/status'" class="space-y-4">
                        @csrf

                        <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl space-y-1 text-xs">
                            <div class="text-slate-500">Karyawan: <strong class="text-slate-900 dark:text-white" x-text="activePayroll.user.name"></strong></div>
                            <div class="text-slate-500">No. Slip: <span class="font-mono" x-text="activePayroll.payroll_number"></span></div>
                            <div class="text-slate-500">Take Home Pay: <span class="font-bold font-mono text-emerald-600 dark:text-emerald-400" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(activePayroll.net_salary)"></span></div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Status Slip Gaji <span class="text-rose-500">*</span></label>
                            <select name="status" x-model="payrollStatus" required
                                    class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-2.5 font-bold">
                                <option value="draft">Draf Payroll (Belum Terbit)</option>
                                <option value="published">Siap / Terbit (Bisa Diunduh Karyawan)</option>
                                <option value="paid">Telah Ditransfer / Lunas</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Catatan Khusus (Opsional)</label>
                            <textarea name="notes" rows="2.5" x-model="payrollNotes" placeholder="Catatan internal tim HR/Finance..."
                                      class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-2.5"></textarea>
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-2.5">
                            <button type="button" @click="statusModalOpen = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-xs">
                                Simpan Status
                            </button>
                        </div>
                    </form>
                </template>

            </div>
        </div>
    </div>
</x-app-layout>
