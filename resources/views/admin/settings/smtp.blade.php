<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-envelope-circle-check text-blue-600"></i> Pengaturan SMTP Email Server
                </h2>
                <p class="text-xs text-gray-500 mt-1">Konfigurasi dinamis pengiriman email otomatis (Wawancara, Offer Letter, & Notifikasi Platform).</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-xl text-sm transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center gap-2 shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-800 text-xs font-bold flex items-center gap-2 shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-red-600 text-base"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Main SMTP Config Form -->
            <form method="POST" action="{{ route('admin.settings.smtp.update') }}" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-10 space-y-6">
                @csrf

                <div class="border-b border-gray-100 pb-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-blue-600"></i> Parameter Server SMTP
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Dapat menggunakan Gmail SMTP, Mailtrap, SendGrid, Amazon SES, atau SMTP Hosting cPanel.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">SMTP Host</label>
                        <input type="text" name="mail_host" value="{{ old('mail_host', $smtp['mail_host']) }}" placeholder="smtp.gmail.com atau smtp.mailtrap.io" required class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm font-bold">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">SMTP Port</label>
                        <input type="number" name="mail_port" value="{{ old('mail_port', $smtp['mail_port']) }}" placeholder="587 / 465" required class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm font-bold">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Enkripsi (Security)</label>
                        <select name="mail_encryption" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm font-bold">
                            <option value="tls" {{ old('mail_encryption', $smtp['mail_encryption']) == 'tls' ? 'selected' : '' }}>TLS (Port 587)</option>
                            <option value="ssl" {{ old('mail_encryption', $smtp['mail_encryption']) == 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
                            <option value="null" {{ old('mail_encryption', $smtp['mail_encryption']) == 'null' ? 'selected' : '' }}>None (Tanpa Enkripsi)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">SMTP Username / Email</label>
                        <input type="text" name="mail_username" value="{{ old('mail_username', $smtp['mail_username']) }}" placeholder="username@domain.com" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">SMTP Password / App Password</label>
                        <input type="password" name="mail_password" value="{{ old('mail_password', $smtp['mail_password']) }}" placeholder="••••••••••••" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Email Pengirim (From Address)</label>
                        <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $smtp['mail_from_address']) }}" placeholder="noreply@perusahaan.com" required class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Pengirim (From Name)</label>
                        <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $smtp['mail_from_name']) }}" placeholder="TalentFlow Recruitment" required class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition text-sm flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Konfigurasi SMTP
                    </button>
                </div>
            </form>

            <!-- Test SMTP Email Form -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-10 space-y-4">
                <div class="border-b border-gray-100 pb-3">
                    <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-emerald-600"></i> Pengujian Koneksi SMTP Server (Test Email)
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Kirim email uji coba langsung untuk memastikan parameter SMTP server berjalan 100% tanpa kendala.</p>
                </div>

                <form method="POST" action="{{ route('admin.settings.smtp.test') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                    @csrf
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Masukkan Alamat Email Penerima Uji Coba</label>
                        <input type="email" name="test_email" value="{{ auth()->user()->email }}" required placeholder="email.anda@gmail.com" class="w-full border-gray-300 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs transition shadow-md flex items-center gap-1.5 w-full sm:w-auto justify-center">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Test Email Now
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
