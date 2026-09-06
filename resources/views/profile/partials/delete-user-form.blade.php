<section>
    <div class="flex items-center justify-between pb-4 border-b border-rose-100 dark:border-rose-950/80 mb-6">
        <div>
            <h3 class="font-bold text-sm text-rose-600 dark:text-rose-400 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                Zona Berbahaya: Hapus Akun
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Penghapusan akun bersifat permanen dan tidak dapat dibatalkan. Seluruh riwayat dan data terkait akan dihapus dari sistem.
            </p>
        </div>
    </div>

    <div class="flex items-center justify-between flex-wrap gap-4">
        <p class="text-xs text-slate-600 dark:text-slate-400 max-w-xl">
            Sebelum menghapus akun, pastikan Anda telah mengunduh atau mencadangkan seluruh data penting yang mungkin Anda perlukan di masa depan.
        </p>

        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-600 hover:text-white dark:hover:bg-rose-600 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 text-xs font-semibold rounded-xl transition shadow-2xs"
        >
            <i class="fa-solid fa-trash-can text-xs"></i>
            <span>Hapus Akun Saya</span>
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-7 space-y-5">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 text-rose-600 dark:text-rose-400">
                <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-base"></i>
                </div>
                <div>
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">
                        Konfirmasi Penghapusan Akun
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Tindakan ini permanen dan tidak dapat dipulihkan.</p>
                </div>
            </div>

            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                Apakah Anda benar-benar yakin ingin menghapus akun Anda? Seluruh riwayat lamaran, pengaturan, dan hak akses akan dihapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi.
            </p>

            <div class="space-y-1.5">
                <label for="password" class="block font-semibold text-xs text-slate-700 dark:text-slate-300">
                    Kata Sandi Konfirmasi
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-100 rounded-xl text-xs focus:ring-1 focus:ring-rose-500 focus:border-rose-500 transition shadow-2xs py-2.5 px-3.5"
                    placeholder="Masukkan kata sandi Anda saat ini"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
            </div>

            <div class="flex justify-end items-center gap-2.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 text-xs font-semibold rounded-xl transition">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl shadow-xs transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                    <span>Ya, Hapus Akun Permanen</span>
                </button>
            </div>
        </form>
    </x-modal>
</section>
