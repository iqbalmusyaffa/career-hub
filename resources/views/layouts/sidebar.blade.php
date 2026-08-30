@php
    $user = auth()->user();
    $roleName = 'Admin';
    $roleBadgeClass = 'bg-blue-500/15 text-blue-400 border-blue-500/30';
    if ($user->hasRole('Super Admin')) {
        $roleName = 'Super Admin';
        $roleBadgeClass = 'bg-rose-500/15 text-rose-400 border-rose-500/30';
    } elseif ($user->hasRole('Company Owner')) {
        $roleName = 'Company Owner';
        $roleBadgeClass = 'bg-amber-500/15 text-amber-400 border-amber-500/30';
    } elseif ($user->hasRole('HR')) {
        $roleName = 'HR Manager';
        $roleBadgeClass = 'bg-blue-500/15 text-blue-400 border-blue-500/30';
    }
@endphp

<div x-data="{ sidebarOpen: false }" class="flex h-screen bg-slate-50 overflow-hidden font-sans">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden"></div>

    <!-- LEFT SIDEBAR -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-68 bg-slate-900 text-slate-300 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col justify-between shrink-0 shadow-xl border-r border-slate-800">
        
        <div class="flex flex-col h-full">
            <!-- Brand Logo Header -->
            <div class="py-5 px-6 border-b border-slate-800/80 bg-slate-950/50 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white font-black text-lg flex items-center justify-center shadow-md shrink-0 border border-blue-500/30">
                        T
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="font-black text-base text-white tracking-tight leading-none">TalentFlow</span>
                        <div class="mt-1">
                            <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-md uppercase tracking-wider border leading-none {{ $roleBadgeClass }}">
                                {{ $roleName }}
                            </span>
                        </div>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto py-6 px-5 space-y-7">

                <!-- GROUP 1: UTAMA -->
                <div class="space-y-1.5">
                    <div class="px-3 text-3xs font-black uppercase tracking-wider text-slate-400 mb-2.5">Utama</div>

                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') || request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                        <i class="fa-solid fa-chart-pie text-sm w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.jobs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.jobs.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-briefcase text-sm w-5 text-center"></i>
                        <span>Lowongan Kerja</span>
                    </a>

                    <a href="{{ route('admin.applications.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.applications.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-users text-sm w-5 text-center"></i>
                        <span>Data Pelamar</span>
                    </a>
                </div>

                <!-- GROUP 2: REKRUTMEN & TIM -->
                <div class="space-y-1">
                    <div class="px-3 text-3xs font-black uppercase tracking-wider text-slate-400 mb-2">Rekrutmen & Tim</div>

                    <a href="{{ route('admin.calendar.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.calendar.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-calendar-days text-sm w-5 text-center text-purple-400"></i>
                        <span>Kalender Rekrutmen</span>
                    </a>

                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.analytics.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-chart-line text-sm w-5 text-center text-emerald-400"></i>
                        <span>Visual Analytics</span>
                    </a>

                    <a href="{{ route('admin.email-templates.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.email-templates.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-envelope-open-text text-sm w-5 text-center text-amber-400"></i>
                        <span>Template Email HR</span>
                    </a>

                    <a href="{{ route('admin.headcount-budgets.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.headcount-budgets.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-coins text-sm w-5 text-center text-emerald-400"></i>
                        <span>Headcount Planning</span>
                    </a>

                    <a href="{{ route('admin.reports.builder.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.reports.builder.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-chart-pie text-sm w-5 text-center text-cyan-400"></i>
                        <span>Custom Report Builder</span>
                    </a>

                    <a href="{{ route('admin.system-flow.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.system-flow.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-diagram-project text-sm w-5 text-center text-rose-400"></i>
                        <span>Diagram Flow & ERD</span>
                    </a>

                    @if(!$user->hasRole('Super Admin'))
                    <a href="{{ route('admin.company-team.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.company-team.index') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-users-gear text-sm w-5 text-center text-blue-400"></i>
                        <span>Tim HR Perusahaan</span>
                    </a>

                    <a href="{{ route('admin.company-team.audit-logs') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.company-team.audit-logs') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-clock-rotate-left text-sm w-5 text-center text-indigo-400"></i>
                        <span>Log Aktivitas Tim HR</span>
                    </a>

                    <a href="{{ route('admin.company.profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.company.profile.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-building-user text-sm w-5 text-center text-amber-400"></i>
                        <span>Profil Perusahaan</span>
                    </a>
                    @endif
                </div>

                <!-- GROUP 3: SUPER ADMIN CONTROL (Super Admin Only) -->
                @if($user->hasRole('Super Admin'))
                <div class="space-y-1 pt-2 border-t border-slate-800">
                    <div class="px-3 text-3xs font-black uppercase tracking-wider text-rose-400 mb-2">Super Admin Control</div>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.users.*') ? 'bg-rose-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-user-shield text-sm w-5 text-center text-rose-400"></i>
                        <span>Kelola Pengguna</span>
                    </a>

                    <a href="{{ route('admin.companies.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.companies.*') ? 'bg-rose-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-building-circle-check text-sm w-5 text-center text-amber-400"></i>
                        <span>Kelola Perusahaan</span>
                    </a>

                    <a href="{{ route('admin.settings.smtp.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.settings.smtp.*') ? 'bg-rose-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-sliders text-sm w-5 text-center text-emerald-400"></i>
                        <span>Pengaturan SMTP</span>
                    </a>

                    <a href="{{ route('admin.settings.seo.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.settings.seo.*') ? 'bg-rose-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-bullhorn text-sm w-5 text-center text-blue-400"></i>
                        <span>Branding & SEO Web</span>
                    </a>

                    <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.audit-logs.*') ? 'bg-rose-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-list-check text-sm w-5 text-center text-indigo-400"></i>
                        <span>Audit Logs Activity</span>
                    </a>

                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.analytics.*') ? 'bg-rose-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-chart-line text-sm w-5 text-center text-blue-400"></i>
                        <span>Executive Analytics</span>
                    </a>

                    <a href="{{ route('admin.announcements.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.announcements.*') ? 'bg-rose-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-bullhorn text-sm w-5 text-center text-amber-400"></i>
                        <span>Broadcast Center</span>
                    </a>

                    <a href="{{ route('admin.blacklists.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.blacklists.*') ? 'bg-rose-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-user-shield text-sm w-5 text-center text-rose-400"></i>
                        <span>Anti-Fraud Blacklist</span>
                    </a>

                    <a href="{{ route('admin.internship-unlocks.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.internship-unlocks.*') ? 'bg-rose-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-key text-sm w-5 text-center text-amber-400"></i>
                        <span>Pusat Tiket Buka Kunci</span>
                    </a>

                    <a href="{{ route('admin.attendance-settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.attendance-settings.*') ? 'bg-rose-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-satellite text-sm w-5 text-center text-cyan-400"></i>
                        <span>Pengaturan Presensi & GPS</span>
                    </a>

                    <a href="{{ route('admin.internship-monitor.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.internship-monitor.*') ? 'bg-rose-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-chart-line text-sm w-5 text-center text-teal-400"></i>
                        <span>Monitoring Lintas Mitra</span>
                    </a>

                    <a href="{{ route('admin.certificates.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.certificates.*') ? 'bg-rose-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-award text-sm w-5 text-center text-amber-400"></i>
                        <span>Master Sertifikat Magang</span>
                    </a>

                    <a href="{{ route('admin.cancellation-tickets.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.cancellation-tickets.*') ? 'bg-rose-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-shield-halved text-sm w-5 text-center text-rose-400"></i>
                        <span>Aduan Pembatalan</span>
                    </a>
                </div>
                @endif

            </div>

            <!-- Interactive Footer User Profile Card inside Sidebar -->
            <div x-data="{ userMenuOpen: false }" class="relative p-3.5 border-t border-slate-800/80 bg-slate-950/40">
                <button @click="userMenuOpen = !userMenuOpen" class="w-full flex items-center justify-between p-2 rounded-xl hover:bg-slate-800/80 transition text-left group focus:outline-none">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center text-sm shrink-0 shadow-md">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-xs text-white truncate group-hover:text-blue-400 transition">{{ $user->name }}</div>
                            <div class="text-3xs text-slate-400 truncate">{{ $user->email }}</div>
                        </div>
                    </div>
                    <i class="fa-solid fa-ellipsis-vertical text-slate-500 group-hover:text-slate-300 ml-2 transition"></i>
                </button>

                <!-- Interactive Popover Menu -->
                <div x-show="userMenuOpen" @click.away="userMenuOpen = false" x-transition.origin.bottom.left class="absolute bottom-full left-3 mb-2 w-56 bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl py-2 z-50 divide-y divide-slate-700/60" style="display: none;">
                    <div class="px-4 py-2 text-3xs font-black uppercase text-slate-400">
                        Akun Terhubung: <span class="text-white font-bold">{{ $roleName }}</span>
                    </div>

                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-700 hover:text-white transition">
                            <i class="fa-solid fa-user-gear text-slate-400 w-4 text-center"></i>
                            <span>Pengaturan Akun</span>
                        </a>
                        @if($user->hasRole('Company Owner') || $user->hasRole('HR'))
                            <a href="{{ route('admin.company.profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-700 hover:text-white transition">
                                <i class="fa-solid fa-building text-amber-400 w-4 text-center"></i>
                                <span>Profil Perusahaan</span>
                            </a>
                        @endif
                    </div>

                    <div class="pt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-rose-400 hover:bg-rose-600 hover:text-white transition">
                                <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                                <span>Keluar / Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Navbar Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 shrink-0 shadow-2xs">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-extrabold text-slate-900 hidden sm:block">Panel Kontrol Dashboard Enterprise</h1>
            </div>

            <!-- Top Header Actions -->
            <div class="flex items-center gap-4">

                <!-- Real-time Notification Bell Component -->
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

                    <button @click="open = !open" class="relative p-2 text-slate-500 hover:text-blue-600 focus:outline-none transition rounded-full hover:bg-slate-100">
                        <i class="fa-solid fa-bell text-lg"></i>
                        <template x-if="unreadCount > 0">
                            <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-600 text-3xs font-black text-white ring-2 ring-white animate-pulse" x-text="unreadCount"></span>
                        </template>
                    </button>

                    <!-- Dropdown Drawer -->
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-3xl shadow-xl border border-slate-200 z-50 overflow-hidden" style="display: none;">
                        <div class="p-4 bg-slate-900 text-white flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-bell text-amber-400"></i>
                                <span class="font-black text-xs">Notifikasi Platform</span>
                                <span x-show="unreadCount > 0" class="px-2 py-0.5 bg-rose-600 text-white rounded-full text-3xs font-black" x-text="unreadCount + ' Baru'"></span>
                            </div>
                            <button @click="markAllAsRead()" class="text-3xs font-bold text-slate-300 hover:text-white underline">
                                Tandai Semua Dibaca
                            </button>
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
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
                                            <h4 class="font-bold text-xs text-slate-900 truncate" x-text="notif.title"></h4>
                                            <span class="text-3xs text-slate-400 shrink-0 ml-2" x-text="notif.created_at_human"></span>
                                        </div>
                                        <p class="text-2xs text-slate-600 mt-0.5 line-clamp-2" x-text="notif.message"></p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="notifications.length === 0">
                                <div class="p-8 text-center text-slate-400 text-xs font-medium">
                                    Belum ada notifikasi baru.
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- User Profile & Logout Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 text-xs font-bold text-slate-700 hover:text-blue-600 transition">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-2xs">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:inline font-bold">{{ $user->name }}</span>
                            <i class="fa-solid fa-chevron-down text-2xs text-slate-400"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 text-xs font-semibold">
                            <i class="fa-solid fa-user-gear text-slate-400"></i> {{ __('Pengaturan Akun') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-xs font-bold text-rose-600">
                                <i class="fa-solid fa-right-from-bracket"></i> {{ __('Keluar / Logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

            </div>
        </header>

        <!-- Main Content Area Scrollable -->
        <div class="flex-1 overflow-y-auto bg-slate-50/60 flex flex-col justify-between">
            <div>
                <!-- ACTIVE GLOBAL ANNOUNCEMENT BANNERS -->
                @php
                    $activeAnnouncements = \App\Models\SystemAnnouncement::getActiveAnnouncementsForUser(auth()->user());
                @endphp
                @if($activeAnnouncements->count() > 0)
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 space-y-3">
                        @foreach($activeAnnouncements as $ann)
                            <div class="p-4 rounded-2xl border shadow-2xs flex items-start justify-between gap-4 text-xs font-bold
                                {{ $ann->type === 'danger' ? 'bg-rose-50 border-rose-200 text-rose-900' : '' }}
                                {{ $ann->type === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-900' : '' }}
                                {{ $ann->type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : '' }}
                                {{ $ann->type === 'info' ? 'bg-blue-50 border-blue-200 text-blue-900' : '' }}">
                                <div class="flex items-start gap-3">
                                    <div class="text-base mt-0.5">
                                        @if($ann->type === 'danger') 🚨 @elseif($ann->type === 'warning') ⚠️ @elseif($ann->type === 'success') 📢 @else ℹ️ @endif
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-xs uppercase tracking-wide">{{ $ann->title }}</h4>
                                        <p class="font-medium text-xs mt-0.5 opacity-90 leading-relaxed">{{ $ann->content }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Optional Header Slot -->
                @if (isset($header))
                    <header class="bg-white border-b border-slate-200/80 shadow-2xs">
                        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
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
        </div>
    </div>

</div>
