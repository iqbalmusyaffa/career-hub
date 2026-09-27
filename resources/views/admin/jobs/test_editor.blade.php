<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.jobs.index') }}" class="w-9 h-9 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-center transition shadow-xs text-xs">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Kelola Tes Seleksi:</span>
                        <span class="text-blue-600 dark:text-blue-400">{{ $job->title }}</span>
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Konfigurasi asesmen online, passing grade, durasi pengerjaan, dan bank soal.</p>
                </div>
            </div>
            <a href="{{ route('admin.jobs.index') }}" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium text-xs rounded-xl transition border border-slate-200 dark:border-slate-700">
                Kembali ke Lowongan
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50/60 dark:bg-slate-950 min-h-screen" x-data="testEditor()">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-xl flex items-center gap-3 text-xs font-medium shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-xl flex items-center gap-3 text-xs font-medium shadow-xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Live Test Analytics Widget (Dark-Mode Optimized) -->
            @if(isset($analytics))
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h4 class="font-black text-xs uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-blue-600 dark:text-blue-400"></i> Statistik & Analisis Hasil Ujian Pelamar
                        </h4>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.jobs.test.send-reminders', $job->id) }}" onsubmit="return confirm('Kirim email & notifikasi pengingat ujian kepada seluruh kandidat dalam status tes yang belum menyelesaikan ujian?');">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/60 dark:hover:bg-amber-900/80 text-amber-900 dark:text-amber-200 border border-amber-300 dark:border-amber-800 rounded-xl font-bold text-3xs transition flex items-center gap-1.5 shadow-2xs">
                                    <i class="fa-solid fa-bell text-amber-600 dark:text-amber-400"></i> Kirim Pengingat Ujian ke Pelamar
                                </button>
                            </form>
                            <span class="text-3xs font-bold text-slate-400 dark:text-slate-500">&bull; Realtime</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-xs">
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-800/80 rounded-xl border border-slate-200/80 dark:border-slate-700">
                            <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Total Peserta</span>
                            <span class="text-lg font-black text-slate-900 dark:text-white mt-0.5 block">{{ $analytics['total_participants'] }} <span class="text-xs font-normal text-slate-500 dark:text-slate-400">Kandidat</span></span>
                        </div>
                        <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/40 rounded-xl border border-emerald-200/80 dark:border-emerald-800/60">
                            <span class="text-3xs font-extrabold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 block">Lulus KKM</span>
                            <span class="text-lg font-black text-emerald-800 dark:text-emerald-300 mt-0.5 block">{{ $analytics['passed_count'] }} <span class="text-xs font-normal text-emerald-600 dark:text-emerald-400">Orang</span></span>
                        </div>
                        <div class="p-3.5 bg-rose-50 dark:bg-rose-950/40 rounded-xl border border-rose-200/80 dark:border-rose-800/60">
                            <span class="text-3xs font-extrabold uppercase tracking-wider text-rose-700 dark:text-rose-400 block">Belum Lulus</span>
                            <span class="text-lg font-black text-rose-800 dark:text-rose-300 mt-0.5 block">{{ $analytics['failed_count'] }} <span class="text-xs font-normal text-rose-600 dark:text-rose-400">Orang</span></span>
                        </div>
                        <div class="p-3.5 bg-blue-50 dark:bg-blue-950/40 rounded-xl border border-blue-200/80 dark:border-blue-800/60">
                            <span class="text-3xs font-extrabold uppercase tracking-wider text-blue-700 dark:text-blue-400 block">Rata-rata Skor</span>
                            <span class="text-lg font-black text-blue-800 dark:text-blue-300 mt-0.5 block">{{ $analytics['avg_score'] }}%</span>
                        </div>
                        <div class="p-3.5 bg-indigo-50 dark:bg-indigo-950/40 rounded-xl border border-indigo-200/80 dark:border-indigo-800/60 col-span-2 sm:col-span-1">
                            <span class="text-3xs font-extrabold uppercase tracking-wider text-indigo-700 dark:text-indigo-400 block">Tingkat Kelulusan</span>
                            <span class="text-lg font-black text-indigo-800 dark:text-indigo-300 mt-0.5 block">{{ $analytics['pass_rate'] }}%</span>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.jobs.test.update', $job->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Test Configuration Card -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-4">
                        <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-blue-600 dark:text-blue-400"></i> Pengaturan Asesmen / Tes
                        </h3>
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700 dark:text-slate-300">
                            <input type="checkbox" name="is_active" value="1" {{ $test->is_active ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500 w-4 h-4 bg-white dark:bg-slate-800">
                            <span>Status Tes Aktif</span>
                        </label>
                    </div>

                    <!-- Mode Selection Radio -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 space-y-3">
                        <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">Metode Pelaksanaan Tes</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <label class="p-3.5 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex items-start gap-3 cursor-pointer hover:border-blue-500 transition shadow-2xs">
                                <input type="radio" name="test_mode" value="internal" x-model="testMode" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="font-bold text-slate-900 dark:text-white block">Sistem Ujian Internal</span>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Soal pilihan ganda langsung dikerjakan pelamar di sistem dan dinilai otomatis.</p>
                                </div>
                            </label>

                            <label class="p-3.5 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex items-start gap-3 cursor-pointer hover:border-blue-500 transition shadow-2xs">
                                <input type="radio" name="test_mode" value="external" x-model="testMode" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="font-bold text-slate-900 dark:text-white block">Tautan Eksternal / PDF</span>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Google Form, HackerRank, Typeform, atau berkas soal format PDF.</p>
                                </div>
                            </label>
                        </div>

                        <!-- External Link Field -->
                        <div x-show="testMode === 'external'" class="pt-2">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tautan URL Asesmen / Psikotes Eksternal</label>
                            <input type="url" name="external_url" value="{{ old('external_url', $test->external_url) }}" placeholder="https://forms.google.com/... atau https://hackerrank.com/..." class="w-full border-slate-300 dark:border-slate-700 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Judul Tes / Asesmen</label>
                            <input type="text" name="title" value="{{ old('title', $test->title) }}" required class="w-full border-slate-300 dark:border-slate-700 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Sesi Ujian / Gelombang (Opsional)</label>
                            <input type="text" name="session_name" value="{{ old('session_name', $test->session_name) }}" placeholder="Contoh: Sesi 1 (Pagi) / Gelombang 1" class="w-full border-slate-300 dark:border-slate-700 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori Tes Seleksi</label>
                            <select name="category" class="w-full border-slate-300 dark:border-slate-700 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-800">
                                <option value="psikotes" {{ old('category', $test->category) == 'psikotes' ? 'selected' : '' }}>Tes Psikotes & Penalaran Logika</option>
                                <option value="technical" {{ old('category', $test->category) == 'technical' ? 'selected' : '' }}>Tes Kemampuan Teknis & Hard Skill</option>
                                <option value="english" {{ old('category', $test->category) == 'english' ? 'selected' : '' }}>Tes Bahasa Inggris / Proficiency</option>
                                <option value="personality" {{ old('category', $test->category) == 'personality' ? 'selected' : '' }}>Tes Kepribadian (DISC / Karakter)</option>
                                <option value="case_study" {{ old('category', $test->category) == 'case_study' ? 'selected' : '' }}>Tes Studi Kasus & Take-Home Assignment</option>
                                <option value="pauli" {{ old('category', $test->category) == 'pauli' ? 'selected' : '' }}>Tes Ketelitian & Kecepatan Kerja</option>
                                <option value="general" {{ old('category', $test->category) == 'general' ? 'selected' : '' }}>Tes Pengetahuan Umum & Etika</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Durasi Pengerjaan (Menit)</label>
                            <div class="relative">
                                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $test->duration_minutes) }}" min="1" max="180" required class="w-full border-slate-300 dark:border-slate-700 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium pr-14 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">Menit</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nilai Kelulusan Minimum (Passing Grade)</label>
                            <div class="relative">
                                <input type="number" name="passing_score" value="{{ old('passing_score', $test->passing_score) }}" min="10" max="100" required class="w-full border-slate-300 dark:border-slate-700 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium pr-12 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">%</span>
                            </div>
                        </div>

                        <!-- Schedule Window: Starts At & Deadline At -->
                        <div class="md:col-span-2 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 space-y-3">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-calendar-check text-blue-600 dark:text-blue-400"></i>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Jadwal & Batas Waktu Pengerjaan Ujian (Opsional)</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        Waktu Mulai Ujian (Start Time)
                                    </label>
                                    <input type="datetime-local" name="starts_at" 
                                           value="{{ old('starts_at', $test->starts_at ? $test->starts_at->format('Y-m-d\TH:i') : '') }}" 
                                           class="w-full border-slate-300 dark:border-slate-700 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                    <span class="text-3xs text-slate-500 dark:text-slate-400 mt-1 block">Kandidat tidak dapat mulai mengerjakan sebelum tanggal/jam ini. (Kosongkan jika bisa langsung).</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        Batas Akhir / Deadline (End Time)
                                    </label>
                                    <input type="datetime-local" name="deadline_at" 
                                           value="{{ old('deadline_at', $test->deadline_at ? $test->deadline_at->format('Y-m-d\TH:i') : '') }}" 
                                           class="w-full border-slate-300 dark:border-slate-700 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                    <span class="text-3xs text-slate-500 dark:text-slate-400 mt-1 block">Akses ujian otomatis ditutup setelah tanggal/jam ini. (Kosongkan jika tanpa deadline).</span>
                                </div>
                            </div>

                            <div class="mt-2 pt-3 border-t border-slate-200/80 dark:border-slate-700 flex items-start sm:items-center justify-between gap-3 flex-col sm:flex-row bg-blue-50/60 dark:bg-blue-950/30 p-3 rounded-lg">
                                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-blue-900 dark:text-blue-200 select-none">
                                    <input type="checkbox" name="notify_candidates" value="1" checked class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                                    <span>Otomatis kirim/perbarui kode token & rangkuman jadwal ke email seluruh kandidat</span>
                                </label>
                                <span class="text-3xs font-semibold px-2.5 py-1 rounded-full bg-blue-200/70 dark:bg-blue-900 text-blue-800 dark:text-blue-200 shrink-0">
                                    <i class="fa-solid fa-users text-3xs mr-1"></i> {{ $candidatesInTestCount ?? 0 }} Kandidat di Tahap Ujian
                                </span>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Instruksi & Petunjuk Pengerjaan untuk Kandidat</label>
                            <textarea name="description" rows="3" class="w-full border-slate-300 dark:border-slate-700 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium bg-white dark:bg-slate-800 text-slate-900 dark:text-white" placeholder="Tuliskan petunjuk pengerjaan bagi kandidat...">{{ old('description', $test->description) }}</textarea>
                        </div>

                        <div class="md:col-span-2 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700 space-y-2">
                            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                <i class="fa-solid fa-file-pdf text-rose-600"></i> Lampiran File PDF Soal / Studi Kasus (Opsional)
                            </label>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Unggah berkas PDF berisi rincian studi kasus, take-home challenge, atau petunjuk teknis (Maks: 10MB).</p>
                            <input type="file" name="pdf_file" accept=".pdf" class="w-full text-xs text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-700 rounded-lg p-2 bg-white dark:bg-slate-800">
                            @if(isset($test) && $test->file_path)
                                <div class="pt-1 flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-400">
                                    <i class="fa-solid fa-circle-check"></i> File Terlampir: 
                                    <a href="{{ Storage::url($test->file_path) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                                        <i class="fa-solid fa-download"></i> Unduh File PDF
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Questions Bank Card (Hidden when testMode is external) -->
                <div x-show="testMode === 'internal'" class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 dark:border-slate-800 pb-4">
                        <div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-list-check text-blue-600 dark:text-blue-400"></i> Bank Soal Pilihan Ganda
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total Soal Terdaftar: <span class="font-bold text-blue-600 dark:text-blue-400" x-text="questions.length"></span> Butir</p>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="{{ route('admin.jobs.test.export', [$job->id, 'format' => 'xlsx']) }}" class="bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:hover:bg-emerald-900/80 text-emerald-800 dark:text-emerald-300 font-semibold py-2 px-3 rounded-lg text-xs border border-emerald-200 dark:border-emerald-800 transition flex items-center gap-1.5 shadow-2xs" title="Unduh Bank Soal Format Excel">
                                <i class="fa-solid fa-file-excel text-emerald-600 dark:text-emerald-400 text-xs"></i> Unduh Excel (.xlsx)
                            </a>

                            <a href="{{ route('admin.jobs.test.export', [$job->id, 'format' => 'csv']) }}" class="bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold py-2 px-3 rounded-lg text-xs border border-slate-200 dark:border-slate-700 transition flex items-center gap-1.5 shadow-2xs" title="Unduh Bank Soal Format CSV">
                                <i class="fa-solid fa-file-csv text-slate-500 dark:text-slate-400 text-xs"></i> Unduh CSV
                            </a>

                            <button type="button" @click="showImportModal = true" class="bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/60 dark:hover:bg-indigo-900/80 text-indigo-800 dark:text-indigo-300 font-semibold py-2 px-3 rounded-lg text-xs border border-indigo-200 dark:border-indigo-800 transition flex items-center gap-1.5 shadow-2xs">
                                <i class="fa-solid fa-file-import text-indigo-600 dark:text-indigo-400 text-xs"></i> Impor Soal (Excel/CSV)
                            </button>

                            <button type="button" @click="addQuestion" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3.5 rounded-lg text-xs transition shadow-2xs flex items-center gap-1.5">
                                <i class="fa-solid fa-plus text-xs"></i> Tambah Soal
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(q, index) in questions" :key="index">
                            <div class="border rounded-2xl bg-white dark:bg-slate-900 shadow-2xs transition-all overflow-hidden"
                                 :class="q.is_collapsed ? 'border-slate-200 dark:border-slate-800' : 'border-slate-300 dark:border-slate-700 hover:border-blue-300'">
                                
                                <!-- Header Bar Soal -->
                                <div class="px-5 py-3.5 bg-slate-50/90 dark:bg-slate-800/80 border-b border-slate-200/80 dark:border-slate-700 flex items-center justify-between gap-3 select-none">
                                    <div class="flex items-center gap-2.5 flex-wrap">
                                        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white text-xs flex items-center justify-center font-black shadow-2xs" x-text="index + 1"></span>
                                        <span class="font-bold text-slate-800 dark:text-slate-200 text-xs">Soal Nomor <span x-text="index + 1"></span></span>
                                        
                                        <!-- Badge Kunci Jawaban -->
                                        <span class="px-2.5 py-0.5 rounded-full text-3xs font-extrabold flex items-center gap-1 border"
                                              :class="q.correct_option ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 border-slate-200 dark:border-slate-700'">
                                            <i class="fa-solid fa-key text-3xs text-emerald-600 dark:text-emerald-400"></i>
                                            <span>Kunci: Opsi <strong class="uppercase" x-text="q.correct_option || 'A'"></strong></span>
                                        </span>

                                        <!-- Badge Total Opsi -->
                                        <span class="px-2 py-0.5 rounded-md text-3xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700"
                                              x-text="(q.show_option_e || (q.option_e && q.option_e.trim() !== '')) ? '5 Opsi (A-E)' : '4 Opsi (A-D)'">
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-1.5">
                                        <button type="button" @click="q.is_collapsed = !q.is_collapsed" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-200/70 dark:hover:bg-slate-700 p-1.5 rounded-lg text-xs transition" :title="q.is_collapsed ? 'Buka Soal' : 'Ciutkan Soal'">
                                            <i class="fa-solid" :class="q.is_collapsed ? 'fa-chevron-down' : 'fa-chevron-up'"></i>
                                        </button>
                                        <button type="button" @click="removeQuestion(index)" class="text-rose-600 hover:text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 dark:hover:bg-rose-900/80 px-2.5 py-1 text-xs font-semibold rounded-lg transition flex items-center gap-1">
                                            <i class="fa-solid fa-trash text-xs"></i> Hapus
                                        </button>
                                    </div>
                                </div>

                                <!-- Body Content (Collapsible) -->
                                <div x-show="!q.is_collapsed" class="p-5 space-y-4">
                                    <!-- Hidden input for correct_option -->
                                    <input type="hidden" :name="`questions[${index}][correct_option]`" :value="q.correct_option">

                                    <!-- Pertanyaan -->
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 flex items-center justify-between">
                                            <span>Teks Pertanyaan / Soal <span class="text-rose-500">*</span></span>
                                            <span class="text-3xs text-slate-400 dark:text-slate-500 font-normal">Mendukung teks deskripsi, soal kasus, atau deret logika</span>
                                        </label>
                                        <textarea :name="`questions[${index}][question_text]`" x-model="q.question_text" rows="2" placeholder="Tuliskan butir pertanyaan ujian di sini..." required class="w-full border-slate-300 dark:border-slate-700 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xs font-medium bg-white dark:bg-slate-800 text-slate-900 dark:text-white"></textarea>
                                    </div>

                                    <!-- Pilihan Jawaban Section -->
                                    <div class="space-y-2.5">
                                        <div class="flex items-center justify-between">
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                                <span>Pilihan Jawaban</span>
                                                <span class="text-3xs font-normal text-slate-500 dark:text-slate-400">(Klik lingkaran atau tombol <strong class="text-emerald-600 dark:text-emerald-400">Set Kunci</strong> untuk memilih jawaban yang benar)</span>
                                            </label>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <!-- OPSI A -->
                                            <div class="relative p-3 rounded-xl border transition-all"
                                                 :class="q.correct_option === 'a' ? 'border-emerald-500 dark:border-emerald-500 bg-emerald-50/60 dark:bg-emerald-950/40 ring-2 ring-emerald-500/20 shadow-xs' : 'border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 dark:hover:border-slate-600'">
                                                <div class="flex items-center justify-between mb-1.5">
                                                    <label class="cursor-pointer flex items-center gap-2" @click="q.correct_option = 'a'">
                                                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-black transition-all"
                                                              :class="q.correct_option === 'a' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300'">
                                                            A
                                                        </span>
                                                        <span class="text-xs font-bold" :class="q.correct_option === 'a' ? 'text-emerald-800 dark:text-emerald-300' : 'text-slate-700 dark:text-slate-300'">Pilihan A</span>
                                                    </label>
                                                    <button type="button" @click="q.correct_option = 'a'" class="px-2 py-0.5 rounded-full text-3xs font-black transition flex items-center gap-1"
                                                            :class="q.correct_option === 'a' ? 'bg-emerald-600 text-white' : 'bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-emerald-100 hover:text-emerald-800'">
                                                        <i class="fa-solid fa-check text-3xs"></i>
                                                        <span x-text="q.correct_option === 'a' ? 'KUNCI BENAR' : 'Set Kunci'"></span>
                                                    </button>
                                                </div>
                                                <input type="text" :name="`questions[${index}][option_a]`" x-model="q.option_a" required placeholder="Ketik isi pilihan A..." class="w-full border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                            </div>

                                            <!-- OPSI B -->
                                            <div class="relative p-3 rounded-xl border transition-all"
                                                 :class="q.correct_option === 'b' ? 'border-emerald-500 dark:border-emerald-500 bg-emerald-50/60 dark:bg-emerald-950/40 ring-2 ring-emerald-500/20 shadow-xs' : 'border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 dark:hover:border-slate-600'">
                                                <div class="flex items-center justify-between mb-1.5">
                                                    <label class="cursor-pointer flex items-center gap-2" @click="q.correct_option = 'b'">
                                                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-black transition-all"
                                                              :class="q.correct_option === 'b' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300'">
                                                            B
                                                        </span>
                                                        <span class="text-xs font-bold" :class="q.correct_option === 'b' ? 'text-emerald-800 dark:text-emerald-300' : 'text-slate-700 dark:text-slate-300'">Pilihan B</span>
                                                    </label>
                                                    <button type="button" @click="q.correct_option = 'b'" class="px-2 py-0.5 rounded-full text-3xs font-black transition flex items-center gap-1"
                                                            :class="q.correct_option === 'b' ? 'bg-emerald-600 text-white' : 'bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-emerald-100 hover:text-emerald-800'">
                                                        <i class="fa-solid fa-check text-3xs"></i>
                                                        <span x-text="q.correct_option === 'b' ? 'KUNCI BENAR' : 'Set Kunci'"></span>
                                                    </button>
                                                </div>
                                                <input type="text" :name="`questions[${index}][option_b]`" x-model="q.option_b" required placeholder="Ketik isi pilihan B..." class="w-full border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                            </div>

                                            <!-- OPSI C -->
                                            <div class="relative p-3 rounded-xl border transition-all"
                                                 :class="q.correct_option === 'c' ? 'border-emerald-500 dark:border-emerald-500 bg-emerald-50/60 dark:bg-emerald-950/40 ring-2 ring-emerald-500/20 shadow-xs' : 'border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 dark:hover:border-slate-600'">
                                                <div class="flex items-center justify-between mb-1.5">
                                                    <label class="cursor-pointer flex items-center gap-2" @click="q.correct_option = 'c'">
                                                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-black transition-all"
                                                              :class="q.correct_option === 'c' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300'">
                                                            C
                                                        </span>
                                                        <span class="text-xs font-bold" :class="q.correct_option === 'c' ? 'text-emerald-800 dark:text-emerald-300' : 'text-slate-700 dark:text-slate-300'">Pilihan C</span>
                                                    </label>
                                                    <button type="button" @click="q.correct_option = 'c'" class="px-2 py-0.5 rounded-full text-3xs font-black transition flex items-center gap-1"
                                                            :class="q.correct_option === 'c' ? 'bg-emerald-600 text-white' : 'bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-emerald-100 hover:text-emerald-800'">
                                                        <i class="fa-solid fa-check text-3xs"></i>
                                                        <span x-text="q.correct_option === 'c' ? 'KUNCI BENAR' : 'Set Kunci'"></span>
                                                    </button>
                                                </div>
                                                <input type="text" :name="`questions[${index}][option_c]`" x-model="q.option_c" required placeholder="Ketik isi pilihan C..." class="w-full border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                            </div>

                                            <!-- OPSI D -->
                                            <div class="relative p-3 rounded-xl border transition-all"
                                                 :class="q.correct_option === 'd' ? 'border-emerald-500 dark:border-emerald-500 bg-emerald-50/60 dark:bg-emerald-950/40 ring-2 ring-emerald-500/20 shadow-xs' : 'border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 dark:hover:border-slate-600'">
                                                <div class="flex items-center justify-between mb-1.5">
                                                    <label class="cursor-pointer flex items-center gap-2" @click="q.correct_option = 'd'">
                                                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-black transition-all"
                                                              :class="q.correct_option === 'd' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300'">
                                                            D
                                                        </span>
                                                        <span class="text-xs font-bold" :class="q.correct_option === 'd' ? 'text-emerald-800 dark:text-emerald-300' : 'text-slate-700 dark:text-slate-300'">Pilihan D</span>
                                                    </label>
                                                    <button type="button" @click="q.correct_option = 'd'" class="px-2 py-0.5 rounded-full text-3xs font-black transition flex items-center gap-1"
                                                            :class="q.correct_option === 'd' ? 'bg-emerald-600 text-white' : 'bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-emerald-100 hover:text-emerald-800'">
                                                        <i class="fa-solid fa-check text-3xs"></i>
                                                        <span x-text="q.correct_option === 'd' ? 'KUNCI BENAR' : 'Set Kunci'"></span>
                                                    </button>
                                                </div>
                                                <input type="text" :name="`questions[${index}][option_d]`" x-model="q.option_d" required placeholder="Ketik isi pilihan D..." class="w-full border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                            </div>

                                            <!-- OPSI E (Jika diaktifkan) -->
                                            <div x-show="q.show_option_e || (q.option_e && q.option_e.trim() !== '')" class="md:col-span-2 relative p-3 rounded-xl border transition-all"
                                                 :class="q.correct_option === 'e' ? 'border-emerald-500 dark:border-emerald-500 bg-emerald-50/60 dark:bg-emerald-950/40 ring-2 ring-emerald-500/20 shadow-xs' : 'border-indigo-200 dark:border-indigo-800 bg-indigo-50/30 dark:bg-indigo-950/30 hover:border-indigo-300'">
                                                <div class="flex items-center justify-between mb-1.5">
                                                    <label class="cursor-pointer flex items-center gap-2" @click="q.correct_option = 'e'">
                                                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-black transition-all"
                                                              :class="q.correct_option === 'e' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-indigo-200 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-300'">
                                                            E
                                                        </span>
                                                        <span class="text-xs font-bold" :class="q.correct_option === 'e' ? 'text-emerald-800 dark:text-emerald-300' : 'text-indigo-900 dark:text-indigo-300'">Pilihan E (Opsional)</span>
                                                    </label>
                                                    <div class="flex items-center gap-2">
                                                        <button type="button" @click="q.correct_option = 'e'" class="px-2 py-0.5 rounded-full text-3xs font-black transition flex items-center gap-1"
                                                                :class="q.correct_option === 'e' ? 'bg-emerald-600 text-white' : 'bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-emerald-100 hover:text-emerald-800'">
                                                            <i class="fa-solid fa-check text-3xs"></i>
                                                            <span x-text="q.correct_option === 'e' ? 'KUNCI BENAR' : 'Set Kunci'"></span>
                                                        </button>
                                                        <button type="button" @click="removeOptionE(q)" class="text-rose-600 hover:text-rose-700 dark:text-rose-400 text-3xs font-bold hover:underline">
                                                            <i class="fa-solid fa-xmark"></i> Hapus Opsi E
                                                        </button>
                                                    </div>
                                                </div>
                                                <input type="text" :name="`questions[${index}][option_e]`" x-model="q.option_e" placeholder="Ketik isi pilihan E (opsional)..." class="w-full border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                            </div>
                                        </div>

                                        <!-- Tombol Tambah Opsi E jika belum aktif -->
                                        <div x-show="!q.show_option_e && (!q.option_e || q.option_e.trim() === '')" class="pt-1">
                                            <button type="button" @click="q.show_option_e = true" class="w-full py-2 border border-dashed border-indigo-300 dark:border-indigo-700 hover:border-indigo-500 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 font-semibold text-xs rounded-xl transition flex items-center justify-center gap-1.5">
                                                <i class="fa-solid fa-plus text-xs"></i>
                                                <span>Tambah Pilihan E (Opsional untuk Format 5 Pilihan A-E)</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="questions.length === 0" class="p-8 text-center bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-dashed border-slate-200 dark:border-slate-700">
                            <p class="text-slate-500 dark:text-slate-400 text-xs">Belum ada soal dibuat. Klik tombol <strong>+ Tambah Soal</strong> untuk membuat butir tes.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('admin.jobs.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium text-xs rounded-xl transition border border-slate-200 dark:border-slate-700">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center gap-2 border border-blue-600">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Pengaturan & Soal Tes</span>
                    </button>
                </div>
            </form>

            <!-- Import Excel & CSV Modal -->
            <div x-show="showImportModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" x-cloak style="display: none;">
                <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-file-import text-indigo-600 dark:text-indigo-400"></i> Impor Soal dari Excel (.xlsx) / CSV
                        </h3>
                        <button type="button" @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.jobs.test.import', $job->id) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pilih File Excel (.xlsx/.xls) atau CSV</label>
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="block w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-950/60 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800">
                        </div>

                        <label class="flex items-center gap-2 p-3 bg-amber-50 dark:bg-amber-950/40 rounded-xl border border-amber-200 dark:border-amber-900 text-xs text-amber-900 dark:text-amber-300 cursor-pointer">
                            <input type="checkbox" name="replace_existing" value="1" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                            <span><strong>Ganti Seluruh Soal Lama</strong> (Hapus bank soal lama dan timpa dengan berkas ini)</span>
                        </label>

                        <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl text-xs text-slate-600 dark:text-slate-400 space-y-1.5 border border-slate-200 dark:border-slate-700">
                            <p class="font-bold text-slate-800 dark:text-slate-200">Format Header Kolom File (6 Kolom):</p>
                            <p class="font-mono text-[11px] text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 p-2 rounded border border-slate-200 dark:border-slate-700">Pertanyaan | Pilihan A | Pilihan B | Pilihan C | Pilihan D | Kunci Jawaban (a/b/c/d)</p>
                            <p class="text-blue-600 dark:text-blue-400 text-[11px]">💡 Unduh template resmi melalui tombol <strong>"Unduh Excel (.xlsx)"</strong> atau <strong>"Unduh CSV"</strong> di atas.</p>
                        </div>

                        <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="showImportModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium rounded-lg text-xs">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-xs shadow-xs transition flex items-center gap-1.5">
                                <i class="fa-solid fa-upload"></i> Unggah & Impor Soal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function testEditor() {
            return {
                showImportModal: false,
                testMode: '{{ old('test_mode', $test->test_mode ?? 'internal') }}',
                questions: ({!! json_encode($test->questions->toArray()) !!} || []).map(q => ({
                    ...q,
                    option_e: q.option_e || '',
                    correct_option: (q.correct_option || 'a').toLowerCase(),
                    show_option_e: Boolean(q.option_e && q.option_e.trim() !== '') || (q.correct_option && q.correct_option.toLowerCase() === 'e'),
                    is_collapsed: false
                })),
                addQuestion() {
                    this.questions.push({
                        question_text: '',
                        option_a: '',
                        option_b: '',
                        option_c: '',
                        option_d: '',
                        option_e: '',
                        correct_option: 'a',
                        show_option_e: false,
                        is_collapsed: false
                    });
                },
                removeQuestion(i) {
                    if (this.questions[i].question_text && !confirm('Hapus butir soal nomor ' + (i + 1) + '?')) {
                        return;
                    }
                    this.questions.splice(i, 1);
                },
                removeOptionE(q) {
                    q.option_e = '';
                    if (q.correct_option === 'e') {
                        q.correct_option = 'a';
                    }
                    q.show_option_e = false;
                }
            }
        }
    </script>
</x-app-layout>
