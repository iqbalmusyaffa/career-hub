<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-blue-600"></i> Tes Online Seleksi Kandidat
                </h2>
                <p class="text-xs text-slate-500 mt-1">Lowongan: <strong class="text-slate-800 dark:text-slate-200">{{ $job->title }}</strong> ({{ $job->company_name }})</p>
            </div>
            <a href="{{ route('candidate.applications.index') }}" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition border border-slate-200 dark:border-slate-700">
                &larr; Kembali ke Lamaran Saya
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950 min-h-screen flex items-center justify-center p-4">
        <div class="max-w-xl w-full mx-auto space-y-6">

            @if($status === 'upcoming')
                <!-- KONDISI 1: UJIAN BELUM DIBUKA -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-7 sm:p-9 shadow-xl border border-slate-200/80 dark:border-slate-800 text-center space-y-6">
                    <div class="w-20 h-20 rounded-3xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800 flex items-center justify-center mx-auto text-3xl shadow-xs animate-bounce">
                        <i class="fa-solid fa-lock"></i>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                            <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300 text-3xs uppercase tracking-wider font-extrabold rounded-full border border-amber-200 dark:border-amber-800">
                                Sesi Ujian Belum Dibuka
                            </span>
                            @if($test->session_name)
                                <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/60 text-indigo-800 dark:text-indigo-300 text-3xs font-extrabold rounded-full border border-indigo-200 dark:border-indigo-800">
                                    <i class="fa-solid fa-layer-group text-3xs mr-1"></i> {{ $test->session_name }}
                                </span>
                            @endif
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
                            {{ $test->title }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-md mx-auto">
                            Akses pengerjaan ujian online ini dijadwalkan khusus oleh perusahaan dan baru dapat dibuka pada waktu yang telah ditetapkan.
                        </p>
                    </div>

                    <!-- Countdown & Date Box -->
                    <div class="p-5 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-3">
                        <div class="text-3xs font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            Jadwal Pembukaan Ujian
                        </div>
                        <div class="text-base sm:text-lg font-black text-blue-600 dark:text-blue-400 flex items-center justify-center gap-2">
                            <i class="fa-regular fa-calendar-check"></i>
                            <span>{{ $test->starts_at->translatedFormat('l, d F Y') }} &bull; {{ $test->starts_at->format('H:i') }} WIB</span>
                        </div>
                        @if($test->deadline_at)
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 border-t border-slate-200 dark:border-slate-700 pt-2">
                                Batas Akhir / Deadline: <strong>{{ $test->deadline_at->translatedFormat('d M Y, H:i') }} WIB</strong>
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                        <a href="{{ route('candidate.applications.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali ke Dashboard Lamaran</span>
                        </a>
                    </div>
                </div>

            @else
                <!-- KONDISI 2: UJIAN SUDAH KEDALUWARSA / DEADLINE BERAKHIR -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-7 sm:p-9 shadow-xl border border-slate-200/80 dark:border-slate-800 text-center space-y-6">
                    <div class="w-20 h-20 rounded-3xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 flex items-center justify-center mx-auto text-3xl shadow-xs">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>

                    <div class="space-y-2">
                        <span class="px-3 py-1 bg-rose-100 dark:bg-rose-900/60 text-rose-800 dark:text-rose-300 text-3xs uppercase tracking-wider font-extrabold rounded-full border border-rose-200 dark:border-rose-800">
                            Akses Ujian Ditutup
                        </span>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
                            Masa Pengerjaan Telah Berakhir
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-md mx-auto">
                            Batas waktu pengerjaan untuk <strong>{{ $test->title }}</strong> telah melewati tenggat waktu (deadline) yang ditentukan.
                        </p>
                    </div>

                    <div class="p-5 bg-rose-50/50 dark:bg-rose-950/30 rounded-2xl border border-rose-200 dark:border-rose-900 space-y-1 text-xs">
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-rose-600 dark:text-rose-400 block">Tenggat Waktu Selesai Pada</span>
                        <div class="text-base font-black text-rose-700 dark:text-rose-300">
                            {{ $test->deadline_at ? $test->deadline_at->translatedFormat('l, d F Y, H:i') . ' WIB' : 'Waktu telah berakhir' }}
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">Jika Anda mengalami kendala teknis tak terduga, silakan hubungi tim HR perusahaan terkait.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                        <a href="{{ route('candidate.applications.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali ke Lamaran Saya</span>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
