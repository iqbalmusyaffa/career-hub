<x-app-layout>
    <div class="py-8" x-data="{ 
        selectedStipends: [], 
        selectAll: false,
        activeStipend: null,
        proposalModalOpen: false,
        bulkModalOpen: false,
        proofModalOpen: false,
        currentProofUrl: '',
        currentProofTitle: '',
        toggleAll() {
            if (this.selectAll) {
                this.selectedStipends = Array.from(document.querySelectorAll('.stipend-checkbox:not(:disabled)')).map(cb => cb.value);
            } else {
                this.selectedStipends = [];
            }
        },
        openProposalModal(stipend) {
            this.activeStipend = stipend;
            this.proposalModalOpen = true;
        },
        openProof(url, title) {
            this.currentProofUrl = url;
            this.currentProofTitle = title;
            this.proofModalOpen = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Page Header & Breadcrumb -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                        <a href="{{ route('mentor.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Mentor</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">Bimbingan Magang</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Pengajuan Uang Saku</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                                Pengajuan & Rekomendasi Uang Saku Mentee
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                                Review akumulasi kehadiran, evaluasi potongan absensi, dan ajukan rekomendasi pencairan bulanan ke HR/Finance.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    @if($pendingProposalCount > 0)
                        <button type="button" @click="bulkModalOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs hover:shadow-md transition">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Ajukan Semua Rekomendasi ({{ $pendingProposalCount }})</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Summary Counter Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Mentees Card -->
                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Mentee</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm font-semibold">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalRecipients }} <span class="text-sm font-medium text-slate-500">Peserta</span></div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Periode {{ $availableMonths[$selectedMonth] ?? $selectedMonth }}</p>
                    </div>
                </div>

                <!-- Pending Mentor Proposal Card -->
                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-amber-200 dark:border-amber-800/60 shadow-xs bg-amber-50/20 dark:bg-amber-950/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Belum Diajukan</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-950/80 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm font-semibold">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="text-2xl font-bold text-amber-700 dark:text-amber-400">{{ $pendingProposalCount }} <span class="text-sm font-medium text-amber-600/80">Peserta</span></div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Memerlukan review & submit Mentor</p>
                    </div>
                </div>

                <!-- Submitted to HR Card -->
                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-blue-200 dark:border-blue-800/60 shadow-xs bg-blue-50/20 dark:bg-blue-950/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-700 dark:text-blue-400">Telah Diajukan ke HR</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-950/80 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm font-semibold">
                            <i class="fa-solid fa-paper-plane"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="text-2xl font-bold text-blue-700 dark:text-blue-400">{{ $submittedProposalCount }} <span class="text-sm font-medium text-blue-600/80">Peserta</span></div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $transferredCount }} telah ditransfer oleh HR</p>
                    </div>
                </div>

                <!-- Total Estimated Amount Card -->
                <div class="p-5 bg-slate-900 text-white rounded-2xl border border-slate-800 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-400 uppercase tracking-wider">
                            <i class="fa-solid fa-wallet"></i>
                            <span>Estimasi Total Bersih</span>
                        </div>
                        <div class="text-xl sm:text-2xl font-bold text-white mt-1.5">
                            Rp {{ number_format($totalEstimatedStipend, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="mt-2 text-[10px] text-slate-400">
                        Formula: Pokok &minus; (Izin &gt; 4 hr + Alpa) &times; (Pokok / 22)
                    </div>
                </div>
            </div>

            <!-- Ketentuan Uang Saku & Petunjuk Mentor -->
            <div class="p-4 rounded-2xl bg-gradient-to-r from-blue-50 via-indigo-50/50 to-white dark:from-slate-800/80 dark:via-slate-800/50 dark:to-slate-900 border border-blue-200/80 dark:border-slate-700/80 shadow-xs">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shrink-0 mt-0.5 shadow-xs">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div class="space-y-1 text-xs">
                        <h4 class="font-bold text-slate-900 dark:text-white">Alur Rekomendasi & Persetujuan Uang Saku oleh Mentor:</h4>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                            1. Mentor mereview akumulasi kehadiran logbook harian mentee pada periode bulan yang dipilih.<br>
                            2. Toleransi izin/sakit adalah <strong>maksimal 4 hari per periode</strong> tanpa pemotongan. Kelebihan izin atau alpa tanpa keterangan akan otomatis dihitung potongannya oleh sistem.<br>
                            3. Klik <strong>"Ajukan Uang Saku"</strong> untuk membubuhkan catatan/rekomendasi performa mentee dan meneruskan data payroll ke tim HR & Finance untuk eksekusi transfer.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Filter and Search Bar -->
            <div class="p-4 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs">
                <form method="GET" action="{{ route('mentor.stipends.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
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
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Status Pengajuan</label>
                        <select name="status" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-emerald-500 focus:border-emerald-500 py-2">
                            <option value="">Semua Status</option>
                            <option value="pending_proposal" {{ $selectedStatus === 'pending_proposal' ? 'selected' : '' }}>Belum Diajukan Mentor</option>
                            <option value="submitted" {{ $selectedStatus === 'submitted' ? 'selected' : '' }}>Telah Diajukan Mentor</option>
                            <option value="transferred" {{ $selectedStatus === 'transferred' ? 'selected' : '' }}>Telah Ditransfer (Selesai)</option>
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
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Cari Mentee</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ $search }}" placeholder="Nama / rek..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 pl-8 pr-2 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <i class="fa-solid fa-search absolute left-2.5 top-2.5 text-xs text-slate-400"></i>
                            </div>
                        </div>
                        <button type="submit" class="py-2 px-3 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-xl text-xs font-bold transition flex items-center justify-center shrink-0 shadow-xs" title="Terapkan Filter">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                        @if($selectedBatch || $selectedStatus || $selectedBankStatus || $selectedAttendance || $search)
                            <a href="{{ route('mentor.stipends.index', ['month' => $selectedMonth]) }}" class="py-2 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold transition flex items-center justify-center shrink-0" title="Reset Filter">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Bulk Action Form & Table -->
            <form method="POST" action="{{ route('mentor.stipends.bulk-submit') }}" id="mentorBulkSubmitForm">
                @csrf
                
                <!-- Bulk Action Bar -->
                <div x-show="selectedStipends.length > 0" x-cloak class="mb-4 p-3 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 rounded-xl flex items-center justify-between gap-4 animate-fade-in">
                    <div class="flex items-center gap-2 text-xs text-emerald-800 dark:text-emerald-300 font-bold">
                        <i class="fa-solid fa-check-double text-base"></i>
                        <span><span x-text="selectedStipends.length"></span> penerima uang saku dipilih.</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengajukan rekomendasi pencairan uang saku untuk seluruh mentee terpilih ke HR / Finance?');" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-xs transition flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Ajukan Rekomendasi Terpilih ke HR</span>
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
                                <th class="p-3.5 text-center">Status Pengajuan</th>
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
                                            <i class="fa-solid fa-check text-emerald-500" title="Telah Selesai Transfer HR"></i>
                                        @endif
                                    </td>
                                    <td class="p-3.5">
                                        <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                            <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-700 dark:text-slate-300 shrink-0">
                                                {{ substr($stipend->user?->name ?? 'M', 0, 1) }}
                                            </div>
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
                                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300" title="Nama rekening sesuai nama KTP">
                                                        <i class="fa-solid fa-circle-check"></i> KTP Match
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.2 rounded text-[9px] font-bold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300" title="Nama rekening berbeda dengan KTP">
                                                        <i class="fa-solid fa-triangle-exclamation"></i> Cek KTP
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
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                                                    <i class="fa-solid fa-triangle-exclamation"></i> Belum Diisi
                                                </span>
                                                <div class="text-[10px] text-slate-400 dark:text-slate-500">Rekening belum diinput</div>
                                                <form method="POST" action="{{ route('mentor.stipends.send-reminder', $stipend->id) }}" class="mt-1">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Kirimkan notifikasi pengingat isi rekening ke {{ $stipend->user?->name }}?');" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-bold bg-amber-50 hover:bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:hover:bg-amber-900/70 dark:text-amber-300 border border-amber-300 dark:border-amber-800/80 transition cursor-pointer" title="Kirim notifikasi pengingat ke peserta">
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
                                                <i class="fa-solid fa-circle-check"></i> Telah Ditransfer HR
                                            </span>
                                        @elseif($stipend->mentor_submitted_at)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 text-[10px] font-bold">
                                                <i class="fa-solid fa-paper-plane"></i> Diajukan ke HR
                                            </span>
                                            <div class="text-[9px] text-slate-400 mt-0.5">{{ $stipend->mentor_submitted_at->format('d/m/Y H:i') }}</div>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 text-[10px] font-bold">
                                                <i class="fa-solid fa-clock"></i> Belum Diajukan
                                            </span>
                                        @endif

                                        @if($stipend->mentor_notes)
                                            <div class="mt-1">
                                                <span class="text-[10px] text-slate-500 italic block truncate max-w-[140px] mx-auto" title="{{ $stipend->mentor_notes }}">
                                                    "{{ $stipend->mentor_notes }}"
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('mentor.stipends.slip', $stipend->id) }}" target="_blank" class="p-2 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/60 dark:hover:bg-blue-900 text-blue-600 dark:text-blue-400 transition" title="Unduh Slip Uang Saku (PDF)">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
                                            @if($selectedMonth > date('Y-m'))
                                                <button type="button" disabled class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-600 cursor-not-allowed flex items-center gap-1" title="Periode belum berjalan">
                                                    <i class="fa-solid fa-lock text-[10px]"></i>
                                                    <span>Belum Dibuka</span>
                                                </button>
                                            @elseif($stipend->status !== 'transferred')
                                                <button type="button" @click="openProposalModal({{ json_encode($stipend) }})" class="px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-2xs flex items-center gap-1 {{ $stipend->mentor_submitted_at ? 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200' : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                                                    <i class="fa-solid {{ $stipend->mentor_submitted_at ? 'fa-pen-to-square' : 'fa-paper-plane' }}"></i>
                                                    <span>{{ $stipend->mentor_submitted_at ? 'Edit Catatan' : 'Ajukan Uang Saku' }}</span>
                                                </button>
                                            @else
                                                <span class="px-2.5 py-1 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10px] font-bold">
                                                    Ditransfer
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="p-8 text-center text-slate-500 dark:text-slate-400">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-xl mx-auto mb-2">
                                            <i class="fa-solid fa-hand-holding-dollar"></i>
                                        </div>
                                        <div class="font-bold text-sm text-slate-800 dark:text-slate-200">Tidak ada data uang saku</div>
                                        <p class="text-xs text-slate-400 mt-0.5">Belum ada peserta magang aktif pada periode atau filter yang dipilih.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($stipends->hasPages())
                    <div class="mt-4">
                        {{ $stipends->links() }}
                    </div>
                @endif
            </form>
        </div>

        <!-- =================== MODAL 1: SINGLE PROPOSAL SUBMIT MODAL =================== -->
        <div x-show="proposalModalOpen" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div @click.away="proposalModalOpen = false" class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base shrink-0">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Rekomendasi Uang Saku Mentee</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal" x-text="activeStipend?.user?.name + ' (' + activeStipend?.period_label + ')'"></p>
                        </div>
                    </div>
                    <button type="button" @click="proposalModalOpen = false" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <template x-if="activeStipend">
                    <form :action="'/mentor/stipends/' + activeStipend.id + '/submit'" method="POST" class="space-y-4">
                        @csrf
                        <!-- Mentee & Calculation Recap Card -->
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-2 text-xs">
                            <div class="flex justify-between font-semibold items-center">
                                <span class="text-slate-500">Rekening Tujuan:</span>
                                <template x-if="activeStipend.bank_account_number">
                                    <span class="text-slate-900 dark:text-white font-mono" x-text="activeStipend.bank_name + ' - ' + activeStipend.bank_account_number + ' (a.n ' + activeStipend.bank_account_holder + ')'"></span>
                                </template>
                                <template x-if="!activeStipend.bank_account_number">
                                    <span class="text-amber-600 font-bold inline-flex items-center gap-1">
                                        <i class="fa-solid fa-triangle-exclamation"></i> Belum Diisi Peserta
                                    </span>
                                </template>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Total Hadir:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200" x-text="activeStipend.present_days + ' Hari Kerja (Izin: ' + activeStipend.excused_days + ' hr, Alpa: ' + activeStipend.unexcused_days + ' hr)'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Nominal Pokok:</span>
                                <span class="text-slate-800 dark:text-slate-200" x-text="'Rp ' + Number(activeStipend.base_nominal).toLocaleString('id-ID')"></span>
                            </div>
                            <div class="flex justify-between" x-show="activeStipend.deduction_amount > 0">
                                <span class="text-rose-500 font-semibold">Potongan Absensi:</span>
                                <span class="text-rose-500 font-bold" x-text="'-Rp ' + Number(activeStipend.deduction_amount).toLocaleString('id-ID')"></span>
                            </div>
                            <div class="pt-2 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center font-bold text-sm">
                                <span class="text-slate-900 dark:text-white">Uang Saku Bersih:</span>
                                <span class="text-emerald-600 dark:text-emerald-400" x-text="'Rp ' + Number(activeStipend.net_amount).toLocaleString('id-ID')"></span>
                            </div>
                            <div class="pt-2 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                                <a :href="'/mentor/stipends/' + activeStipend.id + '/slip'" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/60 dark:hover:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-bold transition">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <span>Cetak / Unduh Slip Resmi (PDF)</span>
                                </a>
                            </div>
                        </div>

                        <!-- Mentor Notes / Recommendation -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Catatan / Rekomendasi Mentor untuk HR & Finance:
                            </label>
                            <textarea name="mentor_notes" rows="3" placeholder="Contoh: Kehadiran disiplin dan performa task bulanan tercapai sangat baik. Direkomendasikan untuk pencairan penuh." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-emerald-500 focus:border-emerald-500 p-2.5" x-text="activeStipend.mentor_notes"></textarea>
                            <p class="text-[11px] text-slate-400 mt-1">Catatan ini akan tampil pada dashboard HR Payroll dan notifikasi mentee.</p>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="proposalModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>Ajukan Rekomendasi ke HR</span>
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

        <!-- =================== MODAL 2: BULK SUBMIT MODAL =================== -->
        <div x-show="bulkModalOpen" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div @click.away="bulkModalOpen = false" class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base shrink-0">
                            <i class="fa-solid fa-paper-plane"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Ajukan Semua Rekomendasi Batch</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Periode {{ $availableMonths[$selectedMonth] ?? $selectedMonth }}</p>
                        </div>
                    </div>
                    <button type="button" @click="bulkModalOpen = false" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('mentor.stipends.bulk-submit') }}" class="space-y-4">
                    @csrf
                    @foreach($stipends as $stipend)
                        @if($stipend->status !== 'transferred')
                            <input type="hidden" name="stipend_ids[]" value="{{ $stipend->id }}">
                        @endif
                    @endforeach

                    <div class="p-3.5 rounded-xl bg-emerald-50/70 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/60 text-xs text-emerald-900 dark:text-emerald-200 space-y-1">
                        <div class="font-bold flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Konfirmasi Pengajuan Massal:</span>
                        </div>
                        <p class="font-normal text-slate-600 dark:text-slate-300">
                            Anda akan mengajukan rekomendasi pencairan uang saku untuk <strong>{{ $pendingProposalCount }} mentee</strong> pada periode <strong>{{ $availableMonths[$selectedMonth] ?? $selectedMonth }}</strong> ke tim HR & Finance.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Catatan Rekomendasi Massal (Opsional):
                        </label>
                        <textarea name="default_notes" rows="3" placeholder="Presensi dan kinerja seluruh mentee pada periode ini telah direview dan direkomendasikan untuk pencairan payroll." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-emerald-500 focus:border-emerald-500 p-2.5"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="bulkModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Kirim Rekomendasi ke HR</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- =================== MODAL 3: PROOF / BANK BOOK VIEWER =================== -->
        <div x-show="proofModalOpen" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div @click.away="proofModalOpen = false" class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full p-5 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white" x-text="currentProofTitle"></h3>
                    <button type="button" @click="proofModalOpen = false" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="flex items-center justify-center p-2 bg-slate-100 dark:bg-slate-800/60 rounded-xl overflow-hidden min-h-[300px]">
                    <img :src="currentProofUrl" alt="Dokumen Buku Tabungan" class="max-h-[70vh] max-w-full rounded-lg object-contain">
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a :href="currentProofUrl" target="_blank" download class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-download"></i>
                        <span>Buka / Unduh File Asli</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
