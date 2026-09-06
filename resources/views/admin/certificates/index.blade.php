<x-app-layout>
    <div class="py-8" x-data="{
        showRevokeModal: false,
        activeCertId: null,
        activeCertName: '',
        activeCertNo: '',
        openRevoke(id, name, no) {
            this.activeCertId = id;
            this.activeCertName = name;
            this.activeCertNo = no;
            this.showRevokeModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Admin</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">Sertifikasi</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="text-blue-600 dark:text-blue-400 font-semibold">Manajemen Sertifikat</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                                Master Sertifikat & Revokasi
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                                Kelola keabsahan dokumen sertifikat magang dan otoritas pencabutan nomor seri resmi.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('certificates.verify.public') }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-2xs">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px] text-blue-500"></i>
                        <span>Portal Verifikasi Publik</span>
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Terbit</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/80 dark:border-blue-900 flex items-center justify-center text-xs font-semibold">
                            <i class="fa-solid fa-file-shield"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalCertificates }} <span class="text-xs font-normal text-slate-500">Dokumen</span></div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Seluruh sertifikat terbit</p>
                    </div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sertifikat Valid</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-900 flex items-center justify-center text-xs font-semibold">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $validCertificates }} <span class="text-xs font-normal text-slate-500">Dokumen</span></div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Keabsahan aktif & terverifikasi</p>
                    </div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Dicabut (Revoked)</span>
                        <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200/80 dark:border-rose-900 flex items-center justify-center text-xs font-semibold">
                            <i class="fa-solid fa-ban"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-rose-600 dark:text-rose-400">{{ $revokedCertificates }} <span class="text-xs font-normal text-slate-500">Dokumen</span></div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Status tidak berlaku</p>
                    </div>
                </div>
            </div>

            <!-- Search Form -->
            <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
                <form action="{{ route('admin.certificates.index') }}" method="GET" class="flex gap-2.5">
                    <div class="relative flex-1">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama peserta, nomor seri sertifikat, atau institusi kampus..." class="w-full pl-9 pr-3.5 py-2 rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-xs text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-xl text-xs font-semibold transition shadow-2xs border border-slate-700">
                        <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                        <span>Cari</span>
                    </button>
                    @if($search)
                        <a href="{{ route('admin.certificates.index') }}" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition flex items-center border border-slate-200 dark:border-slate-700">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table of Certificates -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse bg-white dark:bg-slate-900">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/80 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold uppercase text-slate-500 dark:text-slate-400 tracking-wider">
                                <th class="py-3.5 px-5">Nomor Seri</th>
                                <th class="py-3.5 px-5">Peserta Magang</th>
                                <th class="py-3.5 px-5">Posisi & Mentor</th>
                                <th class="py-3.5 px-5">Grade & Terbit</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs bg-white dark:bg-slate-900">
                            @forelse($certificates as $cert)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/50 transition">
                                <td class="py-4 px-5">
                                    <span class="font-mono font-semibold text-slate-900 dark:text-white">{{ $cert->certificate_number }}</span>
                                </td>
                                <td class="py-4 px-5">
                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $cert->participant_name }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $cert->institution_name ?? 'Institusi Universitas' }}</div>
                                </td>
                                <td class="py-4 px-5">
                                    <div class="font-medium text-slate-800 dark:text-slate-200">{{ $cert->job_title }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Mentor: {{ $cert->mentor_name ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-2xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900">
                                        Grade {{ $cert->performance_grade }}
                                    </span>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">{{ $cert->issued_at->format('d M Y') }}</div>
                                </td>
                                <td class="py-4 px-5">
                                    @if($cert->is_revoked)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-2xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900">
                                            <i class="fa-solid fa-circle-xmark text-[10px]"></i>
                                            <span>Dicabut</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-2xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-900">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                                            <span>Sah & Valid</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('certificates.verify.public', ['code' => $cert->certificate_number]) }}" target="_blank" class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-semibold transition border border-slate-200 dark:border-slate-700" title="Lihat Tampilan Publik">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
                                        </a>

                                        @if($cert->is_revoked)
                                            <form action="{{ route('admin.certificates.restore', $cert->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memulihkan status keabsahan sertifikat ini?');">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition shadow-2xs">
                                                    Pulihkan
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" @click="openRevoke({{ $cert->id }}, '{{ addslashes($cert->participant_name) }}', '{{ $cert->certificate_number }}')" class="px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold transition shadow-2xs">
                                                Cabut
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-xl mb-3">
                                        <i class="fa-solid fa-award"></i>
                                    </div>
                                    <p class="font-medium text-xs text-slate-700 dark:text-slate-300">Belum ada sertifikat magang yang diterbitkan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($certificates->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $certificates->links() }}
                </div>
                @endif
            </div>

            <!-- Revoke Modal -->
            <div x-show="showRevokeModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
                <div @click.away="showRevokeModal = false" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 max-w-lg w-full shadow-xl space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900 flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pencabutan Sertifikat Resmi</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-0.5" x-text="activeCertNo"></p>
                        </div>
                    </div>

                    <form :action="`/admin/certificates/${activeCertId}/revoke`" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Alasan Resmi Pencabutan (Wajib)
                            </label>
                            <textarea name="revocation_reason" rows="3" required placeholder="Contoh: Ditemukan pemalsuan data kehadiran atau pelanggaran berat integritas..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 p-3 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 font-normal"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="showRevokeModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-xl transition border border-slate-200 dark:border-slate-700">
                                Batal
                            </button>
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-xl transition shadow-xs">
                                <i class="fa-solid fa-ban text-xs"></i>
                                <span>Konfirmasi Cabut</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
