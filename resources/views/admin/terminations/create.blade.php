<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.applications.show', $application) }}" class="w-10 h-10 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center justify-center transition shadow-2xs">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                    Terbitkan Surat Rekomendasi Kerja / Paklaring / PHK
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Karyawan: <strong>{{ $employeeName }}</strong> • Posisi: <strong>{{ $jobTitle }}</strong></p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 dark:bg-slate-900 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 dark:border-slate-700 space-y-6">
                <div class="border-b border-slate-100 dark:border-slate-700 pb-4 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Formulir Penerbitan Dokumen Pengalaman Kerja / Rekomendasi / PHK</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pilih jenis dokumen resmi yang ingin diterbitkan oleh manajemen perusahaan.</p>
                    </div>
                    <span id="terminationModeBadge" class="px-3 py-1 bg-slate-900 dark:bg-indigo-950 text-white dark:text-indigo-300 text-3xs font-black rounded-xl uppercase border border-slate-800 dark:border-indigo-800 shrink-0 transition-all shadow-2xs">
                        🌟 Mode Rekomendasi Kerja
                    </span>
                </div>

                <form action="{{ route('admin.applications.terminations.store', $application) }}" method="POST" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs">
                            <div class="font-bold flex items-center gap-2 mb-1">
                                <i class="fa-solid fa-circle-exclamation text-rose-500"></i> Terjadi kesalahan pengisian formulir:
                            </div>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Jenis Dokumen Resmi yang Diterbitkan <span class="text-rose-500">*</span></label>
                            <select id="terminationTypeSelect" name="document_type" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-indigo-500 focus:border-indigo-500 font-bold py-2.5">
                                <option value="recommendation_letter" selected>🌟 Surat Rekomendasi Kerja & Referensi Karir (Job Recommendation Letter)</option>
                                <option value="paklaring_letter">📜 Surat Keterangan Pengalaman Kerja (Paklaring / Service Certificate)</option>
                                <option value="phk_letter">🔴 Surat Pemutusan Hubungan Kerja (PHK & Uang Pesangon)</option>
                                <option value="contract_expired">🟡 Surat Keterangan Selesai Masa Kontrak Kerja</option>
                            </select>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase">Nomor Dokumen Surat <span class="text-rose-500">*</span></label>
                                <span class="text-3xs text-slate-400 dark:text-slate-500 font-medium flex items-center gap-1"><i class="fa-solid fa-lock text-3xs"></i> Otomatis & Terkunci</span>
                            </div>
                            <div class="relative">
                                <input type="text" id="documentNumberInput" name="document_number" value="{{ old('document_number', $docNumber) }}" readonly required class="w-full border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold py-2.5 pl-3 pr-9 cursor-not-allowed select-none focus:ring-0 focus:border-slate-300 dark:focus:border-slate-600">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Nama Lengkap Karyawan <span class="text-rose-500">*</span></label>
                            <input type="text" name="employee_name" value="{{ old('employee_name', $employeeName) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-indigo-500 focus:border-indigo-500 font-bold py-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Posisi / Jabatan Pekerjaan <span class="text-rose-500">*</span></label>
                            <input type="text" name="job_title" value="{{ old('job_title', $jobTitle) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-indigo-500 focus:border-indigo-500 font-bold py-2.5">
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Tgl Mulai Kerja <span class="text-rose-500">*</span></label>
                                <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d', strtotime('-1 year'))) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-medium py-2.5">
                            </div>
                            <div>
                                <label class="block text-3xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Tgl Terakhir Kerja <span class="text-rose-500">*</span></label>
                                <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-medium py-2.5">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Nominal Pesangon / Uang Kompensasi PHK (Opsional)</label>
                            <input type="text" name="severance_compensation" value="{{ old('severance_compensation') }}" placeholder="Contoh: Rp 15.000.000 (Kosongkan jika Surat Rekomendasi/Paklaring)" class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-medium py-2.5">
                        </div>

                        <!-- RECOMMENDATION & STATEMENT TEXT AREA -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Teks Pernyataan Resmi / Ulasan Rekomendasi Karir <span class="text-rose-500">*</span></label>
                            <textarea id="reasonNotesTextarea" name="reason_or_recommendation_notes" rows="6" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-indigo-500 focus:border-indigo-500 font-mono p-4 leading-relaxed">{{ old('reason_or_recommendation_notes', "Perusahaan memberikan Surat Rekomendasi & Referensi Kerja ini secara resmi kepada yang bersangkutan.\n\nSelama bertugas sebagai {$jobTitle}, yang bersangkutan telah menunjukkan dedikasi, integritas, dan performa kerja yang sangat baik bagi perkembangan perusahaan.\n\nManajemen Perusahaan dengan bangga memberikan REKOMENDASI TERBAIK bagi yang bersangkutan untuk dapat berkarir dan memberikan kontribusi di perusahaan/instansi manapun di masa mendatang.") }}</textarea>
                        </div>

                        <div>
                            <label class="block text-3xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Nama HRD Manager</label>
                            <input type="text" name="hr_name" value="{{ old('hr_name', Auth::user()->name) }}" placeholder="HR Manager" class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2">
                        </div>

                        <div>
                            <label class="block text-3xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Nama Owner / Direktur Perusahaan</label>
                            <input type="text" name="owner_name" value="{{ old('owner_name', 'Ir. Budi Santoso') }}" placeholder="Direktur Utama" class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs font-bold py-2">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Tanggal Penerbitan Dokumen <span class="text-rose-500">*</span></label>
                            <input type="date" name="issued_at" value="{{ old('issued_at', date('Y-m-d')) }}" required class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-indigo-500 focus:border-indigo-500 font-medium py-2.5">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.applications.show', $application) }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-xl border border-slate-300 dark:border-slate-600 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs rounded-xl shadow-2xs transition border border-indigo-600 flex items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-xs"></i> Terbitkan & Render Dokumen PDF
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const employeeName = @json($employeeName);
            const jobTitle = @json($jobTitle);

            const docData = {
                recommendation_letter: {
                    badge: '🌟 Mode Rekomendasi Kerja',
                    prefix: 'SKK-REKOM/',
                    notes: `Perusahaan memberikan Surat Rekomendasi & Referensi Kerja ini secara resmi kepada yang bersangkutan. 

Selama bertugas sebagai ${jobTitle}, yang bersangkutan telah menunjukkan dedikasi, integritas, dan performa kerja yang sangat baik bagi perkembangan perusahaan.

Manajemen Perusahaan dengan bangga memberikan REKOMENDASI TERBAIK bagi yang bersangkutan untuk dapat berkarir dan memberikan kontribusi di perusahaan/instansi manapun di masa mendatang.`
                },
                paklaring_letter: {
                    badge: '📜 Mode Surat Paklaring',
                    prefix: 'PAKLARING/',
                    notes: `Surat Keterangan Pengalaman Kerja (Paklaring) ini diterbitkan untuk menerangkan bahwa Sdr/i. ${employeeName} benar telah bekerja pada perusahaan kami sebagai ${jobTitle}.

Selama masa kerja, yang bersangkutan telah menyelesaikan seluruh tanggung jawab dan tugas dengan sangat baik dan penuh integritas.

Kami mengucapkan terima kasih yang sebesar-besarnya atas kontribusi dan dedikasi yang telah diberikan selama masa pengabdian di perusahaan.`
                },
                phk_letter: {
                    badge: '🔴 Mode Surat PHK',
                    prefix: 'PHK/',
                    notes: `Manajemen Perusahaan secara resmi menyampaikan Keputusan Pemutusan Hubungan Kerja (PHK) kepada Sdr/i. ${employeeName} selaku ${jobTitle} terhitung sejak tanggal efektif yang tercantum.

Segala hak penyelesaian hubungan kerja berupa uang pesangon, kompensasi sisa cuti, dan penghargaan masa kerja diselesaikan sesuai dengan ketentuan peraturan perundang-undangan ketenagakerjaan yang berlaku.`
                },
                contract_expired: {
                    badge: '🟡 Mode Selesai Masa Kontrak',
                    prefix: 'SKK-HABIS/',
                    notes: `Diterangkan dengan sebenarnya bahwa masa Perjanjian Kerja Waktu Tertentu (PKWT) atas nama Sdr/i. ${employeeName} untuk posisi ${jobTitle} telah berakhir dan selesai masa berlakunya.

Perusahaan mengucapkan terima kasih dan apresiasi setinggi-tingginya atas seluruh dedikasi, kerja keras, dan profesionalisme yang telah ditunjukkan selama masa kontrak berlangsung.`
                }
            };

            const docSuffix = "{{ date('Y/m/') }}APP{{ sprintf('%04d', $application->id) }}-U{{ sprintf('%04d', $application->user_id) }}";
            const selectEl = document.getElementById('terminationTypeSelect');
            const badgeEl = document.getElementById('terminationModeBadge');
            const docNumberEl = document.getElementById('documentNumberInput');
            const notesEl = document.getElementById('reasonNotesTextarea');

            function updateTerminationForm(type, isInit = false) {
                const data = docData[type];
                if (!data) return;

                badgeEl.innerText = data.badge;
                docNumberEl.value = data.prefix + docSuffix;

                if (!isInit || !notesEl.value.trim()) {
                    notesEl.value = data.notes;
                }
            }

            selectEl.addEventListener('change', function () {
                updateTerminationForm(this.value, false);
            });

            // Initial load
            updateTerminationForm(selectEl.value, true);
        });
    </script>
</x-app-layout>
