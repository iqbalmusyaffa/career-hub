<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-cyan-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/20 text-xl font-black shrink-0">
                    <i class="fa-solid fa-satellite"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 rounded-md text-3xs font-extrabold uppercase tracking-wider border border-blue-200 dark:border-blue-900">
                            Super Admin Master Control
                        </span>
                    </div>
                    <h2 class="font-black text-xl text-slate-900 dark:text-white leading-tight mt-0.5">
                        Master Pengaturan Presensi & GPS Global
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-xs text-emerald-800 dark:text-emerald-200 font-bold flex items-center gap-2 shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.attendance-settings.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Card 1: Waktu Cut-Off & Anti-Rapel Policy -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-700 pb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 flex items-center justify-center font-bold text-lg">
                            ⏰
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 dark:text-white">Batas Waktu Harian & Kebijakan Anti-Rapel</h3>
                            <p class="text-xs text-slate-500">Tentukan jam penutupan presensi dan aturan akhir pekan.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2">
                                Jam Batas Akhir Presensi Harian (Cut-Off WIB)
                            </label>
                            <input type="time" name="cutoff_time" value="{{ old('cutoff_time', $settings['cutoff_time']) }}" required class="w-full text-sm font-bold rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 p-3.5 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                            <p class="text-3xs text-slate-400 mt-1">Default <code>23:59</code> WIB. Setelah jam ini, tanggal otomatis terkunci.</p>
                        </div>

                        <div class="flex flex-col justify-center">
                            <label class="flex items-start gap-3 cursor-pointer p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700">
                                <input type="checkbox" name="allow_weekend_work" value="1" {{ $settings['allow_weekend_work'] ? 'checked' : '' }} class="mt-1 rounded text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Izinkan Presensi di Akhir Pekan (Sabtu & Minggu)</span>
                                    <span class="text-3xs text-slate-400">Jika dinonaktifkan, Sabtu dan Minggu otomatis libur & terkunci.</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Card 2: GPS Satelit & Proteksi Anti-Fake GPS -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-700 pb-4">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 flex items-center justify-center font-bold text-lg">
                            🛰️
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 dark:text-white">Sensor GPS Satelit & Filter Anti-Fake GPS</h3>
                            <p class="text-xs text-slate-500">Konfigurasi ambang batas akurasi satelit dan algoritma mock location.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="anti_fake_gps" value="1" {{ $settings['anti_fake_gps'] ? 'checked' : '' }} class="mt-1 rounded text-rose-600 focus:ring-rose-500">
                                <div>
                                    <span class="text-xs font-black text-slate-800 dark:text-slate-200 block flex items-center gap-2">
                                        <span>Aktifkan Deteksi Ketat Anti-Fake GPS & Mock Location</span>
                                        <span class="px-2 py-0.5 rounded-full text-3xs font-extrabold bg-rose-100 text-rose-700">Direkomendasikan</span>
                                    </span>
                                    <span class="text-3xs text-slate-500 dark:text-slate-400 leading-relaxed block mt-0.5">
                                        Mendeteksi otomatis akurasi sintetis (0m/1m), anomali pergeseran waktu sensor, dan manipulasi browser driver. Form submit akan diblokir dengan alert merah berkedip saat terdeteksi.
                                    </span>
                                </div>
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2">
                                Toleransi Maksimum Akurasi GPS Satelit (Meter)
                            </label>
                            <div class="relative max-w-sm">
                                <input type="number" name="max_gps_accuracy" min="10" max="1000" value="{{ old('max_gps_accuracy', $settings['max_gps_accuracy']) }}" required class="w-full text-sm font-bold rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 p-3.5 pl-10 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">±</span>
                            </div>
                            <p class="text-3xs text-slate-400 mt-1">Default <code>100</code> meter. Sinyal dengan akurasi di luar batas toleransi akan diminta untuk disegarkan.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Standar Kelulusan & Target Jam Kerja -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-700 pb-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center font-bold text-lg">
                            📊
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 dark:text-white">Standar Kelulusan & Akumulasi Jam Magang</h3>
                            <p class="text-xs text-slate-500">Parameter kelulusan pada transkrip evaluasi magang.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2">
                                Target Akumulasi Jam Kerja Default
                            </label>
                            <input type="number" name="target_hours" min="50" max="2000" value="{{ old('target_hours', $settings['target_hours']) }}" required class="w-full text-sm font-bold rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 p-3.5 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                            <p class="text-3xs text-slate-400 mt-1">Default <code>400</code> Jam (Setara 50 hari kerja 8 jam/hari).</p>
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2">
                                Persentase Minimal Kehadiran Kelulusan (%)
                            </label>
                            <input type="number" name="min_percentage" min="50" max="100" value="{{ old('min_percentage', $settings['min_percentage']) }}" required class="w-full text-sm font-bold rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-slate-800 dark:text-slate-100 p-3.5 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                            <p class="text-3xs text-slate-400 mt-1">Default <code>80</code> %. Peserta di bawah ambang batas akan ditandai tidak lulus otomatis.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-black text-xs rounded-2xl shadow-lg shadow-blue-600/30 transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Master Pengaturan Global
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
