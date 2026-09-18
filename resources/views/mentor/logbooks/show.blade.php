<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <a href="{{ route('mentor.logbooks.intern', $logbook->user_id) }}" 
                   class="w-9 h-9 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition flex items-center justify-center shadow-2xs">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-0.5">
                        <a href="{{ route('mentor.logbooks.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Peserta Magang</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <a href="{{ route('mentor.logbooks.intern', $logbook->user_id) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">{{ $logbook->intern->name }}</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">Verifikasi Presensi</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Peninjauan Presensi & Logbook
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if($logbook->status === 'approved')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900 rounded-full text-xs font-semibold">
                        <i class="fa-solid fa-circle-check text-[11px]"></i>
                        <span>Disetujui (ACC)</span>
                    </span>
                @elseif($logbook->status === 'rejected')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200/80 dark:border-rose-900 rounded-full text-xs font-semibold">
                        <i class="fa-solid fa-circle-xmark text-[11px]"></i>
                        <span>Kehadiran Ditolak</span>
                    </span>
                @elseif($logbook->status === 'action_required')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200/80 dark:border-amber-900 rounded-full text-xs font-semibold">
                        <i class="fa-solid fa-triangle-exclamation text-[11px]"></i>
                        <span>Perlu Revisi</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200/80 dark:border-amber-900 rounded-full text-xs font-semibold">
                        <i class="fa-regular fa-clock text-[11px]"></i>
                        <span>Menunggu ACC Mentor</span>
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Detail Information Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
                
                <!-- Card Header -->
                <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-950/40">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/80 dark:border-blue-900 font-bold flex items-center justify-center text-sm shrink-0">
                            {{ strtoupper(substr($logbook->intern->name ?? 'M', 0, 1)) }}
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Profil Peserta Magang</span>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white leading-tight mt-0.5">
                                {{ $logbook->intern->name }}
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $logbook->intern->email }}</p>
                        </div>
                    </div>

                    <div class="sm:text-right flex flex-col sm:items-end gap-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Tanggal & Kehadiran</span>
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $logbook->date->isoFormat('dddd, D MMMM YYYY') }}
                        </div>
                        @php
                            $attLabel = $logbook->attendance_type_label ?? $logbook->attendance_type;
                            $attColor = match($attLabel) {
                                'Hadir' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-900',
                                'Tidak Hadir Dengan Keterangan' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200/80 dark:border-amber-900',
                                'Tidak Hadir Tanpa Keterangan' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200/80 dark:border-rose-900',
                                default => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                            };
                        @endphp
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $attColor }}">
                                {{ $attLabel }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- GPS Geolocation Details -->
                <div class="p-5 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 border border-teal-200/80 dark:border-teal-900 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Titik Koordinat GPS Presensi</span>
                                <div class="text-xs font-semibold text-slate-900 dark:text-white mt-0.5">
                                    {{ $logbook->location_address ?? 'Lokasi Terverifikasi Sistem' }}
                                </div>
                                @if($logbook->latitude)
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">
                                        Lat: {{ $logbook->latitude }}, Long: {{ $logbook->longitude }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($logbook->latitude)
                            <a href="https://maps.google.com/?q={{ $logbook->latitude }},{{ $logbook->longitude }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-800 text-xs font-medium transition shadow-2xs self-start sm:self-center">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                                <span>Buka Google Maps</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Logbook Content Sections -->
                <div class="p-5 sm:p-6 space-y-5 bg-white dark:bg-slate-900">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                            Uraian Aktivitas & Pengerjaan Tugas
                        </label>
                        <div class="text-xs text-slate-800 dark:text-slate-200 bg-slate-50/70 dark:bg-slate-950/40 p-4 rounded-xl border border-slate-200/70 dark:border-slate-800 leading-relaxed">
                            {{ $logbook->activities ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                            Pembelajaran yang Diperoleh
                        </label>
                        <div class="text-xs text-slate-800 dark:text-slate-200 bg-slate-50/70 dark:bg-slate-950/40 p-4 rounded-xl border border-slate-200/70 dark:border-slate-800 leading-relaxed">
                            {{ $logbook->learnings ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                            Kendala / Hambatan
                        </label>
                        <div class="text-xs text-slate-800 dark:text-slate-200 bg-slate-50/70 dark:bg-slate-950/40 p-4 rounded-xl border border-slate-200/70 dark:border-slate-800 leading-relaxed">
                            {{ $logbook->challenges ?? 'Tidak ada kendala yang dilaporkan.' }}
                        </div>
                    </div>

                    @if($logbook->doctor_note_path)
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Lampiran Surat Dokter / Dokumen Izin
                            </label>
                            <div class="p-4 rounded-xl bg-amber-50/50 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-900 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm shrink-0">
                                        @if(\Illuminate\Support\Str::endsWith($logbook->doctor_note_path, '.pdf'))
                                            <i class="fa-solid fa-file-pdf text-rose-500"></i>
                                        @else
                                            <i class="fa-solid fa-file-image text-blue-500"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-900 dark:text-white">Surat Keterangan Dokter Terlampir</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Dokumen bukti ketidakhadiran diunggah oleh peserta.</div>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $logbook->doctor_note_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition shadow-2xs">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    <span>Buka Dokumen</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Mentor / Super Admin ACC Action Form or Approved Verification Summary -->
                @if($logbook->status === 'approved')
                    <div class="p-5 sm:p-6 border-t border-slate-100 dark:border-slate-800 bg-emerald-50/40 dark:bg-emerald-950/20 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-xs font-bold shrink-0">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold text-slate-900 dark:text-white">
                                        Presensi Telah Disetujui & Diverifikasi (ACC)
                                    </h3>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                        Diverifikasi oleh {{ $logbook->mentor->name ?? 'Pembimbing/Super Admin' }}
                                        @if($logbook->approved_at)
                                            pada {{ $logbook->approved_at->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-200 border border-emerald-300 dark:border-emerald-800 rounded-full text-xs font-semibold">
                                <i class="fa-solid fa-circle-check text-[11px]"></i>
                                <span>Status Final: ACC</span>
                            </span>
                        </div>

                        @if($logbook->mentor_notes)
                            <div class="mt-3 p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-emerald-200/60 dark:border-emerald-900/50">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block mb-1">Catatan Pembimbing / Mentor</span>
                                <p class="text-xs text-slate-700 dark:text-slate-300 italic">"{{ $logbook->mentor_notes }}"</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-5 sm:p-6 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">
                                    Keputusan Evaluasi & Verifikasi (ACC)
                                </h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                    Berikan catatan masukan pembimbing sebelum menyetujui atau meminta revisi laporan.
                                </p>
                            </div>
                            @if(auth()->user()->hasRole('Super Admin'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-2xs font-semibold bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200/80 dark:border-purple-900">
                                    <i class="fa-solid fa-shield-halved text-[9px]"></i>
                                    <span>Akses Super Admin</span>
                                </span>
                            @endif
                        </div>

                        <form action="{{ route('mentor.logbooks.approve', $logbook->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ url()->previous() != url()->current() ? url()->previous() : route('admin.internship-monitor.index') }}">

                            <div>
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">
                                    Catatan / Feedback Pembimbing (Opsional)
                                </label>
                                <textarea name="mentor_notes" rows="2" 
                                          placeholder="Tuliskan apresiasi, masukan, atau arahan tugas selanjutnya..." 
                                          class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">{{ old('mentor_notes', $logbook->mentor_notes ?? ($logbook->status === 'rejected' ? 'Laporan ditolak. Silakan periksa kembali instruksi.' : 'Sangat baik, pertahankan konsistensi laporan dan pengerjaan tugas.')) }}</textarea>
                            </div>

                            <div class="flex flex-wrap items-center justify-end gap-2.5 pt-2">
                                <button type="submit" 
                                        formaction="{{ route('mentor.logbooks.reject', $logbook->id) }}" 
                                        name="status_type" 
                                        value="action_required" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-amber-600 dark:text-amber-400 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold transition shadow-2xs">
                                    <i class="fa-solid fa-rotate-left text-[10px]"></i>
                                    <span>Minta Revisi</span>
                                </button>

                                <button type="submit" 
                                        formaction="{{ route('mentor.logbooks.reject', $logbook->id) }}" 
                                        name="status_type" 
                                        value="rejected" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-slate-900 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-slate-200 dark:border-slate-800 hover:border-rose-200 dark:hover:border-rose-900 rounded-xl text-xs font-semibold transition shadow-2xs">
                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                    <span>Tolak Presensi</span>
                                </button>

                                <button type="submit" 
                                        class="inline-flex items-center gap-1.5 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition shadow-xs">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                    <span>Setujui & ACC Presensi</span>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
