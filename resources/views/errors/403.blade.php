<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>403 - Akses Dibatasi | TalentFlow</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
        
        <!-- Decorative Glow Background Effects -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-rose-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-md w-full bg-slate-800/80 backdrop-blur-xl border border-slate-700/80 p-8 sm:p-10 rounded-3xl shadow-2xl text-center space-y-6 relative z-10">
            
            <!-- Animated Shield Icon -->
            <div class="w-20 h-20 bg-rose-500/10 border border-rose-500/30 text-rose-500 rounded-3xl mx-auto flex items-center justify-center text-3xl shadow-lg animate-bounce">
                <i class="fa-solid fa-user-shield"></i>
            </div>

            <div class="space-y-2">
                <span class="px-3 py-1 bg-rose-500/20 text-rose-300 border border-rose-500/30 text-3xs font-black rounded-full uppercase tracking-wider">
                    Error 403 • Restricted Access
                </span>
                <h1 class="text-2xl font-black text-white tracking-tight pt-1">Akses Halaman Dibatasi</h1>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Maaf, akun Anda tidak memiliki izin atau hak akses role yang sesuai untuk membuka halaman ini.
                </p>
            </div>

            <!-- Role Notification Box -->
            @auth
                <div class="p-4 bg-slate-900/80 rounded-2xl border border-slate-700/60 text-xs text-slate-400 space-y-1">
                    <p class="text-3xs font-bold uppercase text-slate-500">Status Akun Login Saat Ini:</p>
                    <p class="font-extrabold text-white">{{ auth()->user()->name }} <span class="text-blue-400">({{ auth()->user()->getRoleNames()->first() ?? 'Kandidat' }})</span></p>
                </div>
            @endauth

            <!-- Smart Redirect Action Buttons -->
            <div class="space-y-3 pt-2">
                @auth
                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('HR') || auth()->user()->hasRole('Company Owner'))
                        <a href="{{ route('admin.dashboard') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3 px-5 rounded-2xl text-xs shadow-lg transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-gauge"></i> Kembali ke Dashboard Management
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3 px-5 rounded-2xl text-xs shadow-lg transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-house"></i> Kembali ke Dashboard Pelamar
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3 px-5 rounded-2xl text-xs shadow-lg transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-to-bracket"></i> Login dengan Akun Berwenang
                    </a>
                @endauth

                <a href="{{ url('/') }}" class="w-full bg-slate-700/60 hover:bg-slate-700 text-slate-300 font-bold py-2.5 px-5 rounded-2xl text-xs border border-slate-600 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda Utama
                </a>
            </div>

            <p class="text-3xs text-slate-500 pt-2 font-medium">TalentFlow Enterprise Recruitment Security Guard</p>
        </div>
    </body>
</html>
