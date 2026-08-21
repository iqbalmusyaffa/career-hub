<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-blue-600"></i> Audit Logs (Jejak Aktivitas Sistem)
                </h2>
                <p class="text-xs text-gray-500 mt-1">Catatan riwayat aktivitas penting pengguna, moderasi akun, dan perubahan sistem Web Karir.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-xl text-sm transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- DataTables Filter & Search Bar -->
            <div class="bg-white p-6 rounded-3xl shadow-2xs border border-gray-100">
                <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 text-xs">
                    <div class="md:col-span-2">
                        <label class="block text-2xs font-bold text-gray-400 uppercase mb-1">Cari Aktivitas / Nama / Email / IP</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama pengguna, kata kunci deskripsi, atau IP Address..." class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-2xs font-bold text-gray-400 uppercase mb-1">Filter Tipe Aksi</label>
                        <select name="action" class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Tipe Aksi</option>
                            @foreach($actionTypes as $type)
                                <option value="{{ $type }}" {{ request('action') == $type ? 'selected' : '' }}>{{ strtoupper(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-2xs font-bold text-gray-400 uppercase mb-1">Baris per Halaman</label>
                        <select name="per_page" onchange="this.form.submit()" class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-bold">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris per Halaman</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris per Halaman</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris per Halaman</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris per Halaman</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-md flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Audit Logs Table -->
            <div class="bg-white rounded-3xl shadow-2xs border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-2xs font-bold text-gray-400 uppercase tracking-wider">
                                <th class="p-4 pl-6">Waktu & IP Address</th>
                                <th class="p-4">Pengguna (Actor)</th>
                                <th class="p-4">Aksi / Event</th>
                                <th class="p-4 pr-6">RincianDeskripsi Aktivitas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="p-4 pl-6 whitespace-nowrap">
                                        <div class="font-bold text-gray-900">{{ $log->created_at->format('d M Y, H:i:s') }}</div>
                                        <div class="text-2xs text-gray-400 font-mono mt-0.5">{{ $log->ip_address ?? '127.0.0.1' }} • {{ $log->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        @if($log->user)
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs">
                                                    {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-900">{{ $log->user->name }}</div>
                                                    <div class="text-2xs text-gray-400">{{ $log->user->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Sistem / Pengunjung</span>
                                        @endif
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        @php
                                            $badgeClass = match($log->action) {
                                                'user_login' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'job_created' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                'application_status_updated' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                'interview_scheduled' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                'offer_letter_created' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                'user_suspended' => 'bg-red-50 text-red-700 border-red-200',
                                                'company_verified' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'smtp_updated' => 'bg-gray-100 text-gray-800 border-gray-300',
                                                default => 'bg-gray-50 text-gray-700 border-gray-200',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 text-2xs font-bold rounded-lg border uppercase tracking-wider {{ $badgeClass }}">
                                            {{ str_replace('_', ' ', $log->action) }}
                                        </span>
                                    </td>
                                    <td class="p-4 pr-6">
                                        <div class="font-medium text-gray-800">{{ $log->description }}</div>
                                        @if($log->user_agent)
                                            <div class="text-3xs text-gray-400 truncate max-w-xs mt-0.5" title="{{ $log->user_agent }}">
                                                {{ $log->user_agent }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-400 text-xs">
                                        Belum ada catatan aktivitas sistem (Audit Log).
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
