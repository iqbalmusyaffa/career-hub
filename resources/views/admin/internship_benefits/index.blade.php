<x-app-layout>
    <div class="py-8" x-data="{
        activeTab: 'interns',
        assignModalOpen: false,
        createBenefitModalOpen: false,
        editBenefitModalOpen: false,
        selectedIntern: null,
        selectedBenefit: null,
        userForm: {
            user_id: '',
            user_name: '',
            internship_benefit_id: '',
            policy_number: '',
            status: 'active',
            custom_nominal: '',
            notes: '',
            start_date: '{{ now()->format('Y-m-d') }}',
            end_date: ''
        },
        openAssignModal(intern, benefitId = '', policyNo = '', status = 'active', nominal = '', notes = '') {
            this.selectedIntern = intern;
            this.userForm.user_id = intern.id;
            this.userForm.user_name = intern.name;
            this.userForm.internship_benefit_id = benefitId;
            this.userForm.policy_number = policyNo;
            this.userForm.status = status;
            this.userForm.custom_nominal = nominal;
            this.userForm.notes = notes;
            this.assignModalOpen = true;
        },
        openEditBenefitModal(benefit) {
            this.selectedBenefit = benefit;
            this.editBenefitModalOpen = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb & Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Admin</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">Program Magang</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Fasilitas & Jaminan BPJS</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fa-solid fa-shield-heart"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                                Fasilitas & Jaminan BPJS Magang
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                                Perlindungan wajib BPJS Ketenagakerjaan (JKK/JKM) & Kesehatan, serta katalog fasilitas penunjang magang transparan.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <button type="button" @click="createBenefitModalOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs hover:shadow-md transition">
                        <i class="fa-solid fa-plus-circle"></i>
                        <span>Tambah Katalog Fasilitas</span>
                    </button>
                    <a href="{{ route('admin.internship-stipends.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 shadow-xs transition">
                        <i class="fa-solid fa-money-bill-wave text-emerald-600"></i>
                        <span>Slip & Payroll</span>
                    </a>
                </div>
            </div>

            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 flex items-start gap-3 shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 mt-0.5 text-base shrink-0"></i>
                    <div class="text-xs sm:text-sm font-medium">{{ session('success') }}</div>
                </div>
            @endif
            @if(session('warning'))
                <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 flex items-start gap-3 shadow-xs">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 dark:text-amber-400 mt-0.5 text-base shrink-0"></i>
                    <div class="text-xs sm:text-sm font-medium">{{ session('warning') }}</div>
                </div>
            @endif

            <!-- KPI & Kepatuhan Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Active Interns -->
                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Anak Magang</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm font-semibold">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalActiveInterns }}</span>
                        <span class="text-xs text-slate-500">peserta aktif</span>
                    </div>
                    <div class="mt-2 text-[11px] text-slate-500 flex items-center gap-1">
                        <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                        <span>Data tersinkronisasi otomatis</span>
                    </div>
                </div>

                <!-- BPJS Ketenagakerjaan (Mandatory) -->
                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-emerald-200 dark:border-emerald-800/80 shadow-xs bg-gradient-to-br from-white to-emerald-50/40 dark:from-slate-800 dark:to-emerald-950/20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">BPJS Ketenagakerjaan</span>
                            <span class="px-1.5 py-0.5 text-[9px] font-black uppercase rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">WAJIB</span>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-2xl font-black text-emerald-700 dark:text-emerald-400">{{ $coveredBpjsTkCount }}</span>
                        <span class="text-xs text-slate-500 font-medium">/ {{ $totalActiveInterns }} Terlindungi</span>
                    </div>
                    <div class="mt-2 flex items-center gap-1 text-[11px] text-emerald-700 dark:text-emerald-400 font-medium">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>JKK & JKM (Permenaker No. 6/2020)</span>
                    </div>
                </div>

                <!-- BPJS Kesehatan (Mandatory) -->
                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-emerald-200 dark:border-emerald-800/80 shadow-xs bg-gradient-to-br from-white to-teal-50/40 dark:from-slate-800 dark:to-teal-950/20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-teal-700 dark:text-teal-400">BPJS Kesehatan</span>
                            <span class="px-1.5 py-0.5 text-[9px] font-black uppercase rounded bg-teal-100 text-teal-800 dark:bg-teal-900/60 dark:text-teal-300">WAJIB</span>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-teal-100 dark:bg-teal-900/60 text-teal-700 dark:text-teal-300 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-heart-pulse"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-2xl font-black text-teal-700 dark:text-teal-400">{{ $coveredBpjsKesCount }}</span>
                        <span class="text-xs text-slate-500 font-medium">/ {{ $totalActiveInterns }} Terdaftar</span>
                    </div>
                    <div class="mt-2 flex items-center gap-1 text-[11px] text-teal-700 dark:text-teal-400 font-medium">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>JKN-KIS tervalidasi aktif</span>
                    </div>
                </div>

                <!-- Fasilitas Non-Wajib (Transparansi) -->
                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-indigo-200 dark:border-indigo-800/80 shadow-xs bg-gradient-to-br from-white to-indigo-50/40 dark:from-slate-800 dark:to-indigo-950/20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-400">Fasilitas Tambahan</span>
                            <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase rounded bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">OPSIONAL</span>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-gift"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-2xl font-black text-indigo-700 dark:text-indigo-400">{{ $totalOptionalFacilitiesGiven }}</span>
                        <span class="text-xs text-slate-500">alokasi fasilitas aktif</span>
                    </div>
                    <div class="mt-2 flex items-center gap-1 text-[11px] text-indigo-700 dark:text-indigo-400 font-medium">
                        <i class="fa-solid fa-info-circle"></i>
                        <span>Transport, Pantry, Laptop, Mentoring</span>
                    </div>
                </div>
            </div>

            <!-- Law & Transparency Info Banner -->
            <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-emerald-900 to-teal-900 text-white shadow-md relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-white/20 text-emerald-200 text-[10px] font-bold uppercase tracking-wider backdrop-blur-xs">
                            <i class="fa-solid fa-gavel"></i>
                            Regulasi Resmi Permenaker No. 6 Tahun 2020
                        </div>
                        <h3 class="text-base sm:text-lg font-bold">Perlindungan Jaminan Sosial Ketenagakerjaan Wajib Pemagang</h3>
                        <p class="text-xs sm:text-sm text-emerald-100/90 max-w-3xl leading-relaxed">
                            Penyelenggara pemagangan <strong>wajib</strong> mengikutsertakan peserta magang dalam program <strong>BPJS Ketenagakerjaan (JKK & JKM)</strong> dengan iuran ditanggung sepenuhnya oleh perusahaan. Fasilitas lain (transport, makan, laptop inventaris) dicantumkan secara transparan pada slip bukti pencairan sebagai kompensasi total magang.
                        </p>
                    </div>
                    <div class="shrink-0 flex items-center gap-2">
                        <span class="px-3 py-2 rounded-xl bg-white/10 text-xs font-semibold border border-white/20 backdrop-blur-xs">
                            <i class="fa-solid fa-circle-check text-emerald-300 mr-1.5"></i> Transparansi Kompensasi 100%
                        </span>
                    </div>
                </div>
                <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
            </div>

            <!-- Tab Navigation & Filters -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-200 dark:border-slate-700/80 px-5 pt-3 gap-3">
                    <div class="flex items-center gap-2 -mb-px">
                        <button type="button" @click="activeTab = 'interns'" :class="activeTab === 'interns' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'" class="inline-flex items-center gap-2 py-3 px-4 border-b-2 text-xs sm:text-sm transition">
                            <i class="fa-solid fa-users-line"></i>
                            <span>Peserta Magang & Alokasi Jaminan ({{ $interns->total() }})</span>
                        </button>
                        <button type="button" @click="activeTab = 'catalog'" :class="activeTab === 'catalog' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'" class="inline-flex items-center gap-2 py-3 px-4 border-b-2 text-xs sm:text-sm transition">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            <span>Katalog Master Fasilitas & BPJS ({{ $allBenefits->count() }})</span>
                        </button>
                    </div>

                    <!-- Search Intern Form (only shown in interns tab) -->
                    <div x-show="activeTab === 'interns'" class="pb-3 sm:pb-0">
                        <form method="GET" action="{{ route('admin.internship-benefits.index') }}" class="flex items-center gap-2">
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none w-48 sm:w-64">
                            </div>
                            @if(request('search'))
                                <a href="{{ route('admin.internship-benefits.index') }}" class="p-1.5 text-slate-400 hover:text-slate-600 text-xs">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- TAB 1: INTERNS LIST & ALLOCATED BENEFITS -->
                <div x-show="activeTab === 'interns'" class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead class="bg-slate-50 dark:bg-slate-900/60 text-slate-700 dark:text-slate-300 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th class="px-5 py-3.5">Peserta Magang</th>
                                    <th class="px-4 py-3.5">
                                        <div class="flex items-center gap-1.5 text-emerald-700 dark:text-emerald-400">
                                            <i class="fa-solid fa-shield-halved"></i>
                                            <span>BPJS Ketenagakerjaan (Wajib)</span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3.5">
                                        <div class="flex items-center gap-1.5 text-teal-700 dark:text-teal-400">
                                            <i class="fa-solid fa-heart-pulse"></i>
                                            <span>BPJS Kesehatan (Wajib)</span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3.5">Fasilitas Tambahan Aktif</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse($interns as $intern)
                                    @php
                                        $userBenefits = $intern->internshipUserBenefits;
                                        $bpjsTkBenefit = $userBenefits->first(fn($b) => $b->benefit && $b->benefit->category === 'bpjs_tk');
                                        $bpjsKesBenefit = $userBenefits->first(fn($b) => $b->benefit && $b->benefit->category === 'bpjs_kes');
                                        $optionalUserBenefits = $userBenefits->filter(fn($b) => $b->benefit && !$b->benefit->is_mandatory && $b->status === 'active');
                                        $onboarding = $intern->candidateOnboarding;
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-750 transition">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                                                    {{ strtoupper(substr($intern->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                                        <span>{{ $intern->name }}</span>
                                                        @if($intern->candidateProfile?->nickname)
                                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-500">({{ $intern->candidateProfile->nickname }})</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-2 mt-0.5">
                                                        <span>{{ $intern->email }}</span>
                                                        <span>•</span>
                                                        <span>{{ $intern->candidateProfile?->educations[0]['institution'] ?? 'Peserta Magang' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- BPJS TK Cell -->
                                        <td class="px-4 py-4">
                                            @if($bpjsTkBenefit && $bpjsTkBenefit->status === 'active')
                                                <div class="inline-flex flex-col gap-0.5">
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                                        <i class="fa-solid fa-circle-check text-emerald-600"></i> Aktif Terlindungi
                                                    </span>
                                                    <div class="text-[11px] font-mono text-slate-700 dark:text-slate-300 font-semibold mt-0.5">
                                                        No: {{ $bpjsTkBenefit->policy_number ?: ($onboarding?->bpjs_ketenagakerjaan_number ?? '-') }}
                                                    </div>
                                                    <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">Iuran 100% Ditanggung Kantor</span>
                                                </div>
                                            @else
                                                <div class="inline-flex flex-col gap-0.5">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                                        <i class="fa-solid fa-triangle-exclamation"></i> Belum Didaftarkan
                                                    </span>
                                                    <span class="text-[10px] text-slate-400">Wajib didaftarkan segera</span>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- BPJS Kes Cell -->
                                        <td class="px-4 py-4">
                                            @if($bpjsKesBenefit && $bpjsKesBenefit->status === 'active')
                                                <div class="inline-flex flex-col gap-0.5">
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800 dark:bg-teal-950 dark:text-teal-300">
                                                        <i class="fa-solid fa-circle-check text-teal-600"></i> Aktif Terdaftar
                                                    </span>
                                                    <div class="text-[11px] font-mono text-slate-700 dark:text-slate-300 font-semibold mt-0.5">
                                                        No: {{ $bpjsKesBenefit->policy_number ?: ($onboarding?->bpjs_kesehatan_number ?? '-') }}
                                                    </div>
                                                    <span class="text-[10px] text-slate-500">JKN-KIS Tervalidasi</span>
                                                </div>
                                            @else
                                                <div class="inline-flex flex-col gap-0.5">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                                        <i class="fa-solid fa-triangle-exclamation"></i> Belum Terdaftar
                                                    </span>
                                                    <span class="text-[10px] text-slate-400">Verifikasi kartu peserta</span>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Optional Facilities Cell -->
                                        <td class="px-4 py-4">
                                            @if($optionalUserBenefits->count() > 0)
                                                <div class="flex flex-wrap gap-1.5 max-w-xs">
                                                    @foreach($optionalUserBenefits as $ub)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600" title="{{ $ub->benefit?->name }}: {{ $ub->notes }}">
                                                            <i class="{{ $ub->benefit?->icon ?? 'fa-solid fa-check' }} text-[9px] text-indigo-500"></i>
                                                            <span>{{ Str::limit($ub->benefit?->name, 18) }}</span>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-[11px] text-slate-400 italic">Belum ada fasilitas tambahan</span>
                                            @endif
                                        </td>

                                        <!-- Action Buttons -->
                                        <td class="px-5 py-4 text-right">
                                            <button type="button" @click="openAssignModal({{ json_encode($intern) }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-900 border border-emerald-200 dark:border-emerald-800 transition">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <span>Kelola Benefit</span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fa-solid fa-user-slash text-3xl mb-2 text-slate-300"></i>
                                                <span>Tidak ada data peserta magang yang ditemukan.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($interns->hasPages())
                        <div class="px-5 py-3 border-t border-slate-200 dark:border-slate-700">
                            {{ $interns->links() }}
                        </div>
                    @endif
                </div>

                <!-- TAB 2: MASTER BENEFIT CATALOG -->
                <div x-show="activeTab === 'catalog'" class="p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Master Fasilitas & Komponen Perlindungan Magang</h3>
                            <p class="text-xs text-slate-500">Katalog standar jaminan sosial wajib dan benefit tambahan yang otomatis tertuang pada slip uang saku magang.</p>
                        </div>
                        <button type="button" @click="createBenefitModalOpen = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition">
                            <i class="fa-solid fa-plus"></i> Tambah Master
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($allBenefits as $benefit)
                            <div class="p-4 rounded-2xl border {{ $benefit->is_mandatory ? 'border-emerald-200 dark:border-emerald-800 bg-emerald-50/20 dark:bg-emerald-950/20' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800' }} shadow-2xs hover:shadow-sm transition flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg {{ $benefit->is_mandatory ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300' : 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950 dark:text-indigo-400' }} flex items-center justify-center text-sm font-bold shrink-0">
                                                <i class="{{ $benefit->icon }}"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">{{ $benefit->name }}</h4>
                                                <span class="text-[10px] text-slate-500 font-medium">{{ $benefit->provider ?? 'Internal Perusahaan' }}</span>
                                            </div>
                                        </div>

                                        @if($benefit->is_mandatory)
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">
                                                WAJIB
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                                                OPSIONAL
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-2.5 line-clamp-2">
                                        {{ $benefit->description ?? 'Tidak ada deskripsi detail.' }}
                                    </p>

                                    <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between text-xs">
                                        <span class="text-slate-500">Tipe Cakupan:</span>
                                        <span class="font-semibold text-slate-800 dark:text-slate-200 capitalize">{{ str_replace('_', ' ', $benefit->coverage_type) }}</span>
                                    </div>

                                    @if($benefit->standard_nominal > 0)
                                        <div class="mt-1 flex items-center justify-between text-xs">
                                            <span class="text-slate-500">Nilai Standar:</span>
                                            <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($benefit->standard_nominal, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                                    <span class="text-[11px] text-slate-500">
                                        <i class="fa-solid fa-users text-slate-400 mr-1"></i>
                                        {{ $benefit->active_participants_count }} Peserta Aktif
                                    </span>

                                    <div class="flex items-center gap-1.5">
                                        <button type="button" @click="openEditBenefitModal({{ json_encode($benefit) }})" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition" title="Edit Katalog">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                        @if(!$benefit->is_mandatory)
                                            <form method="POST" action="{{ route('admin.internship-benefits.destroy', $benefit->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus katalog benefit ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition" title="Hapus Katalog">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        <!-- MODAL: KELOLA BENEFIT PESERTA (ASSIGN) -->
        <div x-show="assignModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="min-h-screen px-4 text-center flex items-center justify-center">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="assignModalOpen = false"></div>

                <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 relative z-10">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-sm font-bold">
                                <i class="fa-solid fa-shield-cat"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                Alokasi Fasilitas & Jaminan Peserta
                            </h3>
                        </div>
                        <button type="button" @click="assignModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-sm">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.internship-benefits.assign-user') }}" class="mt-4 space-y-4">
                        @csrf
                        <input type="hidden" name="user_id" :value="userForm.user_id">

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Peserta Magang</label>
                            <input type="text" :value="userForm.user_name" readonly class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Komponen Fasilitas / Jaminan <span class="text-rose-500">*</span></label>
                            <select name="internship_benefit_id" x-model="userForm.internship_benefit_id" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                                <option value="">-- Pilih Fasilitas / BPJS --</option>
                                <optgroup label="Jaminan Wajib (Regulasi)">
                                    @foreach($mandatoryBenefits as $mb)
                                        <option value="{{ $mb->id }}">{{ $mb->name }} (Wajib - {{ $mb->provider }})</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Fasilitas Tambahan Transparan (Opsional)">
                                    @foreach($optionalBenefits as $ob)
                                        <option value="{{ $ob->id }}">{{ $ob->name }} ({{ $ob->provider }})</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor Polis / Kartu / Inventaris</label>
                                <input type="text" name="policy_number" x-model="userForm.policy_number" placeholder="Contoh: 22094819204" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                                <span class="text-[10px] text-slate-400 mt-0.5 block">Sinkron otomatis ke profil onboarding</span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Status Kepesertaan <span class="text-rose-500">*</span></label>
                                <select name="status" x-model="userForm.status" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                                    <option value="active">Active (Aktif & Terlindungi)</option>
                                    <option value="pending">Pending (Dalam Proses)</option>
                                    <option value="inactive">Inactive (Non-aktif)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Keterangan / Catatan Fasilitas</label>
                            <textarea name="notes" x-model="userForm.notes" rows="2" placeholder="Contoh: Iuran disetor penuh perusahaan mitra. Unit laptop Lenovo ThinkPad T14 inventaris." class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-200 dark:border-slate-700">
                            <button type="button" @click="assignModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition">
                                Simpan Alokasi Benefit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL: TAMBAH MASTER BENEFIT -->
        <div x-show="createBenefitModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="min-h-screen px-4 text-center flex items-center justify-center">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="createBenefitModalOpen = false"></div>

                <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 relative z-10">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-sm font-bold">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                Tambah Master Fasilitas / BPJS
                            </h3>
                        </div>
                        <button type="button" @click="createBenefitModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-sm">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.internship-benefits.store') }}" class="mt-4 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Fasilitas / Program <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required placeholder="Contoh: BPJS Ketenagakerjaan (JKK & JKM)" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kategori <span class="text-rose-500">*</span></label>
                                <select name="category" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                                    <option value="bpjs_tk">BPJS Ketenagakerjaan</option>
                                    <option value="bpjs_kes">BPJS Kesehatan</option>
                                    <option value="transport">Tunjangan Transportasi</option>
                                    <option value="meal">Makan Siang & Pantry</option>
                                    <option value="equipment">Laptop & Perangkat Kerja</option>
                                    <option value="mentoring">Mentoring & Sertifikasi</option>
                                    <option value="wellness">Kesehatan / Wellness</option>
                                    <option value="allowance">Uang Saku / Allowance Tambahan</option>
                                    <option value="other">Fasilitas Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Status Kepatuhan <span class="text-rose-500">*</span></label>
                                <select name="is_mandatory" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                                    <option value="0">Fasilitas Tambahan (Opsional Kantor)</option>
                                    <option value="1">WAJIB (Sesuai Regulasi Magang)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Jenis Cakupan (Coverage) <span class="text-rose-500">*</span></label>
                                <select name="coverage_type" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                                    <option value="company_paid">100% Ditanggung Kantor (Rp 0 Beban Peserta)</option>
                                    <option value="cash_allowance">Tunjangan Tunai (Cash Allowance)</option>
                                    <option value="facility_access">Akses Fasilitas Gedung / Kantor</option>
                                    <option value="non_cash">Non-Tunai / Peminjaman Aset</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nominal Standar (IDR)</label>
                                <input type="number" name="standard_nominal" step="1000" min="0" placeholder="0" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Penyedia / Provider</label>
                            <input type="text" name="provider" placeholder="Contoh: BPJS Ketenagakerjaan RI / Internal Perusahaan" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Detail Fasilitas</label>
                            <textarea name="description" rows="2" placeholder="Jelaskan manfaat, cakupan, dan tujuan fasilitas bagi peserta magang..." class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-200 dark:border-slate-700">
                            <button type="button" @click="createBenefitModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition">
                                Simpan ke Katalog
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL: EDIT MASTER BENEFIT -->
        <div x-show="editBenefitModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="min-h-screen px-4 text-center flex items-center justify-center">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="editBenefitModalOpen = false"></div>

                <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 relative z-10" x-show="selectedBenefit">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 flex items-center justify-center text-sm font-bold">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                Edit Master Benefit
                            </h3>
                        </div>
                        <button type="button" @click="editBenefitModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-sm">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" :action="'/admin/internship-benefits/' + (selectedBenefit ? selectedBenefit.id : '')" class="mt-4 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Fasilitas / Program <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" :value="selectedBenefit ? selectedBenefit.name : ''" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kategori <span class="text-rose-500">*</span></label>
                                <select name="category" x-model="selectedBenefit.category" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                                    <option value="bpjs_tk">BPJS Ketenagakerjaan</option>
                                    <option value="bpjs_kes">BPJS Kesehatan</option>
                                    <option value="transport">Tunjangan Transportasi</option>
                                    <option value="meal">Makan Siang & Pantry</option>
                                    <option value="equipment">Laptop & Perangkat Kerja</option>
                                    <option value="mentoring">Mentoring & Sertifikasi</option>
                                    <option value="wellness">Kesehatan / Wellness</option>
                                    <option value="allowance">Uang Saku / Allowance Tambahan</option>
                                    <option value="other">Fasilitas Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Status Kepatuhan <span class="text-rose-500">*</span></label>
                                <select name="is_mandatory" x-model="selectedBenefit.is_mandatory" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                                    <option value="0">Fasilitas Tambahan (Opsional Kantor)</option>
                                    <option value="1">WAJIB (Sesuai Regulasi Magang)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Jenis Cakupan (Coverage) <span class="text-rose-500">*</span></label>
                                <select name="coverage_type" x-model="selectedBenefit.coverage_type" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                                    <option value="company_paid">100% Ditanggung Kantor (Rp 0 Beban Peserta)</option>
                                    <option value="cash_allowance">Tunjangan Tunai (Cash Allowance)</option>
                                    <option value="facility_access">Akses Fasilitas Gedung / Kantor</option>
                                    <option value="non_cash">Non-Tunai / Peminjaman Aset</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nominal Standar (IDR)</label>
                                <input type="number" name="standard_nominal" :value="selectedBenefit ? selectedBenefit.standard_nominal : 0" step="1000" min="0" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Penyedia / Provider</label>
                            <input type="text" name="provider" :value="selectedBenefit ? selectedBenefit.provider : ''" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Detail Fasilitas</label>
                            <textarea name="description" x-model="selectedBenefit.description" rows="2" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-200 dark:border-slate-700">
                            <button type="button" @click="editBenefitModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition">
                                Perbarui Katalog
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
