<section>
    <header class="border-b border-slate-100 dark:border-slate-700/80 pb-4 mb-5">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200/80 dark:border-indigo-800/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm font-semibold shrink-0">
                <i class="fa-solid fa-bell"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">
                    Preferensi Pengingat Presensi & Logbook
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Pilih bagaimana sistem mengirimkan pengingat kehadiran pagi, catatan logbook harian, dan tugas magang Anda.
                </p>
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="mb-4 p-3.5 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/60 rounded-xl text-xs text-emerald-800 dark:text-emerald-200 font-medium flex items-center gap-2.5">
            <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form method="post" action="{{ route('profile.notifications.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div class="space-y-3.5">
            <!-- Email Preference -->
            <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50/70 dark:bg-slate-900/50 flex items-start gap-3.5 cursor-pointer hover:bg-slate-100/60 dark:hover:bg-slate-900 transition">
                <input type="checkbox" name="email_attendance_reminder_enabled" value="1" 
                    {{ old('email_attendance_reminder_enabled', $user->email_attendance_reminder_enabled ?? true) ? 'checked' : '' }} 
                    class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-indigo-500 text-xs"></i>
                        <span class="text-xs font-bold text-slate-900 dark:text-slate-100">Kirim Pengingat ke Email Pribadi</span>
                        <span class="px-2 py-0.2 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/80 dark:bg-indigo-950/60 dark:text-indigo-300 dark:border-indigo-900">Bunyi di HP</span>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        Sistem akan mengirimkan email pengingat presensi pagi (08:00 WIB) dan melengkapi logbook sore (16:00 & 17:00 WIB) ke <strong>{{ $user->email }}</strong>.
                    </p>
                </div>
            </label>

            <!-- In-App Notification Preference -->
            <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50/70 dark:bg-slate-900/50 flex items-start gap-3.5 cursor-pointer hover:bg-slate-100/60 dark:hover:bg-slate-900 transition">
                <input type="checkbox" name="inapp_attendance_reminder_enabled" value="1" 
                    {{ old('inapp_attendance_reminder_enabled', $user->inapp_attendance_reminder_enabled ?? true) ? 'checked' : '' }} 
                    class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-bell text-indigo-500 text-xs"></i>
                        <span class="text-xs font-bold text-slate-900 dark:text-slate-100">Notifikasi Lonceng di Website</span>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        Tampilkan riwayat pengingat pada menu lonceng di bagian atas dashboard saat Anda membuka website.
                    </p>
                </div>
            </label>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Simpan Preferensi Notifikasi</span>
            </button>
        </div>
    </form>
</section>
