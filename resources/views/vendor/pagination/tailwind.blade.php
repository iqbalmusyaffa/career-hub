@if ($paginator->hasPages() || $paginator->total() > 0)
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        
        <!-- Showing Results Info -->
        <div class="text-slate-500 dark:text-slate-400 text-xs">
            @if ($paginator->total() > 0)
                <span>Menampilkan</span>
                <span class="font-bold text-slate-900 dark:text-white">{{ $paginator->firstItem() }}</span>
                <span>hingga</span>
                <span class="font-bold text-slate-900 dark:text-white">{{ $paginator->lastItem() }}</span>
                <span>dari</span>
                <span class="font-bold text-slate-900 dark:text-white">{{ $paginator->total() }}</span>
                <span>data</span>
            @else
                <span>Total <span class="font-bold text-slate-900 dark:text-white">0</span> data</span>
            @endif
        </div>

        @if ($paginator->hasPages())
            <!-- Pagination Controls -->
            <div class="flex items-center gap-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="px-2.5 py-1.5 text-xs font-medium text-slate-300 dark:text-slate-600 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-lg cursor-not-allowed inline-flex items-center gap-1">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}" class="px-2.5 py-1.5 text-xs font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-lg transition shadow-2xs inline-flex items-center gap-1">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                <div class="flex items-center gap-1">
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true" class="px-2 py-1 text-slate-400 text-xs">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-600 text-white font-bold text-xs shadow-xs">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="w-7 h-7 flex items-center justify-center rounded-lg bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-medium transition shadow-2xs">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}" class="px-2.5 py-1.5 text-xs font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-lg transition shadow-2xs inline-flex items-center gap-1">
                        <span class="hidden sm:inline">Berikutnya</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                @else
                    <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="px-2.5 py-1.5 text-xs font-medium text-slate-300 dark:text-slate-600 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-lg cursor-not-allowed inline-flex items-center gap-1">
                        <span class="hidden sm:inline">Berikutnya</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </span>
                @endif
            </div>
        @endif

    </nav>
@endif
