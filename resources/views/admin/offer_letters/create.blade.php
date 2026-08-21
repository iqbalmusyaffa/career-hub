<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-file-contract text-emerald-600"></i> Buat Surat Penawaran Kerja (Offer Letter)
                </h2>
                <p class="text-xs text-gray-500 mt-1">Kandidat: <span class="font-bold text-gray-800">{{ $application->user->name }}</span> | Posisi: <span class="font-bold text-blue-600">{{ $application->job->title }}</span></p>
            </div>
            <a href="{{ route('admin.applications.show', $application->id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-xl text-sm transition">
                &larr; Kembali ke Detail Pelamar
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.applications.offer-letter.store', $application->id) }}" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-10 space-y-6">
                @csrf

                <div class="border-b border-gray-100 pb-4">
                    <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-pen-ruler text-blue-600"></i> Form Kompensasi & Ketentuan Pekerjaan
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Surat penawaran kerja akan di-generate otomatis dalam bentuk dokumen PDF berformat resmi.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Gaji Nominal Yang Ditawarkan (Rp)</label>
                        <input type="text" name="offered_salary" value="{{ old('offered_salary', $application->job->salary) }}" placeholder="Contoh: 12.000.000" required class="w-full border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm font-bold text-emerald-700">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Mulai Bekerja (Start Date)</label>
                        <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d', strtotime('+14 days'))) }}" required class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Batas Waktu Konfirmasi Kandidat</label>
                        <input type="date" name="expiration_date" value="{{ old('expiration_date', date('Y-m-d', strtotime('+7 days'))) }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi Penempatan Kantor</label>
                        <input type="text" name="work_location" value="{{ old('work_location', $application->job->location) }}" placeholder="Contoh: Jakarta Selatan / Remote" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Ringkasan Fasilitas & Benefit (Tunjangan, BPJS, Asuransi, THR)</label>
                        <textarea name="benefits_summary" rows="3" placeholder="Contoh: Tunjangan Kesehatan BPJS + Asuransi Swasta, THR 1x Gaji, Laptop Perusahaan, Bonus Kinerja Tahunan..." class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">{{ old('benefits_summary') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Catatan Tambahan & Persyaratan Dokumen (Opsional)</label>
                        <textarea name="additional_notes" rows="3" placeholder="Contoh: Harap membawa dokumen fisik SKCK asli dan Ijazah saat hari pertama orientasi..." class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">{{ old('additional_notes') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                    <a href="{{ route('admin.applications.show', $application->id) }}" class="bg-white hover:bg-gray-100 text-gray-700 font-bold py-3 px-6 rounded-xl border border-gray-200 text-sm transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition text-sm flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf"></i> Generate & Kirim Offer Letter PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
