<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 text-[11px] font-semibold rounded-md border border-blue-200 dark:border-blue-800">
                        System Configuration
                    </span>
                    <span class="text-xs text-slate-400 dark:text-slate-500">| Mail Gateway</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight">
                    Pengaturan Server SMTP & Notifikasi Email
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                    Kelola kredensial pengiriman email otomatis platform (Undangan Wawancara, Offer Letter, Reset Sandi, & Verifikasi).
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.dashboard') }}" class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium py-2 px-3.5 rounded-xl text-xs transition border border-slate-200 dark:border-slate-700 shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-slate-400 text-xs"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 min-h-screen bg-slate-50/60 dark:bg-slate-900 transition-colors"
         x-data="smtpSettingsManager({
             host: @js(old('mail_host', $smtp['mail_host'])),
             port: @js(old('mail_port', $smtp['mail_port'])),
             encryption: @js(old('mail_encryption', $smtp['mail_encryption'])),
             username: @js(old('mail_username', $smtp['mail_username'])),
             fromAddress: @js(old('mail_from_address', $smtp['mail_from_address'])),
             fromName: @js(old('mail_from_name', $smtp['mail_from_name']))
         })">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-xl text-xs font-medium flex items-center gap-3 shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm shrink-0"></i>
                    <div class="flex-1">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-xl text-xs font-medium flex items-center gap-3 shadow-2xs">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 dark:text-rose-400 text-sm shrink-0"></i>
                    <div class="flex-1">{{ session('error') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 rounded-xl text-xs font-medium space-y-1 shadow-2xs">
                    <div class="flex items-center gap-2 font-semibold">
                        <i class="fa-solid fa-circle-exclamation text-amber-600"></i>
                        <span>Mohon periksa kesalahan input berikut:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Top Grid: Status Banner -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 rounded-2xl p-5 shadow-2xs">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex items-start sm:items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                            <i class="fa-solid fa-server"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-slate-900 dark:text-white text-sm">Status Layanan Mailer</h3>
                                @if(!empty($smtp['mail_host']))
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Terkonfigurasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Belum Dikonfigurasi
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Driver aktif: <code class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 font-mono text-[11px]">smtp</code> &bull; 
                                Host: <span class="font-medium text-slate-700 dark:text-slate-300">{{ $smtp['mail_host'] ?: 'None' }}:{{ $smtp['mail_port'] ?: '25' }}</span> &bull; 
                                Pengirim: <span class="font-medium text-slate-700 dark:text-slate-300">{{ $smtp['mail_from_address'] ?: 'Belum disetel' }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Quick Preset Selector Buttons -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium flex items-center gap-1.5 mr-1">
                            <i class="fa-solid fa-wand-magic-sparkles text-blue-500 text-[11px]"></i>
                            <span>Template Cepat:</span>
                        </span>
                        <button type="button" @click="applyPreset('sumopod')" 
                            :class="host === 'smtp.sumopod.com' ? 'bg-violet-50 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300 border-violet-300 dark:border-violet-700 ring-1 ring-violet-500/20 shadow-2xs font-semibold' : 'bg-slate-100/80 dark:bg-slate-900/80 hover:bg-violet-50 dark:hover:bg-violet-950/40 text-slate-700 dark:text-slate-300 hover:text-violet-600 dark:hover:text-violet-400 border-slate-200 dark:border-slate-800 font-medium'"
                            class="px-2.5 py-1.5 rounded-lg text-xs border transition flex items-center gap-1.5 cursor-pointer outline-none">
                            <i class="fa-solid fa-cloud-bolt text-violet-500"></i> Sumopod
                        </button>
                        <button type="button" @click="applyPreset('gmail')" 
                            :class="host === 'smtp.gmail.com' ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-300 dark:border-blue-700 ring-1 ring-blue-500/20 shadow-2xs font-semibold' : 'bg-slate-100/80 dark:bg-slate-900/80 hover:bg-blue-50 dark:hover:bg-blue-950/40 text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 border-slate-200 dark:border-slate-800 font-medium'"
                            class="px-2.5 py-1.5 rounded-lg text-xs border transition flex items-center gap-1.5 cursor-pointer outline-none">
                            <i class="fa-brands fa-google text-red-500"></i> Gmail
                        </button>
                        <button type="button" @click="applyPreset('mailtrap')" 
                            :class="host && host.includes('mailtrap') ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-300 dark:border-emerald-700 ring-1 ring-emerald-500/20 shadow-2xs font-semibold' : 'bg-slate-100/80 dark:bg-slate-900/80 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 border-slate-200 dark:border-slate-800 font-medium'"
                            class="px-2.5 py-1.5 rounded-lg text-xs border transition flex items-center gap-1.5 cursor-pointer outline-none">
                            <i class="fa-solid fa-flask text-emerald-500"></i> Mailtrap
                        </button>
                        <button type="button" @click="applyPreset('sendgrid')" 
                            :class="host === 'smtp.sendgrid.net' ? 'bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border-sky-300 dark:border-sky-700 ring-1 ring-sky-500/20 shadow-2xs font-semibold' : 'bg-slate-100/80 dark:bg-slate-900/80 hover:bg-sky-50 dark:hover:bg-sky-950/40 text-slate-700 dark:text-slate-300 hover:text-sky-600 dark:hover:text-sky-400 border-slate-200 dark:border-slate-800 font-medium'"
                            class="px-2.5 py-1.5 rounded-lg text-xs border transition flex items-center gap-1.5 cursor-pointer outline-none">
                            <i class="fa-solid fa-paper-plane text-sky-500"></i> SendGrid
                        </button>
                        <button type="button" @click="applyPreset('brevo')" 
                            :class="host === 'smtp-relay.brevo.com' ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border-indigo-300 dark:border-indigo-700 ring-1 ring-indigo-500/20 shadow-2xs font-semibold' : 'bg-slate-100/80 dark:bg-slate-900/80 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 border-slate-200 dark:border-slate-800 font-medium'"
                            class="px-2.5 py-1.5 rounded-lg text-xs border transition flex items-center gap-1.5 cursor-pointer outline-none">
                            <i class="fa-solid fa-envelope-open-text text-indigo-500"></i> Brevo
                        </button>
                        <button type="button" @click="applyPreset('cpanel')" 
                            class="px-2.5 py-1.5 rounded-lg text-xs font-medium bg-slate-100/80 dark:bg-slate-900/80 hover:bg-amber-50 dark:hover:bg-amber-950/40 text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 border border-slate-200 dark:border-slate-800 transition flex items-center gap-1.5 cursor-pointer outline-none">
                            <i class="fa-solid fa-server text-amber-500"></i> cPanel / SSL
                        </button>
                    </div>
                </div>
            </div>

            <!-- Two-Column Architecture: Form & Side Controls -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left Column: Main SMTP Parameters (8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                    <form method="POST" action="{{ route('admin.settings.smtp.update') }}" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                        @csrf

                        <!-- Card Header -->
                        <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-700/80 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
                            <div>
                                <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-sliders text-blue-600 dark:text-blue-400"></i>
                                    <span>Parameter Server Koneksi & Autentikasi</span>
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Konfigurasi host, port, kredensial akun, serta identitas pengirim.</p>
                            </div>
                            <span class="text-[11px] font-mono text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded">ENV & Database Sync</span>
                        </div>

                        <div class="p-6 space-y-6">

                            <!-- Section 1: Server Connection -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-700/60">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">1. Konfigurasi Jaringan & Enkripsi</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <!-- Host -->
                                    <div class="sm:col-span-2 space-y-1.5">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            SMTP Host <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                                <i class="fa-solid fa-network-wired"></i>
                                            </div>
                                            <input type="text" name="mail_host" x-model="host" placeholder="smtp.gmail.com atau mail.perusahaan.com" required 
                                                class="w-full pl-9 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3 font-mono">
                                        </div>
                                    </div>

                                    <!-- Port -->
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            SMTP Port <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="mail_port" x-model="port" placeholder="587 / 465" required 
                                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3 font-mono">
                                        </div>
                                    </div>

                                    <!-- Encryption -->
                                    <div class="sm:col-span-3 space-y-1.5">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            Protokol Keamanan (Encryption) <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="grid grid-cols-3 gap-3">
                                            <label class="cursor-pointer border rounded-xl p-3 flex items-center gap-3 transition-all"
                                                :class="encryption === 'tls' ? 'border-blue-600 bg-blue-50/40 dark:bg-blue-950/30 text-blue-900 dark:text-blue-300 shadow-2xs' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:border-slate-300'">
                                                <input type="radio" name="mail_encryption" value="tls" x-model="encryption" class="text-blue-600 focus:ring-blue-600">
                                                <div>
                                                    <div class="font-semibold text-xs">TLS (Rekomendasi)</div>
                                                    <div class="text-[10px] text-slate-400">Port standar: 587 / 2525</div>
                                                </div>
                                            </label>

                                            <label class="cursor-pointer border rounded-xl p-3 flex items-center gap-3 transition-all"
                                                :class="encryption === 'ssl' ? 'border-blue-600 bg-blue-50/40 dark:bg-blue-950/30 text-blue-900 dark:text-blue-300 shadow-2xs' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:border-slate-300'">
                                                <input type="radio" name="mail_encryption" value="ssl" x-model="encryption" class="text-blue-600 focus:ring-blue-600">
                                                <div>
                                                    <div class="font-semibold text-xs">SSL (Direct)</div>
                                                    <div class="text-[10px] text-slate-400">Port standar: 465</div>
                                                </div>
                                            </label>

                                            <label class="cursor-pointer border rounded-xl p-3 flex items-center gap-3 transition-all"
                                                :class="encryption === 'null' ? 'border-blue-600 bg-blue-50/40 dark:bg-blue-950/30 text-blue-900 dark:text-blue-300 shadow-2xs' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:border-slate-300'">
                                                <input type="radio" name="mail_encryption" value="null" x-model="encryption" class="text-blue-600 focus:ring-blue-600">
                                                <div>
                                                    <div class="font-semibold text-xs">None</div>
                                                    <div class="text-[10px] text-slate-400">Lokal / Tanpa enkripsi</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Authentication Credentials -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-700/60">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">2. Kredensial Autentikasi Akun</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Username -->
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            SMTP Username / Akun Email
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                            <input type="text" name="mail_username" x-model="username" placeholder="admin@perusahaan.com atau apikey" 
                                                class="w-full pl-9 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                                        </div>
                                    </div>

                                    <!-- Password with Toggle -->
                                    <div class="space-y-1.5" x-data="{ showPass: false }">
                                        <div class="flex items-center justify-between">
                                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                                SMTP Password / App Password
                                            </label>
                                            <button type="button" @click="showPass = !showPass" class="text-[11px] text-blue-600 dark:text-blue-400 hover:underline">
                                                <span x-text="showPass ? 'Sembunyikan' : 'Tampilkan'"></span>
                                            </button>
                                        </div>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                                <i class="fa-solid fa-key"></i>
                                            </div>
                                            <input :type="showPass ? 'text' : 'password'" name="mail_password" value="{{ old('mail_password', $smtp['mail_password']) }}" placeholder="••••••••••••••••" 
                                                class="w-full pl-9 pr-10 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3 font-mono">
                                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs">
                                                <i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Sender Identity -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-700/60">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">3. Identitas Pengirim (Sender Identity)</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- From Address -->
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            Alamat Email Pengirim (From Address) <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                                <i class="fa-solid fa-at"></i>
                                            </div>
                                            <input type="email" name="mail_from_address" x-model="fromAddress" placeholder="noreply@domain-anda.com" required 
                                                class="w-full pl-9 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                                        </div>
                                    </div>

                                    <!-- From Name -->
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            Nama Tampilan Pengirim (From Name) <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                                <i class="fa-solid fa-id-badge"></i>
                                            </div>
                                            <input type="text" name="mail_from_name" x-model="fromName" placeholder="TalentFlow HR Recruitment" required 
                                                class="w-full pl-9 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Card Footer Action Bar -->
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-950 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                                <i class="fa-solid fa-shield-halved text-emerald-500"></i>
                                <span>Kredensial disimpan secara terenkripsi di database sistem.</span>
                            </span>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span>Simpan Konfigurasi SMTP</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Live Testing & Helpful Guides (4 cols) -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- 1. Live Test Email Dispatcher -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50">
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-paper-plane text-emerald-600 dark:text-emerald-400"></i>
                                <span>Uji Koneksi (Test Email)</span>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kirim email uji coba secara real-time untuk memastikan konfigurasi bekerja normal.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.settings.smtp.test') }}" class="p-5 space-y-4" x-data="{ sending: false }" @submit="sending = true">
                            @csrf
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    Email Penerima Uji Coba <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <input type="email" name="test_email" value="{{ auth()->user()->email }}" required placeholder="email.tujuan@gmail.com" 
                                        class="w-full pl-9 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-1 focus:ring-emerald-600 focus:border-emerald-600 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                                </div>
                            </div>

                            <button type="submit" :disabled="sending" class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-xs flex items-center justify-center gap-2">
                                <template x-if="!sending">
                                    <span class="flex items-center gap-2">
                                        <i class="fa-solid fa-paper-plane"></i>
                                        <span>Kirim Email Percobaan</span>
                                    </span>
                                </template>
                                <template x-if="sending">
                                    <span class="flex items-center gap-2">
                                        <i class="fa-solid fa-spinner fa-spin"></i>
                                        <span>Menghubungi Server SMTP...</span>
                                    </span>
                                </template>
                            </button>

                            <p class="text-[11px] text-slate-400 dark:text-slate-500 leading-relaxed">
                                Pastikan Anda telah menyimpan perubahan parameter di sebelah kiri sebelum melakukan pengujian koneksi.
                            </p>
                        </form>
                    </div>

                    <!-- 2. Configuration Guide & Best Practices -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-slate-700/80 p-5 space-y-4">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-lightbulb text-amber-500 text-sm"></i>
                            <h4 class="font-bold text-xs text-slate-900 dark:text-white uppercase tracking-wider">Panduan & Tips Provider</h4>
                        </div>

                        <div class="space-y-3 text-xs text-slate-600 dark:text-slate-300">
                            <!-- Sumopod Tip -->
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-violet-200/60 dark:border-violet-800/60 space-y-1">
                                <div class="font-semibold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                    <i class="fa-solid fa-cloud-bolt text-violet-500"></i>
                                    <span>Sumopod Email Service</span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                                    Host: <code class="font-mono text-slate-700 dark:text-slate-300">smtp.sumopod.com</code> &bull; Port: <code class="font-mono text-slate-700 dark:text-slate-300">465</code> &bull; Enkripsi: <strong class="text-violet-600 dark:text-violet-400">SSL</strong>. Kredensial Username & Password diambil dari tab <em>Credentials</em> dashboard Sumopod Anda.
                                </p>
                            </div>

                            <!-- Gmail Tip -->
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 space-y-1">
                                <div class="font-semibold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                    <i class="fa-brands fa-google text-red-500"></i>
                                    <span>Google / Gmail SMTP</span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                    Jika menggunakan 2-Factor Authentication, Anda wajib membuat <strong>App Password (Sandi Aplikasi)</strong> 16 digit di Akun Google &rarr; Keamanan &rarr; Sandi Aplikasi.
                                </p>
                            </div>

                            <!-- Port Tip -->
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 space-y-1">
                                <div class="font-semibold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                    <i class="fa-solid fa-shield-halved text-blue-500"></i>
                                    <span>Port & Enkripsi Rekomendasi</span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                    Gunakan <strong>Port 587 (TLS)</strong> untuk Gmail, SendGrid, Mailtrap. Gunakan <strong>Port 465 (SSL)</strong> untuk Sumopod atau mail server cPanel/hosting.
                                </p>
                            </div>

                            <!-- Use cases -->
                            <div class="space-y-2 pt-1">
                                <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Email Otomatis yang Terhubung:</span>
                                <ul class="space-y-1.5 text-[11px] text-slate-600 dark:text-slate-400">
                                    <li class="flex items-center gap-2">
                                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i>
                                        <span>Undangan & Jadwal Wawancara Pelamar</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i>
                                        <span>Pemberitahuan Offer Letter HR</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i>
                                        <span>Reset Password & Verifikasi Email</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- Alpine.js Presets Logic -->
    <script>
        function smtpSettingsManager(initial) {
            return {
                host: initial.host || '',
                port: initial.port || 587,
                encryption: initial.encryption || 'tls',
                username: initial.username || '',
                fromAddress: initial.fromAddress || '',
                fromName: initial.fromName || 'TalentFlow System',

                applyPreset(type) {
                    if (type === 'sumopod') {
                        this.host = 'smtp.sumopod.com';
                        this.port = 465;
                        this.encryption = 'ssl';
                    } else if (type === 'gmail') {
                        this.host = 'smtp.gmail.com';
                        this.port = 587;
                        this.encryption = 'tls';
                        if (!this.fromName) this.fromName = 'TalentFlow Recruitment';
                    } else if (type === 'mailtrap') {
                        this.host = 'sandbox.smtp.mailtrap.io';
                        this.port = 2525;
                        this.encryption = 'tls';
                        if (!this.fromAddress) this.fromAddress = 'noreply@talentflow.test';
                    } else if (type === 'sendgrid') {
                        this.host = 'smtp.sendgrid.net';
                        this.port = 587;
                        this.encryption = 'tls';
                        this.username = 'apikey';
                    } else if (type === 'brevo') {
                        this.host = 'smtp-relay.brevo.com';
                        this.port = 587;
                        this.encryption = 'tls';
                    } else if (type === 'cpanel') {
                        this.port = 465;
                        this.encryption = 'ssl';
                    }
                }
            };
        }
    </script>
</x-app-layout>
