<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 dark:text-white leading-tight flex items-center gap-3">
                    <span class="p-2 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-2xl">
                        <i class="fa-solid fa-wand-magic-sparkles text-xl"></i>
                    </span>
                    Interactive Live CV Builder
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Sesuaikan data profil dan pilih template CV ATS-friendly & modern secara real-time.
                </p>
            </div>
            
            <a href="{{ route('profile.candidate.details.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                <i class="fa-solid fa-user-gear"></i>
                Kelola Profil Utama
            </a>
        </div>
    </x-slot>

    @php
        $initialExperiences = $profile->experiences ?? [
            ['title' => 'Software Engineer', 'company' => 'Tech Corp', 'start_date' => '2023', 'end_date' => 'Sekarang', 'description' => 'Mengembangkan aplikasi web berbasis Laravel dan React.']
        ];
        $initialEducations = $profile->educations ?? [
            ['institution' => 'Universitas Indonesia', 'degree' => 'S1', 'field_of_study' => 'Teknik Informatika', 'start_year' => '2019', 'end_year' => '2023']
        ];
        $initialSkills = is_array($profile->skills) ? implode(', ', array_map(fn($s) => is_array($s) ? ($s['name'] ?? '') : $s, $profile->skills)) : ($profile->skills ?? 'PHP, Laravel, JavaScript, Tailwind CSS');
    @endphp

    <div class="py-8" x-data="{
        template: 'ats',
        accentColor: '#0f172a',
        form: {
            name: '{{ addslashes($user->name) }}',
            position: '{{ addslashes($profile->current_position ?? 'Professional Specialist') }}',
            email: '{{ addslashes($user->email) }}',
            phone: '{{ addslashes($profile->phone ?? '') }}',
            address: '{{ addslashes($profile->address ?? '') }}',
            summary: '{{ addslashes(preg_replace('/\s+/', ' ', $profile->summary ?? 'Seorang profesional berdedikasi dengan keahlian mendalam di bidang teknologi dan manajemen proyek.')) }}',
            skillsInput: '{{ addslashes($initialSkills) }}',
            experiences: {{ json_encode($initialExperiences) }},
            educations: {{ json_encode($initialEducations) }}
        },
        addExperience() {
            this.form.experiences.push({ title: '', company: '', start_date: '', end_date: '', description: '' });
        },
        removeExperience(index) {
            this.form.experiences.splice(index, 1);
        },
        addEducation() {
            this.form.educations.push({ institution: '', degree: '', field_of_study: '', start_year: '', end_year: '' });
        },
        removeEducation(index) {
            this.form.educations.splice(index, 1);
        },
        get skillsArray() {
            if (!this.form.skillsInput) return [];
            return this.form.skillsInput.split(',').map(s => s.trim()).filter(s => s.length > 0);
        },
        downloadPdf() {
            const url = '{{ route('profile.cv.download') }}?format=' + this.template + '&color=' + encodeURIComponent(this.accentColor);
            window.open(url, '_blank');
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Panel: Form & Controls -->
                <div class="lg:col-span-6 space-y-6">
                    
                    <!-- Template & Styling Selection Card -->
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700">
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-palette text-indigo-500"></i>
                            1. Pilih Template & Warna Aksen
                        </h3>

                        <!-- Template Selector -->
                        <div class="grid grid-cols-3 gap-3 mb-5">
                            <button type="button" @click="template = 'ats'" 
                                :class="template === 'ats' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-950/30 text-indigo-700 dark:text-indigo-300 ring-2 ring-indigo-500' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 bg-slate-50 dark:bg-slate-900 text-slate-600 dark:text-slate-400'"
                                class="p-3.5 rounded-2xl border text-center transition-all text-xs font-bold flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-file-lines text-lg"></i>
                                <span>ATS Classic</span>
                            </button>

                            <button type="button" @click="template = 'creative'" 
                                :class="template === 'creative' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-950/30 text-indigo-700 dark:text-indigo-300 ring-2 ring-indigo-500' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 bg-slate-50 dark:bg-slate-900 text-slate-600 dark:text-slate-400'"
                                class="p-3.5 rounded-2xl border text-center transition-all text-xs font-bold flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-wand-magic text-lg"></i>
                                <span>Creative Modern</span>
                            </button>

                            <button type="button" @click="template = 'minimalist'" 
                                :class="template === 'minimalist' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-950/30 text-indigo-700 dark:text-indigo-300 ring-2 ring-indigo-500' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 bg-slate-50 dark:bg-slate-900 text-slate-600 dark:text-slate-400'"
                                class="p-3.5 rounded-2xl border text-center transition-all text-xs font-bold flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-crown text-lg"></i>
                                <span>Executive Minimalist</span>
                            </button>
                        </div>

                        <!-- Accent Color Picker -->
                        <div>
                            <label class="block text-2xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                                Warna Aksen Template:
                            </label>
                            <div class="flex items-center gap-3">
                                <template x-for="color in ['#0f172a', '#2563eb', '#059669', '#7c3aed', '#dc2626', '#d97706']" :key="color">
                                    <button type="button" @click="accentColor = color" 
                                        :style="'background-color: ' + color"
                                        :class="accentColor === color ? 'ring-4 ring-indigo-400 scale-110' : 'hover:scale-105'"
                                        class="w-7 h-7 rounded-full transition-transform border-2 border-white shadow-xs">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Information Editor Accordion Form -->
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 space-y-6">
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-pen-to-square text-indigo-500"></i>
                            2. Edit Data CV Live
                        </h3>

                        <!-- Personal Info -->
                        <div class="space-y-4 pt-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                                <input type="text" x-model="form.name" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-indigo-500">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Posisi / Judul Profesional</label>
                                    <input type="text" x-model="form.position" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">No. Handphone</label>
                                    <input type="text" x-model="form.phone" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Ringkasan Profil (Bio / Summary)</label>
                                <textarea x-model="form.summary" rows="3" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>

                        <!-- Work Experiences -->
                        <div class="border-t border-slate-100 dark:border-slate-700/60 pt-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Pengalaman Kerja</h4>
                                <button type="button" @click="addExperience()" class="text-xs text-indigo-600 dark:text-indigo-400 font-bold hover:underline">
                                    + Tambah Pengalaman
                                </button>
                            </div>

                            <template x-for="(exp, index) in form.experiences" :key="index">
                                <div class="p-3.5 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 mb-3 space-y-3 relative">
                                    <button type="button" @click="removeExperience(index)" class="absolute top-2 right-2 text-slate-400 hover:text-red-500 text-xs">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <input type="text" x-model="exp.title" placeholder="Posisi Pekerjaan" class="w-full text-2xs rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        </div>
                                        <div>
                                            <input type="text" x-model="exp.company" placeholder="Nama Perusahaan" class="w-full text-2xs rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <input type="text" x-model="exp.start_date" placeholder="Mulai (e.g. 2022)" class="w-full text-2xs rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        </div>
                                        <div>
                                            <input type="text" x-model="exp.end_date" placeholder="Selesai (e.g. Sekarang)" class="w-full text-2xs rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        </div>
                                    </div>
                                    <div>
                                        <textarea x-model="exp.description" placeholder="Deskripsi tugas dan pencapaian..." rows="2" class="w-full text-2xs rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100"></textarea>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Skills Tags -->
                        <div class="border-t border-slate-100 dark:border-slate-700/60 pt-4">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Daftar Keahlian (Pisahkan dengan Koma)</label>
                            <input type="text" x-model="form.skillsInput" placeholder="Laravel, Vue.js, MySQL, Public Speaking" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-indigo-500">
                        </div>

                        <!-- Download Action Button -->
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                            <button type="button" @click="downloadPdf()" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-extrabold text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-file-pdf text-lg"></i>
                                Unduh CV PDF Resmi (A4)
                            </button>
                        </div>

                    </div>
                </div>

                <!-- Right Panel: Real-Time Live Preview -->
                <div class="lg:col-span-6 sticky top-24">
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xs font-extrabold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-ping"></span>
                                Live Preview CV
                            </h3>
                            <span class="text-3xs px-2.5 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-extrabold rounded-full uppercase" x-text="template + ' template'"></span>
                        </div>

                        <!-- Virtual Sheet Container -->
                        <div class="bg-white text-slate-900 p-6 rounded-2xl shadow-inner border border-slate-200 min-h-[560px] text-xs space-y-4 transition-all"
                            :style="'border-top: 6px solid ' + accentColor">
                            
                            <!-- Template ATS Preview -->
                            <template x-if="template === 'ats'">
                                <div>
                                    <div class="text-center border-b pb-3 mb-3 border-slate-200">
                                        <h2 class="text-xl font-black uppercase text-slate-900 tracking-tight" x-text="form.name"></h2>
                                        <p class="text-xs font-bold text-indigo-600" x-text="form.position"></p>
                                        <p class="text-3xs text-slate-500 mt-1" x-text="'Email: ' + form.email + (form.phone ? ' | HP: ' + form.phone : '')"></p>
                                    </div>

                                    <template x-if="form.summary">
                                        <div class="mb-3">
                                            <h4 class="text-2xs font-extrabold uppercase text-slate-900 border-b border-slate-300 pb-0.5 mb-1 tracking-wider">Ringkasan Profil</h4>
                                            <p class="text-3xs text-slate-700 leading-relaxed" x-text="form.summary"></p>
                                        </div>
                                    </template>

                                    <template x-if="form.experiences.length > 0">
                                        <div class="mb-3">
                                            <h4 class="text-2xs font-extrabold uppercase text-slate-900 border-b border-slate-300 pb-0.5 mb-1 tracking-wider">Pengalaman Kerja</h4>
                                            <template x-for="exp in form.experiences" :key="exp.title">
                                                <div class="mb-2">
                                                    <div class="flex justify-between items-baseline">
                                                        <span class="font-bold text-2xs text-slate-900" x-text="exp.title + (exp.company ? ' — ' + exp.company : '')"></span>
                                                        <span class="text-3xs text-slate-500" x-text="exp.start_date + ' - ' + exp.end_date"></span>
                                                    </div>
                                                    <p class="text-3xs text-slate-600 mt-0.5" x-text="exp.description"></p>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <template x-if="skillsArray.length > 0">
                                        <div>
                                            <h4 class="text-2xs font-extrabold uppercase text-slate-900 border-b border-slate-300 pb-0.5 mb-1 tracking-wider">Keahlian Utama</h4>
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                <template x-for="skill in skillsArray" :key="skill">
                                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-800 border border-slate-300 text-3xs font-semibold rounded" x-text="skill"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Template Creative Preview -->
                            <template x-if="template === 'creative'">
                                <div>
                                    <div class="flex items-center gap-4 pb-4 border-b border-slate-200">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white font-black text-lg shadow-md"
                                             :style="'background-color: ' + accentColor"
                                             x-text="form.name ? form.name.charAt(0) : 'U'">
                                        </div>
                                        <div>
                                            <h2 class="text-lg font-black text-slate-900 leading-tight" x-text="form.name"></h2>
                                            <p class="text-xs font-bold text-indigo-600" x-text="form.position"></p>
                                            <p class="text-3xs text-slate-500 mt-0.5" x-text="form.email + (form.phone ? ' • ' + form.phone : '')"></p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-12 gap-4 mt-3">
                                        <div class="col-span-8 space-y-3">
                                            <template x-if="form.summary">
                                                <div>
                                                    <h4 class="text-3xs font-black uppercase text-indigo-600 tracking-wider mb-1">Tentang Saya</h4>
                                                    <p class="text-3xs text-slate-700 leading-relaxed" x-text="form.summary"></p>
                                                </div>
                                            </template>

                                            <template x-if="form.experiences.length > 0">
                                                <div>
                                                    <h4 class="text-3xs font-black uppercase text-indigo-600 tracking-wider mb-1">Pengalaman</h4>
                                                    <template x-for="exp in form.experiences" :key="exp.title">
                                                        <div class="mb-2">
                                                            <div class="font-bold text-2xs text-slate-900" x-text="exp.title"></div>
                                                            <div class="text-3xs text-slate-500 font-semibold" x-text="exp.company"></div>
                                                            <p class="text-3xs text-slate-600 mt-0.5" x-text="exp.description"></p>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="col-span-4 bg-slate-50 p-3 rounded-xl space-y-3 border border-slate-100">
                                            <template x-if="skillsArray.length > 0">
                                                <div>
                                                    <h4 class="text-3xs font-black uppercase text-slate-700 tracking-wider mb-1">Keahlian</h4>
                                                    <div class="space-y-1">
                                                        <template x-for="skill in skillsArray" :key="skill">
                                                            <div class="px-2 py-0.5 bg-white text-slate-800 text-3xs font-bold rounded shadow-2xs border border-slate-200 truncate" x-text="skill"></div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Template Minimalist Preview -->
                            <template x-if="template === 'minimalist'">
                                <div class="space-y-3">
                                    <div class="pb-3 border-b-2" :style="'border-color: ' + accentColor">
                                        <h2 class="text-xl font-black tracking-tight text-slate-900 uppercase" x-text="form.name"></h2>
                                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest" x-text="form.position"></p>
                                        <div class="mt-2 text-3xs text-slate-600 bg-slate-50 p-2 rounded border-l-2" :style="'border-color: ' + accentColor">
                                            <span x-text="'Email: ' + form.email"></span>
                                            <span x-text="form.phone ? ' | HP: ' + form.phone : ''"></span>
                                        </div>
                                    </div>

                                    <template x-if="form.summary">
                                        <div>
                                            <h4 class="text-3xs font-black uppercase tracking-widest text-slate-900 mb-1 border-b pb-0.5">Ringkasan Eksekutif</h4>
                                            <p class="text-3xs text-slate-700 leading-relaxed" x-text="form.summary"></p>
                                        </div>
                                    </template>

                                    <template x-if="form.experiences.length > 0">
                                        <div>
                                            <h4 class="text-3xs font-black uppercase tracking-widest text-slate-900 mb-1 border-b pb-0.5">Pengalaman Kerja</h4>
                                            <template x-for="exp in form.experiences" :key="exp.title">
                                                <div class="mb-2">
                                                    <div class="flex justify-between font-bold text-2xs text-slate-900">
                                                        <span x-text="exp.title + (exp.company ? ' | ' + exp.company : '')"></span>
                                                        <span class="text-slate-400 text-3xs" x-text="exp.start_date + ' - ' + exp.end_date"></span>
                                                    </div>
                                                    <p class="text-3xs text-slate-600 mt-0.5" x-text="exp.description"></p>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
