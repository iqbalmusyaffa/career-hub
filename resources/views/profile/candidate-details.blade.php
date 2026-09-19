<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-900 leading-tight">
            {{ __('Resume & Profil Lengkap') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10 bg-slate-50/80 dark:bg-slate-900 min-h-screen transition-colors">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Bangun Profil Profesional Anda</h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500 dark:text-slate-400">Isi secara lengkap untuk meningkatkan visibilitas profil dan lamaran Anda.</p>
                </div>
                
                <a href="{{ route('profile.candidate.documents.index') }}" class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white font-bold text-xs rounded-xl shadow-2xs transition border border-slate-900 dark:border-slate-700">
                    <i class="fa-solid fa-folder-closed text-slate-400"></i> Buka Vault Dokumen &rarr;
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl shadow-2xs text-xs">
                    <p class="font-bold text-sm">Ada beberapa kesalahan saat menyimpan data:</p>
                    <ul class="list-disc list-inside mt-1 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $profile = $user->candidateProfile;
                $defaultArray = '[{}]';
                $defaultObject = '{}';
                
                $getOldOrDb = function($field, $default) use ($profile) {
                    $old = old($field);
                    if ($old !== null) return json_encode($old);
                    if ($profile && $profile->$field) return json_encode($profile->$field);
                    return $default;
                };
            @endphp

            <!-- GLOBAL PREVIEW POP-UP MODAL (Photos & Documents) -->
            <div x-data="{ open: false, title: '', url: '', isImage: true }"
                 @open-preview-modal.window="open = true; title = $event.detail.title; url = $event.detail.url; isImage = $event.detail.isImage"
                 x-show="open"
                 x-cloak
                 class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-xs"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-4xl w-full overflow-hidden relative flex flex-col max-h-[90vh]" @click.away="open = false">
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
                    <div class="p-4 sm:p-6 overflow-y-auto flex-1 flex items-center justify-center bg-slate-100/60 dark:bg-slate-900/60 min-h-[380px]">
                        <template x-if="isImage">
                            <img :src="url" :alt="title" class="max-h-[75vh] max-w-full object-contain rounded-xl shadow-md border border-slate-200 dark:border-slate-700 bg-white">
                        </template>
                        <template x-if="!isImage">
                            <iframe :src="url" class="w-full h-[75vh] rounded-xl border border-slate-300 dark:border-slate-700 shadow-inner bg-white"></iframe>
                        </template>
                    </div>
                </div>
            </div>

            <div x-data="resumeForm()" class="pb-12 space-y-6">
                <!-- TOP ANIMATED CLEAN STEP NUMBER WIZARD -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-6 shadow-2xs border border-slate-200 dark:border-slate-700 relative overflow-hidden transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-4 sm:mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-slate-900 dark:bg-slate-700 text-white font-black text-base sm:text-lg flex items-center justify-center shadow-2xs shrink-0">
                                <span x-text="currentStepIndex + 1"></span>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-3xs sm:text-2xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700/60 px-2 sm:px-2.5 py-0.5 rounded-md border border-slate-200 dark:border-slate-600">
                                        Langkah <span x-text="currentStepIndex + 1"></span> dari <span x-text="tabs.length"></span>
                                    </span>
                                    <span class="text-3xs sm:text-xs font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 sm:px-2.5 py-0.5 rounded-md border border-emerald-200 dark:border-emerald-800" x-text="liveCompletionPercentage + '% Selesai'"></span>
                                </div>
                                <h3 class="text-sm sm:text-base lg:text-lg font-bold text-slate-900 dark:text-white mt-0.5 truncate" x-text="tabs[currentStepIndex]?.label"></h3>
                            </div>
                        </div>
                    </div>

                    <!-- Clean Horizontal Step Timeline (Numbers Only 1..11) Scrollable on Mobile -->
                    <div class="overflow-x-auto scrollbar-none -mx-2 px-2 pb-1">
                        <div class="relative flex items-center justify-between px-2 sm:px-4 py-2 min-w-[520px] sm:min-w-0">
                            <!-- Background Line -->
                            <div class="absolute left-6 right-6 top-1/2 -translate-y-1/2 h-1 bg-slate-100 dark:bg-slate-700 rounded-full z-0"></div>
                            <!-- Progress Active Line -->
                            <div class="absolute left-6 top-1/2 -translate-y-1/2 h-1 bg-blue-600 rounded-full transition-all duration-500 ease-out z-0"
                                 :style="`width: max(0%, min(calc(100% - 3rem), ${liveCompletionPercentage}%))`"></div>

                            <template x-for="(t, idx) in tabs" :key="idx">
                                <button type="button" @click="activeTab = t.id" class="relative z-10 flex flex-col items-center shrink-0 cursor-pointer select-none focus:outline-none transition-transform hover:scale-110">
                                    <div :class="{
                                            'w-9 h-9 bg-blue-600 text-white font-bold shadow-2xs ring-4 ring-blue-100 dark:ring-blue-900/60 scale-105': activeTab === t.id,
                                            'w-8 h-8 bg-emerald-600 text-white font-bold shadow-2xs': activeTab !== t.id && isStepCompleted(idx),
                                            'w-8 h-8 bg-white dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-medium border border-slate-300 dark:border-slate-600': activeTab !== t.id && !isStepCompleted(idx)
                                        }"
                                        class="rounded-full flex items-center justify-center text-xs transition-all duration-200">
                                        <span x-text="isStepCompleted(idx) && activeTab !== t.id ? '✓' : (idx + 1)"></span>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Responsive Step Pills for Mobile & Tablet (Horizontal Scroll) -->
                <div class="lg:hidden w-full space-y-2">
                    <div class="flex items-center gap-2 overflow-x-auto pb-1.5 scrollbar-none snap-x">
                        <template x-for="(tab, index) in tabs" :key="index">
                            <button type="button" @click="activeTab = tab.id; window.scrollTo({ top: 120, behavior: 'smooth' });"
                                :class="activeTab === tab.id 
                                    ? 'bg-blue-600 text-white shadow-xs font-bold border-blue-600' 
                                    : (isStepCompleted(index) ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700')"
                                class="shrink-0 snap-start px-3 py-1.5 rounded-xl border text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer">
                                <span class="w-4 h-4 rounded-full text-[10px] font-bold flex items-center justify-center shrink-0"
                                      :class="activeTab === tab.id ? 'bg-white/20 text-white' : (isStepCompleted(index) ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : 'bg-slate-100 dark:bg-slate-700 text-slate-500')"
                                      x-text="isStepCompleted(index) && activeTab !== tab.id ? '✓' : (index + 1)"></span>
                                <span x-text="tab.label" class="whitespace-nowrap"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start">
                    <!-- Desktop Sidebar Tabs (Hidden on Mobile/Tablet, Visible on Large Screens) -->
                    <div class="hidden lg:block lg:w-72 shrink-0">
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col transition-colors">
                            <template x-for="(tab, index) in tabs" :key="index">
                                <button type="button" @click="activeTab = tab.id"
                                    :class="activeTab === tab.id 
                                        ? 'bg-slate-100 dark:bg-slate-700/70 text-slate-900 dark:text-white border-l-4 border-slate-900 dark:border-blue-500 font-bold shadow-2xs' 
                                        : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 border-l-4 border-transparent hover:border-slate-300 dark:hover:border-slate-600 font-medium'"
                                    class="px-4 py-3 text-left text-xs sm:text-sm transition-all w-full border-b border-slate-100 dark:border-slate-700/60 last:border-b-0 flex items-center justify-between group cursor-pointer">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-6 h-6 rounded-full text-xs font-bold flex items-center justify-center transition-all duration-200 shrink-0"
                                              :class="activeTab === tab.id 
                                                  ? 'bg-slate-900 dark:bg-blue-600 text-white shadow-2xs' 
                                                  : (isStepCompleted(index) ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 font-bold' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:bg-slate-200 dark:group-hover:bg-slate-600')"
                                              x-text="isStepCompleted(index) && activeTab !== tab.id ? '✓' : (index + 1)"></span>
                                        <span x-text="tab.label" class="text-xs sm:text-sm truncate"></span>
                                    </div>
                                    <svg x-show="activeTab === tab.id" class="w-4 h-4 text-slate-700 dark:text-slate-300 transform transition-transform group-hover:translate-x-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Form Content Area -->
                    <div class="w-full lg:flex-1 min-w-0">
                        <form method="post" action="{{ route('profile.candidate.details.update') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                        <!-- 1. INFORMASI PRIBADI -->
                        <div x-show="activeTab === 1" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                        <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">1</div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">Informasi Pribadi</h2>
                        </div>
                        <div class="p-6 sm:p-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2 flex flex-col sm:flex-row sm:items-center gap-6">
                                    <div class="relative w-24 h-24 rounded-full bg-slate-100 border border-slate-300 flex items-center justify-center overflow-hidden shrink-0 shadow-2xs group cursor-pointer"
                                         @click="const img = document.getElementById('candidate-photo-preview'); if (img && img.src) $dispatch('open-preview-modal', { title: 'Pas Foto Candidate', url: img.src, isImage: true })">
                                        @php
                                            $photoSrc = ($profile && ($profile->photo || $profile->photo_path)) 
                                                ? Storage::url($profile->photo ?? $profile->photo_path) 
                                                : null;
                                        @endphp

                                        <img id="candidate-photo-preview" src="{{ $photoSrc }}" class="w-full h-full object-cover {{ $photoSrc ? '' : 'hidden' }}">
                                        <svg id="candidate-photo-placeholder" class="w-12 h-12 text-slate-400 {{ $photoSrc ? 'hidden' : '' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        
                                        <!-- Photo Hover Overlay -->
                                        <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-2xs text-white opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-3xs font-bold gap-0.5">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                            <span>Pop-up</span>
                                        </div>

                                        <div id="candidate-photo-spinner" class="absolute inset-0 bg-slate-900/70 backdrop-blur-xs flex items-center justify-center text-white hidden">
                                            <i class="fa-solid fa-spinner fa-spin text-2xl"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="photo" :value="__('Pas Foto (Opsional)')" />
                                        <div class="flex flex-wrap items-center gap-2 mt-1">
                                            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewCandidatePhoto(event)" class="text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition cursor-pointer">
                                            <button type="button" 
                                                    id="btn-photo-preview-modal"
                                                    @click="const img = document.getElementById('candidate-photo-preview'); if (img && img.src) $dispatch('open-preview-modal', { title: 'Pas Foto Candidate', url: img.src, isImage: true })"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-2xs transition {{ $photoSrc ? '' : 'hidden' }}">
                                                <i class="fa-solid fa-eye text-slate-300"></i> Lihat Pop-up
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <p class="text-xs text-slate-400">Maksimal 2MB (JPG/PNG/WEBP)</p>
                                            <span id="photo-status-badge" class="hidden text-2xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-200">
                                                ✅ Foto Tersimpan Otomatis!
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="nik" :value="__('NIK KTP')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <x-text-input id="nik" name="nik" required type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('nik', $profile->nik ?? '')" placeholder="16 digit NIK" />
                                </div>
                                <div>
                                    <x-input-label for="name" :value="__('Nama Lengkap (Sesuai KTP)')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white font-semibold text-gray-900" :value="old('name', $user->name ?? '')" required />
                                </div>
                                <div>
                                    <x-input-label for="nickname" :value="__('Nama Panggilan')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <x-text-input id="nickname" name="nickname" required type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('nickname', $profile->nickname ?? '')" />
                                </div>
                                <div>
                                    <x-input-label for="phone" :value="__('Nomor Handphone')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <x-text-input id="phone" name="phone" required type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('phone', $profile->phone ?? '')" />
                                </div>
                                <div>
                                    <x-input-label for="nationality" :value="__('Kewarganegaraan')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <x-text-input id="nationality" name="nationality" required type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('nationality', $profile->nationality ?? '')" />
                                </div>
                                <div>
                                    <x-input-label for="birth_place" :value="__('Tempat Lahir')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <x-text-input id="birth_place" name="birth_place" required type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('birth_place', $profile->birth_place ?? '')" />
                                </div>
                                <div>
                                    <x-input-label for="dob" :value="__('Tanggal Lahir')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <x-text-input id="dob" name="dob" required type="date" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('dob', ($profile && $profile->dob) ? $profile->dob->format('Y-m-d') : '')" />
                                </div>
                            </div>
                            
                            <div>
                                <x-input-label :value="__('Jenis Kelamin')" class="mb-2 after:content-['*'] after:ml-0.5 after:text-red-500" />
                                <div class="grid grid-cols-2 gap-3 sm:gap-4 max-w-md">
                                    <label class="relative flex items-center gap-3 p-3 sm:p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition shadow-2xs group has-checked:border-blue-500 has-checked:bg-blue-50/50 dark:has-checked:bg-blue-950/30 dark:has-checked:border-blue-500">
                                        <input type="radio" name="gender" value="male" required class="w-4 h-4 text-blue-600 bg-slate-100 dark:bg-slate-700 border-slate-300 dark:border-slate-600 focus:ring-blue-500 focus:ring-offset-0" {{ (old('gender', $profile->gender ?? '') === 'male') ? 'checked' : '' }}>
                                        <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 flex items-center gap-2">
                                            <i class="fa-solid fa-mars text-blue-500"></i>
                                            <span>Laki-laki</span>
                                        </span>
                                    </label>
                                    <label class="relative flex items-center gap-3 p-3 sm:p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition shadow-2xs group has-checked:border-pink-500 has-checked:bg-pink-50/50 dark:has-checked:bg-pink-950/30 dark:has-checked:border-pink-500">
                                        <input type="radio" name="gender" value="female" required class="w-4 h-4 text-pink-600 bg-slate-100 dark:bg-slate-700 border-slate-300 dark:border-slate-600 focus:ring-pink-500 focus:ring-offset-0" {{ (old('gender', $profile->gender ?? '') === 'female') ? 'checked' : '' }}>
                                        <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 group-hover:text-pink-600 dark:group-hover:text-pink-400 flex items-center gap-2">
                                            <i class="fa-solid fa-venus text-pink-500"></i>
                                            <span>Perempuan</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="religion" :value="__('Agama')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <select id="religion" name="religion" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white">
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam" {{ old('religion', $profile->religion ?? '') === 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Kristen Protestan" {{ old('religion', $profile->religion ?? '') === 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option>
                                        <option value="Katolik" {{ old('religion', $profile->religion ?? '') === 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                        <option value="Hindu" {{ old('religion', $profile->religion ?? '') === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Buddha" {{ old('religion', $profile->religion ?? '') === 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                        <option value="Konghucu" {{ old('religion', $profile->religion ?? '') === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="marital_status" :value="__('Status Pernikahan')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <select id="marital_status" name="marital_status" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white">
                                        <option value="">Pilih Status</option>
                                        <option value="Lajang" {{ old('marital_status', $profile->marital_status ?? '') === 'Lajang' ? 'selected' : '' }}>Lajang / Belum Menikah</option>
                                        <option value="Menikah" {{ old('marital_status', $profile->marital_status ?? '') === 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                        <option value="Cerai" {{ old('marital_status', $profile->marital_status ?? '') === 'Cerai' ? 'selected' : '' }}>Cerai</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="md:col-span-2 mt-4 pt-4 border-t border-slate-100 dark:border-slate-700" x-data="{ showEmergency: {{ (old('emergency_contact_name', $profile->emergency_contact_name ?? '') || old('emergency_contact_phone', $profile->emergency_contact_phone ?? '')) ? 'true' : 'false' }} }">
                                <div class="flex items-center gap-2 mb-4">
                                    <input type="checkbox" id="toggle_emergency" x-model="showEmergency" class="w-4 h-4 text-blue-600 bg-slate-100 dark:bg-slate-700 border-slate-300 dark:border-slate-600 rounded focus:ring-blue-500">
                                    <label for="toggle_emergency" class="text-sm font-bold text-slate-800 dark:text-slate-200 cursor-pointer">Tambahkan Kontak Darurat (Opsional)</label>
                                </div>
                                <div x-show="showEmergency" class="grid grid-cols-1 md:grid-cols-2 gap-6" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                    <div>
                                        <x-input-label for="emergency_contact_name" :value="__('Nama Kontak Darurat')" />
                                        <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('emergency_contact_name', $profile->emergency_contact_name ?? '')" placeholder="Nama keluarga/kerabat" />
                                    </div>
                                    <div>
                                        <x-input-label for="emergency_contact_phone" :value="__('No. Handphone Darurat')" />
                                        <x-text-input id="emergency_contact_phone" name="emergency_contact_phone" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('emergency_contact_phone', $profile->emergency_contact_phone ?? '')" placeholder="Contoh: 08123456789" />
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{
                                provinces: [],
                                cities: [],
                                provinceName: '{{ old('province', $profile->province ?? '') }}',
                                cityName: '{{ old('city', $profile->city ?? '') }}',
                                showProv: false,
                                showCity: false,
                                provQuery: '{{ old('province', $profile->province ?? '') }}',
                                cityQuery: '{{ old('city', $profile->city ?? '') }}',
                                get filteredProvs() {
                                    return this.provinces.filter(p => p.name.toLowerCase().includes(this.provQuery.toLowerCase()));
                                },
                                get filteredCities() {
                                    return this.cities.filter(c => c.name.toLowerCase().includes(this.cityQuery.toLowerCase()));
                                },
                                init() {
                                    fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                                        .then(res => res.json())
                                        .then(data => {
                                            this.provinces = data;
                                            if (this.provinceName) {
                                                const p = data.find(x => x.name.toUpperCase() === this.provinceName.toUpperCase());
                                                if (p) this.fetchCities(p.id);
                                            }
                                        })
                                        .catch(err => console.error(err));
                                },
                                fetchCities(id) {
                                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${id}.json`)
                                        .then(res => res.json())
                                        .then(data => this.cities = data)
                                        .catch(err => console.error(err));
                                },
                                selectProv(p) {
                                    this.provinceName = p.name;
                                    this.provQuery = p.name;
                                    this.cityName = '';
                                    this.cityQuery = '';
                                    this.showProv = false;
                                    this.fetchCities(p.id);
                                },
                                selectCity(c) {
                                    this.cityName = c.name;
                                    this.cityQuery = c.name;
                                    this.showCity = false;
                                }
                            }">
                                <div class="md:col-span-2">
                                    <x-input-label for="address" :value="__('Alamat Lengkap')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <textarea id="address" name="address" required rows="2" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white">{{ old('address', $profile->address ?? '') }}</textarea>
                                </div>
                                
                                <!-- Provinsi -->
                                <div class="relative">
                                    <x-input-label for="province" :value="__('Provinsi')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <input type="hidden" name="province" x-model="provinceName">
                                    <x-text-input type="text" x-model="provQuery" required 
                                        @focus="showProv = true" 
                                        @input="showProv = true" 
                                        @click.away="showProv = false" 
                                        class="mt-1 block w-full bg-gray-50 focus:bg-white" 
                                        placeholder="Ketik untuk mencari provinsi..." />
                                    
                                    <!-- Dropdown Autocomplete Provinsi -->
                                    <div x-show="showProv && filteredProvs.length > 0" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                                        <template x-for="p in filteredProvs" :key="p.id">
                                            <div @click="selectProv(p)" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm text-gray-700 border-b border-gray-100 last:border-0" x-text="p.name"></div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Kota/Kabupaten -->
                                <div class="relative">
                                    <x-input-label for="city" :value="__('Kota/Kabupaten')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <input type="hidden" name="city" x-model="cityName">
                                    <x-text-input type="text" x-model="cityQuery" required 
                                        @focus="showCity = true" 
                                        @input="showCity = true" 
                                        @click.away="showCity = false" 
                                        x-bind:disabled="!provinceName"
                                        class="mt-1 block w-full bg-gray-50 focus:bg-white disabled:bg-gray-100 disabled:cursor-not-allowed" 
                                        placeholder="Pilih provinsi terlebih dahulu..." />
                                    
                                    <!-- Dropdown Autocomplete Kota -->
                                    <div x-show="showCity && filteredCities.length > 0" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                                        <template x-for="c in filteredCities" :key="c.id">
                                            <div @click="selectCity(c)" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm text-gray-700 border-b border-gray-100 last:border-0" x-text="c.name"></div>
                                        </template>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="district" :value="__('Kecamatan')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <x-text-input id="district" name="district" required type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('district', $profile->district ?? '')" />
                                </div>
                                <div>
                                    <x-input-label for="postal_code" :value="__('Kode Pos')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <x-text-input id="postal_code" name="postal_code" required type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" :value="old('postal_code', $profile->postal_code ?? '')" placeholder="Cth: 12345" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. RINGKASAN PROFIL -->
                    <div x-show="activeTab === 2" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                        <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">2</div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">Ringkasan Profil (Professional Summary)</h2>
                        </div>
                        <div class="p-6 sm:p-8">
                            <textarea name="summary" required rows="4" class="mt-1 block w-full border-slate-300 dark:border-slate-700 rounded-xl shadow-2xs focus:border-slate-800 focus:ring-slate-800 bg-slate-50/50 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-900 text-xs sm:text-sm text-slate-900 dark:text-white font-medium" placeholder="Deskripsikan diri Anda, pengalaman utama, dan kelebihan Anda secara singkat...">{{ old('summary', $profile->summary ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- 3. PENDIDIKAN -->
                    <div x-show="activeTab === 3" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                        <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">3</div>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white">Riwayat Pendidikan</h2>
                            </div>
                            <button type="button" @click="addEdu" class="text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 transition cursor-pointer">+ Tambah</button>
                        </div>
                        <div class="p-6 sm:p-8 space-y-6">
                            <template x-for="(item, index) in educations" :key="index">
                                <div class="p-6 border border-gray-200 rounded-xl bg-gray-50">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="font-bold text-gray-700">Pendidikan #<span x-text="index + 1"></span></h3>
                                        <button type="button" @click="removeEdu(index)" x-show="educations.length > 1" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1 text-sm rounded-lg transition-colors">Hapus</button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jenjang</label>
                                            <select x-model="item.level" :name="`educations[${index}][level]`" class="mt-1 block w-full border-gray-300 rounded-md">
                                                <option value="">Pilih Jenjang</option>
                                                <option value="SMA">SMA</option>
                                                <option value="SMK">SMK</option>
                                                <option value="D3">D3</option>
                                                <option value="S1">S1 / D4</option>
                                                <option value="S2">S2</option>
                                                <option value="S3">S3</option>
                                            </select>
                                        </div>
                                        <div x-data="{ 
                                            showDropdown: false, 
                                            isSearching: false,
                                            searchResults: [],
                                            searchSchools() {
                                                const query = (item.institution || '').trim().toLowerCase();
                                                
                                                if (['D3', 'S1', 'S2', 'S3'].includes(item.level)) {
                                                    this.searchResults = universities.filter(u => u.toLowerCase().includes(query)).slice(0, 30);
                                                } else if (['SMA', 'SMK'].includes(item.level) && query.length >= 3) {
                                                    this.isSearching = true;
                                                    fetch(`https://api-sekolah-indonesia.vercel.app/sekolah/s?sekolah=${encodeURIComponent(query)}`)
                                                        .then(res => res.json())
                                                        .then(data => {
                                                            if (data && data.dataSekolah) {
                                                                // Filter by level (SMA or SMK)
                                                                let schools = data.dataSekolah;
                                                                schools = schools.filter(s => {
                                                                    const name = (s.sekolah || '').toUpperCase();
                                                                    const type = (s.bentuk || '').toUpperCase();
                                                                    return type === item.level || name.includes(item.level);
                                                                });
                                                                this.searchResults = schools.map(s => s.sekolah);
                                                            } else {
                                                                this.searchResults = [];
                                                            }
                                                        })
                                                        .catch(err => { console.error(err); this.searchResults = []; })
                                                        .finally(() => this.isSearching = false);
                                                } else {
                                                    this.searchResults = [];
                                                }
                                            }
                                        }" class="relative">
                                            <label class="block text-sm font-medium text-gray-700">Institusi / Sekolah</label>
                                            <input type="text" 
                                                x-model="item.institution" 
                                                @focus="showDropdown = true; searchSchools()"
                                                @click.away="showDropdown = false"
                                                @input.debounce.500ms="searchSchools()"
                                                :name="`educations[${index}][institution]`" 
                                                autocomplete="off" 
                                                placeholder="Ketik nama kampus / sekolah..." 
                                                class="mt-1 block w-full border-gray-300 rounded-md">
                                            
                                            <ul x-show="showDropdown" 
                                                x-transition.opacity.duration.200ms
                                                class="absolute z-50 w-full bg-white border border-gray-300 mt-1 rounded-md shadow-lg max-h-60 overflow-y-auto"
                                                style="display: none;">
                                                <template x-for="uni in searchResults" :key="uni">
                                                    <li @click="item.institution = uni; showDropdown = false"
                                                        class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm text-gray-700 border-b border-gray-50 last:border-0"
                                                        x-text="uni"></li>
                                                </template>
                                                <li x-show="isSearching" class="px-4 py-3 text-sm text-blue-500 italic text-center font-semibold">Sedang mencari...</li>
                                                <li x-show="!isSearching && searchResults.length === 0 && (item.institution || '').length < 3" 
                                                    class="px-4 py-3 text-sm text-gray-500 italic text-center">
                                                    Ketik nama untuk mencari...
                                                </li>
                                                <li x-show="!isSearching && searchResults.length === 0 && (item.institution || '').length >= 3" 
                                                    class="px-4 py-3 text-sm text-gray-500 italic text-center">
                                                    Tidak ditemukan. Anda tetap bisa menyimpannya secara manual!
                                                </li>
                                            </ul>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Jurusan / Program Studi</label>
                                            <input type="text" x-model="item.major" list="indonesia-majors-list" :name="`educations[${index}][major]`" placeholder="Cari / Pilih Jurusan (Misal: Teknik Informatika, Akuntansi...)" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <x-indonesia-majors-datalist />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Gelar Akademis (Opsional)</label>
                                            <input type="text" x-model="item.degree" :name="`educations[${index}][degree]`" placeholder="Contoh: S.Kom / M.T / B.Sc / A.Md" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Kota / Negara Institusi</label>
                                            <input type="text" x-model="item.city" :name="`educations[${index}][city]`" placeholder="Contoh: Depok / Jakarta / Melbourne" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">IPK / Nilai Akhir</label>
                                            <input type="text" x-model="item.gpa" :name="`educations[${index}][gpa]`" placeholder="Contoh: 3.85 / 4.00 atau Nilai Rata-rata 88.5" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1">Tahun Masuk</label>
                                                <input type="text" x-model="item.start_year" :name="`educations[${index}][start_year]`" placeholder="Contoh: 2019" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1">Tahun Lulus</label>
                                                <input type="text" x-model="item.end_year" :name="`educations[${index}][end_year]`" placeholder="Contoh: 2023" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm" :disabled="item.is_current">
                                            </div>
                                        </div>
                                        <div class="flex items-center mt-6">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" value="1" x-model="item.is_current" :name="`educations[${index}][is_current]`" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4">
                                                <span class="ml-2 text-xs font-bold text-gray-700">Masih menempuh pendidikan di sini</span>
                                            </label>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Judul Skripsi / Tesis / Tugas Akhir (Opsional)</label>
                                            <input type="text" x-model="item.thesis_title" :name="`educations[${index}][thesis_title]`" placeholder="Contoh: Penerapan Algoritma Machine Learning pada Sistem..." class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi & Pencapaian Akademis (Opsional)</label>
                                            <textarea x-model="item.description" :name="`educations[${index}][description]`" rows="3" placeholder="Jelaskan predikat Cumlaude, beasiswa yang diterima, penelitian, atau organisasi kemahasiswaan..." class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 4. PENGALAMAN KERJA -->
                    <div x-show="activeTab === 4" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                        <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">4</div>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white">Pengalaman Kerja & Profesional</h2>
                            </div>
                            <button type="button" @click="addExp" class="text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 transition cursor-pointer">+ Tambah Pekerjaan</button>
                        </div>
                        <div class="p-6 sm:p-8 space-y-6">
                            <template x-for="(item, index) in experiences" :key="index">
                                <div class="p-6 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 hover:bg-white dark:hover:bg-slate-900 transition-all duration-200 shadow-2xs space-y-4">
                                    <div class="flex justify-between items-center pb-2 border-b border-slate-200 dark:border-slate-700">
                                        <h3 class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                            <i class="fa-solid fa-briefcase text-slate-600 dark:text-slate-400"></i> Pengalaman Kerja #<span x-text="index + 1"></span>
                                        </h3>
                                        <button type="button" @click="removeExp(index)" class="text-rose-600 dark:text-rose-400 hover:text-rose-800 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 px-3 py-1 text-xs font-bold rounded-lg transition-colors border border-rose-200 dark:border-rose-800 cursor-pointer">Hapus</button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Perusahaan / Instansi</label>
                                            <input type="text" x-model="item.company" :name="`experiences[${index}][company]`" placeholder="Contoh: PT Tech Nova Indonesia" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Jabatan / Posisi</label>
                                            <input type="text" x-model="item.position" :name="`experiences[${index}][position]`" placeholder="Contoh: Senior Fullstack Developer" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Industri Perusahaan</label>
                                            <select x-model="item.industry" :name="`experiences[${index}][industry]`" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                                <option value="">Pilih Industri</option>
                                                <option value="Teknologi / Informasi (IT)">Teknologi / Informasi (IT)</option>
                                                <option value="Perbankan & Keuangan">Perbankan & Keuangan</option>
                                                <option value="Kesehatan & Kebugaran">Kesehatan & Kebugaran</option>
                                                <option value="Pendidikan & Edukasi">Pendidikan & Edukasi</option>
                                                <option value="Manufaktur & Pabrik">Manufaktur & Pabrik</option>
                                                <option value="Retail & E-commerce">Retail & E-commerce</option>
                                                <option value="Konsultan & Layanan Profesional">Konsultan & Layanan Profesional</option>
                                                <option value="Media & Hiburan">Media & Hiburan</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Jenis Pekerjaan</label>
                                            <select x-model="item.type" :name="`experiences[${index}][type]`" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                                <option value="">Pilih Jenis</option>
                                                <option value="Penuh Waktu (Full-time)">Penuh Waktu (Full-time)</option>
                                                <option value="Paruh Waktu (Part-time)">Paruh Waktu (Part-time)</option>
                                                <option value="Kontrak (Contract)">Kontrak (Contract)</option>
                                                <option value="Magang (Internship)">Magang (Internship)</option>
                                                <option value="Freelance">Pekerja Lepas (Freelance)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Lokasi / Kota Workstyle</label>
                                            <input type="text" x-model="item.location" :name="`experiences[${index}][location]`" placeholder="Contoh: Jakarta / Remote (WFH)" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Gaji Terakhir (Opsional)</label>
                                            <input type="text" x-model="item.last_salary" :name="`experiences[${index}][last_salary]`" placeholder="Contoh: Rp 8.500.000 / Bulan" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Mulai Kerja</label>
                                                <input type="date" x-model="item.start_date" :name="`experiences[${index}][start_date]`" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Selesai Kerja</label>
                                                <input type="date" x-model="item.end_date" :name="`experiences[${index}][end_date]`" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs" :disabled="item.is_current">
                                            </div>
                                        </div>
                                        <div class="flex items-center mt-6">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" value="1" x-model="item.is_current" :name="`experiences[${index}][is_current]`" class="rounded border-slate-300 dark:border-slate-600 text-slate-900 shadow-2xs focus:ring-slate-800 w-4 h-4">
                                                <span class="ml-2 text-xs font-bold text-slate-700 dark:text-slate-300">Masih bekerja di sini sampai sekarang</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Atasan / Supervisor (Opsional)</label>
                                            <input type="text" x-model="item.supervisor_name" :name="`experiences[${index}][supervisor_name]`" placeholder="Contoh: Bpk. Hendra Gunawan" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kontak / No. HP Atasan (Opsional)</label>
                                            <input type="text" x-model="item.supervisor_contact" :name="`experiences[${index}][supervisor_contact]`" placeholder="Contoh: 0812-xxxx-xxxx" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alasan Resign / Berhenti (Opsional)</label>
                                            <input type="text" x-model="item.reason_for_leaving" :name="`experiences[${index}][reason_for_leaving]`" placeholder="Contoh: Mencari peluang pengembangan tantangan karier baru" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Pekerjaan & Tanggung Jawab Utama</label>
                                            <textarea x-model="item.description" :name="`experiences[${index}][description]`" rows="3" placeholder="Jelaskan peran harian, alat/teknologi yang digunakan, serta lingkup pekerjaan..." class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs"></textarea>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Prestasi Utama / Achievement (Opsional)</label>
                                            <textarea x-model="item.achievements" :name="`experiences[${index}][achievements]`" rows="2" placeholder="Jelaskan kontribusi khusus seperti peningkatan omset %, efisiensi sistem, atau penghargaan karyawan terbaik..." class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <p x-show="experiences.length === 0" class="text-xs text-slate-500 dark:text-slate-400 text-center py-4">Belum ada pengalaman kerja ditambahkan. Klik <strong>+ Tambah Pekerjaan</strong> untuk menambahkan.</p>
                        </div>
                    </div>

                    <!-- 5. ORGANISASI -->
                    <div x-show="activeTab === 5" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                        <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">5</div>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white">Pengalaman Organisasi & Komunitas</h2>
                            </div>
                            <button type="button" @click="addOrg" class="text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 transition cursor-pointer">+ Tambah Organisasi</button>
                        </div>
                        <div class="p-6 sm:p-8 space-y-6">
                            <template x-for="(item, index) in organizations" :key="index">
                                <div class="p-6 border border-gray-200 rounded-xl bg-gray-50/50 hover:bg-white transition-all duration-200 shadow-2xs space-y-4">
                                    <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                                        <h3 class="font-black text-gray-800 flex items-center gap-2">
                                            <i class="fa-solid fa-users text-blue-600"></i> Organisasi #<span x-text="index + 1"></span>
                                        </h3>
                                        <button type="button" @click="removeOrg(index)" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1 text-sm font-medium rounded-lg transition-colors">Hapus</button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Organisasi / Komunitas</label>
                                            <input type="text" x-model="item.name" :name="`organizations[${index}][name]`" placeholder="Contoh: BEM Universitas / PMI / Karang Taruna" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Jabatan / Posisi</label>
                                            <input type="text" x-model="item.position" :name="`organizations[${index}][position]`" placeholder="Contoh: Ketua Umum / Kepala Divisi Humas" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Tingkat Organisasi</label>
                                            <select x-model="item.level" :name="`organizations[${index}][level]`" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                <option value="">Pilih Tingkat</option>
                                                <option value="Kampus / Sekolah">Kampus / Sekolah</option>
                                                <option value="Daerah / Kota / Kabupaten">Daerah / Kota / Kabupaten</option>
                                                <option value="Provinsi / Regional">Provinsi / Regional</option>
                                                <option value="Nasional">Nasional</option>
                                                <option value="Internasional">Internasional</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi / Kota</label>
                                            <input type="text" x-model="item.location" :name="`organizations[${index}][location]`" placeholder="Contoh: Jakarta / Bandung" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1">Mulai Berintegrasi</label>
                                                <input type="date" x-model="item.start_date" :name="`organizations[${index}][start_date]`" class="mt-1 block w-full border-gray-300 rounded-xl text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1">Selesai Berintegrasi</label>
                                                <input type="date" x-model="item.end_date" :name="`organizations[${index}][end_date]`" class="mt-1 block w-full border-gray-300 rounded-xl text-sm" :disabled="item.is_current">
                                            </div>
                                        </div>
                                        <div class="flex items-center mt-6">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" value="1" x-model="item.is_current" :name="`organizations[${index}][is_current]`" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4">
                                                <span class="ml-2 text-xs font-bold text-gray-700">Masih aktif di organisasi ini</span>
                                            </label>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Periode Ringkas (Opsional)</label>
                                            <input type="text" x-model="item.period" :name="`organizations[${index}][period]`" placeholder="Contoh: Jan 2021 - Des 2023" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Peran & Pencapaian Organisasi</label>
                                            <textarea x-model="item.description" :name="`organizations[${index}][description]`" rows="3" placeholder="Jelaskan program kerja utama, jumlah tim yang dipimpin, atau pencapaian organisasi yang berhasil diraih..." class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <p x-show="organizations.length === 0" class="text-sm text-gray-500 text-center py-4">Belum ada pengalaman organisasi ditambahkan. Klik <strong>+ Tambah Organisasi</strong> untuk menambahkan.</p>
                        </div>
                    </div>

                    <!-- 6. SKILLS & BAHASA -->
                    <div x-show="activeTab === 6" class="grid grid-cols-1 gap-8">
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                            <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-lg bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">6</div>
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Keahlian Teknis & Kemampuan Bahasa</h2>
                                </div>
                                <button type="button" @click="addSkill" class="text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 transition cursor-pointer">+ Tambah Keahlian</button>
                            </div>
                            <div class="p-6 space-y-4">
                                <template x-for="(item, index) in skills" :key="index">
                                    <div class="flex flex-col sm:flex-row gap-3 p-4 sm:p-0 border border-slate-200 dark:border-slate-700 sm:border-0 rounded-xl sm:rounded-none bg-slate-50/50 dark:bg-slate-900/50 sm:bg-transparent relative">
                                        <button type="button" @click="removeSkill(index)" class="absolute sm:relative top-2 right-2 sm:top-0 sm:right-0 text-rose-600 dark:text-rose-400 hover:text-rose-800 sm:px-2 flex items-center justify-center shrink-0 font-bold cursor-pointer">
                                            <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            <span class="hidden sm:inline text-xl leading-none">&times;</span>
                                        </button>
                                        <input type="text" x-model="item.name" :name="`skills[${index}][name]`" placeholder="Nama Skill (Cth: PHP)" class="w-full sm:flex-1 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-slate-800 focus:border-slate-800">
                                        <select x-model="item.level" :name="`skills[${index}][level]`" class="w-full sm:w-48 shrink-0 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-slate-800 focus:border-slate-800 font-medium">
                                            <option value="Beginner">Beginner</option>
                                            <option value="Intermediate">Intermediate</option>
                                            <option value="Advanced">Advanced</option>
                                        </select>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                            <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-language text-slate-600 dark:text-slate-400"></i> Penguasaan Bahasa
                                </h2>
                                <button type="button" @click="addLang" class="text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 transition cursor-pointer">+ Tambah Bahasa</button>
                            </div>
                            <div class="p-6 space-y-4">
                                <template x-for="(item, index) in languages" :key="index">
                                    <div class="flex flex-col sm:flex-row gap-3 p-4 sm:p-0 border border-slate-200 dark:border-slate-700 sm:border-0 rounded-xl sm:rounded-none bg-slate-50/50 dark:bg-slate-900/50 sm:bg-transparent relative">
                                        <button type="button" @click="removeLang(index)" class="absolute sm:relative top-2 right-2 sm:top-0 sm:right-0 text-rose-600 dark:text-rose-400 hover:text-rose-800 sm:px-2 flex items-center justify-center shrink-0 font-bold cursor-pointer">
                                            <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            <span class="hidden sm:inline text-xl leading-none">&times;</span>
                                        </button>
                                        <input type="text" x-model="item.name" :name="`languages[${index}][name]`" placeholder="Bahasa (Cth: Inggris)" class="w-full sm:flex-1 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-slate-800 focus:border-slate-800">
                                        <select x-model="item.level" :name="`languages[${index}][level]`" class="w-full sm:w-48 shrink-0 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs focus:ring-slate-800 focus:border-slate-800 font-medium">
                                            <option value="Basic">Basic</option>
                                            <option value="Conversational">Conversational</option>
                                            <option value="Fluent">Fluent</option>
                                            <option value="Native">Native</option>
                                        </select>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- 7. SERTIFIKAT & PELATIHAN -->
                    <div x-show="activeTab === 8" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                        <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">7</div>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white">Sertifikasi & Lisensi Profesional</h2>
                            </div>
                            <button type="button" @click="addCert" class="text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 transition cursor-pointer">+ Tambah Sertifikat</button>
                        </div>
                        <div class="p-6 sm:p-8 space-y-6">
                            <template x-for="(item, index) in certificates" :key="index">
                                <div class="p-6 border border-gray-200 rounded-xl bg-gray-50/50 hover:bg-white transition-all duration-200 shadow-2xs space-y-4">
                                    <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                                        <h3 class="font-black text-gray-800 flex items-center gap-2">
                                            <i class="fa-solid fa-award text-blue-600"></i> Sertifikat #<span x-text="index + 1"></span>
                                        </h3>
                                        <button type="button" @click="removeCert(index)" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1 text-sm font-medium rounded-lg transition-colors">Hapus</button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Sertifikat / Pelatihan</label>
                                            <input type="text" x-model="item.name" :name="`certificates[${index}][name]`" placeholder="Contoh: AWS Certified Solutions Architect / TOEFL ITP" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Penerbit / Penyelenggara</label>
                                            <input type="text" x-model="item.issuer" :name="`certificates[${index}][issuer]`" placeholder="Contoh: Amazon Web Services / ETS / Google" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Sertifikasi</label>
                                            <select x-model="item.type" :name="`certificates[${index}][type]`" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                <option value="">Pilih Jenis</option>
                                                <option value="Sertifikasi Profesional / Kredensial">Sertifikasi Profesional / Kredensial</option>
                                                <option value="Lisensi Resmi / Profesi">Lisensi Resmi / Profesi</option>
                                                <option value="Pelatihan / Bootcamps / Course">Pelatihan / Bootcamps / Course</option>
                                                <option value="Sertifikasi Bahasa (TOEFL / IELTS / JLPT)">Sertifikasi Bahasa (TOEFL / IELTS / JLPT)</option>
                                                <option value="Workshop / Seminar">Workshop / Seminar</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Nomor / ID Sertifikat</label>
                                            <input type="text" x-model="item.number" :name="`certificates[${index}][number]`" placeholder="Contoh: AWS-00129381" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">URL Verifikasi Kredensial</label>
                                            <input type="url" x-model="item.url" :name="`certificates[${index}][url]`" placeholder="Contoh: https://www.credly.com/badges/..." class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Nilai / Skor / Predikat (Opsional)</label>
                                            <input type="text" x-model="item.score" :name="`certificates[${index}][score]`" placeholder="Contoh: Score 580 / Grade A / Distinction" class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1">Tgl Terbit</label>
                                                <input type="date" x-model="item.issue_date" :name="`certificates[${index}][issue_date]`" class="mt-1 block w-full border-gray-300 rounded-xl text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1">Tgl Berakhir</label>
                                                <input type="date" x-model="item.expiry_date" :name="`certificates[${index}][expiry_date]`" class="mt-1 block w-full border-gray-300 rounded-xl text-sm" :disabled="item.does_not_expire">
                                            </div>
                                        </div>
                                        <div class="flex items-center mt-6">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" value="1" x-model="item.does_not_expire" :name="`certificates[${index}][does_not_expire]`" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4">
                                                <span class="ml-2 text-xs font-bold text-gray-700">Sertifikat ini berlaku selamanya (tidak kadaluarsa)</span>
                                            </label>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan / Skill Yang Diuji (Opsional)</label>
                                            <textarea x-model="item.description" :name="`certificates[${index}][description]`" rows="2" placeholder="Jelaskan modul utama atau keahlian spesifik yang disertifikasi..." class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <p x-show="certificates.length === 0" class="text-sm text-gray-500 text-center py-4">Belum ada sertifikat ditambahkan. Klik <strong>+ Tambah Sertifikat</strong> untuk menambahkan.</p>
                        </div>
                    </div>

                    <!-- 8. PORTOFOLIO & PRESTASI -->
                    <div x-show="activeTab === 9" class="grid grid-cols-1 gap-8">
                        <!-- Portofolio / Proyek -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                            <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-lg bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">8</div>
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Portofolio Karya & Proyek Unggulan</h2>
                                </div>
                                <button type="button" @click="addPort" class="text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 transition cursor-pointer">+ Tambah Proyek</button>
                            </div>
                            <div class="p-6 sm:p-8 space-y-6">
                                <template x-for="(item, index) in portfolios" :key="index">
                                    <div class="p-6 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 hover:bg-white dark:hover:bg-slate-900 transition-all duration-200 shadow-2xs space-y-4">
                                        <div class="flex justify-between items-center pb-2 border-b border-slate-200 dark:border-slate-700">
                                            <h3 class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                                <i class="fa-solid fa-laptop-code text-slate-600 dark:text-slate-400"></i> Proyek #<span x-text="index + 1"></span>
                                            </h3>
                                            <button type="button" @click="removePort(index)" class="text-rose-600 dark:text-rose-400 hover:text-rose-800 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 px-3 py-1 text-xs font-bold rounded-lg transition-colors border border-rose-200 dark:border-rose-800 cursor-pointer">Hapus</button>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Proyek / Aplikasi</label>
                                                <input type="text" x-model="item.name" :name="`portfolios[${index}][name]`" placeholder="Contoh: E-Commerce Marketplace App" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kategori Proyek</label>
                                                <select x-model="item.category" :name="`portfolios[${index}][category]`" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                                    <option value="">Pilih Kategori</option>
                                                    <option value="Web Application">Web Application</option>
                                                    <option value="Mobile App (iOS/Android)">Mobile App (iOS/Android)</option>
                                                    <option value="AI / Machine Learning">AI / Machine Learning</option>
                                                    <option value="UI/UX Design & Prototype">UI/UX Design & Prototype</option>
                                                    <option value="Data Analysis / Dashboard">Data Analysis / Dashboard</option>
                                                    <option value="System Architecture / DevOps">System Architecture / DevOps</option>
                                                    <option value="Game Development">Game Development</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Peran Utama dalam Proyek</label>
                                                <input type="text" x-model="item.role" :name="`portfolios[${index}][role]`" placeholder="Contoh: Lead Frontend Developer & UI Designer" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tahun / Waktu Pengerjaan</label>
                                                <input type="text" x-model="item.year" :name="`portfolios[${index}][year]`" placeholder="Contoh: 2023 - 2024" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Teknologi & Tools Yang Digunakan</label>
                                                <input type="text" x-model="item.technologies" :name="`portfolios[${index}][technologies]`" placeholder="Contoh: Laravel 10, Vue 3, TailwindCSS, PostgreSQL, AWS" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tautan Live Demo / Website</label>
                                                <input type="url" x-model="item.url" :name="`portfolios[${index}][url]`" placeholder="Contoh: https://myprojectdemo.com" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tautan Code Repository (GitHub/GitLab)</label>
                                                <input type="url" x-model="item.github_url" :name="`portfolios[${index}][github_url]`" placeholder="Contoh: https://github.com/username/project" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Ringkas & Impact Proyek</label>
                                                <textarea x-model="item.description" :name="`portfolios[${index}][description]`" rows="3" placeholder="Jelaskan tujuan proyek, fitur utama yang Anda kembangkan, serta dampak/skala pengguna dari proyek ini..." class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <p x-show="portfolios.length === 0" class="text-xs text-slate-500 dark:text-slate-400 text-center py-4">Belum ada portofolio ditambahkan. Klik <strong>+ Tambah Proyek</strong> untuk menambahkan.</p>
                            </div>
                        </div>

                        <!-- Prestasi & Penghargaan -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                            <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-trophy text-amber-500"></i> Prestasi & Penghargaan Terkait
                                </h2>
                                <button type="button" @click="addAchieve" class="text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 transition cursor-pointer">+ Tambah Prestasi</button>
                            </div>
                            <div class="p-6 sm:p-8 space-y-6">
                                <template x-for="(item, index) in achievements" :key="index">
                                    <div class="p-6 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-900/50 hover:bg-white dark:hover:bg-slate-900 transition-all duration-200 shadow-2xs space-y-4">
                                        <div class="flex justify-between items-center pb-2 border-b border-slate-200 dark:border-slate-700">
                                            <h3 class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                                <i class="fa-solid fa-trophy text-amber-500"></i> Prestasi #<span x-text="index + 1"></span>
                                            </h3>
                                            <button type="button" @click="removeAchieve(index)" class="text-rose-600 dark:text-rose-400 hover:text-rose-800 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 px-3 py-1 text-xs font-bold rounded-lg transition-colors border border-rose-200 dark:border-rose-800 cursor-pointer">Hapus</button>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Prestasi / Penghargaan</label>
                                                <input type="text" x-model="item.name" :name="`achievements[${index}][name]`" placeholder="Contoh: Juara 1 Hackathon Nasional" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tingkat Prestasi</label>
                                                <select x-model="item.level" :name="`achievements[${index}][level]`" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                                    <option value="">Pilih Tingkat</option>
                                                    <option value="Internal Perusahaan / Kampus">Internal Perusahaan / Kampus</option>
                                                    <option value="Kota / Kabupaten">Kota / Kabupaten</option>
                                                    <option value="Provinsi / Regional">Provinsi / Regional</option>
                                                    <option value="Nasional">Nasional</option>
                                                    <option value="Internasional">Internasional</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Penyelenggara / Lembaga Pemberi</label>
                                                <input type="text" x-model="item.issuer" :name="`achievements[${index}][issuer]`" placeholder="Contoh: Kementerian Kominfo / Google" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Juara / Peringkat / Predikat</label>
                                                <input type="text" x-model="item.rank" :name="`achievements[${index}][rank]`" placeholder="Contoh: Juara 1 / Gold Medalist / Best Innovation" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tahun Perolehan</label>
                                                <input type="text" x-model="item.year" :name="`achievements[${index}][year]`" placeholder="Contoh: 2023" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tautan Bukti / Sertifikat (Opsional)</label>
                                                <input type="url" x-model="item.url" :name="`achievements[${index}][url]`" placeholder="Contoh: https://..." class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Kriteria & Tantangan (Opsional)</label>
                                                <textarea x-model="item.description" :name="`achievements[${index}][description]`" rows="2" placeholder="Jelaskan kriteria penilaian, jumlah peserta yang dilampaui, atau inovasi karya Anda..." class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <p x-show="achievements.length === 0" class="text-xs text-slate-500 dark:text-slate-400 text-center py-4">Belum ada prestasi ditambahkan. Klik <strong>+ Tambah Prestasi</strong> untuk menambahkan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 9. REFERENSI -->
                    <div x-show="activeTab === 11" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                        <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">9</div>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white">Referensi Profesional & Kontak Rekomendasi</h2>
                            </div>
                            <button type="button" @click="addRef" class="text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 transition cursor-pointer">+ Tambah Referensi</button>
                        </div>
                        <div class="p-6 sm:p-8 space-y-6">
                            <template x-for="(item, index) in references" :key="index">
                                <div class="p-6 border border-slate-200 rounded-xl bg-slate-50/50 hover:bg-white transition-all duration-200 shadow-2xs space-y-4">
                                    <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                            <i class="fa-solid fa-address-book text-slate-600"></i> Referensi #<span x-text="index + 1"></span>
                                        </h3>
                                        <button type="button" @click="removeRef(index)" class="text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-3 py-1 text-xs font-bold rounded-lg transition-colors border border-rose-200">Hapus</button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pemberi Referensi</label>
                                            <input type="text" x-model="item.name" :name="`references[${index}][name]`" placeholder="Contoh: Bpk. Bambang Supriyadi" class="mt-1 block w-full border-slate-300 rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Jabatan / Posisi</label>
                                            <input type="text" x-model="item.position" :name="`references[${index}][position]`" placeholder="Contoh: Head of Engineering / HR Manager" class="mt-1 block w-full border-slate-300 rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Perusahaan / Instansi</label>
                                            <input type="text" x-model="item.company" :name="`references[${index}][company]`" placeholder="Contoh: PT Innovasi Digital" class="mt-1 block w-full border-slate-300 rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Hubungan Profesional</label>
                                            <select x-model="item.relationship" :name="`references[${index}][relationship]`" class="mt-1 block w-full border-slate-300 rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                                <option value="">Pilih Hubungan</option>
                                                <option value="Atasan Langsung (Direct Supervisor)">Atasan Langsung (Direct Supervisor)</option>
                                                <option value="Manajer HRD / Divisi">Manajer HRD / Divisi</option>
                                                <option value="Rekan Kerja (Peer / Colleague)">Rekan Kerja (Peer / Colleague)</option>
                                                <option value="Bawahan (Subordinate)">Bawahan (Subordinate)</option>
                                                <option value="Klien / Partner Bisnis">Klien / Partner Bisnis</option>
                                                <option value="Dosen Pembimbing / Akademis">Dosen Pembimbing / Akademis</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Email Profesional</label>
                                            <input type="email" x-model="item.email" :name="`references[${index}][email]`" placeholder="Contoh: bambang@perusahaan.com" class="mt-1 block w-full border-slate-300 rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">No. HP / WhatsApp Kontak</label>
                                            <input type="text" x-model="item.phone" :name="`references[${index}][phone]`" placeholder="Contoh: 0812-3456-7890" class="mt-1 block w-full border-slate-300 rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Lama Bekerja Sama / Mengenal (Opsional)</label>
                                            <input type="text" x-model="item.years_known" :name="`references[${index}][years_known]`" placeholder="Contoh: 3 Tahun (2021 - 2024)" class="mt-1 block w-full border-slate-300 rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Ringkasan Rekomendasi (Opsional)</label>
                                            <textarea x-model="item.notes" :name="`references[${index}][notes]`" rows="2" placeholder="Catatan singkat mengenai karakter profesional atau rekomendasi kinerja..." class="mt-1 block w-full border-slate-300 rounded-xl focus:ring-slate-800 focus:border-slate-800 text-xs"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <p x-show="references.length === 0" class="text-xs text-slate-500 text-center py-4">Belum ada referensi ditambahkan. Klik <strong>+ Tambah Referensi</strong> untuk menambahkan.</p>
                        </div>
                    </div>

                    <!-- 10. PREFERENSI PEKERJAAN & SOSIAL -->
                    <div x-show="activeTab === 12" class="grid grid-cols-1 gap-8">
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                            <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-lg bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">10</div>
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Preferensi Karir & Ekspektasi Gaji</h2>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <div><label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Posisi Diinginkan</label><input type="text" x-model="prefs.position" name="job_preferences[position]" class="mt-1 w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs"></div>
                                <div><label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Lokasi Diinginkan</label><input type="text" x-model="prefs.location" name="job_preferences[location]" class="mt-1 w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs"></div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Gaji Saat Ini / Terakhir (Opsional)</label>
                                    <input type="text" name="current_salary" value="{{ old('current_salary', $profile->current_salary ?? '') }}" placeholder="Contoh: 5.000.000" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tipe Kerja</label>
                                        <select x-model="prefs.type" name="job_preferences[type]" class="mt-1 w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                            <option value="">Semua</option>
                                            <option value="Full-time">Full-time</option>
                                            <option value="Part-time">Part-time</option>
                                            <option value="Contract">Contract</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Sistem Kerja</label>
                                        <select x-model="prefs.wfo_hybrid_remote" name="job_preferences[wfo_hybrid_remote]" class="mt-1 w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                            <option value="">Semua</option>
                                            <option value="WFO">WFO</option>
                                            <option value="Hybrid">Hybrid</option>
                                            <option value="Remote">Remote</option>
                                        </select>
                                    </div>
                                </div>
                                <div><label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Ekspektasi Gaji (Rp)</label><input type="number" x-model="prefs.expected_salary" name="job_preferences[expected_salary]" class="mt-1 w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs"></div>
                                <div class="flex gap-6 mt-4">
                                    <label class="flex items-center text-xs font-bold text-slate-700 dark:text-slate-300"><input type="checkbox" x-model="prefs.willing_to_relocate" name="job_preferences[willing_to_relocate]" value="1" class="mr-2 border-slate-300 dark:border-slate-600 text-blue-600 rounded"> Bersedia Relokasi</label>
                                    <label class="flex items-center text-xs font-bold text-slate-700 dark:text-slate-300"><input type="checkbox" x-model="prefs.willing_to_travel" name="job_preferences[willing_to_travel]" value="1" class="mr-2 border-slate-300 dark:border-slate-600 text-blue-600 rounded"> Bersedia Dinas</label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                            <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-share-nodes text-slate-600 dark:text-slate-400"></i> Tautan Profil Profesional & Sosial
                                </h2>
                            </div>
                            <div class="p-6 sm:p-8 space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-2">
                                            <i class="fa-brands fa-linkedin text-blue-600 text-base"></i> LinkedIn Profile URL
                                        </label>
                                        <input type="url" x-model="socials.linkedin" name="social_links[linkedin]" placeholder="https://linkedin.com/in/username" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-2">
                                            <i class="fa-brands fa-github text-gray-800 dark:text-slate-200 text-base"></i> GitHub / GitLab URL
                                        </label>
                                        <input type="url" x-model="socials.github" name="social_links[github]" placeholder="https://github.com/username" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-2">
                                            <i class="fa-solid fa-globe text-emerald-600 text-base"></i> Website / Interactive Portfolio
                                        </label>
                                        <input type="url" x-model="socials.website" name="social_links[website]" placeholder="https://myportfolio.com" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-2">
                                            <i class="fa-solid fa-square-rss text-orange-500 text-base"></i> Blog / Medium / Dev.to URL
                                        </label>
                                        <input type="url" x-model="socials.blog" name="social_links[blog]" placeholder="https://medium.com/@username" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-2">
                                            <i class="fa-brands fa-behance text-blue-500 text-base"></i> Behance / Dribbble (Desain)
                                        </label>
                                        <input type="url" x-model="socials.behance" name="social_links[behance]" placeholder="https://behance.net/username" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-2">
                                            <i class="fa-brands fa-kaggle text-sky-500 text-base"></i> Kaggle / HuggingFace (Data & AI)
                                        </label>
                                        <input type="url" x-model="socials.kaggle" name="social_links[kaggle]" placeholder="https://kaggle.com/username" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-2">
                                            <i class="fa-brands fa-youtube text-red-600 text-base"></i> YouTube / Video Showreel
                                        </label>
                                        <input type="url" x-model="socials.youtube" name="social_links[youtube]" placeholder="https://youtube.com/@channel" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-2">
                                            <i class="fa-brands fa-instagram text-pink-600 text-base"></i> Instagram / Twitter (X)
                                        </label>
                                        <input type="url" x-model="socials.instagram" name="social_links[instagram]" placeholder="https://instagram.com/username" class="mt-1 block w-full border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl text-xs">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 11. DOKUMEN -->
                    <div x-show="activeTab === 13" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xs border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                        <div class="bg-slate-50 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-slate-900 dark:bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">11</div>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white">Vault Dokumen & Berkas Pendukung (Maks. 5MB per file)</h2>
                            </div>
                        </div>
                        <div class="p-6 sm:p-8" x-data="{ activeTab: 'cv' }">
                            <!-- Tabs Navigation with Upload Status Checkmarks -->
                            <div class="flex flex-wrap border-b border-gray-200 dark:border-slate-700 mb-8 -mx-2 overflow-x-auto pb-2 sm:pb-0 gap-1">
                                <button type="button" data-doc-tab="cv" @click="activeTab = 'cv'" :class="{'border-slate-900 dark:border-blue-500 text-slate-900 dark:text-white font-bold bg-slate-100/80 dark:bg-slate-700/60': activeTab === 'cv', 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200': activeTab !== 'cv'}" class="px-4 py-3 border-b-2 text-xs transition whitespace-nowrap flex items-center gap-1.5 rounded-t-xl cursor-pointer">
                                    CV @if($profile && $profile->cv_path) <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> @endif
                                </button>
                                <button type="button" data-doc-tab="ktp" @click="activeTab = 'ktp'" :class="{'border-slate-900 dark:border-blue-500 text-slate-900 dark:text-white font-bold bg-slate-100/80 dark:bg-slate-700/60': activeTab === 'ktp', 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200': activeTab !== 'ktp'}" class="px-4 py-3 border-b-2 text-xs transition whitespace-nowrap flex items-center gap-1.5 rounded-t-xl cursor-pointer">
                                    KTP @if($profile && $profile->ktp_path) <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> @endif
                                </button>
                                <button type="button" data-doc-tab="ijazah" @click="activeTab = 'ijazah'" :class="{'border-slate-900 dark:border-blue-500 text-slate-900 dark:text-white font-bold bg-slate-100/80 dark:bg-slate-700/60': activeTab === 'ijazah', 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200': activeTab !== 'ijazah'}" class="px-4 py-3 border-b-2 text-xs transition whitespace-nowrap flex items-center gap-1.5 rounded-t-xl cursor-pointer">
                                    Ijazah @if($profile && $profile->ijazah_path) <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> @endif
                                </button>
                                <button type="button" data-doc-tab="transcript" @click="activeTab = 'transcript'" :class="{'border-slate-900 dark:border-blue-500 text-slate-900 dark:text-white font-bold bg-slate-100/80 dark:bg-slate-700/60': activeTab === 'transcript', 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200': activeTab !== 'transcript'}" class="px-4 py-3 border-b-2 text-xs transition whitespace-nowrap flex items-center gap-1.5 rounded-t-xl cursor-pointer">
                                    Transkrip @if($profile && $profile->transcript_path) <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> @endif
                                </button>
                                <button type="button" data-doc-tab="certificate" @click="activeTab = 'certificate'" :class="{'border-slate-900 dark:border-blue-500 text-slate-900 dark:text-white font-bold bg-slate-100/80 dark:bg-slate-700/60': activeTab === 'certificate', 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200': activeTab !== 'certificate'}" class="px-4 py-3 border-b-2 text-xs transition whitespace-nowrap flex items-center gap-1.5 rounded-t-xl cursor-pointer">
                                    Sertifikat @if($profile && $profile->certificate_file_path) <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> @endif
                                </button>
                                <button type="button" data-doc-tab="portfolio" @click="activeTab = 'portfolio'" :class="{'border-slate-900 dark:border-blue-500 text-slate-900 dark:text-white font-bold bg-slate-100/80 dark:bg-slate-700/60': activeTab === 'portfolio', 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200': activeTab !== 'portfolio'}" class="px-4 py-3 border-b-2 text-xs transition whitespace-nowrap flex items-center gap-1.5 rounded-t-xl cursor-pointer">
                                    Portofolio @if($profile && $profile->portfolio_file_path) <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> @endif
                                </button>
                                <button type="button" data-doc-tab="cover_letter" @click="activeTab = 'cover_letter'" :class="{'border-slate-900 dark:border-blue-500 text-slate-900 dark:text-white font-bold bg-slate-100/80 dark:bg-slate-700/60': activeTab === 'cover_letter', 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200': activeTab !== 'cover_letter'}" class="px-4 py-3 border-b-2 text-xs transition whitespace-nowrap flex items-center gap-1.5 rounded-t-xl cursor-pointer">
                                    Surat Lamaran @if($profile && $profile->cover_letter_path) <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> @endif
                                </button>
                                <button type="button" data-doc-tab="skck" @click="activeTab = 'skck'" :class="{'border-slate-900 dark:border-blue-500 text-slate-900 dark:text-white font-bold bg-slate-100/80 dark:bg-slate-700/60': activeTab === 'skck', 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200': activeTab !== 'skck'}" class="px-4 py-3 border-b-2 text-xs transition whitespace-nowrap flex items-center gap-1.5 rounded-t-xl cursor-pointer">
                                    SKCK @if($profile && $profile->skck_path) <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> @endif
                                </button>
                                <button type="button" data-doc-tab="health_certificate" @click="activeTab = 'health_certificate'" :class="{'border-slate-900 dark:border-blue-500 text-slate-900 dark:text-white font-bold bg-slate-100/80 dark:bg-slate-700/60': activeTab === 'health_certificate', 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200': activeTab !== 'health_certificate'}" class="px-4 py-3 border-b-2 text-xs transition whitespace-nowrap flex items-center gap-1.5 rounded-t-xl cursor-pointer">
                                    Ket. Sehat @if($profile && $profile->health_certificate_path) <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> @endif
                                </button>
                                <button type="button" data-doc-tab="consent_letter" @click="activeTab = 'consent_letter'" :class="{'border-slate-900 dark:border-blue-500 text-slate-900 dark:text-white font-bold bg-slate-100/80 dark:bg-slate-700/60': activeTab === 'consent_letter', 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200': activeTab !== 'consent_letter'}" class="px-4 py-3 border-b-2 text-xs transition whitespace-nowrap flex items-center gap-1.5 rounded-t-xl cursor-pointer">
                                    Persetujuan @if($profile && $profile->consent_letter_path) <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> @endif
                                </button>
                            </div>

                            <div class="relative w-full max-w-2xl mx-auto">
                                <!-- CV -->
                                <div x-show="activeTab === 'cv'" x-transition:enter="transition ease-out duration-300">
                                    <div x-data="{ fileName: '' }" class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-6 sm:p-8 bg-slate-50/60 dark:bg-slate-700/30 hover:bg-slate-100/90 dark:hover:bg-slate-700/50 transition flex flex-col items-center justify-center relative group text-center min-h-[220px]">
                                        <input type="file" name="cv" accept=".pdf" onchange="autoUploadDoc(event, 'cv')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="text-slate-700 dark:text-slate-300 mb-3 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-file-pdf text-4xl"></i>
                                        </div>
                                        <p class="font-bold text-base text-slate-900 dark:text-white mb-1">Curriculum Vitae (PDF)</p>
                                        
                                        <div class="doc-status-box mb-3 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-xl flex items-center gap-2 {{ ($profile && $profile->cv_path) ? '' : 'hidden' }}">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> File Sudah Terunggah
                                        </div>
                                        <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
                                            <button type="button" onclick="const url = this.getAttribute('data-url'); const isImage = /\.(jpg|jpeg|png|webp)$/i.test(url); window.dispatchEvent(new CustomEvent('open-preview-modal', { detail: { title: 'Dokumen CV Candidate', url: url, isImage: isImage } }))" data-url="{{ ($profile && $profile->cv_path) ? Storage::url($profile->cv_path) : '' }}" class="doc-preview-btn z-20 inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 {{ ($profile && $profile->cv_path) ? '' : 'hidden' }}"><i class="fa-solid fa-eye text-slate-300"></i> Pratinjau Pop-up</button>
                                            <a href="{{ ($profile && $profile->cv_path) ? Storage::url($profile->cv_path) : '#' }}" target="_blank" class="doc-link-btn z-20 inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 mb-2 {{ ($profile && $profile->cv_path) ? '' : 'hidden' }}">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat / Unduh Dokumen Terunggah
                                            </a>
                                        </div>
                                        <p class="text-2xs text-slate-500 dark:text-slate-400 font-medium">Klik atau seret file baru jika ingin mengganti file</p>
                                    </div>
                                </div>

                                <!-- KTP -->
                                <div x-show="activeTab === 'ktp'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                                    <div x-data="{ fileName: '' }" class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-6 sm:p-8 bg-slate-50/60 dark:bg-slate-700/30 hover:bg-slate-100/90 dark:hover:bg-slate-700/50 transition flex flex-col items-center justify-center relative group text-center min-h-[220px]">
                                        <input type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" onchange="autoUploadDoc(event, 'ktp')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="text-slate-700 dark:text-slate-300 mb-3 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-id-card text-4xl"></i>
                                        </div>
                                        <p class="font-bold text-base text-slate-900 dark:text-white mb-1">KTP (Image/PDF)</p>
                                        
                                        <div class="doc-status-box mb-3 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-xl flex items-center gap-2 {{ ($profile && $profile->ktp_path) ? '' : 'hidden' }}">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> File Sudah Terunggah
                                        </div>
                                        <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
                                            <button type="button" onclick="const url = this.getAttribute('data-url'); const isImage = /\.(jpg|jpeg|png|webp)$/i.test(url); window.dispatchEvent(new CustomEvent('open-preview-modal', { detail: { title: 'Dokumen KTP Candidate', url: url, isImage: isImage } }))" data-url="{{ ($profile && $profile->ktp_path) ? Storage::url($profile->ktp_path) : '' }}" class="doc-preview-btn z-20 inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 {{ ($profile && $profile->ktp_path) ? '' : 'hidden' }}"><i class="fa-solid fa-eye text-slate-300"></i> Pratinjau Pop-up</button>
                                            <a href="{{ ($profile && $profile->ktp_path) ? Storage::url($profile->ktp_path) : '#' }}" target="_blank" class="doc-link-btn z-20 inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 mb-2 {{ ($profile && $profile->ktp_path) ? '' : 'hidden' }}">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat / Unduh Dokumen Terunggah
                                            </a>
                                        </div>
                                        <p class="text-2xs text-slate-500 dark:text-slate-400 font-medium">Klik atau seret file baru jika ingin mengganti file</p>
                                    </div>
                                </div>

                                <!-- Ijazah -->
                                <div x-show="activeTab === 'ijazah'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                                    <div x-data="{ fileName: '' }" class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-6 sm:p-8 bg-slate-50/60 dark:bg-slate-700/30 hover:bg-slate-100/90 dark:hover:bg-slate-700/50 transition flex flex-col items-center justify-center relative group text-center min-h-[220px]">
                                        <input type="file" name="ijazah" accept=".pdf,.jpg,.jpeg,.png" onchange="autoUploadDoc(event, 'ijazah')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="text-slate-700 dark:text-slate-300 mb-3 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-graduation-cap text-4xl"></i>
                                        </div>
                                        <p class="font-bold text-base text-slate-900 dark:text-white mb-1">Ijazah (Image/PDF)</p>
                                        
                                        <div class="doc-status-box mb-3 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-xl flex items-center gap-2 {{ ($profile && $profile->ijazah_path) ? '' : 'hidden' }}">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> File Sudah Terunggah
                                        </div>
                                        <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
                                            <button type="button" onclick="const url = this.getAttribute('data-url'); const isImage = /\.(jpg|jpeg|png|webp)$/i.test(url); window.dispatchEvent(new CustomEvent('open-preview-modal', { detail: { title: 'Dokumen Ijazah Candidate', url: url, isImage: isImage } }))" data-url="{{ ($profile && $profile->ijazah_path) ? Storage::url($profile->ijazah_path) : '' }}" class="doc-preview-btn z-20 inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 {{ ($profile && $profile->ijazah_path) ? '' : 'hidden' }}"><i class="fa-solid fa-eye text-slate-300"></i> Pratinjau Pop-up</button>
                                            <a href="{{ ($profile && $profile->ijazah_path) ? Storage::url($profile->ijazah_path) : '#' }}" target="_blank" class="doc-link-btn z-20 inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 mb-2 {{ ($profile && $profile->ijazah_path) ? '' : 'hidden' }}">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat / Unduh Dokumen Terunggah
                                            </a>
                                        </div>
                                        <p class="text-2xs text-slate-500 dark:text-slate-400 font-medium">Klik atau seret file baru jika ingin mengganti file</p>
                                    </div>
                                </div>

                                <!-- Transkrip -->
                                <div x-show="activeTab === 'transcript'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                                    <div x-data="{ fileName: '' }" class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-6 sm:p-8 bg-slate-50/60 dark:bg-slate-700/30 hover:bg-slate-100/90 dark:hover:bg-slate-700/50 transition flex flex-col items-center justify-center relative group text-center min-h-[220px]">
                                        <input type="file" name="transcript" accept=".pdf,.jpg,.jpeg,.png" onchange="autoUploadDoc(event, 'transcript')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="text-slate-700 dark:text-slate-300 mb-3 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-file-invoice text-4xl"></i>
                                        </div>
                                        <p class="font-bold text-base text-slate-900 dark:text-white mb-1">Transkrip Nilai (PDF/Image)</p>
                                        
                                        <div class="doc-status-box mb-3 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-xl flex items-center gap-2 {{ ($profile && $profile->transcript_path) ? '' : 'hidden' }}">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> File Sudah Terunggah
                                        </div>
                                        <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
                                            <button type="button" onclick="const url = this.getAttribute('data-url'); const isImage = /\.(jpg|jpeg|png|webp)$/i.test(url); window.dispatchEvent(new CustomEvent('open-preview-modal', { detail: { title: 'Dokumen Transkrip Nilai Candidate', url: url, isImage: isImage } }))" data-url="{{ ($profile && $profile->transcript_path) ? Storage::url($profile->transcript_path) : '' }}" class="doc-preview-btn z-20 inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 {{ ($profile && $profile->transcript_path) ? '' : 'hidden' }}"><i class="fa-solid fa-eye text-slate-300"></i> Pratinjau Pop-up</button>
                                            <a href="{{ ($profile && $profile->transcript_path) ? Storage::url($profile->transcript_path) : '#' }}" target="_blank" class="doc-link-btn z-20 inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 mb-2 {{ ($profile && $profile->transcript_path) ? '' : 'hidden' }}">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat / Unduh Dokumen Terunggah
                                            </a>
                                        </div>
                                        <p class="text-2xs text-slate-500 dark:text-slate-400 font-medium">Klik atau seret file baru jika ingin mengganti file</p>
                                    </div>
                                </div>

                                <!-- Sertifikat -->
                                <div x-show="activeTab === 'certificate'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                                    <div x-data="{ fileName: '' }" class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-6 sm:p-8 bg-slate-50/60 dark:bg-slate-700/30 hover:bg-slate-100/90 dark:hover:bg-slate-700/50 transition flex flex-col items-center justify-center relative group text-center min-h-[220px]">
                                        <input type="file" name="certificate_file" accept=".pdf,.zip" onchange="autoUploadDoc(event, 'certificate')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="text-slate-700 dark:text-slate-300 mb-3 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-award text-4xl"></i>
                                        </div>
                                        <p class="font-bold text-base text-slate-900 dark:text-white mb-1">Sertifikat (PDF/ZIP)</p>
                                        
                                        <div class="doc-status-box mb-3 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-xl flex items-center gap-2 {{ ($profile && $profile->certificate_file_path) ? '' : 'hidden' }}">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> File Sudah Terunggah
                                        </div>
                                        <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
                                            <button type="button" onclick="const url = this.getAttribute('data-url'); const isImage = /\.(jpg|jpeg|png|webp)$/i.test(url); window.dispatchEvent(new CustomEvent('open-preview-modal', { detail: { title: 'Dokumen Sertifikat Candidate', url: url, isImage: isImage } }))" data-url="{{ ($profile && $profile->certificate_file_path) ? Storage::url($profile->certificate_file_path) : '' }}" class="doc-preview-btn z-20 inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 {{ ($profile && $profile->certificate_file_path) ? '' : 'hidden' }}"><i class="fa-solid fa-eye text-slate-300"></i> Pratinjau Pop-up</button>
                                            <a href="{{ ($profile && $profile->certificate_file_path) ? Storage::url($profile->certificate_file_path) : '#' }}" target="_blank" class="doc-link-btn z-20 inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 mb-2 {{ ($profile && $profile->certificate_file_path) ? '' : 'hidden' }}">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat / Unduh Dokumen Terunggah
                                            </a>
                                        </div>
                                        <p class="text-2xs text-slate-500 dark:text-slate-400 font-medium">Klik atau seret file baru jika ingin mengganti file</p>
                                    </div>
                                </div>

                                <!-- Portofolio -->
                                <div x-show="activeTab === 'portfolio'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                                    <div x-data="{ fileName: '' }" class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-6 sm:p-8 bg-slate-50/60 dark:bg-slate-700/30 hover:bg-slate-100/90 dark:hover:bg-slate-700/50 transition flex flex-col items-center justify-center relative group text-center min-h-[220px]">
                                        <input type="file" name="portfolio_file" accept=".pdf,.zip" onchange="autoUploadDoc(event, 'portfolio')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="text-slate-700 dark:text-slate-300 mb-3 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-briefcase text-4xl"></i>
                                        </div>
                                        <p class="font-bold text-base text-slate-900 dark:text-white mb-1">Portofolio Karya (PDF/ZIP)</p>
                                        
                                        <div class="doc-status-box mb-3 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-xl flex items-center gap-2 {{ ($profile && $profile->portfolio_file_path) ? '' : 'hidden' }}">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> File Sudah Terunggah
                                        </div>
                                        <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
                                            <button type="button" onclick="const url = this.getAttribute('data-url'); const isImage = /\.(jpg|jpeg|png|webp)$/i.test(url); window.dispatchEvent(new CustomEvent('open-preview-modal', { detail: { title: 'Dokumen Portofolio Candidate', url: url, isImage: isImage } }))" data-url="{{ ($profile && $profile->portfolio_file_path) ? Storage::url($profile->portfolio_file_path) : '' }}" class="doc-preview-btn z-20 inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 {{ ($profile && $profile->portfolio_file_path) ? '' : 'hidden' }}"><i class="fa-solid fa-eye text-slate-300"></i> Pratinjau Pop-up</button>
                                            <a href="{{ ($profile && $profile->portfolio_file_path) ? Storage::url($profile->portfolio_file_path) : '#' }}" target="_blank" class="doc-link-btn z-20 inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 mb-2 {{ ($profile && $profile->portfolio_file_path) ? '' : 'hidden' }}">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat / Unduh Dokumen Terunggah
                                            </a>
                                        </div>
                                        <p class="text-2xs text-slate-500 dark:text-slate-400 font-medium">Klik atau seret file baru jika ingin mengganti file</p>
                                    </div>
                                </div>

                                <!-- Surat Lamaran -->
                                <div x-show="activeTab === 'cover_letter'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                                    <div x-data="{ fileName: '' }" class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-6 sm:p-8 bg-slate-50/60 dark:bg-slate-700/30 hover:bg-slate-100/90 dark:hover:bg-slate-700/50 transition flex flex-col items-center justify-center relative group text-center min-h-[220px]">
                                        <input type="file" name="cover_letter" accept=".pdf" onchange="autoUploadDoc(event, 'cover_letter')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="text-slate-700 dark:text-slate-300 mb-3 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-envelope-open-text text-4xl"></i>
                                        </div>
                                        <p class="font-bold text-base text-slate-900 dark:text-white mb-1">Surat Lamaran (PDF)</p>
                                        
                                        <div class="doc-status-box mb-3 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-xl flex items-center gap-2 {{ ($profile && $profile->cover_letter_path) ? '' : 'hidden' }}">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> File Sudah Terunggah
                                        </div>
                                        <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
                                            <button type="button" onclick="const url = this.getAttribute('data-url'); const isImage = /\.(jpg|jpeg|png|webp)$/i.test(url); window.dispatchEvent(new CustomEvent('open-preview-modal', { detail: { title: 'Surat Lamaran Candidate', url: url, isImage: isImage } }))" data-url="{{ ($profile && $profile->cover_letter_path) ? Storage::url($profile->cover_letter_path) : '' }}" class="doc-preview-btn z-20 inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 {{ ($profile && $profile->cover_letter_path) ? '' : 'hidden' }}"><i class="fa-solid fa-eye text-slate-300"></i> Pratinjau Pop-up</button>
                                            <a href="{{ ($profile && $profile->cover_letter_path) ? Storage::url($profile->cover_letter_path) : '#' }}" target="_blank" class="doc-link-btn z-20 inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 mb-2 {{ ($profile && $profile->cover_letter_path) ? '' : 'hidden' }}">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat / Unduh Dokumen Terunggah
                                            </a>
                                        </div>
                                        <p class="text-2xs text-slate-500 dark:text-slate-400 font-medium">Klik atau seret file baru jika ingin mengganti file</p>
                                    </div>
                                </div>

                                <!-- SKCK -->
                                <div x-show="activeTab === 'skck'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                                    <div x-data="{ fileName: '' }" class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-6 sm:p-8 bg-slate-50/60 dark:bg-slate-700/30 hover:bg-slate-100/90 dark:hover:bg-slate-700/50 transition flex flex-col items-center justify-center relative group text-center min-h-[220px]">
                                        <input type="file" name="skck" accept=".pdf,.jpg,.jpeg,.png" onchange="autoUploadDoc(event, 'skck')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="text-slate-700 dark:text-slate-300 mb-3 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-shield-halved text-4xl"></i>
                                        </div>
                                        <p class="font-bold text-base text-slate-900 dark:text-white mb-1">SKCK (Image/PDF)</p>
                                        
                                        <div class="doc-status-box mb-3 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-xl flex items-center gap-2 {{ ($profile && $profile->skck_path) ? '' : 'hidden' }}">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> File Sudah Terunggah
                                        </div>
                                        <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
                                            <button type="button" onclick="const url = this.getAttribute('data-url'); const isImage = /\.(jpg|jpeg|png|webp)$/i.test(url); window.dispatchEvent(new CustomEvent('open-preview-modal', { detail: { title: 'Dokumen SKCK Candidate', url: url, isImage: isImage } }))" data-url="{{ ($profile && $profile->skck_path) ? Storage::url($profile->skck_path) : '' }}" class="doc-preview-btn z-20 inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 {{ ($profile && $profile->skck_path) ? '' : 'hidden' }}"><i class="fa-solid fa-eye text-slate-300"></i> Pratinjau Pop-up</button>
                                            <a href="{{ ($profile && $profile->skck_path) ? Storage::url($profile->skck_path) : '#' }}" target="_blank" class="doc-link-btn z-20 inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 mb-2 {{ ($profile && $profile->skck_path) ? '' : 'hidden' }}">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat / Unduh Dokumen Terunggah
                                            </a>
                                        </div>
                                        <p class="text-2xs text-slate-500 dark:text-slate-400 font-medium">Klik atau seret file baru jika ingin mengganti file</p>
                                    </div>
                                </div>

                                <!-- Surat Keterangan Sehat -->
                                <div x-show="activeTab === 'health_certificate'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                                    <div x-data="{ fileName: '' }" class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-6 sm:p-8 bg-slate-50/60 dark:bg-slate-700/30 hover:bg-slate-100/90 dark:hover:bg-slate-700/50 transition flex flex-col items-center justify-center relative group text-center min-h-[220px]">
                                        <input type="file" name="health_certificate" accept=".pdf,.jpg,.jpeg,.png" onchange="autoUploadDoc(event, 'health_certificate')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="text-slate-700 dark:text-slate-300 mb-3 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-notes-medical text-4xl"></i>
                                        </div>
                                        <p class="font-bold text-base text-slate-900 dark:text-white mb-1">Surat Keterangan Sehat (Image/PDF)</p>
                                        
                                        <div class="doc-status-box mb-3 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-xl flex items-center gap-2 {{ ($profile && $profile->health_certificate_path) ? '' : 'hidden' }}">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> File Sudah Terunggah
                                        </div>
                                        <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
                                            <button type="button" onclick="const url = this.getAttribute('data-url'); const isImage = /\.(jpg|jpeg|png|webp)$/i.test(url); window.dispatchEvent(new CustomEvent('open-preview-modal', { detail: { title: 'Surat Keterangan Sehat Candidate', url: url, isImage: isImage } }))" data-url="{{ ($profile && $profile->health_certificate_path) ? Storage::url($profile->health_certificate_path) : '' }}" class="doc-preview-btn z-20 inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 {{ ($profile && $profile->health_certificate_path) ? '' : 'hidden' }}"><i class="fa-solid fa-eye text-slate-300"></i> Pratinjau Pop-up</button>
                                            <a href="{{ ($profile && $profile->health_certificate_path) ? Storage::url($profile->health_certificate_path) : '#' }}" target="_blank" class="doc-link-btn z-20 inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 mb-2 {{ ($profile && $profile->health_certificate_path) ? '' : 'hidden' }}">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat / Unduh Dokumen Terunggah
                                            </a>
                                        </div>
                                        <p class="text-2xs text-slate-500 dark:text-slate-400 font-medium">Klik atau seret file baru jika ingin mengganti file</p>
                                    </div>
                                </div>

                                <!-- Surat Pernyataan Persetujuan -->
                                <div x-show="activeTab === 'consent_letter'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 mb-4">
                                        <p class="text-xs text-slate-600 dark:text-slate-400 flex-1">Unggah Surat Pernyataan Persetujuan yang sudah ditandatangani.</p>
                                        <a href="{{ route('profile.candidate.consent.template') }}" class="self-start sm:self-auto shrink-0 inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-white text-xs font-bold rounded-xl transition border border-slate-300 dark:border-slate-600">
                                            <i class="fa-solid fa-file-download text-slate-600 dark:text-slate-300"></i> Download Template
                                        </a>
                                    </div>
                                    <div x-data="{ fileName: '' }" class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-6 sm:p-8 bg-slate-50/60 dark:bg-slate-700/30 hover:bg-slate-100/90 dark:hover:bg-slate-700/50 transition flex flex-col items-center justify-center relative group text-center min-h-[200px]">
                                        <input type="file" name="consent_letter" accept=".pdf" onchange="autoUploadDoc(event, 'consent_letter')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="text-slate-700 dark:text-slate-300 mb-3 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-file-signature text-4xl"></i>
                                        </div>
                                        <p class="font-bold text-base text-slate-900 dark:text-white mb-1">Surat Pernyataan Persetujuan (PDF)</p>
                                        
                                        <div class="doc-status-box mb-3 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-xl flex items-center gap-2 {{ ($profile && $profile->consent_letter_path) ? '' : 'hidden' }}">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> File Sudah Terunggah
                                        </div>
                                        <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
                                            <button type="button" onclick="const url = this.getAttribute('data-url'); const isImage = /\.(jpg|jpeg|png|webp)$/i.test(url); window.dispatchEvent(new CustomEvent('open-preview-modal', { detail: { title: 'Surat Pernyataan Persetujuan Candidate', url: url, isImage: isImage } }))" data-url="{{ ($profile && $profile->consent_letter_path) ? Storage::url($profile->consent_letter_path) : '' }}" class="doc-preview-btn z-20 inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 {{ ($profile && $profile->consent_letter_path) ? '' : 'hidden' }}"><i class="fa-solid fa-eye text-slate-300"></i> Pratinjau Pop-up</button>
                                            <a href="{{ ($profile && $profile->consent_letter_path) ? Storage::url($profile->consent_letter_path) : '#' }}" target="_blank" class="doc-link-btn z-20 inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-xl shadow-2xs transition transform hover:scale-105 mb-2 {{ ($profile && $profile->consent_letter_path) ? '' : 'hidden' }}">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat / Unduh Dokumen Terunggah
                                            </a>
                                        </div>
                                        <p class="text-2xs text-slate-500 font-medium">Klik atau seret file baru jika ingin mengganti file</p>
                                    </div>
                                </div>
                            </div>
                        </div>  </div>

                        <!-- TAB NAVIGATION BUTTONS (NEXT/PREV) -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-5 shadow-2xs border border-slate-200 dark:border-slate-700 flex flex-col-reverse sm:flex-row items-center justify-between gap-3 transition-colors mt-6">
                            <button type="button" @click="prevStep()"
                                :class="currentStepIndex === 0 ? 'invisible opacity-0 pointer-events-none' : ''"
                                class="w-full sm:w-auto px-5 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 rounded-xl shadow-2xs transition flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-arrow-left text-3xs"></i> Langkah Sebelumnya
                            </button>
                            
                            <div class="flex flex-col sm:flex-row items-center gap-2.5 w-full sm:w-auto">
                                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold text-xs rounded-xl shadow-2xs transition flex items-center justify-center gap-2 border border-slate-900 dark:border-slate-600 cursor-pointer">
                                    <i class="fa-solid fa-floppy-disk"></i> Simpan Profil
                                </button>
                                
                                <button type="button" @click="nextStep()"
                                    :class="currentStepIndex === tabs.length - 1 ? 'hidden' : ''"
                                    class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-2xs transition flex items-center justify-center gap-2 cursor-pointer">
                                    <span>Langkah Selanjutnya</span> <i class="fa-solid fa-arrow-right text-3xs"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function resumeForm() {
            return {
                activeTab: 1,
                tabs: [
                    { id: 1, label: 'Informasi Pribadi' },
                    { id: 2, label: 'Ringkasan Profil' },
                    { id: 3, label: 'Riwayat Pendidikan' },
                    { id: 4, label: 'Pengalaman Kerja' },
                    { id: 5, label: 'Pengalaman Organisasi' },
                    { id: 6, label: 'Keahlian & Bahasa' },
                    { id: 8, label: 'Sertifikat' },
                    { id: 9, label: 'Portofolio & Prestasi' },
                    { id: 11, label: 'Referensi' },
                    { id: 12, label: 'Preferensi & Sosial' },
                    { id: 13, label: 'Dokumen' }
                ],
                get currentStepIndex() {
                    const idx = this.tabs.findIndex(t => t.id === this.activeTab);
                    return idx >= 0 ? idx : 0;
                },
                nextStep() {
                    const idx = this.currentStepIndex;
                    if (idx < this.tabs.length - 1) {
                        this.activeTab = this.tabs[idx + 1].id;
                        window.scrollTo({ top: 120, behavior: 'smooth' });
                    }
                },
                prevStep() {
                    const idx = this.currentStepIndex;
                    if (idx > 0) {
                        this.activeTab = this.tabs[idx - 1].id;
                        window.scrollTo({ top: 120, behavior: 'smooth' });
                    }
                },
                universities: [],
                init() {
                    fetch('http://universities.hipolabs.com/search?country=Indonesia')
                        .then(res => res.json())
                        .then(data => {
                            let names = data.map(item => item.name);
                            this.universities = [...new Set(names)].sort();
                        })
                        .catch(err => console.error('Gagal mengambil data universitas:', err));
                },
                nickname: '{{ old('nickname', $profile->nickname ?? '') }}',
                phone: '{{ old('phone', $profile->phone ?? '') }}',
                dob: '{{ old('dob', isset($profile->dob) ? (is_string($profile->dob) ? $profile->dob : $profile->dob->format('Y-m-d')) : '') }}',
                summary: `{!! addslashes(old('summary', $profile->summary ?? '')) !!}`,
                savedCompletionPercentage: {{ $profile ? $profile->completion_percentage : 0 }},
                hasUploadedDocs: {{ ($profile && ($profile->cv_path || $profile->ktp_path || $profile->ijazah_path || $profile->transcript_path || $profile->certificate_file_path || $profile->portfolio_file_path || $profile->cover_letter_path || $profile->skck_path || $profile->health_certificate_path || $profile->consent_letter_path)) ? 'true' : 'false' }},

                isStepCompleted(idx) {
                    switch(idx) {
                        case 0: return !!(this.nickname || this.phone || this.dob);
                        case 1: return !!(this.summary && this.summary.trim().length > 3);
                        case 2: return Array.isArray(this.educations) && this.educations.some(e => e.institution || e.level);
                        case 3: return Array.isArray(this.experiences) && this.experiences.some(e => e.company || e.position);
                        case 4: return Array.isArray(this.organizations) && this.organizations.some(o => o.name || o.position);
                        case 5: return (Array.isArray(this.skills) && this.skills.some(s => s.name)) || (Array.isArray(this.languages) && this.languages.some(l => l.name));
                        case 6: return Array.isArray(this.certificates) && this.certificates.some(c => c.name);
                        case 7: return (Array.isArray(this.portfolios) && this.portfolios.some(p => p.name)) || (Array.isArray(this.achievements) && this.achievements.some(a => a.name));
                        case 8: return Array.isArray(this.references) && this.references.some(r => r.name);
                        case 9: return !!((this.prefs && (this.prefs.position || this.prefs.expected_salary)) || (this.socials && (this.socials.linkedin || this.socials.github)));
                        case 10: return !!this.hasUploadedDocs;
                        default: return false;
                    }
                },

                get liveCompletionPercentage() {
                    let completed = 0;
                    for (let i = 0; i < 11; i++) {
                        if (this.isStepCompleted(i)) completed++;
                    }
                    const calculated = Math.round((completed / 11) * 100);
                    return Math.max(this.savedCompletionPercentage, calculated);
                },

                educations: {!! $getOldOrDb('educations', $defaultArray) !!},
                experiences: {!! $getOldOrDb('experiences', '[]') !!},
                organizations: {!! $getOldOrDb('organizations', '[]') !!},
                skills: {!! $getOldOrDb('skills', '[]') !!},
                languages: {!! $getOldOrDb('languages', '[]') !!},
                certificates: {!! $getOldOrDb('certificates', '[]') !!},
                portfolios: {!! $getOldOrDb('portfolios', '[]') !!},
                achievements: {!! $getOldOrDb('achievements', '[]') !!},
                references: {!! $getOldOrDb('references', '[]') !!},
                prefs: {!! $getOldOrDb('job_preferences', $defaultObject) !!},
                socials: {!! $getOldOrDb('social_links', $defaultObject) !!},
                
                addEdu() { this.educations.push({level:'', institution:'', major:'', degree:'', city:'', start_year:'', end_year:'', is_current:false, gpa:'', thesis_title:'', description:''}); },
                removeEdu(i) { this.educations.splice(i, 1); },
                
                addExp() { this.experiences.push({company:'', position:'', industry:'', type:'', location:'', last_salary:'', start_date:'', end_date:'', is_current:false, supervisor_name:'', supervisor_contact:'', reason_for_leaving:'', description:'', achievements:''}); },
                removeExp(i) { this.experiences.splice(i, 1); },
                
                addOrg() { this.organizations.push({name:'', position:'', level:'', location:'', start_date:'', end_date:'', is_current:false, period:'', description:''}); },
                removeOrg(i) { this.organizations.splice(i, 1); },
                
                addSkill() { this.skills.push({name:'', level:'Beginner'}); },
                removeSkill(i) { this.skills.splice(i, 1); },
                
                addLang() { this.languages.push({name:'', level:'Basic'}); },
                removeLang(i) { this.languages.splice(i, 1); },
                
                addCert() { this.certificates.push({name:'', issuer:'', type:'', number:'', url:'', score:'', issue_date:'', expiry_date:'', does_not_expire:false, description:''}); },
                removeCert(i) { this.certificates.splice(i, 1); },
                
                addPort() { this.portfolios.push({name:'', category:'', role:'', year:'', technologies:'', url:'', github_url:'', description:''}); },
                removePort(i) { this.portfolios.splice(i, 1); },
                
                addAchieve() { this.achievements.push({name:'', level:'', issuer:'', rank:'', year:'', url:'', description:''}); },
                removeAchieve(i) { this.achievements.splice(i, 1); },
                
                addRef() { this.references.push({name:'', position:'', company:'', relationship:'', email:'', phone:'', years_known:'', notes:''}); },
                removeRef(i) { this.references.splice(i, 1); }
            }
        }

        function previewCandidatePhoto(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();

                const img = document.getElementById('candidate-photo-preview');
                const placeholder = document.getElementById('candidate-photo-placeholder');
                const spinner = document.getElementById('candidate-photo-spinner');
                const badge = document.getElementById('photo-status-badge');

                // 1. Render Local Preview Instantly
                reader.onload = function(e) {
                    if (img) {
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(file);

                // 2. Instant Auto-Upload via AJAX
                if (spinner) spinner.classList.remove('hidden');
                if (badge) badge.classList.add('hidden');

                const formData = new FormData();
                formData.append('photo', file);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route('profile.candidate.upload-photo') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (spinner) spinner.classList.add('hidden');
                    if (data.success) {
                        if (img && data.url) img.src = data.url;
                        const popBtn = document.getElementById('btn-photo-preview-modal');
                        if (popBtn) popBtn.classList.remove('hidden');
                        if (badge) {
                            badge.textContent = '✅ Foto Tersimpan Otomatis!';
                            badge.classList.remove('hidden');
                        }
                    } else {
                        alert('Gagal mengunggah foto: ' + (data.message || 'Terjadi kesalahan'));
                    }
                })
                .catch(err => {
                    if (spinner) spinner.classList.add('hidden');
                    console.error(err);
                });
            }
        }

        function autoUploadDoc(event, docType) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const cardBox = input.closest('.border-dashed');
                
                // Show animated spinner overlay
                let spinner = cardBox.querySelector('.doc-spinner');
                if (!spinner) {
                    spinner = document.createElement('div');
                    spinner.className = 'doc-spinner absolute inset-0 bg-emerald-900/70 backdrop-blur-xs rounded-2xl flex flex-col items-center justify-center text-white z-30 font-bold text-xs gap-2 transition-all';
                    spinner.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-3xl"></i><span>Mengunggah & Menyimpan Dokumen...</span>';
                    cardBox.appendChild(spinner);
                }
                spinner.classList.remove('hidden');

                const formData = new FormData();
                formData.append('document', file);
                formData.append('document_type', docType);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route('profile.candidate.upload-document') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(async res => {
                    const data = await res.json().catch(() => null);
                    if (!res.ok) {
                        const errorMsg = (data && data.message) ? data.message : `Status error HTTP ${res.status}`;
                        throw new Error(errorMsg);
                    }
                    return data;
                })
                .then(data => {
                    spinner.classList.add('hidden');
                    if (data && data.success) {
                        // Update status box & link buttons inside dropzone
                        const statusBox = cardBox.querySelector('.doc-status-box');
                        const previewBtn = cardBox.querySelector('.doc-preview-btn');
                        const linkBtn = cardBox.querySelector('.doc-link-btn');
                        if (statusBox) statusBox.classList.remove('hidden');
                        if (previewBtn) {
                            previewBtn.setAttribute('data-url', data.url);
                            previewBtn.classList.remove('hidden');
                        }
                        if (linkBtn) {
                            linkBtn.href = data.url;
                            linkBtn.classList.remove('hidden');
                        }

                        // Show success badge inside card
                        let toast = cardBox.querySelector('.doc-toast');
                        if (!toast) {
                            toast = document.createElement('p');
                            toast.className = 'doc-toast mt-2 text-xs text-emerald-700 font-bold bg-white px-3 py-1.5 rounded-lg border border-emerald-200 shadow-2xs z-20 flex items-center gap-1.5';
                            cardBox.appendChild(toast);
                        }
                        toast.innerHTML = '<i class="fa-solid fa-circle-check text-emerald-600"></i> File ' + file.name + ' Berhasil Tersimpan Otomatis!';
                        toast.classList.remove('hidden');

                        // Dynamically add checkmark to tab button header
                        const tabBtn = document.querySelector(`button[data-doc-tab="${docType}"]`);
                        if (tabBtn && !tabBtn.querySelector('.fa-circle-check')) {
                            const checkIcon = document.createElement('i');
                            checkIcon.className = 'fa-solid fa-circle-check text-emerald-600 text-xs ml-1';
                            tabBtn.appendChild(checkIcon);
                        }
                    } else {
                        alert('Gagal mengunggah dokumen: ' + (data ? data.message : 'Terjadi kesalahan'));
                    }
                })
                .catch(err => {
                    spinner.classList.add('hidden');
                    console.error(err);
                    alert('Gagal mengunggah dokumen: ' + (err.message || 'Silakan periksa koneksi atau ukuran file Anda.'));
                });
            }
        }
    </script>
</x-app-layout>
