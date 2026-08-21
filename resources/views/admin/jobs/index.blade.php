<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Kelola Lowongan') }}
            </h2>
            <a href="{{ route('admin.jobs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition">
                + Tambah Lowongan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            <!-- DataTables Filter & Search Bar -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
                <form method="GET" action="{{ route('admin.jobs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-gray-500 uppercase mb-1">Cari Lowongan</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari posisi, divisi, lokasi..." class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 uppercase mb-1">Status Lowongan</label>
                        <select name="status" class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Tutup</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 uppercase mb-1">Baris per Halaman</label>
                        <select name="per_page" onchange="this.form.submit()" class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris per Halaman</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris per Halaman</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris per Halaman</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris per Halaman</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition">
                            <i class="fa-solid fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.jobs.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-4 rounded-xl text-xs transition text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Posisi</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Lokasi</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Tipe</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Status</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Pelamar</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($jobs as $job)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-4">
                                            <div class="font-bold text-gray-900">{{ $job->title }}</div>
                                            <div class="text-xs text-gray-500">Dibuat {{ $job->created_at->format('d M Y') }}</div>
                                        </td>
                                        <td class="p-4 text-gray-700">{{ $job->location }}</td>
                                        <td class="p-4">
                                            <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full border border-blue-100">
                                                {{ ucfirst($job->work_type) }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            @if($job->status == 'active')
                                                <span class="px-2.5 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full border border-green-100">Aktif</span>
                                            @else
                                                <span class="px-2.5 py-1 bg-red-50 text-red-700 text-xs font-semibold rounded-full border border-red-100">Tutup</span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-800 font-bold text-sm">
                                                {{ $job->applications_count }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-2">
                                                 <a href="{{ route('admin.jobs.export.excel', $job->id) }}" class="px-2.5 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition flex items-center gap-1" title="Export Laporan Rekrutmen Excel (.csv)">
                                                     <i class="fa-solid fa-file-excel text-emerald-600"></i> Excel
                                                 </a>
                                                 <a href="{{ route('admin.jobs.export.pdf', $job->id) }}" class="px-2.5 py-1.5 text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition flex items-center gap-1" title="Export Laporan Rekrutmen PDF (.pdf)">
                                                     <i class="fa-solid fa-file-pdf text-rose-600"></i> PDF
                                                 </a>
                                                 <a href="{{ route('admin.jobs.test.edit', $job->id) }}" class="px-2.5 py-1.5 text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition flex items-center gap-1" title="Kelola Tes Online & Bank Soal">
                                                     <i class="fa-solid fa-list-check"></i> Tes
                                                 </a>
                                                <a href="{{ route('admin.jobs.edit', $job->id) }}" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition" title="Edit Lowongan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus lowongan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition" title="Hapus Lowongan">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-8 text-center text-gray-500">
                                            Belum ada lowongan pekerjaan yang dibuat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(method_exists($jobs, 'hasPages') && $jobs->hasPages())
                        <div class="mt-6 border-t border-gray-100 pt-4">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
