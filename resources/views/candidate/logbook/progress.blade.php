<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <a href="{{ route('candidate.logbook.index') }}" class="w-8 h-8 rounded-xl bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 transition flex items-center justify-center shadow-2xs shrink-0">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h1 class="text-lg sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-blue-600 dark:bg-blue-500 text-white flex items-center justify-center text-xs shadow-xs shrink-0">
                            <i class="fa-solid fa-chart-line"></i>
                        </span>
                        <span>Perkembangan Magang</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Informasi kurikulum, evaluasi mentor, dan rincian uang saku selama program magang.
                    </p>
                </div>
            </div>

            <!-- Server Time Badge (Live Clock) -->
            <div class="flex items-center gap-2 shrink-0" x-data="{
                currentTime: '',
                updateClock() {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    this.currentTime = `${hours}.${minutes}.${seconds} WIB (GMT+7)`;
                }
            }" x-init="updateClock(); setInterval(() => updateClock(), 1000)">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 text-xs font-medium border border-slate-200 dark:border-slate-800 shadow-2xs">
                    <i class="fa-regular fa-clock text-slate-400"></i>
                    <span>Waktu Server <span x-text="currentTime" class="font-mono font-bold text-slate-800 dark:text-slate-200"></span></span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8" x-data="{
        openModal: null, // 'kurikulum', 'evaluasi', 'uang_saku', 'survei'
        surveyRatingMentor: 5,
        surveyRatingProgram: 5,
    }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl shadow-xs flex items-center justify-between text-xs font-medium transition">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            <!-- Candidate Profile Summary Card (Matching Dashboard Design) -->
            <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-600 dark:bg-blue-600 text-white font-bold text-lg flex items-center justify-center shrink-0 shadow-xs">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/60 border border-blue-200/80 dark:border-blue-900 text-blue-700 dark:text-blue-300 rounded-md text-[11px] font-bold uppercase tracking-wider">
                                {{ $jobTitle }}
                            </span>
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-normal">• {{ $periodName }}</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white mt-1">
                            {{ $user->name }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-normal mt-0.5">
                            Periode: {{ $periodStartDate->translatedFormat('d F Y') }} — {{ $periodEndDate->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0 self-start sm:self-center">
                    <a href="{{ route('candidate.logbook.index') }}" class="inline-flex items-center gap-2 py-2 px-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700/80 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl transition border border-slate-200/60 dark:border-slate-700/60">
                        <i class="fa-solid fa-calendar-check text-blue-600 dark:text-blue-400"></i>
                        <span>Lihat Logbook</span>
                    </a>
                </div>
            </div>

            <!-- Graduation & Target Hours Progress Tracker -->
            <div class="p-5 sm:p-6 bg-gradient-to-br {{ $certificate ? 'from-amber-500/10 via-amber-500/5 to-slate-900 border-amber-500/30' : ($isEligibleForCertificate ? 'from-emerald-500/10 via-emerald-500/5 to-slate-900 border-emerald-500/30' : 'from-slate-900 to-slate-900 border-slate-200/80 dark:border-slate-800') }} rounded-2xl border shadow-xs space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl {{ $certificate ? 'bg-amber-500/20 text-amber-500' : ($isEligibleForCertificate ? 'bg-emerald-500/20 text-emerald-500' : 'bg-blue-500/20 text-blue-500') }} flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span>Status Kelulusan & Target Jam Magang</span>
                                @if($certificate)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30 uppercase tracking-wider">
                                        LULUS & TERSERTIFIKASI
                                    </span>
                                @elseif($isEligibleForCertificate)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 uppercase tracking-wider">
                                        MEMENUHI SYARAT KELULUSAN
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-500/30 uppercase tracking-wider">
                                        SEDANG BERLANGSUNG
                                    </span>
                                @endif
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal mt-0.5">
                                Realisasi jam kerja logbook yang telah disetujui Mentor vs target kelulusan resmi.
                            </p>
                        </div>
                    </div>

                    <!-- Quick Claim or View Buttons -->
                    @if($certificate)
                        <div class="flex items-center gap-2">
                            <button type="button" @click="openModal = 'sertifikat'" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-2">
                                <i class="fa-solid fa-award"></i>
                                <span>Lihat E-Sertifikat & Transkrip</span>
                            </button>
                        </div>
                    @elseif($isEligibleForCertificate)
                        <form method="POST" action="{{ route('candidate.logbook.claim-certificate') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-2 animate-pulse">
                                <i class="fa-solid fa-file-circle-check"></i>
                                <span>Klaim E-Sertifikat & Transkrip</span>
                            </button>
                        </form>
                    @endif
                </div>

                @php
                    $pct = $targetHours > 0 ? min(100, round(($totalApprovedHours / $targetHours) * 100)) : 0;
                @endphp

                <!-- Progress Bar -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-600 dark:text-slate-400">Total Akumulasi Jam Kerja: <strong class="text-slate-900 dark:text-white">{{ $totalApprovedHours }} / {{ $targetHours }} Jam</strong></span>
                        <span class="{{ $pct >= 100 ? 'text-emerald-600 dark:text-emerald-400' : 'text-blue-600 dark:text-blue-400' }}">{{ $pct }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden">
                        <div class="{{ $pct >= 100 ? 'bg-emerald-500' : 'bg-blue-600' }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                    </div>
                </div>

                <!-- Metrics Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 text-xs">
                    <div class="p-3 bg-white dark:bg-slate-950 rounded-xl border border-slate-200/80 dark:border-slate-800">
                        <span class="text-[10px] text-slate-400 block uppercase font-bold">Presensi Hadir</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white mt-0.5 block">{{ $totalDaysPresent }} Hari</span>
                    </div>
                    <div class="p-3 bg-white dark:bg-slate-950 rounded-xl border border-slate-200/80 dark:border-slate-800">
                        <span class="text-[10px] text-slate-400 block uppercase font-bold">Target Jam</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white mt-0.5 block">{{ $targetHours }} Jam</span>
                    </div>
                    <div class="p-3 bg-white dark:bg-slate-950 rounded-xl border border-slate-200/80 dark:border-slate-800">
                        <span class="text-[10px] text-slate-400 block uppercase font-bold">Evaluasi Mentor</span>
                        <span class="text-sm font-bold {{ $finalEvaluation ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500' }} mt-0.5 block">
                            {{ $finalEvaluation ? 'Grade ' . $finalEvaluation->final_grade : 'Menunggu' }}
                        </span>
                    </div>
                    <div class="p-3 bg-white dark:bg-slate-950 rounded-xl border border-slate-200/80 dark:border-slate-800">
                        <span class="text-[10px] text-slate-400 block uppercase font-bold">Status Sertifikat</span>
                        <span class="text-sm font-bold {{ $certificate ? 'text-amber-500' : ($isEligibleForCertificate ? 'text-emerald-500' : 'text-slate-500') }} mt-0.5 block">
                            {{ $certificate ? 'Terbit ✓' : ($isEligibleForCertificate ? 'Siap Klaim' : 'Belum Selesai') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 5 Main Action Cards -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 divide-y divide-slate-100 dark:divide-slate-800 overflow-hidden">
                
                <!-- Card 0: E-Sertifikat & Transkrip (Featured) -->
                <button type="button" @click="openModal = 'sertifikat'" class="w-full px-5 py-4 sm:px-6 sm:py-5 flex items-center justify-between hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition text-left group {{ $certificate ? 'bg-amber-50/30 dark:bg-amber-950/10' : '' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition">
                                    E-Sertifikat & Transkrip Nilai
                                </h4>
                                @if($certificate)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300">
                                        Telah Terbit
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal mt-0.5">
                                Dokumen kelulusan resmi dengan QR Code Verifikasi Publik
                            </p>
                        </div>
                    </div>
                    <div class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200 transition pl-2 flex items-center gap-2">
                        @if($certificate)
                            <span class="text-xs text-amber-600 dark:text-amber-400 font-semibold hidden sm:inline">Unduh PDF</span>
                        @endif
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </div>
                </button>

                <!-- Card 1: Kurikulum -->
                <button type="button" @click="openModal = 'kurikulum'" class="w-full px-5 py-4 sm:px-6 sm:py-5 flex items-center justify-between hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition text-left group">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <div>
                            <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">
                                Kurikulum
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal mt-0.5">
                                Kurikulum Magang dan Fokus Pembelajaran
                            </p>
                        </div>
                    </div>
                    <div class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200 transition pl-2">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </div>
                </button>

                <!-- Card 2: Evaluasi Bulanan -->
                <button type="button" @click="openModal = 'evaluasi'" class="w-full px-5 py-4 sm:px-6 sm:py-5 flex items-center justify-between hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition text-left group">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <div>
                            <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                Evaluasi Bulanan
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal mt-0.5">
                                Penilaian Mentor per periode
                            </p>
                        </div>
                    </div>
                    <div class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200 transition pl-2">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </div>
                </button>

                <!-- Card 3: Uang Saku -->
                <button type="button" @click="openModal = 'uang_saku'" class="w-full px-5 py-4 sm:px-6 sm:py-5 flex items-center justify-between hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition text-left group">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <div>
                            <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition">
                                Uang Saku
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal mt-0.5">
                                Rincian pembayaran per periode
                            </p>
                        </div>
                    </div>
                    <div class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200 transition pl-2">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </div>
                </button>

                <!-- Card 4: Survei -->
                <button type="button" @click="openModal = 'survei'" class="w-full px-5 py-4 sm:px-6 sm:py-5 flex items-center justify-between hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition text-left group">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-square-check"></i>
                        </div>
                        <div>
                            <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                                Survei
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal mt-0.5">
                                Tersedia pada bulan terakhir magang
                            </p>
                        </div>
                    </div>
                    <div class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200 transition pl-2">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </div>
                </button>

            </div>

        </div>

        <!-- =================== MODAL 0: E-SERTIFIKAT & TRANSKRIP NILAI =================== -->
        <div x-show="openModal === 'sertifikat'" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div @click.away="openModal = null" class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">E-Sertifikat & Transkrip Nilai Digital</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Dokumen resmi kelulusan magang berstempel verifikasi publik</p>
                        </div>
                    </div>
                    <button type="button" @click="openModal = null" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-300 flex items-center justify-center text-xs transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                @if($certificate)
                    <!-- Certificate Details & Actions -->
                    <div class="space-y-4">
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-amber-500/10 via-amber-500/5 to-slate-900 border border-amber-500/30 space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-amber-500/20 pb-3">
                                <div>
                                    <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-widest block">NOMOR SERI SERTIFIKAT</span>
                                    <h4 class="text-base font-mono font-bold text-slate-900 dark:text-white">{{ $certificate->certificate_number }}</h4>
                                </div>
                                <span class="px-3 py-1 bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30 rounded-xl text-xs font-bold self-start sm:self-center">
                                    Grade: {{ $certificate->performance_grade ?? 'A' }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Penerima:</span>
                                    <strong class="text-slate-900 dark:text-white">{{ $certificate->participant_name }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Peran & Posisi:</span>
                                    <strong class="text-slate-900 dark:text-white">{{ $certificate->job_title }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Tanggal Penerbitan:</span>
                                    <strong class="text-slate-900 dark:text-white">{{ $certificate->issued_at ? $certificate->issued_at->format('d F Y') : '-' }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Mentor Pembimbing:</span>
                                    <strong class="text-slate-900 dark:text-white">{{ $certificate->mentor_name }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- 2 Big Download Buttons -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('candidate.certificates.show', $certificate) }}" target="_blank" class="p-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition flex items-center justify-between shadow-xs group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-lg shrink-0">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </div>
                                    <div class="text-left">
                                        <div class="text-xs font-bold">Unduh E-Sertifikat</div>
                                        <div class="text-[10px] text-blue-200">Format Landscape (A4)</div>
                                    </div>
                                </div>
                                <i class="fa-solid fa-arrow-down text-xs group-hover:translate-y-0.5 transition-transform"></i>
                            </a>

                            @if($transcript)
                                <a href="{{ route('candidate.transcripts.show', $transcript) }}" target="_blank" class="p-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-white transition flex items-center justify-between shadow-xs border border-slate-700 group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center text-lg shrink-0">
                                            <i class="fa-solid fa-file-lines"></i>
                                        </div>
                                        <div class="text-left">
                                            <div class="text-xs font-bold">Unduh Transkrip Nilai</div>
                                            <div class="text-[10px] text-slate-400">Format Portrait (A4)</div>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-arrow-down text-xs group-hover:translate-y-0.5 transition-transform"></i>
                                </a>
                            @endif
                        </div>

                        <!-- Public Verification Link & QR -->
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-500 flex items-center justify-center text-sm shrink-0">
                                    <i class="fa-solid fa-shield-check"></i>
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 dark:text-white block">Tautan Verifikasi Keaslian Publik</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">/verify-certificate/{{ $certificate->certificate_number }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 self-end sm:self-center">
                                <a href="{{ route('certificates.verify.public', ['code' => $certificate->certificate_number]) }}" target="_blank" class="px-3 py-1.5 bg-white dark:bg-slate-900 hover:bg-slate-50 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-bold transition">
                                    Buka Halaman
                                </a>
                                <button type="button" onclick="navigator.clipboard.writeText('{{ route('certificates.verify.public', ['code' => $certificate->certificate_number]) }}'); alert('Tautan verifikasi sertifikat berhasil disalin!');" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition">
                                    Salin Link
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Not yet issued view -->
                    <div class="p-6 text-center bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200/80 dark:border-slate-800 space-y-4">
                        <div class="w-14 h-14 rounded-2xl {{ $isEligibleForCertificate ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }} mx-auto flex items-center justify-center text-2xl shadow-xs">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        
                        @if($isEligibleForCertificate)
                            <div class="space-y-1">
                                <h4 class="text-base font-bold text-slate-900 dark:text-white">Syarat Kelulusan Terpenuhi! 🎉</h4>
                                <p class="text-xs text-slate-600 dark:text-slate-300 max-w-md mx-auto leading-relaxed">
                                    Akumulasi jam kerja Anda telah mencapai target ({{ $totalApprovedHours }}/{{ $targetHours }} Jam). Anda dapat langsung menerbitkan E-Sertifikat dan Transkrip Nilai digital resmi sekarang.
                                </p>
                            </div>

                            <form method="POST" action="{{ route('candidate.logbook.claim-certificate') }}" class="pt-2">
                                @csrf
                                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-2 mx-auto">
                                    <i class="fa-solid fa-file-circle-check"></i>
                                    <span>Klaim & Terbitkan E-Sertifikat Sekarang</span>
                                </button>
                            </form>
                        @else
                            <div class="space-y-1">
                                <h4 class="text-base font-bold text-slate-900 dark:text-white">E-Sertifikat Belum Tersedia</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
                                    E-Sertifikat dan Transkrip Nilai Akademik akan otomatis tersedia setelah Anda menyelesaikan target jam magang ({{ $targetHours }} Jam) atau telah menerima evaluasi akhir dari Mentor.
                                </p>
                            </div>

                            <div class="p-3.5 bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 text-xs text-left max-w-sm mx-auto space-y-2">
                                <div class="flex justify-between font-semibold">
                                    <span class="text-slate-500">Realisasi Jam Kerja:</span>
                                    <span class="text-slate-900 dark:text-white">{{ $totalApprovedHours }} / {{ $targetHours }} Jam</span>
                                </div>
                                <div class="flex justify-between font-semibold">
                                    <span class="text-slate-500">Evaluasi Akhir Mentor:</span>
                                    <span class="{{ $finalEvaluation ? 'text-emerald-500' : 'text-amber-500' }}">
                                        {{ $finalEvaluation ? 'Selesai' : 'Menunggu' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="pt-2">
                    <button type="button" @click="openModal = null" class="w-full py-2.5 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-xl text-xs font-semibold transition border border-slate-700">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- =================== MODAL 1: KURIKULUM MAGANG =================== -->
        <div x-show="openModal === 'kurikulum'" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div @click.away="openModal = null" class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $curriculumTitle ?? 'Kurikulum & Fokus Pembelajaran' }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">{{ $curriculumDescription ?? ('Silabus dan target kompetensi posisi ' . $jobTitle) }}</p>
                        </div>
                    </div>
                    <button type="button" @click="openModal = null" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-300 flex items-center justify-center text-xs transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    @foreach($curriculumModules as $module)
                        <div class="p-4 sm:p-5 rounded-xl border {{ $module['status'] === 'completed' ? 'border-emerald-200 dark:border-emerald-900/60 bg-emerald-50/40 dark:bg-emerald-950/20' : ($module['status'] === 'in_progress' ? 'border-blue-200 dark:border-blue-900/60 bg-blue-50/40 dark:bg-blue-950/20' : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40') }} space-y-3.5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $module['title'] }}</h4>
                                    @if(!empty($module['description']))
                                        <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 font-normal leading-relaxed">{{ $module['description'] }}</p>
                                    @endif
                                </div>
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider shrink-0 {{ $module['status'] === 'completed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300' : ($module['status'] === 'in_progress' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-300' : 'bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-400') }}">
                                    {{ $module['status'] === 'completed' ? 'Selesai' : ($module['status'] === 'in_progress' ? 'Sedang Berjalan' : 'Belum Dimulai') }}
                                </span>
                            </div>

                            @if(!empty($module['competencies']))
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest block mb-1.5">Fokus Kompetensi:</span>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($module['competencies'] as $comp)
                                            <span class="px-2.5 py-0.5 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-[11px] font-medium">
                                                ✓ {{ $comp }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(!empty($module['learning_links']))
                                <div class="pt-2 border-t border-slate-200/60 dark:border-slate-800/80">
                                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest block mb-1.5">Materi & Tautan Pembelajaran:</span>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($module['learning_links'] as $link)
                                            @if(!empty($link['url']))
                                                <a href="{{ $link['url'] }}" 
                                                   target="_blank" 
                                                   rel="noopener noreferrer" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-950/50 hover:bg-blue-100 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60 text-xs font-semibold transition group">
                                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-blue-500 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                                    <span>{{ !empty($link['title']) ? $link['title'] : 'Buka Materi' }}</span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(!empty($module['mentor_notes']))
                                <div class="p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs space-y-1">
                                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                                        <i class="fa-solid fa-clipboard-check text-emerald-500 text-xs"></i>
                                        Catatan Validasi Mentor:
                                    </span>
                                    <p class="text-slate-700 dark:text-slate-300 italic font-normal">
                                        "{{ $module['mentor_notes'] }}"
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="pt-2">
                    <button type="button" @click="openModal = null" class="w-full py-2.5 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-xl text-xs font-semibold transition border border-slate-700">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- =================== MODAL 2: EVALUASI BULANAN =================== -->
        <div x-show="openModal === 'evaluasi'" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div @click.away="openModal = null" class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Evaluasi Mentor Bulanan</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Penilaian performa kerja dan bimbingan mentor</p>
                        </div>
                    </div>
                    <button type="button" @click="openModal = null" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-300 flex items-center justify-center text-xs transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                @if($evaluations->isEmpty())
                    <div class="p-8 text-center bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200/80 dark:border-slate-800 space-y-2">
                        <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-950/50 text-blue-500 mx-auto flex items-center justify-center text-xl">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Belum Ada Evaluasi Bulanan</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                            Mentor akan mengisi penilaian evaluasi secara berkala di akhir setiap bulan masa magang.
                        </p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($evaluations as $eval)
                            <div class="p-4 sm:p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Evaluasi Periode {{ $eval->period ?? $loop->iteration }}</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Mentor: {{ $eval->mentor->name ?? 'Mentor Pembimbing' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $eval->overall_score ?? $eval->total_score ?? '-' }}</span>
                                        <span class="text-[10px] text-slate-400 block font-bold">SKOR AKHIR</span>
                                    </div>
                                </div>

                                @if($eval->notes || $eval->feedback)
                                    <div class="p-3.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-700 dark:text-slate-300">
                                        <span class="font-bold text-slate-900 dark:text-white block mb-1">Catatan & Masukan Mentor:</span>
                                        <p class="italic font-normal">"{{ $eval->notes ?? $eval->feedback }}"</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="pt-2 flex items-center gap-3">
                    <a href="{{ route('candidate.logbook.evaluation') }}" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold text-center transition shadow-xs">
                        Lihat Transkrip Evaluasi Lengkap
                    </a>
                    <button type="button" @click="openModal = null" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-medium transition border border-slate-200 dark:border-slate-700">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- =================== MODAL 3: UANG SAKU & POTONGAN =================== -->
        <div x-show="openModal === 'uang_saku'" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4"
             x-data="{ showBankForm: false }">
            
            <div @click.away="openModal = null" class="bg-white dark:bg-slate-900 rounded-2xl max-w-3xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Rincian & Ketentuan Uang Saku</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Informasi rekening bank KTP, toleransi izin, dan riwayat transfer</p>
                        </div>
                    </div>
                    <button type="button" @click="openModal = null" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-300 flex items-center justify-center text-xs transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Rekening Bank Section (KTP Matched) -->
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-900 dark:text-white">
                            <i class="fa-solid fa-building-columns text-emerald-600 dark:text-emerald-400"></i>
                            <span>Rekening Bank Pencairan Uang Saku</span>
                        </div>
                        <button type="button" @click="showBankForm = !showBankForm" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                            <span x-text="showBankForm ? 'Tutup Form' : 'Ubah / Lengkapi Rekening'"></span>
                        </button>
                    </div>

                    @if($onboarding && $onboarding->bank_account_number)
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs pt-1">
                            <div class="p-2.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-750">
                                <span class="text-slate-400 block text-[10px]">Nama Bank</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $onboarding->bank_name ?? 'BCA' }}</span>
                            </div>
                            <div class="p-2.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-750">
                                <span class="text-slate-400 block text-[10px]">Nomor Rekening</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200 font-mono">{{ $onboarding->bank_account_number }}</span>
                            </div>
                            <div class="p-2.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-750">
                                <span class="text-slate-400 block text-[10px]">Nama Pemilik (Sesuai KTP)</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                    <span>{{ $onboarding->bank_account_holder ?? $user->name }}</span>
                                    <i class="fa-solid fa-circle-check text-[10px]" title="Sesuai KTP Terverifikasi"></i>
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-lg border border-amber-200 dark:border-amber-900/60 text-xs text-amber-800 dark:text-amber-300 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>Rekening bank pencairan uang saku belum dilengkapi.</span>
                            </div>
                            <button type="button" @click="showBankForm = true" class="px-2.5 py-1 bg-amber-600 text-white rounded text-[11px] font-bold">
                                Isi Sekarang
                            </button>
                        </div>
                    @endif

                    <!-- Bank Account Update Form -->
                    <div x-show="showBankForm" x-cloak class="pt-3 border-t border-slate-200 dark:border-slate-700 animate-fade-in">
                        <form method="POST" action="{{ route('candidate.internship.bank-account.store') }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <div class="p-2.5 rounded-lg bg-blue-50/70 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900/50 text-[11px] text-blue-800 dark:text-blue-300">
                                <i class="fa-solid fa-circle-info mr-1"></i>
                                <strong>Ketentuan Wajib:</strong> Nama pemilik rekening bank <u>HARUS PERSIS SESUAI DENGAN NAMA KTP / AKUN ANDA</u> (<strong>{{ $user->name }}</strong>). Rekening atas nama orang lain / pihak ketiga akan ditolak oleh sistem.
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Pilih Bank *</label>
                                    <select name="bank_name" required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-emerald-500 focus:border-emerald-500 py-2">
                                        @php
                                            $currentBank = $onboarding?->bank_name ?? 'BCA';
                                            $bankList = ['BCA', 'Bank Mandiri', 'BRI', 'BNI', 'BSI (Bank Syariah Indonesia)', 'CIMB Niaga', 'Bank Permata', 'Bank Danamon', 'Bank Jago', 'Seabank', 'BTPN / Jenius', 'Bank Mega'];
                                        @endphp
                                        @foreach($bankList as $bank)
                                            <option value="{{ $bank }}" {{ $currentBank === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor Rekening *</label>
                                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $onboarding?->bank_account_number) }}" required placeholder="Contoh: 1234567890" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-emerald-500 focus:border-emerald-500 py-2">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Pemilik Rekening (Sesuai KTP) *</label>
                                    <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder', $onboarding?->bank_account_holder ?? $user->name) }}" required placeholder="{{ $user->name }}" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-emerald-500 focus:border-emerald-500 py-2">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Foto Buku Tabungan / E-Statement (Opsional)</label>
                                    <input type="file" name="bank_book_doc" accept=".pdf,.png,.jpg,.jpeg" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-700 dark:text-slate-300 p-1.5 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950 dark:file:text-emerald-300">
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 pt-2">
                                <button type="button" @click="showBankForm = false" class="px-3 py-1.5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-semibold">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-xs">
                                    Simpan & Validasi Rekening
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Policy Card (4 Days Rule & Medical Note) -->
                <div class="p-4 rounded-xl bg-amber-50/60 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 space-y-2">
                    <div class="flex items-center gap-2 text-amber-800 dark:text-amber-300 text-xs font-bold">
                        <i class="fa-solid fa-circle-info text-sm"></i>
                        <span>Ketentuan Pemotongan Uang Saku Per Periode</span>
                    </div>
                    <ul class="text-xs text-amber-900 dark:text-amber-200/90 font-normal space-y-1.5 list-disc list-inside">
                        <li><strong>Toleransi Izin & Sakit:</strong> Izin atau sakit hingga <strong>maksimal 4 hari per periode</strong> <u>TIDAK</u> dipotong uang saku.</li>
                        <li><strong>Kelebihan Izin (> 4 hari):</strong> Dikenakan pemotongan proporsional harian berdasarkan jumlah hari kerja efektif di bulan bersangkutan.</li>
                        <li><strong>Sakit / Rawat Inap:</strong> Wajib melampirkan surat keterangan dokter / rumah sakit resmi pada logbook harian untuk verifikasi.</li>
                        <li><strong>Tanpa Keterangan (Alpa):</strong> Dikenakan pemotongan langsung per hari ketidakhadiran.</li>
                    </ul>
                </div>

                <!-- Stipend Periods Ledger Table -->
                <div class="space-y-3">
                    <span class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider block">Riwayat Periode Bulanan</span>
                    
                    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 font-semibold border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="p-3">Periode</th>
                                    <th class="p-3 text-center">Hadir</th>
                                    <th class="p-3 text-center">Izin/Sakit</th>
                                    <th class="p-3 text-right">Potongan</th>
                                    <th class="p-3 text-right">Nominal Bersih</th>
                                    <th class="p-3 text-center">Status</th>
                                    <th class="p-3 text-center">Dokumen</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900 font-normal text-slate-700 dark:text-slate-200">
                                @foreach($stipendPeriods as $stipend)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="p-3 font-semibold text-slate-900 dark:text-white">
                                            <div>{{ $stipend['period_label'] }}</div>
                                            <div class="text-[10px] font-normal text-slate-400">Est. Cair: {{ $stipend['payment_date'] }}</div>
                                        </td>
                                        <td class="p-3 text-center">{{ $stipend['present_days'] }} Hari</td>
                                        <td class="p-3 text-center">
                                            <span class="{{ $stipend['excused_days'] > 4 ? 'text-rose-600 dark:text-rose-400 font-bold' : '' }}">
                                                {{ $stipend['excused_days'] }} Hari
                                            </span>
                                            @if($stipend['excused_days'] > 4)
                                                <span class="block text-[10px] text-rose-500">(+{{ $stipend['excused_days'] - 4 }} melebihi kuota)</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-right text-rose-600 dark:text-rose-400 font-medium">
                                            {{ $stipend['deduction'] > 0 ? '-Rp ' . number_format($stipend['deduction'], 0, ',', '.') : 'Rp 0' }}
                                        </td>
                                        <td class="p-3 text-right font-bold text-slate-900 dark:text-white">
                                            Rp {{ number_format($stipend['net_nominal'], 0, ',', '.') }}
                                        </td>
                                        <td class="p-3 text-center">
                                            @if($stipend['status'] === 'transferred' || $stipend['status'] === 'paid')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 text-[10px] font-bold">
                                                    <i class="fa-solid fa-circle-check text-[10px]"></i> Telah Ditransfer
                                                </span>
                                            @elseif($stipend['status'] === 'ready')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 text-[10px] font-bold">
                                                    <i class="fa-solid fa-circle-play text-[10px]"></i> Siap Ditransfer
                                                </span>
                                            @elseif(!empty($stipend['mentor_submitted_at']))
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 text-[10px] font-bold">
                                                    <i class="fa-solid fa-paper-plane text-[10px]"></i> Diajukan Mentor
                                                </span>
                                            @elseif($stipend['status'] === 'in_review')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 text-[10px] font-bold">
                                                    <i class="fa-solid fa-clock text-[10px]"></i> Menunggu Mentor
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300 text-[10px] font-bold">
                                                    Menunggu Periode
                                                </span>
                                            @endif
                                            @if(!empty($stipend['mentor_notes']))
                                                <div class="text-[9px] text-slate-400 italic truncate max-w-[120px] mx-auto mt-0.5" title="{{ $stipend['mentor_notes'] }}">
                                                    "{{ $stipend['mentor_notes'] }}"
                                                </div>
                                            @endif
                                        </td>
                                        <td class="p-3 text-center">
                                            @if(($stipend['status'] === 'transferred' || $stipend['status'] === 'paid') && !empty($stipend['id']))
                                                <a href="{{ route('candidate.internship.stipends.slip', $stipend['id']) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/80 dark:hover:bg-emerald-900 text-emerald-700 dark:text-emerald-300 rounded-lg text-[10px] font-bold transition shadow-2xs">
                                                    <i class="fa-solid fa-file-pdf"></i>
                                                    <span>Unduh E-Slip</span>
                                                </a>
                                            @else
                                                <span class="text-slate-400 text-[10px]">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" @click="openModal = null" class="w-full py-2.5 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-xl text-xs font-semibold transition border border-slate-700">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- =================== MODAL 4: SURVEI EVALUASI =================== -->
        <div x-show="openModal === 'survei'" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div @click.away="openModal = null" class="bg-white dark:bg-slate-900 rounded-2xl max-w-xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-square-check"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Survei Program Magang</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Evaluasi akhir pengalaman magang Anda</p>
                        </div>
                    </div>
                    <button type="button" @click="openModal = null" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-300 flex items-center justify-center text-xs transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                @if($hasSubmittedSurvey)
                    <div class="p-6 text-center bg-emerald-50 dark:bg-emerald-950/40 rounded-xl border border-emerald-200 dark:border-emerald-800 space-y-2">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300 mx-auto flex items-center justify-center text-xl">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <h4 class="text-sm font-bold text-emerald-900 dark:text-emerald-200">Survei Telah Dikirim</h4>
                        <p class="text-xs text-emerald-700 dark:text-emerald-300 max-w-sm mx-auto">
                            Terima kasih atas partisipasi dan masukan berharga Anda untuk pengembangan program magang selanjutnya.
                        </p>
                    </div>
                @else
                    <form method="POST" action="{{ route('candidate.logbook.survey.store') }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                1. Penilaian Kualitas Bimbingan Mentor (Skala 1 - 5)
                            </label>
                            <div class="flex items-center gap-3">
                                <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                    <button type="button" @click="surveyRatingMentor = star" class="text-2xl transition" :class="star <= surveyRatingMentor ? 'text-amber-400' : 'text-slate-300 dark:text-slate-600'">
                                        ★
                                    </button>
                                </template>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 ml-2" x-text="`${surveyRatingMentor} / 5 Bintang`"></span>
                            </div>
                            <input type="hidden" name="mentor_rating" :value="surveyRatingMentor">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                2. Penilaian Relevansi Materi & Program Magang (Skala 1 - 5)
                            </label>
                            <div class="flex items-center gap-3">
                                <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                    <button type="button" @click="surveyRatingProgram = star" class="text-2xl transition" :class="star <= surveyRatingProgram ? 'text-amber-400' : 'text-slate-300 dark:text-slate-600'">
                                        ★
                                    </button>
                                </template>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 ml-2" x-text="`${surveyRatingProgram} / 5 Bintang`"></span>
                            </div>
                            <input type="hidden" name="program_rating" :value="surveyRatingProgram">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                3. Kritik, Saran, & Kesan Selama Magang <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="feedback" rows="4" required placeholder="Tuliskan saran atau hal yang paling berkesan selama menjalani program magang di sini..." class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-200 p-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                        </div>

                        <div class="pt-2 flex items-center gap-3">
                            <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition shadow-xs">
                                Kirim Survei
                            </button>
                            <button type="button" @click="openModal = null" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-medium transition border border-slate-200 dark:border-slate-700">
                                Batal
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
