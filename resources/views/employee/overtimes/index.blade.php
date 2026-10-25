<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                    <i class="fa-solid fa-business-time"></i>
                    <span>Employee Self-Service (ESS)</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">
                    Pengajuan & Riwayat Lembur
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Kelola pengajuan jam kerja lembur dan pantau estimasi kompensasi upah lembur resmi Anda.
                </p>
            </div>

            <!-- Month Filter & Add Button -->
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('employee.overtimes.index') }}" class="flex items-center gap-2">
                    <input type="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()"
                           class="text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 px-3 py-2 shadow-2xs font-semibold focus:ring-2 focus:ring-indigo-500">
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8" x-data="{
        openModal: false,
        overtimeDate: '{{ now()->toDateString() }}',
        startTime: '17:30',
        endTime: '20:30',
        compensationType: 'paid',
        get durationHours() {
            if (!this.startTime || !this.endTime) return 0;
            let [sH, sM] = this.startTime.split(':').map(Number);
            let [eH, eM] = this.endTime.split(':').map(Number);
            let sTotal = sH * 60 + sM;
            let eTotal = eH * 60 + eM;
            if (eTotal < sTotal) eTotal += 24 * 60; // Overnight
            let diff = eTotal - sTotal;
            return Math.max(0, (diff / 60)).toFixed(1);
        },
        get estimatedPay() {
            if (this.compensationType !== 'paid') return 0;
            return Math.round(parseFloat(this.durationHours) * 35000);
        },
        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

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

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-2xl shadow-xs flex items-center justify-between text-xs font-medium transition">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600 dark:text-rose-400 text-base shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-rose-600 hover:text-rose-800 dark:text-rose-400">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            <!-- 4 BENTO METRIC STATS -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 rounded-2xl shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Lembur Disetujui</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-1.5">
                        <span class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalApprovedHours }}</span>
                        <span class="text-xs font-semibold text-slate-400">Jam ({{ $monthDate->translatedFormat('F Y') }})</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 rounded-2xl shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Upah Lembur</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-1.5">
                        <span class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalApprovedPay, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 rounded-2xl shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Menunggu Review</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-1.5">
                        <span class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ $pendingCount }}</span>
                        <span class="text-xs font-semibold text-slate-400">Pengajuan</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 rounded-2xl shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Pengajuan Ditolak</span>
                        <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-ban"></i>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-1.5">
                        <span class="text-2xl font-black text-rose-600 dark:text-rose-400">{{ $rejectedCount }}</span>
                        <span class="text-xs font-semibold text-slate-400">Pengajuan</span>
                    </div>
                </div>
            </div>

            <!-- ACTION BAR & OVERTIME LIST -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Daftar Pengajuan Lembur Periode {{ $monthDate->translatedFormat('F Y') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daftar jam kerja lembur yang telah diajukan beserta status persetujuan dari atasan.</p>
                    </div>

                    <button type="button" @click="openModal = true" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Ajukan Lembur Baru</span>
                    </button>
                </div>

                <!-- TABLE OF OVERTIMES -->
                @if($overtimes->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                                    <th class="p-4 font-semibold">Tanggal</th>
                                    <th class="p-4 font-semibold">Rentang Jam & Durasi</th>
                                    <th class="p-4 font-semibold">Uraian Tugas Lembur</th>
                                    <th class="p-4 font-semibold">Estimasi Upah</th>
                                    <th class="p-4 font-semibold">Status</th>
                                    <th class="p-4 font-semibold text-right">Aksi / Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                                @foreach($overtimes as $ot)
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="font-bold text-slate-900 dark:text-white">{{ $ot->date->translatedFormat('d F Y') }}</div>
                                            <div class="text-[11px] text-slate-400">{{ $ot->date->translatedFormat('l') }}</div>
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="font-mono font-bold text-indigo-600 dark:text-indigo-400">{{ $ot->start_time }} – {{ $ot->end_time }}</div>
                                            <span class="inline-block mt-0.5 px-2 py-0.5 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 rounded font-semibold text-[10px]">
                                                {{ $ot->duration_hours }} Jam
                                            </span>
                                        </td>
                                        <td class="p-4 max-w-xs">
                                            <p class="line-clamp-2 text-xs text-slate-800 dark:text-slate-200">{{ $ot->task_description }}</p>
                                            @if($ot->attachment_path)
                                                <a href="{{ Storage::url($ot->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-blue-600 hover:underline mt-1 font-semibold">
                                                    <i class="fa-solid fa-paperclip"></i>
                                                    <span>Lihat Lampiran</span>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="p-4 whitespace-nowrap font-mono font-bold">
                                            @if($ot->compensation_type === 'paid')
                                                <span class="text-emerald-600 dark:text-emerald-400">Rp {{ number_format($ot->total_compensation, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-purple-600 dark:text-purple-400">Hak Cuti Pengganti</span>
                                            @endif
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $ot->status_badge['class'] }}">
                                                <i class="fa-solid {{ $ot->status_badge['icon'] }} text-[11px]"></i>
                                                <span>{{ $ot->status_badge['text'] }}</span>
                                            </span>
                                            @if($ot->approved_at)
                                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $ot->approved_at->format('d M, H:i') }}</div>
                                            @endif
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-right">
                                            @if($ot->status === 'pending')
                                                <form method="POST" action="{{ route('employee.overtimes.destroy', $ot->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan lembur ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/50 rounded-lg transition border border-rose-200 dark:border-rose-900">
                                                        Batalkan
                                                    </button>
                                                </form>
                                            @elseif(!empty($ot->admin_notes))
                                                <span class="text-[11px] text-slate-400 italic" title="{{ $ot->admin_notes }}">
                                                    "{{ Str::limit($ot->admin_notes, 25) }}"
                                                </span>
                                            @else
                                                <span class="text-slate-400 text-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center space-y-3">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 mx-auto flex items-center justify-center text-2xl shadow-xs">
                            <i class="fa-solid fa-mug-hot"></i>
                        </div>
                        <h4 class="text-base font-bold text-slate-800 dark:text-white">Belum Ada Pengajuan Lembur</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                            Anda belum memiliki catatan pengajuan lembur untuk bulan {{ $monthDate->translatedFormat('F Y') }}. Klik tombol di bawah untuk membuat pengajuan baru.
                        </p>
                        <div class="pt-2">
                            <button type="button" @click="openModal = true" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                                + Ajukan Lembur Sekarang
                            </button>
                        </div>
                    </div>
                @endif
            </div>

        </div>

        <!-- MODAL FORM PENGAJUAN LEMBUR -->
        <div x-show="openModal" x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/75 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="openModal = false" class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-5">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-business-time"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Formulir Pengajuan Lembur</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Pastikan jam kerja lembur telah dikoordinasikan dengan atasan.</p>
                        </div>
                    </div>
                    <button type="button" @click="openModal = false" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('employee.overtimes.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Lembur <span class="text-rose-500">*</span></label>
                        <input type="date" name="date" x-model="overtimeDate" required
                               class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-3 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Jam Mulai <span class="text-rose-500">*</span></label>
                            <input type="time" name="start_time" x-model="startTime" required
                                   class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-3 focus:ring-2 focus:ring-indigo-500 font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Jam Selesai <span class="text-rose-500">*</span></label>
                            <input type="time" name="end_time" x-model="endTime" required
                                   class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-3 focus:ring-2 focus:ring-indigo-500 font-mono">
                        </div>
                    </div>

                    <!-- Live Calculation Card -->
                    <div class="p-3.5 rounded-xl bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-200/80 dark:border-indigo-900/60 flex items-center justify-between text-xs">
                        <div>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 block">Kalkulasi Durasi</span>
                            <span class="font-bold text-slate-900 dark:text-white font-mono text-sm" x-text="durationHours + ' Jam'"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 block">Estimasi Upah Lembur</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono text-sm" x-text="formatRupiah(estimatedPay)"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Pilihan Kompensasi</label>
                        <select name="compensation_type" x-model="compensationType"
                                class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-3 focus:ring-2 focus:ring-indigo-500">
                            <option value="paid">Upah Lembur Berbayar (Disatukan ke Payroll Slip Gaji)</option>
                            <option value="leave_balance">Tambahan Hak Cuti / Kompensasi Hari Libur</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Uraian Tugas / Rencana Kerja Lembur <span class="text-rose-500">*</span></label>
                        <textarea name="task_description" rows="3" required placeholder="Tuliskan alasan lembur, modul sistem yang dikerjakan, atau kendala operasional yang diselesaikan..."
                                  class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-3 focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Lampiran Dokumen / Surat Tugas (Opsional)</label>
                        <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-2.5">
                        <span class="text-[10px] text-slate-400 block mt-1">Format PDF, JPG, PNG (Maks 5 MB).</span>
                    </div>

                    <div class="pt-3 flex items-center justify-end gap-2.5">
                        <button type="button" @click="openModal = false" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold transition">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span>Kirim Pengajuan</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
