<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Employee Self-Service (ESS)</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">
                    E-Slip Gaji Karyawan
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Rincian penghasilan take-home pay, upah lembur, tunjangan, dan potongan BPJS/Pajak bulanan Anda.
                </p>
            </div>

            @if($activePayroll)
                <a href="{{ route('employee.payrolls.slip', $activePayroll->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                    <i class="fa-solid fa-file-pdf text-xs"></i>
                    <span>Unduh Slip Gaji PDF</span>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            @if(session('info'))
                <div class="p-4 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 rounded-2xl shadow-xs flex items-center justify-between text-xs font-medium transition">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-blue-600 dark:text-blue-400 text-base shrink-0"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                </div>
            @endif

            @if($activePayroll)
                <!-- ACTIVE PAYSLIP FEATURED CARD -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
                    
                    <!-- Header Banner -->
                    <div class="p-6 sm:p-7 bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-700 text-white flex flex-col md:flex-row md:items-center justify-between gap-5">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white uppercase tracking-wider">
                                    {{ $activePayroll->payroll_number }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-400 text-slate-950 uppercase tracking-wider">
                                    {{ $activePayroll->status_label }}
                                </span>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-black tracking-tight mt-1">
                                Slip Gaji Periode {{ $activePayroll->period_label }}
                            </h3>
                            <p class="text-xs text-blue-100">
                                Jabatan: <strong>{{ $activePayroll->job_title }}</strong> &bull; {{ $activePayroll->employment_type_label }}
                            </p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-right min-w-[220px]">
                            <span class="text-[11px] font-semibold text-blue-200 block uppercase">Take Home Pay (Gaji Bersih)</span>
                            <div class="text-2xl sm:text-3xl font-black font-mono tracking-tight mt-0.5">
                                Rp {{ number_format($activePayroll->net_salary, 0, ',', '.') }}
                            </div>
                            <span class="text-[10px] text-blue-200 block mt-1">
                                Transfer ke {{ $activePayroll->bank_name }} ({{ $activePayroll->bank_account_number }})
                            </span>
                        </div>
                    </div>

                    <!-- Attendance & Overtime Metric Badges -->
                    <div class="px-6 py-3.5 bg-slate-50 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800 flex items-center gap-4 flex-wrap text-xs text-slate-600 dark:text-slate-300">
                        <div class="flex items-center gap-1.5 font-semibold">
                            <i class="fa-solid fa-calendar-check text-blue-500"></i>
                            <span>Hadir: <strong class="text-slate-900 dark:text-white">{{ $activePayroll->present_days }} Hari</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5 font-semibold">
                            <i class="fa-solid fa-clock text-amber-500"></i>
                            <span>Terlambat: <strong class="text-slate-900 dark:text-white">{{ $activePayroll->late_days }} Hari</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5 font-semibold">
                            <i class="fa-solid fa-business-time text-indigo-500"></i>
                            <span>Lembur: <strong class="text-slate-900 dark:text-white">{{ $activePayroll->overtime_hours }} Jam</strong></span>
                        </div>
                        @if($activePayroll->payment_date)
                            <div class="flex items-center gap-1.5 font-semibold ml-auto text-emerald-600 dark:text-emerald-400">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Tanggal Pencairan: {{ $activePayroll->payment_date->translatedFormat('d F Y') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- 2 Column Breakdown: Earnings vs Deductions -->
                    <div class="p-6 sm:p-7 grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- 1. Penerimaan -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>Penerimaan (Earnings)</span>
                                </h4>
                                <span class="text-xs font-bold text-slate-400">Nominal</span>
                            </div>

                            <div class="space-y-2.5 text-xs text-slate-600 dark:text-slate-300">
                                <div class="flex justify-between items-center">
                                    <span>Gaji Pokok (Basic Salary)</span>
                                    <span class="font-mono font-bold text-slate-900 dark:text-white">Rp {{ number_format($activePayroll->base_salary, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span>Tunjangan Tetap / Jabatan</span>
                                    <span class="font-mono font-bold text-slate-900 dark:text-white">Rp {{ number_format($activePayroll->allowance_fixed, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span>Tunjangan Transport & Makan</span>
                                    <span class="font-mono font-bold text-slate-900 dark:text-white">Rp {{ number_format($activePayroll->allowance_transport, 0, ',', '.') }}</span>
                                </div>
                                @if($activePayroll->overtime_pay > 0)
                                <div class="flex justify-between items-center text-indigo-600 dark:text-indigo-400 font-semibold">
                                    <span>Upah Kerja Lembur ({{ $activePayroll->overtime_hours }} Jam)</span>
                                    <span class="font-mono font-bold">Rp {{ number_format($activePayroll->overtime_pay, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                @if($activePayroll->reimbursement_pay > 0)
                                <div class="flex justify-between items-center text-blue-600 dark:text-blue-400 font-semibold">
                                    <span>Klaim Reimbursement Operasional</span>
                                    <span class="font-mono font-bold">Rp {{ number_format($activePayroll->reimbursement_pay, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                @if($activePayroll->bonus_pay > 0)
                                <div class="flex justify-between items-center text-emerald-600 dark:text-emerald-400 font-semibold">
                                    <span>Insentif & Bonus Kinerja</span>
                                    <span class="font-mono font-bold">Rp {{ number_format($activePayroll->bonus_pay, 0, ',', '.') }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="p-3 bg-emerald-50/70 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-900/60 rounded-xl flex justify-between items-center text-xs font-bold text-emerald-800 dark:text-emerald-300">
                                <span>Total Penerimaan Kotor:</span>
                                <span class="font-mono text-sm">Rp {{ number_format($activePayroll->total_earnings, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- 2. Potongan -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span>Potongan (Deductions)</span>
                                </h4>
                                <span class="text-xs font-bold text-slate-400">Nominal</span>
                            </div>

                            <div class="space-y-2.5 text-xs text-slate-600 dark:text-slate-300">
                                <div class="flex justify-between items-center">
                                    <span>BPJS Ketenagakerjaan (3%)</span>
                                    <span class="font-mono font-bold text-rose-600 dark:text-rose-400">- Rp {{ number_format($activePayroll->deduction_bpjs_tk, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span>BPJS Kesehatan (1%)</span>
                                    <span class="font-mono font-bold text-rose-600 dark:text-rose-400">- Rp {{ number_format($activePayroll->deduction_bpjs_kes, 0, ',', '.') }}</span>
                                </div>
                                @if($activePayroll->deduction_late > 0)
                                <div class="flex justify-between items-center">
                                    <span>Potongan Terlambat ({{ $activePayroll->late_days }}x)</span>
                                    <span class="font-mono font-bold text-rose-600 dark:text-rose-400">- Rp {{ number_format($activePayroll->deduction_late, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                @if($activePayroll->deduction_pph21 > 0)
                                <div class="flex justify-between items-center">
                                    <span>Pajak Penghasilan (PPh 21)</span>
                                    <span class="font-mono font-bold text-rose-600 dark:text-rose-400">- Rp {{ number_format($activePayroll->deduction_pph21, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                @if($activePayroll->deduction_other > 0)
                                <div class="flex justify-between items-center">
                                    <span>Potongan Lain-lain</span>
                                    <span class="font-mono font-bold text-rose-600 dark:text-rose-400">- Rp {{ number_format($activePayroll->deduction_other, 0, ',', '.') }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="p-3 bg-rose-50/70 dark:bg-rose-950/40 border border-rose-200/80 dark:border-rose-900/60 rounded-xl flex justify-between items-center text-xs font-bold text-rose-800 dark:text-rose-300">
                                <span>Total Potongan:</span>
                                <span class="font-mono text-sm">- Rp {{ number_format($activePayroll->total_deductions, 0, ',', '.') }}</span>
                            </div>
                        </div>

                    </div>

                    <!-- Footer Action -->
                    <div class="p-4 sm:p-5 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                        <span class="text-slate-400 text-[11px]">
                            Dokumen sah diterbitkan otomatis oleh sistem HRIS &bull; {{ $activePayroll->created_at->format('d M Y, H:i') }}
                        </span>
                        <a href="{{ route('employee.payrolls.slip', $activePayroll->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition shadow-2xs">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>Unduh E-Slip Resmi (A4)</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- RIWAYAT SLIP GAJI LAINNYA -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Riwayat Slip Gaji Bulanan</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daftar arsip slip gaji seluruh periode masa kerja Anda.</p>
                </div>

                @if($payrolls->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                                    <th class="p-4 font-semibold">Periode</th>
                                    <th class="p-4 font-semibold">No. Dokumen</th>
                                    <th class="p-4 font-semibold">Gaji Bersih (THP)</th>
                                    <th class="p-4 font-semibold">Hari Hadir</th>
                                    <th class="p-4 font-semibold">Status</th>
                                    <th class="p-4 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                                @foreach($payrolls as $p)
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition {{ $activePayroll && $activePayroll->id === $p->id ? 'bg-blue-50/30 dark:bg-blue-950/20' : '' }}">
                                        <td class="p-4 font-bold text-slate-900 dark:text-white whitespace-nowrap">
                                            {{ $p->period_label }}
                                        </td>
                                        <td class="p-4 font-mono text-slate-500 whitespace-nowrap">
                                            {{ $p->payroll_number }}
                                        </td>
                                        <td class="p-4 font-mono font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                            Rp {{ number_format($p->net_salary, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            {{ $p->present_days }} Hari
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $p->status_badge['class'] }}">
                                                <i class="fa-solid {{ $p->status_badge['icon'] }} text-[11px]"></i>
                                                <span>{{ $p->status_badge['text'] }}</span>
                                            </span>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-right space-x-2">
                                            <a href="{{ route('employee.payrolls.index', ['id' => $p->id]) }}" class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-semibold transition">
                                                Lihat Rincian
                                            </a>
                                            <a href="{{ route('employee.payrolls.slip', $p->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 text-blue-700 dark:text-blue-300 rounded-lg text-xs font-semibold transition">
                                                <i class="fa-solid fa-file-pdf"></i>
                                                <span>PDF</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center space-y-3">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 mx-auto flex items-center justify-center text-2xl shadow-xs">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <h4 class="text-base font-bold text-slate-800 dark:text-white">Belum Ada Slip Gaji</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                            Slip gaji bulanan Anda akan tampil di sini setelah tim HR/Payroll menerbitkan rincian payroll resmi.
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
