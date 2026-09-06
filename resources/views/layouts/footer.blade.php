<footer class="bg-slate-900 text-slate-400 text-xs py-10 border-t border-slate-800 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div class="space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-xs">T</div>
                    <span class="font-bold text-base text-white tracking-tight">TalentFlow</span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">Platform Rekrutmen Digital & Karir Terintegrasi. Menghubungkan talenta terbaik dengan perusahaan impian di Indonesia.</p>
            </div>
            <div>
                <h4 class="font-semibold text-slate-200 uppercase text-xs tracking-wider mb-3">Navigasi Utama</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-white transition">Dashboard Platform</a></li>
                    <li><a href="{{ route('jobs.index') }}" class="hover:text-white transition">Cari Lowongan Kerja</a></li>
                    <li><a href="{{ route('companies.index') }}" class="hover:text-white transition">Direktori Perusahaan</a></li>
                    <li><a href="{{ route('salary-benchmark.index') }}" class="hover:text-white transition">Kalkulator Estimasi Gaji</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-slate-200 uppercase text-xs tracking-wider mb-3">Layanan Perusahaan</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('admin.jobs.create') }}" class="hover:text-white transition">Pasang Lowongan Pekerjaan</a></li>
                    <li><a href="{{ route('admin.company.profile.edit') }}" class="hover:text-white transition">Verifikasi Legalitas Perusahaan</a></li>
                    <li><a href="{{ route('admin.applications.index') }}" class="hover:text-white transition">Manajemen Pelamar Kerja</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-slate-200 uppercase text-xs tracking-wider mb-3">Bantuan & Legalitas</h4>
                <p class="text-xs text-slate-400 mb-2">Platform Karir Resmi Terverifikasi & Dilindungi Hak Cipta Perundang-Undangan.</p>
                <div class="flex items-center gap-3 text-sm text-slate-400 pt-2">
                    <i class="fa-brands fa-linkedin hover:text-white transition cursor-pointer"></i>
                    <i class="fa-brands fa-facebook hover:text-white transition cursor-pointer"></i>
                    <i class="fa-brands fa-instagram hover:text-white transition cursor-pointer"></i>
                    <i class="fa-brands fa-youtube hover:text-white transition cursor-pointer"></i>
                </div>
            </div>
        </div>
        <div class="border-t border-slate-800 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} TalentFlow Enterprise Career Platform. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <span>Kebijakan Privasi</span>
                <span>•</span>
                <span>Syarat & Ketentuan</span>
                <span>•</span>
                <span>Keamanan Data</span>
            </div>
        </div>
    </div>
</footer>
