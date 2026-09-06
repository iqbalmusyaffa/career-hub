<x-public-layout>
    <!-- Breadcrumb & Hero -->
    <div class="bg-gradient-to-b from-slate-100/70 via-slate-50/40 to-white pt-10 pb-12 border-b border-slate-200/80">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-6">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Beranda</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <a href="{{ route('pages.guide') }}" class="hover:text-blue-600 transition">Pusat Panduan</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-blue-600 font-semibold">Panduan Penyelenggara</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 border border-slate-300 text-slate-800 text-xs font-semibold mb-3.5">
                        <i class="fa-solid fa-building text-xs"></i> Khusus Perusahaan, Instansi & Tim HR
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Panduan Penyelenggara & Pengelolaan Rekrutmen
                    </h1>
                    <p class="text-slate-600 text-xs sm:text-sm mt-3 leading-relaxed">
                        Petunjuk lengkap verifikasi profil instansi, publikasi lowongan kerja, pembuatan modul tes online, manajemen funnel seleksi kandidat, hingga penerbitan dokumen digital.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2.5 shrink-0">
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center gap-2">
                        <i class="fa-solid fa-plus text-xs"></i> Pasang Lowongan
                    </a>
                    <a href="{{ route('pages.guide.candidate') }}" class="px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 shadow-2xs transition flex items-center gap-2">
                        <i class="fa-solid fa-user-graduate text-xs"></i> Panduan Pelamar &rarr;
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
                            <i class="fa-solid fa-list-check text-blue-600"></i> Alur Penyelenggara
                        </h3>
                        <nav class="space-y-1 text-xs">
                            <a href="#alur-1" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">1. Registrasi & Verifikasi Legalitas</a>
                            <a href="#alur-2" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">2. Pengaturan Cabang & Tim Rekruter</a>
                            <a href="#alur-3" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">3. Publikasi Lowongan Kerja / Magang</a>
                            <a href="#alur-4" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">4. Membuat Soal Tes & Asesmen</a>
                            <a href="#alur-5" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">5. Pipeline Funnel & Screening</a>
                            <a href="#alur-6" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">6. Penjadwalan Interview & Evaluasi</a>
                            <a href="#alur-7" class="block px-3 py-2 rounded-lg font-medium text-slate-600 hover:text-blue-600 hover:bg-white transition">7. Offering, Kontrak & Sertifikat</a>
                        </nav>
                        
                        <div class="pt-4 border-t border-slate-200">
                            <div class="p-3.5 bg-slate-100 rounded-xl border border-slate-200 text-xs">
                                <div class="font-bold text-slate-900 mb-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-shield-halved text-blue-600"></i> Keamanan & Verifikasi
                                </div>
                                <p class="text-slate-600 text-[11px] leading-relaxed">
                                    Perusahaan dengan profil lengkap akan mendapatkan lencana <strong>Verified Partner</strong> untuk meningkatkan kepercayaan pencari kerja.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Steps Content -->
                <div class="lg:col-span-8 order-1 lg:order-2 space-y-12">

                    <!-- Step 1: Registrasi Perusahaan -->
                    <section id="alur-1" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center shrink-0">1</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Registrasi Akun Perusahaan & Verifikasi Legalitas</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Daftarkan akun institusi/perusahaan Anda untuk mengakses panel HR Dashboard yang komprehensif.
                        </p>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2 text-xs text-slate-700">
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                                <span>Daftar akun baru dan ajukan peran sebagai <strong>HR / Company Owner</strong> di menu Profil.</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                                <span>Lengkapi data identitas: Nama Perusahaan, Industri/Sektor Bisnis, Logo, Alamat Kantor, Website, dan Deskripsi Budaya Kerja.</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                                <span>Unggah dokumen legalitas (NIB / SIUP / NPWP) untuk ditinjau oleh Admin dan mendapatkan lencana <strong>Terverifikasi Resmi</strong>.</span>
                            </div>
                        </div>
                    </section>

                    <!-- Step 2: Cabang & Tim -->
                    <section id="alur-2" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center shrink-0">2</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Manajemen Cabang Kantor & Anggota Tim Rekruter</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Kelola operasional rekrutmen multi-cabang dan kolaborasi tim penilai:
                        </p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div class="p-4 rounded-xl border border-slate-200 bg-white space-y-2">
                                <div class="font-bold text-xs text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-building-user text-blue-600"></i> Cabang Perusahaan
                                </div>
                                <p class="text-slate-500 text-[11px] leading-relaxed">
                                    Tambahkan lokasi cabang kantor (Head Office, Cabang Regional) beserta koordinat Google Maps untuk mempermudah penempatan kerja.
                                </p>
                            </div>

                            <div class="p-4 rounded-xl border border-slate-200 bg-white space-y-2">
                                <div class="font-bold text-xs text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-users text-indigo-600"></i> Tim HR & Mentor
                                </div>
                                <p class="text-slate-500 text-[11px] leading-relaxed">
                                    Undang anggota tim HR, interviewer, atau mentor pembimbing magang dengan hak akses yang terkelola.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Step 3: Publikasi Lowongan -->
                    <section id="alur-3" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center shrink-0">3</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Publikasi Lowongan Kerja & Magang</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Buka menu <strong>Kelola Lowongan &rarr; Buat Lowongan Baru</strong> dan isi parameter lengkap:
                        </p>
                        
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2 text-xs text-slate-700">
                            <ul class="space-y-2">
                                <li><strong>Informasi Dasar:</strong> Judul Pekerjaan, Divisi/Bidang, Tipe Kerja (Full-Time, Magang, Kontrak, Remote), dan Kuota Pendaftar.</li>
                                <li><strong>Kompensasi:</strong> Tentukan rentang gaji atau uang saku magang transparan beserta daftar tunjangan/benefit (BPJS, Bonus, Fleksibel WFH).</li>
                                <li><strong>Kriteria Kualifikasi:</strong> Jenjang pendidikan minimal, jurusan yang diutamakan, syarat keahlian (skills), dan batas usia.</li>
                                <li><strong>Batas Waktu (Deadline):</strong> Tentukan tanggal akhir penerimaan berkas lamaran.</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Step 4: Soal Tes -->
                    <section id="alur-4" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center shrink-0">4</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Penyusunan Modul Tes Asesmen Online</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Otomatisasi penyaringan kandidat dengan menyematkan tes online pada lowongan kerja:
                        </p>
                        <div class="p-4 bg-white rounded-xl border border-slate-200 space-y-2 text-xs text-slate-600">
                            <div class="flex items-center gap-2 font-bold text-slate-900">
                                <i class="fa-solid fa-file-circle-question text-blue-600"></i> Fitur Modul Tes:
                            </div>
                            <ul class="list-disc pl-5 space-y-1.5 text-slate-600">
                                <li><strong>Pilihan Ganda Otomatis:</strong> Masukkan butir soal, opsi jawaban (A/B/C/D), kunci jawaban, dan bobot nilai.</li>
                                <li><strong>Durasi Ujian:</strong> Atur alokasi waktu pengerjaan dalam satuan menit.</li>
                                <li><strong>Skor Kelulusan (Passing Grade):</strong> Tentukan nilai minimum agar kandidat otomatis dapat diproses ke tahap wawancara.</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Step 5: Pipeline Funnel -->
                    <section id="alur-5" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center shrink-0">5</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Manajemen Pipeline Funnel Seleksi Kandidat</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Pantau seluruh pelamar dalam papan kanban/pipeline rekrutmen terintegrasi:
                        </p>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2.5 text-xs text-slate-700">
                            <p>• <strong>Screening Berkas:</strong> Tinjau profil, resume PDF, dan video pitch pelamar dengan kalkulasi kecocokan kualifikasi.</p>
                            <p>• <strong>Tahap Tes & Interview:</strong> Pindahkan kandidat antar tahapan (Screening &rarr; Tes &rarr; Wawancara HR &rarr; Wawancara User) dengan 1 klik.</p>
                            <p>• <strong>Fitur Chat Langsung:</strong> Hubungi kandidat langsung di portal untuk konfirmasi kelengkapan berkas atau info seleksi.</p>
                            <p>• <strong>Pemberitahuan Penolakan Otomatis:</strong> Kirimkan email penolakan yang ramah dan profesional jika kandidat belum memenuhi syarat.</p>
                        </div>
                    </section>

                    <!-- Step 6: Interview & Evaluasi -->
                    <section id="alur-6" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center shrink-0">6</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Penjadwalan Wawancara & Input Lembar Evaluasi</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Atur jadwal interview dan input nilai asesmen wawancara:
                        </p>
                        <div class="space-y-3">
                            <div class="p-4 rounded-xl border border-slate-200 bg-white text-xs space-y-1.5">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-calendar-days text-blue-600"></i> Integrasi Kalender & Tautan Meeting
                                </div>
                                <p class="text-slate-600 leading-relaxed">
                                    Tentukan jadwal temu dan lampirkan link Google Meet/Zoom atau alamat kantor. Jadwal akan otomatis tersinkronisasi ke dashboard kandidat.
                                </p>
                            </div>

                            <div class="p-4 rounded-xl border border-slate-200 bg-white text-xs space-y-1.5">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-clipboard-check text-emerald-600"></i> Lembar Penilaian Wawancara
                                </div>
                                <p class="text-slate-600 leading-relaxed">
                                    Tim pewawancara dapat memasukkan skor kriteria (komunikasi, problem solving, teknis) dan catatan evaluasi yang tersimpan rapi pada rekam jejak kandidat.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Step 7: Offering & Dokumen -->
                    <section id="alur-7" class="scroll-mt-24 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center shrink-0">7</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Penerbitan Surat Penawaran, Perjanjian Kerja & Sertifikat</h2>
                        </div>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Lakukan onboarding digital tanpa perlu dokumen fisik:
                        </p>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3 text-xs text-slate-700">
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">📄 1. Penerbitan Offer Letter</h4>
                                <p class="text-slate-600 leading-relaxed">Terbitkan surat penawaran resmi berformat PDF dengan kop perusahaan yang dapat ditandatangani dan direspons kandidat.</p>
                            </div>
                            <div class="pt-2 border-t border-slate-200">
                                <h4 class="font-bold text-slate-900 mb-1">✍️ 2. Perjanjian Kerja / Magang Digital (OTP)</h4>
                                <p class="text-slate-600 leading-relaxed">Klausul hak dan kewajiban disahkan melalui verifikasi digital OTP kedua belah pihak yang sah dan terekam sistem.</p>
                            </div>
                            <div class="pt-2 border-t border-slate-200">
                                <h4 class="font-bold text-slate-900 mb-1">🎓 3. Manajemen Magang & Sertifikasi Resmi</h4>
                                <p class="text-slate-600 leading-relaxed">Mentor dapat menyetujui logbook kegiatan harian dan otomatis menerbitkan <strong>Sertifikat Magang & Transkrip Nilai</strong> ber-QR Code di akhir masa program.</p>
                            </div>
                        </div>
                    </section>

                    <!-- Bottom HR CTA Callout -->
                    <div class="p-6 bg-slate-900 text-white rounded-2xl border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h4 class="font-bold text-sm text-white">Siap Membuka Lowongan & Merekrut Talenta?</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Kelola lowongan dan temukan kandidat terbaik untuk tim Anda hari ini.</p>
                        </div>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition shrink-0">
                            Pasang Lowongan Kerja &rarr;
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-public-layout>
