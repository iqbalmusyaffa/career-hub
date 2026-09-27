<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-slate-900 dark:text-white leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-file-contract text-emerald-600 dark:text-emerald-400"></i> Buat Surat Penawaran Kerja (Offer Letter)
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Kandidat: <strong class="text-slate-800 dark:text-slate-200">{{ $application->user->name }}</strong> • 
                    Posisi: <span class="font-bold text-blue-600 dark:text-blue-400">{{ $application->job->title }}</span> • 
                    Perusahaan: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $application->job->company_name }}</span>
                </p>
            </div>
            <a href="{{ route('admin.applications.show', $application->id) }}" class="bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold py-2 px-4 rounded-xl text-xs transition border border-slate-200 dark:border-slate-700 shadow-2xs flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Detail Pelamar</span>
            </a>
        </div>
    </x-slot>

    @php
        // Detect if job is internship / magang
        $isInternship = Str::contains(strtolower($application->job->work_type ?? ''), ['intern', 'magang']) || Str::contains(strtolower($application->job->title ?? ''), ['intern', 'magang']);
        
        // Parse benefits from Job
        $rawJobBenefits = $application->job->benefits ?? '';
        $jobBenefitsList = array_values(array_filter(array_map('trim', explode(',', $rawJobBenefits))));
        
        // Parse benefits from Company Profile
        $companyProfile = $application->job->companyProfile ?? null;
        $compBenefits = $companyProfile && is_array($companyProfile->benefits) ? $companyProfile->benefits : [];
        
        // Standard presets
        $internshipPreset = [
            'Uang Saku Bulanan (Stipend) Kompetitif',
            'Sertifikat Resmi Selesai Program Magang',
            'Mentoring & Bimbingan Teknis 1-on-1',
            'Peluang Konversi Menjadi Karyawan Tetap (Fast-Track Hiring)',
            'Akses Penuh Fasilitas Kantor & Jaringan Industri',
        ];

        $fulltimePreset = [
            'BPJS Kesehatan & BPJS Ketenagakerjaan',
            'Tunjangan Hari Raya (THR) 1x Gaji Pokok',
            'Asuransi Rawat Inap & Jalan Swasta',
            'Laptop Kerja Perusahaan',
            'Cuti Tahunan 12+ Hari Kerja',
            'Bonus Kinerja Tahunan & Insentif',
        ];

        $remotePreset = [
            'Jam Kerja Fleksibel & 100% Work from Anywhere (WFA)',
            'Tunjangan Internet, Listrik & Pulsa Bulanan',
            'Budget Co-working Space & Home Office Setup',
            'BPJS Kesehatan & Ketenagakerjaan',
            'Budget Pelatihan & Sertifikasi Profesional',
        ];

        // Determine Default Auto-Prefill Benefits
        if (!empty($jobBenefitsList)) {
            $defaultBenefitsList = $jobBenefitsList;
        } elseif (!empty($compBenefits)) {
            $defaultBenefitsList = $compBenefits;
        } else {
            $defaultBenefitsList = $isInternship ? $internshipPreset : $fulltimePreset;
        }
        
        $initialBenefitsText = old('benefits_summary');
        if ($initialBenefitsText === null && !empty($defaultBenefitsList)) {
            $formattedItems = array_map(function($item) {
                return '• ' . ltrim($item, '•- ');
            }, $defaultBenefitsList);
            $initialBenefitsText = implode("\n", $formattedItems);
        }

        // Standard Common Quick Pills
        $commonPills = [
            'BPJS Kesehatan & Ketenagakerjaan',
            'Tunjangan Hari Raya (THR) 1x Gaji',
            'Asuransi Rawat Inap & Jalan Swasta',
            'Laptop Kerja Perusahaan',
            'Jam Kerja Fleksibel / Hybrid',
            'Tunjangan Komunikasi & Pulsa',
            'Bonus Kinerja Tahunan',
            'Makan Siang & Snack Gratis',
            'Cuti Tahunan 12+ Hari',
            'Budget Pelatihan & Sertifikasi',
        ];
    @endphp

    <div class="py-8 bg-slate-50/70 dark:bg-slate-950/60 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.applications.offer-letter.store', $application->id) }}" 
                  x-data="offerLetterForm(
                      @js($initialBenefitsText ?? ''), 
                      @js($jobBenefitsList), 
                      @js($compBenefits),
                      @js($internshipPreset),
                      @js($fulltimePreset),
                      @js($remotePreset)
                  )"
                  class="bg-white dark:bg-slate-900 rounded-3xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-9 space-y-6">
                @csrf

                <!-- Header Form Banner -->
                <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
                    <h3 class="font-bold text-base sm:text-lg text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-pen-ruler text-blue-600 dark:text-blue-400"></i> Form Kompensasi & Ketentuan Pekerjaan
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Dokumen Surat Penawaran Kerja (Offer Letter) akan di-generate otomatis dalam format PDF resmi.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    <div>
                        <x-input-label for="offered_salary" :value="__('Gaji / Uang Saku Nominal Yang Ditawarkan')" class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1" />
                        <div class="relative">
                            <input type="text" id="offered_salary" name="offered_salary" value="{{ old('offered_salary', $application->job->salary) }}" placeholder="Contoh: 12.000.000 atau Sesuai UMK" required class="w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-xs sm:text-sm font-bold text-emerald-700 dark:text-emerald-400 py-2.5 px-3">
                        </div>
                        <p class="text-3xs text-slate-400 mt-1">Otomatis terisi dari acuan lowongan pekerjaan.</p>
                    </div>

                    <div>
                        <x-input-label for="start_date" :value="__('Tanggal Mulai Bekerja (Start Date)')" class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1" />
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $application->job->start_date ? \Carbon\Carbon::parse($application->job->start_date)->format('Y-m-d') : date('Y-m-d', strtotime('+14 days'))) }}" required class="w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm py-2.5 px-3">
                        <p class="text-3xs text-slate-400 mt-1">Estimasi hari pertama onboarding kandidat.</p>
                    </div>

                    <div>
                        <x-input-label for="expiration_date" :value="__('Batas Waktu Konfirmasi Kandidat (Deadline Response)')" class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1" />
                        <input type="date" id="expiration_date" name="expiration_date" value="{{ old('expiration_date', date('Y-m-d', strtotime('+7 days'))) }}" class="w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm py-2.5 px-3">
                        <p class="text-3xs text-slate-400 mt-1">Masa berlaku surat penawaran (default: 7 hari).</p>
                    </div>

                    <div>
                        <x-input-label for="work_location" :value="__('Lokasi Penempatan Kerja')" class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1" />
                        <input type="text" id="work_location" name="work_location" value="{{ old('work_location', $application->job->location) }}" placeholder="Contoh: Jakarta Selatan (Hybrid)" class="w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm py-2.5 px-3">
                        <p class="text-3xs text-slate-400 mt-1">Kantor penempatan atau sistem kerja (WFO/Hybrid/Remote).</p>
                    </div>

                    <!-- SMART BENEFITS SECTION WITH AUTO-PREFILL & QUICK PRESETS & PILLS -->
                    <div class="md:col-span-2 space-y-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <x-input-label for="benefits_summary" :value="__('Ringkasan Fasilitas & Benefit (Tunjangan, BPJS, Asuransi, THR)')" class="text-xs font-bold text-slate-800 dark:text-slate-200" />
                                <p class="text-3xs text-slate-500 dark:text-slate-400">Otomatis terisi dari lowongan / template standar, dan dapat Anda sesuaikan bebas.</p>
                            </div>

                            <!-- Action Quick Buttons & Presets -->
                            <div class="flex items-center gap-1.5 flex-wrap shrink-0">
                                <template x-if="jobBenefits.length > 0">
                                    <button type="button" @click="loadFromJob()" class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-lg text-3xs font-bold transition border border-blue-200 dark:border-blue-800 flex items-center gap-1 cursor-pointer" title="Muat daftar benefit dari lowongan ini">
                                        <i class="fa-solid fa-briefcase text-[10px]"></i>
                                        <span>Dari Lowongan</span>
                                    </button>
                                </template>

                                <template x-if="compBenefits.length > 0">
                                    <button type="button" @click="loadFromCompany()" class="px-2.5 py-1 bg-purple-50 dark:bg-purple-950/60 hover:bg-purple-100 dark:hover:bg-purple-900 text-purple-700 dark:text-purple-300 rounded-lg text-3xs font-bold transition border border-purple-200 dark:border-purple-800 flex items-center gap-1 cursor-pointer" title="Muat daftar benefit master profil perusahaan">
                                        <i class="fa-solid fa-building text-[10px]"></i>
                                        <span>Dari Profil Perusahaan</span>
                                    </button>
                                </template>

                                <button type="button" @click="formatAsBullets()" class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-3xs font-bold transition border border-slate-200 dark:border-slate-700 flex items-center gap-1 cursor-pointer" title="Rapikan teks menjadi bullet point otomatis">
                                    <i class="fa-solid fa-list-ul text-[10px]"></i>
                                    <span>Format Poin (•)</span>
                                </button>
                            </div>
                        </div>

                        <!-- 1-CLICK TEMPLATE PRESETS BAR -->
                        <div class="flex flex-wrap items-center gap-2 p-2.5 bg-blue-50/50 dark:bg-blue-950/30 rounded-2xl border border-blue-100 dark:border-blue-900/60 text-xs">
                            <span class="text-3xs font-bold uppercase tracking-wider text-blue-800 dark:text-blue-300 flex items-center gap-1 shrink-0">
                                <i class="fa-solid fa-layer-group text-blue-600 dark:text-blue-400"></i> Pasang Preset Template:
                            </span>
                            <div class="flex flex-wrap items-center gap-1.5">
                                <button type="button" @click="loadPreset('fulltime')" class="px-2.5 py-1 bg-white dark:bg-slate-800 hover:bg-blue-100 dark:hover:bg-blue-900/70 text-slate-800 dark:text-slate-200 text-3xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 transition cursor-pointer flex items-center gap-1 shadow-2xs">
                                    <span>💼 Karyawan Full-Time</span>
                                </button>
                                <button type="button" @click="loadPreset('internship')" class="px-2.5 py-1 bg-white dark:bg-slate-800 hover:bg-blue-100 dark:hover:bg-blue-900/70 text-slate-800 dark:text-slate-200 text-3xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 transition cursor-pointer flex items-center gap-1 shadow-2xs">
                                    <span>🎓 Program Magang (Intern)</span>
                                </button>
                                <button type="button" @click="loadPreset('remote')" class="px-2.5 py-1 bg-white dark:bg-slate-800 hover:bg-blue-100 dark:hover:bg-blue-900/70 text-slate-800 dark:text-slate-200 text-3xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 transition cursor-pointer flex items-center gap-1 shadow-2xs">
                                    <span>🌐 Remote / WFA</span>
                                </button>
                            </div>
                        </div>

                        <!-- Quick Insertion Chips / Pills -->
                        <div class="p-3 bg-slate-50/80 dark:bg-slate-950/50 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-2">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                                <i class="fa-solid fa-wand-magic-sparkles text-amber-500"></i>
                                <span>Klik untuk menambah / mencoret item benefit secara cepat:</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-1.5">
                                @foreach($commonPills as $pill)
                                    <button type="button" 
                                            @click="togglePill('{{ addslashes($pill) }}')"
                                            :class="hasPill('{{ addslashes($pill) }}') ? 'bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 border-emerald-300 dark:border-emerald-700 font-bold' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-slate-300'"
                                            class="px-2.5 py-1 rounded-lg text-3xs border transition flex items-center gap-1 cursor-pointer select-none">
                                        <span x-text="hasPill('{{ addslashes($pill) }}') ? '✓' : '+'"></span>
                                        <span>{{ $pill }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="relative">
                            <textarea id="benefits_summary" name="benefits_summary" x-model="summaryText" rows="5" placeholder="Contoh:&#10;• Tunjangan Kesehatan BPJS + Asuransi Rawat Inap&#10;• THR 1x Gaji Pokok&#10;• Laptop Perusahaan (MacBook Pro)&#10;• Jam Kerja Fleksibel Hybrid (2 Hari WFH)" class="w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm p-3 font-medium leading-relaxed"></textarea>
                        </div>

                        <!-- Checkbox Simpan ke Profil Perusahaan -->
                        <div class="p-3 bg-slate-50 dark:bg-slate-950/40 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-2.5 text-xs select-none">
                            <input type="checkbox" id="save_to_company_profile" name="save_to_company_profile" value="1" class="rounded text-blue-600 focus:ring-blue-500 border-slate-300 dark:border-slate-600 mt-0.5 cursor-pointer">
                            <label for="save_to_company_profile" class="cursor-pointer">
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">Simpan juga daftar benefit ini ke Profil Master Perusahaan</span>
                                <span class="text-3xs text-slate-500 dark:text-slate-400 block mt-0.5">Jika dicentang, benefit ini otomatis menjadi rujukan baku profil perusahaan untuk pembuatan lowongan dan offer letter berikutnya.</span>
                            </label>
                        </div>
                    </div>

                    <div class="md:col-span-2 space-y-1.5">
                        <x-input-label for="additional_notes" :value="__('Catatan Tambahan & Persyaratan Dokumen Orientasi (Opsional)')" class="text-xs font-semibold text-slate-700 dark:text-slate-300" />
                        <textarea id="additional_notes" name="additional_notes" rows="3" placeholder="Contoh: Harap membawa dokumen fisik SKCK asli, fotokopi KTP, dan Ijazah saat hari pertama orientasi..." class="w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm p-3">{{ old('additional_notes') }}</textarea>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-3 border-t border-slate-100 dark:border-slate-800 pt-6">
                    <a href="{{ route('admin.applications.show', $application->id) }}" class="w-full sm:w-auto bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold py-2.5 px-5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs transition text-center">
                        Batal
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-xs transition text-xs flex items-center justify-center gap-2 cursor-pointer border border-emerald-600">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span>Generate & Kirim Offer Letter PDF</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function offerLetterForm(initialText, jobBenefits, compBenefits, internshipPreset, fulltimePreset, remotePreset) {
            return {
                summaryText: initialText || '',
                jobBenefits: jobBenefits || [],
                compBenefits: compBenefits || [],
                internshipPreset: internshipPreset || [],
                fulltimePreset: fulltimePreset || [],
                remotePreset: remotePreset || [],
                
                loadFromJob() {
                    if (!this.jobBenefits || this.jobBenefits.length === 0) return;
                    this.summaryText = this.jobBenefits.map(function(item) {
                        return '• ' + item.replace(/^[•\-\s]+/, '');
                    }).join("\n");
                },
                
                loadFromCompany() {
                    if (!this.compBenefits || this.compBenefits.length === 0) return;
                    this.summaryText = this.compBenefits.map(function(item) {
                        return '• ' + item.replace(/^[•\-\s]+/, '');
                    }).join("\n");
                },

                loadPreset(type) {
                    var list = [];
                    if (type === 'internship') list = this.internshipPreset;
                    else if (type === 'remote') list = this.remotePreset;
                    else list = this.fulltimePreset;

                    if (list.length > 0) {
                        this.summaryText = list.map(function(item) {
                            return '• ' + item.replace(/^[•\-\s]+/, '');
                        }).join("\n");
                    }
                },
                
                formatAsBullets() {
                    if (!this.summaryText.trim()) return;
                    var lines = this.summaryText.split("\n");
                    var formatted = [];
                    lines.forEach(function(line) {
                        var cleaned = line.trim();
                        if (cleaned) {
                            cleaned = cleaned.replace(/^[•\-\*\d\.\s]+/, '').trim();
                            if (cleaned) {
                                formatted.push('• ' + cleaned);
                            }
                        }
                    });
                    this.summaryText = formatted.join("\n");
                },
                
                hasPill(pillName) {
                    var lowerText = (this.summaryText || '').toLowerCase();
                    return lowerText.includes(pillName.toLowerCase());
                },
                
                togglePill(pillName) {
                    var lines = (this.summaryText || '').split("\n").filter(function(l) { return l.trim().length > 0; });
                    var exists = false;
                    var newLines = [];
                    
                    lines.forEach(function(l) {
                        var textWithoutBullet = l.replace(/^[•\-\*\d\.\s]+/, '').trim();
                        if (textWithoutBullet.toLowerCase() === pillName.toLowerCase()) {
                            exists = true; // remove it
                        } else {
                            newLines.push(l);
                        }
                    });
                    
                    if (!exists) {
                        newLines.push('• ' + pillName);
                    }
                    
                    this.summaryText = newLines.join("\n");
                }
            };
        }
    </script>
</x-app-layout>
