<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <a href="{{ route('mentor.dashboard') }}" class="p-2.5 rounded-xl bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 transition shadow-xs">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="font-bold text-xl sm:text-2xl text-slate-900 dark:text-white tracking-tight">
                        Penilaian Kinerja Magang Akhir
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                        Peserta: <strong>{{ $intern->name }}</strong> &bull; {{ $intern->email }}
                    </p>
                </div>
            </div>

            <span class="px-3 py-1.5 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 rounded-xl text-xs font-semibold border border-blue-200/80 dark:border-blue-900">
                Performance Rating
            </span>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        discipline: {{ old('discipline_score', $evaluation->discipline_score ?? 90) }},
        initiative: {{ old('initiative_score', $evaluation->initiative_score ?? 85) }},
        workQuality: {{ old('work_quality_score', $evaluation->work_quality_score ?? 90) }},
        teamwork: {{ old('teamwork_score', $evaluation->teamwork_score ?? 88) }},
        problemSolving: {{ old('problem_solving_score', $evaluation->problem_solving_score ?? 85) }},
        
        get finalScore() {
            const score = (this.discipline * 0.20) + (this.initiative * 0.20) + (this.workQuality * 0.25) + (this.teamwork * 0.20) + (this.problemSolving * 0.15);
            return score.toFixed(1);
        },
        
        get finalGrade() {
            const score = parseFloat(this.finalScore);
            if (score >= 85) return 'A';
            if (score >= 75) return 'B';
            if (score >= 65) return 'C';
            return 'D';
        }
    }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <form action="{{ route('mentor.evaluations.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="user_id" value="{{ $intern->id }}">

                <!-- Live Score & Grade Calculator Banner -->
                <div class="bg-white dark:bg-slate-900 p-6 sm:p-7 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="space-y-1">
                        <span class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider block">Hasil Evaluasi Live</span>
                        <div class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white mt-1 flex items-baseline gap-3">
                            <span x-text="finalScore + ' / 100'"></span>
                            <span :class="{
                                'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800': finalGrade === 'A',
                                'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800': finalGrade === 'B',
                                'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800': finalGrade === 'C',
                                'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800': finalGrade === 'D'
                            }" class="text-xs font-semibold px-3 py-1 rounded-lg border">
                                Predikat Grade <span x-text="finalGrade" class="font-bold"></span>
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">
                            Skor dihitung otomatis berdasarkan bobot 5 indikator penilaian kompetensi.
                        </p>
                    </div>

                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 rounded-xl border border-blue-200/80 dark:border-blue-900 flex items-center justify-center text-xl shrink-0 font-bold">
                        <i class="fa-solid fa-award"></i>
                    </div>
                </div>

                <!-- 5 Competency Score Sliders Card -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-7 space-y-5">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-blue-600 dark:text-blue-400 text-xs"></i>
                        1. Penilaian 5 Indikator Kompetensi (Skala 1 - 100)
                    </h3>

                    <!-- Indikator 1: Kedisiplinan & Presensi (20%) -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-200/80 dark:border-slate-800 space-y-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <label class="text-xs font-bold text-slate-900 dark:text-white">1. Kedisiplinan & Ketepatan Waktu Presensi (Bobot 20%)</label>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Kepatuhan jam kerja, ketepatan pengisian logbook harian, dan kedisiplinan.</p>
                            </div>
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-900 px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-700 shadow-2xs" x-text="discipline"></span>
                        </div>
                        <input type="range" min="0" max="100" name="discipline_score" x-model="discipline" class="w-full accent-blue-600">
                    </div>

                    <!-- Indikator 2: Inisiatif & Keaktifan (20%) -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-200/80 dark:border-slate-800 space-y-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <label class="text-xs font-bold text-slate-900 dark:text-white">2. Inisiatif & Keaktifan Tugas (Bobot 20%)</label>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Proaktif mencari tugas tambahan, antusiasme belajar, dan keaktifan dalam tim.</p>
                            </div>
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-900 px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-700 shadow-2xs" x-text="initiative"></span>
                        </div>
                        <input type="range" min="0" max="100" name="initiative_score" x-model="initiative" class="w-full accent-blue-600">
                    </div>

                    <!-- Indikator 3: Kualitas Hasil Pekerjaan (25%) -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-200/80 dark:border-slate-800 space-y-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <label class="text-xs font-bold text-slate-900 dark:text-white">3. Kualitas Hasil Pekerjaan & Kerapian (Bobot 25%)</label>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Ketelitian kerja, kerapian kode/dokumen, dan kesesuaian target tugas.</p>
                            </div>
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-900 px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-700 shadow-2xs" x-text="workQuality"></span>
                        </div>
                        <input type="range" min="0" max="100" name="work_quality_score" x-model="workQuality" class="w-full accent-blue-600">
                    </div>

                    <!-- Indikator 4: Kerjasama Tim & Komunikasi (20%) -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-200/80 dark:border-slate-800 space-y-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <label class="text-xs font-bold text-slate-900 dark:text-white">4. Kerjasama Tim & Komunikasi (Bobot 20%)</label>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Kemampuan berkolaborasi dengan mentor, rekan kerja, dan penyampaian ide.</p>
                            </div>
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-900 px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-700 shadow-2xs" x-text="teamwork"></span>
                        </div>
                        <input type="range" min="0" max="100" name="teamwork_score" x-model="teamwork" class="w-full accent-blue-600">
                    </div>

                    <!-- Indikator 5: Problem Solving & Adaptasi (15%) -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-200/80 dark:border-slate-800 space-y-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <label class="text-xs font-bold text-slate-900 dark:text-white">5. Problem Solving & Daya Adaptasi (Bobot 15%)</label>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Kemampuan memecahkan kendala teknis dan kecepatan beradaptasi.</p>
                            </div>
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-900 px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-700 shadow-2xs" x-text="problemSolving"></span>
                        </div>
                        <input type="range" min="0" max="100" name="problem_solving_score" x-model="problemSolving" class="w-full accent-blue-600">
                    </div>

                </div>

                <!-- Feedback & Rekomendasi Re-hire Card -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-7 space-y-5">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-comment-dots text-blue-600 dark:text-blue-400 text-xs"></i>
                        2. Catatan Evaluasi & Rekomendasi Mentor
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 mb-1">Umpan Balik & Catatan Evaluasi Akhir</label>
                        <textarea name="feedback_summary" rows="4" placeholder="Berikan evaluasi menyeluruh mengenai pencapaian dan keunggulan peserta magang..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-950/60 text-slate-800 dark:text-slate-100 p-3.5 leading-relaxed">{{ old('feedback_summary', $evaluation->feedback_summary ?? 'Peserta magang menunjukkan dedikasi yang baik, cepat memahami ruang lingkup pekerjaan, dan berkontribusi secara positif pada tim.') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 mb-1">Rekomendasi Rekrutmen (Re-hire Recommendation)</label>
                        <select name="recommendation" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-950/60 text-slate-800 dark:text-slate-100 p-2.5">
                            <option value="highly_recommended" {{ old('recommendation', $evaluation->recommendation) === 'highly_recommended' ? 'selected' : '' }}>Sangat Direkomendasikan (Highly Recommended for Full-time / Re-hire)</option>
                            <option value="recommended" {{ old('recommendation', $evaluation->recommendation) === 'recommended' ? 'selected' : '' }}>Direkomendasikan (Recommended)</option>
                            <option value="neutral" {{ old('recommendation', $evaluation->recommendation) === 'neutral' ? 'selected' : '' }}>Cukup / Memenuhi Syarat Minimal</option>
                            <option value="not_recommended" {{ old('recommendation', $evaluation->recommendation) === 'not_recommended' ? 'selected' : '' }}>Tidak Direkomendasikan</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                        <a href="{{ route('mentor.dashboard') }}" class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center gap-2">
                            <i class="fa-solid fa-award text-xs"></i>
                            <span>Simpan Evaluasi Kinerja Akhir</span>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
