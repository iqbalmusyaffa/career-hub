<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-xs shrink-0">
                        <i class="fa-solid fa-paper-plane"></i>
                    </span>
                    <span>Lamaran & Riwayat Karir Saya</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Pantau progress status seleksi, jadwal wawancara, dan surat penawaran kerja (offer letter) dari perusahaan impian Anda.
                </p>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('jobs.index') }}" class="w-full sm:w-auto text-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-xs flex items-center gap-2 border border-blue-600">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i> 
                    <span>Cari Lowongan Baru</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-slate-50/70 dark:bg-slate-950/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. STAT METRICS CARDS -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <a href="{{ route('candidate.applications.index') }}" class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:border-blue-500 transition group">
                    <div class="flex items-center justify-between">
                        <span class="text-3xs sm:text-2xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Lamaran</span>
                        <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ $totalCount }}</div>
                    <p class="text-3xs text-slate-400 mt-0.5">Semua posisi yang pernah dilamar</p>
                </a>

                <a href="{{ route('candidate.applications.index', ['status' => 'review']) }}" class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:border-amber-500 transition group">
                    <div class="flex items-center justify-between">
                        <span class="text-3xs sm:text-2xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Dalam Seleksi</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ $reviewCount }}</div>
                    <p class="text-3xs text-slate-400 mt-0.5">Screening & evaluasi profil</p>
                </a>

                <a href="{{ route('candidate.applications.index', ['status' => 'interview']) }}" class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:border-purple-500 transition group">
                    <div class="flex items-center justify-between">
                        <span class="text-3xs sm:text-2xs font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">Tahap Wawancara</span>
                        <div class="w-8 h-8 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ $interviewCount }}</div>
                    <p class="text-3xs text-slate-400 mt-0.5">Sesi interview dengan HR / User</p>
                </a>

                <a href="{{ route('candidate.applications.index', ['status' => 'accepted']) }}" class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:border-emerald-500 transition group">
                    <div class="flex items-center justify-between">
                        <span class="text-3xs sm:text-2xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Penawaran & Diterima</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ $acceptedCount }}</div>
                    <p class="text-3xs text-slate-400 mt-0.5">Offer letter & lolos seleksi</p>
                </a>
            </div>

            <!-- 2. SEARCH & FILTER CONTROLS -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-2xs border border-slate-200/80 dark:border-slate-800 space-y-3">
                <div class="flex flex-col md:flex-row items-center justify-between gap-3">
                    
                    <!-- Tab Filter Pills -->
                    <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 scrollbar-none">
                        @php
                            $currentStatus = request('status', 'all');
                        @endphp
                        <a href="{{ route('candidate.applications.index', array_merge(request()->except(['status', 'page']))) }}" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition shrink-0 {{ $currentStatus === 'all' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                            Semua ({{ $totalCount }})
                        </a>
                        <a href="{{ route('candidate.applications.index', array_merge(request()->except(['page']), ['status' => 'review'])) }}" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition shrink-0 {{ $currentStatus === 'review' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                            Dalam Review ({{ $reviewCount }})
                        </a>
                        <a href="{{ route('candidate.applications.index', array_merge(request()->except(['page']), ['status' => 'interview'])) }}" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition shrink-0 {{ $currentStatus === 'interview' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                            Wawancara ({{ $interviewCount }})
                        </a>
                        <a href="{{ route('candidate.applications.index', array_merge(request()->except(['page']), ['status' => 'accepted'])) }}" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition shrink-0 {{ $currentStatus === 'accepted' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                            Diterima / Offer ({{ $acceptedCount }})
                        </a>
                        <a href="{{ route('candidate.applications.index', array_merge(request()->except(['page']), ['status' => 'rejected'])) }}" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition shrink-0 {{ $currentStatus === 'rejected' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                            Belum Lolos ({{ $rejectedCount }})
                        </a>
                    </div>

                    <!-- Search Box Form -->
                    <form method="GET" action="{{ route('candidate.applications.index') }}" class="w-full md:w-72 relative">
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari posisi atau perusahaan..." 
                               class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white pl-9 pr-4 py-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 3. APPLICATIONS LIST -->
            @if($applications->count() > 0)
                <div class="space-y-4">
                    @foreach($applications as $app)
                        @php
                            $statusVal = is_object($app->status) ? $app->status->value : (string)$app->status;
                            $job = $app->job;
                        @endphp
                        <div class="bg-white dark:bg-slate-900 rounded-3xl p-5 sm:p-6 shadow-xs border border-slate-200/80 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 transition space-y-4">
                            
                            <!-- Header Row: Job Title, Company, Status Badge -->
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-700 dark:text-slate-300 font-bold text-lg shrink-0 shadow-2xs">
                                        <i class="fa-solid fa-building text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="font-extrabold text-base sm:text-lg text-slate-900 dark:text-white leading-snug">
                                                @if($job)
                                                    <a href="{{ route('jobs.show', $job->hash_id ?? $job->id) }}" class="hover:text-blue-600 dark:hover:text-blue-400 hover:underline">
                                                        {{ $job->title }}
                                                    </a>
                                                @else
                                                    Posisi Tidak Tersedia
                                                @endif
                                            </h3>
                                        </div>
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mt-0.5 flex items-center gap-2 flex-wrap">
                                            <span>{{ $job->company_name ?? 'Perusahaan Mitra' }}</span>
                                            @if($job && $job->location)
                                                <span>&bull;</span>
                                                <span class="flex items-center gap-1 text-slate-500"><i class="fa-solid fa-location-dot text-[10px]"></i> {{ $job->location }}</span>
                                            @endif
                                            @if($job && $job->work_type)
                                                <span>&bull;</span>
                                                <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-md text-[10px] font-bold text-slate-600 dark:text-slate-300">{{ $job->work_type }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="shrink-0 self-start sm:self-auto">
                                    @if(in_array($statusVal, ['accepted', 'hired']))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 text-xs font-extrabold rounded-xl border border-emerald-300 dark:border-emerald-800 shadow-2xs">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Diterima Bekerja
                                        </span>
                                    @elseif($statusVal === 'offered')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-100 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 text-xs font-extrabold rounded-xl border border-teal-300 dark:border-teal-800 shadow-2xs">
                                            <i class="fa-solid fa-file-contract text-teal-600"></i> Penawaran (Offer Letter)
                                        </span>
                                    @elseif(in_array($statusVal, ['interview', 'interview_hr', 'interview_user']))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-100 dark:bg-purple-950/60 text-purple-800 dark:text-purple-300 text-xs font-extrabold rounded-xl border border-purple-300 dark:border-purple-800 shadow-2xs">
                                            <i class="fa-solid fa-calendar-days text-purple-600 animate-pulse"></i> Tahap Wawancara
                                        </span>
                                    @elseif($statusVal === 'test')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-100 dark:bg-indigo-950/60 text-indigo-800 dark:text-indigo-300 text-xs font-extrabold rounded-xl border border-indigo-300 dark:border-indigo-800 shadow-2xs">
                                            <i class="fa-solid fa-file-pen text-indigo-600"></i> Ujian Online / Test
                                        </span>
                                    @elseif($statusVal === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 text-xs font-extrabold rounded-xl border border-rose-300 dark:border-rose-800 shadow-2xs">
                                            <i class="fa-solid fa-circle-xmark text-rose-600"></i> Belum Lolos
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-xs font-extrabold rounded-xl border border-amber-300 dark:border-amber-800 shadow-2xs">
                                            <i class="fa-solid fa-hourglass-half text-amber-600"></i> Sedang Ditinjau HR
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- INTERVIEW BANNER CALLOUT (IF SCHEDULED) -->
                            @if($app->interview)
                                <div class="p-4 bg-purple-50 dark:bg-purple-950/40 rounded-2xl border border-purple-200 dark:border-purple-800 space-y-2 text-xs">
                                    <div class="flex items-center justify-between font-bold text-purple-900 dark:text-purple-200">
                                        <span class="flex items-center gap-2">
                                            <i class="fa-solid fa-calendar-check text-purple-600 text-sm"></i>
                                            <span>Jadwal Wawancara Anda</span>
                                        </span>
                                        <span class="px-2 py-0.5 bg-purple-200 dark:bg-purple-900 text-purple-900 dark:text-purple-200 text-3xs rounded-md uppercase font-black">
                                            {{ strtoupper($app->interview->type ?? 'ONLINE') }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-700 dark:text-slate-300 pt-1">
                                        <div>
                                            <span class="text-3xs font-bold text-slate-400 block uppercase">Waktu Sesi:</span>
                                            <strong>{{ $app->interview->scheduled_at ? $app->interview->scheduled_at->format('d M Y, H:i') : '-' }} WIB</strong>
                                        </div>
                                        <div>
                                            <span class="text-3xs font-bold text-slate-400 block uppercase">Lokasi / Tautan:</span>
                                            @if($app->interview->location_or_link && (filter_var($app->interview->location_or_link, FILTER_VALIDATE_URL) || str_starts_with($app->interview->location_or_link, 'http')))
                                                <a href="{{ $app->interview->location_or_link }}" target="_blank" class="text-blue-600 dark:text-blue-400 font-extrabold hover:underline flex items-center gap-1">
                                                    <span>Buka Google Meet / Zoom</span>
                                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                                </a>
                                            @else
                                                <strong>{{ $app->interview->location_or_link ?? 'Menunggu konfirmasi HR' }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                    @if($app->interview->notes)
                                        <p class="text-3xs text-slate-500 dark:text-slate-400 pt-1 border-t border-purple-200/60 dark:border-purple-800">
                                            <strong>Catatan HR:</strong> {{ $app->interview->notes }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <!-- OFFER LETTER BANNER (IF OFFERED / ACCEPTED) -->
                            @if($app->offerLetter)
                                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 rounded-2xl border border-emerald-200 dark:border-emerald-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                    <div class="space-y-1">
                                        <div class="font-extrabold text-emerald-900 dark:text-emerald-200 flex items-center gap-2">
                                            <i class="fa-solid fa-file-signature text-emerald-600"></i>
                                            <span>Surat Penawaran Kerja (Offer Letter) Resmi</span>
                                        </div>
                                        <p class="text-3xs text-emerald-700 dark:text-emerald-300">
                                            Gaji Ditawarkan: <strong>{{ $app->offerLetter->offered_salary }}</strong> • Mulai: <strong>{{ $app->offerLetter->start_date ? \Carbon\Carbon::parse($app->offerLetter->start_date)->format('d M Y') : '-' }}</strong>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        @if($app->offerLetter->document_path ?? null)
                                            <a href="{{ Storage::url($app->offerLetter->document_path) }}" target="_blank" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                                                <i class="fa-solid fa-download"></i> Unduh Offer Letter PDF
                                            </a>
                                        @endif
                                        @if(in_array($statusVal, ['accepted', 'hired']))
                                            <a href="{{ route('candidate.onboarding.create', $app->id) }}" class="px-3 py-1.5 bg-slate-900 dark:bg-slate-800 hover:bg-black text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                                                <i class="fa-solid fa-id-card"></i> Form Onboarding
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Bottom Meta & Action Buttons -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-slate-100 dark:border-slate-800 text-xs">
                                <div class="text-3xs text-slate-400 dark:text-slate-500 font-medium">
                                    <span>Dilamar pada: <strong>{{ $app->created_at->format('d M Y, H:i') }} WIB</strong></span>
                                    <span>• Terakhir diperbarui: {{ $app->updated_at->diffForHumans() }}</span>
                                </div>

                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($job)
                                        <a href="{{ route('jobs.show', $job->hash_id ?? $job->id) }}" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl text-xs transition">
                                            Rincian Lowongan
                                        </a>
                                    @endif

                                    <!-- Chat HR Message Trigger -->
                                    <button type="button" 
                                            onclick="alert('Fitur obrolan langsung terhubung dengan tim HR {{ $job->company_name ?? 'Perusahaan' }}. Silakan periksa kotak masuk notifikasi Anda.')"
                                            class="px-3 py-1.5 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-700 dark:text-blue-300 font-bold rounded-xl text-xs transition border border-blue-200 dark:border-blue-800 flex items-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-comments text-xs"></i>
                                        <span>Hubungi HR</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="pt-4">
                        {{ $applications->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-10 sm:p-14 text-center border border-slate-200/80 dark:border-slate-800 space-y-4 shadow-xs">
                    <div class="w-16 h-16 rounded-3xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl mx-auto shadow-2xs">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <div class="max-w-md mx-auto space-y-1.5">
                        <h3 class="font-extrabold text-lg text-slate-900 dark:text-white">Belum Ada Lamaran Ditemukan</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            @if(request('q') || request('status'))
                                Tidak ada data lamaran yang sesuai dengan filter pencarian Anda. Silakan coba kata kunci lain.
                            @else
                                Anda belum mengajukan lamaran ke lowongan pekerjaan mana pun. Temukan posisi impian Anda sekarang!
                            @endif
                        </p>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-xs transition">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <span>Jelajahi Lowongan Tersedia</span>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
