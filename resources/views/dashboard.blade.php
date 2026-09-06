<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-slate-900 dark:bg-blue-600 text-white flex items-center justify-center text-xs shadow-xs">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <span>Dashboard Karir</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Pantau status lamaran kerja, jadwal wawancara, dan dokumen karir Anda secara terpadu.
                </p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('jobs.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i> 
                    <span>Cari Lowongan</span>
                </a>
                <a href="{{ route('profile.candidate.details.edit') }}" class="bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold py-2 px-4 rounded-xl text-xs transition border border-slate-200 dark:border-slate-800 shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-user-pen text-slate-400 text-xs"></i> 
                    <span>Edit Profil & CV</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl shadow-xs flex items-center gap-3 text-xs font-medium transition">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- HERO WELCOME BANNER CARD -->
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-7 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/60 border border-blue-200/80 dark:border-blue-900 text-blue-700 dark:text-blue-300 rounded-md text-[11px] font-semibold uppercase tracking-wider">
                                Pencari Kerja
                            </span>
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-normal">| {{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                            Selamat Datang, {{ auth()->user()->name }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-2xl leading-relaxed font-normal">
                            Pantau perkembangan berkas lamaran Anda atau temukan peluang karir baru yang sesuai dengan kompetensi Anda.
                        </p>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 p-4 rounded-xl shrink-0 space-y-2 w-full md:w-64">
                        <div class="flex justify-between items-center text-xs font-semibold">
                            <span class="text-slate-700 dark:text-slate-300">Kelengkapan Profil</span>
                            <span class="text-blue-600 dark:text-blue-400 font-bold">
                                {{ auth()->user()->candidateProfile && auth()->user()->candidateProfile->cv_path ? '100%' : '60%' }}
                            </span>
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-blue-600 dark:bg-blue-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ auth()->user()->candidateProfile && auth()->user()->candidateProfile->cv_path ? '100%' : '60%' }}"></div>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">
                            @if(auth()->user()->candidateProfile && auth()->user()->candidateProfile->cv_path)
                                Profil & CV sudah lengkap dan siap melamar.
                            @else
                                Lengkapi CV untuk meningkatkan peluang diterima.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- ACTION ALERT NOTIFICATIONS -->
            @php
                $userInterviews = \App\Models\Interview::whereHas('application', function($q) {
                    $q->where('user_id', auth()->id());
                })->where('scheduled_at', '>=', now())
                  ->with('application.job')
                  ->get();
            @endphp

            @if($userInterviews->count() > 0)
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0 border border-blue-200/80 dark:border-blue-900 font-semibold">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Undangan Wawancara Kerja</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Perhatikan detail tanggal, waktu, dan lokasi wawancara berikut:</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                        @foreach($userInterviews as $userInt)
                            <div class="bg-slate-50 dark:bg-slate-950/60 hover:bg-slate-100/80 dark:hover:bg-slate-800/40 text-slate-900 dark:text-white p-4 rounded-xl border border-slate-200 dark:border-slate-800 space-y-2 transition">
                                <div class="font-bold text-xs text-slate-900 dark:text-white flex items-center justify-between">
                                    <span>{{ $userInt->application->job->title ?? 'Pekerjaan' }}</span>
                                    <span class="uppercase font-semibold text-[10px] bg-slate-200 dark:bg-slate-800 px-2 py-0.5 rounded text-slate-700 dark:text-slate-300">{{ $userInt->type }}</span>
                                </div>
                                <div class="text-xs space-y-1 text-slate-600 dark:text-slate-400">
                                    <p class="flex items-center gap-2"><i class="fa-solid fa-clock text-slate-400 text-xs"></i> <strong>Waktu:</strong> {{ $userInt->scheduled_at->format('d F Y, H:i') }} WIB</p>
                                    @if($userInt->location_or_link)
                                        <p class="flex items-center gap-2"><i class="fa-solid fa-link text-slate-400 text-xs"></i> <strong>Lokasi/Link:</strong> 
                                             @if(Str::startsWith($userInt->location_or_link, 'http'))
                                                <a href="{{ $userInt->location_or_link }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">Buka Tautan Wawancara &rarr;</a>
                                            @else
                                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $userInt->location_or_link }}</span>
                                            @endif
                                        </p>
                                    @endif
                                    @if($userInt->notes)
                                        <p class="text-xs text-slate-500 dark:text-slate-400 italic bg-white dark:bg-slate-900 p-2.5 rounded-lg border border-slate-200 dark:border-slate-800">"{{ $userInt->notes }}"</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @php
                $acceptedApps = isset($recentApplications) 
                    ? $recentApplications->filter(function($app) {
                        $st = is_object($app->status) ? $app->status->value : (string)$app->status;
                        return in_array($st, ['accepted', 'hired']);
                    })
                    : collect();
            @endphp

            @foreach($acceptedApps as $acceptedApp)
                <div class="bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/80 p-6 rounded-2xl shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-lg shrink-0 font-bold shadow-xs">
                                <i class="fa-solid fa-trophy"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-emerald-900 dark:text-emerald-200">Selamat! Anda Diterima Sebagai {{ $acceptedApp->job->title }}</h3>
                                <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-0.5">Perusahaan: <strong>{{ $acceptedApp->job->company_name }}</strong>. Silakan lengkapi data onboarding karyawan.</p>
                            </div>
                        </div>
                        <a href="{{ route('candidate.onboarding.create', $acceptedApp) }}" class="shrink-0 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-semibold text-xs shadow-xs transition flex items-center gap-2">
                            <i class="fa-solid fa-file-signature text-xs"></i> Isi Data Onboarding
                        </a>
                    </div>
                </div>
            @endforeach

            @php
                $pendingAgreements = \App\Models\ApplicationAgreement::where('user_id', auth()->id())
                    ->where('status', 'sent')
                    ->with('application.job')
                    ->get();
            @endphp

            @foreach($pendingAgreements as $pendingAgreement)
                <div class="bg-amber-50/50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/80 p-6 rounded-2xl shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shrink-0 font-bold shadow-xs">
                                <i class="fa-solid fa-file-contract"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-amber-900 dark:text-amber-200">Perjanjian Kerja Menunggu Tanda Tangan Anda</h3>
                                <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5">Dokumen: <strong>{{ $pendingAgreement->title }}</strong> &bull; Perusahaan: <strong>{{ $pendingAgreement->application->job->company_name }}</strong></p>
                            </div>
                        </div>
                        <a href="{{ route('candidate.agreements.show', $pendingAgreement) }}" class="shrink-0 bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl font-semibold text-xs shadow-xs transition flex items-center gap-2">
                            <i class="fa-solid fa-file-signature text-xs"></i> Tanda Tangani Dokumen
                        </a>
                    </div>
                </div>
            @endforeach

            @php
                $myCertificates = \App\Models\InternshipCertificate::where('user_id', auth()->id())
                    ->with('application.job')
                    ->latest()
                    ->get();
            @endphp

            @foreach($myCertificates as $myCert)
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0 font-bold border border-blue-200/80 dark:border-blue-900">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Sertifikat Kelulusan Magang Resmi Telah Diterbitkan</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Posisi: <strong>{{ $myCert->job_title }}</strong> &bull; Predikat: <strong>{{ $myCert->performance_grade }}</strong></p>
                        </div>
                    </div>
                    <a href="{{ route('candidate.certificates.show', $myCert) }}" target="_blank" class="shrink-0 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white px-4 py-2 rounded-xl font-semibold text-xs shadow-xs transition flex items-center gap-2 border border-slate-700 dark:border-slate-600">
                        <i class="fa-solid fa-file-pdf text-xs"></i> Unduh Sertifikat PDF
                    </a>
                </div>
            @endforeach

            @php
                $myActivePeriod = auth()->user()->internshipPeriod;
            @endphp

            @if($myActivePeriod)
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg shrink-0 font-bold border border-indigo-200/80 dark:border-indigo-900">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Program Magang Aktif: {{ $myActivePeriod->period_name }}</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900">Aktif</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Periode: <strong>{{ $myActivePeriod->start_date->format('d M Y') }} s/d {{ $myActivePeriod->end_date->format('d M Y') }}</strong> &bull; Target: <strong>{{ $myActivePeriod->target_hours }} Jam Kerja</strong>
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('candidate.logbook.index') }}" class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold text-xs shadow-xs transition flex items-center gap-2">
                        <i class="fa-solid fa-calendar-check text-xs"></i> Buka Presensi & Logbook
                    </a>
                </div>
            @endif

            @if(!auth()->user()->candidateProfile || !auth()->user()->candidateProfile->cv_path)
                <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3.5 text-slate-800 dark:text-slate-200">
                        <div class="w-9 h-9 rounded-xl bg-amber-500/15 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-300 dark:border-amber-700 flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <span class="font-bold block text-xs mb-0.5 text-slate-900 dark:text-white">Profil & CV Belum Lengkap</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-normal">Profil atau file CV Anda belum diunggah. Lengkapi berkas untuk memudahkan recruiter menilai lamaran Anda.</span>
                        </div>
                    </div>
                    <a href="{{ route('profile.candidate.details.edit') }}" class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-semibold text-xs shadow-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-upload text-xs"></i> Lengkapi Profil & CV
                    </a>
                </div>
            @endif

            <!-- SELECTION TIMELINE STEPPER FOR CANDIDATE'S LATEST APPLICATION -->
            @if(isset($recentApplications) && $recentApplications->count() > 0)
                @php
                    $latestApp = $recentApplications->first();
                    $statusVal = is_object($latestApp->status) ? $latestApp->status->value : (string) $latestApp->status;
                    $job = $latestApp->job;
                    $hasActiveTest = $job && $job->test && $job->test->is_active;
                    $hasOfferLetter = (bool) $latestApp->offerLetter;

                    // Dynamically build stages for this specific job pipeline
                    $stages = [
                        ['key' => 'pending', 'label' => 'Melamar Berkas', 'icon' => 'fa-file-signature'],
                    ];

                    if ($statusVal === 'screening') {
                        $stages[] = ['key' => 'screening', 'label' => 'HR Screening', 'icon' => 'fa-user-check'];
                    }

                    if ($hasActiveTest || $statusVal === 'test') {
                        $stages[] = ['key' => 'test', 'label' => 'Tes Online', 'icon' => 'fa-laptop-code'];
                    }

                    // HR Interview is always standard
                    $stages[] = ['key' => 'interview_hr', 'label' => 'Wawancara HR', 'icon' => 'fa-comments'];

                    if ($statusVal === 'interview_user') {
                        $stages[] = ['key' => 'interview_user', 'label' => 'Wawancara User', 'icon' => 'fa-users-viewfinder'];
                    }

                    if ($statusVal === 'background_check') {
                        $stages[] = ['key' => 'background_check', 'label' => 'Background Check', 'icon' => 'fa-shield-halved'];
                    }

                    if ($hasOfferLetter || $statusVal === 'offered' || $statusVal === 'accepted') {
                        $stages[] = ['key' => 'offered', 'label' => 'Offer Letter', 'icon' => 'fa-file-contract'];
                    }

                    $stages[] = ['key' => 'accepted', 'label' => 'Diterima Kerja', 'icon' => 'fa-circle-check'];

                    // Mapping current status to active stage index
                    $stageKeys = array_column($stages, 'key');
                    
                    $currentKey = $statusVal;
                    if ($statusVal === 'reviewed' || $statusVal === 'processing') $currentKey = 'pending';
                    if ($statusVal === 'interview') $currentKey = 'interview_hr';
                    
                    $activeIdx = array_search($currentKey, $stageKeys);
                    if ($activeIdx === false) {
                        $activeIdx = 0;
                    }
                    $currentStepNum = $activeIdx + 1;
                    $isRejected = ($statusVal === 'rejected');
                    $stageCount = count($stages);
                @endphp

                <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-7 shadow-xs border border-slate-200/80 dark:border-slate-800 space-y-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 px-2.5 py-0.5 rounded-md border border-blue-200/80 dark:border-blue-900 inline-block mb-1">Progress Rekrutmen</span>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Status Tahapan: {{ $latestApp->job->title }}</h3>
                        </div>
                        <div>
                            @if(is_object($latestApp->status) && method_exists($latestApp->status, 'color'))
                                <span class="px-3 py-1 rounded-xl text-xs font-semibold uppercase tracking-wider border border-slate-200 dark:border-slate-700 {{ $latestApp->status->color() }}">
                                    {{ $latestApp->status->label() }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-slate-900 dark:bg-slate-800 text-white rounded-xl text-xs font-semibold uppercase tracking-wider">
                                    {{ $latestApp->status }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($isRejected)
                        <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-rose-800 dark:text-rose-300 rounded-xl flex items-center gap-3 text-xs font-medium">
                            <i class="fa-solid fa-circle-xmark text-rose-600 dark:text-rose-400 text-lg"></i>
                            <div>
                                <p class="font-bold text-sm text-rose-900 dark:text-rose-200">Status Lamaran: Belum Lolos Seleksi</p>
                                <p class="text-xs text-rose-700 dark:text-rose-400 font-normal mt-0.5">Terima kasih atas partisipasi Anda. Tetap semangat melamar lowongan lainnya.</p>
                            </div>
                        </div>
                    @else
                        <!-- Stepper Bar (Dynamic Columns) -->
                        <div class="relative py-4">
                            <div class="hidden md:block absolute top-1/2 left-8 right-8 h-1 bg-slate-100 dark:bg-slate-800 -translate-y-1/2 z-0 rounded-full"></div>

                            <div class="grid grid-cols-2 md:grid-cols-{{ $stageCount }} gap-4 relative z-10">
                                @foreach($stages as $idx => $stg)
                                    @php
                                        $stepIndex = $idx + 1;
                                        $isPassed = $currentStepNum > $stepIndex;
                                        $isCurrent = $currentStepNum === $stepIndex;
                                    @endphp
                                    <div class="flex flex-col items-center text-center space-y-2">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xs shadow-xs transition duration-200
                                            {{ $isCurrent ? 'bg-blue-600 text-white font-bold ring-4 ring-blue-100 dark:ring-blue-900/40' : ($isPassed ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 border border-slate-200 dark:border-slate-700') }}">
                                            @if($isPassed)
                                                <i class="fa-solid fa-check text-xs"></i>
                                            @else
                                                <i class="fa-solid {{ $stg['icon'] }}"></i>
                                            @endif
                                        </div>
                                        <div class="space-y-0.5">
                                            <span class="text-[11px] font-semibold block uppercase tracking-wider {{ $isCurrent ? 'text-blue-600 dark:text-blue-400' : ($isPassed ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500') }}">
                                                Langkah {{ $stepIndex }}
                                            </span>
                                            <span class="text-xs font-medium leading-tight block {{ $isCurrent ? 'text-slate-900 dark:text-white font-semibold' : ($isPassed ? 'text-slate-700 dark:text-slate-300' : 'text-slate-400 dark:text-slate-500') }}">
                                                {{ $stg['label'] }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- 4 METRIC BENTO CARDS GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Lamaran -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-all border border-slate-200/80 dark:border-slate-800 flex items-center justify-between group">
                    <div class="space-y-0.5">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Lamaran</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $totalApplications ?? 0 }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-normal">Berkas Dikirim</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-slate-50 dark:bg-slate-800/80 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-slate-200/80 dark:border-slate-700">
                        <i class="fa-solid fa-file-lines text-base"></i>
                    </div>
                </div>

                <!-- Diproses -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-all border border-slate-200/80 dark:border-slate-800 flex items-center justify-between group">
                    <div class="space-y-0.5">
                        <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Sedang Diproses</p>
                        <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 tracking-tight">{{ $processingApplications ?? 0 }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-normal">Tahap Seleksi</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-50/50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-200/60 dark:border-amber-900/60">
                        <i class="fa-solid fa-spinner text-base"></i>
                    </div>
                </div>

                <!-- Diterima -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-all border border-slate-200/80 dark:border-slate-800 flex items-center justify-between group">
                    <div class="space-y-0.5">
                        <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Diterima Kerja</p>
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 tracking-tight">{{ $acceptedApplications ?? 0 }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-normal">Lolos Seleksi</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-200/60 dark:border-emerald-900/60">
                        <i class="fa-solid fa-circle-check text-base"></i>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-all border border-slate-200/80 dark:border-slate-800 flex items-center justify-between group">
                    <div class="space-y-0.5">
                        <p class="text-xs font-semibold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Belum Lolos</p>
                        <p class="text-2xl font-bold text-rose-600 dark:text-rose-400 tracking-tight">{{ $rejectedApplications ?? 0 }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-normal">Berkas Ditolak</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-rose-50/50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center border border-rose-200/60 dark:border-rose-900/60">
                        <i class="fa-solid fa-circle-xmark text-base"></i>
                    </div>
                </div>
            </div>

            <!-- RECENT APPLICATIONS & RECOMMENDED JOBS (SPLIT 2 COLUMNS) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Applications Card -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col h-full space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                            Lamaran Terakhir Saya
                        </h3>
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Terbaru</span>
                    </div>
                    
                    <div class="flex-1">
                        @if(isset($recentApplications) && $recentApplications->count() > 0)
                            <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($recentApplications as $app)
                                    <li class="py-3.5 first:pt-0 last:pb-0 space-y-2">
                                        <div class="flex justify-between items-start gap-3">
                                            <div>
                                                <a href="{{ route('jobs.show', $app->job) }}" class="font-bold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 text-sm transition">{{ $app->job->title }}</a>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal mt-0.5">{{ $app->job->division ?? 'Umum' }} &bull; {{ $app->job->company_name }}</p>
                                            </div>
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-md border uppercase border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 shrink-0">
                                                {{ is_object($app->status) && method_exists($app->status, 'label') ? $app->status->label() : $app->status }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs font-normal">
                                            <span class="text-slate-400 dark:text-slate-500"><i class="fa-regular fa-calendar text-slate-400 mr-1"></i> {{ $app->created_at->format('d M Y') }}</span>
                                            <button onclick="openLiveChat({{ $app->id }}, '{{ addslashes($app->job->title) }}', 'HR {{ addslashes($app->job->company_name) }}')" class="px-2.5 py-1 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white font-medium rounded-lg transition text-xs flex items-center gap-1.5 border border-slate-700 dark:border-slate-600">
                                                <i class="fa-solid fa-comments text-xs text-blue-300"></i> Chat HR
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="flex flex-col items-center justify-center h-full py-12 text-slate-400 dark:text-slate-500 text-xs text-center space-y-2">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 text-xl">
                                    <i class="fa-solid fa-inbox"></i>
                                </div>
                                <p class="font-semibold text-slate-700 dark:text-slate-300">Anda belum melamar pekerjaan apapun.</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500">Temukan lowongan idaman dan kirimkan lamaran pertama Anda.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Latest / Recommended Jobs Card -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col h-full space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-briefcase text-blue-600 dark:text-blue-400"></i>
                            Rekomendasi Lowongan Terbaru
                        </h3>
                        <a href="{{ route('jobs.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline transition flex items-center gap-1">
                            Lihat Semua &rarr;
                        </a>
                    </div>
                    
                    <div class="flex-1">
                        @php
                            $latestJobs = \App\Models\Job::where('status', 'active')->latest()->take(3)->get();
                        @endphp

                        @if($latestJobs->count() > 0)
                            <div class="space-y-3">
                                @foreach($latestJobs as $job)
                                    <div class="group border border-slate-200/80 dark:border-slate-800 p-4 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/40 hover:border-slate-300 dark:hover:border-slate-700 transition cursor-pointer space-y-2" onclick="window.location.href='{{ route('jobs.show', $job) }}'">
                                        <div class="flex justify-between items-start gap-2">
                                            <div>
                                                <h4 class="font-bold text-slate-900 dark:text-white text-sm group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">{{ $job->title }}</h4>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal mt-0.5"><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $job->location }}</p>
                                            </div>
                                            <span class="text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 px-2 py-0.5 rounded-md uppercase shrink-0">
                                                {{ $job->work_type }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-full py-12 text-slate-400 dark:text-slate-500 text-xs text-center space-y-2">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 text-xl">
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>
                                <p class="font-semibold text-slate-700 dark:text-slate-300">Belum ada lowongan aktif.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- CANDIDATE APPLICATION STATUS POP-UP MODAL -->
    @if(isset($statusPopupApp) && $statusPopupApp)
        @php
            $stVal = is_object($statusPopupApp->status) ? $statusPopupApp->status->value : (string)$statusPopupApp->status;
            $isAccepted = in_array($stVal, ['accepted', 'hired']);
            $isOffered = ($stVal === 'offered');
            $isInterview = in_array($stVal, ['interview', 'interview_hr', 'interview_user']);
            $isTest = ($stVal === 'test');
            $isRejected = ($stVal === 'rejected');
        @endphp

        <div x-data="{ showStatusModal: true }">
            <div x-show="showStatusModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-md flex items-center justify-center p-4" 
                 style="display: none;">
                
                <div @click.outside="showStatusModal = false" class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800 relative text-left">
                    
                    <button type="button" @click="showStatusModal = false" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center text-xs transition cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                    @if($isAccepted)
                        @php $isIntern = strtolower($statusPopupApp->job->work_type ?? '') === 'internship' || strtolower($statusPopupApp->job->work_type ?? '') === 'magang'; @endphp
                        <!-- ACCEPTED MODAL -->
                        <div class="bg-emerald-600 text-white p-6 sm:p-7 text-center relative overflow-hidden">
                            <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center text-xl mx-auto mb-2.5 shadow-xs border border-white/20">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <span class="px-2.5 py-0.5 bg-white/20 text-white rounded-md text-[10px] font-semibold uppercase tracking-wider inline-block mb-1.5 border border-white/20">
                                {{ $isIntern ? 'Program Magang / Internship' : 'Pemberitahuan Kelulusan' }}
                            </span>
                            <h2 class="text-xl font-bold tracking-tight text-white mb-0.5">
                                {{ $isIntern ? 'Selamat! Anda Diterima Magang' : 'Selamat! Anda Lolos Seleksi' }}
                            </h2>
                            <p class="text-xs text-emerald-100 font-normal">
                                {{ $isIntern ? 'Peserta Program Magang Resmi Perusahaan' : 'Lamaran Kerja Berhasil Diterima Perusahaan' }}
                            </p>
                        </div>

                        <div class="p-6 space-y-4 text-center">
                            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-200 dark:border-slate-800 text-xs space-y-1">
                                <p class="text-slate-500 dark:text-slate-400 font-normal text-[11px]">Posisi Pekerjaan:</p>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 dark:text-slate-300 font-medium">{{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                                Perusahaan <strong>{{ $statusPopupApp->job->company_name }}</strong> telah menerima lamaran Anda. Silakan lengkapi data onboarding dan administrasi awal Anda.
                            </p>

                            <div class="pt-1 flex flex-col gap-2">
                                <a href="{{ route('candidate.onboarding.create', $statusPopupApp) }}" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-file-signature text-xs"></i> Lengkapi Data Onboarding &rarr;
                                </a>
                                <button type="button" @click="showStatusModal = false" class="text-xs text-slate-400 font-medium hover:underline py-1">Tutup Nanti Saja</button>
                            </div>
                        </div>

                    @elseif($isOffered)
                        <!-- OFFERED MODAL -->
                        <div class="bg-amber-600 text-white p-6 sm:p-7 text-center relative overflow-hidden">
                            <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center text-xl mx-auto mb-2.5 shadow-xs border border-white/20">
                                <i class="fa-solid fa-file-contract"></i>
                            </div>
                            <span class="px-2.5 py-0.5 bg-white/20 text-white rounded-md text-[10px] font-semibold uppercase tracking-wider inline-block mb-1.5 border border-white/20">
                                Penawaran Kerja Resmi
                            </span>
                            <h2 class="text-xl font-bold tracking-tight text-white mb-0.5">Surat Penawaran Kerja (Offering)</h2>
                            <p class="text-xs text-amber-100 font-normal">Offer Letter Resmi Telah Diterbitkan</p>
                        </div>

                        <div class="p-6 space-y-4 text-center">
                            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-200 dark:border-slate-800 text-xs space-y-1">
                                <p class="text-slate-500 dark:text-slate-400 font-normal text-[11px]">Posisi Ditawarkan:</p>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 dark:text-slate-300 font-medium">{{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                                Perusahaan <strong>{{ $statusPopupApp->job->company_name }}</strong> telah menerbitkan Surat Penawaran Kerja (Offer Letter). Silakan tinjau rincian penawaran dan beri respon Anda.
                            </p>

                            <div class="pt-1 flex flex-col gap-2">
                                <a href="{{ route('dashboard') }}" @click="showStatusModal = false" class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-file-contract text-xs"></i> Tinjau Surat Penawaran &rarr;
                                </a>
                                <button type="button" @click="showStatusModal = false" class="text-xs text-slate-400 font-medium hover:underline py-1">Tutup</button>
                            </div>
                        </div>

                    @elseif($isInterview)
                        <!-- INTERVIEW MODAL -->
                        <div class="bg-blue-600 text-white p-6 sm:p-7 text-center relative overflow-hidden">
                            <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center text-xl mx-auto mb-2.5 shadow-xs border border-white/20">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>
                            <span class="px-2.5 py-0.5 bg-white/20 text-white rounded-md text-[10px] font-semibold uppercase tracking-wider inline-block mb-1.5 border border-white/20">
                                Tahap Interview
                            </span>
                            <h2 class="text-xl font-bold tracking-tight text-white mb-0.5">Undangan Sesi Wawancara</h2>
                            <p class="text-xs text-blue-100 font-normal">Berkas Anda Lolos ke Tahap Interview</p>
                        </div>

                        <div class="p-6 space-y-4 text-center">
                            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-200 dark:border-slate-800 text-xs space-y-1">
                                <p class="text-slate-500 dark:text-slate-400 font-normal text-[11px]">Posisi Lowongan:</p>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 dark:text-slate-300 font-medium">{{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                                Berkas lamaran Anda telah memenuhi kriteria dan diundang untuk mengikuti sesi wawancara. Silakan cek rincian jadwal pada kartu undangan di dashboard.
                            </p>

                            <div class="pt-1 flex flex-col gap-2">
                                <button type="button" @click="showStatusModal = false" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-calendar-days text-xs"></i> Lihat Jadwal Wawancara
                                </button>
                            </div>
                        </div>

                    @elseif($isTest)
                        <!-- TEST MODAL -->
                        <div class="bg-indigo-600 text-white p-6 sm:p-7 text-center relative overflow-hidden">
                            <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center text-xl mx-auto mb-2.5 shadow-xs border border-white/20">
                                <i class="fa-solid fa-laptop-code"></i>
                            </div>
                            <span class="px-2.5 py-0.5 bg-white/20 text-white rounded-md text-[10px] font-semibold uppercase tracking-wider inline-block mb-1.5 border border-white/20">
                                Asesmen Kemampuan
                            </span>
                            <h2 class="text-xl font-bold tracking-tight text-white mb-0.5">Ujian / Tes Online Seleksi</h2>
                            <p class="text-xs text-indigo-100 font-normal">Tahap Penilaian Kemampuan Teknis</p>
                        </div>

                        <div class="p-6 space-y-4 text-center">
                            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-200 dark:border-slate-800 text-xs space-y-1">
                                <p class="text-slate-500 dark:text-slate-400 font-normal text-[11px]">Posisi Lowongan:</p>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 dark:text-slate-300 font-medium">{{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                                Lamaran Anda dinyatakan berhak mengikuti tahapan Tes Online. Pastikan koneksi internet Anda stabil sebelum memulai ujian.
                            </p>

                            <div class="pt-1 flex flex-col gap-2">
                                <a href="{{ route('candidate.tests.show', $statusPopupApp->job) }}" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i> Mulai Kerjakan Tes Sekarang &rarr;
                                </a>
                                <button type="button" @click="showStatusModal = false" class="text-xs text-slate-400 font-medium hover:underline py-1">Kerjakan Nanti</button>
                            </div>
                        </div>

                    @elseif($isRejected)
                        <!-- REJECTED MODAL -->
                        <div class="bg-slate-800 dark:bg-slate-950 text-white p-6 sm:p-7 text-center relative overflow-hidden">
                            <div class="w-12 h-12 rounded-xl bg-white/10 text-rose-400 flex items-center justify-center text-xl mx-auto mb-2.5 shadow-xs border border-white/10">
                                <i class="fa-solid fa-circle-info"></i>
                            </div>
                            <span class="px-2.5 py-0.5 bg-white/10 text-slate-300 rounded-md text-[10px] font-semibold uppercase tracking-wider inline-block mb-1.5 border border-white/10">
                                Status Lamaran
                            </span>
                            <h2 class="text-xl font-bold tracking-tight text-white mb-0.5">Pemberitahuan Tahapan Seleksi</h2>
                            <p class="text-xs text-slate-400 font-normal">Informasi Terkait Hasil Seleksi Berkas</p>
                        </div>

                        <div class="p-6 space-y-4 text-center">
                            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-200 dark:border-slate-800 text-xs space-y-1">
                                <p class="text-slate-500 dark:text-slate-400 font-normal text-[11px]">Posisi Lowongan:</p>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 dark:text-slate-300 font-medium">{{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                                Terima kasih atas minat dan partisipasi Anda pada lowongan ini. Saat ini kualifikasi Anda belum sesuai dengan kebutuhan posisi terkait. Tetap semangat melamar peluang lainnya.
                            </p>

                            <div class="pt-1 flex flex-col gap-2">
                                <a href="{{ route('jobs.index') }}" class="w-full py-2.5 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-magnifying-glass text-xs"></i> Jelajahi Lowongan Lainnya &rarr;
                                </a>
                                <button type="button" @click="showStatusModal = false" class="text-xs text-slate-400 font-medium hover:underline py-1">Tutup</button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endif

    <x-live-chat-drawer />
</x-app-layout>
