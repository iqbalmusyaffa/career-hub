<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.applications.show', $application) }}" class="w-10 h-10 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center justify-center transition shadow-2xs">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                    Terbitkan Sertifikat Magang Resmi (PDF Certificate Builder)
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Peserta Magang: <strong>{{ $participantName }}</strong> • Posisi: <strong>{{ $jobTitle }}</strong></p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 dark:bg-slate-900 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 dark:border-slate-700 space-y-6">
                <div class="border-b border-slate-100 dark:border-slate-700 pb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Formulir Penerbitan Sertifikat Kelulusan Magang</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Sertifikat akan di-render dalam format PDF Landscape A4 resmi dengan QR Code verifikasi.</p>
                    </div>
                    <span class="px-3 py-1 bg-amber-100 dark:bg-amber-950 text-amber-900 dark:text-amber-300 text-3xs font-black rounded-xl uppercase border border-amber-200 dark:border-amber-800">
                        🎓 Sertifikat Magang Resmi
                    </span>
                </div>

                <form action="{{ route('admin.applications.certificates.store', $application) }}" method="POST" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs">
                            <div class="font-bold flex items-center gap-2 mb-1">
                                <i class="fa-solid fa-circle-exclamation text-rose-500"></i> Terjadi kesalahan pengisian formulir:
                            </div>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase">Nomor Sertifikat <span class="text-rose-500">*</span></label>
                                <span class="text-3xs text-slate-400 dark:text-slate-500 font-medium flex items-center gap-1"><i class="fa-solid fa-lock text-3xs"></i> Otomatis & Terkunci</span>
                            </div>
                            <div class="relative">
                                <input type="text" name="certificate_number" value="{{ old('certificate_number', $certNumber) }}" readonly required class="w-full border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold py-2.5 pl-3 pr-9 cursor-not-allowed select-none focus:ring-0 focus:border-slate-300 dark:focus:border-slate-600">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Nama Lengkap Peserta Magang <span class="text-rose-500">*</span></label>
                            <input type="text" name="participant_name" value="{{ old('participant_name', $participantName) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-amber-500 focus:border-amber-500 font-bold py-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Asal Perguruan Tinggi / Sekolah</label>
                            <input type="text" name="institution_name" value="{{ old('institution_name', $institutionName) }}" placeholder="Contoh: Universitas Indonesia" class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-amber-500 focus:border-amber-500 font-medium py-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Posisi / Peran Magang <span class="text-rose-500">*</span></label>
                            <input type="text" name="job_title" value="{{ old('job_title', $jobTitle) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-amber-500 focus:border-amber-500 font-bold py-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Tanggal Mulai Magang <span class="text-rose-500">*</span></label>
                            <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d', strtotime('-3 months'))) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-amber-500 focus:border-amber-500 font-medium py-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Tanggal Selesai Magang <span class="text-rose-500">*</span></label>
                            <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-amber-500 focus:border-amber-500 font-medium py-2.5">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Predikat Evaluasi Kinerja <span class="text-rose-500">*</span></label>
                            <select name="performance_grade" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-amber-500 focus:border-amber-500 font-bold py-2.5">
                                <option value="Sangat Baik (A / 90-100)">Sangat Baik (A / 90-100) - Highly Recommended</option>
                                <option value="Baik (B / 80-89)">Baik (B / 80-89) - Recommended</option>
                                <option value="Cukup (C / 70-79)">Cukup (C / 70-79) - Satisfactory</option>
                            </select>
                        </div>

                        <!-- MENTOR DETAILS CARD -->
                        <div class="sm:col-span-2 bg-slate-50 dark:bg-slate-900/60 p-5 rounded-3xl border border-slate-200 dark:border-slate-700 space-y-3 shadow-2xs">
                            <h4 class="text-xs font-black uppercase text-slate-800 dark:text-white flex items-center gap-1.5 border-b border-slate-200 dark:border-slate-800 pb-2.5">
                                👤 Informasi Mentor Pembimbing Magang
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-3xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Nama Mentor Pembimbing <span class="text-rose-500">*</span></label>
                                    <input type="text" name="mentor_name" value="{{ old('mentor_name', $mentorName) }}" required placeholder="Contoh: Rizky Ramadhan, M.T" class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2.5">
                                </div>
                                <div>
                                    <label class="block text-3xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">No. HP / WA Mentor</label>
                                    <input type="text" name="mentor_phone" value="{{ old('mentor_phone', $mentorPhone) }}" placeholder="Contoh: 081234567890" class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2.5">
                                </div>
                                <div>
                                    <label class="block text-3xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Email Resmi Mentor</label>
                                    <input type="email" name="mentor_email" value="{{ old('mentor_email', $mentorEmail) }}" placeholder="Contoh: mentor@company.com" class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2.5">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-3xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Nama HRD Manager</label>
                            <input type="text" name="hr_name" value="{{ old('hr_name', Auth::user()->name) }}" placeholder="HR Manager" class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2">
                        </div>

                        <div>
                            <label class="block text-3xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Nama Owner / Direktur Perusahaan</label>
                            <input type="text" name="owner_name" value="{{ old('owner_name', 'Ir. Budi Santoso') }}" placeholder="Direktur Utama" class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Tanggal Penerbitan Sertifikat <span class="text-rose-500">*</span></label>
                            <input type="date" name="issued_at" value="{{ old('issued_at', date('Y-m-d')) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-amber-500 focus:border-amber-500 font-medium py-2.5">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.applications.show', $application) }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-xl border border-slate-300 dark:border-slate-600 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-black text-xs rounded-xl shadow-2xs transition border border-amber-600 flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-xs"></i> Terbitkan & Render Sertifikat PDF
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
