<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-user-shield text-rose-600"></i> Manajemen Pengguna Platform (Super Admin)
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola otorisasi akun pengguna, role access, dan blokir status (Suspension).</p>
            </div>
            <div class="text-xs font-bold text-slate-600 bg-white px-4 py-2.5 rounded-2xl border border-slate-200 shadow-2xs">
                Total Pengguna: <span class="font-black text-blue-600 text-sm">{{ $users->total() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Filter & Search Bar -->
            <div class="bg-white p-6 rounded-3xl shadow-2xs border border-slate-200/80">
                <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-3xs tracking-wider">Cari Pengguna</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-medium">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-3xs tracking-wider">Filter Role</label>
                        <select name="role" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-semibold">
                            <option value="">Semua Role</option>
                            <option value="Candidate" {{ request('role') == 'Candidate' ? 'selected' : '' }}>Candidate (Pelamar)</option>
                            <option value="HR" {{ request('role') == 'HR' ? 'selected' : '' }}>HR Manager</option>
                            <option value="Company Owner" {{ request('role') == 'Company Owner' ? 'selected' : '' }}>Company Owner</option>
                            <option value="Super Admin" {{ request('role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-3xs tracking-wider">Filter Status</label>
                        <select name="status" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-semibold">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif Normal</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Diblokir / Suspended</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-3xs tracking-wider">Baris per Halaman</label>
                        <select name="per_page" onchange="this.form.submit()" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris per Halaman</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris per Halaman</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris per Halaman</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris per Halaman</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-extrabold py-2.5 px-4 rounded-xl text-xs transition border border-slate-900">
                            <i class="fa-solid fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-4 rounded-xl text-xs transition text-center border border-slate-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-600 text-3xs font-extrabold uppercase tracking-wider">
                                    <th class="p-4">Pengguna</th>
                                    <th class="p-4">Role Akses</th>
                                    <th class="p-4">Perusahaan / Info</th>
                                    <th class="p-4">Status Akun</th>
                                    <th class="p-4 text-right">Aksi Super Admin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @forelse($users as $user)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-2xl bg-blue-100 text-blue-700 font-black flex items-center justify-center text-sm shrink-0 border border-blue-200">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-extrabold text-slate-900 text-sm">{{ $user->name }}</div>
                                                    <div class="text-3xs text-slate-400 font-medium">{{ $user->email }}</div>
                                                    <div class="text-4xs text-slate-400 mt-0.5">Terdaftar {{ $user->created_at->format('d M Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            @foreach($user->roles as $role)
                                                @php
                                                    $badgeClass = match($role->name) {
                                                        'Super Admin' => 'bg-purple-50 text-purple-800 border-purple-200',
                                                        'Company Owner' => 'bg-blue-50 text-blue-800 border-blue-200',
                                                        'HR' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                                                        default => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                    };
                                                @endphp
                                                <span class="px-2.5 py-1 text-3xs font-bold rounded-lg border {{ $badgeClass }}">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="p-4 text-xs text-slate-600">
                                            @if($user->companyProfile)
                                                <div class="font-bold text-slate-900">{{ $user->companyProfile->company_name }}</div>
                                                <div class="text-3xs text-slate-400 font-medium">{{ $user->companyProfile->industry ?? 'Perusahaan' }}</div>
                                            @elseif($user->candidateProfile)
                                                <div class="text-slate-700 font-medium">{{ $user->candidateProfile->headline ?? 'Pencari Kerja' }}</div>
                                            @else
                                                <span class="text-slate-400 font-medium">-</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            @if($user->is_suspended)
                                                <span class="px-2.5 py-1 bg-rose-50 text-rose-800 text-3xs font-black rounded-lg border border-rose-200 inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-lock"></i> SUSPENDED / DIBLOKIR
                                                </span>
                                                @if($user->status_reason)
                                                    <p class="text-4xs text-rose-600 mt-1 font-medium">{{ $user->status_reason }}</p>
                                                @endif
                                            @else
                                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 text-3xs font-extrabold rounded-lg border border-emerald-200 inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-circle-check"></i> AKTIF NORMAL
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-right">
                                            @if($user->id !== auth()->id())
                                                <div class="flex items-center justify-end gap-2">
                                                    <!-- Toggle Suspend Form -->
                                                    <form method="POST" action="{{ route('admin.users.toggle-suspend', $user->id) }}" onsubmit="return confirm('{{ $user->is_suspended ? 'Aktifkan kembali akun ini?' : 'Yakin ingin MEMBLOKIR / SUSPEND akun ini?' }}');">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if($user->is_suspended)
                                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-2xs transition">
                                                                <i class="fa-solid fa-lock-open mr-1"></i> Buka Blokir
                                                            </button>
                                                        @else
                                                            <button type="submit" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-2xs transition">
                                                                <i class="fa-solid fa-ban mr-1"></i> Suspend / Blokir
                                                            </button>
                                                        @endif
                                                    </form>

                                                    <!-- Delete Account Form -->
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('PERINGATAN! Akun {{ $user->name }} dan seluruh data terkait akan DIHAPUS PERMANEN! Lanjutkan?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-2xs transition">
                                                            <i class="fa-solid fa-trash mr-1"></i> Hapus Permanen
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-xs italic text-slate-400 font-medium">(Akun Anda)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-slate-400 font-medium">
                                            Tidak ada pengguna ditemukan dengan kriteria filter tersebut.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 border-t border-slate-100 pt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
