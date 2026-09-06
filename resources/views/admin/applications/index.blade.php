<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/70">
                        <i class="fa-solid fa-layer-group text-[10px]"></i>
                        Talent Acquisition ATS
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <span class="text-xs font-medium text-slate-500">Pipeline Pelamar</span>
                </div>
                <h2 class="font-bold text-xl text-slate-900 tracking-tight">
                    Data Pelamar & Tahapan Rekrutmen
                </h2>
            </div>
            
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.applications.export.csv') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition border border-slate-300 shadow-2xs hover:border-slate-400">
                    <i class="fa-solid fa-file-csv text-emerald-600 text-sm"></i>
                    <span>Ekspor CSV</span>
                </a>
                <a href="{{ route('admin.applications.export.pdf') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition border border-slate-300 shadow-2xs hover:border-slate-400">
                    <i class="fa-solid fa-file-pdf text-rose-600 text-sm"></i>
                    <span>Unduh PDF</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            @if(session('success'))
                <div class="p-3.5 bg-emerald-50/90 border border-emerald-200 text-emerald-800 rounded-xl flex items-center justify-between text-xs font-medium shadow-2xs">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-sm">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @php
                $currentStatus = request('status', '');
                $counts = $statusCounts ?? [
                    'all' => method_exists($applications, 'total') ? $applications->total() : $applications->count(),
                    'pending' => 0,
                    'test' => 0,
                    'interview' => 0,
                    'accepted' => 0,
                    'rejected' => 0,
                ];

                $tabs = [
                    ['key' => '', 'label' => 'Semua', 'count' => $counts['all'] ?? 0, 'dot' => 'bg-slate-400'],
                    ['key' => 'pending', 'label' => 'Menunggu Review', 'count' => $counts['pending'] ?? 0, 'dot' => 'bg-amber-500'],
                    ['key' => 'test', 'label' => 'Tes Online', 'count' => $counts['test'] ?? 0, 'dot' => 'bg-purple-500'],
                    ['key' => 'interview', 'label' => 'Wawancara', 'count' => $counts['interview'] ?? 0, 'dot' => 'bg-indigo-500'],
                    ['key' => 'accepted', 'label' => 'Diterima (Hired)', 'count' => $counts['accepted'] ?? 0, 'dot' => 'bg-emerald-500'],
                    ['key' => 'rejected', 'label' => 'Ditolak', 'count' => $counts['rejected'] ?? 0, 'dot' => 'bg-rose-500'],
                ];
            @endphp

            <!-- Interactive Stage Tabs (Ashby / Linear Style) -->
            <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-1.5 overflow-x-auto">
                <nav class="flex items-center gap-1 min-w-max">
                    @foreach($tabs as $tab)
                        @php
                            $isActive = ($tab['key'] === '' && empty($currentStatus)) || ($tab['key'] !== '' && $currentStatus === $tab['key']);
                            $urlParams = request()->except(['status', 'page']);
                            if ($tab['key'] !== '') {
                                $urlParams['status'] = $tab['key'];
                            }
                            $tabUrl = route('admin.applications.index', $urlParams);
                        @endphp
                        <a href="{{ $tabUrl }}" 
                           class="flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold transition {{ $isActive ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                            <span class="w-2 h-2 rounded-full {{ $tab['dot'] }}"></span>
                            <span>{{ $tab['label'] }}</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600 border border-slate-200/60' }}">
                                {{ number_format($tab['count']) }}
                            </span>
                        </a>
                    @endforeach
                </nav>
            </div>

            <!-- Integrated Search & Filter Command Bar -->
            <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-4">
                <form method="GET" action="{{ route('admin.applications.index') }}" id="filterForm">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif

                    <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                        <!-- Search Input -->
                        <div class="relative flex-1">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Cari nama pelamar, email, atau posisi pekerjaan..." 
                                   class="w-full pl-9 pr-8 py-2 bg-slate-50/50 hover:bg-white focus:bg-white border-slate-200 focus:border-blue-500 rounded-lg text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:ring-1 focus:ring-blue-500 transition shadow-2xs">
                            @if(request('search'))
                                <a href="{{ route('admin.applications.index', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </a>
                            @endif
                        </div>

                        <!-- Dropdown Filters Group -->
                        <div class="flex flex-wrap items-center gap-2.5">
                            @if(auth()->user()->hasRole('Super Admin'))
                                <div class="w-44">
                                    <input type="text" 
                                           name="company_name" 
                                           value="{{ request('company_name') }}" 
                                           placeholder="Perusahaan..." 
                                           class="w-full px-3 py-2 bg-slate-50/50 hover:bg-white focus:bg-white border-slate-200 focus:border-blue-500 rounded-lg text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:ring-1 focus:ring-blue-500 transition shadow-2xs">
                                </div>
                            @endif

                            <div class="w-48">
                                <x-indonesia-majors-select name="major" value="{{ request('major') }}" placeholder="Semua Jurusan" />
                            </div>

                            <div class="w-28">
                                <select name="per_page" onchange="document.getElementById('filterForm').submit()" class="w-full px-2.5 py-2 bg-slate-50/50 hover:bg-white focus:bg-white border-slate-200 focus:border-blue-500 rounded-lg text-xs font-medium text-slate-700 focus:ring-1 focus:ring-blue-500 transition shadow-2xs">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris</option>
                                    <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25 Baris</option>
                                    <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50 Baris</option>
                                    <option value="100" {{ request('per_page', 100) == 100 ? 'selected' : '' }}>100 Baris</option>
                                </select>
                            </div>

                            <button type="submit" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-xs transition flex items-center gap-1.5 shadow-2xs">
                                <i class="fa-solid fa-filter text-xs"></i>
                                <span>Filter</span>
                            </button>

                            @if(request()->hasAny(['search', 'major', 'company_name', 'status']))
                                <a href="{{ route('admin.applications.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 font-medium rounded-lg text-xs transition border border-slate-200" title="Reset Semua Filter">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Active Filter Chips -->
                    @if(request()->hasAny(['search', 'major', 'company_name']))
                        <div class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-slate-100 text-xs">
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Filter Aktif:</span>
                            
                            @if(request('search'))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200/70 rounded-md text-[11px] font-medium">
                                    <span>Pencarian: "{{ request('search') }}"</span>
                                    <a href="{{ route('admin.applications.index', request()->except('search')) }}" class="hover:text-blue-900"><i class="fa-solid fa-xmark text-[10px]"></i></a>
                                </span>
                            @endif

                            @if(request('major'))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200/70 rounded-md text-[11px] font-medium">
                                    <span>Jurusan: {{ request('major') }}</span>
                                    <a href="{{ route('admin.applications.index', request()->except('major')) }}" class="hover:text-blue-900"><i class="fa-solid fa-xmark text-[10px]"></i></a>
                                </span>
                            @endif

                            @if(request('company_name'))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200/70 rounded-md text-[11px] font-medium">
                                    <span>Perusahaan: {{ request('company_name') }}</span>
                                    <a href="{{ route('admin.applications.index', request()->except('company_name')) }}" class="hover:text-blue-900"><i class="fa-solid fa-xmark text-[10px]"></i></a>
                                </span>
                            @endif

                            <a href="{{ route('admin.applications.index', request()->only('status')) }}" class="text-[11px] font-semibold text-rose-600 hover:text-rose-800 ml-1">
                                Hapus Semua
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Form Bulk Action Pipeline -->
            <form id="bulkForm" action="{{ route('admin.applications.bulk-status') }}" method="POST">
                @csrf
                
                <!-- Floating Bulk Action Toolbar (appears when items are checked) -->
                <div id="bulkToolbar" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-slate-900/95 backdrop-blur-md text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-4 border border-slate-800 animate-in fade-in slide-in-from-bottom-4 duration-200">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-blue-600 text-white font-bold rounded text-xs" id="selectedCount">0</span>
                        <span class="text-xs font-medium text-slate-200">Pelamar Dipilih</span>
                    </div>

                    <div class="h-4 w-px bg-slate-700"></div>

                    <div class="flex items-center gap-2">
                        <select name="status" required class="bg-slate-800 text-slate-100 border-slate-700 rounded-lg text-xs font-medium py-1.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Ubah Status ke --</option>
                            <option value="reviewing">Berkas Ditinjau (Reviewing)</option>
                            <option value="test">Loloskan ke Tes Online</option>
                            <option value="interview">Panggil Wawancara</option>
                            <option value="accepted">Diterima Kerja (Hired)</option>
                            <option value="rejected">Tolak Lamaran (Rejected)</option>
                        </select>
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memperbarui status pelamar yang dipilih?');" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold rounded-lg shadow-xs transition flex items-center gap-1.5">
                            <i class="fa-solid fa-check text-xs"></i> Terapkan
                        </button>
                    </div>

                    <button type="button" id="cancelBulk" class="text-slate-400 hover:text-white text-xs font-medium ml-1">
                        Batal
                    </button>
                </div>

                <!-- Main Data Table -->
                <div class="bg-white rounded-xl shadow-2xs border border-slate-200/90 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 text-[11px] font-semibold uppercase tracking-wider">
                                    <th class="py-3 px-4 w-10 text-center whitespace-nowrap">
                                        <input type="checkbox" id="selectAll" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                    </th>
                                    <th class="py-3 px-4 whitespace-nowrap">Kandidat Pelamar</th>
                                    <th class="py-3 px-4">Posisi & Lowongan</th>
                                    <th class="py-3 px-4 whitespace-nowrap">Kesesuaian Profil</th>
                                    <th class="py-3 px-4 whitespace-nowrap">Tanggal Melamar</th>
                                    <th class="py-3 px-4 whitespace-nowrap">Tahapan Rekrutmen</th>
                                    <th class="py-3 px-4 text-right whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @forelse($applications as $app)
                                    @php
                                        $matchScore = $app->job ? $app->job->calculateMatchScore($app->user->candidateProfile) : 0;
                                        $stVal = is_object($app->status) ? $app->status->value : (string)$app->status;
                                        $candidateName = $app->user->name ?? 'Kandidat';
                                        $candidateInitial = strtoupper(substr($candidateName, 0, 1));
                                        $profile = $app->user->candidateProfile ?? null;
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                            <input type="checkbox" name="application_ids[]" value="{{ $app->getHashedId() }}" class="app-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                        </td>
                                        
                                        <!-- Candidate Column -->
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-xs shrink-0 border border-slate-200">
                                                    {{ $candidateInitial }}
                                                </div>
                                                <div class="min-w-0">
                                                    <a href="{{ route('admin.applications.show', $app->id) }}" class="font-semibold text-slate-900 group-hover:text-blue-600 transition truncate block">
                                                        {{ $candidateName }}
                                                    </a>
                                                    <div class="flex items-center gap-2 text-[11px] text-slate-500 font-normal truncate mt-0.5">
                                                        <span>{{ $app->user->email ?? '-' }}</span>
                                                        @if($profile && $profile->phone)
                                                            <span class="text-slate-300">•</span>
                                                            <span>{{ $profile->phone }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Position & Job Column -->
                                        <td class="py-3.5 px-4">
                                            <div class="font-medium text-slate-900 truncate max-w-xs">
                                                {{ $app->job->title ?? 'Lowongan Tidak Ditemukan' }}
                                            </div>
                                            <div class="flex items-center gap-1.5 text-[11px] text-slate-500 mt-0.5 whitespace-nowrap">
                                                @if(auth()->user()->hasRole('Super Admin') && $app->job && $app->job->company_name)
                                                    <span class="font-medium text-slate-700">{{ $app->job->company_name }}</span>
                                                    <span class="text-slate-300">•</span>
                                                @endif
                                                @if($app->job && $app->job->division)
                                                    <span>{{ $app->job->division }}</span>
                                                    <span class="text-slate-300">•</span>
                                                @endif
                                                <span class="capitalize">{{ $app->job->work_type ?? 'Full-time' }}</span>
                                            </div>
                                        </td>

                                        <!-- Match Score Column -->
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @php
                                                $barColor = match(true) {
                                                    $matchScore >= 75 => 'bg-emerald-500',
                                                    $matchScore >= 50 => 'bg-blue-500',
                                                    $matchScore >= 30 => 'bg-amber-500',
                                                    default => 'bg-slate-300',
                                                };
                                                $textColor = match(true) {
                                                    $matchScore >= 75 => 'text-emerald-700 font-semibold',
                                                    $matchScore >= 50 => 'text-blue-700 font-semibold',
                                                    $matchScore >= 30 => 'text-amber-700 font-medium',
                                                    default => 'text-slate-500',
                                                };
                                            @endphp
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden shrink-0 border border-slate-200/60">
                                                    <div class="h-full {{ $barColor }} rounded-full" style="width: {{ min(100, max(5, $matchScore)) }}%"></div>
                                                </div>
                                                <span class="text-[11px] {{ $textColor }} tabular-nums">{{ $matchScore }}%</span>
                                            </div>
                                        </td>

                                        <!-- Date Column -->
                                        <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">
                                            <div class="font-medium text-slate-800">{{ $app->created_at->format('d M Y') }}</div>
                                            <div class="text-[11px] text-slate-400 font-normal">{{ $app->created_at->format('H:i') }} WIB</div>
                                        </td>

                                        <!-- Stage Status Badge Column -->
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @php
                                                $statusConfig = match($stVal) {
                                                    'accepted', 'hired' => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200/90', 'dot' => 'bg-emerald-500', 'label' => 'Diterima (Hired)'],
                                                    'offered' => ['bg' => 'bg-teal-50 text-teal-800 border-teal-200/90', 'dot' => 'bg-teal-500', 'label' => 'Penawaran (Offering)'],
                                                    'interview', 'interview_hr' => ['bg' => 'bg-indigo-50 text-indigo-800 border-indigo-200/90', 'dot' => 'bg-indigo-500', 'label' => 'Wawancara HR'],
                                                    'interview_user' => ['bg' => 'bg-purple-50 text-purple-800 border-purple-200/90', 'dot' => 'bg-purple-500', 'label' => 'Wawancara User'],
                                                    'test' => ['bg' => 'bg-violet-50 text-violet-800 border-violet-200/90', 'dot' => 'bg-violet-500', 'label' => 'Tes Online'],
                                                    'screening' => ['bg' => 'bg-sky-50 text-sky-800 border-sky-200/90', 'dot' => 'bg-sky-500', 'label' => 'Screening HR'],
                                                    'reviewing', 'reviewed' => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200/90', 'dot' => 'bg-blue-500', 'label' => 'Review Berkas'],
                                                    'processing' => ['bg' => 'bg-slate-100 text-slate-700 border-slate-200/90', 'dot' => 'bg-slate-500', 'label' => 'Diproses'],
                                                    'background_check' => ['bg' => 'bg-cyan-50 text-cyan-800 border-cyan-200/90', 'dot' => 'bg-cyan-500', 'label' => 'Background Check'],
                                                    'rejected' => ['bg' => 'bg-rose-50 text-rose-800 border-rose-200/90', 'dot' => 'bg-rose-500', 'label' => 'Ditolak'],
                                                    default => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200/90', 'dot' => 'bg-amber-500', 'label' => 'Menunggu Review'],
                                                };
                                                $statusLabel = is_object($app->status) && method_exists($app->status, 'label') ? $app->status->label() : ($statusConfig['label'] ?? ucfirst($stVal));
                                            @endphp
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold rounded-full border shadow-2xs whitespace-nowrap {{ $statusConfig['bg'] }}">
                                                <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $statusConfig['dot'] }}"></span>
                                                <span class="whitespace-nowrap">{{ $statusLabel }}</span>
                                            </span>
                                        </td>

                                        <!-- Action Column -->
                                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                            <a href="{{ route('admin.applications.show', $app->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-blue-600 bg-slate-50 hover:bg-blue-50/80 border border-slate-200 hover:border-blue-200 rounded-lg transition shadow-2xs whitespace-nowrap">
                                                <span>Tinjau</span>
                                                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400 group-hover:text-blue-600 transition"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-16 px-4 text-center">
                                            <div class="max-w-sm mx-auto space-y-3">
                                                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto border border-slate-200">
                                                    <i class="fa-solid fa-inbox text-xl"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-slate-800 text-sm">Tidak Ada Berkas Pelamar</h4>
                                                    <p class="text-xs text-slate-500 mt-0.5">Belum ada kandidat yang sesuai dengan filter pencarian ini.</p>
                                                </div>
                                                @if(request()->hasAny(['search', 'major', 'company_name', 'status']))
                                                    <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-semibold transition">
                                                        <i class="fa-solid fa-rotate-left text-xs"></i>
                                                        <span>Reset Filter</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($applications, 'hasPages') && $applications->hasPages())
                        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const selectAll = document.getElementById('selectAll');
                    const checkboxes = document.querySelectorAll('.app-checkbox');
                    const bulkToolbar = document.getElementById('bulkToolbar');
                    const selectedCount = document.getElementById('selectedCount');
                    const cancelBulk = document.getElementById('cancelBulk');

                    function updateToolbar() {
                        const checked = document.querySelectorAll('.app-checkbox:checked');
                        if (checked.length > 0) {
                            bulkToolbar.classList.remove('hidden');
                            selectedCount.textContent = checked.length;
                        } else {
                            bulkToolbar.classList.add('hidden');
                            if (selectAll) selectAll.checked = false;
                        }
                    }

                    if (selectAll) {
                        selectAll.addEventListener('change', function() {
                            checkboxes.forEach(cb => cb.checked = this.checked);
                            updateToolbar();
                        });
                    }

                    checkboxes.forEach(cb => {
                        cb.addEventListener('change', function() {
                            if (selectAll && !this.checked) {
                                selectAll.checked = false;
                            }
                            updateToolbar();
                        });
                    });

                    if (cancelBulk) {
                        cancelBulk.addEventListener('click', function() {
                            checkboxes.forEach(cb => cb.checked = false);
                            if (selectAll) selectAll.checked = false;
                            updateToolbar();
                        });
                    }
                });
            </script>

        </div>
    </div>
</x-app-layout>

