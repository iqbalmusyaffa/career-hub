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
                    <span class="px-4 py-2 bg-amber-50 text-amber-800 text-xs font-black rounded-2xl border border-amber-200 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-clock"></i> Menunggu Review
                    </span>
                @elseif($statusStr == 'reviewed')
                    <span class="px-4 py-2 bg-blue-50 text-blue-800 text-xs font-black rounded-2xl border border-blue-200 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-eye"></i> Sedang Direview
                    </span>
                @elseif($statusStr == 'test')
                    <span class="px-4 py-2 bg-indigo-50 text-indigo-800 text-xs font-black rounded-2xl border border-indigo-200 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-pen-to-square"></i> Tahap Tes Online / Psikotes
                    </span>
                @elseif($statusStr == 'interview')
                    <span class="px-4 py-2 bg-purple-50 text-purple-800 text-xs font-black rounded-2xl border border-purple-200 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-calendar-check"></i> Tahap Wawancara
                    </span>
                @elseif($statusStr == 'offered')
                    <span class="px-4 py-2 bg-emerald-50 text-emerald-800 text-xs font-black rounded-2xl border border-emerald-200 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-file-contract"></i> Tahap Penawaran Kerja
                    </span>
                @elseif($statusStr == 'accepted')
                    <span class="px-4 py-2 bg-emerald-600 text-white text-xs font-black rounded-2xl border border-emerald-700 shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check"></i> Diterima (Hired)
                    </span>
                @elseif($statusStr == 'rejected')
                    <span class="px-4 py-2 bg-rose-50 text-rose-800 text-xs font-black rounded-2xl border border-rose-200 shadow-2xs flex items-center gap-1.5">
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

                <!-- LEFT COLUMN: CANDIDATE PROFILE & STATUS CONTROL (1 COL) -->
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
                                <div class="flex items-center gap-3 text-slate-700">
                                    <i class="fa-solid fa-graduation-cap text-slate-400 w-4 text-center"></i>
                                    <span>Pendidikan: <strong>{{ $application->user->candidateProfile->last_education }}</strong></span>
                                </div>
                            @endif
                        </div>

                        <!-- Skills List -->
                        @if(optional($application->user->candidateProfile)->skills)
                            <div class="pt-4 border-t border-slate-100 space-y-2">
                                <label class="block text-3xs font-black uppercase text-slate-400 tracking-wider">Keahlian Candidates</label>
                                <div class="flex flex-wrap gap-1.5">
                                    @php
                                        $rawSkills = $application->user->candidateProfile->skills;
                                        $skillsArr = [];
                                        if (is_array($rawSkills)) {
                                            foreach ($rawSkills as $s) {
                                                $skillsArr[] = is_array($s) ? ($s['name'] ?? json_encode($s)) : (string)$s;
                                            }
                                        } else {
                                            $skillsArr = explode(',', (string) $rawSkills);
                                        }
                                    @endphp
                                    @foreach($skillsArr as $skill)
                                        @if(!empty(trim($skill)))
                                            <span class="px-2.5 py-1 bg-slate-100 text-slate-700 font-bold rounded-lg text-3xs border border-slate-200">
                                                {{ trim($skill) }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Uploaded CV Button -->
                        @if(optional($application->user->candidateProfile)->cv_path)
                            <div class="pt-4 border-t border-slate-100">
                                <a href="{{ Storage::url($application->user->candidateProfile->cv_path) }}" target="_blank" class="w-full bg-slate-900 hover:bg-black text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-file-arrow-down text-amber-400"></i> Unduh File CV Asli
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- PDF CV System Generator Widget -->
                    <div class="bg-gradient-to-br from-indigo-900 to-slate-900 text-white rounded-3xl p-6 shadow-md space-y-4">
                        <div class="flex items-center gap-3 border-b border-indigo-800/80 pb-3">
                            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center font-black text-white">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-sm text-white">Cetak PDF CV System</h4>
                                <p class="text-3xs text-indigo-200">Format standar profesional otomatis.</p>
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

                                @if($candidateTestResult->project_url)
                                    <div class="pt-2 border-t border-emerald-200/80 text-left">
                                        <a href="{{ $candidateTestResult->project_url }}" target="_blank" class="text-blue-600 font-extrabold hover:underline text-xs flex items-center gap-1.5 truncate">
                                            <i class="fa-brands fa-github text-slate-900 text-sm"></i> Tautan Repository / Project GitHub
                                        </a>
                                    </div>
                                @endif

                                @if($candidateTestResult->answer_file_path)
                                    <div class="pt-2 border-t border-emerald-200/80 text-left">
                                        <a href="{{ Storage::url($candidateTestResult->answer_file_path) }}" target="_blank" class="text-rose-600 font-extrabold hover:underline text-xs flex items-center gap-1.5 truncate">
                                            <i class="fa-solid fa-file-pdf text-rose-600 text-sm"></i> Unduh Berkas Jawaban PDF Candidate
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Candidate Document Vault Widget -->
                    @php
                        $userDocuments = \App\Models\CandidateDocument::where('user_id', $application->user_id)->latest()->get();
                    @endphp
                    <div class="bg-white rounded-3xl p-6 shadow-2xs border border-slate-200/80 space-y-4">
                        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                            <h4 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-folder-closed text-indigo-600"></i> Vault Berkas Pendukung ({{ $userDocuments->count() }})
                            </h4>
                        </div>

                        @if($userDocuments->count() > 0)
                            <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                                @foreach($userDocuments as $doc)
                                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 flex items-center justify-between gap-3 text-xs">
                                        <div class="min-w-0 flex-1">
                                            <span class="px-2 py-0.5 text-3xs font-black rounded-md uppercase border {{ $doc->type_badge_color }}">
                                                {{ $doc->type_label }}
                                            </span>
                                            <h5 class="font-bold text-slate-900 truncate mt-1">{{ $doc->title }}</h5>
                                            <span class="text-3xs text-slate-400 font-medium">{{ strtoupper($doc->file_extension ?? 'PDF') }} • {{ $doc->formatted_size }}</span>
                                        </div>

                                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="p-2 bg-white hover:bg-blue-50 text-blue-600 rounded-xl border border-slate-200 transition shrink-0" title="Buka / Unduh Dokumen">
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

                    <!-- Update Status Lamaran & Quota Control -->
                    <div class="bg-white rounded-3xl p-6 shadow-2xs border border-slate-200/80 space-y-4">
                        <div class="border-b border-slate-100 pb-3">
                            <h4 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-sliders text-blue-600"></i> Update Status & Aturan Kuota
                            </h4>
                        </div>

                        @php
                            $jobQuota = $application->job->quota;
                            $currentHired = \App\Models\Application::where('job_id', $application->job_id)
                                ->whereIn('status', ['accepted', 'hired'])
                                ->count();
                            $isQuotaFull = $jobQuota && ($currentHired >= $jobQuota) && ($statusStr !== 'accepted');
                        @endphp

                        @if($statusStr === 'accepted')
                            <!-- Acceptance Lock & Super Admin Appeal Form -->
                            <div class="bg-rose-50 p-4 rounded-2xl border border-rose-200 space-y-3">
                                <div class="flex items-center gap-2 text-rose-900 font-bold text-xs">
                                    <i class="fa-solid fa-lock text-rose-600"></i>
                                    <span>STATUS DITERIMA TERKUNCI</span>
                                </div>
                                <p class="text-3xs text-rose-700 leading-relaxed">
                                    Status <strong>Diterima</strong> tidak dapat dibatalkan langsung oleh HR untuk melindungi kuota. Pembatalan memerlukan verifikasi <strong>Super Admin</strong>.
                                </p>

                                <form action="{{ route('admin.cancellation-tickets.store', $application->id) }}" method="POST" class="pt-2 space-y-3 border-t border-rose-200">
                                    @csrf
                                    <div>
                                        <label class="block text-3xs font-bold text-rose-900 uppercase mb-1">Alasan Pembatalan ke Super Admin</label>
                                        <textarea name="reason" rows="2" required placeholder="Jelaskan alasan resmi pengajuan pembatalan..." class="w-full border-rose-300 rounded-xl text-xs focus:ring-rose-500 focus:border-rose-500"></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 px-3 rounded-xl text-xs transition shadow-2xs flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-paper-plane"></i> Kirim Aduan Ke Super Admin
                                    </button>
                                </form>
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
                                
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Pilih Status Baru</label>
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

                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs">
                                    Simpan Perubahan Status
                                </button>
                            </form>
                        @endif
                    </div>

                </div>

                <!-- RIGHT COLUMN: OPERATIONAL TOOLS & WORKFLOW (2 COLS) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Job Position Header Card -->
                    <div class="bg-white rounded-3xl p-6 shadow-2xs border border-slate-200/80 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <span class="text-3xs font-black uppercase tracking-wider text-slate-400 block mb-1">Lowongan Pekerjaan Target</span>
                            <h3 class="font-black text-xl text-slate-900">{{ $application->job->title }}</h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Divisi: {{ $application->job->division }} • Tipe: {{ $application->job->work_type }} • Lokasi: {{ $application->job->location }}</p>
                        </div>
                        <a href="{{ route('jobs.show', $application->job->id) }}" target="_blank" class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs rounded-xl border border-blue-200 transition shrink-0 inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Detail Lowongan
                        </a>
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
                                @if($application->interview->notes)
                                    <p class="text-3xs text-purple-800 italic pt-1 border-t border-purple-200/60">"{{ $application->interview->notes }}"</p>
                                @endif
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
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Link Wawancara / Alamat Lokasi Kantor</label>
                                <input type="text" name="location_or_link" placeholder="https://meet.google.com/abc-defg-hij atau Ruang Rapat Lt. 2" value="{{ optional($application->interview)->location_or_link }}" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Catatan Tambahan untuk Kandidat</label>
                                <textarea name="notes" rows="2" placeholder="Harap hadir 10 menit sebelum waktu dan menyiapkan portofolio..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">{{ optional($application->interview)->notes }}</textarea>
                            </div>

                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-extrabold px-6 py-2.5 rounded-xl text-xs shadow-2xs transition flex items-center gap-1.5">
                                <i class="fa-solid fa-calendar-plus"></i> {{ $application->interview ? 'Perbarui Jadwal Wawancara' : 'Simpan & Kirim Jadwal Wawancara' }}
                            </button>
                        </form>
                    </div>

                    <!-- CARD 2: EVALUASI RATING & SCORING SHEET HR -->
                    @php
                        $evaluations = $application->evaluations()->with('evaluator')->latest()->get();
                        $avgRating = $evaluations->count() > 0 ? round($evaluations->avg('rating'), 1) : 0;
                        $avgTech = $evaluations->count() > 0 ? round($evaluations->avg('technical_score'), 1) : 0;
                        $avgAtt = $evaluations->count() > 0 ? round($evaluations->avg('attitude_score'), 1) : 0;
                        $avgComm = $evaluations->count() > 0 ? round($evaluations->avg('communication_score'), 1) : 0;
                    @endphp
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6">
                        <div class="flex justify-between items-start border-b border-slate-100 pb-4">
                            <div>
                                <h4 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-star text-amber-500"></i> Evaluasi Rating & Scoring Sheet HR
                                </h4>
                                <p class="text-xs text-slate-500 mt-0.5">Penilaian kolaboratif antar tim HR untuk menguji kompetensi kandidat.</p>
                            </div>
                            @if($evaluations->count() > 0)
                                <div class="text-right">
                                    <div class="text-xl font-black text-amber-600 flex items-center gap-1 justify-end">
                                        <span>{{ $avgRating }}</span>
                                        <span class="text-sm text-amber-500">★</span>
                                    </div>
                                    <div class="text-3xs text-slate-400 font-bold uppercase">{{ $evaluations->count() }} Review HR</div>
                                </div>
                            @endif
                        </div>

                        @if($evaluations->count() > 0)
                            <div class="grid grid-cols-3 gap-3 text-xs">
                                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80 text-center">
                                    <div class="text-3xs text-slate-400 font-bold uppercase">Skor Teknis</div>
                                    <div class="text-lg font-black text-blue-600 mt-0.5">{{ $avgTech }} / 100</div>
                                </div>
                                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80 text-center">
                                    <div class="text-3xs text-slate-400 font-bold uppercase">Attitude / Sikap</div>
                                    <div class="text-lg font-black text-emerald-600 mt-0.5">{{ $avgAtt }} / 100</div>
                                </div>
                                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80 text-center">
                                    <div class="text-3xs text-slate-400 font-bold uppercase">Komunikasi</div>
                                    <div class="text-lg font-black text-purple-600 mt-0.5">{{ $avgComm }} / 100</div>
                                </div>
                            </div>

                            <div class="space-y-3 pt-2">
                                @foreach($evaluations as $eval)
                                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2 text-xs">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-2">
                                                <span class="font-extrabold text-slate-900">{{ $eval->evaluator->name ?? 'Evaluator HR' }}</span>
                                                <span class="text-amber-500 font-black">
                                                    @for($i=1; $i<=$eval->rating; $i++) ★ @endfor
                                                </span>
                                            </div>
                                            <div>
                                                @if($eval->recommendation === 'hire')
                                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-3xs font-black rounded-lg uppercase">✓ Rekomendasi Terima</span>
                                                @elseif($eval->recommendation === 'consider')
                                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-3xs font-black rounded-lg uppercase">⏳ Pertimbangkan</span>
                                                @else
                                                    <span class="px-2.5 py-1 bg-rose-100 text-rose-800 text-3xs font-black rounded-lg uppercase">✗ Tolak</span>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-slate-600 italic">"{{ $eval->comments }}"</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Form Input Penilaian HR -->
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/80 space-y-4">
                            <h5 class="font-bold text-xs text-slate-900 flex items-center gap-1.5">
                                <i class="fa-solid fa-pen-to-square text-blue-600"></i> Tambah Penilaian & Rating HR Saya
                            </h5>

                            <form action="{{ route('admin.applications.evaluations.store', $application->id) }}" method="POST" class="space-y-4 text-xs">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Rating Bintang (1-5)</label>
                                        <select name="rating" required class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-blue-500 focus:border-blue-500">
                                            <option value="5">★★★★★ (5 - Sangat Baik)</option>
                                            <option value="4" selected>★★★★☆ (4 - Baik)</option>
                                            <option value="3">★★★☆☆ (3 - Cukup)</option>
                                            <option value="2">★★☆☆☆ (2 - Kurang)</option>
                                            <option value="1">★☆☆☆☆ (1 - Buruk)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Skor Teknis (0-100)</label>
                                        <input type="number" name="technical_score" min="0" max="100" value="85" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold">
                                    </div>
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Attitude (0-100)</label>
                                        <input type="number" name="attitude_score" min="0" max="100" value="90" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold">
                                    </div>
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Komunikasi (0-100)</label>
                                        <input type="number" name="communication_score" min="0" max="100" value="80" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-2">
                                        <label class="block font-bold text-slate-700 mb-1">Catatan Evaluasi HR</label>
                                        <input type="text" name="comments" placeholder="Catatan kelebihan, kekurangan, atau hasil wawancara..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Rekomendasi Akhir</label>
                                        <select name="recommendation" required class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-blue-500 focus:border-blue-500">
                                            <option value="hire">✓ Rekomendasi Terima (Hire)</option>
                                            <option value="consider">⏳ Pertimbangkan (Consider)</option>
                                            <option value="reject">✗ Tolak (Reject)</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-6 py-2.5 rounded-xl text-xs shadow-2xs transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-floppy-disk"></i> Simpan Penilaian HR
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- CARD 3: SURAT PENAWARAN KERJA (OFFER LETTER PDF) -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-100 pb-4">
                            <div>
                                <h4 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-file-contract text-emerald-600"></i> Surat Penawaran Kerja (Offer Letter PDF)
                                </h4>
                                <p class="text-xs text-slate-500 mt-0.5">Terbitkan surat penawaran kerja resmi berformat PDF untuk dikonfirmasi kandidat.</p>
                            </div>
                            <a href="{{ route('admin.applications.offer-letter.create', $application->id) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-2xs transition shrink-0 inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-plus"></i> Buat Offer Letter Baru
                            </a>
                        </div>

                        @if($application->offerLetter)
                            <div class="p-5 bg-emerald-50 rounded-2xl border border-emerald-200 space-y-3 text-xs">
                                <div class="flex justify-between items-center border-b border-emerald-200 pb-2">
                                    <span class="font-bold text-slate-900">Status Konfirmasi Kandidat:</span>
                                    @if($application->offerLetter->status === 'accepted')
                                        <span class="px-3 py-1 bg-emerald-600 text-white font-black rounded-lg text-3xs uppercase">✓ DITERIMA KANDIDAT</span>
                                    @elseif($application->offerLetter->status === 'declined')
                                        <span class="px-3 py-1 bg-rose-600 text-white font-black rounded-lg text-3xs uppercase">✗ DITOLAK KANDIDAT</span>
                                    @else
                                        <span class="px-3 py-1 bg-amber-500 text-white font-black rounded-lg text-3xs uppercase">⏳ MENUNGGU RESPONS</span>
                                    @endif
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <p>💵 <strong>Gaji Ditawarkan:</strong> Rp {{ number_format((float) preg_replace('/[^0-9]/', '', $application->offerLetter->offered_salary), 0, ',', '.') }}</p>
                                    <p>📅 <strong>Mulai Bekerja:</strong> {{ $application->offerLetter->start_date ? $application->offerLetter->start_date->format('d M Y') : '-' }}</p>
                                </div>
                                <div class="pt-2 border-t border-emerald-200/60">
                                    <a href="{{ route('offer-letters.download', $application->offerLetter->id) }}" class="text-blue-600 font-extrabold hover:underline inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-download"></i> Unduh File Offer Letter PDF Official
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-500">
                                Belum ada Offer Letter diterbitkan. Klik <strong>+ Buat Offer Letter Baru</strong> untuk menerbitkan surat penawaran kerja berformat PDF.
                            </div>
                        @endif
                    </div>

                    <!-- CARD 5: CATATAN RAHASIA INTERNAL TIM HR (HR INTERNAL CONFIDENTIAL NOTES) -->
                    @php
                        $internalNotes = $application->internalNotes()->with('hrUser')->latest()->get();
                    @endphp
                    <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-md space-y-6">
                        <div class="border-b border-slate-800 pb-4">
                            <h4 class="font-extrabold text-base text-white flex items-center gap-2">
                                <i class="fa-solid fa-lock text-amber-400"></i> Catatan Rahasia Internal Tim HR (Confidential)
                            </h4>
                            <p class="text-xs text-slate-400 mt-0.5">Diskusi internal rahasia antar rekruter/HR. Catatan ini <strong>TIDAK DAPAT DILIHAT</strong> oleh kandidat pelamar.</p>
                        </div>

                        @if($internalNotes->count() > 0)
                            <div class="space-y-3">
                                @foreach($internalNotes as $note)
                                    <div class="p-4 bg-slate-800/90 rounded-2xl border border-slate-700/80 space-y-2 text-xs">
                                        <div class="flex justify-between items-center">
                                            <span class="font-bold text-amber-300 flex items-center gap-1.5">
                                                <i class="fa-solid fa-user-shield text-3xs"></i> {{ $note->hrUser->name ?? 'Tim HR' }}
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <span class="text-3xs text-slate-400 font-medium">{{ $note->created_at->diffForHumans() }}</span>
                                                @if($note->hr_user_id === auth()->id() || auth()->user()->hasRole('Super Admin'))
                                                    <form action="{{ route('admin.applications.internal-notes.destroy', [$application->id, $note->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus catatan rahasia ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-rose-400 hover:text-rose-300 text-3xs font-bold">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-slate-200 leading-relaxed font-mono text-3xs">{{ $note->note_text }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-400 italic">Belum ada catatan internal rahasia untuk kandidat ini.</p>
                        @endif

                        <form action="{{ route('admin.applications.internal-notes.store', $application->id) }}" method="POST" class="space-y-3 pt-2 border-t border-slate-800">
                            @csrf
                            <div>
                                <label class="block text-3xs font-bold text-slate-300 uppercase mb-1">Tambah Catatan Internal Rahasia</label>
                                <textarea name="note_text" rows="2" required placeholder="Tulis catatan internal (misal: negosiasi gaji, kelebihan utama, atau pertimbangan tim HR)..." class="w-full border-slate-700 bg-slate-800/90 text-white rounded-xl text-xs focus:ring-amber-400 focus:border-amber-400"></textarea>
                            </div>
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold px-5 py-2.5 rounded-xl text-xs transition shadow-2xs flex items-center gap-1.5">
                                <i class="fa-solid fa-lock"></i> Simpan Catatan Rahasia HR
                            </button>
                        </form>
                    </div>

                    <!-- CARD 6: EMAIL TEMPLATE AUTO-SENDER ENGINE -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6" x-data="emailTemplateEngine()">
                        <div class="border-b border-slate-100 pb-4">
                            <h4 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-paper-plane text-indigo-600"></i> Auto-Sender Email Template HR
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">Kirim email resmi terformat otomatis (*Undangan Interview & Penolakan Halus*) langsung ke alamat email kandidat.</p>
                        </div>

                        <!-- Template Quick Buttons -->
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
</x-app-layout>

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
