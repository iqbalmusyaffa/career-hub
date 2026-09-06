<x-public-layout>
    <!-- Hero Header -->
    <div class="bg-gradient-to-b from-blue-50/60 via-slate-50/30 to-white pt-12 pb-14 border-b border-slate-200/80">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Breadcrumb -->
            <nav class="flex items-center justify-center gap-2 text-xs font-medium text-slate-500 mb-5">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Beranda</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-blue-600 font-semibold">Pusat Bantuan & FAQ</span>
            </nav>

            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold mb-4 border border-blue-200 shadow-2xs">
                <i class="fa-solid fa-circle-question text-blue-600 text-xs"></i> Pusat Bantuan & Tanya Jawab
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
                Pertanyaan yang Sering Diajukan
            </h1>
            <p class="text-slate-600 text-xs sm:text-base leading-relaxed max-w-2xl mx-auto">
                Temukan jawaban lengkap seputar pembuatan akun, proses melamar kerja, pengerjaan tes online, publikasi lowongan bagi HR, hingga tanda tangan kontrak digital.
            </p>
        </div>
    </div>

    <!-- Main FAQ Content with Interactive Filtering -->
    <div class="py-12 bg-white min-h-screen" x-data="{
        search: '',
        activeCategory: 'all',
        activeAccordion: null,
        toggle(id) {
            this.activeAccordion = (this.activeAccordion === id ? null : id);
        }
    }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Search & Quick Filters -->
            <div class="space-y-4">
                <!-- Search Input Box -->
                <div class="relative max-w-2xl mx-auto">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </div>
                    <input type="text" 
                           x-model="search" 
                           placeholder="Ketik kata kunci pertanyaan (misal: CV, tes online, verifikasi, gaji)..." 
                           class="block w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 text-xs sm:text-sm font-medium bg-slate-50/70 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition shadow-xs">
                    <button x-show="search.length > 0" 
                            @click="search = ''" 
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-circle-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Category Pill Tabs -->
                <div class="flex flex-wrap items-center justify-center gap-2 pt-2">
                    <button @click="activeCategory = 'all'" 
                            :class="activeCategory === 'all' ? 'bg-blue-600 text-white shadow-xs font-semibold' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200'"
                            class="px-4 py-2 rounded-xl text-xs transition cursor-pointer">
                        Semua Kategori
                    </button>
                    <button @click="activeCategory = 'pelamar'" 
                            :class="activeCategory === 'pelamar' ? 'bg-blue-600 text-white shadow-xs font-semibold' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200'"
                            class="px-4 py-2 rounded-xl text-xs transition cursor-pointer flex items-center gap-1.5">
                        <i class="fa-solid fa-user-graduate text-xs"></i> Pelamar Kerja
                    </button>
                    <button @click="activeCategory = 'perusahaan'" 
                            :class="activeCategory === 'perusahaan' ? 'bg-blue-600 text-white shadow-xs font-semibold' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200'"
                            class="px-4 py-2 rounded-xl text-xs transition cursor-pointer flex items-center gap-1.5">
                        <i class="fa-solid fa-building text-xs"></i> Perusahaan & HR
                    </button>
                    <button @click="activeCategory = 'magang'" 
                            :class="activeCategory === 'magang' ? 'bg-blue-600 text-white shadow-xs font-semibold' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200'"
                            class="px-4 py-2 rounded-xl text-xs transition cursor-pointer flex items-center gap-1.5">
                        <i class="fa-solid fa-graduation-cap text-xs"></i> Magang & Sertifikasi
                    </button>
                    <button @click="activeCategory = 'keamanan'" 
                            :class="activeCategory === 'keamanan' ? 'bg-blue-600 text-white shadow-xs font-semibold' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200'"
                            class="px-4 py-2 rounded-xl text-xs transition cursor-pointer flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-xs"></i> Keamanan & Akun
                    </button>
                </div>
            </div>

            <!-- FAQ List Items -->
            <div class="space-y-4 pt-4">

                <!-- 1. Pelamar: Cara Melamar -->
                <div x-show="(activeCategory === 'all' || activeCategory === 'pelamar') && ('bagaimana cara mendaftar akun dan melamar pekerjaan gratis cv'.includes(search.toLowerCase()) || search === '')"
                     class="border border-slate-200 rounded-2xl overflow-hidden transition bg-white shadow-2xs">
                    <button @click="toggle('q1')" 
                            class="w-full text-left p-4 sm:p-5 bg-white hover:bg-slate-50 flex items-center justify-between gap-4 font-bold text-slate-900 text-xs sm:text-sm transition">
                        <span class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs shrink-0 font-bold">1</span>
                            Bagaimana cara mendaftar akun dan mulai melamar pekerjaan?
                        </span>
                        <i class="fa-solid text-xs transition-transform duration-200" :class="activeAccordion === 'q1' ? 'fa-chevron-up text-blue-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeAccordion === 'q1'" x-collapse class="p-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50 space-y-2">
                        <p>Anda dapat mendaftar akun gratis di menu <a href="{{ route('register') }}" class="text-blue-600 font-semibold underline">Daftar Akun</a> (pilih peran Kandidat / Pelamar Kerja). Setelah akun terverifikasi melalui email, lengkapi data profil, pengalaman kerja, keahlian, dan unggah CV PDF terbaru Anda.</p>
                        <p>Selanjutnya, buka menu <strong>Lowongan</strong>, pilih posisi yang diminati, dan tekan tombol <strong>"Lamar Pekerjaan"</strong>.</p>
                    </div>
                </div>

                <!-- 2. Pelamar: Apakah Berbayar? -->
                <div x-show="(activeCategory === 'all' || activeCategory === 'pelamar') && ('apakah melamar kerja di portal ini dipungut biaya gratis uang pungutan'.includes(search.toLowerCase()) || search === '')"
                     class="border border-slate-200 rounded-2xl overflow-hidden transition bg-white shadow-2xs">
                    <button @click="toggle('q2')" 
                            class="w-full text-left p-4 sm:p-5 bg-white hover:bg-slate-50 flex items-center justify-between gap-4 font-bold text-slate-900 text-xs sm:text-sm transition">
                        <span class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs shrink-0 font-bold">2</span>
                            Apakah melamar kerja di portal ini dipungut biaya?
                        </span>
                        <i class="fa-solid text-xs transition-transform duration-200" :class="activeAccordion === 'q2' ? 'fa-chevron-up text-blue-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeAccordion === 'q2'" x-collapse class="p-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50 space-y-2">
                        <p><strong>Tidak ada biaya apa pun (100% Gratis).</strong> Platform ini melarang keras segala bentuk pungutan biaya rekrutmen, biaya pelatihan pra-kerja, maupun biaya tiket akomodasi.</p>
                        <p>Jika Anda menemukan lowongan yang meminta uang atau pembayaran tertentu, segera gunakan tombol <strong>"Laporkan Lowongan (Red Flag)"</strong> pada halaman detail lowongan tersebut.</p>
                    </div>
                </div>

                <!-- 3. Pelamar: Apa itu Match Score? -->
                <div x-show="(activeCategory === 'all' || activeCategory === 'pelamar') && ('apa itu match score persentase kecocokan kualifikasi jurusan skill'.includes(search.toLowerCase()) || search === '')"
                     class="border border-slate-200 rounded-2xl overflow-hidden transition bg-white shadow-2xs">
                    <button @click="toggle('q3')" 
                            class="w-full text-left p-4 sm:p-5 bg-white hover:bg-slate-50 flex items-center justify-between gap-4 font-bold text-slate-900 text-xs sm:text-sm transition">
                        <span class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs shrink-0 font-bold">3</span>
                            Apa yang dimaksud dengan skor Match Score (%) pada lowongan?
                        </span>
                        <i class="fa-solid text-xs transition-transform duration-200" :class="activeAccordion === 'q3' ? 'fa-chevron-up text-blue-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeAccordion === 'q3'" x-collapse class="p-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50">
                        <strong>Match Score</strong> adalah sistem cerdas yang menghitung tingkat kecocokan profil Anda (jurusan pendidikan, keahlian teknis, dan pengalaman kerja) dengan kualifikasi yang dicari oleh perusahaan. Skor yang tinggi membantu memperbesar peluang berkas Anda diprioritaskan oleh HR.
                    </div>
                </div>

                <!-- 4. Pelamar: CV Builder ATS -->
                <div x-show="(activeCategory === 'all' || activeCategory === 'pelamar') && ('bagaimana cara menggunakan cv builder otomatis format ats'.includes(search.toLowerCase()) || search === '')"
                     class="border border-slate-200 rounded-2xl overflow-hidden transition bg-white shadow-2xs">
                    <button @click="toggle('q4')" 
                            class="w-full text-left p-4 sm:p-5 bg-white hover:bg-slate-50 flex items-center justify-between gap-4 font-bold text-slate-900 text-xs sm:text-sm transition">
                        <span class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs shrink-0 font-bold">4</span>
                            Bagaimana cara menggunakan fitur CV Builder online gratis?
                        </span>
                        <i class="fa-solid text-xs transition-transform duration-200" :class="activeAccordion === 'q4' ? 'fa-chevron-up text-blue-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeAccordion === 'q4'" x-collapse class="p-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50">
                        Setelah masuk ke akun kandidat, buka menu <strong>Profil & CV &rarr; CV Builder</strong>. Isi riwayat pendidikan, pengalaman kerja, sertifikasi, dan kontak. Sistem akan otomatis memformat resume Anda dengan tata letak profesional standar ATS yang siap diunduh dalam format PDF.
                    </div>
                </div>

                <!-- 5. Pelamar: Tes Ujian Online -->
                <div x-show="(activeCategory === 'all' || activeCategory === 'pelamar') && ('bagaimana cara mengikuti tes online asesmen ujian seleksi'.includes(search.toLowerCase()) || search === '')"
                     class="border border-slate-200 rounded-2xl overflow-hidden transition bg-white shadow-2xs">
                    <button @click="toggle('q5')" 
                            class="w-full text-left p-4 sm:p-5 bg-white hover:bg-slate-50 flex items-center justify-between gap-4 font-bold text-slate-900 text-xs sm:text-sm transition">
                        <span class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs shrink-0 font-bold">5</span>
                            Bagaimana cara mengikuti ujian atau tes asesmen online dari perusahaan?
                        </span>
                        <i class="fa-solid text-xs transition-transform duration-200" :class="activeAccordion === 'q5' ? 'fa-chevron-up text-blue-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeAccordion === 'q5'" x-collapse class="p-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50 space-y-2">
                        <p>Jika lamaran Anda diloloskan ke tahap tes, notifikasi dan tombol <strong>"Mulai Kerjakan Tes"</strong> akan muncul di kartu lamaran dashboard Anda.</p>
                        <p>Pastikan Anda memiliki koneksi internet yang stabil sebelum menekan tombol mulai, karena ujian dilengkapi penghitung waktu mundur (*timer*) dan jawaban tersimpan otomatis.</p>
                    </div>
                </div>

                <!-- 6. Perusahaan: Verifikasi Akun HR -->
                <div x-show="(activeCategory === 'all' || activeCategory === 'perusahaan') && ('bagaimana cara mendaftarkan akun perusahaan hr verifikasi legalitas'.includes(search.toLowerCase()) || search === '')"
                     class="border border-slate-200 rounded-2xl overflow-hidden transition bg-white shadow-2xs">
                    <button @click="toggle('q6')" 
                            class="w-full text-left p-4 sm:p-5 bg-white hover:bg-slate-50 flex items-center justify-between gap-4 font-bold text-slate-900 text-xs sm:text-sm transition">
                        <span class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs shrink-0 font-bold">6</span>
                            Bagaimana cara mendaftarkan perusahaan dan verifikasi legalitas?
                        </span>
                        <i class="fa-solid text-xs transition-transform duration-200" :class="activeAccordion === 'q6' ? 'fa-chevron-up text-emerald-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeAccordion === 'q6'" x-collapse class="p-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50 space-y-2">
                        <p>Daftarkan akun baru, lalu pada menu Profil klik <strong>"Ajukan Peran Perusahaan / HR"</strong>. Lengkapi identitas perusahaan (nama resmi, logo, deskripsi bisnis, alamat kantor cabang) serta unggah dokumen legalitas (NIB / SIUP / NPWP Perusahaan).</p>
                        <p>Tim Admin akan meninjau dokumen legalitas tersebut untuk menyematkan lencana <strong>Perusahaan Terverifikasi Resmi</strong>.</p>
                    </div>
                </div>

                <!-- 7. Perusahaan: Pasang Lowongan & Modul Tes -->
                <div x-show="(activeCategory === 'all' || activeCategory === 'perusahaan') && ('bagaimana cara memasang lowongan kerja dan membuat soal tes'.includes(search.toLowerCase()) || search === '')"
                     class="border border-slate-200 rounded-2xl overflow-hidden transition bg-white shadow-2xs">
                    <button @click="toggle('q7')" 
                            class="w-full text-left p-4 sm:p-5 bg-white hover:bg-slate-50 flex items-center justify-between gap-4 font-bold text-slate-900 text-xs sm:text-sm transition">
                        <span class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs shrink-0 font-bold">7</span>
                            Bagaimana cara mempublikasikan lowongan kerja dan menyusun soal ujian?
                        </span>
                        <i class="fa-solid text-xs transition-transform duration-200" :class="activeAccordion === 'q7' ? 'fa-chevron-up text-emerald-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeAccordion === 'q7'" x-collapse class="p-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50 space-y-2">
                        <p>Di dashboard Admin/HR, buka menu <strong>Lowongan Kerja &rarr; Tambah Lowongan</strong>. Isi divisi pekerjaan, rentang gaji/uang saku, kualifikasi jurusan, kuota pelamar, dan batas waktu pendaftaran.</p>
                        <p>Anda juga dapat menambahkan butir soal pilihan ganda, durasi ujian, dan batas nilai kelulusan (*passing grade*) secara langsung di tab Modul Ujian.</p>
                    </div>
                </div>

                <!-- 8. Perusahaan: Manajemen Pipeline & Wawancara -->
                <div x-show="(activeCategory === 'all' || activeCategory === 'perusahaan') && ('bagaimana mengelola funnel pelamar dan mengatur jadwal interview'.includes(search.toLowerCase()) || search === '')"
                     class="border border-slate-200 rounded-2xl overflow-hidden transition bg-white shadow-2xs">
                    <button @click="toggle('q8')" 
                            class="w-full text-left p-4 sm:p-5 bg-white hover:bg-slate-50 flex items-center justify-between gap-4 font-bold text-slate-900 text-xs sm:text-sm transition">
                        <span class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs shrink-0 font-bold">8</span>
                            Bagaimana mengelola alur rekrutmen dan menjadwalkan wawancara?
                        </span>
                        <i class="fa-solid text-xs transition-transform duration-200" :class="activeAccordion === 'q8' ? 'fa-chevron-up text-emerald-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeAccordion === 'q8'" x-collapse class="p-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50 space-y-2">
                        <p>Gunakan papan <strong>Recruitment Pipeline Funnel</strong> untuk memindahkan status kandidat (*Screening &rarr; Tes &rarr; Wawancara &rarr; Offering*).</p>
                        <p>Untuk wawancara, klik nama kandidat dan tentukan tanggal, jam, serta lampirkan link Google Meet/Zoom pada menu Jadwalkan Interview. Jadwal akan otomatis tersinkron ke dashboard kandidat.</p>
                    </div>
                </div>

                <!-- 9. Magang: Kontrak Digital OTP & Logbook -->
                <div x-show="(activeCategory === 'all' || activeCategory === 'magang') && ('bagaimana tanda tangan kontrak digital otp logbook harian magang'.includes(search.toLowerCase()) || search === '')"
                     class="border border-slate-200 rounded-2xl overflow-hidden transition bg-white shadow-2xs">
                    <button @click="toggle('q9')" 
                            class="w-full text-left p-4 sm:p-5 bg-white hover:bg-slate-50 flex items-center justify-between gap-4 font-bold text-slate-900 text-xs sm:text-sm transition">
                        <span class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs shrink-0 font-bold">9</span>
                            Bagaimana proses tanda tangan perjanjian digital OTP dan pengisian logbook magang?
                        </span>
                        <i class="fa-solid text-xs transition-transform duration-200" :class="activeAccordion === 'q9' ? 'fa-chevron-up text-indigo-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeAccordion === 'q9'" x-collapse class="p-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50 space-y-2">
                        <p>Setelah pelamar dinyatakan diterima, HR dapat menerbitkan dokumen <strong>Perjanjian Kerja / Magang Digital</strong>. Pelamar dan perusahaan mengesahkan dokumen melalui kode verifikasi <strong>OTP email</strong> yang terekam secara legal di sistem.</p>
                        <p>Peserta magang dapat mengisi laporan kegiatan di menu <strong>Logbook Magang</strong> setiap hari kerja untuk disetujui (*Approve/Reject*) oleh mentor pembimbing perusahaan.</p>
                    </div>
                </div>

                <!-- 10. Magang: Sertifikat & Transkrip Verifikasi -->
                <div x-show="(activeCategory === 'all' || activeCategory === 'magang') && ('bagaimana cara mendapatkan sertifikat magang dan transkrip nilai qr code'.includes(search.toLowerCase()) || search === '')"
                     class="border border-slate-200 rounded-2xl overflow-hidden transition bg-white shadow-2xs">
                    <button @click="toggle('q10')" 
                            class="w-full text-left p-4 sm:p-5 bg-white hover:bg-slate-50 flex items-center justify-between gap-4 font-bold text-slate-900 text-xs sm:text-sm transition">
                        <span class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs shrink-0 font-bold">10</span>
                            Kapan sertifikat magang dan transkrip nilai diterbitkan?
                        </span>
                        <i class="fa-solid text-xs transition-transform duration-200" :class="activeAccordion === 'q10' ? 'fa-chevron-up text-indigo-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeAccordion === 'q10'" x-collapse class="p-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50">
                        Di akhir periode magang, mentor akan menginput lembar evaluasi akhir. Sistem secara otomatis menerbitkan <strong>Sertifikat Resmi Magang</strong> dan <strong>Transkrip Nilai Akademik</strong> berformat PDF yang dilengkapi QR Code verifikasi publik yang dapat dicek keabsahannya oleh perguruan tinggi atau pihak ketiga.
                    </div>
                </div>

                <!-- 11. Keamanan: Privasi & Pelaporan Red Flag -->
                <div x-show="(activeCategory === 'all' || activeCategory === 'keamanan') && ('bagaimana keamanan data cv dan cara melaporkan lowongan red flag penipuan'.includes(search.toLowerCase()) || search === '')"
                     class="border border-slate-200 rounded-2xl overflow-hidden transition bg-white shadow-2xs">
                    <button @click="toggle('q11')" 
                            class="w-full text-left p-4 sm:p-5 bg-white hover:bg-slate-50 flex items-center justify-between gap-4 font-bold text-slate-900 text-xs sm:text-sm transition">
                        <span class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs shrink-0 font-bold">11</span>
                            Bagaimana keamanan data CV saya dan cara melaporkan lowongan mencurigakan?
                        </span>
                        <i class="fa-solid text-xs transition-transform duration-200" :class="activeAccordion === 'q11' ? 'fa-chevron-up text-purple-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeAccordion === 'q11'" x-collapse class="p-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50 space-y-2">
                        <p>Seluruh data pribadi, nomor kontak, dan berkas resume Anda dilindungi dengan enkripsi SSL dan hanya dapat diakses oleh perusahaan yang Anda lamar.</p>
                        <p>Jika Anda menemukan indikasi kecurangan atau lowongan fiktif, klik tombol <strong>"Laporkan Lowongan"</strong> pada halaman posisi terkait. Tim investigasi platform akan segera menindaklanjuti dan membekukan akun penyelenggara yang melanggar ketentuan.</p>
                    </div>
                </div>

            </div>

            <!-- Empty Search State -->
            <div x-show="search.length > 0 && !(
                ('bagaimana cara mendaftar akun dan melamar pekerjaan gratis cv'.includes(search.toLowerCase())) ||
                ('apakah melamar kerja di portal ini dipungut biaya gratis uang pungutan'.includes(search.toLowerCase())) ||
                ('apa itu match score persentase kecocokan kualifikasi jurusan skill'.includes(search.toLowerCase())) ||
                ('bagaimana cara menggunakan cv builder otomatis format ats'.includes(search.toLowerCase())) ||
                ('bagaimana cara mengikuti tes online asesmen ujian seleksi'.includes(search.toLowerCase())) ||
                ('bagaimana cara mendaftarkan akun perusahaan hr verifikasi legalitas'.includes(search.toLowerCase())) ||
                ('bagaimana cara memasang lowongan kerja dan membuat soal tes'.includes(search.toLowerCase())) ||
                ('bagaimana mengelola funnel pelamar dan mengatur jadwal interview'.includes(search.toLowerCase())) ||
                ('bagaimana tanda tangan kontrak digital otp logbook harian magang'.includes(search.toLowerCase())) ||
                ('bagaimana cara mendapatkan sertifikat magang dan transkrip nilai qr code'.includes(search.toLowerCase())) ||
                ('bagaimana keamanan data cv dan cara melaporkan lowongan red flag penipuan'.includes(search.toLowerCase()))
            )" class="text-center py-12 bg-slate-50 rounded-2xl border border-slate-200">
                <i class="fa-solid fa-magnifying-glass text-2xl text-slate-400 mb-2"></i>
                <h4 class="text-sm font-bold text-slate-800">Pertanyaan tidak ditemukan</h4>
                <p class="text-xs text-slate-500 mt-1">Coba gunakan kata kunci lain seperti <em>CV, tes, interview, magang,</em> atau <em>verifikasi</em>.</p>
            </div>

            <!-- Need More Help Banner -->
            <div class="p-8 bg-gradient-to-r from-blue-50 to-slate-50 rounded-3xl border border-blue-100 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Belum Menemukan Jawaban yang Anda Cari?</h3>
                    <p class="text-xs text-slate-500 mt-1">Buka panduan lengkap kami atau hubungi tim customer care kami melalui email.</p>
                </div>
                <div class="flex flex-wrap gap-2.5 shrink-0">
                    <a href="{{ route('pages.guide') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                        Buka Pusat Panduan &rarr;
                    </a>
                    <a href="mailto:support@karirhub.id" class="px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 shadow-2xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-envelope text-xs text-slate-500"></i> Hubungi Kami
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-public-layout>
