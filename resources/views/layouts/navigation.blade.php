<nav x-data="{ open: false }" class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-700 shadow-sm fixed top-0 left-0 right-0 z-50 transition-colors">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/" class="text-xl font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <svg class="w-7 h-7 text-slate-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        TalentFlow
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @auth
                        <x-nav-link :href="auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('HR') || auth()->user()->hasRole('Company Owner') ? route('admin.dashboard') : route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        
                        @if(auth()->user()->hasRole('HR') || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Company Owner'))
                            <x-nav-link :href="route('admin.jobs.index')" :active="request()->routeIs('admin.jobs.*')">
                                Lowongan
                            </x-nav-link>
                            <x-nav-link :href="route('admin.applications.index')" :active="request()->routeIs('admin.applications.*')">
                                Pelamar
                            </x-nav-link>
                            <x-nav-link :href="route('admin.calendar.index')" :active="request()->routeIs('admin.calendar.*')">
                                Kalender
                            </x-nav-link>
                            <x-nav-link :href="route('admin.analytics.index')" :active="request()->routeIs('admin.analytics.*')">
                                Analytics
                            </x-nav-link>
                            <x-nav-link :href="route('admin.company-team.index')" :active="request()->routeIs('admin.company-team.*')">
                                Tim HR
                            </x-nav-link>
                            <x-nav-link :href="route('admin.company.branches.index')" :active="request()->routeIs('admin.company.branches.*')">
                                Cabang
                            </x-nav-link>
                            @if(!auth()->user()->hasRole('Super Admin'))
                                <x-nav-link :href="route('admin.company.profile.edit')" :active="request()->routeIs('admin.company.profile.*')">
                                    Profil Perusahaan
                                </x-nav-link>
                            @endif
                        @endif

                        @if(auth()->user()->hasRole('Super Admin'))
                            <!-- Super Admin Control Dropdown -->
                            <div class="hidden sm:flex sm:items-center">
                                <x-dropdown align="left" width="56">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out whitespace-nowrap {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.companies.*') || request()->routeIs('admin.settings.smtp.*') || request()->routeIs('admin.company.profile.*')) ? 'border-slate-900 text-slate-900 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                                            <span>Kelola Platform</span>
                                            <svg class="ml-1 h-4 w-4 fill-current text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                         <x-dropdown-link :href="route('admin.role-requests.index')">
                                             <i class="fa-solid fa-user-shield text-blue-600 mr-2"></i> Verifikasi Pengajuan Perusahaan
                                         </x-dropdown-link>
                                         <x-dropdown-link :href="route('admin.users.index')">
                                             <i class="fa-solid fa-users text-blue-600 mr-2"></i> Kelola Pengguna
                                         </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.companies.index')">
                                            <i class="fa-solid fa-building text-amber-500 mr-2"></i> Kelola Perusahaan
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.settings.smtp.edit')">
                                            <i class="fa-solid fa-sliders text-emerald-600 mr-2"></i> Pengaturan SMTP
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.audit-logs.index')">
                                            <i class="fa-solid fa-list-check text-indigo-600 mr-2"></i> Audit Logs Activity
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.cancellation-tickets.index')">
                                            <i class="fa-solid fa-shield-halved text-red-600 mr-2"></i> Aduan Pembatalan Penerimaan
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.company.profile.edit')">
                                            <i class="fa-solid fa-id-card text-purple-600 mr-2"></i> Profil Perusahaan
                                        </x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif
                        
                        @if(auth()->user()->hasRole('Candidate'))
                            <x-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                                Cari Lowongan
                            </x-nav-link>
                            <x-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.*')">
                                Perusahaan
                            </x-nav-link>
                            <x-nav-link :href="route('saved-jobs.index')" :active="request()->routeIs('saved-jobs.*')">
                                Lowongan Tersimpan
                            </x-nav-link>
                            <x-nav-link :href="route('candidate.logbook.index')" :active="request()->routeIs('candidate.logbook.*')">
                                📋 Presensi Magang
                            </x-nav-link>
                            <x-nav-link :href="route('candidate.cv-builder')" :active="request()->routeIs('candidate.cv-builder')">
                                ✨ Live CV Builder
                            </x-nav-link>
                            <x-nav-link :href="route('salary-benchmark.index')" :active="request()->routeIs('salary-benchmark.*')">
                                Estimasi Gaji
                            </x-nav-link>
                        @endif

                        @if(auth()->user()->hasRole('Mentor'))
                            <x-nav-link :href="route('mentor.dashboard')" :active="request()->routeIs('mentor.dashboard') || request()->routeIs('mentor.logbooks.*')">
                                👑 ACC Presensi Magang
                            </x-nav-link>
                            <x-nav-link :href="route('mentor.unlock-requests.index')" :active="request()->routeIs('mentor.unlock-requests.*')">
                                📬 Pengajuan Buka Kunci
                            </x-nav-link>
                        @endif
                    @else
                        <x-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                            Cari Lowongan
                        </x-nav-link>
                        <x-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.*')">
                            Perusahaan
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings & Notifications Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 gap-3">
                @auth
                    <!-- Real-time Notification Bell -->
                    <div x-data="{
                        open: false,
                        unreadCount: 0,
                        notifications: [],
                        fetchNotifications() {
                            fetch('{{ route('notifications.index') }}')
                                .then(res => res.json())
                                .then(data => {
                                    this.notifications = data.notifications;
                                    this.unreadCount = data.unread_count;
                                });
                        },
                        markAsRead(id, link) {
                            fetch('/notifications/' + id + '/read', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            }).then(() => {
                                this.fetchNotifications();
                                if (link && link !== '#') {
                                    window.location.href = link;
                                }
                            });
                        },
                        markAllAsRead() {
                            fetch('{{ route('notifications.read-all') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            }).then(() => this.fetchNotifications());
                        }
                    }" x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 15000);" class="relative">

                        <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-blue-600 focus:outline-none transition rounded-full hover:bg-gray-100">
                            <i class="fa-solid fa-bell text-lg"></i>
                            <template x-if="unreadCount > 0">
                                <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-3xs font-black text-white ring-2 ring-white animate-pulse" x-text="unreadCount"></span>
                            </template>
                        </button>

                        <!-- Dropdown Drawer -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-3xl shadow-xl border border-gray-100 z-50 overflow-hidden" style="display: none;">
                            <div class="p-4 bg-gray-900 text-white flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-bell text-amber-400"></i>
                                    <span class="font-black text-xs">Notifikasi Platform</span>
                                    <span x-show="unreadCount > 0" class="px-2 py-0.5 bg-red-600 text-white rounded-full text-3xs font-black" x-text="unreadCount + ' Baru'"></span>
                                </div>
                                <button @click="markAllAsRead()" class="text-3xs font-bold text-gray-300 hover:text-white underline">
                                    Tandai Semua Dibaca
                                </button>
                            </div>

                            <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
                                <template x-for="notif in notifications" :key="notif.id">
                                    <div @click="markAsRead(notif.id, notif.link)" class="p-3.5 hover:bg-blue-50/50 transition cursor-pointer flex gap-3 items-start" :class="{'bg-blue-50/30': !notif.is_read}">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-xs mt-0.5"
                                             :class="{
                                                'bg-blue-100 text-blue-700': notif.type === 'info',
                                                'bg-emerald-100 text-emerald-700': notif.type === 'success',
                                                'bg-amber-100 text-amber-800': notif.type === 'warning'
                                             }">
                                            <i class="fa-solid" :class="{
                                                'fa-info-circle': notif.type === 'info',
                                                'fa-circle-check': notif.type === 'success',
                                                'fa-triangle-exclamation': notif.type === 'warning'
                                            }"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-center">
                                                <h4 class="font-bold text-xs text-gray-900 truncate" x-text="notif.title"></h4>
                                                <span class="text-3xs text-gray-400 shrink-0 ml-2" x-text="notif.created_at_human"></span>
                                            </div>
                                            <p class="text-2xs text-gray-600 mt-0.5 line-clamp-2" x-text="notif.message"></p>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="notifications.length === 0">
                                    <div class="p-8 text-center text-gray-400 text-xs font-medium">
                                        Belum ada notifikasi baru.
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Dark Mode Toggle Button -->
                    <div x-data="{
                        darkMode: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                        toggleTheme() {
                            this.darkMode = !this.darkMode;
                            if (this.darkMode) {
                                document.documentElement.classList.add('dark');
                                localStorage.setItem('theme', 'dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                                localStorage.setItem('theme', 'light');
                            }
                        }
                    }">
                        <button @click="toggleTheme()" type="button" class="p-2 rounded-xl text-slate-500 hover:text-amber-500 dark:text-slate-400 dark:hover:text-amber-400 focus:outline-none transition-colors bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center w-9 h-9" title="Mode Gelap / Terang">
                            <template x-if="darkMode">
                                <i class="fa-solid fa-sun text-amber-400 text-sm"></i>
                            </template>
                            <template x-if="!darkMode">
                                <i class="fa-solid fa-moon text-slate-600 text-sm"></i>
                            </template>
                        </button>
                    </div>

                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-1.5 p-1 text-sm font-medium rounded-full text-slate-700 dark:text-slate-200 bg-transparent hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition-all duration-150" title="{{ Auth::user()->name }}">
                                @php
                                    $userPhoto = (Auth::user()->candidateProfile && (Auth::user()->candidateProfile->photo || Auth::user()->candidateProfile->photo_path)) 
                                        ? Storage::url(Auth::user()->candidateProfile->photo ?? Auth::user()->candidateProfile->photo_path) 
                                        : null;
                                @endphp
                                <div class="w-9 h-9 rounded-full overflow-hidden shrink-0 border border-slate-200 dark:border-slate-700 shadow-2xs">
                                    @if($userPhoto)
                                        <img src="{{ $userPhoto }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-900 dark:bg-slate-700 flex items-center justify-center text-white font-black text-xs">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <svg class="fill-current h-4 w-4 text-slate-400 dark:text-slate-500 pr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- User Info Header inside Dropdown -->
                            <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                                <p class="text-xs font-black text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-3xs text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ __('Pengaturan Akun') }}
                            </x-dropdown-link>

                            @if(auth()->user()->hasRole('Mentor'))
                            <x-dropdown-link :href="route('mentor.dashboard')" class="flex items-center gap-2 text-indigo-600 font-bold bg-indigo-50/60">
                                <i class="fa-solid fa-user-check text-indigo-600"></i>
                                {{ __('👑 Dasbor ACC Presensi Mentor') }}
                            </x-dropdown-link>
                            @endif

                            @if(auth()->user()->hasRole('Candidate'))
                            <x-dropdown-link :href="route('candidate.logbook.index')" class="flex items-center gap-2 text-blue-600 font-bold bg-blue-50/50">
                                <i class="fa-solid fa-calendar-check text-blue-600"></i>
                                {{ __('📋 Catatan & Presensi Magang') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.candidate.details.edit')" class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                {{ __('Profil & Upload CV') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('candidate.cv-builder')" class="flex items-center gap-2 text-indigo-600 font-bold bg-indigo-50/50">
                                <i class="fa-solid fa-wand-magic-sparkles text-indigo-600"></i>
                                {{ __('✨ Interactive Live CV Builder') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.candidate.documents.index')" class="flex items-center gap-2 text-slate-800 font-semibold">
                                <i class="fa-solid fa-folder-closed text-slate-600"></i>
                                {{ __('Vault Dokumen Pendukung') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.cv.download', ['format' => 'ats'])" class="flex items-center gap-2 text-slate-800 font-semibold">
                                <i class="fa-solid fa-file-contract text-slate-600"></i>
                                {{ __('Download CV (ATS Friendly)') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.cv.download', ['format' => 'creative'])" class="flex items-center gap-2 text-slate-800 font-semibold">
                                <i class="fa-solid fa-file-pdf text-slate-600"></i>
                                {{ __('Download CV (Modern Creative)') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.role-request.show')" class="flex items-center gap-2 text-slate-800 font-semibold border-t border-slate-100">
                                <i class="fa-solid fa-building-circle-check text-slate-600"></i>
                                {{ __('Daftarkan Perusahaan / HR') }}
                            </x-dropdown-link>
                            @endif

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();" class="flex items-center gap-2 text-red-600 hover:text-red-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-extrabold text-slate-700 hover:text-slate-900 px-4 py-2 transition">
                        Masuk / Login
                    </a>
                    <a href="{{ route('register') }}" class="text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 px-4 py-2 rounded-xl shadow-2xs transition border border-slate-900">
                        Daftar Akun
                    </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <x-responsive-nav-link :href="auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('HR') || auth()->user()->hasRole('Company Owner') ? route('admin.dashboard') : route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                
                @if(auth()->user()->hasRole('HR') || auth()->user()->hasRole('Super Admin'))
                <x-responsive-nav-link :href="route('admin.jobs.index')" :active="request()->routeIs('admin.jobs.*')">
                    Lowongan
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.applications.index')" :active="request()->routeIs('admin.applications.*')">
                    Pelamar
                </x-responsive-nav-link>
                @endif
                
                @if(auth()->user()->hasRole('Candidate'))
                <x-responsive-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                    Cari Lowongan
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.*')">
                    Perusahaan
                </x-responsive-nav-link>
                @endif
            @else
                <x-responsive-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                    Cari Lowongan
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.*')">
                    Perusahaan
                </x-responsive-nav-link>
            @endauth
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-900 flex items-center justify-center text-white font-bold text-lg border border-slate-900">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Pengaturan Akun') }}
                    </x-responsive-nav-link>

                    @if(auth()->user()->hasRole('Candidate'))
                    <x-responsive-nav-link :href="route('profile.candidate.details.edit')">
                        {{ __('Profil & Upload CV') }}
                    </x-responsive-nav-link>
                    @endif

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();" class="text-red-600">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-gray-200 p-4 space-y-2">
                <a href="{{ route('login') }}" class="block w-full text-center py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">Masuk / Login</a>
                <a href="{{ route('register') }}" class="block w-full text-center py-2 bg-slate-900 text-white font-bold text-xs rounded-xl border border-slate-900">Daftar Akun</a>
            </div>
        @endauth
    </div>
</nav>
