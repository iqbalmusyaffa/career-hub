<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div>
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('mentor.curriculums.index') }}" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center text-xs transition">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h2 class="font-bold text-xl sm:text-2xl text-slate-900 dark:text-white tracking-tight">
                        {{ $isEdit ? 'Edit Silabus Kurikulum & Materi' : 'Buat Silabus Kurikulum Baru' }}
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 font-normal ml-10.5">
                    Tentukan modul silabus pembelajaran, kompetensi yang dicapai, serta link referensi/materi belajar.
                </p>
            </div>
            <a href="{{ route('mentor.curriculums.index') }}" class="px-4 py-2 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 shadow-xs transition flex items-center gap-1.5 self-start sm:self-auto">
                <i class="fa-solid fa-xmark text-xs text-slate-400"></i>
                <span>Batal</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Notifications -->
            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-xl flex items-center gap-3 text-rose-800 dark:text-rose-300 text-xs font-medium">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-base shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-xl space-y-1 text-rose-800 dark:text-rose-300 text-xs font-medium">
                    <div class="font-bold flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                        <span>Mohon periksa kesalahan input berikut:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 pl-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" 
                  action="{{ $isEdit ? route('mentor.curriculums.update', $curriculum->id) : route('mentor.curriculums.store') }}"
                  x-data="curriculumBuilder({
                      initialMaterials: {{ Js::from(old('materials_json') ? json_decode(old('materials_json'), true) : $initialMaterials) }}
                  })"
                  @submit="prepareSubmit($event)"
                  class="space-y-6">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <input type="hidden" name="materials_json" x-model="materialsJson">

                <!-- SECTION 1: INFORMASI UMUM KURIKULUM -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs font-bold shrink-0">
                            1
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Informasi Dasar Kurikulum</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Identitas kurikulum dan penugasan ke lowongan magang</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Judul Kurikulum <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   value="{{ old('title', $curriculum->title) }}" 
                                   required 
                                   placeholder="Contoh: Kurikulum Backend & DevOps Engineering 2026"
                                   class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 py-2.5 px-3">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Posisi Lowongan Terkait
                            </label>
                            <select name="job_id" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 py-2.5 px-3">
                                <option value="">-- Umum / Berlaku untuk Semua Posisi --</option>
                                @foreach($availableJobs as $job)
                                    <option value="{{ $job->id }}" {{ old('job_id', $curriculum->job_id) == $job->id ? 'selected' : '' }}>
                                        {{ $job->title }} ({{ $job->company_name }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-slate-400">Pilih posisi spesifik agar silabus ini otomatis muncul untuk kandidat posisi tersebut.</p>
                        </div>

                        <div class="space-y-1.5" x-data="{ isCustomBatch: false, selectedBatch: '{{ old('batch', $curriculum->batch) }}' }">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    Batch / Angkatan Magang
                                </label>
                                <button type="button" @click="isCustomBatch = !isCustomBatch" class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold hover:underline">
                                    <span x-show="!isCustomBatch">+ Ketik Manual</span>
                                    <span x-show="isCustomBatch">← Pilih Batch Aktif</span>
                                </button>
                            </div>

                            <!-- Dropdown Pilihan Batch Aktif -->
                            <div x-show="!isCustomBatch">
                                <select name="batch" 
                                        x-model="selectedBatch"
                                        :disabled="isCustomBatch"
                                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 py-2.5 px-3">
                                    <option value="">-- Berlaku untuk Semua Batch --</option>
                                    @foreach($availableBatches ?? [] as $batch)
                                        <option value="{{ $batch }}" {{ old('batch', $curriculum->batch) == $batch ? 'selected' : '' }}>
                                            {{ $batch }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Input Teks Manual Jika Batch Belum Ada di Daftar -->
                            <div x-show="isCustomBatch" style="display: none;">
                                <input type="text" 
                                       name="batch" 
                                       x-model="selectedBatch"
                                       :disabled="!isCustomBatch"
                                       placeholder="Contoh: Batch 1 - Semester Genap 2026"
                                       class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 py-2.5 px-3">
                            </div>
                        </div>

                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Deskripsi & Tujuan Pembelajaran
                            </label>
                            <textarea name="description" 
                                      rows="3" 
                                      placeholder="Jelaskan ringkasan kurikulum, sasaran akhir kemampuan peserta magang..."
                                      class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 py-2 px-3">{{ old('description', $curriculum->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: MODUL MATERI & LINK PEMBELAJARAN (REPEATER) -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-xs space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold shrink-0">
                                2
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Silabus Modul & Materi Pembelajaran</h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Tambahkan modul bertahap (Bulan 1, 2, dst.), fokus kompetensi, dan link materi</p>
                            </div>
                        </div>

                        <button type="button" 
                                @click="addMaterial()" 
                                class="inline-flex items-center gap-2 px-3.5 py-2 bg-purple-50 dark:bg-purple-950/60 hover:bg-purple-100 dark:hover:bg-purple-900/60 text-purple-700 dark:text-purple-300 rounded-xl text-xs font-semibold border border-purple-200 dark:border-purple-800 transition shrink-0 self-start sm:self-auto">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>Tambah Modul Materi</span>
                        </button>
                    </div>

                    <!-- Repeater Modules Container -->
                    <div class="space-y-4">
                        <template x-for="(mat, index) in materials" :key="index">
                            <div class="p-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 space-y-4 relative transition hover:border-slate-300 dark:hover:border-slate-700">
                                
                                <!-- Card Header -->
                                <div class="flex items-center justify-between gap-3 pb-3 border-b border-slate-200/60 dark:border-slate-800">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-6 h-6 rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs flex items-center justify-center" x-text="index + 1"></span>
                                        <span class="text-xs font-bold text-slate-900 dark:text-white" x-text="'Modul / Bulan ' + (index + 1)"></span>
                                    </div>
                                    <button type="button" 
                                            @click="removeMaterial(index)" 
                                            x-show="materials.length > 1"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition" 
                                            title="Hapus Modul">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>

                                <!-- Title & Description -->
                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                    <div class="sm:col-span-8 space-y-1">
                                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                            Judul Modul Materi <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" 
                                               x-model="mat.title" 
                                               required 
                                               placeholder="Contoh: Bulan 1: Orientasi & Setup Lingkungan Kerja"
                                               class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 py-2 px-3">
                                    </div>

                                    <div class="sm:col-span-4 space-y-1">
                                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                            Urutan / Bulan Ke
                                        </label>
                                        <input type="number" 
                                               x-model.number="mat.sequence" 
                                               min="1"
                                               class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 py-2 px-3">
                                    </div>

                                    <div class="sm:col-span-12 space-y-1">
                                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                            Ringkasan Pokok Bahasan
                                        </label>
                                        <textarea x-model="mat.description" 
                                                  rows="2" 
                                                  placeholder="Jelaskan gambaran umum topik dan materi yang akan dipelajari..."
                                                  class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 py-2 px-3"></textarea>
                                    </div>
                                </div>

                                <!-- Fokus Kompetensi (Tags) -->
                                <div class="space-y-2 pt-2 border-t border-slate-200/60 dark:border-slate-800">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                            Fokus Target Kompetensi (Checklist Capaian)
                                        </label>
                                        <span class="text-[10px] text-slate-400">Tekan Enter atau klik Tambah</span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-1.5 p-2 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
                                        <template x-for="(comp, cIdx) in mat.competencies" :key="cIdx">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 text-[11px] font-medium">
                                                <span>✓ <span x-text="comp"></span></span>
                                                <button type="button" @click="removeCompetency(index, cIdx)" class="text-blue-400 hover:text-blue-700 dark:hover:text-blue-200 text-xs">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </span>
                                        </template>

                                        <div class="flex items-center gap-2 flex-1 min-w-[200px]">
                                            <input type="text" 
                                                   x-ref="'compInput_' + index" 
                                                   @keydown.enter.prevent="addCompetency(index, $refs['compInput_' + index].value); $refs['compInput_' + index].value = '';"
                                                   placeholder="+ Ketik nama kompetensi (misal: Git Workflow) lalu Enter..."
                                                   class="w-full bg-transparent border-none text-xs text-slate-900 dark:text-slate-100 focus:ring-0 p-1 placeholder-slate-400">
                                        </div>
                                    </div>
                                </div>

                                <!-- Learning Links (Links Pembelajaran) -->
                                <div class="space-y-2 pt-2 border-t border-slate-200/60 dark:border-slate-800">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                            <i class="fa-solid fa-link text-blue-500 mr-1"></i> Tautan / Link Pembelajaran & Referensi
                                        </label>
                                        <button type="button" 
                                                @click="addLink(index)" 
                                                class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                                            <i class="fa-solid fa-plus text-[10px]"></i> Tambah Link
                                        </button>
                                    </div>

                                    <div class="space-y-2">
                                        <template x-for="(link, lIdx) in mat.learning_links" :key="lIdx">
                                            <div class="flex items-center gap-2 bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200 dark:border-slate-800">
                                                <div class="w-1/3">
                                                    <input type="text" 
                                                           x-model="link.title" 
                                                           placeholder="Judul Link (misal: Video Materi / Modul PDF)"
                                                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs py-1.5 px-2.5 text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-blue-600">
                                                </div>
                                                <div class="flex-1">
                                                    <input type="url" 
                                                           x-model="link.url" 
                                                           placeholder="https://drive.google.com/... atau https://youtube.com/..."
                                                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs py-1.5 px-2.5 text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-blue-600">
                                                </div>
                                                <button type="button" 
                                                        @click="removeLink(index, lIdx)" 
                                                        class="p-1.5 text-slate-400 hover:text-rose-600 transition" 
                                                        title="Hapus Link">
                                                    <i class="fa-solid fa-xmark text-xs"></i>
                                                </button>
                                            </div>
                                        </template>

                                        <div x-show="!mat.learning_links || mat.learning_links.length === 0" class="text-[11px] text-slate-400 italic py-1">
                                            Belum ada link materi pada modul ini. Klik "Tambah Link" untuk menyematkan URL Google Drive, Docs, Video Youtube, LMS, dsb.
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </template>
                    </div>

                    <!-- Bottom Add Module Button -->
                    <button type="button" 
                            @click="addMaterial()" 
                            class="w-full py-3 bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-2xl text-xs font-semibold border-2 border-dashed border-slate-200 dark:border-slate-700 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Tambah Modul Silabus Berikutnya</span>
                    </button>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('mentor.curriculums.index') }}" class="px-5 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>{{ $isEdit ? 'Simpan Perubahan' : 'Terbitkan Kurikulum' }}</span>
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script>
        function curriculumBuilder(config) {
            return {
                materials: config.initialMaterials && config.initialMaterials.length > 0 ? config.initialMaterials : [
                    {
                        sequence: 1,
                        title: 'Bulan 1: Orientasi & Pengenalan Workflow',
                        description: '',
                        competencies: ['Git Workflow', 'Struktur Proyek'],
                        learning_links: []
                    }
                ],
                materialsJson: '',
                addMaterial() {
                    const nextSeq = this.materials.length + 1;
                    this.materials.push({
                        sequence: nextSeq,
                        title: 'Bulan ' + nextSeq + ': ',
                        description: '',
                        competencies: [],
                        learning_links: []
                    });
                },
                removeMaterial(index) {
                    if (this.materials.length > 1) {
                        this.materials.splice(index, 1);
                        // Re-index sequences
                        this.materials.forEach((m, idx) => {
                            m.sequence = idx + 1;
                        });
                    }
                },
                addCompetency(matIndex, val) {
                    val = (val || '').trim();
                    if (!val) return;
                    if (!this.materials[matIndex].competencies) {
                        this.materials[matIndex].competencies = [];
                    }
                    if (!this.materials[matIndex].competencies.includes(val)) {
                        this.materials[matIndex].competencies.push(val);
                    }
                },
                removeCompetency(matIndex, compIndex) {
                    this.materials[matIndex].competencies.splice(compIndex, 1);
                },
                addLink(matIndex) {
                    if (!this.materials[matIndex].learning_links) {
                        this.materials[matIndex].learning_links = [];
                    }
                    this.materials[matIndex].learning_links.push({
                        title: '',
                        url: ''
                    });
                },
                removeLink(matIndex, linkIndex) {
                    this.materials[matIndex].learning_links.splice(linkIndex, 1);
                },
                prepareSubmit(event) {
                    this.materialsJson = JSON.stringify(this.materials);
                }
            }
        }
    </script>
</x-app-layout>
