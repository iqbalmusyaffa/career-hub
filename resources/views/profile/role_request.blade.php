<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-900 leading-tight flex items-center gap-3">
            <i class="fa-solid fa-building-circle-check text-blue-600"></i> {{ __('Pengajuan Akun Perusahaan / HR') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 font-bold text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Info Banner -->
            <div class="bg-gradient-to-r from-blue-900 to-indigo-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 bg-white/20 text-blue-200 text-3xs font-extrabold rounded-full uppercase">Pendaftaran Perusahaan</span>
                </div>
                <h3 class="text-2xl font-black">Ingin Membuka Lowongan & Rekrut Talenta Terbaik?</h3>
                <p class="text-xs text-blue-100 mt-2 leading-relaxed">
                    Daftarkan perusahaan Anda ke dalam platform TalentFlow. Tim Super Admin akan memverifikasi dokumen legalitas NIB / SIUP Anda untuk memberikan status <strong>Verified Company</strong>.
                </p>
            </div>

            <!-- Existing Requests Status Card -->
            @if($requests->count() > 0)
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-4">
                    <h4 class="font-black text-lg text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-blue-600"></i> Riwayat Pengajuan Anda
                    </h4>

                    <div class="space-y-3">
                        @foreach($requests as $r)
                            <div class="p-4 rounded-2xl border border-gray-100 bg-gray-50/50 flex items-center justify-between flex-wrap gap-3">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2.5 py-0.5 rounded-full text-3xs font-black uppercase border {{ $r->status_badge }}">
                                            {{ ucfirst($r->status) }}
                                        </span>
                                        <span class="text-xs font-black text-gray-900">{{ $r->company_name }}</span>
                                    </div>
                                    <span class="text-2xs text-gray-500 font-medium">Diajukan pada {{ $r->created_at->format('d M Y, H:i') }} WIB</span>
                                    @if($r->admin_notes)
                                        <div class="mt-2 p-2.5 bg-white rounded-xl border border-gray-200 text-xs text-gray-700">
                                            <strong>Catatan Admin:</strong> {{ $r->admin_notes }}
                                        </div>
                                    @endif
                                </div>

                                @if($r->legal_doc_path)
                                    <a href="{{ Storage::url($r->legal_doc_path) }}" target="_blank" class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                                        <i class="fa-solid fa-file-pdf"></i> Lihat Dokumen Legal
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Application Form -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-6">
                <h4 class="font-black text-xl text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-4">
                    <i class="fa-solid fa-file-signature text-blue-600"></i> Formulir Pengajuan Akun Perusahaan
                </h4>

                <form action="{{ route('profile.role-request.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <x-input-label for="company_name" :value="__('Nama Perusahaan Resmi')" />
                            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('company_name')" placeholder="Misal: PT TechNova Asia Digital" required />
                            <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
                        </div>

                        <div>
                            <x-input-label for="industry" :value="__('Bidang Industri')" />
                            <x-text-input id="industry" name="industry" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('industry')" placeholder="Misal: Teknologi Informasi / E-Commerce" required />
                            <x-input-error class="mt-2" :messages="$errors->get('industry')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <x-input-label for="company_size" :value="__('Jumlah Karyawan')" />
                            <select id="company_size" name="company_size" class="mt-1 block w-full border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white">
                                <option value="1-10 Karyawan">1 - 10 Karyawan (Startup / Micro)</option>
                                <option value="11-50 Karyawan">11 - 50 Karyawan (Small)</option>
                                <option value="51-200 Karyawan">51 - 200 Karyawan (Medium)</option>
                                <option value="201-500 Karyawan">201 - 500 Karyawan (Large)</option>
                                <option value="500+ Karyawan">500+ Karyawan (Enterprise)</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="phone" :value="__('Nomor Telepon Kontak Perusahaan')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('phone')" placeholder="Misal: 021-1234567 / 08123456789" required />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="address" :value="__('Alamat Lengkap Perusahaan')" />
                        <textarea id="address" name="address" rows="2" class="mt-1 block w-full border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white" placeholder="Alamat kantor pusat / operasional...">{{ old('address') }}</textarea>
                    </div>

                    <div>
                        <x-input-label for="legal_doc" :value="__('Dokumen Legalitas Perusahaan (NIB / SIUP - Format PDF)')" />
                        <input id="legal_doc" name="legal_doc" type="file" accept=".pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" required />
                        <p class="text-xs text-gray-500 mt-1">*Wajib mengunggah berkas PDF NIB / SIUP resmi untuk proses verifikasi oleh Super Admin.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('legal_doc')" />
                    </div>

                    <div>
                        <x-input-label for="notes" :value="__('Catatan Tambahan untuk Super Admin (Opsional)')" />
                        <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white" placeholder="Alasan pendaftaran atau info pendukung...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end">
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl text-sm transition shadow-lg flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan Perusahaan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
