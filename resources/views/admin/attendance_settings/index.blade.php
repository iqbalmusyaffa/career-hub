<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Admin</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">Pengaturan</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-blue-600 dark:text-blue-400 font-semibold">Kebijakan Presensi & GPS</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fa-solid fa-satellite-dish"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                                Pengaturan Presensi & GPS Global
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                                Konfigurasi kebijakan cut-off harian, parameter anti-spoofing sensor GPS, dan ambang kelulusan magang.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/60 rounded-xl text-xs text-emerald-800 dark:text-emerald-200 font-medium flex items-center gap-2.5 shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.attendance-settings.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Section 1: Cut-Off Time & Anti-Rapel Policy -->
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs p-6 space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-700/80 pb-4">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200/80 dark:border-amber-800/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm font-semibold">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Batas Waktu Harian & Kebijakan Anti-Rapel</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Tentukan jam batas akhir pengisian presensi harian peserta magang secara global.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Jam Batas Akhir Presensi Harian (Cut-Off WIB)
                            </label>
                            <input type="time" name="cutoff_time" value="{{ old('cutoff_time', $settings['cutoff_time']) }}" required class="w-full text-xs font-medium rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-900 dark:text-slate-100 p-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Default <code class="px-1 py-0.5 rounded bg-slate-100 dark:bg-slate-800 font-mono text-[10px]">23:59</code> WIB. Setelah jam ini, tanggal otomatis terkunci dan dihitung alpa jika tidak diisi.</p>
                        </div>

                        <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700/80 space-y-1.5">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-800 dark:text-slate-200">
                                <i class="fa-solid fa-building-user text-blue-600 dark:text-blue-400"></i>
                                <span>Otonomi Jadwal Kerja Mitra (5 vs 6 Hari)</span>
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                                Pengaturan hari kerja (apakah anak magang masuk hari Sabtu/Minggu) dikonfigurasi secara mandiri oleh masing-masing perusahaan mitra melalui menu <strong>Profil & Data Perusahaan</strong> mereka.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Sensor GPS & Anti-Spoofing -->
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs p-6 space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-700/80 pb-4">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200/80 dark:border-rose-800/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-sm font-semibold">
                            <i class="fa-solid fa-location-crosshairs"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Sensor GPS & Proteksi Anti-Fake GPS</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Parameter akurasi toleransi satelit dan proteksi manipulasi lokasi tiruan (Mock Location).</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700/80">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="anti_fake_gps" value="1" {{ $settings['anti_fake_gps'] ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-slate-900 dark:text-slate-100">Aktifkan Deteksi Ketat Mock Location & Anti-Spoofing</span>
                                        <span class="inline-flex items-center px-2 py-0.2 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/80 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">Rekomendasi</span>
                                    </div>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed block mt-1">
                                        Mendeteksi otomatis akurasi sintetis (0m/1m), anomali timestamp satelit, dan manipulasi browser driver. Submit presensi akan dicegah ketika terdeteksi.
                                    </span>
                                </div>
                            </label>
                        </div>

                        <div class="max-w-md">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Toleransi Maksimum Akurasi GPS Satelit (Meter)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-mono text-xs">±</span>
                                <input type="number" name="max_gps_accuracy" min="10" max="1000" value="{{ old('max_gps_accuracy', $settings['max_gps_accuracy']) }}" required class="w-full text-xs font-medium rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-900 dark:text-slate-100 p-2.5 pl-8 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Default <code class="px-1 py-0.5 rounded bg-slate-100 dark:bg-slate-800 font-mono text-[10px]">100</code> meter. Akurasi di atas batas ini akan diminta refresh GPS.</p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Graduation Standards -->
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs p-6 space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-700/80 pb-4">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/80 dark:border-emerald-800/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm font-semibold">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Standar Kelulusan & Jam Magang</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Parameter kelulusan pada transkrip nilai akademik dan evaluasi akhir peserta magang.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Target Akumulasi Jam Kerja Default
                            </label>
                            <input type="number" name="target_hours" min="50" max="2000" value="{{ old('target_hours', $settings['target_hours']) }}" required class="w-full text-xs font-medium rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-900 dark:text-slate-100 p-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Default <code class="px-1 py-0.5 rounded bg-slate-100 dark:bg-slate-800 font-mono text-[10px]">400</code> Jam (Setara ~50 hari kerja 8 jam/hari).</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Persentase Minimal Kehadiran Kelulusan (%)
                            </label>
                            <input type="number" name="min_percentage" min="50" max="100" value="{{ old('min_percentage', $settings['min_percentage']) }}" required class="w-full text-xs font-medium rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-900 dark:text-slate-100 p-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Default <code class="px-1 py-0.5 rounded bg-slate-100 dark:bg-slate-800 font-mono text-[10px]">80</code> %. Peserta di bawah ambang ini ditandai evaluasi khusus.</p>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Automated Reminders & Notification Master Switch -->
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs p-6 space-y-5" x-data="{ masterEnabled: {{ $settings['reminder_master_enabled'] ? 'true' : 'false' }} }">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/80 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200/80 dark:border-indigo-800/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm font-semibold">
                                <i class="fa-solid fa-bell"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Otomatisasi Notifikasi & Pengingat Kehadiran (Master Switch)</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Kontrol global pengiriman pengingat presensi pagi, logbook sore, dan verifikasi mentor.</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="reminder_master_enabled" value="1" x-model="masterEnabled" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-indigo-600"></div>
                            <span class="ml-2.5 text-xs font-bold text-slate-700 dark:text-slate-300" x-text="masterEnabled ? 'AKTIF' : 'NONAKTIF'"></span>
                        </label>
                    </div>

                    <div x-show="masterEnabled" x-transition class="space-y-4">
                        <!-- Jalur Distribusi -->
                        <div>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider block mb-2.5">
                                <i class="fa-solid fa-paper-plane text-indigo-500 mr-1"></i> Jalur Pengiriman Notifikasi
                            </span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                <label class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 flex items-start gap-3 cursor-pointer hover:bg-slate-100/70 dark:hover:bg-slate-900 transition">
                                    <input type="checkbox" name="reminder_email_enabled" value="1" {{ $settings['reminder_email_enabled'] ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-envelope text-indigo-500 text-xs"></i>
                                            <span class="text-xs font-semibold text-slate-900 dark:text-slate-100">Kirim Email Pengingat Otomatis</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Mengirimkan pesan email langsung ke inbox peserta & mentor agar memicu notifikasi di HP.</p>
                                    </div>
                                </label>

                                <label class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 flex items-start gap-3 cursor-pointer hover:bg-slate-100/70 dark:hover:bg-slate-900 transition">
                                    <input type="checkbox" name="reminder_inapp_enabled" value="1" {{ $settings['reminder_inapp_enabled'] ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-bell text-indigo-500 text-xs"></i>
                                            <span class="text-xs font-semibold text-slate-900 dark:text-slate-100">Notifikasi Lonceng di Website</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Mencatat riwayat pengingat pada menu dropdown lonceng di navbar & sidebar aplikasi.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Kategori Pengingat -->
                        <div>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider block mb-2.5">
                                <i class="fa-regular fa-clock text-indigo-500 mr-1"></i> Jenis & Jadwal Pengingat
                            </span>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                                <label class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 flex items-start gap-3 cursor-pointer hover:bg-slate-100/70 dark:hover:bg-slate-900 transition">
                                    <input type="checkbox" name="reminder_morning_enabled" value="1" {{ $settings['reminder_morning_enabled'] ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <span class="text-xs font-semibold text-slate-900 dark:text-slate-100 block">Presensi Pagi</span>
                                        <span class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold block">08:00 WIB</span>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Ingatkan peserta yang belum presensi check-in kehadiran.</p>
                                    </div>
                                </label>

                                <label class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 flex items-start gap-3 cursor-pointer hover:bg-slate-100/70 dark:hover:bg-slate-900 transition">
                                    <input type="checkbox" name="reminder_afternoon_enabled" value="1" {{ $settings['reminder_afternoon_enabled'] ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <span class="text-xs font-semibold text-slate-900 dark:text-slate-100 block">Logbook Sore & Pulang</span>
                                        <span class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold block">16:00 & 17:00 WIB</span>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Ingatkan melengkapi ringkasan tugas & jam kerja harian.</p>
                                    </div>
                                </label>

                                <label class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 flex items-start gap-3 cursor-pointer hover:bg-slate-100/70 dark:hover:bg-slate-900 transition">
                                    <input type="checkbox" name="reminder_mentor_enabled" value="1" {{ $settings['reminder_mentor_enabled'] ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <span class="text-xs font-semibold text-slate-900 dark:text-slate-100 block">Verifikasi Mentor</span>
                                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold block">Setiap Hari Kerja</span>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Ingatkan mentor yang memiliki antrean logbook pending.</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Simpan Pengaturan</span>
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
