<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white hover:bg-slate-100 text-slate-600 rounded-2xl border border-slate-200 flex items-center justify-center transition shadow-2xs">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    Formulir Data Karyawan & Berkas Onboarding
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Posisi: <strong>{{ $application->job->title }}</strong> • Perusahaan: <strong>{{ $application->job->company_name }}</strong></p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Banner Notification -->
            <div class="bg-slate-900 text-white p-6 rounded-3xl shadow-xl border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl shrink-0 border border-amber-500/30">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-100">Selamat! Anda Telah Dinyatakan Diterima Kerja</h3>
                        <p class="text-xs text-slate-300 mt-0.5">Lengkapi formulir di bawah ini untuk proses administrasi pencairan gaji, BPJS, dan NPWP karyawan.</p>
                    </div>
                </div>
                @if($onboarding && $onboarding->verification_status === 'verified')
                    <span class="px-3.5 py-1.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold rounded-xl shrink-0 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check"></i> Terverifikasi HR
                    </span>
                @else
                    <span class="px-3.5 py-1.5 bg-amber-500/20 text-amber-300 border border-amber-500/30 text-xs font-bold rounded-xl shrink-0 flex items-center gap-1.5">
                        <i class="fa-solid fa-clock"></i> Menunggu Verifikasi
                    </span>
                @endif
            </div>

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl space-y-1 text-xs font-bold shadow-2xs">
                    <div class="flex items-center gap-2 text-rose-900">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                        <span>Mohon periksa kembali formulir Anda:</span>
                    </div>
                    <ul class="list-disc pl-5 font-normal text-rose-700">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- MAIN FORM CARD -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80">
                <form action="{{ route('candidate.onboarding.store', $application) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <!-- SECTION 1: BANK DETAILS -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                            <i class="fa-solid fa-building-columns text-slate-800 text-lg"></i>
                            <h3 class="text-base font-bold text-slate-900">1. Data Rekening Bank (Penggajian)</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Bank <span class="text-rose-500">*</span></label>
                                <select name="bank_name" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                                    <option value="">-- Pilih Bank --</option>
                                    @foreach(['BCA', 'Mandiri', 'BNI', 'BRI', 'CIMB Niaga', 'Bank Permata', 'Bank Syariah Indonesia (BSI)', 'Bank Danamon', 'Lainnya'] as $bnk)
                                        <option value="{{ $bnk }}" {{ old('bank_name', $onboarding->bank_name ?? '') == $bnk ? 'selected' : '' }}>{{ $bnk }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Rekening <span class="text-rose-500">*</span></label>
                                <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $onboarding->bank_account_number ?? '') }}" required placeholder="Contoh: 1234567890" class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Pemilik Rekening <span class="text-rose-500">*</span></label>
                                <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder', $onboarding->bank_account_holder ?? '') }}" required placeholder="Nama harus sesuai dengan buku tabungan" class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                            </div>
                        </div>
                    </div>

                    @php
                        $isInternship = Str::contains(strtolower($application->job->work_type ?? ''), ['intern', 'magang']) || Str::contains(strtolower($application->job->title ?? ''), ['intern', 'magang']);
                    @endphp

                    <!-- SECTION FOR INTERNSHIP / MAGANG DATA -->
                    @if($isInternship)
                        <div class="space-y-4 pt-4 border-t border-slate-100 bg-amber-50/60 p-4 rounded-2xl border border-amber-200/80">
                            <div class="flex items-center gap-2 border-b border-amber-200/80 pb-3">
                                <i class="fa-solid fa-graduation-cap text-amber-700 text-lg"></i>
                                <div>
                                    <h3 class="text-base font-bold text-amber-900">2. Data Akademik & Surat Pengantar Magang Kampus</h3>
                                    <p class="text-2xs text-amber-700">Khusus peserta magang / internship, wajib melengkapi data institusi pendidikan.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIM / NIS (Nomor Induk Mahasiswa/Siswa) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="student_id_number" value="{{ old('student_id_number', $onboarding->student_id_number ?? '') }}" {{ $isInternship ? 'required' : '' }} placeholder="Contoh: 2101012345" class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Asal Perguruan Tinggi / Universitas / Sekolah <span class="text-rose-500">*</span></label>
                                    <input type="text" name="institution_name" value="{{ old('institution_name', $onboarding->institution_name ?? '') }}" {{ $isInternship ? 'required' : '' }} placeholder="Contoh: Universitas Indonesia / ITB" class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Unggah Surat Pengantar / Surat Tugas Magang dari Kampus (PDF / PNG / JPG)</label>
                                    <input type="file" name="internship_letter_doc" accept=".pdf,.png,.jpg,.jpeg" class="w-full border border-slate-300 rounded-xl text-xs p-2 bg-white">
                                    @if($onboarding && $onboarding->internship_letter_doc_path)
                                        <div class="mt-1 text-3xs font-semibold text-emerald-700 flex items-center gap-1">
                                            <i class="fa-solid fa-paperclip"></i> Dokumen terunggah: 
                                            <a href="{{ Storage::url($onboarding->internship_letter_doc_path) }}" target="_blank" class="underline font-bold">Lihat Surat Pengantar Magang</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- SECTION 2: TAX & NPWP -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                            <i class="fa-solid fa-file-invoice-dollar text-slate-800 text-lg"></i>
                            <h3 class="text-base font-bold text-slate-900">2. Nomor Pokok Wajib Pajak (NPWP)</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor NPWP (15 atau 16 digit)</label>
                                <input type="text" name="npwp_number" value="{{ old('npwp_number', $onboarding->npwp_number ?? '') }}" placeholder="Contoh: 12.345.678.9-012.000" class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Unggah Dokumen NPWP (PDF / PNG / JPG)</label>
                                <input type="file" name="npwp_doc" accept=".pdf,.png,.jpg,.jpeg" class="w-full border border-slate-300 rounded-xl text-xs p-2 bg-slate-50">
                                @if($onboarding && $onboarding->npwp_doc_path)
                                    <div class="mt-1 text-3xs font-semibold text-emerald-700 flex items-center gap-1">
                                        <i class="fa-solid fa-paperclip"></i> Dokumen terunggah: 
                                        <a href="{{ Storage::url($onboarding->npwp_doc_path) }}" target="_blank" class="underline font-bold">Lihat File NPWP</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: BPJS KESEHATAN & KETENAGAKERJAAN -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                            <i class="fa-solid fa-heart-pulse text-slate-800 text-lg"></i>
                            <h3 class="text-base font-bold text-slate-900">3. BPJS Kesehatan & Ketenagakerjaan</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- BPJS Kesehatan -->
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Nomor BPJS Kesehatan</label>
                                <input type="text" name="bpjs_kesehatan_number" value="{{ old('bpjs_kesehatan_number', $onboarding->bpjs_kesehatan_number ?? '') }}" placeholder="Nomor Kartu BPJS Kesehatan" class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                                <label class="block text-2xs font-semibold text-slate-500 uppercase">Unggah Kartu BPJS Kesehatan (PDF / PNG / JPG)</label>
                                <input type="file" name="bpjs_kesehatan_doc" accept=".pdf,.png,.jpg,.jpeg" class="w-full border border-slate-300 rounded-xl text-xs p-2 bg-slate-50">
                                @if($onboarding && $onboarding->bpjs_kesehatan_doc_path)
                                    <div class="text-3xs font-semibold text-emerald-700 flex items-center gap-1">
                                        <i class="fa-solid fa-paperclip"></i> File: 
                                        <a href="{{ Storage::url($onboarding->bpjs_kesehatan_doc_path) }}" target="_blank" class="underline font-bold">Kartu BPJS Kesehatan</a>
                                    </div>
                                @endif
                            </div>

                            <!-- BPJS Ketenagakerjaan -->
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Nomor BPJS Ketenagakerjaan (TK)</label>
                                <input type="text" name="bpjs_ketenagakerjaan_number" value="{{ old('bpjs_ketenagakerjaan_number', $onboarding->bpjs_ketenagakerjaan_number ?? '') }}" placeholder="Nomor KPJ BPJSTK" class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                                <label class="block text-2xs font-semibold text-slate-500 uppercase">Unggah Kartu BPJSTK (PDF / PNG / JPG)</label>
                                <input type="file" name="bpjs_ketenagakerjaan_doc" accept=".pdf,.png,.jpg,.jpeg" class="w-full border border-slate-300 rounded-xl text-xs p-2 bg-slate-50">
                                @if($onboarding && $onboarding->bpjs_ketenagakerjaan_doc_path)
                                    <div class="text-3xs font-semibold text-emerald-700 flex items-center gap-1">
                                        <i class="fa-solid fa-paperclip"></i> File: 
                                        <a href="{{ Storage::url($onboarding->bpjs_ketenagakerjaan_doc_path) }}" target="_blank" class="underline font-bold">Kartu BPJSTK</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: FAMILY CARD & ADDITIONAL NOTES -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                            <i class="fa-solid fa-users text-slate-800 text-lg"></i>
                            <h3 class="text-base font-bold text-slate-900">4. Kartu Keluarga (KK) & Catatan Tambahan</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Kartu Keluarga (KK)</label>
                                <input type="text" name="family_card_number" value="{{ old('family_card_number', $onboarding->family_card_number ?? '') }}" placeholder="16 Digit Nomor Kartu Keluarga" class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Unggah Dokumen Kartu Keluarga (PDF / PNG / JPG)</label>
                                <input type="file" name="family_card_doc" accept=".pdf,.png,.jpg,.jpeg" class="w-full border border-slate-300 rounded-xl text-xs p-2 bg-slate-50">
                                @if($onboarding && $onboarding->family_card_doc_path)
                                    <div class="mt-1 text-3xs font-semibold text-emerald-700 flex items-center gap-1">
                                        <i class="fa-solid fa-paperclip"></i> File: 
                                        <a href="{{ Storage::url($onboarding->family_card_doc_path) }}" target="_blank" class="underline font-bold">Kartu Keluarga</a>
                                    </div>
                                @endif
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Tambahan Untuk HR (Opsional)</label>
                                <textarea name="notes" rows="3" placeholder="Tuliskan catatan tambahan jika ada..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium p-3">{{ old('notes', $onboarding->notes ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- SUBMIT ACTION -->
                    <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-xs text-slate-500">
                            <i class="fa-solid fa-shield-halved text-slate-700 mr-1"></i>
                            Data Anda dienkripsi dan terlindungi oleh Kebijakan Privasi perusahaan.
                        </p>
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2 border border-slate-900">
                            <i class="fa-solid fa-paper-plane text-amber-400"></i> Simpan & Kirim Data Onboarding
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>
