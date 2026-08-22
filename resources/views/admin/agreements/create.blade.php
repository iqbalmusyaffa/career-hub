@php
    $isInternship = Str::contains(strtolower($application->job->work_type ?? ''), ['intern', 'magang']) || Str::contains(strtolower($application->job->title ?? ''), ['intern', 'magang']);
    $isRemote = Str::contains(strtolower($application->job->work_type ?? ''), ['remote', 'wfh']);
    $isHybrid = Str::contains(strtolower($application->job->work_type ?? ''), ['hybrid']);
    $isPermanent = Str::contains(strtolower($application->job->work_type ?? ''), ['permanent', 'tetap']);

    if ($isInternship) {
        $defaultType = 'internship_agreement';
        $defaultTitle = 'Surat Perjanjian Magang Kerja (Internship Agreement)';
        $defaultTerms = "PASAL 1: KETENTUAN HUBUNGAN & DURASI PROGRAM MAGANG
1. Pihak Pertama menerima Pihak Kedua sebagai Peserta Magang (Internship) untuk posisi yang tercantum pada surat ini.
2. Jangka waktu magang berlangsung sesuai tanggal mulai hingga tanggal selesai yang disepakati bersama.
3. Hubungan hukum ini adalah Hubungan Pelatihan Kerja/Magang Akademik dan bukan hubungan kerja tetap ketenagakerjaan.

PASAL 2: HAK UANG SAKU (STIPEND) & BENEFIT MAGANG
1. Pihak Kedua berhak menerima Uang Saku Insentif Magang bulanan sebesar nominal yang tercantum pada dokumen ini.
2. Pihak Kedua berhak mendapatkan bimbingan mentor professional, akses fasilitas kerja, serta Sertifikat Magang Resmi di akhir periode magang yang berhasil diselesaikan.

PASAL 3: HAK KEKAYAAN INTELEKTUAL (HAKI / IPR)
1. Seluruh hasil karya, kode program, desain, dokumen teknis, dan ide yang dibuat Pihak Kedua selama masa magang sepenuhnya menjadi Hak Milik Intelektual Pihak Pertama (Perusahaan).

PASAL 4: KERAHASIAAN DATA & NON-DISCLOSURE AGREEMENT (NDA STRICT)
1. Pihak Kedua dilarang keras membocorkan, menyalin, atau menyebarluaskan data rahasia, kode sumber (source code), dan strategi bisnis Pihak Pertama kepada pihak manapun.

PASAL 5: TATA TERTIB & EVALUASI KERJA
1. Pihak Kedua wajib mematuhi jam kerja/magang, menjaga etika profesionalisme, serta mengikuti arahan Mentor dan HRD.
2. Pihak Pertama berhak melakukan evaluasi kinerja berkala terhadap Pihak Kedua.

PASAL 6: PENGHENTIAN PROGRAM MAGANG
1. Pihak Pertama berhak menghentikan program magang secara sepihak apabila Pihak Kedua melakukan pelanggaran berat, tindakan kriminal, atau membocorkan rahasia perusahaan.";
    } elseif ($isRemote) {
        $defaultType = 'remote_contract';
        $defaultTitle = 'Surat Perjanjian Kerja Remote / Work From Home (Remote Work & NDA)';
        $defaultTerms = "PASAL 1: KETENTUAN KERJA REMOTE & LOKASI DOMISILI
1. Pihak Pertama menerima Pihak Kedua untuk bekerja secara Remote / Work From Home (WFH) dari lokasi domisili Pihak Kedua.
2. Pihak Kedua wajib aktif pada saluran komunikasi resmi perusahaan (Slack/Teams/Email) selama Jam Kerja Inti (Core Hours: 09.00 - 17.00 WIB).

PASAL 2: FASILITAS PERANGKAT KERJA & TUNJANGAN INTERNET
1. Pihak Pertama menyediakan fasilitas laptop kerja/alat pendukung kerja sesuai standar posisi Pihak Kedua.
2. Pihak Pertama memberikan Tunjangan Komunikasi & Koneksi Internet Bulanan bersamaan dengan pembayaran gaji pokok.

PASAL 3: KEAMANAN SIBER & AKSES VPN PERUSAHAAN (CYBERSECURITY)
1. Pihak Kedua wajib menggunakan jaringan terenkripsi (VPN Perusahaan) saat menguji atau mengakses database & server Pihak Pertama.
2. Pihak Kedua dilarang menggunakan jaringan Wi-Fi publik tanpa proteksi VPN saat menyelesaikan pekerjaan perusahaan.

PASAL 4: KERAHASIAAN DATA & NON-DISCLOSURE AGREEMENT (NDA STRICT)
1. Seluruh informasi teknis, basis data pelanggan, dan kode sumber aplikasi milik Pihak Pertama bersifat RAHASIA PERUSAHAAN dan dilarang disebarluaskan.

PASAL 5: HAK GAJI, BENEFIT & BPJS KARYAWAN REMOTE
1. Pihak Pertama membayarkan Hak Gaji Pokok, Kepersertaan BPJS Kesehatan & BPJS Ketenagakerjaan, serta THR Keagamaan sesuai hukum yang berlaku.

PASAL 6: PENYELESAIAN PERSELISIHAN & HUKUM YANG BERLAKU
1. Perjanjian ini tunduk pada Hukum Republik Indonesia. Setiap perselisihan diselesaikan secara musyawarah mufakat.";
    } elseif ($isHybrid) {
        $defaultType = 'hybrid_contract';
        $defaultTitle = 'Surat Perjanjian Kerja Hybrid (Hybrid Work & Flexible Policy)';
        $defaultTerms = "PASAL 1: KETENTUAN HUBUNGAN KERJA HYBRID & PEMBAGIAN HARI KERJA
1. Pihak Pertama menerima Pihak Kedua untuk bekerja dengan skema Kerja Hybrid (kombinasi Work From Office / WFO dan Work From Home / WFH).
2. Pembagian hari kerja diatur secara fleksibel sesuai jadwal giliran divisi yang ditentukan oleh Atasan Direct Manager.

PASAL 2: KEHADIRAN KANTOR (WFO) & KETENTUAN FLEKSIBEL (WFH)
1. Pihak Kedua wajib hadir secara fisik di kantor Pihak Pertama pada jadwal piket WFO.
2. Selama hari WFH, Pihak Kedua wajib standby dan merespons komunikasi pada Jam Kerja Inti (Core Hours: 09.00 - 17.00 WIB).

PASAL 3: PERANGKAT KERJA & TUNJANGAN OPERASIONAL
1. Pihak Pertama memfasilitasi perangkat kerja pendukung (laptop kantor) dan tunjangan operasional internet/komunikasi.

PASAL 4: KEAMANAN SIBER & KERAHASIAAN (CYBERSECURITY & NDA STRICT)
1. Pihak Kedua wajib terhubung ke VPN Perusahaan saat mengakses server/data Pihak Pertama selama periode WFH.

PASAL 5: GAJI, BENEFIT & BPJS KARYAWAN HYBRID
1. Pihak Pertama memberikan Hak Gaji Pokok bulanan, Kepersertaan BPJS Kesehatan & BPJS Ketenagakerjaan, serta THR Keagamaan.

PASAL 6: TATA TERTIB & HUKUM YANG BERLAKU
1. Perjanjian ini tunduk dan ditafsirkan berdasarkan Hukum Ketenagakerjaan Republik Indonesia.";
    } elseif ($isPermanent) {
        $defaultType = 'permanent_contract';
        $defaultTitle = 'Surat Perjanjian Kerja Waktu Tidak Tertentu (PKWTT / Karyawan Tetap)';
        $defaultTerms = "PASAL 1: HUBUNGAN KERJA TETAP & MASA EVALUASI PROBATION
1. Pihak Pertama mengangkat Pihak Kedua sebagai Karyawan Tetap (PKWTT) untuk posisi yang ditentukan.
2. Pihak Kedua menjalani Masa Evaluasi Kinerja (Probation) selama 3 (tiga) bulan sejak tanggal mulai kerja.

PASAL 2: HAK GAJI, TUNJANGAN & THR KEAGAMAAN
1. Pihak Pertama membayarkan Gaji Pokok bulanan sebesar nominal yang tercantum pada perjanjian ini.
2. Pihak Kedua berhak mendapatkan jaminan sosial BPJS Kesehatan, BPJS Ketenagakerjaan, serta Tunjangan Hari Raya (THR) Keagamaan sesuai UU Ketenagakerjaan.

PASAL 3: HAK CUTI TAHUNAN & IZIN RESMI
1. Pihak Kedua berhak atas Cuti Tahunan sebanyak 12 (dua belas) hari kerja setelah menjalani masa kerja 12 bulan berturut-turut.

PASAL 4: TATA TERTIB & KODE ETIK PERUSAHAAN
1. Pihak Kedua wajib mematuhi seluruh Peraturan Perusahaan (PP), SOP Kinerja, dan Kode Etik Profesionalisme Pihak Pertama.

PASAL 5: KERAHASIAAN DATA & HAK KEKAYAAN INTELEKTUAL
1. Seluruh penemuan, sistem, dan hasil kerja Pihak Kedua menjadi Hak Milik Intelektual Pihak Pertama secara penuh.

PASAL 6: PEMUTUSAN HUBUNGAN KERJA (PHK) & PESANGON
1. Ketentuan PHK, uang pesangon, dan uang penghargaan masa kerja diatur sesuai ketentuan Undang-Undang Ketenagakerjaan Republik Indonesia.";
    } else {
        $defaultType = 'employment_contract';
        $defaultTitle = 'Surat Perjanjian Kerja Waktu Tertentu (PKWT / Kontrak Kerja)';
        $defaultTerms = "PASAL 1: KETENTUAN KONTRAK KERJA (PKWT) & JANGKA WAKTU
1. Pihak Pertama menerima Pihak Kedua sebagai Karyawan Kontrak (PKWT) terhitung sejak tanggal mulai hingga tanggal selesai kontrak.

PASAL 2: HAK GAJI & COMPENSATIONAL BENEFIT
1. Pihak Pertama memberikan Hak Gaji Pokok bulanan dan Fasilitas BPJS Ketenagakerjaan & BPJS Kesehatan.
2. Di akhir masa berlaku kontrak yang selesai, Pihak Kedua berhak mendapatkan Uang Kompensasi PKWT sesuai Peraturan Pemerintah No. 35 Tahun 2021.

PASAL 3: TATA TERTIB & JAM KERJA
1. Pihak Kedua wajib mematuhi jam kerja resmi kantor serta menyelesaikan target kinerja yang ditetapkan oleh Atasan Direct Manager.

PASAL 4: KERAHASIAAN DATA & KODE SUMBER PERUSAHAAN
1. Pihak Kedua wajib menjaga kerahasiaan seluruh dokumen internal dan data perusahaan selama maupun setelah masa kontrak berakhir.

PASAL 5: PEMUTUSAN KONTRAK SEBELUM WAKTUNYA
1. Apabila salah satu pihak menghentikan kontrak sebelum jangka waktu berakhir tanpa alasan hukum, pihak yang menghentikan wajib memberikan kompensasi ganti rugi sisa masa kontrak.

PASAL 6: HUKUM YANG BERLAKU
1. Perjanjian Kerja Waktu Tertentu ini tunduk dan ditafsirkan berdasarkan Hukum Ketenagakerjaan Republik Indonesia.";
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.applications.show', $application) }}" class="w-10 h-10 bg-white hover:bg-slate-100 text-slate-600 rounded-2xl border border-slate-200 flex items-center justify-center transition shadow-2xs">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    Buat Surat Perjanjian Digital (Contract Builder)
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Kandidat: <strong>{{ $application->user->name }}</strong> • Posisi: <strong>{{ $application->job->title }}</strong> ({{ $application->job->work_type }})</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 space-y-6">
                <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Formulir Penyusunan Dokumen Perjanjian Kerja / Magang</h3>
                        <p class="text-xs text-slate-500">Kandidat akan menerima dokumen ini dan diminta memberikan tanda tangan digital (E-Signature).</p>
                    </div>
                    <span class="px-3 py-1 bg-amber-100 text-amber-900 text-3xs font-black rounded-xl uppercase">
                        {{ $isInternship ? '🎓 Mode Perjanjian Magang' : '💼 Mode Kontrak Kerja (PKWT)' }}
                    </span>
                </div>

                <form action="{{ route('admin.applications.agreements.store', $application) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipe Perjanjian <span class="text-rose-500">*</span></label>
                            <select name="agreement_type" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-medium py-2.5">
                                <option value="employment_contract" {{ old('agreement_type', $defaultType) == 'employment_contract' ? 'selected' : '' }}>💼 Surat Perjanjian Kerja Waktu Tertentu (PKWT / Kontrak)</option>
                                <option value="permanent_contract" {{ old('agreement_type', $defaultType) == 'permanent_contract' ? 'selected' : '' }}>🏢 Surat Perjanjian Kerja Waktu Tidak Tertentu (PKWTT / Karyawan Tetap)</option>
                                <option value="remote_contract" {{ old('agreement_type', $defaultType) == 'remote_contract' ? 'selected' : '' }}>🏠 Surat Perjanjian Kerja Remote / WFH (Remote Work & NDA)</option>
                                <option value="hybrid_contract" {{ old('agreement_type', $defaultType) == 'hybrid_contract' ? 'selected' : '' }}>🔀 Surat Perjanjian Kerja Hybrid (Hybrid Work & Flexible Policy)</option>
                                <option value="internship_agreement" {{ old('agreement_type', $defaultType) == 'internship_agreement' ? 'selected' : '' }}>🎓 Surat Perjanjian Magang (Internship Agreement)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Dokumen Perjanjian <span class="text-rose-500">*</span></label>
                            <input type="text" name="contract_number" value="{{ old('contract_number', $contractNumber) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-bold py-2.5">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Dokumen Perjanjian <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $defaultTitle) }}" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-bold py-2.5">
                        </div>

                        <!-- SIGNATORY 1: HR MANAGER -->
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3 sm:col-span-1">
                            <h4 class="text-xs font-black uppercase text-slate-800 flex items-center gap-1.5 border-b border-slate-200 pb-2">
                                👤 Penanda Tangan 1: HRD / HR Manager
                            </h4>
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">Nama HRD / Manager <span class="text-rose-500">*</span></label>
                                <input type="text" name="hr_name" value="{{ old('hr_name', auth()->user()->name) }}" required class="w-full border-slate-300 rounded-xl text-xs font-medium">
                            </div>
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">Jabatan Resmi <span class="text-rose-500">*</span></label>
                                <input type="text" name="hr_title" value="{{ old('hr_title', 'Head of Human Resources') }}" required class="w-full border-slate-300 rounded-xl text-xs font-medium">
                            </div>
                        </div>

                        <!-- SIGNATORY 2: COMPANY OWNER / DIRECTOR -->
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3 sm:col-span-1">
                            <h4 class="text-xs font-black uppercase text-slate-800 flex items-center gap-1.5 border-b border-slate-200 pb-2">
                                🏛️ Penanda Tangan 2: Owner / Direktur Utama
                            </h4>
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">Nama Direktur / Owner <span class="text-rose-500">*</span></label>
                                <input type="text" name="owner_name" value="{{ old('owner_name', $companyOwner->name ?? $application->job->company_name) }}" required class="w-full border-slate-300 rounded-xl text-xs font-medium">
                            </div>
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 uppercase mb-1">Jabatan Direksi <span class="text-rose-500">*</span></label>
                                <input type="text" name="owner_title" value="{{ old('owner_title', 'Direktur Utama') }}" required class="w-full border-slate-300 rounded-xl text-xs font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:col-span-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Mulai Efektif <span class="text-rose-500">*</span></label>
                                <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required class="w-full border-slate-300 rounded-xl text-xs font-bold py-2.5">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Berakhir (Kosongkan jika Karyawan Tetap)</label>
                                <input type="date" name="end_date" value="{{ old('end_date', $isInternship ? date('Y-m-d', strtotime('+3 months')) : date('Y-m-d', strtotime('+1 year'))) }}" class="w-full border-slate-300 rounded-xl text-xs font-bold py-2.5">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Gaji Pokok / Uang Saku Bulanan <span class="text-rose-500">*</span></label>
                            <input type="text" name="salary_offered" value="{{ old('salary_offered', $application->job->salary ?? 'Rp 5.000.000 / bulan') }}" required class="w-full border-slate-300 rounded-xl text-xs font-bold py-2.5" placeholder="Misal: Rp 4.500.000 (Sesuai UMK 2026)">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pasal & Ketentuan Perjanjian Legal Lengkap (Legal Terms & Clauses) <span class="text-rose-500">*</span></label>
                            <textarea name="terms_content" rows="16" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 font-mono p-4 leading-relaxed">{{ old('terms_content', $defaultTerms) }}</textarea>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.applications.show', $application) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-300 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-black text-xs rounded-xl shadow-2xs transition border border-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane text-amber-400"></i> Terbitkan & Kirim Ke Kandidat
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
