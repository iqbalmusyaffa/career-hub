<footer class="bg-gray-900 text-gray-400 text-xs py-10 border-t border-gray-800 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-sm">T</div>
                    <span class="font-black text-lg text-white">TalentFlow</span>
                </div>
                <p class="text-2xs text-gray-400 leading-relaxed">Platform Rekrutmen Digital & Karir Modern Terintegrasi. Menghubungkan talenta terbaik dengan perusahaan impian di Indonesia.</p>
            </div>
            <div>
                <h4 class="font-bold text-white uppercase text-2xs tracking-wider mb-3">Navigasi Utama</h4>
                <ul class="space-y-2 text-2xs">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-white transition">Dashboard Platform</a></li>
                    <li><a href="{{ route('jobs.index') }}" class="hover:text-white transition">Cari Lowongan Kerja</a></li>
                    <li><a href="{{ route('salary-benchmark.index') }}" class="hover:text-white transition">Kalkulator Estimasi Gaji</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-white uppercase text-2xs tracking-wider mb-3">Layanan Perusahaan</h4>
                <ul class="space-y-2 text-2xs">
                    <li><a href="{{ route('admin.jobs.create') }}" class="hover:text-white transition">Pasang Lowongan Pekerjaan</a></li>
                    <li><a href="{{ route('admin.company.profile.edit') }}" class="hover:text-white transition">Verifikasi Legalitas SIUP / NIB</a></li>
                    <li><a href="{{ route('admin.applications.index') }}" class="hover:text-white transition">Manajemen Pelamar & Ujian Online</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-white uppercase text-2xs tracking-wider mb-3">Bantuan & Legalitas</h4>
                <p class="text-2xs text-gray-400 mb-2">Platform Karir Resmi Terverifikasi & Dilindungi Hak Cipta Perundang-Undangan.</p>
                <div class="flex items-center gap-3 text-sm text-gray-400 pt-1">
                    <i class="fa-brands fa-linkedin hover:text-white transition cursor-pointer"></i>
                    <i class="fa-brands fa-facebook hover:text-white transition cursor-pointer"></i>
                    <i class="fa-brands fa-instagram hover:text-white transition cursor-pointer"></i>
                    <i class="fa-brands fa-youtube hover:text-white transition cursor-pointer"></i>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-2xs text-gray-500">
            <p>&copy; {{ date('Y') }} TalentFlow Web Karir Enterprise. All rights reserved.</p>
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
