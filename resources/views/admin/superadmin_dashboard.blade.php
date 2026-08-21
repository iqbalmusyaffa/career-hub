<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="px-2.5 py-0.5 bg-red-600 text-white text-3xs font-black rounded-lg uppercase tracking-wider">Super Admin</span>
                    Command Center Platform
                </h2>
                <p class="text-xs text-slate-500 mt-1">Pengawasan master ekosistem akun pengguna, legalitas perusahaan, dan moderasi sistem.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.users.index') }}" class="bg-slate-900 hover:bg-black text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs flex items-center gap-1.5">
                    <i class="fa-solid fa-users-gear text-slate-400"></i> Kelola Pengguna
                </a>
                <a href="{{ route('admin.companies.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs flex items-center gap-1.5">
                    <i class="fa-solid fa-building-circle-check text-blue-200"></i> Kelola Perusahaan
                </a>
                <a href="{{ route('admin.settings.smtp.edit') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs flex items-center gap-1.5">
                    <i class="fa-solid fa-envelope-gear text-emerald-200"></i> Pengaturan SMTP
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs flex items-center gap-1.5">
                    <i class="fa-solid fa-list-check text-purple-200"></i> Audit Logs
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center gap-2 shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Master KPI Cards (6 Cards) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5">
                <!-- Total Users -->
                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-semibold uppercase tracking-wider text-slate-500">Total Akun</span>
                        <i class="fa-solid fa-users text-blue-600 text-base"></i>
                    </div>
                    <div class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalUsers) }}</div>
                    <p class="text-3xs font-medium text-slate-500">Terdaftar di Platform</p>
                </div>

                <!-- Candidates -->
                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-semibold uppercase tracking-wider text-slate-500">Kandidat</span>
                        <i class="fa-solid fa-user-graduate text-indigo-600 text-base"></i>
                    </div>
                    <div class="text-2xl font-extrabold text-indigo-600 tracking-tight">{{ number_format($totalCandidates) }}</div>
                    <p class="text-3xs font-medium text-slate-500">Pencari Kerja Active</p>
                </div>

                <!-- Companies -->
                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-semibold uppercase tracking-wider text-slate-500">Perusahaan</span>
                        <i class="fa-solid fa-building text-amber-500 text-base"></i>
                    </div>
                    <div class="text-2xl font-extrabold text-amber-600 tracking-tight">{{ number_format($totalCompanies) }}</div>
                    <p class="text-3xs font-medium text-slate-500"><span class="font-semibold text-emerald-600">{{ $verifiedCompanies }}</span> Verified</p>
                </div>

                <!-- Pending SIUP -->
                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-semibold uppercase tracking-wider text-slate-500">Pending Legal</span>
                        <i class="fa-solid fa-file-circle-exclamation text-rose-500 text-base"></i>
                    </div>
                    <div class="text-2xl font-extrabold text-rose-600 tracking-tight">{{ $pendingCompanyVerifications }}</div>
                    <p class="text-3xs font-semibold text-rose-700">Perlu Verifikasi SIUP</p>
                </div>

                <!-- Total Jobs -->
                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-semibold uppercase tracking-wider text-slate-500">Lowongan</span>
                        <i class="fa-solid fa-briefcase text-emerald-600 text-base"></i>
                    </div>
                    <div class="text-2xl font-extrabold text-emerald-600 tracking-tight">{{ number_format($totalJobs) }}</div>
                    <p class="text-3xs font-medium text-slate-500"><span class="font-semibold text-emerald-700">{{ $activeJobs }}</span> Aktif Tayang</p>
                </div>

                <!-- Suspended Users -->
                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-semibold uppercase tracking-wider text-slate-500">Akun Diblokir</span>
                        <i class="fa-solid fa-user-slash text-slate-700 text-base"></i>
                    </div>
                    <div class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $suspendedUsers }}</div>
                    <p class="text-3xs font-medium text-slate-500">Status Suspended</p>
                </div>
            </div>

            <!-- Content Grid: Pending Legal Documents & Recent Registrations -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Table 1: Pending Company Legal Approvals -->
                <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/80 p-6 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-extrabold text-base text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-file-circle-check text-amber-500"></i> Pengajuan Verifikasi Dokumen Legalitas
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Perusahaan yang mengunggah berkas SIUP / NIB.</p>
                        </div>
                        <a href="{{ route('admin.companies.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    @if(count($pendingCompaniesList) > 0)
                        <div class="space-y-3">
                            @foreach($pendingCompaniesList as $cp)
                                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 flex items-center justify-between text-xs gap-3">
                                    <div>
                                        <p class="font-extrabold text-slate-900 text-sm">{{ $cp->company_name }}</p>
                                        <p class="text-slate-500 mt-0.5">Akun: {{ $cp->user->name ?? '-' }} ({{ $cp->user->email ?? '-' }})</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($cp->legal_doc_path)
                                            <a href="{{ asset('storage/' . $cp->legal_doc_path) }}" target="_blank" class="px-3 py-1.5 bg-blue-50 text-blue-700 font-semibold rounded-lg border border-blue-200 hover:bg-blue-100 transition text-3xs">
                                                <i class="fa-solid fa-file-pdf"></i> Dokumen
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.companies.index') }}" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition text-3xs">
                                            Verifikasi
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200 text-xs text-slate-400">
                            Semua dokumen legalitas perusahaan telah diverifikasi.
                        </div>
                    @endif
                </div>

                <!-- Table 2: Recent User Registrations -->
                <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/80 p-6 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-extrabold text-base text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-user-plus text-blue-600"></i> Pengguna Baru Terdaftar
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Pendaftaran akun pengguna terbaru di platform.</p>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                            Semua Akun &rarr;
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach($recentUsers as $u)
                            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs border border-blue-200">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-extrabold text-slate-900">{{ $u->name }}</p>
                                        <p class="text-3xs text-slate-400">{{ $u->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-0.5 bg-white text-slate-700 font-bold rounded border border-slate-200 text-3xs uppercase">
                                        {{ $u->getRoleNames()->first() ?? 'User' }}
                                    </span>
                                    <p class="text-3xs text-slate-400 mt-1">{{ $u->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
