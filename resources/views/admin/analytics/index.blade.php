<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/80">
                        <i class="fa-solid fa-chart-pie text-[10px]"></i>
                        Recruitment Intelligence
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <span class="text-xs font-medium text-slate-500">Visual Analytics & Funnel</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 tracking-tight">
                    Analytics Performa & Konversi Rekrutmen
                </h2>
            </div>
            
            <div class="flex items-center gap-2.5">
                @if($isSuperAdmin)
                    <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex items-center gap-2">
                        <input type="text" name="company_name" value="{{ request('company_name') }}" placeholder="Filter Perusahaan..." class="px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs font-medium text-slate-700 focus:ring-blue-500 focus:border-blue-500 shadow-2xs">
                        <button type="submit" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-semibold transition shadow-2xs">
                            Filter
                        </button>
                        @if(request('company_name'))
                            <a href="{{ route('admin.analytics.index') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-medium transition border border-slate-200" title="Reset Filter">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        @endif
                    </form>
                @endif
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 font-semibold rounded-lg text-xs transition border border-slate-300 shadow-2xs">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Top Scorecard KPI Tiles -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Applications -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Total Lamaran Masuk</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs border border-blue-100">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <span class="text-2xl font-extrabold text-slate-900 tabular-nums tracking-tight">{{ number_format($totalApplications) }}</span>
                        <span class="text-[11px] font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200/60">Semua Posisi</span>
                    </div>
                    <div class="text-[11px] text-slate-400 font-medium pt-1 border-t border-slate-100">
                        Dari {{ number_format($activeJobsCount) }} lowongan aktif saat ini
                    </div>
                </div>

                <!-- Conversion Rate Hired -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Tingkat Kelulusan (Hired Rate)</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs border border-emerald-100">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <span class="text-2xl font-extrabold text-emerald-600 tabular-nums tracking-tight">{{ $conversionRate }}%</span>
                        <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200/60">{{ $hiredCount }} Diterima</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ min(100, max(5, $conversionRate)) }}%"></div>
                    </div>
                </div>

                <!-- Interview Pass-through -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Lolos ke Tahap Interview</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs border border-indigo-100">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <span class="text-2xl font-extrabold text-indigo-600 tabular-nums tracking-tight">{{ $interviewRate }}%</span>
                        <span class="text-[11px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200/60">{{ $interviewCount }} Kandidat</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-indigo-500 h-full rounded-full transition-all duration-500" style="width: {{ min(100, max(5, $interviewRate)) }}%"></div>
                    </div>
                </div>

                <!-- Avg Time to Hire -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Rata-rata Waktu Rekrutmen</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs border border-amber-100">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-extrabold text-slate-900 tabular-nums tracking-tight">{{ $avgDaysToHire }}</span>
                        <span class="text-xs font-bold text-slate-500">Hari / Kandidat</span>
                    </div>
                    <div class="text-[11px] text-slate-400 font-medium pt-1 border-t border-slate-100">
                        Durasi rata-rata dari melamar s/d accepted
                    </div>
                </div>
            </div>

            <!-- Main Charts Grid: Monthly Trend & Status Donut -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Monthly Trend Line Chart (8 Cols) -->
                <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-5 sm:p-6 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-chart-area text-blue-600"></i>
                                <span>Tren Perekrutan & Volume Lamaran (6 Bulan Terakhir)</span>
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Perbandingan jumlah pelamar masuk dan kandidat yang berhasil diterima kerja.</p>
                        </div>
                        <div class="flex items-center gap-3 text-xs font-medium">
                            <span class="inline-flex items-center gap-1.5 text-blue-700">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                                Pelamar Masuk
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-emerald-700">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                Diterima (Hired)
                            </span>
                        </div>
                    </div>

                    <div class="relative h-72 w-full">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>

                <!-- Status Distribution Donut Chart (4 Cols) -->
                <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-5 sm:p-6 space-y-4">
                    <div class="pb-3 border-b border-slate-100">
                        <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-indigo-600"></i>
                            <span>Komposisi Status Pelamar</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Sebaran kandidat di setiap tahapan rekrutmen.</p>
                    </div>

                    <div class="relative h-56 w-full flex items-center justify-center">
                        <canvas id="statusDonutChart"></canvas>
                    </div>

                    <!-- Custom Clean Legend -->
                    <div class="grid grid-cols-2 gap-2 text-xs pt-3 border-t border-slate-100">
                        <div class="flex items-center justify-between p-2 rounded-lg bg-amber-50/60 border border-amber-200/60">
                            <span class="font-medium text-amber-800 text-[11px]">Reviewing</span>
                            <span class="font-bold text-amber-900">{{ $pendingCount }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg bg-purple-50/60 border border-purple-200/60">
                            <span class="font-medium text-purple-800 text-[11px]">Tes Online</span>
                            <span class="font-bold text-purple-900">{{ $testCount }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg bg-indigo-50/60 border border-indigo-200/60">
                            <span class="font-medium text-indigo-800 text-[11px]">Interview</span>
                            <span class="font-bold text-indigo-900">{{ $interviewCount }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg bg-emerald-50/60 border border-emerald-200/60">
                            <span class="font-medium text-emerald-800 text-[11px]">Hired</span>
                            <span class="font-bold text-emerald-900">{{ $hiredCount }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recruitment Funnel & Division Demand Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Visual Conversion Funnel (7 Cols) -->
                <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-5 sm:p-6 space-y-4">
                    <div class="pb-3 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-filter-circle-dollar text-slate-700"></i>
                                <span>Recruitment Conversion Funnel</span>
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Rasio pelamar yang lolos antar tahapan seleksi.</p>
                        </div>
                    </div>

                    @php
                        $baseTotal = max(1, $totalApplications);
                        $funnelStages = [
                            ['name' => '1. Lamaran Masuk', 'count' => $totalApplications, 'pct' => 100, 'color' => 'bg-blue-600', 'text' => 'text-blue-700'],
                            ['name' => '2. Review & Screening', 'count' => $pendingCount + $testCount + $interviewCount + $hiredCount, 'pct' => round((($pendingCount + $testCount + $interviewCount + $hiredCount) / $baseTotal) * 100), 'color' => 'bg-amber-500', 'text' => 'text-amber-700'],
                            ['name' => '3. Ujian Tes Online', 'count' => $testCount + $interviewCount + $hiredCount, 'pct' => round((($testCount + $interviewCount + $hiredCount) / $baseTotal) * 100), 'color' => 'bg-purple-500', 'text' => 'text-purple-700'],
                            ['name' => '4. Wawancara HR / User', 'count' => $interviewCount + $hiredCount, 'pct' => round((($interviewCount + $hiredCount) / $baseTotal) * 100), 'color' => 'bg-indigo-600', 'text' => 'text-indigo-700'],
                            ['name' => '5. Diterima Kerja (Hired)', 'count' => $hiredCount, 'pct' => round(($hiredCount / $baseTotal) * 100), 'color' => 'bg-emerald-600', 'text' => 'text-emerald-700'],
                        ];
                    @endphp

                    <div class="space-y-3.5 pt-1">
                        @foreach($funnelStages as $st)
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs font-semibold">
                                    <span class="text-slate-800">{{ $st['name'] }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-500 font-medium tabular-nums">{{ number_format($st['count']) }} Kandidat</span>
                                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700 tabular-nums">{{ $st['pct'] }}%</span>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden p-0.5">
                                    <div class="{{ $st['color'] }} h-full rounded-full transition-all duration-700" style="width: {{ max(4, $st['pct']) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Division & Category Demand (5 Cols) -->
                <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-5 sm:p-6 space-y-4">
                    <div class="pb-3 border-b border-slate-100">
                        <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-layer-group text-slate-700"></i>
                            <span>Kategori Divisi Paling Dibutuhkan</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Peminatan dan sebaran lowongan berdasarkan departemen.</p>
                    </div>

                    @php
                        $maxDivCount = $topDivisions->max('count') ?: 1;
                    @endphp

                    <div class="space-y-3 pt-1">
                        @forelse($topDivisions as $div)
                            @php
                                $divPct = round(($div->count / $maxDivCount) * 100);
                            @endphp
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-xs font-semibold">
                                    <span class="text-slate-800">{{ $div->division ?: 'Umum / General' }}</span>
                                    <span class="text-slate-600 font-bold tabular-nums">{{ $div->count }} Lowongan</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-blue-600 h-full rounded-full" style="width: {{ $divPct }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic py-4 text-center">Belum ada data divisi lowongan pekerjaan.</p>
                        @endforelse
                    </div>

                    @if($isSuperAdmin && $topCompanies->count() > 0)
                        <div class="pt-4 border-t border-slate-100">
                            <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-building text-slate-400"></i>
                                <span>Perusahaan Teraktif di Platform</span>
                            </h4>
                            <div class="space-y-2">
                                @foreach($topCompanies->take(3) as $c)
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-200/70 text-xs">
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-900 truncate">{{ $c->company_name }}</p>
                                            <p class="text-[11px] text-slate-500">{{ $c->industry ?? 'Software & Tech' }}</p>
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 shrink-0">
                                            {{ $c->jobs_count }} Lowongan
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>

    <!-- Chart.js Scripts Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Monthly Trend Area / Line Chart
            const monthlyData = @json($monthlyTrend);
            const trendLabels = monthlyData.map(d => d.month);
            const applicationsData = monthlyData.map(d => d.applications);
            const hiresData = monthlyData.map(d => d.hires);

            const ctxTrend = document.getElementById('monthlyTrendChart').getContext('2d');
            
            // Gradients
            const blueGradient = ctxTrend.createLinearGradient(0, 0, 0, 280);
            blueGradient.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
            blueGradient.addColorStop(1, 'rgba(37, 99, 235, 0.01)');

            const greenGradient = ctxTrend.createLinearGradient(0, 0, 0, 280);
            greenGradient.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            greenGradient.addColorStop(1, 'rgba(16, 185, 129, 0.01)');

            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [
                        {
                            label: 'Pelamar Masuk',
                            data: applicationsData,
                            borderColor: '#2563eb',
                            backgroundColor: blueGradient,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#2563eb',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Diterima (Hired)',
                            data: hiresData,
                            borderColor: '#10b981',
                            backgroundColor: greenGradient,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#10b981',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 11 },
                            padding: 10,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11, weight: '500' }, color: '#64748b' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { size: 11, weight: '500' }, color: '#64748b', precision: 0 }
                        }
                    }
                }
            });

            // 2. Status Donut Chart
            const ctxDonut = document.getElementById('statusDonutChart').getContext('2d');
            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: ['Reviewing', 'Tes Online', 'Interview', 'Hired', 'Rejected'],
                    datasets: [{
                        data: [
                            {{ $pendingCount }},
                            {{ $testCount }},
                            {{ $interviewCount }},
                            {{ $hiredCount }},
                            {{ $rejectedCount }}
                        ],
                        backgroundColor: [
                            '#f59e0b', // Amber
                            '#8b5cf6', // Purple
                            '#4f46e5', // Indigo
                            '#10b981', // Emerald
                            '#f43f5e'  // Rose
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 11 },
                            padding: 10,
                            cornerRadius: 8,
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>

