<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.applications.index') }}" class="w-10 h-10 bg-white hover:bg-slate-100 text-slate-600 rounded-2xl border border-slate-200 flex items-center justify-center transition shadow-2xs">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                        Detail Lamaran: <span class="text-blue-600">{{ $application->user->name }}</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Posisi: <strong>{{ $application->job->title }}</strong> ({{ $application->job->division }}) • Dilamar pada {{ $application->created_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>
            <div>
                @php
                    $statusStr = is_object($application->status) ? $application->status->value : (string) $application->status;
                @endphp
                @if($statusStr == 'pending')
                    <span class="px-4 py-2 bg-amber-50 text-amber-800 text-xs font-extrabold rounded-2xl border border-amber-200 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-clock"></i> Menunggu Review
                    </span>
                @elseif($statusStr == 'reviewed' || $statusStr == 'reviewing')
                    <span class="px-4 py-2 bg-blue-50 text-blue-800 text-xs font-extrabold rounded-2xl border border-blue-200 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-eye"></i> Sedang Direview
                    </span>
                @elseif($statusStr == 'test')
                    <span class="px-4 py-2 bg-indigo-50 text-indigo-800 text-xs font-extrabold rounded-2xl border border-indigo-200 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-pen-to-square"></i> Tahap Tes Online
                    </span>
                @elseif($statusStr == 'interview')
                    <span class="px-4 py-2 bg-purple-50 text-purple-800 text-xs font-extrabold rounded-2xl border border-purple-200 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-calendar-check"></i> Tahap Wawancara
                    </span>
                @elseif($statusStr == 'offered')
                    <span class="px-4 py-2 bg-emerald-50 text-emerald-800 text-xs font-extrabold rounded-2xl border border-emerald-200 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-file-contract"></i> Tahap Penawaran Kerja
                    </span>
                @elseif($statusStr == 'accepted' || $statusStr == 'hired')
                    <span class="px-4 py-2 bg-emerald-600 text-white text-xs font-extrabold rounded-2xl border border-emerald-700 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check"></i> Diterima (Hired)
                    </span>
                @elseif($statusStr == 'rejected')
                    <span class="px-4 py-2 bg-rose-50 text-rose-800 text-xs font-extrabold rounded-2xl border border-rose-200 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-xmark text-rose-600"></i> Ditolak
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- MAIN GRID LAYOUT -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <!-- LEFT COLUMN: CANDIDATE PROFILE & ONBOARDING DATA (1 COL) -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Candidate Card -->
                    <div class="bg-white rounded-3xl p-6 shadow-2xs border border-slate-200/80 space-y-5">
                        @php
                            $candPhoto = ($application->user->candidateProfile && ($application->user->candidateProfile->photo || $application->user->candidateProfile->photo_path))
                                ? Storage::url($application->user->candidateProfile->photo ?? $application->user->candidateProfile->photo_path)
                                : null;
                        @endphp

                        <div class="flex flex-col items-center text-center pb-5 border-b border-slate-100">
                            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-md bg-slate-100 mb-3 relative">
                                @if($candPhoto)
                                    <img src="{{ $candPhoto }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-blue-600 text-white font-black flex items-center justify-center text-3xl">
                                        {{ strtoupper(substr($application->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <h3 class="font-black text-lg text-slate-900 leading-snug">{{ $application->user->name }}</h3>
                            <p class="text-xs font-bold text-blue-600 mt-0.5">{{ $application->user->candidateProfile->current_position ?? 'Pelamar Pekerjaan' }}</p>
                        </div>

                        <div class="space-y-3 text-xs">
                            <div class="flex items-center gap-3 text-slate-700">
                                <i class="fa-solid fa-envelope text-slate-400 w-4 text-center"></i>
                                <a href="mailto:{{ $application->user->email }}" class="text-blue-600 font-semibold hover:underline truncate">{{ $application->user->email }}</a>
                            </div>

                            @if(optional($application->user->candidateProfile)->phone)
                                <div class="flex items-center gap-3 text-slate-700">
                                    <i class="fa-solid fa-phone text-slate-400 w-4 text-center"></i>
                                    <a href="tel:{{ $application->user->candidateProfile->phone }}" class="text-blue-600 font-semibold hover:underline">{{ $application->user->candidateProfile->phone }}</a>
                                </div>
                            @endif

                            @if(optional($application->user->candidateProfile)->last_education)
                                <div class="flex items-start gap-3 text-slate-700">
                                    <i class="fa-solid fa-graduation-cap text-slate-400 w-4 text-center mt-1"></i>
                                    <div>
                                        <span class="text-3xs uppercase font-extrabold text-slate-400 block">Pendidikan & Jurusan</span>
                                        <span class="font-black text-slate-900 text-xs">{{ $application->user->candidateProfile->last_education }}</span>
                                        @if(optional($application->user->candidateProfile)->major)
                                            <span class="block text-2xs font-bold text-blue-600">Jurusan: {{ $application->user->candidateProfile->major }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Check Educations Array if available -->
                            @if(optional($application->user->candidateProfile)->educations && is_array($application->user->candidateProfile->educations))
                                <div class="pt-3 border-t border-slate-100 space-y-1.5">
                                    <span class="text-3xs font-black uppercase text-slate-400 tracking-wider block">Riwayat Pendidikan & Jurusan</span>
                                    @foreach($application->user->candidateProfile->educations as $edu)
                                        <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200 text-xs">
                                            <div class="font-extrabold text-slate-900">{{ $edu['institution'] ?? ($edu['school'] ?? '-') }}</div>
                                            <div class="text-2xs font-bold text-blue-600">{{ $edu['degree'] ?? '' }} {{ $edu['major'] ?? ($edu['field_of_study'] ?? '-') }}</div>
                                            <div class="text-3xs text-slate-400 font-medium">{{ $edu['start_year'] ?? '' }} - {{ $edu['end_year'] ?? 'Sekarang' }} @if(!empty($edu['gpa'])) • IPK: {{ $edu['gpa'] }} @endif</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Candidate Uploaded Vault Documents (Ijazah, Transkrip, KTP, Sertifikat) -->
                        @php
                            $candidateDocs = \App\Models\CandidateDocument::where('user_id', $application->user_id)->get();
                        @endphp
                        @if($candidateDocs->count() > 0 || optional($application->user->candidateProfile)->cv_path)
                            <div class="pt-4 border-t border-slate-100 space-y-2">
                                <label class="block text-3xs font-black uppercase text-slate-400 tracking-wider">📁 Dokumen & Berkas Pendukung Candidate</label>
                                <div class="space-y-1.5 text-xs">
                                    @if(optional($application->user->candidateProfile)->cv_path)
                                        <a href="{{ Storage::url($application->user->candidateProfile->cv_path) }}" target="_blank" class="w-full bg-slate-900 hover:bg-black text-white font-bold py-2 px-3 rounded-xl transition text-3xs flex items-center justify-between shadow-2xs">
                                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-file-pdf text-amber-400"></i> Curriculum Vitae (CV Asli)</span>
                                            <span>Unduh &rarr;</span>
                                        </a>
                                    @endif

                                    @foreach($candidateDocs as $doc)
                                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="w-full bg-slate-50 hover:bg-blue-50 text-slate-800 hover:text-blue-900 font-bold py-2 px-3 rounded-xl border border-slate-200 transition text-3xs flex items-center justify-between shadow-2xs">
                                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-file-lines text-blue-600"></i> {{ $doc->document_type ?? 'Dokumen Pendukung' }} ({{ $doc->file_name }})</span>
                                            <span>Lihat &rarr;</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- PDF CV System Generator Widget -->
                    <div class="bg-slate-900 text-white rounded-3xl p-5 shadow-2xs border border-slate-800 space-y-4">
                        <div class="flex items-center gap-3 border-b border-slate-800 pb-3">
                            <div class="w-9 h-9 bg-slate-800 rounded-lg flex items-center justify-center font-bold text-white border border-slate-700">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-xs text-white">Cetak PDF CV System</h4>
                                <p class="text-3xs text-slate-400">Format standar profesional otomatis.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <a href="{{ route('admin.applications.cv.ats', $application->id) }}" target="_blank" class="bg-indigo-800/60 hover:bg-indigo-700 text-white font-bold p-2.5 rounded-xl border border-indigo-700 text-center transition flex flex-col items-center gap-1">
                                <i class="fa-solid fa-robot text-blue-400 text-sm"></i>
                                <span class="text-3xs">CV Format ATS</span>
                            </a>
                            <a href="{{ route('admin.applications.cv.creative', $application->id) }}" target="_blank" class="bg-purple-800/60 hover:bg-purple-700 text-white font-bold p-2.5 rounded-xl border border-purple-700 text-center transition flex flex-col items-center gap-1">
                                <i class="fa-solid fa-palette text-amber-400 text-sm"></i>
                                <span class="text-3xs">CV Format Kreatif</span>
                            </a>
                        </div>
                    </div>

                    <!-- Video Screening Review Widget -->
                    @if($application->screening_video_url)
                        <div class="bg-white rounded-3xl p-5 shadow-2xs border border-slate-200/80 space-y-4">
                            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm font-black border border-rose-100">
                                    <i class="fa-solid fa-video"></i>
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-xs text-slate-900">Screening Video Perkenalan</h4>
                                    <p class="text-3xs text-slate-400 font-medium">Link rekaman yang dikirim kandidat</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                @php
                                    $vUrl = $application->screening_video_url;
                                    $embedUrl = null;
                                    if (str_contains($vUrl, 'youtube.com/watch?v=')) {
                                        $embedUrl = str_replace('watch?v=', 'embed/', $vUrl);
                                    } elseif (str_contains($vUrl, 'youtu.be/')) {
                                        $embedUrl = str_replace('youtu.be/', 'youtube.com/embed/', $vUrl);
                                    }
                                @endphp

                                @if($embedUrl)
                                    <div class="w-full h-44 rounded-2xl overflow-hidden border border-slate-200 shadow-inner bg-black">
                                        <iframe src="{{ $embedUrl }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                @endif

                                <a href="{{ $vUrl }}" target="_blank" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-play"></i> Buka Video Screening di Tab Baru &rarr;
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Match Score & Test Results Widget -->
                    <div class="space-y-4">
                        @php
                            $matchScore = $application->job ? $application->job->calculateMatchScore($application->user->candidateProfile) : 0;
                            $candidateTestResult = \App\Models\CandidateTestResult::where('user_id', $application->user_id)
                                ->where('job_id', $application->job_id)
                                ->first();
                        @endphp

                        <!-- Match Score Card -->
                        <div class="bg-white rounded-3xl p-5 shadow-2xs border border-slate-200/80 text-center space-y-1">
                            <span class="text-3xs font-black uppercase tracking-wider text-slate-400 block">Kesesuaian Kualifikasi Lowongan</span>
                            <div class="text-3xl font-black text-blue-600">🎯 {{ $matchScore }}%</div>
                            <p class="text-3xs text-slate-500">Dihitung otomatis berdasarkan match score skill & kriteria.</p>
                        </div>

                        <!-- Test Score Card -->
                        @if($candidateTestResult)
                            <div class="rounded-3xl p-5 shadow-2xs border text-center space-y-3 {{ $candidateTestResult->passed ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-rose-50 border-rose-200 text-rose-900' }}">
                                <span class="text-3xs font-black uppercase tracking-wider block opacity-75">Hasil Ujian Online Seleksi</span>
                                <div class="text-3xl font-black">{{ $candidateTestResult->score }}%</div>
                                <span class="inline-block px-3 py-1 font-black text-3xs rounded-full uppercase {{ $candidateTestResult->passed ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                                    {{ $candidateTestResult->passed ? '✓ LOLOS KKM' : '✗ TIDAK MEMENUHI KKM' }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Structured Interview Scorecard Rating Widget -->
                    <div class="bg-white rounded-3xl p-5 shadow-2xs border border-slate-200/80 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h4 class="font-extrabold text-xs text-slate-900 uppercase flex items-center gap-2">
                                <i class="fa-solid fa-star text-amber-500"></i> Matriks Scorecard Penilaian Wawancara
                            </h4>
                            @if($application->scorecards->count() > 0)
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-900 border border-amber-200 text-3xs font-black rounded-lg">
                                    Avg: ⭐ {{ number_format($application->scorecards->avg('average_score'), 1) }}/5.0
                                </span>
                            @endif
                        </div>

                        <!-- Form Penilaian Scorecard -->
                        <form action="{{ route('admin.applications.scorecards.store', $application) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <label class="block font-bold text-slate-700 text-3xs uppercase mb-1">Technical Competency</label>
                                    <select name="technical_score" class="w-full border-slate-300 rounded-xl text-xs font-bold p-2 focus:ring-amber-500 focus:border-amber-500">
                                        <option value="5">⭐⭐⭐⭐⭐ 5 - Expert / Outstanding</option>
                                        <option value="4">⭐⭐⭐⭐ 4 - Above Average</option>
                                        <option value="3" selected>⭐⭐⭐ 3 - Meets Expectation</option>
                                        <option value="2">⭐⭐ 2 - Below Expectation</option>
                                        <option value="1">⭐ 1 - Poor / Unsatisfactory</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 text-3xs uppercase mb-1">Communication Skill</label>
                                    <select name="communication_score" class="w-full border-slate-300 rounded-xl text-xs font-bold p-2 focus:ring-amber-500 focus:border-amber-500">
                                        <option value="5">⭐⭐⭐⭐⭐ 5 - Excellent Speaker</option>
                                        <option value="4">⭐⭐⭐⭐ 4 - Good & Clear</option>
                                        <option value="3" selected>⭐⭐⭐ 3 - Average</option>
                                        <option value="2">⭐⭐ 2 - Hesitant</option>
                                        <option value="1">⭐ 1 - Poor Communication</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 text-3xs uppercase mb-1">Problem Solving & Logic</label>
                                    <select name="problem_solving_score" class="w-full border-slate-300 rounded-xl text-xs font-bold p-2 focus:ring-amber-500 focus:border-amber-500">
                                        <option value="5">⭐⭐⭐⭐⭐ 5 - Strong Analytical</option>
                                        <option value="4">⭐⭐⭐⭐ 4 - Good Logic</option>
                                        <option value="3" selected>⭐⭐⭐ 3 - Adequate</option>
                                        <option value="2">⭐⭐ 2 - Limited Logic</option>
                                        <option value="1">⭐ 1 - No Analytical Skill</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 text-3xs uppercase mb-1">Culture & Attitude Fit</label>
                                    <select name="culture_score" class="w-full border-slate-300 rounded-xl text-xs font-bold p-2 focus:ring-amber-500 focus:border-amber-500">
                                        <option value="5">⭐⭐⭐⭐⭐ 5 - Great Culture Fit</option>
                                        <option value="4">⭐⭐⭐⭐ 4 - Positive Attitude</option>
                                        <option value="3" selected>⭐⭐⭐ 3 - Neutral</option>
                                        <option value="2">⭐⭐ 2 - Red Flags</option>
                                        <option value="1">⭐ 1 - Toxic / Unfit</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 text-3xs uppercase mb-1">Rekomendasi Akhir Pewawancara</label>
                                <select name="recommendation" required class="w-full border-slate-300 rounded-xl text-xs font-black p-2 focus:ring-amber-500 focus:border-amber-500">
                                    <option value="strong_hire">🟢 STRONG HIRE (Sangat Direkomendasikan)</option>
                                    <option value="hire" selected>🔵 HIRE (Direkomendasikan)</option>
                                    <option value="hold">🟡 HOLD (Cadangan / Ditangguhkan)</option>
                                    <option value="no_hire">🔴 NO HIRE (Tidak Direkomendasikan)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 text-3xs uppercase mb-1">Catatan Evaluasi Detail Pewawancara</label>
                                <textarea name="notes" rows="2" placeholder="Tuliskan catatan hasil tanya jawab saat wawancara..." class="w-full border-slate-300 rounded-xl text-xs p-2 font-medium"></textarea>
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-black text-white font-extrabold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-save"></i> Simpan Penilaian Scorecard Wawancara
                            </button>
                        </form>

                        <!-- Daftar Scorecard yang Sudah Diisi -->
                        @if($application->scorecards->count() > 0)
                            <div class="pt-3 border-t border-slate-100 space-y-2">
                                <span class="text-3xs font-extrabold uppercase text-slate-400 block">Riwayat Scorecard Pewawancara:</span>
                                @foreach($application->scorecards as $sc)
                                    <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl text-xs space-y-1">
                                        <div class="flex items-center justify-between font-bold text-slate-900">
                                            <span>👤 {{ $sc->interviewer->name ?? 'Pewawancara' }}</span>
                                            <span class="px-2 py-0.5 rounded text-3xs font-black uppercase
                                                {{ $sc->recommendation === 'strong_hire' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                                {{ $sc->recommendation === 'hire' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $sc->recommendation === 'hold' ? 'bg-amber-100 text-amber-800' : '' }}
                                                {{ $sc->recommendation === 'no_hire' ? 'bg-rose-100 text-rose-800' : '' }}">
                                                {{ str_replace('_', ' ', $sc->recommendation) }} (⭐ {{ $sc->average_score }}/5)
                                            </span>
                                        </div>
                                        <div class="text-3xs text-slate-500 font-medium">
                                            Tech: {{ $sc->technical_score }} | Comm: {{ $sc->communication_score }} | Logic: {{ $sc->problem_solving_score }} | Culture: {{ $sc->culture_score }}
                                        </div>
                                        @if($sc->notes)
                                            <p class="text-3xs text-slate-700 italic bg-white p-2 rounded border border-slate-200 mt-1">"{{ $sc->notes }}"</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Candidate Onboarding Employee Data Widget (Bank, NPWP, BPJS) -->
                    @if($application->onboarding)
                        <div class="bg-white rounded-3xl p-5 shadow-2xs border border-slate-200/80 space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-building-columns text-slate-800 text-sm"></i>
                                    <h4 class="font-extrabold text-xs text-slate-900 uppercase">Data Onboarding & Bank Karyawan</h4>
                                </div>
                                @if($application->onboarding->verification_status === 'verified')
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-3xs font-black rounded-lg">Terverifikasi</span>
                                @else
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-3xs font-black rounded-lg">Pending Review</span>
                                @endif
                            </div>

                            <div class="space-y-2 text-xs text-slate-700">
                                <p>🏦 <strong>Bank:</strong> {{ $application->onboarding->bank_name }}</p>
                                <p>💳 <strong>No. Rekening:</strong> <code class="bg-slate-100 px-2 py-0.5 rounded text-slate-900 font-mono font-bold">{{ $application->onboarding->bank_account_number }}</code></p>
                                <p>👤 <strong>Atas Nama:</strong> {{ $application->onboarding->bank_account_holder }}</p>

                                @if($application->onboarding->institution_name)
                                    <p>🎓 <strong>Kampus/Sekolah:</strong> {{ $application->onboarding->institution_name }} (NIM/NIS: {{ $application->onboarding->student_id_number ?? '-' }})</p>
                                @endif

                                @if($application->onboarding->npwp_number)
                                    <p>📄 <strong>NPWP:</strong> {{ $application->onboarding->npwp_number }}</p>
                                @endif

                                @if($application->onboarding->bpjs_kesehatan_number)
                                    <p>🩺 <strong>BPJS Kes:</strong> {{ $application->onboarding->bpjs_kesehatan_number }}</p>
                                @endif

                                @if($application->onboarding->bpjs_ketenagakerjaan_number)
                                    <p>👷 <strong>BPJSTK:</strong> {{ $application->onboarding->bpjs_ketenagakerjaan_number }}</p>
                                @endif
                            </div>

                            @if($application->onboarding->verification_status !== 'verified')
                                <div class="pt-2 flex items-center gap-2">
                                    <form action="{{ route('admin.applications.verify-onboarding', $application) }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="action" value="verify">
                                        <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-xl shadow-2xs transition flex items-center justify-center gap-1.5 border border-emerald-600">
                                            <i class="fa-solid fa-circle-check text-xs"></i> Verifikasi Berkas
                                        </button>
                                    </form>

                                    <button type="button" onclick="document.getElementById('rejectOnboardingModal').classList.remove('hidden')" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl border border-rose-200 transition">
                                        ⚠️ Minta Perbaikan
                                    </button>
                                </div>
                            @else
                                <div class="pt-2">
                                    <button type="button" onclick="document.getElementById('rejectOnboardingModal').classList.remove('hidden')" class="w-full py-2 bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs rounded-xl border border-amber-200 transition flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-rotate-left text-xs"></i> Batalkan Verifikasi & Minta Perbaikan Data
                                    </button>
                                </div>
                            @endif

                            <!-- REJECT ONBOARDING MODAL -->
                            <div id="rejectOnboardingModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
                                <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl border border-slate-200 space-y-4">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                        <h4 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                                            ⚠️ Minta Perbaikan Berkas / Data Onboarding
                                        </h4>
                                        <button type="button" onclick="document.getElementById('rejectOnboardingModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
                                    </div>
                                    <form action="{{ route('admin.applications.verify-onboarding', $application) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alasan Catatan Perbaikan untuk Pelamar <span class="text-rose-500">*</span></label>
                                            <textarea name="rejection_note" rows="3" required placeholder="Contoh: Nomor rekening kurang 1 digit / Berkas NPWP buram." class="w-full border-slate-300 rounded-xl text-xs focus:ring-rose-500 focus:border-rose-500 font-medium p-3"></textarea>
                                        </div>
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" onclick="document.getElementById('rejectOnboardingModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">Batal</button>
                                            <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-2xs">Kirim Catatan Perbaikan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- RIGHT COLUMN: OPERATIONAL TOOLS, STATUS CONTROL & DOCUMENTS (2 COLS) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Update Status Lamaran & Quota Control Card -->
                    <div class="bg-white rounded-3xl p-6 shadow-2xs border border-slate-200/80 space-y-4">
                        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                            <h4 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-sliders text-blue-600"></i> Update Status Lamaran Pelamar
                            </h4>
                            <span class="text-xs font-bold text-slate-500">Target: <strong>{{ $application->job->title }}</strong></span>
                        </div>

                        @php
                            $jobQuota = $application->job->quota;
                            $currentHired = \App\Models\Application::where('job_id', $application->job_id)
                                ->whereIn('status', ['accepted', 'hired'])
                                ->count();
                            $isQuotaFull = $jobQuota && ($currentHired >= $jobQuota) && ($statusStr !== 'accepted');
                        @endphp

                        @if($statusStr === 'accepted')
                            <div class="bg-rose-50 p-4 rounded-2xl border border-rose-200 space-y-3">
                                <div class="flex items-center gap-2 text-rose-900 font-bold text-xs">
                                    <i class="fa-solid fa-lock text-rose-600"></i>
                                    <span>STATUS DITERIMA TERKUNCI (HIRED LOCK)</span>
                                </div>
                                <p class="text-3xs text-rose-700 leading-relaxed">
                                    Status <strong>Diterima</strong> tidak dapat dibatalkan langsung oleh HR untuk melindungi kuota lowongan. Pembatalan memerlukan otorisasi <strong>Super Admin</strong>.
                                </p>
                            </div>
                        @else
                            @if($isQuotaFull)
                                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-3xs text-amber-800 font-bold flex items-center gap-2">
                                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm"></i>
                                    <span>KUOTA PENUH ({{ $currentHired }}/{{ $jobQuota }}). Option 'Terima' Ditutup.</span>
                                </div>
                            @endif

                            <form action="{{ route('admin.applications.updateStatus', $application->id) }}" method="POST" class="space-y-3 text-xs">
                                @csrf
                                @method('PATCH')
                                
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center">
                                    <div class="sm:col-span-2">
                                        <label class="block font-bold text-slate-700 mb-1">Pilih Status Baru Pelamar</label>
                                        <select name="status" class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-blue-500 focus:border-blue-500">
                                            @foreach(\App\Enums\ApplicationStatus::cases() as $st)
                                                @if($st->value !== 'processing')
                                                    <option value="{{ $st->value }}" {{ $statusStr == $st->value ? 'selected' : '' }} {{ ($st->value === 'accepted' && $isQuotaFull) ? 'disabled' : '' }}>
                                                        {{ ($st->value === 'accepted' && $isQuotaFull) ? 'Diterima (DITUTUP - KUOTA PENUH)' : $st->label() }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="pt-5 sm:pt-0">
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs border border-blue-600">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>

                    <!-- DOCUMENT BUILDER CARDS GRID (2 Cols) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Internship Certificate Card -->
                        <div class="bg-white rounded-3xl p-5 shadow-2xs border border-slate-200/80 space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <h4 class="font-extrabold text-xs text-slate-900 uppercase flex items-center gap-2">
                                    <i class="fa-solid fa-graduation-cap text-amber-600"></i> Sertifikat Kelulusan Magang
                                </h4>
                                <a href="{{ route('admin.applications.certificates.create', $application) }}" class="px-2.5 py-1 bg-amber-600 hover:bg-amber-700 text-white font-bold text-3xs rounded-lg transition border border-amber-600">
                                    + Terbitkan
                                </a>
                            </div>

                            @if($application->certificates && $application->certificates->count() > 0)
                                <div class="space-y-2 text-xs">
                                    @foreach($application->certificates as $cert)
                                        <div class="p-3 bg-amber-50/60 rounded-2xl border border-amber-200/80 flex items-center justify-between gap-2">
                                            <div class="truncate">
                                                <span class="font-bold text-amber-950 block truncate">Sertifikat Kelulusan</span>
                                                <span class="text-3xs text-amber-800 font-semibold">Predikat: {{ $cert->performance_grade }}</span>
                                            </div>
                                            <a href="{{ route('candidate.certificates.show', $cert) }}" target="_blank" class="shrink-0 p-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-3xs font-bold transition">
                                                <i class="fa-solid fa-file-pdf"></i> PDF
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-3xs text-slate-400">Belum ada sertifikat magang resmi yang diterbitkan.</p>
                            @endif
                        </div>

                        <!-- Internship Transcript Card -->
                        <div class="bg-white rounded-3xl p-5 shadow-2xs border border-slate-200/80 space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <h4 class="font-extrabold text-xs text-slate-900 uppercase flex items-center gap-2">
                                    <i class="fa-solid fa-square-poll-vertical text-indigo-600"></i> Transkrip Evaluasi Nilai
                                </h4>
                                <a href="{{ route('admin.applications.transcripts.create', $application) }}" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-3xs rounded-lg transition border border-indigo-600">
                                    + Terbitkan
                                </a>
                            </div>

                            @if($application->transcripts && $application->transcripts->count() > 0)
                                <div class="space-y-2 text-xs">
                                    @foreach($application->transcripts as $trans)
                                        <div class="p-3 bg-indigo-50/60 rounded-2xl border border-indigo-200/80 flex items-center justify-between gap-2">
                                            <div class="truncate">
                                                <span class="font-bold text-indigo-950 block truncate">Transkrip Evaluasi Nilai</span>
                                                <span class="text-3xs text-indigo-800 font-semibold">Skor: {{ number_format($trans->final_score, 1) }}/100</span>
                                            </div>
                                            <a href="{{ route('candidate.transcripts.show', $trans) }}" target="_blank" class="shrink-0 p-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-3xs font-bold transition">
                                                <i class="fa-solid fa-file-pdf"></i> PDF
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-3xs text-slate-400">Belum ada transkrip nilai evaluasi magang yang diterbitkan.</p>
                            @endif
                        </div>

                        <!-- Digital Agreement / Contract Card -->
                        <div class="bg-white rounded-3xl p-5 shadow-2xs border border-slate-200/80 space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <h4 class="font-extrabold text-xs text-slate-900 uppercase flex items-center gap-2">
                                    <i class="fa-solid fa-file-contract text-slate-800"></i> Perjanjian Kerja Digital
                                </h4>
                                <a href="{{ route('admin.applications.agreements.create', $application) }}" class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-white font-bold text-3xs rounded-lg transition border border-slate-900">
                                    + Buat Dokumen
                                </a>
                            </div>

                            @if($application->agreements && $application->agreements->count() > 0)
                                <div class="space-y-2 text-xs">
                                    @foreach($application->agreements as $ag)
                                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between gap-3">
                                            <div class="truncate">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-slate-900 block truncate">{{ $ag->title }}</span>
                                                    @if($ag->status === 'signed')
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                            <i class="fa-solid fa-circle-check"></i> Sudah TTD
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                                            <i class="fa-solid fa-clock"></i> Menunggu TTD
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-3xs text-slate-500 font-semibold block mt-0.5">No: {{ $ag->contract_number }}</span>
                                            </div>
                                            <div class="shrink-0 flex items-center gap-1.5">
                                                <a href="{{ route('candidate.agreements.show', $ag) }}" target="_blank" class="px-2 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-3xs font-bold transition flex items-center gap-1">
                                                    <i class="fa-solid fa-eye"></i> Buka
                                                </a>
                                                <a href="{{ route('agreements.download', $ag) }}" target="_blank" class="px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-3xs font-bold transition flex items-center gap-1" title="Unduh Dokumen PDF Perjanjian">
                                                    <i class="fa-solid fa-file-pdf"></i> Unduh PDF
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-3xs text-slate-400">Belum ada dokumen perjanjian kerja digital yang dibuat.</p>
                            @endif
                        </div>

                        <!-- Employee Termination & Recommendation Document Card -->
                        <div class="bg-white rounded-3xl p-5 shadow-2xs border border-slate-200/80 space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <h4 class="font-extrabold text-xs text-slate-900 uppercase flex items-center gap-2">
                                    <i class="fa-solid fa-file-signature text-slate-800"></i> Rekomendasi / Paklaring / PHK
                                </h4>
                                <a href="{{ route('admin.applications.terminations.create', $application) }}" class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-white font-bold text-3xs rounded-lg transition border border-slate-900">
                                    + Terbitkan
                                </a>
                            </div>

                            @if($application->terminations && $application->terminations->count() > 0)
                                <div class="space-y-2 text-xs">
                                    @foreach($application->terminations as $term)
                                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between gap-2">
                                            <div class="truncate">
                                                <span class="font-bold text-slate-900 block truncate">
                                                    @if($term->document_type === 'recommendation_letter')
                                                        Surat Rekomendasi Kerja
                                                    @elseif($term->document_type === 'paklaring_letter')
                                                        Surat Paklaring
                                                    @elseif($term->document_type === 'phk_letter')
                                                        Surat PHK
                                                    @else
                                                        Surat Selesai Kontrak
                                                    @endif
                                                </span>
                                                <span class="text-3xs text-slate-500 font-semibold">No: {{ $term->document_number }}</span>
                                            </div>
                                            <a href="{{ route('candidate.terminations.show', $term) }}" target="_blank" class="shrink-0 p-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-3xs font-bold transition">
                                                <i class="fa-solid fa-file-pdf"></i> PDF
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-3xs text-slate-400">Belum ada surat rekomendasi kerja atau paklaring yang diterbitkan.</p>
                            @endif
                        </div>
                    </div>

                    <!-- CANDIDATE DOCUMENT VAULT WIDGET -->
                    @php
                        $userDocuments = \App\Models\CandidateDocument::where('user_id', $application->user_id)->latest()->get();
                    @endphp
                    <div class="bg-white rounded-3xl p-6 shadow-2xs border border-slate-200/80 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h4 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-folder-open text-amber-500"></i> Vault Berkas & Dokumen Kandidat
                            </h4>
                            <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 text-3xs font-black rounded-lg uppercase">
                                {{ $userDocuments->count() }} Berkas Ter-upload
                            </span>
                        </div>

                        @if($userDocuments->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($userDocuments as $doc)
                                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between gap-3 text-xs">
                                        <div class="min-w-0 flex-1">
                                            <span class="px-2 py-0.5 text-3xs font-black rounded-md uppercase border {{ $doc->type_badge_color }}">
                                                {{ $doc->type_label }}
                                            </span>
                                            <h5 class="font-bold text-slate-900 truncate mt-1">{{ $doc->title }}</h5>
                                            <span class="text-3xs text-slate-400 font-medium">{{ strtoupper($doc->file_extension ?? 'PDF') }} • {{ $doc->formatted_size }}</span>
                                        </div>

                                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="p-2 bg-white hover:bg-blue-50 text-blue-600 rounded-xl border border-slate-200 transition shrink-0" title="Buka Dokumen">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-center text-xs text-slate-400 font-medium">
                                Kandidat belum mengunggah dokumen pendukung di Vault.
                            </div>
                        @endif
                    </div>

                    <!-- CARD 1: PENJADWALAN WAWANCARA (INTERVIEW SCHEDULER) -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <h4 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-calendar-days text-purple-600"></i> Penjadwalan Wawancara & Undangan Interview
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">Atur tanggal, jam, tipe wawancara (Online/Offline), dan tautan Google Meet / lokasi kantor.</p>
                        </div>

                        @if($application->interview)
                            <div class="p-5 bg-purple-50 rounded-2xl border border-purple-200/80 space-y-2 text-xs">
                                <div class="flex items-center justify-between border-b border-purple-200 pb-2">
                                    <span class="font-extrabold text-purple-900 uppercase">Jadwal Wawancara Terdaftar</span>
                                    <span class="px-2.5 py-0.5 bg-purple-600 text-white font-black text-3xs rounded-full uppercase">
                                        {{ strtoupper($application->interview->type) }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs pt-1">
                                    <div>📅 <strong>Waktu:</strong> {{ $application->interview->scheduled_at ? $application->interview->scheduled_at->format('d M Y, H:i') : '-' }} WIB</div>
                                    @if($application->interview->location_or_link)
                                        <div>🔗 <strong>Lokasi/Link:</strong> <a href="{{ $application->interview->location_or_link }}" target="_blank" class="text-blue-600 font-bold hover:underline truncate inline-block max-w-xs">{{ $application->interview->location_or_link }}</a></div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('admin.applications.schedule-interview', $application->id) }}" method="POST" class="space-y-4 text-xs">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Tanggal & Jam Wawancara</label>
                                    <input type="datetime-local" name="scheduled_at" required value="{{ optional($application->interview)->scheduled_at ? $application->interview->scheduled_at->format('Y-m-d\TH:i') : '' }}" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-semibold">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Tipe Wawancara</label>
                                    <select name="type" required class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-blue-500 focus:border-blue-500">
                                        <option value="online" {{ optional($application->interview)->type == 'online' ? 'selected' : '' }}>Online (Google Meet / Zoom)</option>
                                        <option value="offline" {{ optional($application->interview)->type == 'offline' ? 'selected' : '' }}>Offline (Tatap Muka di Kantor)</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block font-bold text-slate-700 mb-1">Lokasi Kantor / Tautan Link Google Meet</label>
                                    <input type="text" name="location_or_link" placeholder="Contoh: https://meet.google.com/abc-defg-hij atau Ruang Rapat 2 Lantai 3" value="{{ optional($application->interview)->location_or_link }}" class="w-full border-slate-300 rounded-xl text-xs font-medium">
                                </div>
                            </div>

                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-extrabold px-6 py-2.5 rounded-xl text-xs shadow-2xs transition flex items-center gap-1.5">
                                <i class="fa-solid fa-paper-plane"></i> Simpan & Kirim Undangan Interview
                            </button>
                        </form>
                    </div>

                    <!-- CARD 2: OFFER LETTER BUILDER -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-4">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 pb-4 gap-3">
                            <div>
                                <h4 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-file-contract text-emerald-600"></i> Surat Penawaran Kerja (Offer Letter)
                                </h4>
                                <p class="text-xs text-slate-500 mt-0.5">Terbitkan penawaran kerja resmi dengan rincian gaji & tanggal mulai kerja.</p>
                            </div>

                            <a href="{{ route('admin.applications.offer-letter.create', $application->id) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-2xs transition border border-emerald-600 shrink-0">
                                + Buat Offer Letter
                            </a>
                        </div>
                    </div>

                    <!-- CARD 3: EMAIL TEMPLATE ENGINE -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6" x-data="emailTemplateEngine()">
                        <div class="border-b border-slate-100 pb-4">
                            <h4 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-envelope-open-text text-indigo-600"></i> Generator Email Template Otomatis
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">Kirim email pemberitahuan ke pelamar dalam satu kali klik.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <button type="button" @click="loadTemplate('interview')" class="p-3.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-900 font-bold rounded-2xl border border-indigo-200 transition text-left space-y-1">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-calendar-check text-indigo-600"></i>
                                    <span>1. Template Undangan Wawancara</span>
                                </div>
                                <p class="text-3xs text-indigo-700 font-medium">Format email undangan wawancara resmi beserta detail jadwal & tautan.</p>
                            </button>

                            <button type="button" @click="loadTemplate('rejection')" class="p-3.5 bg-rose-50 hover:bg-rose-100 text-rose-900 font-bold rounded-2xl border border-rose-200 transition text-left space-y-1">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-heart-crack text-rose-600"></i>
                                    <span>2. Template Penolakan Halus</span>
                                </div>
                                <p class="text-3xs text-rose-700 font-medium">Format email apresiasi & penolakan sopan (Friendly Rejection Letter).</p>
                            </button>
                        </div>

                        <form action="{{ route('admin.applications.send-email-template', $application->id) }}" method="POST" class="space-y-4 text-xs pt-2">
                            @csrf
                            <input type="hidden" name="template_type" x-model="templateType">

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Subjek Email</label>
                                <input type="text" name="subject" x-model="subject" required class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Isi Pesan Email (Body)</label>
                                <textarea name="body" rows="6" x-model="body" required class="w-full border-slate-300 rounded-xl text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 leading-relaxed"></textarea>
                            </div>

                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-6 py-2.5 rounded-xl text-xs shadow-2xs transition flex items-center gap-1.5">
                                <i class="fa-solid fa-paper-plane"></i> Kirim Email Template Ke Candidate
                            </button>
                        </form>
                    </div>

                    <!-- CARD 4: PESAN LANGSUNG / LIVE CHAT (HR ↔ KANDIDAT) -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6" x-data="liveChat('{{ route('applications.messages.fetch', $application->id) }}', '{{ route('applications.messages.send', $application->id) }}')">
                        <div class="border-b border-slate-100 pb-4">
                            <h4 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-comments text-blue-600"></i> Obrolan Langsung / Live Chat (HR ↔ Kandidat)
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">Komunikasi pesan teks langsung real-time dengan pelamar.</p>
                        </div>

                        <div class="h-64 overflow-y-auto p-4 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-3" id="chat-box">
                            <template x-for="msg in messages" :key="msg.id">
                                <div :class="msg.is_me ? 'text-right' : 'text-left'">
                                    <div :class="msg.is_me ? 'bg-blue-600 text-white rounded-2xl rounded-tr-xs' : 'bg-white text-slate-800 border border-slate-200 rounded-2xl rounded-tl-xs'" class="inline-block px-4 py-2.5 max-w-md text-xs shadow-2xs">
                                        <div class="font-bold text-3xs opacity-80 mb-0.5" x-text="msg.sender_name"></div>
                                        <div x-text="msg.message"></div>
                                        <div class="text-3xs opacity-70 mt-1" x-text="msg.time"></div>
                                    </div>
                                </div>
                            </template>
                            <p x-show="messages.length === 0" class="text-xs text-slate-400 text-center py-12">Belum ada obrolan. Ketik pesan di bawah untuk memulai percakapan.</p>
                        </div>

                        <form @submit.prevent="sendMsg" class="flex gap-2">
                            <input type="text" x-model="newMessage" placeholder="Ketik pesan untuk kandidat..." required class="flex-1 border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-medium">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-5 py-2.5 rounded-xl text-xs shadow-2xs transition flex items-center gap-1.5">
                                <i class="fa-solid fa-paper-plane"></i> Kirim
                            </button>
                        </form>
                    </div>

                </div>

            </div>

        </div>
    </div>

<script>
    function liveChat(fetchUrl, sendUrl) {
        return {
            messages: [],
            newMessage: '',
            fetchUrl: fetchUrl,
            sendUrl: sendUrl,
            init() {
                this.loadMessages();
                setInterval(() => this.loadMessages(), 4000);
            },
            loadMessages() {
                fetch(this.fetchUrl)
                    .then(res => res.json())
                    .then(data => {
                        this.messages = data.messages || [];
                    });
            },
            sendMsg() {
                if (!this.newMessage.trim()) return;
                const txt = this.newMessage;
                this.newMessage = '';

                fetch(this.sendUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: txt })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.messages.push(data.message);
                    }
                });
            }
        }
    }

    function emailTemplateEngine() {
        return {
            templateType: 'interview_invitation',
            subject: 'Undangan Wawancara Kerja - Posisi {{ $application->job->title }} ({{ $application->job->company_name }})',
            body: `Yth. Sdr/i {{ $application->user->name }},\n\nTerima kasih atas minat Anda melamar posisi {{ $application->job->title }} di perusahaan kami.\n\nBerdasarkan hasil seleksi berkas & asesmen awal, kami mengundang Anda untuk mengikuti sesi Wawancara Kerja yang akan diselenggarakan pada:\n\n📅 Waktu: {{ optional($application->interview)->scheduled_at ? $application->interview->scheduled_at->format('d F Y, H:i') . ' WIB' : '[Jadwal Belum Ditetapkan]' }}\n📌 Tipe Wawancara: {{ optional($application->interview)->type ? strtoupper($application->interview->type) : 'Online / Offline' }}\n🔗 Lokasi / Link Meeting: {{ optional($application->interview)->location_or_link ?? '[Tautan Google Meet / Alamat Kantor]' }}\n\nHarap hadir 10 menit sebelum sesi dimulai.\n\nHormat kami,\nTim Rekrutmen {{ $application->job->company_name }}`,
            loadTemplate(type) {
                this.templateType = type;
                if (type === 'interview') {
                    this.subject = 'Undangan Wawancara Kerja - Posisi {{ $application->job->title }} ({{ $application->job->company_name }})';
                    this.body = `Yth. Sdr/i {{ $application->user->name }},\n\nTerima kasih atas minat Anda melamar posisi {{ $application->job->title }} di perusahaan kami.\n\nBerdasarkan hasil seleksi berkas & asesmen awal, kami mengundang Anda untuk mengikuti sesi Wawancara Kerja yang akan diselenggarakan pada:\n\n📅 Waktu: {{ optional($application->interview)->scheduled_at ? $application->interview->scheduled_at->format('d F Y, H:i') . ' WIB' : '[Jadwal Belum Ditetapkan]' }}\n📌 Tipe Wawancara: {{ optional($application->interview)->type ? strtoupper($application->interview->type) : 'Online / Offline' }}\n🔗 Lokasi / Link Meeting: {{ optional($application->interview)->location_or_link ?? '[Tautan Google Meet / Alamat Kantor]' }}\n\nHarap hadir 10 menit sebelum sesi dimulai.\n\nHormat kami,\nTim Rekrutmen {{ $application->job->company_name }}`;
                } else if (type === 'rejection') {
                    this.subject = 'Pemberitahuan Status Lamaran Kerja - {{ $application->job->title }} ({{ $application->job->company_name }})';
                    this.body = `Yth. Sdr/i {{ $application->user->name }},\n\nTerima kasih banyak atas waktu dan minat Anda melamar posisi {{ $application->job->title }} di {{ $application->job->company_name }}.\n\nSetelah melakukan peninjauan secara saksama terhadap seluruh kualifikasi dan berkas lamaran yang masuk, dengan berat hati kami menginformasikan bahwa saat ini kami belum dapat melanjutkan proses rekrutmen Anda ke tahap berikutnya untuk posisi ini.\n\nProfil dan kualifikasi Anda sangat mengesankan, namun kami memilih kandidat yang kriteria pengalamannya lebih sesuai dengan kebutuhan spesifik peran saat ini.\n\nKami akan tetap menyimpan data diri Anda dalam database kandidat kami untuk peluang karier di masa mendatang.\n\nTerima kasih atas partisipasi Anda dan kami mendoakan kesuksesan karier Anda ke depan.\n\nHormat kami,\nTim HR {{ $application->job->company_name }}`;
                }
            }
        }
    }
</script>

<x-live-chat-drawer />
</x-app-layout>
