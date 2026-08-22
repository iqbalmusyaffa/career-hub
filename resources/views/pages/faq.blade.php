<x-public-layout>
    <!-- Hero Header -->
    <div class="bg-slate-900 text-white pt-16 pb-20 relative overflow-hidden border-b border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center relative z-10">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-blue-500/10 text-blue-400 text-xs font-bold uppercase tracking-wider mb-4 border border-blue-500/20">
                <i class="fa-solid fa-circle-question"></i> Help Center & FAQ
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4">
                Pusat Bantuan & <span class="text-blue-400">Pertanyaan Populer</span>
            </h1>
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl mx-auto">
                Temukan jawaban cepat atas berbagai pertanyaan mengenai lamaran kerja, alur rekrutmen perusahaan, keamanan dokumen, dan penggunaan platform TalentFlow.
            </p>
        </div>
    </div>

    <!-- Main FAQ Content -->
    <div class="py-16 bg-slate-50/60 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- Category 1: Untuk Pelamar Kerja -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xs border border-slate-200/80 space-y-6">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-slate-900 text-lg">Pertanyaan Pelamar Kerja (Candidate)</h2>
                        <p class="text-xs text-slate-500">Panduan mengajukan lamaran, tes online, dan status onboarding.</p>
                    </div>
                </div>

                <div class="space-y-4" x-data="{ active: 1 }">
                    <!-- Q1 -->
                    <div class="border border-slate-200/80 rounded-2xl overflow-hidden transition">
                        <button @click="active = (active === 1 ? 0 : 1)" class="w-full text-left p-4 sm:p-5 bg-slate-50/60 hover:bg-slate-100/60 flex items-center justify-between gap-4 font-bold text-slate-900 text-sm">
                            <span>1. Bagaimana cara melamar pekerjaan di TalentFlow?</span>
                            <i class="fa-solid" :class="active === 1 ? 'fa-chevron-up text-blue-600' : 'fa-chevron-down text-slate-400'"></i>
                        </button>
                        <div x-show="active === 1" x-collapse class="p-4 sm:p-5 text-xs text-slate-600 leading-relaxed bg-white border-t border-slate-100">
                            Untuk melamar pekerjaan, cari posisi yang sesuai di halaman <a href="{{ route('jobs.index') }}" class="text-blue-600 font-bold hover:underline">Cari Lowongan</a>, klik detail lowongan, lalu tekan tombol <strong>"Kirim Lamaran Sekarang"</strong>. Anda dapat melampirkan berkas CV, sertifikat, dan mengunggah video perkenalan singkat.
                        </div>
                    </div>

                    <!-- Q2 -->
                    <div class="border border-slate-200/80 rounded-2xl overflow-hidden transition">
                        <button @click="active = (active === 2 ? 0 : 2)" class="w-full text-left p-4 sm:p-5 bg-slate-50/60 hover:bg-slate-100/60 flex items-center justify-between gap-4 font-bold text-slate-900 text-sm">
                            <span>2. Apa itu Match Score (%) pada lamaran saya?</span>
                            <i class="fa-solid" :class="active === 2 ? 'fa-chevron-up text-blue-600' : 'fa-chevron-down text-slate-400'"></i>
                        </button>
                        <div x-show="active === 2" x-collapse class="p-4 sm:p-5 text-xs text-slate-600 leading-relaxed bg-white border-t border-slate-100">
                            <strong>Match Score</strong> adalah indikator persentase kecocokan kualifikasi resume Anda (pendidikan, jurusan, skill, dan pengalaman) dengan syarat yang ditentukan oleh perusahaan penawar kerja. Semakin tinggi skor Anda, semakin besar peluang dipanggil interview.
                        </div>
                    </div>

                    <!-- Q3 -->
                    <div class="border border-slate-200/80 rounded-2xl overflow-hidden transition">
                        <button @click="active = (active === 3 ? 0 : 3)" class="w-full text-left p-4 sm:p-5 bg-slate-50/60 hover:bg-slate-100/60 flex items-center justify-between gap-4 font-bold text-slate-900 text-sm">
                            <span>3. Bagaimana cara mengerjakan Tes Ujian Online dari HR?</span>
                            <i class="fa-solid" :class="active === 3 ? 'fa-chevron-up text-blue-600' : 'fa-chevron-down text-slate-400'"></i>
                        </button>
                        <div x-show="active === 3" x-collapse class="p-4 sm:p-5 text-xs text-slate-600 leading-relaxed bg-white border-t border-slate-100">
                            Jika lamaran Anda diloloskan ke tahap tes online, Anda akan menerima pemberitahuan di menu <strong>Dashboard Pelamar</strong>. Masuk ke riwayat lamaran Anda lalu klik tombol <strong>"Mulai Kerjakan Tes"</strong>.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category 2: Untuk HR & Perusahaan -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xs border border-slate-200/80 space-y-6">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                        <i class="fa-solid fa-building-user"></i>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-slate-900 text-lg">Pertanyaan HR & Perusahaan (Recruiter)</h2>
                        <p class="text-xs text-slate-500">Panduan mengelola lowongan, wawancara, dan kontrak digital.</p>
                    </div>
                </div>

                <div class="space-y-4" x-data="{ active: 1 }">
                    <!-- Q1 -->
                    <div class="border border-slate-200/80 rounded-2xl overflow-hidden transition">
                        <button @click="active = (active === 1 ? 0 : 1)" class="w-full text-left p-4 sm:p-5 bg-slate-50/60 hover:bg-slate-100/60 flex items-center justify-between gap-4 font-bold text-slate-900 text-sm">
                            <span>1. Bagaimana cara mendaftarkan akun sebagai HR atau Pemilik Perusahaan?</span>
                            <i class="fa-solid" :class="active === 1 ? 'fa-chevron-up text-emerald-600' : 'fa-chevron-down text-slate-400'"></i>
                        </button>
                        <div x-show="active === 1" x-collapse class="p-4 sm:p-5 text-xs text-slate-600 leading-relaxed bg-white border-t border-slate-100">
                            Daftar akun baru terlebih dahulu, lalu buka menu profil Anda dan pilih <strong>"Ajukan Perubahan Role Perusahaan"</strong>. Upload dokumen verifikasi legalitas PT/CV Anda untuk ditinjau oleh tim Super Admin.
                        </div>
                    </div>

                    <!-- Q2 -->
                    <div class="border border-slate-200/80 rounded-2xl overflow-hidden transition">
                        <button @click="active = (active === 2 ? 0 : 2)" class="w-full text-left p-4 sm:p-5 bg-slate-50/60 hover:bg-slate-100/60 flex items-center justify-between gap-4 font-bold text-slate-900 text-sm">
                            <span>2. Apakah UMK 2026 yang tertera di platform sudah sesuai keputusan pemerintah?</span>
                            <i class="fa-solid" :class="active === 2 ? 'fa-chevron-up text-emerald-600' : 'fa-chevron-down text-slate-400'"></i>
                        </button>
                        <div x-show="active === 2" x-collapse class="p-4 sm:p-5 text-xs text-slate-600 leading-relaxed bg-white border-t border-slate-100">
                            Ya, seluruh besaran UMK 2026 yang terdapat pada platform TalentFlow disinkronkan secara langsung dengan Keputusan Menteri Ketenagakerjaan Republik Indonesia & Peraturan Gubernur di seluruh Kabupaten/Kota se-Indonesia.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Need Help Box -->
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-8 rounded-3xl shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6 border border-slate-700">
                <div>
                    <h3 class="font-extrabold text-lg text-white">Masih Membutuhkan Bantuan Khusus?</h3>
                    <p class="text-xs text-slate-300 mt-1">Tim Support TalentFlow siap melayani pertanyaan Anda 24/7.</p>
                </div>
                <a href="mailto:support@talentflow.com" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl text-xs transition shadow-md whitespace-nowrap">
                    <i class="fa-solid fa-envelope mr-1.5"></i> Hubungi Tim Customer Care
                </a>
            </div>

        </div>
    </div>
</x-public-layout>
