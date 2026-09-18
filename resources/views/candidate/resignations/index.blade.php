<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-lg sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center text-xs shadow-xs shrink-0">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </span>
                    <span>Pengunduran Diri & Pengaturan Akun</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Kelola permohonan pengunduran diri resmi, akses panduan magang, dan sesi akun Anda.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold py-2 px-4 rounded-xl text-xs transition border border-slate-200 dark:border-slate-800 shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl shadow-xs flex items-center gap-3 text-xs font-medium transition">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-300 rounded-2xl shadow-xs flex items-center gap-3 text-xs font-medium transition">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 dark:text-rose-400 text-base shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- 1. INFORMASI PROGRAM MAGANG AKTIF (SESUAI GAMBAR) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 shadow-xs border border-slate-200/80 dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Informasi Program Magang</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900">
                        Aktif Berjalan
                    </span>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400">Perusahaan</span>
                        <span class="font-bold text-slate-900 dark:text-white text-right">{{ $companyName }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400">Posisi</span>
                        <span class="font-bold text-slate-900 dark:text-white text-right">{{ $jobTitle }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400">Lokasi Posisi</span>
                        <span class="font-bold text-slate-900 dark:text-white text-right uppercase">{{ $location }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400">Periode Magang</span>
                        <span class="font-bold text-slate-900 dark:text-white text-right">
                            {{ $startDate ? $startDate->translatedFormat('d F Y') : '-' }} &bull; {{ $endDate ? $endDate->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 2. CARD PENGUNDURAN DIRI (INTERAKTIF & ACCORDION / FORM) -->
            <div x-data="{ openForm: {{ $errors->any() ? 'true' : 'false' }} }" class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 shadow-xs border border-slate-200/80 dark:border-slate-800 space-y-4">
                
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center text-lg shrink-0 border border-rose-200/60 dark:border-rose-900/60">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">Pengunduran Diri</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                            Ajukan pengunduran diri secara mandiri dari aplikasi portal magang.
                        </p>
                    </div>
                </div>

                @if(isset($activeResignation) && $activeResignation)
                    <!-- STATUS PENGAJUAN YANG SEDANG BERJALAN -->
                    <div class="bg-amber-50/60 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/60 p-4 rounded-xl space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded font-bold text-[10px] uppercase {{ $activeResignation->status_badge['class'] }}">
                                {{ $activeResignation->status_badge['label'] }}
                            </span>
                            <span class="text-[11px] text-slate-400 font-medium">Diajukan: {{ $activeResignation->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        <div class="text-xs space-y-1 text-slate-700 dark:text-slate-300">
                            <p><strong>Alasan:</strong> {{ $activeResignation->category_label }}</p>
                            <p><strong>Tanggal Efektif:</strong> {{ $activeResignation->effective_date->translatedFormat('d F Y') }}</p>
                            <p class="italic text-slate-600 dark:text-slate-400 bg-white/70 dark:bg-slate-900/60 p-2.5 rounded-lg border border-amber-200/60 dark:border-amber-900/40">
                                "{{ $activeResignation->reason_details }}"
                            </p>
                            @if($activeResignation->document_path)
                                <div class="pt-1">
                                    <a href="{{ asset('storage/' . $activeResignation->document_path) }}" target="_blank" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-file-pdf"></i> Lihat Surat Pengunduran Diri Diunggah &rarr;
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('candidate.resignations.download', $activeResignation->id) }}" class="py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-xl transition text-xs flex items-center justify-center gap-2 shadow-xs">
                                <i class="fa-solid fa-file-pdf"></i>
                                <span>Unduh Surat Resmi (PDF + QR Code Validasi)</span>
                            </a>
                            <a href="{{ route('resignations.verify.public', $activeResignation->id) }}" target="_blank" class="py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold rounded-xl transition text-xs flex items-center justify-center gap-1.5 border border-slate-700">
                                <i class="fa-solid fa-qrcode"></i>
                                <span>Cek Validasi QR</span>
                            </a>
                        </div>

                        @if($activeResignation->status === 'pending')
                            <div class="pt-2 border-t border-amber-200/80 dark:border-amber-900/60 flex justify-between items-center">
                                <span class="text-[11px] text-slate-500">Menunggu tinjauan tim HR & Pembimbing.</span>
                                <form method="POST" action="{{ route('candidate.resignations.cancel', $activeResignation->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan pengunduran diri ini?');">
                                    @csrf
                                    <button type="submit" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-bold">
                                        Batalkan Pengajuan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- TOMBOL / BANNER BUKA FORM -->
                    <div @click="openForm = !openForm" class="bg-rose-50/40 dark:bg-rose-950/20 hover:bg-rose-50/70 dark:hover:bg-rose-950/30 border border-rose-200/70 dark:border-rose-900/50 p-4 rounded-xl flex items-center justify-between cursor-pointer transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-white dark:bg-slate-900 text-rose-600 flex items-center justify-center text-sm shadow-2xs border border-rose-200/60 dark:border-rose-800">
                                <i class="fa-solid fa-file-signature"></i>
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-rose-950 dark:text-rose-200">Ajukan Pengunduran Diri</h4>
                                <p class="text-[11px] text-rose-700 dark:text-rose-400">Isi alasan, tanggal efektif, dan unggah surat bertanda tangan.</p>
                            </div>
                        </div>
                        <div class="text-rose-400 text-xs font-bold transition-transform duration-200" :class="openForm ? 'rotate-90' : ''">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>

                    <!-- FORM SUBMISSION (COLLAPSIBLE) -->
                    <div x-show="openForm" x-transition class="pt-3 border-t border-slate-100 dark:border-slate-800" style="display: none;">
                        <form method="POST" action="{{ route('candidate.resignations.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                            @csrf

                            <div>
                                <label class="block font-bold text-slate-900 dark:text-white mb-1">
                                    Kategori Alasan Pengunduran Diri <span class="text-rose-500">*</span>
                                </label>
                                <select name="reason_category" required class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white p-2.5 focus:ring-rose-500 focus:border-rose-500">
                                    <option value="">-- Pilih Kategori Alasan --</option>
                                    <option value="academic" {{ old('reason_category') === 'academic' ? 'selected' : '' }}>Akademik / Tugas Akhir Kampus</option>
                                    <option value="health" {{ old('reason_category') === 'health' ? 'selected' : '' }}>Kondisi Kesehatan / Sakit</option>
                                    <option value="relocation" {{ old('reason_category') === 'relocation' ? 'selected' : '' }}>Pindah Domisili / Tempat Tinggal</option>
                                    <option value="personal" {{ old('reason_category') === 'personal' ? 'selected' : '' }}>Alasan Pribadi / Keluarga</option>
                                    <option value="other" {{ old('reason_category') === 'other' ? 'selected' : '' }}>Alasan Lainnya</option>
                                </select>
                                @error('reason_category')
                                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-slate-900 dark:text-white mb-1">
                                    Tanggal Efektif Berhenti <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" name="effective_date" min="{{ date('Y-m-d') }}" value="{{ old('effective_date', date('Y-m-d')) }}" required class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white p-2.5 focus:ring-rose-500 focus:border-rose-500">
                                <p class="text-[11px] text-slate-400 mt-0.5">Hari terakhir Anda bertugas dan mengisi logbook.</p>
                                @error('effective_date')
                                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-slate-900 dark:text-white mb-1">
                                    Rincian Alasan Pengunduran Diri <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="reason_details" rows="3" required placeholder="Jelaskan secara sopan dan terperinci latar belakang pengunduran diri Anda..." class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white p-2.5 focus:ring-rose-500 focus:border-rose-500">{{ old('reason_details') }}</textarea>
                                @error('reason_details')
                                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-slate-900 dark:text-white mb-1">
                                    Catatan Serah Terima Pekerjaan / Aset (Opsional)
                                </label>
                                <textarea name="handover_notes" rows="2" placeholder="Tuliskan daftar tugas atau file yang telah diserahterimakan kepada mentor/rekan kerja..." class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white p-2.5 focus:ring-rose-500 focus:border-rose-500">{{ old('handover_notes') }}</textarea>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-900 dark:text-white mb-1">
                                    Unggah Surat Pengunduran Diri Resmi (PDF/Foto) <span class="text-rose-500">*</span>
                                </label>
                                <input type="file" name="resignation_document" accept=".pdf,.jpg,.jpeg,.png" required class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white p-2 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-rose-600 file:text-white hover:file:bg-rose-700">
                                <p class="text-[11px] text-slate-400 mt-1">Harus menyertakan tanda tangan basah/elektronik Anda. Format PDF/JPG/PNG, Maks. 5MB.</p>
                                @error('resignation_document')
                                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="pt-2 flex justify-end gap-2">
                                <button type="button" @click="openForm = false" class="py-2.5 px-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl transition">
                                    Batal
                                </button>
                                <button type="submit" class="py-2.5 px-5 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-xl shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                                    <i class="fa-solid fa-paper-plane text-xs"></i>
                                    <span>Kirim Pengajuan Pengunduran Diri</span>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <!-- 3. CARD PANDUAN PENGGUNA (SESUAI GAMBAR) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 shadow-xs border border-slate-200/80 dark:border-slate-800">
                <a href="{{ route('pages.guide.candidate') }}" class="flex items-center justify-between group">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0 border border-blue-200/60 dark:border-blue-900/60 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition">Panduan Pengguna</h4>
                            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400">Pelajari tata cara presensi, pengisian logbook, dan evaluasi magang.</p>
                        </div>
                    </div>
                    <div class="text-slate-400 group-hover:text-blue-600 transition-transform group-hover:translate-x-1">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </div>
                </a>
            </div>

            <!-- 4. TOMBOL KELUAR DARI AKUN (LOGOUT) & JAM SERVER REAL-TIME (SESUAI GAMBAR) -->
            <div class="pt-2 text-center space-y-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 font-bold text-xs py-2 px-4 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-950/30 transition cursor-pointer">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Keluar dari akun</span>
                    </button>
                </form>

                <!-- Live Real-Time Server Clock Badge -->
                <div x-data="{ 
                    timeStr: '{{ now()->timezone('Asia/Jakarta')->format('H:i:s') }} WIB (GMT+7)',
                    init() {
                        let serverDate = new Date({{ now()->timezone('Asia/Jakarta')->timestamp * 1000 }});
                        setInterval(() => {
                            serverDate.setSeconds(serverDate.getSeconds() + 1);
                            let hours = String(serverDate.getHours()).padStart(2, '0');
                            let minutes = String(serverDate.getMinutes()).padStart(2, '0');
                            let seconds = String(serverDate.getSeconds()).padStart(2, '0');
                            this.timeStr = `${hours}.${minutes}.${seconds} WIB (GMT+7)`;
                        }, 1000);
                    }
                }" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 text-xs font-medium border border-slate-200/80 dark:border-slate-700 shadow-2xs">
                    <i class="fa-regular fa-clock text-slate-400"></i>
                    <span>Waktu Server <strong x-text="timeStr"></strong></span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
