<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-envelope-open-text text-amber-500"></i> Template Email HR & Auto-Broadcaster
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola draf email resmi perusahaan, undangan wawancara, penolakan halus, dan kirim email instan ke pelamar.</p>
            </div>
            <div class="flex items-center gap-2" x-data="{ createModal: false, broadcastModal: false }">
                <button type="button" @click="$dispatch('open-broadcast-modal')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-2.5 px-4 rounded-xl text-xs shadow-2xs transition flex items-center gap-1.5">
                    <i class="fa-solid fa-paper-plane"></i> 🚀 Kirim Email Instan ke Pelamar
                </button>
                <button type="button" @click="$dispatch('open-create-modal')" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-2.5 px-4 rounded-xl text-xs shadow-2xs transition flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> + Tambah Template Baru
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/60 min-h-screen" x-data="emailTemplatePage()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- DataTables Filter & Search Bar -->
            <div class="bg-white p-5 rounded-3xl shadow-2xs border border-slate-200/80">
                <form method="GET" action="{{ route('admin.email-templates.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1 text-3xs">Cari Template Email</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama template, subjek, atau kata kunci..." class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 pl-9">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1 text-3xs">Baris per Halaman</label>
                        <select name="per_page" onchange="this.form.submit()" class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-blue-500 focus:border-blue-500">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris per Halaman</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris per Halaman</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris per Halaman</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris per Halaman</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Templates List Cards & Table -->
            <div class="bg-white overflow-hidden shadow-2xs rounded-3xl border border-slate-200/80">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 font-extrabold text-slate-600 uppercase tracking-wider text-3xs">
                                    <th class="p-4">Nama & Tipe Template</th>
                                    <th class="p-4">Subjek Email</th>
                                    <th class="p-4">Pratinjau Isi Email</th>
                                    <th class="p-4 text-center">Aksi Operasional</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($templates as $tpl)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="p-4">
                                            <div class="font-extrabold text-slate-900 text-sm">{{ $tpl->name }}</div>
                                            <div class="mt-1">
                                                @if($tpl->type === 'screening')
                                                    <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 font-black rounded-lg text-3xs uppercase">🔍 HR Screening</span>
                                                @elseif($tpl->type === 'test_invitation' || $tpl->type === 'test_reminder')
                                                    <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-800 font-black rounded-lg text-3xs uppercase">🧠 Tahap Tes & Psikotes</span>
                                                @elseif($tpl->type === 'interview')
                                                    <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 font-black rounded-lg text-3xs uppercase">📅 Wawancara Kerja</span>
                                                @elseif($tpl->type === 'offering')
                                                    <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-black rounded-lg text-3xs uppercase">📜 Penawaran Kerja (Offer)</span>
                                                @elseif($tpl->type === 'background_check')
                                                    <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 font-black rounded-lg text-3xs uppercase">📄 Background Check</span>
                                                @elseif($tpl->type === 'reminder')
                                                    <span class="px-2.5 py-0.5 bg-sky-100 text-sky-800 font-black rounded-lg text-3xs uppercase">⏰ Pengingat Sesi</span>
                                                @elseif($tpl->type === 'rejection')
                                                    <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 font-black rounded-lg text-3xs uppercase">💌 Penolakan Halus</span>
                                                @else
                                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-800 font-black rounded-lg text-3xs uppercase">✉️ Umum</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="p-4 font-bold text-slate-800 max-w-xs truncate">
                                            {{ $tpl->subject }}
                                        </td>
                                        <td class="p-4 text-slate-500 max-w-md truncate leading-relaxed">
                                            {{ substr(strip_tags($tpl->body_content), 0, 90) }}...
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button" @click="useTemplate({{ json_encode($tpl) }})" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl border border-indigo-200 transition flex items-center gap-1" title="Gunakan untuk kirim email">
                                                    <i class="fa-solid fa-paper-plane text-xs"></i> Kirim
                                                </button>

                                                <button type="button" @click="editTemplate({{ json_encode($tpl) }})" class="p-2 text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-100 transition" title="Edit Template">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>

                                                <form action="{{ route('admin.email-templates.destroy', $tpl->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus template email ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-rose-600 bg-rose-50 rounded-xl hover:bg-rose-100 transition" title="Hapus Template">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-12 text-center text-slate-400">
                                            Belum ada template email HR yang dibuat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($templates, 'hasPages') && $templates->hasPages())
                        <div class="mt-6 border-t border-slate-100 pt-4">
                            {{ $templates->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- MODAL 1: CREATE NEW TEMPLATE -->
        <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4" style="display: none;">
            <div @click.away="showCreateModal = false" class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="font-black text-lg text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-plus-circle text-blue-600"></i> Buat Template Email HR Baru
                    </h3>
                    <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('admin.email-templates.store') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Template</label>
                            <input type="text" name="name" required placeholder="Misal: Undangan Interview Tahap 2" class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kategori Tipe</label>
                            <select name="type" required class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-blue-500">
                                <option value="screening">🔍 HR Screening Call</option>
                                <option value="test_invitation">🧠 Tahap Tes & Psikotes</option>
                                <option value="interview">📅 Wawancara Kerja</option>
                                <option value="offering">📜 Penawaran Kerja (Offer)</option>
                                <option value="background_check">📄 Background Check</option>
                                <option value="reminder">⏰ Pengingat Sesi</option>
                                <option value="rejection">💌 Penolakan Halus</option>
                                <option value="general">✉️ Umum</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Subjek Email</label>
                        <input type="text" name="subject" required placeholder="Misal: Undangan Wawancara Kerja - Posisi {job_title}" class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Isi Pesan Email (Content Body)</label>
                        <textarea name="body_content" rows="6" required placeholder="Tuliskan isi email... Gunakan tag otomatis: {candidate_name}, {job_title}, {company_name}" class="w-full border-slate-300 rounded-xl text-xs font-medium focus:ring-blue-500 leading-relaxed"></textarea>
                        <p class="text-3xs text-slate-400 mt-1">Tag Otomatis: <code>{candidate_name}</code>, <code>{job_title}</code>, <code>{company_name}</code></p>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-xl text-xs shadow-2xs">Simpan Template</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 2: EDIT TEMPLATE -->
        <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4" style="display: none;">
            <div @click.away="showEditModal = false" class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="font-black text-lg text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-blue-600"></i> Edit Template Email HR
                    </h3>
                    <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form :action="editFormUrl" method="POST" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Template</label>
                            <input type="text" name="name" x-model="activeTemplate.name" required class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kategori Tipe</label>
                            <select name="type" x-model="activeTemplate.type" required class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-blue-500">
                                <option value="interview">📅 Undangan Wawancara</option>
                                <option value="rejection">💌 Penolakan Halus</option>
                                <option value="test_reminder">⏱️ Pengingat Tes</option>
                                <option value="general">✉️ Umum</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Subjek Email</label>
                        <input type="text" name="subject" x-model="activeTemplate.subject" required class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Isi Pesan Email (Content Body)</label>
                        <textarea name="body_content" rows="6" x-model="activeTemplate.body_content" required class="w-full border-slate-300 rounded-xl text-xs font-medium focus:ring-blue-500 leading-relaxed"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-xl text-xs shadow-2xs">Perbarui Template</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 3: BROADCAST / SEND EMAIL TO CANDIDATE -->
        <div x-show="showBroadcastModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4" style="display: none;">
            <div @click.away="showBroadcastModal = false" class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="font-black text-lg text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-indigo-600"></i> Kirim Email Instan Ke Candidate
                    </h3>
                    <button type="button" @click="showBroadcastModal = false" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('admin.email-templates.broadcast') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pilih Pelamar Penerima Email</label>
                        <select name="application_id" @change="onCandidateSelect($event)" required class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-indigo-500">
                            <option value="">-- Pilih Candidate Pelamar --</option>
                            @foreach($applications as $app)
                                <option value="{{ $app->id }}" data-name="{{ $app->user->name }}" data-job="{{ $app->job->title }}" data-company="{{ $app->job->company_name }}">
                                    {{ $app->user->name }} — {{ $app->job->title }} ({{ $app->user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Subjek Email</label>
                        <input type="text" name="subject" x-model="broadcastSubject" required class="w-full border-slate-300 rounded-xl text-xs font-bold focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Isi Pesan Email</label>
                        <textarea name="body_content" rows="6" x-model="broadcastBody" required class="w-full border-slate-300 rounded-xl text-xs font-medium focus:ring-indigo-500 leading-relaxed"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showBroadcastModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl text-xs shadow-2xs flex items-center gap-1">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Email Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>

<script>
    function emailTemplatePage() {
        return {
            showCreateModal: false,
            showEditModal: false,
            showBroadcastModal: false,
            activeTemplate: {},
            editFormUrl: '',
            broadcastSubject: '',
            broadcastBody: '',
            selectedCandidate: { name: '', job: '', company: '' },

            init() {
                window.addEventListener('open-create-modal', () => {
                    this.showCreateModal = true;
                });
                window.addEventListener('open-broadcast-modal', () => {
                    this.showBroadcastModal = true;
                });
            },

            editTemplate(tpl) {
                this.activeTemplate = { ...tpl };
                this.editFormUrl = `/admin/email-templates/${tpl.id}`;
                this.showEditModal = true;
            },

            useTemplate(tpl) {
                this.activeTemplate = { ...tpl };
                this.broadcastSubject = tpl.subject;
                this.broadcastBody = tpl.body_content;
                this.showBroadcastModal = true;
            },

            onCandidateSelect(e) {
                const opt = e.target.options[e.target.selectedIndex];
                if (opt && opt.value) {
                    const cName = opt.getAttribute('data-name');
                    const cJob = opt.getAttribute('data-job');
                    const cCompany = opt.getAttribute('data-company');

                    if (this.activeTemplate && this.activeTemplate.subject) {
                        this.broadcastSubject = this.activeTemplate.subject
                            .replace(/{candidate_name}/g, cName)
                            .replace(/{job_title}/g, cJob)
                            .replace(/{company_name}/g, cCompany);
                        
                        this.broadcastBody = this.activeTemplate.body_content
                            .replace(/{candidate_name}/g, cName)
                            .replace(/{job_title}/g, cJob)
                            .replace(/{company_name}/g, cCompany);
                    }
                }
            }
        }
    }
</script>
