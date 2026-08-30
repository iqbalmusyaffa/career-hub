<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('mentor.dashboard') }}" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 transition">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <h2 class="font-extrabold text-xl text-slate-900 dark:text-white leading-tight">
                        Peninjauan Presensi Magang
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Anak Magang: {{ $logbook->intern->name }} • {{ $logbook->date->isoFormat('D MMMM YYYY') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 border border-blue-300 rounded-full text-3xs font-black uppercase">
                    Status: {{ $logbook->status }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Detail Information Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-4">
                    <div>
                        <span class="text-3xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">PROFIL ANAK MAGANG</span>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white mt-0.5">{{ $logbook->intern->name }}</h3>
                        <p class="text-xs text-slate-500">{{ $logbook->intern->email }}</p>
                    </div>

                    <div class="text-right">
                        <span class="text-3xs font-extrabold text-slate-400 uppercase tracking-wider block">Tanggal Laporan</span>
                        <span class="text-sm font-extrabold text-slate-900 dark:text-white">{{ $logbook->date->format('d M Y') }}</span>
                    </div>
                </div>

                <!-- GPS Geolocation Details -->
                <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-lg">
                            📍
                        </div>
                        <div>
                            <span class="text-3xs font-extrabold text-slate-400 uppercase">Lokasi Presensi GPS</span>
                            <div class="text-xs font-extrabold text-slate-800 dark:text-slate-200">
                                {{ $logbook->location_address ?? 'Lokasi Terverifikasi' }}
                            </div>
                            @if($logbook->latitude)
                                <div class="text-3xs text-slate-400 mt-0.5">
                                    Lat: {{ $logbook->latitude }}, Long: {{ $logbook->longitude }}
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($logbook->latitude)
                        <a href="https://maps.google.com/?q={{ $logbook->latitude }},{{ $logbook->longitude }}" target="_blank" class="px-3.5 py-1.5 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-xl border border-slate-200 dark:border-slate-700 text-3xs font-extrabold hover:bg-slate-100 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-map-location-dot text-rose-500"></i> Buka Google Maps
                        </a>
                    @endif
                </div>

                <!-- Logbook Content -->
                <div class="space-y-4">
                    <div>
                        <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider mb-1">Uraian Aktivitas</h4>
                        <p class="text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-700/60 leading-relaxed font-medium">
                            {{ $logbook->activities }}
                        </p>
                    </div>

                    <div>
                        <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider mb-1">Pembelajaran yang Diperoleh</h4>
                        <p class="text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-700/60 leading-relaxed font-medium">
                            {{ $logbook->learnings }}
                        </p>
                    </div>

                    <div>
                        <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider mb-1">Kendala yang Dialami</h4>
                        <p class="text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-700/60 leading-relaxed font-medium">
                            {{ $logbook->challenges }}
                        </p>
                    </div>
                </div>

                <!-- Mentor / Super Admin ACC Action Form -->
                <div class="pt-6 border-t border-slate-200 dark:border-slate-700 space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">
                            Aksi Keputusan {{ auth()->user()->hasRole('Super Admin') ? 'Super Admin / Mentor' : 'Mentor' }} (ACC)
                        </h4>
                        @if(auth()->user()->hasRole('Super Admin'))
                            <span class="px-2.5 py-0.5 rounded-full text-3xs font-extrabold bg-purple-100 text-purple-700 border border-purple-300">
                                👑 Hak Akses Super Admin
                            </span>
                        @endif
                    </div>

                    <form action="{{ route('mentor.logbooks.approve', $logbook->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-2xs font-extrabold text-slate-500 uppercase tracking-wider mb-1">Catatan / Feedback Bimbingan {{ auth()->user()->hasRole('Super Admin') ? 'Super Admin / Mentor' : 'Mentor' }} (Opsional)</label>
                            <textarea name="mentor_notes" rows="2" placeholder="Tuliskan apresiasi, masukan, atau instruksi perbaikan untuk anak magang..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 p-3 font-medium">{{ old('mentor_notes', $logbook->mentor_notes ?? 'Sangat baik, pertahankan konsistensi laporan dan pengerjaan tugas.') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <button type="submit" formaction="{{ route('mentor.logbooks.reject', $logbook->id) }}" name="status_type" value="action_required" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-extrabold text-xs transition">
                                🔺 Minta Revisi
                            </button>
                            <button type="submit" formaction="{{ route('mentor.logbooks.reject', $logbook->id) }}" name="status_type" value="rejected" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-extrabold text-xs transition">
                                ❌ Tolak Presensi
                            </button>
                            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition flex items-center gap-2">
                                <i class="fa-solid fa-circle-check"></i>
                                ✔️ Setujui & ACC Presensi
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
