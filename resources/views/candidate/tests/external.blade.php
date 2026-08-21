<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-up-right-from-square text-indigo-600"></i> Tes Psikotes & Asesmen Eksternal
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Lowongan: <strong>{{ $job->title }}</strong> ({{ $job->company_name }})</p>
            </div>
            <a href="{{ route('jobs.show', $job->id) }}" class="bg-white hover:bg-slate-100 text-slate-700 font-bold py-2 px-4 rounded-xl border border-slate-200 text-xs transition">
                &larr; Kembali ke Lowongan
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- External Test Banner Card -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200/80 space-y-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-2xl mx-auto flex items-center justify-center text-2xl shadow-md">
                    <i class="fa-solid fa-brain"></i>
                </div>

                <div class="space-y-2">
                    <h3 class="text-xl font-black text-slate-900">{{ $test->title }}</h3>
                    <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-700 font-black text-3xs rounded-full border border-indigo-200 uppercase">
                        🔗 Tes Psikotes Eksternal (Custom Link Provider)
                    </span>
                </div>

                @if($test->description)
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs text-slate-600 leading-relaxed text-left">
                        <span class="font-bold text-slate-800 block mb-1">📋 Petunjuk dari Perusahaan:</span>
                        {{ $test->description }}
                    </div>
                @endif

                @if($test->file_path)
                    <div class="p-5 bg-rose-50 rounded-2xl border border-rose-200 text-left space-y-3">
                        <div class="flex items-center gap-2 text-rose-900 font-extrabold text-xs">
                            <i class="fa-solid fa-file-pdf text-rose-600 text-base"></i>
                            <span>Dokumen Lampiran Soal / Brief Project PDF</span>
                        </div>
                        <p class="text-xs text-rose-700">Unduh dokumen berkas PDF berikut untuk mempelajari soal studi kasus / spesifikasi teknis lengkap yang diberikan oleh HR:</p>
                        <a href="{{ Storage::url($test->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-xl text-xs shadow-2xs transition">
                            <i class="fa-solid fa-download"></i> Unduh File Soal PDF
                        </a>
                    </div>
                @endif

                <div class="p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl border border-indigo-100 space-y-4">
                    <p class="text-xs text-indigo-900 font-bold">
                        Silakan klik tombol di bawah untuk membuka halaman Tes Psikotes / Asesmen pada link eksternal yang disediakan oleh tim HR:
                    </p>

                    <a href="{{ $test->external_url }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm rounded-2xl shadow-md transition transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka & Kerjakan Tes Psikotes Eksternal &rarr;
                    </a>
                    
                    <p class="text-3xs text-indigo-600">Link URL: <span class="font-mono underline">{{ $test->external_url }}</span></p>
                </div>

                <!-- Confirmation & Answer Submission Form -->
                <div class="pt-4 border-t border-slate-100 space-y-4">
                    <p class="text-xs text-slate-500 font-medium">
                        Unggah berkas hasil pengerjaan PDF atau sertakan tautan Repository GitHub / Google Drive project Anda:
                    </p>

                    <form action="{{ route('candidate.tests.submit-external', $job->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs text-left max-w-lg mx-auto bg-slate-50 p-5 rounded-2xl border border-slate-200">
                        @csrf
                        
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">🔗 Link Repository GitHub / Google Drive Project (Opsional)</label>
                            <input type="url" name="project_url" placeholder="https://github.com/username/project-repo atau https://drive.google.com/..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-indigo-500 focus:border-indigo-500 font-medium">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">📄 Upload File Berkas Jawaban PDF (Opsional, Maks: 15MB)</label>
                            <input type="file" name="answer_pdf" accept=".pdf" class="w-full text-xs text-slate-600 border border-slate-300 rounded-xl p-2 bg-white">
                        </div>

                        <button type="submit" onclick="return confirm('Kirim berkas jawaban & konfirmasi pengerjaan tes?');" class="w-full bg-slate-900 hover:bg-black text-white font-extrabold py-3 px-6 rounded-xl text-xs shadow-md transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane text-amber-400"></i> Kirim Berkas Jawaban & Konfirmasi Selesai
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
