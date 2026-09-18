<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat Pengunduran Diri - TalentFlow</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 font-sans selection:bg-rose-500 selection:text-white">

    <div class="max-w-lg w-full bg-slate-800/90 backdrop-blur-md rounded-3xl border border-slate-700/80 p-6 sm:p-8 shadow-2xl space-y-6 text-center">
        
        <!-- Logo & Verification Header -->
        <div class="space-y-3">
            <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-3xl mx-auto shadow-lg">
                <i class="fa-solid fa-shield-check"></i>
            </div>
            <div>
                <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-xs font-bold uppercase tracking-wider">
                    Dokumen Terverifikasi Sah
                </span>
                <h1 class="text-xl sm:text-2xl font-bold text-white mt-2">Verifikasi Surat Pengunduran Diri</h1>
                <p class="text-xs text-slate-400 mt-0.5">Sistem Autentikasi Dokumen Digital TalentFlow Platform</p>
            </div>
        </div>

        @if($resignation)
            <!-- Detail Dokumen -->
            <div class="bg-slate-900/80 rounded-2xl border border-slate-700/80 p-5 text-left text-xs space-y-3.5">
                <div class="flex justify-between items-center pb-2.5 border-b border-slate-800">
                    <span class="text-slate-400">Nomor Registrasi:</span>
                    <span class="font-mono font-bold text-rose-400">SPD/MGN/{{ $resignation->created_at->format('Y/m/') }}{{ sprintf('%04d', $resignation->id) }}</span>
                </div>

                <div class="space-y-2">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Nama Peserta Magang:</span>
                        <span class="font-bold text-white text-sm">{{ $resignation->user->name }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[11px]">Perusahaan Magang:</span>
                        <span class="font-semibold text-slate-200">{{ $resignation->company ? $resignation->company->company_name : ($resignation->application && $resignation->application->job ? $resignation->application->job->company_name : 'PT TALENTFLOW INDONESIA') }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[11px]">Kategori Alasan:</span>
                        <span class="font-semibold text-slate-200">{{ $resignation->category_label }}</span>
                    </div>

                    <div class="flex justify-between items-center pt-1">
                        <div>
                            <span class="text-slate-400 block text-[11px]">Tanggal Efektif Berhenti:</span>
                            <span class="font-bold text-rose-400">{{ $resignation->effective_date->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-400 block text-[11px]">Status Dokumen:</span>
                            <span class="px-2 py-0.5 rounded font-bold uppercase {{ $resignation->status_badge['class'] }}">
                                {{ $resignation->status_badge['label'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-[11px] text-slate-400 space-y-1 leading-relaxed">
                <p><i class="fa-solid fa-lock text-emerald-400 mr-1"></i> Ditandatangani secara elektronik dengan QR Code terenkripsi.</p>
                <p>Dokumen ini sah dan diakui secara resmi oleh perusahaan dan platform.</p>
            </div>

            <div class="pt-2 flex flex-col sm:flex-row gap-2.5">
                <a href="{{ route('candidate.resignations.download', $resignation->id) }}" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-file-pdf text-xs"></i> Unduh PDF Resmi
                </a>
                <a href="/" class="flex-1 py-2.5 bg-slate-700 hover:bg-slate-600 text-white font-semibold text-xs rounded-xl transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-house text-xs"></i> Halaman Utama
                </a>
            </div>

        @else
            <div class="p-6 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-400 text-xs">
                <i class="fa-solid fa-triangle-exclamation text-2xl mb-2 block"></i>
                <p class="font-bold">Dokumen Tidak Ditemukan</p>
                <p class="mt-1 text-slate-400">Nomor registrasi dokumen pengunduran diri tidak valid atau belum terdaftar di sistem.</p>
            </div>
            <a href="/" class="inline-block py-2.5 px-6 bg-slate-700 text-white text-xs font-bold rounded-xl hover:bg-slate-600 transition">
                Kembali ke Beranda
            </a>
        @endif

    </div>

</body>
</html>
