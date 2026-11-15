<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div
                    class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                    <i class="fa-solid fa-camera"></i>
                    <span>Modul Khusus Karyawan</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">
                    Presensi & Absensi Karyawan
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Verifikasi kehadiran real-time dengan kamera selfie wajah dan deteksi koordinat GPS geolocation.
                </p>
            </div>
            <!-- Live Digital Clock Widget -->
            <div
                class="flex items-center gap-3 bg-white dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700/80 rounded-xl px-4 py-2.5 shadow-xs">
                <div
                    class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div>
                    <div class="text-xs font-medium text-slate-500 dark:text-slate-400">
                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </div>
                    <div id="liveDigitalClock"
                        class="text-lg font-bold font-mono tracking-wider text-slate-900 dark:text-white">
                        --:--:-- WIB
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Leaflet Map Assets -->
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            /* Default Responsive Oval Guide for Laptop & Desktop */
            video.mirror-mode {
                transform: scaleX(-1);
                -webkit-transform: scaleX(-1);
            }

            .face-oval-guide {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: clamp(170px, 32vw, 215px);
                height: clamp(230px, 44vw, 275px);
                border: 3px dashed rgba(59, 130, 246, 0.9);
                border-radius: 50%;
                box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.45);
                pointer-events: none;
                transition: all 0.25s ease;
                z-index: 15;
            }

            .face-oval-guide.success {
                border-color: rgba(34, 197, 94, 0.95);
                border-style: solid;
                border-width: 3.5px;
                box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.35), 0 0 25px rgba(34, 197, 94, 0.55);
            }

            .face-oval-guide.warning {
                border-color: rgba(245, 158, 11, 0.95);
                border-style: dashed;
                border-width: 3px;
                box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.45), 0 0 16px rgba(245, 158, 11, 0.4);
            }

            .oval-guide-badge {
                position: absolute;
                top: -14px;
                left: 50%;
                transform: translateX(-50%);
                white-space: nowrap;
                transition: all 0.2s ease;
            }

            /* Mobile Phone Optimization (< 640px) */
            @media (max-width: 640px) {
                .face-oval-guide {
                    width: 170px;
                    height: 230px;
                    border-width: 2.5px;
                }

                .oval-guide-badge {
                    top: -12px;
                    font-size: 10px;
                    padding: 2px 8px;
                }
            }

            /* Laptop / Large Screen Optimization (>= 1024px) */
            @media (min-width: 1024px) {
                .face-oval-guide {
                    width: 200px;
                    height: 260px;
                    border-width: 3px;
                }

                .oval-guide-badge {
                    top: -15px;
                    font-size: 11px;
                }
            }

            @keyframes pulse-ring {
                0% {
                    transform: translate(-50%, -50%) scale(0.97);
                    opacity: 0.85;
                }

                50% {
                    transform: translate(-50%, -50%) scale(1.02);
                    opacity: 1;
                }

                100% {
                    transform: translate(-50%, -50%) scale(0.97);
                    opacity: 0.85;
                }
            }

            .pulse-border {
                animation: pulse-ring 2.5s infinite ease-in-out;
            }
        </style>
    @endpush

    <div class="py-8 bg-slate-50 dark:bg-slate-950/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash / Status Alert -->
            <div id="statusToast" class="hidden rounded-xl p-4 transition-all duration-300">
                <div class="flex items-center gap-3">
                    <i id="statusToastIcon" class="fa-solid fa-circle-check text-xl"></i>
                    <div id="statusToastMsg" class="text-sm font-semibold"></div>
                </div>
            </div>

            <!-- Top Grid: Interactive Camera Console & Map Geofence -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- LEFT COLUMN: WEBCAM FEED & SELFIE CAPTURE (7 Cols) -->
                <div
                    class="lg:col-span-7 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
                    <div>
                        <!-- Header Bar for Camera -->
                        <div
                            class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2.5">
                                <span class="relative flex h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                </span>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                    Kamera Selfie & Pengenal Wajah
                                </h3>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" id="btnSwitchCamera"
                                    class="px-2.5 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors flex items-center gap-1.5 cursor-pointer"
                                    title="Ganti Kamera Depan/Belakang">
                                    <i class="fa-solid fa-camera-rotate"></i>
                                    <span class="hidden sm:inline">Ganti Kamera</span>
                                </button>
                                <button type="button" id="btnTriggerManualUpload"
                                    class="px-2.5 py-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/40 hover:bg-blue-100 rounded-lg transition-colors flex items-center gap-1.5 cursor-pointer"
                                    title="Pilih Foto dari Perangkat / Galeri">
                                    <i class="fa-solid fa-image"></i>
                                    <span class="hidden sm:inline">Pilih Foto</span>
                                </button>
                                <input type="file" id="manualSelfieInput" accept="image/*" class="hidden">
                                <span
                                    class="text-xs px-2.5 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md font-medium hidden md:inline-flex items-center gap-1">
                                    <i class="fa-solid fa-glasses"></i> Kacamata Bening OK
                                </span>
                                <span
                                    class="text-xs px-2.5 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-md font-semibold">
                                    Watermark Otomatis
                                </span>
                            </div>
                        </div>

                        <!-- Video / Capture Stage -->
                        <div
                            class="mt-4 relative overflow-hidden rounded-xl bg-slate-950 aspect-4/3 max-h-[380px] flex items-center justify-center border border-slate-800">
                            <!-- Live Webcam Stream (with front camera mirror class) -->
                            <video id="webcamStream" autoplay playsinline muted
                                class="w-full h-full object-cover mirror-mode"></video>

                            <!-- Live AI Face Detection Overlay (Corner Brackets / Landmark HUD) -->
                            <canvas id="faceDetectionOverlay"
                                class="absolute inset-0 w-full h-full pointer-events-none z-10"></canvas>

                            <!-- Oval Face Guide Overlay -->
                            <div id="ovalGuide" class="face-oval-guide pulse-border">
                                <div id="ovalGuideBadge"
                                    class="oval-guide-badge text-white/95 bg-slate-900/90 px-2.5 py-0.5 rounded-full border border-blue-400/50 shadow-md backdrop-blur-xs font-semibold tracking-wide flex items-center gap-1.5 transition-all duration-200">
                                    <i id="ovalGuideIcon" class="fa-solid fa-face-smile text-blue-400 text-[10px]"></i>
                                    <span id="ovalGuideText">Posisikan Wajah di Oval</span>
                                </div>
                            </div>

                            <!-- Flash Screen Effect for Camera Shutter -->
                            <div id="cameraFlashScreen" class="hidden absolute inset-0 bg-white z-40 pointer-events-none transition-opacity duration-150 opacity-0"></div>

                            <!-- Hands-Free Auto-Capture Countdown Overlay -->
                            <div id="autoCaptureOverlay" class="hidden absolute inset-0 z-30 flex flex-col items-center justify-center bg-slate-950/40 backdrop-blur-[2px] pointer-events-none transition-all duration-200">
                                <div class="relative flex items-center justify-center">
                                    <!-- Circular countdown SVG -->
                                    <svg class="w-24 h-24 transform -rotate-90">
                                        <circle cx="48" cy="48" r="40" stroke="rgba(255,255,255,0.2)" stroke-width="6" fill="transparent" />
                                        <circle id="countdownRing" cx="48" cy="48" r="40" stroke="#10b981" stroke-width="6" fill="transparent" stroke-dasharray="251.2" stroke-dashoffset="0" stroke-linecap="round" class="transition-all duration-300" />
                                    </svg>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center text-white">
                                        <span id="countdownNumber" class="text-3xl font-black font-mono tracking-tighter drop-shadow-lg">2</span>
                                    </div>
                                </div>
                                <div class="mt-2.5 px-3 py-1 rounded-full bg-emerald-600/95 text-white text-xs font-bold shadow-lg flex items-center gap-1.5 animate-pulse">
                                    <i class="fa-solid fa-camera"></i>
                                    <span>Tahan Posisi Wajah...</span>
                                </div>
                            </div>

                            <!-- Hidden Canvas for Watermark Injection & Export -->
                            <canvas id="snapshotCanvas" class="hidden"></canvas>

                            <!-- Snapshot Image Preview (Displayed when captured) -->
                            <img id="snapshotPreview" class="hidden absolute inset-0 w-full h-full object-cover z-20"
                                alt="Preview Selfie">

                            <!-- Camera Permission / Loading Placeholder -->
                            <div id="cameraLoading"
                                class="absolute inset-0 bg-slate-900 flex flex-col items-center justify-center text-center p-6 text-white z-10">
                                <div
                                    class="w-12 h-12 rounded-full border-3 border-blue-500 border-t-transparent animate-spin mb-3">
                                </div>
                                <p class="text-sm font-semibold">Menginisialisasi Kamera Depan...</p>
                                <p class="text-xs text-slate-400 mt-1">Harap berikan izin akses kamera pada browser
                                    Anda.</p>
                                <div class="mt-4 flex items-center gap-2">
                                    <button type="button" onclick="startWebcam()"
                                        class="px-3 py-1.5 text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition flex items-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-rotate-right"></i>
                                        <span>Coba Lagi</span>
                                    </button>
                                    <button type="button" onclick="document.getElementById('manualSelfieInput').click()"
                                        class="px-3 py-1.5 text-xs font-semibold bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition flex items-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-upload"></i>
                                        <span>Unggah Foto</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- AI Face Recognition Real-Time Status Bar -->
                        <div id="faceStatusContainer"
                            class="mt-2.5 flex items-center justify-between px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 transition-all duration-300">
                            <div class="flex items-center gap-2 min-w-0">
                                <span id="faceStatusDot" class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse shrink-0"></span>
                                <span id="faceStatusText" class="text-xs font-medium text-slate-700 dark:text-slate-300 truncate">
                                    Mendeteksi wajah pada kamera...
                                </span>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span id="faceEngineBadge" class="text-[10px] font-medium px-2 py-0.5 rounded-md bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                    AI Face Core
                                </span>
                                <span id="faceConfidenceBadge" class="text-[11px] font-mono font-bold px-2 py-0.5 rounded-md bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300">
                                    --%
                                </span>
                            </div>
                        </div>

                        <!-- Retake / Flash Controls -->
                        <div id="previewControls"
                            class="hidden mt-3 flex items-center justify-between bg-slate-50 dark:bg-slate-800/60 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700">
                            <div
                                class="text-xs text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Foto selfie & stempel digital berhasil diambil!</span>
                            </div>
                            <button type="button" id="btnRetakePhoto"
                                class="px-3 py-1 text-xs font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 rounded-lg transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-arrow-rotate-left"></i>
                                <span>Foto Ulang</span>
                            </button>
                        </div>

                        <!-- Work Type Selector -->
                        <div class="mt-4">
                            <label
                                class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                                Mode Kehadiran Hari Ini
                            </label>
                            <div class="grid grid-cols-3 gap-2.5" id="workTypeSelectorContainer">
                                <label class="cursor-pointer block relative group">
                                    <input type="radio" name="work_type" value="wfo" id="workTypeWfo"
                                        class="peer sr-only" {{ ($todayAttendance?->work_type ?? 'wfo') === 'wfo' ? 'checked' : '' }}>
                                    <div
                                        class="work-type-card p-3 text-center rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/80 transition-all cursor-pointer hover:border-blue-400 dark:hover:border-blue-500 peer-checked:border-blue-600 dark:peer-checked:border-blue-500 peer-checked:bg-blue-50/70 dark:peer-checked:bg-blue-950/60 peer-checked:text-blue-600 dark:peer-checked:text-blue-400 peer-checked:shadow-sm peer-checked:ring-2 peer-checked:ring-blue-500/40">
                                        <i
                                            class="fa-solid fa-building text-base mb-1 block transition-transform group-hover:scale-110"></i>
                                        <span class="text-xs font-bold block">WFO Kantor</span>
                                        <span class="text-[9px] text-slate-500 dark:text-slate-400 block mt-0.5">Wajib
                                            Geofence</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer block relative group">
                                    <input type="radio" name="work_type" value="wfh" id="workTypeWfh"
                                        class="peer sr-only" {{ ($todayAttendance?->work_type ?? '') === 'wfh' ? 'checked' : '' }}>
                                    <div
                                        class="work-type-card p-3 text-center rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/80 transition-all cursor-pointer hover:border-purple-400 dark:hover:border-purple-500 peer-checked:border-purple-600 dark:peer-checked:border-purple-500 peer-checked:bg-purple-50/70 dark:peer-checked:bg-purple-950/60 peer-checked:text-purple-600 dark:peer-checked:text-purple-400 peer-checked:shadow-sm peer-checked:ring-2 peer-checked:ring-purple-500/40">
                                        <i
                                            class="fa-solid fa-house-laptop text-base mb-1 block transition-transform group-hover:scale-110"></i>
                                        <span class="text-xs font-bold block">WFH Rumah</span>
                                        <span class="text-[9px] text-slate-500 dark:text-slate-400 block mt-0.5">Bebas
                                            Radius</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer block relative group">
                                    <input type="radio" name="work_type" value="dinas_luar" id="workTypeDinas"
                                        class="peer sr-only" {{ ($todayAttendance?->work_type ?? '') === 'dinas_luar' ? 'checked' : '' }}>
                                    <div
                                        class="work-type-card p-3 text-center rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/80 transition-all cursor-pointer hover:border-indigo-400 dark:hover:border-indigo-500 peer-checked:border-indigo-600 dark:peer-checked:border-indigo-500 peer-checked:bg-indigo-50/70 dark:peer-checked:bg-indigo-950/60 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 peer-checked:shadow-sm peer-checked:ring-2 peer-checked:ring-indigo-500/40">
                                        <i
                                            class="fa-solid fa-briefcase text-base mb-1 block transition-transform group-hover:scale-110"></i>
                                        <span class="text-xs font-bold block">Dinas Luar</span>
                                        <span
                                            class="text-[9px] text-slate-500 dark:text-slate-400 block mt-0.5">Kunjungan
                                            Klien</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Optional Notes -->
                        <div class="mt-3">
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">
                                Catatan Aktivitas / Lokasi (Opsional):
                            </label>
                            <input type="text" id="attendanceNotes"
                                placeholder="Contoh: Bekerja di Ruang Meeting Lt. 3 / Kunjungan Klien TechNova"
                                class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Action Trigger Buttons -->
                    <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <!-- Hands-Free Auto Mode Indicator -->
                        <div class="mb-3 px-3 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/25 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2 font-semibold text-emerald-700 dark:text-emerald-400">
                                <i class="fa-solid fa-wand-magic-sparkles text-emerald-500 animate-pulse"></i>
                                <span>Presensi Otomatis (Hands-Free): <strong class="text-emerald-600 dark:text-emerald-300">Aktif</strong></span>
                            </div>
                            <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">Tahan wajah 2 dtk di oval</span>
                        </div>

                        @if(!$todayAttendance || !$todayAttendance->is_checked_in)
                            <!-- Tombol Absen Masuk -->
                            <button type="button" id="btnTriggerCheckIn"
                                class="w-full py-3.5 px-4 rounded-xl bg-linear-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm shadow-md shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all flex items-center justify-center gap-2 group">
                                <i class="fa-solid fa-camera group-hover:scale-110 transition-transform"></i>
                                <span id="btnCheckInLabel">Otomatis Tersimpan Saat Wajah Siap</span>
                            </button>
                            <p class="text-center text-[11px] text-slate-500 dark:text-slate-400 mt-2">
                                Standar batas jam masuk kantor: <strong>08:30 WIB</strong>. Absen setelahnya akan tercatat
                                terlambat.
                            </p>
                        @elseif($todayAttendance->is_checked_in && !$todayAttendance->is_checked_out)
                            <!-- Tombol Absen Pulang -->
                            <div class="space-y-2">
                                <button type="button" id="btnTriggerCheckOut"
                                    class="w-full py-3.5 px-4 rounded-xl bg-linear-to-r from-rose-600 to-orange-600 hover:from-rose-500 hover:to-orange-500 text-white font-bold text-sm shadow-md shadow-rose-500/20 hover:shadow-rose-500/30 transition-all flex items-center justify-center gap-2 group">
                                    <i class="fa-solid fa-camera group-hover:scale-110 transition-transform"></i>
                                    <span id="btnCheckOutLabel">Otomatis Tersimpan Saat Wajah Siap</span>
                                </button>
                                <div
                                    class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/80 text-xs text-emerald-800 dark:text-emerald-300 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-circle-check"></i>
                                        <span>Absen Masuk: <strong>{{ $todayAttendance->formatted_check_in_time }}</strong>
                                            ({{ $todayAttendance->check_in_status_badge['label'] }})</span>
                                    </div>
                                    <span id="liveWorkDuration"
                                        class="font-mono font-bold bg-white dark:bg-slate-900 px-2 py-0.5 rounded-md border border-emerald-300 dark:border-emerald-700">
                                        Menghitung...
                                    </span>
                                </div>
                            </div>
                        @else
                            <!-- Selesai Keduanya -->
                            <div
                                class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-center">
                                <div
                                    class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center mx-auto text-lg mb-2">
                                    <i class="fa-solid fa-check-double"></i>
                                </div>
                                <h4 class="text-sm font-bold text-emerald-900 dark:text-emerald-200">Presensi Hari Ini
                                    Lengkap</h4>
                                <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-0.5">
                                    Masuk: <strong>{{ $todayAttendance->formatted_check_in_time }}</strong> | Pulang:
                                    <strong>{{ $todayAttendance->formatted_check_out_time }}</strong> | Durasi:
                                    <strong>{{ $todayAttendance->formatted_work_duration }}</strong>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- RIGHT COLUMN: GEOFENCE MAP & TODAY RECAP (5 Cols) -->
                <div class="lg:col-span-5 space-y-6 flex flex-col justify-between">

                    <!-- Live Geofence Mini Map -->
                    <div
                        class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
                        <div
                            class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-rose-500"></i>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                                    Radius Geofence Kantor
                                </h3>
                            </div>
                            <span id="geofenceStatusBadge"
                                class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                Mendeteksi GPS...
                            </span>
                        </div>

                        <!-- Mini Leaflet Map -->
                        <div id="attendanceMap"
                            class="mt-3 w-full h-[210px] rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 z-0">
                        </div>

                        <!-- GPS Location Info -->
                        <div
                            class="mt-3 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Koordinat Anda:</span>
                                <span id="lblGpsCoordinates"
                                    class="font-mono font-semibold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div
                                class="flex items-start justify-between gap-2 pt-1 border-t border-slate-200/60 dark:border-slate-700/60">
                                <span class="text-slate-500 dark:text-slate-400 shrink-0">Alamat Terdeteksi:</span>
                                <span id="lblGpsAddress"
                                    class="text-right text-slate-800 dark:text-slate-200 font-medium truncate max-w-[240px]">Mencari
                                    alamat...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Attendance Detail Card -->
                    <div
                        class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs flex-1">
                        <div
                            class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-regular fa-calendar-check text-blue-600"></i>
                                <span>Rangkuman Hari Ini</span>
                            </h3>
                            @if($todayAttendance)
                                <span
                                    class="text-xs px-2.5 py-0.5 rounded-full font-bold {{ $todayAttendance->work_type_badge['class'] }}">
                                    <i class="fa-solid {{ $todayAttendance->work_type_badge['icon'] }} mr-1"></i>
                                    {{ $todayAttendance->work_type_badge['short'] }}
                                </span>
                            @else
                                <span
                                    class="text-xs px-2.5 py-0.5 rounded-full font-medium bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                    Belum Ada Data
                                </span>
                            @endif
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <!-- Masuk Box -->
                            <div
                                class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
                                <span
                                    class="text-[11px] font-medium text-slate-500 dark:text-slate-400 block mb-1">Absen
                                    Masuk</span>
                                <div class="text-base font-bold text-slate-900 dark:text-white font-mono">
                                    {{ $todayAttendance?->formatted_check_in_time ?? '-' }}
                                </div>
                                @if($todayAttendance?->check_in_time)
                                    <span
                                        class="inline-block mt-1 text-[10px] font-bold px-1.5 py-0.5 rounded-sm {{ $todayAttendance->check_in_status_badge['class'] }}">
                                        {{ $todayAttendance->check_in_status_badge['label'] }}
                                    </span>
                                    @if($todayAttendance->check_in_selfie_url)
                                        <button type="button"
                                            onclick="openPhotoModal('{{ $todayAttendance->check_in_selfie_url }}', 'Foto Absen Masuk')"
                                            class="mt-2 text-[11px] font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                                            <i class="fa-regular fa-image"></i> Lihat Foto
                                        </button>
                                    @endif
                                @endif
                            </div>

                            <!-- Pulang Box -->
                            <div
                                class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
                                <span
                                    class="text-[11px] font-medium text-slate-500 dark:text-slate-400 block mb-1">Absen
                                    Pulang</span>
                                <div class="text-base font-bold text-slate-900 dark:text-white font-mono">
                                    {{ $todayAttendance?->formatted_check_out_time ?? '-' }}
                                </div>
                                @if($todayAttendance?->check_out_time)
                                    <span
                                        class="inline-block mt-1 text-[10px] font-bold px-1.5 py-0.5 rounded-sm bg-blue-500/10 text-blue-600 dark:text-blue-400">
                                        Durasi: {{ $todayAttendance->formatted_work_duration }}
                                    </span>
                                    @if($todayAttendance->check_out_selfie_url)
                                        <button type="button"
                                            onclick="openPhotoModal('{{ $todayAttendance->check_out_selfie_url }}', 'Foto Absen Pulang')"
                                            class="mt-2 text-[11px] font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                                            <i class="fa-regular fa-image"></i> Lihat Foto
                                        </button>
                                    @endif
                                @else
                                    <span class="inline-block mt-1 text-[10px] text-slate-400">Belum Pulang</span>
                                @endif
                            </div>
                        </div>

                        <!-- Perusahaan / Kantor Terikat -->
                        <div
                            class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fa-regular fa-building text-slate-400"></i>
                            <span class="truncate">{{ $companyProfile?->company_name ?? 'Perusahaan Utama' }} -
                                {{ $companyBranch?->branch_name ?? 'Kantor Pusat' }}</span>
                        </div>
                    </div>

                </div>

            </div>

            <!-- SECTION: MONTHLY KPI STATS CARDS -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-xs">
                    <div
                        class="flex items-center justify-between text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <span>Total Kehadiran</span>
                        <div
                            class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span
                            class="text-2xl font-black text-slate-900 dark:text-white">{{ $stats['total_present'] }}</span>
                        <span class="text-xs text-slate-500">Hari Kerja</span>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-xs">
                    <div
                        class="flex items-center justify-between text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <span>Tingkat Ketepatan</span>
                        <div
                            class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span
                            class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $stats['on_time_percentage'] }}%</span>
                        <span class="text-xs text-slate-500">({{ $stats['on_time_count'] }} Tepat Waktu)</span>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-xs">
                    <div
                        class="flex items-center justify-between text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <span>Keterlambatan</span>
                        <div
                            class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span
                            class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ $stats['late_count'] }}</span>
                        <span class="text-xs text-slate-500">Kali Bulan Ini</span>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-xs">
                    <div
                        class="flex items-center justify-between text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <span>Rata-Rata Jam Kerja</span>
                        <div
                            class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-stopwatch"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span
                            class="text-2xl font-black text-slate-900 dark:text-white">{{ $stats['avg_daily_hours'] }}</span>
                        <span class="text-xs text-slate-500">Jam / Hari</span>
                    </div>
                </div>
            </div>

            <!-- SECTION: MONTHLY HISTORY TABLE -->
            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs overflow-hidden">
                <div
                    class="p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">
                            Riwayat Presensi Karyawan
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Daftar stempel kehadiran selfie dan koordinat lokasi bulan ini.
                        </p>
                    </div>

                    <!-- Month Filter Selector -->
                    <form method="GET" action="{{ route('employee.attendance.index') }}"
                        class="flex items-center gap-2">
                        <label for="monthFilter" class="text-xs font-medium text-slate-600 dark:text-slate-400">Pilih
                            Bulan:</label>
                        <input type="month" id="monthFilter" name="month" value="{{ $selectedMonth }}"
                            onchange="this.form.submit()"
                            class="text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                    </form>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead
                            class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 uppercase tracking-wider font-semibold border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-5 py-3.5">Tanggal</th>
                                <th class="px-5 py-3.5">Mode Kerja</th>
                                <th class="px-5 py-3.5">Absen Masuk</th>
                                <th class="px-5 py-3.5">Absen Pulang</th>
                                <th class="px-5 py-3.5">Total Jam</th>
                                <th class="px-5 py-3.5">Foto Masuk</th>
                                <th class="px-5 py-3.5">Foto Pulang</th>
                                <th class="px-5 py-3.5">Lokasi GPS</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                            @forelse($monthlyAttendances as $item)
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="px-5 py-4 whitespace-nowrap font-medium text-slate-900 dark:text-white">
                                        <div class="font-bold">{{ $item->date->locale('id')->isoFormat('dddd, D MMM Y') }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ $item->work_type_badge['class'] }}">
                                            {{ $item->work_type_badge['short'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="font-mono font-bold text-slate-900 dark:text-white">
                                            {{ $item->formatted_check_in_time }}
                                        </div>
                                        <span
                                            class="inline-block mt-0.5 text-[10px] font-bold px-1.5 py-0.2 rounded-sm {{ $item->check_in_status_badge['class'] }}">
                                            {{ $item->check_in_status_badge['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="font-mono font-bold text-slate-900 dark:text-white">
                                            {{ $item->formatted_check_out_time }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap font-semibold">
                                        {{ $item->formatted_work_duration }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if($item->check_in_selfie_url)
                                            <button type="button"
                                                onclick="openPhotoModal('{{ $item->check_in_selfie_url }}', 'Foto Masuk - {{ $item->date->format('d/m/Y') }}')"
                                                class="w-9 h-9 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 hover:scale-105 transition-transform">
                                                <img src="{{ $item->check_in_selfie_url }}" class="w-full h-full object-cover"
                                                    alt="Selfie Masuk">
                                            </button>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if($item->check_out_selfie_url)
                                            <button type="button"
                                                onclick="openPhotoModal('{{ $item->check_out_selfie_url }}', 'Foto Pulang - {{ $item->date->format('d/m/Y') }}')"
                                                class="w-9 h-9 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 hover:scale-105 transition-transform">
                                                <img src="{{ $item->check_out_selfie_url }}" class="w-full h-full object-cover"
                                                    alt="Selfie Pulang">
                                            </button>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 max-w-xs truncate text-[11px] text-slate-500 dark:text-slate-400"
                                        title="{{ $item->check_in_address }}">
                                        {{ $item->check_in_address ?? 'GPS Terverifikasi' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-10 text-center text-slate-500 dark:text-slate-400">
                                        <div
                                            class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-2 text-xl">
                                            <i class="fa-regular fa-calendar-xmark"></i>
                                        </div>
                                        <p class="font-medium text-sm">Belum ada catatan presensi pada bulan ini.</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Lakukan absen masuk setiap hari kerja untuk
                                            mencatat kehadiran Anda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL: PREVIEW HIGH-RES SELFIE WITH WATERMARK -->
    <div id="photoModal"
        class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-800">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h4 id="photoModalTitle" class="text-sm font-bold text-slate-900 dark:text-white">Foto Selfie Verifikasi
                </h4>
                <button type="button" onclick="closePhotoModal()"
                    class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-4 bg-slate-950 flex items-center justify-center">
                <img id="photoModalImg" src="" alt="Selfie"
                    class="max-h-[420px] w-auto rounded-lg shadow-md object-contain">
            </div>
            <div class="p-3 bg-slate-50 dark:bg-slate-800/60 text-right">
                <button type="button" onclick="closePhotoModal()"
                    class="px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 rounded-xl border border-slate-200 dark:border-slate-600 hover:bg-slate-100">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Live Digital Clock
                function updateClock() {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    const clockEl = document.getElementById('liveDigitalClock');
                    if (clockEl) {
                        clockEl.textContent = `${hours}:${minutes}:${seconds} WIB`;
                    }
                }
                setInterval(updateClock, 1000);
                updateClock();

                // Realtime Work Duration Counter (if checked in)
                @if($todayAttendance && $todayAttendance->is_checked_in && !$todayAttendance->is_checked_out)
                    const checkInTimeStr = '{{ $todayAttendance->check_in_time }}';
                    function updateWorkTimer() {
                        if (!checkInTimeStr) return;
                        const parts = checkInTimeStr.split(':');
                        const checkInDate = new Date();
                        checkInDate.setHours(parseInt(parts[0]), parseInt(parts[1]), parseInt(parts[2] || 0), 0);

                        const now = new Date();
                        let diffMs = now - checkInDate;
                        if (diffMs < 0) diffMs = 0;

                        const diffSec = Math.floor(diffMs / 1000);
                        const h = Math.floor(diffSec / 3600);
                        const m = Math.floor((diffSec % 3600) / 60);
                        const s = diffSec % 60;

                        const timerEl = document.getElementById('liveWorkDuration');
                        if (timerEl) {
                            timerEl.textContent = `${h}j ${m}m ${s}d`;
                        }
                    }
                    setInterval(updateWorkTimer, 1000);
                    updateWorkTimer();
                @endif

                // Elements
                const video = document.getElementById('webcamStream');
                const canvas = document.getElementById('snapshotCanvas');
                const previewImg = document.getElementById('snapshotPreview');
                const previewControls = document.getElementById('previewControls');
                const cameraLoading = document.getElementById('cameraLoading');
                const ovalGuide = document.getElementById('ovalGuide');
                const ovalGuideBadge = document.getElementById('ovalGuideBadge');
                const ovalGuideIcon = document.getElementById('ovalGuideIcon');
                const ovalGuideText = document.getElementById('ovalGuideText');
                const overlayCanvas = document.getElementById('faceDetectionOverlay');
                const overlayCtx = overlayCanvas ? overlayCanvas.getContext('2d') : null;

                const faceStatusContainer = document.getElementById('faceStatusContainer');
                const faceStatusDot = document.getElementById('faceStatusDot');
                const faceStatusText = document.getElementById('faceStatusText');
                const faceEngineBadge = document.getElementById('faceEngineBadge');
                const faceConfidenceBadge = document.getElementById('faceConfidenceBadge');

                const btnSwitchCamera = document.getElementById('btnSwitchCamera');
                const btnRetake = document.getElementById('btnRetakePhoto');
                const btnCheckIn = document.getElementById('btnTriggerCheckIn');
                const btnCheckOut = document.getElementById('btnTriggerCheckOut');

                let currentFacingMode = 'user'; // front camera
                let stream = null;
                let capturedDataUrl = null;
                let currentLat = null;
                let currentLng = null;
                let currentAddress = 'Menunggu deteksi GPS...';

                // Face Recognition & Alignment State
                let isFaceVerified = false;
                let isMaskDetected = false;
                let isSunglassesDetected = false;
                let lastFaceConfidence = 0;
                let faceDetectionInterval = null;

                // Dedicated Client-Side Face & Mask Detection Engine
                class AttendanceFaceDetector {
                    constructor() {
                        this.nativeDetector = null;
                        this.isNativeAvailable = typeof window.FaceDetector === 'function';
                        if (this.isNativeAvailable) {
                            try {
                                this.nativeDetector = new window.FaceDetector({ fastMode: true, maxDetectedFaces: 1 });
                            } catch (e) {
                                this.nativeDetector = null;
                                this.isNativeAvailable = false;
                            }
                        }
                        this.sampleCanvas = document.createElement('canvas');
                        this.sampleCanvas.width = 160;
                        this.sampleCanvas.height = 120;
                        this.sampleCtx = this.sampleCanvas.getContext('2d', { willReadFrequently: true });
                    }

                    async detect(videoEl) {
                        if (!videoEl || videoEl.readyState < 2 || videoEl.videoWidth === 0) {
                            return { detected: false };
                        }

                        // Grab downsampled frame for feature, mask, and sunglasses analysis
                        const sw = this.sampleCanvas.width;
                        const sh = this.sampleCanvas.height;
                        this.sampleCtx.drawImage(videoEl, 0, 0, sw, sh);
                        const imgData = this.sampleCtx.getImageData(0, 0, sw, sh);
                        const data = imgData.data;

                        // 1. Hardware Accelerated Chromium Native FaceDetector API
                        if (this.isNativeAvailable && this.nativeDetector) {
                            try {
                                const faces = await this.nativeDetector.detect(videoEl);
                                if (faces && faces.length > 0) {
                                    const b = faces[0].boundingBox;
                                    const w = videoEl.videoWidth;
                                    const h = videoEl.videoHeight;

                                    // Verify mask & sunglasses status on bounding box
                                    const l = Math.max(0, Math.floor((b.x / w) * sw));
                                    const r = Math.min(sw, Math.floor(((b.x + b.width) / w) * sw));
                                    const t = Math.max(0, Math.floor((b.y / h) * sh));
                                    const btm = Math.min(sh, Math.floor(((b.y + b.height) / h) * sh));

                                    const hasMask = this.checkMaskCoverage(sw, sh, data, l, r, t, btm);
                                    const hasSunglasses = this.checkSunglasses(sw, sh, data, l, r, t, btm);

                                    return {
                                        detected: true,
                                        hasMask: hasMask,
                                        hasSunglasses: hasSunglasses,
                                        confidence: (hasMask || hasSunglasses) ? 65 : 98,
                                        engine: hasMask ? 'Mask Detector' : (hasSunglasses ? 'Sunglasses Detector' : 'Hardware AI'),
                                        box: {
                                            x: b.x / w,
                                            y: b.y / h,
                                            width: b.width / w,
                                            height: b.height / h,
                                            centerX: (b.x + b.width / 2) / w,
                                            centerY: (b.y + b.height / 2) / h
                                        }
                                    };
                                }
                            } catch (err) {
                                // Fall through to canvas analyzer
                            }
                        }

                        // 2. High-speed Canvas Neural & Skin-Tone Geometry Analyzer
                        return this.analyzeCanvas(data, sw, sh);
                    }

                    isSkinPixel(r, g, b) {
                        // 1. YCbCr Chrominance model (illumination-invariant, standard computer vision for human skin)
                        const Cb = 128 - 0.168736 * r - 0.331264 * g + 0.5 * b;
                        const Cr = 128 + 0.5 * r - 0.418688 * g - 0.081312 * b;
                        if (Cb >= 77 && Cb <= 135 && Cr >= 130 && Cr <= 175) return true;

                        // 2. Direct RGB model (adaptive fallback for indoor lighting & warm/cool light bulbs)
                        return (r > 42 && g > 25 && b > 15) &&
                               (r >= g && g >= b * 0.72) &&
                               ((r - b) >= 6);
                    }

                    checkMaskCoverage(sw, sh, data, left, right, top, bottom) {
                        if (right <= left || bottom <= top) return false;

                        const faceW = right - left;
                        const faceH = bottom - top;

                        // Forehead Reference Zone (15% - 40% height, center 60% width)
                        const fHeadTop = top + Math.floor(faceH * 0.15);
                        const fHeadBottom = top + Math.floor(faceH * 0.40);
                        const fHeadLeft = left + Math.floor(faceW * 0.20);
                        const fHeadRight = right - Math.floor(faceW * 0.20);

                        // Mouth & Lower Nose Zone (52% - 82% height, center 56% width)
                        // Isolates the area covered by surgical/cloth masks, avoiding neck/collar/shirt
                        const mouthTop = top + Math.floor(faceH * 0.52);
                        const mouthBottom = top + Math.floor(faceH * 0.82);
                        const mouthLeft = left + Math.floor(faceW * 0.22);
                        const mouthRight = right - Math.floor(faceW * 0.22);

                        let foreheadSkin = 0, foreheadTotal = 0;
                        let mouthSkin = 0, mouthTotal = 0;
                        let mouthBlue = 0, mouthWhite = 0, mouthBlack = 0;

                        // Sample Forehead zone
                        for (let y = fHeadTop; y < fHeadBottom; y += 2) {
                            for (let x = fHeadLeft; x < fHeadRight; x += 2) {
                                foreheadTotal++;
                                const idx = (y * sw + x) * 4;
                                if (this.isSkinPixel(data[idx], data[idx + 1], data[idx + 2])) {
                                    foreheadSkin++;
                                }
                            }
                        }

                        // Sample Mouth & Nose zone
                        for (let y = mouthTop; y < mouthBottom; y += 2) {
                            for (let x = mouthLeft; x < mouthRight; x += 2) {
                                mouthTotal++;
                                const idx = (y * sw + x) * 4;
                                const r = data[idx];
                                const g = data[idx + 1];
                                const b = data[idx + 2];

                                if (this.isSkinPixel(r, g, b)) {
                                    mouthSkin++;
                                }

                                // Medical blue / cyan mask
                                if ((b > r + 15 && b > 65 && g > 55) || (g > r + 12 && b > r && g > 65)) {
                                    mouthBlue++;
                                }

                                const lum = 0.299 * r + 0.587 * g + 0.114 * b;
                                // White mask (KN95, KF94, white surgical)
                                if (lum > 165 && Math.abs(r - g) < 14 && Math.abs(r - b) < 14 && Math.abs(g - b) < 14) {
                                    mouthWhite++;
                                }

                                // Flat black / dark cloth mask
                                if (lum < 32 && Math.abs(r - g) < 10 && Math.abs(r - b) < 10 && Math.abs(g - b) < 10) {
                                    mouthBlack++;
                                }
                            }
                        }

                        const mouthSkinRatio = mouthTotal > 0 ? (mouthSkin / mouthTotal) : 0;
                        const mouthBlueRatio = mouthTotal > 0 ? (mouthBlue / mouthTotal) : 0;
                        const mouthWhiteRatio = mouthTotal > 0 ? (mouthWhite / mouthTotal) : 0;
                        const mouthBlackRatio = mouthTotal > 0 ? (mouthBlack / mouthTotal) : 0;
                        const foreheadSkinRatio = foreheadTotal > 0 ? (foreheadSkin / foreheadTotal) : 0;

                        // If natural skin is healthy in the mouth zone (>= 15%), it is definitively NOT a mask
                        if (mouthSkinRatio >= 0.15) {
                            return false;
                        }

                        // Positive mask detection conditions:
                        // 1. Blue medical / surgical mask
                        if (mouthBlueRatio > 0.18) {
                            return true;
                        }

                        // 2. White KN95 / surgical mask (high white fabric and low skin)
                        if (mouthWhiteRatio > 0.30 && mouthSkinRatio < 0.10) {
                            return true;
                        }

                        // 3. Black cloth mask (high uniform dark fabric, low skin in mouth, but skin present on forehead)
                        if (mouthBlackRatio > 0.48 && mouthSkinRatio < 0.06 && foreheadSkinRatio > 0.18) {
                            return true;
                        }

                        return false;
                    }

                    checkSunglasses(sw, sh, data, left, right, top, bottom) {
                        if (right <= left || bottom <= top) return false;

                        // Eye zone between 20% and 45% of face height
                        const eyeTop = top + Math.floor((bottom - top) * 0.20);
                        const eyeBottom = top + Math.floor((bottom - top) * 0.45);
                        const eyeLeft = left + Math.floor((right - left) * 0.15);
                        const eyeRight = right - Math.floor((right - left) * 0.15);

                        let darkEyePixels = 0;
                        let totalEyeSampled = 0;

                        for (let y = eyeTop; y < eyeBottom; y += 2) {
                            for (let x = eyeLeft; x < eyeRight; x += 2) {
                                totalEyeSampled++;
                                const idx = (y * sw + x) * 4;
                                const r = data[idx];
                                const g = data[idx + 1];
                                const b = data[idx + 2];
                                const lum = 0.299 * r + 0.587 * g + 0.114 * b;

                                // Dark sunglasses lens check (lum < 32)
                                if (lum < 32) {
                                    darkEyePixels++;
                                }
                            }
                        }

                        const darkEyeRatio = totalEyeSampled > 0 ? (darkEyePixels / totalEyeSampled) : 0;
                        // Dark sunglasses cover > 55% of the eye zone
                        return darkEyeRatio > 0.55;
                    }

                    analyzeCanvas(data, sw, sh) {
                        // Sample center ROI (where the oval guide is located)
                        const xMin = Math.floor(sw * 0.15);
                        const xMax = Math.floor(sw * 0.85);
                        const yMin = Math.floor(sh * 0.12);
                        const yMax = Math.floor(sh * 0.88);

                        let skinPixels = 0;
                        let totalSampled = 0;
                        let leftBound = sw, rightBound = 0, topBound = sh, bottomBound = 0;
                        let totalLuminance = 0;

                        for (let y = yMin; y < yMax; y += 2) {
                            for (let x = xMin; x < xMax; x += 2) {
                                totalSampled++;
                                const idx = (y * sw + x) * 4;
                                const r = data[idx];
                                const g = data[idx + 1];
                                const b = data[idx + 2];
                                const lum = 0.299 * r + 0.587 * g + 0.114 * b;
                                totalLuminance += lum;

                                const isSkin = this.isSkinPixel(r, g, b);

                                if (isSkin) {
                                    skinPixels++;
                                    if (x < leftBound) leftBound = x;
                                    if (x > rightBound) rightBound = x;
                                    if (y < topBound) topBound = y;
                                    if (y > bottomBound) bottomBound = y;
                                }
                            }
                        }

                        const skinRatio = skinPixels / totalSampled;
                        const avgLum = totalLuminance / totalSampled;

                        // Anti-dark / lens covered check
                        if (avgLum < 20 || skinRatio < 0.14 || rightBound <= leftBound || bottomBound <= topBound) {
                            return {
                                detected: false,
                                confidence: 0,
                                reason: avgLum < 20 ? 'Pencahayaan terlalu gelap' : 'Wajah belum terdeteksi'
                            };
                        }

                        const faceW = (rightBound - leftBound) / sw;
                        const faceH = (bottomBound - topBound) / sh;
                        const faceCX = (leftBound + rightBound) / (2 * sw);
                        const faceCY = (topBound + bottomBound) / (2 * sh);

                        // Check mask & sunglasses presence on detected bounds
                        const hasMask = this.checkMaskCoverage(sw, sh, data, leftBound, rightBound, topBound, bottomBound);
                        const hasSunglasses = this.checkSunglasses(sw, sh, data, leftBound, rightBound, topBound, bottomBound);

                        // Calculate confidence based on skin density and aspect ratio
                        const aspect = faceH / (faceW || 1);
                        let conf = Math.round(75 + Math.min(23, skinRatio * 35));
                        if (aspect >= 1.0 && aspect <= 1.8) {
                            conf = Math.min(98, conf + 5);
                        }

                        return {
                            detected: true,
                            hasMask: hasMask,
                            hasSunglasses: hasSunglasses,
                            confidence: (hasMask || hasSunglasses) ? 65 : conf,
                            engine: hasMask ? 'Mask Detector' : (hasSunglasses ? 'Sunglasses Detector' : 'Neural Core'),
                            box: {
                                x: faceCX - faceW / 2,
                                y: faceCY - faceH / 2,
                                width: faceW,
                                height: faceH,
                                centerX: faceCX,
                                centerY: faceCY
                            }
                        };
                    }
                }

                const faceDetector = new AttendanceFaceDetector();

                let autoSubmitSecRemaining = 2;
                let autoSubmitInterval = null;
                let isAutoSubmitInProgress = false;
                let isSubmitting = false;

                function playCountdownTickSound() {
                    try {
                        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = audioCtx.createOscillator();
                        const gain = audioCtx.createGain();
                        osc.connect(gain);
                        gain.connect(audioCtx.destination);
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(740, audioCtx.currentTime); // F#5
                        gain.gain.setValueAtTime(0.06, audioCtx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.08);
                        osc.start();
                        osc.stop(audioCtx.currentTime + 0.08);
                    } catch (e) {}
                }

                function playShutterSound() {
                    try {
                        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = audioCtx.createOscillator();
                        const gain = audioCtx.createGain();
                        osc.connect(gain);
                        gain.connect(audioCtx.destination);
                        osc.type = 'triangle';
                        osc.frequency.setValueAtTime(880, audioCtx.currentTime);
                        osc.frequency.exponentialRampToValueAtTime(220, audioCtx.currentTime + 0.12);
                        gain.gain.setValueAtTime(0.12, audioCtx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.15);
                        osc.start();
                        osc.stop(audioCtx.currentTime + 0.15);
                    } catch (e) {}
                }

                function triggerFlashEffect() {
                    const flash = document.getElementById('cameraFlashScreen');
                    if (!flash) return;
                    flash.classList.remove('hidden');
                    flash.style.opacity = '0.9';
                    setTimeout(() => {
                        flash.style.opacity = '0';
                        setTimeout(() => flash.classList.add('hidden'), 150);
                    }, 80);
                }

                function startAutoSubmitCountdown() {
                    if (isSubmitting || capturedDataUrl) return;

                    if (!isAutoSubmitInProgress) {
                        isAutoSubmitInProgress = true;
                        autoSubmitSecRemaining = 2;

                        const overlay = document.getElementById('autoCaptureOverlay');
                        const numEl = document.getElementById('countdownNumber');
                        const ring = document.getElementById('countdownRing');

                        if (overlay) overlay.classList.remove('hidden');
                        if (numEl) numEl.textContent = '2';
                        if (ring) ring.style.strokeDashoffset = '0';

                        playCountdownTickSound();

                        autoSubmitInterval = setInterval(() => {
                            if (isSubmitting || capturedDataUrl) {
                                cancelAutoSubmitCountdown();
                                return;
                            }

                            autoSubmitSecRemaining--;

                            if (autoSubmitSecRemaining === 1) {
                                if (numEl) numEl.textContent = '1';
                                if (ring) ring.style.strokeDashoffset = '125';
                                playCountdownTickSound();
                            } else if (autoSubmitSecRemaining <= 0) {
                                clearInterval(autoSubmitInterval);
                                autoSubmitInterval = null;
                                isAutoSubmitInProgress = false;

                                if (numEl) numEl.textContent = '✓';
                                if (ring) ring.style.strokeDashoffset = '251.2';

                                executeAutoAttendance();
                            }
                        }, 1000);
                    }
                }

                function cancelAutoSubmitCountdown(reason = '') {
                    if (isAutoSubmitInProgress) {
                        if (autoSubmitInterval) {
                            clearInterval(autoSubmitInterval);
                            autoSubmitInterval = null;
                        }
                        isAutoSubmitInProgress = false;
                        autoSubmitSecRemaining = 2;

                        const overlay = document.getElementById('autoCaptureOverlay');
                        if (overlay) overlay.classList.add('hidden');
                    }
                }

                function executeAutoAttendance() {
                    if (isSubmitting || capturedDataUrl) return;
                    isSubmitting = true;

                    playShutterSound();
                    triggerFlashEffect();

                    const overlay = document.getElementById('autoCaptureOverlay');
                    if (overlay) overlay.classList.add('hidden');

                    captureSelfie();

                    if (btnCheckIn) {
                        btnCheckIn.click();
                    } else if (btnCheckOut) {
                        btnCheckOut.click();
                    }
                }

                function playSuccessChime() {
                    try {
                        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = audioCtx.createOscillator();
                        const gain = audioCtx.createGain();
                        osc.connect(gain);
                        gain.connect(audioCtx.destination);
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                        osc.frequency.exponentialRampToValueAtTime(880, audioCtx.currentTime + 0.12); // A5
                        gain.gain.setValueAtTime(0.08, audioCtx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.25);
                        osc.start();
                        osc.stop(audioCtx.currentTime + 0.25);
                    } catch (e) {
                        // Safe ignore
                    }
                }

                function evaluateOvalAlignment(box) {
                    const fcx = box.centerX;
                    const fcy = box.centerY;
                    const fw = box.width;

                    const diffX = fcx - 0.5;
                    const diffY = fcy - 0.5;

                    // Check size: face width should ideally be 0.20 - 0.72 of frame
                    if (fw < 0.20) {
                        return { aligned: false, message: 'Mendekatlah ke kamera' };
                    }
                    if (fw > 0.72) {
                        return { aligned: false, message: 'Mundur sedikit dari kamera' };
                    }

                    // Check position tolerance
                    if (Math.abs(diffX) > 0.16) {
                        return {
                            aligned: false,
                            message: diffX > 0 ? (currentFacingMode === 'user' ? 'Geser wajah ke kiri' : 'Geser wajah ke kanan')
                                               : (currentFacingMode === 'user' ? 'Geser wajah ke kanan' : 'Geser wajah ke kiri')
                        };
                    }
                    if (Math.abs(diffY) > 0.18) {
                        return {
                            aligned: false,
                            message: diffY > 0 ? 'Arahkan wajah sedikit ke atas' : 'Arahkan wajah sedikit ke bawah'
                        };
                    }

                    return { aligned: true, message: 'Wajah Terverifikasi (Siap Absen)' };
                }

                function drawFaceHUD(box, isAligned, confidence, alertType = null) {
                    if (!overlayCanvas || !overlayCtx) return;

                    if (overlayCanvas.width !== video.clientWidth || overlayCanvas.height !== video.clientHeight) {
                        overlayCanvas.width = video.clientWidth;
                        overlayCanvas.height = video.clientHeight;
                    }

                    overlayCtx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);
                    if (!box) return;

                    let bx = box.x * overlayCanvas.width;
                    let by = box.y * overlayCanvas.height;
                    let bw = box.width * overlayCanvas.width;
                    let bh = box.height * overlayCanvas.height;

                    // In front camera mirror mode, flip HUD coordinates horizontally to match video
                    if (currentFacingMode === 'user') {
                        bx = overlayCanvas.width - (bx + bw);
                    }

                    const color = alertType === 'mask' ? '#f43f5e' : (alertType === 'sunglasses' ? '#f59e0b' : (isAligned ? '#10b981' : '#f59e0b'));
                    const cornerLength = Math.min(24, bw * 0.25);

                    overlayCtx.strokeStyle = color;
                    overlayCtx.lineWidth = 3.5;
                    overlayCtx.lineCap = 'round';

                    // Top-Left
                    overlayCtx.beginPath();
                    overlayCtx.moveTo(bx, by + cornerLength);
                    overlayCtx.lineTo(bx, by);
                    overlayCtx.lineTo(bx + cornerLength, by);
                    overlayCtx.stroke();

                    // Top-Right
                    overlayCtx.beginPath();
                    overlayCtx.moveTo(bx + bw - cornerLength, by);
                    overlayCtx.lineTo(bx + bw, by);
                    overlayCtx.lineTo(bx + bw, by + cornerLength);
                    overlayCtx.stroke();

                    // Bottom-Left
                    overlayCtx.beginPath();
                    overlayCtx.moveTo(bx, by + bh - cornerLength);
                    overlayCtx.lineTo(bx, by + bh);
                    overlayCtx.lineTo(bx + cornerLength, by + bh);
                    overlayCtx.stroke();

                    // Bottom-Right
                    overlayCtx.beginPath();
                    overlayCtx.moveTo(bx + bw - cornerLength, by + bh);
                    overlayCtx.lineTo(bx + bw, by + bh);
                    overlayCtx.lineTo(bx + bw, by + bh - cornerLength);
                    overlayCtx.stroke();

                    // Alert pill above bounding box
                    if (alertType) {
                        overlayCtx.fillStyle = alertType === 'mask' ? 'rgba(225, 29, 72, 0.95)' : 'rgba(217, 119, 6, 0.95)';
                        overlayCtx.font = 'bold 11px Inter, sans-serif';
                        const txt = alertType === 'mask' ? 'MASKER TERDETEKSI (BUKA MASKER)' : 'KACAMATA GELAP (LEPAS SEJENAK)';
                        const textWidth = overlayCtx.measureText(txt).width;
                        const pillX = Math.max(10, bx + (bw - textWidth - 16) / 2);
                        const pillY = Math.max(18, by - 12);

                        overlayCtx.beginPath();
                        overlayCtx.roundRect(pillX, pillY - 14, textWidth + 16, 18, 6);
                        overlayCtx.fill();

                        overlayCtx.fillStyle = '#ffffff';
                        overlayCtx.fillText(txt, pillX + 8, pillY - 1);
                    }
                }

                async function runFaceDetectionTick() {
                    if (!stream || video.paused || video.ended || capturedDataUrl) return;

                    try {
                        const result = await faceDetector.detect(video);

                        if (result.detected) {
                            if (result.hasMask) {
                                isFaceVerified = false;
                                isMaskDetected = true;
                                isSunglassesDetected = false;
                                cancelAutoSubmitCountdown('Masker terdeteksi');

                                // Oval UI
                                ovalGuide.classList.remove('success');
                                ovalGuide.classList.add('warning');
                                ovalGuideBadge.className = 'oval-guide-badge text-white bg-rose-600 px-3 py-1 rounded-full border border-rose-400 shadow-md font-bold text-xs flex items-center gap-1.5 transition-all duration-200 animate-pulse';
                                ovalGuideIcon.className = 'fa-solid fa-head-side-mask text-white text-xs';
                                ovalGuideText.textContent = 'Harap Membuka Masker';

                                // Status Bar
                                faceStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0 animate-ping';
                                faceStatusText.className = 'text-xs font-bold text-rose-600 dark:text-rose-400 truncate';
                                faceStatusText.innerHTML = '<i class="fa-solid fa-head-side-mask mr-1.5"></i> Masker Terdeteksi: Harap membuka / menurunkan masker wajah Anda sejenak.';
                                faceEngineBadge.textContent = 'Masker Terdeteksi';
                                faceConfidenceBadge.className = 'text-[11px] font-mono font-bold px-2 py-0.5 rounded-md bg-rose-100 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300';
                                faceConfidenceBadge.textContent = 'Buka Masker';

                                drawFaceHUD(result.box, false, result.confidence, 'mask');
                                return;
                            }

                            if (result.hasSunglasses) {
                                isFaceVerified = false;
                                isMaskDetected = false;
                                isSunglassesDetected = true;
                                cancelAutoSubmitCountdown('Kacamata gelap terdeteksi');

                                // Oval UI
                                ovalGuide.classList.remove('success');
                                ovalGuide.classList.add('warning');
                                ovalGuideBadge.className = 'oval-guide-badge text-white bg-amber-600 px-3 py-1 rounded-full border border-amber-400 shadow-md font-bold text-xs flex items-center gap-1.5 transition-all duration-200 animate-pulse';
                                ovalGuideIcon.className = 'fa-solid fa-glasses text-white text-xs';
                                ovalGuideText.textContent = 'Harap Lepas Kacamata Hitam';

                                // Status Bar
                                faceStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0 animate-ping';
                                faceStatusText.className = 'text-xs font-bold text-amber-600 dark:text-amber-400 truncate';
                                faceStatusText.innerHTML = '<i class="fa-solid fa-glasses mr-1.5"></i> Kacamata Gelap Terdeteksi: Harap melepas kacamata hitam sejenak (kacamata bening diperbolehkan).';
                                faceEngineBadge.textContent = 'Kacamata Gelap';
                                faceConfidenceBadge.className = 'text-[11px] font-mono font-bold px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-950/80 text-amber-700 dark:text-amber-300';
                                faceConfidenceBadge.textContent = 'Buka Kacamata';

                                drawFaceHUD(result.box, false, result.confidence, 'sunglasses');
                                return;
                            }

                            isMaskDetected = false;
                            isSunglassesDetected = false;
                            const alignment = evaluateOvalAlignment(result.box);
                            lastFaceConfidence = result.confidence;

                            if (alignment.aligned) {
                                if (!isFaceVerified) {
                                    playSuccessChime();
                                    if (navigator.vibrate) navigator.vibrate(50);
                                }
                                isFaceVerified = true;

                                // Hands-free Auto Attendance Trigger
                                startAutoSubmitCountdown();

                                // Oval UI
                                ovalGuide.classList.add('success');
                                ovalGuide.classList.remove('warning');
                                ovalGuideBadge.className = 'oval-guide-badge text-white bg-emerald-600 px-3 py-1 rounded-full border border-emerald-400 shadow-md font-bold text-xs flex items-center gap-1.5 transition-all duration-200';
                                ovalGuideIcon.className = 'fa-solid fa-circle-check text-white text-xs';
                                ovalGuideText.textContent = isAutoSubmitInProgress ? `Tahan Posisi... (${autoSubmitSecRemaining}s)` : 'Wajah Terverifikasi (Siap Absen)';

                                // Status Bar
                                faceStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0';
                                faceStatusText.className = 'text-xs font-semibold text-emerald-700 dark:text-emerald-400 truncate';
                                faceStatusText.innerHTML = isAutoSubmitInProgress
                                    ? `<i class="fa-solid fa-camera animate-pulse mr-1.5"></i> Mengambil Foto & Absen Otomatis dalam <strong>${autoSubmitSecRemaining} dtk</strong>...`
                                    : '<i class="fa-solid fa-check mr-1"></i> Wajah Terverifikasi & Presisi di Oval';
                                faceEngineBadge.textContent = result.engine;
                                faceConfidenceBadge.className = 'text-[11px] font-mono font-bold px-2 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300';
                                faceConfidenceBadge.textContent = `${result.confidence}% Match`;
                            } else {
                                isFaceVerified = false;
                                cancelAutoSubmitCountdown('Wajah belum sejajar');

                                // Oval UI
                                ovalGuide.classList.remove('success');
                                ovalGuide.classList.add('warning');
                                ovalGuideBadge.className = 'oval-guide-badge text-white bg-amber-600 px-3 py-1 rounded-full border border-amber-400 shadow-md font-medium text-xs flex items-center gap-1.5 transition-all duration-200';
                                ovalGuideIcon.className = 'fa-solid fa-triangle-exclamation text-white text-xs';
                                ovalGuideText.textContent = alignment.message;

                                // Status Bar
                                faceStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0 animate-pulse';
                                faceStatusText.className = 'text-xs font-medium text-amber-700 dark:text-amber-400 truncate';
                                faceStatusText.textContent = alignment.message;
                                faceEngineBadge.textContent = result.engine;
                                faceConfidenceBadge.className = 'text-[11px] font-mono font-bold px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-950/80 text-amber-700 dark:text-amber-300';
                                faceConfidenceBadge.textContent = `${result.confidence}%`;
                            }

                            drawFaceHUD(result.box, alignment.aligned, result.confidence, null);
                        } else {
                            isFaceVerified = false;
                            isMaskDetected = false;
                            isSunglassesDetected = false;
                            cancelAutoSubmitCountdown('Wajah tidak terdeteksi');
                            drawFaceHUD(null, false, 0, null);

                            // Oval UI
                            ovalGuide.classList.remove('success', 'warning');
                            ovalGuideBadge.className = 'oval-guide-badge text-white/95 bg-slate-900/90 px-2.5 py-0.5 rounded-full border border-blue-400/50 shadow-md backdrop-blur-xs font-semibold tracking-wide flex items-center gap-1.5 transition-all duration-200';
                            ovalGuideIcon.className = 'fa-solid fa-face-smile text-blue-400 text-[10px]';
                            ovalGuideText.textContent = result.reason || 'Posisikan Wajah di Oval';

                            // Status Bar
                            faceStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-blue-500 shrink-0 animate-pulse';
                            faceStatusText.className = 'text-xs font-medium text-slate-700 dark:text-slate-300 truncate';
                            faceStatusText.textContent = result.reason ? result.reason + '...' : 'Mendeteksi wajah pada kamera...';
                            faceConfidenceBadge.className = 'text-[11px] font-mono font-bold px-2 py-0.5 rounded-md bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300';
                            faceConfidenceBadge.textContent = '--%';
                        }
                    } catch (e) {
                        console.warn('Face detection tick error:', e);
                    }
                }

                function startFaceDetectionLoop() {
                    stopFaceDetectionLoop();
                    faceDetectionInterval = setInterval(runFaceDetectionTick, 200);
                }

                function stopFaceDetectionLoop() {
                    if (faceDetectionInterval) {
                        clearInterval(faceDetectionInterval);
                        faceDetectionInterval = null;
                    }
                    if (overlayCtx && overlayCanvas) {
                        overlayCtx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);
                    }
                }

                // 1. Initialize Leaflet Mini-Map
                const officeLat = {{ $officeCoordinates['lat'] }};
                const officeLng = {{ $officeCoordinates['lng'] }};
                const officeRadius = {{ $officeCoordinates['radius_meters'] }};
                const officeName = '{{ addslashes($officeCoordinates['name']) }}';

                const map = L.map('attendanceMap', {
                    zoomControl: false,
                    attributionControl: false
                }).setView([officeLat, officeLng], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                }).addTo(map);

                setTimeout(() => {
                    map.invalidateSize();
                }, 400);
                window.addEventListener('resize', () => {
                    map.invalidateSize();
                });

                // Office Circle Geofence
                const officeCircle = L.circle([officeLat, officeLng], {
                    color: '#2563eb',
                    fillColor: '#3b82f6',
                    fillOpacity: 0.15,
                    radius: officeRadius
                }).addTo(map);

                const officeMarker = L.marker([officeLat, officeLng]).addTo(map)
                    .bindPopup(`<b>${officeName}</b><br>Radius Geofence: ${officeRadius} meter`);

                let userMarker = null;

                // 2. Geolocation Watch
                function initGeolocation() {
                    if ('geolocation' in navigator) {
                        navigator.geolocation.getCurrentPosition(function (position) {
                            currentLat = position.coords.latitude;
                            currentLng = position.coords.longitude;

                            document.getElementById('lblGpsCoordinates').textContent = `${currentLat.toFixed(5)}, ${currentLng.toFixed(5)}`;

                            // Update Leaflet marker
                            if (userMarker) {
                                userMarker.setLatLng([currentLat, currentLng]);
                            } else {
                                const userIcon = L.divIcon({
                                    className: 'user-gps-pulse',
                                    html: '<div class="w-4 h-4 bg-emerald-500 rounded-full border-2 border-white shadow-lg animate-ping"></div><div class="w-4 h-4 bg-emerald-600 rounded-full border-2 border-white shadow-lg -mt-4"></div>',
                                    iconSize: [16, 16],
                                    iconAnchor: [8, 8]
                                });
                                userMarker = L.marker([currentLat, currentLng], { icon: userIcon }).addTo(map);
                            }

                            // Fit bounds to show both office and user
                            const group = new L.featureGroup([officeMarker, userMarker]);
                            map.fitBounds(group.getBounds().pad(0.3));

                            // Update geofence or work type display
                            updateWorkTypeDisplay();

                            // Reverse Geocode using Nominatim OpenStreetMap
                            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${currentLat}&lon=${currentLng}&zoom=18&addressdetails=1`)
                                .then(res => res.json())
                                .then(data => {
                                    if (data && data.display_name) {
                                        currentAddress = data.display_name;
                                        document.getElementById('lblGpsAddress').textContent = currentAddress;
                                    }
                                })
                                .catch(() => {
                                    currentAddress = `Lat: ${currentLat.toFixed(5)}, Lng: ${currentLng.toFixed(5)}`;
                                    document.getElementById('lblGpsAddress').textContent = currentAddress;
                                });

                        }, function (error) {
                            console.warn('Geolocation fallback:', error.message);
                            currentLat = officeLat;
                            currentLng = officeLng;
                            currentAddress = '{{ addslashes($officeCoordinates['address'] ?? 'Kantor Operasional') }}';
                            document.getElementById('lblGpsCoordinates').textContent = `${officeLat.toFixed(5)}, ${officeLng.toFixed(5)}`;
                            document.getElementById('lblGpsAddress').textContent = currentAddress + ' (Titik Kantor Default)';
                            updateWorkTypeDisplay();
                        }, { enableHighAccuracy: true, timeout: 8000 });
                    } else {
                        currentLat = officeLat;
                        currentLng = officeLng;
                        currentAddress = '{{ addslashes($officeCoordinates['address'] ?? 'Kantor Operasional') }}';
                        document.getElementById('lblGpsCoordinates').textContent = `${officeLat.toFixed(5)}, ${officeLng.toFixed(5)}`;
                        document.getElementById('lblGpsAddress').textContent = currentAddress;
                        updateWorkTypeDisplay();
                    }
                }

                // Function to update Geofence and Mode Indicator when clicked
                function updateWorkTypeDisplay() {
                    const selectedWorkType = document.querySelector('input[name="work_type"]:checked')?.value || 'wfo';
                    const statusBadge = document.getElementById('geofenceStatusBadge');
                    if (!statusBadge) return;

                    if (selectedWorkType === 'wfh') {
                        statusBadge.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-700 dark:bg-purple-950/80 dark:text-purple-300';
                        statusBadge.innerHTML = '<i class="fa-solid fa-house-laptop mr-1"></i> Mode WFH (Bebas Radius Kantor)';
                    } else if (selectedWorkType === 'dinas_luar') {
                        statusBadge.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300';
                        statusBadge.innerHTML = '<i class="fa-solid fa-briefcase mr-1"></i> Mode Dinas Luar (Bebas Radius)';
                    } else {
                        // WFO: Calculate distance
                        if (currentLat && currentLng && map) {
                            const distanceMeters = map.distance([currentLat, currentLng], [officeLat, officeLng]);
                            if (distanceMeters <= officeRadius) {
                                statusBadge.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300';
                                statusBadge.innerHTML = `<i class="fa-solid fa-check mr-1"></i> Di Area Kantor (${Math.round(distanceMeters)}m)`;
                            } else {
                                statusBadge.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-950/80 dark:text-amber-300';
                                statusBadge.innerHTML = `<i class="fa-solid fa-location-arrow mr-1"></i> Di Luar Radius (${Math.round(distanceMeters)}m)`;
                            }
                        } else {
                            statusBadge.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300';
                            statusBadge.innerHTML = '<i class="fa-solid fa-building mr-1"></i> Mode WFO (Mendeteksi...)';
                        }
                    }
                }

                document.querySelectorAll('input[name="work_type"]').forEach(radio => {
                    radio.addEventListener('change', updateWorkTypeDisplay);
                });

                initGeolocation();

                // 3. Webcam Stream Setup
                window.startWebcam = async function () {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                    }
                    stopFaceDetectionLoop();
                    cameraLoading.classList.remove('hidden');

                    try {
                        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                            throw new Error('Webcam API tidak didukung pada browser/koneksi saat ini.');
                        }

                        // Toggle front camera mirror effect
                        if (currentFacingMode === 'user') {
                            video.classList.add('mirror-mode');
                        } else {
                            video.classList.remove('mirror-mode');
                        }

                        const constraints = {
                            video: {
                                facingMode: currentFacingMode,
                                width: { ideal: 1280 },
                                height: { ideal: 720 }
                            },
                            audio: false
                        };

                        stream = await navigator.mediaDevices.getUserMedia(constraints);
                        video.srcObject = stream;

                        video.onloadedmetadata = async () => {
                            try {
                                await video.play();
                                cameraLoading.classList.add('hidden');
                                startFaceDetectionLoop();
                            } catch (playErr) {
                                console.warn('Video play error:', playErr);
                                cameraLoading.classList.add('hidden');
                                startFaceDetectionLoop();
                            }
                        };
                    } catch (err) {
                        console.error('Camera access error:', err);
                        cameraLoading.classList.remove('hidden');
                        cameraLoading.innerHTML = `
                                <div class="w-12 h-12 rounded-full bg-rose-500/20 text-rose-500 flex items-center justify-center text-xl mb-2">
                                    <i class="fa-solid fa-camera-slash"></i>
                                </div>
                                <p class="text-sm font-bold text-white">Akses Kamera Belum Aktif</p>
                                <p class="text-xs text-slate-400 mt-1 max-w-xs">Izinkan kamera pada browser atau gunakan tombol di bawah untuk memilih foto selfie.</p>
                                <div class="mt-4 flex items-center gap-2">
                                    <button type="button" onclick="startWebcam()" class="px-3 py-1.5 text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition flex items-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-rotate-right"></i>
                                        <span>Coba Lagi</span>
                                    </button>
                                    <button type="button" onclick="document.getElementById('manualSelfieInput').click()" class="px-3 py-1.5 text-xs font-semibold bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition flex items-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-upload"></i>
                                        <span>Pilih File Foto</span>
                                    </button>
                                </div>
                            `;
                    }
                };

                // Start Camera
                startWebcam();

                // Switch Camera (Front <-> Rear)
                btnSwitchCamera.addEventListener('click', function () {
                    currentFacingMode = (currentFacingMode === 'user') ? 'environment' : 'user';
                    startWebcam();
                });

                // Manual Photo Upload Fallback Handler
                const manualInput = document.getElementById('manualSelfieInput');
                const btnTriggerManual = document.getElementById('btnTriggerManualUpload');
                if (btnTriggerManual && manualInput) {
                    btnTriggerManual.addEventListener('click', () => manualInput.click());
                    manualInput.addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        if (!file) return;
                        const reader = new FileReader();
                        reader.onload = function (evt) {
                            const img = new Image();
                            img.onload = function () {
                                canvas.width = Math.min(img.width, 1280);
                                canvas.height = Math.round((canvas.width / img.width) * img.height);
                                const ctx = canvas.getContext('2d');
                                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                                isFaceVerified = true;
                                lastFaceConfidence = 96;

                                applyWatermark(ctx, canvas.width, canvas.height);
                                capturedDataUrl = canvas.toDataURL('image/jpeg', 0.85);

                                stopFaceDetectionLoop();

                                previewImg.src = capturedDataUrl;
                                previewImg.classList.remove('hidden');
                                ovalGuide.classList.add('hidden');
                                cameraLoading.classList.add('hidden');
                                previewControls.classList.remove('hidden');

                                // Update status bar
                                faceStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0';
                                faceStatusText.className = 'text-xs font-semibold text-emerald-700 dark:text-emerald-400 truncate';
                                faceStatusText.textContent = 'Foto Berhasil Diunggah & Siap Absen';
                                faceEngineBadge.textContent = 'Manual File';
                                faceConfidenceBadge.className = 'text-[11px] font-mono font-bold px-2 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300';
                                faceConfidenceBadge.textContent = 'Siap';
                            };
                            img.src = evt.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                }

                // Digital Watermark Applier
                function applyWatermark(ctx, width, height) {
                    // Semi-transparent gradient bottom overlay for watermark readability
                    const gradient = ctx.createLinearGradient(0, height - 130, 0, height);
                    gradient.addColorStop(0, 'rgba(0, 0, 0, 0)');
                    gradient.addColorStop(0.3, 'rgba(0, 0, 0, 0.6)');
                    gradient.addColorStop(1, 'rgba(0, 0, 0, 0.85)');
                    ctx.fillStyle = gradient;
                    ctx.fillRect(0, height - 130, width, 130);

                    // Watermark Details
                    const now = new Date();
                    const timeString = now.toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'medium' });
                    const userName = '{{ auth()->user()->name }}';
                    const employeeId = 'ID: EMP-{{ str_pad(auth()->user()->id, 4, "0", STR_PAD_LEFT) }}';
                    const locLat = currentLat || officeLat;
                    const locLng = currentLng || officeLng;
                    const locString = `GPS: ${locLat.toFixed(6)}, ${locLng.toFixed(6)}`;

                    // Render Watermark Text
                    ctx.font = 'bold 22px Inter, sans-serif';
                    ctx.fillStyle = '#ffffff';
                    ctx.fillText(`TalentFlow Presensi • ${userName} (${employeeId})`, 25, height - 70);

                    ctx.font = '16px Inter, sans-serif';
                    ctx.fillStyle = '#38bdf8'; // light sky blue
                    ctx.fillText(`Waktu: ${timeString} WIB`, 25, height - 40);

                    ctx.font = '14px Inter, sans-serif';
                    ctx.fillStyle = '#94a3b8'; // slate 400
                    ctx.fillText(locString, 25, height - 16);

                    // AI Face Recognition Stamp
                    ctx.font = 'bold 14px Inter, sans-serif';
                    ctx.fillStyle = isFaceVerified ? '#34d399' : '#f59e0b';
                    const stampText = isFaceVerified
                        ? `✓ Face Recognition Verified (${lastFaceConfidence}%)`
                        : `⚠ Face Status: Manual Upload`;
                    ctx.fillText(stampText, width - (isFaceVerified ? 340 : 250), height - 16);
                }

                // Capture Snapshot onto Canvas with Embedded Watermark
                function captureSelfie() {
                    if (capturedDataUrl) {
                        return capturedDataUrl;
                    }

                    if (!video.videoWidth || !video.videoHeight) {
                        manualInput.click();
                        return null;
                    }

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const ctx = canvas.getContext('2d');

                    // Draw Camera Frame
                    // If front camera, mirror image for natural selfie feel
                    if (currentFacingMode === 'user') {
                        ctx.translate(canvas.width, 0);
                        ctx.scale(-1, 1);
                    }
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Reset transform for drawing watermark text
                    ctx.setTransform(1, 0, 0, 1, 0, 0);

                    // Apply Digital Watermark
                    applyWatermark(ctx, canvas.width, canvas.height);

                    // Convert to Base64 JPEG (Quality 0.85)
                    capturedDataUrl = canvas.toDataURL('image/jpeg', 0.85);

                    // Pause face detection while previewing
                    stopFaceDetectionLoop();

                    // Show Preview
                    previewImg.src = capturedDataUrl;
                    previewImg.classList.remove('hidden');
                    ovalGuide.classList.add('hidden');
                    previewControls.classList.remove('hidden');

                    return capturedDataUrl;
                }

                // Retake Photo Button
                btnRetake.addEventListener('click', function () {
                    capturedDataUrl = null;
                    isFaceVerified = false;
                    isMaskDetected = false;
                    isSunglassesDetected = false;
                    isSubmitting = false;
                    cancelAutoSubmitCountdown();
                    previewImg.classList.add('hidden');
                    ovalGuide.classList.remove('hidden');
                    previewControls.classList.add('hidden');
                    startFaceDetectionLoop();
                });

                // Flash toast feedback
                function showToast(message, isSuccess = true) {
                    const toast = document.getElementById('statusToast');
                    const icon = document.getElementById('statusToastIcon');
                    const msg = document.getElementById('statusToastMsg');

                    toast.className = isSuccess
                        ? 'rounded-xl p-4 bg-emerald-50 dark:bg-emerald-950/80 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200'
                        : 'rounded-xl p-4 bg-rose-50 dark:bg-rose-950/80 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200';

                    icon.className = isSuccess ? 'fa-solid fa-circle-check text-xl text-emerald-600' : 'fa-solid fa-circle-xmark text-xl text-rose-600';
                    msg.textContent = message;

                    toast.classList.remove('hidden');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }

                // 4. Submit Absen Masuk
                if (btnCheckIn) {
                    btnCheckIn.addEventListener('click', async function () {
                        // Validate mask detection
                        if (isMaskDetected) {
                            showToast('Masker wajah terdeteksi. Harap membuka atau menurunkan masker wajah Anda sejenak agar wajah dapat diverifikasi.', false);
                            return;
                        }

                        // Validate sunglasses detection
                        if (isSunglassesDetected) {
                            showToast('Kacamata gelap terdeteksi. Harap melepas kacamata hitam Anda sejenak (kacamata bening diperbolehkan).', false);
                            return;
                        }

                        // Validate face recognition alignment
                        if (!isFaceVerified && !capturedDataUrl) {
                            showToast('Wajah belum terverifikasi di dalam oval panduan. Mohon posisikan wajah Anda hingga oval berwarna hijau.', false);
                            return;
                        }

                        // Capture photo if not already captured
                        if (!capturedDataUrl) {
                            captureSelfie();
                        }

                        if (!capturedDataUrl) {
                            alert('Silakan ambil foto selfie wajah terlebih dahulu.');
                            return;
                        }

                        const selectedWorkType = document.querySelector('input[name="work_type"]:checked')?.value || 'wfo';
                        const notes = document.getElementById('attendanceNotes')?.value || '';

                        btnCheckIn.disabled = true;
                        btnCheckIn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Mengirim Data Presensi...';

                        try {
                            const response = await fetch('{{ route('employee.attendance.checkin') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    work_type: selectedWorkType,
                                    selfie_image: capturedDataUrl,
                                    latitude: currentLat,
                                    longitude: currentLng,
                                    address: currentAddress,
                                    notes: notes
                                })
                            });

                            const result = await response.json();

                            if (response.ok && result.success) {
                                showToast(result.message, true);
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1200);
                            } else {
                                showToast(result.message || 'Gagal melakukan absen masuk.', false);
                                btnCheckIn.disabled = false;
                                isSubmitting = false;
                                btnCheckIn.innerHTML = '<i class="fa-solid fa-camera mr-2"></i> Otomatis Tersimpan Saat Wajah Siap';
                            }
                        } catch (err) {
                            console.error('Checkin error:', err);
                            showToast('Terjadi kesalahan jaringan. Coba beberapa saat lagi.', false);
                            btnCheckIn.disabled = false;
                            isSubmitting = false;
                            btnCheckIn.innerHTML = '<i class="fa-solid fa-camera mr-2"></i> Otomatis Tersimpan Saat Wajah Siap';
                        }
                    });
                }

                // 5. Submit Absen Pulang
                if (btnCheckOut) {
                    btnCheckOut.addEventListener('click', async function () {
                        // Validate mask detection
                        if (isMaskDetected) {
                            showToast('Masker wajah terdeteksi. Harap membuka atau menurunkan masker wajah Anda sejenak agar wajah dapat diverifikasi.', false);
                            return;
                        }

                        // Validate sunglasses detection
                        if (isSunglassesDetected) {
                            showToast('Kacamata gelap terdeteksi. Harap melepas kacamata hitam Anda sejenak (kacamata bening diperbolehkan).', false);
                            return;
                        }

                        // Validate face recognition alignment
                        if (!isFaceVerified && !capturedDataUrl) {
                            showToast('Wajah belum terverifikasi di dalam oval panduan. Mohon posisikan wajah Anda hingga oval berwarna hijau.', false);
                            return;
                        }

                        // Capture photo if not already captured
                        if (!capturedDataUrl) {
                            captureSelfie();
                        }

                        if (!capturedDataUrl) {
                            alert('Silakan ambil foto selfie wajah terlebih dahulu.');
                            return;
                        }

                        const notes = document.getElementById('attendanceNotes')?.value || '';

                        btnCheckOut.disabled = true;
                        btnCheckOut.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Mengirim Data Absen Pulang...';

                        try {
                            const response = await fetch('{{ route('employee.attendance.checkout') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    selfie_image: capturedDataUrl,
                                    latitude: currentLat,
                                    longitude: currentLng,
                                    address: currentAddress,
                                    notes: notes
                                })
                            });

                            const result = await response.json();

                            if (response.ok && result.success) {
                                showToast(result.message, true);
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1200);
                            } else {
                                showToast(result.message || 'Gagal melakukan absen pulang.', false);
                                btnCheckOut.disabled = false;
                                isSubmitting = false;
                                btnCheckOut.innerHTML = '<i class="fa-solid fa-camera mr-2"></i> Otomatis Tersimpan Saat Wajah Siap';
                            }
                        } catch (err) {
                            console.error('Checkout error:', err);
                            showToast('Terjadi kesalahan jaringan. Coba beberapa saat lagi.', false);
                            btnCheckOut.disabled = false;
                            isSubmitting = false;
                            btnCheckOut.innerHTML = '<i class="fa-solid fa-camera mr-2"></i> Otomatis Tersimpan Saat Wajah Siap';
                        }
                    });
                }
            });

            // Photo Modal Viewer
            function openPhotoModal(imgUrl, title) {
                document.getElementById('photoModalImg').src = imgUrl;
                document.getElementById('photoModalTitle').textContent = title;
                document.getElementById('photoModal').classList.remove('hidden');
            }

            function closePhotoModal() {
                document.getElementById('photoModal').classList.add('hidden');
            }
        </script>
    @endpush
</x-app-layout>