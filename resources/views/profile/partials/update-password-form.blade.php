<section>
    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700/60 mb-6">
        <div>
            <h3 class="font-bold text-sm text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-lock text-slate-700 dark:text-slate-300 text-xs"></i>
                Ubah Kata Sandi
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Pastikan akun Anda menggunakan kombinasi kata sandi yang aman dan tidak digunakan pada layanan lain.
            </p>
        </div>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4 max-w-xl">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div class="space-y-1.5">
            <label for="update_password_current_password" class="block font-semibold text-xs text-slate-700 dark:text-slate-300">
                Kata Sandi Saat Ini <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 text-xs">
                    <i class="fa-solid fa-key"></i>
                </span>
                <input id="update_password_current_password" name="current_password" type="password" 
                    class="pl-9 block w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-100 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition shadow-2xs py-2.5" 
                    autocomplete="current-password" placeholder="Masukkan kata sandi lama Anda" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <!-- New Password & Confirmation Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- New Password -->
            <div class="space-y-1.5">
                <label for="update_password_password" class="block font-semibold text-xs text-slate-700 dark:text-slate-300">
                    Kata Sandi Baru <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 text-xs">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input id="update_password_password" name="password" type="password" 
                        class="pl-9 block w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-100 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition shadow-2xs py-2.5" 
                        autocomplete="new-password" placeholder="Minimal 8 karakter" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
            </div>

            <!-- Password Confirmation -->
            <div class="space-y-1.5">
                <label for="update_password_password_confirmation" class="block font-semibold text-xs text-slate-700 dark:text-slate-300">
                    Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 text-xs">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                        class="pl-9 block w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-100 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition shadow-2xs py-2.5" 
                        autocomplete="new-password" placeholder="Ulangi kata sandi baru" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                <i class="fa-solid fa-lock text-xs"></i>
                <span>Perbarui Kata Sandi</span>
            </button>

            @if (session('status') === 'password-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-lg border border-emerald-200 dark:border-emerald-800"
                >
                    <i class="fa-solid fa-circle-check text-xs"></i>
                    Kata sandi berhasil diperbarui
                </span>
            @endif
        </div>
    </form>
</section>
