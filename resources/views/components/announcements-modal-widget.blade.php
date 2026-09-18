@props([
    'announcements' => null,
])

@php
    $items = $announcements ?? \App\Models\SystemAnnouncement::getActiveAnnouncementsForUser(auth()->user());
@endphp

@if($items && $items->count() > 0)
    <div x-data="{
        showModal: false,
        selectedDetail: null,
        openModal() {
            this.showModal = true;
            this.selectedDetail = null;
        },
        closeModal() {
            this.showModal = false;
            this.selectedDetail = null;
        },
        viewDetail(ann) {
            this.selectedDetail = ann;
        },
        formatContent(text) {
            if (!text) return '';
            let formatted = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\*(.*?)\*/g, '<strong class=\'text-slate-900 dark:text-white font-bold\'>$1</strong>')
                .replace(/(https?:\/\/[^\s]+)/g, '<a href=\'$1\' target=\'_blank\' rel=\'noopener noreferrer\' class=\'text-blue-600 dark:text-blue-400 font-semibold underline break-all hover:text-blue-800 dark:hover:text-blue-300 inline-flex items-center gap-1\'><span>$1</span> <i class=\'fa-solid fa-arrow-up-right-from-square text-[10px]\'></i></a>')
                .replace(/\n/g, '<br>');
            return formatted;
        },
        extractUrl(text) {
            if (!text) return null;
            let match = text.match(/(https?:\/\/[^\s]+)/);
            return match ? match[0] : null;
        }
    }">
        
        <!-- 1. NOTIFICATION BANNER STRIP (SESUAI REFERENSI GAMBAR) -->
        <div @click="openModal()" 
             class="bg-[#FEF3E8] dark:bg-amber-950/40 border border-[#FED7AA]/80 dark:border-amber-900/60 rounded-2xl px-4 py-3 sm:px-5 sm:py-3.5 flex items-center justify-between gap-4 shadow-xs hover:bg-[#FEEFD8] dark:hover:bg-amber-950/60 transition cursor-pointer group">
            
            <div class="flex items-center gap-3 min-w-0">
                <!-- Orange Circular Exclamation Icon -->
                <div class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-2xs">
                    !
                </div>
                <!-- Banner Text -->
                <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">
                    {{ $items->count() }} pengumuman &bull; {{ $items->count() }} baru
                </span>
            </div>

            <!-- Action Link -->
            <div class="shrink-0">
                <span class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white underline underline-offset-2 hover:text-amber-700 dark:hover:text-amber-300 transition">
                    Lihat pengumuman
                </span>
            </div>
        </div>

        <!-- 2. POPUP MODAL DAFTAR PENGUMUMAN (SESUAI TAMPILAN KARTU) -->
        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-md flex items-center justify-center p-4 sm:p-6" 
             style="display: none;">
            
            <div @click.outside="closeModal()" class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-800 relative text-left my-8">
                
                <!-- Modal Header -->
                <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/70 dark:bg-slate-950/50">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs shadow-2xs border border-blue-200/60 dark:border-blue-900/60">
                            <i class="fa-solid fa-bullhorn"></i>
                        </span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">Pengumuman Terbaru</h3>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 font-normal">Informasi & siaran resmi untuk peserta</p>
                        </div>
                    </div>
                    
                    <button type="button" @click="closeModal()" class="w-8 h-8 rounded-full bg-slate-200/70 dark:bg-slate-800/80 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center text-xs transition cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Modal Content Area (Scrollable) -->
                <div class="p-5 sm:p-6 space-y-4 max-h-[70vh] overflow-y-auto scrollbar-thin">
                    
                    <!-- Single Detail View if selected -->
                    <template x-if="selectedDetail">
                        <div class="space-y-4">
                            <button type="button" @click="selectedDetail = null" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                                <span>Kembali ke semua pengumuman</span>
                            </button>

                            <div class="p-4 sm:p-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 space-y-3">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[10px] uppercase font-bold px-2.5 py-0.5 rounded-md"
                                          :class="{
                                              'bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-300': selectedDetail.type === 'info',
                                              'bg-amber-100 text-amber-700 dark:bg-amber-900/60 dark:text-amber-300': selectedDetail.type === 'warning',
                                              'bg-rose-100 text-rose-700 dark:bg-rose-900/60 dark:text-rose-300': selectedDetail.type === 'danger',
                                              'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300': selectedDetail.type === 'success'
                                          }"
                                          x-text="selectedDetail.type">
                                    </span>
                                    <span class="text-[11px] text-slate-400" x-text="selectedDetail.created_at"></span>
                                </div>

                                <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white leading-snug" x-text="selectedDetail.title"></h4>

                                <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 text-xs sm:text-sm text-slate-700 dark:text-slate-200 leading-relaxed font-normal whitespace-pre-line"
                                     x-html="formatContent(selectedDetail.content)">
                                </div>

                                <template x-if="extractUrl(selectedDetail.content)">
                                    <div class="pt-1">
                                        <a :href="extractUrl(selectedDetail.content)" target="_blank" rel="noopener noreferrer" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                            <i class="fa-brands fa-whatsapp text-sm"></i>
                                            <span>Buka Tautan / Gabung Saluran &rarr;</span>
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- List of All Announcements -->
                    <template x-if="!selectedDetail">
                        <div class="space-y-3.5">
                            @foreach($items as $ann)
                                @php
                                    $isInfo = $ann->type === 'info';
                                    $isWarning = $ann->type === 'warning';
                                    $isDanger = $ann->type === 'danger';
                                    $isSuccess = $ann->type === 'success';

                                    $cardBorder = $isInfo ? 'border-blue-100 dark:border-blue-900/40 hover:border-blue-300' :
                                                 ($isWarning ? 'border-amber-100 dark:border-amber-900/40 hover:border-amber-300' :
                                                 ($isDanger ? 'border-rose-100 dark:border-rose-900/40 hover:border-rose-300' :
                                                 'border-emerald-100 dark:border-emerald-900/40 hover:border-emerald-300'));

                                    $iconBg = $isInfo ? 'bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/60 dark:border-blue-900/60' :
                                             ($isWarning ? 'bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200/60 dark:border-amber-900/60' :
                                             ($isDanger ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200/60 dark:border-rose-900/60' :
                                             'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-900/60'));

                                    $iconFa = $isInfo ? 'fa-circle-info' :
                                             ($isWarning ? 'fa-triangle-exclamation' :
                                             ($isDanger ? 'fa-circle-exclamation' : 'fa-circle-check'));

                                    $annJson = json_encode([
                                        'id' => $ann->id,
                                        'title' => $ann->title,
                                        'content' => $ann->content,
                                        'type' => $ann->type,
                                        'created_at' => $ann->created_at ? $ann->created_at->translatedFormat('d F Y, H:i') . ' WIB' : now()->translatedFormat('d F Y') . ' WIB',
                                    ]);
                                @endphp

                                <div @click="viewDetail({{ $annJson }})"
                                     class="p-4 sm:p-5 rounded-2xl border {{ $cardBorder }} bg-white dark:bg-slate-900/90 shadow-2xs hover:shadow-md transition cursor-pointer group space-y-2.5">
                                    
                                    <div class="flex items-start gap-3">
                                        <!-- Top Left Icon Badge -->
                                        <div class="w-8 h-8 rounded-xl {{ $iconBg }} flex items-center justify-center text-xs shrink-0 group-hover:scale-105 transition-transform">
                                            <i class="fa-solid {{ $iconFa }}"></i>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition leading-snug">
                                                {{ $ann->title }}
                                            </h4>
                                            <p class="text-[10px] text-slate-400 mt-0.5">
                                                {{ $ann->created_at ? $ann->created_at->translatedFormat('d M Y') : '' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Content Preview -->
                                    <div class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-normal whitespace-pre-line pl-11">
                                        {!! nl2br(e(Str::limit($ann->content, 200))) !!}
                                    </div>

                                    <div class="pt-1 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[11px] font-semibold text-blue-600 dark:text-blue-400 pl-11">
                                        <span class="flex items-center gap-1 group-hover:underline">
                                            <span>Baca selengkapnya</span>
                                            <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                        </span>
                                        <span class="text-[10px] uppercase font-bold text-slate-400">
                                            {{ $ann->type }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="p-4 bg-slate-50 dark:bg-slate-950/60 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                    <button type="button" @click="closeModal()" class="py-2 px-5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs rounded-xl transition cursor-pointer">
                        Tutup
                    </button>
                </div>

            </div>
        </div>

    </div>
@endif

