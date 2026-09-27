<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1 font-medium">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                    <span>/</span>
                    <span>Kelola Platform</span>
                    <span>/</span>
                    <span class="text-blue-600 dark:text-blue-400 font-bold">Kategori Bidang Lowongan</span>
                </div>
                <h2 class="font-bold text-xl sm:text-2xl text-slate-900 dark:text-white leading-tight flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-xs">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <span>Manajemen Kategori Lowongan</span>
                </h2>
            </div>
            
            <div class="flex items-center gap-2">
                <button @click="$dispatch('open-add-category-modal')" type="button" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Kategori Baru</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 dark:bg-slate-900 min-h-screen transition-colors" x-data="{
        activeTab: '{{ $tab ?? 'categories' }}',
        editModalOpen: false,
        addModalOpen: false,
        approveModalOpen: false,
        rejectModalOpen: false,
        selectedCategory: {},
        selectedRequest: {},
        rejectionReason: '',
        openEdit(cat) {
            this.selectedCategory = JSON.parse(JSON.stringify(cat));
            this.editModalOpen = true;
        },
        openApprove(req) {
            this.selectedRequest = JSON.parse(JSON.stringify(req));
            this.approveModalOpen = true;
        },
        openReject(req) {
            this.selectedRequest = JSON.parse(JSON.stringify(req));
            this.rejectionReason = '';
            this.rejectModalOpen = true;
        }
    }" @open-add-category-modal.window="addModalOpen = true">
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

            <!-- Stat Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0 border border-blue-100 dark:border-blue-800">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Kategori</div>
                        <div class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mt-0.5">{{ $stats['total'] }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg shrink-0 border border-emerald-100 dark:border-emerald-800">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Kategori Aktif</div>
                        <div class="text-xl sm:text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $stats['active'] }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center text-lg shrink-0 border border-slate-200 dark:border-slate-600">
                        <i class="fa-solid fa-eye-slash"></i>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Nonaktif</div>
                        <div class="text-xl sm:text-2xl font-bold text-slate-700 dark:text-slate-300 mt-0.5">{{ $stats['inactive'] }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs flex items-center gap-4 cursor-pointer hover:border-amber-400 dark:hover:border-amber-500 transition" @click="activeTab = 'requests'">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg shrink-0 border border-amber-100 dark:border-amber-800 relative">
                        <i class="fa-solid fa-inbox"></i>
                        @if($stats['pending'] > 0)
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-rose-500 rounded-full animate-ping"></span>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-rose-500 rounded-full border-2 border-white dark:border-slate-800"></span>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Pengajuan HR</div>
                        <div class="text-xl sm:text-2xl font-bold text-amber-600 dark:text-amber-400 mt-0.5">{{ $stats['pending'] }} Menunggu</div>
                    </div>
                </div>
            </div>

            <!-- Main Container & Tabs -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs overflow-hidden">
                <!-- Tab Navigation & Filter -->
                <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-700/80 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 bg-slate-50/50 dark:bg-slate-900/50">
                    <div class="flex items-center gap-1.5 p-1 bg-slate-200/70 dark:bg-slate-900/80 rounded-xl border border-slate-300/60 dark:border-slate-700/80 max-w-fit">
                        <button @click="activeTab = 'categories'" type="button" class="px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2" :class="activeTab === 'categories' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                            <i class="fa-solid fa-list-check text-xs"></i>
                            <span>Master Kategori ({{ $stats['total'] }})</span>
                        </button>
                        <button @click="activeTab = 'requests'" type="button" class="px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 relative" :class="activeTab === 'requests' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                            <i class="fa-solid fa-hand-holding-hand text-xs"></i>
                            <span>Pengajuan dari HR</span>
                            @if($stats['pending'] > 0)
                                <span class="px-1.5 py-0.2 rounded-full bg-amber-500 text-white text-[10px] font-black">{{ $stats['pending'] }}</span>
                            @endif
                        </button>
                    </div>

                    <!-- Search Filter (for Categories Tab) -->
                    <form x-show="activeTab === 'categories'" method="GET" action="{{ route('admin.categories.index') }}" class="flex items-center gap-2">
                        <input type="hidden" name="tab" value="categories">
                        <div class="relative w-full sm:w-64">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / deskripsi..." class="w-full pl-9 pr-3 py-2 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                        @if($search)
                            <a href="{{ route('admin.categories.index') }}" class="px-3 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition">Reset</a>
                        @endif
                    </form>
                </div>

                <!-- Tab 1: Master Categories Table -->
                <div x-show="activeTab === 'categories'">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
                                    <th class="py-3.5 px-4 w-12 text-center">Urutan</th>
                                    <th class="py-3.5 px-4">Nama Kategori & Subteks</th>
                                    <th class="py-3.5 px-4">Ikon & Tampilan Card</th>
                                    <th class="py-3.5 px-4 text-center">Jumlah Lowongan</th>
                                    <th class="py-3.5 px-4 text-center">Status</th>
                                    <th class="py-3.5 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                                @forelse($categories as $category)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition">
                                        <!-- Sort Order -->
                                        <td class="py-4 px-4 text-center font-bold text-slate-400 dark:text-slate-500">
                                            #{{ $category->sort_order }}
                                        </td>

                                        <!-- Name & Subtext -->
                                        <td class="py-4 px-4">
                                            <div class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
                                                <span>{{ $category->name }}</span>
                                                @if($category->status === 'rejected')
                                                    <span class="px-2 py-0.5 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10px] font-bold border border-rose-200 dark:border-rose-800">Ditolak</span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 max-w-md">{{ $category->subtext ?: ($category->description ?: 'Tidak ada deskripsi singkat.') }}</div>
                                            <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 font-mono">slug: {{ $category->slug }}</div>
                                        </td>

                                        <!-- Icon & Badge Preview -->
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm font-bold border border-blue-100 dark:border-slate-600 shrink-0">
                                                    <i class="{{ $category->icon ?: 'fa-solid fa-briefcase' }}"></i>
                                                </div>
                                                <div class="text-[11px] text-slate-600 dark:text-slate-300 font-mono">
                                                    <div><span class="text-slate-400 dark:text-slate-500">icon:</span> {{ $category->icon ?: 'fa-solid fa-briefcase' }}</div>
                                                    <div><span class="text-slate-400 dark:text-slate-500">tema:</span> {{ $category->badge_color ?: 'blue' }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Jobs Count -->
                                        <td class="py-4 px-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $category->active_jobs_count > 0 ? 'bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400' }}">
                                                {{ $category->active_jobs_count }} Lowongan
                                            </span>
                                        </td>

                                        <!-- Active Status Toggle -->
                                        <td class="py-4 px-4 text-center">
                                            <form action="{{ route('admin.categories.toggle', $category) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" title="Klik untuk mengubah status aktif" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold transition {{ $category->is_active ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 dark:hover:bg-emerald-900/60' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-600 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                                                    <span class="w-2 h-2 rounded-full {{ $category->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                                    <span>{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                                </button>
                                            </form>
                                        </td>

                                        <!-- Actions -->
                                        <td class="py-4 px-4 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-2">
                                                <button @click="openEdit({{ json_encode($category) }})" type="button" class="p-2 text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg transition" title="Edit Kategori">
                                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                </button>

                                                @if($category->active_jobs_count === 0)
                                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->name }} secara permanen?');" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 text-slate-400 dark:text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/50 rounded-lg transition" title="Hapus Kategori">
                                                            <i class="fa-solid fa-trash text-xs"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-8 text-center text-slate-500 dark:text-slate-400">
                                            <i class="fa-solid fa-folder-open text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                                            <p class="font-medium text-xs">Belum ada kategori yang cocok dengan pencarian.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t border-slate-100 dark:border-slate-700/80">
                        {{ $categories->links() }}
                    </div>
                </div>

                <!-- Tab 2: HR Category Proposals -->
                <div x-show="activeTab === 'requests'" style="display: none;">
                    @if($pendingRequests->count() > 0)
                        <div class="divide-y divide-slate-100 dark:divide-slate-700/60">
                            @foreach($pendingRequests as $req)
                                <div class="p-5 sm:p-6 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                    <div class="space-y-1.5 flex-1 min-w-0">
                                        <div class="flex items-center gap-2.5 flex-wrap">
                                            <span class="px-2.5 py-0.5 rounded-md bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 font-bold text-[10px] uppercase tracking-wider border border-amber-200 dark:border-amber-800">
                                                <i class="fa-solid fa-clock"></i> Menunggu Persetujuan
                                            </span>
                                            <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $req->name }}</h3>
                                            <span class="text-xs text-slate-400 dark:text-slate-500 font-mono">({{ $req->slug }})</span>
                                        </div>

                                        @if($req->subtext)
                                            <p class="text-xs text-slate-600 dark:text-slate-300"><strong class="text-slate-800 dark:text-slate-200">Subteks Lowongan:</strong> {{ $req->subtext }}</p>
                                        @endif

                                        <div class="bg-amber-50/70 dark:bg-amber-950/30 p-3 rounded-xl border border-amber-200/60 dark:border-amber-800/60 text-xs text-amber-900 dark:text-amber-200 max-w-2xl mt-2">
                                            <div class="font-semibold text-[11px] text-amber-800 dark:text-amber-300 mb-0.5"><i class="fa-solid fa-comment-dots mr-1"></i> Alasan Pengajuan oleh HR:</div>
                                            <p class="leading-relaxed">{{ $req->request_reason ?: 'Tidak melampirkan alasan khusus.' }}</p>
                                        </div>

                                        <div class="flex items-center gap-4 text-[11px] text-slate-400 dark:text-slate-500 pt-1">
                                            <span><i class="fa-solid fa-user-tie text-slate-400 dark:text-slate-500 mr-1"></i> Diajukan oleh: <strong class="text-slate-700 dark:text-slate-300">{{ $req->requestedBy->name ?? 'HR Perusahaan' }}</strong> ({{ $req->requestedBy->email ?? '-' }})</span>
                                            <span><i class="fa-solid fa-calendar mr-1"></i> {{ $req->created_at->format('d M Y, H:i') }} WIB</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 self-end md:self-center shrink-0">
                                        <button @click="openApprove({{ json_encode($req) }})" type="button" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5">
                                            <i class="fa-solid fa-check text-xs"></i>
                                            <span>Setujui & Publikasikan</span>
                                        </button>
                                        <button @click="openReject({{ json_encode($req) }})" type="button" class="px-3.5 py-2 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                            <span>Tolak</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700/60 text-slate-400 dark:text-slate-500 flex items-center justify-center text-2xl mx-auto mb-3">
                                <i class="fa-solid fa-envelope-circle-check"></i>
                            </div>
                            <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200">Tidak Ada Pengajuan Menunggu</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">Semua usulan kategori baru dari HR dan Company Owner telah diproses dan disetujui.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Modal Tambah Kategori Baru -->
        <div x-show="addModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-xs transition-opacity" @click="addModalOpen = false"></div>

                <div class="relative bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden z-10 text-slate-900 dark:text-slate-100">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700 mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white">Tambah Kategori Baru</h3>
                        </div>
                        <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Kategori Bidang <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required placeholder="Misal: Keamanan Siber & Cloud" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Subteks / Spesialisasi Terkait</label>
                            <input type="text" name="subtext" placeholder="Misal: Ethical Hacker, SOC Analyst, Network Security" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium">
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Ditampilkan pada kartu kategori di halaman beranda sebagai keterangan tambahan.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Ikon FontAwesome</label>
                                <input type="text" name="icon" value="fa-solid fa-briefcase" placeholder="fa-solid fa-shield-halved" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Warna Badge / Tema</label>
                                <select name="badge_color" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium">
                                    <option value="blue">Blue (Biru Standar)</option>
                                    <option value="emerald">Emerald (Hijau Sukses)</option>
                                    <option value="indigo">Indigo (Ungu Biru)</option>
                                    <option value="purple">Purple (Ungu Kreatif)</option>
                                    <option value="amber">Amber (Kuning/Oranye)</option>
                                    <option value="sky">Sky (Biru Langit)</option>
                                    <option value="teal">Teal (Toska)</option>
                                    <option value="rose">Rose (Merah Muda)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor Urutan Tampil</label>
                                <input type="number" name="sort_order" value="9" min="0" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Status Langsung Aktif</label>
                                <select name="is_active" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium">
                                    <option value="1">Aktif (Tampil di Form Lowongan)</option>
                                    <option value="0">Nonaktif (Disembunyikan)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Lengkap (Opsional)</label>
                            <textarea name="description" rows="2" placeholder="Penjelasan bidang industri ini..." class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-700">
                            <button @click="addModalOpen = false" type="button" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold rounded-xl text-xs transition">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-xs transition">Simpan Kategori</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Kategori -->
        <div x-show="editModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-xs transition-opacity" @click="editModalOpen = false"></div>

                <div class="relative bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden z-10 text-slate-900 dark:text-slate-100">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700 mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white">Edit Kategori Bidang</h3>
                        </div>
                        <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <form :action="'/admin/categories/' + selectedCategory.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Kategori Bidang <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="selectedCategory.name" required class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Subteks / Spesialisasi</label>
                            <input type="text" name="subtext" x-model="selectedCategory.subtext" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Ikon FontAwesome</label>
                                <input type="text" name="icon" x-model="selectedCategory.icon" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Warna Tema</label>
                                <select name="badge_color" x-model="selectedCategory.badge_color" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium">
                                    <option value="blue">Blue</option>
                                    <option value="emerald">Emerald</option>
                                    <option value="indigo">Indigo</option>
                                    <option value="purple">Purple</option>
                                    <option value="amber">Amber</option>
                                    <option value="sky">Sky</option>
                                    <option value="teal">Teal</option>
                                    <option value="rose">Rose</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor Urutan</label>
                                <input type="number" name="sort_order" x-model="selectedCategory.sort_order" min="0" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Status Aktif</label>
                                <select name="is_active" x-model="selectedCategory.is_active" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium">
                                    <option :value="1">Aktif</option>
                                    <option :value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Lengkap</label>
                            <textarea name="description" x-model="selectedCategory.description" rows="2" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500 font-medium"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-700">
                            <button @click="editModalOpen = false" type="button" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold rounded-xl text-xs transition">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-xs transition">Perbarui Kategori</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Setujui Pengajuan Kategori HR -->
        <div x-show="approveModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-xs transition-opacity" @click="approveModalOpen = false"></div>

                <div class="relative bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden z-10 text-slate-900 dark:text-slate-100">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700 mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white">Setujui Pengajuan Kategori</h3>
                        </div>
                        <button @click="approveModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <form :action="'/admin/categories/' + selectedRequest.id + '/approve'" method="POST" class="space-y-4">
                        @csrf
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs text-emerald-800 dark:text-emerald-300">
                            Setelah disetujui, kategori ini akan otomatis berstatus <strong>Aktif</strong> dan dapat dipilih oleh semua HR saat membuat lowongan baru.
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Kategori Resmi <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="selectedRequest.name" required class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-emerald-500 focus:border-emerald-500 font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Subteks / Spesialisasi</label>
                            <input type="text" name="subtext" x-model="selectedRequest.subtext" placeholder="Misal: Spesialisasi bidang ini..." class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-emerald-500 focus:border-emerald-500 font-medium">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Ikon FontAwesome</label>
                                <input type="text" name="icon" x-model="selectedRequest.icon" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Warna Tema</label>
                                <select name="badge_color" x-model="selectedRequest.badge_color" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium">
                                    <option value="blue">Blue</option>
                                    <option value="emerald">Emerald</option>
                                    <option value="indigo">Indigo</option>
                                    <option value="purple">Purple</option>
                                    <option value="amber">Amber</option>
                                    <option value="sky">Sky</option>
                                    <option value="teal">Teal</option>
                                    <option value="rose">Rose</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor Urutan Tampil</label>
                            <input type="number" name="sort_order" value="9" min="0" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium">
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-700">
                            <button @click="approveModalOpen = false" type="button" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold rounded-xl text-xs transition">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-xs transition">Setujui & Publikasikan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Tolak Pengajuan Kategori HR -->
        <div x-show="rejectModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-xs transition-opacity" @click="rejectModalOpen = false"></div>

                <div class="relative bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden z-10 text-slate-900 dark:text-slate-100">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700 mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center text-xs font-bold">
                                <i class="fa-solid fa-xmark"></i>
                            </div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white">Tolak Pengajuan Kategori</h3>
                        </div>
                        <button @click="rejectModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <form :action="'/admin/categories/' + selectedRequest.id + '/reject'" method="POST" class="space-y-4">
                        @csrf
                        <p class="text-xs text-slate-600 dark:text-slate-300">
                            Anda akan menolak pengajuan kategori <strong class="text-slate-900 dark:text-white" x-text="selectedRequest.name"></strong>. Berikan alasan penolakan agar pemohon dapat mengetahuinya:
                        </p>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alasan Penolakan <span class="text-rose-500">*</span></label>
                            <textarea name="rejection_reason" rows="3" required placeholder="Misal: Kategori ini dapat digabung ke dalam kategori Teknologi & IT..." class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-rose-500 focus:border-rose-500 font-medium"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-700">
                            <button @click="rejectModalOpen = false" type="button" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold rounded-xl text-xs transition">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-xs transition">Tolak Pengajuan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
