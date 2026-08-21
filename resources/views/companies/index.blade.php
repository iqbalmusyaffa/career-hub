<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-building text-blue-600"></i> Direktori Perusahaan Terverifikasi
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Jelajahi perusahaan terpercaya dan lihat seluruh lowongan kerja yang sedang dibuka.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Search & Filter Card -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
                <form action="{{ route('companies.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="relative flex-1 w-full">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama perusahaan atau industri..." class="w-full pl-10 pr-4 py-2.5 text-xs text-slate-900 border border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 font-medium">
                    </div>
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-filter"></i> Cari Perusahaan
                    </button>
                    @if(request('search'))
                        <a href="{{ route('companies.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-xs rounded-xl transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Companies List Grid -->
            @if(count($companies) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($companies as $comp)
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-2xl flex items-center justify-center font-black text-2xl shadow-md shrink-0">
                                        🏢
                                    </div>
                                    @if($comp->is_verified)
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-3xs font-black rounded-full border border-emerald-200 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Terverifikasi
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <h3 class="font-extrabold text-slate-900 text-lg hover:text-blue-600 transition">
                                        <a href="{{ route('companies.show', urlencode($comp->name)) }}">
                                            {{ $comp->name }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-slate-500 font-semibold mt-0.5">{{ $comp->industry }} • {{ $comp->employee_count }}</p>
                                </div>

                                <div class="flex items-center gap-2 text-xs text-slate-600 font-medium">
                                    <i class="fa-solid fa-location-dot text-rose-500"></i>
                                    <span>{{ $comp->location }}</span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 font-extrabold text-xs rounded-xl border border-blue-100">
                                    💼 {{ $comp->jobs_count }} Lowongan Aktif
                                </span>
                                <a href="{{ route('companies.show', urlencode($comp->name)) }}" class="text-blue-600 hover:text-blue-800 font-extrabold text-xs flex items-center gap-1">
                                    Lihat Profil &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 space-y-3">
                    <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center text-2xl mx-auto">
                        <i class="fa-solid fa-building-circle-xmark"></i>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-800">Perusahaan Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-500">Tidak ada perusahaan yang sesuai dengan pencarian Anda saat ini.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
