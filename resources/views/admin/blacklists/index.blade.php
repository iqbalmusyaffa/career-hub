<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-user-shield text-rose-600"></i> Anti-Fraud Security Sentinel & Blacklist
                </h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Kelola daftar hitam email, IP address, telepon, atau perusahaan bodong untuk pencegahan penipuan.</p>
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

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Form Tambah Blacklist Item -->
            <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 p-6 sm:p-8 space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-black text-base text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-user-slash text-rose-600"></i> Tambah Item ke Daftar Hitam (Blacklist)
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium">Pengguna atau IP yang masuk daftar hitam akan otomatis dicegat oleh sistem saat login/register.</p>
                </div>

                <form action="{{ route('admin.blacklists.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Entitas Blacklist <span class="text-rose-500">*</span></label>
                            <select name="type" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-rose-500 focus:border-rose-500 font-bold p-3">
                                <option value="email">📧 Alamat Email (Email Address)</option>
                                <option value="ip">🌐 Alamat IP (IP Address Client)</option>
                                <option value="phone">📱 Nomor Telepon / WhatsApp</option>
                                <option value="company_name">🏢 Nama PT / Perusahaan Bodong</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nilai Entitas yang Diblokir <span class="text-rose-500">*</span></label>
                            <input type="text" name="value" required placeholder="Misal: penipu@gmail.com / 192.168.1.100 / PT Fake Digital" class="w-full border-slate-300 rounded-xl text-xs focus:ring-rose-500 focus:border-rose-500 font-bold p-3">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alasan Resmi Pemblokiran (Reason) <span class="text-rose-500">*</span></label>
                        <textarea name="reason" rows="2" required placeholder="Jelaskan indikasi kecurangan, penipuan, atau spamming yang dilakukan..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-rose-500 focus:border-rose-500 font-medium p-3"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-7 py-3 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                            <i class="fa-solid fa-lock"></i> Masukkan ke Daftar Hitam (Blacklist)
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Daftar Hitam Active -->
            <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-shield-cat text-rose-600"></i> Data Blacklist Aktif ({{ $blacklists->total() }})
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-3xs font-extrabold uppercase text-slate-500 tracking-wider">
                                <th class="p-4">Tgl Didaftarkan</th>
                                <th class="p-4">Tipe & Nilai Diblokir</th>
                                <th class="p-4">Alasan Pemblokiran</th>
                                <th class="p-4">Admin Pemblokir</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($blacklists as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-900">{{ $item->created_at->format('d M Y, H:i') }} WIB</div>
                                        <div class="text-3xs text-slate-400 font-medium mt-0.5">{{ $item->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 bg-rose-50 text-rose-800 text-3xs font-black rounded-lg uppercase border border-rose-200 mr-2">
                                            {{ $item->type }}
                                        </span>
                                        <code class="font-mono font-bold text-slate-900 bg-slate-100 px-2 py-1 rounded text-xs">{{ $item->value }}</code>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-200 leading-relaxed font-medium">
                                            "{{ $item->reason }}"
                                        </p>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-900">{{ $item->blocker->name ?? 'Super Admin' }}</div>
                                    </td>
                                    <td class="p-4 text-right whitespace-nowrap">
                                        <form action="{{ route('admin.blacklists.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENGHAPUS item ini dari blacklist?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-3xs rounded-xl border border-slate-300 transition">
                                                Hapus Blacklist
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-400 font-medium">
                                        Belum ada item dalam daftar hitam (blacklist).
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
