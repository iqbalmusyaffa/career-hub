<x-public-layout>
    <div class="bg-slate-50/60 py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb Navigation -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 text-xs font-medium text-slate-500">
                    <li>
                        <a href="{{ route('jobs.index') }}" class="hover:text-blue-600 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-briefcase text-slate-400"></i> Lowongan Kerja
                        </a>
                    </li>
                    <li>
                        <span class="text-slate-300">/</span>
                    </li>
                    <li class="text-slate-800 font-semibold truncate max-w-xs md:max-w-md">
                        {{ $job->title }}
                    </li>
                </ol>
            </nav>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-xs text-sm font-medium">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 shadow-xs text-sm font-medium">
                    <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Main Job Details Card -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden mb-12">
                
                <!-- Accent Banner Header -->
                <div class="h-1.5 bg-blue-600"></div>

                <div class="p-6 sm:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6 border-b border-slate-100 pb-6 mb-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-900 text-white font-bold text-xl flex items-center justify-center border border-slate-800 shrink-0">
                                {{ strtoupper(substr($job->title, 0, 1)) }}
                            </div>

                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-2.5">
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-md border border-slate-200">
                                        {{ $job->division }}
                                    </span>
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-md border border-slate-200">
                                        {{ ucfirst($job->work_type) }}
                                    </span>

                                    @if($job->batch)
                                        <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs font-semibold rounded-md flex items-center gap-1 shadow-2xs">
                                            <i class="fa-solid fa-layer-group text-xs text-indigo-600"></i> {{ $job->batch }}
                                        </span>
                                    @endif

                                    @if($job->duration)
                                        <span class="px-2.5 py-0.5 bg-sky-50 text-sky-700 border border-sky-200 text-xs font-semibold rounded-md flex items-center gap-1">
                                            <i class="fa-regular fa-clock text-xs text-sky-600"></i> {{ $job->duration }}
                                        </span>
                                    @endif

                                    @php $umkCheck = $job->umk_check; @endphp
                                    @if($umkCheck['has_umk'])
                                        @if($umkCheck['is_below'])
                                            <span class="px-2.5 py-0.5 bg-amber-50 text-amber-800 border border-amber-200 text-xs font-semibold rounded-md flex items-center gap-1" title="Gaji yang ditawarkan di bawah UMK 2026 Wilayah {{ $umkCheck['city_district'] }} ({{ $umkCheck['formatted_umk'] }})">
                                                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-xs"></i> Di Bawah UMK ({{ $umkCheck['formatted_umk'] }})
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold rounded-md flex items-center gap-1" title="Gaji yang ditawarkan memenuhi standar UMK 2026 Wilayah {{ $umkCheck['city_district'] }}">
                                                <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> Sesuai UMK ({{ $umkCheck['formatted_umk'] }})
                                            </span>
                                        @endif
                                    @endif

                                    <span class="text-xs text-slate-400 font-normal">
                                        Diposting {{ $job->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 leading-tight mb-3">{{ $job->title }}</h1>
                                
                                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-600 font-medium">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-location-dot text-slate-400 text-xs"></i>
                                        {{ $job->location }}
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-money-bill-wave text-slate-400 text-xs"></i>
                                        {{ $job->salary ?? 'Gaji Negosiasi' }}
                                    </div>
                                    @if($job->deadline)
                                        <div class="flex items-center gap-1.5 text-slate-600">
                                            <i class="fa-solid fa-clock text-slate-400 text-xs"></i>
                                            Batas Lamaran: {{ $job->deadline->format('d M Y') }}
                                        </div>
                                    @endif
                                </div>

                                @if(isset($umk) && $umk)
                                    <div class="mt-4 p-3 bg-slate-50 rounded-xl border border-slate-200/80 flex items-center justify-between flex-wrap gap-2 text-xs">
                                        <div class="flex items-center gap-2 text-slate-800 font-medium">
                                            <span class="px-2 py-0.5 rounded-md bg-slate-800 text-white text-[11px] font-semibold uppercase">UMK 2026</span>
                                            <span>Acuan Gaji Minimum {{ $umk->city_district }}: <strong class="text-slate-900 font-bold">{{ $umk->formatted_umk }} / bulan</strong></span>
                                        </div>
                                        <span class="text-[11px] text-slate-500 bg-white px-2.5 py-1 rounded-md border border-slate-200" title="{{ $umk->legal_decree }}">
                                            SK Gubernur Resmi
                                        </span>
                                    </div>
                                @endif

                                <!-- Quota Indicator -->
                                @if($job->quota)
                                    <div class="mt-4 p-2.5 bg-slate-50 border border-slate-200/80 rounded-xl inline-flex items-center gap-3 text-xs font-medium text-slate-700">
                                        <i class="fa-solid fa-users text-slate-400"></i>
                                        <span>Kuota Pelamar: <strong class="text-slate-900">{{ $job->applications()->count() }} / {{ $job->quota }}</strong></span>
                                        @if($job->isQuotaFull())
                                            <span class="px-2 py-0.5 bg-rose-600 text-white text-[11px] font-semibold rounded-md">Penuh</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-emerald-600 text-white text-[11px] font-semibold rounded-md">Tersisa {{ $job->remainingQuota() }} Slot</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Button Area -->
                        <div class="flex flex-col gap-3 w-full lg:w-auto shrink-0">
                            @if($job->isExpired() || $job->status == 'closed')
                                <div class="px-5 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-xl text-center border border-slate-200 text-xs">
                                    Lowongan Ditutup / Kadaluarsa
                                </div>
                            @elseif($job->isQuotaFull())
                                <div class="px-5 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-xl text-center border border-slate-200 text-xs">
                                    Kuota Pendaftaran Penuh
                                </div>
                            @else
                                @auth
                                    @if(auth()->user()->hasRole('Candidate'))
                                        @if($hasApplied)
                                            <div class="flex flex-col gap-2">
                                                <button disabled class="w-full lg:w-auto px-6 py-2.5 bg-slate-100 text-slate-600 font-semibold rounded-xl cursor-not-allowed flex items-center justify-center gap-2 text-xs border border-slate-200">
                                                    <i class="fa-solid fa-check text-emerald-600"></i>
                                                    Sudah Dilamar
                                                </button>

                                                @if($job->test && $job->test->is_active)
                                                    @if($testResult)
                                                        <a href="{{ route('candidate.tests.show', $job) }}" class="w-full lg:w-auto px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs rounded-xl border border-slate-200 text-center transition">
                                                            Hasil Tes: {{ $testResult->score }}% ({{ $testResult->passed ? 'Lulus' : 'Belum Lulus' }})
                                                        </a>
                                                    @else
                                                        <a href="{{ route('candidate.tests.show', $job) }}" class="w-full lg:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl text-center shadow-xs transition flex items-center justify-center gap-2">
                                                            <i class="fa-solid fa-pen-to-square"></i> Ikuti Tes Seleksi
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        @elseif($isAlreadyEnrolled)
                                            <div class="p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-900 rounded-2xl text-left space-y-2.5 max-w-sm">
                                                <div class="flex items-center gap-2 text-amber-800 dark:text-amber-300 font-bold text-xs">
                                                    <i class="fa-solid fa-circle-exclamation text-amber-600"></i>
                                                    <span>Ketentuan Program Magang</span>
                                                </div>
                                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed font-normal">
                                                    {{ $internshipBlockReason ?? 'Anda tidak dapat mendaftar lowongan magang ini sesuai ketentuan program magang.' }}
                                                </p>
                                                @if(auth()->user()->internshipPeriod)
                                                    <a href="{{ route('candidate.logbook.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                                                        <i class="fa-solid fa-calendar-check text-[10px]"></i>
                                                        <span>Buka Presensi Magang</span>
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <form action="{{ route('jobs.apply', $job) }}" method="POST" class="space-y-3">
                                                @csrf
                                                <div class="space-y-1 text-left">
                                                    <label class="block text-xs font-semibold text-slate-700">
                                                        Link Video Screening <span class="text-slate-400 font-normal">(Opsional)</span>
                                                    </label>
                                                    <input type="url" name="screening_video_url" placeholder="https://youtube.com/... / Loom / Drive" class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 placeholder:text-slate-400">
                                                    <p class="text-[11px] text-slate-400">Sertakan link video perkenalan 1-2 menit untuk memperbesar peluang lolos screening.</p>
                                                </div>
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin melamar posisi ini? Pastikan profil dan CV Anda sudah terbaru.')" class="w-full lg:w-auto px-7 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                                                    Lamar Pekerjaan Ini
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="block w-full lg:w-auto text-center px-7 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                                        Login untuk Melamar
                                    </a>
                                @endauth
                            @endif

                            @auth
                                @if(auth()->user()->candidateProfile)
                                    @php
                                        $matchScore = $job->calculateMatchScore(auth()->user()->candidateProfile);
                                    @endphp
                                    <div class="text-center text-xs font-semibold text-blue-900 bg-blue-50 p-2.5 rounded-xl border border-blue-100">
                                        Match Profile: <strong>{{ $matchScore }}% Cocok</strong>
                                    </div>
                                @endif
                                <button type="button" onclick="document.getElementById('reportModal').style.display='flex'" class="w-full text-center py-2 px-3 bg-rose-50 hover:bg-rose-100 text-rose-700 font-medium text-xs rounded-xl border border-rose-200 transition flex items-center justify-center gap-1.5 mt-2 cursor-pointer">
                                    <i class="fa-solid fa-flag text-rose-500 text-xs"></i> Laporkan Lowongan / Red Flag
                                </button>
                            @endauth
                        </div>
                    </div>

                    <!-- Job Description & Content -->
                    <div class="space-y-8">
                        @if($job->batch || $job->duration || $job->start_date)
                            <div class="p-4 bg-gradient-to-r from-indigo-50/80 to-blue-50/80 rounded-2xl border border-indigo-100 flex flex-wrap items-center justify-between gap-4 text-xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-2xs shrink-0">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] uppercase font-bold text-indigo-700 tracking-wider">Program & Gelombang Rekrutmen</div>
                                        <div class="font-bold text-slate-900 text-sm mt-0.5">
                                            {{ $job->batch ?: 'Program Reguler' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-700">
                                    @if($job->duration)
                                        <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-xl border border-indigo-100 shadow-2xs font-semibold">
                                            <i class="fa-regular fa-clock text-indigo-600"></i>
                                            <span>Durasi: {{ $job->duration }}</span>
                                        </div>
                                    @endif
                                    @if($job->start_date)
                                        <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-xl border border-indigo-100 shadow-2xs font-semibold">
                                            <i class="fa-solid fa-calendar-check text-emerald-600"></i>
                                            <span>Mulai Onboarding: {{ $job->start_date->format('d M Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2 border-b border-slate-100 pb-2">
                                <i class="fa-solid fa-file-lines text-slate-400"></i> Deskripsi Pekerjaan
                            </h3>
                            <div class="text-slate-700 leading-relaxed text-xs whitespace-pre-line font-normal">
                                {{ $job->description }}
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2 border-b border-slate-100 pb-2">
                                <i class="fa-solid fa-graduation-cap text-slate-400"></i> Persyaratan & Kualifikasi
                            </h3>
                            <div class="text-slate-700 leading-relaxed text-xs whitespace-pre-line font-normal">
                                {{ $job->requirements }}
                            </div>
                        </div>

                        @if($job->benefits)
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                                    <i class="fa-solid fa-gift text-slate-400"></i> Tunjangan & Benefit Perusahaan
                                </h3>

                                @php
                                    $rawBenefits = preg_split('/[\n\r,]+/', $job->benefits);
                                    $benefitItems = array_values(array_filter(array_map(function($item) {
                                        return trim(preg_replace('/^[\-\*\•\d\.\s]+/', '', $item));
                                    }, $rawBenefits)));
                                @endphp

                                @if(count($benefitItems) > 0)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                        @foreach($benefitItems as $item)
                                            <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50 flex items-center gap-2.5 text-slate-800">
                                                <div class="w-6 h-6 rounded-lg bg-white flex items-center justify-center text-xs font-bold text-blue-600 shadow-xs shrink-0 border border-slate-200">
                                                    <i class="fa-solid fa-check"></i>
                                                </div>
                                                <span class="text-xs font-medium leading-snug">{{ $item }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-slate-700 leading-relaxed text-xs font-normal bg-slate-50 p-4 rounded-xl border border-slate-200">
                                        {{ $job->benefits }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- MAPS LOCATION SECTION -->
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200/60 pb-3">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                        <i class="fa-solid fa-map-location-dot text-slate-600 text-sm"></i> Lokasi Penempatan Kerja
                                    </h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Alamat kantor: <strong class="text-slate-700">{{ $job->location }}</strong></p>
                                </div>

                                @php
                                    $mapsUrl = $job->google_maps_link ?: 'https://maps.google.com/?q=' . urlencode($job->location);
                                @endphp

                                <a href="{{ $mapsUrl }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition gap-1.5 shrink-0 shadow-xs">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i> Buka Rute di Google Maps
                                </a>
                            </div>

                            <!-- Embedded Interactive Map Iframe -->
                            <div class="w-full h-64 rounded-xl overflow-hidden shadow-inner border border-slate-200 bg-slate-200 relative">
                                <iframe 
                                    width="100%" 
                                    height="100%" 
                                    style="border:0;" 
                                    loading="lazy" 
                                    allowfullscreen 
                                    src="https://maps.google.com/maps?q={{ urlencode($job->location) }}&t=&z=14&ie=UTF8&iwloc=&output=embed">
                                </iframe>
                            </div>
                        </div>

                        @php
                            $compName = $job->company_name ?: 'PT TechNova Asia Digital';
                            $companyJobsCount = \App\Models\Job::where('company_name', $compName)->where('status', 'active')->count();
                        @endphp

                        <!-- Profil Perusahaan Penyelenggara -->
                        <div class="mt-8 p-6 bg-slate-50 rounded-2xl border border-slate-200/90 shadow-2xs space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center font-bold text-base text-blue-600 shrink-0 border border-slate-200 shadow-2xs">
                                        <i class="fa-solid fa-building text-base"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('companies.show', urlencode($compName)) }}" class="group inline-flex items-center gap-1.5">
                                            <h4 class="text-base font-bold text-slate-900 group-hover:text-blue-600 transition">
                                                {{ $compName }}
                                            </h4>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-blue-600 transition"></i>
                                        </a>
                                        <p class="text-xs text-slate-500 font-medium">Software & Technology • 50-200 Karyawan</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg border border-emerald-200 flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> Perusahaan Terverifikasi
                                    </span>
                                </div>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed font-normal">
                                {{ $compName }} adalah perusahaan terpercaya yang membuka berbagai kesempatan karir bagi talenta profesional terbaik.
                            </p>
                            <div class="flex flex-wrap items-center justify-between gap-4 pt-2 border-t border-slate-200/70">
                                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 font-normal">
                                    <span class="flex items-center gap-1"><i class="fa-solid fa-globe text-slate-400"></i> technova-asia.com</span>
                                    <span class="flex items-center gap-1"><i class="fa-solid fa-phone text-slate-400"></i> 021-55443322</span>
                                    <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot text-slate-400"></i> {{ $job->location }}</span>
                                </div>

                                <a href="{{ route('companies.show', urlencode($compName)) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-100 text-slate-800 font-semibold text-xs rounded-xl transition border border-slate-200 shadow-2xs">
                                    <i class="fa-solid fa-briefcase text-xs text-blue-600"></i> Lihat {{ $companyJobsCount }} Lowongan Lainnya &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Red Flag Report Modal -->
    @auth
    <div id="reportModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-xl border border-slate-200 space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> Laporkan Indikasi Masalah / Red Flag
                </h3>
                <button type="button" onclick="document.getElementById('reportModal').style.display='none'" class="text-slate-400 hover:text-slate-600 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('company-reports.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="job_id" value="{{ $job->id }}">
                <input type="hidden" name="company_name" value="{{ $job->company_name }}">

                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Perusahaan yang Dilaporkan</label>
                    <input type="text" readonly value="{{ $job->company_name }} (Posisi: {{ $job->title }})" class="w-full bg-slate-100 border-slate-200 rounded-xl font-medium text-slate-700">
                </div>

                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Kategori Laporan</label>
                    <select name="report_category" required class="w-full border-slate-300 rounded-xl font-medium focus:ring-rose-500 focus:border-rose-500">
                        <option value="deposit_fee">Meminta Uang Jaminan / Biaya Rekrutmen (Scam)</option>
                        <option value="diploma_withholding">Penahanan Ijazah Asli Tanpa Syarat Sah</option>
                        <option value="under_umk">Gaji Di Bawah UMK & Jam Kerja Tidak Wajar</option>
                        <option value="fake_company">Perusahaan Fiktif / Alamat Palsu</option>
                        <option value="harassment">Perlakuan Diskriminatif / Pelecehan</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Kronologi / Penjelasan Laporan <span class="text-rose-500">*</span></label>
                    <textarea name="reason_description" rows="3" required placeholder="Jelaskan secara rinci kronologi kejadian atau alasan indikasi..." class="w-full border-slate-300 rounded-xl font-medium focus:ring-rose-500 focus:border-rose-500 text-slate-800"></textarea>
                </div>

                <!-- Bukti Pendukung (Tautan GDrive / Upload Banyak Screenshot) -->
                <div class="space-y-3 p-4 bg-slate-50 rounded-xl border border-slate-200/90" x-data="{ fileCount: 0 }">
                    <div class="flex items-center justify-between">
                        <label class="block font-bold text-slate-800 text-[11px] uppercase tracking-wide flex items-center gap-1.5">
                            <i class="fa-solid fa-paperclip text-slate-500"></i> Bukti Pendukung (Opsional)
                        </label>
                        <span class="text-[10px] font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">Bisa GDrive & Foto</span>
                    </div>

                    <div>
                        <label class="block text-[11px] font-medium text-slate-700 mb-1">1. Tautan Google Drive / Cloud Link</label>
                        <input type="url" name="evidence_url" placeholder="https://drive.google.com/... (Folder bukti/dokumen)" class="w-full border-slate-300 rounded-lg text-xs font-medium focus:ring-rose-500 focus:border-rose-500 bg-white placeholder:text-slate-400">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[11px] font-medium text-slate-700">2. Unggah Foto / Screenshot (Bisa Pilih Hingga 5 File)</label>
                            <span x-show="fileCount > 0" class="text-[10px] text-emerald-700 font-semibold bg-emerald-50 px-1.5 py-0.5 rounded" x-text="fileCount + ' file dipilih'"></span>
                        </div>
                        <input type="file" name="evidence_files[]" multiple accept="image/png,image/jpeg,image/webp,application/pdf" @change="fileCount = $event.target.files.length" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 cursor-pointer bg-white border border-slate-200 rounded-lg p-1">
                        <p class="text-[10px] text-slate-400 mt-1">Tekan <strong>Ctrl</strong> (atau Shift) saat memilih file untuk mengunggah beberapa screenshot sekaligus (maks. 5MB per file).</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-3">
                    <button type="button" onclick="document.getElementById('reportModal').style.display='none'" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 font-semibold text-slate-700 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 font-semibold text-white rounded-xl shadow-xs flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-paper-plane text-xs"></i> Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endauth
</x-public-layout>
