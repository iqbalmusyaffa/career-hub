<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-blue-600"></i> Tes Online Seleksi Kandidat
                </h2>
                <p class="text-xs text-gray-500 mt-1">Lowongan: <span class="font-bold text-gray-800">{{ $job->title }}</span> ({{ $job->company_name }})</p>
            </div>
            <div id="timer-box" class="bg-red-50 border border-red-200 px-4 py-2 rounded-xl text-red-700 font-black text-base flex items-center gap-2 shadow-2xs">
                <i class="fa-solid fa-stopwatch animate-pulse"></i> Sisa Waktu: <span id="timer-display">--:--</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-6">
                <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $test->title }}</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $test->description }}</p>

                @if($test->file_path)
                    <div class="mt-4 p-4 bg-rose-50 rounded-2xl border border-rose-200 text-left space-y-2">
                        <div class="flex items-center gap-2 text-rose-900 font-extrabold text-xs">
                            <i class="fa-solid fa-file-pdf text-rose-600 text-base"></i>
                            <span>Dokumen Lampiran Soal / Brief Project PDF</span>
                        </div>
                        <p class="text-xs text-rose-700">Silakan unduh dokumen PDF berikut untuk mempelajari soal studi kasus / spesifikasi teknis lengkap yang diberikan oleh HR:</p>
                        <a href="{{ Storage::url($test->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-xl text-xs shadow-2xs transition">
                            <i class="fa-solid fa-download"></i> Unduh File Soal PDF
                        </a>
                    </div>
                @endif

                <div class="mt-4 flex items-center gap-4 text-xs font-semibold text-gray-500 border-t border-gray-100 pt-4">
                    <span>⏱️ Durasi: <strong>{{ $test->duration_minutes }} Menit</strong></span>
                    <span>🎯 Batas Lulus: <strong>{{ $test->passing_score }}%</strong></span>
                    <span>📝 Total Soal: <strong>{{ $test->questions->count() }} Pertanyaan</strong></span>
                </div>
            </div>

            <form id="test-form" method="POST" action="{{ route('candidate.tests.submit', $job->id) }}" class="space-y-6">
                @csrf

                @foreach($test->questions as $index => $q)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-sm shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <div class="font-bold text-gray-900 text-base leading-snug pt-1">
                                {{ $q->question_text }}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 pl-11">
                            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 hover:border-blue-500 hover:bg-blue-50/50 transition cursor-pointer">
                                <input type="radio" name="answers[{{ $q->id }}]" value="a" required class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="text-sm text-gray-800 font-medium">A. {{ $q->option_a }}</span>
                            </label>
                            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 hover:border-blue-500 hover:bg-blue-50/50 transition cursor-pointer">
                                <input type="radio" name="answers[{{ $q->id }}]" value="b" required class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="text-sm text-gray-800 font-medium">B. {{ $q->option_b }}</span>
                            </label>
                            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 hover:border-blue-500 hover:bg-blue-50/50 transition cursor-pointer">
                                <input type="radio" name="answers[{{ $q->id }}]" value="c" required class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="text-sm text-gray-800 font-medium">C. {{ $q->option_c }}</span>
                            </label>
                            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 hover:border-blue-500 hover:bg-blue-50/50 transition cursor-pointer">
                                <input type="radio" name="answers[{{ $q->id }}]" value="d" required class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="text-sm text-gray-800 font-medium">D. {{ $q->option_d }}</span>
                            </label>
                        </div>
                    </div>
                @endforeach

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                    <p class="text-xs text-gray-500">Pastikan seluruh soal telah dijawab sebelum mengirimkan tes.</p>
                    <button type="submit" onclick="return confirm('Yakin ingin menyelesaikan dan mengirimkan jawaban tes ini?');" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition text-sm flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Selesaikan & Kirim Tes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Countdown Timer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let totalSeconds = {{ $test->duration_minutes * 60 }};
            const display = document.getElementById('timer-display');
            const form = document.getElementById('test-form');

            const interval = setInterval(function () {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;

                display.textContent = 
                    (minutes < 10 ? '0' : '') + minutes + ':' + 
                    (seconds < 10 ? '0' : '') + seconds;

                if (totalSeconds <= 0) {
                    clearInterval(interval);
                    alert('Waktu pengerjaan tes telah habis! Tes Anda akan dikirim secara otomatis.');
                    form.submit();
                }

                totalSeconds--;
            }, 1000);
        });
    </script>
</x-app-layout>
