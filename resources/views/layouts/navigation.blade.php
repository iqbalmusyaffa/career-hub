<nav x-data="{ open: false }" class="bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 shadow-xs fixed top-0 left-0 right-0 z-50 transition-colors">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/" class="text-lg font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5 group">
                        <div class="w-8 h-8 rounded-lg bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-white text-xs font-bold shadow-xs group-hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="tracking-tight">TalentFlow</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-7 sm:-my-px sm:ml-8 sm:flex">
                    @auth
                        @php
                            $dashRoute = route('dashboard');
                            if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('HR') || auth()->user()->hasRole('Company Owner')) {
                                $dashRoute = route('admin.dashboard');
                            } elseif (auth()->user()->hasRole('Mentor')) {
                                $dashRoute = route('mentor.dashboard');
                            }
                        @endphp
                        <x-nav-link :href="$dashRoute" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('mentor.dashboard')">
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
                            <x-nav-link :href="route('mentor.logbooks.index')" :active="request()->routeIs('mentor.logbooks.*') || request()->routeIs('mentor.settings.*')">
                                Batch & Presensi Magang
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
                                        <button class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out whitespace-nowrap {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.companies.*') || request()->routeIs('admin.settings.smtp.*') || request()->routeIs('admin.company.profile.*')) ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300' }}">
                                            <span>Kelola Platform</span>
                                            <svg class="ml-1 h-4 w-4 fill-current text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                         <x-dropdown-link :href="route('admin.role-requests.index')">
                                             <i class="fa-solid fa-user-shield text-blue-600 mr-2 w-4"></i> Verifikasi Pengajuan Perusahaan
                                         </x-dropdown-link>
                                         <x-dropdown-link :href="route('admin.users.index')">
                                             <i class="fa-solid fa-users text-slate-600 mr-2 w-4"></i> Kelola Pengguna
                                         </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.companies.index')">
                                            <i class="fa-solid fa-building text-slate-600 mr-2 w-4"></i> Kelola Perusahaan
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.settings.smtp.edit')">
                                            <i class="fa-solid fa-sliders text-slate-600 mr-2 w-4"></i> Pengaturan SMTP
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.audit-logs.index')">
                                            <i class="fa-solid fa-list-check text-slate-600 mr-2 w-4"></i> Audit Logs Activity
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.cancellation-tickets.index')">
                                            <i class="fa-solid fa-shield-halved text-rose-600 mr-2 w-4"></i> Aduan Pembatalan Penerimaan
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.company.profile.edit')">
                                            <i class="fa-solid fa-id-card text-slate-600 mr-2 w-4"></i> Profil Perusahaan
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
                                Presensi Magang
                            </x-nav-link>
                            <x-nav-link :href="route('candidate.cv-builder')" :active="request()->routeIs('candidate.cv-builder')">
                                CV Builder
                            </x-nav-link>
                            <x-nav-link :href="route('salary-benchmark.index')" :active="request()->routeIs('salary-benchmark.*')">
                                Estimasi Gaji
                            </x-nav-link>
                        @endif

                        @if(auth()->user()->hasRole('Mentor'))
                            <x-nav-link :href="route('mentor.logbooks.index')" :active="request()->routeIs('mentor.logbooks.*')">
                                ACC Presensi Magang
                            </x-nav-link>
                            <x-nav-link :href="route('mentor.unlock-requests.index')" :active="request()->routeIs('mentor.unlock-requests.*')">
                                Pengajuan Buka Kunci
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
                            fetch('{{ route('notifications.index') }}', {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                                .then(res => res.json())
                                .then(data => {
                                    this.notifications = data.notifications || [];
                                    this.unreadCount = data.unread_count || 0;
                                })
                                .catch(err => console.error('Notif fetch error:', err));
                        },
                        markAsRead(id, link) {
                            fetch('/notifications/' + id + '/read', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
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
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            }).then(() => this.fetchNotifications());
                        }
                    }" x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 15000);" class="relative">

                        <button @click="open = !open" class="relative p-2 text-slate-500 hover:text-blue-600 focus:outline-none transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                            <i class="fa-solid fa-bell text-sm"></i>
                            <template x-if="unreadCount > 0">
                                <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-600 text-[10px] font-bold text-white ring-2 ring-white" x-text="unreadCount"></span>
                            </template>
                        </button>

                        <!-- Dropdown Drawer -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 z-50 overflow-hidden" style="display: none;">
                            <div class="p-3.5 bg-slate-900 text-white flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-bell text-blue-400 text-xs"></i>
                                    <span class="font-semibold text-xs">Notifikasi</span>
                                    <span x-show="unreadCount > 0" class="px-2 py-0.5 bg-blue-600 text-white rounded-md text-[10px] font-bold" x-text="unreadCount + ' Baru'"></span>
                                </div>
                                <button @click="markAllAsRead()" class="text-[11px] font-medium text-slate-300 hover:text-white underline">
                                    Tandai Dibaca
                                </button>
                            </div>

                            <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700">
                                <template x-for="notif in notifications" :key="notif.id">
                                    <div @click="markAsRead(notif.id, notif.link)" class="p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition cursor-pointer flex gap-3 items-start" :class="{'bg-blue-50/40 dark:bg-blue-950/20': !notif.is_read}">
                                        <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 text-xs mt-0.5"
                                             :class="{
                                                'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300': notif.type === 'info',
                                                'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300': notif.type === 'success',
                                                'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300': notif.type === 'warning'
                                             }">
                                            <i class="fa-solid" :class="{
                                                'fa-info-circle': notif.type === 'info',
                                                'fa-circle-check': notif.type === 'success',
                                                'fa-triangle-exclamation': notif.type === 'warning'
                                             }"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <template x-if="notif.recipient_name">
                                                <div class="mb-0.5">
                                                    <span class="inline-block px-1.5 py-0.2 rounded bg-blue-50 dark:bg-blue-950/80 text-blue-700 dark:text-blue-300 text-[9px] font-bold" x-text="'👤 ' + notif.recipient_name + ' (' + notif.recipient_role + ')'"></span>
                                                </div>
                                            </template>
                                            <div class="flex justify-between items-center">
                                                <h4 class="font-semibold text-xs text-slate-900 dark:text-white truncate" x-text="notif.title"></h4>
                                                <span class="text-[10px] text-slate-400 shrink-0 ml-2" x-text="notif.created_at_human"></span>
                                            </div>
                                            <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5 line-clamp-2" x-text="notif.message"></p>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="notifications.length === 0">
                                    <div class="p-6 text-center text-slate-400 text-xs font-medium">
                                        Belum ada notifikasi baru.
                                    </div>
                                </template>
                            </div>

                            <!-- Footer: Lihat Selengkapnya -->
                            <div class="p-2.5 bg-slate-50 dark:bg-slate-800/90 border-t border-slate-100 dark:border-slate-700 text-center">
                                <a href="{{ route('notifications.all') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition">
                                    <span>Lihat Selengkapnya</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Dark Mode Toggle Button -->
                    <div x-data="{
                        darkMode: document.documentElement.classList.contains('dark'),
                        toggleTheme() {
                            this.darkMode = !this.darkMode;
                            const newTheme = this.darkMode ? 'dark' : 'light';
                            if (this.darkMode) {
                                document.documentElement.classList.add('dark');
                                localStorage.setItem('theme', 'dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                                localStorage.setItem('theme', 'light');
                            }
                            @auth
                            fetch('{{ route('profile.theme.update') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ theme: newTheme })
                            }).catch(e => console.error('Theme sync error:', e));
                            @endauth
                        }
                    }">
                        <button @click="toggleTheme()" type="button" class="p-2 rounded-lg text-slate-500 hover:text-amber-500 dark:text-slate-400 dark:hover:text-amber-400 focus:outline-none transition-colors bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center w-8 h-8" title="Mode Gelap / Terang">
                            <template x-if="darkMode">
                                <i class="fa-solid fa-sun text-amber-400 text-xs"></i>
                            </template>
                            <template x-if="!darkMode">
                                <i class="fa-solid fa-moon text-slate-600 text-xs"></i>
                            </template>
                        </button>
                    </div>

                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-2.5 py-1.5 text-xs font-semibold rounded-lg text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 focus:outline-none transition" title="{{ Auth::user()->name }}">
                                @php
                                    $userPhoto = (Auth::user()->candidateProfile && (Auth::user()->candidateProfile->photo || Auth::user()->candidateProfile->photo_path)) 
                                        ? Storage::url(Auth::user()->candidateProfile->photo ?? Auth::user()->candidateProfile->photo_path) 
                                        : null;
                                @endphp
                                <div class="w-6 h-6 rounded-md overflow-hidden shrink-0 bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-white font-bold text-xs">
                                    @if($userPhoto)
                                        <img src="{{ $userPhoto }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    @endif
                                </div>
                                <span class="hidden md:inline-block max-w-[120px] truncate">{{ explode(' ', Auth::user()->name)[0] }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- User Info Header inside Dropdown -->
                            <div class="px-4 py-2.5 border-b border-slate-100 dark:border-slate-700">
                                <p class="text-xs font-semibold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2.5 py-2 text-xs">
                                <i class="fa-solid fa-user-gear text-slate-400 w-4"></i>
                                {{ __('Pengaturan Akun') }}
                            </x-dropdown-link>

                            @if(auth()->user()->hasRole('Mentor'))
                            <x-dropdown-link :href="route('mentor.dashboard')" class="flex items-center gap-2.5 py-2 text-xs text-blue-600 font-semibold">
                                <i class="fa-solid fa-user-check text-blue-600 w-4"></i>
                                {{ __('ACC Presensi Mentor') }}
                            </x-dropdown-link>
                            @endif

                            @if(auth()->user()->hasRole('Candidate'))
                            <x-dropdown-link :href="route('candidate.logbook.index')" class="flex items-center gap-2.5 py-2 text-xs">
                                <i class="fa-solid fa-calendar-check text-slate-400 w-4"></i>
                                {{ __('Presensi Magang') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.candidate.details.edit')" class="flex items-center gap-2.5 py-2 text-xs">
                                <i class="fa-solid fa-file-lines text-slate-400 w-4"></i>
                                {{ __('Profil & CV') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('candidate.cv-builder')" class="flex items-center gap-2.5 py-2 text-xs">
                                <i class="fa-solid fa-wand-magic-sparkles text-slate-400 w-4"></i>
                                {{ __('Live CV Builder') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.candidate.documents.index')" class="flex items-center gap-2.5 py-2 text-xs">
                                <i class="fa-solid fa-folder-closed text-slate-400 w-4"></i>
                                {{ __('Vault Dokumen Pendukung') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.role-request.show')" class="flex items-center gap-2.5 py-2 text-xs border-t border-slate-100 dark:border-slate-700">
                                <i class="fa-solid fa-building-circle-check text-slate-400 w-4"></i>
                                {{ __('Daftarkan Perusahaan / HR') }}
                            </x-dropdown-link>
                            @endif

                            <div class="border-t border-slate-100 dark:border-slate-700"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();" class="flex items-center gap-2.5 py-2 text-xs text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-rose-500 w-4"></i>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900 px-3 py-2 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg shadow-xs transition">
                        Daftar Akun
                    </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @php
                    $mobileDashRoute = route('dashboard');
                    if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('HR') || auth()->user()->hasRole('Company Owner')) {
                        $mobileDashRoute = route('admin.dashboard');
                    } elseif (auth()->user()->hasRole('Mentor')) {
                        $mobileDashRoute = route('mentor.dashboard');
                    }
                @endphp
                <x-responsive-nav-link :href="$mobileDashRoute" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('mentor.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                
                @if(auth()->user()->hasRole('HR') || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Company Owner'))
                <x-responsive-nav-link :href="route('admin.jobs.index')" :active="request()->routeIs('admin.jobs.*')">
                    Lowongan
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.applications.index')" :active="request()->routeIs('admin.applications.*')">
                    Pelamar
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mentor.logbooks.index')" :active="request()->routeIs('mentor.logbooks.*') || request()->routeIs('mentor.settings.*')">
                    Batch & Presensi Magang
                </x-responsive-nav-link>
                @endif

                @if(auth()->user()->hasRole('Mentor'))
                <x-responsive-nav-link :href="route('mentor.logbooks.index')" :active="request()->routeIs('mentor.logbooks.*')">
                    ACC Presensi Magang
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mentor.unlock-requests.index')" :active="request()->routeIs('mentor.unlock-requests.*')">
                    Pengajuan Buka Kunci
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
            <div class="pt-4 pb-2 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                <div class="px-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-semibold text-sm text-slate-800 dark:text-white">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
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
                                            this.closest('form').submit();" class="text-rose-600">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-slate-200 dark:border-slate-800 p-4 space-y-2">
                <a href="{{ route('login') }}" class="block w-full text-center py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-lg border border-slate-200 dark:border-slate-700">Masuk</a>
                <a href="{{ route('register') }}" class="block w-full text-center py-2 bg-blue-600 text-white font-semibold text-xs rounded-lg">Daftar Akun</a>
            </div>
        @endauth
    </div>
</nav>
