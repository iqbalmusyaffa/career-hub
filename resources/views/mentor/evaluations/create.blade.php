<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('mentor.dashboard') }}" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 transition">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <h2 class="font-extrabold text-xl text-slate-900 dark:text-white leading-tight">
                        Form Penilaian Kinerja Magang Akhir
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Anak Magang: {{ $intern->name }} • {{ $intern->email }}
                    </p>
                </div>
            </div>

            <span class="px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 rounded-xl text-xs font-black border border-indigo-200">
                ⭐ Final Performance Rating
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
                <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-blue-950 text-white p-6 sm:p-8 rounded-3xl shadow-xl border border-indigo-800 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div>
                        <span class="text-3xs font-black text-indigo-300 uppercase tracking-widest block">HASIL EVALUASI LIVE</span>
                        <div class="text-3xl font-black mt-1 flex items-baseline gap-3">
                            <span x-text="finalScore + ' / 100'"></span>
                            <span :class="{
                                'bg-emerald-500/20 text-emerald-300 border-emerald-500/40': finalGrade === 'A',
                                'bg-blue-500/20 text-blue-300 border-blue-500/40': finalGrade === 'B',
                                'bg-amber-500/20 text-amber-300 border-amber-500/40': finalGrade === 'C',
                                'bg-red-500/20 text-red-300 border-red-500/40': finalGrade === 'D'
                            }" class="text-base font-black px-3 py-0.5 rounded-full border">
                                Grade <span x-text="finalGrade"></span>
                            </span>
                        </div>
                        <p class="text-3xs text-indigo-200 mt-2">
                            Skor dihitung secara otomatis berdasarkan bobot 5 indikator penilaian kompetensi.
                        </p>
                    </div>

                    <div class="w-16 h-16 bg-white/10 rounded-2xl border border-white/20 flex items-center justify-center font-black text-2xl shrink-0">
                        🏆
                    </div>
                </div>

                <!-- 5 Competency Score Sliders Card -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-indigo-500"></i>
                        1. Penilaian 5 Indikator Kompetensi (Skala 1 - 100)
                    </h3>

                    <!-- Indikator 1: Kedisiplinan & Presensi (20%) -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <label class="text-xs font-black text-slate-900 dark:text-white">1. Kedisiplinan & Ketepatan Waktu Presensi (Bobot 20%)</label>
                                <p class="text-3xs text-slate-500">Kepatuhan jam kerja, ketepatan pengisian logbook harian, dan kedisiplinan.</p>
                            </div>
                            <span class="text-sm font-black text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-700" x-text="discipline"></span>
                        </div>
                        <input type="range" min="0" max="100" name="discipline_score" x-model="discipline" class="w-full accent-indigo-600">
                    </div>

                    <!-- Indikator 2: Inisiatif & Keaktifan (20%) -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <label class="text-xs font-black text-slate-900 dark:text-white">2. Inisiatif & Keaktifan Tugas (Bobot 20%)</label>
                                <p class="text-3xs text-slate-500">Proaktif mencari tugas tambahan, antusiasme belajar, dan keaktifan dalam tim.</p>
                            </div>
                            <span class="text-sm font-black text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-700" x-text="initiative"></span>
                        </div>
                        <input type="range" min="0" max="100" name="initiative_score" x-model="initiative" class="w-full accent-indigo-600">
                    </div>

                    <!-- Indikator 3: Kualitas Hasil Pekerjaan (25%) -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <label class="text-xs font-black text-slate-900 dark:text-white">3. Kualitas Hasil Pekerjaan & Kerapian (Bobot 25%)</label>
                                <p class="text-3xs text-slate-500">Ketelitian kerja, kerapian kode/dokumen, dan kesesuaian target tugas.</p>
                            </div>
                            <span class="text-sm font-black text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-700" x-text="workQuality"></span>
                        </div>
                        <input type="range" min="0" max="100" name="work_quality_score" x-model="workQuality" class="w-full accent-indigo-600">
                    </div>

                    <!-- Indikator 4: Kerjasama Tim & Komunikasi (20%) -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <label class="text-xs font-black text-slate-900 dark:text-white">4. Kerjasama Tim & Komunikasi (Bobot 20%)</label>
                                <p class="text-3xs text-slate-500">Kemampuan berkolaborasi dengan mentor, rekan kerja, dan penyampaian ide.</p>
                            </div>
                            <span class="text-sm font-black text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-700" x-text="teamwork"></span>
                        </div>
                        <input type="range" min="0" max="100" name="teamwork_score" x-model="teamwork" class="w-full accent-indigo-600">
                    </div>

                    <!-- Indikator 5: Problem Solving & Adaptasi (15%) -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <label class="text-xs font-black text-slate-900 dark:text-white">5. Problem Solving & Daya Adaptasi (Bobot 15%)</label>
                                <p class="text-3xs text-slate-500">Kemampuan memecahkan kendala teknis dan kecepatan beradaptasi.</p>
                            </div>
                            <span class="text-sm font-black text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-700" x-text="problemSolving"></span>
                        </div>
                        <input type="range" min="0" max="100" name="problem_solving_score" x-model="problemSolving" class="w-full accent-indigo-600">
                    </div>

                </div>

                <!-- Feedback & Rekomendasi Re-hire Card -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-comment-medical text-indigo-500"></i>
                        2. Catatan Evaluasi & Rekomendasi Mentor
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 mb-1">Umpan Balik & Catatan Evaluasi Akhir</label>
                        <textarea name="feedback_summary" rows="4" placeholder="Berikan evaluasi menyeluruh mengenai pencapaian dan keunggulan anak magang..." class="w-full text-xs rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 p-3.5 leading-relaxed">{{ old('feedback_summary', $evaluation->feedback_summary ?? 'Anak magang menunjukkan dedikasi yang luar biasa, cepat memahami arsitektur sistem, dan berkontribusi signifikan pada pengembangan aplikasi.') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 mb-1">Rekomendasi Rekrutmen (Re-hire Recommendation)</label>
                        <select name="recommendation" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 p-2.5">
                            <option value="highly_recommended" {{ old('recommendation', $evaluation->recommendation) === 'highly_recommended' ? 'selected' : '' }}>🌟 Sangat Direkomendasikan (Highly Recommended for Full-time / Re-hire)</option>
                            <option value="recommended" {{ old('recommendation', $evaluation->recommendation) === 'recommended' ? 'selected' : '' }}>👍 Direkomendasikan (Recommended)</option>
                            <option value="neutral" {{ old('recommendation', $evaluation->recommendation) === 'neutral' ? 'selected' : '' }}>😐 Cukup / Memenuhi Syarat Minimal</option>
                            <option value="not_recommended" {{ old('recommendation', $evaluation->recommendation) === 'not_recommended' ? 'selected' : '' }}>⚠️ Tidak Direkomendasikan</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                        <a href="{{ route('mentor.dashboard') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-200 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition flex items-center gap-2">
                            <i class="fa-solid fa-award"></i>
                            Simpan Evaluasi Kinerja Akhir
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
