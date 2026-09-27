<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ (config('app.name') && config('app.name') !== 'Laravel') ? config('app.name') : 'KarirHub' }} - Platform Lowongan Kerja & Karir</title>
        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50/50 flex flex-col min-h-screen selection:bg-blue-500 selection:text-white">
        
        <!-- Navigation -->
        <nav x-data="{ open: false, mobilePanduanOpen: {{ request()->routeIs('pages.guide*') ? 'true' : 'false' }} }" class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs sticky top-0 z-50 transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 sm:h-20">
                    <div class="flex items-center">
                        <div class="shrink-0 flex items-center">
                            <a href="/" class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5 group">
                                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-xs group-hover:bg-blue-700 transition-colors">
                                    <i class="fa-solid fa-briefcase text-sm"></i>
                                </div>
                                <span class="font-bold tracking-tight">{{ (config('app.name') && config('app.name') !== 'Laravel') ? config('app.name') : 'KarirHub' }}</span>
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
                                    <span>Panduan & Aturan</span>
                                    <i class="fa-solid fa-chevron-down text-[10px] ml-1.5 opacity-70 transition-transform duration-200" :class="{ 'rotate-180': panduanOpen }"></i>
                                </button>

                                <div x-show="panduanOpen" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute left-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2.5 z-50 divide-y divide-slate-100" 
                                     style="display: none;">
                                    
                                    <div class="py-1 space-y-0.5">
                                        <!-- Panduan Pelamar -->
                                        <a href="{{ route('pages.guide.candidate') }}" @click="panduanOpen = false" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition group">
                                            <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs shrink-0 border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition">
                                                <i class="fa-solid fa-user-graduate"></i>
                                            </div>
                                            <div>
                                                <div>Panduan Pelamar</div>
                                                <div class="text-[10px] text-slate-400 font-normal">Alur CV ATS, tes online & melamar</div>
                                            </div>
                                        </a>

                                        <!-- Panduan Penyelenggara -->
                                        <a href="{{ route('pages.guide.employer') }}" @click="panduanOpen = false" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition group">
                                            <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-xs shrink-0 border border-slate-200 group-hover:bg-slate-900 group-hover:text-white transition">
                                                <i class="fa-solid fa-building"></i>
                                            </div>
                                            <div>
                                                <div>Panduan Penyelenggara</div>
                                                <div class="text-[10px] text-slate-400 font-normal">Pasang lowongan & kelola HR</div>
                                            </div>
                                        </a>

                                        <!-- Panduan Mentor -->
                                        <a href="{{ route('pages.guide.mentor') }}" @click="panduanOpen = false" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-purple-50 hover:text-purple-600 transition group">
                                            <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs shrink-0 border border-purple-100 group-hover:bg-purple-600 group-hover:text-white transition">
                                                <i class="fa-solid fa-chalkboard-user"></i>
                                            </div>
                                            <div>
                                                <div>Panduan Mentor Magang</div>
                                                <div class="text-[10px] text-slate-400 font-normal">ACC presensi, silabus & uang saku</div>
                                            </div>
                                        </a>

                                        <!-- Aturan & Kebijakan Magang -->
                                        <a href="{{ route('pages.guide.rules') }}" @click="panduanOpen = false" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition group">
                                            <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs shrink-0 border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition">
                                                <i class="fa-solid fa-scale-balanced"></i>
                                            </div>
                                            <div>
                                                <div>Aturan & Kebijakan Magang</div>
                                                <div class="text-[10px] text-slate-400 font-normal">Jam kerja, libur, izin & uang saku</div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="pt-1.5 pb-0.5">
                                        <a href="{{ route('pages.guide') }}" @click="panduanOpen = false" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 transition">
                                            <span>Pusat Panduan Utama</span>
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>
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
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-b border-slate-200 shadow-lg max-h-[calc(100vh-4rem)] overflow-y-auto">
                <div class="p-3 space-y-1">
                    <!-- Beranda -->
                    <a href="{{ url('/') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->is('/') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs shrink-0 {{ request()->is('/') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                            <i class="fa-solid fa-house"></i>
                        </div>
                        <span>Beranda</span>
                    </a>

                    <!-- Lowongan -->
                    <a href="{{ route('jobs.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('jobs.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs shrink-0 {{ request()->routeIs('jobs.*') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <span>Lowongan</span>
                    </a>

                    <!-- Penyelenggara -->
                    <a href="{{ route('companies.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('companies.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs shrink-0 {{ request()->routeIs('companies.*') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <span>Penyelenggara</span>
                    </a>

                    @auth
                        @if(auth()->user()->hasRole('Candidate'))
                            <a href="{{ route('saved-jobs.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('saved-jobs.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs shrink-0 {{ request()->routeIs('saved-jobs.*') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                                    <i class="fa-solid fa-bookmark"></i>
                                </div>
                                <span>Lowongan Tersimpan</span>
                            </a>
                        @endif
                    @endauth

                    <!-- Panduan & Aturan (Accordion) -->
                    <div class="pt-1">
                        <button @click="mobilePanduanOpen = !mobilePanduanOpen" type="button" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('pages.guide*') ? 'bg-blue-50/70 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs shrink-0 {{ request()->routeIs('pages.guide*') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                                    <i class="fa-solid fa-book-open"></i>
                                </div>
                                <span>Panduan & Aturan</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': mobilePanduanOpen }"></i>
                        </button>

                        <!-- Submenu Items -->
                        <div x-show="mobilePanduanOpen" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             class="pl-4 pr-1 py-1.5 space-y-1 mt-1 border-l-2 border-blue-200 ml-5">
                            
                            <!-- Panduan Pelamar -->
                            <a href="{{ route('pages.guide.candidate') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('pages.guide.candidate') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                                <div class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center text-xs shrink-0 border border-blue-100">
                                    <i class="fa-solid fa-user-graduate text-[11px]"></i>
                                </div>
                                <div>
                                    <div>Panduan Pelamar</div>
                                    <div class="text-[10px] text-slate-400 font-normal">CV ATS, tes online & melamar</div>
                                </div>
                            </a>

                            <!-- Panduan Penyelenggara -->
                            <a href="{{ route('pages.guide.employer') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('pages.guide.employer') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                                <div class="w-6 h-6 rounded-md bg-slate-100 text-slate-700 flex items-center justify-center text-xs shrink-0 border border-slate-200">
                                    <i class="fa-solid fa-building text-[11px]"></i>
                                </div>
                                <div>
                                    <div>Panduan Penyelenggara</div>
                                    <div class="text-[10px] text-slate-400 font-normal">Pasang lowongan & kelola HR</div>
                                </div>
                            </a>

                            <!-- Panduan Mentor -->
                            <a href="{{ route('pages.guide.mentor') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('pages.guide.mentor') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                                <div class="w-6 h-6 rounded-md bg-purple-50 text-purple-600 flex items-center justify-center text-xs shrink-0 border border-purple-100">
                                    <i class="fa-solid fa-chalkboard-user text-[11px]"></i>
                                </div>
                                <div>
                                    <div>Panduan Mentor Magang</div>
                                    <div class="text-[10px] text-slate-400 font-normal">ACC presensi & silabus</div>
                                </div>
                            </a>

                            <!-- Aturan & Kebijakan Magang -->
                            <a href="{{ route('pages.guide.rules') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('pages.guide.rules') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                                <div class="w-6 h-6 rounded-md bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs shrink-0 border border-indigo-100">
                                    <i class="fa-solid fa-scale-balanced text-[11px]"></i>
                                </div>
                                <div>
                                    <div>Aturan & Kebijakan Magang</div>
                                    <div class="text-[10px] text-slate-400 font-normal">Jam kerja, cuti & uang saku</div>
                                </div>
                            </a>

                            <!-- Pusat Panduan Utama -->
                            <a href="{{ route('pages.guide') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-bold text-blue-600 hover:bg-blue-50 transition border-t border-slate-100 mt-1">
                                <span>Pusat Panduan Utama</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- FAQ -->
                    <a href="{{ route('pages.faq') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('pages.faq') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs shrink-0 {{ request()->routeIs('pages.faq') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                            <i class="fa-solid fa-circle-question"></i>
                        </div>
                        <span>FAQ & Bantuan</span>
                    </a>

                    @auth
                        <div class="pt-3 border-t border-slate-100 mt-2">
                            <a href="{{ url('/dashboard') }}" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 shadow-xs transition">
                                <i class="fa-solid fa-gauge-high text-xs"></i>
                                <span>Ke Dashboard</span>
                            </a>
                        </div>
                        
                        <div class="pt-3 pb-1 border-t border-slate-100 mt-2 bg-slate-50/80 rounded-xl p-3">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-semibold text-sm text-slate-900 truncate">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                
                            <div class="space-y-1">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs text-slate-700 hover:bg-white font-medium transition">
                                    <i class="fa-solid fa-user-gear text-slate-400 w-4"></i>
                                    {{ __('Pengaturan Akun') }}
                                </a>
                
                                @if(auth()->user()->hasRole('Candidate'))
                                <a href="{{ route('profile.candidate.details.edit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs text-slate-700 hover:bg-white font-medium transition">
                                    <i class="fa-solid fa-file-lines text-slate-400 w-4"></i>
                                    {{ __('Profil & CV') }}
                                </a>
                                @endif
                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs text-rose-600 hover:bg-rose-50 font-medium transition">
                                        <i class="fa-solid fa-arrow-right-from-bracket text-rose-500 w-4"></i>
                                        {{ __('Log Out') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="pt-3 space-y-2 border-t border-slate-100 mt-2">
                            <a href="{{ route('login') }}" class="flex items-center justify-center py-2.5 text-sm font-semibold text-slate-700 border border-slate-200 rounded-xl hover:bg-slate-50 transition">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="flex items-center justify-center py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-xs transition">Daftar Akun</a>
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
        <footer class="bg-slate-950 text-slate-400 text-xs border-t border-slate-800/80">
            <div class="max-w-7xl mx-auto py-14 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-10">
                    
                    <!-- Col 1: Brand & Social Media -->
                    <div class="lg:col-span-2 space-y-4">
                        <a href="/" class="text-xl font-bold text-white tracking-tight flex items-center gap-2.5 group">
                            <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-md group-hover:bg-blue-700 transition">
                                <i class="fa-solid fa-briefcase text-sm"></i>
                            </div>
                            <span class="font-extrabold tracking-tight text-lg sm:text-xl">TalentFlow</span>
                        </a>

                        <p class="text-slate-400 text-xs max-w-sm leading-relaxed">
                            Platform ekosistem karir & rekrutmen digital modern yang menghubungkan ribuan talenta profesional dan mahasiswa magang dengan perusahaan terkemuka di Indonesia.
                        </p>

                        <!-- Trust Badges -->
                        <div class="flex flex-wrap items-center gap-2 pt-1">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-900 text-emerald-400 rounded-lg text-[11px] font-semibold border border-emerald-500/20 shadow-2xs">
                                <i class="fa-solid fa-shield-halved text-emerald-400 text-[10px]"></i>
                                Terverifikasi & Aman
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-900 text-blue-400 rounded-lg text-[11px] font-semibold border border-blue-500/20 shadow-2xs">
                                <i class="fa-solid fa-lock text-blue-400 text-[10px]"></i>
                                SSL 256-Bit
                            </span>
                        </div>

                        <!-- Social Media Icons -->
                        <div class="pt-2">
                            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2.5">Ikuti Kami di Media Sosial</div>
                            <div class="flex items-center gap-2">
                                <a href="https://linkedin.com" target="_blank" rel="noopener" class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-blue-600 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-800 hover:border-blue-500 shadow-2xs" title="LinkedIn">
                                    <i class="fa-brands fa-linkedin-in text-xs"></i>
                                </a>
                                <a href="https://instagram.com" target="_blank" rel="noopener" class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-gradient-to-tr hover:from-amber-600 hover:via-rose-600 hover:to-purple-600 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-800 hover:border-rose-500 shadow-2xs" title="Instagram">
                                    <i class="fa-brands fa-instagram text-xs"></i>
                                </a>
                                <a href="https://youtube.com" target="_blank" rel="noopener" class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-rose-600 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-800 hover:border-rose-500 shadow-2xs" title="YouTube">
                                    <i class="fa-brands fa-youtube text-xs"></i>
                                </a>
                                <a href="https://tiktok.com" target="_blank" rel="noopener" class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-800 hover:border-slate-600 shadow-2xs" title="TikTok">
                                    <i class="fa-brands fa-tiktok text-xs"></i>
                                </a>
                                <a href="https://x.com" target="_blank" rel="noopener" class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-800 hover:border-slate-600 shadow-2xs" title="X (Twitter)">
                                    <i class="fa-brands fa-x-twitter text-xs"></i>
                                </a>
                                <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-emerald-600 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-800 hover:border-emerald-500 shadow-2xs" title="WhatsApp Support">
                                    <i class="fa-brands fa-whatsapp text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Col 2: Jelajahi Karir -->
                    <div>
                        <h3 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-3.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-compass text-blue-500 text-[11px]"></i>
                            <span>Jelajahi Karir</span>
                        </h3>
                        <ul class="space-y-2.5 text-xs">
                            <li><a href="{{ route('jobs.index') }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Cari Lowongan Kerja</a></li>
                            <li><a href="{{ route('jobs.index', ['work_type' => 'Internship']) }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Lowongan Magang</a></li>
                            <li><a href="{{ route('companies.index') }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Direktori Perusahaan</a></li>
                            <li><a href="{{ route('salary-benchmark.index') }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Estimasi & Standar Gaji</a></li>
                            <li><a href="{{ route('saved-jobs.index') }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Lowongan Tersimpan</a></li>
                        </ul>
                    </div>

                    <!-- Col 3: Untuk Perusahaan (HR) -->
                    <div>
                        <h3 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-3.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-building text-blue-500 text-[11px]"></i>
                            <span>Untuk Perusahaan</span>
                        </h3>
                        <ul class="space-y-2.5 text-xs">
                            <li><a href="{{ route('admin.jobs.create') }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Pasang Lowongan Baru</a></li>
                            <li><a href="{{ route('profile.role-request.show') }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Pengajuan Akun Mitra</a></li>
                            <li><a href="{{ route('admin.applications.index') }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Manajemen Seleksi Pelamar</a></li>
                            <li><a href="{{ route('admin.company-team.index') }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Kolaborasi Tim HR</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Masuk Portal HR / Owner</a></li>
                        </ul>
                    </div>

                    <!-- Col 4: Dukungan & Kontak -->
                    <div>
                        <h3 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-3.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-headset text-blue-500 text-[11px]"></i>
                            <span>Bantuan & Kontak</span>
                        </h3>
                        <ul class="space-y-2.5 text-xs">
                            <li><a href="{{ route('pages.faq') }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Pusat Bantuan & FAQ</a></li>
                            <li><a href="{{ route('pages.guide') }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Panduan Pengguna</a></li>
                            <li><a href="{{ route('pages.privacy') }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Kebijakan Privasi</a></li>
                            <li><a href="{{ route('pages.terms') }}" class="hover:text-blue-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-slate-600"></i> Syarat & Ketentuan</a></li>
                            <li class="pt-2 border-t border-slate-900">
                                <a href="mailto:support@talentflow.id" class="text-blue-400 hover:underline flex items-center gap-2">
                                    <i class="fa-solid fa-envelope text-slate-400"></i>
                                    <span>support@talentflow.id</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

                <!-- Bottom Copyright & Status Bar -->
                <div class="mt-12 pt-8 border-t border-slate-800/80 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                    <p>
                        &copy; {{ date('Y') }} TalentFlow Indonesia Career Platform. Hak Cipta Dilindungi Undang-Undang.
                    </p>

                    <!-- System Status Indicator -->
                    <div class="flex items-center gap-2 px-3 py-1 bg-slate-900 rounded-full border border-slate-800 text-[11px] text-slate-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Semua Sistem Normal</span>
                    </div>

                    <div class="flex items-center gap-4 text-[11px]">
                        <span>Indonesia (ID)</span>
                        <span>•</span>
                        <span>Platform Rekrutmen Resmi</span>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
