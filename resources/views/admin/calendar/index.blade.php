<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-200/80">
                        <i class="fa-solid fa-calendar-check text-[10px]"></i>
                        Talent Acquisition Calendar
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <span class="text-xs font-medium text-slate-500">Agenda & Wawancara</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 tracking-tight">
                    Kalender Rekrutmen & Agenda Seleksi
                </h2>
            </div>
            
            <div class="flex items-center gap-2.5">
                <button type="button" onclick="refetchCalendar()" class="inline-flex items-center gap-2 px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition border border-slate-300 shadow-2xs hover:border-slate-400" title="Muat Ulang Event">
                    <i class="fa-solid fa-arrows-rotate text-slate-500" id="syncIcon"></i>
                    <span>Sinkron Live</span>
                </button>
                <button type="button" onclick="openSyncModal()" class="inline-flex items-center gap-2 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition shadow-2xs">
                    <i class="fa-brands fa-google text-xs"></i>
                    <span>Sinkron Google Calendar</span>
                </button>
            </div>
        </div>
    </x-slot>

    <!-- FullCalendar.js v6 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <div class="py-6 bg-slate-50/60 dark:bg-slate-900 min-h-screen transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Month Metric Stats Banner -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500">Jadwal Wawancara Bulan Ini</p>
                        <p class="text-xl font-bold text-indigo-600 mt-0.5">{{ number_format($interviewsThisMonthCount) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                        <i class="fa-solid fa-video text-sm"></i>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500">Deadline Lowongan Bulan Ini</p>
                        <p class="text-xl font-bold text-rose-600 mt-0.5">{{ number_format($deadlinesThisMonthCount) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100">
                        <i class="fa-solid fa-hourglass-half text-sm"></i>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500">Masa Berlaku Offer Letter</p>
                        <p class="text-xl font-bold text-amber-600 mt-0.5">{{ number_format($offersThisMonthCount) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                        <i class="fa-solid fa-file-signature text-sm"></i>
                    </div>
                </div>
            </div>

            <!-- Filter Categories Toolbar -->
            <div class="bg-white p-3 rounded-xl border border-slate-200/90 shadow-2xs flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mr-1">Filter Kategori:</span>
                    <button type="button" onclick="filterEvents('all')" id="filter-all" class="category-filter-btn active px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 text-white transition shadow-2xs">
                        Semua Agenda
                    </button>
                    <button type="button" onclick="filterEvents('interview')" id="filter-interview" class="category-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition">
                        <i class="fa-solid fa-video text-[10px] mr-1"></i> Wawancara ({{ $interviewsThisMonthCount }})
                    </button>
                    <button type="button" onclick="filterEvents('deadline')" id="filter-deadline" class="category-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition">
                        <i class="fa-solid fa-hourglass-half text-[10px] mr-1"></i> Deadline Lowongan ({{ $deadlinesThisMonthCount }})
                    </button>
                    <button type="button" onclick="filterEvents('offer')" id="filter-offer" class="category-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition">
                        <i class="fa-solid fa-file-signature text-[10px] mr-1"></i> Expired Offer ({{ $offersThisMonthCount }})
                    </button>
                </div>

                <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Live Auto-Sync (60s)</span>
                </div>
            </div>

            <!-- Bento 2-Column Grid: Calendar (Left) & Upcoming Schedule Sidebar (Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Main Calendar View (8 Cols) -->
                <div class="lg:col-span-8 bg-white rounded-2xl shadow-2xs border border-slate-200/90 p-5 sm:p-6">
                    <div id="recruitmentCalendar"></div>
                </div>

                <!-- Agenda Sidebar (4 Cols) -->
                <div class="lg:col-span-4 space-y-5">
                    
                    <!-- Upcoming Interviews Card -->
                    <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 p-5">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-video"></i>
                                </div>
                                <h3 class="font-bold text-sm text-slate-900">Agenda Wawancara Terdekat</h3>
                            </div>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200/60">
                                {{ $upcomingInterviews->count() }}
                            </span>
                        </div>

                        <div class="space-y-3">
                            @forelse($upcomingInterviews as $interview)
                                @php
                                    $cName = $interview->application->user->name ?? 'Kandidat';
                                    $cInitial = strtoupper(substr($cName, 0, 1));
                                    $jTitle = $interview->application->job->title ?? 'Pekerjaan';
                                    $isToday = $interview->scheduled_at->isToday();
                                    $isTomorrow = $interview->scheduled_at->isTomorrow();
                                @endphp
                                <div class="p-3 rounded-xl border {{ $isToday ? 'bg-indigo-50/50 border-indigo-200' : 'bg-slate-50/60 border-slate-200/80' }} hover:border-indigo-300 transition space-y-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">
                                                {{ $cInitial }}
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-semibold text-slate-900 text-xs truncate">{{ $cName }}</h4>
                                                <p class="text-[11px] text-slate-500 truncate">{{ $jTitle }}</p>
                                            </div>
                                        </div>
                                        @if($isToday)
                                            <span class="shrink-0 px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-600 text-white">Hari Ini</span>
                                        @elseif($isTomorrow)
                                            <span class="shrink-0 px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-800">Besok</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between text-[11px] pt-1.5 border-t border-slate-100 text-slate-600">
                                        <div class="flex items-center gap-1.5 font-medium">
                                            <i class="fa-regular fa-clock text-slate-400"></i>
                                            <span>{{ $interview->scheduled_at->format('d M, H:i') }} WIB</span>
                                        </div>
                                        <a href="{{ route('admin.applications.show', $interview->application_id) }}" class="font-semibold text-blue-600 hover:text-blue-800">
                                            Tinjau &rarr;
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-slate-400 text-xs">
                                    <i class="fa-solid fa-calendar-xmark text-2xl mb-1.5 text-slate-300 block"></i>
                                    Tidak ada jadwal wawancara terdekat.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Upcoming Job Deadlines Card -->
                    <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 p-5">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-hourglass-half"></i>
                                </div>
                                <h3 class="font-bold text-sm text-slate-900">Deadline Lowongan</h3>
                            </div>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200/60">
                                {{ $upcomingDeadlines->count() }}
                            </span>
                        </div>

                        <div class="space-y-2.5">
                            @forelse($upcomingDeadlines as $job)
                                @php
                                    $deadline = \Carbon\Carbon::parse($job->deadline);
                                    $daysRemaining = (int) ceil(now()->diffInDays($deadline, false));
                                @endphp
                                <div class="p-3 rounded-xl bg-slate-50/60 border border-slate-200/80 flex items-center justify-between gap-3 text-xs">
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.jobs.show', $job->id) }}" class="font-semibold text-slate-900 hover:text-blue-600 truncate block">
                                            {{ $job->title }}
                                        </a>
                                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $deadline->format('d M Y') }}</p>
                                    </div>
                                    <span class="shrink-0 px-2 py-1 rounded text-[10px] font-bold {{ $daysRemaining <= 2 ? 'bg-rose-100 text-rose-800' : 'bg-slate-200/80 text-slate-700' }}">
                                        {{ $daysRemaining > 0 ? 'Sisa ' . $daysRemaining . ' Hari' : 'Hari Ini' }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-6 text-slate-400 text-xs">
                                    Tidak ada deadline lowongan dalam waktu dekat.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Sync Info Card -->
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-800 dark:to-slate-800/90 border border-blue-200/70 dark:border-slate-700 text-xs space-y-2.5">
                        <div class="flex items-center gap-2 text-blue-900 dark:text-blue-300 font-bold">
                            <i class="fa-brands fa-google text-blue-600 dark:text-blue-400"></i>
                            <span>Sinkronisasi Google Calendar</span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-[11px]">
                            Masukkan agenda wawancara dan deadline ke Google Calendar di HP & Laptop secara otomatis via feed iCal live.
                        </p>
                        <button type="button" onclick="openSyncModal()" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-xs transition shadow-2xs flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-link text-xs"></i>
                            <span>Buka Panduan & Link Sinkron</span>
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Quick Preview Event Modal -->
    <div id="eventModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between" id="modalHeader">
                <span id="modalCategoryBadge" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold"></span>
                <button type="button" onclick="closeEventModal()" class="text-slate-400 hover:text-slate-600 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-5 space-y-4 text-xs">
                <div>
                    <h3 id="modalTitle" class="font-bold text-base text-slate-900"></h3>
                    <p id="modalSubtitle" class="text-xs text-slate-500 mt-0.5"></p>
                </div>

                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200/80 space-y-2 text-xs">
                    <div class="flex items-center gap-2.5 text-slate-700">
                        <i class="fa-regular fa-calendar text-slate-400 w-4"></i>
                        <span id="modalDate" class="font-medium"></span>
                    </div>
                    <div class="flex items-center gap-2.5 text-slate-700">
                        <i class="fa-regular fa-clock text-slate-400 w-4"></i>
                        <span id="modalTime" class="font-medium"></span>
                    </div>
                    <div class="flex items-center gap-2.5 text-slate-700" id="modalLocationRow">
                        <i class="fa-solid fa-location-dot text-slate-400 w-4"></i>
                        <span id="modalLocation" class="font-medium"></span>
                    </div>
                </div>

                <div id="modalNotesContainer">
                    <label class="font-semibold text-slate-700 block mb-1 text-[11px] uppercase tracking-wider">Catatan Acara:</label>
                    <p id="modalNotes" class="text-slate-600 bg-slate-50/50 p-2.5 rounded-lg border border-slate-200 text-xs italic"></p>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2">
                <a id="modalGCalBtn" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 font-semibold rounded-lg text-xs transition shadow-2xs">
                    <i class="fa-brands fa-google text-blue-600"></i>
                    <span>+ Google Calendar</span>
                </a>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="closeEventModal()" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-lg text-xs transition">
                        Tutup
                    </button>
                    <a id="modalActionBtn" href="#" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg text-xs transition shadow-2xs">
                        Lihat Berkas &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Google Calendar & iCal Feed Sync Modal -->
    <div id="syncModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-lg w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                        <i class="fa-brands fa-google"></i>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Sinkronkan Jadwal ke Google Calendar</h3>
                </div>
                <button type="button" onclick="closeSyncModal()" class="text-slate-400 hover:text-slate-600 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-5 space-y-4 text-xs">
                <!-- Method 1: iCal Subscribe -->
                <div class="space-y-2">
                    <h4 class="font-bold text-slate-900 text-xs flex items-center gap-1.5">
                        <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px]">1</span>
                        Langganan Otomatis (Live Calendar Feed URL):
                    </h4>
                    <p class="text-slate-600 text-[11px] leading-relaxed">
                        Salin URL feed kalender berikut lalu tempelkan di Google Calendar via menu <em>"Other calendars" &rarr; "From URL"</em>. Seluruh jadwal baru akan otomatis masuk ke Google Calendar Anda.
                    </p>
                    <div class="flex items-center gap-2">
                        <input type="text" readonly id="feedUrlInput" value="{{ route('admin.calendar.feed') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-mono text-slate-700">
                        <button type="button" onclick="copyFeedUrl()" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg text-xs shrink-0 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-copy"></i>
                            <span id="copyBtnText">Salin</span>
                        </button>
                    </div>
                </div>

                <div class="h-px bg-slate-100"></div>

                <!-- Method 2: Download .ics -->
                <div class="space-y-2">
                    <h4 class="font-bold text-slate-900 text-xs flex items-center gap-1.5">
                        <span class="w-4 h-4 rounded-full bg-slate-600 text-white flex items-center justify-center text-[10px]">2</span>
                        Unduh File Kalender (.ics):
                    </h4>
                    <p class="text-slate-600 text-[11px] leading-relaxed">
                        Bisa langsung diimpor ke aplikasi Apple Calendar (iOS/macOS), Microsoft Outlook, atau aplikasi kalender bawaan HP Anda.
                    </p>
                    <a href="{{ route('admin.calendar.feed') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg text-xs font-semibold transition shadow-2xs">
                        <i class="fa-solid fa-download text-indigo-600"></i>
                        <span>Unduh File (.ics) Sekarang</span>
                    </a>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="button" onclick="closeSyncModal()" class="px-4 py-2 bg-slate-900 text-white font-semibold rounded-lg text-xs transition">
                    Selesai
                </button>
            </div>
        </div>
    </div>

    <!-- FullCalendar Engine Script & Custom Event Renderer -->
    <script>
        let calendarInstance = null;
        let activeCategoryFilter = 'all';

        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('recruitmentCalendar');
            
            calendarInstance = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                firstDay: 1, // Start on Monday
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    day: 'Hari',
                    list: 'Agenda List'
                },
                events: '{{ route('admin.calendar.events') }}',
                eventDisplay: 'block',
                displayEventTime: false,
                dayMaxEvents: 3,
                
                // Custom Event Content Renderer (Ashby / Linear Style)
                eventContent: function(arg) {
                    const props = arg.event.extendedProps || {};
                    const category = props.category || 'interview';
                    
                    // Filter check
                    if (activeCategoryFilter !== 'all' && activeCategoryFilter !== category) {
                        return { domNodes: [] };
                    }

                    const container = document.createElement('div');
                    container.className = 'custom-calendar-event group ' + (category === 'interview' ? 'event-pill-interview' : (category === 'deadline' ? 'event-pill-deadline' : 'event-pill-offer'));

                    let iconHtml = '<i class="fa-solid fa-video text-[10px]"></i>';
                    if (category === 'deadline') {
                        iconHtml = '<i class="fa-solid fa-hourglass-half text-[10px]"></i>';
                    } else if (category === 'offer') {
                        iconHtml = '<i class="fa-solid fa-file-signature text-[10px]"></i>';
                    }

                    let timeBadge = '';
                    if (category === 'interview' && props.formatted_time) {
                        const timeOnly = props.formatted_time.split(' - ')[0];
                        timeBadge = `<span class="event-time">${timeOnly}</span>`;
                    }

                    container.innerHTML = `
                        <div class="flex items-center gap-1.5 min-w-0">
                            ${iconHtml}
                            ${timeBadge}
                            <span class="truncate font-semibold text-[11px]">${arg.event.title}</span>
                        </div>
                    `;

                    return { domNodes: [container] };
                },

                // Event Click -> Open Pop-up Modal
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    showEventModal(info.event);
                },

                height: 'auto',
                aspectRatio: 1.6,
            });

            calendarInstance.render();

            // Real-time Background Auto-Sync every 60 seconds
            setInterval(() => {
                refetchCalendar(true);
            }, 60000);
        });

        // Refetch calendar events with visual feedback
        function refetchCalendar(isSilent = false) {
            const icon = document.getElementById('syncIcon');
            if (icon && !isSilent) icon.classList.add('fa-spin');
            
            if (calendarInstance) {
                calendarInstance.refetchEvents();
                setTimeout(() => {
                    if (icon && !isSilent) icon.classList.remove('fa-spin');
                }, 800);
            }
        }

        // Category Filter
        function filterEvents(category) {
            activeCategoryFilter = category;
            
            document.querySelectorAll('.category-filter-btn').forEach(btn => {
                btn.classList.remove('bg-slate-900', 'text-white');
                if (btn.id === 'filter-' + category) {
                    btn.classList.add('bg-slate-900', 'text-white');
                }
            });

            if (calendarInstance) {
                calendarInstance.render();
            }
        }

        // Event Modal Controls
        function showEventModal(event) {
            const props = event.extendedProps || {};
            const category = props.category || 'interview';
            
            const modal = document.getElementById('eventModal');
            const badge = document.getElementById('modalCategoryBadge');
            
            if (category === 'interview') {
                badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200';
                badge.innerHTML = '<i class="fa-solid fa-video text-[10px]"></i> Sesi Wawancara';
            } else if (category === 'deadline') {
                badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200';
                badge.innerHTML = '<i class="fa-solid fa-hourglass-half text-[10px]"></i> Batas Akhir Lowongan';
            } else {
                badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200';
                badge.innerHTML = '<i class="fa-solid fa-file-signature text-[10px]"></i> Expired Offer Letter';
            }

            document.getElementById('modalTitle').textContent = event.title;
            document.getElementById('modalSubtitle').textContent = props.job_title ? 'Posisi: ' + props.job_title : '';
            document.getElementById('modalDate').textContent = props.formatted_date || '';
            document.getElementById('modalTime').textContent = props.formatted_time || '';
            
            const locSpan = document.getElementById('modalLocation');
            const locRow = document.getElementById('modalLocationRow');
            if (props.location_or_link) {
                locRow.classList.remove('hidden');
                locSpan.textContent = props.location_or_link;
            } else {
                locRow.classList.add('hidden');
            }

            const notesEl = document.getElementById('modalNotes');
            const notesCont = document.getElementById('modalNotesContainer');
            if (props.notes) {
                notesCont.classList.remove('hidden');
                notesEl.textContent = props.notes;
            } else {
                notesCont.classList.add('hidden');
            }

            // Google Calendar Button URL
            const gcalBtn = document.getElementById('modalGCalBtn');
            if (props.google_calendar_url) {
                gcalBtn.href = props.google_calendar_url;
                gcalBtn.classList.remove('hidden');
            } else {
                gcalBtn.classList.add('hidden');
            }

            // Action URL
            const actionBtn = document.getElementById('modalActionBtn');
            if (props.action_url) {
                actionBtn.href = props.action_url;
                actionBtn.classList.remove('hidden');
            } else {
                actionBtn.classList.add('hidden');
            }

            modal.classList.remove('hidden');
        }

        function closeEventModal() {
            document.getElementById('eventModal').classList.add('hidden');
        }

        // Sync Modal Controls
        function openSyncModal() {
            document.getElementById('syncModal').classList.remove('hidden');
        }

        function closeSyncModal() {
            document.getElementById('syncModal').classList.add('hidden');
        }

        function copyFeedUrl() {
            const input = document.getElementById('feedUrlInput');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value);
            
            const btnText = document.getElementById('copyBtnText');
            btnText.textContent = 'Tersalin!';
            setTimeout(() => { btnText.textContent = 'Salin'; }, 2000);
        }
    </script>

    <!-- Custom Tailwind Calendar Stylesheet -->
    <style>
        .fc {
            font-family: inherit;
        }
        
        /* Toolbar */
        .fc-header-toolbar {
            margin-bottom: 1.25rem !important;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .fc-toolbar-title {
            font-size: 1.15rem !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            letter-spacing: -0.025em;
        }
        .dark .fc-toolbar-title {
            color: #f8fafc !important;
        }
        
        /* Buttons */
        .fc-button-primary {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #334155 !important;
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
            font-size: 0.75rem !important;
            padding: 0.4rem 0.75rem !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.15s ease-in-out;
        }
        .fc-button-primary:hover {
            background-color: #f8fafc !important;
            border-color: #94a3b8 !important;
            color: #0f172a !important;
        }
        .fc-button-primary.fc-button-active {
            background-color: #0f172a !important;
            border-color: #0f172a !important;
            color: #ffffff !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
        }

        .dark .fc-button-primary {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
        }
        .dark .fc-button-primary:hover {
            background-color: #1e293b !important;
            border-color: #475569 !important;
            color: #ffffff !important;
        }
        .dark .fc-button-primary.fc-button-active {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
        }
        
        /* Day Grid Cell */
        .fc-theme-standard td, .fc-theme-standard th {
            border-color: #f1f5f9 !important;
        }
        .dark .fc-theme-standard td, .dark .fc-theme-standard th {
            border-color: #334155 !important;
        }
        .fc-col-header-cell {
            padding: 0.6rem 0 !important;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0 !important;
        }
        .dark .fc-col-header-cell {
            background-color: #0f172a !important;
            border-bottom: 1px solid #334155 !important;
        }
        .fc-col-header-cell-cushion {
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            color: #64748b !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .dark .fc-col-header-cell-cushion {
            color: #94a3b8 !important;
        }
        .fc-daygrid-day-number {
            font-weight: 600;
            font-size: 0.8rem;
            color: #475569;
            padding: 0.35rem 0.5rem !important;
        }
        .dark .fc-daygrid-day-number {
            color: #cbd5e1 !important;
        }
        .dark .fc-day-other .fc-daygrid-day-number {
            color: #475569 !important;
            opacity: 0.5;
        }
        .dark .fc-day-other {
            background-color: rgba(15, 23, 42, 0.4) !important;
        }
        
        /* Today Cell Accent */
        .fc-day-today {
            background-color: #f8fafc !important;
        }
        .dark .fc-day-today {
            background-color: rgba(37, 99, 235, 0.12) !important;
        }
        .fc-day-today .fc-daygrid-day-number {
            background-color: #2563eb;
            color: #ffffff !important;
            border-radius: 9999px;
            width: 1.5rem;
            height: 1.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0.25rem 0.35rem;
        }
        
        /* Event Pill Styling */
        .fc-daygrid-event-harness {
            margin-bottom: 2px !important;
        }
        .fc-event {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            cursor: pointer;
        }
        .custom-calendar-event {
            padding: 0.25rem 0.45rem;
            border-radius: 0.4rem;
            font-size: 0.7rem;
            line-height: 1.15;
            transition: transform 0.1s ease, box-shadow 0.1s ease;
        }
        .custom-calendar-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Event Categories */
        .event-pill-interview {
            background-color: #eef2ff !important;
            border: 1px solid #c7d2fe !important;
            color: #3730a3 !important;
        }
        .dark .event-pill-interview {
            background-color: rgba(49, 46, 129, 0.45) !important;
            border: 1px solid rgba(99, 102, 241, 0.4) !important;
            color: #c7d2fe !important;
        }
        .event-pill-deadline {
            background-color: #fff1f2 !important;
            border: 1px solid #fecdd3 !important;
            color: #9f1239 !important;
        }
        .dark .event-pill-deadline {
            background-color: rgba(136, 19, 55, 0.45) !important;
            border: 1px solid rgba(244, 63, 94, 0.4) !important;
            color: #fecdd3 !important;
        }
        .event-pill-offer {
            background-color: #fffbeb !important;
            border: 1px solid #fde68a !important;
            color: #92400e !important;
        }
        .dark .event-pill-offer {
            background-color: rgba(120, 53, 15, 0.45) !important;
            border: 1px solid rgba(245, 158, 11, 0.4) !important;
            color: #fde68a !important;
        }
        
        .event-time {
            font-weight: 700;
            opacity: 0.85;
            padding-right: 0.25rem;
            border-right: 1px solid currentColor;
            margin-right: 0.25rem;
            font-variant-numeric: tabular-nums;
        }
    </style>
</x-app-layout>


