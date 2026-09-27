<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1 font-medium">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.jobs.index') }}" class="hover:text-blue-600 transition">Lowongan</a>
                    <span>/</span>
                    <span class="text-blue-600 dark:text-blue-400 font-bold">Pengajuan Kategori</span>
                </div>
                <h2 class="font-bold text-xl sm:text-2xl text-slate-900 dark:text-white leading-tight flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-xs">
                        <i class="fa-solid fa-hand-holding-hand"></i>
                    </div>
                    <span>Pengajuan Kategori Bidang Pekerjaan</span>
                </h2>
            </div>
            
            <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                <i class="fa-solid fa-briefcase text-xs"></i>
                <span>Kembali ke Pasang Lowongan</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 dark:bg-slate-900 min-h-screen transition-colors" x-data="{ proposeModalOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center gap-3 text-xs sm:text-sm font-medium shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-2xl flex items-center gap-3 text-xs sm:text-sm font-medium shadow-xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 dark:text-rose-400 text-base shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Banner Info -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 sm:p-8 text-white shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <span class="px-3 py-1 bg-white/20 text-white rounded-full text-[11px] font-bold uppercase tracking-wider inline-block">Prosedur Pengajuan Kategori</span>
                    <h3 class="text-xl sm:text-2xl font-bold">Perlu Kategori Bidang Baru untuk Lowongan Anda?</h3>
                    <p class="text-xs sm:text-sm text-blue-100 leading-relaxed">
                        Jika bidang posisi lowongan perusahaan Anda belum terakomodasi dalam 8 kategori resmi, Anda dapat mengajukan penambahan kategori ke Super Admin. Setelah disetujui, kategori tersebut akan langsung aktif di formulir lowongan.
                    </p>
                </div>
                <button @click="proposeModalOpen = true" type="button" class="px-5 py-3 bg-white text-blue-700 hover:bg-blue-50 text-xs font-bold rounded-2xl shadow-md transition shrink-0 flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Ajukan Kategori Baru</span>
                </button>
            </div>

            <!-- List of My Proposals -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700/80 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 dark:text-white">Riwayat Pengajuan Kategori Anda</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daftar usulan kategori baru yang pernah Anda kirimkan ke Super Admin</p>
                    </div>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-700/60">
                    @forelse($myRequests as $req)
                        <div class="p-5 sm:p-6 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div class="space-y-2 flex-1 min-w-0">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ $req->name }}</h4>
                                    
                                    @if($req->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i> Disetujui & Aktif
                                        </span>
                                    @elseif($req->status === 'pending_approval')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            <i class="fa-solid fa-clock text-[10px]"></i> Menunggu Review Super Admin
                                        </span>
                                    @elseif($req->status === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                            <i class="fa-solid fa-circle-xmark text-[10px]"></i> Belum Disetujui
                                        </span>
                                    @endif
                                </div>

                                @if($req->subtext)
                                    <p class="text-xs text-slate-600 dark:text-slate-300"><strong class="text-slate-800 dark:text-slate-200">Spesialisasi:</strong> {{ $req->subtext }}</p>
                                @endif

                                <div class="text-xs text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/60 p-3 rounded-xl border border-slate-100 dark:border-slate-700/60 max-w-2xl">
                                    <div class="font-semibold text-slate-700 dark:text-slate-300 mb-0.5 text-[11px]">Alasan Pengajuan:</div>
                                    <p class="leading-relaxed">{{ $req->request_reason }}</p>
                                </div>

                                @if($req->status === 'rejected' && $req->rejection_reason)
                                    <div class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-xl text-xs text-rose-800 dark:text-rose-300 max-w-2xl">
                                        <div class="font-bold mb-0.5 flex items-center gap-1">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            <span>Catatan dari Super Admin:</span>
                                        </div>
                                        <p>{{ $req->rejection_reason }}</p>
                                    </div>
                                @endif

                                <div class="text-[11px] text-slate-400 dark:text-slate-500">
                                    <span>Diajukan pada: {{ $req->created_at->format('d M Y, H:i') }} WIB</span>
                                    @if($req->approved_at)
                                        <span class="ml-3">• Disetujui pada: {{ $req->approved_at->format('d M Y, H:i') }} WIB</span>
                                    @endif
                                </div>
                            </div>

                            @if($req->status === 'active')
                                <a href="{{ route('admin.jobs.create') }}" class="px-4 py-2 bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/60 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 border border-blue-100 dark:border-blue-800">
                                    <span>Pakai di Lowongan</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700/60 text-slate-400 dark:text-slate-500 flex items-center justify-center text-2xl mx-auto mb-3">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200">Belum Ada Pengajuan Kategori</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">Anda belum pernah mengajukan kategori bidang baru. Seluruh 8 kategori standar sudah dapat langsung digunakan.</p>
                            <button @click="proposeModalOpen = true" type="button" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition">
                                Ajukan Sekarang
                            </button>
                        </div>
                    @endforelse
                </div>

                @if($myRequests->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700/80">
                        {{ $myRequests->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- Modal Ajukan Kategori Baru (HR) -->
        <div x-show="proposeModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-xs transition-opacity" @click="proposeModalOpen = false"></div>

                <div class="relative bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden z-10 text-slate-900 dark:text-slate-100">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700 mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white">Ajukan Kategori Bidang Baru</h3>
                        </div>
                        <button @click="proposeModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.category-requests.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Usulan Kategori <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required placeholder="Misal: Hukum & Legal Korporat" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Spesialisasi / Contoh Posisi Terkait</label>
                            <input type="text" name="subtext" placeholder="Misal: Legal Specialist, Corporate Lawyer, Contract Manager" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alasan / Urgensi Penambahan Kategori <span class="text-rose-500">*</span></label>
                            <textarea name="request_reason" rows="3" required placeholder="Jelaskan kebutuhan lowongan perusahaan Anda yang belum terakomodasi di 8 kategori standar..." class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-700">
                            <button @click="proposeModalOpen = false" type="button" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold rounded-xl text-xs transition">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-xs transition">Kirim Pengajuan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
