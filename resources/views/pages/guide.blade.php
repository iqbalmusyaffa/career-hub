<x-public-layout>
    <!-- Hero Header -->
    <div class="bg-gradient-to-b from-blue-50/60 via-slate-50/40 to-white pt-14 pb-16 border-b border-slate-200/80">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold mb-4 border border-blue-200 shadow-2xs">
                <i class="fa-solid fa-book-open text-xs text-blue-600"></i> Pusat Panduan & Edukasi
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
                Pusat Panduan Penggunaan Platform
            </h1>
            <p class="text-slate-600 text-xs sm:text-base leading-relaxed max-w-2xl mx-auto">
                Pilih panduan sesuai kebutuhan Anda. Kami menyediakan petunjuk lengkap langkah demi langkah baik bagi pencari kerja maupun perusahaan penyelenggara lowongan.
            </p>
        </div>
    </div>

    <!-- Guide Category Cards -->
    <div class="py-14 bg-white min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

            <!-- 4 Major Choice Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- 1. Panduan Pelamar Kerja -->
                <div class="bg-gradient-to-br from-blue-50/50 via-white to-slate-50/50 p-7 rounded-3xl border border-blue-100 hover:border-blue-300 hover:shadow-lg transition-all group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-xl shadow-xs mb-5 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600 block mb-1">Untuk Kandidat & Pelamar</span>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-2">Panduan Pelamar Kerja & Magang</h2>
                        <p class="text-xs text-slate-600 leading-relaxed mb-5">
                            Panduan lengkap mulai dari pembuatan CV online ATS, pencarian lowongan terpercaya, tes asesmen online, hingga tanda tangan kontrak kerja digital.
                        </p>

                        <div class="space-y-2 mb-6 text-xs text-slate-700">
                            <div class="flex items-center gap-2 font-medium">
                                <i class="fa-solid fa-circle-check text-blue-600 text-xs"></i>
                                <span>Pembuatan & Download CV Standar ATS</span>
                            </div>
                            <div class="flex items-center gap-2 font-medium">
                                <i class="fa-solid fa-circle-check text-blue-600 text-xs"></i>
                                <span>Alur Ujian Online & Video Pitch</span>
                            </div>
                            <div class="flex items-center gap-2 font-medium">
                                <i class="fa-solid fa-circle-check text-blue-600 text-xs"></i>
                                <span>Pengisian Logbook Harian & Onboarding</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('pages.guide.candidate') }}" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                        Buka Panduan Pelamar <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <!-- 2. Panduan Penyelenggara Lowongan -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-7 rounded-3xl border border-slate-700 hover:shadow-xl transition-all group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-500 text-white flex items-center justify-center text-xl shadow-xs mb-5 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-blue-400 block mb-1">Untuk Perusahaan & HR</span>
                        <h2 class="text-lg sm:text-xl font-bold text-white mb-2">Panduan Penyelenggara Lowongan</h2>
                        <p class="text-xs text-slate-300 leading-relaxed mb-5">
                            Petunjuk komprehensif bagi tim HR untuk verifikasi legalitas, memasang lowongan, membuat soal tes online, mengelola pipeline seleksi, dan payroll magang.
                        </p>

                        <div class="space-y-2 mb-6 text-xs text-slate-300">
                            <div class="flex items-center gap-2 font-medium">
                                <i class="fa-solid fa-circle-check text-blue-400 text-xs"></i>
                                <span>Verifikasi Legalitas & Profil Instansi</span>
                            </div>
                            <div class="flex items-center gap-2 font-medium">
                                <i class="fa-solid fa-circle-check text-blue-400 text-xs"></i>
                                <span>Manajemen Pipeline Funnel & Asesmen</span>
                            </div>
                            <div class="flex items-center gap-2 font-medium">
                                <i class="fa-solid fa-circle-check text-blue-400 text-xs"></i>
                                <span>Penerbitan Kontrak OTP & Transfer Uang Saku</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('pages.guide.employer') }}" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                        Buka Panduan Penyelenggara <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <!-- 3. Panduan Mentor Magang -->
                <div class="bg-gradient-to-br from-purple-50/50 via-white to-indigo-50/30 p-7 rounded-3xl border border-purple-100 hover:border-purple-300 hover:shadow-lg transition-all group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-purple-600 text-white flex items-center justify-center text-xl shadow-xs mb-5 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-purple-600 block mb-1">Untuk Pembimbing / Mentor</span>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-2">Panduan Mentor & Pembimbing Magang</h2>
                        <p class="text-xs text-slate-600 leading-relaxed mb-5">
                            Tata cara memeriksa laporan presensi harian mentee, menyetujui (ACC), memvalidasi modul silabus kurikulum, serta mengajukan uang saku bulanan.
                        </p>

                        <div class="space-y-2 mb-6 text-xs text-slate-700">
                            <div class="flex items-center gap-2 font-medium">
                                <i class="fa-solid fa-circle-check text-purple-600 text-xs"></i>
                                <span>Persetujuan (ACC) Presensi & Logbook</span>
                            </div>
                            <div class="flex items-center gap-2 font-medium">
                                <i class="fa-solid fa-circle-check text-purple-600 text-xs"></i>
                                <span>Validasi Kelulusan Modul Silabus</span>
                            </div>
                            <div class="flex items-center gap-2 font-medium">
                                <i class="fa-solid fa-circle-check text-purple-600 text-xs"></i>
                                <span>Rekomendasi Uang Saku & Scorecard</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('pages.guide.mentor') }}" class="w-full py-2.5 px-4 bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                        Buka Panduan Mentor <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <!-- 4. Aturan & Kebijakan Magang -->
                <div class="bg-gradient-to-br from-indigo-50/50 via-white to-blue-50/30 p-7 rounded-3xl border border-indigo-100 hover:border-indigo-300 hover:shadow-lg transition-all group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl shadow-xs mb-5 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600 block mb-1">Tata Tertib & Kebijakan</span>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-2">Aturan & Ketentuan Program Magang</h2>
                        <p class="text-xs text-slate-600 leading-relaxed mb-5">
                            Pedoman resmi mengenai jam kerja, batas pengisian 23:59 WIB, hari libur nasional, batas toleransi izin 4 hari, dan formula pemotongan uang saku.
                        </p>

                        <div class="space-y-2 mb-6 text-xs text-slate-700">
                            <div class="flex items-center gap-2 font-medium">
                                <i class="fa-solid fa-circle-check text-indigo-600 text-xs"></i>
                                <span>Toleransi Izin / Sakit Maksimal 4 Hari</span>
                            </div>
                            <div class="flex items-center gap-2 font-medium">
                                <i class="fa-solid fa-circle-check text-indigo-600 text-xs"></i>
                                <span>Perhitungan Pemotongan Uang Saku</span>
                            </div>
                            <div class="flex items-center gap-2 font-medium">
                                <i class="fa-solid fa-circle-check text-indigo-600 text-xs"></i>
                                <span>Ketentuan Validasi Rekening Bank KTP</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('pages.guide.rules') }}" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                        Baca Aturan & Kebijakan <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- FAQ Banner Callout -->
            <div class="p-8 bg-slate-50/80 rounded-3xl border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Masih memiliki pertanyaan seputar fitur platform?</h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Kunjungi Pusat Bantuan (FAQ) kami untuk membaca jawaban atas pertanyaan yang sering diajukan.</p>
                </div>
                <a href="{{ route('pages.faq') }}" class="px-6 py-3 bg-white hover:bg-slate-100 text-slate-800 font-semibold text-xs rounded-xl border border-slate-300 shadow-2xs transition shrink-0 flex items-center gap-2">
                    <i class="fa-solid fa-circle-question text-blue-600"></i> Buka FAQ & Bantuan
                </a>
            </div>

        </div>
    </div>
</x-public-layout>

