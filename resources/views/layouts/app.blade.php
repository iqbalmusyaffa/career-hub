<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $siteName = \App\Models\SystemSetting::getByKey('site_name', 'TalentFlow');
            $siteTagline = \App\Models\SystemSetting::getByKey('site_tagline', 'Platform Rekrutmen Enterprise & Portal Karir Modern');
            $favicon = \App\Models\SystemSetting::getByKey('site_favicon');
            $metaTitle = \App\Models\SystemSetting::getByKey('seo_meta_title', $siteName . ' - ' . $siteTagline);
            $metaDesc = \App\Models\SystemSetting::getByKey('seo_meta_description', 'Temukan lowongan kerja terbaik dan kelola rekrutmen perusahaan secara efisien dengan TalentFlow Enterprise.');
            $metaKeywords = \App\Models\SystemSetting::getByKey('seo_meta_keywords', 'lowongan kerja, karir, rekrutmen, hr management, lamar kerja');
            $ogImage = \App\Models\SystemSetting::getByKey('seo_og_image');
            $gaId = \App\Models\SystemSetting::getByKey('google_analytics_id');
        @endphp

        <title>{{ isset($title) ? $title . ' - ' . $siteName : $metaTitle }}</title>

        <!-- Dynamic Favicon -->
        @if($favicon)
            <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $favicon) }}">
        @endif

        <!-- SEO Meta Tags -->
        <meta name="description" content="{{ $metaDesc }}">
        <meta name="keywords" content="{{ $metaKeywords }}">
        <meta name="robots" content="index, follow">

        <!-- OpenGraph Social Share -->
        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDesc }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        @if($ogImage)
            <meta property="og:image" content="{{ asset('storage/' . $ogImage) }}">
        @endif

        <!-- Google Analytics -->
        @if($gaId)
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ $gaId }}');
            </script>
        @endif

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <style>
            @keyframes talentflowSpin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            @keyframes talentflowPulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.1); }
            }
            @keyframes talentflowBlink {
                0% { opacity: 0.5; }
                100% { opacity: 1; }
            }
        </style>

        <!-- Dark Mode Initialization Script -->
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-slate-100 min-h-screen overflow-x-hidden">
        
        <!-- PURE CSS & JS FULLSCREEN SPLASH LOADING SCREEN -->
        <div id="talentflow-splash-screen" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 999999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: opacity 0.5s ease, visibility 0.5s ease; visibility: visible; opacity: 1;">
            <div style="background: #ffffff; padding: 32px 40px; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35); border: 1px solid rgba(255, 255, 255, 0.4); display: flex; flex-direction: column; align-items: center; gap: 16px; text-align: center; min-width: 240px;">
                <!-- Glowing Spinning Outer Ring -->
                <div style="position: relative; width: 72px; height: 72px; display: flex; align-items: center; justify-content: center;">
                    <div style="position: absolute; inset: 0; border: 4px solid #cbd5e1; border-top: 4px solid #0f172a; border-radius: 50%; animation: talentflowSpin 0.75s linear infinite;"></div>
                    <div style="width: 44px; height: 44px; background: #0f172a; color: #ffffff; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 900; box-shadow: 0 8px 16px rgba(15, 23, 42, 0.25); animation: talentflowPulse 1.2s ease-in-out infinite;">
                        ⚡
                    </div>
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 900; color: #0f172a; margin: 0; font-family: system-ui, -apple-system, sans-serif;">{{ $siteName }}</h3>
                    <p style="font-size: 11px; font-weight: 800; color: #475569; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; font-family: system-ui, -apple-system, sans-serif; animation: talentflowBlink 1s infinite alternate;">Memuat Halaman...</p>
                </div>
                <!-- Progress Line -->
                <div style="width: 140px; height: 4px; background: #e2e8f0; border-radius: 99px; overflow: hidden; position: relative; margin-top: 4px;">
                    <div id="talentflow-progress-fill" style="position: absolute; top: 0; left: 0; height: 100%; width: 45%; background: #0f172a; transition: width 0.3s ease; border-radius: 99px;"></div>
                </div>
            </div>
        </div>

        @auth
            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('HR') || auth()->user()->hasRole('Company Owner'))
                <!-- Left Sidebar Layout for Admin / Management Roles -->
                @include('layouts.sidebar')
            @else
                <!-- Top Navigation Layout for Candidates -->
                <div class="min-h-screen flex flex-col justify-between pt-16">
                    <div>
                        @include('layouts.navigation')

                        @if (isset($header))
                            <header class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 shadow-xs transition-colors">
                                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                                    {{ $header }}
                                </div>
                            </header>
                        @endif

                        <main>
                            @if (isset($slot))
                                {{ $slot }}
                            @endif
                            @yield('content')
                        </main>
                    </div>

                    @include('layouts.footer')
                </div>
            @endif
        @else
            <!-- Guest Layout -->
            <div class="min-h-screen flex flex-col justify-between">
                <div>
                    @include('layouts.navigation')
                    <main>
                        @if (isset($slot))
                            {{ $slot }}
                        @endif
                        @yield('content')
                    </main>
                </div>
                @include('layouts.footer')
            </div>
        @endauth

        <script>
            (function() {
                const splash = document.getElementById('talentflow-splash-screen');
                const fill = document.getElementById('talentflow-progress-fill');
                
                function showLoadingScreen() {
                    if (splash) {
                        splash.style.visibility = 'visible';
                        splash.style.opacity = '1';
                    }
                    if (fill) fill.style.width = '60%';
                }

                function hideLoadingScreen() {
                    if (fill) fill.style.width = '100%';
                    setTimeout(function() {
                        if (splash) {
                            splash.style.opacity = '0';
                            setTimeout(function() {
                                splash.style.visibility = 'hidden';
                            }, 500);
                        }
                    }, 600);
                }

                // Show for guaranteed 1.0 second on load so animation is clearly visible
                showLoadingScreen();

                window.addEventListener('load', function() {
                    setTimeout(hideLoadingScreen, 1000);
                });

                setTimeout(hideLoadingScreen, 2200);

                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('a[href]').forEach(function(link) {
                        const href = link.getAttribute('href');
                        if (href && href.startsWith(window.location.origin) && !href.includes('#') && !link.getAttribute('target')) {
                            link.addEventListener('click', function() {
                                showLoadingScreen();
                            });
                        }
                    });

                    document.querySelectorAll('form').forEach(function(form) {
                        form.addEventListener('submit', function() {
                            showLoadingScreen();
                        });
                    });
                });

                window.addEventListener('beforeunload', function() {
                    showLoadingScreen();
                });
            })();
        </script>
    </body>
</html>
