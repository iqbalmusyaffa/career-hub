<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <a href="{{ route('candidate.logbook.index') }}" class="w-8 h-8 rounded-xl bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 transition flex items-center justify-center shadow-2xs shrink-0">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h1 class="text-lg sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 dark:bg-indigo-500 text-white flex items-center justify-center text-xs shadow-xs shrink-0">
                            <i class="fa-solid fa-award"></i>
                        </span>
                        <span>Transkrip & Evaluasi Kinerja Magang</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Hasil penilaian kinerja akhir dari Mentor Pembimbing & Perusahaan.
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

    <div class="py-6 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Executive Final Score Card -->
            <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-blue-950 text-white p-6 sm:p-8 rounded-2xl shadow-xl border border-indigo-800 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <span class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest block">PREDIKAT & SKOR AKHIR MAGANG</span>
                    <div class="text-4xl font-black mt-2 flex items-baseline gap-3">
                        <span>{{ number_format($evaluation->final_score, 1) }}</span>
                        <span class="text-xs font-bold px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 rounded-full">
                            Grade {{ $evaluation->final_grade }} (Sangat Memuaskan)
                        </span>
                    </div>
                    <p class="text-xs text-indigo-200 mt-2">
                        Diterbitkan oleh Mentor Pembimbing pada {{ $evaluation->evaluated_at ? $evaluation->evaluated_at->isoFormat('D MMMM YYYY') : now()->isoFormat('D MMMM YYYY') }}.
                    </p>
                </div>

                <div class="w-16 h-16 bg-white/10 rounded-2xl border border-white/20 flex items-center justify-center font-black text-2xl shrink-0">
                    🏆
                </div>
            </div>

            <!-- Breakdown Chart & 5 Competency Indicators -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6 space-y-6">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-chart-column text-indigo-500"></i>
                    Rincian Nilai 5 Indikator Kompetensi
                </h3>

                <div class="space-y-4">
                    <!-- Indikator 1 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-semibold text-slate-800 dark:text-slate-200">
                            <span>1. Kedisiplinan & Presensi (Bobot 20%)</span>
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $evaluation->discipline_score }} / 100</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden border border-slate-200/60 dark:border-slate-700">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $evaluation->discipline_score }}%"></div>
                        </div>
                    </div>

                    <!-- Indikator 2 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-semibold text-slate-800 dark:text-slate-200">
                            <span>2. Inisiatif & Keaktifan (Bobot 20%)</span>
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $evaluation->initiative_score }} / 100</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden border border-slate-200/60 dark:border-slate-700">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $evaluation->initiative_score }}%"></div>
                        </div>
                    </div>

                    <!-- Indikator 3 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-semibold text-slate-800 dark:text-slate-200">
                            <span>3. Kualitas Hasil Pekerjaan (Bobot 25%)</span>
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $evaluation->work_quality_score }} / 100</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden border border-slate-200/60 dark:border-slate-700">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $evaluation->work_quality_score }}%"></div>
                        </div>
                    </div>

                    <!-- Indikator 4 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-semibold text-slate-800 dark:text-slate-200">
                            <span>4. Kerjasama Tim & Komunikasi (Bobot 20%)</span>
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $evaluation->teamwork_score }} / 100</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden border border-slate-200/60 dark:border-slate-700">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $evaluation->teamwork_score }}%"></div>
                        </div>
                    </div>

                    <!-- Indikator 5 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-semibold text-slate-800 dark:text-slate-200">
                            <span>5. Problem Solving & Adaptasi (Bobot 15%)</span>
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $evaluation->problem_solving_score }} / 100</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden border border-slate-200/60 dark:border-slate-700">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $evaluation->problem_solving_score }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Mentor Feedback & Recommendation -->
                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 space-y-4">
                    <div class="p-4 bg-indigo-50/60 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/60 rounded-xl">
                        <h4 class="text-xs font-bold text-indigo-900 dark:text-indigo-300 mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-comment-dots"></i> Catatan & Umpan Balik Mentor:
                        </h4>
                        <p class="text-xs text-indigo-800 dark:text-indigo-200 leading-relaxed font-normal">
                            "{{ $evaluation->feedback_summary }}"
                        </p>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200/80 dark:border-slate-800">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Rekomendasi Rekrutmen Mentor:</span>
                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-full text-[10px] font-bold uppercase">
                            🌟 {{ str_replace('_', ' ', strtoupper($evaluation->recommendation)) }}
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
