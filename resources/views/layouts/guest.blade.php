<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TalentFlow') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <style>
            @keyframes talentflowSpin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            @keyframes talentflowPulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.08); }
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased overflow-x-hidden bg-gray-50">
        
        <!-- PURE CSS & JS FULLSCREEN SPLASH LOADING SCREEN -->
        <div id="talentflow-splash-screen" style="position: fixed; inset: 0; background: #ffffff; z-index: 999999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: opacity 0.5s ease-out, visibility 0.5s ease-out;">
            <div style="display: flex; flex-direction: column; align-items: center; gap: 20px; text-align: center;">
                <div style="position: relative; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                    <div style="position: absolute; inset: 0; border: 4px solid #e2e8f0; border-top: 4px solid #2563eb; border-radius: 50%; animation: talentflowSpin 0.8s linear infinite;"></div>
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #2563eb, #4f46e5); color: white; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 900; box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4); animation: talentflowPulse 1.5s ease-in-out infinite;">
                        ⚡
                    </div>
                </div>
                <div>
                    <h2 style="font-size: 20px; font-weight: 900; color: #0f172a; margin: 0; font-family: sans-serif; letter-spacing: -0.5px;">TalentFlow</h2>
                    <p style="font-size: 11px; font-weight: 700; color: #2563eb; letter-spacing: 2px; text-transform: uppercase; margin-top: 6px; font-family: sans-serif;">Memuat Halaman...</p>
                </div>
                <div style="width: 160px; height: 4px; background: #f1f5f9; border-radius: 99px; overflow: hidden; position: relative;">
                    <div id="talentflow-progress-fill" style="position: absolute; top: 0; left: 0; height: 100%; width: 0%; background: linear-gradient(90deg, #2563eb, #4f46e5); transition: width 0.4s ease; border-radius: 99px;"></div>
                </div>
            </div>
        </div>

        <div class="min-h-screen w-full bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex flex-col items-center pt-6 sm:pt-12 pb-12 relative overflow-hidden">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 -left-40 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-0 -right-40 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-40 left-20 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-8 py-10 bg-white/80 backdrop-blur-xl shadow-2xl overflow-hidden sm:rounded-2xl border border-white/50">
                <div class="flex flex-col items-center mb-8">
                    <a href="/" class="flex flex-col items-center gap-2 group">
                        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-2xl font-extrabold text-gray-900 tracking-tight mt-2">TalentFlow</span>
                    </a>
                </div>

                {{ $slot }}
            </div>
        </div>

        <script>
            (function() {
                const splash = document.getElementById('talentflow-splash-screen');
                const fill = document.getElementById('talentflow-progress-fill');
                
                if (fill) fill.style.width = '40%';
                
                setTimeout(function() {
                    if (fill) fill.style.width = '85%';
                }, 300);

                function dismissSplash() {
                    if (fill) fill.style.width = '100%';
                    setTimeout(function() {
                        if (splash) {
                            splash.style.opacity = '0';
                            splash.style.visibility = 'hidden';
                        }
                    }, 400);
                }

                window.addEventListener('load', function() {
                    setTimeout(dismissSplash, 600);
                });

                setTimeout(dismissSplash, 1800);
            })();
        </script>
    </body>
</html>
