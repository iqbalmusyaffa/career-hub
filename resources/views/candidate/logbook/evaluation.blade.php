<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 dark:text-white leading-tight flex items-center gap-3">
                    <span class="p-2 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-2xl">
                        <i class="fa-solid fa-award text-xl"></i>
                    </span>
                    Transkrip & Evaluasi Kinerja Magang Akhir
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Hasil penilaian kinerja akhir dari Mentor Pembimbing & Perusahaan.
                </p>
            </div>

            <a href="{{ route('candidate.logbook.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                ← Kembali ke Kalender Presensi
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Executive Final Score Card -->
            <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-blue-950 text-white p-8 rounded-3xl shadow-xl border border-indigo-800 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <span class="text-3xs font-black text-indigo-300 uppercase tracking-widest block">PREDIKAT & SKOR AKHIR MAGANG</span>
                    <div class="text-4xl font-black mt-2 flex items-baseline gap-3">
                        <span>{{ number_format($evaluation->final_score, 1) }}</span>
                        <span class="text-lg font-black px-4 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 rounded-full">
                            Grade {{ $evaluation->final_grade }} (Sangat Memuaskan)
                        </span>
                    </div>
                    <p class="text-xs text-indigo-200 mt-2">
                        Diterbitkan oleh Mentor Pembimbing pada {{ $evaluation->evaluated_at ? $evaluation->evaluated_at->isoFormat('D MMMM YYYY') : now()->isoFormat('D MMMM YYYY') }}.
                    </p>
                </div>

                <div class="w-20 h-20 bg-white/10 rounded-2xl border border-white/20 flex items-center justify-center font-black text-3xl shrink-0">
                    🏆
                </div>
            </div>

            <!-- Breakdown Chart & 5 Competency Indicators -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                <h3 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-chart-column text-indigo-500"></i>
                    Rincian Nilai 5 Indikator Kompetensi
                </h3>

                <div class="space-y-4">
                    <!-- Indikator 1 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-bold text-slate-800 dark:text-slate-200">
                            <span>1. Kedisiplinan & Presensi (Bobot 20%)</span>
                            <span class="text-indigo-600 dark:text-indigo-400 font-extrabold">{{ $evaluation->discipline_score }} / 100</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $evaluation->discipline_score }}%"></div>
                        </div>
                    </div>

                    <!-- Indikator 2 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-bold text-slate-800 dark:text-slate-200">
                            <span>2. Inisiatif & Keaktifan (Bobot 20%)</span>
                            <span class="text-indigo-600 dark:text-indigo-400 font-extrabold">{{ $evaluation->initiative_score }} / 100</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $evaluation->initiative_score }}%"></div>
                        </div>
                    </div>

                    <!-- Indikator 3 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-bold text-slate-800 dark:text-slate-200">
                            <span>3. Kualitas Hasil Pekerjaan (Bobot 25%)</span>
                            <span class="text-indigo-600 dark:text-indigo-400 font-extrabold">{{ $evaluation->work_quality_score }} / 100</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $evaluation->work_quality_score }}%"></div>
                        </div>
                    </div>

                    <!-- Indikator 4 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-bold text-slate-800 dark:text-slate-200">
                            <span>4. Kerjasama Tim & Komunikasi (Bobot 20%)</span>
                            <span class="text-indigo-600 dark:text-indigo-400 font-extrabold">{{ $evaluation->teamwork_score }} / 100</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $evaluation->teamwork_score }}%"></div>
                        </div>
                    </div>

                    <!-- Indikator 5 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-bold text-slate-800 dark:text-slate-200">
                            <span>5. Problem Solving & Adaptasi (Bobot 15%)</span>
                            <span class="text-indigo-600 dark:text-indigo-400 font-extrabold">{{ $evaluation->problem_solving_score }} / 100</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $evaluation->problem_solving_score }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Mentor Feedback & Recommendation -->
                <div class="pt-6 border-t border-slate-100 dark:border-slate-700 space-y-4">
                    <div class="p-4 bg-indigo-50/60 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-800 rounded-2xl">
                        <h4 class="text-xs font-extrabold text-indigo-900 dark:text-indigo-300 mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-comment-dots"></i> Catatan & Umpan Balik Mentor:
                        </h4>
                        <p class="text-xs text-indigo-800 dark:text-indigo-200 leading-relaxed font-medium">
                            "{{ $evaluation->feedback_summary }}"
                        </p>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700">
                        <span class="text-xs font-extrabold text-slate-700 dark:text-slate-300">Rekomendasi Rekrutmen Mentor:</span>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-full text-3xs font-black uppercase">
                            🌟 {{ str_replace('_', ' ', strtoupper($evaluation->recommendation)) }}
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
