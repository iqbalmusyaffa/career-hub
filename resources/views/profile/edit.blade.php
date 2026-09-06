<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1 font-medium">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-900 dark:text-white font-semibold">Pengaturan Akun</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-slate-900 dark:bg-slate-700 text-white flex items-center justify-center text-xs">
                        <i class="fa-solid fa-user-gear"></i>
                    </span>
                    Pengaturan Profil & Keamanan
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelola identitas akun, kredensial login, dan preferensi keamanan Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50/60 dark:bg-slate-900 min-h-screen transition-colors">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- User Identity Summary Banner -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 sm:p-6 shadow-2xs border border-slate-200/80 dark:border-slate-700/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-bold text-xl flex items-center justify-center shadow-xs shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">{{ $user->name }}</h3>
                            @php
                                $primaryRole = $user->roles->first()?->name ?? 'User';
                                $badgeStyle = match($primaryRole) {
                                    'Super Admin' => 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800',
                                    'Company Owner' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                    'HR' => 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                    default => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                                };
                            @endphp
                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded-md border {{ $badgeStyle }}">
                                {{ $primaryRole === 'HR' ? 'HR Manager' : $primaryRole }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-normal flex flex-wrap items-center gap-2">
                            <span><i class="fa-solid fa-envelope text-slate-400 mr-1 text-[11px]"></i> {{ $user->email }}</span>
                            <span>&bull;</span>
                            <span><i class="fa-solid fa-calendar text-slate-400 mr-1 text-[11px]"></i> Bergabung {{ $user->created_at->translatedFormat('d F Y') }}</span>
                        </p>
                    </div>
                </div>

                @if(auth()->user()->hasRole('Candidate'))
                    <a href="{{ route('profile.candidate.details.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-xs transition shadow-xs">
                        <i class="fa-solid fa-file-lines text-xs"></i> Kelola Resume & CV
                    </a>
                @endif
            </div>

            <!-- Card 1: Informasi Akun -->
            <div class="bg-white dark:bg-slate-800 p-6 sm:p-7 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Card 2: Keamanan & Kata Sandi -->
            <div class="bg-white dark:bg-slate-800 p-6 sm:p-7 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80">
                @include('profile.partials.update-password-form')
            </div>

            <!-- Card 3: Hapus Akun -->
            <div class="bg-white dark:bg-slate-800 p-6 sm:p-7 rounded-2xl shadow-2xs border border-rose-200/60 dark:border-rose-950/60">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>
