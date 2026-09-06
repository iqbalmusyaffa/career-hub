<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1 font-medium">
                    <span>Super Admin</span>
                    <span>/</span>
                    <span class="text-slate-900 dark:text-white font-semibold">Pengguna</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-slate-900 dark:bg-slate-700 text-white flex items-center justify-center text-xs">
                        <i class="fa-solid fa-users"></i>
                    </span>
                    Manajemen Pengguna Platform
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelola otorisasi akun pengguna, role access, simulasi login, dan audit status keamanan.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.users.export', request()->all()) }}" class="px-3.5 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold shadow-2xs inline-flex items-center gap-2 transition">
                    <i class="fa-solid fa-file-csv text-emerald-600 dark:text-emerald-400 text-sm"></i>
                    <span>Ekspor Data (CSV)</span>
                </a>
                <div class="text-xs font-medium text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-2xs flex items-center gap-2">
                    <span>Total Pengguna:</span>
                    <span class="font-bold text-slate-900 dark:text-white">{{ number_format($users->total()) }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div x-data="userManager()" class="py-6 bg-slate-50/60 dark:bg-slate-900 min-h-screen transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-xl flex items-center gap-3 text-xs font-medium shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-xl flex items-center gap-3 text-xs font-medium shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 dark:text-rose-400 text-base shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Filter & Search Toolbar -->
            <div class="bg-white dark:bg-slate-800 p-4 sm:p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80">
                <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-3 text-xs">
                    <!-- Search -->
                    <div class="space-y-1 sm:col-span-2 md:col-span-2">
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 text-[11px]">Cari Pengguna</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="pl-8 w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="space-y-1">
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 text-[11px]">Role Akses</label>
                        <select name="role" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                            <option value="">Semua Role</option>
                            <option value="Candidate" {{ request('role') == 'Candidate' ? 'selected' : '' }}>Candidate</option>
                            <option value="HR" {{ request('role') == 'HR' ? 'selected' : '' }}>HR Manager</option>
                            <option value="Company Owner" {{ request('role') == 'Company Owner' ? 'selected' : '' }}>Company Owner</option>
                            <option value="Super Admin" {{ request('role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="space-y-1">
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 text-[11px]">Status Akun</label>
                        <select name="status" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif Normal</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
                        </select>
                    </div>

                    <!-- Email Verified -->
                    <div class="space-y-1">
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 text-[11px]">Verifikasi Email</label>
                        <select name="verified" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2">
                            <option value="">Semua</option>
                            <option value="verified" {{ request('verified') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="unverified" {{ request('verified') == 'unverified' ? 'selected' : '' }}>Belum Verifikasi</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3 rounded-xl text-xs transition shadow-xs flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-filter text-[11px]"></i>
                            <span>Filter</span>
                        </button>
                        @if(request()->hasAny(['search', 'role', 'status', 'verified', 'per_page']))
                            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-semibold rounded-xl text-xs transition border border-slate-200 dark:border-slate-600" title="Reset Filter">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Floating Bulk Actions Toolbar -->
            <div x-show="selectedUsers.length > 0" x-cloak 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="bg-slate-900 dark:bg-slate-800 text-white p-3 sm:px-5 rounded-2xl shadow-xl border border-slate-800 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 bg-blue-600 text-white font-bold rounded-lg text-[11px]" x-text="selectedUsers.length + ' dipilih'"></span>
                    <span class="text-slate-300">Terapkan tindakan massal untuk akun terpilih:</span>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <form method="POST" action="{{ route('admin.users.bulk-action') }}" @submit.prevent="submitBulk('activate', 'Aktifkan kembali seluruh akun yang dipilih?')">
                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-[11px]"></i> Aktifkan
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.users.bulk-action') }}" @submit.prevent="submitBulk('verify_email', 'Verifikasi status email seluruh akun yang dipilih?')">
                        <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-envelope-circle-check text-[11px]"></i> Verifikasi Email
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.users.bulk-action') }}" @submit.prevent="submitBulk('suspend', 'PERINGATAN! Tangguhkan / blokir seluruh akun yang dipilih?')">
                        <button type="submit" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-semibold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-ban text-[11px]"></i> Tangguhkan
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.users.bulk-action') }}" @submit.prevent="submitBulk('delete', 'PERINGATAN BAHAYA! Hapus permanen seluruh akun terpilih beserta datanya?')">
                        <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-semibold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-trash text-[11px]"></i> Hapus
                        </button>
                    </form>

                    <button type="button" @click="selectedUsers = []" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white rounded-xl font-medium transition">
                        Batal
                    </button>
                </div>
            </div>

            <!-- Users Table Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-700/80 text-slate-500 dark:text-slate-400 text-[11px] font-semibold uppercase tracking-wider">
                                <th class="py-3.5 px-4 sm:px-5 w-10">
                                    <input type="checkbox" @change="toggleSelectAll($event)" :checked="isAllSelected()" class="rounded border-slate-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </th>
                                <th class="py-3.5 px-4">Pengguna</th>
                                <th class="py-3.5 px-4">Role Akses</th>
                                <th class="py-3.5 px-4">Afiliasi / Profil</th>
                                <th class="py-3.5 px-4">Verifikasi Email</th>
                                <th class="py-3.5 px-4">Status Akun</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Aksi & Kontrol</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-900/40 transition">
                                    <td class="py-3.5 px-4 sm:px-5">
                                        @if($user->id !== auth()->id())
                                            <input type="checkbox" value="{{ $user->id }}" x-model="selectedUsers" class="rounded border-slate-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold flex items-center justify-center text-xs shrink-0 border border-slate-200 dark:border-slate-600">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <button type="button" @click="openDrawer({{ json_encode($user) }}, {{ json_encode($user->roles->pluck('name')) }}, {{ json_encode($user->companyProfile) }}, {{ json_encode($user->candidateProfile) }}, {{ $user->applications->count() }})" class="font-semibold text-slate-900 dark:text-white text-xs hover:text-blue-600 dark:hover:text-blue-400 transition text-left">
                                                    {{ $user->name }}
                                                </button>
                                                <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $user->email }}</div>
                                                <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Daftar: {{ $user->created_at->translatedFormat('d M Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @php
                                            $primaryRole = $user->roles->first()?->name ?? 'Candidate';
                                            $roleBadge = match($primaryRole) {
                                                'Super Admin' => 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800',
                                                'Company Owner' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                                'HR' => 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                                default => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                                            };
                                            $roleLabel = match($primaryRole) {
                                                'HR' => 'HR Manager',
                                                default => $primaryRole,
                                            };
                                        @endphp
                                        <div class="flex items-center gap-1.5">
                                            <span class="inline-block px-2 py-0.5 text-[10px] font-semibold rounded-md border {{ $roleBadge }}">
                                                {{ $roleLabel }}
                                            </span>
                                            @if($user->id !== auth()->id())
                                                <button type="button" @click="openRoleModal({{ $user->id }}, '{{ $user->name }}', '{{ $primaryRole }}')" class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 p-1" title="Ubah Role">
                                                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs">
                                        @if($user->companyProfile)
                                            <div class="font-semibold text-slate-900 dark:text-slate-200">{{ $user->companyProfile->company_name }}</div>
                                            <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $user->companyProfile->industry ?? 'Perusahaan Mitra' }}</div>
                                        @elseif($user->candidateProfile)
                                            <div class="text-slate-700 dark:text-slate-300 font-medium">{{ $user->candidateProfile->headline ?? 'Pencari Kerja' }}</div>
                                            @if($user->applications->count() > 0)
                                                <div class="text-[10px] text-blue-600 dark:text-blue-400 font-medium">{{ $user->applications->count() }} berkas lamaran</div>
                                            @endif
                                        @else
                                            <span class="text-slate-400 dark:text-slate-500">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <form method="POST" action="{{ route('admin.users.toggle-email-verification', $user->id) }}">
                                            @csrf
                                            @if($user->email_verified_at)
                                                <button type="submit" title="Klik untuk mencabut status verifikasi email" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 hover:bg-blue-100 transition">
                                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                                    Terverifikasi
                                                </button>
                                            @else
                                                <button type="submit" title="Klik untuk verifikasi email manual" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                                                    <i class="fa-solid fa-clock text-[10px]"></i>
                                                    Belum Verifikasi
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($user->is_suspended)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800" title="{{ $user->status_reason ?: 'Ditangguhkan oleh Super Admin' }}">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Ditangguhkan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif Normal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 sm:px-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- Detail Drawer Trigger -->
                                            <button type="button" @click="openDrawer({{ json_encode($user) }}, {{ json_encode($user->roles->pluck('name')) }}, {{ json_encode($user->companyProfile) }}, {{ json_encode($user->candidateProfile) }}, {{ $user->applications->count() }})" class="p-1.5 bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-lg text-xs transition shadow-2xs" title="Lihat Profil Lengkap">
                                                <i class="fa-solid fa-eye text-[11px]"></i>
                                            </button>

                                            @if($user->id !== auth()->id())
                                                <!-- Impersonate Button (Login As) -->
                                                <form method="POST" action="{{ route('admin.users.impersonate', $user->id) }}" onsubmit="return confirm('Masuk dan bertindak sebagai {{ $user->name }} (Mode Simulasi Super Admin)?');">
                                                    @csrf
                                                    <button type="submit" class="p-1.5 bg-slate-50 dark:bg-slate-900 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-600 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-lg text-xs transition shadow-2xs" title="Login Sebagai Pengguna Ini (Simulasi)">
                                                        <i class="fa-solid fa-right-to-bracket text-[11px] text-indigo-500"></i>
                                                    </button>
                                                </form>

                                                <!-- Send Password Reset -->
                                                <form method="POST" action="{{ route('admin.users.send-password-reset', $user->id) }}" onsubmit="return confirm('Kirimkan email tautan reset kata sandi ke {{ $user->email }}?');">
                                                    @csrf
                                                    <button type="submit" class="p-1.5 bg-slate-50 dark:bg-slate-900 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-lg text-xs transition shadow-2xs" title="Kirim Tautan Reset Sandi via Email">
                                                        <i class="fa-solid fa-key text-[11px] text-blue-500"></i>
                                                    </button>
                                                </form>

                                                <!-- Toggle Suspend Button (Opens Modal with Reason) -->
                                                @if($user->is_suspended)
                                                    <form method="POST" action="{{ route('admin.users.toggle-suspend', $user->id) }}" onsubmit="return confirm('Buka penangguhan dan aktifkan kembali akun {{ $user->name }}?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="px-2 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-lg text-xs font-semibold transition shadow-2xs flex items-center gap-1" title="Buka Penangguhan">
                                                            <i class="fa-solid fa-lock-open text-[11px]"></i>
                                                            <span>Buka</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" @click="openSuspendModal({{ $user->id }}, '{{ $user->name }}')" class="p-1.5 bg-slate-50 dark:bg-slate-900 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-600 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 rounded-lg text-xs transition shadow-2xs" title="Tangguhkan Akun">
                                                        <i class="fa-solid fa-ban text-[11px] text-amber-500"></i>
                                                    </button>
                                                @endif

                                                <!-- Delete User Button -->
                                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Hapus permanen akun {{ $user->name }} beserta seluruh data terkait?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 bg-slate-50 dark:bg-slate-900 hover:bg-rose-600 hover:text-white dark:hover:bg-rose-600 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:border-rose-600 rounded-lg text-xs transition shadow-2xs" title="Hapus Permanen">
                                                        <i class="fa-solid fa-trash text-[11px]"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium italic">Akun Anda</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 px-4 text-center text-slate-400 dark:text-slate-500 font-medium">
                                        <i class="fa-solid fa-users-slash text-2xl text-slate-300 dark:text-slate-600 mb-2 block"></i>
                                        Tidak ada pengguna ditemukan dengan kriteria filter yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-900/40">
                    {{ $users->links('vendor.pagination.tailwind') }}
                </div>
            </div>

            <!-- MODAL: QUICK ROLE SWITCHER -->
            <div x-show="roleModal.open" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
                <div @click.away="roleModal.open = false" class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-200 dark:border-slate-800 text-left">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h4 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-user-shield text-blue-600 dark:text-blue-400"></i>
                            Ubah Role Pengguna
                        </h4>
                        <button @click="roleModal.open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <form :action="'/admin/users/' + roleModal.userId + '/update-role'" method="POST" class="space-y-4 text-xs">
                        @csrf
                        @method('PATCH')

                        <div>
                            <p class="text-slate-600 dark:text-slate-400 mb-2">
                                Tetapkan hak akses baru untuk pengguna <strong class="text-slate-900 dark:text-white" x-text="roleModal.userName"></strong>:
                            </p>

                            <div class="space-y-2">
                                <label class="flex items-center gap-3 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <input type="radio" name="role" value="Candidate" x-model="roleModal.selectedRole" class="text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <div class="font-semibold text-slate-900 dark:text-white">Candidate (Pelamar)</div>
                                        <div class="text-[11px] text-slate-500">Dapat melamar pekerjaan, upload resume, dan logbook magang.</div>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <input type="radio" name="role" value="HR" x-model="roleModal.selectedRole" class="text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <div class="font-semibold text-slate-900 dark:text-white">HR Manager</div>
                                        <div class="text-[11px] text-slate-500">Dapat mengelola lowongan, seleksi pelamar, dan jadwal interview.</div>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <input type="radio" name="role" value="Company Owner" x-model="roleModal.selectedRole" class="text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <div class="font-semibold text-slate-900 dark:text-white">Company Owner</div>
                                        <div class="text-[11px] text-slate-500">Kontrol penuh atas legalitas perusahaan, tim HR, dan billing.</div>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <input type="radio" name="role" value="Super Admin" x-model="roleModal.selectedRole" class="text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <div class="font-semibold text-rose-600 dark:text-rose-400">Super Admin</div>
                                        <div class="text-[11px] text-slate-500">Akses master control menyeluruh ke seluruh sistem platform.</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="roleModal.open = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold rounded-xl text-xs transition border border-slate-200 dark:border-slate-700">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs shadow-xs transition">
                                Simpan Perubahan Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL: SUSPENSION REASON -->
            <div x-show="suspendModal.open" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
                <div @click.away="suspendModal.open = false" class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-200 dark:border-slate-800 text-left">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h4 class="font-bold text-sm text-amber-600 dark:text-amber-400 flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            Tangguhkan / Blokir Akun
                        </h4>
                        <button @click="suspendModal.open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <form :action="'/admin/users/' + suspendModal.userId + '/toggle-suspend'" method="POST" class="space-y-4 text-xs">
                        @csrf
                        @method('PATCH')

                        <p class="text-slate-600 dark:text-slate-400">
                            Anda akan menangguhkan akses akun <strong class="text-slate-900 dark:text-white" x-text="suspendModal.userName"></strong>. Pengguna tidak akan dapat login ke platform sampai status dibuka kembali.
                        </p>

                        <div class="space-y-1">
                            <label class="block font-semibold text-slate-700 dark:text-slate-300">Alasan Penangguhan</label>
                            <textarea name="reason" rows="3" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-slate-900 dark:text-slate-100 p-2.5" placeholder="Contoh: Spam lowongan fiktif / Pelanggaran etika kandidat..." required></textarea>
                        </div>

                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="suspendModal.open = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold rounded-xl text-xs transition border border-slate-200 dark:border-slate-700">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl text-xs shadow-xs transition">
                                Konfirmasi Penangguhan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SLIDE-OVER DRAWER: USER PROFILE DETAILS -->
            <div x-show="drawer.open" x-cloak class="fixed inset-0 z-50 overflow-hidden">
                <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" @click="drawer.open = false"></div>

                <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                    <div class="w-screen max-w-md bg-white dark:bg-slate-900 border-l border-slate-200 dark:border-slate-800 shadow-2xl p-6 flex flex-col justify-between overflow-y-auto">
                        
                        <div class="space-y-6">
                            <!-- Drawer Header -->
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                                <h3 class="font-bold text-sm text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                                    <i class="fa-solid fa-id-card text-blue-600 dark:text-blue-400"></i>
                                    Detail Informasi Pengguna
                                </h3>
                                <button @click="drawer.open = false" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                    <i class="fa-solid fa-xmark text-base"></i>
                                </button>
                            </div>

                            <!-- User Overview Card -->
                            <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200/80 dark:border-slate-800">
                                <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-xs" x-text="drawer.user ? drawer.user.name.charAt(0).toUpperCase() : ''"></div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 dark:text-white text-sm truncate" x-text="drawer.user?.name"></h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate" x-text="drawer.user?.email"></p>
                                    <div class="mt-1 flex items-center gap-1.5 flex-wrap">
                                        <template x-for="r in drawer.roles" :key="r">
                                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800" x-text="r"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Attributes List -->
                            <div class="space-y-3 text-xs">
                                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium">Status Akun</span>
                                    <span class="font-semibold" :class="drawer.user?.is_suspended ? 'text-rose-600' : 'text-emerald-600'" x-text="drawer.user?.is_suspended ? 'Ditangguhkan' : 'Aktif Normal'"></span>
                                </div>

                                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium">Verifikasi Email</span>
                                    <span class="font-semibold" :class="drawer.user?.email_verified_at ? 'text-blue-600' : 'text-slate-500'" x-text="drawer.user?.email_verified_at ? 'Terverifikasi' : 'Belum Diverifikasi'"></span>
                                </div>

                                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium">Tanggal Pendaftaran</span>
                                    <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="drawer.user?.created_at ? new Date(drawer.user.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) : '-'"></span>
                                </div>

                                <template x-if="drawer.company">
                                    <div>
                                        <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                                            <span class="text-slate-500 dark:text-slate-400 font-medium">Perusahaan Mitra</span>
                                            <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="drawer.company.company_name"></span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                                            <span class="text-slate-500 dark:text-slate-400 font-medium">Industri / Sektor</span>
                                            <span class="text-slate-800 dark:text-slate-200" x-text="drawer.company.industry || '-'"></span>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="drawer.candidate">
                                    <div>
                                        <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                                            <span class="text-slate-500 dark:text-slate-400 font-medium">Headline Kandidat</span>
                                            <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="drawer.candidate.headline || 'Pencari Kerja'"></span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                                            <span class="text-slate-500 dark:text-slate-400 font-medium">Total Lamaran Masuk</span>
                                            <span class="font-bold text-blue-600" x-text="drawer.appCount + ' Lamaran'"></span>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="drawer.user?.status_reason">
                                    <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800">
                                        <span class="font-semibold text-amber-800 dark:text-amber-300 block mb-0.5">Catatan Penangguhan:</span>
                                        <p class="text-amber-700 dark:text-amber-400" x-text="drawer.user.status_reason"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Drawer Footer Actions -->
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-2">
                            <template x-if="drawer.user && drawer.user.id !== {{ auth()->id() }}">
                                <form method="POST" :action="'/admin/users/' + drawer.user.id + '/impersonate'" onsubmit="return confirm('Masuk sebagai pengguna ini?');">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-right-to-bracket"></i>
                                        <span>Login Sebagai Pengguna Ini</span>
                                    </button>
                                </form>
                            </template>

                            <button type="button" @click="drawer.open = false" class="w-full py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs rounded-xl transition">
                                Tutup Panel
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Alpine.js User Management Controller Script -->
    <script>
        function userManager() {
            return {
                selectedUsers: [],
                allIds: @json($users->pluck('id')),
                currentUserId: {{ auth()->id() }},
                
                roleModal: {
                    open: false,
                    userId: null,
                    userName: '',
                    selectedRole: 'Candidate'
                },

                suspendModal: {
                    open: false,
                    userId: null,
                    userName: ''
                },

                drawer: {
                    open: false,
                    user: null,
                    roles: [],
                    company: null,
                    candidate: null,
                    appCount: 0
                },

                isAllSelected() {
                    const validIds = this.allIds.filter(id => id !== this.currentUserId);
                    return validIds.length > 0 && this.selectedUsers.length === validIds.length;
                },

                toggleSelectAll(e) {
                    if (e.target.checked) {
                        this.selectedUsers = this.allIds.filter(id => id !== this.currentUserId);
                    } else {
                        this.selectedUsers = [];
                    }
                },

                openRoleModal(userId, name, currentRole) {
                    this.roleModal.userId = userId;
                    this.roleModal.userName = name;
                    this.roleModal.selectedRole = currentRole;
                    this.roleModal.open = true;
                },

                openSuspendModal(userId, name) {
                    this.suspendModal.userId = userId;
                    this.suspendModal.userName = name;
                    this.suspendModal.open = true;
                },

                openDrawer(user, roles, company, candidate, appCount) {
                    this.drawer.user = user;
                    this.drawer.roles = roles;
                    this.drawer.company = company;
                    this.drawer.candidate = candidate;
                    this.drawer.appCount = appCount;
                    this.drawer.open = true;
                },

                submitBulk(action, confirmMsg) {
                    if (!confirm(confirmMsg)) return;

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.users.bulk-action") }}';

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);

                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = action;
                    form.appendChild(actionInput);

                    this.selectedUsers.forEach(id => {
                        const idInput = document.createElement('input');
                        idInput.type = 'hidden';
                        idInput.name = 'user_ids[]';
                        idInput.value = id;
                        form.appendChild(idInput);
                    });

                    document.body.appendChild(form);
                    form.submit();
                }
            };
        }
    </script>
</x-app-layout>
