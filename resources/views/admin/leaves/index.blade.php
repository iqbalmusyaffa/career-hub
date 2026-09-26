<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
                    <span class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-calendar-check"></i>
                    </span>
                    Manajemen Cuti & Izin Karyawan / Magang
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Kelola persetujuan permohonan cuti, izin dispensasi magang, dan konfigurasi kuota perusahaan.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="document.getElementById('policyModal').classList.remove('hidden')" class="px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 shadow-2xs transition flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-indigo-500"></i> Pengaturan Kuota Cuti
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/80 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-emerald-800 dark:text-emerald-200 text-xs font-bold flex items-center gap-3 shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/80 border border-rose-200 dark:border-rose-800 rounded-2xl text-rose-800 dark:text-rose-200 text-xs font-bold flex items-center gap-3 shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 dark:bg-rose-950/80 border border-rose-200 dark:border-rose-800 rounded-2xl text-rose-800 dark:text-rose-200 text-xs font-bold space-y-1 shadow-2xs">
                    @foreach($errors->all() as $err)
                        <div class="flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-rose-500"></i> {{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <!-- Summary Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/80 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl shrink-0 border border-amber-200 dark:border-amber-800/80">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $pendingCount }}</div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400">Menunggu Review</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 flex items-center justify-center text-xl shrink-0 border border-sky-200 dark:border-sky-800/80">
                        <i class="fa-solid fa-umbrella-beach"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $onLeaveTodayCount }}</div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400">Sedang Cuti Hari Ini</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0 border border-emerald-200 dark:border-emerald-800/80">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $approvedThisMonth }}</div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400">Disetujui Bulan Ini</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shrink-0 border border-indigo-200 dark:border-indigo-800/80">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div>
                        <div class="text-xs font-black text-slate-900 dark:text-white flex items-center gap-1.5">
                            <span>PKWT: <strong>{{ $policy->annual_leave_quota }}h</strong></span> •
                            <span>Tetap: <strong>{{ $policy->permanent_leave_quota }}h</strong></span>
                        </div>
                        <div class="text-3xs font-bold text-slate-500 dark:text-slate-400 mt-0.5">Magang: Maks. <strong>{{ $policy->internship_max_excused_days }} Hari</strong> Izin</div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Bar -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200/80 dark:border-slate-700 shadow-2xs">
                <form method="GET" action="{{ route('admin.leaves.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                    <div class="sm:col-span-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama karyawan / pelamar..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium py-2.5">
                    </div>

                    <div class="sm:col-span-3">
                        <select name="status" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium py-2.5">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Menunggu Persetujuan</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ Disetujui</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>⚪ Dibatalkan</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <select name="leave_type" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium py-2.5">
                            <option value="all" {{ request('leave_type') == 'all' ? 'selected' : '' }}>Semua Tipe Cuti / Izin</option>
                            <option value="annual_leave" {{ request('leave_type') == 'annual_leave' ? 'selected' : '' }}>Cuti Tahunan (Annual Leave)</option>
                            <option value="sick_leave" {{ request('leave_type') == 'sick_leave' ? 'selected' : '' }}>Izin Sakit (Sick Leave)</option>
                            <option value="academic_leave" {{ request('leave_type') == 'academic_leave' ? 'selected' : '' }}>Izin Akademik / Wisuda</option>
                            <option value="family_event" {{ request('leave_type') == 'family_event' ? 'selected' : '' }}>Izin Acara Keluarga</option>
                            <option value="special_leave" {{ request('leave_type') == 'special_leave' ? 'selected' : '' }}>Cuti Khusus (Menikah/Duka)</option>
                            <option value="internship_permission" {{ request('leave_type') == 'internship_permission' ? 'selected' : '' }}>Izin Magang / Dispensasi</option>
                            <option value="maternity_leave" {{ request('leave_type') == 'maternity_leave' ? 'selected' : '' }}>Cuti Melahirkan</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2 flex items-center gap-2">
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-2xs transition flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-magnifying-glass"></i> Filter
                        </button>
                        <a href="{{ route('admin.leaves.index') }}" class="p-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-xl transition" title="Reset">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Leave Requests Table -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700 shadow-2xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700/80 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Daftar Pengajuan Cuti & Izin</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Total: <strong>{{ $leaveRequests->total() }}</strong> pengajuan terdaftar</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50/80 dark:bg-slate-900/60 text-3xs uppercase font-extrabold text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-slate-700">
                            <tr>
                                <th class="py-3.5 px-4">Karyawan / Peserta</th>
                                <th class="py-3.5 px-4">Tipe Cuti / Izin</th>
                                <th class="py-3.5 px-4">Rentang Waktu</th>
                                <th class="py-3.5 px-4 text-center">Durasi</th>
                                <th class="py-3.5 px-4">Alasan & Lampiran</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 font-medium">
                            @forelse($leaveRequests as $leave)
                                @php $badge = $leave->status_badge; @endphp
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                                    <td class="py-3.5 px-4">
                                        <div class="font-extrabold text-slate-900 dark:text-white">{{ $leave->user->name ?? 'User #' . $leave->user_id }}</div>
                                        <div class="text-3xs text-slate-500 dark:text-slate-400">{{ $leave->user->email ?? '-' }}</div>
                                        @if($leave->application && $leave->application->job)
                                            <span class="inline-block mt-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700 text-3xs font-semibold text-slate-700 dark:text-slate-300">
                                                {{ $leave->application->job->title }} ({{ $leave->application->job->work_type }})
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-2 font-bold text-slate-800 dark:text-slate-200">
                                            <i class="fa-solid {{ $leave->leave_type_icon }}"></i>
                                            <span>{{ $leave->leave_type_label }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-800 dark:text-slate-200">
                                            {{ $leave->start_date->translatedFormat('d M Y') }}
                                            @if($leave->start_date != $leave->end_date)
                                                <span class="text-slate-400 font-normal">s/d</span> {{ $leave->end_date->translatedFormat('d M Y') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 text-xs font-black border border-indigo-200/80 dark:border-indigo-800">
                                            {{ $leave->total_days }} Hari Kerja
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 max-w-xs">
                                        <p class="text-xs text-slate-700 dark:text-slate-300 line-clamp-2">{{ $leave->reason }}</p>
                                        @if($leave->attachment_path)
                                            <a href="{{ $leave->attachment_path }}" target="_blank" class="inline-flex items-center gap-1 text-3xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline mt-1">
                                                <i class="fa-solid fa-paperclip"></i> Lihat Dokumen Bukti
                                            </a>
                                        @endif
                                        @if($leave->admin_notes)
                                            <div class="mt-1 text-3xs text-slate-500 dark:text-slate-400 italic bg-slate-50 dark:bg-slate-900/50 p-1.5 rounded-lg border border-slate-200 dark:border-slate-800">
                                                <strong>Catatan:</strong> {{ $leave->admin_notes }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-xl text-3xs font-black uppercase border {{ $badge['class'] }} flex items-center justify-center gap-1.5 w-max mx-auto shadow-2xs">
                                            <i class="fa-solid {{ $badge['icon'] }}"></i>
                                            {{ $badge['label'] }}
                                        </span>
                                        @if($leave->approved_at)
                                            <div class="text-4xs text-slate-400 mt-1">
                                                {{ $leave->approved_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        @if($leave->status === 'pending')
                                            <div class="flex items-center justify-end gap-1.5">
                                                <!-- Quick Approve Form -->
                                                <form action="{{ route('admin.leaves.approve', $leave) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI pengajuan cuti ini?')">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black shadow-2xs transition flex items-center gap-1">
                                                        <i class="fa-solid fa-check"></i> Setujui
                                                    </button>
                                                </form>

                                                <!-- Reject Modal Trigger -->
                                                <button type="button" onclick="openRejectModal({{ $leave->id }}, '{{ addslashes($leave->user->name ?? '') }}', '{{ addslashes($leave->leave_type_label) }}')" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:hover:bg-rose-900 dark:text-rose-300 rounded-xl text-xs font-black border border-rose-200 dark:border-rose-800 transition flex items-center gap-1">
                                                    <i class="fa-solid fa-xmark"></i> Tolak
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-3xs font-semibold text-slate-400">Selesai Ditinjau</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center mx-auto mb-3 text-slate-400 text-2xl">
                                            <i class="fa-solid fa-calendar-xmark"></i>
                                        </div>
                                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Belum Ada Pengajuan Cuti / Izin</p>
                                        <p class="text-xs text-slate-500 mt-1">Pengajuan dari karyawan atau peserta magang akan muncul di daftar ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($leaveRequests->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                        {{ $leaveRequests->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- MODAL PENGATURAN KEBIJAKAN KUOTA CUTI -->
    <div id="policyModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 dark:border-slate-700 space-y-6 animate-in fade-in zoom-in-95">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-base">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-base text-slate-900 dark:text-white">Pengaturan Kebijakan Cuti</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Konfigurasi kuota tahunan dan toleransi izin</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('policyModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.leaves.policy.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                        Kuota Cuti Tahunan PKWT / Remote / Hybrid (Hari / Tahun) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="annual_leave_quota" value="{{ old('annual_leave_quota', $policy->annual_leave_quota) }}" min="1" max="365" required class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold py-2.5">
                    <p class="text-3xs text-slate-400 mt-1">Standar UU No. 13/2003 adalah minimal 12 hari kerja.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                        Kuota Cuti Karyawan Tetap / PKWTT (Hari / Tahun) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="permanent_leave_quota" value="{{ old('permanent_leave_quota', $policy->permanent_leave_quota) }}" min="1" max="365" required class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold py-2.5">
                    <p class="text-3xs text-slate-400 mt-1">Rekomendasi 14 s/d 15 hari kerja sebagai insentif loyalitas.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                        Batas Toleransi Izin Magang Tanpa Potong Stipend (Hari / Periode) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="internship_max_excused_days" value="{{ old('internship_max_excused_days', $policy->internship_max_excused_days) }}" min="0" max="100" required class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold py-2.5">
                    <p class="text-3xs text-slate-400 mt-1">Sesuai kesepakatan magang (Default: 4 hari kerja). Jika lebih, stipend dipotong prorata.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Catatan Kebijakan Tambahan / SOP</label>
                    <textarea name="notes" rows="3" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-medium p-3" placeholder="Misal: Cuti diajukan minimal H-3 sebelum tanggal cuti...">{{ old('notes', $policy->notes) }}</textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('policyModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-xl shadow-2xs">Simpan Kebijakan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TOLAK PERMOHONAN -->
    <div id="rejectModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-700 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                <h3 class="font-black text-sm text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-circle-xmark text-rose-500"></i> Tolak Permohonan Cuti / Izin
                </h3>
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <p id="rejectModalSubtext" class="text-xs text-slate-500 dark:text-slate-400"></p>

            <form id="rejectForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                        Alasan / Catatan Penolakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="admin_notes" rows="3" required placeholder="Tuliskan alasan penolakan untuk kandidat/karyawan..." class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-medium p-3"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black rounded-xl shadow-2xs">Tolak Permohonan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(leaveId, userName, leaveType) {
            const form = document.getElementById('rejectForm');
            form.action = `/admin/leaves/${leaveId}/reject`;
            document.getElementById('rejectModalSubtext').innerText = `Menolak permohonan ${leaveType} dari ${userName}.`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
