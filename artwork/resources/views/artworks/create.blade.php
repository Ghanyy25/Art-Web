<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Artwork Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Menampilkan Error Validasi Global --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                            <div class="flex items-center text-red-600 mb-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-bold">Terjadi Kesalahan:</span>
                            </div>
                            <ul class="list-disc list-inside text-sm text-red-600 ml-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('artworks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        {{-- 1. Judul Karya --}}
                        <div>
                            <x-input-label for="title" :value="__('Judul Karya')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus placeholder="Berikan judul yang menarik" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        {{-- 2. Deskripsi --}}
                        <div>
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description" rows="4" maxlength="500" placeholder="Ceritakan tentang karya ini (maks. 500 karakter)..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- 3. Kategori --}}
                        <div>
                            <x-input-label for="category_id" :value="__('Kategori')" />
                            <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Pilih Kategori --</option>
                                @isset($categories)
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        {{-- 4. File Upload dengan PREVIEW --}}
                        <div>
                            <x-input-label for="artwork_file" :value="__('File Karya')" class="mb-2" />

                            {{-- Area Drag & Drop --}}
                            <div class="relative flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition cursor-pointer group" onclick="document.getElementById('artwork_file').click()">

                                <div class="space-y-1 text-center">
                                    {{-- Image Preview (Hidden by default) --}}
                                    <img id="preview-image" src="#" alt="Preview" class="mx-auto max-h-80 rounded-lg shadow-md hidden object-contain mb-4">

                                    {{-- Placeholder Icon & Text --}}
                                    <div id="upload-placeholder">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center mt-2">
                                            <span class="relative font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                Upload a file
                                            </span>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 10MB</p>
                                    </div>
                                </div>

                                {{-- Input File Tersembunyi --}}
                                <input id="artwork_file" name="artwork_file" type="file" class="sr-only" accept="image/*" onchange="previewFile(event)">
                            </div>

                            {{-- Nama File Terpilih (Optional) --}}
                            <p id="file-name" class="mt-2 text-sm text-gray-500 italic hidden"></p>

                            <x-input-error :messages="$errors->get('artwork_file')" class="mt-2" />
                        </div>

                        {{-- 5. Tags --}}
                        <div>
                            <x-input-label for="tags" :value="__('Tags (pisahkan dengan koma)')" />
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">#</span>
                                </div>
                                <x-text-input id="tags" name="tags" type="text" class="pl-7 block w-full" :value="old('tags')" placeholder="design, ui/ux, 3d" />
                            </div>
                            <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                            <x-primary-button class="w-full md:w-auto justify-center">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                {{ __('Upload Karya') }}
                            </x-primary-button>

                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 underline decoration-gray-300 underline-offset-4">
                                {{ __('Batal') }}
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Script JavaScript untuk Preview --}}
    <script>
        function previewFile(event) {
            const input = event.target;
            const file = input.files[0];
            const previewImage = document.getElementById('preview-image');
            const placeholder = document.getElementById('upload-placeholder');
            const fileNameText = document.getElementById('file-name');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Set src gambar
                    previewImage.src = e.target.result;

                    // Tampilkan gambar, sembunyikan placeholder
                    previewImage.classList.remove('hidden');
                    placeholder.classList.add('hidden');

                    // Tampilkan nama file
                    fileNameText.textContent = `File terpilih: ${file.name}`;
                    fileNameText.classList.remove('hidden');
                }

                reader.readAsDataURL(file);
            } else {
                // Reset jika user membatalkan pilihan
                previewImage.src = '#';
                previewImage.classList.add('hidden');
                placeholder.classList.remove('hidden');
                fileNameText.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
