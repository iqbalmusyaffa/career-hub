<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(auth()->user()->hasRole('Candidate'))
            <div class="p-6 sm:p-8 bg-blue-50 shadow-sm sm:rounded-xl border border-blue-100">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-bold text-blue-900">
                            {{ __('Resume & CV Anda') }}
                        </h2>
                        <p class="mt-1 text-sm text-blue-700">
                            {{ __('Kelola data diri, pendidikan, pengalaman kerja, daftar keahlian, dan unggah CV Anda di halaman khusus Resume.') }}
                        </p>
                    </header>
                    <div class="mt-6">
                        <a href="{{ route('profile.candidate.details.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            Buka Halaman Resume
                        </a>
                    </div>
                </div>
            </div>
        @endif

            <div class="p-6 sm:p-8 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
