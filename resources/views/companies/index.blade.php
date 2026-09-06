<x-public-layout>
    <div class="py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200 pb-4">
                <div>
                    <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider block">Mitra Rekrutmen</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mt-0.5">
                        Direktori Perusahaan Terverifikasi
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5 font-normal">Jelajahi perusahaan terpercaya dan lihat seluruh lowongan kerja yang sedang dibuka.</p>
                </div>
            </div>

            <!-- Search & Filter Card -->
            <div class="bg-white rounded-2xl p-4 shadow-xs border border-slate-200/80">
                <form action="{{ route('companies.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="relative flex-1 w-full">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama perusahaan atau industri..." class="w-full pl-10 pr-4 py-2 text-xs text-slate-900 border border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 font-medium placeholder:text-slate-400">
                    </div>
                    <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-filter text-xs"></i> Cari Perusahaan
                    </button>
                    @if(request('search'))
                        <a href="{{ route('companies.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition border border-slate-300">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Companies List Grid -->
            @if(count($companies) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($companies as $comp)
                        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs hover:border-slate-300 hover:shadow-md transition flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="w-12 h-12 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold text-base border border-slate-800 shrink-0">
                                        {{ strtoupper(substr($comp->name, 0, 2)) }}
                                    </div>
                                    @if($comp->is_verified)
                                        <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-800 text-xs font-semibold rounded-md border border-emerald-200 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> Terverifikasi
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <h3 class="font-bold text-slate-900 text-base hover:text-blue-600 transition">
                                        <a href="{{ route('companies.show', urlencode($comp->name)) }}">
                                            {{ $comp->name }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $comp->industry }} • {{ $comp->employee_count }}</p>
                                </div>

                                <div class="flex items-center gap-2 text-xs text-slate-500 font-normal">
                                    <i class="fa-solid fa-location-dot text-slate-400 text-xs"></i>
                                    <span>{{ $comp->location }}</span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 font-medium text-xs rounded-lg border border-slate-200">
                                    {{ $comp->jobs_count }} Lowongan Aktif
                                </span>
                                <a href="{{ route('companies.show', urlencode($comp->name)) }}" class="inline-flex items-center justify-center px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl shadow-xs transition shrink-0">
                                    Lihat Profil &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 space-y-3 shadow-xs">
                    <div class="w-14 h-14 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center text-xl mx-auto border border-slate-200">
                        <i class="fa-solid fa-building-circle-xmark"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Perusahaan Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-500">Tidak ada perusahaan yang sesuai dengan pencarian Anda saat ini.</p>
                </div>
            @endif

        </div>
    </div>
</x-public-layout>
