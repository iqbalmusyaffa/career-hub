<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-900 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-bookmark text-slate-700"></i> {{ __('Lowongan Tersimpan') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 shadow-2xs text-xs font-bold">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-2xs sm:rounded-2xl border border-slate-200 p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 pb-4 border-b border-slate-200 gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Koleksi Lowongan Favorit Anda</h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Lamar kembali atau pantau tanggal batas akhir lowongan yang Anda simpan.</p>
                    </div>
                    <span class="px-3 py-1 bg-slate-100 text-slate-800 text-xs font-bold rounded-lg border border-slate-300 w-fit">
                        {{ $savedJobs->total() }} Tersimpan
                    </span>
                </div>

                @if($savedJobs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($savedJobs as $job)
                            <div class="bg-white rounded-2xl shadow-2xs border border-slate-200 hover:border-slate-400 transition-all duration-200 flex flex-col group h-full relative overflow-hidden">
                                <div class="p-6 flex-grow flex flex-col">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white font-bold text-base flex items-center justify-center border border-slate-900">
                                            {{ strtoupper(substr($job->title, 0, 1)) }}
                                        </div>
                                        <form action="{{ route('jobs.bookmark', $job->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" title="Hapus dari simpanan" class="p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition border border-slate-200">
                                                <i class="fa-solid fa-bookmark text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <a href="{{ route('jobs.show', $job->id) }}" class="group-hover:text-slate-700 transition">
                                        <h4 class="text-base font-bold text-slate-900 mb-2 leading-snug line-clamp-2">{{ $job->title }}</h4>
                                    </a>
                                    
                                    <div class="mb-3">
                                        <span class="inline-flex items-center text-3xs font-bold text-slate-700 uppercase tracking-wider bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">
                                            🏢 {{ $job->division }}
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap items-center text-xs text-slate-500 mb-4 gap-x-4 gap-y-1.5 font-medium">
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-location-dot text-slate-500"></i>
                                            <span>{{ $job->location }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-laptop-code text-slate-500"></i>
                                            <span>{{ ucfirst($job->work_type) }}</span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-slate-600 text-xs leading-relaxed line-clamp-3 mb-4 flex-grow">
                                        {{ Str::limit(strip_tags($job->description), 100) }}
                                    </p>
                                </div>
                                
                                <div class="px-5 py-4 border-t border-slate-200 bg-slate-50/50 rounded-b-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <span class="text-3xs uppercase tracking-wider text-slate-400 font-bold block">Gaji Offer</span>
                                        <span class="text-xs font-bold text-slate-900 block truncate">{{ $job->salary ?? 'Negosiasi' }}</span>
                                    </div>
                                    <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition shrink-0 w-full sm:w-auto text-center border border-slate-900 shadow-2xs">
                                        Lamar / Detail &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $savedJobs->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-14 h-14 bg-slate-100 text-slate-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-xl font-bold border border-slate-200">
                            <i class="fa-solid fa-bookmark"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">Belum ada lowongan tersimpan</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">Jelajahi lowongan pekerjaan yang tersedia dan tekan ikon simpan untuk menyimpannya.</p>
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition shadow-2xs border border-slate-900">
                            Cari Lowongan Sekarang
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
