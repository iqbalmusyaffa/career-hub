<x-app-layout>
    <!-- Header Banner -->
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('candidate.logbook.index') }}" class="w-10 h-10 rounded-2xl bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition flex items-center justify-center shadow-xs shrink-0">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 rounded-md text-3xs font-extrabold uppercase tracking-wider border border-blue-100 dark:border-blue-900">
                            Logbook Magang
                        </span>
                        <span class="text-3xs text-slate-400">•</span>
                        <span class="text-3xs font-bold text-slate-500 dark:text-slate-400">Periode 1</span>
                    </div>
                    <h2 class="font-black text-xl text-slate-900 dark:text-white leading-tight mt-0.5 flex items-center gap-2">
                        Laporan Harian Magang
                        <span class="text-xs font-semibold text-slate-400">({{ $carbonDate->isoFormat('D MMMM YYYY') }})</span>
                    </h2>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('candidate.logbook.index') }}" class="px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-extrabold transition shadow-2xs">
                    ← Kembali ke Kalender
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        gpsLoading: false,
        gpsSuccess: {{ $logbook->latitude ? 'true' : 'false' }},
        isFakeGps: false,
        fakeGpsReason: '',
        gpsAccuracy: null,
        latitude: '{{ $logbook->latitude ?? '' }}',
        longitude: '{{ $logbook->longitude ?? '' }}',
        address: '{{ addslashes($logbook->location_address ?? '') }}',
        lastCapturedTime: '',

        init() {
            @if($isEditable)
                this.getLocation();
            @endif
        },

        async getLocation() {
            if (!{{ $isEditable ? 'true' : 'false' }}) return;
            this.gpsLoading = true;
            this.isFakeGps = false;
            this.fakeGpsReason = '';

            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung Geolocation GPS.');
                this.gpsLoading = false;
                return;
            }

            // Anti Fake GPS Check 1: Automated/Headless browser spoofing
            if (navigator.webdriver) {
                this.isFakeGps = true;
                this.fakeGpsReason = 'Terdeteksi otomatisasi browser / emulator yang tidak sah.';
                this.gpsLoading = false;
                return;
            }

            const options = {
                enableHighAccuracy: true,
                timeout: 12000,
                maximumAge: 0 // Wajib realtime (tidak menggunakan cache lama)
            };

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const coords = position.coords;
                    const accuracy = coords.accuracy;
                    this.gpsAccuracy = Math.round(accuracy);
                    this.lastCapturedTime = new Date().toLocaleTimeString('id-ID');

                    // Anti Fake GPS Check 2: Anomali Nilai Akurasi
                    // Aplikasi Fake GPS / Mock Location sering memberikan accuracy = 0 atau fixed 1m, atau sangat tidak wajar
                    if (accuracy <= 0 || accuracy === 1) {
                        this.isFakeGps = true;
                        this.fakeGpsReason = 'Terdeteksi sinyal Mock Location / Fake GPS buatan (Akurasi artifisial).';
                        this.gpsSuccess = false;
                        this.gpsLoading = false;
                        return;
                    }

                    // Anti Fake GPS Check 3: Timestamp Staleness (Selisih waktu sensor > 30 detik)
                    const timeDiff = Math.abs(Date.now() - position.timestamp);
                    if (timeDiff > 45000) {
                        this.isFakeGps = true;
                        this.fakeGpsReason = 'Terdeteksi manipulasi timestamp lokasi (Cached/Injected GPS).';
                        this.gpsSuccess = false;
                        this.gpsLoading = false;
                        return;
                    }

                    // Anti Fake GPS Check 4: Validasi format & desimal koordinat
                    if (isNaN(coords.latitude) || isNaN(coords.longitude) ||
                        coords.latitude < -90 || coords.latitude > 90 ||
                        coords.longitude < -180 || coords.longitude > 180) {
                        this.isFakeGps = true;
                        this.fakeGpsReason = 'Koordinat GPS berada di luar rentang geografis valid.';
                        this.gpsSuccess = false;
                        this.gpsLoading = false;
                        return;
                    }

                    this.latitude = coords.latitude.toFixed(7);
                    this.longitude = coords.longitude.toFixed(7);
                    this.isFakeGps = false;
                    this.gpsSuccess = true;
                    this.address = `Mengambil alamat lokasi... (${this.latitude}, ${this.longitude})`;

                    // Reverse geocode realtime address via OpenStreetMap Nominatim
                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${coords.latitude}&lon=${coords.longitude}&zoom=18&addressdetails=1`, {
                            headers: { 'Accept-Language': 'id' }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            if (data && data.display_name) {
                                this.address = data.display_name;
                            } else {
                                this.address = `Koordinat Terverifikasi (${this.latitude}, ${this.longitude})`;
                            }
                        } else {
                            this.address = `Koordinat Terverifikasi (${this.latitude}, ${this.longitude})`;
                        }
                    } catch (e) {
                        this.address = `Koordinat Terverifikasi (${this.latitude}, ${this.longitude})`;
                    }

                    this.gpsLoading = false;
                },
                (error) => {
                    let msg = 'Gagal mengambil lokasi GPS.';
                    if (error.code === error.PERMISSION_DENIED) {
                        msg = 'Akses lokasi GPS ditolak oleh pengguna. Mohon izinkan akses lokasi pada browser untuk melakukan presensi.';
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        msg = 'Informasi lokasi tidak tersedia. Pastikan GPS perangkat Anda aktif.';
                    } else if (error.code === error.TIMEOUT) {
                        msg = 'Pengambilan sinyal GPS satelit waktu habis (timeout). Silakan coba lagi.';
                    }
                    alert(msg);
                    this.gpsLoading = false;
                    this.gpsSuccess = false;
                },
                options
            );
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <form action="{{ route('candidate.logbook.store') }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $carbonDate->format('Y-m-d') }}">
                <input type="hidden" name="latitude" x-model="latitude">
                <input type="hidden" name="longitude" x-model="longitude">
                <input type="hidden" name="location_address" x-model="address">

                <!-- 2-Column Responsive Dashboard Grid (lg:grid-cols-12) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- LEFT COLUMN: Main Logbook Input Form (lg:col-span-8) -->
                    <div class="lg:col-span-8 space-y-6">
                        
                        <!-- Main Card -->
                        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200/80 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                            
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/80 pb-5">
                                <div>
                                    <span class="text-3xs font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest block">DETAIL LAPORAN HARIAN</span>
                                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">
                                        {{ $carbonDate->isoFormat('D MMMM YYYY') }}
                                    </h3>
                                </div>

                                <div class="hidden sm:block">
                                    @if($logbook->status === 'approved')
                                        <span class="px-4 py-1.5 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 rounded-full text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle-check"></i> Disetujui Mentor
                                        </span>
                                    @elseif($logbook->status === 'rejected')
                                        <span class="px-4 py-1.5 bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/30 rounded-full text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle-xmark"></i> Kehadiran Ditolak (Bisa Diedit)
                                        </span>
                                    @elseif($logbook->status === 'action_required')
                                        <span class="px-4 py-1.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30 rounded-full text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-triangle-exclamation"></i> Perlu Revisi (Bisa Diedit)
                                        </span>
                                    @else
                                        <span class="px-4 py-1.5 bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/30 rounded-full text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                                            Menunggu Persetujuan
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if(isset($unlockRequest) && $unlockRequest)
                                <!-- Super Admin Unlock Dispensation Banner -->
                                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-sm shrink-0">
                                            <i class="fa-solid fa-file-circle-check"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-emerald-900 dark:text-emerald-200">
                                                Dispensasi Resmi Presensi Disetujui
                                            </h4>
                                            <p class="text-3xs text-emerald-700 dark:text-emerald-300 font-medium mt-0.5">
                                                Permohonan buka kunci oleh Mentor telah disetujui Super Admin (Aktif s/d: {{ $unlockRequest->unlocked_until ? $unlockRequest->unlocked_until->format('d/m/Y H:i') . ' WIB' : '-' }}).
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('candidate.unlock-requests.pdf', $unlockRequest->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition shrink-0 shadow-2xs">
                                        <i class="fa-solid fa-file-pdf"></i>
                                        <span>Unduh Surat Resmi PDF</span>
                                    </a>
                                </div>
                            @endif

                            @if(!$isEditable)
                                <!-- Read-Only Lock Banner -->
                                <div class="p-4 bg-slate-100 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-700 rounded-2xl flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-base shrink-0">
                                        🔒
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-800 dark:text-slate-200">
                                            Laporan Dikunci & Tidak Dapat Diedit
                                        </h4>
                                        <p class="text-3xs text-slate-600 dark:text-slate-400 leading-relaxed font-medium mt-1">
                                            {{ $lockReason ?? 'Laporan harian ini telah dikunci sesuai kebijakan presensi.' }}
                                        </p>
                                    </div>
                                </div>
                            @elseif(in_array($logbook->status, ['rejected', 'action_required']))
                                <!-- Action Required / Rejected Editable Banner -->
                                <div class="p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-2xl flex items-start gap-3">
                                    <i class="fa-solid fa-pen-to-square text-amber-500 text-lg shrink-0 mt-0.5"></i>
                                    <div>
                                        <h4 class="text-xs font-black text-amber-900 dark:text-amber-300">
                                            Akses Pengeditan Terbuka (Perlu Revisi / Ditolak)
                                        </h4>
                                        <p class="text-3xs text-amber-800 dark:text-amber-200 leading-relaxed font-medium mt-0.5">
                                            Silakan perbaiki uraian aktivitas atau dokumen pendukung sesuai catatan Mentor di bawah ini, lalu klik **Kirim Ulang Laporan**.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if($logbook->mentor_notes)
                                <!-- Mentor Feedback Notes -->
                                <div class="p-5 bg-amber-500/10 border border-amber-500/30 rounded-2xl flex items-start gap-3">
                                    <i class="fa-solid fa-comment-dots text-amber-500 text-lg shrink-0 mt-0.5"></i>
                                    <div>
                                        <h4 class="text-xs font-black text-amber-900 dark:text-amber-300 mb-1">
                                            Catatan Bimbingan Mentor:
                                        </h4>
                                        <p class="text-xs text-amber-800 dark:text-amber-200 leading-relaxed font-medium">
                                            "{{ $logbook->mentor_notes }}"
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Field 1: Kehadiran -->
                            <div>
                                <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-user-check text-blue-500"></i>
                                    Kehadiran
                                </label>
                                <select name="attendance_type" {{ !$isEditable ? 'disabled' : '' }} class="w-full text-xs font-extrabold rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 py-3.5 px-4 transition {{ !$isEditable ? 'bg-slate-100 dark:bg-slate-900/80 cursor-not-allowed opacity-80' : '' }}">
                                    <option value="Hadir" {{ old('attendance_type', $logbook->attendance_type_label ?? $logbook->attendance_type) === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="Tidak Hadir Dengan Keterangan" {{ old('attendance_type', $logbook->attendance_type_label ?? $logbook->attendance_type) === 'Tidak Hadir Dengan Keterangan' ? 'selected' : '' }}>Tidak Hadir Dengan Keterangan</option>
                                    <option value="Tidak Hadir Tanpa Keterangan" {{ old('attendance_type', $logbook->attendance_type_label ?? $logbook->attendance_type) === 'Tidak Hadir Tanpa Keterangan' ? 'selected' : '' }}>Tidak Hadir Tanpa Keterangan</option>
                                </select>
                            </div>

                            <!-- Field 2: Uraian Aktivitas Pekerjaan -->
                            <div>
                                <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-list-check text-blue-500"></i>
                                    Uraian Aktivitas Pekerjaan
                                </label>
                                <textarea name="activities" rows="5" {{ !$isEditable ? 'disabled' : '' }} placeholder="Tuliskan detail aktivitas, tugas, dan fitur yang Anda selesaikan..." 
                                    class="w-full text-xs leading-relaxed font-medium rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 p-4 transition shadow-2xs {{ !$isEditable ? 'bg-slate-100 dark:bg-slate-900/80 cursor-not-allowed opacity-80' : '' }}">{{ old('activities', $logbook->activities ?? 'Setelah menyelesaikan proses testing dan pengecekan issue sebelumnya, kegiatan dilanjutkan dengan mempelajari dan memperdalam pemahaman mengenai bahasa pemrograman C#, framework .NET, serta database PostgreSQL yang digunakan dalam pengembangan aplikasi.') }}</textarea>
                            </div>

                            <!-- Field 3: Pembelajaran yang Diperoleh -->
                            <div>
                                <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-lightbulb text-amber-500"></i>
                                    Pembelajaran yang Diperoleh
                                </label>
                                <textarea name="learnings" rows="4" {{ !$isEditable ? 'disabled' : '' }} placeholder="Tuliskan pengetahuan teknis atau wawasan baru yang Anda dapatkan..." 
                                    class="w-full text-xs leading-relaxed font-medium rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 p-4 transition shadow-2xs {{ !$isEditable ? 'bg-slate-100 dark:bg-slate-900/80 cursor-not-allowed opacity-80' : '' }}">{{ old('learnings', $logbook->learnings ?? 'Mempelajari konsep dasar dan struktur bahasa C#, memahami penggunaan framework .NET dalam pengembangan aplikasi, serta mempelajari dasar-dasar PostgreSQL untuk memahami pengelolaan dan penggunaan database pada aplikasi.') }}</textarea>
                            </div>

                            <!-- Field 4: Kendala yang Dialami -->
                            <div>
                                <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                                    Kendala yang Dialami & Solusi
                                </label>
                                <textarea name="challenges" rows="3" {{ !$isEditable ? 'disabled' : '' }} placeholder="Tuliskan hambatan teknis yang dihadapi serta solusinya..." 
                                    class="w-full text-xs leading-relaxed font-medium rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 p-4 transition shadow-2xs {{ !$isEditable ? 'bg-slate-100 dark:bg-slate-900/80 cursor-not-allowed opacity-80' : '' }}">{{ old('challenges', $logbook->challenges ?? 'Dalam proses pembelajaran, masih terdapat beberapa konsep pada C#, .NET, dan PostgreSQL yang perlu dipahami lebih lanjut melalui latihan dan eksplorasi agar dapat lebih memahami penerapannya pada aplikasi.') }}</textarea>
                            </div>

                            <!-- Form Footer Buttons -->
                            <div class="pt-5 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between gap-3">
                                <div class="text-3xs text-slate-500">
                                    <template x-if="isFakeGps">
                                        <span class="text-rose-600 dark:text-rose-400 font-extrabold flex items-center gap-1">
                                            <i class="fa-solid fa-triangle-exclamation"></i> Tombol kirim diblokir karena Fake GPS terdeteksi.
                                        </span>
                                    </template>
                                    <template x-if="!isFakeGps && gpsSuccess">
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check"></i> GPS Realtime Terkunci & Sah
                                        </span>
                                    </template>
                                </div>

                                <div class="flex items-center gap-3">
                                    <a href="{{ route('candidate.logbook.index') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-200 transition">
                                        Kembali
                                    </a>

                                    @if($isEditable)
                                        <button type="submit" 
                                            :disabled="isFakeGps || gpsLoading"
                                            :class="isFakeGps ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-blue-600/30'"
                                            class="px-7 py-3 text-white font-black text-xs rounded-xl shadow-lg transition-all flex items-center gap-2">
                                            <i class="fa-solid fa-paper-plane"></i>
                                            Kirim Laporan ke Mentor
                                        </button>
                                    @else
                                        <button type="button" disabled class="px-6 py-2.5 bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 font-extrabold text-xs rounded-xl cursor-not-allowed flex items-center gap-2 border border-slate-300 dark:border-slate-600">
                                            <i class="fa-solid fa-lock"></i>
                                            Laporan Dikunci (Read-Only)
                                        </button>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Interactive Status, GPS Card & Quick Stats (lg:col-span-4) -->
                    <div class="lg:col-span-4 space-y-6">

                        <!-- Fake GPS Warning Alert Banner (Shown when Fake GPS is Detected) -->
                        <template x-if="isFakeGps">
                            <div class="p-5 bg-gradient-to-br from-rose-500 to-red-600 text-white rounded-3xl shadow-xl border border-red-400 space-y-3 animate-bounce">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center text-xl shrink-0">
                                        🚨
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black uppercase tracking-wider">PERINGATAN: FAKE GPS TERDETEKSI!</h4>
                                        <p class="text-3xs text-rose-100 font-medium">Manipulasi Titik Lokasi Ditolak Sistem</p>
                                    </div>
                                </div>
                                <div class="p-3 bg-black/20 rounded-2xl text-xs leading-relaxed font-semibold">
                                    <span x-text="fakeGpsReason"></span>
                                </div>
                                <p class="text-3xs text-rose-100 leading-tight">
                                    Harap matikan aplikasi Mock Location / Fake GPS dan aktifkan GPS satelit asli perangkat Anda untuk melanjutkan presensi.
                                </p>
                            </div>
                        </template>

                        <!-- Card 1: Status & Verification Overview -->
                        <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white p-6 rounded-3xl shadow-xl border border-slate-700/80 space-y-4">
                            <span class="text-3xs font-black text-blue-400 uppercase tracking-widest block">INFORMASI STATUS HARIAN</span>
                            
                            <div class="p-4 bg-white/5 rounded-2xl border border-white/10 space-y-2">
                                <div class="text-3xs text-slate-400 font-extrabold uppercase">STATUS PERSETUJUAN</div>
                                <div class="text-sm font-black flex items-center gap-2">
                                    @if($logbook->status === 'approved')
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                                        <span class="text-emerald-300">Disetujui Mentor</span>
                                    @elseif($logbook->status === 'rejected')
                                        <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                                        <span class="text-red-300">Kehadiran Ditolak</span>
                                    @elseif($logbook->status === 'action_required')
                                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                                        <span class="text-amber-300">Perlu Revisi</span>
                                    @else
                                        <span class="w-2.5 h-2.5 rounded-full bg-blue-400 animate-pulse"></span>
                                        <span class="text-blue-300">Menunggu ACC Mentor</span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div class="p-3 bg-white/5 rounded-xl border border-white/10">
                                    <div class="text-3xs text-slate-400 font-extrabold uppercase">JAM KERJA</div>
                                    <div class="text-sm font-black text-white mt-0.5">8 Jam</div>
                                </div>
                                <div class="p-3 bg-white/5 rounded-xl border border-white/10">
                                    <div class="text-3xs text-slate-400 font-extrabold uppercase">STATUS HAK EDIT</div>
                                    <div class="text-3xs font-black {{ $isEditable ? 'text-emerald-400' : 'text-amber-400' }} mt-0.5">
                                        {{ $isEditable ? 'Bisa Diedit' : 'Terkunci' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: GPS Location Card Widget -->
                        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-3xs font-black text-slate-400 uppercase tracking-widest block">VALIDASI LOKASI GPS REALTIME</span>
                                <template x-if="isFakeGps">
                                    <span class="px-2 py-0.5 bg-red-100 text-red-600 rounded-full text-3xs font-black uppercase">Fake GPS</span>
                                </template>
                                <template x-if="!isFakeGps && gpsSuccess">
                                    <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 rounded-full text-3xs font-black uppercase flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-ping"></span> Realtime Live
                                    </span>
                                </template>
                            </div>

                            <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-3">
                                <div class="flex items-start gap-2.5 text-xs font-bold text-slate-800 dark:text-slate-200">
                                    <i class="fa-solid fa-location-dot text-rose-500 text-base shrink-0 mt-0.5"></i>
                                    <span x-text="address ? address : 'Menunggu sinkronisasi titik koordinat GPS...'"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-200 dark:border-slate-700/60 text-3xs">
                                    <div>
                                        <span class="text-slate-400 font-extrabold block uppercase">Akurasi Satelit:</span>
                                        <span class="font-bold text-slate-700 dark:text-slate-300" x-text="gpsAccuracy ? `±${gpsAccuracy} meter` : '-'"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 font-extrabold block uppercase">Waktu Lock GPS:</span>
                                        <span class="font-bold text-slate-700 dark:text-slate-300" x-text="lastCapturedTime ? `${lastCapturedTime} WIB` : '-'"></span>
                                    </div>
                                </div>
                                
                                <template x-if="latitude && longitude">
                                    <div class="text-3xs text-slate-400 font-mono bg-white dark:bg-slate-800 p-2 rounded-xl border border-slate-200 dark:border-slate-700">
                                        Lat: <span x-text="latitude"></span>, Lng: <span x-text="longitude"></span>
                                    </div>
                                </template>
                            </div>

                            <button type="button" @click="getLocation()" {{ !$isEditable ? 'disabled' : '' }} class="w-full py-3 bg-slate-900 dark:bg-slate-700 hover:bg-slate-800 text-white rounded-2xl text-xs font-black transition flex items-center justify-center gap-2 shadow-xs {{ !$isEditable ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <i class="fa-solid fa-rotate text-xs" :class="gpsLoading ? 'animate-spin' : ''"></i>
                                <span x-text="gpsLoading ? 'Mengunci Sinyal GPS...' : 'Segarkan / Validasi Titik GPS'"></span>
                            </button>
                        </div>

                        <!-- Server Time Pill -->
                        <div class="flex justify-center">
                            <div class="inline-flex items-center gap-2 px-5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full shadow-xs text-xs font-bold text-slate-700 dark:text-slate-300">
                                <i class="fa-regular fa-clock text-slate-400"></i>
                                <span>Waktu Server</span>
                                <span class="font-extrabold text-slate-900 dark:text-white" x-text="new Date().toLocaleTimeString('id-ID') + ' WIB'"></span>
                            </div>
                        </div>

                    </div>

                </div>
            </form>

        </div>
    </div>
</x-app-layout>
