<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-blue-600"></i> Ujian Seleksi Online
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Posisi: <strong class="text-slate-800 dark:text-slate-200">{{ $job->title }}</strong> &bull; {{ $job->company_name ?: 'Perusahaan Mitra' }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <div id="timer-box" class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 px-4 py-2 rounded-2xl text-rose-700 dark:text-rose-300 font-black text-sm flex items-center gap-2 shadow-xs">
                    <i class="fa-solid fa-stopwatch animate-pulse text-base"></i> Sisa Waktu: <span id="timer-display">--:--</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div x-data="takeTest()" class="py-8 bg-slate-50/70 dark:bg-slate-950 min-h-screen select-none"
         @copy.prevent="warnAntiCheat('Menyalin teks dilarang selama ujian!')" 
         @cut.prevent="warnAntiCheat('Memotong teks dilarang selama ujian!')" 
         @contextmenu.prevent="warnAntiCheat('Klik kanan dinonaktifkan demi keamanan ujian!')">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Anti-Cheat Warning Toast Banner -->
            <div x-show="warningToast" x-cloak 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="-translate-y-4 opacity-0"
                 x-transition:enter-end="translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 class="p-4 bg-amber-500 text-slate-950 rounded-2xl shadow-xl flex items-center justify-between gap-3 font-bold text-xs sticky top-4 z-40 border border-amber-600">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-base"></i>
                    <span x-text="warningToast"></span>
                </div>
                <button type="button" @click="warningToast = ''" class="text-slate-900 hover:text-white">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Main Test Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- LEFT COLUMN: Questions List (lg:col-span-8) -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- Header Info Box -->
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-xs border border-slate-200/80 dark:border-slate-800 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-900 text-3xs font-extrabold uppercase tracking-wider rounded-md">
                                    {{ ucfirst($test->category ?? 'Ujian Seleksi') }}
                                </span>
                                <h3 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white mt-1.5">{{ $test->title }}</h3>
                            </div>
                            @if($test->session_name)
                                <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-900 text-3xs font-extrabold rounded-full shrink-0">
                                    <i class="fa-solid fa-layer-group mr-1"></i> {{ $test->session_name }}
                                </span>
                            @endif
                        </div>

                        @if($test->description)
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed bg-slate-50 dark:bg-slate-800/50 p-3.5 rounded-2xl border border-slate-200/60 dark:border-slate-700">
                                <strong class="text-slate-800 dark:text-slate-200 block text-3xs uppercase mb-0.5">Petunjuk Pengerjaan:</strong>
                                {{ $test->description }}
                            </p>
                        @endif

                        @if($test->file_path)
                            <div class="p-3.5 bg-rose-50 dark:bg-rose-950/40 rounded-2xl border border-rose-200 dark:border-rose-900 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2 text-xs font-bold text-rose-900 dark:text-rose-200">
                                    <i class="fa-solid fa-file-pdf text-rose-600 text-base shrink-0"></i>
                                    <span>Terdapat Dokumen Lampiran Soal / Brief PDF</span>
                                </div>
                                <a href="{{ Storage::url($test->file_path) }}" target="_blank" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-xl text-3xs shadow-xs transition flex items-center gap-1.5 shrink-0">
                                    <i class="fa-solid fa-download"></i> Unduh PDF
                                </a>
                            </div>
                        @endif

                        <div class="flex items-center gap-4 text-3xs font-bold text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-100 dark:border-slate-800 flex-wrap">
                            <span>⏱️ Durasi: <strong class="text-slate-800 dark:text-slate-200">{{ $test->duration_minutes }} Menit</strong></span>
                            <span>🎯 KKM Lulus: <strong class="text-emerald-600 dark:text-emerald-400">{{ $test->passing_score }}%</strong></span>
                            <span>📝 Total Soal: <strong class="text-blue-600 dark:text-blue-400">{{ $questions->count() }} Butir</strong></span>
                            @if($test->deadline_at)
                                <span>📅 Batas Akhir: <strong class="text-rose-600">{{ $test->deadline_at->translatedFormat('d M Y, H:i') }} WIB</strong></span>
                            @endif
                        </div>
                    </div>

                    <!-- Exam Form Submission -->
                    <form id="test-form" method="POST" action="{{ route('candidate.tests.submit', $job->id) }}" class="space-y-6">
                        @csrf

                        @foreach($questions as $index => $q)
                            <div id="question-{{ $index }}" 
                                 class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-7 shadow-xs border transition-all space-y-4"
                                 :class="flagged[{{ $q->id }}] ? 'border-amber-400 ring-2 ring-amber-400/20' : (answers[{{ $q->id }}] ? 'border-blue-400/80 dark:border-blue-800' : 'border-slate-200/80 dark:border-slate-800')">
                                
                                <!-- Question Header Bar -->
                                <div class="flex items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-7 h-7 rounded-xl bg-blue-600 text-white font-black flex items-center justify-center text-xs shadow-xs">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                            Soal Nomor {{ $index + 1 }} dari {{ $questions->count() }}
                                        </span>
                                    </div>

                                    <!-- Flag / Ragu-ragu Toggle Button -->
                                    <button type="button" 
                                            @click="toggleFlag({{ $q->id }})" 
                                            class="px-3 py-1 rounded-xl text-3xs font-extrabold transition flex items-center gap-1.5 border"
                                            :class="flagged[{{ $q->id }}] ? 'bg-amber-400 text-slate-950 border-amber-500 shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:bg-amber-50 dark:hover:bg-amber-950/40'">
                                        <i class="fa-solid fa-flag text-3xs" :class="flagged[{{ $q->id }}] ? 'text-slate-950' : 'text-amber-500'"></i>
                                        <span x-text="flagged[{{ $q->id }}] ? 'Ditandai Ragu-ragu' : 'Ragu-ragu'"></span>
                                    </button>
                                </div>

                                <!-- Question Text -->
                                <div class="text-sm sm:text-base font-bold text-slate-900 dark:text-white leading-relaxed pt-1">
                                    {{ $q->question_text }}
                                </div>

                                <!-- Options Grid -->
                                <div class="grid grid-cols-1 gap-2.5 pt-1">
                                    
                                    <!-- Option A -->
                                    <label class="flex items-center gap-3.5 p-3.5 rounded-2xl border transition-all cursor-pointer"
                                           :class="answers[{{ $q->id }}] === 'a' ? 'border-blue-600 bg-blue-50/60 dark:bg-blue-950/40 ring-2 ring-blue-500/20 shadow-xs' : 'border-slate-200 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-800/40 hover:bg-slate-100/70 dark:hover:bg-slate-800/80'">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="a" x-model="answers[{{ $q->id }}]" class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-3xs font-black shrink-0"
                                              :class="answers[{{ $q->id }}] === 'a' ? 'bg-blue-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300'">
                                            A
                                        </span>
                                        <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $q->option_a }}</span>
                                    </label>

                                    <!-- Option B -->
                                    <label class="flex items-center gap-3.5 p-3.5 rounded-2xl border transition-all cursor-pointer"
                                           :class="answers[{{ $q->id }}] === 'b' ? 'border-blue-600 bg-blue-50/60 dark:bg-blue-950/40 ring-2 ring-blue-500/20 shadow-xs' : 'border-slate-200 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-800/40 hover:bg-slate-100/70 dark:hover:bg-slate-800/80'">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="b" x-model="answers[{{ $q->id }}]" class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-3xs font-black shrink-0"
                                              :class="answers[{{ $q->id }}] === 'b' ? 'bg-blue-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300'">
                                            B
                                        </span>
                                        <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $q->option_b }}</span>
                                    </label>

                                    <!-- Option C -->
                                    <label class="flex items-center gap-3.5 p-3.5 rounded-2xl border transition-all cursor-pointer"
                                           :class="answers[{{ $q->id }}] === 'c' ? 'border-blue-600 bg-blue-50/60 dark:bg-blue-950/40 ring-2 ring-blue-500/20 shadow-xs' : 'border-slate-200 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-800/40 hover:bg-slate-100/70 dark:hover:bg-slate-800/80'">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="c" x-model="answers[{{ $q->id }}]" class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-3xs font-black shrink-0"
                                              :class="answers[{{ $q->id }}] === 'c' ? 'bg-blue-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300'">
                                            C
                                        </span>
                                        <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $q->option_c }}</span>
                                    </label>

                                    <!-- Option D -->
                                    <label class="flex items-center gap-3.5 p-3.5 rounded-2xl border transition-all cursor-pointer"
                                           :class="answers[{{ $q->id }}] === 'd' ? 'border-blue-600 bg-blue-50/60 dark:bg-blue-950/40 ring-2 ring-blue-500/20 shadow-xs' : 'border-slate-200 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-800/40 hover:bg-slate-100/70 dark:hover:bg-slate-800/80'">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="d" x-model="answers[{{ $q->id }}]" class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-3xs font-black shrink-0"
                                              :class="answers[{{ $q->id }}] === 'd' ? 'bg-blue-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300'">
                                            D
                                        </span>
                                        <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $q->option_d }}</span>
                                    </label>

                                    <!-- Option E (if present) -->
                                    @if(!empty($q->option_e))
                                        <label class="flex items-center gap-3.5 p-3.5 rounded-2xl border transition-all cursor-pointer"
                                               :class="answers[{{ $q->id }}] === 'e' ? 'border-blue-600 bg-blue-50/60 dark:bg-blue-950/40 ring-2 ring-blue-500/20 shadow-xs' : 'border-slate-200 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-800/40 hover:bg-slate-100/70 dark:hover:bg-slate-800/80'">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="e" x-model="answers[{{ $q->id }}]" class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                                            <span class="w-5 h-5 rounded-full flex items-center justify-center text-3xs font-black shrink-0"
                                                  :class="answers[{{ $q->id }}] === 'e' ? 'bg-blue-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300'">
                                                E
                                            </span>
                                            <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $q->option_e }}</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>

                <!-- RIGHT COLUMN: Sticky Question Palette & Submit Bar (lg:col-span-4) -->
                <div class="lg:col-span-4 sticky top-6 space-y-4">
                    
                    <!-- Question Palette Card -->
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-5 sm:p-6 shadow-xl border border-slate-200/80 dark:border-slate-800 space-y-4">
                        
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                            <h4 class="font-black text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-table-cells text-blue-600"></i> Navigasi Soal
                            </h4>
                            <span class="text-xs font-extrabold text-blue-600 dark:text-blue-400">
                                <span x-text="answeredCount()"></span>/{{ $questions->count() }} Terjawab
                            </span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="space-y-1.5">
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                     :style="`width: ${progressPercent()}%`"></div>
                            </div>
                            <div class="flex justify-between text-3xs font-bold text-slate-400">
                                <span>Progres Pengerjaan</span>
                                <span x-text="`${progressPercent()}%`"></span>
                            </div>
                        </div>

                        <!-- Grid Number Buttons -->
                        <div class="grid grid-cols-5 gap-2 max-h-64 overflow-y-auto pr-1">
                            @foreach($questions as $index => $q)
                                <button type="button" 
                                        @click="scrollToQuestion({{ $index }})"
                                        class="h-10 rounded-xl font-black text-xs transition flex items-center justify-center relative shadow-2xs"
                                        :class="flagged[{{ $q->id }}] ? 'bg-amber-400 text-slate-950 font-black ring-2 ring-amber-500' : (answers[{{ $q->id }}] ? 'bg-emerald-600 text-white font-black' : 'bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700')">
                                    <span>{{ $index + 1 }}</span>
                                    <template x-if="flagged[{{ $q->id }}]">
                                        <i class="fa-solid fa-flag text-[9px] text-slate-950 absolute top-1 right-1"></i>
                                    </template>
                                </button>
                            @endforeach
                        </div>

                        <!-- Color Legend -->
                        <div class="grid grid-cols-3 gap-2 pt-3 border-t border-slate-100 dark:border-slate-800 text-3xs font-bold text-slate-600 dark:text-slate-400">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded bg-emerald-600 shrink-0"></span>
                                <span>Terjawab</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded bg-amber-400 shrink-0"></span>
                                <span>Ragu-ragu</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded bg-slate-200 dark:bg-slate-700 shrink-0"></span>
                                <span>Belum</span>
                            </div>
                        </div>

                        <!-- Final Action Button -->
                        <div class="pt-2">
                            <button type="button" 
                                    @click="openConfirmModal()"
                                    class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-extrabold text-xs rounded-2xl shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>Selesaikan & Kirim Ujian</span>
                            </button>
                        </div>
                    </div>

                    <!-- Security Reminder Card -->
                    <div class="p-4 rounded-2xl bg-slate-100/80 dark:bg-slate-900/60 border border-slate-200/70 dark:border-slate-800 text-3xs text-slate-500 space-y-1">
                        <div class="font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1">
                            <i class="fa-solid fa-shield-halved text-blue-600"></i> Sistem Pengawasan Ujian Aktif
                        </div>
                        <p>Dilarang berpindah tab browser, membuka aplikasi lain, atau menyalin soal selama ujian berlangsung.</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Submit Confirmation Modal -->
        <div x-show="showConfirmModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-200 dark:border-slate-800 text-center space-y-5 animate-in fade-in zoom-in duration-200">
                
                <div class="w-16 h-16 rounded-3xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto text-2xl shadow-xs">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>

                <div class="space-y-1.5">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white">Konfirmasi Pengumpulan Ujian</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Pastikan Anda telah memeriksa kembali seluruh jawaban sebelum mengirim.</p>
                </div>

                <!-- Bento Summary Status -->
                <div class="grid grid-cols-3 gap-2.5 text-xs text-center">
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900 rounded-2xl">
                        <span class="text-3xs uppercase font-extrabold text-emerald-600 block">Terjawab</span>
                        <span class="font-black text-base text-emerald-800 dark:text-emerald-300" x-text="answeredCount()"></span>
                    </div>
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900 rounded-2xl">
                        <span class="text-3xs uppercase font-extrabold text-amber-600 block">Ragu-ragu</span>
                        <span class="font-black text-base text-amber-800 dark:text-amber-300" x-text="flaggedCount()"></span>
                    </div>
                    <div class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 rounded-2xl">
                        <span class="text-3xs uppercase font-extrabold text-rose-600 block">Belum</span>
                        <span class="font-black text-base text-rose-800 dark:text-rose-300" x-text="unansweredCount()"></span>
                    </div>
                </div>

                <template x-if="unansweredCount() > 0 || flaggedCount() > 0">
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-xl border border-amber-200 dark:border-amber-900 text-3xs text-amber-800 dark:text-amber-300 font-semibold">
                        ⚠️ Masih ada <strong x-text="unansweredCount()"></strong> butir soal belum dijawab dan <strong x-text="flaggedCount()"></strong> butir ditandai ragu-ragu.
                    </div>
                </template>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button" @click="showConfirmModal = false" class="py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition">
                        Periksa Lagi
                    </button>
                    <button type="button" @click="submitFormNow()" class="py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">
                        Ya, Kumpulkan
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Test Taking Alpine.js Component & Timer -->
    <script>
        function takeTest() {
            return {
                answers: {},
                flagged: {},
                totalQuestions: {{ $questions->count() }},
                showConfirmModal: false,
                warningToast: '',
                tabSwitchCount: 0,

                answeredCount() {
                    return Object.keys(this.answers).filter(k => this.answers[k] !== undefined && this.answers[k] !== '').length;
                },

                flaggedCount() {
                    return Object.keys(this.flagged).filter(k => this.flagged[k] === true).length;
                },

                unansweredCount() {
                    return Math.max(0, this.totalQuestions - this.answeredCount());
                },

                progressPercent() {
                    if (this.totalQuestions === 0) return 0;
                    return Math.round((this.answeredCount() / this.totalQuestions) * 100);
                },

                toggleFlag(qid) {
                    this.flagged[qid] = !this.flagged[qid];
                },

                scrollToQuestion(index) {
                    const el = document.getElementById('question-' + index);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                },

                warnAntiCheat(msg) {
                    this.warningToast = msg;
                    setTimeout(() => {
                        this.warningToast = '';
                    }, 4000);
                },

                openConfirmModal() {
                    this.showConfirmModal = true;
                },

                submitFormNow() {
                    document.getElementById('test-form').submit();
                },

                init() {
                    // Anti-Cheat: Detect Tab Switching
                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden) {
                            this.tabSwitchCount++;
                            this.warnAntiCheat(`⚠️ Peringatan (${this.tabSwitchCount}): Anda terdeteksi beralih dari jendela ujian!`);
                        }
                    });
                }
            }
        }

        // Countdown Timer Script
        document.addEventListener('DOMContentLoaded', function () {
            let totalSeconds = {{ $test->duration_minutes * 60 }};
            const display = document.getElementById('timer-display');
            const form = document.getElementById('test-form');

            const interval = setInterval(function () {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;

                display.textContent = 
                    (minutes < 10 ? '0' : '') + minutes + ':' + 
                    (seconds < 10 ? '0' : '') + seconds;

                if (totalSeconds <= 0) {
                    clearInterval(interval);
                    alert('Waktu pengerjaan tes telah habis! Jawaban Anda akan dikumpulkan secara otomatis.');
                    form.submit();
                }

                totalSeconds--;
            }, 1000);
        });
    </script>
</x-app-layout>
