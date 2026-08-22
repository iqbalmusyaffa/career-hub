<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-square-poll-vertical text-blue-600"></i> Hasil Tes Online Seleksi
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center space-y-6">
                @if($existingResult->passed)
                    <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-4xl mx-auto shadow-inner">
                        <i class="fa-solid fa-trophy animate-bounce"></i>
                    </div>
                    <div>
                        <span class="px-4 py-1.5 bg-emerald-100 text-emerald-800 text-sm font-black rounded-full border border-emerald-200 uppercase tracking-wide">
                            LULUS SELEKSI TES ONLINE
                        </span>
                        <h3 class="text-3xl font-black text-gray-900 mt-4">Selamat! Anda Lulus Tes</h3>
                        <p class="text-sm text-gray-600 mt-2">Skor Anda telah memenuhi kriteria batas kelulusan (Passing Grade: {{ $test->passing_score }}%) perusahaan.</p>
                    </div>
                @else
                    <div class="w-20 h-20 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-4xl mx-auto shadow-inner">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                    <div>
                        <span class="px-4 py-1.5 bg-amber-100 text-amber-800 text-sm font-black rounded-full border border-amber-200 uppercase tracking-wide">
                            BELUM MEMENUHI PASSING GRADE
                        </span>
                        <h3 class="text-2xl font-black text-gray-900 mt-4">Tes Selesai Dikerjakan</h3>
                        <p class="text-sm text-gray-600 mt-2">Skor Anda belum mencapai kriteria batas kelulusan (Passing Grade: {{ $test->passing_score }}%).</p>
                    </div>
                @endif

                <!-- Score Counter Box -->
                <div class="p-6 bg-gray-50 rounded-2xl border border-gray-200 grid grid-cols-2 gap-4 max-w-md mx-auto">
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase">Skor Akhir Anda</span>
                        <span class="text-4xl font-black {{ $existingResult->passed ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $existingResult->score }}%
                        </span>
                    </div>
                    <div class="border-l border-gray-200 pl-4">
                        <span class="block text-xs font-bold text-gray-500 uppercase">Batas Kelulusan</span>
                        <span class="text-4xl font-black text-gray-800">
                            {{ $test->passing_score }}%
                        </span>
                    </div>
                </div>

                <div class="text-xs text-gray-400 border-t border-gray-100 pt-4">
                    Diselesaikan pada: {{ $existingResult->completed_at ? $existingResult->completed_at->format('d M Y, H:i') : '-' }} WIB
                </div>

                <div class="pt-4">
                    <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition text-sm">
                        &larr; Kembali ke Halaman Lowongan
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
