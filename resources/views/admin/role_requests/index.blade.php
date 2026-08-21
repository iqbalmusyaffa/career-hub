<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-900 leading-tight flex items-center gap-3">
            <i class="fa-solid fa-user-shield text-blue-600"></i> {{ __('Pusat Verifikasi Pengajuan Akun Perusahaan') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 font-bold text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Summary Card -->
            <div class="bg-gradient-to-r from-slate-900 to-blue-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl flex items-center justify-between flex-wrap gap-4">
                <div>
                    <span class="px-3 py-1 bg-white/20 text-blue-200 text-3xs font-extrabold rounded-full uppercase">Approval Management</span>
                    <h3 class="text-2xl font-black mt-2">Daftar Permohonan Akun Perusahaan Baru</h3>
                    <p class="text-xs text-slate-300 mt-1">Verifikasi berkas legalitas NIB/SIUP dan berikan persetujuan role Company Owner secara aman.</p>
                </div>
                <div class="text-right">
                    <span class="text-3xl font-black text-amber-400">{{ $requests->where('status', 'pending')->count() }}</span>
                    <span class="text-xs text-slate-300 block font-semibold">Menunggu Verifikasi</span>
                </div>
            </div>

            <!-- Table Requests -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-3xs font-extrabold text-gray-400 uppercase tracking-wider">
                                <th class="pb-3">Pemohon</th>
                                <th class="pb-3">Nama Perusahaan</th>
                                <th class="pb-3">Industri & Telepon</th>
                                <th class="pb-3">Dokumen Legal (NIB)</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-right">Aksi Super Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @forelse($requests as $r)
                                <tr>
                                    <td class="py-4">
                                        <div class="font-extrabold text-gray-900">{{ $r->user->name ?? 'User' }}</div>
                                        <div class="text-xs text-gray-500">{{ $r->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="py-4 font-black text-blue-900">
                                        {{ $r->company_name }}
                                        <span class="text-3xs font-bold text-gray-400 block">{{ $r->company_size }}</span>
                                    </td>
                                    <td class="py-4 text-xs">
                                        <div class="font-semibold text-gray-700">{{ $r->industry }}</div>
                                        <div class="text-gray-500"><i class="fa-solid fa-phone text-3xs"></i> {{ $r->phone }}</div>
                                    </td>
                                    <td class="py-4 text-xs">
                                        @if($r->legal_doc_path)
                                            <a href="{{ Storage::url($r->legal_doc_path) }}" target="_blank" class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-3xs font-black inline-flex items-center gap-1.5">
                                                <i class="fa-solid fa-file-pdf"></i> Pratinjau NIB PDF
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Tanpa berkas</span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        <span class="px-2.5 py-1 rounded-full text-3xs font-black uppercase border {{ $r->status_badge }}">
                                            {{ ucfirst($r->status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-right space-x-2">
                                        @if($r->status === 'pending')
                                            <form action="{{ route('admin.role-requests.approve', $r->encrypted_id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Setujui pengajuan perusahaan ini? Role pengguna akan diubah menjadi Company Owner.')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-check"></i> Setujui
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.role-requests.reject', $r->encrypted_id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Tolak pengajuan perusahaan ini?')" class="px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-xl text-xs font-bold transition inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-xmark"></i> Tolak
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-3xs text-gray-400 italic">Proses Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-xs text-gray-400">Belum ada pengajuan pendaftaran perusahaan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
