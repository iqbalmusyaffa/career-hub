<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- PAGE HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500 mb-1">
                    <span>Pengaturan Perusahaan</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <span class="text-blue-600 dark:text-blue-400">Tim & Hak Akses</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                    <i class="fa-solid fa-users-gear text-blue-600 dark:text-blue-400"></i>
                    Manajemen Tim HR Perusahaan
                </h1>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Kelola anggota staf rekrutmen internal dan hak akses untuk <strong class="text-slate-900 dark:text-white">{{ $companyProfile->company_name ?? 'Perusahaan' }}</strong>.
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.company-team.audit-logs') }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-semibold transition flex items-center gap-2 shadow-xs">
                    <i class="fa-solid fa-clock-rotate-left text-slate-400"></i> Log Aktivitas Tim
                </a>
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-semibold transition flex items-center gap-2 shadow-xs">
                    &larr; Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center gap-2.5 shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-2xl text-rose-800 dark:text-rose-300 text-xs font-semibold flex items-center gap-2.5 shadow-xs">
                <i class="fa-solid fa-circle-exclamation text-rose-600 dark:text-rose-400 text-sm"></i> {{ session('error') }}
            </div>
        @endif

        <!-- INVITE / ADD NEW TEAM MEMBER FORM -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 sm:p-6 space-y-4 shadow-xs">
            <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-blue-600 dark:text-blue-400"></i>
                    Undang / Tambah Staf HR Baru
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Masukkan identitas staf dan tetapkan posisi peran rekrutmen internal.</p>
            </div>

            <form method="POST" action="{{ route('admin.company-team.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3.5 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Contoh: Budi Santoso" 
                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Email Perusahaan</label>
                    <input type="email" name="email" required placeholder="budi@perusahaan.com" 
                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Peran Internal (Role Title)</label>
                    <select name="role_title" required 
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-semibold">
                        <option value="Lead Recruiter">Lead Recruiter</option>
                        <option value="Interviewer">Interviewer</option>
                        <option value="HR Specialist">HR Specialist</option>
                        <option value="HR Administrator">HR Administrator</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl text-xs transition shadow-xs flex items-center justify-center gap-1.5 h-[38px]">
                        <i class="fa-solid fa-paper-plane"></i> Tambah Anggota
                    </button>
                </div>
            </form>
        </div>

        <!-- TEAM MEMBERS TABLE -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-xs">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-users text-blue-600 dark:text-blue-400"></i> Daftar Anggota Tim HR
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                        {{ method_exists($teamMembers, 'total') ? $teamMembers->total() : count($teamMembers) }} Anggota
                    </span>
                </div>

                <form method="GET" action="{{ route('admin.company-team.index') }}" class="flex items-center gap-2 text-xs">
                    <span class="text-slate-500 dark:text-slate-400">Baris:</span>
                    <select name="per_page" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-lg text-xs py-1 px-2.5 text-slate-800 dark:text-slate-200 font-semibold focus:outline-none">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris</option>
                        <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25 Baris</option>
                        <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50 Baris</option>
                        <option value="100" {{ request('per_page', 100) == 100 ? 'selected' : '' }}>100 Baris</option>
                    </select>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4 pl-6">Staf HR</th>
                            <th class="py-3 px-4">Peran Internal</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Diundang Oleh</th>
                            <th class="py-3 px-4 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs text-slate-700 dark:text-slate-300">
                        @forelse($teamMembers as $member)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-4 pl-6 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 font-bold flex items-center justify-center text-xs border border-blue-200 dark:border-blue-800/60 shadow-2xs">
                                            {{ strtoupper(substr($member->user->name ?? 'HR', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white text-xs">{{ $member->user->name ?? '-' }}</div>
                                            <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $member->user->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 font-semibold rounded-md border border-blue-200 dark:border-blue-800/60 text-[11px]">
                                        {{ $member->role_title }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($member->status === 'pending_owner_approval')
                                        <span class="px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 font-semibold rounded-md border border-amber-200 dark:border-amber-800/60 text-[11px] inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> Menunggu Persetujuan Owner
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-semibold rounded-md border border-emerald-200 dark:border-emerald-800/60 text-[11px] inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-500 dark:text-slate-400 text-xs">
                                    {{ $member->inviter->name ?? 'Pemilik Perusahaan' }}
                                </td>
                                <td class="py-3.5 px-4 pr-6 text-right whitespace-nowrap space-x-1">
                                    @if($member->status === 'pending_owner_approval')
                                        <form method="POST" action="{{ route('company-team.approve-co-owner', $member->id) }}" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg text-xs transition shadow-2xs">
                                                <i class="fa-solid fa-check"></i> Setujui Co-Owner
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('company-team.reject-co-owner', $member->id) }}" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 font-semibold rounded-lg text-xs border border-slate-200 dark:border-slate-700 transition">
                                                <i class="fa-solid fa-user-shield"></i> Jadikan HR
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.company-team.destroy', $member->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota tim HR ini?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 hover:bg-rose-100 dark:hover:bg-rose-900/60 font-semibold rounded-lg text-xs border border-rose-200 dark:border-rose-800/60 transition">
                                            <i class="fa-solid fa-trash-can text-[10px]"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs font-medium">
                                    <i class="fa-solid fa-users text-3xl mb-2 text-slate-300 dark:text-slate-700 block"></i>
                                    Belum ada anggota tim HR yang ditambahkan. Gunakan formulir di atas untuk mengundang staf HR baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($teamMembers, 'hasPages') && $teamMembers->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950">
                    {{ $teamMembers->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
