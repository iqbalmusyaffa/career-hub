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

            <!-- 2 Major Choice Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- 1. Panduan Pelamar Kerja -->
                <div class="bg-gradient-to-br from-blue-50/50 via-white to-slate-50/50 p-8 rounded-3xl border border-blue-100 hover:border-blue-300 hover:shadow-lg transition-all group flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-2xl shadow-sm mb-6 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600 block mb-1">Untuk Kandidat</span>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-3">Panduan Pelamar Kerja & Magang</h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                            Panduan lengkap mulai dari pembuatan CV online ATS, pencarian lowongan terpercaya, pengiriman lamaran kerja, tes asesmen online, hingga tanda tangan kontrak kerja digital.
                        </p>

                        <div class="space-y-2.5 mb-8 text-xs text-slate-700">
                            <div class="flex items-center gap-2.5 font-medium">
                                <i class="fa-solid fa-circle-check text-blue-600 text-xs"></i>
                                <span>Cara Membuat & Mengunduh CV ATS</span>
                            </div>
                            <div class="flex items-center gap-2.5 font-medium">
                                <i class="fa-solid fa-circle-check text-blue-600 text-xs"></i>
                                <span>Tips Lolos Screening & Tes Ujian Online</span>
                            </div>
                            <div class="flex items-center gap-2.5 font-medium">
                                <i class="fa-solid fa-circle-check text-blue-600 text-xs"></i>
                                <span>Pengisian Logbook & Penerbitan Sertifikat</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('pages.guide.candidate') }}" class="w-full py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                        Buka Panduan Pelamar Lengkap <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <!-- 2. Panduan Penyelenggara Lowongan -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-8 rounded-3xl border border-slate-700 hover:shadow-xl transition-all group flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-blue-500 text-white flex items-center justify-center text-2xl shadow-sm mb-6 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-400 block mb-1">Untuk Perusahaan & HR</span>
                        <h2 class="text-xl sm:text-2xl font-bold text-white mb-3">Panduan Penyelenggara Lowongan</h2>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-6">
                            Petunjuk komprehensif bagi tim HR & perusahaan untuk verifikasi legalitas, memasang lowongan kerja, membuat modul soal tes online, mengelola pipeline kandidat, dan onboarding.
                        </p>

                        <div class="space-y-2.5 mb-8 text-xs text-slate-300">
                            <div class="flex items-center gap-2.5 font-medium">
                                <i class="fa-solid fa-circle-check text-blue-400 text-xs"></i>
                                <span>Verifikasi Profil & Legalitas Instansi</span>
                            </div>
                            <div class="flex items-center gap-2.5 font-medium">
                                <i class="fa-solid fa-circle-check text-blue-400 text-xs"></i>
                                <span>Manajemen Pipeline Funnel & Asesmen</span>
                            </div>
                            <div class="flex items-center gap-2.5 font-medium">
                                <i class="fa-solid fa-circle-check text-blue-400 text-xs"></i>
                                <span>Penerbitan Offer Letter & Kontrak OTP</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('pages.guide.employer') }}" class="w-full py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                        Buka Panduan Penyelenggara Lengkap <i class="fa-solid fa-arrow-right text-xs"></i>
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

