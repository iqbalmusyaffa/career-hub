<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Keaslian Sertifikat Magang - TalentFlow</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between selection:bg-blue-600 selection:text-white">

    <!-- Header Navigation -->
    <header class="border-b border-slate-800 bg-slate-900/60 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-xl shadow-md shadow-blue-500/30">
                    ⚡
                </div>
                <div>
                    <span class="font-black text-base text-white tracking-tight block">TalentFlow</span>
                    <span class="text-3xs text-slate-400 font-bold uppercase tracking-widest">Portal Verifikasi Publik</span>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <a href="/login" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition border border-slate-700">
                    Masuk Portal
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-12 px-4 sm:px-6 lg:px-8 flex-1 flex flex-col justify-center">
        <div class="max-w-3xl mx-auto w-full space-y-8">
            
            <div class="text-center space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 text-xs font-extrabold">
                    <i class="fa-solid fa-shield-halved"></i>
                    Official Credential Verification Service
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    Verifikasi Keaslian Sertifikat Magang
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 max-w-lg mx-auto">
                    Masukkan nomor seri sertifikat atau scan QR Code resmi untuk memeriksa keaslian data kelulusan peserta magang.
                </p>
            </div>

            <!-- Search Form Bar -->
            <form action="{{ route('certificates.verify.public') }}" method="GET" class="flex flex-col sm:flex-row gap-2 max-w-xl mx-auto">
                <div class="relative flex-1">
                    <i class="fa-solid fa-barcode absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="code" value="{{ $searchCode }}" placeholder="Contoh: CERT-2026-TF-00123" required class="w-full bg-slate-900 border border-slate-700 rounded-2xl pl-11 pr-4 py-3.5 text-xs text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono font-bold uppercase tracking-wider">
                </div>
                <button type="submit" class="px-7 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-black transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i> Verifikasi Sekarang
                </button>
            </form>

            <!-- Verification Result Box -->
            @if($searched)
                @if($certificate)
                    @if($certificate->is_revoked)
                        <!-- REVOKED CERTIFICATE BANNER -->
                        <div class="bg-gradient-to-br from-red-950/80 to-slate-900 border-2 border-red-500 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl animate-shake">
                            <div class="flex items-center gap-4 border-b border-red-800 pb-5">
                                <div class="w-14 h-14 rounded-2xl bg-red-600 text-white flex items-center justify-center text-2xl font-black shrink-0 shadow-lg shadow-red-600/30">
                                    ❌
                                </div>
                                <div>
                                    <span class="px-2.5 py-0.5 rounded-full text-3xs font-black uppercase tracking-wider bg-red-500 text-white">
                                        SERTIFIKAT TELAH DICABUT (REVOKED)
                                    </span>
                                    <h3 class="text-xl font-black text-white mt-1">Sertifikat Tidak Berlaku</h3>
                                    <p class="text-xs text-red-200">Nomor Seri: <span class="font-mono font-bold">{{ $certificate->certificate_number }}</span></p>
                                </div>
                            </div>

                            <div class="p-4 bg-red-900/30 rounded-2xl border border-red-800/80 space-y-1 text-xs">
                                <span class="font-bold text-red-300 block">Alasan Pencabutan Resmi:</span>
                                <p class="text-red-100 font-medium">"{{ $certificate->revocation_reason }}"</p>
                                <span class="text-3xs text-red-400 block pt-1">Dicabut pada: {{ $certificate->revoked_at->isoFormat('D MMMM YYYY') }}</span>
                            </div>
                        </div>
                    @else
                        <!-- VALID CERTIFICATE CARD -->
                        <div class="bg-gradient-to-br from-slate-900 via-slate-850 to-slate-900 border-2 border-emerald-500 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl">
                            
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-2xl font-black shrink-0 shadow-lg shadow-emerald-500/30">
                                        ✔️
                                    </div>
                                    <div>
                                        <span class="px-2.5 py-0.5 rounded-full text-3xs font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                            TERVERIFIKASI ASLI & SAH (VALID)
                                        </span>
                                        <h3 class="text-xl font-black text-white mt-1">{{ $certificate->participant_name }}</h3>
                                        <p class="text-xs text-slate-400 font-mono">No. Seri: <span class="text-emerald-400 font-bold">{{ $certificate->certificate_number }}</span></p>
                                    </div>
                                </div>

                                <span class="px-4 py-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded-2xl text-xs font-black uppercase tracking-wider text-center">
                                    Grade: {{ $certificate->performance_grade ?? 'A' }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div class="p-4 bg-slate-950 rounded-2xl border border-slate-800 space-y-1">
                                    <span class="text-3xs text-slate-500 uppercase font-black">Posisi & Peran Magang</span>
                                    <div class="text-sm font-bold text-white">{{ $certificate->job_title }}</div>
                                </div>

                                <div class="p-4 bg-slate-950 rounded-2xl border border-slate-800 space-y-1">
                                    <span class="text-3xs text-slate-500 uppercase font-black">Institusi / Universitas</span>
                                    <div class="text-sm font-bold text-white">{{ $certificate->institution_name ?? 'Universitas / Kampus Terdaftar' }}</div>
                                </div>

                                <div class="p-4 bg-slate-950 rounded-2xl border border-slate-800 space-y-1">
                                    <span class="text-3xs text-slate-500 uppercase font-black">Periode Pelaksanaan</span>
                                    <div class="text-sm font-bold text-white">{{ $certificate->start_date->format('d M Y') }} s/d {{ $certificate->end_date->format('d M Y') }}</div>
                                </div>

                                <div class="p-4 bg-slate-950 rounded-2xl border border-slate-800 space-y-1">
                                    <span class="text-3xs text-slate-500 uppercase font-black">Tanggal Penerbitan Resmi</span>
                                    <div class="text-sm font-bold text-white">{{ $certificate->issued_at->format('d M Y') }}</div>
                                </div>
                            </div>

                            <div class="p-4 bg-blue-500/10 rounded-2xl border border-blue-500/30 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2.5 text-blue-300">
                                    <i class="fa-solid fa-award text-base"></i>
                                    <span>Telah divalidasi oleh Tim Penyelenggara & Mentor Perusahaan.</span>
                                </div>
                            </div>

                        </div>
                    @endif
                @else
                    <!-- NOT FOUND BANNER -->
                    <div class="p-8 bg-slate-900 border border-slate-800 rounded-3xl text-center space-y-3">
                        <div class="text-4xl">🔍</div>
                        <h3 class="text-lg font-black text-white">Sertifikat Tidak Ditemukan</h3>
                        <p class="text-xs text-slate-400 max-w-md mx-auto">
                            Nomor seri <span class="font-mono text-amber-400 font-bold">{{ $searchCode }}</span> tidak terdaftar dalam database sertifikat resmi TalentFlow. Pastikan nomor yang dimasukkan sudah benar.
                        </p>
                    </div>
                @endif
            @endif

        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 py-6 text-center text-3xs text-slate-500 font-medium">
        &copy; {{ date('Y') }} TalentFlow Enterprise Credential Verification System. All rights reserved.
    </footer>

</body>
</html>
