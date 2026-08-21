<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-folder-closed text-slate-700"></i> Vault Berkas & Dokumen Pendukung
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola ijazah, transkrip nilai, SKCK, KTP, sertifikasi, dan portofolio Anda.</p>
            </div>
            <a href="{{ route('profile.candidate.details.edit') }}" class="bg-white hover:bg-slate-50 text-slate-700 font-bold py-2 px-3.5 rounded-xl border border-slate-300 text-xs transition shadow-2xs">
                &larr; Kembali ke Profil Saya
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/80 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- GLOBAL PREVIEW POP-UP MODAL (Photos & Documents) -->
            <div x-data="{ open: false, title: '', url: '', isImage: true }"
                 @open-preview-modal.window="open = true; title = $event.detail.title; url = $event.detail.url; isImage = $event.detail.isImage"
                 x-show="open"
                 x-cloak
                 class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-xs"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full overflow-hidden relative flex flex-col max-h-[90vh]" @click.away="open = false">
                    <!-- Modal Header -->
                    <div class="bg-slate-900 px-6 py-4 flex items-center justify-between text-white shrink-0 border-b border-slate-800">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-file-lines text-slate-400 text-lg"></i>
                            <h3 class="font-bold text-base sm:text-lg text-slate-100" x-text="title"></h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <a :href="url" target="_blank" download class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-bold transition flex items-center gap-1.5 border border-slate-700">
                                <i class="fa-solid fa-download text-slate-400"></i> Unduh File
                            </a>
                            <button type="button" @click="open = false" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center transition font-bold text-xl border border-slate-700">
                                &times;
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content Body (Image / PDF Iframe) -->
                    <div class="p-4 sm:p-6 overflow-y-auto flex-1 flex items-center justify-center bg-slate-100/60 min-h-[380px]">
                        <template x-if="isImage">
                            <img :src="url" :alt="title" class="max-h-[75vh] max-w-full object-contain rounded-xl shadow-md border border-slate-200 bg-white">
                        </template>
                        <template x-if="!isImage">
                            <iframe :src="url" class="w-full h-[75vh] rounded-xl border border-slate-300 shadow-inner bg-white"></iframe>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Documents Vault Grid Section -->
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                    <h3 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-box-archive text-slate-700"></i> Berkas Terunggah di Vault Saya ({{ $documents->count() }})
                    </h3>
                </div>

                @if($documents->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($documents as $doc)
                            <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-2xs hover:shadow-md transition flex flex-col justify-between space-y-4">
                                <div class="space-y-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <span class="px-2.5 py-0.5 font-bold text-3xs rounded-md border uppercase {{ $doc->type_badge_color }}">
                                            {{ $doc->type_label }}
                                        </span>
                                        <span class="text-3xs font-semibold text-slate-400">
                                            {{ strtoupper($doc->file_extension ?? 'PDF') }} • {{ $doc->formatted_size }}
                                        </span>
                                    </div>

                                    <div>
                                        <h4 class="font-bold text-slate-900 text-base leading-snug">
                                            {{ $doc->title }}
                                        </h4>
                                        <p class="text-3xs text-slate-400 mt-1 font-medium">Diunggah: {{ ($doc->updated_at ?? $doc->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                                    <button type="button" 
                                            onclick="const url = '{{ Storage::url($doc->file_path) }}'; const isImage = /\.(jpg|jpeg|png|webp)$/i.test(url); window.dispatchEvent(new CustomEvent('open-preview-modal', { detail: { title: '{{ addslashes($doc->title) }}', url: url, isImage: isImage } }))"
                                            class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs py-2 px-3 rounded-xl transition border border-slate-900 flex items-center justify-center gap-1.5 shadow-2xs">
                                        <i class="fa-solid fa-eye text-slate-300"></i> Pratinjau Pop-up
                                    </button>

                                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank" download class="p-2 text-slate-700 hover:bg-slate-100 rounded-xl transition border border-slate-200" title="Unduh Berkas">
                                        <i class="fa-solid fa-download"></i>
                                    </a>

                                    <form action="{{ route('profile.candidate.documents.destroy', $doc->encrypted_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus berkas dokumen ini dari Vault?');" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition border border-rose-200" title="Hapus Berkas">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 space-y-3">
                        <div class="w-14 h-14 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center text-xl mx-auto">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">Vault Dokumen Masih Kosong</h3>
                        <p class="text-xs text-slate-500">Anda dapat mengunggah berkas dokumen Anda melalui halaman <a href="{{ route('profile.candidate.details.edit') }}" class="text-slate-900 underline font-bold">Lengkapi Profil Candidate (Dokumen Vault)</a>.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
