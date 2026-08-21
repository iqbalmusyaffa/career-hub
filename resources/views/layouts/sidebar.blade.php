@php
    $user = auth()->user();
    $roleName = 'Admin';
    $roleBadgeClass = 'bg-blue-600';
    if ($user->hasRole('Super Admin')) {
        $roleName = 'Super Admin';
        $roleBadgeClass = 'bg-red-600';
    } elseif ($user->hasRole('Company Owner')) {
        $roleName = 'Company Owner';
        $roleBadgeClass = 'bg-amber-500';
    } elseif ($user->hasRole('HR')) {
        $roleName = 'HR Manager';
        $roleBadgeClass = 'bg-indigo-600';
    }
@endphp

<div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-50 overflow-hidden font-sans">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"></div>

    <!-- LEFT SIDEBAR -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col justify-between shrink-0 shadow-xl border-r border-slate-800">
        
        <div class="flex flex-col h-full">
            <!-- Brand Logo Header -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800/80 bg-slate-950/40">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-base shadow-md">
                        T
                    </div>
                    <div>
                        <span class="font-black text-lg text-white tracking-tight">TalentFlow</span>
                        <div class="flex items-center gap-1 mt-0.5">
                            <span class="px-2 py-0.2 text-3xs font-black rounded-md text-white uppercase tracking-wider {{ $roleBadgeClass }}">
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
            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-6">

                <!-- GROUP 1: UTAMA -->
                <div class="space-y-1">
                    <div class="px-3 text-3xs font-black uppercase tracking-wider text-slate-400 mb-2">Utama</div>

                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') || request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
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

                    @if(!$user->hasRole('Super Admin'))
                    <a href="{{ route('admin.company-team.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.company-team.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-users-gear text-sm w-5 text-center text-blue-400"></i>
                        <span>Tim HR Perusahaan</span>
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
                    <div class="px-3 text-3xs font-black uppercase tracking-wider text-red-400 mb-2">Super Admin Control</div>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.users.*') ? 'bg-red-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-user-shield text-sm w-5 text-center text-red-400"></i>
                        <span>Kelola Pengguna</span>
                    </a>

                    <a href="{{ route('admin.companies.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.companies.*') ? 'bg-red-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-building-circle-check text-sm w-5 text-center text-amber-400"></i>
                        <span>Kelola Perusahaan</span>
                    </a>

                    <a href="{{ route('admin.settings.smtp.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.settings.smtp.*') ? 'bg-red-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-envelope-gear text-sm w-5 text-center text-emerald-400"></i>
                        <span>Pengaturan SMTP</span>
                    </a>

                    <a href="{{ route('admin.settings.seo.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.settings.seo.*') ? 'bg-red-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-bullhorn text-sm w-5 text-center text-blue-400"></i>
                        <span>Branding & SEO Web</span>
                    </a>

                    <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.audit-logs.*') ? 'bg-red-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-list-check text-sm w-5 text-center text-indigo-400"></i>
                        <span>Audit Logs Activity</span>
                    </a>

                    <a href="{{ route('admin.cancellation-tickets.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.cancellation-tickets.*') ? 'bg-red-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
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

                <!-- Interactive Popover Menu (Appears upwards) -->
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
        <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-4 sm:px-8 shrink-0 shadow-2xs">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-gray-600 hover:bg-gray-100">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-black text-gray-900 hidden sm:block">Panel Kontrol Dashboard Enterprise</h1>
            </div>

            <!-- Top Header Actions: Notification Bell & Profile Dropdown -->
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

                <!-- User Profile & Logout Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 text-xs font-bold text-gray-700 hover:text-blue-600 transition">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-2xs">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:inline">{{ $user->name }}</span>
                            <i class="fa-solid fa-chevron-down text-2xs text-gray-400"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                            <i class="fa-solid fa-user-gear text-gray-400"></i> {{ __('Pengaturan Akun') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-red-600">
                                <i class="fa-solid fa-right-from-bracket"></i> {{ __('Keluar / Logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

            </div>
        </header>

        <!-- Main Content Area Scrollable -->
        <div class="flex-1 overflow-y-auto bg-gray-50 flex flex-col justify-between">
            <div>
                <!-- Optional Header Slot -->
                @if (isset($header))
                    <header class="bg-white border-b border-gray-100 shadow-2xs">
                        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

</div>
