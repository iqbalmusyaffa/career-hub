<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'TalentFlow') }}</title>
        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 flex flex-col min-h-screen">
        
        <!-- Navigation -->
        <nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="/" class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2 group">
                                <div class="w-9 h-9 bg-slate-900 rounded-xl flex items-center justify-center text-white shadow-2xs group-hover:bg-slate-800 transition border border-slate-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                TalentFlow
                            </a>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('jobs.*') ? 'border-slate-900 text-slate-900 font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300' }} text-xs font-semibold leading-5 transition">
                                💼 Cari Lowongan
                            </a>
                            @auth
                                @if(auth()->user()->hasRole('Candidate'))
                                    <a href="{{ route('saved-jobs.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('saved-jobs.*') ? 'border-slate-900 text-slate-900 font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300' }} text-xs font-semibold leading-5 transition">
                                        ⭐ Lowongan Tersimpan
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                    <div class="hidden sm:flex sm:items-center sm:ml-6 gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-xs font-bold bg-slate-900 text-white px-4 py-2 rounded-xl hover:bg-slate-800 shadow-2xs border border-slate-900 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                Dashboard
                            </a>
                            
                            <!-- Settings Dropdown -->
                            <div class="ml-2 relative">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-2 py-2 border border-slate-200 text-xs leading-4 font-bold rounded-full text-slate-700 bg-slate-50 hover:bg-slate-100 hover:text-slate-900 focus:outline-none transition ease-in-out duration-150 shadow-2xs">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-slate-900 flex items-center justify-center text-white font-bold shadow-2xs border border-slate-900">
                                                    {{ substr(Auth::user()->name, 0, 1) }}
                                                </div>
                                                <span class="hidden md:inline-block font-semibold">{{ explode(' ', Auth::user()->name)[0] }}</span>
                                                <svg class="fill-current h-4 w-4 text-slate-400 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>
                
                                    <x-slot name="content">
                                        <div class="px-4 py-3 border-b border-gray-100">
                                            <p class="text-sm leading-5">Masuk sebagai</p>
                                            <p class="text-sm font-medium leading-5 text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                        </div>
                                        
                                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 py-2.5">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ __('Pengaturan Akun') }}
                                        </x-dropdown-link>
                
                                        @if(auth()->user()->hasRole('Candidate'))
                                        <x-dropdown-link :href="route('profile.candidate.details.edit')" class="flex items-center gap-2 py-2.5">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            {{ __('Profil & Upload CV') }}
                                        </x-dropdown-link>
                                        @endif
                                        
                                        <div class="border-t border-gray-100"></div>
                
                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();" class="flex items-center gap-2 py-2.5 text-red-600 hover:text-red-700 hover:bg-red-50">
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-xs font-bold text-slate-700 hover:text-slate-900 transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-xs font-bold bg-slate-900 text-white px-4 py-2 rounded-xl hover:bg-slate-800 shadow-2xs border border-slate-900 transition-all">Daftar</a>
                            @endif
                        @endauth
                    </div>
                    
                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-b border-slate-200 shadow-2xs">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('jobs.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('jobs.*') ? 'border-slate-900 text-slate-900 bg-slate-100' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-50' }} text-sm font-bold focus:outline-none transition duration-150 ease-in-out">
                        Cari Lowongan
                    </a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-sm font-bold text-slate-900 hover:bg-slate-50">Dashboard</a>
                        
                        <!-- Mobile Settings Options -->
                        <div class="pt-4 pb-1 border-t border-gray-200 mt-2 bg-gray-50">
                            <div class="px-4 flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-slate-900 flex items-center justify-center text-white font-bold text-lg border border-slate-900">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-base text-gray-800">{{ Auth::user()->name }}</div>
                                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                
                            <div class="mt-3 space-y-1">
                                <a href="{{ route('profile.edit') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out">
                                    {{ __('Pengaturan Akun') }}
                                </a>
                
                                @if(auth()->user()->hasRole('Candidate'))
                                <a href="{{ route('profile.candidate.details.edit') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out">
                                    {{ __('Profil & Upload CV') }}
                                </a>
                                @endif
                
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                
                                    <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-red-600 hover:text-red-800 hover:bg-red-50 hover:border-red-300 focus:outline-none transition duration-150 ease-in-out">
                                        {{ __('Log Out') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50">Daftar</a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 border-t border-gray-800 text-gray-400 text-sm">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2 space-y-4">
                        <a href="/" class="text-xl font-bold text-white tracking-tight flex items-center gap-2">
                            <div class="w-8 h-8 bg-slate-800 border border-slate-700 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-2xs">
                                T
                            </div>
                            TalentFlow
                        </a>
                        <p class="text-slate-400 text-xs max-w-sm leading-relaxed font-normal">
                            Platform rekrutmen digital terintegrasi resmi untuk menghubungkan talenta terbaik Indonesia dengan perusahaan nasional dan multinasional.
                        </p>
                        <div class="flex flex-wrap items-center gap-3 pt-1">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-800 text-emerald-400 rounded-md text-3xs font-bold border border-slate-700">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Verified SSL & Data Encryption
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-800 text-slate-300 rounded-md text-3xs font-bold border border-slate-700">
                                🏢 500+ Partner Perusahaan
                            </span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xs font-bold text-slate-300 tracking-wider uppercase mb-4">Pintasan Navigasi</h3>
                        <ul class="space-y-2 text-3xs">
                            <li><a href="{{ route('jobs.index') }}" class="hover:text-white transition">🔍 Cari Lowongan Kerja</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-white transition">🔑 Masuk ke Portal</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition">📝 Mendaftar Akun Baru</a></li>
                            @auth
                                <li><a href="{{ url('/dashboard') }}" class="hover:text-white transition">📊 Dashboard Saya</a></li>
                            @endauth
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-3xs font-bold text-slate-300 tracking-wider uppercase mb-4">Dukungan & Legal</h3>
                        <ul class="space-y-2 text-3xs">
                            <li><a href="{{ route('pages.faq') }}" class="hover:text-white transition">Pusat Bantuan & FAQ</a></li>
                            <li><a href="{{ route('pages.privacy') }}" class="hover:text-white transition">Kebijakan Privasi & Data</a></li>
                            <li><a href="{{ route('pages.terms') }}" class="hover:text-white transition">Syarat & Ketentuan Layanan</a></li>
                            <li><a href="mailto:support@talentflow.com" class="hover:text-white transition text-slate-300 font-medium">✉️ support@talentflow.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-10 border-t border-slate-800 pt-6 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-3xs text-slate-500 font-medium">
                        &copy; {{ date('Y') }} TalentFlow Enterprise Career Portal. Hak Cipta Dilindungi Undang-Undang.
                    </p>
                    <div class="flex items-center gap-4 text-3xs text-slate-500 font-medium">
                        <span>Bahasa Indonesia (ID)</span>
                        <span>•</span>
                        <span>Keamanan Terjamin</span>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
