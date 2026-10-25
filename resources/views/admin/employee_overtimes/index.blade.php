<x-app-layout>
    <div class="py-8" x-data="{
        reviewModalOpen: false,
        activeOvertime: null,
        reviewStatus: 'approved',
        adminNotes: '',
        compensationAmount: 0,
        openReview(ot) {
            this.activeOvertime = ot;
            this.reviewStatus = 'approved';
            this.adminNotes = ot.admin_notes || '';
            this.compensationAmount = ot.total_compensation || 0;
            this.reviewModalOpen = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumbs & Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Admin</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">Karyawan</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold">Kelola Lembur</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fa-solid fa-business-time"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                                Persetujuan & Monitoring Lembur Karyawan
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                                Verifikasi jam lembur kerja harian, validasi tugas lapangan, dan persetujuan kompensasi upah lembur.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Month Picker Filter -->
                <form method="GET" action="{{ route('admin.employee-overtimes.index') }}" class="flex items-center gap-2">
                    <input type="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()"
                           class="text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 px-3 py-2 shadow-2xs font-semibold focus:ring-2 focus:ring-indigo-500">
                </form>
            </div>

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl shadow-xs flex items-center justify-between text-xs font-medium transition">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            <!-- 4 Bento Metric Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Pengajuan</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">{{ $totalRequests }} <span class="text-xs font-medium text-slate-400">Pengajuan</span></div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-amber-200 dark:border-amber-800/60 shadow-xs bg-amber-50/20 dark:bg-amber-950/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Menunggu Review</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-300 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl font-bold text-amber-700 dark:text-amber-300">{{ $pendingCount }} <span class="text-xs font-medium text-amber-600">Pending</span></div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-indigo-200 dark:border-indigo-800/60 shadow-xs bg-indigo-50/20 dark:bg-indigo-950/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-400">Total Jam Lembur</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl font-bold text-indigo-700 dark:text-indigo-300">{{ $approvedHours }} <span class="text-xs font-medium text-indigo-600">Jam Disetujui</span></div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800/90 rounded-2xl border border-emerald-200 dark:border-emerald-800/60 shadow-xs bg-emerald-50/20 dark:bg-emerald-950/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Total Upah Lembur</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-xl sm:text-2xl font-bold text-emerald-700 dark:text-emerald-300">Rp {{ number_format($approvedCompensation, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="p-4 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
                <form method="GET" action="{{ route('admin.employee-overtimes.index') }}" class="flex items-center gap-2 flex-wrap flex-1">
                    <input type="hidden" name="month" value="{{ $selectedMonth }}">
                    
                    <div class="relative min-w-[200px]">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama karyawan..."
                               class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 pl-8 pr-3 py-2">
                        <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
                    </div>

                    <select name="status" onchange="this.form.submit()" class="text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 px-3 py-2 font-medium">
                        <option value="all" {{ $selectedStatus === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ $selectedStatus === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                        <option value="approved" {{ $selectedStatus === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ $selectedStatus === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>

                    <button type="submit" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-2xs">
                        Filter
                    </button>
                    @if($search || $selectedStatus !== 'all')
                        <a href="{{ route('admin.employee-overtimes.index', ['month' => $selectedMonth]) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Overtime Table -->
            <div class="bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs overflow-hidden">
                @if($overtimes->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                                    <th class="p-4 font-semibold">Karyawan</th>
                                    <th class="p-4 font-semibold">Tanggal & Waktu</th>
                                    <th class="p-4 font-semibold">Durasi Lembur</th>
                                    <th class="p-4 font-semibold">Tugas / Pekerjaan</th>
                                    <th class="p-4 font-semibold">Upah Kompensasi</th>
                                    <th class="p-4 font-semibold">Status</th>
                                    <th class="p-4 font-semibold text-right">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-300">
                                @foreach($overtimes as $ot)
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-bold text-xs uppercase shrink-0">
                                                    {{ substr($ot->user->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <span class="font-bold text-slate-900 dark:text-white block">{{ $ot->user->name }}</span>
                                                    <span class="text-[11px] text-slate-400">{{ $ot->user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="font-bold text-slate-800 dark:text-slate-200">{{ $ot->date->translatedFormat('d M Y') }}</div>
                                            <div class="font-mono text-indigo-600 dark:text-indigo-400 font-semibold">{{ $ot->start_time }} – {{ $ot->end_time }}</div>
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 rounded-lg font-bold font-mono text-xs">
                                                {{ $ot->duration_hours }} Jam
                                            </span>
                                        </td>
                                        <td class="p-4 max-w-xs">
                                            <p class="line-clamp-2 text-slate-800 dark:text-slate-200 text-xs">{{ $ot->task_description }}</p>
                                            @if($ot->attachment_path)
                                                <a href="{{ \Illuminate\Support\Facades\Storage::url($ot->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-blue-600 hover:underline mt-1 font-semibold">
                                                    <i class="fa-solid fa-paperclip"></i>
                                                    <span>Lampiran</span>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="p-4 whitespace-nowrap font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                            Rp {{ number_format($ot->total_compensation, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $ot->status_badge['class'] }}">
                                                <i class="fa-solid {{ $ot->status_badge['icon'] }} text-[11px]"></i>
                                                <span>{{ $ot->status_badge['text'] }}</span>
                                            </span>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-right">
                                            <button type="button" @click="openReview({{ json_encode($ot) }})"
                                                    class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/60 dark:hover:bg-indigo-900 text-indigo-700 dark:text-indigo-300 font-bold rounded-lg transition text-xs shadow-2xs">
                                                Review & Tindakan
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                        {{ $overtimes->links() }}
                    </div>
                @else
                    <div class="p-12 text-center space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center text-xl">
                            <i class="fa-solid fa-inbox"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white">Tidak Ada Data Lembur</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">Tidak ditemukan data pengajuan lembur karyawan pada filter atau periode bulan ini.</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- MODAL REVIEW LEMBUR -->
        <div x-show="reviewModalOpen" x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/75 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="reviewModalOpen = false" class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-4">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Review Pengajuan Lembur</h3>
                    <button type="button" @click="reviewModalOpen = false" class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <template x-if="activeOvertime">
                    <form method="POST" :action="'{{ url('/admin/employee-overtimes') }}/' + activeOvertime.id + '/status'" class="space-y-4">
                        @csrf

                        <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl space-y-1 text-xs">
                            <div class="text-slate-500">Karyawan: <strong class="text-slate-900 dark:text-white" x-text="activeOvertime.user.name"></strong></div>
                            <div class="text-slate-500">Tanggal: <span class="font-mono" x-text="activeOvertime.date"></span> (<span x-text="activeOvertime.duration_hours + ' Jam'"></span>)</div>
                            <div class="text-slate-500">Tugas: <span class="italic text-slate-700 dark:text-slate-300" x-text="activeOvertime.task_description"></span></div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Keputusan Status <span class="text-rose-500">*</span></label>
                            <select name="status" x-model="reviewStatus" required
                                    class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-2.5 font-bold">
                                <option value="approved">Setujui (Approved)</option>
                                <option value="rejected">Tolak (Rejected)</option>
                            </select>
                        </div>

                        <div x-show="reviewStatus === 'approved'">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nominal Upah Lembur (Rp)</label>
                            <input type="number" name="total_compensation" x-model="compensationAmount"
                                   class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-2.5 font-mono font-bold">
                            <span class="text-[10px] text-slate-400 block mt-1">Dihitung otomatis: Durasi x Rp 35.000/jam (dapat disesuaikan jika ada kebijakan khusus).</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Catatan Admin / Alasan Penolakan</label>
                            <textarea name="admin_notes" rows="2.5" x-model="adminNotes" placeholder="Tambahkan catatan khusus atau alasan penolakan..."
                                      class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-2.5"></textarea>
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-2.5">
                            <button type="button" @click="reviewModalOpen = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-xs">
                                Simpan Keputusan
                            </button>
                        </div>
                    </form>
                </template>

            </div>
        </div>
    </div>
</x-app-layout>
