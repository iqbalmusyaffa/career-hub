<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.candidate.details.edit') }}" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition flex items-center justify-center shadow-2xs shrink-0" title="Kembali ke Profil">
                    <i class="fa-solid fa-arrow-left text-xs sm:text-sm"></i>
                </a>
                <div>
                    <h1 class="text-lg sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xs shadow-xs shrink-0">
                            <i class="fa-solid fa-file-invoice"></i>
                        </span>
                        <span>Pembuat CV & Resume Profesional</span>
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Pilih template standar industri, edit data dengan tab interaktif, dan unduh dokumen siap kirim.
                    </p>
                </div>
            </div>
            
            <!-- Quick Top Action Buttons -->
            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ route('profile.candidate.details.edit') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition border border-slate-200 dark:border-slate-700 shadow-2xs">
                    <i class="fa-solid fa-user-gear text-slate-400"></i>
                    <span class="hidden md:inline">Kelola Profil Utama</span>
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $initialExperiences = !empty($profile->experiences) ? $profile->experiences : [
            ['title' => 'Software Engineer', 'company' => 'PT Teknologi Nusantara', 'start_date' => '2023', 'end_date' => 'Sekarang', 'description' => 'Mengembangkan arsitektur backend dengan Laravel dan mengoptimalkan performa database MySQL.']
        ];
        $initialEducations = !empty($profile->educations) ? $profile->educations : [
            ['institution' => 'Universitas Indonesia', 'degree' => 'S1', 'field_of_study' => 'Teknik Informatika', 'start_year' => '2019', 'end_year' => '2023', 'gpa' => '3.85']
        ];
        $initialOrganizations = !empty($profile->organizations) ? $profile->organizations : [
            ['name' => 'Himpunan Mahasiswa Komputer', 'position' => 'Ketua Divisi Riset & Teknologi', 'period' => '2021 - 2022', 'description' => 'Mengkoordinasikan pelatihan pemrograman dan workshop karir bagi 200+ anggota.']
        ];
        $initialCertificates = !empty($profile->certificates) ? $profile->certificates : [
            ['name' => 'Certified Full Stack Web Developer', 'issuer' => 'BNSP / Kemkominfo', 'year' => '2024']
        ];
        $initialLanguages = !empty($profile->languages) ? $profile->languages : [
            ['name' => 'Bahasa Indonesia', 'proficiency' => 'Fasih (Native)'],
            ['name' => 'Bahasa Inggris', 'proficiency' => 'Kemahiran Kerja Profesional']
        ];
        $initialSkills = is_array($profile->skills) ? implode(', ', array_map(fn($s) => is_array($s) ? ($s['name'] ?? '') : (string)$s, $profile->skills)) : ($profile->skills ?? 'PHP, Laravel, MySQL, RESTful API, JavaScript, Git, Tailwind CSS');
        $initialLinkedin = $profile->social_links['linkedin'] ?? '';
        $initialPortfolio = $profile->social_links['portfolio'] ?? '';
        $avatarUrl = !empty($user->avatar) ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar)) : '';
    @endphp

    <div class="py-6 sm:py-8" x-data="{
        template: 'ats',
        accentColor: '#0f172a',
        activeTab: 'design',
        previewZoom: false,
        isSaving: false,
        saveSuccessMessage: '',
        saveErrorMessage: '',
        avatarUrl: '{{ addslashes($avatarUrl) }}',
        mobileView: 'editor',
        form: {
            name: '{{ addslashes($user->name) }}',
            position: '{{ addslashes($profile->current_position ?? 'Spesialis Profesional') }}',
            email: '{{ addslashes($user->email) }}',
            phone: '{{ addslashes($profile->phone ?? '') }}',
            address: '{{ addslashes($profile->address ?? '') }}',
            linkedin: '{{ addslashes($initialLinkedin) }}',
            portfolio: '{{ addslashes($initialPortfolio) }}',
            summary: '{{ addslashes(preg_replace('/\s+/', ' ', $profile->summary ?? 'Profesional berorientasi hasil dengan dedikasi tinggi dalam memecahkan masalah kompleks, berkolaborasi dalam tim, serta mendorong peningkatan efisiensi operasional.')) }}',
            skillsInput: '{{ addslashes($initialSkills) }}',
            experiences: {{ json_encode($initialExperiences) }},
            educations: {{ json_encode($initialEducations) }},
            organizations: {{ json_encode($initialOrganizations) }},
            certificates: {{ json_encode($initialCertificates) }},
            languages: {{ json_encode($initialLanguages) }}
        },
        addExperience() {
            this.form.experiences.push({ title: '', company: '', start_date: '', end_date: '', description: '' });
        },
        removeExperience(index) {
            this.form.experiences.splice(index, 1);
        },
        addEducation() {
            this.form.educations.push({ institution: '', degree: '', field_of_study: '', start_year: '', end_year: '', gpa: '' });
        },
        removeEducation(index) {
            this.form.educations.splice(index, 1);
        },
        addOrganization() {
            this.form.organizations.push({ name: '', position: '', period: '', description: '' });
        },
        removeOrganization(index) {
            this.form.organizations.splice(index, 1);
        },
        addCertificate() {
            this.form.certificates.push({ name: '', issuer: '', year: '' });
        },
        removeCertificate(index) {
            this.form.certificates.splice(index, 1);
        },
        addLanguage() {
            this.form.languages.push({ name: '', proficiency: 'Konversasi Menengah' });
        },
        removeLanguage(index) {
            this.form.languages.splice(index, 1);
        },
        get skillsArray() {
            if (!this.form.skillsInput) return [];
            return this.form.skillsInput.split(',').map(s => s.trim()).filter(s => s.length > 0);
        },
        async saveProfileData() {
            this.isSaving = true;
            this.saveSuccessMessage = '';
            this.saveErrorMessage = '';
            try {
                const response = await fetch('{{ route('candidate.cv-builder.save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: this.form.name,
                        position: this.form.position,
                        phone: this.form.phone,
                        address: this.form.address,
                        linkedin: this.form.linkedin,
                        portfolio: this.form.portfolio,
                        summary: this.form.summary,
                        skills: this.form.skillsInput,
                        experiences: this.form.experiences,
                        educations: this.form.educations,
                        organizations: this.form.organizations,
                        certificates: this.form.certificates,
                        languages: this.form.languages
                    })
                });
                const res = await response.json();
                if (response.ok && res.success) {
                    this.saveSuccessMessage = res.message || 'Perubahan data CV berhasil disimpan ke profil!';
                    setTimeout(() => { this.saveSuccessMessage = ''; }, 4500);
                } else {
                    this.saveErrorMessage = res.message || 'Gagal menyimpan perubahan.';
                    setTimeout(() => { this.saveErrorMessage = ''; }, 4500);
                }
            } catch (e) {
                this.saveErrorMessage = 'Terjadi kesalahan jaringan saat menyimpan data.';
                setTimeout(() => { this.saveErrorMessage = ''; }, 4500);
            } finally {
                this.isSaving = false;
            }
        },
        downloadPdf() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('profile.cv.download') }}';
            form.target = '_blank';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const formatInput = document.createElement('input');
            formatInput.type = 'hidden';
            formatInput.name = 'format';
            formatInput.value = this.template;
            form.appendChild(formatInput);

            const colorInput = document.createElement('input');
            colorInput.type = 'hidden';
            colorInput.name = 'color';
            colorInput.value = this.accentColor;
            form.appendChild(colorInput);

            const formPayloadInput = document.createElement('input');
            formPayloadInput.type = 'hidden';
            formPayloadInput.name = 'form';
            formPayloadInput.value = JSON.stringify(this.form);
            form.appendChild(formPayloadInput);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Mobile Toggle Bar (Editor vs Preview) -->
            <div class="lg:hidden flex items-center p-1 bg-slate-200 dark:bg-slate-800 rounded-xl mb-4">
                <button type="button" @click="mobileView = 'editor'"
                    :class="mobileView === 'editor' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white font-bold shadow-xs' : 'text-slate-600 dark:text-slate-400 font-medium'"
                    class="flex-1 py-2 text-xs rounded-lg transition text-center flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Formulir Editor</span>
                </button>
                <button type="button" @click="mobileView = 'preview'"
                    :class="mobileView === 'preview' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white font-bold shadow-xs' : 'text-slate-600 dark:text-slate-400 font-medium'"
                    class="flex-1 py-2 text-xs rounded-lg transition text-center flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-file-lines"></i>
                    <span>Pratinjau Kertas A4</span>
                </button>
            </div>

            <!-- Main Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
                
                <!-- Left Panel: Tabbed Form Editor -->
                <div class="lg:col-span-6 space-y-4" :class="mobileView === 'preview' ? 'hidden lg:block' : 'block'">
                    
                    <!-- Top Action & Toast Bar -->
                    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Editor CV Interaktif</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="saveProfileData()" :disabled="isSaving"
                                class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50" title="Simpan ke database profil">
                                <i class="fa-solid" :class="isSaving ? 'fa-spinner fa-spin' : 'fa-floppy-disk text-slate-500 dark:text-slate-300'"></i>
                                <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Profil'"></span>
                            </button>

                            <button type="button" @click="downloadPdf()"
                                class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer" title="Unduh PDF cetak">
                                <i class="fa-solid fa-file-pdf"></i>
                                <span>Unduh PDF</span>
                            </button>
                        </div>
                    </div>

                    <!-- Toast Feedback -->
                    <template x-if="saveSuccessMessage">
                        <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-xs text-emerald-700 dark:text-emerald-300 font-semibold flex items-center gap-2 shadow-2xs">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                            <span x-text="saveSuccessMessage"></span>
                        </div>
                    </template>
                    <template x-if="saveErrorMessage">
                        <div class="p-3.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-2xl text-xs text-rose-700 dark:text-rose-300 font-semibold flex items-center gap-2 shadow-2xs">
                            <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm"></i>
                            <span x-text="saveErrorMessage"></span>
                        </div>
                    </template>

                    <!-- Horizontal Tab Navigation Bar -->
                    <div class="bg-white dark:bg-slate-800 p-2 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 flex overflow-x-auto gap-1.5 scrollbar-none">
                        <button type="button" @click="activeTab = 'design'" 
                            :class="activeTab === 'design' ? 'bg-blue-600 text-white shadow-2xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/60 font-medium'"
                            class="px-3 py-2 rounded-xl text-xs whitespace-nowrap transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-palette text-2xs"></i>
                            <span>1. Desain</span>
                        </button>

                        <button type="button" @click="activeTab = 'personal'" 
                            :class="activeTab === 'personal' ? 'bg-blue-600 text-white shadow-2xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/60 font-medium'"
                            class="px-3 py-2 rounded-xl text-xs whitespace-nowrap transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-user text-2xs"></i>
                            <span>2. Kontak & Bio</span>
                        </button>

                        <button type="button" @click="activeTab = 'experience'" 
                            :class="activeTab === 'experience' ? 'bg-blue-600 text-white shadow-2xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/60 font-medium'"
                            class="px-3 py-2 rounded-xl text-xs whitespace-nowrap transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-briefcase text-2xs"></i>
                            <span>3. Pengalaman</span>
                        </button>

                        <button type="button" @click="activeTab = 'education'" 
                            :class="activeTab === 'education' ? 'bg-blue-600 text-white shadow-2xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/60 font-medium'"
                            class="px-3 py-2 rounded-xl text-xs whitespace-nowrap transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-graduation-cap text-2xs"></i>
                            <span>4. Pendidikan</span>
                        </button>

                        <button type="button" @click="activeTab = 'skills'" 
                            :class="activeTab === 'skills' ? 'bg-blue-600 text-white shadow-2xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/60 font-medium'"
                            class="px-3 py-2 rounded-xl text-xs whitespace-nowrap transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-bolt text-2xs"></i>
                            <span>5. Keahlian</span>
                        </button>
                    </div>

                    <!-- TAB CONTENT PANELS -->
                    <div class="bg-white dark:bg-slate-800 p-5 sm:p-6 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 min-h-[420px]">
                        
                        <!-- TAB 1: DESAIN & TEMPLATE (COMPACT SLEEK DESIGN) -->
                        <div x-show="activeTab === 'design'" class="space-y-4">
                            <div class="border-b border-slate-100 dark:border-slate-700 pb-2.5">
                                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                                    Pilih Format Template Dokumen
                                </h3>
                                <p class="text-2xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Pilih layout yang sesuai dengan target lamaran kerja Anda.
                                </p>
                            </div>

                            <!-- Sleek Compact Template Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- ATS Standard -->
                                <button type="button" @click="template = 'ats'" 
                                    :class="template === 'ats' ? 'border-blue-600 bg-blue-50/50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 ring-2 ring-blue-500/30 font-bold shadow-xs' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-slate-300 font-medium'"
                                    class="p-3.5 rounded-xl border text-left transition flex flex-col justify-between gap-2.5 cursor-pointer shadow-2xs">
                                    <div class="flex items-center justify-between">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/60 text-blue-600 flex items-center justify-center text-sm">
                                            <i class="fa-solid fa-file-lines"></i>
                                        </div>
                                        <span class="text-4xs px-2 py-0.5 bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 font-bold rounded-full">ATS Friendly</span>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-900 dark:text-white">ATS Standar</div>
                                        <div class="text-3xs text-slate-500 dark:text-slate-400">1 Kolom Formal • Tanpa Foto</div>
                                    </div>
                                    <span class="text-4xs text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check"></i> Loker & Korporat
                                    </span>
                                </button>

                                <!-- Modern 2 Column (Kreatif + Foto) -->
                                <button type="button" @click="template = 'creative'" 
                                    :class="template === 'creative' ? 'border-blue-600 bg-blue-50/50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 ring-2 ring-blue-500/30 font-bold shadow-xs' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-slate-300 font-medium'"
                                    class="p-3.5 rounded-xl border text-left transition flex flex-col justify-between gap-2.5 cursor-pointer shadow-2xs">
                                    <div class="flex items-center justify-between">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 flex items-center justify-center text-sm">
                                            <i class="fa-solid fa-id-badge"></i>
                                        </div>
                                        <span class="text-4xs px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-bold rounded-full">Kreatif + Foto</span>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-900 dark:text-white">Modern 2 Kolom</div>
                                        <div class="text-3xs text-slate-500 dark:text-slate-400">Sidebar Foto • Ringkas & Visual</div>
                                    </div>
                                    <span class="text-4xs text-indigo-600 dark:text-indigo-400 font-semibold flex items-center gap-1">
                                        <i class="fa-solid fa-wand-magic-sparkles"></i> Startup & Agensi
                                    </span>
                                </button>

                                <!-- Minimalist -->
                                <button type="button" @click="template = 'minimalist'" 
                                    :class="template === 'minimalist' ? 'border-blue-600 bg-blue-50/50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 ring-2 ring-blue-500/30 font-bold shadow-xs' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-slate-300 font-medium'"
                                    class="p-3.5 rounded-xl border text-left transition flex flex-col justify-between gap-2.5 cursor-pointer shadow-2xs">
                                    <div class="flex items-center justify-between">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center text-sm">
                                            <i class="fa-solid fa-align-left"></i>
                                        </div>
                                        <span class="text-4xs px-2 py-0.5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold rounded-full">Elegan</span>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-900 dark:text-white">Ringkas Elegan</div>
                                        <div class="text-3xs text-slate-500 dark:text-slate-400">1 Kolom Minimalis Bersih</div>
                                    </div>
                                    <span class="text-4xs text-slate-600 dark:text-slate-400 font-semibold flex items-center gap-1">
                                        <i class="fa-solid fa-check"></i> Rapi & Ringkas
                                    </span>
                                </button>
                            </div>

                            <!-- Accent Color Selection -->
                            <div class="pt-3.5 border-t border-slate-100 dark:border-slate-700">
                                <label class="block text-2xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                    Warna Aksen Desain:
                                </label>
                                <div class="flex items-center gap-3">
                                    <template x-for="color in ['#0f172a', '#2563eb', '#059669', '#7c3aed', '#dc2626', '#d97706']" :key="color">
                                        <button type="button" @click="accentColor = color" 
                                            :style="'background-color: ' + color"
                                            :class="accentColor === color ? 'ring-3 ring-offset-2 ring-blue-500 scale-110 shadow-md' : 'hover:scale-105 opacity-90 hover:opacity-100'"
                                            class="w-7 h-7 rounded-full transition border-2 border-white dark:border-slate-800 shadow-2xs cursor-pointer">
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Next Tab Trigger -->
                            <div class="pt-3 flex justify-end">
                                <button type="button" @click="activeTab = 'personal'" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                                    <span>Lanjut Isi Kontak & Bio</span>
                                    <i class="fa-solid fa-arrow-right text-2xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- TAB 2: KONTAK & BIO -->
                        <div x-show="activeTab === 'personal'" class="space-y-4">
                            <div class="border-b border-slate-100 dark:border-slate-700 pb-3">
                                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                                    Informasi Pribadi, Kontak, & Bio
                                </h3>
                                <p class="text-2xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Pastikan data kontak aktif dan mudah dihubungi oleh tim rekruter.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block text-2xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                                    <input type="text" x-model="form.name" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-bold text-slate-700 dark:text-slate-300 mb-1">Posisi / Judul Profesi</label>
                                    <input type="text" x-model="form.position" placeholder="e.g. Senior Web Developer" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                                <div>
                                    <label class="block text-2xs font-bold text-slate-700 dark:text-slate-300 mb-1">Email</label>
                                    <input type="email" x-model="form.email" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-bold text-slate-700 dark:text-slate-300 mb-1">No. Handphone / WA</label>
                                    <input type="text" x-model="form.phone" placeholder="08123456789" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-bold text-slate-700 dark:text-slate-300 mb-1">Domisili / Kota</label>
                                    <input type="text" x-model="form.address" placeholder="Jakarta, Indonesia" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block text-2xs font-bold text-slate-700 dark:text-slate-300 mb-1">LinkedIn URL (Opsional)</label>
                                    <input type="text" x-model="form.linkedin" placeholder="linkedin.com/in/username" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-bold text-slate-700 dark:text-slate-300 mb-1">Portofolio / GitHub / Web (Opsional)</label>
                                    <input type="text" x-model="form.portfolio" placeholder="github.com/username / portfolio.com" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-2xs font-bold text-slate-700 dark:text-slate-300 mb-1">Ringkasan Profil (Bio / Professional Summary)</label>
                                <textarea x-model="form.summary" rows="3" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500" placeholder="Tuliskan 2-3 kalimat ringkas mengenai spesialisasi dan nilai unggul yang Anda tawarkan..."></textarea>
                            </div>

                            <!-- Tab Navigation Buttons -->
                            <div class="pt-4 flex justify-between">
                                <button type="button" @click="activeTab = 'design'" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold transition cursor-pointer">
                                    <i class="fa-solid fa-arrow-left text-2xs mr-1"></i> Format Desain
                                </button>
                                <button type="button" @click="activeTab = 'experience'" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition cursor-pointer">
                                    <span>Lanjut Riwayat Kerja</span> <i class="fa-solid fa-arrow-right text-2xs ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- TAB 3: PENGALAMAN KERJA & ORGANISASI -->
                        <div x-show="activeTab === 'experience'" class="space-y-6">
                            
                            <!-- Pengalaman Kerja -->
                            <div>
                                <div class="flex justify-between items-center pb-2.5 mb-3 border-b border-slate-100 dark:border-slate-700">
                                    <div>
                                        <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Pengalaman Kerja & Profesional</h3>
                                        <p class="text-2xs text-slate-500 dark:text-slate-400">Cantumkan posisi terbaru dengan pencapaian yang terukur.</p>
                                    </div>
                                    <button type="button" @click="addExperience()" class="px-3 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 font-bold text-2xs rounded-lg hover:bg-blue-100 transition cursor-pointer">
                                        + Tambah Posisi
                                    </button>
                                </div>

                                <template x-for="(exp, index) in form.experiences" :key="index">
                                    <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200 dark:border-slate-700 mb-3 space-y-2.5 relative">
                                        <button type="button" @click="removeExperience(index)" class="absolute top-3 right-3 text-slate-400 hover:text-rose-600 text-xs transition cursor-pointer" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pr-6">
                                            <div>
                                                <label class="block text-3xs font-semibold text-slate-500 mb-1">Posisi Pekerjaan</label>
                                                <input type="text" x-model="exp.title" placeholder="e.g. Frontend Engineer" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                            </div>
                                            <div>
                                                <label class="block text-3xs font-semibold text-slate-500 mb-1">Nama Perusahaan</label>
                                                <input type="text" x-model="exp.company" placeholder="e.g. PT Maju Bersama" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2.5">
                                            <div>
                                                <label class="block text-3xs font-semibold text-slate-500 mb-1">Mulai Bekerja</label>
                                                <input type="text" x-model="exp.start_date" placeholder="e.g. Jan 2022" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                            </div>
                                            <div>
                                                <label class="block text-3xs font-semibold text-slate-500 mb-1">Selesai Bekerja</label>
                                                <input type="text" x-model="exp.end_date" placeholder="e.g. Sekarang / Des 2023" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-3xs font-semibold text-slate-500 mb-1">Deskripsi Tugas & Pencapaian</label>
                                            <textarea x-model="exp.description" placeholder="• Mengembangkan fitur pembayaran yang meningkatkan konversi sebesar 15%..." rows="2" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100"></textarea>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Pengalaman Organisasi -->
                            <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                                <div class="flex justify-between items-center pb-2.5 mb-3 border-b border-slate-100 dark:border-slate-700">
                                    <div>
                                        <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Pengalaman Organisasi & Relawan</h3>
                                        <p class="text-2xs text-slate-500 dark:text-slate-400">Kegiatan kemahasiswaan, komunitas, atau kepanitiaan.</p>
                                    </div>
                                    <button type="button" @click="addOrganization()" class="px-3 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 font-bold text-2xs rounded-lg hover:bg-blue-100 transition cursor-pointer">
                                        + Tambah Organisasi
                                    </button>
                                </div>

                                <template x-for="(org, index) in form.organizations" :key="index">
                                    <div class="p-3.5 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200 dark:border-slate-700 mb-3 space-y-2 relative">
                                        <button type="button" @click="removeOrganization(index)" class="absolute top-2.5 right-2.5 text-slate-400 hover:text-rose-600 text-xs transition cursor-pointer" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>

                                        <div class="grid grid-cols-2 gap-2 pr-6">
                                            <input type="text" x-model="org.name" placeholder="Nama Organisasi / Komunitas" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                            <input type="text" x-model="org.position" placeholder="Jabatan / Peran" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <input type="text" x-model="org.period" placeholder="Periode (e.g. 2021 - 2022)" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                            <input type="text" x-model="org.description" placeholder="Uraian kegiatan / tanggung jawab" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Tab Navigation Buttons -->
                            <div class="pt-4 flex justify-between">
                                <button type="button" @click="activeTab = 'personal'" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold transition cursor-pointer">
                                    <i class="fa-solid fa-arrow-left text-2xs mr-1"></i> Kontak & Bio
                                </button>
                                <button type="button" @click="activeTab = 'education'" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition cursor-pointer">
                                    <span>Lanjut Pendidikan</span> <i class="fa-solid fa-arrow-right text-2xs ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- TAB 4: PENDIDIKAN -->
                        <div x-show="activeTab === 'education'" class="space-y-4">
                            <div class="flex justify-between items-center pb-2.5 mb-3 border-b border-slate-100 dark:border-slate-700">
                                <div>
                                    <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Pendidikan Formal</h3>
                                    <p class="text-2xs text-slate-500 dark:text-slate-400">Riwayat universitas atau sekolah terakhir.</p>
                                </div>
                                <button type="button" @click="addEducation()" class="px-3 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 font-bold text-2xs rounded-lg hover:bg-blue-100 transition cursor-pointer">
                                    + Tambah Pendidikan
                                </button>
                            </div>

                            <template x-for="(edu, index) in form.educations" :key="index">
                                <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200 dark:border-slate-700 mb-3 space-y-2.5 relative">
                                    <button type="button" @click="removeEducation(index)" class="absolute top-3 right-3 text-slate-400 hover:text-rose-600 text-xs transition cursor-pointer" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pr-6">
                                        <div>
                                            <label class="block text-3xs font-semibold text-slate-500 mb-1">Universitas / Sekolah</label>
                                            <input type="text" x-model="edu.institution" placeholder="e.g. Universitas Indonesia" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        </div>
                                        <div>
                                            <label class="block text-3xs font-semibold text-slate-500 mb-1">Program Studi / Jurusan</label>
                                            <input type="text" x-model="edu.field_of_study" placeholder="e.g. Teknik Informatika" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-2.5">
                                        <div>
                                            <label class="block text-3xs font-semibold text-slate-500 mb-1">Jenjang</label>
                                            <input type="text" x-model="edu.degree" placeholder="S1 / D3 / SMA" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        </div>
                                        <div>
                                            <label class="block text-3xs font-semibold text-slate-500 mb-1">Tahun Lulus</label>
                                            <input type="text" x-model="edu.end_year" placeholder="2024" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        </div>
                                        <div>
                                            <label class="block text-3xs font-semibold text-slate-500 mb-1">IPK / Nilai</label>
                                            <input type="text" x-model="edu.gpa" placeholder="3.85" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Tab Navigation Buttons -->
                            <div class="pt-4 flex justify-between">
                                <button type="button" @click="activeTab = 'experience'" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold transition cursor-pointer">
                                    <i class="fa-solid fa-arrow-left text-2xs mr-1"></i> Pengalaman
                                </button>
                                <button type="button" @click="activeTab = 'skills'" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition cursor-pointer">
                                    <span>Lanjut Keahlian & Lisensi</span> <i class="fa-solid fa-arrow-right text-2xs ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- TAB 5: KEAHLIAN, SERTIFIKASI & BAHASA -->
                        <div x-show="activeTab === 'skills'" class="space-y-6">
                            
                            <!-- Keahlian Utama -->
                            <div>
                                <label class="block text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-1">
                                    Daftar Keahlian Utama
                                </label>
                                <p class="text-2xs text-slate-500 dark:text-slate-400 mb-2">Pisahkan setiap keahlian dengan tanda koma (e.g. PHP, Laravel, MySQL, REST API, Git, Leadership)</p>
                                <input type="text" x-model="form.skillsInput" placeholder="Laravel, MySQL, JavaScript, Public Speaking" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Sertifikasi & Lisensi -->
                            <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                                <div class="flex justify-between items-center pb-2.5 mb-3 border-b border-slate-100 dark:border-slate-700">
                                    <div>
                                        <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Sertifikasi & Lisensi Profesi</h3>
                                        <p class="text-2xs text-slate-500 dark:text-slate-400">Bukti kredensial keahlian yang diakui.</p>
                                    </div>
                                    <button type="button" @click="addCertificate()" class="px-3 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 font-bold text-2xs rounded-lg hover:bg-blue-100 transition cursor-pointer">
                                        + Tambah Sertifikat
                                    </button>
                                </div>

                                <template x-for="(cert, index) in form.certificates" :key="index">
                                    <div class="p-3 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200 dark:border-slate-700 mb-2.5 space-y-2 relative">
                                        <button type="button" @click="removeCertificate(index)" class="absolute top-2.5 right-2.5 text-slate-400 hover:text-rose-600 text-xs transition cursor-pointer" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 pr-6">
                                            <div class="sm:col-span-2">
                                                <input type="text" x-model="cert.name" placeholder="Nama Sertifikasi" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                            </div>
                                            <div>
                                                <input type="text" x-model="cert.year" placeholder="Tahun (2024)" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                            </div>
                                        </div>
                                        <div>
                                            <input type="text" x-model="cert.issuer" placeholder="Lembaga Penerbit (e.g. BNSP / Google)" class="w-full text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Kemampuan Bahasa -->
                            <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                                <div class="flex justify-between items-center pb-2.5 mb-3 border-b border-slate-100 dark:border-slate-700">
                                    <div>
                                        <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Kemampuan Bahasa</h3>
                                    </div>
                                    <button type="button" @click="addLanguage()" class="px-3 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 font-bold text-2xs rounded-lg hover:bg-blue-100 transition cursor-pointer">
                                        + Tambah Bahasa
                                    </button>
                                </div>

                                <template x-for="(lang, index) in form.languages" :key="index">
                                    <div class="p-2.5 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200 dark:border-slate-700 mb-2 flex items-center gap-2 relative">
                                        <input type="text" x-model="lang.name" placeholder="Bahasa (e.g. Bahasa Inggris)" class="flex-1 text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        <input type="text" x-model="lang.proficiency" placeholder="Tingkat (e.g. Fasih / Profesional)" class="flex-1 text-2xs rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        <button type="button" @click="removeLanguage(index)" class="text-slate-400 hover:text-rose-600 text-xs p-1 cursor-pointer" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <!-- Action Bottom Toolbar -->
                            <div class="pt-5 border-t border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row gap-3">
                                <button type="button" @click="saveProfileData()" :disabled="isSaving" 
                                    class="flex-1 py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs shadow-xs transition flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                                    <i class="fa-solid" :class="isSaving ? 'fa-spinner fa-spin' : 'fa-floppy-disk'"></i>
                                    <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Semua ke Profil'"></span>
                                </button>

                                <button type="button" @click="downloadPdf()" 
                                    class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-file-pdf text-base"></i>
                                    <span>Unduh CV Format PDF (A4)</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Panel: Authentic A4 Paper Sheet Preview -->
                <div class="lg:col-span-6 sticky top-20" :class="mobileView === 'editor' ? 'hidden lg:block' : 'block'">
                    
                    <div class="bg-slate-200 dark:bg-slate-900/90 p-4 sm:p-5 rounded-3xl border border-slate-300 dark:border-slate-800 shadow-sm space-y-3">
                        
                        <!-- Top Preview Control Bar -->
                        <div class="flex items-center justify-between pb-2 border-b border-slate-300/80 dark:border-slate-800">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Pratinjau Kertas A4</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="text-3xs px-2.5 py-0.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-md uppercase border border-slate-200 dark:border-slate-700" x-text="template"></span>
                                <button type="button" @click="previewZoom = !previewZoom" class="text-2xs px-2 py-0.5 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-600 rounded-md border border-slate-200 dark:border-slate-700 transition cursor-pointer" :title="previewZoom ? 'Ukuran Normal' : 'Perbesar Teks'">
                                    <i class="fa-solid" :class="previewZoom ? 'fa-magnifying-glass-minus' : 'fa-magnifying-glass-plus'"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Real Authentic White Paper Sheet Container -->
                        <div class="overflow-y-auto max-h-[750px] pr-1 scrollbar-thin">
                            <div class="bg-white text-slate-900 p-7 sm:p-8 rounded-lg shadow-xl ring-1 ring-slate-900/10 min-h-[680px] space-y-4 transition-all"
                                :class="previewZoom ? 'text-xs' : 'text-3xs'"
                                :style="'border-top: 5px solid ' + (template === 'ats' ? '#111827' : accentColor)">
                                
                                <!-- 1. ATS STANDARD PREVIEW -->
                                <template x-if="template === 'ats'">
                                    <div class="font-sans text-slate-900 space-y-3">
                                        <div class="text-center border-b-2 border-slate-900 pb-2.5 mb-2.5">
                                            <h2 class="font-black uppercase text-slate-900 tracking-tight" :class="previewZoom ? 'text-lg' : 'text-sm'" x-text="form.name"></h2>
                                            <p class="font-bold text-slate-800 mt-0.5" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="form.position"></p>
                                            <p class="text-slate-600 mt-1" :class="previewZoom ? 'text-2xs' : 'text-4xs'" x-text="form.email + (form.phone ? ' • ' + form.phone : '') + (form.address ? ' • ' + form.address : '') + (form.linkedin ? ' • ' + form.linkedin : '') + (form.portfolio ? ' • ' + form.portfolio : '')"></p>
                                        </div>

                                        <template x-if="form.summary">
                                            <div>
                                                <h4 class="font-extrabold uppercase text-slate-900 border-b border-slate-900 pb-0.5 mb-1 tracking-wider" :class="previewZoom ? 'text-xs' : 'text-3xs'">RINGKASAN PROFESIONAL</h4>
                                                <p class="text-slate-800 leading-relaxed text-justify" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="form.summary"></p>
                                            </div>
                                        </template>

                                        <template x-if="form.experiences.length > 0">
                                            <div>
                                                <h4 class="font-extrabold uppercase text-slate-900 border-b border-slate-900 pb-0.5 mb-1.5 tracking-wider" :class="previewZoom ? 'text-xs' : 'text-3xs'">PENGALAMAN KERJA</h4>
                                                <template x-for="exp in form.experiences" :key="exp.title + exp.company">
                                                    <div class="mb-2">
                                                        <div class="flex justify-between items-baseline">
                                                            <span class="font-bold text-slate-900" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="exp.title + (exp.company ? ' — ' + exp.company : '')"></span>
                                                            <span class="text-slate-600 whitespace-nowrap" :class="previewZoom ? 'text-2xs' : 'text-4xs'" x-text="exp.start_date + ' – ' + exp.end_date"></span>
                                                        </div>
                                                        <p class="text-slate-700 mt-0.5 pl-2 text-justify" :class="previewZoom ? 'text-2xs' : 'text-3xs'" x-text="exp.description ? '• ' + exp.description : ''"></p>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>

                                        <template x-if="form.educations.length > 0">
                                            <div>
                                                <h4 class="font-extrabold uppercase text-slate-900 border-b border-slate-900 pb-0.5 mb-1.5 tracking-wider" :class="previewZoom ? 'text-xs' : 'text-3xs'">PENDIDIKAN</h4>
                                                <template x-for="edu in form.educations" :key="edu.institution">
                                                    <div class="mb-1.5">
                                                        <div class="flex justify-between items-baseline">
                                                            <span class="font-bold text-slate-900" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="edu.institution + ((edu.degree || edu.field_of_study) ? ' — ' + (edu.degree ? edu.degree + ' ' : '') + (edu.field_of_study || '') : '')"></span>
                                                            <span class="text-slate-600 whitespace-nowrap" :class="previewZoom ? 'text-2xs' : 'text-4xs'" x-text="(edu.start_year ? edu.start_year + ' – ' : '') + (edu.end_year || 'Selesai')"></span>
                                                        </div>
                                                        <template x-if="edu.gpa">
                                                            <div class="text-slate-600" :class="previewZoom ? 'text-2xs' : 'text-4xs'">IPK: <strong x-text="edu.gpa"></strong></div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>

                                        <template x-if="skillsArray.length > 0">
                                            <div>
                                                <h4 class="font-extrabold uppercase text-slate-900 border-b border-slate-900 pb-0.5 mb-1 tracking-wider" :class="previewZoom ? 'text-xs' : 'text-3xs'">KEAHLIAN & KOMPETENSI</h4>
                                                <p class="text-slate-800 leading-relaxed" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="skillsArray.join(' • ')"></p>
                                            </div>
                                        </template>

                                        <template x-if="form.organizations.length > 0">
                                            <div>
                                                <h4 class="font-extrabold uppercase text-slate-900 border-b border-slate-900 pb-0.5 mb-1 tracking-wider" :class="previewZoom ? 'text-xs' : 'text-3xs'">PENGALAMAN ORGANISASI</h4>
                                                <template x-for="org in form.organizations" :key="org.name">
                                                    <div class="mb-1">
                                                        <div class="flex justify-between items-baseline">
                                                            <span class="font-bold text-slate-900" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="org.position + ' — ' + org.name"></span>
                                                            <span class="text-slate-600 whitespace-nowrap" :class="previewZoom ? 'text-2xs' : 'text-4xs'" x-text="org.period"></span>
                                                        </div>
                                                        <p class="text-slate-700 pl-2 text-justify" :class="previewZoom ? 'text-2xs' : 'text-3xs'" x-text="org.description ? '• ' + org.description : ''"></p>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>

                                        <template x-if="form.certificates.length > 0">
                                            <div>
                                                <h4 class="font-extrabold uppercase text-slate-900 border-b border-slate-900 pb-0.5 mb-1 tracking-wider" :class="previewZoom ? 'text-xs' : 'text-3xs'">SERTIFIKASI & LISENSI</h4>
                                                <template x-for="cert in form.certificates" :key="cert.name">
                                                    <p class="text-slate-800" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="'• ' + cert.name + (cert.issuer ? ' — ' + cert.issuer : '') + (cert.year ? ' (' + cert.year + ')' : '')"></p>
                                                </template>
                                            </div>
                                        </template>

                                        <template x-if="form.languages.length > 0">
                                            <div>
                                                <h4 class="font-extrabold uppercase text-slate-900 border-b border-slate-900 pb-0.5 mb-1 tracking-wider" :class="previewZoom ? 'text-xs' : 'text-3xs'">KEMAMPUAN BAHASA</h4>
                                                <p class="text-slate-800" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="form.languages.map(l => l.name + (l.proficiency ? ' (' + l.proficiency + ')' : '')).join(' • ')"></p>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <!-- 2. CREATIVE 2 COLUMN PREVIEW (DENGAN FOTO PROFIL) -->
                                <template x-if="template === 'creative'">
                                    <div class="space-y-3">
                                        <!-- Header with Profile Photo -->
                                        <div class="flex items-center gap-3.5 pb-3 border-b border-slate-200">
                                            <template x-if="avatarUrl">
                                                <img :src="avatarUrl" alt="Foto Profil" class="w-12 h-12 rounded-full object-cover shadow-xs border-2 shrink-0" :style="'border-color: ' + accentColor">
                                            </template>
                                            <template x-if="!avatarUrl">
                                                <div class="w-11 h-11 rounded-full flex items-center justify-center text-white font-black text-sm shadow-xs shrink-0"
                                                     :style="'background-color: ' + accentColor"
                                                     x-text="form.name ? form.name.charAt(0) : 'U'">
                                                </div>
                                            </template>
                                            <div class="min-w-0">
                                                <h2 class="font-black text-slate-900 leading-tight truncate" :class="previewZoom ? 'text-base' : 'text-xs'" x-text="form.name"></h2>
                                                <p class="font-bold truncate" :class="previewZoom ? 'text-xs' : 'text-3xs'" :style="'color: ' + accentColor" x-text="form.position"></p>
                                                <p class="text-slate-500 truncate" :class="previewZoom ? 'text-2xs' : 'text-4xs'" x-text="form.email + (form.phone ? ' • ' + form.phone : '')"></p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-12 gap-3.5">
                                            <!-- Main Content Left -->
                                            <div class="col-span-8 space-y-3">
                                                <template x-if="form.summary">
                                                    <div>
                                                        <h4 class="font-black uppercase tracking-wider mb-0.5" :class="previewZoom ? 'text-xs' : 'text-3xs'" :style="'color: ' + accentColor">Tentang Saya</h4>
                                                        <p class="text-slate-700 leading-relaxed text-justify" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="form.summary"></p>
                                                    </div>
                                                </template>

                                                <template x-if="form.experiences.length > 0">
                                                    <div>
                                                        <h4 class="font-black uppercase tracking-wider mb-1" :class="previewZoom ? 'text-xs' : 'text-3xs'" :style="'color: ' + accentColor">Pengalaman Kerja</h4>
                                                        <template x-for="exp in form.experiences" :key="exp.title + exp.company">
                                                            <div class="mb-2">
                                                                <div class="font-bold text-slate-900" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="exp.title"></div>
                                                                <div class="text-slate-500 font-semibold" :class="previewZoom ? 'text-2xs' : 'text-4xs'" x-text="exp.company + ' | ' + exp.start_date + ' - ' + exp.end_date"></div>
                                                                <p class="text-slate-600 mt-0.5" :class="previewZoom ? 'text-2xs' : 'text-3xs'" x-text="exp.description"></p>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>

                                                <template x-if="form.organizations.length > 0">
                                                    <div>
                                                        <h4 class="font-black uppercase tracking-wider mb-1" :class="previewZoom ? 'text-xs' : 'text-3xs'" :style="'color: ' + accentColor">Organisasi</h4>
                                                        <template x-for="org in form.organizations" :key="org.name">
                                                            <div class="mb-1">
                                                                <div class="font-bold text-slate-900" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="org.position + ' — ' + org.name"></div>
                                                                <p class="text-slate-600" :class="previewZoom ? 'text-2xs' : 'text-3xs'" x-text="org.description"></p>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Sidebar Right -->
                                            <div class="col-span-4 bg-slate-50 p-2.5 rounded-xl space-y-2.5 border border-slate-100">
                                                <div>
                                                    <h4 class="font-black uppercase text-slate-700 tracking-wider mb-1" :class="previewZoom ? 'text-xs' : 'text-3xs'">Kontak</h4>
                                                    <div class="space-y-0.5 text-slate-600" :class="previewZoom ? 'text-2xs' : 'text-4xs'">
                                                        <div class="truncate" x-text="'📧 ' + form.email"></div>
                                                        <template x-if="form.phone"><div x-text="'📞 ' + form.phone"></div></template>
                                                        <template x-if="form.address"><div class="truncate" x-text="'📍 ' + form.address"></div></template>
                                                        <template x-if="form.linkedin"><div class="truncate" x-text="'🔗 ' + form.linkedin"></div></template>
                                                        <template x-if="form.portfolio"><div class="truncate" x-text="'🌐 ' + form.portfolio"></div></template>
                                                    </div>
                                                </div>

                                                <template x-if="skillsArray.length > 0">
                                                    <div>
                                                        <h4 class="font-black uppercase text-slate-700 tracking-wider mb-1" :class="previewZoom ? 'text-xs' : 'text-3xs'">Keahlian</h4>
                                                        <div class="space-y-1">
                                                            <template x-for="skill in skillsArray" :key="skill">
                                                                <div class="px-1.5 py-0.5 bg-white text-slate-800 font-bold rounded border border-slate-200 truncate shadow-2xs" :class="previewZoom ? 'text-2xs' : 'text-4xs'" x-text="skill"></div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>

                                                <template x-if="form.educations.length > 0">
                                                    <div>
                                                        <h4 class="font-black uppercase text-slate-700 tracking-wider mb-1" :class="previewZoom ? 'text-xs' : 'text-3xs'">Pendidikan</h4>
                                                        <template x-for="edu in form.educations" :key="edu.institution">
                                                            <div class="mb-1">
                                                                <div class="font-bold text-slate-900" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="edu.institution"></div>
                                                                <div class="text-slate-500" :class="previewZoom ? 'text-2xs' : 'text-4xs'" x-text="(edu.degree ? edu.degree + ' ' : '') + (edu.field_of_study || '') + (edu.end_year ? ' (' + edu.end_year + ')' : '')"></div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>

                                                <template x-if="form.languages.length > 0">
                                                    <div>
                                                        <h4 class="font-black uppercase text-slate-700 tracking-wider mb-1" :class="previewZoom ? 'text-xs' : 'text-3xs'">Bahasa</h4>
                                                        <template x-for="lang in form.languages" :key="lang.name">
                                                            <div class="text-slate-600" :class="previewZoom ? 'text-2xs' : 'text-4xs'" x-text="'• ' + lang.name + (lang.proficiency ? ' (' + lang.proficiency + ')' : '')"></div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- 3. MINIMALIST PREVIEW -->
                                <template x-if="template === 'minimalist'">
                                    <div class="space-y-3 font-sans">
                                        <div class="pb-2 border-b-2" :style="'border-color: ' + accentColor">
                                            <h2 class="font-black tracking-tight text-slate-900 uppercase" :class="previewZoom ? 'text-base' : 'text-sm'" x-text="form.name"></h2>
                                            <p class="font-semibold text-slate-500 uppercase tracking-widest" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="form.position"></p>
                                            <div class="mt-1.5 text-slate-600 bg-slate-50 p-2 rounded border-l-2" :class="previewZoom ? 'text-2xs' : 'text-3xs'" :style="'border-color: ' + accentColor">
                                                <span x-text="'Email: ' + form.email"></span>
                                                <span x-text="form.phone ? ' | HP: ' + form.phone : ''"></span>
                                                <span x-text="form.address ? ' | Lokasi: ' + form.address : ''"></span>
                                                <span x-text="form.linkedin ? ' | LinkedIn: ' + form.linkedin : ''"></span>
                                            </div>
                                        </div>

                                        <template x-if="form.summary">
                                            <div>
                                                <h4 class="font-black uppercase tracking-widest text-slate-900 mb-0.5 border-b pb-0.5" :class="previewZoom ? 'text-xs' : 'text-3xs'">Ringkasan Eksekutif</h4>
                                                <p class="text-slate-700 leading-relaxed" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="form.summary"></p>
                                            </div>
                                        </template>

                                        <template x-if="form.experiences.length > 0">
                                            <div>
                                                <h4 class="font-black uppercase tracking-widest text-slate-900 mb-1 border-b pb-0.5" :class="previewZoom ? 'text-xs' : 'text-3xs'">Pengalaman Kerja</h4>
                                                <template x-for="exp in form.experiences" :key="exp.title + exp.company">
                                                    <div class="mb-1.5">
                                                        <div class="flex justify-between font-bold text-slate-900" :class="previewZoom ? 'text-xs' : 'text-3xs'">
                                                            <span x-text="exp.title + (exp.company ? ' | ' + exp.company : '')"></span>
                                                            <span class="text-slate-400" :class="previewZoom ? 'text-2xs' : 'text-4xs'" x-text="exp.start_date + ' - ' + exp.end_date"></span>
                                                        </div>
                                                        <p class="text-slate-600 mt-0.5" :class="previewZoom ? 'text-2xs' : 'text-3xs'" x-text="exp.description"></p>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>

                                        <template x-if="form.educations.length > 0">
                                            <div>
                                                <h4 class="font-black uppercase tracking-widest text-slate-900 mb-1 border-b pb-0.5" :class="previewZoom ? 'text-xs' : 'text-3xs'">Pendidikan</h4>
                                                <template x-for="edu in form.educations" :key="edu.institution">
                                                    <div class="mb-1">
                                                        <div class="flex justify-between font-bold text-slate-900" :class="previewZoom ? 'text-xs' : 'text-3xs'">
                                                            <span x-text="edu.institution + ((edu.degree || edu.field_of_study) ? ' — ' + (edu.degree ? edu.degree + ' ' : '') + (edu.field_of_study || '') : '')"></span>
                                                            <span class="text-slate-400" :class="previewZoom ? 'text-2xs' : 'text-4xs'" x-text="(edu.start_year ? edu.start_year + ' - ' : '') + (edu.end_year || 'Selesai')"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>

                                        <template x-if="skillsArray.length > 0">
                                            <div>
                                                <h4 class="font-black uppercase tracking-widest text-slate-900 mb-1 border-b pb-0.5" :class="previewZoom ? 'text-xs' : 'text-3xs'">Keahlian</h4>
                                                <p class="text-slate-700 leading-relaxed" :class="previewZoom ? 'text-xs' : 'text-3xs'" x-text="skillsArray.join(' • ')"></p>
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
    </div>
</x-app-layout>
