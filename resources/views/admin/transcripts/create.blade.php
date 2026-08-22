<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.applications.show', $application) }}" class="w-10 h-10 bg-white hover:bg-slate-100 text-slate-600 rounded-2xl border border-slate-200 flex items-center justify-center transition shadow-2xs">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    Input Transkrip Evaluasi Nilai Magang (Academic Grade Builder)
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Peserta Magang: <strong>{{ $participantName }}</strong> • NIM: <strong>{{ $studentIdNumber }}</strong> • Kampus: <strong>{{ $institutionName }}</strong></p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6">
                <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Formulir Penilaian 5 Kriteria Evaluasi Akademik Magang</h3>
                        <p class="text-xs text-slate-500">Nilai akan dikalkulasi secara otomatis menjadi Rata-Rata Akhir (GPA) dan Huruf Mutu Akademik pada berkas PDF.</p>
                    </div>
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-900 text-3xs font-black rounded-xl uppercase">
                        📊 Transkrip Nilai Akademik
                    </span>
                </div>

                <form action="{{ route('admin.applications.transcripts.store', $application) }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Transkrip <span class="text-rose-500">*</span></label>
                            <input type="text" name="transcript_number" value="{{ old('transcript_number', $transcriptNumber) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-bold py-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap Peserta Magang <span class="text-rose-500">*</span></label>
                            <input type="text" name="participant_name" value="{{ old('participant_name', $participantName) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-bold py-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIM / NIS Siswa Magang</label>
                            <input type="text" name="student_id_number" value="{{ old('student_id_number', $studentIdNumber) }}" placeholder="Contoh: 2021014008" class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Asal Perguruan Tinggi / Sekolah</label>
                            <input type="text" name="institution_name" value="{{ old('institution_name', $institutionName) }}" placeholder="Contoh: Universitas Indonesia" class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Posisi / Peran Magang <span class="text-rose-500">*</span></label>
                            <input type="text" name="job_title" value="{{ old('job_title', $jobTitle) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-bold py-2.5">
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">Tgl Mulai <span class="text-rose-500">*</span></label>
                                <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d', strtotime('-3 months'))) }}" required class="w-full border-slate-300 rounded-xl text-xs font-medium py-2.5">
                            </div>
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">Tgl Selesai <span class="text-rose-500">*</span></label>
                                <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required class="w-full border-slate-300 rounded-xl text-xs font-medium py-2.5">
                            </div>
                        </div>

                        <!-- 5 EVALUATION CRITERIA SCORES (0 - 100) -->
                        <div class="sm:col-span-2 bg-indigo-50/60 p-5 rounded-3xl border border-indigo-200/80 space-y-4">
                            <h4 class="text-xs font-black uppercase text-indigo-900 flex items-center gap-1.5 border-b border-indigo-200/80 pb-2">
                                📊 Input Nilai 5 Kriteria Evaluasi Akademik (Skala 0 - 100)
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">1. Kedisiplinan & Presensi (0 - 100) <span class="text-rose-500">*</span></label>
                                    <input type="number" step="0.1" name="score_discipline" value="{{ old('score_discipline', 92.5) }}" min="0" max="100" required class="w-full border-slate-300 rounded-xl text-xs font-bold py-2">
                                </div>

                                <div>
                                    <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">2. Keahlian Teknis & Hasil Kerja (0 - 100) <span class="text-rose-500">*</span></label>
                                    <input type="number" step="0.1" name="score_technical" value="{{ old('score_technical', 95.0) }}" min="0" max="100" required class="w-full border-slate-300 rounded-xl text-xs font-bold py-2">
                                </div>

                                <div>
                                    <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">3. Komunikasi & Kerjasama Tim (0 - 100) <span class="text-rose-500">*</span></label>
                                    <input type="number" step="0.1" name="score_communication" value="{{ old('score_communication', 90.0) }}" min="0" max="100" required class="w-full border-slate-300 rounded-xl text-xs font-bold py-2">
                                </div>

                                <div>
                                    <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">4. Inisiatif & Problem Solving (0 - 100) <span class="text-rose-500">*</span></label>
                                    <input type="number" step="0.1" name="score_problem_solving" value="{{ old('score_problem_solving', 93.0) }}" min="0" max="100" required class="w-full border-slate-300 rounded-xl text-xs font-bold py-2">
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">5. Etika & Profesionalisme Kerja (0 - 100) <span class="text-rose-500">*</span></label>
                                    <input type="number" step="0.1" name="score_ethics" value="{{ old('score_ethics', 94.0) }}" min="0" max="100" required class="w-full border-slate-300 rounded-xl text-xs font-bold py-2">
                                </div>
                            </div>
                        </div>

                        <!-- MENTOR NOTES -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Evaluasi & Rekomendasi Mentor Pembimbing</label>
                            <textarea name="mentor_notes" rows="3" class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium p-3">{{ old('mentor_notes', 'Peserta magang sangat proaktif, disiplin tinggi, memiliki kemampuan teknis yang cepat beradaptasi dengan stack perusahaan, serta mampu berkomunikasi dengan sangat baik bersama tim.') }}</textarea>
                        </div>

                        <!-- MENTOR DETAILS -->
                        <div class="sm:col-span-2 bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                            <h4 class="text-xs font-black uppercase text-slate-800 flex items-center gap-1.5 border-b border-slate-200 pb-2">
                                👤 Informasi Mentor Pembimbing Magang
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">Nama Mentor Pembimbing <span class="text-rose-500">*</span></label>
                                    <input type="text" name="mentor_name" value="{{ old('mentor_name', 'Rizky Ramadhan, M.T') }}" required placeholder="Contoh: Rizky Ramadhan, M.T" class="w-full border-slate-300 rounded-xl text-xs font-bold py-2">
                                </div>
                                <div>
                                    <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">No. HP / WhatsApp Mentor</label>
                                    <input type="text" name="mentor_phone" value="{{ old('mentor_phone', '081234567890') }}" placeholder="Contoh: 081234567890" class="w-full border-slate-300 rounded-xl text-xs font-bold py-2">
                                </div>
                                <div>
                                    <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">Email Resmi Mentor</label>
                                    <input type="email" name="mentor_email" value="{{ old('mentor_email', 'mentor@company.com') }}" placeholder="Contoh: mentor@company.com" class="w-full border-slate-300 rounded-xl text-xs font-bold py-2">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">Nama HRD Manager</label>
                            <input type="text" name="hr_name" value="{{ old('hr_name', Auth::user()->name) }}" placeholder="HR Manager" class="w-full border-slate-300 rounded-xl text-xs font-bold py-2">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Penerbitan Transkrip <span class="text-rose-500">*</span></label>
                            <input type="date" name="issued_at" value="{{ old('issued_at', date('Y-m-d')) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.applications.show', $application) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-300 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs rounded-xl shadow-2xs transition border border-indigo-600 flex items-center gap-2">
                            <i class="fa-solid fa-square-poll-vertical text-xs"></i> Kalkulasi & Render Transkrip Nilai PDF
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
