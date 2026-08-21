<x-public-layout>
    <div class="bg-gray-50/60 py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb Navigation -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 text-xs font-semibold text-gray-500">
                    <li>
                        <a href="{{ route('jobs.index') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                            💼 Lowongan Kerja
                        </a>
                    </li>
                    <li>
                        <span class="text-gray-300">/</span>
                    </li>
                    <li class="text-gray-900 font-bold truncate max-w-xs md:max-w-md">
                        {{ $job->title }}
                    </li>
                </ol>
            </nav>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-sm text-sm font-semibold">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 shadow-sm text-sm font-semibold">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Main Job Details Card -->
            <div class="bg-white rounded-2xl shadow-2xs border border-slate-200 overflow-hidden mb-12">
                
                <!-- Accent Banner Header -->
                <div class="h-2 bg-slate-900"></div>

                <div class="p-6 sm:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 border-b border-slate-200 pb-6 mb-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-900 text-white font-bold text-xl flex items-center justify-center border border-slate-900 shrink-0">
                                {{ strtoupper(substr($job->title, 0, 1)) }}
                            </div>

                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-800 text-3xs font-bold rounded-md border border-slate-200 uppercase">
                                        🏢 {{ $job->division }}
                                    </span>
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-800 text-3xs font-bold rounded-md border border-slate-200 uppercase">
                                        💻 {{ ucfirst($job->work_type) }}
                                    </span>
                                    <span class="text-3xs text-slate-400 font-medium">
                                        Diposting {{ $job->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight mb-2">{{ $job->title }}</h1>
                                
                                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-600 font-medium">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-location-dot text-slate-500"></i>
                                        {{ $job->location }}
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-money-bill-wave text-slate-500"></i>
                                        {{ $job->salary ?? 'Gaji Negosiasi' }}
                                    </div>
                                    @if($job->deadline)
                                        <div class="flex items-center gap-1.5 text-slate-700 font-semibold">
                                            <i class="fa-solid fa-clock text-slate-500"></i>
                                            Deadline: {{ $job->deadline->format('d M Y') }}
                                        </div>
                                    @endif
                                </div>

                                @if(isset($umk) && $umk)
                                    <div class="mt-3.5 p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between flex-wrap gap-2 text-xs shadow-2xs">
                                        <div class="flex items-center gap-2 text-slate-900 font-bold">
                                            <span class="px-2 py-0.5 rounded-md bg-slate-900 text-white text-3xs font-bold uppercase">UMK 2026</span>
                                            <span>Acuan Gaji Minimum {{ $umk->city_district }}: <strong class="text-slate-900 font-bold">{{ $umk->formatted_umk }} / bulan</strong></span>
                                        </div>
                                        <span class="text-3xs font-bold text-slate-700 bg-white px-2.5 py-1 rounded-lg border border-slate-200" title="{{ $umk->legal_decree }}">
                                            📜 Landasan Hukum Resmi Gubernur
                                        </span>
                                    </div>
                                @endif

                                <!-- Quota Indicator -->
                                @if($job->quota)
                                    <div class="mt-4 p-2.5 bg-slate-50 border border-slate-200 rounded-xl inline-flex items-center gap-3 text-xs font-bold text-slate-800">
                                        <i class="fa-solid fa-users text-slate-600"></i>
                                        <span>Kuota Pelamar: <strong>{{ $job->applications()->count() }} / {{ $job->quota }}</strong></span>
                                        @if($job->isQuotaFull())
                                            <span class="px-2 py-0.5 bg-rose-600 text-white text-3xs font-bold rounded-md uppercase">Penuh</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-emerald-600 text-white text-3xs font-bold rounded-md uppercase">Tersisa {{ $job->remainingQuota() }} Slot</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Button Area -->
                        <div class="flex flex-col gap-3 w-full lg:w-auto shrink-0">
                            @if($job->isExpired() || $job->status == 'closed')
                                <div class="px-5 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl text-center border border-slate-300 text-xs">
                                    ⛔ Lowongan Ditutup / Kadaluarsa
                                </div>
                            @elseif($job->isQuotaFull())
                                <div class="px-5 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl text-center border border-slate-300 text-xs">
                                    ⚠️ Kuota Pendaftaran Penuh
                                </div>
                            @else
                                @auth
                                    @if(auth()->user()->hasRole('Candidate'))
                                        @if($hasApplied)
                                            <div class="flex flex-col gap-2">
                                                <button disabled class="w-full lg:w-auto px-6 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl cursor-not-allowed flex items-center justify-center gap-2 text-xs border border-slate-300">
                                                    <i class="fa-solid fa-check text-slate-600"></i>
                                                    Sudah Dilamar
                                                </button>

                                                @if($job->test && $job->test->is_active)
                                                    @if($testResult)
                                                        <a href="{{ route('candidate.tests.show', $job->id) }}" class="w-full lg:w-auto px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-900 font-bold text-xs rounded-xl border border-slate-300 text-center transition">
                                                            📊 Hasil Tes: {{ $testResult->score }}% ({{ $testResult->passed ? 'Lulus' : 'Belum Lulus' }})
                                                        </a>
                                                    @else
                                                        <a href="{{ route('candidate.tests.show', $job->id) }}" class="w-full lg:w-auto px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl text-center shadow-2xs transition flex items-center justify-center gap-2 border border-slate-900">
                                                            <i class="fa-solid fa-pen-to-square"></i> Ikuti Tes Online Seleksi
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        @else
                                            <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin melamar posisi ini? Pastikan profil dan CV Anda sudah terbaru.')" class="w-full lg:w-auto px-7 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-2xs transition border border-slate-900">
                                                    🚀 Lamar Sekarang
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="block w-full lg:w-auto text-center px-7 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-2xs transition border border-slate-900">
                                        🔑 Login untuk Melamar
                                    </a>
                                @endauth
                            @endif

                            @auth
                                @if(auth()->user()->candidateProfile)
                                    @php
                                        $matchScore = $job->calculateMatchScore(auth()->user()->candidateProfile);
                                    @endphp
                                    <div class="text-center text-xs font-bold text-slate-800 bg-slate-100 p-2 rounded-xl border border-slate-200">
                                        🎯 Match Score: <strong>{{ $matchScore }}%</strong>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <!-- Job Description & Content -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2 border-b border-slate-200 pb-2">
                                📋 Deskripsi Pekerjaan
                            </h3>
                            <div class="text-slate-700 leading-relaxed text-xs whitespace-pre-line font-normal">
                                {{ $job->description }}
                            </div>
                        </div>

                        <div>
                            <h3 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2 border-b border-slate-200 pb-2">
                                🎓 Persyaratan & Kualifikasi
                            </h3>
                            <div class="text-slate-700 leading-relaxed text-xs whitespace-pre-line font-normal">
                                {{ $job->requirements }}
                            </div>
                        </div>

                        @if($job->benefits)
                            <div>
                                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                                    <i class="fa-solid fa-gift text-slate-500"></i> Tunjangan & Benefit Perusahaan
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
                                            <div class="p-3 rounded-xl border border-slate-200 bg-slate-50 flex items-center gap-2.5 text-slate-800">
                                                <div class="w-7 h-7 rounded-lg bg-white flex items-center justify-center text-xs font-bold shadow-2xs shrink-0 text-slate-700 border border-slate-200">
                                                    <i class="fa-solid fa-check"></i>
                                                </div>
                                                <span class="text-xs font-bold leading-snug">{{ $item }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-slate-700 leading-relaxed text-xs font-medium bg-slate-50 p-4 rounded-xl border border-slate-200">
                                        {{ $job->benefits }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- MAPS LOCATION SECTION -->
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 pb-3">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                        <i class="fa-solid fa-map-location-dot text-slate-700 text-sm"></i> Lokasi Penempatan & Peta Google Maps Kantor
                                    </h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Alamat kerja: <strong>{{ $job->location }}</strong></p>
                                </div>

                                @php
                                    $mapsUrl = $job->google_maps_link ?: 'https://maps.google.com/?q=' . urlencode($job->location);
                                @endphp

                                <a href="{{ $mapsUrl }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition gap-1.5 shrink-0 border border-slate-900 shadow-2xs">
                                    <i class="fa-solid fa-diamond-turn-right text-xs"></i> Buka Rute di Google Maps &rarr;
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
                        <div class="mt-8 p-6 bg-slate-900 text-white rounded-2xl border border-slate-800 shadow-2xs space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center font-bold text-base text-white shrink-0 border border-slate-700">
                                        🏢
                                    </div>
                                    <div>
                                        <a href="{{ route('companies.show', urlencode($compName)) }}" class="group">
                                            <h4 class="text-base font-bold text-white group-hover:text-slate-300 transition flex items-center gap-1.5">
                                                {{ $compName }}
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xs opacity-75 group-hover:opacity-100"></i>
                                            </h4>
                                        </a>
                                        <p class="text-xs text-slate-400 font-semibold">Software & Technology • 50-200 Karyawan</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 bg-emerald-500/20 text-emerald-300 text-3xs font-bold rounded-lg border border-emerald-500/30 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-emerald-400"></i> Perusahaan Terverifikasi
                                    </span>
                                </div>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed">
                                {{ $compName }} adalah perusahaan terpercaya yang membuka berbagai kesempatan karir bagi talenta profesional terbaik dengan inovasi industri 4.0.
                            </p>
                            <div class="flex flex-wrap items-center justify-between gap-4 pt-2 border-t border-slate-800/80">
                                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-400 font-medium">
                                    <span><i class="fa-solid fa-globe text-slate-400"></i> technova-asia.com</span>
                                    <span><i class="fa-solid fa-phone text-slate-400"></i> 021-55443322</span>
                                    <span><i class="fa-solid fa-location-dot text-slate-400"></i> {{ $job->location }}</span>
                                </div>

                                <a href="{{ route('companies.show', urlencode($compName)) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl transition border border-slate-700">
                                    <i class="fa-solid fa-briefcase text-xs"></i> Lihat {{ $companyJobsCount }} Lowongan Perusahaan Ini &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-public-layout>
