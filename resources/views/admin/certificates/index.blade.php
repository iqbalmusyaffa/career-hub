<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-600 text-white flex items-center justify-center shadow-lg shadow-orange-500/20 text-xl font-black shrink-0">
                    <i class="fa-solid fa-award"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 rounded-md text-3xs font-extrabold uppercase tracking-wider border border-amber-200 dark:border-amber-900">
                            Super Admin Master Control
                        </span>
                    </div>
                    <h2 class="font-black text-xl text-slate-900 dark:text-white leading-tight mt-0.5">
                        Master Manajemen & Revokasi Sertifikat Magang
                    </h2>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('certificates.verify.public') }}" target="_blank" class="px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-extrabold transition shadow-2xs flex items-center gap-2">
                    <i class="fa-solid fa-arrow-up-right-from-square text-blue-500"></i> Buka Portal Verifikasi Publik
                </a>
            </div>
        </div>
    </x-slot>

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

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-5 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-3xs font-black uppercase tracking-wider text-slate-400">Total Sertifikat Terbit</span>
                        <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $totalCertificates }} Dokumen</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950 text-blue-600 flex items-center justify-center text-xl font-bold">
                        📜
                    </div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-3xs font-black uppercase tracking-wider text-emerald-500">Sertifikat Aktif / Valid</span>
                        <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $validCertificates }} Dokumen</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 flex items-center justify-center text-xl font-bold">
                        ✔️
                    </div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-3xs font-black uppercase tracking-wider text-rose-500">Sertifikat Dicabut (Revoked)</span>
                        <div class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1">{{ $revokedCertificates }} Dokumen</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950 text-rose-600 flex items-center justify-center text-xl font-bold">
                        ❌
                    </div>
                </div>
            </div>

            <!-- Search Form -->
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-xs">
                <form action="{{ route('admin.certificates.index') }}" method="GET" class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama peserta, nomor seri, atau institusi kampus..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-xs text-slate-800 dark:text-slate-100">
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 dark:bg-slate-700 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition">
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route('admin.certificates.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold transition flex items-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table of Certificates -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-700 text-3xs font-black uppercase text-slate-400 tracking-wider">
                                <th class="py-4 px-6">Nomor Seri Sertifikat</th>
                                <th class="py-4 px-6">Peserta Magang</th>
                                <th class="py-4 px-6">Posisi & Mitra</th>
                                <th class="py-4 px-6">Grade & Tanggal Terbit</th>
                                <th class="py-4 px-6">Status Validitas</th>
                                <th class="py-4 px-6 text-right">Aksi Super Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs">
                            @forelse($certificates as $cert)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-900/40 transition">
                                <td class="py-4 px-6 font-mono font-bold text-slate-900 dark:text-white">
                                    {{ $cert->certificate_number }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-extrabold text-slate-800 dark:text-slate-200">{{ $cert->participant_name }}</div>
                                    <span class="text-3xs text-slate-400">{{ $cert->institution_name ?? 'Universitas' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-700 dark:text-slate-300">{{ $cert->job_title }}</div>
                                    <span class="text-3xs text-slate-400">{{ $cert->mentor_name ?? 'Mentor' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2 py-0.5 rounded font-black text-3xs bg-amber-50 text-amber-700 border border-amber-200">
                                        Grade: {{ $cert->performance_grade }}
                                    </span>
                                    <span class="text-3xs text-slate-400 block mt-0.5">{{ $cert->issued_at->format('d M Y') }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($cert->is_revoked)
                                        <span class="px-2.5 py-1 rounded-full text-3xs font-black uppercase bg-red-100 text-red-700 border border-red-300">
                                            Dicabut (Revoked)
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-3xs font-black uppercase bg-emerald-100 text-emerald-700 border border-emerald-300">
                                            Sah & Valid
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('certificates.verify.public', ['code' => $cert->certificate_number]) }}" target="_blank" class="p-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold transition" title="Lihat Tampilan Publik">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        @if($cert->is_revoked)
                                            <form action="{{ route('admin.certificates.restore', $cert->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memulihkan status sertifikat ini?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-3xs font-black transition">
                                                    Pulihkan
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" @click="openRevoke({{ $cert->id }}, '{{ addslashes($cert->participant_name) }}', '{{ $cert->certificate_number }}')" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-3xs font-black transition">
                                                Cabut Sertifikat
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <div class="text-3xl mb-2">📜</div>
                                    <p class="font-bold text-xs">Belum ada sertifikat magang yang diterbitkan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($certificates->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $certificates->links() }}
                </div>
                @endif
            </div>

            <!-- Revoke Modal -->
            <div x-show="showRevokeModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
                <div @click.away="showRevokeModal = false" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                        <div class="w-10 h-10 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-lg font-bold">
                            ⚠️
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 dark:text-white">Pencabutan Sertifikat Resmi</h3>
                            <p class="text-xs text-slate-500 font-mono" x-text="activeCertNo"></p>
                        </div>
                    </div>

                    <form :action="`/admin/certificates/${activeCertId}/revoke`" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2">
                                Alasan Resmi Pencabutan (Wajib)
                            </label>
                            <textarea name="revocation_reason" rows="3" required placeholder="Contoh: Ditemukan pemalsuan data kehadiran atau pelanggaran berat integritas..." class="w-full text-xs rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-950 p-3.5 focus:ring-2 focus:ring-red-500/30 focus:border-red-500 font-medium"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" @click="showRevokeModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-black text-xs rounded-xl transition shadow-lg shadow-red-600/30">
                                Konfirmasi Cabut Sertifikat
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
