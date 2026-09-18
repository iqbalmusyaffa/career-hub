<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h2 class="font-bold text-xl sm:text-2xl text-slate-900 dark:text-white tracking-tight">
                        Kurikulum & Materi Pembelajaran
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 font-normal">
                    Kelola silabus modul pembelajaran, fokus kompetensi, dan link materi untuk peserta magang.
                </p>
            </div>
            <a href="{{ route('mentor.curriculums.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs transition flex items-center gap-2 self-start sm:self-auto">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Kurikulum Baru</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl flex items-center gap-3 text-emerald-800 dark:text-emerald-300 text-xs font-medium">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-xl flex items-center gap-3 text-rose-800 dark:text-rose-300 text-xs font-medium">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-base shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Search & Filter Bar -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-5 border border-slate-200 dark:border-slate-800 shadow-xs">
                <form method="GET" action="{{ route('mentor.curriculums.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                    <div class="sm:col-span-5 relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul kurikulum..." class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-1 focus:ring-blue-600 focus:border-blue-600">
                    </div>

                    <div class="sm:col-span-3">
                        <select name="job_id" class="w-full py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-blue-600 focus:border-blue-600">
                            <option value="">Semua Posisi</option>
                            @foreach($availableJobs as $job)
                                <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                                    {{ $job->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <select name="batch" class="w-full py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-blue-600 focus:border-blue-600">
                            <option value="">Semua Batch</option>
                            @foreach($availableBatches as $b)
                                <option value="{{ $b }}" {{ request('batch') == $b ? 'selected' : '' }}>
                                    {{ $b }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2 flex items-center gap-2">
                        <button type="submit" class="w-full py-2 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-xl text-xs font-semibold transition border border-slate-700">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'job_id', 'batch']))
                            <a href="{{ route('mentor.curriculums.index') }}" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition" title="Reset Filter">
                                <i class="fa-solid fa-rotate-left text-xs"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Curriculums List -->
            @if($curriculums->isEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-12 text-center border border-slate-200 dark:border-slate-800 shadow-xs space-y-4">
                    <div class="w-16 h-16 bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center text-2xl mx-auto border border-purple-200/50 dark:border-purple-800/40">
                        <i class="fa-solid fa-book-open-reader"></i>
                    </div>
                    <div class="space-y-1 max-w-md mx-auto">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Belum Ada Kurikulum & Materi</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Buat silabus pembelajaran bertahap (Bulan 1 s/d selesai), fokus kompetensi, dan link materi untuk membimbing peserta magang secara terarah.
                        </p>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('mentor.curriculums.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs transition">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>Buat Kurikulum Pertama</span>
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($curriculums as $item)
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 sm:p-6 shadow-xs flex flex-col justify-between hover:border-slate-300 dark:hover:border-slate-700 transition">
                            <div class="space-y-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if($item->job)
                                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/60 dark:border-blue-900/60">
                                                    {{ $item->job->title }}
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                                    Umum / Seluruh Posisi
                                                </span>
                                            @endif

                                            @if($item->batch)
                                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 border border-purple-200/60 dark:border-purple-900/60">
                                                    {{ $item->batch }}
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white pt-1">
                                            {{ $item->title }}
                                        </h3>
                                    </div>

                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <a href="{{ route('mentor.curriculums.edit', $item->id) }}" class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 border border-slate-200 dark:border-slate-700 text-xs transition" title="Edit Kurikulum">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('mentor.curriculums.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kurikulum ini? Seluruh modul materi di dalamnya juga akan terhapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-rose-50 dark:hover:bg-rose-950/60 text-slate-600 dark:text-slate-300 hover:text-rose-600 dark:hover:text-rose-400 border border-slate-200 dark:border-slate-700 text-xs transition" title="Hapus Kurikulum">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if($item->description)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed font-normal">
                                        {{ $item->description }}
                                    </p>
                                @endif

                                <!-- Modules Preview List -->
                                <div class="pt-2 border-t border-slate-100 dark:border-slate-800/80 space-y-2">
                                    <div class="flex items-center justify-between text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                        <span>Modul & Silabus Materi ({{ $item->materials->count() }})</span>
                                    </div>
                                    <div class="space-y-1.5">
                                        @forelse($item->materials->take(3) as $mat)
                                            <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-800 text-xs">
                                                <span class="font-medium text-slate-800 dark:text-slate-200 truncate pr-2">
                                                    {{ $mat->title }}
                                                </span>
                                                <div class="flex items-center gap-2 shrink-0">
                                                    @if(!empty($mat->learning_links))
                                                        <span class="inline-flex items-center gap-1 text-[10px] text-blue-600 dark:text-blue-400 font-bold bg-blue-50 dark:bg-blue-950/50 px-2 py-0.5 rounded">
                                                            <i class="fa-solid fa-link text-[9px]"></i> {{ count($mat->learning_links) }} Link
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-400 italic">Belum ada modul materi.</p>
                                        @endforelse

                                        @if($item->materials->count() > 3)
                                            <div class="text-[11px] text-slate-400 text-center font-medium pt-1">
                                                + {{ $item->materials->count() - 3 }} modul materi lainnya
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-400">
                                <span>Diperbarui: {{ $item->updated_at->diffForHumans() }}</span>
                                <a href="{{ route('mentor.curriculums.edit', $item->id) }}" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline flex items-center gap-1">
                                    <span>Kelola Modul</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-2">
                    {{ $curriculums->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
