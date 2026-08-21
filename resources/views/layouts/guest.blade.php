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
        <div id="talentflow-splash-screen" style="position: fixed; inset: 0; background: #ffffff; z-index: 999999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: opacity 0.4s ease-out, visibility 0.4s ease-out;">
            <div style="display: flex; flex-direction: column; align-items: center; gap: 20px; text-align: center;">
                <div style="position: relative; width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                    <div style="position: absolute; inset: 0; border: 3px solid #e2e8f0; border-top: 3px solid #0f172a; border-radius: 50%; animation: talentflowSpin 0.8s linear infinite;"></div>
                    <div style="width: 44px; height: 44px; background: #0f172a; color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700; shadow-2xs; animation: talentflowPulse 1.5s ease-in-out infinite;">
                        ⚡
                    </div>
                </div>
                <div>
                    <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 0; font-family: sans-serif; letter-spacing: -0.3px;">TalentFlow</h2>
                    <p style="font-size: 11px; font-weight: 700; color: #475569; letter-spacing: 1.5px; text-transform: uppercase; margin-top: 4px; font-family: sans-serif;">Memuat Halaman...</p>
                </div>
                <div style="width: 140px; height: 3px; background: #f1f5f9; border-radius: 99px; overflow: hidden; position: relative;">
                    <div id="talentflow-progress-fill" style="position: absolute; top: 0; left: 0; height: 100%; width: 0%; background: #0f172a; transition: width 0.4s ease; border-radius: 99px;"></div>
                </div>
            </div>
        </div>

        <div class="min-h-screen w-full bg-slate-50 flex flex-col items-center justify-center py-12 relative overflow-hidden">
            <div class="relative z-10 w-full sm:max-w-md px-6 sm:px-8 py-8 bg-white shadow-2xs overflow-hidden sm:rounded-2xl border border-slate-200">
                <div class="flex flex-col items-center mb-6">
                    <a href="/" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center text-white shadow-2xs group-hover:bg-slate-800 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xl font-bold text-slate-900 tracking-tight mt-1">TalentFlow</span>
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
