<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.applications.show', $application) }}" class="w-10 h-10 bg-white hover:bg-slate-100 text-slate-600 rounded-2xl border border-slate-200 flex items-center justify-center transition shadow-2xs">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    Terbitkan Surat Rekomendasi Kerja / Paklaring / PHK
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Karyawan: <strong>{{ $employeeName }}</strong> • Posisi: <strong>{{ $jobTitle }}</strong></p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6">
                <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Formulir Penerbitan Dokumen Pengalaman Kerja / Rekomendasi / PHK</h3>
                        <p class="text-xs text-slate-500">Pilih jenis dokumen resmi yang ingin diterbitkan oleh manajemen perusahaan.</p>
                    </div>
                    <span class="px-3 py-1 bg-slate-900 text-white text-3xs font-black rounded-xl uppercase">
                        📄 Dokumen Resmi HR
                    </span>
                </div>

                <form action="{{ route('admin.applications.terminations.store', $application) }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Dokumen Resmi yang Diterbitkan <span class="text-rose-500">*</span></label>
                            <select name="document_type" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-bold py-2.5">
                                <option value="recommendation_letter" selected>🌟 Surat Rekomendasi Kerja & Referensi Karir (Job Recommendation Letter)</option>
                                <option value="paklaring_letter">📜 Surat Keterangan Pengalaman Kerja (Paklaring / Service Certificate)</option>
                                <option value="phk_letter">🔴 Surat Pemutusan Hubungan Kerja (PHK & Uang Pesangon)</option>
                                <option value="contract_expired">🟡 Surat Keterangan Selesai Masa Kontrak Kerja</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Dokumen Surat <span class="text-rose-500">*</span></label>
                            <input type="text" name="document_number" value="{{ old('document_number', $docNumber) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-bold py-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap Karyawan <span class="text-rose-500">*</span></label>
                            <input type="text" name="employee_name" value="{{ old('employee_name', $employeeName) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-bold py-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Posisi / Jabatan Pekerjaan <span class="text-rose-500">*</span></label>
                            <input type="text" name="job_title" value="{{ old('job_title', $jobTitle) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-bold py-2.5">
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">Tgl Mulai Kerja <span class="text-rose-500">*</span></label>
                                <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d', strtotime('-1 year'))) }}" required class="w-full border-slate-300 rounded-xl text-xs font-medium py-2.5">
                            </div>
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">Tgl Terakhir Kerja <span class="text-rose-500">*</span></label>
                                <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required class="w-full border-slate-300 rounded-xl text-xs font-medium py-2.5">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nominal Pesangon / Uang Kompensasi PHK (Opsional)</label>
                            <input type="text" name="severance_compensation" value="{{ old('severance_compensation') }}" placeholder="Contoh: Rp 15.000.000 (Kosongkan jika Surat Rekomendasi/Paklaring)" class="w-full border-slate-300 rounded-xl text-xs font-medium py-2.5">
                        </div>

                        <!-- RECOMMENDATION & STATEMENT TEXT AREA -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Teks Pernyataan Resmi / Ulasan Rekomendasi Karir <span class="text-rose-500">*</span></label>
                            <textarea name="reason_or_recommendation_notes" rows="6" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-mono p-4 leading-relaxed">{{ old('reason_or_recommendation_notes', "Perusahaan memberikan Surat Rekomendasi & Referensi Kerja ini secara resmi kepada yang bersangkutan. 

Selama bertugas sebagai {$jobTitle}, yang bersangkutan telah menunjukkan dedikasi, integritas, dan performa kerja yang sangat baik bagi perkembangan perusahaan.

Manajemen Perusahaan dengan bangga memberikan REKOMENDASI TERBAIK bagi yang bersangkutan untuk dapat berkarir dan memberikan kontribusi di perusahaan/instansi manapun di masa mendatang.") }}</textarea>
                        </div>

                        <div>
                            <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">Nama HRD Manager</label>
                            <input type="text" name="hr_name" value="{{ old('hr_name', Auth::user()->name) }}" placeholder="HR Manager" class="w-full border-slate-300 rounded-xl text-xs font-bold py-2">
                        </div>

                        <div>
                            <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">Nama Owner / Direktur Perusahaan</label>
                            <input type="text" name="owner_name" value="{{ old('owner_name', 'Ir. Budi Santoso') }}" placeholder="Direktur Utama" class="w-full border-slate-300 rounded-xl text-xs font-bold py-2">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Penerbitan Dokumen <span class="text-rose-500">*</span></label>
                            <input type="date" name="issued_at" value="{{ old('issued_at', date('Y-m-d')) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.applications.show', $application) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-300 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-black text-xs rounded-xl shadow-2xs transition border border-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-xs"></i> Terbitkan & Render Dokumen PDF
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
