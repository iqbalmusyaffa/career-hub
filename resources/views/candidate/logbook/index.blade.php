<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xs shadow-xs shrink-0">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <h1 class="text-lg sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Catatan Kehadiran & Presensi Magang
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Lihat catatan kehadiran, isi jurnal harian, dan pantau pemenuhan jam kerja Anda.
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 flex-wrap sm:flex-nowrap">
                <!-- Live Server Time -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 text-xs font-medium border border-slate-200 dark:border-slate-800 shadow-2xs" x-data="{
                    currentTime: '',
                    updateClock() {
                        const now = new Date();
                        const hours = String(now.getHours()).padStart(2, '0');
                        const minutes = String(now.getMinutes()).padStart(2, '0');
                        const seconds = String(now.getSeconds()).padStart(2, '0');
                        this.currentTime = `${hours}.${minutes}.${seconds} WIB (GMT+7)`;
                    }
                }" x-init="updateClock(); setInterval(() => updateClock(), 1000)">
                    <i class="fa-regular fa-clock text-slate-400"></i>
                    <span>Waktu Server <span x-text="currentTime" class="font-mono font-bold text-slate-800 dark:text-slate-200"></span></span>
                </div>

                <!-- Sinkronkan Status CTA -->
                <a href="{{ route('candidate.logbook.sync', array_filter(['month' => request('month', $startDate->format('Y-m')), 'candidate_id' => $isAdminOrStaff ? $user->id : null])) }}" class="inline-flex items-center gap-2 py-2 px-3.5 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold transition shadow-2xs" title="Sinkronkan status presensi, hari kerja, dan nominal uang saku">
                    <i class="fa-solid fa-arrows-rotate text-xs text-blue-600 dark:text-blue-400"></i>
                    <span>Sinkronkan Status</span>
                </a>

                <!-- Isi Presensi CTA -->
                <a href="{{ route('candidate.logbook.show', ['date' => now()->format('Y-m-d')]) }}" class="inline-flex items-center gap-2 py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs transition">
                    <i class="fa-solid fa-plus-circle text-xs"></i>
                    <span>Isi Presensi Hari Ini</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if($isAdminOrStaff && $candidateList->isNotEmpty())
                <!-- Staff / Admin Candidate Selector Banner -->
                <div class="p-4 rounded-2xl bg-indigo-50/90 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-2xs">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-base font-bold shrink-0 shadow-xs">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-indigo-950 dark:text-indigo-200 uppercase tracking-wider">Mode Pratinjau {{ $currentUser->roles->pluck('name')->first() }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-200 dark:bg-indigo-900/80 text-indigo-900 dark:text-indigo-200">
                                    {{ $user->name }}
                                </span>
                            </div>
                            <p class="text-xs text-indigo-700 dark:text-indigo-300 font-normal mt-0.5">
                                Menampilkan logbook & presensi harian untuk peserta magang terpilih.
                            </p>
                        </div>
                    </div>
                    
                    <form method="GET" action="{{ route('candidate.logbook.index') }}" class="flex items-center gap-2 self-stretch sm:self-auto">
                        <input type="hidden" name="month" value="{{ request('month', $startDate->format('Y-m')) }}">
                        <label for="candidate_select" class="text-xs font-bold text-indigo-900 dark:text-indigo-300 shrink-0">Pilih Peserta:</label>
                        <select id="candidate_select" name="candidate_id" onchange="this.form.submit()" class="w-full sm:w-auto text-xs rounded-xl border-indigo-300 dark:border-indigo-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-indigo-500 py-2 font-medium">
                            @foreach($candidateList as $cand)
                                <option value="{{ $cand->id }}" {{ $user->id == $cand->id ? 'selected' : '' }}>
                                    {{ $cand->name }} ({{ $cand->email }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @endif

            <!-- Top Summary Widgets (Progress & Hours Logged Tracker) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Attendance Percentage Widget -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest block">PERSENTASE KEHADIRAN ({{ $user->name }})</span>
                            <div class="w-9 h-9 bg-blue-50 dark:bg-blue-950/60 rounded-xl border border-blue-100 dark:border-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-base shrink-0">
                                📊
                            </div>
                        </div>

                        <div class="mt-2 flex flex-wrap items-baseline gap-3">
                            <span class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $attendancePercentage }}%</span>
                            <span class="text-[10px] font-bold px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800 rounded-full">
                                Lolos Syarat Kelulusan
                            </span>
                        </div>

                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 font-normal">
                            Standar kehadiran terpenuhi ({{ $totalFilled }}/{{ $totalWorkingDays }} Hari Kerja).
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden p-0.5 border border-slate-200/60 dark:border-slate-700">
                        <div class="bg-gradient-to-r from-blue-600 to-emerald-500 h-1 rounded-full transition-all duration-1000" style="width: {{ $attendancePercentage }}%"></div>
                    </div>
                </div>

                <!-- Hours Logged Tracker Widget -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">AKUMULASI JAM KERJA</span>
                            <div class="w-9 h-9 bg-emerald-50 dark:bg-emerald-950/60 rounded-xl border border-emerald-100 dark:border-emerald-900 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-bold text-base shrink-0">
                                ⏱️
                            </div>
                        </div>

                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $totalWorkHoursLogged }}</span>
                            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">/ {{ $targetWorkHours }} Jam Kerja</span>
                        </div>

                        <div class="mt-2 flex items-center gap-2">
                            @if($totalWorkHoursLogged >= ($targetWorkHours * 0.8))
                                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">TARGET 80% TERLAMPAUI</span>
                            @else
                                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                                <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">{{ round(($totalWorkHoursLogged / max(1, $targetWorkHours)) * 100) }}% TERCAPAI</span>
                            @endif
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden p-0.5 border border-slate-200/60 dark:border-slate-700">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-1 rounded-full transition-all duration-1000" style="width: {{ min(100, round(($totalWorkHoursLogged / max(1, $targetWorkHours)) * 100)) }}%"></div>
                    </div>
                </div>

            </div>

            <!-- Calendar Container Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 overflow-hidden">
                
                <!-- Period Header Bar -->
                <div class="p-4 bg-slate-50 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <a href="{{ route('candidate.logbook.index', array_filter(['month' => $prevMonth, 'candidate_id' => $isAdminOrStaff ? $user->id : null])) }}" class="p-2 rounded-xl bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800 transition shadow-2xs" title="Bulan Sebelumnya">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </a>

                    <div class="text-center">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-900 mb-1">
                            <i class="fa-solid fa-layer-group text-[10px]"></i>
                            <span>{{ $periodName }}</span>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                            {{ $monthLabel }}
                        </h3>
                        <p class="text-[11px] font-medium text-slate-400 dark:text-slate-500">
                            Periode: {{ $periodStartDate->translatedFormat('d M Y') }} – {{ $periodEndDate->translatedFormat('d M Y') }}
                        </p>
                    </div>

                    <a href="{{ route('candidate.logbook.index', array_filter(['month' => $nextMonth, 'candidate_id' => $isAdminOrStaff ? $user->id : null])) }}" class="p-2 rounded-xl bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800 transition shadow-2xs" title="Bulan Berikutnya">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                </div>

                <!-- Calendar Grid Matrix -->
                <div class="p-4 sm:p-6">
                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-2xs">
                        
                        <!-- Days of Week Header -->
                        <div class="grid grid-cols-7 text-center bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400">
                            <div>Sen</div>
                            <div>Sel</div>
                            <div>Rab</div>
                            <div>Kam</div>
                            <div>Jum</div>
                            <div>Sab</div>
                            <div>Min</div>
                        </div>

                        <!-- Dynamic Calendar Dates Matrix -->
                        <div class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach($matrix as $week)
                                <div class="grid grid-cols-7 text-center divide-x divide-slate-200 dark:divide-slate-800">
                                    @foreach($week as $item)
                                        @php
                                            $dateStr = $item['date'];
                                            $dateCarbon = \Carbon\Carbon::parse($dateStr)->startOfDay();
                                            $todayCarbon = \Carbon\Carbon::today();
                                            $entry = $logbooks[$dateStr] ?? null;
                                            $unlockReq = $unlockRequestsMap[$dateStr] ?? null;
                                            $isUnlocked = $unlockReq && $unlockReq->unlocked_until && $unlockReq->unlocked_until->isFuture();
                                            $isWeekend = $item['isWeekend'] ?? false;
                                            $isPadding = $item['isPadding'] ?? false;
                                            $highlight = $item['highlight'] ?? false;
                                            
                                            $holidayObj = $holidaysMap[$dateStr] ?? null;
                                            $isOverridden = $holidayObj && isset($overridesMap[$holidayObj->id]);
                                            
                                            $isNationalHoliday = $holidayObj && $holidayObj->type === 'national_holiday';
                                            $isCutiBersama = $holidayObj && $holidayObj->type === 'cuti_bersama' && !$isOverridden;
                                            $isCompanyHoliday = $holidayObj && $holidayObj->type === 'company_holiday';
                                            $isPast = $dateCarbon->lessThan($todayCarbon);
                                            $isBeforeStart = $dateCarbon->lessThan($periodStartDate->copy()->startOfDay());
                                            $isAfterEnd = $dateCarbon->greaterThan($periodEndDate->copy()->startOfDay());
                                            $isOutsidePeriod = $isBeforeStart || $isAfterEnd;

                                            if ($isNationalHoliday) {
                                                $status = 'national_holiday';
                                            } elseif ($isCutiBersama) {
                                                $status = 'cuti_bersama';
                                            } elseif ($isCompanyHoliday) {
                                                $status = 'company_holiday';
                                            } elseif ($entry) {
                                                $status = $entry->status;
                                            } elseif ($isUnlocked) {
                                                $status = 'unlocked';
                                            } elseif ($isWeekend) {
                                                $status = 'weekend';
                                            } elseif ($isPadding) {
                                                $status = 'padding';
                                            } elseif ($isOutsidePeriod) {
                                                $status = 'outside_period';
                                            } elseif ($isPast) {
                                                $status = 'absent';
                                            } else {
                                                $status = 'unfilled';
                                            }

                                            $cellTooltip = '';
                                            if ($holidayObj) {
                                                $typeStr = $holidayObj->type === 'company_holiday' ? 'Libur Khusus Perusahaan' : ($holidayObj->type === 'cuti_bersama' ? 'Cuti Bersama Pemerintah' : 'Libur Nasional');
                                                $cellTooltip = $holidayObj->name . ' (' . $typeStr . ')';
                                            } elseif ($status === 'outside_period') {
                                                $cellTooltip = $isBeforeStart ? 'Sebelum Periode Magang Dimulai' : 'Setelah Periode Magang Selesai';
                                            } elseif ($status === 'absent') {
                                                $cellTooltip = 'Terlewat / Tidak Hadir (Terkunci)';
                                            } elseif ($status === 'unlocked') {
                                                $cellTooltip = 'Dispensasi Presensi Disetujui';
                                            }
                                        @endphp

                                        <a href="{{ route('candidate.logbook.show', array_filter(['date' => $dateStr, 'candidate_id' => $isAdminOrStaff ? $user->id : null])) }}" 
                                           title="{{ $cellTooltip }}"
                                           class="py-3 px-1 hover:bg-blue-50/70 dark:hover:bg-blue-950/40 transition flex flex-col items-center justify-center gap-1 min-h-[64px] relative group {{ $isPadding ? 'opacity-40 bg-slate-50/40 dark:bg-slate-900/20' : ($highlight ? 'bg-blue-50/80 dark:bg-blue-950/50' : ($status === 'unlocked' ? 'bg-emerald-50/50 dark:bg-emerald-950/30' : ($isNationalHoliday ? 'bg-rose-50/40 dark:bg-rose-950/30' : ($isCutiBersama ? 'bg-amber-50/40 dark:bg-amber-950/30' : ($isCompanyHoliday ? 'bg-purple-50/40 dark:bg-purple-950/30' : ($isWeekend ? 'bg-slate-50/60 dark:bg-slate-900/40' : ($status === 'outside_period' ? 'opacity-40 bg-slate-50/40 dark:bg-slate-900/20' : ($status === 'absent' ? 'bg-rose-50/20 dark:bg-rose-950/10' : '')))))))) }}">
                                            
                                            @if($highlight)
                                                <div class="w-6 h-6 rounded-full border-2 border-blue-600 text-blue-600 dark:text-blue-400 font-bold text-xs flex items-center justify-center bg-white dark:bg-slate-800 shadow-2xs">
                                                    {{ $item['day'] }}
                                                </div>
                                            @else
                                                <span class="text-xs font-semibold {{ $isNationalHoliday ? 'text-rose-600 dark:text-rose-400' : ($isCutiBersama ? 'text-amber-600 dark:text-amber-400' : ($isCompanyHoliday ? 'text-purple-600 dark:text-purple-400' : ($status === 'absent' ? 'text-rose-600 dark:text-rose-400' : ($status === 'unlocked' ? 'text-emerald-600 dark:text-emerald-400' : ($status === 'outside_period' ? 'text-slate-400 dark:text-slate-600' : 'text-slate-700 dark:text-slate-300'))))) }}">{{ $item['day'] }}</span>
                                            @endif

                                            @if($status === 'approved')
                                                <i class="fa-solid fa-check text-emerald-500 text-xs font-bold"></i>
                                            @elseif($status === 'pending')
                                                <span class="w-2 h-2 bg-blue-600 rotate-45 inline-block"></span>
                                            @elseif($status === 'action_required')
                                                <span class="w-0 h-0 border-l-[4px] border-l-transparent border-r-[4px] border-r-transparent border-b-[8px] border-b-amber-600 inline-block"></span>
                                            @elseif($status === 'rejected')
                                                <i class="fa-solid fa-xmark text-red-600 text-xs font-bold"></i>
                                            @elseif($status === 'unlocked')
                                                <i class="fa-solid fa-lock-open text-emerald-500 text-xs" title="Dispensasi Disetujui (Buka Kunci)"></i>
                                            @elseif($status === 'national_holiday')
                                                <span class="w-2 h-2 bg-rose-600 rounded-full inline-block" title="{{ $holidayObj->name ?? 'Libur Nasional' }}"></span>
                                            @elseif($status === 'cuti_bersama')
                                                <span class="w-2 h-2 bg-amber-500 rounded-full inline-block" title="{{ $holidayObj->name ?? 'Cuti Bersama' }}"></span>
                                            @elseif($status === 'company_holiday')
                                                <span class="w-2 h-2 bg-purple-600 rounded-full inline-block" title="{{ $holidayObj->name ?? 'Libur Khusus Perusahaan' }}"></span>
                                            @elseif($status === 'outside_period')
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-700 inline-block opacity-40" title="Di Luar Periode Magang"></span>
                                            @elseif($status === 'absent')
                                                <span class="w-2 h-2 bg-rose-400 rounded-full inline-block opacity-75" title="Terlewat / Lupa Absen (Terkunci)"></span>
                                            @elseif($status === 'weekend')
                                                <span class="w-2 h-2 bg-slate-800 dark:bg-slate-400 rounded-2xs inline-block"></span>
                                            @elseif($status === 'padding')
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-700 inline-block opacity-40"></span>
                                            @else
                                                <span class="w-2 h-2 rounded-full border border-slate-300 dark:border-slate-600 inline-block"></span>
                                            @endif
                                        </a>
                                    @endforeach

                                    @if(count($week) < 7)
                                        <div class="col-span-{{ 7 - count($week) }} bg-slate-50/30 dark:bg-slate-900/20"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Legend Items -->
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <div class="flex flex-wrap items-center justify-between gap-y-2.5 gap-x-4 text-xs font-semibold">
                            
                            <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                <i class="fa-solid fa-check text-emerald-500"></i>
                                <span>Disetujui</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                <span class="w-2 h-2 bg-rose-600 rounded-full inline-block"></span>
                                <span>Libur Nasional</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                <span class="w-2 h-2 bg-amber-500 rounded-full inline-block"></span>
                                <span>Cuti Bersama</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                <span class="w-2 h-2 bg-purple-600 rounded-full inline-block"></span>
                                <span>Libur Perusahaan</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                <span class="w-2 h-2 bg-rose-400 rounded-full inline-block opacity-75"></span>
                                <span>Tidak Hadir</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                <i class="fa-solid fa-xmark text-red-600"></i>
                                <span>Ditolak</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                <span class="w-0 h-0 border-l-[4px] border-l-transparent border-r-[4px] border-r-transparent border-b-[8px] border-b-amber-600 inline-block"></span>
                                <span>Perlu Tindakan</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                <span class="w-2 h-2 bg-blue-600 rotate-45 inline-block"></span>
                                <span>Menunggu Mentor</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                <span class="w-2 h-2 rounded-full border border-slate-400 inline-block"></span>
                                <span>Belum Diisi</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                <span class="w-2 h-2 bg-slate-800 dark:bg-slate-400 rounded-2xs inline-block"></span>
                                <span>Weekend</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                                <i class="fa-solid fa-lock-open text-emerald-500"></i>
                                <span>Dispensasi</span>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
