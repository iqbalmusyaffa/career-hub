<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('mentor.unlock-requests.index') }}" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 transition">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <h2 class="font-extrabold text-xl text-slate-900 dark:text-white leading-tight">
                        Form Pengajuan Buka Kunci Presensi Magang
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Permohonan resmi pembukaan tanggal terlewat yang dikirimkan ke Super Admin.
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                
                <div class="border-b border-slate-100 dark:border-slate-700 pb-4">
                    <span class="text-3xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest block">FORMULIR PERMOHONAN RESMI</span>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mt-0.5">Pengajuan Dispensasi Tanggal Terkunci</h3>
                </div>

                @if($errors->any())
                    <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-2xl text-xs text-rose-700 dark:text-rose-300 space-y-1">
                        <span class="font-bold block">Terdapat kesalahan pengisian:</span>
                        <ul class="list-disc list-inside text-3xs space-y-0.5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('mentor.unlock-requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Field 1: Pilih Anak Magang -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2">
                            Pilih Anak Magang <span class="text-rose-500">*</span>
                        </label>
                        <select name="intern_id" required class="w-full text-xs font-bold rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 p-3.5 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500">
                            <option value="">-- Pilih Peserta Magang --</option>
                            @foreach($interns as $intern)
                                <option value="{{ $intern->id }}" {{ old('intern_id') == $intern->id ? 'selected' : '' }}>
                                    {{ $intern->name }} ({{ $intern->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Field 2: Tanggal yang Terkunci -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2">
                            Tanggal Presensi yang Terkunci <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="target_date" max="{{ now()->format('Y-m-d') }}" value="{{ old('target_date') }}" required class="w-full text-xs font-bold rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 p-3.5 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500">
                        <p class="text-3xs text-slate-400 mt-1">Hanya dapat memilih tanggal hari ini atau yang sudah lewat.</p>
                    </div>

                    <!-- Field 3: Kategori Kendala Resmi -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2">
                            Kategori Kendala Resmi <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" required class="w-full text-xs font-bold rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 p-3.5 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500">
                            <option value="platform_outage" {{ old('category') == 'platform_outage' ? 'selected' : '' }}>🌐 Gangguan Teknis Server Penyelenggara (Platform Outage)</option>
                            <option value="partner_issue" {{ old('category') == 'partner_issue' ? 'selected' : '' }}>🏢 Kendala Operasional Resmi Mitra Kantor (Pemadaman Server Total)</option>
                            <option value="force_majeure" {{ old('category') == 'force_majeure' ? 'selected' : '' }}>🚨 Keadaan Darurat Lapangan / Force Majeure</option>
                        </select>
                        <p class="text-3xs text-rose-500 font-bold mt-1">Catatan: Pilihan 'Lupa Absen' dilarang dan tidak dilayani sistem.</p>
                    </div>

                    <!-- Field 4: Uraian & Kronologi Kejadian -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2">
                            Uraian & Kronologi Kejadian <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="description" rows="4" placeholder="Jelaskan secara rinci kendala teknis atau force majeure yang terjadi pada tanggal terkait..." required class="w-full text-xs font-medium rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 p-4 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500">{{ old('description') }}</textarea>
                    </div>

                    <!-- Field 5: Upload Berkas Bukti -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2">
                            Upload Bukti Lampiran (Screenshot Error / Surat Resmi)
                        </label>
                        <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs font-semibold rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 p-2.5 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="text-3xs text-slate-400 mt-1">Format PDF, JPG, PNG (Maks 5MB).</p>
                    </div>

                    <!-- Field 6: Pernyataan Integritas Mentor -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="integrity_declaration" value="1" required class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500">
                            <span class="text-3xs text-slate-700 dark:text-slate-300 font-bold leading-relaxed">
                                Saya selaku Mentor menyatakan bahwa permohonan ini diajukan atas kendala teknis/operasional resmi dan <u>bukan akibat kelalaian atau lupa absen</u> peserta magang. Saya siap bertanggung jawab penuh atas validitas data pengajuan ini.
                            </span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <a href="{{ route('mentor.unlock-requests.index') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-200 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-7 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Permohonan ke Super Admin
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>
