<x-app-layout>
    <div class="py-8" x-data="{
        actionModalOpen: false,
        activeClaim: null,
        claimStatus: 'approved',
        adminNotes: '',
        openAction(claim) {
            this.activeClaim = claim;
            this.claimStatus = claim.status === 'pending' ? 'approved' : claim.status;
            this.adminNotes = claim.admin_notes || '';
            this.actionModalOpen = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumbs & Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Admin</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">Karyawan</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Klaim Reimbursement</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                                Verifikasi & Pencairan Reimbursement Karyawan
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                                Validasi bukti struk nota pengeluaran operasional, verifikasi rekening bank, dan konfirmasi pencairan dana.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Month Picker Filter -->
                <form method="GET" action="{{ route('admin.employee-reimbursements.index') }}" class="flex items-center gap-2">
                    <input type="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()"
                           class="text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 px-3 py-2 shadow-2xs font-semibold focus:ring-2 focus:ring-emerald-500">
                </form>
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
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Klaim</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">{{ $totalClaims }} <span class="text-xs font-medium text-slate-400">Tiket</span></div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-amber-200 dark:border-amber-800/60 shadow-xs bg-amber-50/20 dark:bg-amber-950/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Menunggu Review</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-300 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl font-bold text-amber-700 dark:text-amber-300">{{ $pendingCount }} <span class="text-xs font-medium text-amber-600">Pending</span></div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-indigo-200 dark:border-indigo-800/60 shadow-xs bg-indigo-50/20 dark:bg-indigo-950/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-400">Disetujui (Siap Bayar)</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-thumbs-up"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-xl sm:text-2xl font-bold text-indigo-700 dark:text-indigo-300">Rp {{ number_format($approvedAmount, 0, ',', '.') }}</div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-emerald-200 dark:border-emerald-800/60 shadow-xs bg-emerald-50/20 dark:bg-emerald-950/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Telah Dicairkan</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-xl sm:text-2xl font-bold text-emerald-700 dark:text-emerald-300">Rp {{ number_format($paidAmount, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="p-4 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
                <form method="GET" action="{{ route('admin.employee-reimbursements.index') }}" class="flex items-center gap-2 flex-wrap flex-1">
                    <input type="hidden" name="month" value="{{ $selectedMonth }}">
                    
                    <div class="relative min-w-[200px]">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari tiket, judul, atau nama..."
                               class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 pl-8 pr-3 py-2">
                        <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
                    </div>

                    <select name="status" onchange="this.form.submit()" class="text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 font-medium">
                        <option value="all" {{ $selectedStatus === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ $selectedStatus === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                        <option value="approved" {{ $selectedStatus === 'approved' ? 'selected' : '' }}>Disetujui (Siap Bayar)</option>
                        <option value="paid" {{ $selectedStatus === 'paid' ? 'selected' : '' }}>Telah Dicairkan</option>
                        <option value="rejected" {{ $selectedStatus === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>

                    <select name="category" onchange="this.form.submit()" class="text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 font-medium">
                        <option value="all" {{ $selectedCategory === 'all' ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="operational" {{ $selectedCategory === 'operational' ? 'selected' : '' }}>Operasional Kantor</option>
                        <option value="transport" {{ $selectedCategory === 'transport' ? 'selected' : '' }}>Transportasi</option>
                        <option value="communication" {{ $selectedCategory === 'communication' ? 'selected' : '' }}>Pulsa / Internet</option>
                        <option value="meal_overtime" {{ $selectedCategory === 'meal_overtime' ? 'selected' : '' }}>Konsumsi Lembur</option>
                        <option value="medical" {{ $selectedCategory === 'medical' ? 'selected' : '' }}>Medis / Obat</option>
                    </select>

                    <button type="submit" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-2xs">
                        Filter
                    </button>
                    @if($search || $selectedStatus !== 'all' || $selectedCategory !== 'all')
                        <a href="{{ route('admin.employee-reimbursements.index', ['month' => $selectedMonth]) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table of Claims -->
            <div class="bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs overflow-hidden">
                @if($claims->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                                    <th class="p-4 font-semibold">Tiket & Karyawan</th>
                                    <th class="p-4 font-semibold">Kategori & Keperluan</th>
                                    <th class="p-4 font-semibold">Nominal Klaim</th>
                                    <th class="p-4 font-semibold">Rekening Penerima</th>
                                    <th class="p-4 font-semibold">Bukti Transaksi</th>
                                    <th class="p-4 font-semibold">Status</th>
                                    <th class="p-4 font-semibold text-right">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-300">
                                @foreach($claims as $c)
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                        <td class="p-4 whitespace-nowrap">
                                            <span class="font-mono font-bold text-slate-900 dark:text-white block">{{ $c->claim_number }}</span>
                                            <div class="font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $c->user->name }}</div>
                                            <span class="text-[10px] text-slate-400">{{ $c->transaction_date->translatedFormat('d M Y') }}</span>
                                        </td>
                                        <td class="p-4 max-w-xs">
                                            <div class="flex items-center gap-1.5 mb-1">
                                                <i class="fa-solid {{ $c->category_icon }} text-[11px]"></i>
                                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $c->category_label }}</span>
                                            </div>
                                            <div class="font-bold text-slate-900 dark:text-white line-clamp-1">{{ $c->title }}</div>
                                            <p class="text-[11px] text-slate-400 line-clamp-2">{{ $c->description }}</p>
                                        </td>
                                        <td class="p-4 whitespace-nowrap font-mono font-bold text-sm text-slate-900 dark:text-white">
                                            Rp {{ number_format($c->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <span class="font-bold block text-slate-800 dark:text-slate-200">{{ $c->bank_name }}</span>
                                            <span class="font-mono text-[11px] text-slate-400 block">{{ $c->bank_account_number }}</span>
                                            <span class="text-[10px] text-slate-400">a.n {{ $c->bank_account_holder }}</span>
                                        </td>
                                        <td class="p-4 whitespace-nowrap space-y-1">
                                            <a href="{{ \Illuminate\Support\Facades\Storage::url($c->receipt_path) }}" target="_blank"
                                               class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-[11px] font-semibold transition block">
                                                <i class="fa-solid fa-receipt text-indigo-500"></i>
                                                <span>Lihat Struk</span>
                                            </a>
                                            @if($c->disbursement_proof_path)
                                                <a href="{{ \Illuminate\Support\Facades\Storage::url($c->disbursement_proof_path) }}" target="_blank"
                                                   class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 rounded-lg text-[11px] font-semibold transition block">
                                                    <i class="fa-solid fa-file-invoice text-emerald-500"></i>
                                                    <span>Bukti Transfer</span>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $c->status_badge['class'] }}">
                                                <i class="fa-solid {{ $c->status_badge['icon'] }} text-[11px]"></i>
                                                <span>{{ $c->status_badge['text'] }}</span>
                                            </span>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-right">
                                            <button type="button" @click="openAction({{ json_encode($c) }})"
                                                    class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:hover:bg-emerald-900 text-emerald-700 dark:text-emerald-300 font-bold rounded-lg transition text-xs shadow-2xs">
                                                Tindakan & Review
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                        {{ $claims->links() }}
                    </div>
                @else
                    <div class="p-12 text-center space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center text-xl">
                            <i class="fa-solid fa-inbox"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white">Tidak Ada Klaim Reimbursement</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">Tidak ditemukan pengajuan klaim penggantian biaya operasional pada filter ini.</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- MODAL ACTION TINDAKAN -->
        <div x-show="actionModalOpen" x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/75 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="actionModalOpen = false" class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Review & Verifikasi Klaim</h3>
                    <button type="button" @click="actionModalOpen = false" class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <template x-if="activeClaim">
                    <form method="POST" :action="'{{ url('/admin/employee-reimbursements') }}/' + activeClaim.id + '/status'" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl space-y-1 text-xs">
                            <div class="text-slate-500">No. Tiket: <strong class="text-slate-900 dark:text-white font-mono" x-text="activeClaim.claim_number"></strong></div>
                            <div class="text-slate-500">Karyawan: <span class="font-bold text-slate-800 dark:text-slate-200" x-text="activeClaim.user.name"></span></div>
                            <div class="text-slate-500">Nominal: <span class="font-bold font-mono text-emerald-600 dark:text-emerald-400" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(activeClaim.amount)"></span></div>
                            <div class="text-slate-500">Rekening: <span class="font-mono" x-text="activeClaim.bank_name + ' - ' + activeClaim.bank_account_number + ' (a.n ' + activeClaim.bank_account_holder + ')'"></span></div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tindakan Keputusan <span class="text-rose-500">*</span></label>
                            <select name="status" x-model="claimStatus" required
                                    class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-2.5 font-bold">
                                <option value="approved">Setujui Klaim (Siap Bayar)</option>
                                <option value="paid">Telah Ditransfer / Dicairkan</option>
                                <option value="rejected">Tolak Klaim</option>
                            </select>
                        </div>

                        <div x-show="claimStatus === 'paid'">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Upload Bukti Transfer Bank (Opsional)</label>
                            <input type="file" name="disbursement_proof" accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-2">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Catatan Verifikator</label>
                            <textarea name="admin_notes" rows="2.5" x-model="adminNotes" placeholder="Catatan persetujuan atau alasan penolakan klaim..."
                                      class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-2.5"></textarea>
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-2.5">
                            <button type="button" @click="actionModalOpen = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </template>

            </div>
        </div>
    </div>
</x-app-layout>
