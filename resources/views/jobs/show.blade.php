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
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden mb-12">
                
                <!-- Accent Banner Header -->
                <div class="h-3 bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-500"></div>

                <div class="p-8 sm:p-10">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 border-b border-gray-100 pb-8 mb-8">
                        <div class="flex items-start gap-5">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-black text-2xl flex items-center justify-center shadow-lg shrink-0">
                                {{ strtoupper(substr($job->title, 0, 1)) }}
                            </div>

                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-extrabold rounded-full border border-blue-100">
                                        🏢 {{ $job->division }}
                                    </span>
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-extrabold rounded-full border border-emerald-100">
                                        💻 {{ ucfirst($job->work_type) }}
                                    </span>
                                    <span class="text-xs text-gray-400 font-medium">
                                        Diposting {{ $job->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 leading-tight mb-3">{{ $job->title }}</h1>
                                
                                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs md:text-sm text-gray-600 font-medium">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        {{ $job->location }}
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $job->salary ?? 'Gaji Negosiasi' }}
                                    </div>
                                    @if($job->deadline)
                                        <div class="flex items-center gap-1.5 text-amber-600 font-semibold">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Deadline: {{ $job->deadline->format('d M Y') }}
                                        </div>
                                    @endif
                                </div>

                                @if(isset($umk) && $umk)
                                    <div class="mt-3.5 p-3.5 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl border border-emerald-200/80 flex items-center justify-between flex-wrap gap-2 text-xs shadow-2xs">
                                        <div class="flex items-center gap-2.5 text-emerald-950 font-black">
                                            <span class="px-2 py-0.5 rounded-md bg-emerald-600 text-white text-3xs font-black tracking-wider uppercase">UMK 2026</span>
                                            <span>Acuan Gaji Minimum {{ $umk->city_district }}: <strong class="text-emerald-700 text-sm font-black">{{ $umk->formatted_umk }} / bulan</strong></span>
                                        </div>
                                        <span class="text-3xs font-bold text-emerald-800 bg-emerald-100/90 px-2.5 py-1 rounded-lg border border-emerald-200" title="{{ $umk->legal_decree }}">
                                            📜 Landasan Hukum Resmi Gubernur
                                        </span>
                                    </div>
                                @endif

                                <!-- Quota Indicator -->
                                @if($job->quota)
                                    <div class="mt-4 p-3 bg-indigo-50 border border-indigo-100 rounded-2xl inline-flex items-center gap-3 text-xs font-bold text-indigo-900">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        <span>Kuota Pelamar: <strong>{{ $job->applications()->count() }} / {{ $job->quota }}</strong></span>
                                        @if($job->isQuotaFull())
                                            <span class="px-2.5 py-0.5 bg-red-600 text-white text-2xs font-extrabold rounded-md uppercase">Penuh</span>
                                        @else
                                            <span class="px-2.5 py-0.5 bg-green-600 text-white text-2xs font-extrabold rounded-md uppercase">Tersisa {{ $job->remainingQuota() }} Slot</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Button Area -->
                        <div class="flex flex-col gap-3 w-full lg:w-auto shrink-0">
                            @if($job->isExpired() || $job->status == 'closed')
                                <div class="px-6 py-3 bg-red-100 text-red-700 font-extrabold rounded-2xl text-center border border-red-200 text-sm">
                                    ⛔ Lowongan Ditutup / Kadaluarsa
                                </div>
                            @elseif($job->isQuotaFull())
                                <div class="px-6 py-3 bg-amber-100 text-amber-800 font-extrabold rounded-2xl text-center border border-amber-200 text-sm">
                                    ⚠️ Kuota Pendaftaran Penuh
                                </div>
                            @else
                                @auth
                                    @if(auth()->user()->hasRole('Candidate'))
                                        @if($hasApplied)
                                            <div class="flex flex-col gap-2">
                                                <button disabled class="w-full lg:w-auto px-8 py-3.5 bg-emerald-50 text-emerald-700 font-extrabold rounded-2xl cursor-not-allowed flex items-center justify-center gap-2 text-sm border border-emerald-200">
                                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Sudah Dilamar
                                                </button>

                                                @if($job->test && $job->test->is_active)
                                                    @if($testResult)
                                                        <a href="{{ route('candidate.tests.show', $job->id) }}" class="w-full lg:w-auto px-6 py-2.5 bg-purple-50 hover:bg-purple-100 text-purple-800 font-extrabold text-xs rounded-xl border border-purple-200 text-center transition">
                                                            📊 Hasil Tes: {{ $testResult->score }}% ({{ $testResult->passed ? 'Lulus' : 'Belum Lulus' }})
                                                        </a>
                                                    @else
                                                        <a href="{{ route('candidate.tests.show', $job->id) }}" class="w-full lg:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl text-center shadow-md transition flex items-center justify-center gap-2">
                                                            <i class="fa-solid fa-pen-to-square"></i> Ikuti Tes Online Seleksi
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        @else
                                            <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin melamar posisi ini? Pastikan profil dan CV Anda sudah terbaru.')" class="w-full lg:w-auto px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-black text-sm rounded-2xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                                                    🚀 Lamar Sekarang
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="block w-full lg:w-auto text-center px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-black text-sm rounded-2xl shadow-lg hover:shadow-xl transition">
                                        🔑 Login untuk Melamar
                                    </a>
                                @endauth
                            @endif

                            @auth
                                @if(auth()->user()->candidateProfile)
                                    @php
                                        $matchScore = $job->calculateMatchScore(auth()->user()->candidateProfile);
                                    @endphp
                                    <div class="text-center text-xs font-bold text-blue-700 bg-blue-50 p-2 rounded-xl border border-blue-100">
                                        🎯 Match Score: <strong>{{ $matchScore }}%</strong>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <!-- Job Description & Content -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-lg font-black text-gray-900 mb-3 flex items-center gap-2 border-b border-gray-100 pb-2">
                                📋 Deskripsi Pekerjaan
                            </h3>
                            <div class="text-gray-700 leading-relaxed text-sm whitespace-pre-line font-normal">
                                {{ $job->description }}
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-black text-gray-900 mb-3 flex items-center gap-2 border-b border-gray-100 pb-2">
                                🎓 Persyaratan & Kualifikasi
                            </h3>
                            <div class="text-gray-700 leading-relaxed text-sm whitespace-pre-line font-normal">
                                {{ $job->requirements }}
                            </div>
                        </div>

                        @if($job->benefits)
                            <div>
                                <h3 class="text-lg font-black text-slate-900 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                                    <i class="fa-solid fa-gift text-amber-500"></i> Tunjangan & Benefit Perusahaan
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
                                            @php
                                                $lower = strtolower($item);
                                                $icon = 'fa-circle-check';
                                                $bgClass = 'bg-emerald-50/90 border-emerald-200 text-emerald-950';
                                                $iconColor = 'text-emerald-600';

                                                if (Str::contains($lower, ['bpjs', 'kesehatan', 'asuransi', 'medis', 'gigi', 'ketenagakerjaan', 'rawat'])) {
                                                    $icon = 'fa-notes-medical';
                                                    $bgClass = 'bg-emerald-50 border-emerald-200 text-emerald-950';
                                                    $iconColor = 'text-emerald-600';
                                                } elseif (Str::contains($lower, ['pelatihan', 'sertifikasi', 'kursus', 'beasiswa', 'training', 'edukasi'])) {
                                                    $icon = 'fa-graduation-cap';
                                                    $bgClass = 'bg-indigo-50 border-indigo-200 text-indigo-950';
                                                    $iconColor = 'text-indigo-600';
                                                } elseif (Str::contains($lower, ['kopi', 'snack', 'makan', 'lunch', 'pantry', 'cater', 'minum'])) {
                                                    $icon = 'fa-mug-hot';
                                                    $bgClass = 'bg-amber-50 border-amber-200 text-amber-950';
                                                    $iconColor = 'text-amber-600';
                                                } elseif (Str::contains($lower, ['bonus', 'thr', 'insentif', 'gaji', 'saham', 'investasi', 'keuangan'])) {
                                                    $icon = 'fa-coins';
                                                    $bgClass = 'bg-yellow-50 border-yellow-200 text-yellow-950';
                                                    $iconColor = 'text-yellow-600';
                                                } elseif (Str::contains($lower, ['remote', 'work from home', 'wfh', 'fleksibel', 'cuti', 'libur'])) {
                                                    $icon = 'fa-house-laptop';
                                                    $bgClass = 'bg-blue-50 border-blue-200 text-blue-950';
                                                    $iconColor = 'text-blue-600';
                                                } elseif (Str::contains($lower, ['laptop', 'macbook', 'gadget', 'perangkat', 'komputer'])) {
                                                    $icon = 'fa-laptop';
                                                    $bgClass = 'bg-purple-50 border-purple-200 text-purple-950';
                                                    $iconColor = 'text-purple-600';
                                                } elseif (Str::contains($lower, ['gym', 'fitness', 'olahraga', 'kebugaran', 'sport'])) {
                                                    $icon = 'fa-dumbbell';
                                                    $bgClass = 'bg-rose-50 border-rose-200 text-rose-950';
                                                    $iconColor = 'text-rose-600';
                                                } elseif (Str::contains($lower, ['parkir', 'transport', 'bensin', 'kendaraan', 'jemput', 'travel'])) {
                                                    $icon = 'fa-car';
                                                    $bgClass = 'bg-cyan-50 border-cyan-200 text-cyan-950';
                                                    $iconColor = 'text-cyan-600';
                                                } elseif (Str::contains($lower, ['voucher', 'belanja', 'diskon', 'belanja'])) {
                                                    $icon = 'fa-ticket-simple';
                                                    $bgClass = 'bg-teal-50 border-teal-200 text-teal-950';
                                                    $iconColor = 'text-teal-600';
                                                } elseif (Str::contains($lower, ['pulsa', 'hp', 'internet', 'kuota', 'telepon'])) {
                                                    $icon = 'fa-mobile-screen-button';
                                                    $bgClass = 'bg-sky-50 border-sky-200 text-sky-950';
                                                    $iconColor = 'text-sky-600';
                                                } elseif (Str::contains($lower, ['game', 'hiburan', 'outing', 'gathering', 'rekreasi', 'wisata'])) {
                                                    $icon = 'fa-gamepad';
                                                    $bgClass = 'bg-fuchsia-50 border-fuchsia-200 text-fuchsia-950';
                                                    $iconColor = 'text-fuchsia-600';
                                                }
                                            @endphp

                                            <div class="p-3.5 rounded-2xl border flex items-center gap-3 transition hover:shadow-xs {{ $bgClass }}">
                                                <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-sm font-black shadow-2xs shrink-0 {{ $iconColor }}">
                                                    <i class="fa-solid {{ $icon }}"></i>
                                                </div>
                                                <span class="text-xs font-extrabold leading-snug">{{ $item }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-gray-700 leading-relaxed text-xs font-medium bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                        {{ $job->benefits }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- MAPS LOCATION SECTION -->
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-200/80 space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200/60 pb-3">
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                        <i class="fa-solid fa-map-location-dot text-rose-600 text-base"></i> Lokasi Penempatan & Peta Google Maps Kantor
                                    </h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Alamat kerja: <strong>{{ $job->location }}</strong></p>
                                </div>

                                @php
                                    $mapsUrl = $job->google_maps_link ?: 'https://maps.google.com/?q=' . urlencode($job->location);
                                @endphp

                                <a href="{{ $mapsUrl }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-2xs transition gap-1.5 shrink-0">
                                    <i class="fa-solid fa-diamond-turn-right"></i> Buka Rute di Google Maps &rarr;
                                </a>
                            </div>

                            <!-- Embedded Interactive Map Iframe -->
                            <div class="w-full h-64 rounded-2xl overflow-hidden shadow-inner border border-slate-200/80 bg-slate-200 relative">
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

                        <!-- Profil Perusahaan Penyelenggara (Clickable to Public Company Vacancies Profile Page) -->
                        <div class="mt-10 p-6 bg-slate-900 text-white rounded-3xl border border-slate-800 shadow-xl space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center font-black text-xl text-white shadow-md shrink-0">
                                        🏢
                                    </div>
                                    <div>
                                        <a href="{{ route('companies.show', urlencode($compName)) }}" class="group">
                                            <h4 class="text-base font-black text-white group-hover:text-blue-300 transition flex items-center gap-1.5">
                                                {{ $compName }}
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xs opacity-75 group-hover:opacity-100"></i>
                                            </h4>
                                        </a>
                                        <p class="text-xs text-blue-300 font-semibold">Software & Technology • 50-200 Karyawan</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 bg-green-500/20 text-green-300 text-2xs font-extrabold rounded-full border border-green-500/30 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-green-400"></i> Perusahaan Terverifikasi
                                    </span>
                                </div>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed">
                                {{ $compName }} adalah perusahaan terpercaya yang membuka berbagai kesempatan karir bagi talenta profesional terbaik dengan inovasi industri 4.0.
                            </p>
                            <div class="flex flex-wrap items-center justify-between gap-4 pt-2 border-t border-slate-800/80">
                                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-400 font-medium">
                                    <span><i class="fa-solid fa-globe text-blue-400"></i> technova-asia.com</span>
                                    <span><i class="fa-solid fa-phone text-emerald-400"></i> 021-55443322</span>
                                    <span><i class="fa-solid fa-location-dot text-rose-400"></i> {{ $job->location }}</span>
                                </div>

                                <a href="{{ route('companies.show', urlencode($compName)) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-2xs transition">
                                    <i class="fa-solid fa-briefcase"></i> Lihat {{ $companyJobsCount }} Lowongan Perusahaan Ini &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-public-layout>
