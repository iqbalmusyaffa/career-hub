<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('mentor.unlock-requests.index') }}" class="w-9 h-9 rounded-xl bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center justify-center transition shadow-2xs">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight">
                        Form Pengajuan Buka Kunci Presensi Magang
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Permohonan resmi pembukaan tanggal terlewat yang dikirimkan ke Super Admin.
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 dark:bg-slate-950 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8 space-y-6">
                
                <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-lock-open text-[10px]"></i> Formulir Permohonan Resmi
                    </span>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">Pengajuan Dispensasi Tanggal Terkunci</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Sampaikan permohonan pembukaan akses presensi untuk tanggal terlewat atau cuti bersama secara resmi kepada Super Admin.
                    </p>
                </div>

                @if($errors->any())
                    <div class="p-4 bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 rounded-xl space-y-1 text-xs font-medium shadow-xs">
                        <div class="flex items-center gap-2 font-bold text-rose-900 dark:text-rose-100">
                            <i class="fa-solid fa-triangle-exclamation text-rose-600 dark:text-rose-400"></i>
                            <span>Terdapat kesalahan pengisian formulir:</span>
                        </div>
                        <ul class="list-disc list-inside pl-4 text-rose-700 dark:text-rose-300 space-y-0.5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div x-data="{
                    selectedBatch: '{{ old('batch_filter', '') }}',
                    selectedInternId: '{{ old('intern_id', '') }}',
                    allInterns: {{ Js::from($interns->map(fn($i) => [
                        'id' => $i->id,
                        'name' => $i->name,
                        'email' => $i->email,
                        'batch' => $i->internshipPeriod?->period_name ?? $i->applications->first()?->job?->batch ?? 'Batch 1 - 2026',
                        'job_title' => $i->applications->first()?->job?->title ?? 'Peserta Magang',
                    ])) }},
                    get filteredInterns() {
                        if (!this.selectedBatch) return this.allInterns;
                        return this.allInterns.filter(i => i.batch === this.selectedBatch);
                    },
                    get selectedIntern() {
                        return this.allInterns.find(i => i.id == this.selectedInternId) || null;
                    },
                    onBatchChange() {
                        if (this.selectedIntern && this.selectedBatch && this.selectedIntern.batch !== this.selectedBatch) {
                            this.selectedInternId = '';
                        }
                    }
                }">
                    <form action="{{ route('mentor.unlock-requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Field: Filter / Pilih Batch Magang -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 flex items-center justify-between">
                                    <span>Pilih / Filter Batch Magang</span>
                                    <span class="text-[10px] text-slate-400 font-normal">Opsional</span>
                                </label>
                                <select name="batch_filter" x-model="selectedBatch" @change="onBatchChange()" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition p-3">
                                    <option value="">-- Semua Batch Magang --</option>
                                    @foreach($batches as $batchName)
                                        <option value="{{ $batchName }}">{{ $batchName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Field 1: Pilih Anak Magang -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                    Pilih Peserta Magang <span class="text-rose-500">*</span>
                                </label>
                                <select name="intern_id" x-model="selectedInternId" required class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition p-3">
                                    <option value="" class="dark:bg-slate-900">-- Pilih Peserta Magang --</option>
                                    <template x-for="intern in filteredInterns" :key="intern.id">
                                        <option :value="intern.id" x-text="intern.name + ' (' + intern.email + ') — [' + intern.batch + ']'"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Selected Intern Detail Preview Card -->
                        <template x-if="selectedIntern">
                            <div class="p-3.5 rounded-xl bg-blue-50/70 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900/60 flex items-center justify-between gap-3 text-xs animate-fade-in">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                                        <span x-text="selectedIntern.name.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white" x-text="selectedIntern.name"></div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400" x-text="selectedIntern.email + ' • ' + selectedIntern.job_title"></div>
                                    </div>
                                </div>
                                <div>
                                    <span class="px-2.5 py-1 rounded-lg bg-blue-100 dark:bg-blue-900/80 text-blue-700 dark:text-blue-300 font-bold text-[10px]" x-text="selectedIntern.batch"></span>
                                </div>
                            </div>
                        </template>

                    <!-- Field 2: Tanggal yang Terkunci -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Tanggal Presensi yang Terkunci <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="target_date" max="{{ now()->format('Y-m-d') }}" value="{{ old('target_date') }}" required class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition p-3">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-1.5">
                            <i class="fa-regular fa-clock text-slate-400 text-xs"></i>
                            <span>Hanya dapat memilih tanggal hari ini atau tanggal yang sudah lewat.</span>
                        </p>
                    </div>

                    <!-- Field 3: Kategori Kendala Resmi -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Kategori Kendala Resmi <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" required class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition p-3">
                            <option value="medical_emergency" {{ old('category') == 'medical_emergency' ? 'selected' : '' }} class="dark:bg-slate-900">
                                🏥 Sakit / Rawat Inap / Pengobatan Medis (RS / Puskesmas / Surat Dokter)
                            </option>
                            <option value="academic_urgent" {{ old('category') == 'academic_urgent' ? 'selected' : '' }} class="dark:bg-slate-900">
                                🎓 Keperluan Akademik Kampus Mendesak (Wisuda, Sidang Akhir, Ijazah, Yudisium)
                            </option>
                            <option value="personal_urgent" {{ old('category') == 'personal_urgent' ? 'selected' : '' }} class="dark:bg-slate-900">
                                🚨 Keperluan Mendesak Pribadi / Keluarga Inti (Duka Cita / Musibah Keluarga / Dokumen Negara Wajib)
                            </option>
                            <option value="cuti_bersama" {{ old('category') == 'cuti_bersama' ? 'selected' : '' }} class="dark:bg-slate-900">
                                🏖️ Cuti Bersama / Hari Libur Tertentu (Dispensasi Magang)
                            </option>
                            <option value="dinas_luar" {{ old('category') == 'dinas_luar' ? 'selected' : '' }} class="dark:bg-slate-900">
                                💼 Penugasan Dinas Luar / Event Lapangan Mitra Perusahaan
                            </option>
                            <option value="libur_nasional_agenda" {{ old('category') == 'libur_nasional_agenda' ? 'selected' : '' }} class="dark:bg-slate-900">
                                🏛️ Penugasan Agenda Libur Nasional / Penyesuaian Kalender
                            </option>
                            <option value="platform_outage" {{ old('category') == 'platform_outage' ? 'selected' : '' }} class="dark:bg-slate-900">
                                🌐 Gangguan Teknis Server Penyelenggara (Platform Outage)
                            </option>
                            <option value="partner_issue" {{ old('category') == 'partner_issue' ? 'selected' : '' }} class="dark:bg-slate-900">
                                🏢 Kendala Operasional Resmi Mitra Kantor (Pemadaman Server/Jaringan/Listrik)
                            </option>
                            <option value="force_majeure" {{ old('category') == 'force_majeure' ? 'selected' : '' }} class="dark:bg-slate-900">
                                🚨 Keadaan Darurat Lapangan / Bencana / Musibah (Force Majeure)
                            </option>
                        </select>
                        
                        <!-- Warning / Notice Alert Box -->
                        <div class="mt-2.5 p-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-amber-900 dark:text-amber-200 text-xs flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-info text-amber-600 dark:text-amber-400 mt-0.5 shrink-0 text-sm"></i>
                            <div class="leading-relaxed">
                                <strong>Ketentuan Sistem:</strong> Dispensasi resmi diterima untuk: <u>Sakit/Rawat Medis</u> (lampirkan surat dokter), <u>Akademik Kampus</u> (Wisuda/Sidang/Ijazah), <u>Cuti Bersama</u>, <u>Dinas Luar</u>, <u>Server Outage</u>, atau <u>Force Majeure</u>. Pengajuan akibat kelalaian pribadi ('Lupa Absen') ditolak sistem.
                            </div>
                        </div>
                    </div>

                    <!-- Field 4: Uraian & Kronologi Kejadian -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Uraian & Kronologi Kejadian <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="description" rows="4" placeholder="Jelaskan secara rinci kendala teknis, kegiatan cuti bersama/libur, atau force majeure yang terjadi pada tanggal terkait..." required class="w-full rounded-xl text-xs font-medium text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500 p-3.5">{{ old('description') }}</textarea>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Minimal 20 karakter penjelasan untuk membantu Super Admin mengevaluasi pengajuan.</p>
                    </div>

                    <!-- Field 5: Upload Berkas Bukti -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Upload Bukti Lampiran (Screenshot Error / Surat Resmi / Surat Tugas) <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950/70 text-slate-800 dark:text-slate-200 p-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 dark:file:bg-indigo-950/60 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 transition cursor-pointer">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-1.5">
                            <i class="fa-regular fa-file text-slate-400 text-xs"></i>
                            <span>Format berkas: PDF, JPG, PNG (Maksimal 5MB).</span>
                        </p>
                    </div>

                    <!-- Field 6: Pernyataan Integritas Mentor -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-200 dark:border-slate-800">
                        <label class="flex items-start gap-3 cursor-pointer select-none">
                            <input type="checkbox" name="integrity_declaration" value="1" required class="mt-0.5 rounded text-blue-600 focus:ring-blue-500 border-slate-300 dark:border-slate-700 h-4 w-4 shrink-0 cursor-pointer">
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-medium leading-relaxed">
                                Saya selaku Mentor menyatakan bahwa permohonan ini diajukan atas kendala teknis, cuti bersama, atau operasional resmi dan <strong class="text-slate-900 dark:text-white">bukan akibat kelalaian peserta magang</strong>. Saya bertanggung jawab penuh atas keabsahan data permohonan ini.
                            </span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ route('mentor.unlock-requests.index') }}" class="px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs rounded-xl border border-slate-200 dark:border-slate-700 transition cursor-pointer">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center gap-2 border border-blue-600 cursor-pointer">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Kirim Permohonan ke Super Admin</span>
                        </button>
                    </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
