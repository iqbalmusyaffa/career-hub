<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    📄 {{ $termination->document_type === 'recommendation_letter' ? 'Surat Rekomendasi Kerja' : ($termination->document_type === 'paklaring_letter' ? 'Surat Paklaring' : ($termination->document_type === 'phk_letter' ? 'Surat Pemutusan Hubungan Kerja (PHK)' : 'Surat Selesai Kontrak')) }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Nomor Dokumen: <strong>{{ $termination->document_number }}</strong></p>
            </div>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                ← Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- ALERT NOTIFICATION -->
            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- DOCUMENT DETAILS CARD -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6">
                
                <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 leading-tight">
                            @if($termination->document_type === 'recommendation_letter')
                                Surat Rekomendasi Kerja & Referensi Karir Resmi
                            @elseif($termination->document_type === 'paklaring_letter')
                                Surat Keterangan Pengalaman Kerja (Paklaring)
                            @elseif($termination->document_type === 'phk_letter')
                                Surat Pemutusan Hubungan Kerja (PHK)
                            @else
                                Surat Keterangan Selesai Masa Kontrak Kerja
                            @endif
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Perusahaan Penerbit: <strong>{{ $termination->application->job->company_name ?? 'PT TalentFlow Indonesia' }}</strong></p>
                    </div>

                    <a href="{{ route('candidate.terminations.show', $termination) }}" target="_blank" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-2xs transition flex items-center gap-2 border border-slate-900">
                        <i class="fa-solid fa-file-pdf"></i> Lihat / Pratinjau PDF Resmi
                    </a>
                </div>

                <!-- METADATA GRID -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-xs">
                    <div>
                        <span class="text-slate-500 font-medium block">Nama Karyawan:</span>
                        <span class="font-extrabold text-slate-900">{{ $termination->employee_name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 font-medium block">Posisi / Jabatan:</span>
                        <span class="font-extrabold text-slate-900">{{ $termination->job_title }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 font-medium block">Masa Kerja Perusahaan:</span>
                        <span class="font-extrabold text-slate-900">
                            {{ $termination->start_date ? $termination->start_date->format('d F Y') : '-' }} s/d 
                            {{ $termination->end_date ? $termination->end_date->format('d F Y') : '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-500 font-medium block">Status Otentikasi TTD:</span>
                        @if($termination->status === 'signed')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-3xs font-black rounded-lg">
                                ✅ Terverifikasi TTD & OTP
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-100 text-amber-800 text-3xs font-black rounded-lg">
                                ⏳ Menunggu Tanda Tangan Anda
                            </span>
                        @endif
                    </div>
                </div>

                @if($termination->severance_compensation)
                    <div class="bg-amber-50 border border-amber-200 text-amber-900 p-4 rounded-2xl text-xs font-bold">
                        💰 Rincian Uang Pesangon / Kompensasi: <strong>{{ $termination->severance_compensation }}</strong>
                    </div>
                @endif

                <!-- STATEMENT & RECOMMENDATION CLAUSES -->
                <div class="space-y-3">
                    <h4 class="font-black text-xs uppercase text-slate-900 tracking-wider">Pernyataan Resmi & Ulasan Perusahaan:</h4>
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 text-xs font-mono text-slate-800 whitespace-pre-wrap leading-relaxed">
{{ $termination->reason_or_recommendation_notes }}
                    </div>
                </div>

                <!-- SIGNATURE FORM SECTION FOR CANDIDATE -->
                @if($termination->status !== 'signed')
                    <div class="pt-6 border-t border-slate-200 space-y-6">
                        <div class="bg-amber-50/80 border border-amber-200/90 rounded-2xl p-4 flex items-start gap-3">
                            <i class="fa-solid fa-pen-nib text-amber-600 text-lg shrink-0 mt-0.5"></i>
                            <div class="text-xs text-amber-900 leading-relaxed">
                                <strong class="block font-bold mb-0.5">Instruksi Penandatanganan Digital & OTP Email:</strong>
                                Silakan buat tanda tangan Anda pada pad di bawah ini, centang persetujuan, lalu klik <strong>"Kirim Kode OTP Email"</strong> untuk menerima 6-digit kode verifikasi pengesahan dokumen.
                            </div>
                        </div>

                        <!-- SEND OTP BUTTON FORM -->
                        <div class="flex items-center justify-between bg-slate-900 text-white p-4 rounded-2xl shadow-sm">
                            <div class="text-xs">
                                <span class="font-bold block">Verifikasi Kode OTP Email</span>
                                <span class="text-slate-300">Kode OTP 6-digit akan dikirim ke email: <strong>{{ auth()->user()->email }}</strong></span>
                            </div>
                            <form action="{{ route('candidate.terminations.send-otp', $termination) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs rounded-xl shadow-sm transition border border-amber-300 flex items-center gap-1.5">
                                    <i class="fa-solid fa-paper-plane text-xs"></i> Kirim Kode OTP Baru
                                </button>
                            </form>
                        </div>

                        <!-- DIGITAL SIGNATURE SUBMIT FORM -->
                        <form action="{{ route('candidate.terminations.sign', $termination) }}" method="POST" id="signatureForm" class="space-y-6">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap Penanda Tangan <span class="text-rose-500">*</span></label>
                                <input type="text" name="signer_name" value="{{ old('signer_name', auth()->user()->name) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-bold py-2.5">
                            </div>

                            <!-- HTML5 CANVAS SIGNATURE PAD -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase">Tanda Tangan Digital (Canvas Pad) <span class="text-rose-500">*</span></label>
                                    <button type="button" id="clearCanvasBtn" class="text-3xs font-bold text-rose-600 hover:text-rose-700 uppercase underline">
                                        <i class="fa-solid fa-eraser"></i> Hapus Tanda Tangan
                                    </button>
                                </div>
                                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-2 bg-slate-50 flex items-center justify-center">
                                    <canvas id="signatureCanvas" width="600" height="180" class="w-full bg-white rounded-xl cursor-crosshair border border-slate-200 touch-none"></canvas>
                                </div>
                                <input type="hidden" name="signature_data" id="signatureDataInput" required>
                                <p class="text-3xs text-slate-400 mt-1">* Buat tanda tangan Anda di dalam kotak menggunakan mouse atau layar sentuh HP.</p>
                            </div>

                            <!-- OTP CODE INPUT -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Masukkan Kode OTP Email (6-Digit) <span class="text-rose-500">*</span></label>
                                <input type="text" name="otp_code" maxlength="6" placeholder="Contoh: 123456" required class="w-full sm:w-1/2 border-slate-300 rounded-xl text-sm focus:ring-slate-900 focus:border-slate-900 font-mono font-black tracking-widest text-center py-2.5">
                            </div>

                            <!-- CHECKBOX AGREEMENT -->
                            <div class="flex items-start gap-2 pt-2">
                                <input type="checkbox" name="agree_checkbox" value="accepted" id="agree_checkbox" required class="rounded border-slate-300 text-slate-900 focus:ring-slate-900 mt-0.5">
                                <label for="agree_checkbox" class="text-xs text-slate-700 leading-relaxed font-semibold">
                                    Saya dengan ini mengesahkan secara sah digital dokumen ini beserta seluruh isi ulasan rekomendasi / hak kompensasi kerja.
                                </label>
                            </div>

                            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                                <button type="submit" id="submitSigBtn" class="w-full sm:w-auto px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-xl shadow-md transition border border-emerald-600 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-signature text-sm"></i> Sahkan & Tanda Tangani Dokumen Digital
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <!-- SIGNED CONFIRMATION BOX -->
                    <div class="pt-6 border-t border-slate-200 bg-emerald-50/60 p-6 rounded-3xl border border-emerald-200/80 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-lg font-bold shrink-0 shadow-sm">
                                ✓
                            </div>
                            <div>
                                <h4 class="font-extrabold text-sm text-emerald-950">Dokumen Telah Sah & Ditandatangani Digital!</h4>
                                <p class="text-xs text-emerald-800">Ditandatangani oleh: <strong>{{ $termination->signer_name }}</strong> • Tgl: {{ $termination->signed_at ? $termination->signed_at->format('d F Y, H:i') : '-' }} WIB</p>
                            </div>
                        </div>

                        <a href="{{ route('candidate.terminations.show', $termination) }}" target="_blank" class="shrink-0 px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-2xs transition flex items-center gap-2 border border-rose-600">
                            <i class="fa-solid fa-file-pdf"></i> Pratinjau & Cetak PDF Resmi
                        </a>
                    </div>
                @endif

            </div>

        </div>
    </div>

    <!-- CANVAS SIGNATURE PAD SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('signatureCanvas');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            let isDrawing = false;
            let hasDrawn = false;

            ctx.strokeStyle = '#0f172a';
            ctx.lineWidth = 2.5;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';

            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                return {
                    x: (clientX - rect.left) * (canvas.width / rect.width),
                    y: (clientY - rect.top) * (canvas.height / rect.height)
                };
            }

            function startDrawing(e) {
                isDrawing = true;
                const pos = getPos(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
                e.preventDefault();
            }

            function draw(e) {
                if (!isDrawing) return;
                hasDrawn = true;
                const pos = getPos(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
                e.preventDefault();
            }

            function stopDrawing(e) {
                if (isDrawing) {
                    isDrawing = false;
                    document.getElementById('signatureDataInput').value = canvas.toDataURL('image/png');
                }
            }

            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseleave', stopDrawing);

            canvas.addEventListener('touchstart', startDrawing, { passive: false });
            canvas.addEventListener('touchmove', draw, { passive: false });
            canvas.addEventListener('touchend', stopDrawing);

            document.getElementById('clearCanvasBtn').addEventListener('click', function () {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                document.getElementById('signatureDataInput').value = '';
                hasDrawn = false;
            });

            document.getElementById('signatureForm').addEventListener('submit', function (e) {
                if (!hasDrawn && !document.getElementById('signatureDataInput').value) {
                    e.preventDefault();
                    alert('Mohon buat tanda tangan Anda terlebih dahulu di dalam kotak Canvas Pad!');
                }
            });
        });
    </script>
</x-app-layout>
