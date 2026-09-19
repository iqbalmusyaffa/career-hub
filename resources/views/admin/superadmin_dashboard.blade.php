<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 text-[11px] font-semibold rounded-md border border-rose-200 dark:border-rose-800">
                        Super Administrator
                    </span>
                    <span class="text-xs text-slate-400 dark:text-slate-500 font-normal">| Pusat Kontrol Platform</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight">
                    Dashboard Kontrol Sistem
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                    Pengawasan akun pengguna, verifikasi berkas legalitas perusahaan, dan pemantauan sistem rekrutmen.
                </p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.users.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3.5 rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-users text-xs"></i> Kelola Pengguna
                </a>
                <a href="{{ route('admin.companies.index') }}" class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold py-2 px-3.5 rounded-xl text-xs transition border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-building-circle-check text-slate-500 dark:text-slate-400 text-xs"></i> Verifikasi Perusahaan
                </a>
                <a href="{{ route('admin.settings.smtp.edit') }}" class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold py-2 px-3.5 rounded-xl text-xs transition border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-slate-500 dark:text-slate-400 text-xs"></i> Pengaturan SMTP
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold py-2 px-3.5 rounded-xl text-xs transition border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-slate-500 dark:text-slate-400 text-xs"></i> Log Audit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 min-h-screen bg-slate-50/60 dark:bg-slate-900 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-xl text-xs font-medium flex items-center gap-3 shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- KPI Metric Cards Grid (6 Columns) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
                
                <!-- 1. Total Users -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Akun</span>
                        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs border border-blue-100 dark:border-blue-800">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalUsers) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Terdaftar di sistem</p>
                    </div>
                </div>

                <!-- 2. Candidates -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Kandidat</span>
                        <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs border border-indigo-100 dark:border-indigo-800">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalCandidates) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Pencari kerja aktif</p>
                    </div>
                </div>

                <!-- 3. Companies -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Perusahaan</span>
                        <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs border border-amber-100 dark:border-amber-800">
                            <i class="fa-solid fa-building"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalCompanies) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal"><span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $verifiedCompanies }}</span> terverifikasi</p>
                    </div>
                </div>

                <!-- 4. Pending Legal Verifications -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Verifikasi NIB</span>
                        <div class="w-9 h-9 rounded-lg {{ $pendingCompanyVerifications > 0 ? 'bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800' : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800' }} flex items-center justify-center text-xs">
                            <i class="fa-solid {{ $pendingCompanyVerifications > 0 ? 'fa-file-circle-exclamation' : 'fa-circle-check' }}"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold {{ $pendingCompanyVerifications > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }} tracking-tight">
                            {{ $pendingCompanyVerifications }}
                        </div>
                        <p class="text-[11px] font-normal {{ $pendingCompanyVerifications > 0 ? 'text-amber-700 dark:text-amber-400' : 'text-slate-500 dark:text-slate-400' }}">
                            {{ $pendingCompanyVerifications > 0 ? 'Menunggu review' : 'Semua diproses' }}
                        </p>
                    </div>
                </div>

                <!-- 5. Total Jobs -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Lowongan</span>
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs border border-emerald-100 dark:border-emerald-800">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalJobs) }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal"><span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $activeJobs }}</span> lowongan aktif</p>
                    </div>
                </div>

                <!-- 6. Suspended Accounts -->
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between text-slate-400 dark:text-slate-500">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Akun Suspend</span>
                        <div class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center text-xs border border-slate-200 dark:border-slate-600">
                            <i class="fa-solid fa-user-slash"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $suspendedUsers }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Akses dibatasi</p>
                    </div>
                </div>

            </div>

            <!-- Content Grid: Pending Legal Documents & Recent Registrations -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Table 1: Pending Company & Role Approvals -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 p-5 sm:p-6 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700/60 pb-3">
                        <div>
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-file-shield text-amber-500 text-xs"></i> Permohonan Verifikasi & Akun Perusahaan
                                @if(isset($pendingRoleRequestsCount) && $pendingRoleRequestsCount > 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white animate-pulse">{{ $pendingRoleRequestsCount }} Baru</span>
                                @endif
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Pendaftaran akun Perusahaan/HR baru & unggahan berkas legalitas (NIB/SIUP).</p>
                        </div>
                        <a href="{{ route('admin.role-requests.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                            Pusat Verifikasi &rarr;
                        </a>
                    </div>

                    @php
                        $hasPending = (isset($pendingRoleRequests) && count($pendingRoleRequests) > 0) || (isset($pendingCompaniesList) && count($pendingCompaniesList) > 0);
                    @endphp

                    @if($hasPending)
                        <div class="space-y-3">
                            {{-- 1. Pending Role Requests (from Candidate/User) --}}
                            @if(isset($pendingRoleRequests) && count($pendingRoleRequests) > 0)
                                @foreach($pendingRoleRequests as $rr)
                                    <div class="p-3.5 bg-blue-50/60 dark:bg-slate-900/80 rounded-xl border border-blue-200/60 dark:border-slate-700/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-bold text-slate-900 dark:text-white text-xs">{{ $rr->company_name }}</span>
                                                <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 rounded text-[10px] font-bold border border-blue-200 dark:border-blue-800">
                                                    Pengajuan Akun {{ $rr->requested_role ?: 'Company' }}
                                                </span>
                                                <span class="px-2 py-0.5 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 rounded text-[10px] font-semibold border border-amber-200 dark:border-amber-800">
                                                    Menunggu Review
                                                </span>
                                            </div>
                                            <p class="text-slate-500 dark:text-slate-400 text-[11px] font-normal">
                                                Pemohon: <strong class="text-slate-700 dark:text-slate-300">{{ $rr->user->name ?? 'User' }}</strong> ({{ $rr->user->email ?? '-' }}) &bull; Telp: {{ $rr->phone ?? '-' }} &bull; {{ $rr->industry ?? 'Umum' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            @if($rr->legal_doc_path)
                                                <a href="{{ Storage::url($rr->legal_doc_path) }}" target="_blank" class="px-3 py-1.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold rounded-lg border border-slate-200 dark:border-slate-700 text-xs transition flex items-center gap-1.5 shadow-2xs">
                                                    <i class="fa-solid fa-file-pdf text-rose-500 text-xs"></i> NIB / Dokumen
                                                </a>
                                            @endif
                                            <form action="{{ route('admin.role-requests.approve', $rr->encrypted_id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Setujui pengajuan perusahaan {{ addslashes($rr->company_name) }}? Role pemohon akan diubah menjadi Company Owner.')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition text-xs shadow-2xs flex items-center gap-1">
                                                    <i class="fa-solid fa-check"></i> Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.role-requests.reject', $rr->encrypted_id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Tolak pengajuan perusahaan ini?')" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 font-semibold rounded-lg border border-rose-200 dark:border-rose-800 text-xs transition">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- 2. Pending Company Profile Document Verifications --}}
                            @if(isset($pendingCompaniesList) && count($pendingCompaniesList) > 0)
                                @foreach($pendingCompaniesList as $cp)
                                    <div class="p-3.5 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200/80 dark:border-slate-700/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-900 dark:text-white text-xs">{{ $cp->company_name }}</span>
                                                <span class="px-2 py-0.5 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 rounded text-[10px] font-semibold border border-amber-200 dark:border-amber-800">Verifikasi Berkas Legal</span>
                                            </div>
                                            <p class="text-slate-500 dark:text-slate-400 text-[11px] font-normal">
                                                Penanggung Jawab: {{ $cp->user->name ?? '-' }} ({{ $cp->user->email ?? '-' }})
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            @if($cp->legal_doc_path)
                                                <a href="{{ asset('storage/' . $cp->legal_doc_path) }}" target="_blank" class="px-3 py-1.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold rounded-lg border border-slate-200 dark:border-slate-700 text-xs transition flex items-center gap-1.5 shadow-2xs">
                                                    <i class="fa-solid fa-file-pdf text-rose-500 text-xs"></i> Dokumen
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.companies.index') }}" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-xs shadow-2xs">
                                                Tinjau &rarr;
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @else
                        <div class="p-8 text-center bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-dashed border-slate-200 dark:border-slate-700 text-xs text-slate-500 dark:text-slate-400 font-normal">
                            <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-lg mb-1 block"></i>
                            Semua dokumen legalitas dan permohonan akun perusahaan telah selesai diproses.
                        </div>
                    @endif
                </div>

                <!-- Table 2: Recent User Registrations -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 p-5 sm:p-6 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700/60 pb-3">
                        <div>
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-user-plus text-blue-600 dark:text-blue-400 text-xs"></i> Pengguna Baru Terdaftar
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Akun pengguna yang baru saja mendaftar di portal.</p>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                            Semua Pengguna &rarr;
                        </a>
                    </div>

                    <div class="space-y-2.5">
                        @foreach($recentUsers as $u)
                            <div class="p-3 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200/70 dark:border-slate-700/60 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-slate-800 dark:bg-slate-700 text-white font-bold flex items-center justify-center text-xs shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-900 dark:text-white text-xs truncate">{{ $u->name }}</p>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-normal truncate">{{ $u->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0 ml-3">
                                    <span class="px-2 py-0.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold rounded-md border border-slate-200 dark:border-slate-700 text-[10px] uppercase tracking-wider">
                                        {{ $u->getRoleNames()->first() ?? 'User' }}
                                    </span>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-normal mt-0.5">{{ $u->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Master Control Shortcuts -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 p-5 sm:p-6 space-y-4">
                <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <i class="fa-solid fa-gears text-slate-500 dark:text-slate-400 text-xs"></i> Konfigurasi Master & Fitur Administrator
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Akses cepat ke modul pengaturan, keamanan, dan moderasi portal.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 pt-1">
                    <a href="{{ route('admin.settings.smtp.edit') }}" class="p-4 bg-slate-50 dark:bg-slate-900/60 hover:bg-slate-100/80 dark:hover:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-700 transition space-y-1 block group">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs font-semibold mb-2 border border-blue-100 dark:border-blue-800">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <h4 class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Pengaturan SMTP Email</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Konfigurasi pengiriman notifikasi email otomatis ke pelamar & HR.</p>
                    </a>

                    <a href="{{ route('admin.settings.seo.edit') }}" class="p-4 bg-slate-50 dark:bg-slate-900/60 hover:bg-slate-100/80 dark:hover:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-700 transition space-y-1 block group">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs font-semibold mb-2 border border-indigo-100 dark:border-indigo-800">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                        <h4 class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Branding & SEO Portal</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Ubah nama platform, favicon, meta deskripsi, dan integrasi Google Analytics.</p>
                    </a>

                    <a href="{{ route('admin.blacklists.index') }}" class="p-4 bg-slate-50 dark:bg-slate-900/60 hover:bg-slate-100/80 dark:hover:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-700 transition space-y-1 block group">
                        <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xs font-semibold mb-2 border border-rose-100 dark:border-rose-800">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <h4 class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Anti-Fraud & Blacklist</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Blokir pengguna fiktif, pelapor palsu, atau spammer yang melanggar aturan.</p>
                    </a>

                    <a href="{{ route('admin.certificates.index') }}" class="p-4 bg-slate-50 dark:bg-slate-900/60 hover:bg-slate-100/80 dark:hover:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-700 transition space-y-1 block group">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-semibold mb-2 border border-emerald-100 dark:border-emerald-800">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <h4 class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Master Sertifikat</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Pantau penerbitan sertifikat magang dan nomor seri verifikasi QR code.</p>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
