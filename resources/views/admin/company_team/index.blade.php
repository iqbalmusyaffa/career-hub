<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-users-gear text-blue-600"></i> Manajemen Tim HR Perusahaan
                </h2>
                <p class="text-xs text-gray-500 mt-1">Kelola hak akses dan anggota staf rekrutmen internal untuk {{ $companyProfile->company_name }}.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-xl text-sm transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center gap-2 shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-800 text-xs font-bold flex items-center gap-2 shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-red-600 text-base"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Invite New HR Team Member Form -->
            <div class="bg-white rounded-3xl shadow-2xs border border-gray-100 p-6 sm:p-8 space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-user-plus text-blue-600"></i> Undang / Tambah Staf HR Baru
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Masukkan nama, email, dan tetapkan peran internal staf rekrutmen perusahaan.</p>
                </div>

                <form method="POST" action="{{ route('admin.company-team.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nama Staf HR</label>
                        <input type="text" name="name" required placeholder="Contoh: Budi Santoso" class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Alamat Email Staf</label>
                        <input type="email" name="email" required placeholder="budi@perusahaan.com" class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Peran Internal (Role Title)</label>
                        <select name="role_title" required class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold">
                            <option value="Lead Recruiter">Lead Recruiter</option>
                            <option value="Interviewer">Interviewer</option>
                            <option value="HR Specialist">HR Specialist</option>
                            <option value="HR Administrator">HR Administrator</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs transition shadow-md flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-paper-plane"></i> Tambah Anggota Tim
                        </button>
                    </div>
                </form>
            </div>

            <!-- Team Members Table -->
            <div class="bg-white rounded-3xl shadow-2xs border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-users text-blue-600"></i> Daftar Anggota Tim HR Perusahaan ({{ method_exists($teamMembers, 'total') ? $teamMembers->total() : count($teamMembers) }})
                    </h3>

                    <form method="GET" action="{{ route('admin.company-team.index') }}" class="flex items-center gap-2 text-xs">
                        <label class="font-bold text-gray-500 whitespace-nowrap">Baris per Halaman:</label>
                        <select name="per_page" onchange="this.form.submit()" class="border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold py-1.5 px-3">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                        </select>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-2xs font-bold text-gray-400 uppercase tracking-wider">
                                <th class="p-4 pl-6">Staf HR</th>
                                <th class="p-4">Peran Internal</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Diundang Oleh</th>
                                <th class="p-4 pr-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                            @forelse($teamMembers as $member)
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="p-4 pl-6 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-sm border border-blue-200">
                                                {{ strtoupper(substr($member->user->name ?? 'HR', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 text-sm">{{ $member->user->name ?? '-' }}</div>
                                                <div class="text-2xs text-gray-500">{{ $member->user->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 font-bold rounded-lg border border-blue-200 text-2xs">
                                            {{ $member->role_title }}
                                        </span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 text-2xs uppercase">
                                            ● {{ $member->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-gray-500">
                                        {{ $member->inviter->name ?? 'Pemilik Perusahaan' }}
                                    </td>
                                    <td class="p-4 pr-6 text-right whitespace-nowrap">
                                        <form method="POST" action="{{ route('admin.company-team.destroy', $member->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota tim HR ini?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 font-bold rounded-xl text-2xs border border-red-200 transition">
                                                <i class="fa-solid fa-trash-can"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-400 text-xs font-medium">
                                        Belum ada anggota tim HR yang ditambahkan. Gunakan formulir di atas untuk mengundang staf HR baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($teamMembers, 'hasPages') && $teamMembers->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">
                        {{ $teamMembers->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
