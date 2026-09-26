<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
                    <span class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-umbrella-beach"></i>
                    </span>
                    Portal Cuti & Izin Mandiri
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Ajukan cuti tahunan, izin sakit, atau dispensasi magang dan pantau status persetujuan secara transparan.
                </p>
            </div>
            <div>
                <button type="button" onclick="document.getElementById('applyLeaveModal').classList.remove('hidden')" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-xl shadow-2xs transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-amber-300"></i> Ajukan Cuti / Izin
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

            <!-- Balance Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shrink-0 border border-indigo-200 dark:border-indigo-800/80">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalQuota }} <span class="text-xs font-bold text-slate-400">Hari</span></div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ $quotaTitle }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0 border border-emerald-200 dark:border-emerald-800/80">
                        <i class="fa-solid fa-battery-half"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $remainingDays }} <span class="text-xs font-bold text-slate-400">Hari</span></div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400">Sisa Kuota Tersedia</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl shrink-0 border border-rose-200 dark:border-rose-800/80">
                        <i class="fa-solid fa-calendar-minus"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $usedDays }} <span class="text-xs font-bold text-slate-400">Hari</span></div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400">Telah Digunakan</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/80 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl shrink-0 border border-amber-200 dark:border-amber-800/80">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $pendingDays }} <span class="text-xs font-bold text-slate-400">Hari</span></div>
                        <div class="text-xs font-bold text-slate-500 dark:text-slate-400">Menunggu Review HRD</div>
                    </div>
                </div>
            </div>

            <!-- Policy Highlight Alert -->
            <div class="bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-200/80 dark:border-indigo-800/80 rounded-2xl p-4 flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 text-sm mt-0.5 shadow-2xs">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="text-xs text-indigo-950 dark:text-indigo-200 leading-relaxed">
                    @if($isIntern)
                        <strong class="font-black text-indigo-900 dark:text-indigo-100">Ketentuan Izin Peserta Magang:</strong>
                        Peserta magang mendapatkan toleransi izin resmi/mendesak hingga <strong>{{ $policy->internship_max_excused_days }} hari kerja</strong> tanpa pemotongan uang saku. Apabila akumulasi izin melebihi {{ $policy->internship_max_excused_days }} hari, akan diberlakukan pemotongan uang saku (stipend) secara proporsional sesuai perjanjian kerja sama.
                    @else
                        <strong class="font-black text-indigo-900 dark:text-indigo-100">Ketentuan Cuti Karyawan:</strong>
                        Alokasi cuti tahunan resmi Anda adalah <strong>{{ $totalQuota }} hari kerja</strong> per tahun. Pengajuan cuti sebaiknya dilakukan minimal 3 hari sebelum tanggal mulai pelaksanaan agar tim HRD dan atasan langsung dapat menyesuaikan jadwal kerja.
                    @endif
                </div>
            </div>

            <!-- Leave Requests History -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700 shadow-2xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700/80 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Riwayat Pengajuan Cuti & Izin Anda</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Daftar permohonan yang pernah Anda ajukan</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50/80 dark:bg-slate-900/60 text-3xs uppercase font-extrabold text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-slate-700">
                            <tr>
                                <th class="py-3.5 px-4">Tipe Cuti / Izin</th>
                                <th class="py-3.5 px-4">Rentang Tanggal</th>
                                <th class="py-3.5 px-4 text-center">Durasi</th>
                                <th class="py-3.5 px-4">Alasan & Dokumen</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4">Catatan HRD</th>
                                <th class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 font-medium">
                            @forelse($leaveRequests as $leave)
                                @php $badge = $leave->status_badge; @endphp
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-2 font-bold text-slate-900 dark:text-white">
                                            <i class="fa-solid {{ $leave->leave_type_icon }}"></i>
                                            <span>{{ $leave->leave_type_label }}</span>
                                        </div>
                                        <div class="text-3xs text-slate-400 mt-0.5">Diajukan: {{ $leave->created_at->translatedFormat('d M Y H:i') }}</div>
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
                                                <i class="fa-solid fa-paperclip"></i> Lihat Berkas Lampiran
                                            </a>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-xl text-3xs font-black uppercase border {{ $badge['class'] }} flex items-center justify-center gap-1.5 w-max mx-auto shadow-2xs">
                                            <i class="fa-solid {{ $badge['icon'] }}"></i>
                                            {{ $badge['label'] }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 max-w-xs text-3xs text-slate-500 dark:text-slate-400">
                                        @if($leave->admin_notes)
                                            <div class="bg-slate-50 dark:bg-slate-900/50 p-2 rounded-lg border border-slate-200 dark:border-slate-800 italic">
                                                "{{ $leave->admin_notes }}"
                                                @if($leave->approver)
                                                    <div class="text-4xs font-bold not-italic text-slate-600 dark:text-slate-300 mt-0.5">— {{ $leave->approver->name }}</div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        @if($leave->status === 'pending')
                                            <form action="{{ route('candidate.leaves.cancel', $leave) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-slate-200 rounded-xl text-xs font-bold transition">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-3xs text-slate-400 font-semibold">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center mx-auto mb-3 text-slate-400 text-2xl">
                                            <i class="fa-solid fa-umbrella-beach"></i>
                                        </div>
                                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Belum Ada Pengajuan Cuti / Izin</p>
                                        <p class="text-xs text-slate-500 mt-1">Klik tombol <strong>"Ajukan Cuti / Izin"</strong> di atas untuk membuat permohonan baru.</p>
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

    <!-- MODAL AJUKAN CUTI / IZIN BARU -->
    <div id="applyLeaveModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 dark:border-slate-700 space-y-5 animate-in fade-in zoom-in-95 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-base">
                        <i class="fa-solid fa-calendar-plus"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-base text-slate-900 dark:text-white">Formulir Pengajuan Cuti / Izin</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Permohonan akan diteruskan ke Tim HRD</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('applyLeaveModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('candidate.leaves.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                        Tipe Cuti / Izin <span class="text-rose-500">*</span>
                    </label>
                    <select name="leave_type" required class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold py-2.5">
                        @if($isIntern)
                            <option value="academic_leave">🎓 Izin Akademik / Kampus (Sidang, Ujian, Bimbingan, Wisuda)</option>
                            <option value="family_event">👨‍👩‍👧‍👦 Izin Acara Keluarga / Keperluan Mendesak</option>
                            <option value="sick_leave">🩺 Izin Sakit (Sick Leave)</option>
                            <option value="internship_permission">📄 Izin Magang / Dispensasi Khusus Lainnya</option>
                        @else
                            <option value="annual_leave">💼 Cuti Tahunan (Annual Leave)</option>
                            <option value="sick_leave">🩺 Izin Sakit (Sick Leave)</option>
                            <option value="academic_leave">🎓 Izin Akademik / Kampus / Wisuda</option>
                            <option value="family_event">👨‍👩‍👧‍👦 Izin Acara Keluarga / Keperluan Mendesak</option>
                            <option value="special_leave">💍 Cuti Khusus / Izin Berbayar (Menikah, Khitanan, Duka Cita)</option>
                            <option value="maternity_leave">🤰 Cuti Melahirkan / Keguguran</option>
                        @endif
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                            Tanggal Mulai <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="modalStartDate" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold py-2.5">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                            Tanggal Selesai <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="modalEndDate" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold py-2.5">
                    </div>
                </div>

                <div class="p-3 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200/80 dark:border-slate-700 text-xs flex items-center justify-between">
                    <span class="font-bold text-slate-600 dark:text-slate-400">Estimasi Durasi Hari Kerja:</span>
                    <span id="calculatedDaysBadge" class="font-black text-indigo-600 dark:text-indigo-400">1 Hari Kerja</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                        Alasan / Keterangan Cuti <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="reason" rows="3" required placeholder="Jelaskan alasan izin atau keperluan cuti Anda secara rinci..." class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-medium p-3">{{ old('reason') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                        Lampiran Dokumen Bukti (Opsional / Wajib untuk Sakit & Dispensasi Kampus)
                    </label>
                    <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-950/80 dark:file:text-indigo-300">
                    <p class="text-3xs text-slate-400 mt-1">Format: PDF, JPG, PNG (Maks. 5 MB)</p>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('applyLeaveModal').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-xl shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-paper-plane text-amber-300"></i> Ajukan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateDurationEstimate() {
            const startVal = document.getElementById('modalStartDate').value;
            const endVal = document.getElementById('modalEndDate').value;
            const badge = document.getElementById('calculatedDaysBadge');

            if (!startVal || !endVal) return;

            const start = new Date(startVal);
            const end = new Date(endVal);

            if (end < start) {
                badge.innerText = 'Tanggal tidak valid';
                badge.className = 'font-black text-rose-500';
                return;
            }

            let count = 0;
            let cur = new Date(start);
            while (cur <= end) {
                const day = cur.getDay();
                if (day !== 0 && day !== 6) { // Exclude Sunday (0) and Saturday (6)
                    count++;
                }
                cur.setDate(cur.getDate() + 1);
            }

            badge.innerText = `${count} Hari Kerja`;
            badge.className = 'font-black text-indigo-600 dark:text-indigo-400';
        }

        document.getElementById('modalStartDate').addEventListener('change', updateDurationEstimate);
        document.getElementById('modalEndDate').addEventListener('change', updateDurationEstimate);
    </script>
</x-app-layout>
