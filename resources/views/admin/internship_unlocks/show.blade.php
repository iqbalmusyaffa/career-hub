<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.internship-unlocks.index') }}" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 transition">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <h2 class="font-extrabold text-xl text-slate-900 dark:text-white leading-tight">
                        Review Tiket Buka Kunci Presensi #ULK-{{ str_pad($unlockRequest->id, 4, '0', STR_PAD_LEFT) }}
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Diajukan oleh Mentor: {{ $unlockRequest->mentor->name }} • {{ $unlockRequest->created_at->isoFormat('D MMMM YYYY H:mm') }} WIB
                    </p>
                </div>
            </div>

            <div>
                @if($unlockRequest->status === 'approved')
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300">
                        ✔️ Disetujui Super Admin
                    </span>
                @elseif($unlockRequest->status === 'rejected')
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-red-100 text-red-800 border border-red-300">
                        ❌ Ditolak
                    </span>
                @else
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-300">
                        ⏳ Menunggu Keputusan
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Detail Information Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 border-b border-slate-100 dark:border-slate-700 pb-5">
                    <div>
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-400 block">Peserta Magang</span>
                        <h3 class="text-sm font-black text-slate-900 dark:text-white mt-1">{{ $unlockRequest->intern->name }}</h3>
                        <p class="text-xs text-slate-500">{{ $unlockRequest->intern->email }}</p>
                    </div>

                    <div>
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-400 block">Tanggal yang Diminta Dibuka</span>
                        <h3 class="text-base font-black text-indigo-600 dark:text-indigo-400 mt-0.5">{{ $unlockRequest->target_date->isoFormat('D MMMM YYYY') }}</h3>
                        <span class="text-3xs text-rose-500 font-bold">Terkunci karena lewat batas 23:59</span>
                    </div>

                    <div>
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-400 block">Mitra Perusahaan</span>
                        <h3 class="text-sm font-black text-slate-900 dark:text-white mt-1">{{ $unlockRequest->company->company_name ?? 'Mitra Perusahaan' }}</h3>
                        <span class="text-3xs text-slate-500">Mentor: {{ $unlockRequest->mentor->name }}</span>
                    </div>
                </div>

                <!-- Kategori Kendala -->
                <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-2">
                    <span class="text-3xs font-black uppercase tracking-wider text-slate-400">Kategori Alasan Pengajuan:</span>
                    <div class="text-sm font-black text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        @if($unlockRequest->category === 'platform_outage')
                            <span>🌐 Gangguan Teknis Server Penyelenggara / Platform Outage</span>
                        @elseif($unlockRequest->category === 'partner_issue')
                            <span>🏢 Kendala Operasional Resmi Mitra Perusahaan (Server Down / Pemadaman Total)</span>
                        @else
                            <span>🚨 Keadaan Darurat Lapangan / Force Majeure</span>
                        @endif
                    </div>
                </div>

                <!-- Uraian Kronologi -->
                <div class="space-y-2">
                    <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Uraian & Kronologi Kejadian oleh Mentor:</h4>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/40 rounded-2xl border border-slate-200 dark:border-slate-700 text-xs leading-relaxed text-slate-800 dark:text-slate-200 font-medium">
                        {{ $unlockRequest->description }}
                    </div>
                </div>

                <!-- Bukti Lampiran File -->
                <div class="space-y-2">
                    <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Bukti Dokumen / Screenshot Pendukung:</h4>
                    @if($unlockRequest->attachment_path)
                        <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                                    📎
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200">Berkas Bukti Resmi Terlampir</div>
                                    <span class="text-3xs text-slate-400">Format file dokumen / gambar valid</span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $unlockRequest->attachment_path) }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-extrabold transition shadow-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Dokumen
                            </a>
                        </div>
                    @else
                        <div class="p-4 bg-amber-50 dark:bg-amber-950/40 rounded-2xl border border-amber-200 dark:border-amber-900 text-xs text-amber-800 dark:text-amber-200 font-medium">
                            ⚠️ Mentor tidak melampirkan berkas bukti dokumen.
                        </div>
                    @endif
                </div>

                <!-- Decision / Action Form for Super Admin -->
                @if($unlockRequest->status === 'pending')
                    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 space-y-4">
                        <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Aksi Keputusan Super Admin</h4>
                        
                        <form action="{{ route('admin.internship-unlocks.approve', $unlockRequest->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-2xs font-extrabold text-slate-500 uppercase tracking-wider mb-1">Catatan Keputusan / Instruksi Super Admin (Wajib)</label>
                                <textarea name="admin_notes" rows="2" placeholder="Tuliskan catatan persetujuan atau alasan penolakan..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 p-3 font-medium">Permohonan disetujui. Akses buka kunci presensi diberikan selama 24 jam ke depan.</textarea>
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Durasi Akses:</span>
                                    <select name="unlock_hours" class="text-xs font-bold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 py-1.5 px-3">
                                        <option value="24" selected>1x24 Jam (24 Jam)</option>
                                        <option value="48">2x24 Jam (48 Jam)</option>
                                        <option value="12">12 Jam</option>
                                    </select>
                                </div>

                                <div class="flex items-center gap-3">
                                    <button type="submit" formaction="{{ route('admin.internship-unlocks.reject', $unlockRequest->id) }}" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-extrabold text-xs transition flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-xmark"></i> Tolak Permohonan
                                    </button>
                                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-check"></i> ✔️ Setujui Buka Kunci Tanggal
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="p-4 bg-slate-100 dark:bg-slate-900/80 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-1">
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-400">Catatan Keputusan Super Admin:</span>
                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-200">"{{ $unlockRequest->admin_notes }}"</p>
                        <span class="text-3xs text-slate-400 block pt-1">Diselesaikan oleh: {{ $unlockRequest->resolver->name ?? 'Super Admin' }} • {{ $unlockRequest->updated_at->format('d M Y H:i') }} WIB</span>
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
