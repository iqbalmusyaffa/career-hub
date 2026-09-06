<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TalentFlow') }} - Portal Karir</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased overflow-x-hidden bg-slate-50/70 selection:bg-blue-500 selection:text-white">
        <div class="min-h-screen w-full flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full sm:max-w-md bg-white p-8 rounded-2xl border border-slate-200/90 shadow-sm">
                <div class="flex flex-col items-center mb-6">
                    <a href="/" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white shadow-xs group-hover:bg-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xl font-bold text-slate-900 tracking-tight">TalentFlow</span>
                    </a>
                </div>

                {{ $slot }}
            </div>
            
            <p class="mt-6 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} TalentFlow Enterprise. Hak Cipta Dilindungi.
            </p>
        </div>
    </body>
</html>
