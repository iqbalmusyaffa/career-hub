<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="px-2.5 py-0.5 bg-rose-600 text-white text-[10px] font-bold rounded-md uppercase tracking-wider shadow-2xs">Super Admin</span>
                    Command Center Platform
                </h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Pengawasan master ekosistem akun pengguna, legalitas perusahaan, dan moderasi sistem.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.users.index') }}" class="bg-slate-900 hover:bg-black text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-md flex items-center gap-2 border border-slate-900">
                    <i class="fa-solid fa-users-gear text-slate-300"></i> Kelola Pengguna
                </a>
                <a href="{{ route('admin.companies.index') }}" class="bg-white hover:bg-slate-100 text-slate-800 font-bold py-2.5 px-4 rounded-xl text-xs transition border border-slate-300 shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-building-circle-check text-blue-600"></i> Kelola Perusahaan
                </a>
                <a href="{{ route('admin.settings.smtp.edit') }}" class="bg-white hover:bg-slate-100 text-slate-800 font-bold py-2.5 px-4 rounded-xl text-xs transition border border-slate-300 shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-emerald-600"></i> Pengaturan SMTP
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="bg-white hover:bg-slate-100 text-slate-800 font-bold py-2.5 px-4 rounded-xl text-xs transition border border-slate-300 shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-purple-600"></i> Audit Logs
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/70 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-xs font-bold flex items-center gap-3 shadow-2xs">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Master KPI Cards (6 Cards Grid) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5">
                <!-- Total Users -->
                <div class="bg-white p-5 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-2 group">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-500">Total Akun</span>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-black border border-blue-100 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($totalUsers) }}</div>
                    <p class="text-3xs font-semibold text-slate-500">Terdaftar di Platform</p>
                </div>

                <!-- Candidates -->
                <div class="bg-white p-5 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-2 group">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-500">Kandidat</span>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black border border-indigo-100 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-indigo-600 tracking-tight">{{ number_format($totalCandidates) }}</div>
                    <p class="text-3xs font-semibold text-slate-500">Pencari Kerja Active</p>
                </div>

                <!-- Companies -->
                <div class="bg-white p-5 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-2 group">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-500">Perusahaan</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-black border border-amber-100 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-building"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-amber-600 tracking-tight">{{ number_format($totalCompanies) }}</div>
                    <p class="text-3xs font-semibold text-slate-500"><span class="font-extrabold text-emerald-600">{{ $verifiedCompanies }}</span> Verified</p>
                </div>

                <!-- Pending SIUP -->
                <div class="bg-white p-5 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-2 group">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-500">Pending Legal</span>
                        <div class="w-10 h-10 rounded-xl {{ $pendingCompanyVerifications > 0 ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }} flex items-center justify-center text-sm font-black group-hover:scale-110 transition duration-300">
                            <i class="fa-solid {{ $pendingCompanyVerifications > 0 ? 'fa-file-circle-exclamation' : 'fa-circle-check' }}"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black {{ $pendingCompanyVerifications > 0 ? 'text-rose-600' : 'text-emerald-600' }} tracking-tight">{{ $pendingCompanyVerifications }}</div>
                    <p class="text-3xs font-extrabold {{ $pendingCompanyVerifications > 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                        {{ $pendingCompanyVerifications > 0 ? 'Perlu Verifikasi SIUP 🚨' : 'Semua Terverifikasi 🟢' }}
                    </p>
                </div>

                <!-- Total Jobs -->
                <div class="bg-white p-5 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-2 group">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-500">Lowongan</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-black border border-emerald-100 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-emerald-600 tracking-tight">{{ number_format($totalJobs) }}</div>
                    <p class="text-3xs font-semibold text-slate-500"><span class="font-extrabold text-emerald-700">{{ $activeJobs }}</span> Aktif Tayang</p>
                </div>

                <!-- Suspended Users -->
                <div class="bg-white p-5 rounded-3xl shadow-2xs hover:shadow-md transition-all duration-300 border border-slate-200/90 space-y-2 group">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-3xs font-extrabold uppercase tracking-wider text-slate-500">Akun Diblokir</span>
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-sm font-black border border-slate-200 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-user-slash"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $suspendedUsers }}</div>
                    <p class="text-3xs font-semibold text-slate-500">Status Suspended</p>
                </div>
            </div>

            <!-- Content Grid: Pending Legal Documents & Recent Registrations -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Table 1: Pending Company Legal Approvals -->
                <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/90 p-6 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-black text-base text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-file-circle-check text-amber-500"></i> Pengajuan Verifikasi Dokumen Legalitas
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5 font-medium">Perusahaan yang mengunggah berkas SIUP / NIB.</p>
                        </div>
                        <a href="{{ route('admin.companies.index') }}" class="text-xs font-black text-blue-600 hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    @if(count($pendingCompaniesList) > 0)
                        <div class="space-y-3">
                            @foreach($pendingCompaniesList as $cp)
                                <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-200 flex items-center justify-between text-xs gap-3">
                                    <div>
                                        <p class="font-black text-slate-900 text-sm">{{ $cp->company_name }}</p>
                                        <p class="text-slate-500 font-medium mt-0.5">Akun: {{ $cp->user->name ?? '-' }} ({{ $cp->user->email ?? '-' }})</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($cp->legal_doc_path)
                                            <a href="{{ asset('storage/' . $cp->legal_doc_path) }}" target="_blank" class="px-3 py-1.5 bg-blue-50 text-blue-800 font-extrabold rounded-lg border border-blue-200 hover:bg-blue-100 transition text-3xs shadow-2xs flex items-center gap-1">
                                                <i class="fa-solid fa-file-pdf"></i> Dokumen
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.companies.index') }}" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-lg transition text-3xs shadow-2xs">
                                            Verifikasi →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-xs text-slate-400 font-medium">
                            Semua dokumen legalitas perusahaan telah diverifikasi.
                        </div>
                    @endif
                </div>

                <!-- Table 2: Recent User Registrations -->
                <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/90 p-6 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-black text-base text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-user-plus text-blue-600"></i> Pengguna Baru Terdaftar
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5 font-medium">Pendaftaran akun pengguna terbaru di platform.</p>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="text-xs font-black text-blue-600 hover:underline">
                            Semua Akun &rarr;
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach($recentUsers as $u)
                            <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-200 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-900 text-white font-black flex items-center justify-center text-xs border border-slate-800 shadow-2xs">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-900">{{ $u->name }}</p>
                                        <p class="text-3xs text-slate-400 font-medium">{{ $u->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2.5 py-1 bg-white text-slate-800 font-extrabold rounded-md border border-slate-200 text-3xs uppercase tracking-wider shadow-2xs">
                                        {{ $u->getRoleNames()->first() ?? 'User' }}
                                    </span>
                                    <p class="text-3xs text-slate-400 font-medium mt-1">{{ $u->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
