<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submit Karya untuk: {{ $challenge->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Tampilkan Error jika ada --}}
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Form Upload --}}
                    <form action="{{ route('challenge.submit.store', $challenge->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Judul Karya --}}
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Judul Karya')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus placeholder="Beri judul yang menarik..." />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi / Konsep')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Jelaskan konsep karya Anda...">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- AREA UPLOAD GAMBAR DENGAN PREVIEW --}}
                        <div class="mb-6">
                            <x-input-label for="image" :value="__('Upload File Gambar')" />

                            {{-- Container Preview --}}
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition" id="drop-area">
                                <div class="space-y-1 text-center">

                                    {{-- Preview Image (Hidden by default) --}}
                                    <img id="image-preview" src="#" alt="Preview Gambar" class="mx-auto max-h-64 rounded-lg shadow-md hidden mb-4 object-contain" />

                                    {{-- Icon Upload (Hidden when image selected) --}}
                                    <svg id="upload-icon" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload file</span>
                                            {{-- INPUT FILE --}}
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 10MB
                                    </p>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('challenges.show', $challenge->slug ?? $challenge->id) }}" class="text-gray-600 hover:text-gray-900 underline text-sm mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Kirim Submission') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Script JavaScript untuk Preview --}}
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            const imageField = document.getElementById("image-preview");
            const uploadIcon = document.getElementById("upload-icon");

            reader.onload = function(){
                if(reader.readyState == 2){
                    // Tampilkan gambar
                    imageField.src = reader.result;
                    imageField.classList.remove("hidden");

                    // Sembunyikan icon upload agar rapi
                    uploadIcon.classList.add("hidden");
                }
            }

            // Jika user memilih file, baca filenya
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</x-app-layout>
