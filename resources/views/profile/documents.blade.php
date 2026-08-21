<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-folder-closed text-indigo-600"></i> Vault Berkas & Dokumen Pendukung
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola ijazah, transkrip nilai, SKCK, KTP, sertifikasi, dan portofolio untuk melengkapi verifikasi lamaran Anda.</p>
            </div>
            <a href="{{ route('profile.candidate.details.edit') }}" class="bg-white hover:bg-slate-100 text-slate-700 font-bold py-2.5 px-4 rounded-xl border border-slate-200 text-xs transition">
                &larr; Kembali ke Profil Saya
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Upload Document Form Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-black text-lg text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up text-blue-600"></i> Unggah Dokumen Pendukung Baru
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Format file didukung: PDF, JPG, PNG, DOC, DOCX (Ukuran Maksimal: 10MB per file).</p>
                </div>

                <form action="{{ route('profile.candidate.documents.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1">Kategori Jenis Dokumen</label>
                        <select name="document_type" required class="w-full text-xs font-bold border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                            <option value="ijazah">📜 Ijazah Pendidikan</option>
                            <option value="transkrip">📊 Transkrip Nilai</option>
                            <option value="ktp">🆔 KTP / Kartu Identitas</option>
                            <option value="skck">🛡️ SKCK Kepolisian</option>
                            <option value="certificate">🏅 Sertifikasi Keahlian</option>
                            <option value="portfolio">🎨 Portofolio Karya / Project</option>
                            <option value="other">📄 Berkas Pendukung Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1">Judul / Nama Dokumen</label>
                        <input type="text" name="title" required placeholder="Contoh: Ijazah S1 Teknik Informatika" class="w-full text-xs border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1">Pilih Berkas File</label>
                        <input type="file" name="document_file" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="w-full text-xs text-slate-600 border border-slate-300 rounded-xl p-2 bg-white">
                    </div>

                    <div class="md:col-span-3 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-6 py-3 rounded-xl text-xs shadow-md transition flex items-center gap-2">
                            <i class="fa-solid fa-upload"></i> Simpan Ke Vault Dokumen
                        </button>
                    </div>
                </form>
            </div>

            <!-- Documents Vault Grid Section -->
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                    <h3 class="font-black text-lg text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-box-archive text-indigo-600"></i> Berkas Terunggah di Vault Saya ({{ $documents->count() }})
                    </h3>
                </div>

                @if($documents->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($documents as $doc)
                            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs hover:shadow-md transition flex flex-col justify-between space-y-4">
                                <div class="space-y-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <span class="px-3 py-1 font-black text-3xs rounded-full border uppercase {{ $doc->type_badge_color }}">
                                            {{ $doc->type_label }}
                                        </span>
                                        <span class="text-3xs font-bold text-slate-400">
                                            {{ strtoupper($doc->file_extension ?? 'PDF') }} • {{ $doc->formatted_size }}
                                        </span>
                                    </div>

                                    <div>
                                        <h4 class="font-extrabold text-slate-900 text-base leading-snug">
                                            {{ $doc->title }}
                                        </h4>
                                        <p class="text-3xs text-slate-400 mt-1 font-medium">Diunggah: {{ ($doc->updated_at ?? $doc->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-800 font-extrabold text-xs py-2 px-3 rounded-xl transition text-center flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-eye text-blue-600"></i> Lihat Berkas
                                    </a>

                                    <form action="{{ route('profile.candidate.documents.destroy', $doc->encrypted_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus berkas dokumen ini dari Vault?');" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition border border-rose-200">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 space-y-3">
                        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center text-2xl mx-auto">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-800">Vault Dokumen Masih Kosong</h3>
                        <p class="text-xs text-slate-500">Unggah dokumen ijazah, transkrip, SKCK, atau sertifikasi Anda menggunakan form di atas.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
