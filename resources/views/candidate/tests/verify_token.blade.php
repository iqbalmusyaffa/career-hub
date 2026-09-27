<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-blue-600"></i> Verifikasi Token Akses Ujian
                </h2>
                <p class="text-xs text-slate-500 mt-1">Lowongan: <strong class="text-slate-800 dark:text-slate-200">{{ $job->title }}</strong> ({{ $job->company_name }})</p>
            </div>
            <a href="{{ route('candidate.applications.index') }}" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition border border-slate-200 dark:border-slate-700">
                &larr; Kembali ke Lamaran Saya
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12 bg-slate-50/70 dark:bg-slate-950 min-h-screen flex items-center justify-center p-4">
        <div class="max-w-2xl w-full mx-auto space-y-6">

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-2xl flex items-center gap-3 text-xs font-semibold shadow-xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center gap-3 text-xs font-semibold shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- 1. DETAIL ASESMEN & PETUNJUK RESMI -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-7 shadow-xs border border-slate-200/80 dark:border-slate-800 space-y-4">
                <div class="flex items-start justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                    <div>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-900 text-3xs font-extrabold uppercase tracking-wider rounded-md">
                                {{ ucfirst($test->category ?? 'Asesmen Seleksi') }}
                            </span>
                            @if($test->session_name)
                                <span class="px-2.5 py-0.5 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-900 text-3xs font-bold rounded-md">
                                    <i class="fa-solid fa-layer-group text-3xs mr-1"></i> {{ $test->session_name }}
                                </span>
                            @endif
                        </div>
                        <h3 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white mt-1.5 leading-snug">
                            {{ $test->title }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Perusahaan: <strong class="text-slate-800 dark:text-slate-200">{{ $job->company_name ?: 'Perusahaan Mitra' }}</strong> &bull; Posisi: <span class="text-blue-600 dark:text-blue-400 font-semibold">{{ $job->title }}</span>
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-file-pen"></i>
                    </div>
                </div>

                <!-- 4 Metrics Bento -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700 text-center space-y-0.5">
                        <span class="text-3xs uppercase font-extrabold text-slate-400 dark:text-slate-500 block">Durasi</span>
                        <span class="font-black text-sm text-slate-900 dark:text-white">{{ $test->duration_minutes }} Menit</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700 text-center space-y-0.5">
                        <span class="text-3xs uppercase font-extrabold text-slate-400 dark:text-slate-500 block">Passing Grade</span>
                        <span class="font-black text-sm text-emerald-600 dark:text-emerald-400">{{ $test->passing_score }}%</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700 text-center space-y-0.5">
                        <span class="text-3xs uppercase font-extrabold text-slate-400 dark:text-slate-500 block">Total Soal</span>
                        <span class="font-black text-sm text-blue-600 dark:text-blue-400">{{ $test->questions ? $test->questions->count() : '-' }} Butir</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700 text-center space-y-0.5">
                        <span class="text-3xs uppercase font-extrabold text-slate-400 dark:text-slate-500 block">Metode</span>
                        <span class="font-black text-sm text-slate-900 dark:text-white">{{ $test->test_mode === 'external' ? 'Eksternal' : 'Pilihan Ganda' }}</span>
                    </div>
                </div>

                @if($test->starts_at || $test->deadline_at)
                    <div class="p-3.5 bg-blue-50/50 dark:bg-blue-950/30 rounded-2xl border border-blue-200 dark:border-blue-900 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-blue-900 dark:text-blue-200">
                        @if($test->starts_at)
                            <span class="flex items-center gap-1.5"><i class="fa-regular fa-calendar-check text-blue-600"></i> Dimulai: <strong>{{ $test->starts_at->translatedFormat('d M Y, H:i') }} WIB</strong></span>
                        @endif
                        @if($test->deadline_at)
                            <span class="flex items-center gap-1.5 text-rose-600 dark:text-rose-400"><i class="fa-solid fa-hourglass-end"></i> Deadline: <strong>{{ $test->deadline_at->translatedFormat('d M Y, H:i') }} WIB</strong></span>
                        @endif
                    </div>
                @endif

                @if($test->description)
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200/80 dark:border-slate-700 text-xs space-y-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200 text-3xs uppercase tracking-wider block">Petunjuk Pengerjaan:</span>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ $test->description }}</p>
                    </div>
                @endif
            </div>

            <!-- 2. FORM INPUT TOKEN AKSES -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200/80 dark:border-slate-800 text-center space-y-5">
                <div class="space-y-1.5">
                    <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-950/80 text-indigo-800 dark:text-indigo-300 text-3xs uppercase tracking-wider font-extrabold rounded-full border border-indigo-200 dark:border-indigo-900">
                        🔑 Kunci Masuk Ujian
                    </span>
                    <h4 class="text-lg font-black text-slate-900 dark:text-white">
                        Masukkan Token Akses Anda
                    </h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                        Kode token ujian acak telah dikirimkan ke email Anda (<strong>{{ auth()->user()->email }}</strong>) dan notifikasi akun Anda.
                    </p>
                </div>

                <form method="POST" action="{{ route('candidate.tests.verify-token', $job->id) }}" class="space-y-4 max-w-sm mx-auto">
                    @csrf
                    <div>
                        <input type="text" name="token" required placeholder="Contoh: TK-XXXXXX" maxlength="20" autofocus 
                               class="w-full text-center text-xl font-mono font-black uppercase tracking-widest py-3 px-4 rounded-2xl border-2 border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 shadow-inner">
                        <span class="text-3xs text-slate-400 dark:text-slate-500 mt-1.5 block">Ketik token acak unik dari email/notifikasi Anda.</span>
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-xs transition flex items-center justify-center gap-2 border border-blue-600 cursor-pointer">
                        <i class="fa-solid fa-unlock text-xs"></i>
                        <span>Verifikasi Token & Mulai Tes</span>
                    </button>
                </form>

                <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700 text-[11px] text-slate-500 dark:text-slate-400">
                    💡 Periksa folder <em>Inbox / Spam</em> email Anda atau lonceng notifikasi untuk melihat token.
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
