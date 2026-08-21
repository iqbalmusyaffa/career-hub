<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>404 - Halaman Tidak Ditemukan | TalentFlow</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
        
        <!-- Decorative Glow Background Effects -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-md w-full bg-slate-800/80 backdrop-blur-xl border border-slate-700/80 p-8 sm:p-10 rounded-3xl shadow-2xl text-center space-y-6 relative z-10">
            
            <!-- Animated Icon -->
            <div class="w-20 h-20 bg-blue-500/10 border border-blue-500/30 text-blue-400 rounded-3xl mx-auto flex items-center justify-center text-3xl shadow-lg">
                <i class="fa-solid fa-compass-drafting text-4xl animate-spin" style="animation-duration: 10s;"></i>
            </div>

            <div class="space-y-2">
                <span class="px-3 py-1 bg-blue-500/20 text-blue-300 border border-blue-500/30 text-3xs font-black rounded-full uppercase tracking-wider">
                    Error 404 • Page Not Found
                </span>
                <h1 class="text-2xl font-black text-white tracking-tight pt-1">Halaman Tidak Ditemukan</h1>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Maaf, alamat URL yang Anda tuju tidak ditemukan, telah dipindahkan, atau lowongan pekerjaan sudah ditutup.
                </p>
            </div>

            <!-- Navigation Buttons -->
            <div class="space-y-3 pt-2">
                <a href="{{ route('jobs.index') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3 px-5 rounded-2xl text-xs shadow-lg transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-briefcase"></i> Cari Lowongan Pekerjaan
                </a>

                <a href="{{ url('/') }}" class="w-full bg-slate-700/60 hover:bg-slate-700 text-slate-300 font-bold py-2.5 px-5 rounded-2xl text-xs border border-slate-600 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-house"></i> Kembali ke Beranda Utama
                </a>
            </div>

            <p class="text-3xs text-slate-500 pt-2 font-medium">TalentFlow Enterprise Portal Karir</p>
        </div>
    </body>
</html>
