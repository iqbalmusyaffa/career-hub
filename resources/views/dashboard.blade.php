<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-900 leading-tight">
            {{ __('Dashboard Kandidat') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-2xs flex items-center gap-3 text-xs font-bold">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <div>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @php
                $userInterviews = \App\Models\Interview::whereHas('application', function($q) {
                    $q->where('user_id', auth()->id());
                })->where('scheduled_at', '>=', now())
                  ->with('application.job')
                  ->get();
            @endphp

            @if($userInterviews->count() > 0)
                <div class="bg-slate-900 border border-slate-800 text-white p-6 rounded-2xl shadow-2xs space-y-4">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-calendar-check text-2xl text-slate-300"></i>
                        <div>
                            <h3 class="text-base font-bold">Anda Memiliki Undangan Wawancara!</h3>
                            <p class="text-xs text-slate-300">Perhatikan detail tanggal & lokasi wawancara di bawah ini:</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-2">
                        @foreach($userInterviews as $userInt)
                            <div class="bg-slate-800 text-white p-4 rounded-xl border border-slate-700 space-y-2">
                                <div class="font-bold text-sm text-slate-100">{{ $userInt->application->job->title ?? 'Pekerjaan' }}</div>
                                <div class="text-xs space-y-1 text-slate-300">
                                    <p>📅 <strong>Waktu:</strong> {{ $userInt->scheduled_at->format('d F Y, H:i') }} WIB</p>
                                    <p>📌 <strong>Tipe:</strong> <span class="uppercase font-bold text-3xs bg-slate-700 px-2 py-0.5 rounded text-slate-200 border border-slate-600">{{ $userInt->type }}</span></p>
                                    @if($userInt->location_or_link)
                                        <p>🔗 <strong>Lokasi/Link:</strong> 
                                            @if(Str::startsWith($userInt->location_or_link, 'http'))
                                                <a href="{{ $userInt->location_or_link }}" target="_blank" class="text-slate-200 underline font-bold">Buka Link Wawancara</a>
                                            @else
                                                <span class="font-semibold text-slate-200">{{ $userInt->location_or_link }}</span>
                                            @endif
                                        </p>
                                    @endif
                                    @if($userInt->notes)
                                        <p class="text-2xs text-slate-400 italic mt-2">"{{ $userInt->notes }}"</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!auth()->user()->candidateProfile || !auth()->user()->candidateProfile->cv_path)
                <div class="bg-white border border-slate-300 p-6 rounded-2xl shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-start gap-3 text-slate-800">
                        <i class="fa-solid fa-circle-exclamation text-amber-500 text-xl shrink-0 mt-0.5"></i>
                        <div>
                            <span class="font-bold block text-sm mb-0.5 text-slate-900">Perhatian: Profil Belum Lengkap!</span>
                            <span class="text-xs text-slate-600">Profil Anda belum lengkap atau CV belum diunggah. Lengkapi segera untuk dapat melamar pekerjaan.</span>
                        </div>
                    </div>
                    <a href="{{ route('profile.candidate.details.edit') }}" class="shrink-0 bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-2xs transition border border-slate-900">
                        Lengkapi Profil & Unggah CV
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

                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-2xs border border-slate-200 space-y-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-200 pb-4">
                        <div>
                            <span class="text-3xs font-bold uppercase tracking-wider text-slate-500 block">Progress Rekrutmen</span>
                            <h3 class="text-base font-bold text-slate-900 mt-0.5">Status Tahapan: {{ $latestApp->job->title }}</h3>
                        </div>
                        <div>
                            @if(is_object($latestApp->status) && method_exists($latestApp->status, 'color'))
                                <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider border border-slate-200 {{ $latestApp->status->color() }}">
                                    {{ $latestApp->status->label() }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-slate-100 text-slate-800 rounded-lg text-xs font-bold uppercase border border-slate-200">
                                    {{ $latestApp->status }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($isRejected)
                        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-3 text-xs font-bold">
                            <i class="fa-solid fa-circle-xmark text-rose-600 text-lg"></i>
                            <div>
                                <p class="font-bold text-sm">Status Lamaran: Belum Lolos Seleksi</p>
                                <p class="text-3xs text-rose-700 font-medium">Terima kasih atas partisipasi Anda. Tetap semangat melamar lowongan lainnya!</p>
                            </div>
                        </div>
                    @else
                        <!-- Stepper Bar (Dynamic Columns) -->
                        <div class="relative py-4">
                            <div class="hidden md:block absolute top-1/2 left-8 right-8 h-1 bg-slate-200 -translate-y-1/2 z-0"></div>

                            <div class="grid grid-cols-2 md:grid-cols-{{ $stageCount }} gap-4 relative z-10">
                                @foreach($stages as $idx => $stg)
                                    @php
                                        $stepIndex = $idx + 1;
                                        $isPassed = $currentStepNum > $stepIndex;
                                        $isCurrent = $currentStepNum === $stepIndex;
                                    @endphp
                                    <div class="flex flex-col items-center text-center space-y-2">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm shadow-2xs transition duration-300
                                            {{ $isCurrent ? 'bg-slate-900 text-white font-bold scale-105 border border-slate-900' : ($isPassed ? 'bg-slate-800 text-white font-bold' : 'bg-slate-100 text-slate-400 border border-slate-200') }}">
                                            @if($isPassed)
                                                <i class="fa-solid fa-check"></i>
                                            @else
                                                <i class="fa-solid {{ $stg['icon'] }}"></i>
                                            @endif
                                        </div>
                                        <div class="space-y-0.5">
                                            <span class="text-3xs font-bold block uppercase tracking-wider {{ $isCurrent ? 'text-slate-900' : ($isPassed ? 'text-slate-700' : 'text-slate-400') }}">
                                                Langkah {{ $stepIndex }}
                                            </span>
                                            <span class="text-xs font-bold leading-tight block {{ $isCurrent ? 'text-slate-900' : ($isPassed ? 'text-slate-700' : 'text-slate-400') }}">
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

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Stat Cards -->
                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-500 mb-1">Total Lamaran</p>
                        <p class="text-3xl font-bold text-slate-900">{{ $totalApplications ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 border border-slate-200">
                        <i class="fa-solid fa-file-lines text-base"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-500 mb-1">Diproses</p>
                        <p class="text-3xl font-bold text-amber-600">{{ $processingApplications ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-200">
                        <i class="fa-solid fa-spinner text-base"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-500 mb-1">Diterima</p>
                        <p class="text-3xl font-bold text-emerald-600">{{ $acceptedApplications ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-200">
                        <i class="fa-solid fa-circle-check text-base"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-500 mb-1">Ditolak</p>
                        <p class="text-3xl font-bold text-rose-600">{{ $rejectedApplications ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-200">
                        <i class="fa-solid fa-circle-xmark text-base"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Applications -->
                <div class="bg-white p-6 rounded-2xl shadow-2xs border border-slate-200 flex flex-col h-full">
                    <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-slate-700"></i>
                        Lamaran Terakhir Saya
                    </h3>
                    
                    <div class="flex-1">
                        @if(isset($recentApplications) && $recentApplications->count() > 0)
                            <ul class="space-y-4">
                                @foreach($recentApplications as $app)
                                    <li class="border-b border-slate-100 pb-4 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <a href="{{ route('jobs.show', $app->job_id) }}" class="font-bold text-slate-900 hover:text-slate-700 text-sm transition">{{ $app->job->title }}</a>
                                                <p class="text-xs text-slate-500">{{ $app->job->division }}</p>
                                            </div>
                                            <span class="px-2.5 py-0.5 text-3xs font-bold rounded-lg border uppercase border-slate-200 bg-slate-50 text-slate-700">
                                                {{ $app->status }}
                                            </span>
                                        </div>
                                        <div class="text-3xs text-slate-400 mt-2 font-medium">Dilamar pada: {{ $app->created_at->format('d M Y') }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="flex flex-col items-center justify-center h-full py-8 text-slate-400 text-xs">
                                <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-300"></i>
                                <p>Anda belum melamar pekerjaan apapun.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Latest Jobs -->
                <div class="bg-white p-6 rounded-2xl shadow-2xs border border-slate-200 flex flex-col h-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-briefcase text-slate-700"></i>
                            Lowongan Terbaru
                        </h3>
                        <a href="{{ route('jobs.index') }}" class="text-xs font-bold text-slate-700 hover:text-slate-900 transition flex items-center gap-1">
                            Lihat Semua &rarr;
                        </a>
                    </div>
                    
                    <div class="flex-1">
                        @php
                            $latestJobs = \App\Models\Job::where('status', 'active')->latest()->take(3)->get();
                        @endphp

                        @if($latestJobs->count() > 0)
                            <ul class="space-y-3">
                                @foreach($latestJobs as $job)
                                    <li class="group border border-slate-200 p-4 rounded-xl hover:bg-slate-50 transition cursor-pointer" onclick="window.location.href='{{ route('jobs.show', $job->id) }}'">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-bold text-slate-900 text-sm group-hover:text-slate-700 transition">{{ $job->title }}</h4>
                                                <p class="text-xs text-slate-500">{{ $job->location }}</p>
                                            </div>
                                            <span class="text-3xs font-bold bg-slate-100 text-slate-800 border border-slate-200 px-2 py-0.5 rounded-md uppercase">{{ $job->work_type }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="flex flex-col items-center justify-center h-full py-8 text-slate-400 text-xs">
                                <p>Belum ada lowongan tersedia saat ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
