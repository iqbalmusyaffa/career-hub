<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-bullhorn text-blue-600"></i> Broadcast Center & Pengumuman Global
                </h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Kirim pengumuman resmi dan banner siaran ke seluruh pengguna platform.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-4 rounded-xl text-xs transition border border-slate-200">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form Buat Pengumuman Baru -->
            <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 p-6 sm:p-8 space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-black text-base text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-blue-600"></i> Buat & Siarkan Pengumuman Baru
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium">Isi judul, isi materi pengumuman, dan tentukan target penerima siaran.</p>
                </div>

                <form action="{{ route('admin.announcements.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Judul Pengumuman <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" required placeholder="Misal: Pemeliharaan Server Sistem 24 Agustus 2026" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold p-3">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Banner Alert <span class="text-rose-500">*</span></label>
                            <select name="type" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold p-3">
                                <option value="info">🔵 Informasi (Info - Biru)</option>
                                <option value="warning">🟡 Peringatan (Warning - Kuning)</option>
                                <option value="success">🟢 Pengumuman Positif (Success - Hijau)</option>
                                <option value="danger">🔴 Penting / Darurat (Danger - Merah)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Target Pengguna Penerima <span class="text-rose-500">*</span></label>
                            <select name="target_role" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold p-3">
                                <option value="all">🌐 Seluruh Pengguna (All Users)</option>
                                <option value="company_owner">🏢 Pimpinan Perusahaan (Company Owner)</option>
                                <option value="hr">💼 Tim HR & Recruiter</option>
                                <option value="candidate">👨‍🎓 Pencari Kerja (Candidates)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Kedaluwarsa (Opsional)</label>
                            <input type="date" name="expires_at" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-medium p-3">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Materi Pengumuman Lengkap <span class="text-rose-500">*</span></label>
                        <textarea name="content" rows="3" required placeholder="Tuliskan materi siaran pengumuman di sini..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-medium p-3"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-7 py-3 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Publikasikan & Broadcast Pengumuman
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Daftar Pengumuman -->
            <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-blue-600"></i> Riwayat Siaran Pengumuman ({{ $announcements->total() }})
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-3xs font-extrabold uppercase text-slate-500 tracking-wider">
                                <th class="p-4">Tgl Dipublikasikan</th>
                                <th class="p-4">Judul & Isi Pengumuman</th>
                                <th class="p-4">Target Penerima</th>
                                <th class="p-4">Tipe Alert</th>
                                <th class="p-4">Status Tayang</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($announcements as $ann)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-900">{{ $ann->created_at->format('d M Y, H:i') }} WIB</div>
                                        <div class="text-3xs text-slate-400 font-medium mt-0.5">Oleh: {{ $ann->creator->name ?? 'Super Admin' }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-extrabold text-slate-900 text-sm">{{ $ann->title }}</div>
                                        <p class="text-3xs text-slate-500 mt-1 line-clamp-2">{{ strip_tags($ann->content) }}</p>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-800 text-3xs font-extrabold rounded-lg uppercase border border-slate-200">
                                            {{ $ann->target_role }}
                                        </span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 text-3xs font-black rounded-lg uppercase border 
                                            {{ $ann->type === 'danger' ? 'bg-rose-50 text-rose-800 border-rose-200' : '' }}
                                            {{ $ann->type === 'warning' ? 'bg-amber-50 text-amber-800 border-amber-200' : '' }}
                                            {{ $ann->type === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : '' }}
                                            {{ $ann->type === 'info' ? 'bg-blue-50 text-blue-800 border-blue-200' : '' }}">
                                            {{ $ann->type }}
                                        </span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        @if($ann->is_active)
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-black rounded-lg text-3xs border border-emerald-200">TAYANG AKTIF</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 font-bold rounded-lg text-3xs border border-slate-200">NONAKTIF</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <form action="{{ route('admin.announcements.toggle', $ann) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-3xs rounded-xl border border-slate-300 transition">
                                                    {{ $ann->is_active ? 'Sembunyikan' : 'Tayangkan' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.announcements.destroy', $ann) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs rounded-xl border border-rose-200 transition">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-400 font-medium">
                                        Belum ada pengumuman global yang disiarkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
