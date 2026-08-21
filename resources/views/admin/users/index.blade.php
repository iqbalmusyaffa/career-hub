<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Manajemen Pengguna (Super Admin)') }}
            </h2>
            <div class="text-sm font-medium text-gray-500">
                Total Pengguna: <span class="font-bold text-blue-600">{{ $users->total() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl flex items-center gap-3 shadow-xs">
                    <i class="fa-solid fa-triangle-exclamation text-red-600 text-lg"></i>
                    <span class="font-medium text-sm">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Filter & Search Bar -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 text-xs">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cari Pengguna</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="w-full border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Filter Role</label>
                        <select name="role" class="w-full border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Role</option>
                            <option value="Candidate" {{ request('role') == 'Candidate' ? 'selected' : '' }}>Candidate (Pelamar)</option>
                            <option value="HR" {{ request('role') == 'HR' ? 'selected' : '' }}>HR Manager</option>
                            <option value="Company Owner" {{ request('role') == 'Company Owner' ? 'selected' : '' }}>Company Owner</option>
                            <option value="Super Admin" {{ request('role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Filter Status</label>
                        <select name="status" class="w-full border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif Normal</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Diblokir / Suspended</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Baris per Halaman</label>
                        <select name="per_page" onchange="this.form.submit()" class="w-full border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 font-bold">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris per Halaman</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris per Halaman</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris per Halaman</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris per Halaman</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition">
                            <i class="fa-solid fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-4 rounded-xl text-sm transition text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100">
                                    <th class="p-4 font-bold text-gray-600 text-xs uppercase">Pengguna</th>
                                    <th class="p-4 font-bold text-gray-600 text-xs uppercase">Role</th>
                                    <th class="p-4 font-bold text-gray-600 text-xs uppercase">Perusahaan / Info</th>
                                    <th class="p-4 font-bold text-gray-600 text-xs uppercase">Status Akun</th>
                                    <th class="p-4 font-bold text-gray-600 text-xs uppercase text-right">Aksi Super Admin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-50/60 transition">
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-sm shrink-0">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-900 text-sm">{{ $user->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                                    <div class="text-2xs text-gray-400">Terdaftar {{ $user->created_at->format('d M Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            @foreach($user->roles as $role)
                                                @php
                                                    $badgeClass = match($role->name) {
                                                        'Super Admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                        'Company Owner' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                        'HR' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                                        default => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                    };
                                                @endphp
                                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg border {{ $badgeClass }}">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="p-4 text-xs text-gray-600">
                                            @if($user->companyProfile)
                                                <div class="font-bold text-gray-800">{{ $user->companyProfile->company_name }}</div>
                                                <div class="text-2xs text-gray-500">{{ $user->companyProfile->industry ?? 'Perusahaan' }}</div>
                                            @elseif($user->candidateProfile)
                                                <div class="text-gray-700">{{ $user->candidateProfile->headline ?? 'Pencari Kerja' }}</div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            @if($user->is_suspended)
                                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-black rounded-lg border border-red-200 inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-lock"></i> SUSPENDED / DIBLOKIR
                                                </span>
                                                @if($user->status_reason)
                                                    <p class="text-2xs text-red-600 mt-1">{{ $user->status_reason }}</p>
                                                @endif
                                            @else
                                                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-lg border border-emerald-200 inline-flex items-center gap-1">
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
                                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-2xs transition">
                                                                <i class="fa-solid fa-lock-open mr-1"></i> Buka Blokir
                                                            </button>
                                                        @else
                                                            <button type="submit" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg shadow-2xs transition">
                                                                <i class="fa-solid fa-ban mr-1"></i> Suspend / Blokir
                                                            </button>
                                                        @endif
                                                    </form>

                                                    <!-- Delete Account Form -->
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('PERINGATAN! Akun {{ $user->name }} dan seluruh data terkait akan DIHAPUS PERMANEN! Lanjutkan?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg shadow-2xs transition">
                                                            <i class="fa-solid fa-trash mr-1"></i> Hapus Permanen
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-xs italic text-gray-400">(Akun Anda)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-gray-500">
                                            Tidak ada pengguna ditemukan dengan kriteria filter tersebut.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
