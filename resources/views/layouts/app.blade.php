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

        <!-- Dark Mode Initialization Script (Cross-Browser & Database Synced) -->
        <script>
            @auth
                const userDbTheme = "{{ auth()->user()->theme_preference ?? 'system' }}";
                if (userDbTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else if (userDbTheme === 'light') {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            @else
                if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            @endauth
        </script>

        <!-- Global Dark Mode Core Stylesheet -->
        <style>
            .dark { color-scheme: dark; }
            .dark body { background-color: #0b1120 !important; color: #f1f5f9 !important; }
            .dark .bg-white:not([data-theme="light"]):not(.keep-white) { background-color: #1e293b !important; color: #f8fafc !important; }
            .dark .bg-slate-50:not([data-theme="light"]):not(.keep-light),
            .dark .bg-slate-50\/60, .dark .bg-slate-50\/70, .dark .bg-slate-50\/80,
            .dark .bg-gray-50, .dark .bg-gray-50\/50, .dark .bg-gray-50\/60, .dark .bg-gray-50\/70, .dark .bg-gray-50\/80,
            .dark .bg-zinc-50, .dark .bg-neutral-50 { background-color: #0b1120 !important; }
            .dark .bg-slate-100:not([data-theme="light"]),
            .dark .bg-gray-100, .dark .bg-zinc-100 { background-color: #1e293b !important; }
            .dark .bg-slate-200, .dark .bg-gray-200, .dark .bg-zinc-200 { background-color: #334155 !important; }
            
            .dark .border-slate-100, .dark .border-slate-200, .dark .border-slate-200\/60,
            .dark .border-slate-200\/70, .dark .border-slate-200\/80, .dark .border-slate-200\/90,
            .dark .border-slate-300, .dark .border-gray-100, .dark .border-gray-200, .dark .border-gray-300 {
                border-color: #334155 !important;
            }
            .dark .divide-slate-100 > :not([hidden]) ~ :not([hidden]),
            .dark .divide-slate-200 > :not([hidden]) ~ :not([hidden]),
            .dark .divide-slate-300 > :not([hidden]) ~ :not([hidden]),
            .dark .divide-gray-100 > :not([hidden]) ~ :not([hidden]),
            .dark .divide-gray-200 > :not([hidden]) ~ :not([hidden]) {
                border-color: #334155 !important;
            }
            
            .dark .text-slate-900, .dark .text-slate-800, .dark .text-gray-900, .dark .text-gray-800 { color: #f8fafc !important; }
            .dark .text-slate-700, .dark .text-slate-600, .dark .text-gray-700, .dark .text-gray-600 { color: #cbd5e1 !important; }
            .dark .text-slate-500, .dark .text-slate-400, .dark .text-gray-500, .dark .text-gray-400 { color: #94a3b8 !important; }
            
            .dark input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="file"]),
            .dark select, .dark textarea {
                background-color: #0f172a !important;
                color: #f8fafc !important;
                border-color: #334155 !important;
            }
            .dark input[type="file"] {
                color: #94a3b8 !important;
            }
            .dark input::placeholder, .dark textarea::placeholder { color: #64748b !important; }
            .dark select option { background-color: #0f172a !important; color: #f8fafc !important; }
            
            .dark tr:hover, .dark tr.hover\:bg-slate-50:hover, .dark tr.hover\:bg-slate-50\/70:hover, .dark tr.hover\:bg-slate-50\/80:hover {
                background-color: rgba(51, 65, 85, 0.4) !important;
            }
            .dark .hover\:bg-slate-50:hover, .dark .hover\:bg-slate-100:hover, .dark .hover\:bg-slate-50\/80:hover {
                background-color: #334155 !important;
            }
            .dark thead tr { background-color: #0f172a !important; border-color: #334155 !important; }
            
            .dark .bg-emerald-50, .dark .bg-emerald-50\/40, .dark .bg-emerald-50\/50 {
                background-color: rgba(6, 78, 59, 0.35) !important; color: #6ee7b7 !important; border-color: rgba(6, 95, 70, 0.6) !important;
            }
            .dark .text-emerald-700, .dark .text-emerald-800, .dark .text-emerald-900 { color: #6ee7b7 !important; }
            
            .dark .bg-blue-50, .dark .bg-blue-50\/40, .dark .bg-blue-50\/50 {
                background-color: rgba(30, 58, 138, 0.35) !important; color: #93c5fd !important; border-color: rgba(30, 64, 175, 0.6) !important;
            }
            .dark .text-blue-700, .dark .text-blue-800, .dark .text-blue-900 { color: #93c5fd !important; }
            
            .dark .bg-indigo-50, .dark .bg-indigo-50\/40, .dark .bg-indigo-50\/50 {
                background-color: rgba(49, 46, 129, 0.35) !important; color: #a5b4fc !important; border-color: rgba(55, 48, 163, 0.6) !important;
            }
            .dark .text-indigo-700, .dark .text-indigo-800, .dark .text-indigo-900 { color: #a5b4fc !important; }
            
            .dark .bg-amber-50, .dark .bg-amber-50\/40, .dark .bg-amber-50\/50 {
                background-color: rgba(120, 53, 15, 0.35) !important; color: #fcd34d !important; border-color: rgba(146, 64, 14, 0.6) !important;
            }
            .dark .text-amber-700, .dark .text-amber-800, .dark .text-amber-900 { color: #fcd34d !important; }
            
            .dark .bg-rose-50, .dark .bg-rose-50\/40, .dark .bg-rose-50\/50 {
                background-color: rgba(136, 19, 55, 0.35) !important; color: #fda4af !important; border-color: rgba(159, 18, 57, 0.6) !important;
            }
            .dark .text-rose-700, .dark .text-rose-800, .dark .text-rose-900 { color: #fda4af !important; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    <body class="font-sans antialiased bg-slate-50/60 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen overflow-x-hidden selection:bg-blue-500 selection:text-white">
        
        @if(session()->has('impersonator_id'))
            <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white text-xs font-semibold px-4 py-2 flex items-center justify-between shadow-md relative z-50">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-200 animate-pulse"></span>
                    <span>Mode Simulasi Aktif: Anda sedang masuk sebagai <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})</span>
                </div>
                <form method="POST" action="{{ route('admin.impersonate.leave') }}">
                    @csrf
                    <button type="submit" class="bg-white text-amber-900 hover:bg-amber-50 font-bold px-3 py-1 rounded-lg text-xs transition shadow-xs flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Kembali ke Super Admin</span>
                    </button>
                </form>
            </div>
        @endif

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
    </body>
</html>
