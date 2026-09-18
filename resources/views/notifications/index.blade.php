<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-slate-900 dark:text-white leading-tight flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm shadow-2xs">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <span>{{ $isSuperAdmin && $scope === 'global' ? 'Pusat Notifikasi Global (Super Admin)' : 'Pusat Notifikasi' }}</span>
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    @if($isSuperAdmin && $scope === 'global')
                        Memantau seluruh lalu lintas notifikasi sistem, reminder presensi, pengajuan uang saku, dan verifikasi lintas role pengguna.
                    @else
                        Pantau pembaruan status lamaran, presensi harian, verifikasi logbook, dan rekomendasi uang saku Anda secara lengkap.
                    @endif
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                @if($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 dark:bg-blue-950/70 dark:hover:bg-blue-900/80 dark:text-blue-300 border border-blue-200 dark:border-blue-800 rounded-xl text-xs font-bold transition flex items-center gap-2 cursor-pointer shadow-2xs">
                            <i class="fa-solid fa-check-double"></i>
                            <span>Tandai Semua Dibaca</span>
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('notifications.clear-read') }}" onsubmit="return confirm('Apakah Anda yakin ingin membersihkan notifikasi yang telah dibaca?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold transition flex items-center gap-2 cursor-pointer shadow-2xs">
                        <i class="fa-solid fa-trash-can text-slate-400"></i>
                        <span>Bersihkan Terbaca</span>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-slate-50/50 dark:bg-slate-950/40 min-h-screen" x-data="{
        detailModalOpen: false,
        activeNotif: null,
        openDetail(notif) {
            this.activeNotif = notif;
            this.detailModalOpen = true;
            if (!notif.is_read) {
                fetch('/notifications/' + notif.id + '/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                notif.is_read = true;
            }
        }
    }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center justify-between gap-3 shadow-2xs text-xs font-bold animate-fade-in">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 text-sm">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            <!-- Super Admin Scope Switcher -->
            @if($isSuperAdmin)
                <div class="bg-blue-900/10 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800/80 rounded-2xl p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xs">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white">Mode Tampilan Notifikasi Super Admin</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Pilih antara melihat notifikasi global seluruh platform atau notifikasi personal akun admin.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 p-1 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                        <a href="{{ route('notifications.all', ['scope' => 'global']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $scope === 'global' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-blue-600' }}">
                            <i class="fa-solid fa-earth-americas"></i>
                            <span>Notifikasi Global Platform</span>
                        </a>
                        <a href="{{ route('notifications.all', ['scope' => 'personal']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $scope === 'personal' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-blue-600' }}">
                            <i class="fa-solid fa-user-shield"></i>
                            <span>Notifikasi Saya</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Filters & Search Bar Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-2xs space-y-4">
                
                <form method="GET" action="{{ route('notifications.all') }}" class="space-y-3">
                    <input type="hidden" name="scope" value="{{ $scope }}">

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <!-- Tabs -->
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 scrollbar-none text-xs font-semibold">
                            <a href="{{ route('notifications.all', ['scope' => $scope, 'role' => $roleFilter, 'status' => 'all', 'type' => $type, 'search' => $search]) }}" 
                               class="px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap {{ $status === 'all' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 font-bold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                <i class="fa-solid fa-layer-group text-[11px]"></i>
                                <span>Semua</span>
                                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'all' ? 'bg-slate-700 text-white dark:bg-slate-200 dark:text-slate-900' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">{{ $totalCount }}</span>
                            </a>

                            <a href="{{ route('notifications.all', ['scope' => $scope, 'role' => $roleFilter, 'status' => 'unread', 'type' => $type, 'search' => $search]) }}" 
                               class="px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap {{ $status === 'unread' ? 'bg-blue-600 text-white font-bold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                <i class="fa-solid fa-envelope text-[11px]"></i>
                                <span>Belum Dibaca</span>
                                @if($unreadCount > 0)
                                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-rose-500 text-white font-bold">{{ $unreadCount }}</span>
                                @endif
                            </a>

                            <a href="{{ route('notifications.all', ['scope' => $scope, 'role' => $roleFilter, 'status' => 'read', 'type' => $type, 'search' => $search]) }}" 
                               class="px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap {{ $status === 'read' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 font-bold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                <i class="fa-solid fa-envelope-open text-[11px]"></i>
                                <span>Telah Dibaca</span>
                            </a>

                            <div class="h-5 w-px bg-slate-200 dark:bg-slate-700 mx-1 hidden sm:block"></div>

                            <!-- Type Filters -->
                            <a href="{{ route('notifications.all', ['scope' => $scope, 'role' => $roleFilter, 'status' => $status, 'type' => 'info', 'search' => $search]) }}" 
                               class="px-3 py-1.5 rounded-lg text-xs transition whitespace-nowrap {{ $type === 'info' ? 'bg-blue-100 dark:bg-blue-950/80 text-blue-700 dark:text-blue-300 font-bold' : 'text-slate-500 hover:text-blue-600' }}">
                                <i class="fa-solid fa-circle-info text-blue-500 mr-1"></i> Info
                            </a>
                            <a href="{{ route('notifications.all', ['scope' => $scope, 'role' => $roleFilter, 'status' => $status, 'type' => 'warning', 'search' => $search]) }}" 
                               class="px-3 py-1.5 rounded-lg text-xs transition whitespace-nowrap {{ $type === 'warning' ? 'bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 font-bold' : 'text-slate-500 hover:text-amber-600' }}">
                                <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1"></i> Peringatan
                            </a>
                            <a href="{{ route('notifications.all', ['scope' => $scope, 'role' => $roleFilter, 'status' => $status, 'type' => 'success', 'search' => $search]) }}" 
                               class="px-3 py-1.5 rounded-lg text-xs transition whitespace-nowrap {{ $type === 'success' ? 'bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 font-bold' : 'text-slate-500 hover:text-emerald-600' }}">
                                <i class="fa-solid fa-circle-check text-emerald-500 mr-1"></i> Sukses
                            </a>
                        </div>

                        <!-- Search Input -->
                        <div class="flex items-center gap-2 w-full md:w-72">
                            <input type="hidden" name="status" value="{{ $status }}">
                            <input type="hidden" name="type" value="{{ $type }}">
                            <div class="relative w-full">
                                <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul / isi / nama..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 text-slate-800 dark:text-slate-200 pl-8 pr-3 py-2 focus:ring-blue-500 focus:border-blue-500 shadow-2xs">
                                <i class="fa-solid fa-search absolute left-2.5 top-2.5 text-xs text-slate-400"></i>
                            </div>
                            @if($search || $type !== 'all' || $status !== 'all' || ($roleFilter && $roleFilter !== 'all'))
                                <a href="{{ route('notifications.all', ['scope' => $scope]) }}" class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs transition shrink-0" title="Reset Filter">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Super Admin Role Filter Bar -->
                    @if($isSuperAdmin && $scope === 'global')
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 flex-wrap text-xs">
                            <span class="text-[11px] font-bold text-slate-400 uppercase mr-1">Filter Role Penerima:</span>
                            @foreach([
                                'all' => 'Semua Role',
                                'Candidate' => 'Candidate / Magang',
                                'Mentor' => 'Mentor Bimbingan',
                                'HR' => 'HR / Perusahaan',
                                'Company Owner' => 'Owner',
                                'Super Admin' => 'Super Admin'
                            ] as $rKey => $rLabel)
                                <a href="{{ route('notifications.all', ['scope' => $scope, 'role' => $rKey, 'status' => $status, 'type' => $type, 'search' => $search]) }}"
                                   class="px-2.5 py-1 rounded-lg text-xs font-semibold transition {{ $roleFilter === $rKey ? 'bg-blue-600 text-white shadow-2xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200' }}">
                                    {{ $rLabel }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                </form>
            </div>

            <!-- Notification Items List -->
            <div class="space-y-3">
                @forelse($notifications as $notif)
                    @php
                        $recipientRole = $notif->user?->roles?->pluck('name')->first() ?? 'User';
                        $notifData = [
                            'id' => $notif->id,
                            'title' => $notif->title,
                            'message' => $notif->message,
                            'link' => $notif->link,
                            'type' => $notif->type,
                            'is_read' => (bool)$notif->is_read,
                            'recipient_name' => $notif->user?->name ?? 'User',
                            'recipient_email' => $notif->user?->email ?? '',
                            'recipient_role' => $recipientRole,
                            'created_at_human' => $notif->created_at->diffForHumans(),
                            'created_at_full' => $notif->created_at->translatedFormat('d F Y, H:i:s \W\I\B'),
                        ];
                    @endphp
                    <div class="group bg-white dark:bg-slate-900 rounded-2xl border transition-all duration-200 p-4 sm:p-5 shadow-2xs hover:shadow-xs flex flex-col sm:flex-row items-start justify-between gap-4 {{ !$notif->is_read ? 'border-blue-300 dark:border-blue-700/80 bg-blue-50/20 dark:bg-blue-950/20 ring-1 ring-blue-100 dark:ring-blue-900/30' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700' }}">
                        
                        <div class="flex items-start gap-3.5 flex-1 min-w-0">
                            <!-- Icon Badge -->
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 text-sm shadow-2xs {{ $notif->type === 'success' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : ($notif->type === 'warning' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/70 dark:text-amber-300 border border-amber-200 dark:border-amber-800' : 'bg-blue-100 text-blue-700 dark:bg-blue-950/70 dark:text-blue-300 border border-blue-200 dark:border-blue-800') }}">
                                <i class="fa-solid {{ $notif->type === 'success' ? 'fa-circle-check' : ($notif->type === 'warning' ? 'fa-triangle-exclamation' : 'fa-circle-info') }}"></i>
                            </div>

                            <!-- Content Area -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h3 class="font-bold text-sm text-slate-900 dark:text-white leading-snug">
                                        {{ $notif->title }}
                                    </h3>
                                    
                                    @if(!$notif->is_read)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-600 text-white">
                                            Baru
                                        </span>
                                    @endif

                                    <!-- Super Admin Global: Show Target User & Role Badge -->
                                    @if($isSuperAdmin && $scope === 'global' && $notif->user)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            <i class="fa-solid fa-user text-[9px] text-blue-500"></i>
                                            <span>{{ $notif->user->name }}</span>
                                            <span class="px-1 py-0.2 rounded bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 text-[9px] font-bold">{{ $recipientRole }}</span>
                                        </span>
                                    @endif

                                    <span class="inline-flex items-center text-[11px] text-slate-400 dark:text-slate-500 font-medium ml-auto sm:ml-0" title="{{ $notif->created_at->translatedFormat('d F Y, H:i') }}">
                                        <i class="fa-regular fa-clock text-[10px] mr-1"></i>
                                        {{ $notif->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-normal whitespace-pre-line">
                                    {{ $notif->message }}
                                </p>

                                <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-2 flex items-center gap-2">
                                    <span>{{ $notif->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Controls -->
                        <div class="flex items-center gap-1.5 sm:self-center shrink-0 w-full sm:w-auto justify-end pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100 dark:border-slate-800">
                            
                            <!-- Lihat Detail Modal Trigger -->
                            <button type="button" 
                                    @click="openDetail({{ json_encode($notifData) }})"
                                    class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold transition flex items-center gap-1.5 cursor-pointer shadow-2xs"
                                    title="Baca rincian notifikasi selengkapnya">
                                <i class="fa-regular fa-eye text-slate-500"></i>
                                <span>Lihat Rincian</span>
                            </button>

                            @if($notif->link && $notif->link !== '#')
                                <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                                    @csrf
                                    <input type="hidden" name="redirect_to_link" value="1">
                                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-xs">
                                        <span>Buka Halaman</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </button>
                                </form>
                            @endif

                            @if(!$notif->is_read)
                                <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-800 transition cursor-pointer" title="Tandai telah dibaca">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}" onsubmit="return confirm('Hapus notifikasi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer" title="Hapus Notifikasi">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-12 text-center shadow-2xs">
                        <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-2xl mx-auto mb-4">
                            <i class="fa-regular fa-bell-slash"></i>
                        </div>
                        <h3 class="font-bold text-base text-slate-900 dark:text-white">Tidak ada notifikasi</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">
                            @if($search)
                                Tidak ditemukan notifikasi yang cocok dengan kata kunci "{{ $search }}".
                            @elseif($status === 'unread')
                                Semua notifikasi pada kategori ini sudah dibaca.
                            @else
                                Belum ada riwayat notifikasi untuk filter ini.
                            @endif
                        </p>
                        @if($search || $status !== 'all' || $type !== 'all' || ($roleFilter && $roleFilter !== 'all'))
                            <div class="mt-4">
                                <a href="{{ route('notifications.all', ['scope' => $scope]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 dark:bg-slate-700 text-white text-xs font-semibold hover:bg-slate-800 transition">
                                    <i class="fa-solid fa-rotate-left"></i>
                                    <span>Reset Filter</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Pagination Links -->
            @if($notifications->hasPages())
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-2xs">
                    {{ $notifications->links() }}
                </div>
            @endif

        </div>

        <!-- Detail Modal (Alpine.js) -->
        <div x-show="detailModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" 
                 @click="detailModalOpen = false"></div>

            <!-- Dialog Content -->
            <div class="min-h-full flex items-center justify-center p-4">
                <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 p-6 sm:p-7 overflow-hidden animate-scale-up"
                     @click.stop>
                    
                    <template x-if="activeNotif">
                        <div class="space-y-4">
                            <!-- Header -->
                            <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-base shrink-0"
                                         :class="{
                                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-300': activeNotif.type === 'success',
                                            'bg-amber-100 text-amber-800 dark:bg-amber-950/70 dark:text-amber-300': activeNotif.type === 'warning',
                                            'bg-blue-100 text-blue-700 dark:bg-blue-950/70 dark:text-blue-300': activeNotif.type === 'info'
                                         }">
                                        <i class="fa-solid" :class="{
                                            'fa-circle-check': activeNotif.type === 'success',
                                            'fa-triangle-exclamation': activeNotif.type === 'warning',
                                            'fa-circle-info': activeNotif.type === 'info'
                                        }"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-sm sm:text-base text-slate-900 dark:text-white" x-text="activeNotif.title"></h3>
                                        <span class="text-[11px] text-slate-400 dark:text-slate-500" x-text="activeNotif.created_at_full"></span>
                                    </div>
                                </div>
                                <button type="button" @click="detailModalOpen = false" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                    <i class="fa-solid fa-xmark text-sm"></i>
                                </button>
                            </div>

                            <!-- Super Admin: Recipient Metadata -->
                            <template x-if="activeNotif.recipient_name">
                                <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium">Penerima Notifikasi:</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                        <span x-text="activeNotif.recipient_name"></span>
                                        <span class="px-1.5 py-0.2 rounded bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 text-[10px] font-bold" x-text="activeNotif.recipient_role"></span>
                                    </span>
                                </div>
                            </template>

                            <!-- Body Text -->
                            <div class="py-2">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Isi Pesan Notifikasi:</label>
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 text-xs sm:text-sm text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line font-medium" x-text="activeNotif.message"></div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="flex items-center justify-between gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] text-slate-400" x-text="activeNotif.created_at_human"></span>

                                <div class="flex items-center gap-2">
                                    <button type="button" @click="detailModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                                        Tutup
                                    </button>
                                    
                                    <template x-if="activeNotif.link && activeNotif.link !== '#'">
                                        <a :href="activeNotif.link" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                                            <span>Buka Halaman Terkait</span>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                </div>
            </div>
        </div>

    </div>
</x-app-layout>
