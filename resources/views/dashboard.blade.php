<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Kandidat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h4 class="font-semibold">Berhasil</h4>
                        <p class="text-sm">{{ session('success') }}</p>
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
                <div class="bg-amber-500 text-white p-6 rounded-xl shadow-lg space-y-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <div>
                            <h3 class="text-lg font-extrabold">Anda Memiliki Undangan Wawancara!</h3>
                            <p class="text-sm text-amber-100">Perhatikan detail tanggal & lokasi wawancara di bawah ini:</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-2">
                        @foreach($userInterviews as $userInt)
                            <div class="bg-white text-gray-900 p-4 rounded-lg shadow-sm border border-amber-200">
                                <div class="font-bold text-base text-blue-600 mb-1">{{ $userInt->application->job->title ?? 'Pekerjaan' }}</div>
                                <div class="text-sm space-y-1">
                                    <p>📅 <strong>Waktu:</strong> {{ $userInt->scheduled_at->format('d F Y, H:i') }} WIB</p>
                                    <p>📌 <strong>Tipe:</strong> <span class="uppercase font-bold text-xs bg-amber-100 px-2 py-0.5 rounded text-amber-800">{{ $userInt->type }}</span></p>
                                    @if($userInt->location_or_link)
                                        <p>🔗 <strong>Lokasi/Link:</strong> 
                                            @if(Str::startsWith($userInt->location_or_link, 'http'))
                                                <a href="{{ $userInt->location_or_link }}" target="_blank" class="text-blue-600 underline font-semibold">Buka Link Wawancara</a>
                                            @else
                                                <span class="font-semibold">{{ $userInt->location_or_link }}</span>
                                            @endif
                                        </p>
                                    @endif
                                    @if($userInt->notes)
                                        <p class="text-xs text-gray-500 italic mt-2">"{{ $userInt->notes }}"</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!auth()->user()->candidateProfile || !auth()->user()->candidateProfile->cv_path)
                <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 p-6 rounded-xl shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-start gap-3 text-amber-800">
                        <svg class="w-6 h-6 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <div>
                            <span class="font-bold block text-lg mb-1">Perhatian: Profil Belum Lengkap!</span>
                            <span>Profil Anda belum lengkap atau CV belum diunggah. Lengkapi segera untuk dapat melamar pekerjaan.</span>
                        </div>
                    </div>
                    <a href="{{ route('profile.candidate.details.edit') }}" class="shrink-0 bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-lg font-semibold shadow-md transition-all hover:shadow-lg transform hover:-translate-y-0.5">
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

                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-3xs font-black uppercase tracking-wider text-blue-600 block">🗺️ Visual Selection Progress Tracker</span>
                            <h3 class="text-lg font-black text-slate-900 mt-0.5">Status Tahapan Rekrutmen: {{ $latestApp->job->title }}</h3>
                        </div>
                        <div>
                            @if(is_object($latestApp->status) && method_exists($latestApp->status, 'color'))
                                <span class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider shadow-2xs {{ $latestApp->status->color() }}">
                                    {{ $latestApp->status->label() }}
                                </span>
                            @else
                                <span class="px-3.5 py-1.5 bg-blue-100 text-blue-800 rounded-xl text-xs font-extrabold uppercase">
                                    {{ $latestApp->status }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($isRejected)
                        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-xs font-bold">
                            <i class="fa-solid fa-circle-xmark text-rose-600 text-xl"></i>
                            <div>
                                <p class="font-extrabold text-sm">Status Lamaran: Belum Lolos Seleksi</p>
                                <p class="text-3xs text-rose-700 font-medium">Terima kasih atas partisipasi Anda. Tetap semangat melamar lowongan lainnya di TalentFlow!</p>
                            </div>
                        </div>
                    @else
                        <!-- Stepper Bar (Dynamic Columns) -->
                        <div class="relative py-4">
                            <div class="hidden md:block absolute top-1/2 left-8 right-8 h-1 bg-slate-100 -translate-y-1/2 z-0"></div>

                            <div class="grid grid-cols-2 md:grid-cols-{{ $stageCount }} gap-4 relative z-10">
                                @foreach($stages as $idx => $stg)
                                    @php
                                        $stepIndex = $idx + 1;
                                        $isPassed = $currentStepNum > $stepIndex;
                                        $isCurrent = $currentStepNum === $stepIndex;
                                    @endphp
                                    <div class="flex flex-col items-center text-center space-y-2">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-base shadow-sm transition duration-300
                                            {{ $isCurrent ? 'bg-blue-600 text-white ring-4 ring-blue-100 font-black scale-110' : ($isPassed ? 'bg-emerald-500 text-white font-bold' : 'bg-slate-100 text-slate-400 border border-slate-200') }}">
                                            @if($isPassed)
                                                <i class="fa-solid fa-check"></i>
                                            @else
                                                <i class="fa-solid {{ $stg['icon'] }}"></i>
                                            @endif
                                        </div>
                                        <div class="space-y-0.5">
                                            <span class="text-3xs font-black block uppercase tracking-wider {{ $isCurrent ? 'text-blue-600' : ($isPassed ? 'text-emerald-600' : 'text-slate-400') }}">
                                                Langkah {{ $stepIndex }}
                                            </span>
                                            <span class="text-xs font-extrabold leading-tight block {{ $isCurrent ? 'text-slate-900 font-black' : ($isPassed ? 'text-slate-700' : 'text-slate-400') }}">
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
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Lamaran</p>
                        <p class="text-4xl font-extrabold text-blue-600">{{ $totalApplications ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Diproses</p>
                        <p class="text-4xl font-extrabold text-amber-500">{{ $processingApplications ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Diterima</p>
                        <p class="text-4xl font-extrabold text-green-600">{{ $acceptedApplications ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Ditolak</p>
                        <p class="text-4xl font-extrabold text-red-600">{{ $rejectedApplications ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Applications -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col h-full">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Lamaran Terakhir Saya
                    </h3>
                    
                    <div class="flex-1">
                        @if(isset($recentApplications) && $recentApplications->count() > 0)
                            <ul class="space-y-4">
                                @foreach($recentApplications as $app)
                                    <li class="border-b border-gray-50 pb-4 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <a href="{{ route('jobs.show', $app->job_id) }}" class="font-semibold text-gray-900 hover:text-blue-600 transition">{{ $app->job->title }}</a>
                                                <p class="text-sm text-gray-500">{{ $app->job->division }}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($app->status === 'Pending') bg-gray-100 text-gray-700
                                                @elseif($app->status === 'Processing') bg-amber-100 text-amber-700
                                                @elseif($app->status === 'Accepted') bg-green-100 text-green-700
                                                @elseif($app->status === 'Rejected') bg-red-100 text-red-700
                                                @endif
                                            ">
                                                {{ $app->status }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-400 mt-2">Dilamar pada: {{ $app->created_at->format('d M Y') }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="flex flex-col items-center justify-center h-full py-8 text-gray-400">
                                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p>Anda belum melamar pekerjaan apapun.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Latest Jobs -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col h-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Lowongan Terbaru
                        </h3>
                        <a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition flex items-center">
                            Lihat Semua &rarr;
                        </a>
                    </div>
                    
                    <div class="flex-1">
                        @php
                            $latestJobs = \App\Models\Job::where('status', 'active')->latest()->take(3)->get();
                        @endphp

                        @if($latestJobs->count() > 0)
                            <ul class="space-y-4">
                                @foreach($latestJobs as $job)
                                    <li class="group border border-gray-100 p-4 rounded-lg hover:border-blue-200 hover:shadow-sm transition cursor-pointer" onclick="window.location.href='{{ route('jobs.show', $job->id) }}'">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition">{{ $job->title }}</h4>
                                                <p class="text-sm text-gray-500">{{ $job->location }}</p>
                                            </div>
                                            <span class="text-xs font-semibold bg-indigo-50 text-indigo-600 px-2 py-1 rounded">{{ $job->work_type }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="flex flex-col items-center justify-center h-full py-8 text-gray-400">
                                <p>Belum ada lowongan tersedia saat ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
