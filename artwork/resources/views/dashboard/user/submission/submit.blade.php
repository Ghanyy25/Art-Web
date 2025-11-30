<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submit Karya: {{ $challenge->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert Error --}}
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-red-700" role="alert">
                    <p class="font-bold">Gagal</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Validation Errors List --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 p-4 rounded-lg border border-red-200">
                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                 x-data="{
                    tab: '{{ old('submission_type', 'new') }}',
                    selectedArtwork: '{{ old('existing_artwork_id') }}'
                 }">

                <div class="p-6 md:p-8">

                    <form action="{{ route('challenge.submit.store', $challenge->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Input Hidden untuk Jenis Submission --}}
                        <input type="hidden" name="submission_type" x-model="tab">

                        {{-- NAVIGATION TABS --}}
                        <div class="flex border-b border-gray-200 mb-8">
                            <button type="button"
                                    @click="tab = 'new'"
                                    :class="tab === 'new' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Upload Karya Baru
                            </button>
                            <button type="button"
                                    @click="tab = 'existing'"
                                    :class="tab === 'existing' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                Pilih dari Galeri Saya
                            </button>
                        </div>

                        {{-- KONTEN TAB 1: UPLOAD BARU --}}
                        <div x-show="tab === 'new'" x-transition:enter="transition ease-out duration-300">

                            {{-- Judul Karya --}}
                            <div class="mb-5">
                                <x-input-label for="title" :value="__('Judul Karya')" />
                                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" placeholder="Beri judul yang menarik..." />
                            </div>

                            {{-- Deskripsi --}}
                            <div class="mb-5">
                                <x-input-label for="description" :value="__('Deskripsi / Konsep')" />
                                <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Jelaskan konsep karya Anda...">{{ old('description') }}</textarea>
                            </div>

                            {{-- Upload File (Dengan Preview) --}}
                            <div class="mb-6">
                                <x-input-label class="mb-2" :value="__('File Gambar')" />
                                <div class="flex items-center justify-center w-full">
                                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden group">

                                        {{-- Placeholder --}}
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                                            <svg class="w-8 h-8 mb-4 text-gray-500 group-hover:text-indigo-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                            <p class="text-xs text-gray-500">PNG, JPG or GIF (MAX. 10MB)</p>
                                        </div>

                                        {{-- Preview Image --}}
                                        <img id="image-preview" src="#" alt="Preview" class="absolute inset-0 w-full h-full object-contain hidden bg-white p-2">

                                        <input id="dropzone-file" name="image" type="file" class="hidden" accept="image/*" onchange="previewImage(event)" />
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- KONTEN TAB 2: PILIH DARI GALERI --}}
                        <div x-show="tab === 'existing'" x-transition:enter="transition ease-out duration-300" style="display: none;">

                            {{-- Input Hidden ID Artwork Terpilih --}}
                            <input type="hidden" name="existing_artwork_id" x-model="selectedArtwork">

                            @if($myArtworks->count() > 0)
                                <p class="text-sm text-gray-600 mb-4">Pilih satu karya dari portofolio Anda untuk diikutkan dalam challenge ini:</p>

                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-h-[500px] overflow-y-auto p-2">
                                    @foreach($myArtworks as $art)
                                        <div @click="selectedArtwork = '{{ $art->id }}'"
                                             class="cursor-pointer relative group rounded-lg overflow-hidden border-2 transition-all duration-200"
                                             :class="selectedArtwork == '{{ $art->id }}' ? 'border-indigo-600 ring-2 ring-indigo-200 shadow-lg scale-95' : 'border-gray-200 hover:border-indigo-300'">

                                            <div class="aspect-square bg-gray-100">
                                                <img src="{{ Storage::url($art->file_path) }}" class="w-full h-full object-cover">
                                            </div>

                                            <div class="p-2 bg-white">
                                                <p class="text-sm font-bold text-gray-800 truncate">{{ $art->title }}</p>
                                                <p class="text-xs text-gray-500">{{ $art->created_at->format('d M Y') }}</p>
                                            </div>

                                            {{-- Icon Checkmark (Muncul saat dipilih) --}}
                                            <div x-show="selectedArtwork == '{{ $art->id }}'" class="absolute top-2 right-2 bg-indigo-600 text-white rounded-full p-1 shadow-md">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                {{-- Empty State Galeri --}}
                                <div class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="mt-2 text-sm text-gray-500">Anda belum memiliki karya di galeri.</p>
                                    <button type="button" @click="tab = 'new'" class="mt-4 text-indigo-600 hover:underline text-sm font-medium">
                                        Upload karya baru saja
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- TOMBOL SUBMIT --}}
                        <div class="mt-8 flex items-center justify-end border-t pt-6">
                            <a href="{{ route('challenges.show', $challenge->id) }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>

                            <x-primary-button class="px-6 py-3 text-base">
                                {{ __('Kirim Submission') }}
                            </x-primary-button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Preview Gambar (Untuk Tab Upload Baru) --}}
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('opacity-0'); // Sembunyikan placeholder tapi tetap ada space
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
