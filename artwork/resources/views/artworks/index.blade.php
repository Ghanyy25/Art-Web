<x-app-layout>
    {{-- 1. Header Slot: Masuk ke sticky header di app.blade.php --}}
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Artworks') }}
            </h2>
            <a href="{{ route('artworks.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Upload New
            </a>
        </div>
    </x-slot>

    {{-- 2. Main Content Area --}}
    <div>
        {{-- Alert Sukses --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 flex justify-between items-center rounded-r shadow-sm">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- Konten Grid --}}
        @if($artworks->isEmpty())
            {{-- Empty State --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl p-10 text-center border border-gray-100">
                <div class="flex flex-col items-center justify-center py-6">
                    <div class="bg-gray-50 rounded-full p-4 mb-4">
                        <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada karya</h3>
                    <p class="mt-1 text-sm text-gray-500 max-w-sm">Galeri Anda masih kosong. Mulai bagikan kreativitas Anda kepada dunia dengan mengunggah karya pertama.</p>
                    <div class="mt-6">
                        <a href="{{ route('artworks.create') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm hover:underline">
                            + Upload Karya Pertama
                        </a>
                    </div>
                </div>
            </div>
        @else
            {{-- Grid Artworks --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach($artworks as $artwork)
                    <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-200 flex flex-col h-full">

                        {{-- Image Container dengan Rasio Tetap (Aspect Ratio) --}}
                        <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                            <img src="{{ Storage::url($artwork->file_path) }}"
                                 alt="{{ $artwork->title }}"
                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">

                            {{-- Overlay Action Buttons (Muncul saat Hover) --}}
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('artworks.edit', $artwork->id) }}"
                                   class="p-2 bg-white text-gray-700 rounded-full hover:text-yellow-600 hover:bg-yellow-50 transition shadow-lg transform hover:scale-110"
                                   title="Edit Karya">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                {{-- Tombol Hapus --}}
                                <div x-data="{ showDeleteModal: false }">
                                    <button type="button"
                                            @click="showDeleteModal = true"
                                            class="p-2 bg-white text-gray-700 rounded-full hover:text-red-600 hover:bg-red-50 transition shadow-lg transform hover:scale-110"
                                            title="Hapus Karya">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>

                                    <div x-show="showDeleteModal"
                                        style="display: none;"
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm p-4"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0">

                                        <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden transform transition-all"
                                            @click.away="showDeleteModal = false">

                                            <div class="p-6">
                                                <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Karya Ini?</h3>
                                                <p class="text-sm text-gray-600">
                                                    Apakah Anda yakin ingin menghapus karya ini secara permanen? Tindakan ini tidak dapat dibatalkan.
                                                </p>
                                            </div>

                                            <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-2">
                                                <form action="{{ route('artworks.destroy', $artwork->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex justify-center w-full px-4 py-2 bg-red-600 text-base font-medium text-white rounded-md shadow-sm hover:bg-red-700 focus:outline-none sm:w-auto sm:text-sm">
                                                        Ya, Hapus
                                                    </button>
                                                </form>

                                                <button type="button"
                                                        @click="showDeleteModal = false"
                                                        class="inline-flex justify-center w-full px-4 py-2 bg-white text-base font-medium text-gray-700 border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm">
                                                    Batal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Content --}}
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="mb-auto">
                                <h3 class="font-bold text-gray-800 text-lg truncate mb-1" title="{{ $artwork->title }}">
                                    {{ $artwork->title }}
                                </h3>
                                <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                                    {{ $artwork->description }}
                                </p>
                            </div>

                            {{-- Footer Card --}}
                            <div class="pt-4 mt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                                <span class="flex items-center" title="Tanggal Upload">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $artwork->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
