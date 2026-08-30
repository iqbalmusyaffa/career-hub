<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-md shadow-blue-500/20 text-xl font-black shrink-0">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <h2 class="font-black text-xl text-slate-900 dark:text-white leading-tight">
                        Catatan Kehadiran & Presensi Magang
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">
                        Lihat catatan kehadiran dan laporan harian Anda.
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
                <a href="{{ route('candidate.logbook.evaluation') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-md shadow-indigo-600/30 transition">
                    <i class="fa-solid fa-award"></i>
                    <span>Transkrip Evaluasi Akhir</span>
                </a>
                <a href="{{ route('candidate.logbook.show', ['date' => now()->format('Y-m-d')]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-extrabold shadow-md shadow-blue-600/30 transition">
                    <i class="fa-solid fa-plus-circle text-sm"></i>
                    <span>Isi Presensi Hari Ini</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        currentTime: '',
        updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            this.currentTime = `${hours}.${minutes}.${seconds} WIB (GMT+7)`;
        }
    }" x-init="updateClock(); setInterval(() => updateClock(), 1000)">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Top Summary Widgets (Progress & Hours Logged Tracker) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Attendance Percentage Widget -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-3xs font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest block">PERSENTASE KEHADIRAN</span>
                            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-950/60 rounded-2xl border border-blue-100 dark:border-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400 font-black text-lg shrink-0">
                                📊
                            </div>
                        </div>

                        <div class="mt-2 flex flex-wrap items-baseline gap-3">
                            <span class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $attendancePercentage }}%</span>
                            <span class="text-3xs font-black px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800 rounded-full">
                                Lolos Syarat Kelulusan Magang
                            </span>
                        </div>

                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 font-medium">
                            Standar kehadiran institusi & kampus terpenuhi dengan sangat baik (22/22 Hari).
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden p-0.5 border border-slate-200 dark:border-slate-600">
                        <div class="bg-gradient-to-r from-blue-600 to-emerald-500 h-1.5 rounded-full transition-all duration-1000" style="width: {{ $attendancePercentage }}%"></div>
                    </div>
                </div>

                <!-- Hours Logged Tracker Widget -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-3xs font-black text-slate-400 uppercase tracking-widest block">AKUMULASI JAM KERJA MAGANG</span>
                            <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-950/60 rounded-2xl border border-emerald-100 dark:border-emerald-900 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-black text-lg shrink-0">
                                ⏱️
                            </div>
                        </div>

                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $totalWorkHoursLogged }}</span>
                            <span class="text-xs font-bold text-slate-400 dark:text-slate-400">/ {{ $targetWorkHours }} Jam Kerja</span>
                        </div>

                        <div class="mt-2 flex items-center gap-2">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-3xs font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">TARGET 80% TERLAMPAUI</span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden p-0.5 border border-slate-200 dark:border-slate-600">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-1.5 rounded-full transition-all duration-1000" style="width: {{ min(100, round(($totalWorkHoursLogged / max(1, $targetWorkHours)) * 100)) }}%"></div>
                    </div>
                </div>

            </div>

            <!-- Calendar Container Card (Matching Image 1 Exact Layout) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                
                <!-- Period Header Bar -->
                <div class="p-4 bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <button type="button" class="p-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-700 transition shadow-2xs">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>

                    <div class="text-center">
                        <h3 class="font-extrabold text-sm text-slate-900 dark:text-white">Periode 1</h3>
                        <p class="text-3xs font-extrabold text-slate-500 dark:text-slate-400 tracking-wider uppercase mt-0.5">
                            10 AGU 2026 - 9 SEP 2026
                        </p>
                    </div>

                    <button type="button" class="p-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-700 transition shadow-2xs">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>

                <!-- Calendar Grid Matrix -->
                <div class="p-6">
                    <div class="border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-2xs">
                        
                        <!-- Days of Week Header -->
                        <div class="grid grid-cols-7 text-center bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-700 py-3 text-xs font-extrabold text-slate-600 dark:text-slate-400">
                            <div>Sen</div>
                            <div>Sel</div>
                            <div>Rab</div>
                            <div>Kam</div>
                            <div>Jum</div>
                            <div>Sab</div>
                            <div>Min</div>
                        </div>

                        @php
                            $matrix = [
                                [
                                    ['day' => 10, 'date' => '2026-08-10'],
                                    ['day' => 11, 'date' => '2026-08-11'],
                                    ['day' => 12, 'date' => '2026-08-12'],
                                    ['day' => 13, 'date' => '2026-08-13'],
                                    ['day' => 14, 'date' => '2026-08-14'],
                                    ['day' => 15, 'date' => '2026-08-15', 'isWeekend' => true],
                                    ['day' => 16, 'date' => '2026-08-16', 'isWeekend' => true],
                                ],
                                [
                                    ['day' => 17, 'date' => '2026-08-17', 'isWeekend' => true],
                                    ['day' => 18, 'date' => '2026-08-18'],
                                    ['day' => 19, 'date' => '2026-08-19'],
                                    ['day' => 20, 'date' => '2026-08-20'],
                                    ['day' => 21, 'date' => '2026-08-21'],
                                    ['day' => 22, 'date' => '2026-08-22', 'isWeekend' => true],
                                    ['day' => 23, 'date' => '2026-08-23', 'isWeekend' => true],
                                ],
                                [
                                    ['day' => 24, 'date' => '2026-08-24', 'highlight' => true],
                                    ['day' => 25, 'date' => '2026-08-25'],
                                    ['day' => 26, 'date' => '2026-08-26'],
                                    ['day' => 27, 'date' => '2026-08-27'],
                                    ['day' => 28, 'date' => '2026-08-28'],
                                    ['day' => 29, 'date' => '2026-08-29', 'isWeekend' => true],
                                    ['day' => 30, 'date' => '2026-08-30', 'isWeekend' => true],
                                ],
                                [
                                    ['day' => 31, 'date' => '2026-08-31'],
                                    ['day' => 1, 'date' => '2026-09-01'],
                                    ['day' => 2, 'date' => '2026-09-02'],
                                    ['day' => 3, 'date' => '2026-09-03'],
                                    ['day' => 4, 'date' => '2026-09-04'],
                                    ['day' => 5, 'date' => '2026-09-05', 'isWeekend' => true],
                                    ['day' => 6, 'date' => '2026-09-06', 'isWeekend' => true],
                                ],
                                [
                                    ['day' => 7, 'date' => '2026-09-07'],
                                    ['day' => 8, 'date' => '2026-09-08'],
                                    ['day' => 9, 'date' => '2026-09-09'],
                                ]
                            ];
                        @endphp

                        <!-- Dynamic Calendar Dates Matrix -->
                        <div class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($matrix as $week)
                                <div class="grid grid-cols-7 text-center divide-x divide-slate-200 dark:divide-slate-700">
                                    @foreach($week as $item)
                                        @php
                                            $dateStr = $item['date'];
                                            $dateCarbon = \Carbon\Carbon::parse($dateStr)->startOfDay();
                                            $todayCarbon = \Carbon\Carbon::today();
                                            $entry = $logbooks[$dateStr] ?? null;
                                            $isWeekend = $item['isWeekend'] ?? false;
                                            $highlight = $item['highlight'] ?? false;
                                            
                                            $holidayObj = $holidaysMap[$dateStr] ?? null;
                                            $isOverridden = $holidayObj && isset($overridesMap[$holidayObj->id]);
                                            
                                            $isHoliday = ($holidayObj && !$isOverridden) || $isWeekend;
                                            $isPast = $dateCarbon->lessThan($todayCarbon);

                                            if ($entry) {
                                                $status = $entry->status;
                                            } elseif ($isHoliday) {
                                                $status = 'weekend';
                                            } elseif ($isPast) {
                                                $status = 'absent'; // Otomatis Tidak Hadir / Lupa Absen (Terkunci)
                                            } else {
                                                $status = 'unfilled';
                                            }
                                        @endphp

                                        <a href="{{ route('candidate.logbook.show', ['date' => $dateStr]) }}" 
                                           class="py-3.5 px-1 hover:bg-blue-50/70 dark:hover:bg-blue-950/40 transition flex flex-col items-center justify-center gap-1.5 min-h-[68px] {{ $highlight ? 'bg-blue-50/80 dark:bg-blue-950/50' : ($isWeekend ? 'bg-slate-50/60 dark:bg-slate-900/40' : ($status === 'absent' ? 'bg-rose-50/30 dark:bg-rose-950/20' : '')) }}">
                                            
                                            @if($highlight)
                                                <div class="w-7 h-7 rounded-full border-2 border-blue-600 text-blue-600 dark:text-blue-400 font-black text-xs flex items-center justify-center bg-white dark:bg-slate-800 shadow-2xs">
                                                    {{ $item['day'] }}
                                                </div>
                                            @else
                                                <span class="text-xs font-extrabold {{ $status === 'absent' ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-300' }}">{{ $item['day'] }}</span>
                                            @endif

                                            @if($status === 'approved')
                                                <i class="fa-solid fa-check text-emerald-500 text-xs font-bold"></i>
                                            @elseif($status === 'pending')
                                                <span class="w-2.5 h-2.5 bg-blue-600 rotate-45 inline-block"></span>
                                            @elseif($status === 'action_required')
                                                <span class="w-0 h-0 border-l-[5px] border-l-transparent border-r-[5px] border-r-transparent border-b-[9px] border-b-amber-600 inline-block"></span>
                                            @elseif($status === 'rejected')
                                                <i class="fa-solid fa-xmark text-red-600 text-xs font-bold"></i>
                                            @elseif($status === 'absent')
                                                <span class="w-2.5 h-2.5 bg-rose-500 rounded-full inline-block" title="Terlewat / Lupa Absen (Terkunci)"></span>
                                            @elseif($status === 'weekend')
                                                <span class="w-2.5 h-2.5 bg-slate-800 dark:bg-slate-400 rounded-2xs inline-block"></span>
                                            @else
                                                <span class="w-2.5 h-2.5 rounded-full border-2 border-slate-300 dark:border-slate-600 inline-block"></span>
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

                    <!-- Legend Items (Matching Image 1 Legend Bar) -->
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700/60">
                        <div class="flex flex-wrap items-center justify-between gap-y-3 gap-x-4 text-xs font-extrabold">
                            
                            <div class="flex items-center gap-1.5 text-slate-800 dark:text-slate-200">
                                <i class="fa-solid fa-check text-emerald-500"></i>
                                <span>Disetujui</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-800 dark:text-slate-200">
                                <span class="w-2.5 h-2.5 bg-rose-500 rounded-full inline-block"></span>
                                <span>Tidak Hadir</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-800 dark:text-slate-200">
                                <i class="fa-solid fa-xmark text-red-600"></i>
                                <span>Kehadiran Ditolak</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-800 dark:text-slate-200">
                                <span class="w-0 h-0 border-l-[5px] border-l-transparent border-r-[5px] border-r-transparent border-b-[9px] border-b-amber-600 inline-block"></span>
                                <span>Perlu Tindakan Anda</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-800 dark:text-slate-200">
                                <span class="w-2.5 h-2.5 bg-blue-600 rotate-45 inline-block"></span>
                                <span>Menunggu Tindakan Mentor</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-800 dark:text-slate-200">
                                <span class="w-2.5 h-2.5 rounded-full border-2 border-slate-400 inline-block"></span>
                                <span>Belum Diisi</span>
                            </div>

                            <div class="flex items-center gap-1.5 text-slate-800 dark:text-slate-200">
                                <span class="w-2.5 h-2.5 bg-slate-800 dark:bg-slate-400 rounded-2xs inline-block"></span>
                                <span>Hari Libur</span>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

            <!-- Server Time Pill -->
            <div class="flex justify-center">
                <div class="inline-flex items-center gap-2 px-5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full shadow-xs text-xs font-bold text-slate-700 dark:text-slate-300">
                    <i class="fa-regular fa-clock text-slate-400"></i>
                    <span>Waktu Server</span>
                    <span class="font-extrabold text-slate-900 dark:text-white" x-text="currentTime"></span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
