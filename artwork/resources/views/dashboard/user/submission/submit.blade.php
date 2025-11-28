<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit Karya ke Challenge') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 mb-8 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-indigo-700">
                            Anda akan mengirimkan karya untuk: <span class="font-bold">{{ $challenge->title }}</span>.
                            <br>
                            Pastikan karya sesuai tema! Batas waktu: {{ $challenge->end_date->format('d M Y, H:i') }}.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if($userArtworks->isEmpty())
                        {{-- KONDISI 1: User belum punya karya sama sekali --}}
                        <div class="text-center py-10">
                            <div class="mb-4 text-gray-400">
                                <i class="fas fa-image text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Anda belum memiliki karya yang valid</h3>
                            <p class="text-gray-500 mt-2 max-w-md mx-auto">
                                Entah Anda belum mengupload karya, atau semua karya Anda sudah pernah disubmit ke challenge ini.
                            </p>
                            <a href="{{ route('artworks.create') }}" class="inline-flex items-center mt-6 px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-plus mr-2"></i> Upload Karya Baru
                            </a>
                        </div>
                    @else
                        {{-- KONDISI 2: Ada karya untuk dipilih --}}
                        <form action="{{ route('challenge.submit.store', $challenge->id) }}" method="POST">
                            @csrf

                            <h3 class="text-lg font-medium text-gray-900 mb-4">Pilih salah satu karya Anda:</h3>

                            {{-- Grid Pilihan Karya (Radio Visual) --}}
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8" x-data="{ selected: null }">
                                @foreach($userArtworks as $artwork)
                                    <label class="cursor-pointer relative group">
                                        <input type="radio" name="artwork_id" value="{{ $artwork->id }}" class="peer sr-only" x-model="selected">

                                        <div class="relative rounded-xl overflow-hidden border-2 transition-all duration-200"
                                             :class="selected == {{ $artwork->id }} ? 'border-indigo-600 ring-2 ring-indigo-200 ring-offset-2 scale-[1.02]' : 'border-gray-200 hover:border-gray-300'">

                                            {{-- Gambar --}}
                                            <div class="aspect-square bg-gray-100">
                                                <img src="{{ Storage::url($artwork->file_path) }}"
                                                     alt="{{ $artwork->title }}"
                                                     class="w-full h-full object-cover">
                                            </div>

                                            {{-- Overlay Checkmark (Muncul saat dipilih) --}}
                                            <div class="absolute inset-0 bg-indigo-600 bg-opacity-20 hidden peer-checked:flex items-center justify-center transition-opacity">
                                                <div class="bg-white rounded-full p-2 text-indigo-600 shadow-lg">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            </div>

                                            {{-- Judul Karya --}}
                                            <div class="p-3 bg-white border-t border-gray-100">
                                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $artwork->title }}</p>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <x-input-error :messages="$errors->get('artwork_id')" class="mb-4" />

                            <div class="flex items-center justify-between border-t pt-6 mt-6">
                                <a href="{{ route('challenges.show', $challenge->slug) }}" class="text-sm text-gray-600 hover:text-gray-900">
                                    Batal
                                </a>

                                <x-primary-button class="ml-3">
                                    {{ __('Kirim Submisi') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
