<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-900 leading-tight flex items-center gap-3">
            <i class="fa-solid fa-building-circle-check text-slate-700"></i> {{ __('Pengajuan Akun Perusahaan / HR') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-slate-50/50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-emerald-800 dark:text-emerald-300 font-bold text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-lg"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Info Banner -->
            <div class="bg-slate-900 dark:bg-slate-950 border border-slate-800 text-white rounded-2xl p-6 sm:p-8 shadow-2xs">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 bg-white/10 text-slate-200 text-3xs font-bold rounded-lg uppercase border border-white/10">Pendaftaran Perusahaan & HR</span>
                </div>
                <h3 class="text-xl font-bold">Ingin Membuka Lowongan & Rekrut Talenta Terbaik?</h3>
                <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                    Daftarkan perusahaan Anda atau ajukan status staf HR ke dalam platform. Tim Super Admin akan memverifikasi dokumen legalitas resmi untuk memberikan status <strong>Verified Company</strong>.
                </p>
            </div>

            <!-- Existing Requests Status Card -->
            @if($requests->count() > 0)
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xs border border-slate-200 dark:border-slate-700 space-y-4">
                    <h4 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-slate-700 dark:text-slate-300"></i> Riwayat Pengajuan Anda
                    </h4>

                    <div class="space-y-3">
                        @foreach($requests as $r)
                            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between flex-wrap gap-3">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2.5 py-0.5 rounded-full text-3xs font-bold uppercase border {{ $r->status_badge }}">
                                            {{ ucfirst($r->status) }}
                                        </span>
                                        <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $r->company_name }}</span>
                                    </div>
                                    <span class="text-2xs text-slate-500 dark:text-slate-400 font-medium">Diajukan pada {{ $r->created_at->format('d M Y, H:i') }} WIB</span>
                                    @if($r->admin_notes)
                                        <div class="mt-2 p-2.5 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 text-xs text-slate-700 dark:text-slate-300">
                                            <strong>Catatan Admin:</strong> {{ $r->admin_notes }}
                                        </div>
                                    @endif
                                </div>

                                @if($r->legal_doc_path)
                                    <a href="{{ Storage::url($r->legal_doc_path) }}" target="_blank" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-xl text-xs font-bold transition flex items-center gap-1.5 border border-slate-300 dark:border-slate-600">
                                        <i class="fa-solid fa-file-pdf text-rose-600"></i> Lihat Dokumen Legal
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Application Form -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xs border border-slate-200 dark:border-slate-700 space-y-6">
                <h4 class="font-bold text-lg text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-4">
                    <i class="fa-solid fa-file-signature text-slate-700 dark:text-slate-300"></i> Formulir Pengajuan Akun Perusahaan / HR
                </h4>

                <form action="{{ route('profile.role-request.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ requestedRole: '{{ old('requested_role', 'Company Owner') }}' }">
                    @csrf

                    <div>
                        <x-input-label for="requested_role" :value="__('Peran / Role Yang Diajukan')" />
                        <select id="requested_role" name="requested_role" x-model="requestedRole" class="mt-1 block w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-slate-800 focus:border-slate-800 bg-slate-50/50 focus:bg-white">
                            <option value="Company Owner">Company Owner (Pemilik Perusahaan / Founder / Direktur)</option>
                            <option value="HR">HR Specialist (Staf HR / Recruiter Perusahaan)</option>
                        </select>
                        <p class="text-3xs text-slate-500 mt-1" x-show="requestedRole === 'Company Owner'">
                            *Role <strong>Company Owner</strong> memiliki wewenang penuh mengelola profil perusahaan, tim HR, serta data keuangan/rek bank.
                        </p>
                        <p class="text-3xs text-slate-500 mt-1" x-show="requestedRole === 'HR'">
                            *Role <strong>HR Specialist</strong> berfokus pada membuat lowongan kerja, meninjau pelamar, dan menguji kandidat (Tidak mengisi data bank).
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <x-input-label for="company_name" :value="__('Nama Perusahaan Resmi')" />
                            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full bg-slate-50/50 focus:bg-white text-xs border-slate-300 rounded-xl" :value="old('company_name')" placeholder="Misal: PT TechNova Asia Digital" required />
                            <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
                        </div>

                        <div>
                            <x-input-label for="industry" :value="__('Bidang Industri')" />
                            <x-text-input id="industry" name="industry" type="text" class="mt-1 block w-full bg-slate-50/50 focus:bg-white text-xs border-slate-300 rounded-xl" :value="old('industry')" placeholder="Misal: Teknologi Informasi / E-Commerce" required />
                            <x-input-error class="mt-2" :messages="$errors->get('industry')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <x-input-label for="company_size" :value="__('Jumlah Karyawan')" />
                            <select id="company_size" name="company_size" class="mt-1 block w-full border-slate-300 rounded-xl text-xs focus:ring-slate-800 focus:border-slate-800 bg-slate-50/50 focus:bg-white">
                                <option value="1-10 Karyawan">1 - 10 Karyawan (Startup / Micro)</option>
                                <option value="11-50 Karyawan">11 - 50 Karyawan (Small)</option>
                                <option value="51-200 Karyawan">51 - 200 Karyawan (Medium)</option>
                                <option value="201-500 Karyawan">201 - 500 Karyawan (Large)</option>
                                <option value="500+ Karyawan">500+ Karyawan (Enterprise)</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="phone" :value="__('Nomor Telepon Kontak Perusahaan')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full bg-slate-50/50 focus:bg-white text-xs border-slate-300 rounded-xl" :value="old('phone')" placeholder="Misal: 021-1234567 / 08123456789" required />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="address" :value="__('Alamat Lengkap Perusahaan')" />
                        <textarea id="address" name="address" rows="2" class="mt-1 block w-full border-slate-300 rounded-xl text-xs focus:ring-slate-800 focus:border-slate-800 bg-slate-50/50 focus:bg-white" placeholder="Alamat kantor pusat / operasional...">{{ old('address') }}</textarea>
                    </div>

                    <!-- Rekening Bank & NPWP (Hanya muncul jika Role yang diajukan adalah Company Owner) -->
                    <div x-show="requestedRole === 'Company Owner'" class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-4">
                        <div class="flex items-center gap-2 text-slate-800 font-bold text-xs">
                            <i class="fa-solid fa-building-columns text-slate-700"></i>
                            <span>Informasi Rekening Bank & NPWP Perusahaan (Khusus Role Company Owner - Opsional)</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="bank_name" :value="__('Nama Bank')" />
                                <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full bg-white text-xs border-slate-300 rounded-xl" :value="old('bank_name')" placeholder="Misal: Bank BCA / Mandiri / BNI" />
                            </div>

                            <div>
                                <x-input-label for="bank_account_number" :value="__('Nomor Rekening')" />
                                <x-text-input id="bank_account_number" name="bank_account_number" type="text" class="mt-1 block w-full bg-white text-xs border-slate-300 rounded-xl" :value="old('bank_account_number')" placeholder="Misal: 1234567890" />
                            </div>

                            <div>
                                <x-input-label for="bank_account_name" :value="__('Atas Nama Rekening')" />
                                <x-text-input id="bank_account_name" name="bank_account_name" type="text" class="mt-1 block w-full bg-white text-xs border-slate-300 rounded-xl" :value="old('bank_account_name')" placeholder="Misal: PT TechNova Asia Digital" />
                            </div>

                            <div>
                                <x-input-label for="npwp_number" :value="__('Nomor NPWP Perusahaan')" />
                                <x-text-input id="npwp_number" name="npwp_number" type="text" class="mt-1 block w-full bg-white text-xs border-slate-300 rounded-xl" :value="old('npwp_number')" placeholder="Misal: 01.234.567.8-901.000" />
                            </div>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="legal_doc" :value="__('Dokumen Legalitas Perusahaan (NIB / SIUP - Format PDF)')" />
                        <input id="legal_doc" name="legal_doc" type="file" accept=".pdf" class="mt-1 block w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer" required />
                        <p class="text-xs text-slate-500 mt-1">*Wajib mengunggah berkas PDF NIB / SIUP resmi untuk proses verifikasi oleh Super Admin.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('legal_doc')" />
                    </div>

                    <div>
                        <x-input-label for="notes" :value="__('Catatan Tambahan untuk Super Admin (Opsional)')" />
                        <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full border-slate-300 rounded-xl text-xs focus:ring-slate-800 focus:border-slate-800 bg-slate-50/50 focus:bg-white" placeholder="Alasan pendaftaran atau info pendukung...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex items-center justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition shadow-2xs flex items-center gap-2 border border-slate-900">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan Perusahaan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
