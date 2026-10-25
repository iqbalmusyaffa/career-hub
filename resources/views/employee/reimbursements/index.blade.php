<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Employee Self-Service (ESS)</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">
                    Klaim Biaya Operasional & Reimbursement
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Ajukan klaim penggantian biaya operasional dinas, kesehatan, dan kebutuhan kerja Anda secara transparan.
                </p>
            </div>

            <!-- Month Filter -->
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('employee.reimbursements.index') }}" class="flex items-center gap-2">
                    <input type="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()"
                           class="text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 px-3 py-2 shadow-2xs font-semibold focus:ring-2 focus:ring-emerald-500">
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8" x-data="{
        openModal: false,
        claimCategory: 'operational',
        claimAmount: '',
        formatRupiah(num) {
            if (!num) return 'Rp 0';
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

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

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-2xl shadow-xs flex items-center justify-between text-xs font-medium transition">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600 dark:text-rose-400 text-base shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-rose-600 hover:text-rose-800 dark:text-rose-400">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            <!-- 4 BENTO STAT CARDS -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 rounded-2xl shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Disetujui (Total)</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-1.5">
                        <span class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">Rp {{ number_format($totalApproved, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 rounded-2xl shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Telah Dicairkan</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-1.5">
                        <span class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 rounded-2xl shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Menunggu Review</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-1.5">
                        <span class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ $pendingCount }}</span>
                        <span class="text-xs font-semibold text-slate-400">Pengajuan</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 rounded-2xl shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Siap Ditransfer</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-money-check-dollar"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-1.5">
                        <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ $approvedCount }}</span>
                        <span class="text-xs font-semibold text-slate-400">Klaim</span>
                    </div>
                </div>
            </div>

            <!-- CLAIMS LIST -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Daftar Klaim Penggantian Biaya ({{ $monthDate->translatedFormat('F Y') }})</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Seluruh klaim pengeluaran operasional beserta lampiran bukti struk pembayaran.</p>
                    </div>

                    <button type="button" @click="openModal = true" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Ajukan Klaim Reimbursement</span>
                    </button>
                </div>

                <!-- TABLE -->
                @if($claims->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                                    <th class="p-4 font-semibold">Tiket & Tanggal</th>
                                    <th class="p-4 font-semibold">Kategori & Keperluan</th>
                                    <th class="p-4 font-semibold">Nominal Klaim</th>
                                    <th class="p-4 font-semibold">Rekening Pencairan</th>
                                    <th class="p-4 font-semibold">Bukti Struk / Transfer</th>
                                    <th class="p-4 font-semibold">Status</th>
                                    <th class="p-4 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                                @foreach($claims as $c)
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                        <td class="p-4 whitespace-nowrap">
                                            <span class="font-mono font-bold text-slate-900 dark:text-white block">{{ $c->claim_number }}</span>
                                            <span class="text-[11px] text-slate-400">{{ $c->transaction_date->translatedFormat('d M Y') }}</span>
                                        </td>
                                        <td class="p-4 max-w-xs">
                                            <div class="flex items-center gap-1.5 mb-1">
                                                <i class="fa-solid {{ $c->category_icon }} text-[11px]"></i>
                                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $c->category_label }}</span>
                                            </div>
                                            <div class="text-xs text-slate-700 dark:text-slate-300 font-semibold">{{ $c->title }}</div>
                                            <p class="text-[11px] text-slate-400 truncate">{{ $c->description }}</p>
                                        </td>
                                        <td class="p-4 whitespace-nowrap font-mono font-bold text-sm text-slate-900 dark:text-white">
                                            Rp {{ number_format($c->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <span class="font-semibold block text-slate-800 dark:text-slate-200">{{ $c->bank_name }}</span>
                                            <span class="text-[11px] font-mono text-slate-400 block">{{ $c->bank_account_number }}</span>
                                            <span class="text-[10px] text-slate-400">a.n {{ $c->bank_account_holder }}</span>
                                        </td>
                                        <td class="p-4 whitespace-nowrap space-y-1">
                                            <a href="{{ Storage::url($c->receipt_path) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-[11px] font-semibold transition">
                                                <i class="fa-solid fa-receipt text-indigo-500"></i>
                                                <span>Struk Pembayaran</span>
                                            </a>
                                            @if($c->disbursement_proof_path)
                                                <a href="{{ Storage::url($c->disbursement_proof_path) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 rounded-lg text-[11px] font-semibold transition block">
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
                                            @if($c->admin_notes)
                                                <div class="text-[10px] text-slate-400 mt-1 italic max-w-[120px] truncate" title="{{ $c->admin_notes }}">
                                                    "{{ $c->admin_notes }}"
                                                </div>
                                            @endif
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-right">
                                            @if($c->status === 'pending')
                                                <form method="POST" action="{{ route('employee.reimbursements.destroy', $c->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan klaim reimbursement ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/50 rounded-lg transition border border-rose-200 dark:border-rose-900">
                                                        Batalkan
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-slate-400 text-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center space-y-3">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 mx-auto flex items-center justify-center text-2xl shadow-xs">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <h4 class="text-base font-bold text-slate-800 dark:text-white">Belum Ada Klaim Reimbursement</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                            Anda belum mengajukan klaim penggantian biaya operasional untuk periode {{ $monthDate->translatedFormat('F Y') }}.
                        </p>
                        <div class="pt-2">
                            <button type="button" @click="openModal = true" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                                + Buat Klaim Baru
                            </button>
                        </div>
                    </div>
                @endif
            </div>

        </div>

        <!-- MODAL FORM KLAIM REIMBURSEMENT -->
        <div x-show="openModal" x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/75 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="openModal = false" class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-5 max-h-[92vh] overflow-y-auto">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Formulir Klaim Reimbursement</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Lampirkan foto struk/nota pembayaran yang sah dan jelas.</p>
                        </div>
                    </div>
                    <button type="button" @click="openModal = false" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('employee.reimbursements.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kategori Pengeluaran <span class="text-rose-500">*</span></label>
                        <select name="category" x-model="claimCategory" required
                                class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-3 focus:ring-2 focus:ring-emerald-500">
                            <option value="operational">Operasional Kantor / Peralatan Kerja</option>
                            <option value="transport">Transportasi Dinas / Bensin / Tol</option>
                            <option value="meal_overtime">Konsumsi Lembur / Makan Dinas</option>
                            <option value="communication">Pulsa / Kuota Internet WFH</option>
                            <option value="medical">Kesehatan / Medis / Obat-obatan</option>
                            <option value="other">Lain-lain</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Judul Keperluan / Pengeluaran <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="Contoh: Pembelian Kuota Internet WFH Bulan Oktober"
                               class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-3 focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                            <input type="date" name="transaction_date" value="{{ now()->toDateString() }}" max="{{ now()->toDateString() }}" required
                                   class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-3 focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nominal Pengeluaran (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="amount" x-model="claimAmount" placeholder="Contoh: 150000" min="1000" required
                                   class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-3 focus:ring-2 focus:ring-emerald-500 font-mono">
                        </div>
                    </div>

                    <!-- Live Amount Format -->
                    <div x-show="claimAmount > 0" class="p-3 bg-emerald-50/70 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-900/60 rounded-xl flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Total Nominal:</span>
                        <span class="font-bold font-mono text-emerald-700 dark:text-emerald-300 text-sm" x-text="formatRupiah(claimAmount)"></span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi & Rincian Transaksi <span class="text-rose-500">*</span></label>
                        <textarea name="description" rows="3" required placeholder="Rincikan barang/layanan yang dibeli, tempat transaksi, serta keterkaitannya dengan pekerjaan..."
                                  class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-3 focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Upload Foto Struk / Bukti Pembayaran <span class="text-rose-500">*</span></label>
                        <input type="file" name="receipt" accept=".pdf,.jpg,.jpeg,.png" required
                               class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-2.5">
                        <span class="text-[10px] text-slate-400 block mt-1">Lampirkan foto struk nota fisik atau e-receipt resmi (Maks 5 MB).</span>
                    </div>

                    <!-- Rekening Tujuan Transfer -->
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-200 dark:border-slate-700 space-y-2.5">
                        <div class="text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-building-columns text-emerald-500"></i>
                            <span>Rekening Pencairan Reimbursement</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-0.5">Nama Bank</label>
                                <input type="text" name="bank_name" value="{{ $bankPrefill['bank_name'] }}" required
                                       class="w-full text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 p-2">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-0.5">Nomor Rekening</label>
                                <input type="text" name="bank_account_number" value="{{ $bankPrefill['bank_account_number'] }}" required
                                       class="w-full text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 p-2 font-mono">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-400 block mb-0.5">Nama Pemilik Rekening</label>
                            <input type="text" name="bank_account_holder" value="{{ $bankPrefill['bank_account_holder'] }}" required
                                   class="w-full text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 p-2">
                        </div>
                    </div>

                    <div class="pt-3 flex items-center justify-end gap-2.5">
                        <button type="button" @click="openModal = false" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold transition">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span>Kirim Klaim</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
