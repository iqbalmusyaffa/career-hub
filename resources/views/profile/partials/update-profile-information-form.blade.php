<section>
    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700/60 mb-6">
        <div>
            <h3 class="font-bold text-sm text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-id-card text-blue-600 dark:text-blue-400 text-xs"></i>
                Informasi Akun
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Perbarui nama lengkap dan alamat email yang terhubung dengan akun Anda.
            </p>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Full Name -->
            <div class="space-y-1.5">
                <label for="name" class="block font-semibold text-xs text-slate-700 dark:text-slate-300">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 text-xs">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input id="name" name="name" type="text" 
                        class="pl-9 block w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-100 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition shadow-2xs py-2.5" 
                        value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                </div>
                <x-input-error class="mt-1" :messages="$errors->get('name')" />
            </div>

            <!-- Email Address -->
            <div class="space-y-1.5">
                <label for="email" class="block font-semibold text-xs text-slate-700 dark:text-slate-300">
                    Alamat Email <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 text-xs">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input id="email" name="email" type="email" 
                        class="pl-9 block w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-100 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition shadow-2xs py-2.5" 
                        value="{{ old('email', $user->email) }}" required autocomplete="username" />
                </div>
                <x-input-error class="mt-1" :messages="$errors->get('email')" />
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 flex items-start gap-3">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm mt-0.5"></i>
                <div class="text-xs text-amber-800 dark:text-amber-300">
                    <p class="font-semibold">Alamat email Anda belum diverifikasi.</p>
                    <button form="send-verification" class="mt-1 underline text-xs font-semibold text-amber-900 dark:text-amber-200 hover:text-amber-950">
                        Klik di sini untuk mengirim ulang email verifikasi.
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1.5 font-semibold text-emerald-600 dark:text-emerald-400">
                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            </div>
        @endif

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                <i class="fa-solid fa-check text-xs"></i>
                <span>Simpan Perubahan</span>
            </button>

            @if (session('status') === 'profile-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-lg border border-emerald-200 dark:border-emerald-800"
                >
                    <i class="fa-solid fa-circle-check text-xs"></i>
                    Perubahan berhasil disimpan
                </span>
            @endif
        </div>
    </form>
</section>
