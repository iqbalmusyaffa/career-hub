<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-900 dark:text-white leading-tight flex items-center gap-3">
            <i class="fa-solid fa-user-shield text-blue-600 dark:text-blue-400"></i> {{ __('Pusat Verifikasi Pengajuan Akun Perusahaan & HR') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10 bg-slate-50/60 dark:bg-slate-900 min-h-screen transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-emerald-800 dark:text-emerald-300 font-bold text-xs sm:text-sm flex items-center gap-3 shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-lg shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Summary Card -->
            <div class="bg-slate-900 dark:bg-slate-800 text-white rounded-2xl p-5 sm:p-6 shadow-2xs border border-slate-800 dark:border-slate-700 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <span class="px-2.5 py-1 bg-slate-800 dark:bg-slate-700 text-slate-300 text-3xs font-bold rounded-md uppercase border border-slate-700 dark:border-slate-600">Approval Management</span>
                    <h3 class="text-lg sm:text-xl font-bold mt-2 text-slate-100">Daftar Permohonan Akun Perusahaan Baru</h3>
                    <p class="text-xs text-slate-400 mt-1">Verifikasi berkas legalitas NIB/SIUP dan berikan persetujuan role Company Owner secara aman.</p>
                </div>
                <div class="text-right">
                    <span class="text-3xl font-black text-amber-400">{{ $requests->where('status', 'pending')->count() }}</span>
                    <span class="text-xs text-slate-400 block font-semibold">Menunggu Verifikasi</span>
                </div>
            </div>

            <!-- Table Requests -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 sm:p-6 shadow-2xs border border-slate-200 dark:border-slate-700 space-y-4 transition-colors">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700 text-3xs font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                <th class="pb-3">Pemohon</th>
                                <th class="pb-3">Nama Perusahaan</th>
                                <th class="pb-3">Industri & Telepon</th>
                                <th class="pb-3">Dokumen Legal (NIB)</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-right">Aksi Super Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs sm:text-sm">
                            @forelse($requests as $r)
                                <tr>
                                    <td class="py-4">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $r->user->name ?? 'User' }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $r->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="py-4 font-bold text-blue-700 dark:text-blue-400">
                                        {{ $r->company_name }}
                                        <span class="text-3xs font-medium text-slate-400 dark:text-slate-500 block">{{ $r->company_size }} &bull; Role: {{ $r->requested_role ?: 'Company Owner' }}</span>
                                    </td>
                                    <td class="py-4 text-xs">
                                        <div class="font-semibold text-slate-700 dark:text-slate-300">{{ $r->industry }}</div>
                                        <div class="text-slate-500 dark:text-slate-400"><i class="fa-solid fa-phone text-3xs"></i> {{ $r->phone }}</div>
                                    </td>
                                    <td class="py-4 text-xs">
                                        @if($r->legal_doc_path)
                                            <a href="{{ Storage::url($r->legal_doc_path) }}" target="_blank" class="px-3 py-1.5 bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/60 rounded-xl text-3xs font-bold inline-flex items-center gap-1.5 border border-blue-200 dark:border-blue-800 transition">
                                                <i class="fa-solid fa-file-pdf text-rose-500"></i> Pratinjau NIB PDF
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic">Tanpa berkas</span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        <span class="px-2.5 py-1 rounded-full text-3xs font-black uppercase border {{ $r->status_badge }}">
                                            {{ ucfirst($r->status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-right space-x-2 whitespace-nowrap">
                                        @if($r->status === 'pending')
                                            <form action="{{ route('admin.role-requests.approve', $r->encrypted_id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Setujui pengajuan perusahaan {{ addslashes($r->company_name) }}? Role pengguna akan diubah menjadi {{ $r->requested_role ?: 'Company Owner' }}.')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-2xs inline-flex items-center gap-1 cursor-pointer">
                                                    <i class="fa-solid fa-check"></i> Setujui
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.role-requests.reject', $r->encrypted_id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Tolak pengajuan perusahaan ini?')" class="px-3 py-1.5 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 hover:bg-rose-100 dark:hover:bg-rose-900/60 border border-rose-200 dark:border-rose-800 rounded-xl text-xs font-bold transition inline-flex items-center gap-1 cursor-pointer">
                                                    <i class="fa-solid fa-xmark"></i> Tolak
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-3xs text-slate-400 dark:text-slate-500 italic">Proses Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-xs text-slate-400 dark:text-slate-500">Belum ada pengajuan pendaftaran perusahaan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
