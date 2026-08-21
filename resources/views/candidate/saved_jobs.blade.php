<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-bookmark text-amber-500"></i> {{ __('Lowongan Tersimpan') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-sm text-sm font-semibold">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 pb-4 border-b border-gray-100 gap-4">
                    <div>
                        <h3 class="text-xl font-black text-gray-900">Koleksi Lowongan Favorit Anda</h3>
                        <p class="text-xs text-gray-500 font-medium mt-1">Lamar kembali atau pantau tanggal batas akhir lowongan yang Anda simpan.</p>
                    </div>
                    <span class="px-3.5 py-1.5 bg-amber-50 text-amber-700 text-xs font-black rounded-full border border-amber-200/60 w-fit">
                        ⭐ {{ $savedJobs->total() }} Tersimpan
                    </span>
                </div>

                @if($savedJobs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($savedJobs as $job)
                            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-200 transition-all duration-300 flex flex-col group h-full relative overflow-hidden">
                                <div class="h-2 bg-gradient-to-r from-amber-400 to-amber-600"></div>

                                <div class="p-6 flex-grow flex flex-col">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-black text-lg flex items-center justify-center shadow-md">
                                            {{ strtoupper(substr($job->title, 0, 1)) }}
                                        </div>
                                        <form action="{{ route('jobs.bookmark', $job->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" title="Hapus dari simpanan" class="p-2 rounded-xl text-amber-500 hover:bg-amber-50 transition border border-amber-200">
                                                <i class="fa-solid fa-bookmark text-base"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <a href="{{ route('jobs.show', $job->id) }}" class="group-hover:text-blue-600 transition">
                                        <h4 class="text-base font-black text-gray-900 mb-2 leading-snug line-clamp-2">{{ $job->title }}</h4>
                                    </a>
                                    
                                    <div class="mb-3">
                                        <span class="inline-flex items-center text-2xs font-extrabold text-blue-700 uppercase tracking-wider bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100">
                                            🏢 {{ $job->division }}
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap items-center text-xs text-gray-500 mb-4 gap-x-4 gap-y-1.5 font-medium">
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-location-dot text-blue-500"></i>
                                            <span>{{ $job->location }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-laptop-code text-emerald-500"></i>
                                            <span>{{ ucfirst($job->work_type) }}</span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-600 text-xs leading-relaxed line-clamp-3 mb-4 flex-grow">
                                        {{ Str::limit(strip_tags($job->description), 100) }}
                                    </p>
                                </div>
                                
                                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/80 rounded-b-3xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <span class="text-[10px] uppercase tracking-wider text-gray-400 font-extrabold block">Gaji Offer</span>
                                        <span class="text-xs font-black text-gray-900 block truncate">{{ $job->salary ?? 'Negosiasi' }}</span>
                                    </div>
                                    <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-sm hover:shadow transition shrink-0 w-full sm:w-auto text-center">
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
                        <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                            <i class="fa-solid fa-bookmark"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 mb-1">Belum ada lowongan tersimpan</h3>
                        <p class="text-xs text-gray-500 max-w-sm mx-auto mb-6">Jelajahi lowongan pekerjaan yang tersedia dan tekan ikon bintang/bookmark untuk menyimpannya.</p>
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-2xl shadow-md transition">
                            🔍 Cari Lowongan Sekarang
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
