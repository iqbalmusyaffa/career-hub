<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-900 leading-tight flex items-center gap-3">
            <i class="fa-solid fa-chart-pie text-blue-600"></i> {{ __('Dasbor Analisis & Rekap KPI Rekrutmen') }}
        </h2>
    </x-slot>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Summary KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Lowongan Aktif</span>
                        <div class="text-3xl font-black text-blue-600 mt-1">{{ number_format($totalActiveJobs) }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pelamar</span>
                        <div class="text-3xl font-black text-indigo-600 mt-1">{{ number_format($totalApplications) }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Lolos Hired</span>
                        <div class="text-3xl font-black text-emerald-600 mt-1">{{ number_format($stageStats['Diterima (Hired)'] ?? 0) }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tingkat Kelulusan</span>
                        <div class="text-3xl font-black text-purple-600 mt-1">
                            {{ $totalApplications > 0 ? round(($stageStats['Diterima (Hired)'] / $totalApplications) * 100, 1) : 0 }}%
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                </div>
            </div>

            <!-- Visual Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Chart 1: Pipeline Conversion -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="font-black text-lg text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-filter text-blue-600"></i> Konversi Tahapan Pipeline Rekrutmen
                    </h3>
                    <div class="h-64">
                        <canvas id="pipelineChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Education Demographics -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="font-black text-lg text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap text-indigo-600"></i> Demografi Pendidikan Pelamar
                    </h3>
                    <div class="h-64 flex justify-center">
                        <canvas id="educationChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Popular Jobs Table -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                <h3 class="font-black text-lg text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-fire text-amber-500"></i> 5 Lowongan Paling Populer (Jumlah Pelamar)
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-3xs font-extrabold text-gray-400 uppercase tracking-wider">
                                <th class="pb-3">Posisi Pekerjaan</th>
                                <th class="pb-3">Divisi</th>
                                <th class="pb-3">Lokasi</th>
                                <th class="pb-3 text-right">Jumlah Pelamar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @forelse($popularJobs as $job)
                                <tr>
                                    <td class="py-3 font-extrabold text-gray-900">{{ $job->title }}</td>
                                    <td class="py-3 text-xs font-semibold text-gray-600">{{ $job->division }}</td>
                                    <td class="py-3 text-xs font-semibold text-blue-600">{{ $job->location }}</td>
                                    <td class="py-3 text-right font-black text-indigo-600">{{ $job->applications_count }} Pelamar</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-xs text-gray-400">Belum ada data pelamar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pipeline Conversion Bar Chart
            const ctxPipeline = document.getElementById('pipelineChart').getContext('2d');
            new Chart(ctxPipeline, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($stageStats)) !!},
                    datasets: [{
                        label: 'Jumlah Pelamar',
                        data: {!! json_encode(array_values($stageStats)) !!},
                        backgroundColor: ['#3b82f6', '#6366f1', '#8b5cf6', '#ec4899', '#10b981', '#059669', '#ef4444'],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Education Doughnut Chart
            const ctxEducation = document.getElementById('educationChart').getContext('2d');
            new Chart(ctxEducation, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($educationStats)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($educationStats)) !!},
                        backgroundColor: ['#60a5fa', '#818cf8', '#a78bfa', '#c084fc']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
</x-app-layout>
