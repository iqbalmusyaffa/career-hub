<footer class="bg-slate-950 text-slate-400 text-xs border-t border-slate-800/80 mt-auto">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-8">
            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-3.5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-xs">
                        <i class="fa-solid fa-briefcase text-xs"></i>
                    </div>
                    <span class="font-extrabold text-base text-white tracking-tight">TalentFlow</span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                    Platform ekosistem karir & rekrutmen digital modern yang menghubungkan ribuan talenta profesional dengan perusahaan impian di Indonesia.
                </p>
                <div class="flex items-center gap-2 pt-1">
                    <a href="https://linkedin.com" target="_blank" rel="noopener" class="w-7 h-7 rounded-lg bg-slate-900 hover:bg-blue-600 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-800 hover:border-blue-500 shadow-2xs" title="LinkedIn">
                        <i class="fa-brands fa-linkedin-in text-xs"></i>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noopener" class="w-7 h-7 rounded-lg bg-slate-900 hover:bg-gradient-to-tr hover:from-amber-600 hover:via-rose-600 hover:to-purple-600 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-800 hover:border-rose-500 shadow-2xs" title="Instagram">
                        <i class="fa-brands fa-instagram text-xs"></i>
                    </a>
                    <a href="https://youtube.com" target="_blank" rel="noopener" class="w-7 h-7 rounded-lg bg-slate-900 hover:bg-rose-600 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-800 hover:border-rose-500 shadow-2xs" title="YouTube">
                        <i class="fa-brands fa-youtube text-xs"></i>
                    </a>
                    <a href="https://x.com" target="_blank" rel="noopener" class="w-7 h-7 rounded-lg bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-800 hover:border-slate-600 shadow-2xs" title="X (Twitter)">
                        <i class="fa-brands fa-x-twitter text-xs"></i>
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="w-7 h-7 rounded-lg bg-slate-900 hover:bg-emerald-600 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-800 hover:border-emerald-500 shadow-2xs" title="WhatsApp Support">
                        <i class="fa-brands fa-whatsapp text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Col 2: Navigasi -->
            <div>
                <h4 class="font-bold text-slate-200 uppercase text-xs tracking-wider mb-3.5">Jelajahi Karir</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('jobs.index') }}" class="hover:text-blue-400 transition">Cari Lowongan</a></li>
                    <li><a href="{{ route('companies.index') }}" class="hover:text-blue-400 transition">Direktori Perusahaan</a></li>
                    <li><a href="{{ route('salary-benchmark.index') }}" class="hover:text-blue-400 transition">Kalkulator Gaji UMK</a></li>
                    <li><a href="{{ route('saved-jobs.index') }}" class="hover:text-blue-400 transition">Lowongan Tersimpan</a></li>
                </ul>
            </div>

            <!-- Col 3: Layanan HR -->
            <div>
                <h4 class="font-bold text-slate-200 uppercase text-xs tracking-wider mb-3.5">Solusi Rekrutmen</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('admin.jobs.create') }}" class="hover:text-blue-400 transition">Pasang Lowongan</a></li>
                    <li><a href="{{ route('admin.company.profile.edit') }}" class="hover:text-blue-400 transition">Profil & Legalitas</a></li>
                    <li><a href="{{ route('admin.applications.index') }}" class="hover:text-blue-400 transition">Data Seleksi Pelamar</a></li>
                    <li><a href="{{ route('admin.company-team.index') }}" class="hover:text-blue-400 transition">Manajemen Tim HR</a></li>
                </ul>
            </div>

            <!-- Col 4: Dukungan -->
            <div>
                <h4 class="font-bold text-slate-200 uppercase text-xs tracking-wider mb-3.5">Bantuan & Legalitas</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('pages.faq') }}" class="hover:text-blue-400 transition">Pusat Bantuan & FAQ</a></li>
                    <li><a href="{{ route('pages.privacy') }}" class="hover:text-blue-400 transition">Kebijakan Privasi</a></li>
                    <li><a href="{{ route('pages.terms') }}" class="hover:text-blue-400 transition">Syarat & Ketentuan</a></li>
                    <li class="pt-1 text-slate-400"><a href="mailto:support@talentflow.id" class="text-blue-400 hover:underline">support@talentflow.id</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-800/80 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} TalentFlow Indonesia Career Platform. Hak Cipta Dilindungi Undang-Undang.</p>
            <div class="flex items-center gap-2 px-3 py-1 bg-slate-900 rounded-full border border-slate-800 text-[11px] text-slate-400">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Semua Sistem Normal</span>
            </div>
            <div class="flex items-center gap-4 text-[11px]">
                <span>Indonesia (ID)</span>
                <span>•</span>
                <span>Platform Rekrutmen Resmi</span>
            </div>
        </div>
    </div>
</footer>
