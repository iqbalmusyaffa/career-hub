@php
    $user = auth()->user();
    $roleName = 'Pencari Kerja';
    $roleBadgeClass = 'bg-blue-500/10 text-blue-400 border-blue-500/20';
    $dashboardRoute = route('dashboard');
    $portalTitle = 'Portal Karir & Magang';

    if ($user->hasRole('Super Admin')) {
        $roleName = 'Super Admin';
        $roleBadgeClass = 'bg-rose-500/10 text-rose-400 border-rose-500/20';
        $dashboardRoute = route('admin.dashboard');
        $portalTitle = 'Panel Super Admin';
    } elseif ($user->hasRole('Company Owner')) {
        $roleName = 'Company Owner';
        $roleBadgeClass = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
        $dashboardRoute = route('admin.dashboard');
        $portalTitle = 'Panel Pemilik Perusahaan';
    } elseif ($user->hasRole('HR')) {
        $roleName = 'HR Manager';
        $roleBadgeClass = 'bg-blue-500/10 text-blue-400 border-blue-500/20';
        $dashboardRoute = route('admin.dashboard');
        $portalTitle = 'Panel Rekrutmen HR';
    } elseif ($user->hasRole('Mentor')) {
        $roleName = 'Mentor Magang';
        $roleBadgeClass = 'bg-purple-500/10 text-purple-400 border-purple-500/20';
        $dashboardRoute = route('mentor.dashboard');
        $portalTitle = 'Panel Mentor Pembimbing';
    } elseif ($user->internshipPeriod) {
        $roleName = 'Peserta Magang';
        $roleBadgeClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
        $dashboardRoute = route('dashboard');
        $portalTitle = 'Portal Peserta Magang';
    }
@endphp

<div x-data="{ sidebarOpen: false }" class="flex h-screen bg-slate-50 dark:bg-slate-900 overflow-hidden font-sans">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs lg:hidden" style="display: none;"></div>

    <!-- LEFT SIDEBAR -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col justify-between shrink-0 border-r border-slate-800">
        
        <div class="flex flex-col h-full">
            <!-- Brand Logo Header -->
            <div class="py-4 px-5 border-b border-slate-800 bg-slate-950/40 flex items-center justify-between">
                <a href="{{ $dashboardRoute }}" class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-600 text-white font-bold text-sm flex items-center justify-center shadow-xs shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="font-bold text-sm text-white tracking-tight leading-none">TalentFlow</span>
                        <div class="mt-1">
                            <span class="inline-block px-1.5 py-0.5 text-[9px] font-semibold rounded uppercase tracking-wider border leading-none {{ $roleBadgeClass }}">
                                {{ $roleName }}
                            </span>
                        </div>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto py-5 px-3.5 space-y-6 scrollbar-thin">

                @if($user->hasRole('Super Admin') || $user->hasRole('HR') || $user->hasRole('Company Owner'))
                    <!-- ============================================== -->
                    <!-- ROLE: ADMIN / HR / COMPANY OWNER               -->
                    <!-- ============================================== -->

                    <!-- GROUP 1: UTAMA -->
                    <div class="space-y-1">
                        <div class="px-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Utama</div>

                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-chart-pie text-xs w-4 text-center"></i>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('admin.jobs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.jobs.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-briefcase text-xs w-4 text-center"></i>
                            <span>Lowongan Kerja</span>
                        </a>

                        <a href="{{ route('admin.applications.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.applications.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-users text-xs w-4 text-center"></i>
                            <span>Data Pelamar</span>
                        </a>
                    </div>

                    <!-- GROUP 2: REKRUTMEN & TIM -->
                    <div class="space-y-1">
                        <div class="px-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Rekrutmen & Tim</div>

                        <a href="{{ route('admin.calendar.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.calendar.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-calendar-days text-xs w-4 text-center"></i>
                            <span>Kalender Rekrutmen</span>
                        </a>

                        <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.analytics.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-chart-line text-xs w-4 text-center"></i>
                            <span>Visual Analytics</span>
                        </a>

                        <a href="{{ route('admin.email-templates.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.email-templates.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-envelope-open-text text-xs w-4 text-center"></i>
                            <span>Template Email HR</span>
                        </a>

                        <a href="{{ route('admin.headcount-budgets.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.headcount-budgets.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-coins text-xs w-4 text-center"></i>
                            <span>Headcount Planning</span>
                        </a>

                        <a href="{{ route('admin.reports.builder.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.reports.builder.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-chart-pie text-xs w-4 text-center"></i>
                            <span>Custom Report Builder</span>
                        </a>

                        <a href="{{ route('admin.system-flow.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.system-flow.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-diagram-project text-xs w-4 text-center"></i>
                            <span>Diagram Flow & ERD</span>
                        </a>

                        <a href="{{ route('mentor.curriculums.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('mentor.curriculums.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-book-open text-xs w-4 text-center"></i>
                            <span>Kurikulum & Materi</span>
                        </a>

                        <a href="{{ route('mentor.settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('mentor.settings.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-layer-group text-xs w-4 text-center"></i>
                            <span>Master Batch & Libur</span>
                        </a>

                        <a href="{{ route('admin.internship-stipends.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.internship-stipends.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-money-bill-wave text-xs w-4 text-center"></i>
                            <span>Uang Saku Magang</span>
                        </a>

                        @if(!$user->hasRole('Super Admin'))
                        <a href="{{ route('admin.company-team.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.company-team.index') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-users-gear text-xs w-4 text-center"></i>
                            <span>Tim HR Perusahaan</span>
                        </a>

                        <a href="{{ route('admin.company-team.audit-logs') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.company-team.audit-logs') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-clock-rotate-left text-xs w-4 text-center"></i>
                            <span>Log Aktivitas Tim HR</span>
                        </a>

                        <a href="{{ route('admin.company.profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.company.profile.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-building-user text-xs w-4 text-center"></i>
                            <span>Profil Perusahaan</span>
                        </a>
                        @endif
                    </div>

                    <!-- GROUP 3: SUPER ADMIN CONTROL -->
                    @if($user->hasRole('Super Admin'))
                    <div class="space-y-1 pt-2 border-t border-slate-800">
                        <div class="px-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Super Admin Control</div>

                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.users.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-user-shield text-xs w-4 text-center"></i>
                            <span>Kelola Pengguna</span>
                        </a>

                        <a href="{{ route('admin.companies.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.companies.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-building-circle-check text-xs w-4 text-center"></i>
                            <span>Kelola Perusahaan</span>
                        </a>

                        <a href="{{ route('admin.settings.smtp.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.settings.smtp.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-sliders text-xs w-4 text-center"></i>
                            <span>Pengaturan SMTP</span>
                        </a>

                        <a href="{{ route('admin.settings.seo.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.settings.seo.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-bullhorn text-xs w-4 text-center"></i>
                            <span>Branding & SEO Web</span>
                        </a>

                        <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.audit-logs.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-list-check text-xs w-4 text-center"></i>
                            <span>Audit Logs Activity</span>
                        </a>

                        <a href="{{ route('admin.announcements.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.announcements.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-bullhorn text-xs w-4 text-center"></i>
                            <span>Broadcast Center</span>
                        </a>

                        <a href="{{ route('admin.blacklists.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.blacklists.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-user-shield text-xs w-4 text-center"></i>
                            <span>Anti-Fraud Blacklist</span>
                        </a>

                        @php
                            $pendingRedFlagCount = \App\Models\CompanyReport::where('status', 'pending')->count();
                        @endphp
                        <a href="{{ route('admin.company-reports.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.company-reports.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-flag text-xs w-4 text-center text-rose-400"></i>
                                <span>Laporan Red Flag</span>
                            </div>
                            @if($pendingRedFlagCount > 0)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white shadow-xs animate-pulse">
                                    {{ $pendingRedFlagCount }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('admin.internship-unlocks.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.internship-unlocks.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-key text-xs w-4 text-center"></i>
                            <span>Pusat Buka Kunci</span>
                        </a>

                        <a href="{{ route('admin.attendance-settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.attendance-settings.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-satellite text-xs w-4 text-center"></i>
                            <span>Pengaturan GPS</span>
                        </a>

                        <a href="{{ route('admin.internship-monitor.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.internship-monitor.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-chart-line text-xs w-4 text-center"></i>
                            <span>Monitoring Magang</span>
                        </a>

                        <a href="{{ route('admin.certificates.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.certificates.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-award text-xs w-4 text-center"></i>
                            <span>Master Sertifikat</span>
                        </a>

                        <a href="{{ route('admin.cancellation-tickets.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.cancellation-tickets.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-shield-halved text-xs w-4 text-center"></i>
                            <span>Aduan Pembatalan</span>
                        </a>
                    </div>
                    @endif

                @elseif($user->hasRole('Mentor'))
                    <!-- ============================================== -->
                    <!-- ROLE: MENTOR PEMBIMBING                        -->
                    <!-- ============================================== -->

                    <div class="space-y-1">
                        <div class="px-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Utama</div>

                        <a href="{{ route('mentor.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('mentor.dashboard') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-gauge-high text-xs w-4 text-center"></i>
                            <span>Dashboard Mentor</span>
                        </a>
                    </div>

                    <div class="space-y-1">
                        <div class="px-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Bimbingan Magang</div>

                        <a href="{{ route('mentor.logbooks.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('mentor.logbooks.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-calendar-check text-xs w-4 text-center"></i>
                            <span>ACC Presensi & Logbook</span>
                        </a>

                        <a href="{{ route('mentor.stipends.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('mentor.stipends.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-hand-holding-dollar text-xs w-4 text-center"></i>
                            <span>Pengajuan Uang Saku</span>
                        </a>

                        <a href="{{ route('mentor.unlock-requests.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('mentor.unlock-requests.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-key text-xs w-4 text-center"></i>
                            <span>Pengajuan Buka Kunci</span>
                        </a>

                        <a href="{{ route('mentor.settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('mentor.settings.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-layer-group text-xs w-4 text-center"></i>
                            <span>Pengaturan Batch & Libur</span>
                        </a>

                        <a href="{{ route('mentor.curriculums.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('mentor.curriculums.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-book-open text-xs w-4 text-center"></i>
                            <span>Kurikulum & Materi</span>
                        </a>
                    </div>

                @else
                    <!-- ============================================== -->
                    <!-- ROLE: CANDIDATE / PESERTA MAGANG               -->
                    <!-- ============================================== -->

                    <!-- GROUP 1: UTAMA -->
                    <div class="space-y-1">
                        <div class="px-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Utama</div>

                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-gauge-high text-xs w-4 text-center"></i>
                            <span>{{ $user->internshipPeriod ? 'Dashboard Magang' : 'Dashboard Karir' }}</span>
                        </a>
                    </div>

                    <!-- GROUP 2: EKSPLORASI KARIR -->
                    <div class="space-y-1">
                        <div class="px-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Eksplorasi Karir</div>

                        <a href="{{ route('jobs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('jobs.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-magnifying-glass text-xs w-4 text-center"></i>
                            <span>Cari Lowongan</span>
                        </a>

                        <a href="{{ route('companies.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('companies.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-building text-xs w-4 text-center"></i>
                            <span>Perusahaan</span>
                        </a>

                        <a href="{{ route('saved-jobs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('saved-jobs.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-bookmark text-xs w-4 text-center"></i>
                            <span>Lowongan Tersimpan</span>
                        </a>
                    </div>

                    <!-- GROUP 3: PROGRAM MAGANG -->
                    <div class="space-y-1">
                        <div class="px-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Program Magang</div>

                        <a href="{{ route('candidate.logbook.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('candidate.logbook.index') || request()->routeIs('candidate.logbook.show') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-calendar-check text-xs w-4 text-center"></i>
                            <span>Presensi & Logbook</span>
                        </a>

                        <a href="{{ route('candidate.logbook.progress') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('candidate.logbook.progress') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-chart-line text-xs w-4 text-center"></i>
                            <span>Perkembangan Magang</span>
                        </a>

                        <a href="{{ route('candidate.logbook.evaluation') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('candidate.logbook.evaluation') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-award text-xs w-4 text-center"></i>
                            <span>Nilai & Evaluasi</span>
                        </a>

                        <a href="{{ route('candidate.resignations.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('candidate.resignations.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-arrow-right-from-bracket text-xs w-4 text-center text-rose-400"></i>
                            <span>Pengunduran Diri</span>
                        </a>
                    </div>

                    <!-- GROUP 4: FITUR & TOOLS -->
                    <div class="space-y-1">
                        <div class="px-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Tools & Dokumen</div>

                        <a href="{{ route('candidate.cv-builder') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('candidate.cv-builder') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-file-signature text-xs w-4 text-center"></i>
                            <span>CV Builder</span>
                        </a>

                        <a href="{{ route('salary-benchmark.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('salary-benchmark.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-coins text-xs w-4 text-center"></i>
                            <span>Estimasi Gaji</span>
                        </a>

                        <a href="{{ route('profile.candidate.details.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('profile.candidate.details.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-user-pen text-xs w-4 text-center"></i>
                            <span>Edit Profil & CV</span>
                        </a>

                        <a href="{{ route('pages.guide.candidate') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('pages.guide.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-book-open text-xs w-4 text-center text-blue-400"></i>
                            <span>Panduan Pengguna</span>
                        </a>
                    </div>

                @endif

            </div>

            <!-- Footer User Profile Card inside Sidebar -->
            <div x-data="{ userMenuOpen: false }" class="relative p-3 border-t border-slate-800 bg-slate-950/40">
                <button @click="userMenuOpen = !userMenuOpen" class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-slate-800/80 transition text-left group focus:outline-none">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-xs text-white truncate group-hover:text-blue-400 transition">{{ $user->name }}</div>
                            <div class="text-[11px] text-slate-400 truncate">{{ $user->email }}</div>
                        </div>
                    </div>
                    <i class="fa-solid fa-ellipsis-vertical text-slate-500 group-hover:text-slate-300 ml-2 transition text-xs"></i>
                </button>

                <!-- Popover Menu -->
                <div x-show="userMenuOpen" @click.away="userMenuOpen = false" x-transition.origin.bottom.left class="absolute bottom-full left-3 mb-2 w-56 bg-slate-800 border border-slate-700 rounded-xl shadow-xl py-1.5 z-50 divide-y divide-slate-700/60" style="display: none;">
                    <div class="px-3.5 py-1.5 text-[10px] font-semibold uppercase text-slate-400">
                        Akun: <span class="text-white font-bold">{{ $roleName }}</span>
                    </div>

                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3.5 py-1.5 text-xs text-slate-200 hover:bg-slate-700 hover:text-white transition">
                            <i class="fa-solid fa-user-gear text-slate-400 w-4 text-center"></i>
                            <span>Pengaturan Akun</span>
                        </a>

                        @if($user->hasRole('Candidate'))
                            <a href="{{ route('profile.candidate.details.edit') }}" class="flex items-center gap-2.5 px-3.5 py-1.5 text-xs text-slate-200 hover:bg-slate-700 hover:text-white transition">
                                <i class="fa-solid fa-id-card text-slate-400 w-4 text-center"></i>
                                <span>Profil & CV</span>
                            </a>
                        @endif

                        @if($user->hasRole('Company Owner') || $user->hasRole('HR'))
                            <a href="{{ route('admin.company.profile.edit') }}" class="flex items-center gap-2.5 px-3.5 py-1.5 text-xs text-slate-200 hover:bg-slate-700 hover:text-white transition">
                                <i class="fa-solid fa-building text-slate-400 w-4 text-center"></i>
                                <span>Profil Perusahaan</span>
                            </a>
                        @endif
                    </div>

                    <div class="pt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2.5 px-3.5 py-1.5 text-xs font-semibold text-rose-400 hover:bg-rose-950/40 hover:text-rose-300 transition">
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
        <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 sm:px-6 shrink-0 transition-colors">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>
                <h1 class="text-sm font-bold text-slate-900 dark:text-white hidden sm:block">{{ $portalTitle }}</h1>
            </div>

            <!-- Top Header Actions -->
            <div class="flex items-center gap-2.5">

                <!-- Dark Mode / Light Mode Toggle Button -->
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
                    <button @click="toggleTheme()" type="button" 
                        class="p-2 rounded-xl text-slate-500 hover:text-amber-500 dark:text-slate-400 dark:hover:text-amber-400 focus:outline-none transition-colors bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center w-9 h-9 shadow-2xs cursor-pointer" 
                        :title="darkMode ? 'Beralih ke Light Mode' : 'Beralih ke Dark Mode'">
                        <template x-if="darkMode">
                            <i class="fa-solid fa-sun text-amber-400 text-xs"></i>
                        </template>
                        <template x-if="!darkMode">
                            <i class="fa-solid fa-moon text-slate-600 text-xs"></i>
                        </template>
                    </button>
                </div>

                <!-- Real-time Notification Bell Component -->
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

                    <button @click="open = !open" class="relative p-2 text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none transition rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center w-9 h-9 shadow-2xs cursor-pointer">
                        <i class="fa-solid fa-bell text-xs"></i>
                        <template x-if="unreadCount > 0">
                            <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-600 text-[10px] font-bold text-white ring-2 ring-white dark:ring-slate-800" x-text="unreadCount"></span>
                        </template>
                    </button>

                    <!-- Dropdown Drawer -->
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 z-50 overflow-hidden" style="display: none;">
                        <div class="p-3.5 bg-slate-900 text-white flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-bell text-blue-400 text-xs"></i>
                                <span class="font-semibold text-xs">Notifikasi Platform</span>
                                <span x-show="unreadCount > 0" class="px-2 py-0.5 bg-blue-600 text-white rounded-md text-[10px] font-bold" x-text="unreadCount + ' Baru'"></span>
                            </div>
                            <button @click="markAllAsRead()" class="text-[11px] font-medium text-slate-300 hover:text-white underline cursor-pointer">
                                Tandai Dibaca
                            </button>
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700">
                            <template x-for="notif in notifications" :key="notif.id">
                                <div @click="markAsRead(notif.id, notif.link)" class="p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition cursor-pointer flex gap-3 items-start" :class="{'bg-blue-50/40 dark:bg-blue-950/20': !notif.is_read}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 text-xs mt-0.5"
                                         :class="{
                                            'bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-300': notif.type === 'info',
                                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300': notif.type === 'success',
                                            'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300': notif.type === 'warning'
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

                <!-- User Profile & Logout Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 transition cursor-pointer">
                            <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-xs">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:inline font-semibold">{{ $user->name }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 text-xs">
                            <i class="fa-solid fa-user-gear text-slate-400 w-4"></i> {{ __('Pengaturan Akun') }}
                        </x-dropdown-link>

                        @if($user->hasRole('Candidate'))
                            <x-dropdown-link :href="route('profile.candidate.details.edit')" class="flex items-center gap-2 text-xs">
                                <i class="fa-solid fa-id-card text-slate-400 w-4"></i> {{ __('Edit Profil & CV') }}
                            </x-dropdown-link>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30">
                                <i class="fa-solid fa-arrow-right-from-bracket text-rose-500 w-4"></i> {{ __('Keluar / Logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

            </div>
        </header>

        <!-- Main Content Area Scrollable -->
        <div class="flex-1 overflow-y-auto bg-slate-50/60 dark:bg-slate-900 flex flex-col justify-between transition-colors">
            <div>
                <!-- Optional Header Slot -->
                @if (isset($header))
                    <header class="bg-white dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700/80 transition-colors">
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
        </div>
    </div>

</div>
