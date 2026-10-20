<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
                    <span class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-umbrella-beach"></i>
                    </span>
                    Portal Cuti & Izin Mandiri
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Ajukan cuti tahunan, izin sakit, atau dispensasi magang dan pantau status persetujuan secara transparan.
                </p>
            </div>
            <div>
                <button type="button" onclick="document.getElementById('applyLeaveModal').classList.remove('hidden')" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-xl shadow-2xs transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-amber-300"></i> Ajukan Cuti / Izin
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/80 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-emerald-800 dark:text-emerald-200 text-xs font-bold flex items-center gap-3 shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/80 border border-rose-200 dark:border-rose-800 rounded-2xl text-rose-800 dark:text-rose-200 text-xs font-bold flex items-center gap-3 shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="p-4 bg-rose-50 dark:bg-rose-950/80 border border-rose-200 dark:border-rose-800 rounded-2xl text-rose-800 dark:text-rose-200 text-xs font-bold space-y-1 shadow-2xs">
                    @foreach($errors->all() as $err)
                        <div class="flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-rose-500"></i> {{ $err }}</div>
                    @endforeach
                </div>
            @endif

            @if($isIntern && count($periodMonths) > 0)
                <!-- Period Navigation Tabs for Interns -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 border border-slate-200/80 dark:border-slate-700/80 shadow-2xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 dark:border-slate-700/60">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg shrink-0 border border-indigo-200 dark:border-indigo-800/80 shadow-2xs">
                                <i class="fa-solid fa-calendar-week"></i>
                            </div>
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">
                                        Periode Kuota Izin Magang
                                    </h3>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-3xs font-extrabold bg-emerald-50 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/80 whitespace-nowrap shrink-0">
                                        <i class="fa-solid fa-arrows-rotate text-emerald-500"></i> Reset Otomatis Tiap Awal Bulan
                                    </span>
                                </div>
                                <p class="text-3xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Toleransi 5 hari bebas potong berlaku per bulan. Pilih bulan untuk melihat pemakaian & sisa kuota:
                                </p>
                            </div>
                        </div>

                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-700/80 text-xs font-bold text-slate-600 dark:text-slate-300 self-start sm:self-auto shrink-0">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            <span>Melihat Periode: <strong class="text-indigo-600 dark:text-indigo-400">{{ $periodLabel }}</strong></span>
                        </div>
                    </div>

                    <!-- Month Buttons (Clean Wrapped Pills, No Scrollbar) -->
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach($periodMonths as $pm)
                            @php
                                $isActive = ($selectedMonth === $pm['key']);
                            @endphp
                            <a href="{{ route('candidate.leaves.index', ['month' => $pm['key'], 'filter_month' => $filterMonth]) }}"
                               class="group px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all duration-150 flex items-center gap-2 shrink-0 border {{ $isActive ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs shadow-indigo-500/20 scale-[1.02]' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80 dark:bg-slate-900/60 dark:hover:bg-slate-700/60 dark:text-slate-200 dark:border-slate-700/80' }}">
                                <span>{{ $pm['label'] }}</span>
                                @if($pm['is_current'])
                                    <span class="px-1.5 py-0.2 rounded-md text-4xs font-black uppercase tracking-wider {{ $isActive ? 'bg-white/20 text-white' : 'bg-indigo-100 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400' }}">
                                        Bulan Ini
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!$isIntern && count($availableYears ?? []) > 0)
                <!-- Year Navigation Bar for Employees -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 border border-slate-200/80 dark:border-slate-700/80 shadow-2xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 dark:border-slate-700/60">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg shrink-0 border border-indigo-200 dark:border-indigo-800/80 shadow-2xs">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">
                                        Periode Hak Cuti Tahunan Karyawan
                                    </h3>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-3xs font-extrabold bg-indigo-50 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/80 whitespace-nowrap shrink-0">
                                        <i class="fa-solid fa-arrows-rotate text-indigo-500"></i> Reset Otomatis Tiap 1 Januari
                                    </span>
                                </div>
                                <p class="text-3xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Hak cuti tahunan resmi berlaku per tahun kalender (Januari - Desember). Pilih tahun:
                                </p>
                            </div>
                        </div>

                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-700/80 text-xs font-bold text-slate-600 dark:text-slate-300 self-start sm:self-auto shrink-0">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            <span>Melihat Periode: <strong class="text-indigo-600 dark:text-indigo-400">{{ $periodLabel }}</strong></span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        @foreach($availableYears as $yr)
                            @php $isActive = ($selectedYear == $yr); @endphp
                            <a href="{{ route('candidate.leaves.index', ['year' => $yr, 'filter_month' => $filterMonth]) }}"
                               class="group px-4 py-2 rounded-xl text-xs font-extrabold transition-all duration-150 flex items-center gap-2 shrink-0 border {{ $isActive ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs shadow-indigo-500/20 scale-[1.02]' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80 dark:bg-slate-900/60 dark:hover:bg-slate-700/60 dark:text-slate-200 dark:border-slate-700/80' }}">
                                <span>Tahun {{ $yr }}</span>
                                @if($yr == \Carbon\Carbon::now()->year)
                                    <span class="px-1.5 py-0.2 rounded-md text-4xs font-black uppercase tracking-wider {{ $isActive ? 'bg-white/20 text-white' : 'bg-indigo-100 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400' }}">
                                        Tahun Ini
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Balance Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shrink-0 border border-indigo-200 dark:border-indigo-800/80">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalQuota }} <span class="text-xs font-bold text-slate-400">Hari</span></div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400">
                            @if($isIntern)
                                Batas Izin Bebas Potong
                                <span class="block text-3xs font-extrabold text-indigo-600 dark:text-indigo-400"><i class="fa-solid fa-arrows-rotate mr-1"></i>{{ $periodLabel }} (Reset Bulanan)</span>
                            @else
                                {{ $quotaTitle }}
                                <span class="block text-3xs font-extrabold text-indigo-600 dark:text-indigo-400"><i class="fa-solid fa-arrows-rotate mr-1"></i>Reset Tiap 1 Januari</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0 border border-emerald-200 dark:border-emerald-800/80">
                        <i class="fa-solid fa-battery-half"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $remainingDays }} <span class="text-xs font-bold text-slate-400">Hari</span></div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400">
                            @if($isIntern)
                                Sisa Kuota Bebas Potong
                            @else
                                Sisa Cuti Tahunan Tersedia
                            @endif
                            <span class="block text-3xs font-extrabold text-emerald-600 dark:text-emerald-400">Tersedia di {{ $periodLabel }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl shrink-0 border border-rose-200 dark:border-rose-800/80">
                        <i class="fa-solid fa-calendar-minus"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $usedDays }} <span class="text-xs font-bold text-slate-400">Hari</span></div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400">
                            @if($isIntern)
                                Izin Disetujui ({{ $periodLabel }})
                                @if(($rejectedDays ?? 0) > 0)
                                    <span class="block text-3xs font-extrabold text-rose-600 dark:text-rose-400"><i class="fa-solid fa-circle-xmark mr-1"></i>{{ $rejectedDays }} Hari Ditolak (Tidak Hadir/Alpa)</span>
                                @elseif($excessDays > 0)
                                    <span class="block text-3xs font-extrabold text-rose-600 dark:text-rose-400"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $excessDays }} Hari Melebihi Kuota</span>
                                @else
                                    <span class="block text-3xs font-medium text-slate-400">Bebas Potongan Uang Saku</span>
                                @endif
                            @else
                                Cuti Terpakai ({{ $periodLabel }})
                                <span class="block text-3xs font-medium text-slate-400">Disetujui HRD (Tahun Kalender)</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/80 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl shrink-0 border border-amber-200 dark:border-amber-800/80">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $pendingDays }} <span class="text-xs font-bold text-slate-400">Hari</span></div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400">
                            Menunggu Review HRD
                            <span class="block text-3xs font-extrabold text-amber-600 dark:text-amber-400">Periode {{ $periodLabel }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Policy Highlight Alert -->
            <div class="bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-200/80 dark:border-indigo-800/80 rounded-2xl p-4 flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 text-sm mt-0.5 shadow-2xs">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="text-xs text-indigo-950 dark:text-indigo-200 leading-relaxed">
                    @if($isIntern)
                        <div class="space-y-1.5">
                            <div class="flex flex-wrap items-center gap-2">
                                <strong class="font-black text-indigo-900 dark:text-indigo-100">Ketentuan Izin Peserta Magang (Sistem Reset Kuota Bulanan):</strong>
                                <span class="px-2 py-0.5 rounded-md text-4xs font-black uppercase bg-indigo-200/80 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                    <i class="fa-solid fa-rotate mr-0.5"></i> Reset Otomatis Tiap Awal Bulan
                                </span>
                            </div>
                            <p>
                                Peserta magang mendapatkan alokasi toleransi izin resmi/mendesak hingga <strong>{{ $policy->internship_max_excused_days }} hari kerja per periode/bulan</strong> tanpa pemotongan uang saku. Kuota 5 hari ini <strong>ter-reset otomatis setiap awal bulan baru</strong>.
                            </p>
                            <p class="text-3xs text-indigo-800/90 dark:text-indigo-300/80">
                                Apabila peserta mengajukan izin melebihi batas kuota toleransi {{ $policy->internship_max_excused_days }} hari dalam satu bulan (seperti pada bulan September: 5 hari disetujui bebas potong + 1 hari melebihi kuota), <strong>hari kelebihan tersebut langsung berstatus Ditolak</strong> dan pada presensi kalender otomatis tercatat sebagai <strong>Tidak Hadir Tanpa Keterangan (Alpa)</strong> dengan pemotongan uang saku proporsional sesuai nominal UMK. Ketika memasuki bulan berikutnya (Oktober), hak izin bebas potong Anda telah <strong>ter-reset kembali penuh menjadi {{ $policy->internship_max_excused_days }} hari</strong>.
                            </p>
                        </div>
                    @else
                        <div class="space-y-1.5">
                            <div class="flex flex-wrap items-center gap-2">
                                <strong class="font-black text-indigo-900 dark:text-indigo-100">Ketentuan Cuti Karyawan (Sistem Kuota Tahunan):</strong>
                                <span class="px-2 py-0.5 rounded-md text-4xs font-black uppercase bg-indigo-200/80 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                    <i class="fa-solid fa-arrows-rotate mr-0.5"></i> Reset Otomatis Tiap 1 Januari
                                </span>
                            </div>
                            <p>
                                Alokasi hak cuti tahunan resmi Anda adalah <strong>{{ $totalQuota }} hari kerja per tahun kalender</strong> ({{ $isPermanent ? 'Karyawan Tetap / PKWTT: 15 Hari' : 'Karyawan Kontrak / PKWT: 12 Hari' }} sesuai UU No. 13/2003 & UU Cipta Kerja pasal 79).
                            </p>
                            <p class="text-3xs text-indigo-800/90 dark:text-indigo-300/80">
                                Kuota cuti tahunan berlaku untuk <strong>periode 1 tahun berjalan (1 Januari s/d 31 Desember)</strong>. Ketika memasuki tahun baru (1 Januari), hak cuti Anda akan <strong>ter-reset otomatis kembali penuh</strong> sebanyak {{ $totalQuota }} hari kerja. Sisa cuti tahun sebelumnya tidak diakumulasikan ke tahun berikutnya (hangus), kecuali terdapat persetujuan khusus izin cuti tunda (*carry-over*) dari manajemen HRD.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Leave Requests History -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700 shadow-2xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Riwayat Pengajuan Cuti & Izin Anda</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Total izin disetujui seluruh periode: <strong class="text-indigo-600 dark:text-indigo-400">{{ $totalUsedAllTime }} Hari Kerja</strong>
                        </p>
                    </div>
                    @if($isIntern && count($periodMonths) > 0)
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-400 font-bold">Filter Tabel:</span>
                            <form method="GET" action="{{ route('candidate.leaves.index') }}" class="inline">
                                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                <select name="filter_month" onchange="this.form.submit()" class="text-xs font-bold rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-1.5 px-3">
                                    <option value="all" {{ ($filterMonth ?? 'all') === 'all' ? 'selected' : '' }}>Semua Bulan ({{ $totalUsedAllTime }} Hari)</option>
                                    @foreach($periodMonths as $pm)
                                        <option value="{{ $pm['key'] }}" {{ ($filterMonth ?? '') === $pm['key'] ? 'selected' : '' }}>{{ $pm['label'] }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50/80 dark:bg-slate-900/60 text-3xs uppercase font-extrabold text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-slate-700">
                            <tr>
                                <th class="py-3.5 px-4">Tipe Cuti / Izin</th>
                                <th class="py-3.5 px-4">Rentang Tanggal</th>
                                <th class="py-3.5 px-4 text-center">Durasi</th>
                                <th class="py-3.5 px-4">Alasan & Dokumen</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4">Catatan HRD</th>
                                <th class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 font-medium">
                            @forelse($leaveRequests as $leave)
                                @php $badge = $leave->status_badge; @endphp
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-2 font-bold text-slate-900 dark:text-white">
                                            <i class="fa-solid {{ $leave->leave_type_icon }}"></i>
                                            <span>{{ $leave->leave_type_label }}</span>
                                        </div>
                                        <div class="text-3xs text-slate-400 mt-0.5">Diajukan: {{ $leave->created_at->translatedFormat('d M Y H:i') }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-800 dark:text-slate-200">
                                            {{ $leave->start_date->translatedFormat('d M Y') }}
                                            @if($leave->start_date != $leave->end_date)
                                                <span class="text-slate-400 font-normal">s/d</span> {{ $leave->end_date->translatedFormat('d M Y') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 text-xs font-black border border-indigo-200/80 dark:border-indigo-800">
                                            {{ $leave->total_days }} Hari Kerja
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 max-w-xs">
                                        <p class="text-xs text-slate-700 dark:text-slate-300 line-clamp-2">{{ $leave->reason }}</p>
                                        @if($leave->attachment_path)
                                            <a href="{{ $leave->attachment_path }}" target="_blank" class="inline-flex items-center gap-1 text-3xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline mt-1">
                                                <i class="fa-solid fa-paperclip"></i> Lihat Berkas Lampiran
                                            </a>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-xl text-3xs font-black uppercase border {{ $badge['class'] }} flex items-center justify-center gap-1.5 w-max mx-auto shadow-2xs">
                                            <i class="fa-solid {{ $badge['icon'] }}"></i>
                                            {{ $badge['label'] }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 max-w-xs text-3xs text-slate-500 dark:text-slate-400">
                                        @if($leave->admin_notes)
                                            <div class="bg-slate-50 dark:bg-slate-900/50 p-2 rounded-lg border border-slate-200 dark:border-slate-800 italic">
                                                "{{ $leave->admin_notes }}"
                                                @if($leave->approver)
                                                    <div class="text-4xs font-bold not-italic text-slate-600 dark:text-slate-300 mt-0.5">— {{ $leave->approver->name }}</div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        @if($leave->status === 'pending')
                                            <form action="{{ route('candidate.leaves.cancel', $leave) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-slate-200 rounded-xl text-xs font-bold transition">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-3xs text-slate-400 font-semibold">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center mx-auto mb-3 text-slate-400 text-2xl">
                                            <i class="fa-solid fa-umbrella-beach"></i>
                                        </div>
                                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Belum Ada Pengajuan Cuti / Izin</p>
                                        <p class="text-xs text-slate-500 mt-1">Klik tombol <strong>"Ajukan Cuti / Izin"</strong> di atas untuk membuat permohonan baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($leaveRequests->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                        {{ $leaveRequests->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- MODAL AJUKAN CUTI / IZIN BARU -->
    <div id="applyLeaveModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 dark:border-slate-700 space-y-5 animate-in fade-in zoom-in-95 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-base">
                        <i class="fa-solid fa-calendar-plus"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-base text-slate-900 dark:text-white">Formulir Pengajuan Cuti / Izin</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Permohonan akan diteruskan ke Tim HRD</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('applyLeaveModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('candidate.leaves.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                @if($isIntern)
                    <div class="p-3.5 bg-amber-50/80 dark:bg-amber-950/40 rounded-2xl border border-amber-200/80 dark:border-amber-800/80 text-3xs text-amber-900 dark:text-amber-200 space-y-1">
                        <div class="font-extrabold flex items-center gap-1.5 text-amber-800 dark:text-amber-300">
                            <i class="fa-solid fa-circle-exclamation text-amber-500"></i> Ketentuan Kuota Izin Magang:
                        </div>
                        <p class="leading-relaxed">
                            Batas toleransi izin bebas potong adalah <strong>{{ $totalQuota }} hari kerja / bulan</strong> (Sisa bulan {{ $periodLabel }}: <strong>{{ $remainingDays }} hari</strong>). Pengajuan yang melebihi batas kuota akan <strong>langsung ditolak</strong> dan dicatat sebagai <strong>Tidak Hadir (Alpa)</strong> di logbook dengan pemotongan uang saku.
                        </p>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                        Tipe Cuti / Izin <span class="text-rose-500">*</span>
                    </label>
                    <select name="leave_type" required class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold py-2.5">
                        @if($isIntern)
                            <option value="academic_leave">🎓 Izin Akademik / Kampus (Sidang, Ujian, Bimbingan, Wisuda)</option>
                            <option value="family_event">👨‍👩‍👧‍👦 Izin Acara Keluarga / Keperluan Mendesak</option>
                            <option value="sick_leave">🩺 Izin Sakit (Sick Leave)</option>
                            <option value="internship_permission">📄 Izin Magang / Dispensasi Khusus Lainnya</option>
                        @else
                            <option value="annual_leave">💼 Cuti Tahunan (Annual Leave)</option>
                            <option value="sick_leave">🩺 Izin Sakit (Sick Leave)</option>
                            <option value="academic_leave">🎓 Izin Akademik / Kampus / Wisuda</option>
                            <option value="family_event">👨‍👩‍👧‍👦 Izin Acara Keluarga / Keperluan Mendesak</option>
                            <option value="special_leave">💍 Cuti Khusus / Izin Berbayar (Menikah, Khitanan, Duka Cita)</option>
                            <option value="maternity_leave">🤰 Cuti Melahirkan / Keguguran</option>
                        @endif
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                            Tanggal Mulai <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="modalStartDate" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold py-2.5">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                            Tanggal Selesai <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="modalEndDate" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold py-2.5">
                    </div>
                </div>

                <div class="p-3 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200/80 dark:border-slate-700 text-xs flex items-center justify-between">
                    <span class="font-bold text-slate-600 dark:text-slate-400">Estimasi Durasi Hari Kerja:</span>
                    <span id="calculatedDaysBadge" class="font-black text-indigo-600 dark:text-indigo-400">1 Hari Kerja</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                        Alasan / Keterangan Cuti <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="reason" rows="3" required placeholder="Jelaskan alasan izin atau keperluan cuti Anda secara rinci..." class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-medium p-3">{{ old('reason') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                        Lampiran Dokumen Bukti (Opsional / Wajib untuk Sakit & Dispensasi Kampus)
                    </label>
                    <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-950/80 dark:file:text-indigo-300">
                    <p class="text-3xs text-slate-400 mt-1">Format: PDF, JPG, PNG (Maks. 5 MB)</p>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('applyLeaveModal').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-xl shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-paper-plane text-amber-300"></i> Ajukan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateDurationEstimate() {
            const startVal = document.getElementById('modalStartDate').value;
            const endVal = document.getElementById('modalEndDate').value;
            const badge = document.getElementById('calculatedDaysBadge');

            if (!startVal || !endVal) return;

            const start = new Date(startVal);
            const end = new Date(endVal);

            if (end < start) {
                badge.innerText = 'Tanggal tidak valid';
                badge.className = 'font-black text-rose-500';
                return;
            }

            let count = 0;
            let cur = new Date(start);
            while (cur <= end) {
                const day = cur.getDay();
                if (day !== 0 && day !== 6) { // Exclude Sunday (0) and Saturday (6)
                    count++;
                }
                cur.setDate(cur.getDate() + 1);
            }

            badge.innerText = `${count} Hari Kerja`;
            badge.className = 'font-black text-indigo-600 dark:text-indigo-400';
        }

        document.getElementById('modalStartDate').addEventListener('change', updateDurationEstimate);
        document.getElementById('modalEndDate').addEventListener('change', updateDurationEstimate);
    </script>
</x-app-layout>
