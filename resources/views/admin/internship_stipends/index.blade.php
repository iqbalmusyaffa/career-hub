<x-app-layout>
    <div class="py-8" x-data="{ 
        selectedStipends: [], 
        selectAll: false,
        activeStipend: null,
        statusModalOpen: false,
        proofModalOpen: false,
        currentProofUrl: '',
        currentProofTitle: '',
        toggleAll() {
            if (this.selectAll) {
                this.selectedStipends = Array.from(document.querySelectorAll('.stipend-checkbox')).map(cb => cb.value);
            } else {
                this.selectedStipends = [];
            }
        },
        openStatusModal(stipend) {
            this.activeStipend = stipend;
            this.statusModalOpen = true;
        },
        openProof(url, title) {
            this.currentProofUrl = url;
            this.currentProofTitle = title;
            this.proofModalOpen = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Admin</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">Magang</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Uang Saku & Payroll</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                                Rekapitulasi & Payroll Uang Saku Magang
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                                Kelola pencairan uang saku bulanan, validasi nama rekening KTP, dan rekap transfer bank.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('admin.internship-stipends.export-excel', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs hover:shadow-md transition">
                        <i class="fa-solid fa-file-excel"></i>
                        <span>Export Excel Payroll</span>
                    </a>
                </div>
            </div>

            <!-- Summary Counter Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Recipients Card -->
                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Penerima</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm font-semibold">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalRecipients }} <span class="text-sm font-medium text-slate-500">Peserta</span></div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Periode {{ $availableMonths[$selectedMonth] ?? $selectedMonth }}</p>
                    </div>
                </div>

                <!-- Total Disbursed Card -->
                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-emerald-200 dark:border-emerald-800/60 shadow-xs bg-emerald-50/20 dark:bg-emerald-950/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Telah Ditransfer</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm font-semibold">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-400">Rp {{ number_format($totalDisbursed, 0, ',', '.') }}</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $transferredCount }} dari {{ $totalRecipients }} telah cair</p>
                    </div>
                </div>

                <!-- Total Pending Card -->
                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-amber-200 dark:border-amber-800/60 shadow-xs bg-amber-50/20 dark:bg-amber-950/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Menunggu / Siap Transfer</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-950/80 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm font-semibold">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="text-2xl font-bold text-amber-700 dark:text-amber-400">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Memerlukan verifikasi & transfer</p>
                    </div>
                </div>

                <!-- Policy Info Card -->
                <div class="p-5 bg-slate-900 text-white rounded-2xl border border-slate-800 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1.5 text-xs font-bold text-amber-400 uppercase tracking-wider">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Ketentuan Uang Saku</span>
                        </div>
                        <p class="text-[11px] text-slate-300 mt-1.5 leading-relaxed">
                            Izin/Sakit &le; 4 hari <strong>Bebas Potong</strong>. Hari kerja: 22 hari. Rekening wajib atas nama peserta sesuai KTP.
                        </p>
                    </div>
                    <div class="mt-2 text-[10px] text-slate-400">
                        Formula Potongan: (Izin &gt; 4 + Alpa) &times; (Nominal / 22)
                    </div>
                </div>
            </div>

            <!-- Filter and Search Bar -->
            <div class="p-4 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs">
                <form method="GET" action="{{ route('admin.internship-stipends.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                    <!-- Month Selector -->
                    <div class="lg:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Periode Bulan</label>
                        <select name="month" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-emerald-500 focus:border-emerald-500 py-2">
                            @foreach($availableMonths as $mKey => $mLabel)
                                <option value="{{ $mKey }}" {{ $selectedMonth === $mKey ? 'selected' : '' }}>
                                    {{ $mLabel }} {{ $mKey === date('Y-m') ? '(Bulan Ini)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Batch Selector -->
                    <div class="lg:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Batch Magang</label>
                        <select name="batch" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-emerald-500 focus:border-emerald-500 py-2">
                            <option value="">Semua Batch</option>
                            @foreach($batches as $batchName)
                                @php
                                    $bName = is_object($batchName) ? $batchName->batch_name : $batchName;
                                @endphp
                                <option value="{{ $bName }}" {{ $selectedBatch === $bName ? 'selected' : '' }}>
                                    {{ $bName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Attendance Filter -->
                    <div class="lg:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Filter Kehadiran</label>
                        <select name="attendance" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-emerald-500 focus:border-emerald-500 py-2">
                            <option value="">Semua Presensi</option>
                            <option value="alpha" {{ in_array($selectedAttendance, ['alpha', 'unexcused']) ? 'selected' : '' }}>🔴 Ada Alpa / Tidak Hadir</option>
                            <option value="izin_sakit" {{ in_array($selectedAttendance, ['izin_sakit', 'excused']) ? 'selected' : '' }}>🟡 Ada Izin / Sakit</option>
                            <option value="excess_excused" {{ $selectedAttendance === 'excess_excused' ? 'selected' : '' }}>⚠️ Izin &gt; 4 Hari (Potong)</option>
                            <option value="has_deduction" {{ $selectedAttendance === 'has_deduction' ? 'selected' : '' }}>💸 Ada Potongan Uang Saku</option>
                            <option value="perfect" {{ in_array($selectedAttendance, ['perfect', 'full_attendance']) ? 'selected' : '' }}>🟢 Hadir Penuh / Bebas Potong</option>
                        </select>
                    </div>

                    <!-- Status Selector -->
                    <div class="lg:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Status Transfer</label>
                        <select name="status" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-emerald-500 focus:border-emerald-500 py-2">
                            <option value="">Semua Status</option>
                            <option value="in_review" {{ $selectedStatus === 'in_review' ? 'selected' : '' }}>Dalam Verifikasi</option>
                            <option value="ready" {{ $selectedStatus === 'ready' ? 'selected' : '' }}>Siap Ditransfer</option>
                            <option value="transferred" {{ $selectedStatus === 'transferred' ? 'selected' : '' }}>Telah Ditransfer</option>
                            <option value="pending_mentor" {{ $selectedStatus === 'pending_mentor' ? 'selected' : '' }}>Belum Diajukan Mentor</option>
                            <option value="submitted_mentor" {{ $selectedStatus === 'submitted_mentor' ? 'selected' : '' }}>Telah Diajukan Mentor</option>
                        </select>
                    </div>

                    <!-- Bank Account Status Selector -->
                    <div class="lg:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Status Rekening</label>
                        <select name="bank_status" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-emerald-500 focus:border-emerald-500 py-2">
                            <option value="">Semua Rekening</option>
                            <option value="ktp_match" {{ $selectedBankStatus === 'ktp_match' ? 'selected' : '' }}>✓ KTP Match</option>
                            <option value="ktp_unmatch" {{ $selectedBankStatus === 'ktp_unmatch' ? 'selected' : '' }}>⚠️ Cek KTP (Nama Beda)</option>
                            <option value="unfilled" {{ $selectedBankStatus === 'unfilled' ? 'selected' : '' }}>⚠️ Belum Isi Rekening</option>
                        </select>
                    </div>

                    <!-- Search Input & Action Buttons -->
                    <div class="lg:col-span-2 flex items-end gap-1.5">
                        <div class="flex-1 min-w-0">
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Cari Peserta</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ $search }}" placeholder="Nama / rek..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 pl-8 pr-2 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <i class="fa-solid fa-search absolute left-2.5 top-2.5 text-xs text-slate-400"></i>
                            </div>
                        </div>
                        <button type="submit" class="py-2 px-3 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-xl text-xs font-bold transition flex items-center justify-center shrink-0 shadow-xs" title="Terapkan Filter">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                        @if($selectedBatch || $selectedStatus || $selectedBankStatus || $selectedAttendance || $search)
                            <a href="{{ route('admin.internship-stipends.index', ['month' => $selectedMonth]) }}" class="py-2 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold transition flex items-center justify-center shrink-0" title="Reset Filter">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Bulk Action Form & Table -->
            <form method="POST" action="{{ route('admin.internship-stipends.bulk-transfer') }}" id="bulkTransferForm">
                @csrf
                
                <!-- Bulk Action Bar -->
                <div x-show="selectedStipends.length > 0" x-cloak class="mb-4 p-3 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 rounded-xl flex items-center justify-between gap-4 animate-fade-in">
                    <div class="flex items-center gap-2 text-xs text-emerald-800 dark:text-emerald-300 font-bold">
                        <i class="fa-solid fa-check-double text-base"></i>
                        <span><span x-text="selectedStipends.length"></span> penerima uang saku dipilih.</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menandai semua penerima terpilih sebagai TELAH DITRANSFER? Sistem akan mengirimkan notifikasi resmi ke masing-masing peserta.');" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-xs transition flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Tandai Sebagai TELAH DITRANSFER</span>
                        </button>
                    </div>
                </div>

                <!-- Main Table -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-900 shadow-xs">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 font-semibold border-b border-slate-200 dark:border-slate-700/80">
                            <tr>
                                <th class="p-3.5 w-10 text-center">
                                    <input type="checkbox" x-model="selectAll" @change="toggleAll" class="rounded text-emerald-600 focus:ring-emerald-500 dark:bg-slate-800 dark:border-slate-700">
                                </th>
                                <th class="p-3.5">Peserta Magang</th>
                                <th class="p-3.5">Rekening Bank (KTP Match)</th>
                                <th class="p-3.5 text-center">Kehadiran (HK)</th>
                                <th class="p-3.5 text-right">Pokok</th>
                                <th class="p-3.5 text-right">Potongan</th>
                                <th class="p-3.5 text-right">Uang Saku Bersih</th>
                                <th class="p-3.5 text-center">Status</th>
                                <th class="p-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-normal text-slate-700 dark:text-slate-200">
                            @forelse($stipends as $stipend)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                                    <td class="p-3.5 text-center">
                                        @if($stipend->status !== 'transferred')
                                            <input type="checkbox" name="stipend_ids[]" value="{{ $stipend->id }}" x-model="selectedStipends" class="stipend-checkbox rounded text-emerald-600 focus:ring-emerald-500 dark:bg-slate-800 dark:border-slate-700">
                                        @else
                                            <i class="fa-solid fa-check text-emerald-500" title="Telah Selesai Transfer"></i>
                                        @endif
                                    </td>
                                    <td class="p-3.5">
                                        <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                            <span>{{ $stipend->user?->name }}</span>
                                        </div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $stipend->user?->email }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $stipend->batch_name }}</div>
                                    </td>
                                    <td class="p-3.5">
                                        @if($stipend->bank_account_number)
                                            <div class="font-semibold text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                                <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-700 dark:text-slate-300 mr-1">{{ $stipend->bank_name }}</span>
                                                <span class="font-mono">{{ $stipend->bank_account_number }}</span>
                                            </div>
                                            <div class="text-[11px] text-slate-600 dark:text-slate-400 flex items-center gap-1.5 mt-0.5 whitespace-nowrap">
                                                <span>a.n {{ $stipend->bank_account_holder }}</span>
                                                @if($stipend->is_ktp_matched)
                                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-300/60 dark:border-emerald-800/60" title="Nama rekening sesuai nama KTP">
                                                        <i class="fa-solid fa-circle-check"></i> KTP Match
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-300/60 dark:border-rose-800/60" title="Nama rekening ({{ $stipend->bank_account_holder }}) berbeda dengan KTP ({{ $stipend->user?->name }})">
                                                        <i class="fa-solid fa-triangle-exclamation"></i> Cek KTP / Beda Nama
                                                    </span>
                                                @endif
                                            </div>
                                            @if($stipend->bank_book_doc_path)
                                                <div class="mt-1">
                                                    <button type="button" @click="openProof('{{ asset('storage/' . $stipend->bank_book_doc_path) }}', 'Buku Tabungan - {{ $stipend->user?->name }}')" class="text-[10px] text-blue-600 hover:text-blue-700 dark:text-blue-400 flex items-center gap-1">
                                                        <i class="fa-solid fa-image"></i>
                                                        <span>Lihat Buku Tabungan</span>
                                                    </button>
                                                </div>
                                            @endif
                                        @else
                                            <div class="space-y-1">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-300 dark:border-amber-800/80">
                                                    <i class="fa-solid fa-triangle-exclamation"></i> Belum Mengisi Rekening
                                                </span>
                                                <div class="text-[10px] text-slate-400 dark:text-slate-500">Nomor rekening belum diinput</div>
                                                <form method="POST" action="{{ route('admin.internship-stipends.send-reminder', $stipend->id) }}" class="mt-1">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Kirimkan notifikasi pengingat pengisian rekening ke {{ $stipend->user?->name }}?');" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-bold bg-amber-50 hover:bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:hover:bg-amber-900/70 dark:text-amber-300 border border-amber-300 dark:border-amber-800/80 transition cursor-pointer" title="Kirim notifikasi pengingat ke peserta">
                                                        <i class="fa-regular fa-bell"></i>
                                                        <span>Kirim Pengingat</span>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-center whitespace-nowrap">
                                        @if($selectedMonth > date('Y-m'))
                                            <div class="font-bold text-slate-400 dark:text-slate-500">0 / {{ $stipend->total_working_days }} HK</div>
                                            <div class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold mt-0.5">
                                                <i class="fa-regular fa-clock"></i> Belum Berjalan
                                            </div>
                                        @else
                                            <div class="font-bold {{ $stipend->present_days === 0 && $stipend->unexcused_days > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-800 dark:text-slate-200' }}">
                                                {{ $stipend->present_days }} / {{ $stipend->total_working_days }} HK
                                            </div>
                                            <div class="text-[10px] text-slate-500">
                                                <span class="{{ $stipend->excused_days > 4 ? 'text-rose-500 font-bold' : '' }}">Izin: {{ $stipend->excused_days }} hr</span>
                                                @if($stipend->unexcused_days > 0)
                                                    <span class="text-rose-500 font-bold ml-1">Alpa: {{ $stipend->unexcused_days }} hr</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-right font-medium text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                        Rp {{ number_format($stipend->base_nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3.5 text-right font-medium whitespace-nowrap {{ $stipend->deduction_amount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-400' }}">
                                        @if($selectedMonth > date('Y-m'))
                                            <span class="text-slate-400 dark:text-slate-500">Rp 0</span>
                                        @else
                                            {{ $stipend->deduction_amount > 0 ? '-Rp ' . number_format($stipend->deduction_amount, 0, ',', '.') : 'Rp 0' }}
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-right whitespace-nowrap">
                                        @if($selectedMonth > date('Y-m'))
                                            <div class="font-bold text-slate-400 dark:text-slate-500 text-[13px]">Rp 0</div>
                                            <div class="text-[9px] text-slate-400">Periode belum berjalan</div>
                                        @elseif($stipend->net_amount == 0)
                                            <div class="font-bold text-rose-600 dark:text-rose-400 text-[13px]">Rp 0</div>
                                            <div class="text-[9px] text-rose-500 font-semibold">Potongan Alpa 100%</div>
                                        @else
                                            <div class="font-bold text-emerald-600 dark:text-emerald-400 text-[13px]">
                                                Rp {{ number_format($stipend->net_amount, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-center whitespace-nowrap">
                                        @if($selectedMonth > date('Y-m'))
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 text-[10px] font-semibold">
                                                <i class="fa-regular fa-calendar-xmark"></i> Belum Berjalan
                                            </span>
                                        @elseif($stipend->status === 'transferred')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 text-[10px] font-bold">
                                                <i class="fa-solid fa-circle-check"></i> Telah Ditransfer
                                            </span>
                                        @elseif($stipend->status === 'ready')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-100 text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300 text-[10px] font-bold">
                                                <i class="fa-solid fa-circle-play"></i> Siap Ditransfer
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 text-[10px] font-bold">
                                                <i class="fa-solid fa-clock"></i> Dalam Verifikasi
                                            </span>
                                        @endif

                                        @if($stipend->mentor_submitted_at)
                                            <div class="mt-1">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300" title="Diajukan oleh {{ $stipend->mentor?->name ?? 'Mentor' }} pada {{ $stipend->mentor_submitted_at->format('d/m/Y H:i') }}">
                                                    <i class="fa-solid fa-user-check"></i> {{ $stipend->mentor?->name ?? 'Mentor' }}
                                                </span>
                                                @if($stipend->mentor_notes)
                                                    <div class="text-[9px] text-slate-500 italic truncate max-w-[130px] mx-auto mt-0.5" title="{{ $stipend->mentor_notes }}">
                                                        "{{ $stipend->mentor_notes }}"
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($selectedMonth <= date('Y-m'))
                                            <div class="mt-1">
                                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.2 rounded text-[9px] font-semibold text-amber-600 bg-amber-50 dark:bg-amber-950/40">
                                                    <i class="fa-solid fa-clock text-[8px]"></i> Belum Diajukan Mentor
                                                </span>
                                            </div>
                                        @endif

                                        @if($stipend->transferred_at)
                                            <div class="text-[9px] text-slate-400 mt-0.5">{{ $stipend->transferred_at->format('d/m/Y H:i') }}</div>
                                        @endif
                                    </td>
                                     <td class="p-3.5 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('admin.internship-stipends.slip', $stipend->id) }}" target="_blank" class="p-2 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/60 dark:hover:bg-blue-900 text-blue-600 dark:text-blue-400 transition" title="Unduh Slip Uang Saku (PDF)">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
                                            <button type="button" @click="openStatusModal({{ json_encode($stipend) }})" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition" title="Update Status / Upload Bukti">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            @if($stipend->proof_path)
                                                <button type="button" @click="openProof('{{ asset('storage/' . $stipend->proof_path) }}', 'Bukti Transfer - {{ $stipend->user?->name }}')" class="p-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:hover:bg-emerald-900 text-emerald-600 dark:text-emerald-400 transition" title="Lihat Bukti Transfer">
                                                    <i class="fa-solid fa-receipt"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="p-8 text-center text-slate-500 dark:text-slate-400">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-2 text-lg">
                                            <i class="fa-solid fa-folder-open"></i>
                                        </div>
                                        <div class="text-sm font-semibold">Tidak ada data uang saku ditemukan</div>
                                        <p class="text-xs text-slate-400 mt-0.5">Coba sesuaikan periode bulan atau filter pencarian Anda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $stipends->links() }}
                </div>
            </form>

        </div>

        <!-- =================== MODAL: UPDATE STATUS & UPLOAD BUKTI =================== -->
        <div x-show="statusModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="statusModalOpen = false" class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base shrink-0">
                            <i class="fa-solid fa-money-check-dollar"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Update Status Uang Saku</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400" x-text="activeStipend?.user?.name + ' (' + activeStipend?.period_label + ')'"></p>
                        </div>
                    </div>
                    <button type="button" @click="statusModalOpen = false" class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-slate-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <template x-if="activeStipend">
                    <form :action="'{{ url('/admin/internship-stipends') }}/' + activeStipend.id + '/status'" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <!-- Info Card -->
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 text-xs space-y-1.5">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Rekening Tujuan:</span>
                                <template x-if="activeStipend.bank_account_number">
                                    <span class="font-bold text-slate-900 dark:text-white font-mono" x-text="activeStipend.bank_name + ' - ' + activeStipend.bank_account_number"></span>
                                </template>
                                <template x-if="!activeStipend.bank_account_number">
                                    <span class="text-amber-600 font-bold inline-flex items-center gap-1">
                                        <i class="fa-solid fa-triangle-exclamation"></i> Belum Diisi Peserta
                                    </span>
                                </template>
                            </div>
                            <div class="flex justify-between items-center" x-show="activeStipend.bank_account_holder">
                                <span class="text-slate-500">Atas Nama:</span>
                                <span class="font-bold text-slate-900 dark:text-white" x-text="activeStipend.bank_account_holder"></span>
                            </div>
                            <template x-if="activeStipend.mentor_notes">
                                <div class="p-2 rounded-lg bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900/40 text-[11px] space-y-0.5">
                                    <div class="font-bold text-blue-800 dark:text-blue-300 flex items-center gap-1">
                                        <i class="fa-solid fa-user-check text-[10px]"></i>
                                        <span>Rekomendasi Mentor (<span x-text="activeStipend.mentor ? activeStipend.mentor.name : 'Mentor'"></span>):</span>
                                    </div>
                                    <p class="text-slate-600 dark:text-slate-300 italic font-normal" x-text="'&quot;' + activeStipend.mentor_notes + '&quot;'"></p>
                                </div>
                            </template>
                            <div class="flex justify-between items-center pt-1 border-t border-slate-200 dark:border-slate-700">
                                <span class="text-slate-500 font-semibold">Nominal Bersih (Net):</span>
                                <span class="font-black text-emerald-600 text-sm" x-text="'Rp ' + Number(activeStipend.net_amount).toLocaleString('id-ID')"></span>
                            </div>
                            <div class="pt-2 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                                <a :href="'{{ url('/admin/internship-stipends') }}/' + activeStipend.id + '/slip'" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/60 dark:hover:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-bold transition">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <span>Cetak / Unduh Slip Resmi (PDF)</span>
                                </a>
                            </div>
                        </div>

                        <!-- Status Selection -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Status Pencairan *</label>
                            <select name="status" x-model="activeStipend.status" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-emerald-500 focus:border-emerald-500 py-2">
                                <option value="in_review">Dalam Verifikasi (In Review)</option>
                                <option value="ready">Siap Ditransfer (Ready for Transfer)</option>
                                <option value="transferred">Telah Ditransfer (Transferred / Paid)</option>
                            </select>
                        </div>

                        <!-- Upload Proof File -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Bukti Transfer (Struk Bank / Resi PDF / JPG)</label>
                            <input type="file" name="proof_file" accept=".pdf,.png,.jpg,.jpeg" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-700 dark:text-slate-300 p-2 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950 dark:file:text-emerald-300">
                            <p class="text-[10px] text-slate-400 mt-1">Opsional, format PDF/JPG/PNG maksimal 5MB.</p>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Catatan HR / Payroll</label>
                            <textarea name="notes" rows="2" x-model="activeStipend.notes" placeholder="Catatan nomor referensi bank, keterangan khusus, dsb..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-emerald-500 focus:border-emerald-500 p-2"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="statusModalOpen = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-200 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

        <!-- =================== MODAL: PREVIEW BUKTI / DOKUMEN =================== -->
        <div x-show="proofModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="proofModalOpen = false" class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white" x-text="currentProofTitle"></h3>
                    <button type="button" @click="proofModalOpen = false" class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-slate-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="flex items-center justify-center bg-slate-100 dark:bg-slate-950 rounded-xl p-2 min-h-[300px] overflow-hidden">
                    <template x-if="currentProofUrl.endsWith('.pdf')">
                        <iframe :src="currentProofUrl" class="w-full h-[450px] rounded-lg"></iframe>
                    </template>
                    <template x-if="!currentProofUrl.endsWith('.pdf')">
                        <img :src="currentProofUrl" class="max-h-[450px] w-auto object-contain rounded-lg shadow-xs" alt="Bukti Transfer">
                    </template>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <a :href="currentProofUrl" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300 transition">
                        <i class="fa-solid fa-download"></i>
                        <span>Buka / Unduh File Asli</span>
                    </a>
                    <button type="button" @click="proofModalOpen = false" class="px-4 py-1.5 bg-slate-900 dark:bg-slate-800 text-white rounded-lg text-xs font-semibold">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
