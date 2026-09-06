<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'KarirHub') }} - Platform Lowongan Kerja & Karir</title>
        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50/50 flex flex-col min-h-screen selection:bg-blue-500 selection:text-white">
        
        <!-- Navigation -->
        <nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs sticky top-0 z-50 transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 sm:h-20">
                    <div class="flex items-center">
                        <div class="shrink-0 flex items-center">
                            <a href="/" class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5 group">
                                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-xs group-hover:bg-blue-700 transition-colors">
                                    <i class="fa-solid fa-briefcase text-sm"></i>
                                </div>
                                <span class="font-bold tracking-tight">{{ config('app.name', 'KarirHub') }}</span>
                            </a>
                        </div>
                        <div class="hidden sm:-my-px sm:ml-8 sm:flex sm:items-center sm:space-x-1">
                            <!-- Beranda -->
                            <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 text-sm rounded-full transition {{ request()->is('/') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50' }}">
                                Beranda
                            </a>

                            <!-- Lowongan -->
                            <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2 text-sm rounded-full transition {{ request()->routeIs('jobs.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50' }}">
                                Lowongan
                            </a>

                            <!-- Penyelenggara -->
                            <a href="{{ route('companies.index') }}" class="inline-flex items-center px-4 py-2 text-sm rounded-full transition {{ request()->routeIs('companies.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50' }}">
                                Penyelenggara
                            </a>

                            <!-- Panduan Dropdown -->
                            <div class="relative" x-data="{ panduanOpen: false }">
                                <button @click="panduanOpen = !panduanOpen" @click.outside="panduanOpen = false" class="inline-flex items-center px-4 py-2 text-sm rounded-full transition {{ request()->routeIs('pages.guide*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50' }}">
                                    <span>Panduan</span>
                                    <i class="fa-solid fa-chevron-down text-[10px] ml-1.5 opacity-70 transition-transform duration-200" :class="{ 'rotate-180': panduanOpen }"></i>
                                </button>

                                <div x-show="panduanOpen" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute left-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2 z-50" 
                                     style="display: none;">
                                    <a href="{{ route('pages.guide.candidate') }}" @click="panduanOpen = false" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                        <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs shrink-0 border border-blue-100">
                                            <i class="fa-solid fa-user-graduate"></i>
                                        </div>
                                        <div>
                                            <div>Panduan Pelamar</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Alur CV, tes online & melamar</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('pages.guide.employer') }}" @click="panduanOpen = false" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                        <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-xs shrink-0 border border-slate-200">
                                            <i class="fa-solid fa-building"></i>
                                        </div>
                                        <div>
                                            <div>Panduan Penyelenggara</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Pasang lowongan & kelola HR</div>
                                        </div>
                                    </a>
                                    <div class="border-t border-slate-100 my-1"></div>
                                    <a href="{{ route('pages.guide') }}" @click="panduanOpen = false" class="flex items-center justify-between px-4 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-50 transition">
                                        <span>Pusat Panduan Utama</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- FAQ -->
                            <a href="{{ route('pages.faq') }}" class="inline-flex items-center px-4 py-2 text-sm rounded-full transition {{ request()->routeIs('pages.faq') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50' }}">
                                FAQ
                            </a>

                            @auth
                                @if(auth()->user()->hasRole('Candidate'))
                                    <a href="{{ route('saved-jobs.index') }}" class="inline-flex items-center px-4 py-2 text-sm rounded-full transition {{ request()->routeIs('saved-jobs.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50' }}">
                                        Tersimpan
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                    <div class="hidden sm:flex sm:items-center sm:ml-6 gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-xs font-semibold bg-slate-900 text-white px-4 py-2.5 rounded-lg hover:bg-slate-800 shadow-xs transition-all flex items-center gap-2">
                                <i class="fa-solid fa-gauge-high text-xs"></i>
                                <span>Dashboard</span>
                            </a>
                            
                            <!-- Settings Dropdown -->
                            <div class="ml-2 relative">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-3 py-1.5 border border-slate-200 text-xs font-semibold rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition shadow-xs">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-md bg-blue-600 flex items-center justify-center text-white font-bold text-xs">
                                                    {{ substr(Auth::user()->name, 0, 1) }}
                                                </div>
                                                <span>{{ explode(' ', Auth::user()->name)[0] }}</span>
                                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                                            </div>
                                        </button>
                                    </x-slot>
                
                                    <x-slot name="content">
                                        <div class="px-4 py-2.5 border-b border-slate-100">
                                            <p class="text-xs text-slate-500">Masuk sebagai</p>
                                            <p class="text-xs font-semibold text-slate-900 truncate">{{ Auth::user()->email }}</p>
                                        </div>
                                        
                                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2.5 py-2 text-xs text-slate-700">
                                            <i class="fa-solid fa-user-gear text-slate-400 w-4"></i>
                                            {{ __('Pengaturan Akun') }}
                                        </x-dropdown-link>
                
                                        @if(auth()->user()->hasRole('Candidate'))
                                        <x-dropdown-link :href="route('profile.candidate.details.edit')" class="flex items-center gap-2.5 py-2 text-xs text-slate-700">
                                            <i class="fa-solid fa-file-lines text-slate-400 w-4"></i>
                                            {{ __('Profil & CV') }}
                                        </x-dropdown-link>
                                        @endif
                                        
                                        <div class="border-t border-slate-100"></div>
                
                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();" class="flex items-center gap-2.5 py-2 text-xs text-rose-600 hover:bg-rose-50">
                                                <i class="fa-solid fa-arrow-right-from-bracket text-rose-500 w-4"></i>
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-700 hover:text-blue-600 px-3 py-2 transition">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg shadow-xs transition-colors">Daftar Akun</a>
                            @endif
                        @endauth
                    </div>
                    
                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-b border-slate-200 shadow-sm">
                <div class="p-3 space-y-1">
                    <a href="{{ url('/') }}" class="flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->is('/') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        Beranda
                    </a>
                    <a href="{{ route('jobs.index') }}" class="flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('jobs.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        Lowongan
                    </a>
                    <a href="{{ route('companies.index') }}" class="flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('companies.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        Penyelenggara
                    </a>
                    <a href="{{ route('pages.guide.candidate') }}" class="flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('pages.guide.candidate') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        Panduan Pelamar
                    </a>
                    <a href="{{ route('pages.guide.employer') }}" class="flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('pages.guide.employer') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        Panduan Penyelenggara
                    </a>
                    <a href="{{ route('pages.faq') }}" class="flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('pages.faq') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        FAQ
                    </a>
                    @auth
                        @if(auth()->user()->hasRole('Candidate'))
                            <a href="{{ route('saved-jobs.index') }}" class="flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('saved-jobs.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                                Tersimpan
                            </a>
                        @endif
                    @endauth
                    @auth
                        <a href="{{ url('/dashboard') }}" class="flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 transition">
                            <i class="fa-solid fa-gauge-high mr-2 text-xs"></i> Dashboard
                        </a>
                        
                        <div class="pt-4 pb-1 border-t border-slate-200 mt-2 bg-slate-50">
                            <div class="px-4 flex items-center gap-3 mb-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-sm text-slate-900">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                
                            <div class="mt-2 space-y-1">
                                <a href="{{ route('profile.edit') }}" class="block pl-3 pr-4 py-2 text-sm text-slate-600 hover:bg-slate-100 font-medium">
                                    {{ __('Pengaturan Akun') }}
                                </a>
                
                                @if(auth()->user()->hasRole('Candidate'))
                                <a href="{{ route('profile.candidate.details.edit') }}" class="block pl-3 pr-4 py-2 text-sm text-slate-600 hover:bg-slate-100 font-medium">
                                    {{ __('Profil & CV') }}
                                </a>
                                @endif
                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();" class="block pl-3 pr-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-medium">
                                        {{ __('Log Out') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="p-4 space-y-2 border-t border-slate-200 mt-2">
                            <a href="{{ route('login') }}" class="block w-full text-center py-2 text-sm font-semibold text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block w-full text-center py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Daftar Akun</a>
                            @endif
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Modern Footer -->
        <footer class="bg-slate-900 text-slate-400 text-xs border-t border-slate-800">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2 space-y-4">
                        <a href="/" class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                            <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xs">
                                <i class="fa-solid fa-briefcase text-xs"></i>
                            </div>
                            {{ config('app.name', 'KarirHub') }}
                        </a>
                        <p class="text-slate-400 text-xs max-w-sm leading-relaxed">
                            Platform rekrutmen digital terintegrasi untuk menghubungkan talenta terbaik dengan perusahaan terkemuka di Indonesia.
                        </p>
                        <div class="flex items-center gap-3 pt-1">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-800/80 text-emerald-400 rounded-md text-[11px] font-medium border border-slate-700/60">
                                <i class="fa-solid fa-shield-halved text-emerald-400 text-[10px]"></i>
                                Terverifikasi & Aman
                            </span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-slate-200 uppercase tracking-wider mb-3">Navigasi</h3>
                        <ul class="space-y-2 text-xs">
                            <li><a href="{{ route('jobs.index') }}" class="hover:text-white transition">Cari Lowongan Kerja</a></li>
                            <li><a href="{{ route('companies.index') }}" class="hover:text-white transition">Direktori Penyelenggara</a></li>
                            <li><a href="{{ route('pages.guide') }}" class="hover:text-white transition">Pusat Panduan</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-white transition">Masuk Portal</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition">Daftar Akun Baru</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-slate-200 uppercase tracking-wider mb-3">Dukungan & Informasi</h3>
                        <ul class="space-y-2 text-xs">
                            <li><a href="{{ route('pages.faq') }}" class="hover:text-white transition">Pusat Bantuan & FAQ</a></li>
                            <li><a href="{{ route('pages.privacy') }}" class="hover:text-white transition">Kebijakan Privasi</a></li>
                            <li><a href="{{ route('pages.terms') }}" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                            <li><a href="mailto:support@karirhub.id" class="hover:text-white transition text-slate-300">support@karirhub.id</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-10 border-t border-slate-800/80 pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                    <p>
                        &copy; {{ date('Y') }} TalentFlow Career Platform. Hak Cipta Dilindungi Undang-Undang.
                    </p>
                    <div class="flex items-center gap-4">
                        <span>Indonesia (ID)</span>
                        <span>•</span>
                        <span>Platform Rekrutmen Resmi</span>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
