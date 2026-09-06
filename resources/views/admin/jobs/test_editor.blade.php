<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.jobs.index') }}" class="w-9 h-9 bg-white hover:bg-slate-100 text-slate-600 rounded-xl border border-slate-200 flex items-center justify-center transition shadow-xs text-xs">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-slate-900 tracking-tight flex items-center gap-2">
                        <span>Kelola Tes Seleksi:</span>
                        <span class="text-blue-600">{{ $job->title }}</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Konfigurasi asesmen online, passing grade, durasi pengerjaan, dan bank soal.</p>
                </div>
            </div>
            <a href="{{ route('admin.jobs.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs rounded-xl transition border border-slate-200">
                Kembali ke Lowongan
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50/60 min-h-screen" x-data="testEditor()">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-xs font-medium shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.jobs.test.update', $job->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Test Configuration Card -->
                <div class="bg-white rounded-xl shadow-xs border border-slate-200/80 p-6 sm:p-8 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <h3 class="font-bold text-base text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-blue-600"></i> Pengaturan Asesmen / Tes
                        </h3>
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                            <input type="checkbox" name="is_active" value="1" {{ $test->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                            <span>Status Tes Aktif</span>
                        </label>
                    </div>

                    <!-- Mode Selection Radio -->
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                        <label class="block text-xs font-bold text-slate-800">Metode Pelaksanaan Tes</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <label class="p-3.5 bg-white rounded-xl border border-slate-200 flex items-start gap-3 cursor-pointer hover:border-blue-500 transition shadow-2xs">
                                <input type="radio" name="test_mode" value="internal" x-model="testMode" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="font-bold text-slate-900 block">Sistem Ujian Internal</span>
                                    <p class="text-xs text-slate-500 mt-0.5">Soal pilihan ganda langsung dikerjakan pelamar di sistem dan dinilai otomatis.</p>
                                </div>
                            </label>

                            <label class="p-3.5 bg-white rounded-xl border border-slate-200 flex items-start gap-3 cursor-pointer hover:border-blue-500 transition shadow-2xs">
                                <input type="radio" name="test_mode" value="external" x-model="testMode" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="font-bold text-slate-900 block">Tautan Eksternal / PDF</span>
                                    <p class="text-xs text-slate-500 mt-0.5">Google Form, HackerRank, Typeform, atau berkas soal format PDF.</p>
                                </div>
                            </label>
                        </div>

                        <!-- External Link Field -->
                        <div x-show="testMode === 'external'" class="pt-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tautan URL Asesmen / Psikotes Eksternal</label>
                            <input type="url" name="external_url" value="{{ old('external_url', $test->external_url) }}" placeholder="https://forms.google.com/... atau https://hackerrank.com/..." class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Tes / Asesmen</label>
                            <input type="text" name="title" value="{{ old('title', $test->title) }}" required class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Tes Seleksi</label>
                            <select name="category" class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium text-slate-800 bg-white">
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
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Durasi Pengerjaan (Menit)</label>
                            <div class="relative">
                                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $test->duration_minutes) }}" min="1" max="180" required class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium pr-14">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">Menit</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nilai Kelulusan Minimum (Passing Grade)</label>
                            <div class="relative">
                                <input type="number" name="passing_score" value="{{ old('passing_score', $test->passing_score) }}" min="10" max="100" required class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium pr-12">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">%</span>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Instruksi & Petunjuk Pengerjaan untuk Kandidat</label>
                            <textarea name="description" rows="3" class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium" placeholder="Tuliskan petunjuk pengerjaan bagi kandidat...">{{ old('description', $test->description) }}</textarea>
                        </div>

                        <div class="md:col-span-2 bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2">
                            <label class="block text-xs font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-file-pdf text-rose-600"></i> Lampiran File PDF Soal / Studi Kasus (Opsional)
                            </label>
                            <p class="text-xs text-slate-500">Unggah berkas PDF berisi rincian studi kasus, take-home challenge, atau petunjuk teknis (Maks: 10MB).</p>
                            <input type="file" name="pdf_file" accept=".pdf" class="w-full text-xs text-slate-600 border border-slate-300 rounded-lg p-2 bg-white">
                            @if(isset($test) && $test->file_path)
                                <div class="pt-1 flex items-center gap-2 text-xs font-semibold text-emerald-700">
                                    <i class="fa-solid fa-circle-check"></i> File Terlampir: 
                                    <a href="{{ Storage::url($test->file_path) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                                        <i class="fa-solid fa-download"></i> Unduh File PDF
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Questions Bank Card (Hidden when testMode is external) -->
                <div x-show="testMode === 'internal'" class="bg-white rounded-xl shadow-xs border border-slate-200/80 p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-bold text-base text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-list-check text-blue-600"></i> Bank Soal Pilihan Ganda
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Total Soal Terdaftar: <span class="font-bold text-blue-600" x-text="questions.length"></span> Butir</p>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="{{ route('admin.jobs.test.export', $job->id) }}" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold py-2 px-3 rounded-lg text-xs border border-emerald-200 transition flex items-center gap-1.5 shadow-2xs">
                                <i class="fa-solid fa-file-csv text-emerald-600 text-xs"></i> Unduh Format CSV
                            </a>

                            <button type="button" @click="showImportModal = true" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-800 font-semibold py-2 px-3 rounded-lg text-xs border border-indigo-200 transition flex items-center gap-1.5 shadow-2xs">
                                <i class="fa-solid fa-file-import text-indigo-600 text-xs"></i> Impor Soal CSV
                            </button>

                            <button type="button" @click="addQuestion" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3.5 rounded-lg text-xs transition shadow-2xs flex items-center gap-1.5">
                                <i class="fa-solid fa-plus text-xs"></i> Tambah Soal
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(q, index) in questions" :key="index">
                            <div class="p-5 border border-slate-200 rounded-xl bg-slate-50/50 hover:bg-white transition-all space-y-3 shadow-2xs">
                                <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                                    <h4 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] flex items-center justify-center font-bold" x-text="index + 1"></span>
                                        <span>Soal Nomor <span x-text="index + 1"></span></span>
                                    </h4>
                                    <button type="button" @click="removeQuestion(index)" class="text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 px-2.5 py-1 text-xs font-semibold rounded-lg transition flex items-center gap-1">
                                        <i class="fa-solid fa-trash text-xs"></i> Hapus Soal
                                    </button>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Teks Pertanyaan</label>
                                    <textarea :name="`questions[${index}][question_text]`" x-model="q.question_text" rows="2" placeholder="Tuliskan butir pertanyaan..." required class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs font-medium"></textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-white p-3.5 rounded-xl border border-slate-200">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pilihan A</label>
                                        <input type="text" :name="`questions[${index}][option_a]`" x-model="q.option_a" required placeholder="Opsi jawaban A..." class="w-full border-slate-300 rounded-lg text-xs font-medium">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pilihan B</label>
                                        <input type="text" :name="`questions[${index}][option_b]`" x-model="q.option_b" required placeholder="Opsi jawaban B..." class="w-full border-slate-300 rounded-lg text-xs font-medium">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pilihan C</label>
                                        <input type="text" :name="`questions[${index}][option_c]`" x-model="q.option_c" required placeholder="Opsi jawaban C..." class="w-full border-slate-300 rounded-lg text-xs font-medium">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pilihan D</label>
                                        <input type="text" :name="`questions[${index}][option_d]`" x-model="q.option_d" required placeholder="Opsi jawaban D..." class="w-full border-slate-300 rounded-lg text-xs font-medium">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-emerald-800 mb-1 flex items-center gap-1.5">
                                        <i class="fa-solid fa-key text-emerald-600"></i> Kunci Jawaban Benar
                                    </label>
                                    <select :name="`questions[${index}][correct_option]`" x-model="q.correct_option" required class="w-full sm:w-64 border-emerald-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-xs font-bold bg-emerald-50/50">
                                        <option value="a">Opsi A</option>
                                        <option value="b">Opsi B</option>
                                        <option value="c">Opsi C</option>
                                        <option value="d">Opsi D</option>
                                    </select>
                                </div>
                            </div>
                        </template>

                        <div x-show="questions.length === 0" class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <p class="text-slate-500 text-xs">Belum ada soal dibuat. Klik tombol <strong>+ Tambah Soal</strong> untuk membuat butir tes.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.jobs.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs rounded-xl transition border border-slate-200">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center gap-2 border border-blue-600">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Pengaturan & Soal Tes</span>
                    </button>
                </div>
            </form>

            <!-- Import CSV Modal -->
            <div x-show="showImportModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4" x-cloak style="display: none;">
                <div class="bg-white rounded-xl max-w-lg w-full p-6 shadow-xl border border-slate-200 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-file-csv text-indigo-600"></i> Impor Soal dari Berkas CSV
                        </h3>
                        <button type="button" @click="showImportModal = false" class="text-slate-400 hover:text-slate-600">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.jobs.test.import', $job->id) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih File CSV Soal</label>
                            <input type="file" name="csv_file" accept=".csv" required class="block w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-lg">
                        </div>

                        <div class="p-3 bg-slate-50 rounded-lg text-xs text-slate-600 space-y-1">
                            <p class="font-bold text-slate-800">Format Kolom File CSV (6 Kolom):</p>
                            <p class="font-mono text-[11px] text-slate-700">Pertanyaan, Pilihan A, Pilihan B, Pilihan C, Pilihan D, Kunci (a/b/c/d)</p>
                            <p class="text-blue-600 text-[11px]">Anda dapat mengunduh format contoh dengan tombol <strong>"Unduh Format CSV"</strong>.</p>
                        </div>

                        <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                            <button type="button" @click="showImportModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-lg text-xs">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-xs shadow-xs transition flex items-center gap-1.5">
                                <i class="fa-solid fa-upload"></i> Unggah & Impor
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
                questions: {!! json_encode($test->questions->toArray()) !!},
                addQuestion() {
                    this.questions.push({
                        question_text: '',
                        option_a: '',
                        option_b: '',
                        option_c: '',
                        option_d: '',
                        correct_option: 'a'
                    });
                },
                removeQuestion(i) {
                    this.questions.splice(i, 1);
                }
            }
        }
    </script>
</x-app-layout>
