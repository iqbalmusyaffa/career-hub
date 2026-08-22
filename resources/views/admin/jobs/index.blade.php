<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-briefcase text-blue-600"></i> Kelola Lowongan Pekerjaan
                </h2>
                <p class="text-xs text-slate-600 mt-1 font-medium">Daftar lowongan kerja yang aktif & dipublikasikan oleh perusahaan.</p>
            </div>
            <a href="{{ route('admin.jobs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs flex items-center gap-1.5 border border-blue-600">
                <i class="fa-solid fa-plus"></i> Tambah Lowongan Baru
            </a>
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

            <!-- DataTables Filter & Search Bar -->
            <div class="bg-white p-6 rounded-3xl shadow-2xs border border-slate-200/80">
                <form method="GET" action="{{ route('admin.jobs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-3xs tracking-wider">Cari Lowongan</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Posisi, divisi, lokasi..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-medium">
                    </div>

                    @if(auth()->user()->hasRole('Super Admin'))
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-3xs tracking-wider">Filter Perusahaan (PT)</label>
                        <input type="text" name="company_name" value="{{ request('company_name') }}" placeholder="Misal: PT TechNova..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-medium">
                    </div>
                    @endif

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-3xs tracking-wider">Filter Syarat Jurusan</label>
                        <x-indonesia-majors-select name="major" value="{{ request('major') }}" placeholder="Cari / Pilih Jurusan..." />
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-3xs tracking-wider">Status Lowongan</label>
                        <select name="status" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-semibold">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif (Tayang)</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Tutup</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-3xs tracking-wider">Baris per Halaman</label>
                        <select name="per_page" onchange="this.form.submit()" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-extrabold py-2.5 px-4 rounded-xl text-xs transition border border-slate-900">
                            <i class="fa-solid fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.jobs.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-4 rounded-xl text-xs transition text-center border border-slate-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-600 text-3xs font-extrabold uppercase tracking-wider">
                                    <th class="p-4">Posisi & Divisi</th>
                                    <th class="p-4">Lokasi</th>
                                    <th class="p-4">Tipe Kerja</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4 text-center">Total Pelamar</th>
                                    <th class="p-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @forelse($jobs as $job)
                                    <tr class="hover:bg-blue-50/50 transition group">
                                        <td class="p-4">
                                            <a href="{{ route('admin.applications.index') }}?job_id={{ $job->id }}" class="font-extrabold text-slate-900 text-sm group-hover:text-blue-600 transition block">
                                                {{ $job->title }}
                                            </a>
                                            <div class="text-3xs text-slate-500 font-medium mt-0.5">Divisi: {{ $job->division ?? '-' }} • Dibuat {{ $job->created_at->format('d M Y') }}</div>
                                        </td>
                                        <td class="p-4 font-medium text-slate-700">{{ $job->location }}</td>
                                        <td class="p-4">
                                            <span class="px-2.5 py-1 bg-blue-50 text-blue-800 text-3xs font-bold rounded-lg border border-blue-200 uppercase">
                                                {{ ucfirst($job->work_type) }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            @if($job->status == 'active')
                                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 text-3xs font-black rounded-lg border border-emerald-200">AKTIF</span>
                                            @else
                                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-3xs font-black rounded-lg border border-slate-200">TUTUP</span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-center">
                                            <a href="{{ route('admin.applications.index') }}?job_id={{ $job->id }}" class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-900 font-extrabold text-xs border border-slate-200 transition shadow-2xs group/btn cursor-pointer">
                                                <i class="fa-solid fa-users mr-1.5 text-3xs"></i> {{ $job->applications_count }} Pelamar &rarr;
                                            </a>
                                        </td>
                                        <td class="p-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                 <a href="{{ route('admin.jobs.export.excel', $job) }}" class="p-2 text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition" title="Export Excel (.csv)">
                                                     <i class="fa-solid fa-file-excel text-xs"></i>
                                                 </a>
                                                 <a href="{{ route('admin.jobs.export.pdf', $job) }}" class="p-2 text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition" title="Export PDF (.pdf)">
                                                     <i class="fa-solid fa-file-pdf text-xs"></i>
                                                 </a>
                                                 <a href="{{ route('admin.jobs.test.edit', $job) }}" class="p-2 text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl transition" title="Kelola Tes Online">
                                                     <i class="fa-solid fa-list-check text-xs"></i>
                                                 </a>
                                                <a href="{{ route('admin.jobs.edit', $job) }}" class="p-2 text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl transition" title="Edit Lowongan">
                                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                </a>
                                                <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus lowongan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition" title="Hapus Lowongan">
                                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-8 text-center text-slate-400 font-medium">
                                            Belum ada lowongan pekerjaan yang dibuat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(method_exists($jobs, 'hasPages') && $jobs->hasPages())
                        <div class="mt-6 border-t border-slate-100 pt-4">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
