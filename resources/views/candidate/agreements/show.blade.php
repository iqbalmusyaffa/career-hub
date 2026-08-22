<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white hover:bg-slate-100 text-slate-600 rounded-2xl border border-slate-200 flex items-center justify-center transition shadow-2xs">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    Dokumen Perjanjian Digital (E-Signature)
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">No. Dokumen: <strong>{{ $agreement->contract_number }}</strong> • Status: 
                    @if($agreement->status === 'signed')
                        <span class="text-emerald-600 font-bold">✓ Ditandatangani</span>
                    @else
                        <span class="text-amber-600 font-bold">⏳ Menunggu Tanda Tangan Anda</span>
                    @endif
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- CONTRACT DETAILS & CLAUSES CARD -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6">
                
                <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 leading-tight">{{ $agreement->title }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Diterbitkan oleh: <strong>{{ $agreement->application->job->company_name ?? 'Perusahaan' }}</strong></p>
                    </div>
                    @if($agreement->status === 'signed')
                        <a href="{{ route('agreements.download', $agreement) }}" target="_blank" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-2xs transition flex items-center gap-2 border border-rose-600">
                            <i class="fa-solid fa-file-pdf"></i> Pratinjau & Cetak PDF Resmi
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                    <div>
                        <span class="text-slate-500 block font-semibold mb-0.5">Nama Pihak Pertama (Kandidat):</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $agreement->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block font-semibold mb-0.5">Posisi Pekerjaan / Magang:</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $agreement->application->job->title }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block font-semibold mb-0.5">Masa Berlaku Perjanjian:</span>
                        <span class="font-bold text-slate-900">{{ $agreement->start_date ? $agreement->start_date->format('d M Y') : '-' }} s/d {{ $agreement->end_date ? $agreement->end_date->format('d M Y') : 'Selesai' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block font-semibold mb-0.5">Gaji / Insentif Stipend Magang:</span>
                        <span class="font-bold text-emerald-700 text-sm">{{ $agreement->stipend_or_salary }}</span>
                    </div>
                </div>

                <!-- CLAUSES TEXT BOX -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase">Pasal-Pasal & Ketentuan Perjanjian Hukum:</label>
                    <div class="bg-slate-900 text-slate-100 p-5 rounded-2xl font-mono text-xs leading-relaxed whitespace-pre-wrap max-h-96 overflow-y-auto border border-slate-800 shadow-inner">
{{ $agreement->terms_content }}
                    </div>
                </div>

                <!-- SIGNATURE FORM SECTION -->
                @if($agreement->status !== 'signed')
                    <div class="pt-6 border-t border-slate-200 space-y-6">
                        <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl flex items-start gap-3 text-xs text-amber-900 font-medium">
                            <i class="fa-solid fa-pen-nib text-amber-600 text-lg shrink-0 mt-0.5"></i>
                            <div>
                                <span class="font-bold block text-sm mb-0.5">Prosedur Tanda Tangan Digital (E-Signature)</span>
                                <span>Gunakan pad di bawah ini untuk membuat coretan tanda tangan Anda. Tanda tangan ini secara sah mewakili persetujuan Anda atas perjanjian kerja/magang ini.</span>
                            </div>
                        </div>

                        <form action="{{ route('candidate.agreements.sign', $agreement) }}" method="POST" onsubmit="prepareSignatureSubmit(event)" class="space-y-6">
                            @csrf
                            <input type="hidden" name="signature_data" id="signatureDataInput">

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap Penanda Tangan <span class="text-rose-500">*</span></label>
                                <input type="text" name="signer_name" value="{{ old('signer_name', $agreement->user->name) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-bold py-2.5">
                            </div>

                            <!-- HTML5 Canvas Signature Pad -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase">Pad Tanda Tangan Digital (Gambar / Coret di Kotak) <span class="text-rose-500">*</span></label>
                                    <button type="button" onclick="clearCanvas()" class="text-3xs text-rose-600 font-bold hover:underline">✕ Bersihkan Pad</button>
                                </div>
                                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-2 bg-slate-50 flex items-center justify-center">
                                    <canvas id="signatureCanvas" width="600" height="180" class="bg-white border border-slate-200 rounded-xl w-full cursor-crosshair touch-none shadow-2xs"></canvas>
                                </div>
                                <span class="text-3xs text-slate-400 mt-1 block">Gunakan mouse atau layar sentuh HP Anda untuk mencoretkan tanda tangan di atas kotak putih.</span>
                            </div>

                            <!-- OTP Verification Section -->
                            <div class="bg-amber-50/80 border border-amber-200 p-5 rounded-2xl space-y-3">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-amber-900 uppercase">Kode OTP Email Verifikasi Keamanan (6 Digit) <span class="text-rose-500">*</span></label>
                                        <p class="text-3xs text-amber-700 font-medium">Klik tombol untuk mengirimkan kode OTP 6-digit ke Email terdaftar Anda (<strong>{{ $agreement->user->email }}</strong>).</p>
                                    </div>
                                    <button type="button" onclick="requestOtpCode()" id="requestOtpBtn" class="shrink-0 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-2xs transition flex items-center gap-1.5 border border-amber-600">
                                        <i class="fa-solid fa-envelope text-3xs"></i> Kirim Kode OTP Ke Email
                                    </button>
                                </div>

                                <div id="otpNoticeBox" class="hidden p-3 bg-white border border-amber-300 text-amber-900 rounded-xl text-xs font-bold flex items-center justify-between">
                                    <span>🔐 <span id="otpNoticeMsg">OTP Terkirim!</span></span>
                                    <span class="bg-amber-100 text-amber-900 px-2.5 py-1 rounded-lg text-xs font-mono font-black border border-amber-300" id="demoOtpBadge">OTP: ------</span>
                                </div>

                                <input type="text font-mono font-bold text-center tracking-widest text-base" name="otp_code" id="otpCodeInput" maxlength="6" required placeholder="Masukkan 6 Digit OTP (Contoh: 123456)" class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-mono py-2.5 px-3 uppercase">
                            </div>

                            <!-- Agreement Checkbox -->
                            <div class="flex items-start gap-2.5 p-3.5 bg-slate-100 rounded-2xl border border-slate-200">
                                <input type="checkbox" name="agree_checkbox" value="1" required id="agreeCheck" class="mt-0.5 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                                <label for="agreeCheck" class="text-xs font-bold text-slate-800 leading-snug cursor-pointer">
                                    Saya telah membaca, memahami, dan menyetujui seluruh pasal & ketentuan dalam Surat Perjanjian Digital ini secara sadar tanpa paksaan dari pihak manapun.
                                </label>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl shadow-lg transition border border-slate-900 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-pen-nib text-amber-400 text-sm"></i> Tanda Tangan & Sahkan Perjanjian Digital Now
                            </button>
                        </form>
                    </div>
                @else
                    <!-- SIGNED DISPLAY STATE -->
                    <div class="pt-6 border-t border-slate-200 space-y-4">
                        <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl shrink-0 font-bold shadow-sm">
                                    ✓
                                </div>
                                <div>
                                    <span class="font-extrabold text-emerald-900 text-sm block">Dokumen Ini Telah Ditandatangani Secara Sah!</span>
                                    <span class="text-xs text-emerald-700">Ditandatangani oleh <strong>{{ $agreement->signer_name }}</strong> pada {{ $agreement->signed_at ? $agreement->signed_at->format('d M Y, H:i') : '-' }} WIB</span>
                                </div>
                            </div>
                            <a href="{{ route('agreements.download', $agreement) }}" class="shrink-0 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-xl transition shadow-2xs">
                                📥 Unduh File PDF
                            </a>
                        </div>

                        @if($agreement->signature_data)
                            <div class="text-center pt-2">
                                <span class="text-3xs font-bold uppercase text-slate-400 block mb-1">Pratinjau Tanda Tangan Digital Kandidat:</span>
                                <img src="{{ $agreement->signature_data }}" class="max-h-24 mx-auto border border-slate-200 rounded-xl p-2 bg-white shadow-2xs">
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- HTML5 Canvas Signature Pad Script -->
    <script>
        const canvas = document.getElementById('signatureCanvas');
        let ctx = null;
        let isDrawing = false;
        let hasDrawn = false;

        if (canvas) {
            ctx = canvas.getContext('2d');
            ctx.lineWidth = 2.5;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#0f172a';

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
                hasDrawn = true;
                const pos = getPos(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
            }

            function draw(e) {
                if (!isDrawing) return;
                e.preventDefault();
                const pos = getPos(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
            }

            function stopDrawing() {
                isDrawing = false;
            }

            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseleave', stopDrawing);

            canvas.addEventListener('touchstart', startDrawing, {passive: false});
            canvas.addEventListener('touchmove', draw, {passive: false});
            canvas.addEventListener('touchend', stopDrawing);
        }

        function clearCanvas() {
            if (ctx && canvas) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                hasDrawn = false;
            }
        }

        function prepareSignatureSubmit(e) {
            if (!hasDrawn) {
                e.preventDefault();
                alert('Silakan coretkan tanda tangan Anda terlebih dahulu pada kotak pad yang disediakan.');
                return false;
            }

            const otpVal = document.getElementById('otpCodeInput').value.trim();
            if (!otpVal || otpVal.length < 6) {
                e.preventDefault();
                alert('Silakan minta dan masukkan 6-digit Kode OTP Verifikasi terlebih dahulu.');
                return false;
            }

            const dataURL = canvas.toDataURL('image/png');
            document.getElementById('signatureDataInput').value = dataURL;
        }

        function requestOtpCode() {
            const btn = document.getElementById('requestOtpBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Mengirim...';

            fetch('{{ route("candidate.agreements.send-otp", $agreement) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-rotate-right text-xs"></i> Kirim Ulang OTP';
                if (data.success) {
                    document.getElementById('otpNoticeBox').classList.remove('hidden');
                    document.getElementById('otpNoticeMsg').innerText = data.message;
                    if (data.otp_code) {
                        document.getElementById('demoOtpBadge').innerText = 'DEMO OTP: ' + data.otp_code;
                        document.getElementById('otpCodeInput').value = data.otp_code;
                    }
                } else {
                    alert(data.message || 'Gagal mengirim OTP');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane text-xs"></i> Kirim Kode OTP';
                console.error(err);
            });
        }
    </script>
</x-app-layout>
