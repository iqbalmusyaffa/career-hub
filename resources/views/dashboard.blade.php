<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center text-sm shadow-md">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    Dashboard Candidate
                </h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Pantau status lamaran kerja, jadwal wawancara, dan dokumen karir Anda secara terpadu.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('jobs.index') }}" class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari Lowongan Kerja
                </a>
                <a href="{{ route('profile.candidate.details.edit') }}" class="bg-white hover:bg-slate-100 text-slate-800 font-bold py-2.5 px-4 rounded-xl text-xs transition border border-slate-300 shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-user-pen text-slate-600"></i> Edit Profile & CV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/70 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200/80 text-emerald-900 rounded-2xl shadow-sm flex items-center gap-3 text-xs font-bold transition">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- HERO WELCOME BANNER CARD -->
            <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 rounded-3xl shadow-xl border border-slate-800">
                <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 bg-white/10 backdrop-blur-md border border-white/15 text-indigo-200 rounded-full text-3xs font-black uppercase tracking-wider">
                                ✨ Welcome Back
                            </span>
                            <span class="text-xs text-slate-400 font-medium">| Candidate Hub</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                            Halo, {{ auth()->user()->name }}! 👋
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                            Siap melangkah ke jenjang karir berikutnya? Cek perkembangan berkas lamaran Anda atau cari peluang kerja terbaru dari berbagai perusahaan terpercaya.
                        </p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/15 p-4 rounded-2xl shrink-0 space-y-2 w-full md:w-64">
                        <div class="flex justify-between items-center text-xs font-bold">
                            <span class="text-slate-200">Kelengkapan Profil</span>
                            <span class="text-emerald-400 font-black">
                                {{ auth()->user()->candidateProfile && auth()->user()->candidateProfile->cv_path ? '100%' : '60%' }}
                            </span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden border border-slate-700">
                            <div class="bg-gradient-to-r from-indigo-400 to-emerald-400 h-2 rounded-full transition-all duration-500" style="width: {{ auth()->user()->candidateProfile && auth()->user()->candidateProfile->cv_path ? '100%' : '60%' }}"></div>
                        </div>
                        <p class="text-3xs text-slate-300 font-medium">
                            @if(auth()->user()->candidateProfile && auth()->user()->candidateProfile->cv_path)
                                ✅ Profil & CV Anda sudah lengkap. Siap melamar!
                            @else
                                ⚡ Unggah CV Anda untuk meningkatkan peluang diterima.
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
                <div class="bg-slate-900 border border-slate-800 text-white p-6 rounded-3xl shadow-xl space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl shrink-0 border border-amber-500/30 font-bold shadow-md">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white">Anda Memiliki Undangan Wawancara!</h3>
                            <p class="text-xs text-slate-300">Perhatikan detail tanggal & lokasi wawancara di bawah ini:</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-2">
                        @foreach($userInterviews as $userInt)
                            <div class="bg-slate-800/90 hover:bg-slate-800 text-white p-4 rounded-2xl border border-slate-700/80 space-y-2.5 transition">
                                <div class="font-bold text-sm text-slate-100 flex items-center justify-between">
                                    <span>{{ $userInt->application->job->title ?? 'Pekerjaan' }}</span>
                                    <span class="uppercase font-extrabold text-3xs bg-slate-700 px-2 py-0.5 rounded-lg text-slate-200 border border-slate-600">{{ $userInt->type }}</span>
                                </div>
                                <div class="text-xs space-y-1.5 text-slate-300">
                                    <p class="flex items-center gap-2">📅 <strong>Waktu:</strong> {{ $userInt->scheduled_at->format('d F Y, H:i') }} WIB</p>
                                    @if($userInt->location_or_link)
                                        <p class="flex items-center gap-2">🔗 <strong>Lokasi/Link:</strong> 
                                            @if(Str::startsWith($userInt->location_or_link, 'http'))
                                                <a href="{{ $userInt->location_or_link }}" target="_blank" class="text-amber-400 hover:underline font-bold">Buka Link Wawancara →</a>
                                            @else
                                                <span class="font-semibold text-slate-200">{{ $userInt->location_or_link }}</span>
                                            @endif
                                        </p>
                                    @endif
                                    @if($userInt->notes)
                                        <p class="text-2xs text-slate-400 italic bg-slate-900/60 p-2.5 rounded-xl border border-slate-700/50">"{{ $userInt->notes }}"</p>
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
                <div class="bg-gradient-to-r from-emerald-950 via-slate-900 to-emerald-950 border border-emerald-500/40 text-white p-6 rounded-3xl shadow-xl space-y-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-slate-950 flex items-center justify-center text-xl shrink-0 font-black shadow-lg">
                                <i class="fa-solid fa-trophy"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-white">Selamat! Anda Diterima Sebagai {{ $acceptedApp->job->title }}</h3>
                                <p class="text-xs text-slate-300 mt-0.5">Perusahaan: <strong>{{ $acceptedApp->job->company_name }}</strong>. Silakan lengkapi formulir data rekening bank, NPWP, dan BPJS Onboarding karyawan.</p>
                            </div>
                        </div>
                        <a href="{{ route('candidate.onboarding.create', $acceptedApp) }}" class="shrink-0 bg-emerald-400 hover:bg-emerald-300 text-slate-950 px-6 py-3 rounded-2xl font-black text-xs shadow-lg transition border border-emerald-300 flex items-center gap-2">
                            <i class="fa-solid fa-file-signature text-slate-900"></i> Isi Data Onboarding & Bank →
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
                <div class="bg-gradient-to-r from-amber-950 via-slate-900 to-amber-950 border border-amber-500/40 text-white p-6 rounded-3xl shadow-xl space-y-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-400 text-slate-950 flex items-center justify-center text-xl shrink-0 font-black shadow-lg">
                                <i class="fa-solid fa-pen-nib"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-100">Dokumen Perjanjian Kerja Menunggu Tanda Tangan Anda!</h3>
                                <p class="text-xs text-amber-200 mt-0.5">Dokumen: <strong>{{ $pendingAgreement->title }}</strong> &bull; Perusahaan: <strong>{{ $pendingAgreement->application->job->company_name }}</strong></p>
                            </div>
                        </div>
                        <a href="{{ route('candidate.agreements.show', $pendingAgreement) }}" class="shrink-0 bg-amber-400 hover:bg-amber-300 text-slate-950 px-6 py-3 rounded-2xl font-black text-xs shadow-lg transition border border-amber-300 flex items-center gap-2">
                            <i class="fa-solid fa-file-contract"></i> Tanda Tangani Dokumen Digital →
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
                <div class="bg-gradient-to-r from-slate-900 via-amber-950 to-slate-900 border border-amber-500/50 text-white p-6 rounded-3xl shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-400 text-slate-950 flex items-center justify-center text-xl shrink-0 font-black shadow-lg">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-100">Sertifikat Kelulusan Magang Resmi Telah Diterbitkan!</h3>
                            <p class="text-xs text-amber-200 mt-0.5">Posisi: <strong>{{ $myCert->job_title }}</strong> &bull; Predikat: <strong>{{ $myCert->performance_grade }}</strong></p>
                        </div>
                    </div>
                    <a href="{{ route('candidate.certificates.show', $myCert) }}" target="_blank" class="shrink-0 bg-amber-400 hover:bg-amber-300 text-slate-950 px-6 py-3 rounded-2xl font-black text-xs shadow-lg transition border border-amber-300 flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf"></i> Unduh / Cetak Sertifikat PDF →
                    </a>
                </div>
            @endforeach

            @php
                $myTranscripts = \App\Models\InternshipTranscript::where('user_id', auth()->id())
                    ->with('application.job')
                    ->latest()
                    ->get();
            @endphp

            @foreach($myTranscripts as $myTrans)
                <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border border-indigo-500/50 text-white p-6 rounded-3xl shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500 text-white flex items-center justify-center text-xl shrink-0 font-black shadow-lg">
                            <i class="fa-solid fa-square-poll-vertical"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-100">Transkrip Nilai Magang Resmi Telah Diterbitkan!</h3>
                            <p class="text-xs text-indigo-200 mt-0.5">Posisi: <strong>{{ $myTrans->job_title }}</strong> &bull; Skor Akhir: <strong>{{ number_format($myTrans->final_score, 1) }}/100 ({{ $myTrans->grade_letter }})</strong></p>
                        </div>
                    </div>
                    <a href="{{ route('candidate.transcripts.show', $myTrans) }}" target="_blank" class="shrink-0 bg-indigo-500 hover:bg-indigo-400 text-white px-6 py-3 rounded-2xl font-black text-xs shadow-lg transition border border-indigo-400 flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf"></i> Unduh / Cetak Transkrip PDF →
                    </a>
                </div>
            @endforeach

            @php
                $myTerminations = \App\Models\EmployeeTermination::where('user_id', auth()->id())
                    ->with('application.job')
                    ->latest()
                    ->get();
            @endphp

            @foreach($myTerminations as $myTerm)
                <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 border border-slate-700 text-white p-6 rounded-3xl shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-800 text-white flex items-center justify-center text-xl shrink-0 font-black shadow-lg">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-100">
                                @if($myTerm->document_type === 'recommendation_letter')
                                    Surat Rekomendasi Kerja & Referensi Karir Resmi Diterbitkan!
                                @elseif($myTerm->document_type === 'paklaring_letter')
                                    Surat Paklaring Pengalaman Kerja Diterbitkan!
                                @elseif($myTerm->document_type === 'phk_letter')
                                    Surat Pemutusan Hubungan Kerja (PHK) Diterbitkan.
                                @else
                                    Surat Keterangan Selesai Masa Kontrak Diterbitkan.
                                @endif
                            </h3>
                            <p class="text-xs text-slate-300 mt-0.5">Posisi: <strong>{{ $myTerm->job_title }}</strong> &bull; No: <strong>{{ $myTerm->document_number }}</strong></p>
                        </div>
                    </div>
                    <a href="{{ route('candidate.terminations.show', $myTerm) }}" target="_blank" class="shrink-0 bg-slate-100 hover:bg-white text-slate-950 px-6 py-3 rounded-2xl font-black text-xs shadow-lg transition border border-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf"></i> Unduh / Cetak Dokumen PDF →
                    </a>
                </div>
            @endforeach

            @if(!auth()->user()->candidateProfile || !auth()->user()->candidateProfile->cv_path)
                <div class="bg-amber-50/80 border border-amber-200 p-6 rounded-3xl shadow-2xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3.5 text-slate-800">
                        <div class="w-10 h-10 rounded-2xl bg-amber-500 text-slate-950 flex items-center justify-center text-lg shrink-0 font-black shadow-md mt-0.5">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <span class="font-extrabold block text-sm mb-0.5 text-slate-900">Perhatian: Profil Belum Lengkap!</span>
                            <span class="text-xs text-slate-600 leading-relaxed">Profil Anda belum lengkap atau file CV belum diunggah. Lengkapi segera untuk meningkatkan skor seleksi lamaran Anda.</span>
                        </div>
                    </div>
                    <a href="{{ route('profile.candidate.details.edit') }}" class="shrink-0 bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl font-extrabold text-xs shadow-md transition border border-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-upload text-amber-400"></i> Lengkapi Profil & CV →
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
                        $stages[] = ['key' => 'test', 'label' => 'Tes Online / Psikotes', 'icon' => 'fa-laptop-code'];
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

                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/90 space-y-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-3xs font-extrabold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 inline-block mb-1">Progress Rekrutmen</span>
                            <h3 class="text-lg font-black text-slate-900">Status Tahapan: {{ $latestApp->job->title }}</h3>
                        </div>
                        <div>
                            @if(is_object($latestApp->status) && method_exists($latestApp->status, 'color'))
                                <span class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider border border-slate-200 shadow-2xs {{ $latestApp->status->color() }}">
                                    {{ $latestApp->status->label() }}
                                </span>
                            @else
                                <span class="px-3.5 py-1.5 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-2xs">
                                    {{ $latestApp->status }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($isRejected)
                        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-xs font-bold">
                            <i class="fa-solid fa-circle-xmark text-rose-600 text-xl"></i>
                            <div>
                                <p class="font-extrabold text-sm text-rose-900">Status Lamaran: Belum Lolos Seleksi</p>
                                <p class="text-3xs text-rose-700 font-medium mt-0.5">Terima kasih atas partisipasi Anda. Tetap semangat melamar lowongan lainnya!</p>
                            </div>
                        </div>
                    @else
                        <!-- Stepper Bar (Dynamic Columns) -->
                        <div class="relative py-4">
                            <div class="hidden md:block absolute top-1/2 left-8 right-8 h-1.5 bg-slate-100 -translate-y-1/2 z-0 rounded-full"></div>

                            <div class="grid grid-cols-2 md:grid-cols-{{ $stageCount }} gap-4 relative z-10">
                                @foreach($stages as $idx => $stg)
                                    @php
                                        $stepIndex = $idx + 1;
                                        $isPassed = $currentStepNum > $stepIndex;
                                        $isCurrent = $currentStepNum === $stepIndex;
                                    @endphp
                                    <div class="flex flex-col items-center text-center space-y-2">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-sm shadow-md transition duration-300
                                            {{ $isCurrent ? 'bg-slate-900 text-white font-extrabold scale-110 border-2 border-indigo-500 shadow-indigo-200' : ($isPassed ? 'bg-emerald-600 text-white font-black' : 'bg-slate-100 text-slate-400 border border-slate-200') }}">
                                            @if($isPassed)
                                                <i class="fa-solid fa-check text-base"></i>
                                            @else
                                                <i class="fa-solid {{ $stg['icon'] }}"></i>
                                            @endif
                                        </div>
                                        <div class="space-y-0.5">
                                            <span class="text-3xs font-black block uppercase tracking-wider {{ $isCurrent ? 'text-slate-900' : ($isPassed ? 'text-emerald-700' : 'text-slate-400') }}">
                                                Langkah {{ $stepIndex }}
                                            </span>
                                            <span class="text-xs font-bold leading-tight block {{ $isCurrent ? 'text-slate-900 font-black' : ($isPassed ? 'text-slate-700' : 'text-slate-400') }}">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Lamaran -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 flex items-center justify-between group">
                    <div class="space-y-1">
                        <p class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">Total Lamaran</p>
                        <p class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalApplications ?? 0 }}</p>
                        <p class="text-3xs text-slate-400 font-medium">Berkas Dikirim</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-file-lines text-xl"></i>
                    </div>
                </div>

                <!-- Diproses -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 flex items-center justify-between group">
                    <div class="space-y-1">
                        <p class="text-xs font-extrabold text-amber-600 uppercase tracking-wider">Sedang Diproses</p>
                        <p class="text-3xl font-black text-amber-600 tracking-tight">{{ $processingApplications ?? 0 }}</p>
                        <p class="text-3xs text-amber-700/70 font-medium">Tahap Seleksi</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-spinner text-xl"></i>
                    </div>
                </div>

                <!-- Diterima -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 flex items-center justify-between group">
                    <div class="space-y-1">
                        <p class="text-xs font-extrabold text-emerald-600 uppercase tracking-wider">Diterima Kerja</p>
                        <p class="text-3xl font-black text-emerald-600 tracking-tight">{{ $acceptedApplications ?? 0 }}</p>
                        <p class="text-3xs text-emerald-700/70 font-medium">Lolos Seleksi</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 flex items-center justify-between group">
                    <div class="space-y-1">
                        <p class="text-xs font-extrabold text-rose-600 uppercase tracking-wider">Belum Lolos</p>
                        <p class="text-3xl font-black text-rose-600 tracking-tight">{{ $rejectedApplications ?? 0 }}</p>
                        <p class="text-3xs text-rose-700/70 font-medium">Berkas Ditolak</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-circle-xmark text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- RECENT APPLICATIONS & RECOMMENDED JOBS (SPLIT 2 COLUMNS) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Applications Card -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs border border-slate-200/90 flex flex-col h-full space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-slate-600"></i>
                            Lamaran Terakhir Saya
                        </h3>
                        <span class="text-3xs font-extrabold text-slate-400 uppercase tracking-wider">Terbaru</span>
                    </div>
                    
                    <div class="flex-1">
                        @if(isset($recentApplications) && $recentApplications->count() > 0)
                            <ul class="divide-y divide-slate-100">
                                @foreach($recentApplications as $app)
                                    <li class="py-3.5 first:pt-0 last:pb-0 space-y-2.5">
                                        <div class="flex justify-between items-start gap-3">
                                            <div>
                                                <a href="{{ route('jobs.show', $app->job) }}" class="font-extrabold text-slate-900 hover:text-indigo-600 text-sm transition">{{ $app->job->title }}</a>
                                                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $app->job->division ?? 'Umum' }} &bull; {{ $app->job->company_name }}</p>
                                            </div>
                                            <span class="px-2.5 py-1 text-3xs font-extrabold rounded-lg border uppercase border-slate-200 bg-slate-50 text-slate-700 shrink-0">
                                                {{ is_object($app->status) && method_exists($app->status, 'label') ? $app->status->label() : $app->status }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between text-3xs font-semibold">
                                            <span class="text-slate-400"><i class="fa-regular fa-calendar text-slate-400 mr-1"></i> {{ $app->created_at->format('d M Y') }}</span>
                                            <button onclick="openLiveChat({{ $app->id }}, '{{ addslashes($app->job->title) }}', 'HR {{ addslashes($app->job->company_name) }}')" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg transition shadow-2xs flex items-center gap-1.5 border border-slate-900">
                                                <i class="fa-solid fa-comments text-3xs text-indigo-300"></i> Chat HR
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="flex flex-col items-center justify-center h-full py-12 text-slate-400 text-xs text-center space-y-2">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 text-xl">
                                    <i class="fa-solid fa-inbox"></i>
                                </div>
                                <p class="font-bold text-slate-700">Anda belum melamar pekerjaan apapun.</p>
                                <p class="text-3xs text-slate-400">Temukan lowongan idaman dan kirimkan lamaran pertama Anda!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Latest / Recommended Jobs Card -->
                <div class="bg-white p-6 rounded-3xl shadow-2xs border border-slate-200/90 flex flex-col h-full space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-briefcase text-indigo-600"></i>
                            Rekomendasi Lowongan Terbaru
                        </h3>
                        <a href="{{ route('jobs.index') }}" class="text-xs font-extrabold text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1">
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
                                    <div class="group border border-slate-200/80 p-4 rounded-2xl hover:bg-slate-50/80 hover:border-slate-300 transition cursor-pointer space-y-2" onclick="window.location.href='{{ route('jobs.show', $job) }}'">
                                        <div class="flex justify-between items-start gap-2">
                                            <div>
                                                <h4 class="font-extrabold text-slate-900 text-sm group-hover:text-indigo-600 transition">{{ $job->title }}</h4>
                                                <p class="text-xs text-slate-500 font-medium mt-0.5"><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $job->location }}</p>
                                            </div>
                                            <span class="text-3xs font-extrabold bg-indigo-50 text-indigo-800 border border-indigo-100 px-2.5 py-1 rounded-md uppercase shrink-0">
                                                {{ $job->work_type }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-full py-12 text-slate-400 text-xs text-center space-y-2">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 text-xl">
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>
                                <p class="font-bold text-slate-700">Belum ada lowongan aktif.</p>
                            </div>
                        @endif
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
                
                <div @click.outside="showStatusModal = false" class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-200 relative">
                    
                    <button type="button" @click="showStatusModal = false" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-black/20 hover:bg-black/40 text-white flex items-center justify-center text-xs transition cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                    @if($isAccepted)
                        @php $isIntern = strtolower($statusPopupApp->job->work_type ?? '') === 'internship' || strtolower($statusPopupApp->job->work_type ?? '') === 'magang'; @endphp
                        <!-- ACCEPTED MODAL -->
                        <div class="bg-gradient-to-br {{ $isIntern ? 'from-amber-600 via-teal-800 to-slate-900' : 'from-emerald-600 via-teal-700 to-emerald-900' }} text-white p-8 text-center relative overflow-hidden">
                            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md text-emerald-200 flex items-center justify-center text-3xl mx-auto mb-3 shadow-lg border border-white/20 font-black">
                                {{ $isIntern ? '🎓' : '🏆' }}
                            </div>
                            <span class="px-3 py-1 bg-white/20 text-emerald-100 rounded-full text-3xs font-black uppercase tracking-wider inline-block mb-2 border border-white/20">
                                {{ $isIntern ? '🎓 Program Magang / Internship' : 'Update Status Terbaru' }}
                            </span>
                            <h2 class="text-2xl font-black tracking-tight text-white mb-1">
                                {{ $isIntern ? 'SELAMAT! DITERIMA MAGANG! 🎉' : 'SELAMAT! ANDA DITERIMA! 🎉' }}
                            </h2>
                            <p class="text-xs text-emerald-100 font-medium">
                                {{ $isIntern ? 'Peserta Program Magang Resmi Perusahaan' : 'Lamaran Kerja Berhasil Lolos Seleksi' }}
                            </p>
                        </div>

                        <div class="p-6 sm:p-8 space-y-5 text-center">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-xs space-y-1">
                                <p class="text-slate-500 font-medium">Posisi Pekerjaan:</p>
                                <p class="font-black text-slate-900 text-base">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 font-bold">🏢 {{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed font-medium">
                                Selamat! Perusahaan <strong>{{ $statusPopupApp->job->company_name }}</strong> telah menerima lamaran Anda. Silakan isi data onboarding, nomor rekening bank, NPWP, dan BPJS Anda untuk proses selanjutnya.
                            </p>

                            <div class="pt-2 flex flex-col gap-2">
                                <a href="{{ route('candidate.onboarding.create', $statusPopupApp) }}" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg transition border border-emerald-600 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-file-signature"></i> Isi Data Onboarding & Bank Sekarang &rarr;
                                </a>
                                <button type="button" @click="showStatusModal = false" class="text-xs text-slate-400 font-bold hover:underline py-1">Nanti Saja</button>
                            </div>
                        </div>

                    @elseif($isOffered)
                        <!-- OFFERED MODAL -->
                        <div class="bg-gradient-to-br from-amber-500 via-amber-600 to-amber-800 text-white p-8 text-center relative overflow-hidden">
                            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md text-amber-100 flex items-center justify-center text-3xl mx-auto mb-3 shadow-lg border border-white/20 font-black">
                                📜
                            </div>
                            <span class="px-3 py-1 bg-white/20 text-amber-100 rounded-full text-3xs font-black uppercase tracking-wider inline-block mb-2 border border-white/20">
                                Update Status Terbaru
                            </span>
                            <h2 class="text-2xl font-black tracking-tight text-white mb-1">SURAT PENAWARAN KERJA!</h2>
                            <p class="text-xs text-amber-100 font-medium">Offer Letter Resmi Diterbitkan</p>
                        </div>

                        <div class="p-6 sm:p-8 space-y-5 text-center">
                            <div class="bg-amber-50 p-4 rounded-2xl border border-amber-200/80 text-xs space-y-1">
                                <p class="text-amber-700 font-medium">Posisi Pekerjaan:</p>
                                <p class="font-black text-amber-950 text-base">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-amber-800 font-bold">🏢 {{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed font-medium">
                                Perusahaan <strong>{{ $statusPopupApp->job->company_name }}</strong> telah menerbitkan Surat Penawaran Kerja (Offer Letter) resmi. Silakan buka untuk membaca rincian tawaran dan memberikan respon Anda.
                            </p>

                            <div class="pt-2 flex flex-col gap-2">
                                <a href="{{ route('dashboard') }}" @click="showStatusModal = false" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-xs rounded-xl shadow-lg transition border border-amber-500 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-file-contract"></i> Tinjau & Respon Offer Letter &rarr;
                                </a>
                                <button type="button" @click="showStatusModal = false" class="text-xs text-slate-400 font-bold hover:underline py-1">Nanti Saja</button>
                            </div>
                        </div>

                    @elseif($isInterview)
                        <!-- INTERVIEW MODAL -->
                        <div class="bg-gradient-to-br from-blue-600 via-indigo-700 to-slate-900 text-white p-8 text-center relative overflow-hidden">
                            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md text-blue-200 flex items-center justify-center text-3xl mx-auto mb-3 shadow-lg border border-white/20 font-black">
                                📅
                            </div>
                            <span class="px-3 py-1 bg-white/20 text-blue-100 rounded-full text-3xs font-black uppercase tracking-wider inline-block mb-2 border border-white/20">
                                Undangan Sesi Wawancara
                            </span>
                            <h2 class="text-2xl font-black tracking-tight text-white mb-1">UNDANGAN WAWANCARA!</h2>
                            <p class="text-xs text-blue-100 font-medium">Lolos ke Tahap Interview</p>
                        </div>

                        <div class="p-6 sm:p-8 space-y-5 text-center">
                            <div class="bg-blue-50 p-4 rounded-2xl border border-blue-200/80 text-xs space-y-1">
                                <p class="text-blue-600 font-medium">Posisi Pekerjaan:</p>
                                <p class="font-black text-slate-900 text-base">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 font-bold">🏢 {{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed font-medium">
                                Selamat! Berkas lamaran Anda dinilai memenuhi kriteria dan diundang untuk mengikuti sesi wawancara langsung dengan tim HR/User.
                            </p>

                            <div class="pt-2 flex flex-col gap-2">
                                <button type="button" @click="showStatusModal = false" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl shadow-lg transition border border-slate-900 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-calendar-check"></i> Cek Jadwal Wawancara &rarr;
                                </button>
                            </div>
                        </div>

                    @elseif($isTest)
                        <!-- TEST MODAL -->
                        <div class="bg-gradient-to-br from-indigo-600 via-purple-700 to-slate-900 text-white p-8 text-center relative overflow-hidden">
                            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md text-indigo-200 flex items-center justify-center text-3xl mx-auto mb-3 shadow-lg border border-white/20 font-black">
                                💻
                            </div>
                            <span class="px-3 py-1 bg-white/20 text-indigo-100 rounded-full text-3xs font-black uppercase tracking-wider inline-block mb-2 border border-white/20">
                                Undangan Ujian Online
                            </span>
                            <h2 class="text-2xl font-black tracking-tight text-white mb-1">TES ONLINE SELEKSI!</h2>
                            <p class="text-xs text-indigo-100 font-medium">Tahap Penilaian Kemampuan</p>
                        </div>

                        <div class="p-6 sm:p-8 space-y-5 text-center">
                            <div class="bg-indigo-50 p-4 rounded-2xl border border-indigo-200/80 text-xs space-y-1">
                                <p class="text-indigo-600 font-medium">Posisi Pekerjaan:</p>
                                <p class="font-black text-slate-900 text-base">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 font-bold">🏢 {{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed font-medium">
                                Lamaran Anda dinyatakan lolos ke tahap Tes Online. Silakan persiapkan diri Anda dan mulai kerjakan soal tes seleksi.
                            </p>

                            <div class="pt-2 flex flex-col gap-2">
                                <a href="{{ route('candidate.tests.show', $statusPopupApp->job) }}" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-lg transition border border-indigo-600 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-pen-to-square"></i> Mulai Kerjakan Tes Sekarang &rarr;
                                </a>
                                <button type="button" @click="showStatusModal = false" class="text-xs text-slate-400 font-bold hover:underline py-1">Nanti Saja</button>
                            </div>
                        </div>

                    @elseif($isRejected)
                        <!-- REJECTED MODAL -->
                        <div class="bg-gradient-to-br from-slate-800 via-rose-950 to-slate-900 text-white p-8 text-center relative overflow-hidden">
                            <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md text-rose-300 flex items-center justify-center text-3xl mx-auto mb-3 shadow-lg border border-white/15 font-black">
                                🛡️
                            </div>
                            <span class="px-3 py-1 bg-white/10 text-rose-200 rounded-full text-3xs font-black uppercase tracking-wider inline-block mb-2 border border-white/15">
                                Pemberitahuan Status Lamaran
                            </span>
                            <h2 class="text-xl font-black tracking-tight text-white mb-1">PEMBERITAHUAN TAHAPAN SELEKSI</h2>
                            <p class="text-xs text-slate-300 font-medium">Update Status Lamaran Kerja</p>
                        </div>

                        <div class="p-6 sm:p-8 space-y-5 text-center">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-xs space-y-1">
                                <p class="text-slate-500 font-medium">Posisi Pekerjaan:</p>
                                <p class="font-black text-slate-900 text-base">{{ $statusPopupApp->job->title }}</p>
                                <p class="text-slate-600 font-bold">🏢 {{ $statusPopupApp->job->company_name }}</p>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed font-medium">
                                Terima kasih telah berpartisipasi dalam proses seleksi posisi <strong>{{ $statusPopupApp->job->title }}</strong> di <strong>{{ $statusPopupApp->job->company_name }}</strong>. Saat ini lamaran Anda belum lolos ke tahap berikutnya. Tetap semangat, peluang karir lain menanti Anda!
                            </p>

                            <div class="pt-2 flex flex-col gap-2">
                                <a href="{{ route('jobs.index') }}" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl shadow-lg transition border border-slate-900 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-magnifying-glass"></i> Cari Lowongan Kerja Lainnya &rarr;
                                </a>
                                <button type="button" @click="showStatusModal = false" class="text-xs text-slate-400 font-bold hover:underline py-1">Tutup Pop-up</button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endif

    <x-live-chat-drawer />
</x-app-layout>
