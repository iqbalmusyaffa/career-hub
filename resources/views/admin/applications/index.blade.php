<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Daftar Lamaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Kelola Data Pelamar</h3>
                            <p class="text-xs text-gray-500">Total {{ method_exists($applications, 'total') ? $applications->total() : $applications->count() }} lamaran terdaftar</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.applications.export.csv') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                📊 Export Excel / CSV
                            </a>
                            <a href="{{ route('admin.applications.export.pdf') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                📄 Export PDF Report
                            </a>
                        </div>
                    </div>

                    <!-- DataTables Filter & Search Bar -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-200/80">
                        <form method="GET" action="{{ route('admin.applications.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                            <div>
                                <label class="block font-bold text-gray-600 uppercase mb-1">Cari Pelamar / Posisi</label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, posisi..." class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block font-bold text-gray-600 uppercase mb-1">Status Lamaran</label>
                                <select name="status" class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Semua Status Lamaran</option>
                                    @foreach(\App\Enums\ApplicationStatus::cases() as $st)
                                        <option value="{{ $st->value }}" {{ request('status') == $st->value ? 'selected' : '' }}>{{ $st->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-gray-600 uppercase mb-1">Baris per Halaman</label>
                                <select name="per_page" onchange="this.form.submit()" class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris per Halaman</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris per Halaman</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris per Halaman</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris per Halaman</option>
                                </select>
                            </div>
                            <div class="flex items-end gap-2">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition">
                                    <i class="fa-solid fa-filter mr-1"></i> Filter
                                </button>
                                <a href="{{ route('admin.applications.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-4 rounded-xl text-xs transition text-center">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Pelamar</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Posisi Dilamar</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Match Score</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Tanggal Melamar</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Status</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($applications as $app)
                                    @php
                                        $matchScore = $app->job ? $app->job->calculateMatchScore($app->user->candidateProfile) : 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-4">
                                            <div class="font-bold text-gray-900">{{ $app->user->name ?? 'Kandidat' }}</div>
                                            <div class="text-xs text-gray-500">{{ $app->user->email ?? '-' }}</div>
                                        </td>
                                        <td class="p-4">
                                            <div class="font-medium text-blue-700">{{ $app->job->title ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $app->job->division ?? '' }}</div>
                                        </td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $matchScore >= 70 ? 'bg-green-100 text-green-800' : ($matchScore >= 40 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700') }}">
                                                🎯 {{ $matchScore }}% Match
                                            </span>
                                        </td>
                                        <td class="p-4 text-sm text-gray-700">
                                            {{ $app->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="p-4">
                                            @if($app->status == 'pending')
                                                <span class="px-2.5 py-1 bg-yellow-50 text-yellow-700 text-xs font-semibold rounded-full border border-yellow-100">Menunggu</span>
                                            @elseif($app->status == 'reviewed')
                                                <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full border border-blue-100">Direview</span>
                                            @elseif($app->status == 'interview')
                                                <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full border border-amber-100">Wawancara</span>
                                            @elseif($app->status == 'accepted')
                                                <span class="px-2.5 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full border border-green-100">Diterima</span>
                                            @elseif($app->status == 'rejected')
                                                <span class="px-2.5 py-1 bg-red-50 text-red-700 text-xs font-semibold rounded-full border border-red-100">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            <a href="{{ route('admin.applications.show', $app->encrypted_id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-sm font-medium transition shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-8 text-center text-gray-500">
                                            Belum ada lamaran yang masuk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(method_exists($applications, 'hasPages') && $applications->hasPages())
                        <div class="mt-6 border-t border-gray-100 pt-4">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
