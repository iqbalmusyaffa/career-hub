<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-file-pen text-blue-600"></i> Kelola Tes Online Seleksi
                </h2>
                <p class="text-xs text-gray-500 mt-1">Lowongan: <span class="font-bold text-gray-800">{{ $job->title }}</span> ({{ $job->company_name }})</p>
            </div>
            <a href="{{ route('admin.jobs.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-xl text-sm transition">
                &larr; Kembali ke Lowongan
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen" x-data="testEditor()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.jobs.test.update', $job->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Test Configuration Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                        <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-blue-600"></i> Pengaturan Asesmen / Tes
                        </h3>
                        <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-gray-700">
                            <input type="checkbox" name="is_active" value="1" {{ $test->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                            <span>Status Aktif</span>
                        </label>
                    </div>

                    <!-- Mode Selection Radio -->
                    <div class="p-4 bg-blue-50/60 rounded-2xl border border-blue-100 space-y-3">
                        <label class="block text-sm font-bold text-blue-900">Pilih Metode Pelaksanaan Tes</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <label class="p-3 bg-white rounded-xl border border-blue-200 flex items-center gap-2 cursor-pointer hover:border-blue-500 transition">
                                <input type="radio" name="test_mode" value="internal" x-model="testMode" class="text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="font-bold text-gray-800">1. Sistem Tes Built-in TalentFlow</span>
                                    <p class="text-3xs text-gray-500">Soal pilihan ganda otomatis dinilai oleh sistem.</p>
                                </div>
                            </label>

                            <label class="p-3 bg-white rounded-xl border border-blue-200 flex items-center gap-2 cursor-pointer hover:border-blue-500 transition">
                                <input type="radio" name="test_mode" value="external" x-model="testMode" class="text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="font-bold text-gray-800">2. Tautan Link Eksternal / PDF</span>
                                    <p class="text-3xs text-gray-500">Google Form, HackerRank, Typeform, atau Berkas PDF.</p>
                                </div>
                            </label>
                        </div>

                        <!-- External Link Field -->
                        <div x-show="testMode === 'external'" class="pt-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tautan URL Ujian / Psikotes Eksternal</label>
                            <input type="url" name="external_url" value="{{ old('external_url', $test->external_url) }}" placeholder="https://forms.google.com/... atau https://hackerrank.com/..." class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Judul Tes / Asesmen</label>
                            <input type="text" name="title" value="{{ old('title', $test->title) }}" required class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm font-bold">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Kategori Tes Seleksi</label>
                            <select name="category" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm font-bold text-gray-800">
                                <option value="psikotes" {{ old('category', $test->category) == 'psikotes' ? 'selected' : '' }}>🧠 Tes Psikotes & Penalaran Logika / IQ</option>
                                <option value="technical" {{ old('category', $test->category) == 'technical' ? 'selected' : '' }}>💻 Tes Kemampuan Teknis & Hard Skill</option>
                                <option value="english" {{ old('category', $test->category) == 'english' ? 'selected' : '' }}>🔤 Tes Bahasa Inggris / TOEFL</option>
                                <option value="personality" {{ old('category', $test->category) == 'personality' ? 'selected' : '' }}>🧩 Tes Kepribadian (DISC / MBTI / Big Five)</option>
                                <option value="case_study" {{ old('category', $test->category) == 'case_study' ? 'selected' : '' }}>📊 Tes Studi Kasus & Take-Home Assignment</option>
                                <option value="pauli" {{ old('category', $test->category) == 'pauli' ? 'selected' : '' }}>⏱️ Tes Ketelitian & Kecepatan (Pauli / Kraepelin)</option>
                                <option value="general" {{ old('category', $test->category) == 'general' ? 'selected' : '' }}>🌐 Tes Pengetahuan Umum & Etika Kerja</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Durasi Estimasi Pengerjaan (Menit)</label>
                            <div class="relative">
                                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $test->duration_minutes) }}" min="1" max="180" required class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm pr-16">
                                <span class="absolute right-3 top-2.5 text-xs text-gray-500 font-medium">Menit</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Batas Nilai Kelulusan / Passing Grade (%)</label>
                            <div class="relative">
                                <input type="number" name="passing_score" value="{{ old('passing_score', $test->passing_score) }}" min="10" max="100" required class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm pr-12">
                                <span class="absolute right-3 top-2.5 text-xs text-gray-500 font-medium">% Skor</span>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Instruksi & Petunjuk Pengerjaan untuk Kandidat</label>
                            <textarea name="description" rows="2" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">{{ old('description', $test->description) }}</textarea>
                        </div>

                        <div class="md:col-span-2 bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2">
                            <label class="block text-sm font-bold text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-file-pdf text-rose-600"></i> Upload Lampiran File PDF Soal / Studi Kasus (Opsional)
                            </label>
                            <p class="text-xs text-gray-500">Unggah berkas PDF berisi instruksi lengkap, soal studi kasus, atau spesifikasi teknis project (Maks: 10MB).</p>
                            <input type="file" name="pdf_file" accept=".pdf" class="w-full text-xs text-slate-600 border border-slate-300 rounded-xl p-2 bg-white">
                            @if(isset($test) && $test->file_path)
                                <div class="pt-1 flex items-center gap-2 text-xs font-bold text-emerald-700">
                                    <i class="fa-solid fa-circle-check"></i> Terunggah: 
                                    <a href="{{ Storage::url($test->file_path) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                                        <i class="fa-solid fa-download"></i> Unduh File PDF Soal Terpasang
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Questions Bank Card (Hidden when testMode is external) -->
                <div x-show="testMode === 'internal'" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-list-check text-blue-600"></i> Bank Soal Pilihan Ganda
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Jumlah Soal: <span class="font-bold text-blue-600" x-text="questions.length"></span> Soal</p>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <!-- Export / Download CSV Button -->
                            <a href="{{ route('admin.jobs.test.export', $job->id) }}" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold py-2 px-3 rounded-xl text-xs border border-emerald-200 transition flex items-center gap-1.5 shadow-2xs">
                                <i class="fa-solid fa-file-csv text-emerald-600 text-sm"></i> Unduh / Ekspor CSV
                            </a>

                            <!-- Import CSV Form Button -->
                            <button type="button" @click="showImportModal = true" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-800 font-bold py-2 px-3 rounded-xl text-xs border border-indigo-200 transition flex items-center gap-1.5 shadow-2xs">
                                <i class="fa-solid fa-file-import text-indigo-600 text-sm"></i> Impor Soal CSV
                            </button>

                            <button type="button" @click="addQuestion" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition shadow-2xs">
                                + Tambah Soal
                            </button>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <template x-for="(q, index) in questions" :key="index">
                            <div class="p-6 border border-gray-200 rounded-2xl bg-gray-50/50 hover:bg-white transition-all space-y-4 shadow-2xs">
                                <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                                    <h4 class="font-black text-gray-800 text-sm flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold" x-text="index + 1"></span>
                                        Pertanyaan #<span x-text="index + 1"></span>
                                    </h4>
                                    <button type="button" @click="removeQuestion(index)" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1 text-xs font-bold rounded-lg transition">
                                        <i class="fa-solid fa-trash mr-1"></i> Hapus Soal
                                    </button>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Teks Pertanyaan</label>
                                    <textarea :name="`questions[${index}][question_text]`" x-model="q.question_text" rows="2" placeholder="Tuliskan soal / pertanyaan tes..." required class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded-xl border border-gray-200">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Pilihan A</label>
                                        <input type="text" :name="`questions[${index}][option_a]`" x-model="q.option_a" required placeholder="Jawaban A..." class="w-full border-gray-300 rounded-xl text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Pilihan B</label>
                                        <input type="text" :name="`questions[${index}][option_b]`" x-model="q.option_b" required placeholder="Jawaban B..." class="w-full border-gray-300 rounded-xl text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Pilihan C</label>
                                        <input type="text" :name="`questions[${index}][option_c]`" x-model="q.option_c" required placeholder="Jawaban C..." class="w-full border-gray-300 rounded-xl text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Pilihan D</label>
                                        <input type="text" :name="`questions[${index}][option_d]`" x-model="q.option_d" required placeholder="Jawaban D..." class="w-full border-gray-300 rounded-xl text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-emerald-800 mb-1 flex items-center gap-1">
                                        <i class="fa-solid fa-key text-emerald-600"></i> Kunci Jawaban Benar
                                    </label>
                                    <select :name="`questions[${index}][correct_option]`" x-model="q.correct_option" required class="w-full border-emerald-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm font-bold bg-emerald-50/50">
                                        <option value="a">Pilihan A</option>
                                        <option value="b">Pilihan B</option>
                                        <option value="c">Pilihan C</option>
                                        <option value="d">Pilihan D</option>
                                    </select>
                                </div>
                            </div>
                        </template>

                        <div x-show="questions.length === 0" class="p-8 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <p class="text-gray-500 text-sm">Belum ada soal ditambahkan. Klik <strong>+ Tambah Soal</strong> untuk membuat pertanyaan pilihan ganda.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.jobs.index') }}" class="bg-white hover:bg-gray-100 text-gray-700 font-bold py-3 px-6 rounded-xl border border-gray-200 text-sm transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition text-sm flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Tes & Soal
                    </button>
                </div>
            </form>

            <!-- Import CSV Modal -->
            <div x-show="showImportModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-xs flex items-center justify-center p-4" x-cloak>
                <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-gray-100 space-y-6">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                        <h3 class="font-black text-lg text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-file-csv text-indigo-600"></i> Impor Soal dari File CSV
                        </h3>
                        <button type="button" @click="showImportModal = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.jobs.test.import', $job->id) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih File CSV Soal</label>
                            <input type="file" name="csv_file" accept=".csv" required class="block w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>

                        <div class="p-4 bg-gray-50 rounded-2xl text-2xs text-gray-600 space-y-1">
                            <p class="font-bold text-gray-800">Format Kolom File CSV (6 Kolom):</p>
                            <p><code>Pertanyaan, Pilihan A, Pilihan B, Pilihan C, Pilihan D, Kunci (a/b/c/d)</code></p>
                            <p class="text-blue-600 font-medium">Tips: Anda dapat mengunduh templat CSV dengan tombol <strong>"Unduh / Ekspor CSV"</strong>.</p>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="showImportModal = false" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl text-xs">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md transition flex items-center gap-1.5">
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
