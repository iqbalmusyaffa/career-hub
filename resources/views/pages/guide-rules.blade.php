<x-public-layout>
    <!-- Breadcrumb & Hero Header -->
    <div class="bg-gradient-to-b from-indigo-50/70 via-blue-50/30 to-white pt-10 pb-12 border-b border-slate-200/80">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-6">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Beranda</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <a href="{{ route('pages.guide') }}" class="hover:text-blue-600 transition">Pusat Panduan</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-indigo-600 font-semibold">Aturan & Kebijakan Magang</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-semibold mb-3.5">
                        <i class="fa-solid fa-scale-balanced text-xs"></i> Tata Tertib & Kebijakan Resmi
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Aturan & Ketentuan Program Magang (Internship Policy)
                    </h1>
                    <p class="text-slate-600 text-xs sm:text-sm mt-3 leading-relaxed">
                        Pedoman resmi mengenai jam kerja, tata cara presensi harian, ketentuan libur nasional, batas toleransi izin & sakit, formula perhitungan uang saku, serta syarat kelulusan sertifikasi magang.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2.5 shrink-0">
                    <a href="{{ route('pages.guide.candidate') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center gap-2">
                        <i class="fa-solid fa-user-graduate text-xs"></i> Panduan Pelamar
                    </a>
                    <a href="{{ route('pages.guide.mentor') }}" class="px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 shadow-2xs transition flex items-center gap-2">
                        <i class="fa-solid fa-chalkboard-user text-xs"></i> Panduan Mentor &rarr;
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
                            <i class="fa-solid fa-list-check text-indigo-600"></i> Daftar Aturan & Kebijakan
                        </h3>
                        <nav class="space-y-1 text-xs">
                            <a href="#aturan-1" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-indigo-600 hover:bg-white transition">1. Jam Kerja & Presensi Logbook</a>
                            <a href="#aturan-2" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-indigo-600 hover:bg-white transition">2. Libur Nasional & Cuti Bersama</a>
                            <a href="#aturan-3" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-indigo-600 hover:bg-white transition">3. Toleransi Izin & Surat Sakit</a>
                            <a href="#aturan-4" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-indigo-600 hover:bg-white transition">4. Skema & Formula Potongan Uang Saku</a>
                            <a href="#aturan-5" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-indigo-600 hover:bg-white transition">5. Ketentuan Rekening Bank & KTP Match</a>
                            <a href="#aturan-6" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-indigo-600 hover:bg-white transition">6. Alur Revisi & Penolakan Laporan</a>
                            <a href="#aturan-7" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-indigo-600 hover:bg-white transition">7. Syarat Kelulusan & Sertifikat</a>
                        </nav>

                        <div class="pt-4 border-t border-slate-200">
                            <div class="p-3.5 bg-indigo-50/80 rounded-xl border border-indigo-100 text-xs">
                                <div class="font-bold text-indigo-900 mb-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-shield-halved text-indigo-600"></i> Kepatuhan Sistem
                                </div>
                                <p class="text-slate-600 text-[11px] leading-relaxed">
                                    Seluruh perhitungan kehadiran, toleransi izin, alpa, dan pemotongan uang saku diproses secara otomatis oleh algoritma sistem.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Rules Content -->
                <div class="lg:col-span-8 order-1 lg:order-2 space-y-12">

                    <!-- Rule 1: Jam Kerja & Presensi Logbook -->
                    <section id="aturan-1" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0">1</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Jam Kerja & Pengisian Logbook Harian</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Setiap peserta magang wajib mendokumentasikan kegiatan kerja harian secara tepat waktu melalui sistem kalender logbook mandiri.
                        </p>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3 text-xs text-slate-700">
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-clock text-indigo-600 mt-0.5"></i>
                                <div>
                                    <strong class="text-slate-900">Batas Waktu Pengisian (Deadline):</strong> Logbook wajib diisi pada hari yang sama paling lambat pukul <strong>23:59 WIB</strong>. Pengisian melewati tengah malam akan terkunci secara otomatis.
                                </div>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-location-dot text-indigo-600 mt-0.5"></i>
                                <div>
                                    <strong class="text-slate-900">Verifikasi Lokasi & Tipe Kehadiran:</strong> Peserta memilih metode <em>WFO (Work From Office)</em> atau <em>WFH (Work From Home)</em> disertai tagging koordinat GPS lokasi kehadiran dan unggah foto dokumentasi aktivitas.
                                </div>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-pen-nib text-indigo-600 mt-0.5"></i>
                                <div>
                                    <strong class="text-slate-900">Uraian Aktivitas:</strong> Deskripsikan output pekerjaan harian, kendala yang dihadapi, serta capaian pembelajaran (minimal 20 karakter).
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Rule 2: Libur Nasional & Cuti Bersama -->
                    <section id="aturan-2" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0">2</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Hari Libur Nasional & Cuti Bersama</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Sistem secara otomatis menyesuaikan kalender kerja dengan Surat Keputusan Bersama (SKB) Libur Nasional Republik Indonesia serta kalender cuti instansi perusahaan.
                        </p>
                        <div class="p-4 bg-emerald-50/60 rounded-2xl border border-emerald-200 text-xs space-y-2 text-emerald-900">
                            <div class="font-bold flex items-center gap-2 text-emerald-800">
                                <i class="fa-solid fa-calendar-check"></i> Ketentuan Hari Libur:
                            </div>
                            <ul class="list-disc pl-5 space-y-1 text-slate-700">
                                <li>Pada hari libur resmi dan akhir pekan (Sabtu-Minggu), tanggal pada kalender akan berstatus <strong>"Hari Libur Nasional / Cuti Bersama"</strong> (ikon payung/bendera).</li>
                                <li>Peserta <strong>tidak diwajibkan</strong> mengisi logbook harian dan <strong>tidak akan dihitung alpa</strong>.</li>
                                <li>Hari libur tidak memotong kuota izin peserta dan tidak mempengaruhi nominal uang saku magang.</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Rule 3: Toleransi Izin & Sakit -->
                    <section id="aturan-3" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0">3</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Kebijakan Toleransi Izin & Surat Keterangan Sakit</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Perusahaan memberikan hak toleransi ketidakhadiran berizin untuk menjaga fleksibilitas dan kesehatan peserta magang.
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                            <div class="p-4 rounded-2xl border border-slate-200 bg-white space-y-2">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-notes-medical text-amber-500"></i> Kuota Toleransi 4 Hari Bebas Potong
                                </div>
                                <p class="text-slate-600 leading-relaxed text-[11px]">
                                    Akumulasi izin dan sakit hingga <strong>maksimal 4 hari per periode bulanan</strong> berstatus <strong>Bebas Potongan Uang Saku</strong> (uang saku dibayar penuh).
                                </p>
                            </div>
                            <div class="p-4 rounded-2xl border border-slate-200 bg-white space-y-2">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-file-waveform text-blue-600"></i> Wajib Unggah Surat Dokter
                                </div>
                                <p class="text-slate-600 leading-relaxed text-[11px]">
                                    Untuk ketidakhadiran karena sakit &ge; 1 hari, peserta diwajibkan mengunggah foto / scan <strong>Surat Keterangan Dokter</strong> resmi untuk verifikasi Mentor.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Rule 4: Skema & Formula Potongan Uang Saku -->
                    <section id="aturan-4" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0">4</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Skema & Formula Pemotongan Uang Saku</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Uang saku magang dihitung secara proporsional berdasarkan jumlah Hari Kerja (HK) aktif dalam 1 bulan (standar 22 HK):
                        </p>

                        <!-- Formula Card Box -->
                        <div class="p-5 rounded-2xl bg-slate-900 text-white space-y-3">
                            <div class="text-[11px] font-bold uppercase tracking-wider text-amber-400 flex items-center gap-2">
                                <i class="fa-solid fa-calculator"></i> Formula Resmi Pemotongan:
                            </div>
                            <div class="p-3 bg-slate-800/90 rounded-xl font-mono text-xs sm:text-sm text-emerald-400 border border-slate-700">
                                Potongan = [ Alpa + Max(0, Izin &minus; 4 Hari) ] &times; (Uang Saku Pokok / Total Hari Kerja Aktif)
                            </div>
                            <div class="p-3 bg-slate-800/90 rounded-xl font-mono text-xs sm:text-sm text-white border border-slate-700">
                                Uang Saku Bersih = Max(0, Uang Saku Pokok &minus; Total Potongan)
                            </div>
                            <div class="text-[11px] text-slate-400 leading-relaxed">
                                &bull; Tarif Harian = Rp 2.800.000 / 22 Hari Kerja &asymp; <strong>Rp 127.273 / hari</strong>.<br>
                                &bull; Jika peserta <strong>tidak hadir sama sekali (0 / 22 HK)</strong>, maka potongan adalah 100% (Rp 2.800.000) sehingga Uang Saku Bersih adalah <strong>Rp 0</strong>.<br>
                                &bull; Periode bulan yang belum berjalan (misal bulan depan) belum dapat diajukan pencairannya hingga periode kerja berlangsung.
                            </div>
                        </div>
                    </section>

                    <!-- Rule 5: Rekening Bank & KTP Match -->
                    <section id="aturan-5" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0">5</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Ketentuan Rekening Bank & Validasi KTP Match</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Untuk menjamin kepatuhan audit keuangan dan kelancaran transfer payroll perbankan:
                        </p>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2.5 text-xs text-slate-700">
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                                <span><strong>Rekening Atas Nama Pribadi:</strong> Rekening bank wajib terdaftar atas nama lengkap peserta magang sesuai kartu identitas (KTP).</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                                <span><strong>Indikator KTP Match:</strong> Sistem otomatis mencocokkan nama pemegang rekening dengan nama akun peserta. Lencana hijau <strong>"✓ KTP Match"</strong> akan tersemat jika data sinkron.</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5"></i>
                                <span><strong>Rekening Berbeda Nama:</strong> Jika menggunakan rekening keluarga (misal orang tua), peserta wajib mengunggah foto Kartu Keluarga / Surat Kuasa untuk diverifikasi manual oleh HR.</span>
                            </div>
                        </div>
                    </section>

                    <!-- Rule 6: Alur Revisi Logbook -->
                    <section id="aturan-6" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0">6</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Alur Revisi Logbook & Koreksi Status</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Jika laporan harian dinilai kurang lengkap atau belum memenuhi standar oleh Mentor:
                        </p>
                        <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-200 text-xs space-y-2 text-amber-900">
                            <p>1. Status logbook akan berubah menjadi <span class="px-2 py-0.5 rounded font-bold bg-amber-100 text-amber-800">Perlu Tindakan Anda (Revisi)</span> disertai catatan koreksi dari Mentor.</p>
                            <p>2. Peserta dapat membuka kembali formulir logbook pada tanggal terkait dan menekan tombol <strong>"Kirim Ulang Revisi Laporan"</strong> (meskipun sudah melewati pukul 23:59).</p>
                            <p>3. Tombol <strong>"🔄 Sinkronkan Status"</strong> di header kalender dapat digunakan kapan saja untuk menyegarkan status persetujuan terbaru dari Mentor.</p>
                        </div>
                    </section>

                    <!-- Rule 7: Kelulusan & Sertifikat -->
                    <section id="aturan-7" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0">7</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Syarat Kelulusan & Penerbitan Sertifikat Magang</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Di akhir periode, peserta yang memenuhi seluruh kualifikasi berhak menerima <strong>Sertifikat Resmi & Transkrip Nilai Digital</strong>:
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                            <div class="p-4 rounded-2xl border border-slate-200 bg-white space-y-1.5">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-hourglass-end text-indigo-600"></i> Target Jam Kerja
                                </div>
                                <p class="text-slate-600 text-[11px] leading-relaxed">Memenuhi target akumulasi jam kerja minimum (standar 400 jam atau sesuai kesepakatan batch).</p>
                            </div>
                            <div class="p-4 rounded-2xl border border-slate-200 bg-white space-y-1.5">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-graduation-cap text-purple-600"></i> Kelulusan Modul Silabus
                                </div>
                                <p class="text-slate-600 text-[11px] leading-relaxed">Menyelesaikan dan divalidasi kelulusan seluruh modul pembelajaran kompetensi magang oleh Mentor.</p>
                            </div>
                            <div class="p-4 rounded-2xl border border-slate-200 bg-white space-y-1.5">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-star text-amber-500"></i> Evaluasi Mentor & HR
                                </div>
                                <p class="text-slate-600 text-[11px] leading-relaxed">Mendapatkan skor evaluasi akhir (hard skills, soft skills, kedisiplinan) dengan predikat minimal "Baik".</p>
                            </div>
                            <div class="p-4 rounded-2xl border border-slate-200 bg-white space-y-1.5">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-qrcode text-emerald-600"></i> Sertifikat QR Code Terverifikasi
                                </div>
                                <p class="text-slate-600 text-[11px] leading-relaxed">Sertifikat dilengkapi QR Code verifikasi publik yang dapat divalidasi keasliannya oleh pihak kampus dan instansi eksternal.</p>
                            </div>
                        </div>
                    </section>

                    <!-- Bottom CTA Banner -->
                    <div class="p-6 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-3xl border border-indigo-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">Punya Pertanyaan Spesifik Terkait Kebijakan Magang?</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Kunjungi FAQ atau hubungi tim HR perusahaan melalui platform.</p>
                        </div>
                        <a href="{{ route('pages.faq') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-xs transition shrink-0">
                            Buka Tanya Jawab (FAQ) &rarr;
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-public-layout>
