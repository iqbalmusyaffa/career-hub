<x-public-layout>
    <!-- Breadcrumb & Hero Header -->
    <div class="bg-gradient-to-b from-purple-50/70 via-indigo-50/30 to-white pt-10 pb-12 border-b border-slate-200/80">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-6">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Beranda</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <a href="{{ route('pages.guide') }}" class="hover:text-blue-600 transition">Pusat Panduan</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-purple-600 font-semibold">Panduan Mentor Magang</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-50 border border-purple-200 text-purple-700 text-xs font-semibold mb-3.5">
                        <i class="fa-solid fa-chalkboard-user text-xs"></i> Khusus Pembimbing / Mentor Perusahaan
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Panduan Mentor & Pembimbing Magang
                    </h1>
                    <p class="text-slate-600 text-xs sm:text-sm mt-3 leading-relaxed">
                        Panduan operasional bagi mentor untuk mereview & menyetujui (ACC) logbook presensi harian, memvalidasi progres silabus kurikulum, mengajukan rekomendasi uang saku, dan memberikan evaluasi akhir.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2.5 shrink-0">
                    <a href="{{ route('mentor.dashboard') }}" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center gap-2">
                        <i class="fa-solid fa-gauge text-xs"></i> Dashboard Mentor
                    </a>
                    <a href="{{ route('pages.guide.rules') }}" class="px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 shadow-2xs transition flex items-center gap-2">
                        <i class="fa-solid fa-scale-balanced text-xs"></i> Aturan Magang &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                <!-- Quick Navigation Sidebar (Desktop) -->
                <div class="lg:col-span-4 order-2 lg:order-1">
                    <div class="sticky top-24 bg-slate-50/80 p-5 rounded-2xl border border-slate-200 space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-purple-600"></i> Alur Kerja Mentor
                        </h3>
                        <nav class="space-y-1 text-xs">
                            <a href="#mentor-1" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-purple-600 hover:bg-white transition">1. Dashboard & Monitoring Mentee</a>
                            <a href="#mentor-2" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-purple-600 hover:bg-white transition">2. Review & ACC Presensi Harian</a>
                            <a href="#mentor-3" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-purple-600 hover:bg-white transition">3. Mengembalikan Revisi / Tolak</a>
                            <a href="#mentor-4" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-purple-600 hover:bg-white transition">4. Validasi Modul Silabus Kurikulum</a>
                            <a href="#mentor-5" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-purple-600 hover:bg-white transition">5. Rekomendasi Uang Saku Bulanan</a>
                            <a href="#mentor-6" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-purple-600 hover:bg-white transition">6. Evaluasi Midterm & Final Score</a>
                        </nav>

                        <div class="pt-4 border-t border-slate-200">
                            <div class="p-3.5 bg-purple-50/80 rounded-xl border border-purple-100 text-xs">
                                <div class="font-bold text-purple-900 mb-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check text-purple-600"></i> Peran Strategis Mentor
                                </div>
                                <p class="text-slate-600 text-[11px] leading-relaxed">
                                    Persetujuan mentor menjadi dasar legal bagi tim HR & Finance dalam memproses pencairan uang saku dan penerbitan sertifikat magang.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Guide Content -->
                <div class="lg:col-span-8 order-1 lg:order-2 space-y-12">

                    <!-- Section 1: Dashboard -->
                    <section id="mentor-1" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-purple-600 text-white font-bold text-xs flex items-center justify-center shrink-0">1</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Dashboard & Daftar Peserta Bimbingan</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Melalui menu <strong>Dashboard Mentor</strong>, Anda dapat melihat rangkuman seluruh peserta magang yang ditugaskan kepada Anda:
                        </p>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2.5 text-xs text-slate-700">
                            <p>&bull; <strong>Statistik Logbook:</strong> Jumlah logbook menunggu persetujuan (ACC), disetujui, dan perlu revisi.</p>
                            <p>&bull; <strong>Akumulasi Jam Kerja:</strong> Total jam kerja yang telah diselesaikan oleh masing-masing peserta dibanding target periode.</p>
                            <p>&bull; <strong>Filter Batch / Periode:</strong> Kemudahan memfilter data mentee berdasarkan angkatan batch magang aktif.</p>
                        </div>
                    </section>

                    <!-- Section 2: Review & ACC Logbook -->
                    <section id="mentor-2" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-purple-600 text-white font-bold text-xs flex items-center justify-center shrink-0">2</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Memeriksa & Menyetujui (ACC) Logbook Presensi</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Laporan harian yang dikirim mentee sebelum 23:59 WIB akan masuk ke antrean tinjauan Anda:
                        </p>
                        <div class="p-4 bg-white rounded-2xl border border-slate-200 space-y-3 text-xs">
                            <div class="font-bold text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-clipboard-check text-emerald-600"></i> Langkah Melakukan ACC:
                            </div>
                            <ol class="list-decimal pl-5 space-y-1.5 text-slate-600">
                                <li>Buka menu <strong>ACC Presensi Magang</strong> &rarr; pilih nama mentee.</li>
                                <li>Periksa kesesuaian uraian aktivitas, durasi jam kerja, lokasi presensi (GPS), dan bukti foto dokumentasi.</li>
                                <li>Tuliskan feedback positif atau arahan teknis pada kolom <strong>Catatan Mentor</strong>.</li>
                                <li>Klik tombol hijau <strong>"Setujui (ACC) Presensi"</strong> untuk mengesahkan kehadiran.</li>
                            </ol>
                        </div>
                    </section>

                    <!-- Section 3: Mengembalikan Revisi / Tolak -->
                    <section id="mentor-3" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-purple-600 text-white font-bold text-xs flex items-center justify-center shrink-0">3</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Mengembalikan Laporan untuk Revisi atau Menolak</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Jika laporan harian tidak sesuai standar atau uraian tugas kurang jelas:
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                            <div class="p-4 rounded-2xl border border-amber-200 bg-amber-50/40 space-y-2">
                                <div class="font-bold text-amber-900 flex items-center gap-2">
                                    <i class="fa-solid fa-triangle-exclamation text-amber-600"></i> Minta Revisi (Action Required)
                                </div>
                                <p class="text-slate-600 text-[11px] leading-relaxed">
                                    Pilih opsi <em>"Kembalikan untuk Revisi"</em> dan berikan catatan poin yang wajib diperbaiki. Mentee akan mendapatkan notifikasi dan dapat mengedit kembali laporannya.
                                </p>
                            </div>
                            <div class="p-4 rounded-2xl border border-rose-200 bg-rose-50/40 space-y-2">
                                <div class="font-bold text-rose-900 flex items-center gap-2">
                                    <i class="fa-solid fa-circle-xmark text-rose-600"></i> Tolak Kehadiran (Reject)
                                </div>
                                <p class="text-slate-600 text-[11px] leading-relaxed">
                                    Pilih <em>"Tolak Kehadiran"</em> jika mentee terbukti tidak hadir atau melakukan pelanggaran disiplin. Kehadiran akan dihitung sebagai Alpa.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Section 4: Validasi Modul Kurikulum -->
                    <section id="mentor-4" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-purple-600 text-white font-bold text-xs flex items-center justify-center shrink-0">4</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Validasi Progres Silabus & Modul Pembelajaran</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Mentor bertindak sebagai penguji kompetensi per materi pembelajaran:
                        </p>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2.5 text-xs text-slate-700">
                            <p>1. Buka tabel <strong>Progres Silabus & Verifikasi Modul</strong> pada profil bimbingan peserta.</p>
                            <p>2. Review kompetensi materi dan hasil penugasan (task/project) mentee.</p>
                            <p>3. Masukkan catatan evaluasi lalu klik tombol <strong>"✓ Lulus"</strong> untuk mengesahkan kelulusan modul terkait.</p>
                            <p>4. Progres kelulusan silabus akan tercatat langsung pada transkrip nilai akhir mentee.</p>
                        </div>
                    </section>

                    <!-- Section 5: Rekomendasi Uang Saku Bulanan -->
                    <section id="mentor-5" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-purple-600 text-white font-bold text-xs flex items-center justify-center shrink-0">5</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Pengajuan Rekomendasi Uang Saku ke HR / Finance</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Di akhir periode bulan berjalan, Mentor mereview akumulasi kehadiran dan mengajukan nominal payroll ke HR:
                        </p>
                        <div class="p-4 bg-white rounded-2xl border border-slate-200 space-y-3 text-xs">
                            <div class="font-bold text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-hand-holding-dollar text-purple-600"></i> Alur Rekomendasi Payroll:
                            </div>
                            <ul class="list-disc pl-5 space-y-1.5 text-slate-600">
                                <li>Buka menu <strong>Rekomendasi Uang Saku</strong> (URL: <code>/mentor/stipends</code>).</li>
                                <li>Pilih periode bulan berjalan. Periksa rekap Hari Kerja (HK), jumlah izin, dan alpa mentee.</li>
                                <li>Gunakan fitur <strong>Filter Kehadiran</strong> untuk melihat peserta yang izin, alpa, atau hadir penuh.</li>
                                <li>Klik tombol <strong>"Ajukan Uang Saku"</strong> secara perorangan atau pilih banyak mentee dan tekan <strong>"Ajukan Semua Terpilih ke HR"</strong>.</li>
                                <li>Tim HR & Finance akan menerima notifikasi otomatis untuk memproses verifikasi rekening dan transfer uang saku.</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Section 6: Evaluasi Midterm & Final -->
                    <section id="mentor-6" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-purple-600 text-white font-bold text-xs flex items-center justify-center shrink-0">6</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Evaluasi Midterm & Penilaian Akhir (Scorecard)</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Di pertengahan dan akhir masa magang, Mentor mengisi lembar evaluasi kompetensi menyeluruh:
                        </p>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2.5 text-xs text-slate-700">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-purple-600 mt-0.5"></i>
                                <span><strong>Hard Skills & Output Tugas:</strong> Kualitas hasil kerja, pemahaman teknis, dan ketepatan solusi.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-purple-600 mt-0.5"></i>
                                <span><strong>Soft Skills & Komunikasi:</strong> Sikap kerja, inisiatif, kerjasama tim, dan kemampuan presentasi.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-purple-600 mt-0.5"></i>
                                <span><strong>Kedisiplinan & Etika:</strong> Ketepatan waktu presensi dan kepatuhan terhadap aturan kerja perusahaan.</span>
                            </div>
                        </div>
                    </section>

                    <!-- Bottom CTA Banner -->
                    <div class="p-6 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-3xl border border-purple-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">Siap Membimbing Peserta Magang Anda?</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Akses panel Mentor untuk mulai memantau dan mereview logbook harian.</p>
                        </div>
                        <a href="{{ route('mentor.dashboard') }}" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs rounded-xl shadow-xs transition shrink-0">
                            Buka Dashboard Mentor &rarr;
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-public-layout>
