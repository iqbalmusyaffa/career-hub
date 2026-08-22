<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center text-sm shadow-md">
                        <i class="fa-solid fa-chart-pie"></i>
                    </span>
                    Dashboard Analytics & Funnel Rekrutmen HR
                </h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Statistik konversi pelamar, funnel seleksi rekrutmen, dan performa penerbitan dokumen resmi.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.jobs.create') }}" class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-plus text-indigo-400"></i> Pasang Lowongan Baru
                </a>
                <a href="{{ route('admin.analytics.index') }}" class="bg-white hover:bg-slate-100 text-slate-800 font-bold py-2.5 px-4 rounded-xl text-xs transition border border-slate-300 shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-blue-600"></i> Analitik Rekrutmen
                </a>
                <a href="{{ route('admin.calendar.index') }}" class="bg-white hover:bg-slate-100 text-slate-800 font-bold py-2.5 px-4 rounded-xl text-xs transition border border-slate-300 shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-purple-600"></i> Kalender Interview
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/70 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- METRIC BENTO CARDS GRID (4 COLUMNS) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Lowongan -->
                <a href="{{ route('admin.jobs.index') }}" class="bg-white p-6 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-3 group cursor-pointer block">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500 group-hover:text-blue-600 transition">Total Lowongan</span>
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-black border border-blue-100 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalJobs }}</div>
                        <div class="text-xs font-semibold text-slate-500 mt-1"><span class="font-black text-blue-600">{{ $activeJobs }}</span> Lowongan Aktif Membuka Lamaran</div>
                    </div>
                </a>

                <!-- Total Pelamar -->
                <a href="{{ route('admin.applications.index') }}" class="bg-white p-6 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-3 group cursor-pointer block">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500 group-hover:text-indigo-600 transition">Total Pelamar Terdaftar</span>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-black border border-indigo-100 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-indigo-600 tracking-tight">{{ number_format($totalApplicants) }}</div>
                        <div class="text-xs font-semibold text-slate-500 mt-1"><span class="font-black text-slate-800">{{ number_format($totalApplications) }}</span> Berkas Berhasil Diterima</div>
                    </div>
                </a>

                <!-- Tahap Wawancara -->
                <a href="{{ route('admin.calendar.index') }}" class="bg-white p-6 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-3 group cursor-pointer block">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500 group-hover:text-amber-600 transition">Tahap Wawancara</span>
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-black border border-amber-100 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-amber-600 tracking-tight">{{ $statusCounts['interview'] ?? 0 }}</div>
                        <div class="text-xs font-semibold text-slate-500 mt-1">Kandidat Aktif Tahap Interview</div>
                    </div>
                </a>

                <!-- Rate Konversi Hired -->
                <a href="{{ route('admin.applications.index') }}" class="bg-white p-6 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-3 group cursor-pointer block">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500 group-hover:text-emerald-600 transition">Rate Konversi Hired</span>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-black border border-emerald-100 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-emerald-600 tracking-tight">{{ $conversionRate }}%</div>
                        <div class="text-xs font-semibold text-slate-500 mt-1"><span class="font-black text-emerald-700">{{ $statusCounts['accepted'] ?? 0 }}</span> Kandidat Lolos Rekrutmen</div>
                    </div>
                </a>
            </div>

            <!-- RECRUITMENT FUNNEL CONVERSION BAR -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xs border border-slate-200/90 space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 pb-4 gap-2">
                    <div>
                        <h3 class="font-black text-base text-slate-900 tracking-tight flex items-center gap-2">
                            <i class="fa-solid fa-filter text-indigo-600"></i> Funnel Rekrutmen Pelamar (Recruitment Conversion Pipeline)
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Tahapan proses seleksi pelamar dari mendaftar hingga resmi diterima kerja.</p>
                    </div>
                    <span class="px-3.5 py-1.5 bg-indigo-50 border border-indigo-100 text-indigo-900 text-3xs font-black rounded-xl uppercase tracking-wider shadow-2xs">
                        🎯 Total Conversion: {{ $conversionRate }}% Hired
                    </span>
                </div>

                <!-- FUNNEL STAGES GRID -->
                @php
                    $maxFunnel = max($totalApplications, 1);
                    $pendingPct = round(($statusCounts['pending'] / $maxFunnel) * 100, 1);
                    $reviewPct = round(($statusCounts['reviewing'] / $maxFunnel) * 100, 1);
                    $interviewPct = round(($statusCounts['interview'] / $maxFunnel) * 100, 1);
                    $hiredPct = round(($statusCounts['accepted'] / $maxFunnel) * 100, 1);
                    $rejectedPct = round(($statusCounts['rejected'] / $maxFunnel) * 100, 1);
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 text-center">
                    <!-- STAGE 1: PENDING -->
                    <a href="{{ route('admin.applications.index') }}?status=pending" class="p-4 bg-slate-50/80 hover:bg-slate-100 rounded-2xl border border-slate-200 space-y-2.5 transition block cursor-pointer group">
                        <span class="text-3xs font-black uppercase tracking-wider text-slate-500 block group-hover:text-slate-900">1. Masuk</span>
                        <div class="text-2xl font-black text-slate-900">{{ number_format($statusCounts['pending']) }}</div>
                        <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-slate-800 h-full rounded-full" style="width: {{ $pendingPct }}%;"></div>
                        </div>
                        <span class="text-3xs font-extrabold text-slate-500">{{ $pendingPct }}% dari total</span>
                    </a>

                    <!-- STAGE 2: REVIEWING -->
                    <a href="{{ route('admin.applications.index') }}?status=reviewing" class="p-4 bg-purple-50/60 hover:bg-purple-100/80 rounded-2xl border border-purple-200/80 space-y-2.5 transition block cursor-pointer group">
                        <span class="text-3xs font-black uppercase tracking-wider text-purple-900 block">2. Peninjauan</span>
                        <div class="text-2xl font-black text-purple-900">{{ number_format($statusCounts['reviewing']) }}</div>
                        <div class="w-full bg-purple-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-purple-600 h-full rounded-full" style="width: {{ $reviewPct }}%;"></div>
                        </div>
                        <span class="text-3xs font-extrabold text-purple-800">{{ $reviewPct }}% dari total</span>
                    </a>

                    <!-- STAGE 3: INTERVIEW -->
                    <a href="{{ route('admin.calendar.index') }}" class="p-4 bg-amber-50/60 hover:bg-amber-100/80 rounded-2xl border border-amber-200/80 space-y-2.5 transition block cursor-pointer group">
                        <span class="text-3xs font-black uppercase tracking-wider text-amber-900 block">3. Wawancara</span>
                        <div class="text-2xl font-black text-amber-900">{{ number_format($statusCounts['interview']) }}</div>
                        <div class="w-full bg-amber-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full rounded-full" style="width: {{ $interviewPct }}%;"></div>
                        </div>
                        <span class="text-3xs font-extrabold text-amber-800">{{ $interviewPct }}% dari total</span>
                    </a>

                    <!-- STAGE 4: HIRED -->
                    <a href="{{ route('admin.applications.index') }}?status=accepted" class="p-4 bg-emerald-50/60 hover:bg-emerald-100/80 rounded-2xl border border-emerald-200/80 space-y-2.5 transition block cursor-pointer group">
                        <span class="text-3xs font-black uppercase tracking-wider text-emerald-900 block">4. Lolos Hired</span>
                        <div class="text-2xl font-black text-emerald-900">{{ number_format($statusCounts['accepted']) }}</div>
                        <div class="w-full bg-emerald-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-600 h-full rounded-full" style="width: {{ $hiredPct }}%;"></div>
                        </div>
                        <span class="text-3xs font-extrabold text-emerald-800">{{ $hiredPct }}% dari total</span>
                    </a>

                    <!-- STAGE 5: REJECTED -->
                    <a href="{{ route('admin.applications.index') }}?status=rejected" class="p-4 bg-rose-50/60 hover:bg-rose-100/80 rounded-2xl border border-rose-200/80 space-y-2.5 transition block cursor-pointer group">
                        <span class="text-3xs font-black uppercase tracking-wider text-rose-900 block">5. Tidak Lolos</span>
                        <div class="text-2xl font-black text-rose-900">{{ number_format($statusCounts['rejected']) }}</div>
                        <div class="w-full bg-rose-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-rose-500 h-full rounded-full" style="width: {{ $rejectedPct }}%;"></div>
                        </div>
                        <span class="text-3xs font-extrabold text-rose-800">{{ $rejectedPct }}% dari total</span>
                    </a>
                </div>
            </div>

            <!-- DOCUMENT & WORK TYPE STATISTICS BAR -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- CHART 1: MONTHLY APPLICANT TREND -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs border border-slate-200/90 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="font-black text-sm text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-chart-area text-blue-600"></i> Tren Pertumbuhan Pelamar (6 Bulan Terakhir)
                        </h3>
                    </div>
                    <div class="h-64 relative flex items-center justify-center">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>

                <!-- LEGAL DOCUMENTS ISSUED STATS -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs border border-slate-200/90 space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="font-black text-sm text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-file-shield text-emerald-600"></i> Statistik Penerbitan Dokumen Resmi HR
                        </h3>
                        <p class="text-3xs text-slate-500 font-medium">Total dokumen legalitas karir & magang yang diterbitkan oleh manajemen.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-xs">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/90 space-y-1">
                            <span class="text-3xs font-extrabold text-slate-500 uppercase block">📜 Perjanjian Kerja</span>
                            <div class="text-2xl font-black text-slate-900">{{ number_format($agreementsCount) }}</div>
                            <span class="text-3xs text-slate-400 font-medium">PKWT, PKWTT, Remote, Hybrid</span>
                        </div>

                        <div class="p-4 bg-amber-50/60 rounded-2xl border border-amber-200/80 space-y-1">
                            <span class="text-3xs font-extrabold text-amber-900 uppercase block">🎓 Sertifikat Magang</span>
                            <div class="text-2xl font-black text-amber-950">{{ number_format($certificatesCount) }}</div>
                            <span class="text-3xs text-amber-800 font-medium">A4 Landscape + Mentor</span>
                        </div>

                        <div class="p-4 bg-indigo-50/60 rounded-2xl border border-indigo-200/80 space-y-1">
                            <span class="text-3xs font-extrabold text-indigo-900 uppercase block">📊 Transkrip Evaluasi</span>
                            <div class="text-2xl font-black text-indigo-950">{{ number_format($transcriptsCount) }}</div>
                            <span class="text-3xs text-indigo-800 font-medium">5 Kriteria + GPA Rata-rata</span>
                        </div>

                        <div class="p-4 bg-slate-100 rounded-2xl border border-slate-300 space-y-1">
                            <span class="text-3xs font-extrabold text-slate-700 uppercase block">📄 Rekomendasi & Paklaring</span>
                            <div class="text-2xl font-black text-slate-900">{{ number_format($terminationsCount) }}</div>
                            <span class="text-3xs text-slate-500 font-medium">Paklaring, Rekomendasi, PHK</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid: Latest Applications & Upcoming Interviews -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Latest Applications (2 Cols) -->
                <div class="lg:col-span-2 bg-white rounded-3xl shadow-2xs border border-slate-200/90 p-6 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-black text-base text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-blue-600"></i> Pelamar Terbaru
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5 font-medium">Daftar berkas lamaran yang baru saja dikirim oleh kandidat.</p>
                        </div>
                        <a href="{{ route('admin.applications.index') }}" class="text-xs font-black text-blue-600 hover:underline flex items-center gap-1">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    @if(count($latestApplications) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 font-extrabold border-b border-slate-100 uppercase tracking-wider text-3xs">
                                        <th class="p-3 pl-4">Kandidat</th>
                                        <th class="p-3">Posisi Dilamar</th>
                                        <th class="p-3">Status</th>
                                        <th class="p-3 pr-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                                    @foreach($latestApplications as $app)
                                        <tr class="hover:bg-blue-50/50 transition cursor-pointer group" onclick="window.location.href='{{ route('admin.applications.show', $app->id) }}'">
                                            <td class="p-3 pl-4 whitespace-nowrap">
                                                <div class="font-extrabold text-slate-900 text-xs group-hover:text-blue-600 transition">{{ $app->user->name ?? 'Kandidat' }}</div>
                                                <div class="text-3xs text-slate-400 font-medium">{{ $app->user->email ?? '-' }}</div>
                                            </td>
                                            <td class="p-3 whitespace-nowrap">
                                                <div class="font-bold text-slate-800 text-xs">{{ $app->job->title ?? '-' }}</div>
                                            </td>
                                            <td class="p-3 whitespace-nowrap">
                                                @php
                                                    $statusStr = is_object($app->status) ? $app->status->value : (string) $app->status;
                                                    $badgeStyle = match($statusStr) {
                                                        'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                        'reviewing' => 'bg-purple-50 text-purple-800 border-purple-200',
                                                        'interview' => 'bg-blue-50 text-blue-800 border-blue-200',
                                                        'accepted' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                        'rejected' => 'bg-rose-50 text-rose-800 border-rose-200',
                                                        default => 'bg-slate-50 text-slate-700 border-slate-200',
                                                    };
                                                @endphp
                                                <span class="px-2.5 py-1 text-3xs font-extrabold rounded-lg border uppercase tracking-wider {{ $badgeStyle }}">
                                                    {{ $statusStr }}
                                                </span>
                                            </td>
                                            <td class="p-3 pr-4 text-right whitespace-nowrap">
                                                <a href="{{ route('admin.applications.show', $app->id) }}" class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-3xs transition shadow-2xs inline-flex items-center gap-1">
                                                    Review Berkas &rarr;
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-xs text-slate-500 font-medium">
                            Belum ada berkas lamaran baru yang masuk.
                        </div>
                    @endif
                </div>

                <!-- Right Column: Upcoming Interviews -->
                <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/90 p-6 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-black text-base text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-calendar-check text-purple-600"></i> Wawancara Mendatang
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5 font-medium">Jadwal interview aktif kandidat.</p>
                        </div>
                        <a href="{{ route('admin.calendar.index') }}" class="text-xs font-black text-blue-600 hover:underline flex items-center gap-1">
                            Kalender &rarr;
                        </a>
                    </div>

                    @if(count($upcomingInterviews) > 0)
                        <div class="space-y-3">
                            @foreach($upcomingInterviews as $interview)
                                <a href="{{ route('admin.applications.show', $interview->application_id) }}" class="p-4 bg-slate-50/80 hover:bg-purple-50/60 hover:border-purple-200 rounded-2xl border border-slate-200 space-y-2 text-xs transition cursor-pointer block group shadow-2xs">
                                    <div class="flex justify-between items-start">
                                        <span class="font-black text-slate-900 group-hover:text-purple-600 transition">{{ $interview->application->user->name ?? 'Kandidat' }}</span>
                                        <span class="px-2 py-0.5 bg-purple-50 text-purple-700 font-extrabold rounded-md border border-purple-200 text-3xs uppercase">
                                            {{ strtoupper($interview->type) }}
                                        </span>
                                    </div>
                                    <p class="text-2xs font-bold text-slate-600">{{ $interview->application->job->title ?? '-' }}</p>
                                    <div class="text-3xs font-medium text-slate-400 pt-1.5 flex items-center gap-1.5 border-t border-slate-200/50">
                                        <i class="fa-regular fa-clock text-slate-400"></i>
                                        <span>{{ $interview->scheduled_at->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-xs text-slate-400 font-medium">
                            Belum ada jadwal wawancara mendatang.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- CHART.JS INTEGRATION -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('monthlyTrendChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyTrendLabels) !!},
                    datasets: [{
                        label: 'Jumlah Lamaran Masuk',
                        data: {!! json_encode($monthlyTrendData) !!},
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#2563eb'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
