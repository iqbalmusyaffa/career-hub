<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-blue-600"></i> Executive Analytics & Command Center
                </h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Analisis makro performa rekrutmen, konversi pelamar, dan efisiensi platform.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-4 rounded-xl text-xs transition border border-slate-200">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- KPI Summary Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Applications -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-500">Total Lamaran Masuk</span>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm border border-blue-100">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($totalApplications) }}</div>
                    <p class="text-3xs text-slate-500 font-medium">Melalui portal karir platform</p>
                </div>

                <!-- Conversion Rate -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-500">Conversion Hired Rate</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm border border-emerald-100">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-emerald-600 tracking-tight">{{ $conversionRate }}%</div>
                    <p class="text-3xs text-emerald-700 font-bold">Rasio Pelamar ➔ Diterima Kerja</p>
                </div>

                <!-- Avg Time to Hire -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-500">Rata-rata Waktu Rekrutmen</span>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm border border-indigo-100">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-indigo-600 tracking-tight">{{ $avgDaysToHire }} <span class="text-sm font-bold text-slate-500">Hari</span></div>
                    <p class="text-3xs text-indigo-700 font-bold">Time-to-Hire Rata-rata</p>
                </div>

                <!-- Total Companies -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-500">Perusahaan Terdaftar</span>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm border border-purple-100">
                            <i class="fa-solid fa-building"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-purple-600 tracking-tight">{{ number_format($totalCompanies) }}</div>
                    <p class="text-3xs text-slate-500 font-medium">Perusahaan aktif di platform</p>
                </div>
            </div>

            <!-- Pipeline Breakdown & Top Hiring Companies -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Funnel Stage Distribution (2 Cols) -->
                <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 p-6 space-y-6 lg:col-span-2">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-filter-circle-dollar text-blue-600"></i> Distribusi Tahapan Funnel Rekrutmen Global
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Breakdown status lamaran di seluruh perusahaan.</p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center">
                        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200/80 space-y-1">
                            <span class="text-3xs font-black uppercase text-amber-700 block">Reviewing</span>
                            <div class="text-2xl font-black text-amber-900">{{ number_format($pendingCount) }}</div>
                            <span class="text-3xs text-amber-700 font-medium">Menunggu</span>
                        </div>
                        <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-200/80 space-y-1">
                            <span class="text-3xs font-black uppercase text-indigo-700 block">Ujian Tes</span>
                            <div class="text-2xl font-black text-indigo-900">{{ number_format($testCount) }}</div>
                            <span class="text-3xs text-indigo-700 font-medium">Tes Online</span>
                        </div>
                        <div class="p-4 bg-purple-50 rounded-2xl border border-purple-200/80 space-y-1">
                            <span class="text-3xs font-black uppercase text-purple-700 block">Wawancara</span>
                            <div class="text-2xl font-black text-purple-900">{{ number_format($interviewCount) }}</div>
                            <span class="text-3xs text-purple-700 font-medium">Interview</span>
                        </div>
                        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200/80 space-y-1">
                            <span class="text-3xs font-black uppercase text-emerald-700 block">Hired</span>
                            <div class="text-2xl font-black text-emerald-900">{{ number_format($hiredCount) }}</div>
                            <span class="text-3xs text-emerald-700 font-bold">Diterima</span>
                        </div>
                        <div class="p-4 bg-rose-50 rounded-2xl border border-rose-200/80 space-y-1">
                            <span class="text-3xs font-black uppercase text-rose-700 block">Rejected</span>
                            <div class="text-2xl font-black text-rose-900">{{ number_format($rejectedCount) }}</div>
                            <span class="text-3xs text-rose-700 font-medium">Gagal</span>
                        </div>
                    </div>

                    <!-- Top Categories -->
                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <h4 class="font-extrabold text-xs text-slate-900 uppercase tracking-wider">🔥 Kategori Divisi Paling Banyak Dibuka</h4>
                        <div class="space-y-2">
                            @foreach($topDivisions as $div)
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs font-bold text-slate-800">
                                    <span>{{ $div->division ?? 'Umum' }}</span>
                                    <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 rounded-lg text-3xs font-black">{{ $div->count }} Lowongan</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Top Hiring Companies Sidebar -->
                <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 p-6 space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-trophy text-amber-500"></i> Top Hiring Companies
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Perusahaan teraktif membuka lowongan.</p>
                    </div>

                    <div class="space-y-3">
                        @foreach($topCompanies as $idx => $comp)
                            <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-200/80 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-slate-900 text-white font-black text-xs flex items-center justify-center shrink-0">
                                    #{{ $idx + 1 }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-extrabold text-xs text-slate-900 truncate">{{ $comp->company_name }}</h4>
                                    <p class="text-3xs text-slate-500 mt-0.5 font-medium truncate">{{ $comp->industry ?? 'Software & Tech' }}</p>
                                </div>
                                @if($comp->is_verified)
                                    <i class="fa-solid fa-circle-check text-blue-600 text-xs shrink-0" title="Perusahaan Terverifikasi"></i>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
