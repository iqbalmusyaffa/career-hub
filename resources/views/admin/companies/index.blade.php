<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-building-circle-check text-blue-600"></i> Master Control Perusahaan Mitra
                </h2>
                <p class="text-xs text-slate-500 mt-1">Super Admin memiliki kontrol penuh atas verifikasi legalitas, edit profil, suspensi, dan hapus perusahaan mitra.</p>
            </div>
            <div class="text-xs font-bold text-slate-600 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-2xs">
                Total Perusahaan: <span class="text-blue-600 font-extrabold text-sm">{{ $companies->total() }}</span>
            </div>
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

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Search & Filtering Bar -->
            <div class="bg-white p-6 rounded-3xl shadow-2xs border border-slate-200/80">
                <form method="GET" action="{{ route('admin.companies.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-600 uppercase mb-1">Cari Perusahaan</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama perusahaan..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-600 uppercase mb-1">Status Verifikasi SIUP</label>
                        <select name="verification" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status Verifikasi</option>
                            <option value="verified" {{ request('verification') == 'verified' ? 'selected' : '' }}>Verified (Centang Biru)</option>
                            <option value="unverified" {{ request('verification') == 'unverified' ? 'selected' : '' }}>Belum Verifikasi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-600 uppercase mb-1">Status Operasional</label>
                        <select name="status" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Diblokir / Suspended</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-600 uppercase mb-1">Baris per Halaman</label>
                        <select name="per_page" onchange="this.form.submit()" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Data per Halaman</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Data per Halaman</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Data per Halaman</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Data per Halaman</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-2xs">
                            <i class="fa-solid fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.companies.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-4 rounded-xl text-xs transition text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Companies List Table -->
            <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 overflow-hidden" x-data="{ activeEditModal: null }">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-building text-blue-600"></i> Daftar Perusahaan Terdaftar ({{ $companies->total() }})
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-3xs font-bold text-slate-400 uppercase tracking-wider">
                                <th class="p-4 pl-6">Perusahaan</th>
                                <th class="p-4">Pemilik (Owner)</th>
                                <th class="p-4">Industri & Ukuran</th>
                                <th class="p-4">Dokumen Legalitas</th>
                                <th class="p-4">Status Verifikasi</th>
                                <th class="p-4">Status Akun</th>
                                <th class="p-4 pr-6 text-right">Master Control Super Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700 font-medium">
                            @forelse($companies as $company)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-4 pl-6 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @if($company->logo_path)
                                                <img src="{{ Storage::url($company->logo_path) }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 shadow-2xs shrink-0">
                                            @else
                                                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white font-black flex items-center justify-center text-sm shrink-0 shadow-2xs">
                                                    {{ strtoupper(substr($company->company_name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-extrabold text-slate-900 text-sm flex items-center gap-1.5">
                                                    {{ $company->company_name }}
                                                    @if($company->is_verified)
                                                        <i class="fa-solid fa-circle-check text-blue-500 text-xs" title="Verified Perusahaan Official"></i>
                                                    @endif
                                                </div>
                                                @if($company->website)
                                                    <a href="{{ $company->website }}" target="_blank" class="text-3xs text-blue-600 hover:underline">{{ $company->website }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        @if($company->user)
                                            <div class="font-bold text-slate-900">{{ $company->user->name }}</div>
                                            <div class="text-3xs text-slate-400">{{ $company->user->email }}</div>
                                        @else
                                            <span class="text-slate-400 italic">Owner Tidak Ada</span>
                                        @endif
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="font-semibold text-slate-800">{{ $company->industry ?? 'Umum' }}</div>
                                        <div class="text-3xs text-slate-400">{{ $company->company_size ?? '-' }}</div>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        @if($company->legal_doc_path)
                                            <a href="{{ Storage::url($company->legal_doc_path) }}" target="_blank" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-3xs rounded-xl border border-blue-200 transition inline-flex items-center gap-1">
                                                <i class="fa-solid fa-file-pdf text-red-500"></i> Unduh SIUP/NIB
                                            </a>
                                        @else
                                            <span class="text-3xs italic text-slate-400">Belum Unggah</span>
                                        @endif
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        @if($company->is_verified)
                                            <span class="px-2.5 py-1 bg-blue-50 text-blue-800 font-black rounded-lg border border-blue-200 text-3xs">
                                                ✓ VERIFIED
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 font-bold rounded-lg border border-slate-200 text-3xs">
                                                BELUM VERIFIKASI
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        @if($company->is_suspended)
                                            <span class="px-2.5 py-1 bg-rose-50 text-rose-800 font-black rounded-lg border border-rose-200 text-3xs">
                                                SUSPENDED
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 font-bold rounded-lg border border-emerald-200 text-3xs">
                                                AKTIF
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 pr-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            
                                            <!-- MASTER EDIT BUTTON -->
                                            <button @click="activeEditModal = {{ $company->id }}" class="px-2.5 py-1.5 bg-slate-900 hover:bg-black text-white font-bold text-3xs rounded-xl transition shadow-2xs flex items-center gap-1" title="Edit Data Perusahaan (Master Override)">
                                                <i class="fa-solid fa-pen-to-square text-amber-400"></i> Edit Master
                                            </button>

                                            <!-- TOGGLE VERIFY -->
                                            <form method="POST" action="{{ route('admin.companies.toggle-verify', $company->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                @if($company->is_verified)
                                                    <button type="submit" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-3xs rounded-xl transition border border-slate-200" title="Cabut Verifikasi Centang Biru">
                                                        Cabut Verified
                                                    </button>
                                                @else
                                                    <button type="submit" class="px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-3xs rounded-xl shadow-2xs transition" title="Berikan Verifikasi Centang Biru">
                                                        <i class="fa-solid fa-check mr-0.5"></i> Verifikasi
                                                    </button>
                                                @endif
                                            </form>

                                            <!-- TOGGLE SUSPEND -->
                                            <form method="POST" action="{{ route('admin.companies.toggle-suspend', $company->id) }}" onsubmit="return confirm('{{ $company->is_suspended ? 'Buka blokir perusahaan ini?' : 'PERINGATAN! Blokir / Suspend perusahaan ini?' }}');" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                @if($company->is_suspended)
                                                    <button type="submit" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-3xs rounded-xl shadow-2xs transition">
                                                        Unsuspend
                                                    </button>
                                                @else
                                                    <button type="submit" class="px-2.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-3xs rounded-xl shadow-2xs transition">
                                                        Suspend
                                                    </button>
                                                @endif
                                            </form>

                                            <!-- MASTER DELETE -->
                                            <form method="POST" action="{{ route('admin.companies.destroy', $company->id) }}" onsubmit="return confirm('PERINGATAN BAHAYA! Hapus permanen profil perusahaan {{ $company->company_name }} beserta seluruh data lowongannya?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-3xs rounded-xl transition" title="Hapus Perusahaan Permanen">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>

                                <!-- MASTER EDIT MODAL FOR EACH COMPANY -->
                                <div x-show="activeEditModal === {{ $company->id }}" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
                                    <div @click.away="activeEditModal = null" class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100 text-left">
                                        <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                                            <h4 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                                                <i class="fa-solid fa-building-flag text-blue-600"></i> Master Edit Data Perusahaan: {{ $company->company_name }}
                                            </h4>
                                            <button @click="activeEditModal = null" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
                                        </div>

                                        <form action="{{ route('admin.companies.update', $company->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                                            @csrf
                                            @method('PUT')

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block font-bold text-slate-700 mb-1">Nama Perusahaan</label>
                                                    <input type="text" name="company_name" value="{{ old('company_name', $company->company_name) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold">
                                                </div>
                                                <div>
                                                    <label class="block font-bold text-slate-700 mb-1">Sektor / Industri</label>
                                                    <input type="text" name="industry" value="{{ old('industry', $company->industry) }}" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label class="block font-bold text-slate-700 mb-1">Jumlah Karyawan / Ukuran</label>
                                                    <input type="text" name="company_size" value="{{ old('company_size', $company->company_size) }}" placeholder="1-50 Karyawan" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label class="block font-bold text-slate-700 mb-1">No. Telepon Perusahaan</label>
                                                    <input type="text" name="phone" value="{{ old('phone', $company->phone) }}" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label class="block font-bold text-slate-700 mb-1">Website Resmi</label>
                                                    <input type="url" name="website" value="{{ old('website', $company->website) }}" placeholder="https://company.com" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label class="block font-bold text-slate-700 mb-1">Link Google Maps Kantor</label>
                                                    <input type="text" name="google_maps_link" value="{{ old('google_maps_link', $company->google_maps_link) }}" placeholder="https://maps.google.com/?q=..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Alamat Kantor Resmi</label>
                                                <input type="text" name="address" value="{{ old('address', $company->address) }}" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Deskripsi & Profil Perusahaan</label>
                                                <textarea name="description" rows="3" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">{{ old('description', $company->description) }}</textarea>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                                                <div>
                                                    <label class="block font-bold text-slate-700 mb-1">Update Logo Perusahaan</label>
                                                    <input type="file" name="logo" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                </div>
                                                <div>
                                                    <label class="block font-bold text-slate-700 mb-1">Update Berkas Legal SIUP/NIB</label>
                                                    <input type="file" name="legal_doc" accept=".pdf,image/*" class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                                </div>
                                            </div>

                                            <div class="flex justify-end gap-2 pt-4">
                                                <button type="button" @click="activeEditModal = null" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                                                    Batal
                                                </button>
                                                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-xl text-xs shadow-2xs transition">
                                                    Simpan Perubahan Master
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-400 text-xs font-medium">
                                        Tidak ada perusahaan ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($companies->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50">
                        {{ $companies->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
