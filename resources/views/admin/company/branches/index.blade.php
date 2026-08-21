<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-gray-900 leading-tight flex items-center gap-3">
                <i class="fa-solid fa-code-branch text-blue-600"></i> {{ __('Manajemen Cabang & Anak Perusahaan') }}
            </h2>
            <button onclick="document.getElementById('addBranchModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition shadow-md flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Cabang Baru
            </button>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 font-bold text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Company Info Banner -->
            <div class="bg-gradient-to-r from-slate-900 to-blue-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl flex items-center justify-between flex-wrap gap-4">
                <div>
                    <span class="px-3 py-1 bg-white/20 text-blue-200 text-3xs font-extrabold rounded-full uppercase tracking-wider">Multi-Branch System</span>
                    <h3 class="text-2xl font-black mt-2">{{ $companyProfile->company_name ?: 'PT TechNova Asia Digital' }}</h3>
                    <p class="text-xs text-slate-300 mt-1">Kelola seluruh lokasi kantor cabang, anak perusahaan, dan lokasi penempatan lowongan kerja dalam satu dasbor terpadu.</p>
                </div>
                <div class="text-right">
                    <span class="text-3xl font-black text-amber-400">{{ $branches->count() }}</span>
                    <span class="text-xs text-slate-300 block font-semibold">Total Kantor / Cabang</span>
                </div>
            </div>

            <!-- Branches Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($branches as $b)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 rounded-full text-3xs font-black uppercase {{ $b->is_headquarter ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-100' }}">
                                    {{ $b->is_headquarter ? '⭐ Kantor Pusat' : '🏢 Kantor Cabang' }}
                                </span>
                                <span class="text-xs text-gray-400 font-semibold flex items-center gap-1">
                                    <i class="fa-solid fa-briefcase text-blue-500"></i> {{ $b->jobs_count }} Lowongan
                                </span>
                            </div>

                            <h4 class="font-black text-lg text-gray-900 mb-1">{{ $b->branch_name }}</h4>
                            <p class="text-xs font-bold text-blue-600 mb-3"><i class="fa-solid fa-location-dot"></i> {{ $b->city }}</p>

                            @if($b->address)
                                <p class="text-xs text-gray-600 mb-2 leading-relaxed"><i class="fa-solid fa-map-pin text-gray-400"></i> {{ $b->address }}</p>
                            @endif

                            <div class="space-y-1 text-2xs text-gray-500 font-medium">
                                @if($b->phone)
                                    <div><i class="fa-solid fa-phone text-gray-400"></i> {{ $b->phone }}</div>
                                @endif
                                @if($b->email)
                                    <div><i class="fa-solid fa-envelope text-gray-400"></i> {{ $b->email }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-3xs text-gray-400 font-medium">Ditambahkan {{ $b->created_at->format('d M Y') }}</span>
                            <form action="{{ route('admin.company.branches.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus cabang ini? Lowongan terkait tidak akan terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 transition">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 rounded-3xl text-center border border-gray-100">
                        <i class="fa-solid fa-building-circle-exclamation text-4xl text-gray-300 mb-3"></i>
                        <h4 class="font-bold text-gray-700 text-lg">Belum Ada Kantor Cabang</h4>
                        <p class="text-xs text-gray-500 mt-1 mb-4">Tambahkan kantor cabang atau lokasi penempatan anak perusahaan Anda di sini.</p>
                        <button onclick="document.getElementById('addBranchModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl inline-flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Tambah Cabang Pertama
                        </button>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Modal Tambah Cabang -->
    <div id="addBranchModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="font-black text-gray-900 text-lg flex items-center gap-2">
                    <i class="fa-solid fa-building text-blue-600"></i> Tambah Kantor Cabang
                </h3>
                <button onclick="document.getElementById('addBranchModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.company.branches.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Nama Cabang / Anak Perusahaan</label>
                    <input type="text" name="branch_name" required placeholder="Contoh: Kantor Cabang Surabaya / PT TechNova Digital Bali" class="w-full border-gray-300 rounded-xl text-sm font-bold focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Kota / Wilayah Penempatan</label>
                    <input type="text" name="city" required placeholder="Contoh: Kota Surabaya / Kota Tangerang" class="w-full border-gray-300 rounded-xl text-sm font-bold focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Alamat Lengkap (Opsional)</label>
                    <textarea name="address" rows="2" placeholder="Jl. Raya Utama No. 123..." class="w-full border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Telepon (Opsional)</label>
                        <input type="text" name="phone" placeholder="031-1234567" class="w-full border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Email (Opsional)</label>
                        <input type="email" name="email" placeholder="surabaya@company.com" class="w-full border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_headquarter" id="is_headquarter" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="is_headquarter" class="text-xs font-extrabold text-gray-700">Tandai sebagai Kantor Pusat (Headquarter)</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('addBranchModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-md">
                        Simpan Cabang
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
