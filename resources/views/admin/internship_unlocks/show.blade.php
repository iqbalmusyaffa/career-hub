<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb & Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.internship-unlocks.index') }}" class="w-9 h-9 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750 text-slate-600 dark:text-slate-300 flex items-center justify-center text-xs transition shadow-2xs">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-0.5">
                            <a href="{{ route('admin.internship-unlocks.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Buka Kunci Presensi</a>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                            <span class="text-purple-600 dark:text-purple-400 font-mono">#ULK-{{ str_pad($unlockRequest->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">
                            Review Permohonan Buka Kunci
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if($unlockRequest->status === 'approved')
                        <a href="{{ route('admin.internship-unlocks.pdf', $unlockRequest->id) }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-2xs">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>Unduh Surat Resmi (PDF)</span>
                        </a>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-900">
                            <i class="fa-solid fa-circle-check text-xs"></i>
                            <span>Disetujui</span>
                        </span>
                    @elseif($unlockRequest->status === 'rejected')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">
                            <i class="fa-solid fa-circle-xmark text-xs"></i>
                            <span>Ditolak</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>Menunggu Keputusan</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Detail Information Card -->
            <div class="bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs p-6 space-y-6">
                
                <!-- 3 Columns Metadata Summary -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 border-b border-slate-100 dark:border-slate-700/80 pb-6">
                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-1">Peserta Magang</span>
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $unlockRequest->intern->name }}</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $unlockRequest->intern->email }}</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-purple-50/50 dark:bg-purple-950/30 border border-purple-100 dark:border-purple-900/40">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400 block mb-1">Tanggal Terkunci</span>
                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $unlockRequest->target_date->isoFormat('D MMMM YYYY') }}</div>
                        <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-0.5">Lewat cut-off 23:59 WIB</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-1">Mitra & Pengaju</span>
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $unlockRequest->company->company_name ?? 'Mitra Perusahaan' }}</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Oleh: {{ $unlockRequest->mentor->name }}</p>
                    </div>
                </div>

                <!-- Reason Category Badge & Description -->
                <div class="space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kategori Kendala</span>
                    <div>
                        @if($unlockRequest->category === 'medical_emergency')
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">
                                <i class="fa-solid fa-hospital-user"></i>
                                <span>Sakit / Rawat Inap / Penanganan Medis</span>
                            </div>
                        @elseif($unlockRequest->category === 'academic_urgent')
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-900">
                                <i class="fa-solid fa-graduation-cap"></i>
                                <span>Keperluan Akademik Kampus Urgent (Wisuda, Sidang Akhir, Ijazah, Yudisium)</span>
                            </div>
                        @elseif($unlockRequest->category === 'personal_urgent')
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">
                                <i class="fa-solid fa-heart-pulse"></i>
                                <span>Keperluan Mendesak Pribadi / Keluarga Inti</span>
                            </div>
                        @elseif($unlockRequest->category === 'dinas_luar')
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900">
                                <i class="fa-solid fa-briefcase"></i>
                                <span>Penugasan Dinas Luar / Event Lapangan</span>
                            </div>
                        @elseif($unlockRequest->category === 'cuti_bersama')
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold bg-teal-50 text-teal-700 border border-teal-200/80 dark:bg-teal-950/60 dark:text-teal-300 dark:border-teal-900">
                                <i class="fa-solid fa-umbrella-beach"></i>
                                <span>Cuti Bersama / Hari Libur Tertentu (Dispensasi Magang)</span>
                            </div>
                        @elseif($unlockRequest->category === 'libur_nasional_agenda')
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200/80 dark:bg-purple-950/60 dark:text-purple-300 dark:border-purple-900">
                                <i class="fa-solid fa-calendar-star"></i>
                                <span>Penugasan Agenda Libur Nasional / Penyesuaian Kalender</span>
                            </div>
                        @elseif($unlockRequest->category === 'platform_outage')
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/80 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-900">
                                <i class="fa-solid fa-globe"></i>
                                <span>Gangguan Teknis Server Penyelenggara / Platform Outage</span>
                            </div>
                        @elseif($unlockRequest->category === 'partner_issue')
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/80 dark:bg-indigo-950/60 dark:text-indigo-300 dark:border-indigo-900">
                                <i class="fa-solid fa-building-circle-exclamation"></i>
                                <span>Kendala Operasional Resmi Mitra Perusahaan</span>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>Keadaan Darurat Lapangan / Force Majeure</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Chronology Statement -->
                <div class="space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kronologi & Alasan Pengajuan oleh Mentor</span>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700/80 text-xs leading-relaxed text-slate-800 dark:text-slate-200 font-normal">
                        {{ $unlockRequest->description }}
                    </div>
                </div>

                <!-- Attachment Proof File -->
                <div class="space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Berkas Bukti Pendukung</span>
                    @if($unlockRequest->attachment_path)
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700/80 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-900 flex items-center justify-center text-sm font-bold">
                                    <i class="fa-regular fa-file-lines"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-slate-900 dark:text-slate-100">Dokumen Lampiran Resmi</div>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Bukti validasi yang diunggah oleh mentor</span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $unlockRequest->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold transition shadow-2xs">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-blue-500"></i>
                                <span>Buka Berkas</span>
                            </a>
                        </div>
                    @else
                        <div class="p-3 bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-slate-200 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-slate-400"></i>
                            <span>Mentor tidak melampirkan berkas bukti dokumen pendukung.</span>
                        </div>
                    @endif
                </div>

                <!-- Super Admin Action Desk -->
                @if($unlockRequest->status === 'pending')
                    <div class="pt-6 border-t border-slate-200 dark:border-slate-700/80 space-y-4">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">
                                Keputusan Super Admin
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Berikan instruksi persetujuan atau alasan jika permohonan ditolak.
                            </p>
                        </div>
                        
                        <form action="{{ route('admin.internship-unlocks.approve', $unlockRequest->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                    Catatan / Instruksi Keputusan (Wajib)
                                </label>
                                <textarea name="admin_notes" rows="2" placeholder="Tuliskan catatan persetujuan atau alasan penolakan..." required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-900 dark:text-slate-100 p-3 font-normal focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">Permohonan disetujui. Akses buka kunci presensi diberikan selama 24 jam ke depan.</textarea>
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Durasi Buka Kunci:</span>
                                    <select name="unlock_hours" class="text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 py-1.5 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                        <option value="24" selected>1x24 Jam (24 Jam)</option>
                                        <option value="48">2x24 Jam (48 Jam)</option>
                                        <option value="12">12 Jam</option>
                                    </select>
                                </div>

                                <div class="flex items-center gap-2.5">
                                    <button type="submit" formaction="{{ route('admin.internship-unlocks.reject', $unlockRequest->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold transition shadow-xs">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                        <span>Tolak Permohonan</span>
                                    </button>
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition shadow-xs">
                                        <i class="fa-solid fa-check text-xs"></i>
                                        <span>Setujui Buka Kunci</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200 dark:border-slate-700/80 space-y-1.5">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Catatan Keputusan:</span>
                        <p class="text-xs font-medium text-slate-800 dark:text-slate-200">"{{ $unlockRequest->admin_notes }}"</p>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 pt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-user-check text-[10px] text-slate-400"></i>
                            <span>Diproses oleh: {{ $unlockRequest->resolver->name ?? 'Super Admin' }} • {{ $unlockRequest->updated_at->isoFormat('D MMMM YYYY HH:mm') }} WIB</span>
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
