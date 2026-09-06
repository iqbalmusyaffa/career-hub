<x-public-layout>
    <!-- Breadcrumb & Hero -->
    <div class="bg-gradient-to-b from-blue-50/60 via-slate-50/30 to-white pt-10 pb-12 border-b border-slate-200/80">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-6">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Beranda</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <a href="{{ route('pages.guide') }}" class="hover:text-blue-600 transition">Pusat Panduan</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-blue-600 font-semibold">Panduan Pelamar Kerja</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold mb-3.5">
                        <i class="fa-solid fa-user-graduate text-xs"></i> Khusus Kandidat & Pencari Kerja
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Panduan Lengkap Pelamar Kerja & Magang
                    </h1>
                    <p class="text-slate-600 text-xs sm:text-sm mt-3 leading-relaxed">
                        Pelajari alur pendaftaran akun, pembuatan CV profesional, pencarian lowongan terverifikasi, pengerjaan tes online, hingga proses tanda tangan kontrak digital.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2.5 shrink-0">
                    <a href="{{ route('jobs.index') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i> Cari Lowongan
                    </a>
                    <a href="{{ route('pages.guide.employer') }}" class="px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 shadow-2xs transition flex items-center gap-2">
                        <i class="fa-solid fa-building text-xs"></i> Panduan HR &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Guide Content -->
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                <!-- Quick Navigation Sidebar (Desktop) -->
                <div class="lg:col-span-4 order-2 lg:order-1">
                    <div class="sticky top-24 bg-slate-50/80 p-5 rounded-2xl border border-slate-200 space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-blue-600"></i> Daftar Isi Panduan
                        </h3>
                        <nav class="space-y-1 text-xs">
                            <a href="#tahap-1" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">1. Registrasi & Akun Kandidat</a>
                            <a href="#tahap-2" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">2. Melengkapi Profil & CV Builder</a>
                            <a href="#tahap-3" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">3. Mencari & Memilih Lowongan</a>
                            <a href="#tahap-4" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">4. Proses Melamar & Video Pitch</a>
                            <a href="#tahap-5" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">5. Mengikuti Ujian & Tes Online</a>
                            <a href="#tahap-6" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">6. Wawancara & Offer Letter</a>
                            <a href="#tahap-7" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">7. Kontrak Digital & Logbook Magang</a>
                        </nav>
                        
                        <div class="pt-4 border-t border-slate-200">
                            <div class="p-3.5 bg-blue-50/80 rounded-xl border border-blue-100 text-xs">
                                <div class="font-bold text-blue-900 mb-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-question text-blue-600"></i> Butuh Bantuan?
                                </div>
                                <p class="text-slate-600 text-[11px] leading-relaxed">
                                    Temukan jawaban seputar pertanyaan teknis di <a href="{{ route('pages.faq') }}" class="text-blue-600 font-semibold underline">Pusat Bantuan FAQ</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Steps Content -->
                <div class="lg:col-span-8 order-1 lg:order-2 space-y-12">

                    <!-- Step 1: Registrasi -->
                    <section id="tahap-1" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center shrink-0">1</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Registrasi Akun & Verifikasi Email</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Langkah awal adalah membuat akun pencari kerja. Pastikan Anda mendaftar dengan alamat email yang aktif untuk menerima notifikasi tahapan seleksi secara realtime.
                        </p>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2 text-xs text-slate-700">
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-check text-emerald-600 mt-0.5"></i>
                                <span>Buka halaman <a href="{{ route('register') }}" class="text-blue-600 font-semibold">Daftar Akun</a>, lalu pilih peran sebagai <strong>Kandidat / Pencari Kerja</strong>.</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-check text-emerald-600 mt-0.5"></i>
                                <span>Anda dapat mendaftar menggunakan formulir email atau opsi <strong>Masuk dengan Google</strong> untuk proses instan.</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-check text-emerald-600 mt-0.5"></i>
                                <span>Cek kotak masuk email Anda dan klik tautan verifikasi akun yang dikirimkan sistem.</span>
                            </div>
                        </div>
                    </section>

                    <!-- Step 2: Profil & CV -->
                    <section id="tahap-2" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center shrink-0">2</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Melengkapi Profil & Menggunakan CV Builder</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Kelengkapan profil Anda sangat mempengaruhi penilaian awal HR dan skor kecocokan sistem (<em>Match Score</em>).
                        </p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div class="p-4 rounded-xl border border-slate-200 bg-white space-y-2">
                                <div class="font-bold text-xs text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-file-pdf text-blue-600"></i> Unggah Dokumen CV
                                </div>
                                <p class="text-slate-500 text-[11px] leading-relaxed">
                                    Unggah file CV dalam format PDF (maks. 5MB). Pastikan mencantumkan pengalaman relevan, nomor telepon, dan portofolio.
                                </p>
                            </div>

                            <div class="p-4 rounded-xl border border-slate-200 bg-white space-y-2">
                                <div class="font-bold text-xs text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-wand-magic-sparkles text-indigo-600"></i> CV Builder Online
                                </div>
                                <p class="text-slate-500 text-[11px] leading-relaxed">
                                    Gunakan fitur pembuat CV online otomatis yang sudah memenuhi standar ATS untuk diunduh kapan saja.
                                </p>
                            </div>
                        </div>

                        <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-900">
                            <strong>💡 Tips Lolos Seleksi:</strong> Masukkan keahlian teknis (<em>hard skills</em>) dan jurusan Anda secara spesifik agar sistem dapat merekomendasikan lowongan yang paling cocok di dashboard Anda.
                        </div>
                    </section>

                    <!-- Step 3: Pencarian Lowongan -->
                    <section id="tahap-3" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center shrink-0">3</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Mencari & Memfilter Lowongan Terpercaya</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Gunakan fitur filter lengkap di halaman <a href="{{ route('jobs.index') }}" class="text-blue-600 font-semibold">Lowongan Kerja</a> untuk menemukan pekerjaan yang tepat:
                        </p>
                        
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2 text-xs text-slate-700">
                            <ul class="space-y-2">
                                <li class="flex items-start gap-2">
                                    <i class="fa-solid fa-location-dot text-slate-400 mt-0.5"></i>
                                    <span><strong>Filter Wilayah & Lokasi:</strong> Cari berdasarkan kota terdekat atau pilih opsi <em>Remote / WFH</em>.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fa-solid fa-briefcase text-slate-400 mt-0.5"></i>
                                    <span><strong>Tipe Kontrak:</strong> Pilih antara Full-Time, Magang / Internship, Kontrak, atau Part-Time.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fa-solid fa-shield-check text-emerald-600 mt-0.5"></i>
                                    <span><strong>Lencana Terverifikasi:</strong> Prioritaskan penyelenggara dengan tanda centang biru untuk memastikan lowongan resmi bebas pungutan biaya.</span>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <!-- Step 4: Lamar Pekerjaan -->
                    <section id="tahap-4" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center shrink-0">4</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Mengirim Lamaran & Video Pitch</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Pada halaman detail lowongan, klik tombol <strong>Lamar Sekarang</strong>. Anda dapat melengkapi informasi pendukung:
                        </p>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2.5 text-xs text-slate-700">
                            <p>1. <strong>Surat Pengantar (Cover Note):</strong> Tuliskan alasan singkat mengapa Anda kandidat terbaik untuk posisi ini.</p>
                            <p>2. <strong>Video Pitch Perkenalan (Opsional / Jika Diminta):</strong> Lampirkan tautan video perkenalan singkat (1-2 menit) untuk menunjukkan keahlian komunikasi Anda.</p>
                            <p>3. <strong>Konfirmasi Berkas:</strong> Pastikan CV dan portofolio yang terlampir sudah merupakan versi paling mutakhir.</p>
                        </div>
                    </section>

                    <!-- Step 5: Tes Online -->
                    <section id="tahap-5" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center shrink-0">5</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Mengerjakan Ujian & Tes Asesmen Online</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Beberapa perusahaan menyertakan tes kualifikasi teknis atau psikotes online. Jika status lamaran Anda berubah menjadi <strong>Tahap Tes</strong>:
                        </p>
                        <div class="p-4 bg-white rounded-xl border border-slate-200 space-y-2 text-xs text-slate-600">
                            <div class="flex items-center gap-2 font-bold text-slate-900">
                                <i class="fa-solid fa-laptop-code text-blue-600"></i> Ketentuan Pengerjaan Tes:
                            </div>
                            <ul class="list-disc pl-5 space-y-1.5 text-slate-600">
                                <li>Buka dashboard lamaran Anda dan klik tombol <strong>Mulai Ujian</strong>.</li>
                                <li>Perhatikan batas waktu pengerjaan (<em>timer countdown</em>).</li>
                                <li>Pastikan koneksi internet stabil sebelum menekan tombol mulai.</li>
                                <li>Jawaban akan terkunci dan dinilai secara otomatis setelah tombol <strong>Submit Jawaban</strong> ditekan.</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Step 6: Interview & Offer Letter -->
                    <section id="tahap-6" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center shrink-0">6</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Wawancara & Penerimaan Surat Penawaran (Offer Letter)</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Jika Anda lolos tahap tes, tim HR akan mengirimkan undangan wawancara:
                        </p>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3 text-xs text-slate-700">
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">📅 Jadwal Wawancara</h4>
                                <p class="text-slate-600 leading-relaxed">Informasi tanggal, jam, dan link video conference (Google Meet/Zoom) akan tampil pada kartu lamaran di dashboard Anda.</p>
                            </div>
                            <div class="pt-2 border-t border-slate-200">
                                <h4 class="font-bold text-slate-900 mb-1">📄 Penerimaan Offer Letter</h4>
                                <p class="text-slate-600 leading-relaxed">Setelah dinyatakan lolos seluruh tahapan, perusahaan akan menerbitkan dokumen penawaran resmi (gaji/uang saku, tanggal mulai, dan benefit). Anda dapat meninjau dan menerima penawaran langsung di platform.</p>
                            </div>
                        </div>
                    </section>

                    <!-- Step 7: Kontrak & Magang -->
                    <section id="tahap-7" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center shrink-0">7</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Penandatanganan Perjanjian Digital & Logbook Magang</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Platform mendukung proses onboarding digital yang aman dan transparan:
                        </p>
                        <div class="space-y-3">
                            <div class="p-4 rounded-xl border border-slate-200 bg-white text-xs space-y-1.5">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-signature text-emerald-600"></i> Tanda Tangan Perjanjian Kerja / Magang
                                </div>
                                <p class="text-slate-600 leading-relaxed">
                                    Baca dokumen perjanjian kerja dan lakukan pengesahan digital menggunakan kode verifikasi <strong>OTP</strong> yang dikirimkan ke email Anda.
                                </p>
                            </div>

                            <div class="p-4 rounded-xl border border-slate-200 bg-white text-xs space-y-1.5">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-book-journal-whills text-blue-600"></i> Pengisian Logbook Harian (Peserta Magang)
                                </div>
                                <p class="text-slate-600 leading-relaxed">
                                    Bagi peserta magang, isi aktivitas harian di menu <strong>Logbook Magang</strong> untuk diverifikasi oleh mentor perusahaan. Di akhir periode, Anda akan menerima <strong>Sertifikat Resmi & Transkrip Nilai</strong>.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Bottom Help Callout -->
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-slate-50 rounded-2xl border border-blue-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">Sudah Siap Memulai Pencarian Karir?</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Jelajahi ratusan peluang kerja terbaru dari berbagai industri di Indonesia.</p>
                        </div>
                        <a href="{{ route('jobs.index') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition shrink-0">
                            Jelajahi Lowongan Sekarang &rarr;
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-public-layout>
