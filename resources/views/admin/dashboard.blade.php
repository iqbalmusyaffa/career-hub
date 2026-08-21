<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    Dashboard Operasional HR
                </h2>
                <p class="text-xs text-slate-500 mt-1">Ringkasan aktivitas rekrutmen, statistik pelamar, dan jadwal wawancara aktif.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.jobs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Lowongan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Metric Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Lowongan -->
                <div class="bg-white p-6 rounded-2xl shadow-2xs border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Lowongan</span>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base font-bold">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalJobs }}</div>
                        <div class="text-xs font-medium text-slate-500 mt-1"><span class="font-semibold text-blue-600">{{ $activeJobs }}</span> Lowongan Aktif</div>
                    </div>
                </div>

                <!-- Total Pelamar -->
                <div class="bg-white p-6 rounded-2xl shadow-2xs border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pelamar</span>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base font-bold">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-indigo-600 tracking-tight">{{ number_format($totalApplicants) }}</div>
                        <div class="text-xs font-medium text-slate-500 mt-1"><span class="font-semibold text-slate-700">{{ number_format($totalApplications) }}</span> Berkas Berhasil Diterima</div>
                    </div>
                </div>

                <!-- Tahap Wawancara -->
                <div class="bg-white p-6 rounded-2xl shadow-2xs border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tahap Wawancara</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base font-bold">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-amber-600 tracking-tight">{{ $statusCounts['interview'] ?? 0 }}</div>
                        <div class="text-xs font-medium text-slate-500 mt-1">Kandidat Aktif Wawancara</div>
                    </div>
                </div>

                <!-- Diterima -->
                <div class="bg-white p-6 rounded-2xl shadow-2xs border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Kandidat Diterima</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base font-bold">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-emerald-600 tracking-tight">{{ $statusCounts['accepted'] ?? 0 }}</div>
                        <div class="text-xs font-medium text-slate-500 mt-1">Lolos Rekrutmen Perusahaan</div>
                    </div>
                </div>
            </div>

            <!-- Content Grid: Latest Applications & Upcoming Interviews -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Latest Applications (2 Cols) -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-2xs border border-slate-200/80 p-6 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-extrabold text-base text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-blue-600"></i> Pelamar Terbaru
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Daftar berkas lamaran yang baru saja dikirim oleh kandidat.</p>
                        </div>
                        <a href="{{ route('admin.applications.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    @if(count($latestApplications) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                                        <th class="p-3 pl-4">Kandidat</th>
                                        <th class="p-3">Posisi Dilamar</th>
                                        <th class="p-3">Status</th>
                                        <th class="p-3 pr-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                                    @foreach($latestApplications as $app)
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="p-3 pl-4 whitespace-nowrap">
                                                <div class="font-bold text-slate-900 text-xs">{{ $app->user->name ?? 'Kandidat' }}</div>
                                                <div class="text-3xs text-slate-400">{{ $app->user->email ?? '-' }}</div>
                                            </td>
                                            <td class="p-3 whitespace-nowrap">
                                                <div class="font-semibold text-slate-800 text-xs">{{ $app->job->title ?? '-' }}</div>
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
                                                <span class="px-2.5 py-1 text-3xs font-bold rounded-lg border uppercase tracking-wider {{ $badgeStyle }}">
                                                    {{ $statusStr }}
                                                </span>
                                            </td>
                                            <td class="p-3 pr-4 text-right whitespace-nowrap">
                                                <a href="{{ route('admin.applications.show', $app->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold rounded-lg text-3xs transition">
                                                    Review Berkas
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200 text-xs text-slate-500 font-medium">
                            Belum ada berkas lamaran baru yang masuk.
                        </div>
                    @endif
                </div>

                <!-- Right Column: Upcoming Interviews -->
                <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/80 p-6 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-extrabold text-base text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-calendar-check text-purple-600"></i> Wawancara Mendatang
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Jadwal interview aktif kandidat.</p>
                        </div>
                        <a href="{{ route('admin.calendar.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                            Kalender &rarr;
                        </a>
                    </div>

                    @if(count($upcomingInterviews) > 0)
                        <div class="space-y-3">
                            @foreach($upcomingInterviews as $interview)
                                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 space-y-1.5 text-xs">
                                    <div class="flex justify-between items-start">
                                        <span class="font-extrabold text-slate-900">{{ $interview->application->user->name ?? 'Kandidat' }}</span>
                                        <span class="px-2 py-0.5 bg-purple-50 text-purple-700 font-bold rounded-md border border-purple-200 text-3xs uppercase">
                                            {{ strtoupper($interview->type) }}
                                        </span>
                                    </div>
                                    <p class="text-2xs font-semibold text-slate-600">{{ $interview->application->job->title ?? '-' }}</p>
                                    <div class="text-3xs font-medium text-slate-400 pt-1 flex items-center gap-1.5 border-t border-slate-200/50">
                                        <i class="fa-regular fa-clock text-slate-400"></i>
                                        <span>{{ $interview->scheduled_at->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200 text-xs text-slate-400">
                            Belum ada jadwal wawancara mendatang.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
