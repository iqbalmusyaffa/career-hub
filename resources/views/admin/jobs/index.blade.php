<x-app-layout>
    <x-slot name="header">
        <div x-data class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-briefcase text-base"></i>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-slate-900 dark:text-white tracking-tight">
                        Kelola Lowongan Pekerjaan
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pantau status publikasi, program batch magang/kerja, tahapan tes asesmen, dan pelamar.</p>
                </div>
            </div>
            <button type="button" @click="$dispatch('open-create-job-modal')" onclick="window.dispatchEvent(new CustomEvent('open-create-job-modal'))" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs transition shadow-xs border border-blue-600 cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Lowongan Baru</span>
            </button>
        </div>
    </x-slot>

    <div x-data="jobsManager()" 
         @open-create-job-modal.window="openCreateModal()" 
         @keydown.escape.window="showCreateModal = false; showEditModal = false">
        <div class="py-6 bg-slate-50/60 dark:bg-slate-950 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                @if(session('success'))
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 rounded-xl flex items-center gap-3 text-xs font-medium shadow-xs">
                        <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 rounded-xl space-y-1 text-xs font-medium shadow-xs">
                        <div class="flex items-center gap-2 font-bold text-rose-900 dark:text-rose-100">
                            <i class="fa-solid fa-triangle-exclamation text-rose-600 dark:text-rose-400"></i>
                            <span>Terdapat kendala pada isian formulir:</span>
                        </div>
                        <ul class="list-disc list-inside pl-4 text-rose-700 dark:text-rose-300 space-y-0.5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Executive Quick Stats -->
                @php
                    $activeCount = $jobs->filter(function($j) {
                        $s = is_object($j->status) ? $j->status->value : (string)$j->status;
                        return $s === 'active';
                    })->count();
                    $closedCount = $jobs->filter(function($j) {
                        $s = is_object($j->status) ? $j->status->value : (string)$j->status;
                        return $s === 'closed';
                    })->count();
                    $totalApplicants = $jobs->sum('applications_count');
                @endphp
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Lowongan</p>
                            <p class="text-xl font-bold text-slate-900 dark:text-white mt-0.5">{{ method_exists($jobs, 'total') ? $jobs->total() : $jobs->count() }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900">
                            <i class="fa-solid fa-briefcase text-sm"></i>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Lowongan Aktif</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $activeCount }}</p>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Tayang</span>
                            </div>
                        </div>
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-900">
                            <i class="fa-solid fa-circle-check text-sm"></i>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Lowongan Ditutup</p>
                            <p class="text-xl font-bold text-slate-600 dark:text-slate-300 mt-0.5">{{ $closedCount }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center border border-slate-200 dark:border-slate-700">
                            <i class="fa-solid fa-folder-closed text-sm"></i>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Pelamar Masuk</p>
                            <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400 mt-0.5">{{ number_format($totalApplicants) }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-indigo-900">
                            <i class="fa-solid fa-users text-sm"></i>
                        </div>
                    </div>
                </div>

                <!-- Filter & Search Toolbar -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800">
                    <form method="GET" action="{{ route('admin.jobs.index') }}" class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 text-xs">
                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Cari Lowongan</label>
                                <div class="relative">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Posisi, divisi, lokasi..." class="w-full pl-9 pr-3 py-2 border-slate-300 dark:border-slate-700 rounded-lg text-xs focus:ring-blue-500 focus:border-blue-500 text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-950 placeholder:text-slate-400 font-medium">
                                </div>
                            </div>

                            @if(auth()->user()->hasRole('Super Admin'))
                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Filter Perusahaan</label>
                                <input type="text" name="company_name" value="{{ request('company_name') }}" placeholder="Nama perusahaan..." class="w-full px-3 py-2 border-slate-300 dark:border-slate-700 rounded-lg text-xs focus:ring-blue-500 focus:border-blue-500 text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-950 font-medium">
                            </div>
                            @endif

                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Syarat Jurusan</label>
                                <x-indonesia-majors-select name="major" value="{{ request('major') }}" placeholder="Pilih jurusan..." />
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Status Publikasi</label>
                                <select name="status" class="w-full px-3 py-2 border-slate-300 dark:border-slate-700 rounded-lg text-xs focus:ring-blue-500 focus:border-blue-500 font-medium text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-950">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif (Tayang)</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Baris per Halaman</label>
                                <select name="per_page" onchange="this.form.submit()" class="w-full px-3 py-2 border-slate-300 dark:border-slate-700 rounded-lg text-xs focus:ring-blue-500 focus:border-blue-500 font-medium text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-950">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris</option>
                                    <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25 Baris</option>
                                    <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50 Baris</option>
                                    <option value="100" {{ request('per_page', 100) == 100 ? 'selected' : '' }}>100 Baris</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <a href="{{ route('admin.jobs.index') }}" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg text-xs transition border border-slate-200 dark:border-slate-700">
                                Reset Filter
                            </a>
                            <button type="submit" class="px-4 py-2 bg-slate-900 dark:bg-blue-600 hover:bg-slate-800 dark:hover:bg-blue-700 text-white font-semibold rounded-lg text-xs transition border border-slate-900 dark:border-blue-600 flex items-center gap-1.5 shadow-xs">
                                <i class="fa-solid fa-filter text-xs"></i>
                                <span>Terapkan Filter</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Main Data Table -->
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 text-xs font-semibold uppercase tracking-wider">
                                    <th class="py-3.5 px-4">Posisi & Divisi</th>
                                    <th class="py-3.5 px-4">Batch / Periode</th>
                                    <th class="py-3.5 px-4">Lokasi Penempatan</th>
                                    <th class="py-3.5 px-4">Tipe Kerja</th>
                                    <th class="py-3.5 px-4">Status</th>
                                    <th class="py-3.5 px-4 text-center">Total Pelamar</th>
                                    <th class="py-3.5 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                                @forelse($jobs as $job)
                                    @php
                                        $jobData = [
                                            'id' => $job->id,
                                            'title' => $job->title,
                                            'division' => $job->division,
                                            'location' => $job->location,
                                            'google_maps_link' => $job->google_maps_link,
                                            'work_type' => $job->work_type,
                                            'experience_level' => $job->experience_level,
                                            'education_level' => $job->education_level,
                                            'major_requirement' => $job->major_requirement,
                                            'skills_required' => $job->skills_required,
                                            'gender_requirement' => $job->gender_requirement,
                                            'age_range' => $job->age_range,
                                            'salary' => $job->salary,
                                            'quota' => $job->quota,
                                            'batch' => $job->batch,
                                            'duration' => $job->duration,
                                            'start_date' => optional($job->start_date)->format('Y-m-d'),
                                            'deadline' => optional($job->deadline)->format('Y-m-d'),
                                            'description' => $job->description,
                                            'company_name' => $job->company_name,
                                            'requirements' => $job->requirements,
                                            'benefits' => $job->benefits,
                                            'status' => is_object($job->status) ? $job->status->value : (string)$job->status,
                                        ];
                                    @endphp
                                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition group">
                                        <td class="py-3.5 px-4">
                                            <a href="{{ route('admin.applications.index') }}?job_id={{ $job->id }}" class="font-bold text-slate-900 dark:text-white text-sm group-hover:text-blue-600 dark:group-hover:text-blue-400 transition block">
                                                {{ $job->title }}
                                            </a>
                                            <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                @if($job->division)
                                                    <span class="font-medium text-slate-600 dark:text-slate-300">{{ $job->division }}</span>
                                                    <span class="text-slate-300 dark:text-slate-600">•</span>
                                                @endif
                                                @if($job->salary)
                                                    <span class="text-emerald-700 dark:text-emerald-400 font-medium">{{ $job->salary }}</span>
                                                    <span class="text-slate-300 dark:text-slate-600">•</span>
                                                @endif
                                                <span>Dibuat {{ $job->created_at->format('d M Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @if($job->batch)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 shadow-2xs">
                                                    <i class="fa-solid fa-layer-group text-[10px]"></i>
                                                    <span>{{ $job->batch }}</span>
                                                </span>
                                                @if($job->duration)
                                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1">
                                                        <i class="fa-regular fa-clock text-[10px]"></i>
                                                        <span>{{ $job->duration }}</span>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-xs text-slate-400 italic">Reguler / Terbuka</span>
                                                @if($job->duration)
                                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                                        {{ $job->duration }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-700 dark:text-slate-300">
                                            <div class="flex items-center gap-1.5 font-medium">
                                                <i class="fa-solid fa-location-dot text-slate-400 text-xs"></i>
                                                <span>{{ $job->location }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @php
                                                $wt = strtolower($job->work_type);
                                                $badgeClass = match($wt) {
                                                    'remote' => 'bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-800',
                                                    'hybrid' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                                    'internship' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                                    'contract', 'part-time' => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                                                    default => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-[11px] font-semibold rounded-md border {{ $badgeClass }}">
                                                {{ ucfirst($job->work_type) }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @php
                                                $statusValue = is_object($job->status) ? $job->status->value : (string)$job->status;
                                            @endphp
                                            @if($statusValue === 'active')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-semibold rounded-full border border-emerald-200 dark:border-emerald-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    Aktif
                                                </span>
                                            @elseif($statusValue === 'inactive' || $statusValue === 'draft')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-[11px] font-semibold rounded-full border border-amber-200 dark:border-amber-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    Draf / Review
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[11px] font-semibold rounded-full border border-slate-200 dark:border-slate-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                    Ditutup
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <a href="{{ route('admin.applications.index') }}?job_id={{ $job->id }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 dark:bg-slate-800/80 hover:bg-blue-50 dark:hover:bg-blue-900/40 text-slate-700 dark:text-slate-200 hover:text-blue-700 dark:hover:text-blue-300 rounded-lg text-xs font-semibold border border-slate-200 dark:border-slate-700 hover:border-blue-200 dark:hover:border-blue-800 transition shadow-2xs">
                                                <i class="fa-solid fa-users text-slate-400 text-xs"></i>
                                                <span>{{ $job->applications_count }} Pelamar</span>
                                                <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                                            </a>
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="inline-flex items-center gap-1.5 justify-end" x-data="{ openMenu: false }">
                                                <!-- Tes Seleksi Link -->
                                                <a href="{{ route('admin.jobs.test.edit', $job) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 rounded-lg text-xs font-medium transition" title="Kelola Tes Online">
                                                    <i class="fa-solid fa-list-check text-xs"></i>
                                                    <span class="hidden sm:inline">Tes Seleksi</span>
                                                </a>

                                                <!-- Edit Pop-up Modal Trigger -->
                                                <button type="button" @click="openEditModal({{ json_encode($jobData) }}, '{{ route('admin.jobs.update', $job) }}')" class="p-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-lg text-xs transition cursor-pointer" title="Edit Lowongan (Pop-up)">
                                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                </button>

                                                <!-- Dropdown Menu for More Actions (Exports, Delete) -->
                                                <div class="relative">
                                                    <button @click="openMenu = !openMenu" @click.away="openMenu = false" class="p-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-lg text-xs transition cursor-pointer">
                                                        <i class="fa-solid fa-ellipsis-vertical text-xs px-0.5"></i>
                                                    </button>

                                                    <div x-show="openMenu" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="absolute right-0 mt-1 w-44 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 py-1 z-30 text-left" style="display: none;">
                                                        <a href="{{ route('admin.jobs.export.excel', $job) }}" class="flex items-center gap-2 px-3 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/60 hover:text-emerald-700 dark:hover:text-emerald-400 transition">
                                                            <i class="fa-solid fa-file-excel text-emerald-600 text-xs w-4"></i>
                                                            <span>Ekspor CSV / Excel</span>
                                                        </a>
                                                        <a href="{{ route('admin.jobs.export.pdf', $job) }}" class="flex items-center gap-2 px-3 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/60 hover:text-rose-700 dark:hover:text-rose-400 transition">
                                                            <i class="fa-solid fa-file-pdf text-rose-600 text-xs w-4"></i>
                                                            <span>Ekspor Rekap PDF</span>
                                                        </a>
                                                        <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                                                        <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan {{ addslashes($job->title) }}? Seluruh data pelamar terkait akan terhapus.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer">
                                                                <i class="fa-solid fa-trash text-rose-600 dark:text-rose-400 text-xs w-4"></i>
                                                                <span>Hapus Lowongan</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                                <i class="fa-solid fa-briefcase text-xl"></i>
                                            </div>
                                            <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm">Belum Ada Lowongan Pekerjaan</h4>
                                            <p class="text-slate-500 dark:text-slate-400 text-xs max-w-sm mx-auto mt-1">Mulai publikasikan posisi lowongan untuk menjaring talenta dan kandidat terbaik.</p>
                                            <div class="mt-4">
                                                <button type="button" @click="openCreateModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs transition shadow-xs inline-flex items-center gap-2">
                                                    <i class="fa-solid fa-plus"></i>
                                                    <span>Tambah Lowongan Sekarang</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($jobs, 'hasPages') && $jobs->hasPages())
                        <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- ============================================================== -->
        <!-- MODAL 1: TAMBAH LOWONGAN PEKERJAAN (CREATE POP-UP)             -->
        <!-- ============================================================== -->
        <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4 sm:p-6" x-cloak style="display: none;">
            <div @click.away="showCreateModal = false" x-show="showCreateModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 rounded-2xl max-w-4xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                
                <!-- Modal Header -->
                <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/80 dark:bg-slate-800/60">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-plus text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white">Tambah Lowongan Baru</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Publikasikan posisi lowongan untuk menjaring kandidat terbaik.</p>
                        </div>
                    </div>
                    <button type="button" @click="showCreateModal = false" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                <!-- Modal Body Form -->
                <form action="{{ route('admin.jobs.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    <div class="p-6 sm:p-8 overflow-y-auto space-y-6 flex-1 text-xs text-slate-800 dark:text-slate-200">
                        
                        <!-- Section 1: Info Utama -->
                        <div>
                            <div class="border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3.5 flex flex-wrap justify-between items-center gap-2">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="fa-solid fa-briefcase text-blue-600 dark:text-blue-400"></i> Informasi Utama Lowongan
                                </h4>
                                @if(!auth()->user()->hasRole('Super Admin'))
                                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5 bg-slate-50 dark:bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700">
                                        <i class="fa-solid fa-building text-blue-600 dark:text-blue-400"></i>
                                        <span>Perusahaan:</span>
                                        <strong class="text-slate-900 dark:text-white">{{ $companyProfile->company_name ?? (auth()->user()->name . ' Company') }}</strong>
                                    </span>
                                @endif
                            </div>

                            @if(!auth()->user()->hasRole('Super Admin'))
                                <!-- Banner Indikator Perusahaan HR Login -->
                                <div class="mb-4 p-3 bg-blue-50/70 dark:bg-blue-950/40 border border-blue-200/80 dark:border-blue-800/80 rounded-xl flex items-center justify-between gap-3 text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-2xs shrink-0">
                                            <i class="fa-solid fa-building"></i>
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-blue-700 dark:text-blue-300 font-bold uppercase tracking-wider">Perusahaan Penyelenggara (Sesuai Akun HR)</div>
                                            <div class="font-bold text-slate-900 dark:text-white text-xs mt-0.5 flex items-center gap-1.5">
                                                <span>{{ $companyProfile->company_name ?? (auth()->user()->name . ' Company') }}</span>
                                                <span class="px-1.5 py-0.2 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 text-[9px] font-black rounded border border-emerald-200 dark:border-emerald-800 uppercase">
                                                    <i class="fa-solid fa-circle-check"></i> Otomatis Sesuai HR
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.company.profile.edit') }}" target="_blank" class="text-[11px] font-bold text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:underline shrink-0 flex items-center gap-1">
                                        <span>Ubah Profil</span> <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                </div>
                                <input type="hidden" name="company_name" value="{{ $companyProfile->company_name ?? (auth()->user()->name . ' Company') }}">
                            @else
                                <div class="mb-4">
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Perusahaan Penyelenggara <span class="text-rose-500">*</span></label>
                                    <input type="text" name="company_name" list="company_list_options_create" required placeholder="Ketik atau pilih nama perusahaan..." class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500" value="{{ old('company_name', $companyProfile->company_name ?? '') }}">
                                    <datalist id="company_list_options_create">
                                        @foreach(\App\Models\CompanyProfile::whereNotNull('company_name')->where('company_name', '!=', '')->pluck('company_name')->unique() as $cName)
                                            <option value="{{ $cName }}">
                                        @endforeach
                                    </datalist>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Sebagai Super Admin, Anda dapat mempublikasikan lowongan untuk perusahaan mitra mana pun.</p>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Posisi / Judul Pekerjaan <span class="text-rose-500">*</span></label>
                                    <input type="text" name="title" required placeholder="Misal: Senior Backend Developer atau Full-Stack Intern" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori / Divisi <span class="text-rose-500">*</span></label>
                                    <input type="text" name="division" list="division_options_create" required placeholder="Pilih atau ketik divisi..." class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                    <datalist id="division_options_create">
                                        <option value="Engineering">
                                        <option value="Design">
                                        <option value="Product">
                                        <option value="Data Science">
                                        <option value="Marketing">
                                        <option value="Human Resources">
                                        <option value="Finance">
                                        <option value="Operations">
                                        <option value="Sales & Business Development">
                                    </datalist>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Penempatan & Sistem Kerja -->
                        <div>
                            <div class="border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3.5">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="fa-solid fa-location-dot text-blue-600 dark:text-blue-400"></i> Penempatan & Sistem Kerja
                                </h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                                <div class="relative">
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Lokasi Penempatan <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <input type="text" name="location" x-model="createLocation" @focus="createOpenCities = true; fetchCities(createLocation, 'create')" @input.debounce.300ms="fetchCities(createLocation, 'create'); checkCreateUmk(); createOpenCities = true" @click.away="createOpenCities = false" autocomplete="off" required placeholder="Ketik kota penempatan..." class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500 pr-8">
                                        <div class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                            <i class="fa-solid fa-location-dot text-xs"></i>
                                        </div>
                                    </div>
                                    <!-- Autocomplete drawer -->
                                    <div x-show="createOpenCities && createCities.length > 0" x-transition class="absolute left-0 right-0 mt-1 bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 z-50 max-h-40 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800" style="display: none;">
                                        <template x-for="c in createCities" :key="c.city_district">
                                            <div @click="createLocation = c.city_district; createOpenCities = false; checkCreateUmk()" class="px-3 py-2 hover:bg-blue-50 dark:hover:bg-slate-800 cursor-pointer transition flex items-center justify-between text-xs">
                                                <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="c.city_district"></span>
                                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300" x-text="c.province"></span>
                                            </div>
                                        </template>
                                    </div>
                                    <template x-if="createUmk">
                                        <div class="mt-1.5 p-1.5 bg-emerald-50 dark:bg-emerald-950/50 rounded-lg border border-emerald-200 dark:border-emerald-800 text-[11px] text-emerald-900 dark:text-emerald-200 flex items-center justify-between gap-1 shadow-2xs">
                                            <span class="flex items-center gap-1">
                                                <i class="fa-solid fa-scale-balanced text-emerald-600 dark:text-emerald-400"></i>
                                                <span x-text="'UMK 2026: ' + createUmk.formatted_umk"></span>
                                            </span>
                                            <span class="text-[9px] text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/60 px-1 py-0.5 rounded font-medium" x-text="createUmk.province"></span>
                                        </div>
                                    </template>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Sistem Kerja <span class="text-rose-500">*</span></label>
                                    <select name="work_type" required class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition">
                                        <option value="Full-time" class="dark:bg-slate-900">Full-Time (WFO)</option>
                                        <option value="Hybrid" class="dark:bg-slate-900">Hybrid (WFO & Remote)</option>
                                        <option value="Remote" class="dark:bg-slate-900">Remote (100% WFA)</option>
                                        <option value="Part-time" class="dark:bg-slate-900">Part-Time</option>
                                        <option value="Contract" class="dark:bg-slate-900">Contract / Proyek</option>
                                        <option value="Internship" class="dark:bg-slate-900">Internship / Magang</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tingkat Pengalaman</label>
                                    <select name="experience_level" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition">
                                        <option value="Semua Tingkat" class="dark:bg-slate-900">Semua Tingkat</option>
                                        <option value="Fresh Graduate" class="dark:bg-slate-900">Fresh Graduate (0-1 Thn)</option>
                                        <option value="Junior Level" class="dark:bg-slate-900">Junior (1-3 Thn)</option>
                                        <option value="Mid Level" class="dark:bg-slate-900">Mid Level (3-5 Thn)</option>
                                        <option value="Senior Level" class="dark:bg-slate-900">Senior (5+ Thn)</option>
                                        <option value="Managerial" class="dark:bg-slate-900">Lead / Managerial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tautan Google Maps Kantor (Opsional)</label>
                                <input type="text" name="google_maps_link" placeholder="https://maps.google.com/?q=..." class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                            </div>
                        </div>

                        <!-- Section 3: Batch, Periode & Durasi Program (Khusus Magang / Kontrak / Umum) -->
                        <div>
                            <div class="border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3.5">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="fa-solid fa-layer-group text-blue-600 dark:text-blue-400"></i> Program Batch, Periode & Durasi
                                </h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        <span>Batch / Periode Program</span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">(Opsional)</span>
                                    </label>
                                    <input type="text" name="batch" list="batch_options_list_create" placeholder="Misal: Batch 1 - Semester Genap 2026" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                    <datalist id="batch_options_list_create">
                                        @foreach($availableBatches ?? [] as $bName)
                                            <option value="{{ $bName }}">
                                        @endforeach
                                        <option value="Batch 1 - 2026">
                                        <option value="Batch 2 - 2026">
                                        <option value="Batch 1 - Semester Ganjil 2026">
                                        <option value="Batch 2 - Semester Genap 2026">
                                    </datalist>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Penting untuk lowongan magang atau rekrutmen gelombang tertentu.</p>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        <span>Durasi Magang / Kontrak</span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">(Opsional)</span>
                                    </label>
                                    <input type="text" name="duration" list="duration_options_list_create" placeholder="Misal: 6 Bulan / 3 Bulan / Tetap" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                    <datalist id="duration_options_list_create">
                                        <option value="3 Bulan">
                                        <option value="6 Bulan">
                                        <option value="1 Tahun">
                                        <option value="Tetap (Permanent)">
                                        <option value="Fleksibel / Proyek">
                                    </datalist>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        <span>Tanggal Mulai / Onboarding</span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">(Opsional)</span>
                                    </label>
                                    <input type="date" name="start_date" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition">
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Kriteria Pendidikan & Kualifikasi -->
                        <div>
                            <div class="border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3.5">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="fa-solid fa-graduation-cap text-blue-600 dark:text-blue-400"></i> Kriteria Kualifikasi
                                </h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3.5">
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Min. Pendidikan</label>
                                    <select name="education_level" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition">
                                        <option value="Semua Jenjang" class="dark:bg-slate-900">Semua Jenjang</option>
                                        <option value="SMA/SMK" class="dark:bg-slate-900">SMA / SMK Sederajat</option>
                                        <option value="D3" class="dark:bg-slate-900">D3 (Diploma 3)</option>
                                        <option value="D4/S1" class="dark:bg-slate-900">D4 / S1 (Sarjana)</option>
                                        <option value="S2" class="dark:bg-slate-900">S2 (Magister)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Jurusan Studi</label>
                                    <input type="text" name="major_requirement" list="indonesia-majors-list" placeholder="Pilih jurusan..." class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kriteria Gender</label>
                                    <select name="gender_requirement" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition">
                                        <option value="Pria & Wanita" class="dark:bg-slate-900">Pria & Wanita</option>
                                        <option value="Khusus Pria" class="dark:bg-slate-900">Khusus Pria</option>
                                        <option value="Khusus Wanita" class="dark:bg-slate-900">Khusus Wanita</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Batasan Usia (Tahun)</label>
                                    <div class="relative">
                                        <input type="text" name="age_range" placeholder="Contoh: 21 - 35 atau 30" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500 pr-14">
                                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-[11px] font-semibold text-slate-400">
                                            Tahun
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kata Kunci Keahlian (Skills Match)</label>
                                <input type="text" name="skills_required" placeholder="Misal: PHP, Laravel, MySQL, REST API (pisahkan koma)" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                            </div>
                        </div>

                        <!-- Section 5: Kompensasi & Batas Waktu -->
                        <div>
                            <div class="border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3.5">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="fa-solid fa-money-bill-wave text-blue-600 dark:text-blue-400"></i> Kompensasi & Kuota
                                </h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kisaran Gaji / Uang Saku</label>
                                    <input type="text" id="create_salary_input" name="salary" placeholder="Misal: Sesuai UMK / Negosiasi" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                    <div class="mt-1 flex flex-wrap gap-1 text-[10px]">
                                        <button type="button" @click="document.getElementById('create_salary_input').value = 'Gaji Negosiasi'" class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium">Negosiasi</button>
                                        <button type="button" @click="document.getElementById('create_salary_input').value = 'Sesuai UMK 2026 Wilayah'" class="px-1.5 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 font-medium">Sesuai UMK</button>
                                        <button type="button" @click="document.getElementById('create_salary_input').value = 'Magang Sesuai UMK 2026 Wilayah'" class="px-1.5 py-0.5 rounded bg-teal-50 dark:bg-teal-950/50 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800 font-medium">Magang UMK</button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Batas Kuota Pelamar</label>
                                    <input type="number" name="quota" min="1" placeholder="Kosongkan jika bebas" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Batas Akhir (Deadline) <span class="text-rose-500">*</span></label>
                                    <input type="date" name="deadline" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition">
                                </div>
                            </div>
                        </div>

                        <!-- Section 6: Deskripsi & Persyaratan -->
                        <div>
                            <div class="border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3.5">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="fa-solid fa-file-lines text-blue-600 dark:text-blue-400"></i> Rincian Keterangan
                                </h4>
                            </div>
                            <div class="space-y-3.5">
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Pekerjaan <span class="text-rose-500">*</span></label>
                                    <textarea name="description" rows="3" required placeholder="Jelaskan peran dan tugas utama sehari-hari..." class="w-full rounded-xl text-xs font-medium text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500"></textarea>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Persyaratan & Kualifikasi Detail <span class="text-rose-500">*</span></label>
                                    <textarea name="requirements" rows="3" required placeholder="Tuliskan kualifikasi teknis dan keahlian yang dicari..." class="w-full rounded-xl text-xs font-medium text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500"></textarea>
                                </div>
                                <!-- Checklist Tunjangan & Benefit -->
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="block font-semibold text-slate-700 dark:text-slate-300">Tunjangan & Fasilitas Benefit (Opsional)</label>
                                        <span class="text-[11px] font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 px-2 py-0.5 rounded-md border border-blue-100 dark:border-blue-900" x-text="createSelectedBenefits.length + ' dipilih'"></span>
                                    </div>

                                    <!-- Hidden input for form submission -->
                                    <input type="hidden" name="benefits" :value="createSelectedBenefits.join(', ')">

                                    <!-- Checklist Grid -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <template x-for="(benefit, index) in createAvailableBenefits" :key="index">
                                            <label class="flex items-center justify-between gap-2 p-2.5 rounded-xl border cursor-pointer text-xs transition select-none"
                                                   :class="createSelectedBenefits.includes(benefit) ? 'bg-blue-50/80 dark:bg-blue-950/60 border-blue-400 dark:border-blue-600 text-blue-900 dark:text-blue-200 font-semibold shadow-2xs ring-1 ring-blue-300 dark:ring-blue-600' : 'bg-white dark:bg-slate-950/40 border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50/50 dark:hover:bg-slate-800/40'">
                                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                                    <input type="checkbox" 
                                                           :checked="createSelectedBenefits.includes(benefit)"
                                                           @change="toggleCreateBenefit(benefit)"
                                                           class="rounded text-blue-600 focus:ring-blue-500 border-slate-300 dark:border-slate-700 h-4 w-4 shrink-0 cursor-pointer">
                                                    <span class="truncate" x-text="benefit"></span>
                                                </div>
                                                <template x-if="!defaultBenefits.includes(benefit)">
                                                    <button type="button" @click.stop.prevent="removeCreateBenefit(benefit)" class="text-slate-400 hover:text-rose-600 p-1 rounded-md hover:bg-rose-50 dark:hover:bg-rose-950/50 transition shrink-0" title="Hapus dari daftar">
                                                        <i class="fa-solid fa-xmark text-xs"></i>
                                                    </button>
                                                </template>
                                            </label>
                                        </template>
                                    </div>

                                    <!-- Form Tambah Benefit Baru -->
                                    <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-50/80 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800">
                                        <div class="relative flex-1">
                                            <input type="text" x-model="newCustomBenefitCreate" @keydown.enter.prevent="addCustomBenefitCreate()"
                                                   placeholder="Tambah benefit kustom (misal: Ruang Game, Reimburse Kacamata, Konversi SKS)..."
                                                   class="w-full rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 border-slate-300 dark:border-slate-700 focus:ring-blue-500 focus:border-blue-500 py-1.5 px-3 bg-white dark:bg-slate-900">
                                        </div>
                                        <button type="button" @click="addCustomBenefitCreate()" 
                                                class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition shrink-0 flex items-center gap-1.5 shadow-xs border border-blue-600 cursor-pointer">
                                            <i class="fa-solid fa-plus text-xs"></i>
                                            <span>Tambah</span>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Status Publikasi Awal</label>
                                    <select name="status" class="w-full sm:w-56 rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition shadow-2xs">
                                        <option value="active" class="dark:bg-slate-900">Aktif (Langsung Tayang)</option>
                                        <option value="inactive" class="dark:bg-slate-900">Non-Aktif (Draf)</option>
                                        <option value="closed" class="dark:bg-slate-900">Ditutup</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="p-4 sm:p-5 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5 shrink-0">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium text-xs rounded-xl border border-slate-200 dark:border-slate-700 transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 border border-blue-600 cursor-pointer">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Simpan & Publikasikan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- MODAL 2: EDIT LOWONGAN PEKERJAAN (EDIT POP-UP)                 -->
        <!-- ============================================================== -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4 sm:p-6" x-cloak style="display: none;">
            <div @click.away="showEditModal = false" x-show="showEditModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 rounded-2xl max-w-4xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                
                <!-- Modal Header -->
                <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/80 dark:bg-slate-800/60">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white">
                                <span>Edit Lowongan:</span>
                                <span class="text-blue-600 dark:text-blue-400" x-text="editForm.title"></span>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Perbarui rincian kualifikasi, batch, kuota, atau status publikasi lowongan.</p>
                        </div>
                    </div>
                    <button type="button" @click="showEditModal = false" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                <!-- Modal Body Form -->
                <form :action="editForm.updateUrl" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 sm:p-8 overflow-y-auto space-y-6 flex-1 text-xs text-slate-800 dark:text-slate-200">
                        
                        <!-- Section 1: Info Utama -->
                        <div>
                            <div class="border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3.5 flex flex-wrap justify-between items-center gap-2">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="fa-solid fa-briefcase text-blue-600 dark:text-blue-400"></i> Informasi Utama Lowongan
                                </h4>
                                @if(!auth()->user()->hasRole('Super Admin'))
                                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5 bg-slate-50 dark:bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700">
                                        <i class="fa-solid fa-building text-blue-600 dark:text-blue-400"></i>
                                        <span>Perusahaan:</span>
                                        <strong class="text-slate-900 dark:text-white">{{ $companyProfile->company_name ?? (auth()->user()->name . ' Company') }}</strong>
                                    </span>
                                @endif
                            </div>

                            @if(!auth()->user()->hasRole('Super Admin'))
                                <!-- Banner Indikator Perusahaan HR Login -->
                                <div class="mb-4 p-3 bg-blue-50/70 dark:bg-blue-950/40 border border-blue-200/80 dark:border-blue-800/80 rounded-xl flex items-center justify-between gap-3 text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-2xs shrink-0">
                                            <i class="fa-solid fa-building"></i>
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-blue-700 dark:text-blue-300 font-bold uppercase tracking-wider">Perusahaan Penyelenggara (Sesuai Akun HR)</div>
                                            <div class="font-bold text-slate-900 dark:text-white text-xs mt-0.5 flex items-center gap-1.5">
                                                <span>{{ $companyProfile->company_name ?? (auth()->user()->name . ' Company') }}</span>
                                                <span class="px-1.5 py-0.2 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 text-[9px] font-black rounded border border-emerald-200 dark:border-emerald-800 uppercase">
                                                    <i class="fa-solid fa-circle-check"></i> Otomatis Sesuai HR
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.company.profile.edit') }}" target="_blank" class="text-[11px] font-bold text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:underline shrink-0 flex items-center gap-1">
                                        <span>Ubah Profil</span> <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                </div>
                                <input type="hidden" name="company_name" value="{{ $companyProfile->company_name ?? (auth()->user()->name . ' Company') }}">
                            @else
                                <div class="mb-4">
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Perusahaan Penyelenggara <span class="text-rose-500">*</span></label>
                                    <input type="text" name="company_name" x-model="editForm.company_name" list="company_list_options_edit" required placeholder="Ketik atau pilih nama perusahaan..." class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                    <datalist id="company_list_options_edit">
                                        @foreach(\App\Models\CompanyProfile::whereNotNull('company_name')->where('company_name', '!=', '')->pluck('company_name')->unique() as $cName)
                                            <option value="{{ $cName }}">
                                        @endforeach
                                    </datalist>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Sebagai Super Admin, Anda dapat memindahkan lowongan ke perusahaan mitra mana pun.</p>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Posisi / Judul Pekerjaan <span class="text-rose-500">*</span></label>
                                    <input type="text" name="title" x-model="editForm.title" required class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori / Divisi <span class="text-rose-500">*</span></label>
                                    <input type="text" name="division" x-model="editForm.division" list="division_options_edit" required class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                    <datalist id="division_options_edit">
                                        <option value="Engineering">
                                        <option value="Design">
                                        <option value="Product">
                                        <option value="Data Science">
                                        <option value="Marketing">
                                        <option value="Human Resources">
                                        <option value="Finance">
                                        <option value="Operations">
                                        <option value="Sales & Business Development">
                                    </datalist>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Penempatan & Sistem Kerja -->
                        <div>
                            <div class="border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3.5">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="fa-solid fa-location-dot text-blue-600 dark:text-blue-400"></i> Penempatan & Sistem Kerja
                                </h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                                <div class="relative">
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Lokasi Penempatan <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <input type="text" name="location" x-model="editForm.location" @focus="editOpenCities = true; fetchCities(editForm.location, 'edit')" @input.debounce.300ms="fetchCities(editForm.location, 'edit'); checkEditUmk(); editOpenCities = true" @click.away="editOpenCities = false" autocomplete="off" required class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500 pr-8">
                                        <div class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                            <i class="fa-solid fa-location-dot text-xs"></i>
                                        </div>
                                    </div>
                                    <!-- Autocomplete drawer -->
                                    <div x-show="editOpenCities && editCities.length > 0" x-transition class="absolute left-0 right-0 mt-1 bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 z-50 max-h-40 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800" style="display: none;">
                                        <template x-for="c in editCities" :key="c.city_district">
                                            <div @click="editForm.location = c.city_district; editOpenCities = false; checkEditUmk()" class="px-3 py-2 hover:bg-blue-50 dark:hover:bg-slate-800 cursor-pointer transition flex items-center justify-between text-xs">
                                                <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="c.city_district"></span>
                                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300" x-text="c.province"></span>
                                            </div>
                                        </template>
                                    </div>
                                    <template x-if="editUmk">
                                        <div class="mt-1.5 p-1.5 bg-emerald-50 dark:bg-emerald-950/50 rounded-lg border border-emerald-200 dark:border-emerald-800 text-[11px] text-emerald-900 dark:text-emerald-200 flex items-center justify-between gap-1 shadow-2xs">
                                            <span class="flex items-center gap-1">
                                                <i class="fa-solid fa-scale-balanced text-emerald-600 dark:text-emerald-400"></i>
                                                <span x-text="'UMK 2026: ' + editUmk.formatted_umk"></span>
                                            </span>
                                            <span class="text-[9px] text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/60 px-1 py-0.5 rounded font-medium" x-text="editUmk.province"></span>
                                        </div>
                                    </template>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Sistem Kerja <span class="text-rose-500">*</span></label>
                                    <select name="work_type" x-model="editForm.work_type" required class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition">
                                        <option value="Full-time" class="dark:bg-slate-900">Full-Time (WFO)</option>
                                        <option value="Hybrid" class="dark:bg-slate-900">Hybrid (WFO & Remote)</option>
                                        <option value="Remote" class="dark:bg-slate-900">Remote (100% WFA)</option>
                                        <option value="Part-time" class="dark:bg-slate-900">Part-Time</option>
                                        <option value="Contract" class="dark:bg-slate-900">Contract / Proyek</option>
                                        <option value="Internship" class="dark:bg-slate-900">Internship / Magang</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tingkat Pengalaman</label>
                                    <select name="experience_level" x-model="editForm.experience_level" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition">
                                        <option value="Semua Tingkat" class="dark:bg-slate-900">Semua Tingkat</option>
                                        <option value="Fresh Graduate" class="dark:bg-slate-900">Fresh Graduate (0-1 Thn)</option>
                                        <option value="Junior Level" class="dark:bg-slate-900">Junior (1-3 Thn)</option>
                                        <option value="Mid Level" class="dark:bg-slate-900">Mid Level (3-5 Thn)</option>
                                        <option value="Senior Level" class="dark:bg-slate-900">Senior (5+ Thn)</option>
                                        <option value="Managerial" class="dark:bg-slate-900">Lead / Managerial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tautan Google Maps Kantor (Opsional)</label>
                                <input type="text" name="google_maps_link" x-model="editForm.google_maps_link" placeholder="https://maps.google.com/?q=..." class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                            </div>
                        </div>

                        <!-- Section 3: Batch, Periode & Durasi Program (Edit) -->
                        <div>
                            <div class="border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3.5">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="fa-solid fa-layer-group text-blue-600 dark:text-blue-400"></i> Program Batch, Periode & Durasi
                                </h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        <span>Batch / Periode Program</span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">(Opsional)</span>
                                    </label>
                                    <input type="text" name="batch" x-model="editForm.batch" list="batch_options_list_edit" placeholder="Misal: Batch 1 - Semester Genap 2026" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                    <datalist id="batch_options_list_edit">
                                        @foreach($availableBatches ?? [] as $bName)
                                            <option value="{{ $bName }}">
                                        @endforeach
                                        <option value="Batch 1 - 2026">
                                        <option value="Batch 2 - 2026">
                                        <option value="Batch 1 - Semester Ganjil 2026">
                                        <option value="Batch 2 - Semester Genap 2026">
                                    </datalist>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        <span>Durasi Magang / Kontrak</span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">(Opsional)</span>
                                    </label>
                                    <input type="text" name="duration" x-model="editForm.duration" list="duration_options_list_edit" placeholder="Misal: 6 Bulan / 3 Bulan / Tetap" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                    <datalist id="duration_options_list_edit">
                                        <option value="3 Bulan">
                                        <option value="6 Bulan">
                                        <option value="1 Tahun">
                                        <option value="Tetap (Permanent)">
                                        <option value="Fleksibel / Proyek">
                                    </datalist>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        <span>Tanggal Mulai / Onboarding</span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">(Opsional)</span>
                                    </label>
                                    <input type="date" name="start_date" x-model="editForm.start_date" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition">
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Kriteria Pendidikan & Kualifikasi -->
                        <div>
                            <div class="border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3.5">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="fa-solid fa-graduation-cap text-blue-600 dark:text-blue-400"></i> Kriteria Kualifikasi
                                </h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3.5">
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Min. Pendidikan</label>
                                    <select name="education_level" x-model="editForm.education_level" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition">
                                        <option value="Semua Jenjang" class="dark:bg-slate-900">Semua Jenjang</option>
                                        <option value="SMA/SMK" class="dark:bg-slate-900">SMA / SMK Sederajat</option>
                                        <option value="D3" class="dark:bg-slate-900">D3 (Diploma 3)</option>
                                        <option value="D4/S1" class="dark:bg-slate-900">D4 / S1 (Sarjana)</option>
                                        <option value="S2" class="dark:bg-slate-900">S2 (Magister)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Jurusan Studi</label>
                                    <input type="text" name="major_requirement" x-model="editForm.major_requirement" list="indonesia-majors-list" placeholder="Pilih jurusan..." class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kriteria Gender</label>
                                    <select name="gender_requirement" x-model="editForm.gender_requirement" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition">
                                        <option value="Pria & Wanita" class="dark:bg-slate-900">Pria & Wanita</option>
                                        <option value="Khusus Pria" class="dark:bg-slate-900">Khusus Pria</option>
                                        <option value="Khusus Wanita" class="dark:bg-slate-900">Khusus Wanita</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Batasan Usia (Tahun)</label>
                                    <div class="relative">
                                        <input type="text" name="age_range" x-model="editForm.age_range" placeholder="Contoh: 21 - 35 atau 30" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500 pr-14">
                                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-[11px] font-semibold text-slate-400">
                                            Tahun
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kata Kunci Keahlian (Skills Match)</label>
                                <input type="text" name="skills_required" x-model="editForm.skills_required" placeholder="Misal: PHP, Laravel, MySQL, REST API (pisahkan koma)" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                            </div>
                        </div>

                        <!-- Section 5: Kompensasi & Batas Waktu -->
                        <div>
                            <div class="border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3.5">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="fa-solid fa-money-bill-wave text-blue-600 dark:text-blue-400"></i> Kompensasi & Kuota
                                </h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kisaran Gaji / Uang Saku</label>
                                    <input type="text" id="edit_salary_input" name="salary" x-model="editForm.salary" placeholder="Misal: Sesuai UMK / Negosiasi" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Batas Kuota Pelamar</label>
                                    <input type="number" name="quota" x-model="editForm.quota" min="1" placeholder="Kosongkan jika bebas" class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Batas Akhir (Deadline) <span class="text-rose-500">*</span></label>
                                    <input type="date" name="deadline" x-model="editForm.deadline" required class="w-full rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition">
                                </div>
                            </div>
                        </div>

                        <!-- Section 6: Deskripsi & Persyaratan -->
                        <div>
                            <div class="border-b border-slate-100 dark:border-slate-800 pb-2.5 mb-3.5">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="fa-solid fa-file-lines text-blue-600 dark:text-blue-400"></i> Rincian Keterangan
                                </h4>
                            </div>
                            <div class="space-y-3.5">
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Pekerjaan <span class="text-rose-500">*</span></label>
                                    <textarea name="description" x-model="editForm.description" rows="3" required placeholder="Jelaskan peran dan tugas utama sehari-hari..." class="w-full rounded-xl text-xs font-medium text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500"></textarea>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Persyaratan & Kualifikasi Detail <span class="text-rose-500">*</span></label>
                                    <textarea name="requirements" x-model="editForm.requirements" rows="3" required placeholder="Tuliskan kualifikasi teknis dan keahlian yang dicari..." class="w-full rounded-xl text-xs font-medium text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition placeholder-slate-400 dark:placeholder-slate-500"></textarea>
                                </div>
                                <!-- Checklist Tunjangan & Benefit -->
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="block font-semibold text-slate-700 dark:text-slate-300">Tunjangan & Fasilitas Benefit (Opsional)</label>
                                        <span class="text-[11px] font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 px-2 py-0.5 rounded-md border border-blue-100 dark:border-blue-900" x-text="editSelectedBenefits.length + ' dipilih'"></span>
                                    </div>

                                    <!-- Hidden input for form submission -->
                                    <input type="hidden" name="benefits" :value="editSelectedBenefits.join(', ')">

                                    <!-- Checklist Grid -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <template x-for="(benefit, index) in editAvailableBenefits" :key="index">
                                            <label class="flex items-center justify-between gap-2 p-2.5 rounded-xl border cursor-pointer text-xs transition select-none"
                                                   :class="editSelectedBenefits.includes(benefit) ? 'bg-blue-50/80 dark:bg-blue-950/60 border-blue-400 dark:border-blue-600 text-blue-900 dark:text-blue-200 font-semibold shadow-2xs ring-1 ring-blue-300 dark:ring-blue-600' : 'bg-white dark:bg-slate-950/40 border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50/50 dark:hover:bg-slate-800/40'">
                                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                                    <input type="checkbox" 
                                                           :checked="editSelectedBenefits.includes(benefit)"
                                                           @change="toggleEditBenefit(benefit)"
                                                           class="rounded text-blue-600 focus:ring-blue-500 border-slate-300 dark:border-slate-700 h-4 w-4 shrink-0 cursor-pointer">
                                                    <span class="truncate" x-text="benefit"></span>
                                                </div>
                                                <template x-if="!defaultBenefits.includes(benefit)">
                                                    <button type="button" @click.stop.prevent="removeEditBenefit(benefit)" class="text-slate-400 hover:text-rose-600 p-1 rounded-md hover:bg-rose-50 dark:hover:bg-rose-950/50 transition shrink-0" title="Hapus dari daftar">
                                                        <i class="fa-solid fa-xmark text-xs"></i>
                                                    </button>
                                                </template>
                                            </label>
                                        </template>
                                    </div>

                                    <!-- Form Tambah Benefit Baru -->
                                    <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-50/80 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800">
                                        <div class="relative flex-1">
                                            <input type="text" x-model="newCustomBenefitEdit" @keydown.enter.prevent="addCustomBenefitEdit()"
                                                   placeholder="Tambah benefit kustom (misal: Ruang Game, Reimburse Kacamata, Konversi SKS)..."
                                                   class="w-full rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 border-slate-300 dark:border-slate-700 focus:ring-blue-500 focus:border-blue-500 py-1.5 px-3 bg-white dark:bg-slate-900">
                                        </div>
                                        <button type="button" @click="addCustomBenefitEdit()" 
                                                class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition shrink-0 flex items-center gap-1.5 shadow-xs border border-blue-600 cursor-pointer">
                                            <i class="fa-solid fa-plus text-xs"></i>
                                            <span>Tambah</span>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Status Publikasi Lowongan</label>
                                    <select name="status" x-model="editForm.status" class="w-full sm:w-56 rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-950/70 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition shadow-2xs">
                                        <option value="active" class="dark:bg-slate-900">Aktif (Tayang)</option>
                                        <option value="inactive" class="dark:bg-slate-900">Non-Aktif (Draf)</option>
                                        <option value="closed" class="dark:bg-slate-900">Ditutup</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="p-4 sm:p-5 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5 shrink-0">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium text-xs rounded-xl border border-slate-200 dark:border-slate-700 transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 border border-blue-600 cursor-pointer">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Hidden datalist helper -->
        <x-indonesia-majors-datalist />
    </div>

    @php
        $oldCreateBenefits = old('benefits') ? array_values(array_filter(array_map('trim', explode(',', old('benefits'))))) : [];
    @endphp

    <script>
        function jobsManager() {
            const defaultBenefitsList = [
                'Asuransi Kesehatan & BPJS',
                'Jam Kerja Fleksibel / Hybrid',
                'Laptop Kerja Perusahaan',
                'Makan Siang & Snack Gratis',
                'Bonus Kinerja & THR',
                'Pelatihan & Sertifikasi Industri',
                'Cuti Tahunan Tambahan',
                'Fasilitas Olahraga / Gym'
            ];

            return {
                showCreateModal: {{ request('create') ? 'true' : 'false' }},
                showEditModal: false,

                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.get('create') === '1') {
                        this.openCreateModal();
                    }
                },

                // Default Benefits Master
                defaultBenefits: [...defaultBenefitsList],

                // Create Form State
                createLocation: '{{ old('location', '') }}',
                createUmk: null,
                createCities: [],
                createOpenCities: false,
                createAvailableBenefits: [...defaultBenefitsList],
                createSelectedBenefits: @json($oldCreateBenefits),
                newCustomBenefitCreate: '',

                // Edit Form State
                editForm: {
                    id: '',
                    updateUrl: '',
                    title: '',
                    division: '',
                    location: '',
                    google_maps_link: '',
                    work_type: 'Full-time',
                    experience_level: 'Semua Tingkat',
                    education_level: 'Semua Jenjang',
                    major_requirement: '',
                    gender_requirement: 'Pria & Wanita',
                    age_range: '',
                    skills_required: '',
                    salary: '',
                    quota: '',
                    batch: '',
                    duration: '',
                    start_date: '',
                    deadline: '',
                    description: '',
                    requirements: '',
                    benefits: '',
                    status: 'active'
                },
                editUmk: null,
                editCities: [],
                editOpenCities: false,
                editAvailableBenefits: [...defaultBenefitsList],
                editSelectedBenefits: [],
                newCustomBenefitEdit: '',

                // Create Modal Benefits Methods
                toggleCreateBenefit(benefit) {
                    if (this.createSelectedBenefits.includes(benefit)) {
                        this.createSelectedBenefits = this.createSelectedBenefits.filter(b => b !== benefit);
                    } else {
                        this.createSelectedBenefits.push(benefit);
                    }
                },

                addCustomBenefitCreate() {
                    const val = this.newCustomBenefitCreate.trim();
                    if (!val) return;
                    if (!this.createAvailableBenefits.includes(val)) {
                        this.createAvailableBenefits.push(val);
                    }
                    if (!this.createSelectedBenefits.includes(val)) {
                        this.createSelectedBenefits.push(val);
                    }
                    this.newCustomBenefitCreate = '';
                },

                removeCreateBenefit(benefit) {
                    this.createAvailableBenefits = this.createAvailableBenefits.filter(b => b !== benefit);
                    this.createSelectedBenefits = this.createSelectedBenefits.filter(b => b !== benefit);
                },

                // Edit Modal Benefits Methods
                toggleEditBenefit(benefit) {
                    if (this.editSelectedBenefits.includes(benefit)) {
                        this.editSelectedBenefits = this.editSelectedBenefits.filter(b => b !== benefit);
                    } else {
                        this.editSelectedBenefits.push(benefit);
                    }
                },

                addCustomBenefitEdit() {
                    const val = this.newCustomBenefitEdit.trim();
                    if (!val) return;
                    if (!this.editAvailableBenefits.includes(val)) {
                        this.editAvailableBenefits.push(val);
                    }
                    if (!this.editSelectedBenefits.includes(val)) {
                        this.editSelectedBenefits.push(val);
                    }
                    this.newCustomBenefitEdit = '';
                },

                removeEditBenefit(benefit) {
                    this.editAvailableBenefits = this.editAvailableBenefits.filter(b => b !== benefit);
                    this.editSelectedBenefits = this.editSelectedBenefits.filter(b => b !== benefit);
                },

                openCreateModal() {
                    this.showCreateModal = true;
                    if (this.createLocation) {
                        this.checkCreateUmk();
                    }
                },

                openEditModal(jobData, updateUrl) {
                    this.editForm = {
                        id: jobData.id,
                        updateUrl: updateUrl,
                        title: jobData.title || '',
                        division: jobData.division || '',
                        location: jobData.location || '',
                        google_maps_link: jobData.google_maps_link || '',
                        work_type: jobData.work_type || 'Full-time',
                        experience_level: jobData.experience_level || 'Semua Tingkat',
                        education_level: jobData.education_level || 'Semua Jenjang',
                        major_requirement: jobData.major_requirement || '',
                        gender_requirement: jobData.gender_requirement || 'Pria & Wanita',
                        age_range: jobData.age_range || '',
                        skills_required: jobData.skills_required || '',
                        salary: jobData.salary || '',
                        quota: jobData.quota || '',
                        batch: jobData.batch || '',
                        duration: jobData.duration || '',
                        start_date: jobData.start_date ? jobData.start_date.substring(0, 10) : '',
                        deadline: jobData.deadline ? jobData.deadline.substring(0, 10) : '',
                        description: jobData.description || '',
                        requirements: jobData.requirements || '',
                        benefits: jobData.benefits || '',
                        status: jobData.status || 'active'
                    };

                    // Populate edit benefits checklist
                    this.editAvailableBenefits = [...this.defaultBenefits];
                    let existingBenefits = [];
                    if (jobData.benefits) {
                        existingBenefits = jobData.benefits.split(',').map(b => b.trim()).filter(Boolean);
                        existingBenefits.forEach(b => {
                            if (!this.editAvailableBenefits.includes(b)) {
                                this.editAvailableBenefits.push(b);
                            }
                        });
                    }
                    this.editSelectedBenefits = existingBenefits;

                    this.checkEditUmk();
                    this.showEditModal = true;
                },

                fetchCities(query, type) {
                    if (!query || query.length < 2) {
                        if (type === 'create') this.createCities = [];
                        if (type === 'edit') this.editCities = [];
                        return;
                    }
                    fetch('/api/regions/cities?query=' + encodeURIComponent(query))
                        .then(res => res.json())
                        .then(data => {
                            if (type === 'create') this.createCities = data;
                            if (type === 'edit') this.editCities = data;
                        })
                        .catch(() => {});
                },

                checkCreateUmk() {
                    if (!this.createLocation || this.createLocation.length < 3) {
                        this.createUmk = null;
                        return;
                    }
                    fetch('/api/umk-lookup?location=' + encodeURIComponent(this.createLocation))
                        .then(res => res.json())
                        .then(data => {
                            this.createUmk = data.found ? data : null;
                        })
                        .catch(() => { this.createUmk = null; });
                },

                checkEditUmk() {
                    if (!this.editForm.location || this.editForm.location.length < 3) {
                        this.editUmk = null;
                        return;
                    }
                    fetch('/api/umk-lookup?location=' + encodeURIComponent(this.editForm.location))
                        .then(res => res.json())
                        .then(data => {
                            this.editUmk = data.found ? data : null;
                        })
                        .catch(() => { this.editUmk = null; });
                }
            };
        }

        window.jobsManager = jobsManager;
        if (window.Alpine) {
            Alpine.data('jobsManager', jobsManager);
        } else {
            document.addEventListener('alpine:init', () => {
                Alpine.data('jobsManager', jobsManager);
            });
        }
    </script>
</x-app-layout>
