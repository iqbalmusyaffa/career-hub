<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-users-viewfinder text-blue-600"></i> Daftar & Pipeline Pelamar Kerja
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh data lamaran masuk dan pergerakan kandidat.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.applications.export.csv') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-extrabold transition shadow-2xs border border-emerald-600">
                    <i class="fa-solid fa-file-excel"></i> Export Excel / CSV
                </a>
                <a href="{{ route('admin.applications.export.pdf') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-extrabold transition shadow-2xs border border-rose-600">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF Report
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- DataTables Filter & Search Bar -->
            <div class="bg-white p-6 rounded-3xl shadow-2xs border border-slate-200/80">
                <form method="GET" action="{{ route('admin.applications.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-3xs tracking-wider">Cari Pelamar / Posisi</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, email, posisi..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-medium">
                    </div>

                    @if(auth()->user()->hasRole('Super Admin'))
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-3xs tracking-wider">Filter Perusahaan (PT)</label>
                        <input type="text" name="company_name" value="{{ request('company_name') }}" placeholder="Misal: PT TechNova..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-medium">
                    </div>
                    @endif

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-3xs tracking-wider">Filter Jurusan Pendidikan</label>
                        <x-indonesia-majors-select name="major" value="{{ request('major') }}" placeholder="Cari / Pilih Jurusan..." />
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-3xs tracking-wider">Status Tahapan Lamaran</label>
                        <select name="status" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 font-semibold">
                            <option value="">Semua Status Tahapan</option>
                            @foreach(\App\Enums\ApplicationStatus::cases() as $st)
                                <option value="{{ $st->value }}" {{ request('status') == $st->value ? 'selected' : '' }}>{{ $st->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-extrabold py-2.5 px-4 rounded-xl text-xs transition border border-slate-900">
                            <i class="fa-solid fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.applications.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-4 rounded-xl text-xs transition text-center border border-slate-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Form Bulk Action Pipeline -->
            <form id="bulkForm" action="{{ route('admin.applications.bulk-status') }}" method="POST">
                @csrf
                
                <!-- Floating Bulk Action Toolbar (Tampil Saat Ada Checkbox Dicentang) -->
                <div id="bulkToolbar" class="hidden sticky top-4 z-40 bg-slate-900 text-white p-4 rounded-2xl shadow-xl flex items-center justify-between gap-4 mb-4 border border-slate-800 animate-fadeIn">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-blue-600 text-white font-black rounded-lg text-xs" id="selectedCount">0 Dicentang</span>
                        <span class="text-xs font-bold text-slate-300">Pilih Aksi Massal Pelamar:</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <select name="status" required class="bg-slate-800 text-white border-slate-700 rounded-xl text-xs font-bold p-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Status Baru --</option>
                            <option value="reviewing">🔍 Berkas Ditinjau (Reviewing)</option>
                            <option value="test">📝 Loloskan ke Tes Online</option>
                            <option value="interview">📅 Panggil Interview</option>
                            <option value="accepted">🎉 Diterima Kerja (Hired)</option>
                            <option value="rejected">❌ Tolak Lamaran (Rejected)</option>
                        </select>
                        <button type="submit" onclick="return confirm('Ubah status seluruh pelamar terpilih?');" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl shadow-md transition flex items-center gap-2">
                            <i class="fa-solid fa-bolt"></i> Terapkan Aksi Massal
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-2xs border border-slate-200/80 overflow-hidden">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200/80 text-slate-500 text-3xs font-extrabold uppercase tracking-wider">
                                        <th class="p-4 w-10">
                                            <input type="checkbox" id="selectAll" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                        </th>
                                        <th class="p-4">Kandidat Pelamar</th>
                                        <th class="p-4">Posisi Lowongan Target</th>
                                        <th class="p-4">Match Score</th>
                                        <th class="p-4">Tanggal Dilamar</th>
                                        <th class="p-4">Status Tahapan</th>
                                        <th class="p-4 text-right">Aksi Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs">
                                    @forelse($applications as $app)
                                        @php
                                            $matchScore = $app->job ? $app->job->calculateMatchScore($app->user->candidateProfile) : 0;
                                            $stVal = is_object($app->status) ? $app->status->value : (string)$app->status;
                                        @endphp
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="p-4">
                                                <input type="checkbox" name="application_ids[]" value="{{ $app->getHashedId() }}" class="app-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                            </td>
                                            <td class="p-4">
                                                <div class="font-extrabold text-slate-900 text-sm">{{ $app->user->name ?? 'Kandidat' }}</div>
                                                <div class="text-3xs text-slate-400 font-medium mt-0.5">{{ $app->user->email ?? '-' }}</div>
                                            </td>
                                            <td class="p-4">
                                                <div class="font-bold text-blue-700">{{ $app->job->title ?? '-' }}</div>
                                                <div class="text-3xs text-slate-400 font-medium">{{ $app->job->division ?? '' }}</div>
                                            </td>
                                            <td class="p-4">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-3xs font-black {{ $matchScore >= 70 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : ($matchScore >= 40 ? 'bg-blue-50 text-blue-800 border border-blue-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                                                    🎯 {{ $matchScore }}% Match
                                                </span>
                                            </td>
                                            <td class="p-4 text-slate-600 font-medium">
                                                {{ $app->created_at->format('d M Y, H:i') }}
                                            </td>
                                            <td class="p-4">
                                                <span class="px-2.5 py-1 text-3xs font-black rounded-lg uppercase border 
                                                    {{ $stVal === 'accepted' || $stVal === 'hired' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : '' }}
                                                    {{ $stVal === 'interview' ? 'bg-amber-50 text-amber-800 border-amber-200' : '' }}
                                                    {{ $stVal === 'test' ? 'bg-purple-50 text-purple-800 border-purple-200' : '' }}
                                                    {{ $stVal === 'reviewing' || $stVal === 'reviewed' ? 'bg-blue-50 text-blue-800 border-blue-200' : '' }}
                                                    {{ $stVal === 'pending' ? 'bg-slate-100 text-slate-800 border-slate-200' : '' }}
                                                    {{ $stVal === 'rejected' ? 'bg-rose-50 text-rose-800 border-rose-200' : '' }}">
                                                    {{ is_object($app->status) ? $app->status->label() : strtoupper($app->status) }}
                                                </span>
                                            </td>
                                            <td class="p-4 text-right">
                                                <a href="{{ route('admin.applications.show', $app->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition shadow-2xs">
                                                    <i class="fa-solid fa-eye text-3xs text-amber-400"></i> Detail Lamaran
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="p-8 text-center text-slate-400 font-medium">
                                                Belum ada pelamar kerja yang memenuhi kriteria filter.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($applications, 'hasPages') && $applications->hasPages())
                            <div class="mt-6 border-t border-slate-100 pt-4">
                                {{ $applications->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const selectAll = document.getElementById('selectAll');
                    const checkboxes = document.querySelectorAll('.app-checkbox');
                    const bulkToolbar = document.getElementById('bulkToolbar');
                    const selectedCount = document.getElementById('selectedCount');

                    function updateToolbar() {
                        const checked = document.querySelectorAll('.app-checkbox:checked');
                        if (checked.length > 0) {
                            bulkToolbar.classList.remove('hidden');
                            selectedCount.textContent = checked.length + ' Dicentang';
                        } else {
                            bulkToolbar.classList.add('hidden');
                        }
                    }

                    if (selectAll) {
                        selectAll.addEventListener('change', function() {
                            checkboxes.forEach(cb => cb.checked = this.checked);
                            updateToolbar();
                        });
                    }

                    checkboxes.forEach(cb => {
                        cb.addEventListener('change', updateToolbar);
                    });
                });
            </script>

        </div>
    </div>
</x-app-layout>
