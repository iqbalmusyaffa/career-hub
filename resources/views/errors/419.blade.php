<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>419 - Sesi Berakhir | TalentFlow</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
        
        <!-- Decorative Glow Background Effects -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-md w-full bg-slate-800/80 backdrop-blur-xl border border-slate-700/80 p-8 sm:p-10 rounded-3xl shadow-2xl text-center space-y-6 relative z-10">
            
            <!-- Animated Icon -->
            <div class="w-20 h-20 bg-purple-500/10 border border-purple-500/30 text-purple-400 rounded-3xl mx-auto flex items-center justify-center text-3xl shadow-lg">
                <i class="fa-solid fa-hourglass-end text-3xl animate-pulse"></i>
            </div>

            <div class="space-y-2">
                <span class="px-3 py-1 bg-purple-500/20 text-purple-300 border border-purple-500/30 text-3xs font-black rounded-full uppercase tracking-wider">
                    Error 419 • Page Expired
                </span>
                <h1 class="text-2xl font-black text-white tracking-tight pt-1">Sesi Formulir Berakhir</h1>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Sesi keamanan formulir Anda telah kadaluarsa karena terlalu lama tidak ada aktivitas. Silakan muat ulang halaman.
                </p>
            </div>

            <!-- Navigation Buttons -->
            <div class="space-y-3 pt-2">
                <button onclick="window.location.reload();" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-extrabold py-3 px-5 rounded-2xl text-xs shadow-lg transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rotate-right"></i> Muat Ulang & Refresh Halaman
                </button>

                <a href="{{ url('/') }}" class="w-full bg-slate-700/60 hover:bg-slate-700 text-slate-300 font-bold py-2.5 px-5 rounded-2xl text-xs border border-slate-600 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-house"></i> Kembali ke Beranda Utama
                </a>
            </div>

            <p class="text-3xs text-slate-500 pt-2 font-medium">TalentFlow Enterprise Security Token</p>
        </div>
    </body>
</html>
