<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-purple-600"></i> Kalender Rekrutmen Interaktif
                </h2>
                <p class="text-xs text-gray-500 mt-1">Jadwal wawancara kerja, deadline closing lowongan, dan masa berlaku Offer Letter dalam 1 tampilan kalender.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-xl text-sm transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <!-- FullCalendar.js v6 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Calendar Legend & Header Card -->
            <div class="bg-white p-6 rounded-3xl shadow-2xs border border-gray-100 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-6 flex-wrap text-xs font-bold">
                    <span class="text-gray-400 uppercase tracking-wider text-2xs">Legenda Warna Event:</span>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-purple-600 inline-block"></span>
                        <span class="text-gray-700">📌 Undangan Wawancara</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-600 inline-block"></span>
                        <span class="text-gray-700">⏳ Deadline Closing Lowongan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                        <span class="text-gray-700">📄 Expired Offer Letter</span>
                    </div>
                </div>

                <div class="text-2xs text-gray-500">
                    <i class="fa-solid fa-mouse-pointer text-blue-600"></i> Klik event pada kalender untuk melihat rincian.
                </div>
            </div>

            <!-- Calendar Container Card -->
            <div class="bg-white rounded-3xl shadow-2xs border border-gray-100 p-6 sm:p-8">
                <div id="recruitmentCalendar"></div>
            </div>

        </div>
    </div>

    <!-- FullCalendar Script Setup -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('recruitmentCalendar');
            
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
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
                    list: 'Daftar Log'
                },
                events: '{{ route('admin.calendar.events') }}',
                eventClick: function(info) {
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.open(info.event.url, "_self");
                    }
                },
                height: 'auto',
                aspectRatio: 1.8,
            });

            calendar.render();
        });
    </script>

    <style>
        /* Custom FullCalendar Tailwind Polish */
        .fc {
            font-family: inherit;
        }
        .fc-header-toolbar {
            margin-bottom: 1.5rem !important;
        }
        .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 800 !important;
            color: #111827;
        }
        .fc-button-primary {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
            border-radius: 0.75rem !important;
            font-weight: 700 !important;
            font-size: 0.75rem !important;
            padding: 0.5rem 1rem !important;
            text-transform: capitalize !important;
        }
        .fc-button-primary:hover {
            background-color: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
        }
        .fc-daygrid-day-number {
            font-weight: 700;
            font-size: 0.85rem;
            color: #374151;
        }
        .fc-event {
            border-radius: 0.5rem !important;
            padding: 2px 4px !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            cursor: pointer;
        }
    </style>
</x-app-layout>
